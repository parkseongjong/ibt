<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Api;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\Validation\Validation;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Datasource\ConnectionManager;
use Cake\Console\ShellDispatcher;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html

 	*********************

	- 작성자 : 이충현
	- 최초 작성일 : 2021-05-14
	- CTC Wallet 에서 거래소 내 고객 정보, 코인 가격, 코인 리스트, epay 정보, 입금 처리 위해 만들어짐.
	- 최근 수정일 : 2021-05-17 : api 수정 사항 수정
	- 최근 수정일 : 2021-09-18 By.오정택 : api 접근 IP 추가 3.37.251.249

	*********************
 */
class WalletController extends AppController
{
	
	public function beforeFilter(Event $event)
    {
		 $this->Auth->allow(['userinfo','price','coinList','epayinfo','deposit']);
	}
	/* https 구분 */
	public function isHttpsRequest() {	
		if ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {		
			return true; 
		}
		//$respArr = ['code'=>'805','error'=> true,'msg'=>'HTTPS로 요청해주세요'];
		//header('Content-Type: application/json');
		//echo json_encode($respArr,JSON_UNESCAPED_UNICODE); die;
	}
	/* ip 체크 (허용된 ip만 조회 가능) */
	public function confirm_ip(){
		$this_ip = $this->get_client_ip();
		$access_ip = ['112.171.120.140','175.126.82.225','3.37.251.249'];
		if(!in_array($this_ip, $access_ip)){
			$this->cause_error(803);
			die;
		}
		return 'success';
	}
	/* 인증키 체크 (허용된 인증키만 조회 가능) */
	public function confirm_auth_key($auth_key){
		$access_key = ['E7146GHKUP13'];
		if(!in_array($auth_key, $access_key)){
			$this->cause_error(801);
			die;
		}
		return 'success';
	}
	/* 필수값 체크 (빈값 없을 경우만 조회 가능) */
	public function require_check($data = array(), $type){
		if($type == 'userinfo'){
			if(empty($data['auth_key']) || empty($data['max_id'])){
				$this->cause_error(802); die;
			}
		} else if($type == 'price'){
			if(empty($data['auth_key']) || empty($data['seccond_coin']) || empty($data['first_coin'])){
				$this->cause_error(802); die;
			}
		} else if ($type == 'list'){
			if(empty($data['auth_key'])){
				$this->cause_error(802); die;
			}
		} else if ($type =='epayinfo'){
			if(empty($data['auth_key']) || empty($data['coin'])){
				$this->cause_error(802); die;
			}
		} else if($type =='deposit'){
			if(empty($data['auth_key']) || empty($data['user_id']) || empty($data['cryptocoin_id']) || empty($data['amount']) || empty($data['wallet_address']) || empty($data['epay_id']) || empty($data['target_id'])){
				$this->cause_error(802); die;
			}
		}
		return 'success';
	}
	/* 숫자만 입력 */
	public function number_check($data = array(), $type){
		if($type == 'userinfo'){
			if(!is_numeric($data['max_id'])){
				$this->cause_error(806); die;
			}
		} else if($type =='deposit'){
			if(!is_numeric($data['user_id']) || !is_numeric($data['cryptocoin_id']) || !is_numeric($data['amount']) || !is_numeric($data['epay_id']) || !is_numeric($data['target_id'])){
				$this->cause_error(806); die;
			}
		}
		return 'success';
	}
	/* 1. 회원 정보 */
	public function userinfo(){
		$this->confirm_ip(); // ip 체크
		if($this->request->is(['post', 'put'])) { // get,post 체크
			$request_data = $this->request->data();
			$this->require_check($request_data, 'userinfo'); // 필수값 체크
			$this->number_check($request_data, 'userinfo'); // 숫자만 들어갈 곳에 숫자만 들어갔는지
			$this->confirm_auth_key($request_data['auth_key']); // auth_key 체크
			$data = $this->get_user_info($request_data['max_id']); // 데이터 가져오기
			$respArr = ['code'=>'200','error'=> false,'msg'=>'Success','count'=>count($data),'data'=>$data];
			header('Content-Type: application/json');
			echo json_encode($respArr,JSON_UNESCAPED_UNICODE); die;
		}
		$this->cause_error(804); die;
	}

