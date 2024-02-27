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

namespace App\Controller\Front;


use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager; 
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
//use Google\Authenticator\GoogleAuthenticator;


class ExchangeController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		// Allow users to register and logout.
		// You should not add the "login" action to allow list. Doing so would
		// cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['signup', 'logout','frontLogin','frontRegister','verify','index','forgotPassword','successregister','marketHistory',"notCompletedOrderListAjax","getCurrenPrice"]);
    }

    public function addChatMessage( $_msg,$_user_name ){
    	
    	$this->loadModel('Messages');
    	
    		$authUserId = $this->Auth->user('id');
    		//echo $_msg;

    		$_save_msg = $this->Messages->newEntity();
			$msgArr = [];
			$msgArr['user_name'] = $_user_name;
			//$msgArr['user_type'] = ;
			$msgArr['msg'] = $_msg;
			$_save_msg = $this->Messages->patchEntity($_save_msg, $msgArr);
			$save = $this->Messages->save( $_save_msg );
			
    	exit();
    }
	
	
	public function testindex($firstCoin=null,$secondCoin=null){ 
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Messages');
		$this->loadModel('Coinpair');
		$cudate = date('Y-m-d H:i:s');
		$authUserId = $this->Auth->user('id');
		
		$this->set('authUserId',$authUserId);

		/* chat messages */

		$_user = $this->Users->find('all',array( 
					                                  'conditions' => array('id' => $authUserId),			
											        ))->hydrate(false)->first();
		$user_name = $_user['username'];


		$get_messages = $this->Messages->find('all',array( 
					                                  'conditions' => array('id'),'limit'=>'10','order'=>array('Messages.id' => 'DESC'),			
											        ))->hydrate(false)->toArray();

		$this->set(compact('get_messages','user_name'));

		/* chat messages */
		
		if(empty($firstCoin) || empty($secondCoin) || $firstCoin==null || $secondCoin==null){
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$goToWallet  = 0;
		if(in_array($authUserId,[10000719,10000699,10003992,10003090,10003091,10003093])){
			$goToWallet  = 1;
		}
		$this->set('goToWallet',$goToWallet);
		
		$user = $this->Users->get($this->Auth->user('id'));
		$this->set('user',$user);
		
		$this->set("firstCoin",$firstCoin);
		$this->set("secondCoin",$secondCoin);
		$currentUserId = $this->Auth->user('id');
		$currentUserName = $this->Auth->user('name');
		$this->set('currentUserName',$currentUserName);
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
		$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
		
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
		$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
		
		
		$firstCoinId = $getFirstCoinDetail['id'];
		$secondCoinId = $getSecondCoinDetail['id'];
		
		// for post request
		if($this->request->is('ajax')){
			$minEthToSpend = 0.02;
			$adminFee = 0.50000000;
			$getFromType = $this->request->data['type'];
			
			if($getFromType=="buy"){
				
				$receiveCoins = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				if(($receiveCoins <= 0) || ($perPrice <= 0)){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Amount and Price should be positive</div>");
					echo json_encode($arr);
					die;
					//$this->Flash->error(__("Amount Or Price should be positive."));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$receiveCoins = round($receiveCoins,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $receiveCoins*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToPay = $totalAmount+$adminFeeAmt; // calculate total amount to pay with admin fee
				
				
				if($firstCoinId == 5 && $totalAmount < 1 ){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Spend Amount should be 1 USD</div>");
					echo json_encode($arr);
					die;
				}
				
				if($firstCoinId != 5) {
					if($totalAmtToPay < $minEthToSpend){
						$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Limit for exchange is ".$minEthToSpend." ".$firstCoin." </div>");
						echo json_encode($arr); die;
					}
				}
				

				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$firstCoinId); // check user account balance
				if($getUserBalance < $totalAmtToPay){
					
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Insufficient ".$firstCoin." in account</div>");
					echo json_encode($arr); die;
					/* $this->Flash->error(__("Insufficient ".$firstCoin." in account"));
					return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]); */
				}
				else {
					$arr = array('error'=>0,"message"=>"<div class='alert alert-success'>Order Placed Successfully</div>");
					echo json_encode($arr); die;
				}
				
			/* 	
				$newInsertArr = [];
				$newInsertArr['buyer_user_id'] = $currentUserId;
				$newInsertArr['total_buy_spend_amount'] = $totalAmount;
				$newInsertArr['buy_spend_amount'] = $totalAmount;
				$newInsertArr['buy_spend_coin_id'] = $firstCoinId;
				$newInsertArr['per_price'] = $perPrice;
				$newInsertArr['total_buy_get_amount'] = $receiveCoins;
				$newInsertArr['buy_get_amount'] = $receiveCoins;
				$newInsertArr['buy_get_coin_id'] = $secondCoinId;
				$newInsertArr['buy_fees'] = $adminFeeAmt;
				$newInsertArr['status'] = 'pending';
				
				$exchangeTransactions=$this->BuyExchange->newEntity();
				$exchangeTransactions=$this->BuyExchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->BuyExchange->save($exchangeTransactions);
				$exchangeId = $saveData->id; */
				
			}
			
			if($getFromType=="sell"){
				
				
				
				$volume = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				if(($volume <= 0) || ($perPrice <= 0)){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Amount and Price should be positive</div>");
					echo json_encode($arr); die;
					//$this->Flash->error(__("Amount and Price should be positive."));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$volume = round($volume,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $volume*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToReceive = $totalAmount-$adminFeeAmt; // calculate total amount to pay with admin fee
				
				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$secondCoinId); // check user account balance
				
				
				$minEthToSpend = 0.02;
				if($firstCoinId == 5 && $totalAmount < 1 ){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Receive Amount should be 1 USD</div>");
					echo json_encode($arr);
					die;
				} 
				
				if($firstCoinId != 5) {
					if($totalAmount < $minEthToSpend){
						$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Limit for exchange is ".$minEthToSpend." ".$firstCoin." </div>");
						echo json_encode($arr); die;
					}
				}
				
				
				if($getUserBalance < $volume){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Insufficient ".$secondCoin." in account</div>");
					echo json_encode($arr); die;
					//echo "<div class='alert alert-danger'>Insufficient ".$secondCoin." in account</div>";
					/* $this->Flash->error(__("Insufficient ".$secondCoin." in account"));
					return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]); */
				}
				else {
					//echo "<div class='alert alert-success'>Order Placed Successfully</div>";
					$arr = array('error'=>0,"message"=>"<div class='alert alert-success'>Order Placed Successfully</div>");
					echo json_encode($arr); die;
				}
				die;
				/* $newInsertArr = [];
				$newInsertArr['seller_user_id'] = $currentUserId;
				$newInsertArr['total_sell_spend_amount'] = $volume;
				$newInsertArr['sell_spend_amount'] = $volume;
				$newInsertArr['sell_spend_coin_id'] = $secondCoinId;
				$newInsertArr['per_price'] = $perPrice;
				$newInsertArr['total_sell_get_amount'] = $totalAmtToReceive;
				$newInsertArr['sell_get_amount'] = $totalAmtToReceive;
				$newInsertArr['sell_get_coin_id'] = $firstCoinId;
				$newInsertArr['sell_fees'] = $adminFeeAmt;
				$newInsertArr['status'] = 'pending';
				
				$exchangeTransactions=$this->SellExchange->newEntity();
				$exchangeTransactions=$this->SellExchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->SellExchange->save($exchangeTransactions);
				$exchangeId = $saveData->id; */
				
			}
			die;
			
		}
		
		
		
		
		if(empty($getFirstCoinDetail) || empty($getSecondCoinDetail)){
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		/* $firstCoinId = $getFirstCoinDetail['id'];
		$secondCoinId = $getSecondCoinDetail['id']; */
		
		
		// admc allow for 2 users 
		/* if(!in_array($authUserId,[10000699,10003992,10003090]) && ($secondCoinId==4 || $firstCoinId==4)){
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		} */
		
		
		
		$this->set("firstCoinId",$firstCoinId);
		$this->set("secondCoinId",$secondCoinId);
		
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		$searchData = array('coin_first_id'=>$firstCoinId,'coin_second_id'=>$secondCoinId);
		$currentCoinPairDetail = $this->Coinpair->find('all',['conditions'=>$searchData])->hydrate(false)->first();
		$this->set('currentCoinPairDetail',$currentCoinPairDetail);
		
		
		
		
		// get Coin Pair List
		$searchData = array('Coinpair.status'=>1);
		/* if(!in_array($currentUserId,[10003090,10003992])){
			$searchData['Coinpair.id NOT IN']=[5,6];
		} */
		$getCoinPairList = $this->Coinpair->find('all',['conditions'=>$searchData,
														'contain'=>['cryptocoin_first','cryptocoin_second'],
														'order'=>['Coinpair.id'=>'desc'],
														'limit' => $this->setting['pagination']
														])
														->hydrate(false)
														->toArray();
		$this->set('getCoinPairList',$getCoinPairList);
		
		$getLastTenTransactions = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																							  ['get_cryptocoin_id'=>$secondCoinId,
																							   'spend_cryptocoin_id'=>$firstCoinId],
																							  ['spend_cryptocoin_id'=>$secondCoinId,
																							   'get_cryptocoin_id'=>$firstCoinId]
																							  ]
																						],	
																		//'limit' => 10,			 
																		'order' => ['id'=>'asc']
																		])	
																	  ->hydrate(false)
																	  ->toArray();
																	  
																	  
		/* $allBuyOrderList = $this->BuyExchange->find('all',['conditions'=>['buy_spend_coin_id '=>$firstCoinId,
																		 'buy_get_coin_id '=>$secondCoinId]
																		 ])
																	  ->hydrate(false)
																	  ->toArray();
																	  
		$allSellOrderList = $this->SellExchange->find('all',['conditions'=>['sell_spend_coin_id '=>$secondCoinId,
																	       'sell_get_coin_id '=>$firstCoinId]
																		   ])
																	  ->hydrate(false)
																	  ->toArray();
		
		$allBuySellOrderCount = count($allBuyOrderList)+count($allSellOrderList); */
		$allBuyOrderList = $this->BuyExchange->find('all',['conditions'=>['buy_spend_coin_id '=>$firstCoinId,
																		 'buy_get_coin_id '=>$secondCoinId]
																		 ])
																	  ->hydrate(false)
																	  ->count();
																	  
		$allSellOrderList = $this->SellExchange->find('all',['conditions'=>['sell_spend_coin_id '=>$secondCoinId,
																	       'sell_get_coin_id '=>$firstCoinId]
																		   ])
																	  ->hydrate(false)
																	  ->count();
		
		$allBuySellOrderCount = $allBuyOrderList+$allSellOrderList;
		$this->request->session()->write('totalOrder', $allBuySellOrderCount);															  
																  
		$sendGraphData = [] ;
		$st=1;
		foreach($getLastTenTransactions as $getLastTrans) {
			$setGraptAmt = ($getLastTrans['get_cryptocoin_id']==$secondCoinId) ? $getLastTrans['get_amount'] : $getLastTrans['spend_amount'] ;
			$sendGraphData[$st]['time'] = $getLastTrans['created_at'];
			$sendGraphData[$st]['amt'] = $getLastTrans['get_per_price'] ;
			$st++;
		}
		$this->set('sendGraphData',$sendGraphData);	
		
		
		// collect graph data
		
		$getGrpData = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$secondCoinId,
																					   'spend_cryptocoin_id'=>$firstCoinId],
																					  ['spend_cryptocoin_id'=>$secondCoinId,
																					   'get_cryptocoin_id'=>$firstCoinId]
																					  ]
																				],
															'fields'=>[
																		"open_price"=>"substring_index(group_concat(ExchangeHistory.get_per_price ORDER BY id ASC SEPARATOR ','), ',', 1)",
																	    "close_price"=>"substring_index(group_concat(ExchangeHistory.get_per_price ORDER BY id DESC SEPARATOR ','), ',', 1)",
																	    "min_price"=>"min(ExchangeHistory.get_per_price)",
																	    "max_price"=>"max(ExchangeHistory.get_per_price)",
																	    "datecol"=>"DATE_FORMAT(ExchangeHistory.created_at,'%Y-%m-%d %H:00')",
																	   ],
															"group"=>["DATE_FORMAT(ExchangeHistory.created_at,'%Y-%m-%d %H')"],
															"order"=>["id"=>"ASC"],
															//"limit"=>50
															])
															->hydrate(false)
															->toArray();
		
		$this->set('getGrpData',$getGrpData);	
		
		$firstCoinSum = 0 ;
		$secondCoinSum = 0 ;
		
		$getUserTotalFirstCoin = $this->Transactions->find(); 
		$getUserTotalCoinFirstCnt = $getUserTotalFirstCoin
									->select(['sum' => $getUserTotalFirstCoin->func()->sum('Transactions.coin_amount')])
									->where(['Transactions.user_id'=>$authUserId,
											'Transactions.cryptocoin_id'=>$firstCoinId,
											 'Transactions.status'=>'completed'])
									->hydrate(false)		 
									->toArray();
		if(!empty($getUserTotalCoinFirstCnt[0]['sum'])){							
			$firstCoinSum = $getUserTotalCoinFirstCnt[0]['sum'];
		}
		
		$getUserTotalSecondCoin = $this->Transactions->find(); 
		$getUserTotalCoinSecondCnt = $getUserTotalSecondCoin
									->select(['sum' => $getUserTotalSecondCoin->func()->sum('Transactions.coin_amount')])
									->where(['Transactions.user_id'=>$authUserId,
											'Transactions.cryptocoin_id'=>$secondCoinId,
											 'Transactions.status'=>'completed'])
									->hydrate(false)		 
									->toArray();
		if(!empty($getUserTotalCoinSecondCnt[0]['sum'])){	
		
			$secondCoinSum = $getUserTotalCoinSecondCnt[0]['sum'];
		}

		$this->set('firstCoinSum',$firstCoinSum);
		$this->set('secondCoinSum',$secondCoinSum);
		
		
		$minEthToSpend = 0.02;
		// for post request
		/* if($this->request->is('ajax')){
			$adminFee = 0.50000000;
			$getFromType = $this->request->data['type'];
			
			if($getFromType=="buy"){
				
				$receiveCoins = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				if(($receiveCoins <= 0) || ($perPrice <= 0)){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Amount and Price should be positive</div>");
					echo json_encode($arr);
					die;
					//$this->Flash->error(__("Amount Or Price should be positive."));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$receiveCoins = round($receiveCoins,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $receiveCoins*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToPay = $totalAmount+$adminFeeAmt; // calculate total amount to pay with admin fee
				
				
				if($totalAmtToPay < $minEthToSpend){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Limit for exchange is ".$minEthToSpend." ".$firstCoin." </div>");
					echo json_encode($arr); die;
				}
				
				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$firstCoinId); // check user account balance
				if($getUserBalance < $totalAmtToPay){
					
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Insufficient ".$firstCoin." in account</div>");
					echo json_encode($arr); die;
					
				}
				else {
					$arr = array('error'=>0,"message"=>"<div class='alert alert-success'>Order Placed Successfully</div>");
					echo json_encode($arr); die;
				}
				
			
				
			}
			
			if($getFromType=="sell"){
				
				
				
				$volume = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				if(($volume <= 0) || ($perPrice <= 0)){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Amount and Price should be positive</div>");
					echo json_encode($arr); die;
					//$this->Flash->error(__("Amount and Price should be positive."));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$volume = round($volume,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $volume*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToReceive = $totalAmount-$adminFeeAmt; // calculate total amount to pay with admin fee
				
				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$secondCoinId); // check user account balance
				
				
				$minEthToSpend = 0.02;
				if($totalAmount < $minEthToSpend){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Limit for exchange is ".$minEthToSpend." ".$firstCoin." </div>");
					echo json_encode($arr); die;
				}
				
				
				if($getUserBalance < $volume){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Insufficient ".$secondCoin." in account</div>");
					echo json_encode($arr); die;
					
				}
				else {
					//echo "<div class='alert alert-success'>Order Placed Successfully</div>";
					$arr = array('error'=>0,"message"=>"<div class='alert alert-success'>Order Placed Successfully</div>");
					echo json_encode($arr); die;
				}
				die;
				
				
			}
			die;
			
			// after save into exchange table
			if($saveData){
				$exchangeId = $saveData->id;
				
				if($getFromType=="buy"){
					$newInsertArr = [];
					$newTransArr['exchange_id']= $exchangeId;
					$newInsertArr['user_id'] = $currentUserId;
					$newInsertArr['cryptocoin_id'] = $firstCoinId;
					$newInsertArr['coin_amount'] = "-".$totalAmount;
					$newInsertArr['tx_type'] = 'buy_exchange';
					$newInsertArr['exchange_id'] = $exchangeId;
					$newInsertArr['remark'] = 'reserve for exchange';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$purchaseCoinTransactions=$this->Transactions->newEntity();
					$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
					$insertBuyId = $purchaseCoinTransactions->id;
				}
				
				
				if($getFromType=="sell"){
					
					
					$newInsertArr = [];
					$newTransArr['exchange_id']= $exchangeId;
					$newInsertArr['user_id'] = $currentUserId;
					$newInsertArr['cryptocoin_id'] = $secondCoinId;
					$newInsertArr['coin_amount'] = "-".$volume;
					$newInsertArr['tx_type'] = 'sell_exchange';
					$newInsertArr['exchange_id'] = $exchangeId;
					$newInsertArr['remark'] = 'reserve for exchange';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$purchaseCoinTransactions=$this->Transactions->newEntity();
					$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
					$insertSellId = $purchaseCoinTransactions->id;
					
				}
				
				$this->Flash->success(__(ucfirst($getFromType)." Order Created Successfully."));
				return $this->redirect(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
			}
			else {
				$this->Flash->error(__("Unable to Create ".$getFromType." Order ! Try Again."));
				return $this->redirect(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
			}
			

			// start exchange for current order
			
			if($getFromType=="buy"){
				
				$buy_get_amount = $volume;
				$buy_get_coin_id = 	$secondCoinId;	
				$buy_spend_coin_id = $firstCoinId;
				$buy_id = $insertBuyId ;
				$buy_per_price = $perPrice;
				
				$sellData = $this->SellExchange->find('all',['conditions'=>['status !='=>'completed',
																			'per_price <='=>$buy_per_price,
																			'sell_spend_coin_id '=>$buy_get_coin_id,
																			'sell_get_coin_id '=>$buy_spend_coin_id
																			]
															])
															->hydrate(false)
															->toArray();
				
				if(!empty($sellData)){
					$timeStmp = time();
					
					
					foreach($sellData as $singleSell){
						$realtedId =uniqid(); 
						
					
						$seller_user_id = $singleSell['seller_user_id'];
						$sell_spend_amount = $singleSell['sell_spend_amount'];
						$sell_spend_coin_id = $singleSell['sell_spend_coin_id'];
						$sell_per_price = $singleSell['per_price'];
						$sell_get_amount = $singleSell['sell_get_amount'];
						$sell_get_coin_id = $singleSell['sell_get_coin_id'];
						$sell_status = $singleSell['status'];
						$sell_Fees = $singleSell['sell_fees'];
						$sell_id = $singleSell['id'];
						
						
							if($sell_spend_amount > $buy_get_amount){
								
								$buyerAddAmt = $buy_get_amount;
								$sellerAddAmt = $buy_get_amount * $sell_per_price;		
								
								//$soldHcAmount = $buy_hc_amount;
								
								// sell log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'from';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $sell_status;		
								$addLog = $this->Users->addlog($logArr);
								
							
								// buy log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'to';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $buy_status;		
								$addLog = $this->Users->addlog($logArr);
							
								
								$insertData = [];
								//$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
								$insertData['spend_cryptocoin_id'] = $buy_spend_coin_id;
								$insertData['spend_amount'] = $buy_get_amount;
								$insertData['get_amount'] = $buy_get_amount;
								$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
								$insertData['buy_exchange_id'] = $buy_id;
								$insertData['sell_exchange_id'] = $sell_id;
								$insertData['spend_per_price'] = $sell_per_price;	
								$insertData['get_per_price'] = $buy_per_price;	
								$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
								$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
								$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
								$exchangeHistroyId = $exhangeSave->id;
								
								
								// exchange  log
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $exchangeHistroyId;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'subtract';
								$logArr['table_type'] = 'exchange';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
					
								
								
								$remainingAmt = $sell_spend_amount-$buy_get_amount;
								$buy_get_amount = 0.0000000;
								$buyUpdate = $this->BuyExchange->get($buy_id);
								$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>0.0000000,'status'=>'completed']);
								$buyUpdate = $this->BuyExchange->save($buyUpdate);
								
								// buy log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = 0.0000000;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
								
								$remainingStatus = "completed";
								if($remainingAmt>0){
									$remainingStatus = "pending";
								}
								
								$sellUpdate = $this->SellExchange->get($sell_id);
								$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>$remainingAmt,
																							'status'=>$remainingStatus]);
								$sellUpdate = $this->SellExchange->save($sellUpdate);
								
								// sell log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $remainingAmt;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $remainingStatus;		
								$addLog = $this->Users->addlog($logArr);
								
							}
							else {
								
								$buyerAddAmt = $sell_spend_amount * $buy_per_price;
								$sellerAddAmt = $sell_spend_amount;	
								
								
								// buy log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'from';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $buy_status;		
								$addLog = $this->Users->addlog($logArr);
								
								// sell log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'to';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $sell_status;		
								$addLog = $this->Users->addlog($logArr);
								
								
															
								$insertData = [];
								$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
								$insertData['spend_amount'] = $sell_spend_amount;
								$insertData['get_amount'] = $sell_spend_amount;
								$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
								$insertData['buy_exchange_id'] = $buy_id;
								$insertData['sell_exchange_id'] = $sell_id;
								$insertData['spend_per_price'] = $sell_per_price;	
								$insertData['get_per_price'] = $buy_per_price;	
								$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
								$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
								$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
								$exchangeHistroyId = $exhangeSave->id; 
								
								
								// exchange  log
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $exchangeHistroyId;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'subtract';
								$logArr['table_type'] = 'exchange';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
								$remainingAmt = $buy_get_amount-$sell_spend_amount;
								$buy_get_amount = $remainingAmt;
								
								$remainingStatus = "completed";
								if($remainingAmt>0){
									$remainingStatus = "pending";
								}
								
								$buyUpdate = $this->BuyExchange->get($buy_id);
								$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>$remainingAmt,'status'=>$remainingStatus]);
								$buyUpdate = $this->BuyExchange->save($buyUpdate);
								
								
								// buy log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $remainingAmt;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $remainingStatus;		
								$addLog = $this->Users->addlog($logArr);
								
								
								$sellUpdate = $this->SellExchange->get($sell_id);
								$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>0.0000000,'status'=>'completed']);
								$sellUpdate = $this->SellExchange->save($sellUpdate);
								
								// sell log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = 0.0000000;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
							} 
							
							
							// add btc to when user sell hc start
								
								
								
								
								
								// add Coin in seller account
								$newTransArr = [];
								$newTransArr['user_id']= $seller_user_id;
								$newTransArr['coin_amount']= $sellerAddAmt;
								$newTransArr['cryptocoin_id']= $sell_get_coin_id;
								$newTransArr['exchange_id']= $sell_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'sell_exchange';
								$newTransArr['remark']= 'sell_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created_at']= $cudate;
								$newTransArr['updated_at']= $cudate;
								
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								$transactionId = $addCoinToSellerAccount->id;
								
								$adminFeePercent = 0.50000000;
								$sellerAddAmtGet = $sellerAddAmt*$sell_per_price;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								
								
								// deduct adminFees From Seller Account
								$adminFeesFromSellerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromSellerDeduct,$transactionId,$seller_user_id,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								// add adminFees to Admin
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								
								
								
								
								// add coin in buyer account
								$newTransArr = [];
								$newTransArr['user_id']= $buyer_user_id;
								$newTransArr['coin_amount']= $sellerAddAmt;
								$newTransArr['cryptocoin_id']= $buy_get_coin_id;
								$newTransArr['exchange_id']= $buy_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'buy_exchange';
								$newTransArr['remark']= 'buy_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created_at']= $cudate;
								$newTransArr['updated_at']= $cudate;
								$transactionId = $addCoinToSellerAccount->id;
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								
								
								// deduct adminFees From buyer Account
								$sellerAddAmtGet = $sellerAddAmt*$buy_per_price;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								$adminFeesFromBuyerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromBuyerDeduct,$transactionId,$buyer_user_id,$buy_get_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
							
								// add adminFees to Admin
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$buy_get_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
					}
				}
				
				
				
			}
			
			
			if($getFromType=="sell"){
				
				$sell_get_amount = $volume;
				$sell_get_coin_id = $firstCoinId;	
				$sell_spend_coin_id = $secondCoinId;
				$sell_id = $insertBuyId ;
				$sell_per_price = $perPrice;
				
				$buyData = $this->BuyExchange->find('all',['conditions'=>['status !='=>'completed',
																			'per_price >='=>$sell_per_price,
																			'buy_spend_coin_id '=>$sell_get_coin_id,
																			'buy_get_coin_id '=>$sell_spend_coin_id
																			]
															])
															->hydrate(false)
															->toArray();
				
				if(!empty($buyData)){
					$timeStmp = time();
					
					
					foreach($buyData as $singleBuy){
						$realtedId =uniqid(); 
						
					
						$buyer_user_id = $singleBuy['buyer_user_id'];
						$buy_spend_amount = $singleBuy['buy_spend_amount'];
						$buy_spend_coin_id = $singleBuy['buy_spend_coin_id'];
						$buy_per_price = $singleBuy['per_price'];
						$buy_get_amount = $singleBuy['buy_get_amount'];
						$buy_get_coin_id = $singleBuy['buy_get_coin_id'];
						$buy_status = $singleBuy['status'];
						$buy_Fees = $singleBuy['buy_fees'];
						$buy_id = $singleBuy['id'];
						
						
							if($sell_spend_amount > $buy_get_amount){
								
								$buyerAddAmt = $buy_get_amount;
								$sellerAddAmt = $buy_get_amount * $sell_per_price;		
								
								//$soldHcAmount = $buy_hc_amount;
								
								// sell log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'from';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $sell_status;		
								$addLog = $this->Users->addlog($logArr);
								
							
								// buy log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'to';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $buy_status;		
								$addLog = $this->Users->addlog($logArr);
							
								
								$insertData = [];
								//$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
								$insertData['spend_cryptocoin_id'] = $buy_spend_coin_id;
								$insertData['spend_amount'] = $buy_get_amount;
								$insertData['get_amount'] = $buy_get_amount;
								$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
								$insertData['buy_exchange_id'] = $buy_id;
								$insertData['sell_exchange_id'] = $sell_id;
								$insertData['spend_per_price'] = $sell_per_price;	
								$insertData['get_per_price'] = $buy_per_price;	
								$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
								$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
								$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
								$exchangeHistroyId = $exhangeSave->id;
								
								
								// exchange  log
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $exchangeHistroyId;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'subtract';
								$logArr['table_type'] = 'exchange';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
					
								
								
								$remainingAmt = $sell_spend_amount-$buy_get_amount;
								$buy_get_amount = 0.0000000;
								$buyUpdate = $this->BuyExchange->get($buy_id);
								$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>0.0000000,'status'=>'completed']);
								$buyUpdate = $this->BuyExchange->save($buyUpdate);
								
								// buy log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = 0.0000000;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
								
								$remainingStatus = "completed";
								if($remainingAmt>0){
									$remainingStatus = "pending";
								}
								
								$sellUpdate = $this->SellExchange->get($sell_id);
								$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>$remainingAmt,
																							'status'=>$remainingStatus]);
								$sellUpdate = $this->SellExchange->save($sellUpdate);
								
								// sell log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $remainingAmt;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $remainingStatus;		
								$addLog = $this->Users->addlog($logArr);
								
							}
							else {
								
								$buyerAddAmt = $sell_spend_amount * $buy_per_price;
								$sellerAddAmt = $sell_spend_amount;	
								
								
								// buy log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'from';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $buy_status;		
								$addLog = $this->Users->addlog($logArr);
								
								// sell log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'to';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $sell_status;		
								$addLog = $this->Users->addlog($logArr);
								
								
															
								$insertData = [];
								$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
								$insertData['spend_amount'] = $sell_spend_amount;
								$insertData['get_amount'] = $sell_spend_amount;
								$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
								$insertData['buy_exchange_id'] = $buy_id;
								$insertData['sell_exchange_id'] = $sell_id;
								$insertData['spend_per_price'] = $sell_per_price;	
								$insertData['get_per_price'] = $buy_per_price;	
								$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
								$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
								$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
								$exchangeHistroyId = $exhangeSave->id; 
								
								
								// exchange  log
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $exchangeHistroyId;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'subtract';
								$logArr['table_type'] = 'exchange';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
								$remainingAmt = $buy_get_amount-$sell_spend_amount;
								$buy_get_amount = $remainingAmt;
								
								$remainingStatus = "completed";
								if($remainingAmt>0){
									$remainingStatus = "pending";
								}
								
								$buyUpdate = $this->BuyExchange->get($buy_id);
								$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>$remainingAmt,'status'=>$remainingStatus]);
								$buyUpdate = $this->BuyExchange->save($buyUpdate);
								
								
								// buy log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $remainingAmt;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $remainingStatus;		
								$addLog = $this->Users->addlog($logArr);
								
								
								$sellUpdate = $this->SellExchange->get($sell_id);
								$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>0.0000000,'status'=>'completed']);
								$sellUpdate = $this->SellExchange->save($sellUpdate);
								
								// sell log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = 0.0000000;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
							} 
							
							
							// add btc to when user sell hc start
								
								
								
								
								
								// add Coin in seller account
								$newTransArr = [];
								$newTransArr['user_id']= $seller_user_id;
								$newTransArr['coin_amount']= $sellerAddAmt;
								$newTransArr['cryptocoin_id']= $sell_get_coin_id;
								$newTransArr['exchange_id']= $sell_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'sell_exchange';
								$newTransArr['remark']= 'sell_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created_at']= $cudate;
								$newTransArr['updated_at']= $cudate;
								
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								$transactionId = $addCoinToSellerAccount->id;
								
								$adminFeePercent = 0.50000000;
								$sellerAddAmtGet = $sellerAddAmt*$sell_per_price;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								
								
								// deduct adminFees From Seller Account
								$adminFeesFromSellerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromSellerDeduct,$transactionId,$seller_user_id,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								// add adminFees to Admin
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								
								
								
								
								// add coin in buyer account
								$newTransArr = [];
								$newTransArr['user_id']= $buyer_user_id;
								$newTransArr['coin_amount']= $sellerAddAmt;
								$newTransArr['cryptocoin_id']= $buy_get_coin_id;
								$newTransArr['exchange_id']= $buy_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'buy_exchange';
								$newTransArr['remark']= 'buy_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created_at']= $cudate;
								$newTransArr['updated_at']= $cudate;
								$transactionId = $addCoinToSellerAccount->id;
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								
								
								// deduct adminFees From buyer Account
								$sellerAddAmtGet = $sellerAddAmt*$buy_per_price;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								$adminFeesFromBuyerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromBuyerDeduct,$transactionId,$buyer_user_id,$buy_get_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
							
								// add adminFees to Admin
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$buy_get_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
					}
				}
				
				
				
			}
			
			
			
			
			
			
		} */
		
	}

	public function index($firstCoin=null,$secondCoin=null){ 
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Coinpair');
		$this->loadModel('Messages');
		$cudate = date('Y-m-d H:i:s');
		$authUserId = $this->Auth->user('id');
		
		$this->set('authUserId',$authUserId);
		$user_name = "Guest";
		if(!empty($authUserId)) {
			$_user = $this->Users->find('all',array( 
														  'conditions' => array('id' => $authUserId),			
														))->hydrate(false)->first();
			$user_name = $_user['username'];
		}
		
		
		if(empty($firstCoin) || empty($secondCoin) || $firstCoin==null || $secondCoin==null){
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$get_messages = $this->Messages->find('all',array( 
					                                  'conditions' => array('id'),'limit'=>'10','order'=>array('Messages.id' => 'desc'),			
											        ))->hydrate(false)->toArray();

		$this->set(compact('get_messages','user_name'));
		
		
		$goToWallet  = 0;
		if(in_array($authUserId,[10000719,10000699,10003992,10003090,10003091,10003093])){
			$goToWallet  = 1;
		}
		$this->set('goToWallet',$goToWallet);
		
		if(!empty($authUserId)) {
			$user = $this->Users->get($this->Auth->user('id'));
			$this->set('user',$user);
		}
		$this->set("firstCoin",$firstCoin);
		$this->set("secondCoin",$secondCoin);
		$currentUserId = "";
		$currentUserName = "Guest";
		if(!empty($authUserId)) {
			$currentUserId = $this->Auth->user('id');
			$currentUserName = $this->Auth->user('name');
		}
		$this->set('currentUserName',$currentUserName);
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
		$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
		
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
		$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
		
		
		$firstCoinId = $getFirstCoinDetail['id'];
		$secondCoinId = $getSecondCoinDetail['id'];
		
		$checkPairExist = $this->Coinpair->find('all',['conditions'=>['coin_first_id'=>$firstCoinId,'coin_second_id'=>$secondCoinId]])->hydrate(false)->first();
		if(empty($checkPairExist)){
			//$this->Flash->error(__("Unable to Create ".$getFromType." Order ! Try Again."));
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		/* if($authUserId != 10003992 && $firstCoinId == 3  && $secondCoinId == 4){
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		} */
		// for post request
		if($this->request->is('ajax')){
			$minEthToSpend = 0.0000002;
			$adminFee = 0.50000000;
			$getFromType = $this->request->data['type'];
			
			if($getFromType=="buy"){
				
				$receiveCoins = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				if(($receiveCoins <= 0) || ($perPrice <= 0)){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Amount and Price should be positive</div>");
					echo json_encode($arr);
					die;
					//$this->Flash->error(__("Amount Or Price should be positive."));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$receiveCoins = round($receiveCoins,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $receiveCoins*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToPay = $totalAmount+$adminFeeAmt; // calculate total amount to pay with admin fee
				
				
				if($firstCoinId == 5 && $totalAmount < 1 ){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Spend Amount should be 1 USD</div>");
					echo json_encode($arr);
					die;
				}
				
				if($firstCoinId != 5) {
					if($totalAmtToPay < $minEthToSpend){
						$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Limit for exchange is ".$minEthToSpend." ".$firstCoin." </div>");
						echo json_encode($arr); die;
					}
				}
				

				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$firstCoinId); // check user account balance
				if($getUserBalance < $totalAmtToPay){
					
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Insufficient ".$firstCoin." in account</div>");
					echo json_encode($arr); die;
					/* $this->Flash->error(__("Insufficient ".$firstCoin." in account"));
					return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]); */
				}
				else {
					$arr = array('error'=>0,"message"=>"<div class='alert alert-success'>Order Placed Successfully</div>");
					echo json_encode($arr); die;
				}
				
			/* 	
				$newInsertArr = [];
				$newInsertArr['buyer_user_id'] = $currentUserId;
				$newInsertArr['total_buy_spend_amount'] = $totalAmount;
				$newInsertArr['buy_spend_amount'] = $totalAmount;
				$newInsertArr['buy_spend_coin_id'] = $firstCoinId;
				$newInsertArr['per_price'] = $perPrice;
				$newInsertArr['total_buy_get_amount'] = $receiveCoins;
				$newInsertArr['buy_get_amount'] = $receiveCoins;
				$newInsertArr['buy_get_coin_id'] = $secondCoinId;
				$newInsertArr['buy_fees'] = $adminFeeAmt;
				$newInsertArr['status'] = 'pending';
				
				$exchangeTransactions=$this->BuyExchange->newEntity();
				$exchangeTransactions=$this->BuyExchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->BuyExchange->save($exchangeTransactions);
				$exchangeId = $saveData->id; */
				
			}
			
			if($getFromType=="sell"){
				
				
				
				$volume = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				if(($volume <= 0) || ($perPrice <= 0)){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Amount and Price should be positive</div>");
					echo json_encode($arr); die;
					//$this->Flash->error(__("Amount and Price should be positive."));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$volume = round($volume,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $volume*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToReceive = $totalAmount-$adminFeeAmt; // calculate total amount to pay with admin fee
				
				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$secondCoinId); // check user account balance
				
				
				$minEthToSpend = 0.0000002;
				if($firstCoinId == 5 && $totalAmount < 1 ){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Receive Amount should be 1 USD</div>");
					echo json_encode($arr);
					die;
				} 
				
				if($firstCoinId != 5) {
					if($totalAmount < $minEthToSpend){
						$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Limit for exchange is ".$minEthToSpend." ".$firstCoin." </div>");
						echo json_encode($arr); die;
					}
				}
				
				
				if($getUserBalance < $volume){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Insufficient ".$secondCoin." in account</div>");
					echo json_encode($arr); die;
					//echo "<div class='alert alert-danger'>Insufficient ".$secondCoin." in account</div>";
					/* $this->Flash->error(__("Insufficient ".$secondCoin." in account"));
					return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]); */
				}
				else {
					//echo "<div class='alert alert-success'>Order Placed Successfully</div>";
					$arr = array('error'=>0,"message"=>"<div class='alert alert-success'>Order Placed Successfully</div>");
					echo json_encode($arr); die;
				}
				die;
				/* $newInsertArr = [];
				$newInsertArr['seller_user_id'] = $currentUserId;
				$newInsertArr['total_sell_spend_amount'] = $volume;
				$newInsertArr['sell_spend_amount'] = $volume;
				$newInsertArr['sell_spend_coin_id'] = $secondCoinId;
				$newInsertArr['per_price'] = $perPrice;
				$newInsertArr['total_sell_get_amount'] = $totalAmtToReceive;
				$newInsertArr['sell_get_amount'] = $totalAmtToReceive;
				$newInsertArr['sell_get_coin_id'] = $firstCoinId;
				$newInsertArr['sell_fees'] = $adminFeeAmt;
				$newInsertArr['status'] = 'pending';
				
				$exchangeTransactions=$this->SellExchange->newEntity();
				$exchangeTransactions=$this->SellExchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->SellExchange->save($exchangeTransactions);
				$exchangeId = $saveData->id; */
				
			}
			die;
			
		}
		
		
		
		
		if(empty($getFirstCoinDetail) || empty($getSecondCoinDetail)){
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		/* $firstCoinId = $getFirstCoinDetail['id'];
		$secondCoinId = $getSecondCoinDetail['id']; */
		
		
		// admc allow for 2 users 
		/* if(!in_array($authUserId,[10000699,10003992,10003090]) && ($secondCoinId==4 || $firstCoinId==4)){
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		} */
		
		
		
		$this->set("firstCoinId",$firstCoinId);
		$this->set("secondCoinId",$secondCoinId);
		
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		$searchData = array('coin_first_id'=>$firstCoinId,'coin_second_id'=>$secondCoinId);
		$currentCoinPairDetail = $this->Coinpair->find('all',['conditions'=>$searchData])->hydrate(false)->first();
		$this->set('currentCoinPairDetail',$currentCoinPairDetail);
		
		
		
		
		// get Coin Pair List
		$searchData = array('Coinpair.status'=>1);
		/* if(!in_array($currentUserId,[10003090,10003992])){
			$searchData['Coinpair.id NOT IN']=[5,6];
		} */
		$getCoinPairList = $this->Coinpair->find('all',['conditions'=>$searchData,
														'contain'=>['cryptocoin_first','cryptocoin_second'],
														'order'=>['Coinpair.id'=>'asc'],
														//'limit' => $this->setting['pagination']
														])
														->hydrate(false)
														->toArray();
		$this->set('getCoinPairList',$getCoinPairList);
		
		$getLastTenTransactions = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																							  ['get_cryptocoin_id'=>$secondCoinId,
																							   'spend_cryptocoin_id'=>$firstCoinId],
																							  ['spend_cryptocoin_id'=>$secondCoinId,
																							   'get_cryptocoin_id'=>$firstCoinId]
																							  ]
																						],	
																		//'limit' => 10,			 
																		'order' => ['id'=>'asc']
																		])	
																	  ->hydrate(false)
																	  ->toArray();
																	  
																	  
		/* $allBuyOrderList = $this->BuyExchange->find('all',['conditions'=>['buy_spend_coin_id '=>$firstCoinId,
																		 'buy_get_coin_id '=>$secondCoinId]
																		 ])
																	  ->hydrate(false)
																	  ->toArray();
																	  
		$allSellOrderList = $this->SellExchange->find('all',['conditions'=>['sell_spend_coin_id '=>$secondCoinId,
																	       'sell_get_coin_id '=>$firstCoinId]
																		   ])
																	  ->hydrate(false)
																	  ->toArray();
		
		$allBuySellOrderCount = count($allBuyOrderList)+count($allSellOrderList); */
		$allBuyOrderList = $this->BuyExchange->find('all',['conditions'=>['buy_spend_coin_id '=>$firstCoinId,
																		 'buy_get_coin_id '=>$secondCoinId]
																		 ])
																	  ->hydrate(false)
																	  ->count();
																	  
		$allSellOrderList = $this->SellExchange->find('all',['conditions'=>['sell_spend_coin_id '=>$secondCoinId,
																	       'sell_get_coin_id '=>$firstCoinId]
																		   ])
																	  ->hydrate(false)
																	  ->count();
		
		$allBuySellOrderCount = $allBuyOrderList+$allSellOrderList;
		$this->request->session()->write('totalOrder', $allBuySellOrderCount);															  
																  
		$sendGraphData = [] ;
		$st=1;
		foreach($getLastTenTransactions as $getLastTrans) {
			$setGraptAmt = ($getLastTrans['get_cryptocoin_id']==$secondCoinId) ? $getLastTrans['get_amount'] : $getLastTrans['spend_amount'] ;
			$sendGraphData[$st]['time'] = $getLastTrans['created_at'];
			$sendGraphData[$st]['amt'] = $getLastTrans['get_per_price'] ;
			$st++;
		}
		$this->set('sendGraphData',$sendGraphData);	
		
		
		// collect graph data
		
		$getGrpData = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$secondCoinId,
																					   'spend_cryptocoin_id'=>$firstCoinId],
																					  ['spend_cryptocoin_id'=>$secondCoinId,
																					   'get_cryptocoin_id'=>$firstCoinId]
																					  ]
																				],
															'fields'=>[
																		"open_price"=>"substring_index(group_concat(ExchangeHistory.get_per_price ORDER BY id ASC SEPARATOR ','), ',', 1)",
																	    "close_price"=>"substring_index(group_concat(ExchangeHistory.get_per_price ORDER BY id DESC SEPARATOR ','), ',', 1)",
																	    "min_price"=>"min(ExchangeHistory.get_per_price)",
																	    "max_price"=>"max(ExchangeHistory.get_per_price)",
																	    "datecol"=>"DATE_FORMAT(ExchangeHistory.created_at,'%Y-%m-%d %H:00')",
																	   ],
															"group"=>["DATE_FORMAT(ExchangeHistory.created_at,'%Y-%m-%d %H')"],
															"order"=>["id"=>"ASC"],
															//"limit"=>50
															])
															->hydrate(false)
															->toArray();
		
		$this->set('getGrpData',$getGrpData);	
		
		$firstCoinSum = 0 ;
		$secondCoinSum = 0 ;
		
		$getUserTotalFirstCoin = $this->Transactions->find(); 
		$getUserTotalCoinFirstCnt = $getUserTotalFirstCoin
									->select(['sum' => $getUserTotalFirstCoin->func()->sum('Transactions.coin_amount')])
									->where(['Transactions.user_id'=>$authUserId,
											'Transactions.cryptocoin_id'=>$firstCoinId,
											 'Transactions.status'=>'completed'])
									->hydrate(false)		 
									->toArray();
		if(!empty($getUserTotalCoinFirstCnt[0]['sum'])){							
			$firstCoinSum = $getUserTotalCoinFirstCnt[0]['sum'];
		}
		
		$getUserTotalSecondCoin = $this->Transactions->find(); 
		$getUserTotalCoinSecondCnt = $getUserTotalSecondCoin
									->select(['sum' => $getUserTotalSecondCoin->func()->sum('Transactions.coin_amount')])
									->where(['Transactions.user_id'=>$authUserId,
											'Transactions.cryptocoin_id'=>$secondCoinId,
											 'Transactions.status'=>'completed'])
									->hydrate(false)		 
									->toArray();
		if(!empty($getUserTotalCoinSecondCnt[0]['sum'])){	
		
			$secondCoinSum = $getUserTotalCoinSecondCnt[0]['sum'];
		}

		$this->set('firstCoinSum',$firstCoinSum);
		$this->set('secondCoinSum',$secondCoinSum);
		
		
		$minEthToSpend = 0.02;
		// for post request
		/* if($this->request->is('ajax')){
			$adminFee = 0.50000000;
			$getFromType = $this->request->data['type'];
			
			if($getFromType=="buy"){
				
				$receiveCoins = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				if(($receiveCoins <= 0) || ($perPrice <= 0)){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Amount and Price should be positive</div>");
					echo json_encode($arr);
					die;
					//$this->Flash->error(__("Amount Or Price should be positive."));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$receiveCoins = round($receiveCoins,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $receiveCoins*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToPay = $totalAmount+$adminFeeAmt; // calculate total amount to pay with admin fee
				
				
				if($totalAmtToPay < $minEthToSpend){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Limit for exchange is ".$minEthToSpend." ".$firstCoin." </div>");
					echo json_encode($arr); die;
				}
				
				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$firstCoinId); // check user account balance
				if($getUserBalance < $totalAmtToPay){
					
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Insufficient ".$firstCoin." in account</div>");
					echo json_encode($arr); die;
					
				}
				else {
					$arr = array('error'=>0,"message"=>"<div class='alert alert-success'>Order Placed Successfully</div>");
					echo json_encode($arr); die;
				}
				
			
				
			}
			
			if($getFromType=="sell"){
				
				
				
				$volume = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				if(($volume <= 0) || ($perPrice <= 0)){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Amount and Price should be positive</div>");
					echo json_encode($arr); die;
					//$this->Flash->error(__("Amount and Price should be positive."));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$volume = round($volume,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $volume*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToReceive = $totalAmount-$adminFeeAmt; // calculate total amount to pay with admin fee
				
				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$secondCoinId); // check user account balance
				
				
				$minEthToSpend = 0.02;
				if($totalAmount < $minEthToSpend){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Minimum Limit for exchange is ".$minEthToSpend." ".$firstCoin." </div>");
					echo json_encode($arr); die;
				}
				
				
				if($getUserBalance < $volume){
					$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>Insufficient ".$secondCoin." in account</div>");
					echo json_encode($arr); die;
					
				}
				else {
					//echo "<div class='alert alert-success'>Order Placed Successfully</div>";
					$arr = array('error'=>0,"message"=>"<div class='alert alert-success'>Order Placed Successfully</div>");
					echo json_encode($arr); die;
				}
				die;
				
				
			}
			die;
			
			// after save into exchange table
			if($saveData){
				$exchangeId = $saveData->id;
				
				if($getFromType=="buy"){
					$newInsertArr = [];
					$newTransArr['exchange_id']= $exchangeId;
					$newInsertArr['user_id'] = $currentUserId;
					$newInsertArr['cryptocoin_id'] = $firstCoinId;
					$newInsertArr['coin_amount'] = "-".$totalAmount;
					$newInsertArr['tx_type'] = 'buy_exchange';
					$newInsertArr['exchange_id'] = $exchangeId;
					$newInsertArr['remark'] = 'reserve for exchange';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$purchaseCoinTransactions=$this->Transactions->newEntity();
					$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
					$insertBuyId = $purchaseCoinTransactions->id;
				}
				
				
				if($getFromType=="sell"){
					
					
					$newInsertArr = [];
					$newTransArr['exchange_id']= $exchangeId;
					$newInsertArr['user_id'] = $currentUserId;
					$newInsertArr['cryptocoin_id'] = $secondCoinId;
					$newInsertArr['coin_amount'] = "-".$volume;
					$newInsertArr['tx_type'] = 'sell_exchange';
					$newInsertArr['exchange_id'] = $exchangeId;
					$newInsertArr['remark'] = 'reserve for exchange';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$purchaseCoinTransactions=$this->Transactions->newEntity();
					$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
					$insertSellId = $purchaseCoinTransactions->id;
					
				}
				
				$this->Flash->success(__(ucfirst($getFromType)." Order Created Successfully."));
				return $this->redirect(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
			}
			else {
				$this->Flash->error(__("Unable to Create ".$getFromType." Order ! Try Again."));
				return $this->redirect(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
			}
			

			// start exchange for current order
			
			if($getFromType=="buy"){
				
				$buy_get_amount = $volume;
				$buy_get_coin_id = 	$secondCoinId;	
				$buy_spend_coin_id = $firstCoinId;
				$buy_id = $insertBuyId ;
				$buy_per_price = $perPrice;
				
				$sellData = $this->SellExchange->find('all',['conditions'=>['status !='=>'completed',
																			'per_price <='=>$buy_per_price,
																			'sell_spend_coin_id '=>$buy_get_coin_id,
																			'sell_get_coin_id '=>$buy_spend_coin_id
																			]
															])
															->hydrate(false)
															->toArray();
				
				if(!empty($sellData)){
					$timeStmp = time();
					
					
					foreach($sellData as $singleSell){
						$realtedId =uniqid(); 
						
					
						$seller_user_id = $singleSell['seller_user_id'];
						$sell_spend_amount = $singleSell['sell_spend_amount'];
						$sell_spend_coin_id = $singleSell['sell_spend_coin_id'];
						$sell_per_price = $singleSell['per_price'];
						$sell_get_amount = $singleSell['sell_get_amount'];
						$sell_get_coin_id = $singleSell['sell_get_coin_id'];
						$sell_status = $singleSell['status'];
						$sell_Fees = $singleSell['sell_fees'];
						$sell_id = $singleSell['id'];
						
						
							if($sell_spend_amount > $buy_get_amount){
								
								$buyerAddAmt = $buy_get_amount;
								$sellerAddAmt = $buy_get_amount * $sell_per_price;		
								
								//$soldHcAmount = $buy_hc_amount;
								
								// sell log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'from';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $sell_status;		
								$addLog = $this->Users->addlog($logArr);
								
							
								// buy log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'to';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $buy_status;		
								$addLog = $this->Users->addlog($logArr);
							
								
								$insertData = [];
								//$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
								$insertData['spend_cryptocoin_id'] = $buy_spend_coin_id;
								$insertData['spend_amount'] = $buy_get_amount;
								$insertData['get_amount'] = $buy_get_amount;
								$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
								$insertData['buy_exchange_id'] = $buy_id;
								$insertData['sell_exchange_id'] = $sell_id;
								$insertData['spend_per_price'] = $sell_per_price;	
								$insertData['get_per_price'] = $buy_per_price;	
								$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
								$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
								$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
								$exchangeHistroyId = $exhangeSave->id;
								
								
								// exchange  log
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $exchangeHistroyId;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'subtract';
								$logArr['table_type'] = 'exchange';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
					
								
								
								$remainingAmt = $sell_spend_amount-$buy_get_amount;
								$buy_get_amount = 0.0000000;
								$buyUpdate = $this->BuyExchange->get($buy_id);
								$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>0.0000000,'status'=>'completed']);
								$buyUpdate = $this->BuyExchange->save($buyUpdate);
								
								// buy log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = 0.0000000;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
								
								$remainingStatus = "completed";
								if($remainingAmt>0){
									$remainingStatus = "pending";
								}
								
								$sellUpdate = $this->SellExchange->get($sell_id);
								$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>$remainingAmt,
																							'status'=>$remainingStatus]);
								$sellUpdate = $this->SellExchange->save($sellUpdate);
								
								// sell log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $remainingAmt;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $remainingStatus;		
								$addLog = $this->Users->addlog($logArr);
								
							}
							else {
								
								$buyerAddAmt = $sell_spend_amount * $buy_per_price;
								$sellerAddAmt = $sell_spend_amount;	
								
								
								// buy log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'from';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $buy_status;		
								$addLog = $this->Users->addlog($logArr);
								
								// sell log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'to';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $sell_status;		
								$addLog = $this->Users->addlog($logArr);
								
								
															
								$insertData = [];
								$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
								$insertData['spend_amount'] = $sell_spend_amount;
								$insertData['get_amount'] = $sell_spend_amount;
								$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
								$insertData['buy_exchange_id'] = $buy_id;
								$insertData['sell_exchange_id'] = $sell_id;
								$insertData['spend_per_price'] = $sell_per_price;	
								$insertData['get_per_price'] = $buy_per_price;	
								$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
								$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
								$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
								$exchangeHistroyId = $exhangeSave->id; 
								
								
								// exchange  log
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $exchangeHistroyId;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'subtract';
								$logArr['table_type'] = 'exchange';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
								$remainingAmt = $buy_get_amount-$sell_spend_amount;
								$buy_get_amount = $remainingAmt;
								
								$remainingStatus = "completed";
								if($remainingAmt>0){
									$remainingStatus = "pending";
								}
								
								$buyUpdate = $this->BuyExchange->get($buy_id);
								$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>$remainingAmt,'status'=>$remainingStatus]);
								$buyUpdate = $this->BuyExchange->save($buyUpdate);
								
								
								// buy log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $remainingAmt;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $remainingStatus;		
								$addLog = $this->Users->addlog($logArr);
								
								
								$sellUpdate = $this->SellExchange->get($sell_id);
								$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>0.0000000,'status'=>'completed']);
								$sellUpdate = $this->SellExchange->save($sellUpdate);
								
								// sell log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = 0.0000000;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
							} 
							
							
							// add btc to when user sell hc start
								
								
								
								
								
								// add Coin in seller account
								$newTransArr = [];
								$newTransArr['user_id']= $seller_user_id;
								$newTransArr['coin_amount']= $sellerAddAmt;
								$newTransArr['cryptocoin_id']= $sell_get_coin_id;
								$newTransArr['exchange_id']= $sell_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'sell_exchange';
								$newTransArr['remark']= 'sell_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created_at']= $cudate;
								$newTransArr['updated_at']= $cudate;
								
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								$transactionId = $addCoinToSellerAccount->id;
								
								$adminFeePercent = 0.50000000;
								$sellerAddAmtGet = $sellerAddAmt*$sell_per_price;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								
								
								// deduct adminFees From Seller Account
								$adminFeesFromSellerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromSellerDeduct,$transactionId,$seller_user_id,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								// add adminFees to Admin
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								
								
								
								
								// add coin in buyer account
								$newTransArr = [];
								$newTransArr['user_id']= $buyer_user_id;
								$newTransArr['coin_amount']= $sellerAddAmt;
								$newTransArr['cryptocoin_id']= $buy_get_coin_id;
								$newTransArr['exchange_id']= $buy_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'buy_exchange';
								$newTransArr['remark']= 'buy_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created_at']= $cudate;
								$newTransArr['updated_at']= $cudate;
								$transactionId = $addCoinToSellerAccount->id;
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								
								
								// deduct adminFees From buyer Account
								$sellerAddAmtGet = $sellerAddAmt*$buy_per_price;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								$adminFeesFromBuyerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromBuyerDeduct,$transactionId,$buyer_user_id,$buy_get_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
							
								// add adminFees to Admin
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$buy_get_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
					}
				}
				
				
				
			}
			
			
			if($getFromType=="sell"){
				
				$sell_get_amount = $volume;
				$sell_get_coin_id = $firstCoinId;	
				$sell_spend_coin_id = $secondCoinId;
				$sell_id = $insertBuyId ;
				$sell_per_price = $perPrice;
				
				$buyData = $this->BuyExchange->find('all',['conditions'=>['status !='=>'completed',
																			'per_price >='=>$sell_per_price,
																			'buy_spend_coin_id '=>$sell_get_coin_id,
																			'buy_get_coin_id '=>$sell_spend_coin_id
																			]
															])
															->hydrate(false)
															->toArray();
				
				if(!empty($buyData)){
					$timeStmp = time();
					
					
					foreach($buyData as $singleBuy){
						$realtedId =uniqid(); 
						
					
						$buyer_user_id = $singleBuy['buyer_user_id'];
						$buy_spend_amount = $singleBuy['buy_spend_amount'];
						$buy_spend_coin_id = $singleBuy['buy_spend_coin_id'];
						$buy_per_price = $singleBuy['per_price'];
						$buy_get_amount = $singleBuy['buy_get_amount'];
						$buy_get_coin_id = $singleBuy['buy_get_coin_id'];
						$buy_status = $singleBuy['status'];
						$buy_Fees = $singleBuy['buy_fees'];
						$buy_id = $singleBuy['id'];
						
						
							if($sell_spend_amount > $buy_get_amount){
								
								$buyerAddAmt = $buy_get_amount;
								$sellerAddAmt = $buy_get_amount * $sell_per_price;		
								
								//$soldHcAmount = $buy_hc_amount;
								
								// sell log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'from';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $sell_status;		
								$addLog = $this->Users->addlog($logArr);
								
							
								// buy log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'to';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $buy_status;		
								$addLog = $this->Users->addlog($logArr);
							
								
								$insertData = [];
								//$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
								$insertData['spend_cryptocoin_id'] = $buy_spend_coin_id;
								$insertData['spend_amount'] = $buy_get_amount;
								$insertData['get_amount'] = $buy_get_amount;
								$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
								$insertData['buy_exchange_id'] = $buy_id;
								$insertData['sell_exchange_id'] = $sell_id;
								$insertData['spend_per_price'] = $sell_per_price;	
								$insertData['get_per_price'] = $buy_per_price;	
								$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
								$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
								$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
								$exchangeHistroyId = $exhangeSave->id;
								
								
								// exchange  log
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $exchangeHistroyId;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'subtract';
								$logArr['table_type'] = 'exchange';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
					
								
								
								$remainingAmt = $sell_spend_amount-$buy_get_amount;
								$buy_get_amount = 0.0000000;
								$buyUpdate = $this->BuyExchange->get($buy_id);
								$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>0.0000000,'status'=>'completed']);
								$buyUpdate = $this->BuyExchange->save($buyUpdate);
								
								// buy log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = 0.0000000;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
								
								$remainingStatus = "completed";
								if($remainingAmt>0){
									$remainingStatus = "pending";
								}
								
								$sellUpdate = $this->SellExchange->get($sell_id);
								$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>$remainingAmt,
																							'status'=>$remainingStatus]);
								$sellUpdate = $this->SellExchange->save($sellUpdate);
								
								// sell log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $remainingAmt;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'sell';		
								$logArr['status'] = $remainingStatus;		
								$addLog = $this->Users->addlog($logArr);
								
							}
							else {
								
								$buyerAddAmt = $sell_spend_amount * $buy_per_price;
								$sellerAddAmt = $sell_spend_amount;	
								
								
								// buy log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $buy_get_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'from';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $buy_status;		
								$addLog = $this->Users->addlog($logArr);
								
								// sell log before exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'to';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $sell_status;		
								$addLog = $this->Users->addlog($logArr);
								
								
															
								$insertData = [];
								$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
								$insertData['spend_amount'] = $sell_spend_amount;
								$insertData['get_amount'] = $sell_spend_amount;
								$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
								$insertData['buy_exchange_id'] = $buy_id;
								$insertData['sell_exchange_id'] = $sell_id;
								$insertData['spend_per_price'] = $sell_per_price;	
								$insertData['get_per_price'] = $buy_per_price;	
								$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
								$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
								$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
								$exchangeHistroyId = $exhangeSave->id; 
								
								
								// exchange  log
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $sell_spend_amount;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $exchangeHistroyId;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'subtract';
								$logArr['table_type'] = 'exchange';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
								$remainingAmt = $buy_get_amount-$sell_spend_amount;
								$buy_get_amount = $remainingAmt;
								
								$remainingStatus = "completed";
								if($remainingAmt>0){
									$remainingStatus = "pending";
								}
								
								$buyUpdate = $this->BuyExchange->get($buy_id);
								$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>$remainingAmt,'status'=>$remainingStatus]);
								$buyUpdate = $this->BuyExchange->save($buyUpdate);
								
								
								// buy log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = $remainingAmt;
								$logArr['cryptocoin_id'] = $buy_get_coin_id;
								$logArr['table_id'] = $buy_id;
								$logArr['per_price'] = $buy_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'buy';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = $remainingStatus;		
								$addLog = $this->Users->addlog($logArr);
								
								
								$sellUpdate = $this->SellExchange->get($sell_id);
								$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>0.0000000,'status'=>'completed']);
								$sellUpdate = $this->SellExchange->save($sellUpdate);
								
								// sell log after exchange
								$logArr = [];
								$logArr['related_id'] = $realtedId;
								$logArr['amount'] = 0.0000000;
								$logArr['cryptocoin_id'] = $sell_spend_coin_id;
								$logArr['table_id'] = $sell_id;
								$logArr['per_price'] = $sell_per_price;
								$logArr['type'] = 'remaining';
								$logArr['table_type'] = 'sell';
								$logArr['is_greater'] = 'buy';		
								$logArr['status'] = 'completed';		
								$addLog = $this->Users->addlog($logArr);
								
							} 
							
							
							// add btc to when user sell hc start
								
								
								
								
								
								// add Coin in seller account
								$newTransArr = [];
								$newTransArr['user_id']= $seller_user_id;
								$newTransArr['coin_amount']= $sellerAddAmt;
								$newTransArr['cryptocoin_id']= $sell_get_coin_id;
								$newTransArr['exchange_id']= $sell_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'sell_exchange';
								$newTransArr['remark']= 'sell_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created_at']= $cudate;
								$newTransArr['updated_at']= $cudate;
								
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								$transactionId = $addCoinToSellerAccount->id;
								
								$adminFeePercent = 0.50000000;
								$sellerAddAmtGet = $sellerAddAmt*$sell_per_price;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								
								
								// deduct adminFees From Seller Account
								$adminFeesFromSellerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromSellerDeduct,$transactionId,$seller_user_id,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								// add adminFees to Admin
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								
								
								
								
								// add coin in buyer account
								$newTransArr = [];
								$newTransArr['user_id']= $buyer_user_id;
								$newTransArr['coin_amount']= $sellerAddAmt;
								$newTransArr['cryptocoin_id']= $buy_get_coin_id;
								$newTransArr['exchange_id']= $buy_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'buy_exchange';
								$newTransArr['remark']= 'buy_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created_at']= $cudate;
								$newTransArr['updated_at']= $cudate;
								$transactionId = $addCoinToSellerAccount->id;
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								
								
								// deduct adminFees From buyer Account
								$sellerAddAmtGet = $sellerAddAmt*$buy_per_price;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								$adminFeesFromBuyerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromBuyerDeduct,$transactionId,$buyer_user_id,$buy_get_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
							
								// add adminFees to Admin
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$buy_get_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
					}
				}
				
				
				
			}
			
			
			
			
			
			
		} */
		
	}
	
	
	
	public function notCompletedOrderListAjax($firstCoinId,$secondCoinId){
		
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$currentUserId = $this->Auth->user('id');
		
		$buyOrderList = $this->BuyExchange->find('all',['conditions'=>[//'buyer_user_id !='=>$currentUserId,
																		'buy_spend_coin_id '=>$firstCoinId,
																		'buy_get_coin_id '=>$secondCoinId,
																		'buy_get_amount >'=>0,
																	    'status '=>'pending'],
														'fields'=>['sum'=>'sum(buy_get_amount)','per_price'],				
														//'limit' => 10,	
														'group'=>['per_price'],	
														'order' => ['BuyExchange.per_price'=>'desc']])
																	  ->hydrate(false)
																	  ->toArray();
																	  
																	  
															  
																	  
		$sellOrderList = $this->SellExchange->find('all',['conditions'=>[//'seller_user_id !='=>$currentUserId,
																	     'sell_spend_coin_id '=>$secondCoinId,
																	     'sell_get_coin_id '=>$firstCoinId,
																		 'sell_spend_amount >'=>0,
																	     'status '=>'pending'],
														'fields'=>['sum'=>'sum(sell_spend_amount)','per_price'],				 
														//'limit' => 10,
														'group'=>['per_price'],				
														'order' => ['SellExchange.per_price'=>'asc']])
														->hydrate(false)
														->toArray();
		$returnData = [];
		$returnData['buyOrderList'] = array_reverse($buyOrderList);
		$returnData['sellOrderList'] = $sellOrderList;
			
		echo json_encode($returnData); die;		
			
		
	}
	



	public function myOrderListAjax($firstCoinId,$secondCoinId){
		
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$currentUserId = $this->Auth->user('id');
		
		$myBuyOrderList = $this->BuyExchange->find('all',['conditions'=>['buyer_user_id '=>$currentUserId,
																		'buy_spend_coin_id '=>$firstCoinId,
																		'buy_get_coin_id '=>$secondCoinId],
															'limit' => 10,			
														  'order' => ['id'=>'desc']])
																	  ->hydrate(false)
																	  ->toArray();
																	  
		$mySellOrderList = $this->SellExchange->find('all',['conditions'=>['seller_user_id '=>$currentUserId,
																	     'sell_spend_coin_id '=>$secondCoinId,
																	     'sell_get_coin_id '=>$firstCoinId],
															'limit' => 10,			 
															'order' => ['id'=>'desc']])
																	  ->hydrate(false)
																	  ->toArray();
		$returnData = [];
		$returnData['myBuyOrderList'] = $myBuyOrderList;
		$returnData['mySellOrderList'] = $mySellOrderList;
			
		echo json_encode($returnData); die;			
	}	
	
	
