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
// src/Controller/UsersController.php

namespace App\Controller\Admin;

ini_set('memory_limit', '-1');
use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;

class TransactionsController extends AppController
{
   
	public function getINR()
    {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://blockchain.info/ticker');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$contents = curl_exec($ch);
		$arr = json_decode($contents);
		echo "1 BTC  =".$arr->INR->buy." INR";
		die;
	}
	
	
	public function ico(){
		
		$this->set('title' , 'HC : Lending');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Referal');
		
		$authUser = $this->Auth->user();
		$this->set('authUser',$authUser);
		
		$type = "purchase";
		
		$coin_arr=['referral','purchase','bonus'];
		if(!in_array($type,$coin_arr)){
			$type = "purchase";
		}	
		$cudate = date("Y-m-d H:i:s");	
		$this->set('display_type','AGC');
		// for purchase
		$currentUser = $this->Auth->user('id');
		$referralUserId = $this->Auth->user('referral_user_id');
		
		
		$currentUserWallet = $this->Auth->user('unique_id');
		$this->set('currentUserWallet',$currentUserWallet);
		
		$getUserTotalCoin = $this->Cointransactions->find(); 
		$getUserTotalCoinCnt = $getUserTotalCoin
									->select(['sum' => $getUserTotalCoin->func()->sum('coin')])
									->where(array('type'=>'purchase'))
									->toArray();
		
		$getUserTotalCoinCount = $getUserTotalCoinCnt[0]['sum'];
		$this->set('getUserTotalCoinCount',$getUserTotalCoinCount);
		
		
		$limit = $this->setting['pagination'];
		
		$searchData = array();
		$searchData['AND'][] = array('type'=>'purchase');
		
		
		
		$this->set('listing',$this->Paginator->paginate($this->Cointransactions, [
						 'conditions'=>$searchData,
						'order'=>['Cointransactions.id'=>'desc'],
						'contain'=>['user'],
						'limit' => $limit,
					]));
		$this->set('type',$type);
		
		
		$getBitJsonData = file_get_contents("https://blockchain.info/ticker");
		$getDecode = json_decode($getBitJsonData,true); 
		$buyUsd = $getDecode['USD']['buy'];
		$this->set('buyUsd',$buyUsd);
		
		$query = $this->Agctransactions->find(); 
			
		
		$totalAMXCoin = $this->Token->find('all')->hydrate(false)->first();
		$this->set('totalAMXCoin',$totalAMXCoin);
		
		$coinPrice = $totalAMXCoin['price'];
		$this->set('coinPrice',$coinPrice);
		
	}
	
