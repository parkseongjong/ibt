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

namespace App\Controller\Tech;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Event\Event;
use Cake\View\Helper\SessionHelper;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\Log\Log;
use Cake\Network\Exception\NotFoundException;
use Cake\Mailer\Email;

class DormantUsersController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
	/* 휴면 계정 리스트 */
	public function dormantList(){
		$this->loadModel('DormantUsers');
		$this->loadModel('Users');
		$settings = array('limit' => 20);
		$query = $this->DormantUsers->find()->select(['id','email','name','phone_number','last_login','created','annual_membership','dormant_date']);

		if($this->request->query('search_value')){ // 검색어
			$query = $query->where(['name' => $this->request->query('search_value')]);
		}

		if ($this->request->query('start_date')) { 
			$query = $query->where(['DATE(DormantUsers.created) >= ' => $this->request->query('start_date')]);
		}

		if ($this->request->query('end_date')) { 
			$query = $query->where(['DATE(DormantUsers.created) <= ' => $this->request->query('end_date')]);
		}

		if($this->request->query('sort_value')){ // 최신순 오래된 순
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			if(empty($order_value)){
				$order_value = 'DormantUsers.id';
			}
			$query = $query->order([$order_value=>$sort_value]);
		} else {
			$query = $query->order(['DormantUsers.id'=> 'DESC']);
		}

		if($this->request->query('pagination')){ // 페이지당 리스트 갯수
			$settings = array('limit' => $this->request->query('pagination'));
		}
		try {
			$dormant_list = $this->Paginator->paginate($query);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$dormant_list =  $this->Paginator->paginate($query,$settings);
		}
		
		$this->set('dormant_list',$dormant_list);
		
	}

	/* 사용자 - 휴면 계정 구분 */
	public function dormant(){
		// 1. 하루에 한 번 오늘 날짜부터 라스트 로그인이 11개월이거나 넘었다면 안내 메일 테이블로 insert 시킨다.
		$insert_email_result = $this->insert_dormant_email();
		echo $insert_email_result;
		// 2. 안내 메일 테이블에서 매일 아침 9시에 메일 전송한다. 이때 전송 N => Y 로 업데이트 시켜준다.
		$send_email_result = $this->dormant_send_email();
		echo $send_email_result;
		// 2.5. 안내 메일 받고 로그인 하였다면, 안내 메일 테이블에서 해당 유저의 정보는 삭제해준다. ==> /front2/UsersController login() 에서 처리
		
		// 3. 휴면 계정은 오늘 날짜부터 라스트 로그인이 12개월이거나 넘었고, 안내 메일이 발송 (Y) 되었다면, 해당 유저는 users 테이블에서 null =>dormant_users 테이블로 insert
		$insert_dormant_result = $this->insert_dormant();
		echo $insert_dormant_result;
		exit;
		// 3.5 휴면 계정이 완료되었다면 안내 메일 테이블에서 해당 유저의 정보는 삭제해준다.
		// 4. 휴면 계정 사용자가 로그인 후 본인인증 및 해제 처리 시 복구, dormant_users 에서 해당 유저 정보 삭제 ==> /front2/UsersController 에서 처리해야함
	}

	// 1. 오늘 날짜부터 라스트 로그인이 11개월이거나 넘었다면 안내 메일 테이블로 insert 시킨다.
	private function insert_dormant_email(){
		$this->loadModel('Users');
		$this->loadModel('DormantEmail');

		$query = $this->Users->find()->select(['id']);
		$query = $query->join(['d' => ['table' => 'dormant_users','type' => 'LEFT OUTER','conditions' => 'd.user_id = Users.id']]); // 휴면 계정 고객과
		$query = $query->join(['e' => ['table' => 'dormant_email','type' => 'LEFT OUTER','conditions' => 'e.user_id = Users.id']]); // 이미 email 테이블에 들어가 있는 고객은 제외
		$email_list = $query->where(['TIMESTAMPDIFF(MONTH,Users.last_login,NOW()) >= '=>11,'d.user_id is null', 'e.user_id is null'])->all();

		if(empty($email_list) || count($email_list) < 1) {
			$returnArr = ['status'=>'fail','message'=>'메일 발송 예정 대상자 없음'];
			return json_encode($returnArr,JSON_UNESCAPED_UNICODE);
		}
		if(!empty($email_list)){
			foreach($email_list as $l){
				$insert_query = $this->DormantEmail->query();
				$insert_query->insert(['user_id','send_email','created'])->values(['user_id'=>$l->id,'send_email'=>'N','created'=>date('Y-m-d H:i:s')])->execute();
			}
		}
		$returnArr = ['status'=>'success','message'=> count($email_list). ' 건의 메일 발송 예정 대상자 확인'];
		return json_encode($returnArr,JSON_UNESCAPED_UNICODE);
	}
	// 2. 안내 메일 테이블에서 매일 아침 9시에 메일 전송한다. 이때 전송 완료 시 N => Y 로 업데이트 시켜준다.
	private function dormant_send_email(){
		$this->loadModel('DormantEmail');
		$query = $this->DormantEmail->find()->select(['id'=>'DormantEmail.id','email'=>'u.email','username'=>'u.username','dortmant_date'=>'DATE_ADD(u.last_login, INTERVAL 1 YEAR)']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'user_id = u.id']]);  
		$email_send_list = $query->where(['send_email'=>'N','TIMESTAMPDIFF(MONTH,u.last_login,NOW()) >= '=>11])->all();

		if(empty($email_send_list) || count($email_send_list) < 1) {
			$returnArr = ['status'=>'fail','message'=>'메일 발송 대상자 없음'];
			return json_encode($returnArr,JSON_UNESCAPED_UNICODE);
		}
		$send_count = 0;
		if(!empty($email_send_list)){
			foreach($email_send_list as $l){
				$mail_status = $this->send_email($l->email,$this->masking('P',$l->username),$l->dortmant_date);
				if($mail_status == 'success'){
					$send_count++;
					$update_query = $this->DormantEmail->query();
					$update_query->update()->set(['send_email'=>'Y','send_date'=>date('Y-m-d H:i:s')])->where(['id'=>$l->id])->execute();
				}
			}
		}
		$returnArr = ['status'=>'success','message'=>$send_count.' 건의 메일 발송'];
		return json_encode($returnArr,JSON_UNESCAPED_UNICODE);
	}
	// 3. 휴면 계정은 오늘 날짜부터 라스트 로그인이 12개월이거나 넘었고, 안내 메일이 발송 (Y) 되었다면, 해당 유저는 users 테이블에서 null =>dormant_users 테이블로 insert
	private function insert_dormant(){
		$this->loadModel('Users');
		$this->loadModel('DormantUsers');
		$query = $this->Users->find('all');
		$query = $query->join(['d' => ['table' => 'dormant_users','type' => 'LEFT OUTER','conditions' => 'd.user_id = Users.id']]); // 휴면 계정 고객과
		$query = $query->join(['e' => ['table' => 'dormant_email','type' => 'LEFT OUTER','conditions' => 'e.user_id = Users.id']]); // 이미 email 테이블에 들어가 있는 고객은 제외
		$list = $query->where(['TIMESTAMPDIFF(YEAR,Users.last_login,NOW()) >= '=>1,'send_email'=>'Y','d.user_id is null', 'e.user_id is not null'])->hydrate(false)->all();
		
		if(empty($list) || count($list) < 1){
			$returnArr = ['status'=>'fail','message'=>'휴면 계정 예정 사용자 없음'];
			return json_encode($returnArr,JSON_UNESCAPED_UNICODE);
		}
		$columns = $this->empty_array();
		
		foreach ($list->toArray() as $k=>$data){
			$data['dormant_date'] = date('Y-m-d H:i:s');
			$data['user_id'] = $data['id'];
			$data['dormant'] = 'Y';
			$data['auth_req_list'] = $this->get_auth_change($data['id']);
			$data['wallet_address_list'] = $this->get_wallet_address($data['id']);
			$data['board_qna_list'] = $this->get_board_qna($data['id']);

			$newObj = $this->DormantUsers->newEntity();
			$newObj = $this->DormantUsers->patchEntity($newObj, $data);
			$saveThisData = $this->DormantUsers->save($newObj);

			if($saveThisData){
				$update_query = $this->Users->query();
				$update_query->update()->set($columns)->where(['id'=>$data['id']])->execute();
				$this->deleteAllOrders($data['id']); // 예약 주문 취소
				if(!empty($data['auth_req_list'])){
					$this->update_auth_change($data['id']); // 인증변경요청
				}
				if(!empty($data['wallet_address_list'])){
					$this->update_wallet_address($data['id']); //출금주소
				}
				if(!empty($data['board_qna_list'])){
					$this->update_board_qna($data['id']); // 1대1문의
				}
				$this->delete_dormant_email($data['id']);
			}
		}
		$returnArr = ['status'=>'success','message'=> count($list).' 건 휴면 계정으로 전환'];
		return json_encode($returnArr,JSON_UNESCAPED_UNICODE);
	}
	// 3.5 휴면 계정이 완료되었다면 안내 메일 테이블에서 해당 유저의 정보는 삭제해준다.
	private function delete_dormant_email($user_id){
		$this->loadModel('DormantEmail');
		$query = $this->DormantEmail->query();
		$query->delete()->where(['user_id'=>$user_id,'send_email'=>'Y'])->execute();
	}
	/* 인증 변경 요청 테이블 해당 유저 정보 가져오기 */
	private function get_auth_change($user_id){
		$this->loadModel('ChangeAuth');
		$resp_arr = [];
		$list = $this->ChangeAuth->find('all')->where(['user_id'=>$user_id])->hydrate(false)->all();
		if(!empty($list)){
			foreach($list->toArray() as $k=>$data){
				$resp_arr[$k] = $data;
			}
		}
		return json_encode($resp_arr,JSON_UNESCAPED_UNICODE);
	}
	/* 출금 지갑 해당 유저 정보 가져오기 */
	private function get_wallet_address($user_id){
		$this->loadModel('WithdrawalWalletAddress');
		$resp_arr = [];
		$list = $this->WithdrawalWalletAddress->find()->select(['id','wallet_name'])->where(['user_id'=>$user_id])->hydrate(false)->all();
		if(!empty($list)){
			foreach($list->toArray() as $k=>$data){
				$resp_arr[$k] = $data;
			}
		}
		return json_encode($resp_arr,JSON_UNESCAPED_UNICODE);
	}
	/* 1대1문의 해당 유저 정보 가져오기 */
	private function get_board_qna($user_id){
		$this->loadModel('BoardQna');
		$resp_arr = [];
		$list = $this->BoardQna->find('all')->where(['users_id'=>$user_id])->hydrate(false)->all();
		if(!empty($list)){
			foreach($list->toArray() as $k=>$data){
				$resp_arr[$k] = $data;
			}
		}
		return json_encode($resp_arr,JSON_UNESCAPED_UNICODE);
	}
	/* 1대1문의 해당 유저 null 처리 */
	private function update_board_qna($user_id){
		$this->loadModel('BoardQna');
		$resp_arr = [];
		$columns = $this->BoardQna->schema()->columns();
		foreach($columns as $k=>$data){
			if($data != 'id'){ // id 제외
				$resp_arr[$data] = null; // 그 외 초기화
			}
		}
		$query = $this->BoardQna->query();
		$query->update()->set($resp_arr)->where(['users_id'=>$user_id])->execute();
		return;
	}
	/* ChangeAuth 해당 유저 null 처리 */
	private function update_auth_change($user_id){
		$this->loadModel('ChangeAuth');
		$resp_arr = [];
		$columns = $this->ChangeAuth->schema()->columns();
		foreach($columns as $k=>$data){
			if($data != 'id'){ // id 제외
				$resp_arr[$data] = null; // 그 외 초기화
			}
		}
		$query = $this->ChangeAuth->query();
		$query->update()->set($resp_arr)->where(['user_id'=>$user_id])->execute();
		return;
	}
	/* 지갑 목록 해당 유저 null 처리 */
	private function update_wallet_address($user_id){
		$this->loadModel('WithdrawalWalletAddress');
		$query = $this->WithdrawalWalletAddress->query();
		$query->update()->set(['wallet_name'=>null])->where(['user_id'=>$user_id])->execute();
		return;
	}

	/* id 제외 모든 칼럼 null 처리 */
	private function empty_array(){
		$this->loadModel('Users');
		$resp_arr = [];
		$columns = $this->Users->schema()->columns();
		foreach($columns as $k=>$data){
			if($data != 'id'){ // id 제외
				if($data == 'dormant'){ // 휴면계정 표시
					$resp_arr[$data] = 'Y';
				} else {
					$resp_arr[$data] = null; // 그 외 초기화
				}
			}
		}
		return $resp_arr;
	}
	/* 휴면 계정 전화 메일 전송 */
	private function send_email($user_email,$user_name,$dortmant_date){
		$email = new Email('default');
		$email->viewVars(['user_name'=>$user_name,'dortmant_date'=>$dortmant_date]);
		$email->from(['cs@onefamilymall.com'=>'Coin IBT'])
			->to($user_email)
			->subject('[COIN IBT] 회원님의 아이디가 휴면상태로 전환될 예정입니다')
			->emailFormat('html')
			->template('dormant');
		if($email->send()){
			return 'success';
		} 
		return 'fail';
	}
	private function undormantData(){
		$user_id = 2803;
		$this->loadModel('DormantUsers');
		$this->loadModel('ChangeAuth');
		$this->loadModel('Users');
		$this->loadModel('WithdrawalWalletAddress');
		$this->loadModel('BoardQna');
		$dormant_user = $this->DormantUsers->find('all')->where(['user_id'=>$user_id])->hydrate(false)->first();
		$columns = $this->Users->schema()->columns();

		if(!empty($dormant_user)){
			$data_arr = [];
			$change_auth_data = [];
			$wallet_address_data = [];
			$board_qna_data = [];
			foreach($dormant_user as $k=>$data){ // 칼럼을 key, 데이터를 value로 배열 만들기
				 if($k == 'auth_req_list'){ // ChangeAuth 에 업데이트할 배열과
					$change_auth_data = json_decode($data,true);
				} else if($k == 'wallet_address_list'){ // 지갑 주소에 업데이트 할 배열 
					$wallet_address_data = json_decode($data,true);
				} else if($k == 'board_qna_list'){ // 1대1문의 게시판에 업데이트할 배열
					$board_qna_data = json_decode($data,true);
				}   
				foreach($columns as $key=>$value){
					if($k == $value){
						if($k == 'last_login') {
							$data_arr[$k] = date('Y-m-d H:i:s');
						} else {
							$data_arr[$k] = $data;
						}
						continue;
					}
				}
			}
			$data_arr['dormant'] = 'N';// 휴면계정 표시

			if(!empty($data_arr)){
				$update_query = $this->Users->query();
				$update_query->update()->set($data_arr)->where(['id'=>$user_id])->execute();

				$delete_query = $this->DormantUsers->query();
				$delete_query->delete()->where(['user_id'=>$user_id])->execute();

				//$this->check_dormant_email($user_id);
				if(!empty($wallet_address_data)){ // 왈렛 주소 null=> 값으로 업데이트
					foreach($wallet_address_data as $k=>$data){
						$id = 0;
						foreach($data as $key=>$value){
							if($key == 'id'){
								$id = $value;
							} else {
								$wallet_update_query = $this->WithdrawalWalletAddress->query();
								$wallet_update_query->update()->set([$key=>$value])->where(['id'=>$id])->execute();
							}
						}
					}
				}
				if(!empty($change_auth_data)){
					foreach($change_auth_data as $k=>$data){ // 인증변경요청 null 처리된 부분 다시 원복
						$id = 0;
						foreach($data as $key=>$value){
							if($key == 'id'){
								$id = $value;
							} else {
								$auth_update_query = $this->ChangeAuth->query();
								$auth_update_query->update()->set([$key=>$value])->where(['id'=>$id])->execute();
							}
						}
					}
				}
				if(!empty($board_qna_data)){
					foreach($board_qna_data as $k=>$data){ // 인증변경요청 null 처리된 부분 다시 원복
						$id = 0;
						foreach($data as $key=>$value){
							if($key == 'id'){
								$id = $value;
							} else {
								$board_update_query = $this->BoardQna->query();
								$board_update_query->update()->set([$key=>$value])->where(['id'=>$id])->execute();
							}
						}
					}
				}
				echo 'test success';
				die;
			}
		}
		echo 'fail';
		die;
	}
	

	/* 전체 예약된 주문 취소 - 하쌈 작성 */
	private function deleteAllOrders($userId){
        $cudate = date('Y-m-d H:i:s');
        $this->loadModel('Transactions');
        $this->loadModel('Users');
        $this->loadModel('BuyExchange');
        $this->loadModel('SellExchange');
        $adminFeePercent = $this->Users->getAdninFee("buy_sell_fee");
        $queryBuy = $this->BuyExchange->find('all',['conditions'=>['buyer_user_id'=>$userId,'status'=>'pending']])->toArray();
        $querySell = $this->SellExchange->find('all',['conditions'=>['seller_user_id'=>$userId,'status'=>'pending']])->toArray();
        if(!empty($queryBuy)){
            foreach ($queryBuy as $queryItemBuy) {
                $buyUpdate = $this->BuyExchange->get($queryItemBuy['id']);
                $buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['status'=>'deleted']);
                $this->BuyExchange->save($buyUpdate);
                $getAmount = $queryItemBuy['buy_get_amount'];
                $perPrice = $queryItemBuy['per_price'];
                $spendAmount = $getAmount*$perPrice;
                $userId = $queryItemBuy['buyer_user_id'];
                $cryptocoinId = $queryItemBuy['buy_spend_coin_id'];

                $transactionsUpdate = $this->Transactions->find('all',["conditions"=>[
                    "tx_type"=>'buy_exchange',
                    "remark"=>"reserve for exchange"]])
                    ->first();
                if(!empty($transactionsUpdate)){
                    $getTxId = $transactionsUpdate['id'];
                    $this->Transactions->delete($transactionsUpdate);

                    // delete admin fees
                    $transactionsFees = $this->Transactions->find('all',["conditions"=>["exchange_id"=>$queryItemBuy['id'],
                        "transaction_id"=>$getTxId,
                        "tx_type"=>'buy_exchange',
                        "remark"=>"adminFees"]])
                        ->first();
                    if(!empty($transactionsFees)){
                        $this->Transactions->delete($transactionsFees);
                    }
                }
            }
        }

        if(!empty($querySell)){
            foreach ($querySell as $queryItemSell) {
                $sellUpdate = $this->SellExchange->get($queryItemSell['id']);
                $sellUpdate = $this->SellExchange->patchEntity($sellUpdate, ['status' => 'deleted']);
                $this->SellExchange->save($sellUpdate);

                $spendAmount = $queryItemSell['sell_spend_amount'];
                $exchangeId = $queryItemSell['id'];
                $userId = $queryItemSell['seller_user_id'];
                $cryptocoinId = $queryItemSell['sell_spend_coin_id'];

                $transactionsUpdate = $this->Transactions->find('all', ["conditions" => [
                    "exchange_id" => $queryItemSell['id'],
                    "tx_type" => 'sell_exchange',
                    "remark" => "reserve for exchange"]])
                    ->first();
                if (!empty($transactionsUpdate)) {
                    $getTxId = $transactionsUpdate['id'];
                    $this->Transactions->delete($transactionsUpdate);
                }
            }
        }
		return ;
    }

}
