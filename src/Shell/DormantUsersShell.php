<?php 

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;


class DormantUsersShell extends Shell
{
	/* 사용자 - 휴면 계정 구분 */
	public function dormant(){
		// 1. 하루에 한 번 오늘 날짜부터 라스트 로그인이 11개월이거나 넘었다면 안내 메일 테이블로 insert 시킨다.
		// 2. 안내 메일 테이블에서 매일 메일 전송한다. 이때 전송 N => Y 로 업데이트 시켜준다.
		// 2.5. 안내 메일 받고 로그인 하였다면, 안내 메일 테이블에서 해당 유저의 정보는 삭제해준다. ==> /front2/UsersController login() 에서 처리
		// 3. 휴면 계정은 오늘 날짜부터 라스트 로그인이 12개월이거나 넘었고, 안내 메일이 발송 (Y) 되었다면, 해당 유저는 users 테이블에서 null =>dormant_users 테이블로 insert
		// 3.5 휴면 계정이 완료되었다면 안내 메일 테이블에서 해당 유저의 정보는 삭제해준다.
		// 4. 휴면 계정 사용자가 로그인 후 본인인증 및 해제 처리 시 복구, dormant_users 에서 해당 유저 정보 삭제 ==> /front2/UsersController 에서 처리해야함
		Log::write('debug',  '휴면계정 관리 프로세스 시작');
		$insert_email_result = $this->insert_dormant_email();
		Log::write('debug',  $insert_email_result);
		$send_email_result = $this->dormant_send_email();
		Log::write('debug',  $send_email_result);
		$insert_dormant_result = $this->insert_dormant();
		Log::write('debug',  $insert_dormant_result);
		Log::write('debug',  '휴면계정 관리 프로세스 종료');
		die;
	}

	// 1. 오늘 날짜부터 라스트 로그인이 11개월이거나 넘었다면 안내 메일 테이블로 insert 시킨다.
	function insert_dormant_email(){
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
	function dormant_send_email(){
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
	function insert_dormant(){
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
	function delete_dormant_email($user_id){
		$this->loadModel('DormantEmail');
		$query = $this->DormantEmail->query();
		$query->delete()->where(['user_id'=>$user_id,'send_email'=>'Y'])->execute();
	}
	/* 인증 변경 요청 테이블 해당 유저 정보 가져오기 */
	function get_auth_change($user_id){
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
	function get_wallet_address($user_id){
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
	/* ChangeAuth 해당 유저 null 처리 */
	function update_auth_change($user_id){
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
	function update_wallet_address($user_id){
		$this->loadModel('WithdrawalWalletAddress');
		$resp_arr = [];
		$columns = $this->WithdrawalWalletAddress->schema()->columns();
		foreach($columns as $k=>$data){
			if($data != 'id'){ // id 제외
				$resp_arr[$data] = null; // 그 외 초기화
			}
		}
		$query = $this->WithdrawalWalletAddress->query();
		$query->update()->set(['wallet_name'=>null])->where(['user_id'=>$user_id])->execute();
		return;
	}
	/* 1대1문의 해당 유저 정보 가져오기 */
	function get_board_qna($user_id){
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
	function update_board_qna($user_id){
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

	/* id 제외 모든 칼럼 null 처리 */
	function empty_array(){
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
	function send_email($user_email,$user_name,$dortmant_date){
		$email = new Email('default');
		$email->viewVars(['user_name'=>$user_name,'dortmant_date'=>$dortmant_date]);
		$email->from(['cs@onefamilymall.com'=>'Coin IBT'])
			->to($user_email)
			/*->subject('[COIN IBT] 회원님의 아이디가 휴면상태로 전환될 예정입니다')*/
            ->subject('[SM IBT] Your ID will be changed to a dormant state.')
			->emailFormat('html')
			->template('dormant');
		if($email->send()){
			return 'success';
		} 
		return 'fail';
	}

	/* 전체 예약된 주문 취소 - 하쌈 작성 */
	function deleteAllOrders($userId){
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
	/* 개인정보 마스킹 */
	public function masking($_type, $_data){
		$_data = str_replace('-','',$_data);
		$strlen = mb_strlen($_data, 'utf-8');
		$maskingValue = "";

		$useHyphen = "-";

		if($_type == 'N'){ // Name
			switch($strlen){
				case 2:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'*';
					break;
				case 3:
					//$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'*'.mb_strcut($_data, 8, 11, "UTF-8");
					$maskingValue = mb_substr($_data, 0, 1)."*".mb_substr($_data, 2, 3);
					break;
				case 4:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'**'.mb_strcut($_data, 12, 15, "UTF-8");
					break;
				case 5:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'***'.mb_strcut($_data, 12, 15, "UTF-8");
					break;
				default:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'****'.mb_strcut($_data, 8, $strlen, "UTF-8");
					break;
			}
		} else if($_type == 'P'){ // Phone
			switch($strlen){
				case 0:
					$maskingValue = '';
					break;
				case 10:
					$maskingValue = mb_substr($_data, 0, 2)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 6, 4);
					break;
				case 11:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, 4);
					break;
				default:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, $strlen);
					break;
			}
		} else if($_type == 'B') { // Bank Name
			switch($strlen){
				case 0:
					$maskingValue = '';
					break;
				case 8:
					$maskingValue = mb_substr($_data, 0, 2)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, 4);
					break;
				case 9:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}***{$useHyphen}".mb_substr($_data, 6, 4);
					break;
				case 10:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, 4);
					break;
				case 11:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, 4);
					break;
				case 12:
					$maskingValue = mb_substr($_data, 0, 4)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 8, 4);
					break;
				case 13:
					$maskingValue = mb_substr($_data, 0, 4)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 8, 5);
					break;
				case 14:
					$maskingValue = mb_substr($_data, 0, 4)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 9, 10);
					break;
				case 15:
					$maskingValue = mb_substr($_data, 0, 5)."{$useHyphen}*****{$useHyphen}".mb_substr($_data, 9, 10);
					break;
				case 16:
					$maskingValue = mb_substr($_data, 0, 5)."{$useHyphen}*****{$useHyphen}".mb_substr($_data, 9, 10);
					break;
				default:
					$maskingValue = mb_substr($_data, 0, 5)."{$useHyphen}*****{$useHyphen}".mb_substr($_data, 9, $strlen);
					break;
			}
		} else if($_type == 'E') { // Email
			$email = explode('@',$_data)[0];
			if(empty(explode('@',$_data)[1])){
				return;
			}
			$email_strlen = mb_strlen($email, 'utf-8');
			switch($email_strlen){
				case 0:
					$maskingValue = '';
					break;
				case 2:
					$maskingValue = mb_strcut($email, 0, 1, "UTF-8").'*';
					break;
				case 3:
					$maskingValue = mb_strcut($email, 0, 1, "UTF-8").'**';
					break;
				case 4:
					$maskingValue = mb_strcut($email, 0, 2, "UTF-8").'**';
					break;
				case 5:
					$maskingValue = mb_strcut($email, 0, 2, "UTF-8").'***';
					break;
				case 6:
					$maskingValue = mb_strcut($email, 0, 3, "UTF-8").'***';
					break;
				default:
					$maskingValue = mb_strcut($email, 0, 3, "UTF-8").'****'.mb_strcut($email, 8, $email_strlen, "UTF-8");
					break;
			}
			$maskingValue = $maskingValue. '@' .explode('@',$_data)[1];

		}
		return $maskingValue;
	}

}
?>