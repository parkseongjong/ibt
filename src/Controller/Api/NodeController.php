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
	- 최초 작성일 : 2021-08-31
	- Node 서버에서 앰브리시가 만들어 놓은 DB select 및 insert 쿼리를 api로 대체하기 위해 만들어졌습니다.
	- 1. getAddressList - SELECT TRIM(LOWER(eth_address)) as singleAddr FROM users where eth_address!=''
	- 2. findUser - "SELECT id,eth_address FROM users where TRIM(LOWER(eth_address))='" + receiverAddressLower +  "'";
	- 3. saveWallet - INSERT INTO principal_wallet SET user_id = '" +userId +"',cryptocoin_id = 18,amount = '" +amt +"',wallet_address = '" +ethAddress +"',tx_id = '" +txId +"',type = 'purchase',status = 'completed'";
	- 최근 수정일 : 2021-08-31
	*********************
 */
class NodeController extends AppController
{
	public function beforeFilter(Event $event)
    {
		 $this->Auth->allow(['getAddressList','findUser','saveWallet']);
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
		$access_ip = ['112.171.120.140','15.164.225.146','125.141.133.23','110.10.189.191','54.180.5.130'];
		if(!in_array($this_ip, $access_ip)){
			$this->cause_error(803);
			die;
		}
		return 'success';
	}
	/* 인증키 체크 (허용된 인증키만 조회 가능) */
	public function confirm_auth_key($auth_key){
		$access_key = ['E7146GHKUP14'];
		if(!in_array($auth_key, $access_key)){
			$this->cause_error(801);
			die;
		}
		return 'success';
	}
	/* 필수값 체크 (빈값 없을 경우만 조회 가능) */
	public function require_check($data = array(), $type){
		if($type == 'getAddressList'){
			if(empty($data['auth_key'])){
				$this->cause_error(802); die;
			}
		} else if($type == 'findUser'){
			if(empty($data['auth_key']) || empty($data['receiverAddressLower'])){
				$this->cause_error(802); die;
			}
		} else if ($type == 'saveWallet'){
			if(empty($data['auth_key']) || empty($data['userId']) || empty($data['amt']) || empty($data['ethAddress']) || empty($data['txId'])){
				$this->cause_error(802); die;
			}
		}
		return 'success';
	}
	/* 숫자만 입력 */
	public function number_check($data = array(), $type){
		if($type == 'saveWallet'){
			if(!is_numeric($data['amt'])){
				$this->cause_error(806); die;
			}
		} 
		return 'success';
	}
	/* 1. eth 전체 주소 가져오기 */
	public function getAddressList(){ 
		$this->confirm_ip(); // ip 체크
		if($this->request->is(['post', 'put'])) { // get,post 체크
			$request_data = $this->request->data();
			$this->require_check($request_data, 'getAddressList'); // 필수값 체크
			$this->confirm_auth_key($request_data['auth_key']); // auth_key 체크
			$data = $this->getList(); // 데이터 가져오기
			header('Content-Type: application/json');
			echo json_encode($data ,JSON_UNESCAPED_UNICODE); die;
		}
		$this->cause_error(804); die;
		
	}

	/* 1-1. 실제 정보 데이터 */
	private function getList(){
		$respArr = [];
		$this->loadModel('Users');
		$user_list = $this->Users->find()->select(['singleAddr'=>'TRIM(LOWER(eth_address))'])->where(['eth_address !='=>''])->hydrate(false)->toArray();
		if(!empty($user_list)){
			$respArr = $user_list;
		}
		return $respArr;
	}

	/* 2. 유저 정보 */
	public function findUser(){ //0x41e00223484753d9f0f3eff981b39c5c63d47241
		$this->confirm_ip(); // ip 체크
		if($this->request->is(['post', 'put'])) { // get, post 체크
			$request_data = $this->request->data();
			$this->require_check($request_data, 'findUser'); // 필수값 체크
			$this->confirm_auth_key($request_data['auth_key']); // auth key 체크
			$data = $this->getUser($request_data['receiverAddressLower']); // 데이터 가져오기
			header('Content-Type: application/json');
			echo json_encode($data,JSON_UNESCAPED_UNICODE); die;
		}
		$this->cause_error(804); die;
	}
	/* 2-1. 실제 유저 정보 */
	private function getUser($address){
		$respArr = [];
		$this->loadModel('Users');
		$user_list = $this->Users->find()->select(['id','eth_address'])->where(['TRIM(LOWER(eth_address))'=>$address])->hydrate(false)->first();
		if(!empty($user_list)){
			$respArr = $user_list;
		}
		return $respArr;
	}
	/* 3. 입금 처리 */
	public function saveWallet(){
		$this->confirm_ip(); // ip 체크
		if($this->request->is(['post', 'put'])) { // get, post 체크
			$request_data = $this->request->data();
			$this->require_check($request_data, 'saveWallet'); // 필수값 체크
			$this->number_check($request_data, 'saveWallet'); // 숫자 체크
			$this->confirm_auth_key($request_data['auth_key']); // auth key 체크
			$data = $this->insert_data($request_data); // insert
			header('Content-Type: application/json');
			echo json_encode($data,JSON_UNESCAPED_UNICODE); die;
		}
		$this->cause_error(804); die;
	}
	/* 3-1. 실제 입금 insert */
	private function insert_data($data = array()){
		$this->loadModel('PrincipalWallet');
		$respArr = [];

		$principal_arr = [];
		$principal_arr['user_id'] = $data['userId'];
		$principal_arr['cryptocoin_id'] = 18;
		$principal_arr['amount'] = $data['amt'];
		$principal_arr['wallet_address'] = $data['ethAddress'];
		$principal_arr['tx_id'] = $data['txId'];
		$principal_arr['type'] = 'purchase';
		$principal_arr['status'] = 'completed';
		$principal_arr['created_at'] = date('Y-m-d H:i:s');

		$principal_wallet = $this->PrincipalWallet->newEntity();
		$principal_wallet = $this->PrincipalWallet->patchEntity($principal_wallet, $principal_arr);
		$wallet = $this->PrincipalWallet->save($principal_wallet);

		if($wallet){
			$respArr = ['success'=>true,'msg'=>'','error_msg'=>''];
		} else {
			$respArr = ['success'=>false, 'msg'=>'지갑 저장 실패','error_msg'=>$principal_wallet->errors()];
		}
		return $respArr;
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