	/* 1-1. 실제 회원 정보 데이터 */
	public function get_user_info($max_id){
		$respArr = [];
		$this->loadModel('Users');
		$user_list = $this->Users->find()->select(['id','name','phone_number','eth_address'])->where(['user_type' => 'U','eth_address !='=>'','id >'=>$max_id])->hydrate(false)->toArray();
		if(!empty($user_list)){
			$respArr = $user_list;
		}
		return $respArr;
	}

	/* 2. 코인 가격 */
	public function price(){
		$this->confirm_ip(); // ip 체크
		if($this->request->is(['post', 'put'])) { // get, post 체크
			$request_data = $this->request->data();
			$this->require_check($request_data, 'price'); // 필수값 체크
			$this->confirm_auth_key($request_data['auth_key']); // auth key 체크
			$data = $this->get_coin_price($request_data['seccond_coin'],$request_data['first_coin']); // 데이터 가져오기
			$respArr = ['code'=>'200','error'=> false,'msg'=>'Success','data'=>$data];
			header('Content-Type: application/json');
			echo json_encode($respArr,JSON_UNESCAPED_UNICODE); die;
		}
		$this->cause_error(804); die;
	}
	/* 2-1. 코인 체크 - 가격 확인  */
	public function get_coin_price($seccond_coin, $first_coin){
		$respArr = [];
		$seccond_coin = strtoupper($seccond_coin);
		$first_coin = strtoupper($first_coin);
		$seccond_coin_data =  $this->get_coin($seccond_coin);
		$first_coin_data = $this->get_coin($first_coin);
		$return_value = 0;
		$current_price = $this->get_current_price($first_coin_data->id,$seccond_coin_data->id);
		if(!empty($current_price)){
			$return_value = number_format($current_price,2);
		}
		return $respArr = ['price'=>$return_value,'first_coin_status'=>$first_coin_data->status,'seccond_coin_status'=>$seccond_coin_data->status];  
	}
	/* 2-2. 코인 확인 및 정보 가져오기 */
	public function get_coin($coin_short_name){
		$this->loadModel('Cryptocoin');
		$coin = $this->Cryptocoin->find()->select(['id','name','short_name','status'])->where(['OR'=>[['name'=>$coin_short_name],['short_name'=>$coin_short_name]]])->first();
		if(empty($coin)){
			$this->cause_error(805); die;
		}
		return $coin;
	}
	/* 2-3. 실제 코인 가격 데이터 */
	public function get_current_price($firstCoinId,$secondCoinId){       
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Cryptocoin');
		$this->loadModel('Coinpair');

		$getCoinPairSingle = $this->Coinpair->find()->select(['binance_price','pair_price'])->where(['OR'=>[['coin_first_id'=>$secondCoinId,'coin_second_id'=>$firstCoinId,'binance_price'=>'Y'],
								['coin_second_id'=>$secondCoinId,'coin_first_id'=>$firstCoinId,'binance_price'=>'Y']]])->first();
		if(!empty($getCoinPairSingle['binance_price'])){
			return $getCoinPairSingle->pair_price;
		} else {												
			$current_price = $this->ExchangeHistory->find()->select(['get_per_price'])
				->where(['OR'=>[['get_cryptocoin_id'=>$secondCoinId,'spend_cryptocoin_id'=>$firstCoinId],['spend_cryptocoin_id'=>$secondCoinId,'get_cryptocoin_id'=>$firstCoinId]]])->order(['id'=>'DESC'])->first();
			if(empty($current_price)){
				$current_price = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId],'fields'=>['get_per_price'=>'usd_price']])->first();
			}
			return $current_price->get_per_price;
		}
    }
	/* 3. 코인 리스트 */
	public function coinList(){
		$this->confirm_ip(); // ip 체크
		if($this->request->is(['post', 'put'])) { // get, post 체크
			$request_data = $this->request->data();
			$this->require_check($request_data, 'list'); // 필수값 체크
			$this->confirm_auth_key($request_data['auth_key']); // auth key 체크
			$data = $this->get_coin_list(); // 데이터 가져오기
			$respArr = ['code'=>'200','error'=> false,'msg'=>'Success','data'=>$data];
			header('Content-Type: application/json');
			echo json_encode($respArr,JSON_UNESCAPED_UNICODE); die;
		}
		$this->cause_error(804); die;
	}
	/* 3-1. 실제 코인 리스트 데이터 */
	public function get_coin_list(){
		$this->loadModel('Cryptocoin');
		$respArr = [];
		$coin_list = $this->Cryptocoin->find()->select(['id','short_name','status'])->hydrate(false)->toArray();
		if(!empty($coin_list)){
			$respArr = $coin_list;
		}
		return $respArr;
	}
	/* 4. E-pay 정보 */
	public function epayinfo(){
		$this->confirm_ip(); // ip 체크
		if($this->request->is(['post', 'put'])) { // get, post 체크
			$request_data = $this->request->data(); 
			$this->require_check($request_data, 'epayinfo'); // 필수값 체크
			$this->confirm_auth_key($request_data['auth_key']); // auth key 체크
			$epay = $this->get_epay_coin($request_data['coin']); // 데이터 가져오기
			$respArr = ['code'=>'200','error'=> false,'msg'=>'Success','epay_id'=>$epay['id'],'status'=>$epay['status']];
			header('Content-Type: application/json');
			echo json_encode($respArr,JSON_UNESCAPED_UNICODE); die;
		}
		$this->cause_error(804); die;
	}
	/* 4-1. 실제 E-pay 데이터 */
	public function get_epay_coin($coin_short_name){
		$coin_name = $this->check_epay_coin(strtoupper($coin_short_name));
		$this->loadModel('Epay');
		$coin = $this->Epay->find()->select(['id','status'])->where(['OR'=>[['name'=>$coin_name],['short_name'=>$coin_name]]])->first();
		if(empty($coin)){
			$this->cause_error(805); die;
		}
		return $coin;
	}
	/* 4-2. coin에 E- 안 붙어있을 경우 붙여주기 */
	public function check_epay_coin($coin_name){
		if(strpos($coin_name,'-') === false){
			$coin_name = 'E-'.$coin_name;
		}
		return $coin_name;
	}
	/* 5. 입금 */
	public function deposit(){
		$this->confirm_ip(); // ip 체크
		if($this->request->is(['post', 'put'])) { // get, post 체크
			$request_data = $this->request->data();
			$this->require_check($request_data, 'deposit'); // 필수값 체크
			$this->confirm_auth_key($request_data['auth_key']); // auth key 체크
			$this->number_check($request_data, 'deposit'); // 
			$this->epay_id_check($request_data['epay_id']);
			$this->coin_id_check($request_data['cryptocoin_id']);
			$status = $this->insert_data($request_data); // data insert 시키기
			if($status['status'] == 'Success'){
				$respArr = ['code'=>'200','error'=> false,'msg'=>$status['status']];
			} else {
				$respArr = ['code'=>'201','error'=> true,'msg'=>'알 수 없는 DB 오류','error_data'=>$status];
			}
			header('Content-Type: application/json');
			echo json_encode($respArr,JSON_UNESCAPED_UNICODE); die;
		}
		$this->cause_error(804); die;
	}
	/* 5-1. 실제 입금 insert */
	public function insert_data($data = array()){
		$this->loadModel('PrincipalWallet');
		$this->loadModel('EpayLogs');
		$respArr = [];

		if(empty($data['type'])){ $data['type'] = 'purchase'; }
		if(empty($data['remark'])){ $data['remark'] = 'airdrop reward'; }
		if(empty($data['status'])){ $data['status'] = 'completed'; }
		if(empty($data['target'])){ $data['target'] = 'CTC Wallet'; }

		$epay_log_arr = [];
		$epay_log_arr['user_id'] = $data['user_id'];
		$epay_log_arr['epay_id'] = $data['epay_id'];
		$epay_log_arr['amount'] = $data['amount'];
		$epay_log_arr['target'] = $data['target'];
		$epay_log_arr['target_id'] = $data['target_id'];
		$epay_log_arr['created'] = date('Y-m-d H:i:s');
		$epay_log_arr['modified'] = date('Y-m-d H:i:s');

		$principal_arr = [];
		$principal_arr['user_id'] = $data['user_id'];
		$principal_arr['cryptocoin_id'] = $data['cryptocoin_id'];
		$principal_arr['amount'] = $data['amount'];
		$principal_arr['wallet_address'] = $data['wallet_address'];
		$principal_arr['type'] = $data['type'];
		$principal_arr['remark'] = $data['remark'];
		$principal_arr['status'] = $data['status'];
		$principal_arr['created_at'] = date('Y-m-d H:i:s');

		$principal_wallet = $this->PrincipalWallet->newEntity();
		$principal_wallet = $this->PrincipalWallet->patchEntity($principal_wallet, $principal_arr);
		$wallet = $this->PrincipalWallet->save($principal_wallet);

		if($wallet){
			$epay_log_arr['principal_wallet_id'] = $wallet->id;

			$epay_log = $this->EpayLogs->newEntity();
			$epay_log = $this->EpayLogs->patchEntity($epay_log, $epay_log_arr);
			$log = $this->EpayLogs->save($epay_log);
			if($log){
				$respArr = ['status'=>'Success','msg'=>'','error_msg'=>''];
			} else {
				$respArr = ['status'=>'fail', 'msg'=>'지갑 저장 완료하였으나 epay-log 저장 오류','error_msg'=>$epay_log->errors()];
			}
		}else{
			$respArr = ['status'=>'fail', 'msg'=>'지갑 저장 실패','error_msg'=>$principal_wallet->errors()];
		}
		return $respArr;
	}
	/* e-pay id 존재 여부 확인 */
	public function epay_id_check($id){
		$this->loadModel('Epay');
		$coin = $this->Epay->find()->select(['id','status'])->where(['id'=>$id])->first();
		if(empty($coin)){
			$this->cause_error(805); die;
		}
		return $coin;
	}
	/* coin id 존재 여부 확인 */
	public function coin_id_check($id){
		$this->loadModel('Cryptocoin');
		$coin = $this->Cryptocoin->find()->select(['id','name','short_name'])->where(['id'=>$id])->first();
		if(empty($coin)){
			$this->cause_error(805); die;
		}
		return $coin;
	}
	/* 에러 코드 */
	public function cause_error($code){
		$error_arr = [];
		switch($code){
			case 801 :
				$error_arr = ['code'=>'801','error'=> true,'msg'=>'인증키 불일치'];
				break;
			case 802 :
				$error_arr = ['code'=>'802','error'=> true,'msg'=>'필수값이 누락'];
				break;
			case 803 :
				$error_arr = ['code'=>'803','error'=> true,'msg'=>'접근이 불가한 IP입니다'];
				break;
			case 804 :
				$error_arr = ['code'=>'804','error'=> true,'msg'=>'요청 형식이 잘못되었습니다'];
				break;
			case 805 :
				$error_arr = ['code'=>'805','error'=> true,'msg'=>'지원하지 않는 코인입니다'];
				break;
			case 806 :
				$error_arr = ['code'=>'806','error'=> true,'msg'=>'입력값의 데이터 형식이 잘못되었습니다'];
				break;
			default:
				$error_arr = ['code'=>'800','error'=> true,'msg'=>'알 수 없는 오류가 발생했습니다'];
				break;
		}
		header('Content-Type: application/json');
		echo json_encode($error_arr,JSON_UNESCAPED_UNICODE); die;
	}
}
?>