/* 	public function deleteMyOrder($tableId,$tableType){
		if ($this->request->is('ajax')) {
			$cudate = date('Y-m-d H:i:s');
			$this->loadModel('Transactions');
			$currentUserId = $this->Auth->user('id');
			
			if($tableType=="buy") {	
				$adminFeePercent = 0.50000000;
				$this->loadModel('BuyExchange');
				$query = $this->BuyExchange->find('all',['conditions'=>['buyer_user_id'=>$currentUserId,'id'=>$tableId,'status'=>'pending']])->hydrate(false)->first();
				if(!empty($query)){
					$buyUpdate = $this->BuyExchange->get($tableId);
					$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['status'=>'deleted']);
					$buyUpdate = $this->BuyExchange->save($buyUpdate);
					
					$getAmount = $query['buy_get_amount'];
					$perPrice = $query['per_price'];
					$spendAmount = $getAmount*$perPrice;
					$exchangeId = $query['id'];
					$userId = $query['buyer_user_id'];
					$cryptocoinId = $query['buy_spend_coin_id'];
					
					
					
					$transactionsUpdate = $this->Transactions->find('all',["conditions"=>["exchange_id"=>$tableId,
																				 "tx_type"=>'buy_exchange',
																				 "remark"=>"reserve for exchange"]])
																				 ->first();
					$transactionsUpdate = $this->Transactions->patchEntity($transactionsUpdate,['remark'=>'reserve_deleted']);
					$transactionsUpdate = $this->Transactions->save($transactionsUpdate);
					$transactionId = $transactionsUpdate->id;
					
					// add spend amount back to user
					$newInsertArr = [];
					$newInsertArr['transaction_id']= $transactionId;
					$newInsertArr['exchange_id']= $exchangeId;
					$newInsertArr['user_id'] = $userId;
					$newInsertArr['cryptocoin_id'] = $cryptocoinId;
					$newInsertArr['coin_amount'] = $spendAmount;
					$newInsertArr['tx_type'] = 'buy_exchange';
					$newInsertArr['remark'] = 'return_on_cancel';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					//
					$getFindExist = $this->Transactions->find('all',['conditions'=>$newInsertArr])->hydrate(false)->first();
					
					if(empty($getFindExist)) {
						// insert data
						$purchaseCoinTransactions=$this->Transactions->newEntity();
						$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
						$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
					}
					
					
					
					
					// add fees back to user
					$spendAmount = $getAmount*$perPrice;
					$spendFees = ($spendAmount*$adminFeePercent)/100;
					$newInsertArr = [];
					$newInsertArr['transaction_id']= $transactionId;
					$newInsertArr['exchange_id']= $exchangeId;
					$newInsertArr['user_id'] = $userId;
					$newInsertArr['cryptocoin_id'] = $cryptocoinId;
					$newInsertArr['coin_amount'] = $spendFees;
					$newInsertArr['tx_type'] = 'buy_exchange';
					$newInsertArr['remark'] = 'fees_return_on_cancel';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					
					$getFindExistFees = $this->Transactions->find('all',['conditions'=>$newInsertArr])->hydrate(false)->first();
					
					if(empty($getFindExistFees)) {
						// insert data
						$purchaseCoinTransactions=$this->Transactions->newEntity();
						$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
						$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
					}
					
					echo 1;
					
				}
				else {
					echo 0;
				}
			}
			if($tableType=="sell") {
				$this->loadModel('SellExchange');
				$query = $this->SellExchange->find('all',['conditions'=>['seller_user_id'=>$currentUserId,'id'=>$tableId,'status'=>'pending']])->hydrate(false)->first();
				if(!empty($query)){
					
					
					
					$sellUpdate = $this->SellExchange->get($tableId);
					$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['status'=>'deleted']);
					$sellUpdate = $this->SellExchange->save($sellUpdate);
					
					
					$spendAmount = $query['sell_spend_amount'];
					$exchangeId = $query['id'];
					$userId = $query['seller_user_id'];
					$cryptocoinId = $query['sell_spend_coin_id'];
					
					
					$transactionsUpdate = $this->Transactions->find('all',["conditions"=>["exchange_id"=>$tableId,
																				 "tx_type"=>'sell_exchange',
																				 "remark"=>"reserve for exchange"]])
																				 ->first();
					$transactionsUpdate = $this->Transactions->patchEntity($transactionsUpdate,['remark'=>'reserve_deleted']);
					$transactionsUpdate = $this->Transactions->save($transactionsUpdate);
					$transactionId = $transactionsUpdate->id;
					// add spend amount back to user
					$newInsertArr = [];
					$newInsertArr['transaction_id']= $transactionId;
					$newInsertArr['exchange_id']= $exchangeId;
					$newInsertArr['user_id'] = $userId;
					$newInsertArr['cryptocoin_id'] = $cryptocoinId;
					$newInsertArr['coin_amount'] = $spendAmount;
					$newInsertArr['tx_type'] = 'sell_exchange';
					$newInsertArr['remark'] = 'return_on_cancel';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					//
					$getFindExist = $this->Transactions->find('all',['conditions'=>$newInsertArr])->hydrate(false)->first();
					
					if(empty($getFindExist)) {
						// insert data
						$purchaseCoinTransactions=$this->Transactions->newEntity();
						$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
						$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
					}
					
					
					
					
					
					echo 1;
				}
				else {
					echo 0;
				}
			}
		}
		die; 
	}
	 */
	
	
	
	
	public function deleteMyOrder($tableId,$tableType){
		if ($this->request->is('ajax')) {
			$cudate = date('Y-m-d H:i:s');
			$this->loadModel('Transactions');
			$currentUserId = $this->Auth->user('id');
			
			if($tableType=="buy") {	
				$adminFeePercent = 0.50000000;
				$this->loadModel('BuyExchange');
				$query = $this->BuyExchange->find('all',['conditions'=>['buyer_user_id'=>$currentUserId,'id'=>$tableId,'status'=>'pending']])->hydrate(false)->first();
				if(!empty($query)){
					$buyUpdate = $this->BuyExchange->get($tableId);
					$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['status'=>'deleted']);
					$buyUpdate = $this->BuyExchange->save($buyUpdate);
					
					$getAmount = $query['buy_get_amount'];
					$perPrice = $query['per_price'];
					$spendAmount = $getAmount*$perPrice;
					$exchangeId = $query['id'];
					$userId = $query['buyer_user_id'];
					$cryptocoinId = $query['buy_spend_coin_id'];
					
					
					
					$transactionsUpdate = $this->Transactions->find('all',["conditions"=>["exchange_id"=>$tableId,
																				 "tx_type"=>'buy_exchange',
																				 "remark"=>"reserve for exchange"]])
																				 ->first();
					if(!empty($transactionsUpdate)){
						$getTxId = $transactionsUpdate['id'];
						$result = $this->Transactions->delete($transactionsUpdate);
						
						// delete admin fees 
						$transactionsFees = $this->Transactions->find('all',["conditions"=>["exchange_id"=>$tableId,
																							"transaction_id"=>$getTxId,		
																							 "tx_type"=>'buy_exchange',
																							 "remark"=>"adminFees"]])
																							 ->first();
						if(!empty($transactionsFees)){
							$result = $this->Transactions->delete($transactionsFees);
						}		
						
					}															 
					echo 1;
				}
				else {
					echo 0;
				}
			}
			if($tableType=="sell") {
				$this->loadModel('SellExchange');
				$query = $this->SellExchange->find('all',['conditions'=>['seller_user_id'=>$currentUserId,'id'=>$tableId,'status'=>'pending']])->hydrate(false)->first();
				if(!empty($query)){
					
					
					
					$sellUpdate = $this->SellExchange->get($tableId);
					$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['status'=>'deleted']);
					$sellUpdate = $this->SellExchange->save($sellUpdate);
					
					/* $getAmount = $query['buy_get_amount'];
					$perPrice = $query['per_price']; */
					$spendAmount = $query['sell_spend_amount'];
					$exchangeId = $query['id'];
					$userId = $query['seller_user_id'];
					$cryptocoinId = $query['sell_spend_coin_id'];
					
					
					$transactionsUpdate = $this->Transactions->find('all',["conditions"=>["exchange_id"=>$tableId,
																				 "tx_type"=>'sell_exchange',
																				 "remark"=>"reserve for exchange"]])
																				 ->first();
					if(!empty($transactionsUpdate)){
						$getTxId = $transactionsUpdate['id'];
						$result = $this->Transactions->delete($transactionsUpdate);
					}						
					
					echo 1;
				}
				else {
					echo 0;
				}
			}
		}
		die; 
	}


	
	public function marketHistory($firstCoinId,$secondCoinId){
		if ($this->request->is('ajax')) {
			$this->loadModel('ExchangeHistory');
			
			$exchangeHistoryList = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																							  ['get_cryptocoin_id'=>$secondCoinId,
																							   'get_amount >'=>0,
																							   'spend_cryptocoin_id'=>$firstCoinId],
																							  ['spend_cryptocoin_id'=>$secondCoinId,
																							   'get_amount >'=>0,
																							   'get_cryptocoin_id'=>$firstCoinId]
																							  ]
																						],	
																		'limit' => 25,			 
																		'order' => ['id'=>'desc']
																		])	
																	  ->hydrate(false)
																	  ->toArray();
																	  
			echo json_encode($exchangeHistoryList); die;
		}
	}
	
	
	
	public function getCurrenPrice($firstCoinId,$secondCoinId){
		if ($this->request->is('ajax')) {
			$this->loadModel('ExchangeHistory');
			$this->loadModel('Cryptocoin');
			$cudate = date('Y-m-d');
			$currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																							  ['get_cryptocoin_id'=>$secondCoinId,
																							   'spend_cryptocoin_id'=>$firstCoinId],
																							  ['spend_cryptocoin_id'=>$secondCoinId,
																							   'get_cryptocoin_id'=>$firstCoinId]
																							  ]
																						],	
																		'limit' => 2,			 
																		'order' => ['id'=>'desc']
																		])	
																	  ->hydrate(false)
																	  ->toArray();
																	  
			$getOneDayBeforePrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																							  ['get_cryptocoin_id'=>$secondCoinId,
																							   'spend_cryptocoin_id'=>$firstCoinId,
																							   'TIMESTAMPDIFF(MINUTE,created_at,NOW()) <= '=>24],
																							  ['spend_cryptocoin_id'=>$secondCoinId,
																							   'get_cryptocoin_id'=>$firstCoinId,
																							   'TIMESTAMPDIFF(MINUTE,created_at,NOW()) <= '=>24]
																							  ]
																						],	
																		'limit' => 1,			 
																		'order' => ['id'=>'desc']
																		])	
																	  ->hydrate(false)
																	  ->first();														  
																	  
			$currentEthVolume = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																							  ['get_cryptocoin_id'=>$secondCoinId,
																							   'spend_cryptocoin_id'=>$firstCoinId,
																							   //'DATE_FORMAT(created_at,"%Y-%m-%d")'=>$cudate,
																							   'TIMESTAMPDIFF(MINUTE,created_at,NOW()) <= '=>24
																							   ],
																							  ['spend_cryptocoin_id'=>$secondCoinId,
																							   'get_cryptocoin_id'=>$firstCoinId,
																						 	   //'DATE_FORMAT(created_at,"%Y-%m-%d")'=>$cudate
																							   'TIMESTAMPDIFF(MINUTE,created_at,NOW()) <= '=>24
																							   ]
																							]
																					],
																		'fields'=>['totalsum'=>'sum(get_amount*get_per_price)','created_at'],	
																		'limit' => 1,
																		//'group'=>['DATE_FORMAT(created_at,"%Y-%m-%d")'],	
																		'order' => ['id'=>'desc']
																		])	
																	  ->hydrate(false)
																	  ->first();
																	  
																	  
			$getRecentMaxMinPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																							  ['get_cryptocoin_id'=>$secondCoinId,
																							   'spend_cryptocoin_id'=>$firstCoinId,
																							 // 'DATE_FORMAT(created_at,"%Y-%m-%d")'=>$cudate,
																							 'TIMESTAMPDIFF(MINUTE,created_at,NOW()) <= '=>24
																							   ],
																							  ['spend_cryptocoin_id'=>$secondCoinId,
																							   'get_cryptocoin_id'=>$firstCoinId,
																						 	  // 'DATE_FORMAT(created_at,"%Y-%m-%d")'=>$cudate,
																							  'TIMESTAMPDIFF(MINUTE,created_at,NOW()) <= '=>24
																							   ]
																							]
																					],
																		'fields'=>['maxprice'=>'max(get_per_price)','minprice'=>'min(get_per_price)'],	
																		'limit' => 1,
																		//'group'=>['DATE_FORMAT(created_at,"%Y-%m-%d")'],	
																		'order' => ['id'=>'desc']
																		])	
																	  ->hydrate(false)
																	  ->first();

			
			/* $sendArr = [];
			$sendArr['current_volume'] = $currentEthVolume['totalsum'];	
			$sendArr['current_price'] = $currentPrice;	
																	  
			echo json_encode($sendArr); die; */
			
			$sendArr = [];
			$sendArr['real_current_price'] = 0;
			$sendArr['onedaybefore_price'] = 0;
			$sendArr['max_price'] = 0;
			$sendArr['min_price'] = 0;
			$sendArr['goto'] = "up";		
			if(!empty($getRecentMaxMinPrice)){
				$sendArr['max_price'] = $getRecentMaxMinPrice['maxprice'];
				$sendArr['min_price'] = $getRecentMaxMinPrice['minprice'];
			}
			
			
			if(!empty($currentEthVolume)){
				$sendArr['current_volume'] = $currentEthVolume['totalsum'];		
			}
			
			if(empty($currentPrice)){
				$currentPrice = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId],'fields'=>['get_per_price'=>'usd_price']])->hydrate(false)->toArray();
				$sendArr['real_current_price'] = $currentPrice[0]['get_per_price'];
			}
			else {
				$sendArr['goto'] = ($currentPrice[0]['get_per_price']==$currentPrice[0]['get_per_price']) ? "up" : "down"; 
				if(count($currentPrice)>1) {
					$sendArr['goto'] = ($currentPrice[0]['get_per_price']>$currentPrice[1]['get_per_price']) ? "up" : "down"; 
				}
				$sendArr['real_current_price'] = $currentPrice[0]['get_per_price'];
			}
			
			if(empty($getOneDayBeforePrice)){
				$sendArr['onedaybefore_price'] = $sendArr['real_current_price'];
			}
			else {
				$sendArr['onedaybefore_price'] = $getOneDayBeforePrice['get_per_price'];
			}
			$sendArr['current_price'] = $currentPrice;	
			$changeInOneDay = (($sendArr['real_current_price']-$sendArr['onedaybefore_price'])/$sendArr['real_current_price'])*100;		
			$sendArr['change_in_one_day'] = $changeInOneDay;
			echo json_encode($sendArr); die;
		}
	}
	
	
	
	
	
	public function exchange($firstCoin,$secondCoin){ 
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Coinpair');
		$cudate = date('Y-m-d H:i:s');
		$authUserId = $this->Auth->user('id');
		try {
		$user = $this->Users->get($this->Auth->user('id'));
		$this->set('user',$user);
		
		$this->set("firstCoin",$firstCoin);
		$this->set("secondCoin",$secondCoin);
		$currentUserId = $this->Auth->user('id');
		$currentParentId = $this->Auth->user('referral_user_id');
		$currentUserName = $this->Auth->user('name');
		$this->set('currentUserName',$currentUserName);
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
		$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
		
		$firstCoinId = $getFirstCoinDetail['id'];
		$secondCoinId = $getSecondCoinDetail['id'];
		
		$this->set("firstCoinId",$firstCoinId);
		$this->set("secondCoinId",$secondCoinId);
		
		$getUserBalance = '';
		// for post request
		if($this->request->is('ajax')){
			$adminFee = 0.50000000;
			$getFromType = $this->request->data['type'];
			
			if($getFromType=="buy"){
				
				$receiveCoins = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				$volume = $receiveCoins;
				
				if(($receiveCoins <= 0) || ($perPrice <= 0)){ echo "Amount Or Price should be positive."; die;
					//$this->Flash->error(__("Amount Or Price should be positive."));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$receiveCoins = round($receiveCoins,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $receiveCoins*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToPay = $totalAmount+$adminFeeAmt; // calculate total amount to pay with admin fee
				
				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$firstCoinId); // check user account balance
				if($getUserBalance < $totalAmtToPay){
					
					die;
					//$this->Flash->error(__("Insufficient ".$firstCoin." in account"));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$newInsertArr = [];
				$newInsertArr['buyer_user_id'] = $currentUserId;
				$newInsertArr['total_buy_spend_amount'] = $totalAmount;
				$newInsertArr['buy_spend_amount'] = $totalAmount;
				$newInsertArr['buy_spend_coin_id'] = $firstCoinId;
				$newInsertArr['per_price'] = $perPrice;
				$newInsertArr['total_buy_get_amount'] = $receiveCoins;
				$newInsertArr['buy_get_amount'] = $receiveCoins;
				$newInsertArr['buy_get_coin_id'] = $secondCoinId;
				$newInsertArr['buy_fees'] = $adminFeeAmt;
				$newInsertArr['status'] = 'pending';
				
				$exchangeTransactions=$this->BuyExchange->newEntity();
				$exchangeTransactions=$this->BuyExchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->BuyExchange->save($exchangeTransactions);
				$exchangeId = $saveData->id;
				$insertBuyId = $exchangeId;
				$insertBuyStatus = $saveData->status;
				$insertBuyUserId = $saveData->buyer_user_id;
				$insertBuySpendAmount = $saveData->buy_spend_amount;
				
				
			} 
			
			if($getFromType=="sell"){
				
				
				
				$volume = $this->request->data['volume'];
				$perPrice = $this->request->data['per_price'];
				
				if(($volume <= 0) || ($perPrice <= 0)){ echo "Amount and Price should be positive"; die;
					/* $this->Flash->error(__("Amount and Price should be positive."));
					return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]); */
				}
				
				$volume = round($volume,8);
				$perPrice = round($perPrice,8);
				$totalAmount = $volume*$perPrice;
				$adminFeeAmt = ($totalAmount*$adminFee)/100;
				$adminFeeAmt = round($adminFeeAmt,8);
				
				$totalAmtToReceive = $totalAmount-$adminFeeAmt; // calculate total amount to pay with admin fee
				
				$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$secondCoinId); // check user account balance
				if($getUserBalance < $volume){
					die;
					//$this->Flash->error(__("Insufficient ".$secondCoin." in account"));
					//return $this->redirect(['prefix'=>'front','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
				}
				
				$newInsertArr = [];
				$newInsertArr['seller_user_id'] = $currentUserId;
				$newInsertArr['total_sell_spend_amount'] = $volume;
				$newInsertArr['sell_spend_amount'] = $volume;
				$newInsertArr['sell_spend_coin_id'] = $secondCoinId;
				$newInsertArr['per_price'] = $perPrice;
				$newInsertArr['total_sell_get_amount'] = $totalAmount;
				$newInsertArr['sell_get_amount'] = $totalAmount;
				$newInsertArr['sell_get_coin_id'] = $firstCoinId;
				$newInsertArr['sell_fees'] = $adminFeeAmt;
				$newInsertArr['status'] = 'pending';
				
				$exchangeTransactions=$this->SellExchange->newEntity();
				$exchangeTransactions=$this->SellExchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->SellExchange->save($exchangeTransactions);
				$exchangeId = $saveData->id;
				$insertSellId = $exchangeId;
				$insertSellerUserId = $currentUserId;
				$insertSellGetAmount = $totalAmount;
				$insertSellStatus = $saveData->status;
			}
			
			
			// after save into exchange table
			if($saveData){
				$exchangeId = $saveData->id;
				
				if($getFromType=="buy"){
					$newInsertArr = [];
					$newTransArr['exchange_id']= $exchangeId;
					$newInsertArr['user_id'] = $currentUserId;
					$newInsertArr['cryptocoin_id'] = $firstCoinId;
					$newInsertArr['coin_amount'] = "-".$totalAmount;
					$newInsertArr['tx_type'] = 'buy_exchange';
					$newInsertArr['exchange_id'] = $exchangeId;
					$newInsertArr['remark'] = 'reserve for exchange';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['description'] = 'buy_button_click';
					$newInsertArr['current_balance'] = $getUserBalance;
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$purchaseCoinTransactions=$this->Transactions->newEntity();
					$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
					$transactionIdNew = $purchaseCoinTransactions->id;
					// deduct admin fee
					
					$adminFeesFromBuyerDeductNew  = "-".$adminFeeAmt;
					$this->Users->adminFees($adminFeesFromBuyerDeductNew,$transactionIdNew,$currentUserId,$firstCoinId,$exchangeId,'',"buy_exchange");
					
					
					/* $adminFeesFromBuyerAddToAdmin  = $adminFeeAmt;
					$this->Users->adminFees($adminFeesFromBuyerAddToAdmin,$transactionIdNew,1,$firstCoinId,$exchangeId,'',"buy_exchange"); */
					
				}
				
				
				if($getFromType=="sell"){
					
					
					$newInsertArr = [];
					$newTransArr['exchange_id']= $exchangeId;
					$newInsertArr['user_id'] = $currentUserId;
					$newInsertArr['cryptocoin_id'] = $secondCoinId;
					$newInsertArr['coin_amount'] = "-".$volume;
					$newInsertArr['tx_type'] = 'sell_exchange';
					$newInsertArr['exchange_id'] = $exchangeId;
					$newInsertArr['remark'] = 'reserve for exchange';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['description'] = 'sell_button_click';
					$newInsertArr['current_balance'] = $getUserBalance;
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$purchaseCoinTransactions=$this->Transactions->newEntity();
					$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
				
					
				}
				
				//$this->Flash->success(__(ucfirst($getFromType)." Order Created Successfully."));
				//return $this->redirect(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
			}
			else {
				//$this->Flash->error(__("Unable to Create ".$getFromType." Order ! Try Again."));
				//return $this->redirect(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]);
			}
			

			// start exchange for current order
			
			if($getFromType=="buy"){
				
				$buyer_user_id = $insertBuyUserId;
				$buy_get_amount = $volume;
				$buy_get_coin_id = 	$secondCoinId;	
				$buy_spend_amount = $insertBuySpendAmount;
				$buy_spend_coin_id = $firstCoinId;
				$buy_id = $insertBuyId ;
				$buy_per_price = $perPrice;
				$buy_status = $insertBuyStatus;
				$buyer_spend_amount = $volume*$perPrice;
				$buyer_parent_id = $currentParentId;
				
				
				
				$sellData = $this->SellExchange->find('all',['conditions'=>['status '=>'pending',
																			'per_price <='=>$buy_per_price,
																			'sell_spend_coin_id '=>$buy_get_coin_id,
																			'sell_get_coin_id '=>$buy_spend_coin_id
																			],
															'order' => ['SellExchange.per_price'=>'asc']				
															])
															->hydrate(false)
															->toArray();
				
				if(!empty($sellData)){
					$timeStmp = time();
					
					
					foreach($sellData as $singleSell){
						$realtedId =uniqid(); 
						$basePerPrice = $singleSell['per_price'];
						
						
						
						$seller_user_id = $singleSell['seller_user_id'];
						$sellerDetails = $this->Users->findById($seller_user_id)->hydrate(false)->first();
						$seller_parent_id = $sellerDetails['referral_user_id'];
						$sell_spend_amount = $singleSell['sell_spend_amount'];
						$sell_spend_coin_id = $singleSell['sell_spend_coin_id'];
						$sell_per_price = $singleSell['per_price'];
						$sell_get_amount = $singleSell['sell_get_amount'];
						$sell_get_coin_id = $singleSell['sell_get_coin_id'];
						$sell_status = $singleSell['status'];
						$sell_Fees = $singleSell['sell_fees'];
						$sell_id = $singleSell['id'];
						$buy_get_amount = $sell_spend_amount;
						
							if($buyer_spend_amount>0){
								if($sell_get_amount > $buyer_spend_amount){
									$buy_get_amount = $buyer_spend_amount/$sell_per_price;
									
									
									
									
									
									
									
									
									
									$mainAmountForExchange = $buy_get_amount;
									$addAmountForBuyer = $buy_get_amount;
									$addAmountForSeller = $buy_get_amount*$basePerPrice;
									// buyer Fess check
									$amountBuyerSpend = $mainAmountForExchange*$basePerPrice;
									$buyerFees = $amountBuyerSpend*($adminFee/100); // calculate buyer fees amount
									$getBuyerBalance = $this->Users->getLocalUserBalance($buyer_user_id,$firstCoinId);
									/* if($getBuyerBalance < $buyerFees){
										$message = "Buyer balance is ".$getBuyerBalance." which is lower than buyer admin fees =>".$buyerFees;
										file_put_contents('exhcange.log', $message.PHP_EOL, FILE_APPEND);
										die;
									} */
									
									
									
									$buyerAddAmt = $buy_get_amount;
									$sellerAddAmt = $buy_get_amount * $basePerPrice;		
									
									//$soldHcAmount = $buy_hc_amount;
									
									// sell log before exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $sell_spend_amount;
									$logArr['cryptocoin_id'] = $sell_spend_coin_id;
									$logArr['table_id'] = $sell_id;
									$logArr['per_price'] = $sell_per_price;
									$logArr['type'] = 'from';
									$logArr['table_type'] = 'sell';
									$logArr['is_greater'] = 'sell';		
									$logArr['click_on'] = $getFromType;		
									$logArr['status'] = $sell_status;		
									$addLog = $this->Users->addlog($logArr);
									
								
									// buy log before exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $buy_get_amount;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $buy_id;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'to';
									$logArr['table_type'] = 'buy';
									$logArr['is_greater'] = 'sell';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $buy_status;		
									$addLog = $this->Users->addlog($logArr);
								
									
									$insertData = [];
									//$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
									$insertData['spend_cryptocoin_id'] = $buy_spend_coin_id;
									$insertData['spend_amount'] = $buy_get_amount;
									$insertData['get_amount'] = $buy_get_amount;
									$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
									$insertData['buy_exchange_id'] = $buy_id;
									$insertData['sell_exchange_id'] = $sell_id;
									$insertData['spend_per_price'] = $sell_per_price;	
									$insertData['get_per_price'] = $basePerPrice;	
									$insertData['extype'] = $getFromType;	
									$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
									$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
									$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
									$exchangeHistroyId = $exhangeSave->id;
									
									
									// exchange  log
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $buy_get_amount;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $exchangeHistroyId;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'subtract';
									$logArr['table_type'] = 'exchange';
									$logArr['is_greater'] = 'sell';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = 'completed';		
									$addLog = $this->Users->addlog($logArr);
						
									
									
									//$remainingAmt = $sell_spend_amount-$buy_get_amount;
									$remainingAmt = $sell_get_amount - $buyer_spend_amount;
									$buy_get_amount = 0.0000000;
									$buyer_spend_amount = 0.0000000;
									$buyUpdate = $this->BuyExchange->get($buy_id);
									$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>0.0000000,'per_price'=>$basePerPrice,'status'=>'completed']);
									$buyUpdate = $this->BuyExchange->save($buyUpdate);
									
									// buy log after exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = 0.0000000;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $buy_id;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'remaining';
									$logArr['table_type'] = 'buy';
									$logArr['is_greater'] = 'sell';
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = 'completed';		
									$addLog = $this->Users->addlog($logArr);
									
									
									$remainingStatus = "completed";
									$newCompletedSellExchangeId = $sell_id;
									if($remainingAmt>0){
										$remainingStatus = "pending";
										
									    $remainingSellerSpendAmt = $mainAmountForExchange;
										$completedSellSpendAmt = $sell_spend_amount-$mainAmountForExchange;
										// create new completed order
										$newInsertArr = [];
										$newInsertArr['seller_user_id'] = $seller_user_id;
										$newInsertArr['sell_exchange_id'] = $sell_id;
										$newInsertArr['total_sell_spend_amount'] = $mainAmountForExchange;
										$newInsertArr['sell_spend_amount'] = $mainAmountForExchange;
										$newInsertArr['sell_spend_coin_id'] = $sell_spend_coin_id;
										$newInsertArr['per_price'] = $sell_per_price;
										$newInsertArr['total_sell_get_amount'] =$mainAmountForExchange*$sell_per_price;
										$newInsertArr['sell_get_amount'] = $mainAmountForExchange*$sell_per_price;
										$newInsertArr['sell_get_coin_id'] = $sell_get_coin_id;
										$newInsertArr['sell_fees'] = ($mainAmountForExchange*$sell_per_price*$adminFee/100);
										$newInsertArr['sell_description'] = "Created From Sell Order";
										$newInsertArr['status'] = 'completed';
										
										$exchangeTransactions=$this->SellExchange->newEntity();
										$exchangeTransactions=$this->SellExchange->patchEntity($exchangeTransactions,$newInsertArr);
										$saveData = $this->SellExchange->save($exchangeTransactions);
										$newCompletedSellExchangeId = $saveData->id;
										
									}
									
									/*************************
									resserve amount calculation start
									For Buyer and Seller
									**************************/
									
									
									if($remainingAmt>0){
									
										// update remaining reserve amount for seller
										$reserveSellUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$sell_id,
																										 'tx_type'=>'sell_exchange',
																										 'remark'=>'reserve for exchange']])
																					->first();
										if(!empty($reserveSellUpdate)) {											
											$reserveSellUpdate = $this->Transactions->patchEntity($reserveSellUpdate,['coin_amount'=>"-".$remainingAmt/$sell_per_price]);
											$reserveSellUpdate = $this->Transactions->save($reserveSellUpdate); 	
										}										
										
									}
									
									// add spend amount for seller
									$newTransArr = [];
									$newTransArr['user_id']= $seller_user_id;
									$newTransArr['coin_amount']= "-".$mainAmountForExchange;
									$newTransArr['cryptocoin_id']= $sell_spend_coin_id;
									$newTransArr['exchange_id']= $newCompletedSellExchangeId;
									$newTransArr['exchange_history_id']= $exchangeHistroyId;
									$newTransArr['tx_type']= 'sell_exchange';
									$newTransArr['remark']= 'reserve_completed';
									$newTransArr['status']= 'completed';
									$newTransArr['description']= 'type is buy add spend amount for seller';
									$newTransArr['created'] = $cudate;
									$newTransArr['updated'] = $cudate;
									
									$resserveSellerCompletedAccount = $this->Transactions->newEntity();
									$resserveSellerCompletedAccount = $this->Transactions->patchEntity($resserveSellerCompletedAccount,$newTransArr);
									$resserveSellerCompletedAccount = $this->Transactions->save($resserveSellerCompletedAccount);
									
									// update remaining reserve amount for buyer
									$reserveBuyUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$buy_id,
																									    'tx_type'=>'buy_exchange',
																									    'remark'=>'reserve for exchange']])
																				->first();
									if(!empty($reserveBuyUpdate)) {											
										$reserveBuyUpdate = $this->Transactions->patchEntity($reserveBuyUpdate,['remark'=>'reserve_completed']);
										$reserveBuyUpdate = $this->Transactions->save($reserveBuyUpdate); 
									}
									
									/*************************
									resserve amount calculation end
									For Buyer and Seller
									*************************/
									
									
									$sellUpdate = $this->SellExchange->get($sell_id);
									if(!empty($sellUpdate)) {	
									    
										$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['total_sell_spend_amount'=>$remainingAmt/$sell_per_price,
																									'sell_spend_amount'=>$remainingAmt/$sell_per_price,
																									'total_sell_get_amount'=>$remainingAmt,
																									'sell_get_amount'=>$remainingAmt,
																									'status'=>$remainingStatus]);
										$sellUpdate = $this->SellExchange->save($sellUpdate);
									}
									
									// sell log after exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $remainingAmt;
									$logArr['cryptocoin_id'] = $sell_spend_coin_id;
									$logArr['table_id'] = $sell_id;
									$logArr['per_price'] = $sell_per_price;
									$logArr['type'] = 'remaining';
									$logArr['table_type'] = 'sell';
									$logArr['is_greater'] = 'sell';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $remainingStatus;		
									$addLog = $this->Users->addlog($logArr);
									
								}
								else {
									
									$addAmountForBuyer = $sell_spend_amount;
									$addAmountForSeller = $sell_spend_amount*$basePerPrice;
									// buyer Fess check
									$mainAmountForExchange = $sell_spend_amount;
									$amountBuyerReceive = $mainAmountForExchange*$basePerPrice;
									$buyerFees = $amountBuyerReceive*($adminFee/100); // calculate buyer fees amount
									$getBuyerBalance = $this->Users->getLocalUserBalance($buyer_user_id,$firstCoinId);
									/* if($getBuyerBalance < $buyerFees){
										 $message = "Buyer balance is ".$getBuyerBalance." which is lower than buyer admin fees =>".$buyerFees;
										file_put_contents('exhcange.log', $message.PHP_EOL, FILE_APPEND);
										die;
									} */
									
									$buyerAddAmt = $sell_spend_amount * $basePerPrice;
									$sellerAddAmt = $sell_spend_amount;	
									
									
									// buy log before exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $buy_get_amount;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $buy_id;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'from';
									$logArr['table_type'] = 'buy';
									$logArr['is_greater'] = 'buy';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $buy_status;		
									$addLog = $this->Users->addlog($logArr);
									
									// sell log before exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $sell_spend_amount;
									$logArr['cryptocoin_id'] = $sell_spend_coin_id;
									$logArr['table_id'] = $sell_id;
									$logArr['per_price'] = $sell_per_price;
									$logArr['type'] = 'to';
									$logArr['table_type'] = 'sell';
									$logArr['is_greater'] = 'buy';
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $sell_status;		
									$addLog = $this->Users->addlog($logArr);
									
									
																
									$insertData = [];
									$insertData['spend_cryptocoin_id'] = $buy_spend_coin_id;
									$insertData['spend_amount'] = $sell_spend_amount;
									$insertData['get_amount'] = $sell_spend_amount;
									$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
									$insertData['buy_exchange_id'] = $buy_id;
									$insertData['sell_exchange_id'] = $sell_id;
									$insertData['spend_per_price'] = $sell_per_price;	
									$insertData['get_per_price'] = $basePerPrice;	
									$insertData['extype'] = $getFromType;	
									$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
									$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
									$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
									$exchangeHistroyId = $exhangeSave->id; 
									
									
									// exchange  log
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $sell_spend_amount;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $exchangeHistroyId;
									$logArr['per_price'] = $sell_per_price;
									$logArr['type'] = 'subtract';
									$logArr['table_type'] = 'exchange';
									$logArr['is_greater'] = 'buy';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = 'completed';		
									$addLog = $this->Users->addlog($logArr);
									
									//$remainingAmt = $buy_get_amount-$sell_spend_amount;
									$remainingAmt = $buyer_spend_amount - $sell_get_amount;
									
									$remainingStatus = "completed";
									$newCompletedBuyExchangeId = $buy_id;
									if($remainingAmt>0){
										$remainingStatus = "pending";
										
										
										
										// create new buy completed order
										$newInsertArr = [];
										$newInsertArr['buyer_user_id'] = $buyer_user_id;
										$newInsertArr['buy_exchange_id'] = $buy_id;
										$newInsertArr['total_buy_spend_amount'] = $sell_spend_amount*$basePerPrice;
										$newInsertArr['buy_spend_amount'] = $sell_spend_amount*$basePerPrice;
										$newInsertArr['buy_spend_coin_id'] = $buy_spend_coin_id;
										$newInsertArr['per_price'] = $basePerPrice;
										$newInsertArr['total_buy_get_amount'] = $sell_spend_amount;
										$newInsertArr['buy_get_amount'] = $sell_spend_amount;
										$newInsertArr['buy_get_coin_id'] = $buy_get_coin_id;
										$newInsertArr['buy_fees'] = ($sell_spend_amount*$basePerPrice*$adminFee/100);
										$newInsertArr['buy_description'] = "Created From Buy Order";
										$newInsertArr['status'] = 'completed';
										
										$exchangeTransactions=$this->BuyExchange->newEntity();
										$exchangeTransactions=$this->BuyExchange->patchEntity($exchangeTransactions,$newInsertArr);
										$saveData = $this->BuyExchange->save($exchangeTransactions);
										$newCompletedBuyExchangeId = $saveData->id;
									}
									
									
									
									/*************************
									resserve amount calculation start
									For Buyer and Seller
									*************************/
									// update remaining reserve amount for buyer
									$reserveSellUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$sell_id,
																										'tx_type'=>'sell_exchange',
																										'remark'=>'reserve for exchange']])
																				->first();
									if(!empty($reserveSellUpdate)) {											
										$reserveSellUpdate = $this->Transactions->patchEntity($reserveSellUpdate,['remark'=>'reserve_completed']);
										$reserveSellUpdate = $this->Transactions->save($reserveSellUpdate);
									}
									 
									if($sell_get_amount == $buyer_spend_amount){
										// update remaining reserve amount for buyer
										$reserveBuyUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$buy_id,
																											'tx_type'=>'buy_exchange',
																											'remark'=>'reserve for exchange']])
																					->first();
										if(!empty($reserveBuyUpdate)) {											
											$reserveBuyUpdate = $this->Transactions->patchEntity($reserveBuyUpdate,['remark'=>'reserve_completed']);
											$reserveBuyUpdate = $this->Transactions->save($reserveBuyUpdate);  
										}
										
										
									}
									else {
										// update remaining reserve amount for buyer
										$reserveBuyUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$buy_id,
																										 'tx_type'=>'buy_exchange',
																										 'remark'=>'reserve for exchange']])
																					->first();
										if(!empty($reserveBuyUpdate)) {											
											$reserveBuyUpdate = $this->Transactions->patchEntity($reserveBuyUpdate,['coin_amount'=>"-".$remainingAmt]);
											$reserveBuyUpdate = $this->Transactions->save($reserveBuyUpdate); 
										}
										
										
										// add spend amount for seller
										$newTransArr = [];
										$newTransArr['user_id']= $buyer_user_id;
										$newTransArr['coin_amount']= "-".$addAmountForBuyer*$basePerPrice;
										$newTransArr['cryptocoin_id']= $buy_spend_coin_id;
										$newTransArr['exchange_id']= $newCompletedBuyExchangeId;
										$newTransArr['exchange_history_id']= $exchangeHistroyId;
										$newTransArr['tx_type']= 'buy_exchange';
										$newTransArr['remark']= 'reserve_completed';
										$newTransArr['status']= 'completed';
										$newTransArr['description']= 'type is buy add spend amount for seller';
										$newTransArr['created'] = $cudate;
										$newTransArr['updated'] = $cudate;
										
										$resserveBuyerCompletedAccount = $this->Transactions->newEntity();
										$resserveBuyerCompletedAccount = $this->Transactions->patchEntity($resserveBuyerCompletedAccount,$newTransArr);
										$resserveBuyerCompletedAccount = $this->Transactions->save($resserveBuyerCompletedAccount);
									}
									$buy_get_amount = $remainingAmt;
									$buyer_spend_amount = $buyer_spend_amount - $sell_get_amount;
									/*************************
									resserve amount calculation end
									For Buyer and Seller
									*************************/
									
									$buyUpdate = $this->BuyExchange->get($buy_id);
									if(!empty($buyUpdate)) {
										
										if($remainingAmt>0){
											$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,[
																									'total_buy_get_amount'=>$remainingAmt/$buy_per_price,
																									'buy_get_amount'=>$remainingAmt/$buy_per_price,
																									'total_buy_spend_amount'=>$remainingAmt,
																									'buy_spend_amount'=>$remainingAmt,
																									'status'=>$remainingStatus]);
											$buyUpdate = $this->BuyExchange->save($buyUpdate);
										}
										else {
											$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,[
																									'buy_get_amount'=>$remainingAmt/$buy_per_price,
																									'buy_spend_amount'=>$remainingAmt,
																									'status'=>$remainingStatus]);
											$buyUpdate = $this->BuyExchange->save($buyUpdate);
										}
									}
									
									
									// buy log after exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $remainingAmt;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $buy_id;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'remaining';
									$logArr['table_type'] = 'buy';
									$logArr['is_greater'] = 'buy';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $remainingStatus;		
									$addLog = $this->Users->addlog($logArr);
									
									
									$sellUpdate = $this->SellExchange->get($sell_id);
									if(!empty($sellUpdate)) {
										$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>0.0000000,'status'=>'completed']);
										$sellUpdate = $this->SellExchange->save($sellUpdate);
									}
									
									// sell log after exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = 0.0000000;
									$logArr['cryptocoin_id'] = $sell_spend_coin_id;
									$logArr['table_id'] = $sell_id;
									$logArr['per_price'] = $sell_per_price;
									$logArr['type'] = 'remaining';
									$logArr['table_type'] = 'sell';
									$logArr['is_greater'] = 'buy';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = 'completed';		
									$addLog = $this->Users->addlog($logArr);
									
								} 
							
							
							// add btc to when user sell hc start
								
								
								
								
								
								// add Coin in seller account
								$newTransArr = [];
								$newTransArr['user_id']= $seller_user_id;
								$newTransArr['coin_amount']= $addAmountForSeller;
								$newTransArr['cryptocoin_id']= $sell_get_coin_id;
								$newTransArr['exchange_id']= $sell_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'sell_exchange';
								$newTransArr['remark']= 'sell_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created'] = $cudate;
								$newTransArr['updated'] = $cudate;
								
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								$transactionId = $addCoinToSellerAccount->id;
								
								$adminFeePercent = 0.50000000;
								//$sellerAddAmtGet = $sellerAddAmt*$sell_per_price;
								$sellerAddAmtGet = $addAmountForSeller;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								
								
								// deduct adminFees From Seller Account
								$adminFeesFromSellerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromSellerDeduct,$transactionId,$seller_user_id,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								// add adminFees to Admin or parent
								$getSellerParent = (!empty($seller_parent_id)) ? $seller_parent_id : 1;
								$this->Users->adminFees($adminFeesAmt,$transactionId,$getSellerParent,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								
								
								
								
								// add coin in buyer account
								$newTransArr = [];
								$newTransArr['user_id']= $buyer_user_id;
								$newTransArr['coin_amount']= $addAmountForBuyer;
								$newTransArr['cryptocoin_id']= $buy_get_coin_id;
								$newTransArr['exchange_id']= $buy_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'buy_exchange';
								$newTransArr['remark']= 'buy_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created'] = $cudate;
								$newTransArr['updated'] = $cudate;
					
								$addCoinToBuyerAccount = $this->Transactions->newEntity();
								$addCoinToBuyerAccount = $this->Transactions->patchEntity($addCoinToBuyerAccount,$newTransArr);
								$addCoinToBuyerAccount = $this->Transactions->save($addCoinToBuyerAccount);
								$transactionId = $addCoinToBuyerAccount->id;
								
								
								// deduct adminFees From buyer Account
								//$sellerAddAmtGet = $sellerAddAmt*$basePerPrice;
								$sellerAddAmtGet = $addAmountForBuyer*$basePerPrice;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								$adminFeesFromBuyerDeduct  = "-".$adminFeesAmt;
								//$this->Users->adminFees($adminFeesFromBuyerDeduct,$transactionId,$buyer_user_id,$buy_spend_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
							
								// add adminFees to Admin / parent
								$getBuyerParent = (!empty($buyer_parent_id)) ? $buyer_parent_id : 1;
								$this->Users->adminFees($adminFeesAmt,$transactionId,$getBuyerParent,$buy_spend_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
								
								
								// return amount if buyer get amount at low price
								
							/* 	if($buy_per_price > $basePerPrice){
									$exchangeMainAmount = $addAmountForBuyer;
									$exchangeMainPrice = $basePerPrice;
									$amoutWithBuyerPrice = $exchangeMainAmount*$buy_per_price;
									$amoutWithSellerPrice = $exchangeMainAmount*$basePerPrice;
									$returnAmtForBuyer = $amoutWithBuyerPrice - $amoutWithSellerPrice;
									
										$newTransArr = [];
										$newTransArr['user_id']= $buyer_user_id;
										$newTransArr['coin_amount']= $returnAmtForBuyer;
										$newTransArr['cryptocoin_id']= $buy_spend_coin_id;
										$newTransArr['exchange_id']= $buy_id;
										$newTransArr['exchange_history_id']= $exchangeHistroyId;
										$newTransArr['tx_type']= 'buy_exchange';
										$newTransArr['remark']= 'return amount';
										$newTransArr['status']= 'completed';
										$newTransArr['created'] = $cudate;
										$newTransArr['updated'] = $cudate;
										
										$addCoinToBuyerAccount = $this->Transactions->newEntity();
										$addCoinToBuyerAccount = $this->Transactions->patchEntity($addCoinToBuyerAccount,$newTransArr);
										$addCoinToBuyerAccount = $this->Transactions->save($addCoinToBuyerAccount);
										
										
										//return fees to buyer
										
										$feesAccordingToBuyerPrice = $addAmountForBuyer*$buy_per_price;
										$feesAccordingToBuyerPrice = ($feesAccordingToBuyerPrice*$adminFeePercent/100);
										$feesAccordingToBasePrice = $adminFeesAmt;
										
										$returnFeeForBuyer = $feesAccordingToBuyerPrice-$feesAccordingToBasePrice;
										
										$newTransArr = [];
										$newTransArr['user_id']= $buyer_user_id;
										$newTransArr['coin_amount']= $returnFeeForBuyer;
										$newTransArr['cryptocoin_id']= $buy_spend_coin_id;
										$newTransArr['exchange_id']= $buy_id;
										$newTransArr['exchange_history_id']= $exchangeHistroyId;
										$newTransArr['tx_type']= 'buy_exchange';
										$newTransArr['remark']= 'return fees';
										$newTransArr['status']= 'completed';
										$newTransArr['created'] = $cudate;
										$newTransArr['updated'] = $cudate;
										
										$addFeesToBuyerAccount = $this->Transactions->newEntity();
										$addFeesToBuyerAccount = $this->Transactions->patchEntity($addFeesToBuyerAccount,$newTransArr);
										$addFeesToBuyerAccount = $this->Transactions->save($addFeesToBuyerAccount);
										
									
								} */
							}	
								
								
								
					}
				}
				
				
				
			}
			
			
			if($getFromType=="sell"){
				
				
				$seller_user_id = $insertSellerUserId;
				$seller_parent_id = $currentParentId;
				$sell_spend_amount = $volume;
				$sell_get_coin_id = $firstCoinId;	
				$sell_spend_coin_id = $secondCoinId;
				$sell_id = $insertSellId ;
				$sell_per_price = $perPrice;
				$sell_get_amount = $insertSellGetAmount;
				$sell_status =  $insertSellStatus;
				
				
				$buyData = $this->BuyExchange->find('all',['conditions'=>['status '=>'pending',
																			'per_price >='=>$sell_per_price,
																			'buy_spend_coin_id '=>$sell_get_coin_id,
																			'buy_get_coin_id '=>$sell_spend_coin_id
																			],
															'order' => ['BuyExchange.per_price'=>'desc']				
															])
															->hydrate(false)
															->toArray();
				
				if(!empty($buyData)){
					$timeStmp = time();
					
					foreach($buyData as $singleBuy){
						$realtedId =uniqid(); 
						
					
						$buyer_user_id = $singleBuy['buyer_user_id'];
						$buyerDetails = $this->Users->findById($buyer_user_id)->hydrate(false)->first();
						$buyer_parent_id = $buyerDetails['referral_user_id'];
						$buy_spend_amount = $singleBuy['buy_spend_amount'];
						$buy_spend_coin_id = $singleBuy['buy_spend_coin_id'];
						$buy_per_price = $singleBuy['per_price'];
						$buy_get_amount = $singleBuy['buy_get_amount'];
						$buy_get_coin_id = $singleBuy['buy_get_coin_id'];
						$buy_status = $singleBuy['status'];
						$buy_Fees = $singleBuy['buy_fees'];
						$buy_id = $singleBuy['id'];
						$basePerPrice = $buy_per_price;	
							
							if($sell_spend_amount>0){
						
								if($sell_spend_amount > $buy_get_amount){
									$addAmountForBuyer = $buy_get_amount;
									$addAmountForSeller = $buy_get_amount*$basePerPrice;
									// buyer Fess check
									$mainAmountForExchange = $buy_get_amount;
									$amountBuyerReceive = $mainAmountForExchange*$basePerPrice;
									$buyerFees = $amountBuyerReceive*($adminFee/100); // calculate buyer fees amount
									$getBuyerBalance = $this->Users->getLocalUserBalance($buyer_user_id,$firstCoinId);
									/* if($getBuyerBalance < $buyerFees){
										$message = "Buyer balance is ".$getBuyerBalance." which is lower than buyer admin fees =>".$buyerFees;
										file_put_contents('exhcange.log', $message.PHP_EOL, FILE_APPEND);
										continue;
									} */
									 
									
									$buyerAddAmt = $buy_get_amount;
									$sellerAddAmt = $buy_get_amount * $buy_per_price;		
									
									//$soldHcAmount = $buy_hc_amount;
									
									// sell log before exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $sell_spend_amount;
									$logArr['cryptocoin_id'] = $sell_spend_coin_id;
									$logArr['table_id'] = $sell_id;
									$logArr['per_price'] = $buy_per_price;
									$logArr['type'] = 'from';
									$logArr['table_type'] = 'sell';
									$logArr['is_greater'] = 'sell';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $sell_status;		
									$addLog = $this->Users->addlog($logArr);
									
								
									// buy log before exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $buy_get_amount;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $buy_id;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'to';
									$logArr['table_type'] = 'buy';
									$logArr['is_greater'] = 'sell';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $buy_status;		
									$addLog = $this->Users->addlog($logArr);
								
									
									$insertData = [];
									//$insertData['spend_cryptocoin_id'] = $sell_spend_coin_id;
									$insertData['spend_cryptocoin_id'] = $buy_spend_coin_id;
									$insertData['spend_amount'] = $buy_get_amount;
									$insertData['get_amount'] = $buy_get_amount;
									$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
									$insertData['buy_exchange_id'] = $buy_id;
									$insertData['sell_exchange_id'] = $sell_id;
									$insertData['spend_per_price'] = $buy_per_price;	
									$insertData['get_per_price'] = $basePerPrice;	
									$insertData['extype'] = $getFromType;	
									$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
									$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
									$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
									$exchangeHistroyId = $exhangeSave->id;
									
									
									// exchange  log
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $buy_get_amount;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $exchangeHistroyId;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'subtract';
									$logArr['table_type'] = 'exchange';
									$logArr['is_greater'] = 'sell';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = 'completed';		
									$addLog = $this->Users->addlog($logArr);
						
									
									
									$remainingAmt = $sell_spend_amount-$buy_get_amount;
									$sell_spend_amount = $remainingAmt;
									$buy_get_amount = 0.0000000;
									$buyUpdate = $this->BuyExchange->get($buy_id);
									if(!empty($buyUpdate)) {
										$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>0.0000000,'status'=>'completed']);
										$buyUpdate = $this->BuyExchange->save($buyUpdate);
									}
									
									// buy log after exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = 0.0000000;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $buy_id;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'remaining';
									$logArr['table_type'] = 'buy';
									$logArr['is_greater'] = 'sell';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = 'completed';		
									$addLog = $this->Users->addlog($logArr);
									
									
									$remainingStatus = "completed";
									if($remainingAmt>0){
										$remainingStatus = "pending";
										
										// create new completed order
										$newInsertArr = [];
										$newInsertArr['seller_user_id'] = $seller_user_id;
										$newInsertArr['sell_exchange_id'] = $sell_id;
										$newInsertArr['total_sell_spend_amount'] = $addAmountForBuyer;
										$newInsertArr['sell_spend_amount'] = $addAmountForBuyer;
										$newInsertArr['sell_spend_coin_id'] = $sell_spend_coin_id;
										$newInsertArr['per_price'] = $buy_per_price;
										$newInsertArr['total_sell_get_amount'] = $addAmountForBuyer*$buy_per_price;
										$newInsertArr['sell_get_amount'] = $addAmountForBuyer*$buy_per_price;;
										$newInsertArr['sell_get_coin_id'] = $sell_get_coin_id;
										$newInsertArr['sell_fees'] = ($addAmountForBuyer*$buy_per_price*$adminFee/100);
										$newInsertArr['sell_description'] = "created from sell order";
										$newInsertArr['status'] = 'completed';
										
										$exchangeTransactions=$this->SellExchange->newEntity();
										$exchangeTransactions=$this->SellExchange->patchEntity($exchangeTransactions,$newInsertArr);
										$saveData = $this->SellExchange->save($exchangeTransactions);
										$newCompletedSellExchangeId = $saveData->id;
									}
									
									/*************************
									resserve amount calculation start
									For Buyer and Seller
									**************************/
									
									
									if($remainingAmt>0){
										// update remaining reserve amount for seller
										$reserveSellUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$sell_id,
																										 'tx_type'=>'sell_exchange',
																										 'remark'=>'reserve for exchange']])
																					->first();
										if(!empty($reserveSellUpdate)) {											
											$reserveSellUpdate = $this->Transactions->patchEntity($reserveSellUpdate,['coin_amount'=>"-".$remainingAmt]);
											$reserveSellUpdate = $this->Transactions->save($reserveSellUpdate); 
										}										
										
									}
									
									// add spend amount for seller
									$newTransArr = [];
									$newTransArr['user_id']= $seller_user_id;
									$newTransArr['coin_amount']= "-".$addAmountForBuyer;
									$newTransArr['cryptocoin_id']= $sell_spend_coin_id;
									$newTransArr['exchange_id']= $newCompletedSellExchangeId;
									$newTransArr['exchange_history_id']= $exchangeHistroyId;
									$newTransArr['tx_type']= 'sell_exchange';
									$newTransArr['remark']= 'reserve_completed';
									$newTransArr['status']= 'completed';
									$newTransArr['description']= 'type is sell add spend amount for seller';
									$newTransArr['created']= $cudate;
									$newTransArr['updated']= $cudate;
									
									$resserveSellerCompletedAccount = $this->Transactions->newEntity();
									$resserveSellerCompletedAccount = $this->Transactions->patchEntity($resserveSellerCompletedAccount,$newTransArr);
									$resserveSellerCompletedAccount = $this->Transactions->save($resserveSellerCompletedAccount);
									
									// update remaining reserve amount for buyer
									$reserveBuyUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$buy_id,
																										'tx_type'=>'buy_exchange',
																										'remark'=>'reserve for exchange']])
																				->first();
									if(!empty($reserveBuyUpdate)) {											
										$reserveBuyUpdate = $this->Transactions->patchEntity($reserveBuyUpdate,['remark'=>'reserve_completed']);
										$reserveBuyUpdate = $this->Transactions->save($reserveBuyUpdate);
									}									
										
									/*************************
									resserve amount calculation end
									For Buyer and Seller
									*************************/
									
									$sellUpdate = $this->SellExchange->get($sell_id);
									if(!empty($sellUpdate)) {	
										$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['total_sell_spend_amount'=>$remainingAmt,
																									'sell_spend_amount'=>$remainingAmt,
																									'total_sell_get_amount'=>$remainingAmt*$sell_per_price,
																									'sell_get_amount'=>$remainingAmt*$sell_per_price,
																									'status'=>$remainingStatus]);
										$sellUpdate = $this->SellExchange->save($sellUpdate);
									}
									
									// sell log after exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $remainingAmt;
									$logArr['cryptocoin_id'] = $sell_spend_coin_id;
									$logArr['table_id'] = $sell_id;
									$logArr['per_price'] = $buy_per_price;
									$logArr['type'] = 'remaining';
									$logArr['table_type'] = 'sell';
									$logArr['is_greater'] = 'sell';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $remainingStatus;		
									$addLog = $this->Users->addlog($logArr);
									
								}
								else {
									$addAmountForBuyer = $sell_spend_amount;
									$addAmountForSeller = $sell_spend_amount*$basePerPrice;
									// buyer Fess check
									$mainAmountForExchange = $sell_spend_amount;
									$amountBuyerReceive = $mainAmountForExchange*$basePerPrice;
									$buyerFees = $amountBuyerReceive*($adminFee/100); // calculate buyer fees amount
									$getBuyerBalance = $this->Users->getLocalUserBalance($buyer_user_id,$firstCoinId);
									/* if($getBuyerBalance < $buyerFees){
										$message = "Buyer balance is ".$getBuyerBalance." which is lower than buyer admin fees =>".$buyerFees;
										file_put_contents('exhcange.log', $message.PHP_EOL, FILE_APPEND);
										continue;
									} */
									
									$buyerAddAmt = $sell_spend_amount * $basePerPrice;
									$sellerAddAmt = $sell_spend_amount;	
									
									
									// buy log before exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $buy_get_amount;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $buy_id;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'from';
									$logArr['table_type'] = 'buy';
									$logArr['is_greater'] = 'buy';
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $buy_status;		
									$addLog = $this->Users->addlog($logArr);
									
									// sell log before exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $sell_spend_amount;
									$logArr['cryptocoin_id'] = $sell_spend_coin_id;
									$logArr['table_id'] = $sell_id;
									$logArr['per_price'] = $buy_per_price;
									$logArr['type'] = 'to';
									$logArr['table_type'] = 'sell';
									$logArr['is_greater'] = 'buy';
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $sell_status;		
									$addLog = $this->Users->addlog($logArr);
									
									
																
									$insertData = [];
									$insertData['spend_cryptocoin_id'] = $buy_spend_coin_id;
									$insertData['spend_amount'] = $sell_spend_amount;
									$insertData['get_amount'] = $sell_spend_amount;
									$insertData['get_cryptocoin_id'] = $buy_get_coin_id;
									$insertData['buy_exchange_id'] = $buy_id;
									$insertData['sell_exchange_id'] = $sell_id;
									$insertData['spend_per_price'] = $buy_per_price;	
									$insertData['get_per_price'] = $basePerPrice;	
									$insertData['extype'] = $getFromType;	
									$ExchangeHistoryEntry  = $this->ExchangeHistory->newEntity();
									$ExchangeHistoryEntry = $this->ExchangeHistory->patchEntity($ExchangeHistoryEntry, $insertData);
									$exhangeSave = $this->ExchangeHistory->save($ExchangeHistoryEntry);
									$exchangeHistroyId = $exhangeSave->id; 
									
									
									// exchange  log
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $sell_spend_amount;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $exchangeHistroyId;
									$logArr['per_price'] = $buy_per_price;
									$logArr['type'] = 'subtract';
									$logArr['table_type'] = 'exchange';
									$logArr['is_greater'] = 'buy';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = 'completed';		
									$addLog = $this->Users->addlog($logArr);
									
									$remainingAmt = $buy_get_amount-$sell_spend_amount;
									
									
									$remainingStatus = "completed";
									$newCompletedBuyExchangeId = $buy_id;
									if($remainingAmt>0){
										$remainingStatus = "pending";
										
										// create new buy completed order
										$newInsertArr = [];
										$newInsertArr['buyer_user_id'] = $buyer_user_id;
										$newInsertArr['buy_exchange_id'] = $buy_id;
										$newInsertArr['total_buy_spend_amount'] = $sell_spend_amount*$buy_per_price;
										$newInsertArr['buy_spend_amount'] = $sell_spend_amount*$buy_per_price;
										$newInsertArr['buy_spend_coin_id'] = $buy_spend_coin_id;
										$newInsertArr['per_price'] = $buy_per_price;
										$newInsertArr['total_buy_get_amount'] = $sell_spend_amount;
										$newInsertArr['buy_get_amount'] = $sell_spend_amount;
										$newInsertArr['buy_get_coin_id'] = $buy_get_coin_id;
										$newInsertArr['buy_fees'] = ($sell_spend_amount*$buy_per_price*$adminFee/100);
										$newInsertArr['buy_description'] = "created from buy order";
										$newInsertArr['status'] = 'completed';
										
										$exchangeTransactions=$this->BuyExchange->newEntity();
										$exchangeTransactions=$this->BuyExchange->patchEntity($exchangeTransactions,$newInsertArr);
										$saveData = $this->BuyExchange->save($exchangeTransactions);
										$newCompletedBuyExchangeId = $saveData->id;
										
									}
									
									
									/*************************
									resserve amount calculation start
									For Buyer and Seller
									*************************/
									// update remaining reserve amount for buyer
									$reserveSellUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$sell_id,
																										'tx_type'=>'sell_exchange',
																										'remark'=>'reserve for exchange']])
																				->first();
									if(!empty($reserveSellUpdate)) {											
										$reserveSellUpdate = $this->Transactions->patchEntity($reserveSellUpdate,['remark'=>'reserve_completed']);
										$reserveSellUpdate = $this->Transactions->save($reserveSellUpdate);
									}
									
									if($sell_spend_amount == $buy_get_amount){
										// update remaining reserve amount for buyer
										$reserveBuyUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$buy_id,
																											'tx_type'=>'buy_exchange',
																											'remark'=>'reserve for exchange']])
																					->first();
										if(!empty($reserveBuyUpdate)) {											
											$reserveBuyUpdate = $this->Transactions->patchEntity($reserveBuyUpdate,['remark'=>'reserve_completed']);
											$reserveBuyUpdate = $this->Transactions->save($reserveBuyUpdate);  
										}
										
										
									}
									else {
										$reserveBuyUpdateId = '';
										// update remaining reserve amount for buyer
										$reserveBuyUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$buy_id,
																										 'tx_type'=>'buy_exchange',
																										 'remark'=>'reserve for exchange']])
																					->first();
										if(!empty($reserveBuyUpdate)) {												
											$reserveBuyUpdate = $this->Transactions->patchEntity($reserveBuyUpdate,['coin_amount'=>"-".($remainingAmt*$buy_per_price)]);
											$reserveBuyUpdate = $this->Transactions->save($reserveBuyUpdate); 
											$reserveBuyUpdateId = $reserveBuyUpdate->id;
											
											
											
											// start new change
											$getPointFivePercent = (($remainingAmt*$buy_per_price)*$adminFee)/100;
											$getPointFivePercentToAdmin = $getPointFivePercent;
											$getPointFivePercent = "-".$getPointFivePercent;
											
											
											//update admin fees from buyer account for completed order
											$reserveBuyUpdateFee = $this->Transactions->find('all',['conditions'=>['transaction_id'=>$reserveBuyUpdateId,
																										 'user_id'=>$buyer_user_id]])
																					->first();
											$reserveBuyUpdateFee = $this->Transactions->patchEntity($reserveBuyUpdateFee,['coin_amount'=>$getPointFivePercent]);
											$reserveBuyUpdateFee = $this->Transactions->save($reserveBuyUpdateFee); 
												
											//update admin fees in admin account 
											/* $reserveBuyUpdateFeeToAdmin = $this->Transactions->find('all',['conditions'=>['transaction_id'=>$reserveBuyUpdateId,
																										 'user_id'=>1]])
																					->first();
											$reserveBuyUpdateFeeToAdmin = $this->Transactions->patchEntity($reserveBuyUpdateFeeToAdmin,['coin_amount'=>$getPointFivePercentToAdmin]);
											$reserveBuyUpdateFeeToAdmin = $this->Transactions->save($reserveBuyUpdateFeeToAdmin); */	
										}
										
										
										// add spend amount for seller
										$newTransArr = [];
										$newTransArr['user_id']= $buyer_user_id;
										$newTransArr['coin_amount']= "-".$addAmountForBuyer*$buy_per_price;
										$newTransArr['cryptocoin_id']= $buy_spend_coin_id;
										$newTransArr['exchange_id']= $newCompletedBuyExchangeId;
										$newTransArr['exchange_history_id']= $exchangeHistroyId;
										$newTransArr['tx_type']= 'buy_exchange';
										$newTransArr['remark']= 'reserve_completed';
										$newTransArr['status']= 'completed';
										$newTransArr['description']= $reserveBuyUpdateId.' is Transactions Id type is sell add spend amount for seller';
										$newTransArr['created']= $cudate;
										$newTransArr['updated']= $cudate;
										
										$resserveBuyerCompletedAccount = $this->Transactions->newEntity();
										$resserveBuyerCompletedAccount = $this->Transactions->patchEntity($resserveBuyerCompletedAccount,$newTransArr);
										$resserveBuyerCompletedAccount = $this->Transactions->save($resserveBuyerCompletedAccount);
										
										
										// start new change
										// subtract admin fees from buyer account for completed order
										$resserveBuyerCompletedAccountFee = (($addAmountForBuyer*$buy_per_price)*$adminFee)/100;
										$resserveBuyerCompletedAccountFeeToAdmin =  $resserveBuyerCompletedAccountFee;
										$resserveBuyerCompletedAccountFee = "-".$resserveBuyerCompletedAccountFee;
										$this->Users->adminFees($resserveBuyerCompletedAccountFee,$resserveBuyerCompletedAccountFeeTxId,$buyer_user_id,$buy_spend_coin_id,'','',"buy_exchange");
										
										// add into admin account
										/* $this->Users->adminFees($resserveBuyerCompletedAccountFeeToAdmin,$resserveBuyerCompletedAccountFeeTxId,1,$buy_spend_coin_id,'','',"buy_exchange"); */
									}
									$buy_get_amount = $remainingAmt;
									/*************************
									resserve amount calculation end
									For Buyer and Seller
									*************************/
									
									$buyUpdate = $this->BuyExchange->get($buy_id);
									if(!empty($buyUpdate)) {
										if($remainingAmt>0){
											$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['total_buy_spend_amount'=>$remainingAmt*$buyUpdate['per_price'],														  'buy_spend_amount'=>$remainingAmt*$buyUpdate['per_price'],
																									 'total_buy_get_amount'=>$remainingAmt,
																									 'buy_get_amount'=>$remainingAmt,	
																									 'status'=>$remainingStatus]);
											$buyUpdate = $this->BuyExchange->save($buyUpdate);

										}
										else {		
											$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>$remainingAmt,'status'=>$remainingStatus]);
											$buyUpdate = $this->BuyExchange->save($buyUpdate);
										}
									}
									
									$sell_spend_amount = 0.00000000;
									// buy log after exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = $remainingAmt;
									$logArr['cryptocoin_id'] = $buy_get_coin_id;
									$logArr['table_id'] = $buy_id;
									$logArr['per_price'] = $basePerPrice;
									$logArr['type'] = 'remaining';
									$logArr['table_type'] = 'buy';
									$logArr['is_greater'] = 'buy';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = $remainingStatus;		
									$addLog = $this->Users->addlog($logArr);
									
									
									$sellUpdate = $this->SellExchange->get($sell_id);
									if(!empty($sellUpdate)) {	
										$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['sell_spend_amount'=>0.0000000,
																									'per_price'=>$basePerPrice,
																									'total_sell_get_amount'=>$sellUpdate['total_sell_spend_amount']*$basePerPrice,
																									'sell_get_amount'=>$sellUpdate['total_sell_spend_amount']*$basePerPrice,
																									'status'=>'completed']);
										$sellUpdate = $this->SellExchange->save($sellUpdate);
									}
									// sell log after exchange
									$logArr = [];
									$logArr['related_id'] = $realtedId;
									$logArr['amount'] = 0.0000000;
									$logArr['cryptocoin_id'] = $sell_spend_coin_id;
									$logArr['table_id'] = $sell_id;
									$logArr['per_price'] = $buy_per_price;
									$logArr['type'] = 'remaining';
									$logArr['table_type'] = 'sell';
									$logArr['is_greater'] = 'buy';	
									$logArr['click_on'] = $getFromType;										
									$logArr['status'] = 'completed';		
									$addLog = $this->Users->addlog($logArr);
									
								} 
							
							
							// add btc to when user sell hc start
								
								
								
								
								
								// add Coin in seller account
								$newTransArr = [];
								$newTransArr['user_id']= $seller_user_id;
								$newTransArr['coin_amount']= $addAmountForSeller;
								$newTransArr['cryptocoin_id']= $sell_get_coin_id;
								$newTransArr['exchange_id']= $sell_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'sell_exchange';
								$newTransArr['remark']= 'sell_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created']= $cudate;
								$newTransArr['updated']= $cudate;
								
								
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								$transactionId = $addCoinToSellerAccount->id;
								$adminFeePercent = 0.50000000;
								//$sellerAddAmtGet = $sellerAddAmt*$sell_per_price;
								$sellerAddAmtGet = $addAmountForSeller;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								
								
								// deduct adminFees From Seller Account
								$adminFeesFromSellerDeduct  = "-".$adminFeesAmt;
								$this->Users->adminFees($adminFeesFromSellerDeduct,$transactionId,$seller_user_id,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								// add adminFees to Admin
								$getSellerParent = (!empty($seller_parent_id)) ? $seller_parent_id: 1;
								$this->Users->adminFees($adminFeesAmt,$transactionId,$getSellerParent,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								
								
								
								
								// add coin in buyer account
								$newTransArr = [];
								$newTransArr['user_id']= $buyer_user_id;
								$newTransArr['coin_amount']= $addAmountForBuyer;
								$newTransArr['cryptocoin_id']= $buy_get_coin_id;
								$newTransArr['exchange_id']= $buy_id;
								$newTransArr['exchange_history_id']= $exchangeHistroyId;
								$newTransArr['tx_type']= 'buy_exchange';
								$newTransArr['remark']= 'buy_exchange';
								$newTransArr['status']= 'completed';
								$newTransArr['created']= $cudate;
								$newTransArr['updated']= $cudate;
								$transactionId = $addCoinToSellerAccount->id;
								$addCoinToSellerAccount = $this->Transactions->newEntity();
								$addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount,$newTransArr);
								$addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
								
								
								// deduct adminFees From buyer Account 
								//$sellerAddAmtGet = $sellerAddAmt*$basePerPrice;
								$sellerAddAmtGet = $addAmountForBuyer*$sell_per_price;;
								$adminFeesAmt = ($sellerAddAmtGet*$adminFeePercent/100);
								$adminFeesFromBuyerDeduct  = "-".$adminFeesAmt;
								//$this->Users->adminFees($adminFeesFromBuyerDeduct,$transactionId,$buyer_user_id,$buy_spend_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
							
								// add adminFees to Admin
								$getBuyerParent = (!empty($buyer_parent_id)) ? $buyer_parent_id: 1;
								$this->Users->adminFees($adminFeesAmt,$transactionId,$getBuyerParent,$buy_spend_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
								
								
								
								// return amount if buyer get amount at low price
								
								/* if($buy_per_price > $basePerPrice){
									$exchangeMainAmount = $addAmountForBuyer;
									$exchangeMainPrice = $basePerPrice;
									$amoutWithBuyerPrice = $exchangeMainAmount*$buy_per_price;
									$amoutWithSellerPrice = $exchangeMainAmount*$basePerPrice;
									$returnAmtForBuyer = $amoutWithBuyerPrice - $amoutWithSellerPrice;
									
										$newTransArr = [];
										$newTransArr['user_id']= $buyer_user_id;
										$newTransArr['coin_amount']= $returnAmtForBuyer;
										$newTransArr['cryptocoin_id']= $buy_spend_coin_id;
										$newTransArr['exchange_id']= $buy_id;
										$newTransArr['exchange_history_id']= $exchangeHistroyId;
										$newTransArr['tx_type']= 'sell_exchange';
										$newTransArr['remark']= 'return amount';
										$newTransArr['status']= 'completed';
										$newTransArr['created_at']= $cudate;
										$newTransArr['updated_at']= $cudate;
										
										$addCoinToBuyerAccount = $this->Transactions->newEntity();
										$addCoinToBuyerAccount = $this->Transactions->patchEntity($addCoinToBuyerAccount,$newTransArr);
										$addCoinToBuyerAccount = $this->Transactions->save($addCoinToBuyerAccount);
									
									//return fees to buyer
										
									$feesAccordingToBuyerPrice = $addAmountForBuyer*$buy_per_price;
									$feesAccordingToBuyerPrice = ($feesAccordingToBuyerPrice*$adminFeePercent/100);
									$feesAccordingToBasePrice = $adminFeesAmt;
									
									$returnFeeForBuyer = $feesAccordingToBuyerPrice-$feesAccordingToBasePrice;
									
									$newTransArr = [];
									$newTransArr['user_id']= $buyer_user_id;
									$newTransArr['coin_amount']= $returnFeeForBuyer;
									$newTransArr['cryptocoin_id']= $buy_spend_coin_id;
									$newTransArr['exchange_id']= $buy_id;
									$newTransArr['exchange_history_id']= $exchangeHistroyId;
									$newTransArr['tx_type']= 'buy_exchange';
									$newTransArr['remark']= 'return fees';
									$newTransArr['status']= 'completed';
									$newTransArr['created_at']= $cudate;
									$newTransArr['updated_at']= $cudate;
									
									$addFeesToBuyerAccount = $this->Transactions->newEntity();
									$addFeesToBuyerAccount = $this->Transactions->patchEntity($addFeesToBuyerAccount,$newTransArr);
									$addFeesToBuyerAccount = $this->Transactions->save($addFeesToBuyerAccount);
									
									
								} */
							}	
								
								
								
					}
				}
				
			}
		}
		}
		catch(Exception $e) {
			$message = "Error : ".$e->getMessage()." at Line No ".$e->getLine()." On date ".date('Y-m-d H:i:s');
			file_put_contents('newexchange.log', $message.PHP_EOL, FILE_APPEND);
		}
		die;
	}
	
	
	
	
	public function getUserBalance($firstCoinId,$secondCoinId){
		if ($this->request->is('ajax')) {
			$this->loadModel('ExchangeHistory');
			$currentUserId = $this->Auth->user('id');
			$getUserFirstCoinBalance = $this->Users->getLocalUserBalance($currentUserId,$firstCoinId); // check user account balance
			$getUserSecondCoinBalance = $this->Users->getLocalUserBalance($currentUserId,$secondCoinId); // check user account balance
				
			
			$returnData = [];
			$returnData['firstCoinBalance'] = $getUserFirstCoinBalance;
			$returnData['secondCoinBalance'] = $getUserSecondCoinBalance;
				
			echo json_encode($returnData); die;
		}
	}
	
	
	
	public function checkExchange($firstCoinId,$secondCoinId){
		if ($this->request->is('ajax')) {
			$this->loadModel('BuyExchange');
			$this->loadModel('SellExchange');
			$currentUserId = $this->Auth->user('id');
			$allBuyOrderList = $this->BuyExchange->find('all',['conditions'=>['buy_spend_coin_id '=>$firstCoinId,
																		 'buy_get_coin_id '=>$secondCoinId,
																		 'status !='=>'deleted']
																		 ])
																	  ->hydrate(false)
																	  ->count();
																	  
			$allSellOrderList = $this->SellExchange->find('all',['conditions'=>['sell_spend_coin_id '=>$secondCoinId,
																			   'sell_get_coin_id '=>$firstCoinId,
																			   'status !='=>'deleted']
																			   ])
																		  ->hydrate(false)
																		  ->count();
			
			$allBuySellOrderCount = $allBuyOrderList+$allSellOrderList;
			$olderOrder = $this->request->session()->read('totalOrder');
			//if($allBuySellOrderCount>$olderOrder){
			if($allBuySellOrderCount!=$olderOrder){
				$this->request->session()->write('totalOrder', $allBuySellOrderCount);
				echo 1;
			}
			else {
				echo 0;
			}
			
		}
		die;
	}
	

	
	public function getMyVolume($firstCoinId,$secondCoinId){
		if ($this->request->is('ajax')) {
			$this->loadModel('Transactions');
			$this->loadModel('BuyExchange');
			$this->loadModel('SellExchange');
			$authUserId = $this->Auth->user('id');
			$startDate = $this->request->data['start_date']." 00:00:00";
			$endDate = $this->request->data['end_date']." 23:59:59";
			$cudate = date('Y-m-d',strtotime(' -1 day' ) );
			$myBuyVolumeSum = 0 ; 
			$myBuyVolume = $this->BuyExchange->find('all',['conditions'=>[ 'BuyExchange.buy_spend_coin_id'=>$firstCoinId,
																			'BuyExchange.buy_get_coin_id'=>$secondCoinId,
																			'DATE(BuyExchange.created_at) >='=>$startDate,
																			'DATE(BuyExchange.created_at) <='=>$endDate, 
																			'BuyExchange.buyer_user_id'=>$authUserId,
																			'BuyExchange.status'=>'completed',
																			],
															//'contain'=>['buytransactions'],
															'group'=>['BuyExchange.buyer_user_id'],
															'fields' => ['buyVolSum'=>'SUM(BuyExchange.total_buy_spend_amount)'],			 
															])	
														  ->hydrate(false)
														  ->first();
														  
			 if(!empty($myBuyVolume['buyVolSum'])){
				$myBuyVolumeSum =$myBuyVolume['buyVolSum'] ; 
			} 											  
					
			$mySellVolumeSum = 0 ; 
			/* $mySellVolume = $this->SellExchange->find('all',['conditions'=>['Transactions.cryptocoin_id'=>$coinId,
																			'Transactions.created <='=>$cudate,
																			'Transactions.created >='=>$cudate,
																			'Transactions.remark'=>'sell_exchange'
																			],	
															'fields' => ['sellVolSum'=>'SUM(Transactions.coin_amount)'],			 
															])	
														  ->hydrate(false)
														  ->toArray(); */
														  
			$mySellVolume = $this->SellExchange->find('all',['conditions'=>['SellExchange.sell_spend_coin_id'=>$secondCoinId,
																			'SellExchange.sell_get_coin_id'=>$firstCoinId,
																			'DATE(SellExchange.created_at) >='=>$startDate,
																			'DATE(SellExchange.created_at) <='=>$endDate, 
																			'SellExchange.seller_user_id'=>$authUserId,
																			'SellExchange.status'=>'completed',
																			],
															//'contain'=>['selltransactions'],
															'group'=>['SellExchange.seller_user_id'],
															'fields' => ['sellVolSum'=>'SUM(SellExchange.total_sell_get_amount)'],			 
															])	
														  ->hydrate(false)
														  ->first();											  
														  
			if(!empty($mySellVolume['sellVolSum'])){
				$mySellVolumeSum =$mySellVolume['sellVolSum'] ; 
			}
				
			
			$sendArr = [];
			$sendArr['myBuyVolumeSum'] = $myBuyVolumeSum;	
			$sendArr['mySellVolumeSum'] = $mySellVolumeSum;	
			$sendArr['totalVolumeSum'] = $myBuyVolumeSum+$mySellVolumeSum;	
																	  
			echo json_encode($sendArr); 
		}
		die;
	}
	


}
