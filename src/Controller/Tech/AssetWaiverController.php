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
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Network\Exception\NotFoundException;
use Cake\Mailer\Email;

class AssetWaiverController extends AppController
{
	public function initialize() { 
		parent::initialize();
		$this -> loadComponent('Csrf');
	}
	/* 자산포기각서 목록 */
	function assetlist() {
		$this->loadModel('AssetWaiver');
		$query = $this->AssetWaiver->find()->select(['id','user_id','origin_file_name','save_file_name','path','is_leaving','created','approval_date','name'=>'u.name','total_remain']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'left','conditions' => ['u.id = AssetWaiver.user_id']]]);
		$query = $query->order(['AssetWaiver.id'=>'desc']);
		try {
			$asset_waiver_list =  $this->Paginator->paginate($query);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$asset_waiver_list =  $this->Paginator->paginate($query);
		}
		$this -> set('asset_waiver_list',$asset_waiver_list);
	}
	/* 자산포기각서 다운로드 */
	public function filedownload( $image = null) {
		$this->loadModel('AssetWaiver');
		$image_name = $this->AssetWaiver->find()->select(['origin_file_name'])->where(['save_file_name'=>$image])->first();
		$file_path = WWW_ROOT.'uploads/leaving_coinibt/'.$image;
        $this->autoRender=false;
        $this->response->file($file_path,array('download' => true,'name'=>$image_name->origin_file_name));
	}
	/* 탈퇴 기능 */
	public function realLeaving(){
		if($this->request->is('ajax')){
			$id = $this->request->data('id');
			$status = $this->request->data('status');
			if(!empty($id) && !empty($status)){
				if($status == 'Y'){ // 승인
					$result = $this->leaving($id,$status);
				} else if ($status == 'N'){ // 반려
					$result = $this->refuse($id,$status);
				}
				echo $result;
			}
		}
		die;
	}
	/* 반려 */
	private function refuse($id, $status){
		$this->loadModel('AssetWaiver');
		// 자산포기각서 테이블 상태값 업데이트 
		$this->update_asset_waiver($id, $status);
		
		// 메일 발송 정보 가져오기
		$select_query = $this->AssetWaiver->find()->select(['email'=>'u.email','user_name'=>'u.username','name'=>'u.name']);
		$select_query->join(['u' => ['table' => 'users','type' => 'left','conditions' => 'u.id = AssetWaiver.user_id']]);
		$select_query = $select_query->where(['AssetWaiver.id'=>$id])->first();

		$data = [];
		$data['user_email'] = $select_query->email;
		$data['user_name'] = $select_query->user_name;
		$data['name'] = $select_query->name;
		$data['leaving_date'] = date('Y-m-d H:i:s');
		// 반려 메일 발송
		$result = $this->send_email('[COIN IBT] 회원 탈퇴가 반려되었습니다', 'refuse_leaving',$data);
		return $result;
	}
	// 자산포기각서 테이블 상태값 업데이트 
	private function update_asset_waiver($id, $status){
		$this->loadModel('AssetWaiver');
		$update_query = $this->AssetWaiver->query();
		$update_query->update()->set(['is_leaving'=>$status,'approval_date'=>date('Y-m-d H:i:s')])->where(['id'=>$id])->execute();
		return;
	}
	/* 실제 탈퇴 시키는 곳 */
	private function leaving($id,$status){
		$this->loadModel('AssetWaiver');
		$this->loadModel('LeavingUsers');
		$this->loadModel('Users');
		$user = $this->AssetWaiver->find()->select(['user_id'])->where(['id'=>$id])->first();
		$user_id = $user->user_id;
		//$check_asset = ''; // 자산 체크 하는 곳
		//$check_asset = $this->checkAssetTotal($user_id);
		//if($check_asset == 'fail'){
		//	return 'assetWaiver';
		//}
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
				$data_arr['asset_waiver'] = $id;
				$newObj = $this->LeavingUsers->newEntity();
				$newObj = $this->LeavingUsers->patchEntity($newObj, $data_arr);
				$saveThisData = $this->LeavingUsers->save($newObj);
				if($saveThisData){
					$email_data = [];
					$email_data['user_email'] = $user['email'];
					$email_data['user_name'] = $this->masking('P',$user['username']);
					$email_data['name'] = $user['name'];
					$email_data['leaving_date'] = date('Y-m-d H:i:s');
					$this->send_email('[COIN IBT] 회원 탈퇴가 완료되었습니다', 'leaving', $email_data);
					$this->update_asset_waiver($id, $status);
					$this->deleteAllOrders($user['id']); // 주문 취소
					$this->delete_user_all($user['id']); // 유저 삭제 및 개인 정보 테이블 삭제

					return 'success';
				}
				return 'fail';
			}
		}
		return 'fail';
	}

	/* 탈퇴 안내 메일 전송 */
	private function send_email($subject, $template, $data){
		$email = new Email('default');
		$email->viewVars(['user_name'=>$data['user_name'],'name'=>$data['name'],'leaving_date'=>$data['leaving_date']]);
		$email->from(['cs@onefamilymall.com'=>'Coin IBT'])
			->to($data['user_email'])
			->subject($subject)
			->emailFormat('html')
			->template($template);
		if($email->send()){
			return 'success';
		} 
		return 'fail';
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

		if(($total_KRW_value + abs($reserveTotalBalance) + $deposit_application_wallet_amount) > 0){
			return 'fail';
		} else if(($total_KRW_value + abs($reserveTotalBalance) + $deposit_application_wallet_amount) == 0){
			return 'success';
		}
		return 'fail';
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
		return ;
	}
	
}