	public function icoSearch()
	{
		
		$this->loadModel('Cointransactions');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			
			
			
			
			$searchData = array();
			/* if($type=="referral" || $type=="bonus"){
				$searchData['AND'][] = array('status'=>'completed');	
			} */
			$searchData['AND'][] = array('type' => 'purchase');
			
			$limit = $this->setting['pagination'];
			
			/*  if($search['pagination'] != ''){
				 $limit =  $search['pagination'];
			 } */
			
			/* if($search['start_date'] != '' && $search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
			}
			else if($search['start_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
			}
			else if($search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
			} */
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$getData = $this->Paginator->paginate($this->Cointransactions, [
						    'conditions'=>$searchData,
							'contain'=>['user'],
						    'order'=>['Cointransactions.id'=>'desc'],
							'limit' => $limit
						]);
			//print_r($getData); die;			
			$this->set('listing',$getData);
		
			
			//$this->set('type',$search['type']);
		}
	
	}
	
	public function send()
    {
		$this->set('title','Send');
		$this->loadModel('Agctransactions');
		$this->loadModel('Users');
		$this->loadModel('Referal');
		$this->loadModel('Token');
		$this->loadModel('Cointransactions');
		$transaction = $this->Transactions->newEntity();
		$limit=500;
		$cudate = date("Y-m-d H:i:s");
		$searchData = array();
		//$searchData['AND'][] = array('coin_type'=>'B','trans_type'=>'S','user_id'=>$this->Auth->user('id'));
		if ($this->request->is(['post' ,'put'])) 
		{
			
				$currentCoinPrice = $this->Token->find('all')->hydrate(false)->first();
				$dollerPerCoin = $currentCoinPrice['price'];
				
				$getBitJsonData = file_get_contents("https://blockchain.info/ticker");
				$getDecode = json_decode($getBitJsonData,true); 
				$dollerPerBtc = $getDecode['USD']['buy'];
				
				$coinPurchaseVal = $this->request->data['amount'];
				$wallet_address = $this->request->data['wallet_address'];
				$payment_date = $this->request->data['payment_date'];
				
				if(isset($this->request->data['amount']))
				{
				
				$user = $this->Users->find('all',['fields'=>['id','referral_user_id'],'conditions'=>['id !='=>$this->Auth->user('id'),'unique_id'=>$wallet_address]])->hydrate(false)->first();

				if(!empty($user))
				{
					$referralUserId = $user['referral_user_id'];
				
					$newInsertArr = [];
					$newInsertArr['user_id'] = $user['id'];
					//$newInsertArr['btc'] = $btcPurchaseVal;
					$newInsertArr['coin'] = $coinPurchaseVal;
					$newInsertArr['dollar'] = $dollerPerCoin*$coinPurchaseVal;
					$newInsertArr['doller_per_hc'] = $dollerPerCoin;
					$newInsertArr['type'] = 'send_by_admin';
					$newInsertArr['admin_send_date'] = $payment_date;
					$newInsertArr['updated_at'] = $cudate;
					
					//print_r($newInsertArr); die;
					
					// insert data
					$purchaseCoinTransactions=$this->Cointransactions->newEntity();
					$purchaseCoinTransactions=$this->Cointransactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$saveData = $this->Cointransactions->save($purchaseCoinTransactions);
					$cointransactionsId = $saveData->id;
					
					if($saveData){
							
							// calculation for referral user
							if(!empty($referralUserId)){
								$findReferralUser = $this->Users->find("all",['conditions'=>["id"=>$referralUserId]])->hydrate(false)->first(); 
								$getReferalSetting = $this->Referal->find("all")->hydrate(false)->first();  
								$referralPercent = $getReferalSetting['referal_percent']; 
								$dollerReferral = $coinPurchaseVal*($referralPercent/100);
								$btcReferral = $dollerReferral/$dollerPerBtc;
								$coinReferral = $dollerReferral/$dollerPerCoin;
								
								$newReferalArr = [];
								$newReferalArr['user_id'] = $referralUserId;
								$newReferalArr['referral_user_id'] = $user['id'];
								$newReferalArr['btc'] = $btcReferral;
								$newReferalArr['coin'] = $coinReferral;
								$newReferalArr['dollar'] = $dollerReferral;
								$newReferalArr['doller_per_hc'] = $dollerPerCoin;
								$newReferalArr['type'] = 'referral';
								$newReferalArr['updated_at'] = $cudate;
								
								$referalTransactions=$this->Cointransactions->newEntity();
								$referalTransactions=$this->Cointransactions->patchEntity($referalTransactions,$newReferalArr);
								$saveReferaData = $this->Cointransactions->save($referalTransactions);
								//$cointransactionsId = $saveData->id;
									
							}
							$this->Flash->success(__('HC coin transfered successfully.'));
							return $this->redirect('admin/transactions/send');
					}
					else {
						$this->Flash->error(__('Unable to send HC. Try Again.'));
						return $this->redirect('admin/transactions/send');
					}
				}
				else {
					$this->Flash->error(__('Invalid wallet address'));
					return $this->redirect('admin/transactions/send');
				}
			
			}
			/* else{
				//Filter
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				if($search['pagination'] != '') $limit =  $search['pagination'];
				//pr($search);die;
				if($search['name'] != '') $searchData['AND'][] =array('from_user.name LIKE' => '%'.$search['name'].'%');
				if($search['unique_id'] != '') $searchData['AND'][] =array('from_user.unique_id' => $search['unique_id']);
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Cointransactions.created) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Cointransactions.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Cointransactions.created)' => $search['end_date']);
			} */
		}
		
		$transaction = $this->Cointransactions->find();
		$searchData['AND'][]= array('type'=>'send_by_admin');
		$this->set('listing', $this->Paginator->paginate($this->Cointransactions, [
			'contain'=>['user'],
			'conditions' => $searchData,
			'limit' => $limit,
			'order'=>['id'=>'desc']

		]));

		$this->set('transaction',$transaction);
        

    }
	
	
	public function btcSend()
    {   
	
		$this->set('title','Send');
		$this->loadModel('Agctransactions');
		$this->loadModel('Users');
		$this->loadModel('Referal');
		$this->loadModel('Token');
		$this->loadModel('Cointransactions');
		$this->loadModel('WithdrawalLog');
		$transaction = $this->Transactions->newEntity();
		$limit=500;
		$cudate = date("Y-m-d H:i:s");
		$searchData = array();
		if ($this->request->is(['post' ,'put'])) 
		{
			
			$requestType = $this->request->data['request_type']; 
			$securePin = $this->request->data['secure_pin']; 
	
			// for transaction request start
			if($requestType == "admin_trans") {
				
				if(!isset($this->request->data['agc_ids'])){
					$this->Flash->error("Select Atleast One User");
					return $this->redirect(['controller'=>'transactions','action'=>'btcSend']);
				}
				$getAgcIds = $this->request->data['agc_ids'];
				
				
				
				$checkAllAgcId = $this->Agctransactions->find("all",['conditions'=>['Agctransactions.id in'=>$getAgcIds,																	 'Agctransactions.admin_withdrawl_transfer'=>'yes',																	  'Agctransactions.coin_type'=>'withdrawal']])
																->hydrate(false)->all()->toArray();
				if(!empty($checkAllAgcId)){
					$this->Flash->error("All Transaction should be pending. Try Again");
					return $this->redirect(['controller'=>'transactions','action'=>'btcSend']);
				}					
				
				
				$wallet_address = [];
				$btcAmountToSend = [];
				$findAllAgcData = $this->Agctransactions->find("all",['conditions'=>['Agctransactions.id IN'=>$getAgcIds,																	 'Agctransactions.admin_withdrawl_transfer'=>'no',																	  'Agctransactions.coin_type'=>'withdrawal']])
																->hydrate(false)
																->all()
																->toArray();
				/* $wallet_address []= '2N9VCVvUziZPs3cfrW2NXWB6aovwamceNiL';
				$btcAmountToSend [] = 0.004; */
				
				foreach($findAllAgcData as $singelRecord){
					//var_dump($singelRecord['btc_coins']); die;
					$wallet_address[] = $singelRecord['wallet_address'];
					$btcAmountToSend[] = number_format(abs($singelRecord['btc_coins']),8); 
				}
				
				
				$callWithDrewlApi = $this->Users->withdrawBtcAmount($wallet_address,$btcAmountToSend,$securePin);
				
				
				file_put_contents("withdrawal_log.txt","===============>".date('Y-m-d H:i:s').json_encode($callWithDrewlApi).PHP_EOL,FILE_APPEND); // add response to log file
				
				
				
				if(!empty($callWithDrewlApi)) {
					// save log of withdrawal start
					
					if($callWithDrewlApi['status']=='fail'){
						$withdrawalArr['status'] = $callWithDrewlApi['status'];
						$withdrawalArr['error_message'] = $callWithDrewlApi['data']['error_message'];
					}
					else {
						$withdrawalArr['status'] = $callWithDrewlApi['status'];
						$withdrawalArr['network'] = $callWithDrewlApi['data']['network'];
						$withdrawalArr['txid'] = $callWithDrewlApi['data']['txid'];
						$withdrawalArr['amount_withdrawn'] = $callWithDrewlApi['data']['amount_withdrawn'];
						$withdrawalArr['amount_sent'] = $callWithDrewlApi['data']['amount_sent'];
						$withdrawalArr['network_fee'] = $callWithDrewlApi['data']['network_fee'];
						$withdrawalArr['blockio_fee'] = $callWithDrewlApi['data']['blockio_fee'];
					}
					
					$addData=$this->WithdrawalLog->newEntity();
					$addData=$this->WithdrawalLog->patchEntity($addData,$withdrawalArr);
					$addData = $this->WithdrawalLog->save($addData);
					// save log of withdrawal end
				}
				
				
				if(!empty($callWithDrewlApi) && $callWithDrewlApi['status']!='fail'){
					$transId = $callWithDrewlApi['data']['txid'];
					foreach($getAgcIds as $btc_transaction_id){
					
						//echo $btc_transaction_id; die;
						$btcData = $this->Agctransactions->get($btc_transaction_id);
						$btcData->admin_withdrawl_transfer = 'yes';
						$btcData->trans_id = $transId;
						$btcData->payment_date = $cudate;
						$btcSaveData=$this->Agctransactions->save($btcData);
							$userId = $btcSaveData['user_id'];
							$userData = $this->Users->find('all',array('conditions'=>array('id'=>$userId)))->first();
							
							$emailData = ['btc_amount'=>abs($btcSaveData['btc_coins']),
										  'name'=>$userData['name'],
										  'payment_date'=>$btcSaveData['payment_date'],
										  'wallet_address'=>$btcSaveData['wallet_address'],
										  'trans_id'=>$transId];
							
								$email = new Email('default');
								$email->viewVars(['data'=>$emailData]);
								$email->from([$this->setting['email_from']] )
									->to($userData['email'])
									->subject('Btc withdrawal From HedgeConnect')
									->emailFormat('html')
									->template('withdrawal')
									->send();
						
						
					}
					
					$this->Flash->success('Transaction Completed.');
					$this->redirect(['controller'=>'transactions','action'=>'btcSend']);
				}
				else {
					$this->Flash->error('Unable to make transaction. Try Again !! Error : '.$callWithDrewlApi['data']['error_message']);
					$this->redirect(['controller'=>'transactions','action'=>'btcSend']);
				}
			}
		}	
		
		$limit =  $this->setting['pagination'];
		$searchData = array();
		//$searchData['AND'][] = array('user_id' => $currentUser);
		$searchData['AND'][] = array('coin_type' => 'withdrawal');
		$searchData['AND'][] = array('trans_type' => 'debit');
		
		$limit = $this->setting['pagination'];
		
		
		$query = $this->Paginator->paginate($this->Agctransactions, [
						 'conditions'=>$searchData,
						 'contain'=>['user'=>['fields'=>['id','username','unique_id']]],
						'order'=>['Agctransactions.id'=>'desc'],
						'limit' => $limit,
					]);
		
		$this->set('listing',$query);
        

    }
	
	
	public function btcSendSearch()
	{
		
		$this->loadModel('Agctransactions');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			
			$search = $this->request->data;
			
			
			
			$searchData = array();
			
			//$searchData['AND'][] = array('user_id' => $currentUser);
			$searchData['AND'][] = array('coin_type' => 'withdrawal');
			$searchData['AND'][] = array('trans_type' => 'debit');
			if (isset($search['search_keyword']) && $search['search_keyword'] != '') {
                $searchData['AND'][] = array('username like' => '%' . $search['search_keyword'] . '%');
            }
			$limit = $this->setting['pagination'];
			
			 if(isset($search['pagination']) && $search['pagination'] != ''){
				 $limit =  $search['pagination'];
			 }
			
			/* if($search['start_date'] != '' && $search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
			}
			else if($search['start_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
			}
			else if($search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
			}
			 */
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$getData = $this->Paginator->paginate($this->Agctransactions, [
						    'conditions'=>$searchData,
						    'order'=>['Agctransactions.id'=>'desc'],
							'contain'=>['user'=>['fields'=>['id','username','unique_id']]],
							'limit' => $limit
						]);
			//print_r($getData); die;			
			$this->set('listing',$getData);
		
		}
	
	}
	
	
	
	public function transaction()
    {
		$this->set('title','Transaction');
		$this->loadModel('Agctransactions');
			$this->set('display_type','BTC');
			
			$limit = $this->setting['pagination'];
			$type = "BTC";
			$searchData = array();
			
			$searchData['AND'][] = array("Agctransactions.status"=>'completed'); 
			
			if ($this->request->is(['post' ,'put']) ) 
			{
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				
				if($search['pagination'] != '') $limit =  $search['pagination'];
				if($search['username'] != ''){
					$searchData['AND'][] = array("user.name like"=>"%".$search['username']."%");
				}
				
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Agctransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Agctransactions.created_at) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Agctransactions.created_at)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Agctransactions.created_at)' => $search['end_date']);
				
			}
			$collectdata = $this->Paginator->paginate($this->Agctransactions, [
						    //'contain'=>['user'=>['fields'=>['name','unique_id']],'from_user'=>['fields'=>['name','unique_id']]],
							'contain'=>['user'=>['fields'=>['username','unique_id']]],
							'conditions'=>$searchData,
						    'order'=>['Agctransactions.id'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		
	}
	
	public function transactionSearch()
	{
		
		$this->loadModel('Agctransactions');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = $search['type'];
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			$type = "All";
			
			$searchData = array();
		
			$limit = $this->setting['pagination'];
			
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['username'] != ''){
				$searchData['AND'][] = array("cavasotti.name like"=>"%".$search['username']."%");
			}
			
			$searchData['AND'][] = array("Agctransactions.status"=>'completed');
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Agctransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Agctransactions.created_at) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Agctransactions.created_at)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Agctransactions.created_at)' => $search['end_date']);
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$collectdata = $this->Paginator->paginate($this->Agctransactions, [
						    //'contain'=>['user'=>['fields'=>['name','unique_id']],'from_user'=>['fields'=>['name','unique_id']]],
							'contain'=>['user'=>['fields'=>['username','unique_id']]],
							'conditions'=>$searchData,
						    'order'=>['Agctransactions.id'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
		
			
			$this->set('type',$search['type']);
		}
	
	}
   
}
