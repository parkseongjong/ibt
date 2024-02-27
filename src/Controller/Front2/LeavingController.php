<?php

namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
use DateTime;
use Google_Client;
use Google_Service_Plus;
use Google_Service_Oauth2;
use http\Cookie;


class LeavingController extends AppController {

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['leavingComplete','assetWaiverDownload']);
    }
	/* 탈퇴 페이지 */
	public function leavingCoinibt(){
		$username = $this->Auth->user('username');
		$this->set('username',$username);
		$this->loadModel('AssetWaiver');
		$already_exist = $this->AssetWaiver->find()->where(['user_id'=>$this->Auth->user('id')])->count();
		if($already_exist > 0){
			//return $this->redirect(['action' => 'assetWaiverCheck']);
		}
		if($this->request->is('post')) {
			$password = $this->request->data('password');
			$checkPass = (new DefaultPasswordHasher)->check($password,$this->Auth->user('password'));
			if($checkPass){
				$result = $this->realLeaving($this->Auth->user('id'));// 탈퇴 처리 함수
				if($result == 'success'){ // 자산 없을 경우
					return $this->redirect(['action' => 'leavingComplete']);
				} else if($result == 'assetWaiver') { // 자산 남아 있을 경우 
					return $this->redirect(['action' => 'assetWaiver']);
				} else {
					$this->Flash->error('알 수 없는 오류가 발생했습니다. 관리자에게 문의해주세요.');
					return;
				}
			} else {
				$this->Flash->error('비밀번호가 일치하지 않습니다');
				return;
			}
		}
	}
	/* 탈퇴 완료 페이지 */
	public function leavingComplete(){
	
	}
	/* 자산 포기 안내 페이지 및 다운로드 페이지 */
	public function assetWaiver(){
	
	}
	/* 자산포기각서 확인 페이지 */
	public function assetWaiverCheck(){
		$user_id = $this->Auth->user('id');
	}
	/* 자산 포기 업로드 페이지 */
	public function assetWaiverUpload(){
		$this->loadModel('AssetWaiver');
		if($this->request->is('post')) {
			$asset_waiver_img = $this->request->data('asset_waiver_img');
			if(!empty($asset_waiver_img)){
				$user_id = $this->Auth->user('id');
				$check_asset = []; // 자산 체크 하는 곳
				$check_asset = $this->checkAssetTotal($user_id);
				if($check_asset['status'] == 'success'){
					$this->Flash->error('남아 있는 자산이 없습니다.');
					return ;
				}
				$max_file_size = 5242880;
				$pfile = $asset_waiver_img ['tmp_name'];
				$ptfile = $asset_waiver_img ['type'];
				$directory = 'uploads/leaving_coinibt';
				$filename = $asset_waiver_img ['name'];
				$ext = substr($filename, strrpos($filename, '.') + 1);
				$save_file_name = md5(microtime()) . '.' . $ext;

				$size = $asset_waiver_img ['size'];
				if($size > $max_file_size){
					$this->Flash->error('이미지 크키는 5MB 이하로 업로드해주세요.');
					return;
				}
				$upload = $this->imageUpload($pfile,$ptfile,$directory,$save_file_name);
				if(!$upload){
					$this->Flash->error('이미지 업로드에 실패했습니다.');
					return;
				}
				$query = $this->AssetWaiver->query();
				$query->insert(['user_id','origin_file_name','save_file_name','path','is_leaving','created','total_remain'])
					->values(['user_id'=>$user_id,'origin_file_name'=>$filename,'save_file_name'=>$save_file_name,'path'=>$directory,'is_leaving'=>'P','created'=>date('Y-m-d H:i:s'),'total_remain'=>$check_asset['total_remain']])->execute();
				$this->Flash->success('자산포기각서 업로드가 완료되었습니다.');
				return;
				
			}
			$this->Flash->error('자산포기각서를 첨부해주세요');
			return;
		}
	}
	/* 자산 포기 각서 다운로드 */
	public function assetWaiverDownload(){
		//$file_path = WWW_ROOT.'downloads'.DS.'asset_waiver.pdf';
		$file_path = WWW_ROOT.'downloads'.DS.'asset_waiver_210906.pdf'; //210906 업데이트
        $this->autoRender=false;
        $this->response->file($file_path,array('download' => true));
	}
	/* 실제 탈퇴 처리 로직 */
	private function realLeaving($user_id){
		$this->loadModel('LeavingUsers');
		$this->loadModel('Users');
		$check_asset = []; // 자산 체크 하는 곳
		$check_asset = $this->checkAssetTotal($user_id);
		if($check_asset['status'] == 'fail'){
			return 'assetWaiver';
		}
		$user = $this->Users->find('all')->where(['id'=>$user_id])->hydrate(false)->first();
		$columns = $this->LeavingUsers->schema()->columns();

		if(!empty($user)){
			$data_arr = [];
			foreach($user as $k=>$data){ // 칼럼을 key, 데이터를 value로 배열 만들기
				foreach($columns as $key=>$value){
					if($k == $value){
						$data_arr[$k] = $data;
						continue;
					}
				}
			}
			if(!empty($data_arr)){
				$data_arr['leave_date'] = date('Y-m-d H:i:s');
                //폐기할 데이터 전화번호 , 계좌번호 , 은행명
                $data_arr['phone_number'] = '';
                $data_arr['account_number'] = '';
                $data_arr['bank'] = '';
				$newObj = $this->LeavingUsers->newEntity();
				$newObj = $this->LeavingUsers->patchEntity($newObj, $data_arr);
				$saveThisData = $this->LeavingUsers->save($newObj);
				if($saveThisData){
					$this->send_email($user['email'],$this->masking('P',$user['username']), $user['name'],date('Y-m-d H:i:s')); // 탈퇴 안내 메일
					$this->deleteAllOrders($user['id']); // 주문 취소
					$this->delete_user_all($user['id']); // 유저 삭제 및 개인 정보 테이블 삭제
					return 'success';
				}
				return 'fail';
			}
		}
		return 'fail';
	}
	/* 이미지 업로드 */
	private function imageUpload($pfile,$ptfile,$directory,$filename) {
		$fileTemp = $pfile;
        $image_name = $filename;
        $imageType = $ptfile;
        $allowed = array('image/jpeg', 'image/png', 'image/gif', 'image/JPG', 'image/jpg', 'image/pjpeg');
        //To check if the file are image file
        if (!in_array($imageType, $allowed)) {
            return false;
        } else { 
	    	if(move_uploaded_file($fileTemp,WWW_ROOT.$directory."/".$image_name)) {
	        	return true;
	    	} else {
	        	return false;
	    	}
         }
     }
	 /* 자산 확인 */
	 private function checkAssetTotal($userId){
		$this->loadModel('Cryptocoin');
        $this->loadModel('Users');
		$this->loadModel('DepositApplicationWallet');
        $principalTotalBalance = 0.0;
        $tradingTotalBalance = 0.0;
        $total_KRW_value = 0.0;
        $total_coins_value = 0.0;
		$reserveTotalBalance = 0.0;
        $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId);
            $principalTotalBalance = $principalTotalBalance + $principalBalance;
            $tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
            $tradingTotalBalance = $tradingTotalBalance + $tradingBalance;
            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);

			$reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
			$reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);
			$reserveBalance = $reserveBuyBalance + $reserveSellBalance;
			$reserveTotalBalance = $reserveTotalBalance + $reserveBalance;

            $currentKRWTotalVal = ($principalBalance*$getMyCustomPrice)+($tradingBalance*$getMyCustomPrice);
            $currentCoinsTotalVal  = ($principalBalance + $tradingBalance);
            $total_KRW_value = $total_KRW_value + $currentKRWTotalVal;
            $total_coins_value = $total_coins_value + $currentCoinsTotalVal;
        }
		$deposit_application_wallet_amount = 0;
		$deposit_application_wallet = $this->DepositApplicationWallet->find()->select(['amount'])->where(['user_id'=>$userId])->first();
		if(!empty($deposit_application_wallet)){
			$deposit_application_wallet_amount = $deposit_application_wallet->amount;
		}
		$total_ramain = $total_KRW_value + abs($reserveTotalBalance) + $deposit_application_wallet_amount;
		$return_arr = [];

		if($total_ramain > 0){
			$return_arr = ['status'=>'fail','total_remain'=>$total_ramain];
		} else if($total_ramain == 0){
			$return_arr = ['status'=>'success','total_remain'=> 0];
		}
		return $return_arr;
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
	/* 탈퇴 안내 메일 전송 */
	private function send_email($user_email,$user_name, $name,$leaving_date){
		$email = new Email('default');
		$email->viewVars(['user_name'=>$user_name,'name'=>$name,'leaving_date'=>$leaving_date]);
		$email->from(['cs@onefamilymall.com'=>'Coin IBT'])
			->to($user_email)
			->subject('[COIN IBT] 회원 탈퇴가 완료되었습니다')
			->emailFormat('html')
			->template('leaving');
		if($email->send()){
			return 'success';
		} 
		return 'fail';
	}
	/* 회원 삭제 */
	private function delete_user_all($user_id){
		$this->loadModel('BoardQna');
		$this->loadModel('ChangeAuth');
		$this->loadModel('DepositApplicationList');
		$this->loadModel('DepositApplicationWallet');
		$this->loadModel('ErrorLoginLogs');
		$this->loadModel('LoginLogs');
		$this->loadModel('LoginSessions');
		$this->loadModel('WithdrawalWalletAddress');
		$this->loadModel('Users');

		$delete_qna = $this->BoardQna->query();
		$delete_qna->delete()->where(['users_id'=>$user_id])->execute();
		$delete_auth = $this->ChangeAuth->query();
		$delete_auth->delete()->where(['user_id'=>$user_id])->execute();
		$delete_da_list = $this->DepositApplicationList->query();
		$delete_da_list->delete()->where(['user_id'=>$user_id])->execute();
		$delete_da_wallet = $this->DepositApplicationWallet->query();
		$delete_da_wallet->delete()->where(['user_id'=>$user_id])->execute();
		$delete_error = $this->ErrorLoginLogs->query();
		$delete_error->delete()->where(['user_id'=>$user_id])->execute();
		$delete_log = $this->LoginLogs->query();
		$delete_log->delete()->where(['user_id'=>$user_id])->execute();
		$delete_session = $this->LoginSessions->query();
		$delete_session->delete()->where(['user_id'=>$user_id])->execute();
		$delete_address = $this->WithdrawalWalletAddress->query();
		$delete_address->delete()->where(['user_id'=>$user_id])->execute();

		$delete_query = $this->Users->query();
		$delete_query->delete()->where(['id'=>$user_id])->execute();
		return $this->redirect(['action' => 'leavingComplete']);
	}
}
?>