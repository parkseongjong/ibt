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


class SellOrderShell extends Shell
{
    public function main($insertSellId, $insertSellStatus, $insertSellerUserId, $insertSellGetAmount, $firstCoinId, $secondCoinId, $volume, $perPrice)
    {
					
		
				
				$cudate = date('Y-m-d H:i:s');
				$adminFee = 0.50000000;
				$getFromType = "buy";
				$this->loadModel('BuyExchange');
				$this->loadModel('SellExchange');
				$this->loadModel('Transactions');			
				$this->loadModel('Users');			
				$this->loadModel('ExchangeHistory');
				$seller_user_id = $insertSellerUserId;
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
										$newInsertArr['total_sell_spend_amount'] = $addAmountForBuyer;
										$newInsertArr['sell_spend_amount'] = $addAmountForBuyer;
										$newInsertArr['sell_spend_coin_id'] = $sell_spend_coin_id;
										$newInsertArr['per_price'] = $buy_per_price;
										$newInsertArr['total_sell_get_amount'] = $addAmountForBuyer*$buy_per_price;
										$newInsertArr['sell_get_amount'] = $addAmountForBuyer*$buy_per_price;;
										$newInsertArr['sell_get_coin_id'] = $sell_get_coin_id;
										$newInsertArr['sell_fees'] = ($addAmountForBuyer*$buy_per_price*$adminFee/100);
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
									$buy_get_amount = $remainingAmt;
									
									$remainingStatus = "completed";
									$newCompletedBuyExchangeId = $buy_id;
									if($remainingAmt>0){
										$remainingStatus = "pending";
										
										// create new buy completed order
										$newInsertArr = [];
										$newInsertArr['buyer_user_id'] = $buyer_user_id;
										$newInsertArr['total_buy_spend_amount'] = $sell_spend_amount*$buy_per_price;
										$newInsertArr['buy_spend_amount'] = $sell_spend_amount*$buy_per_price;
										$newInsertArr['buy_spend_coin_id'] = $buy_spend_coin_id;
										$newInsertArr['per_price'] = $buy_per_price;
										$newInsertArr['total_buy_get_amount'] = $sell_spend_amount;
										$newInsertArr['buy_get_amount'] = $sell_spend_amount;
										$newInsertArr['buy_get_coin_id'] = $buy_get_coin_id;
										$newInsertArr['buy_fees'] = ($sell_spend_amount*$buy_per_price*$adminFee/100);
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
										// update remaining reserve amount for buyer
										$reserveBuyUpdate = $this->Transactions->find('all',['conditions'=>['exchange_id'=>$buy_id,
																										 'tx_type'=>'buy_exchange',
																										 'remark'=>'reserve for exchange']])
																					->first();
										if(!empty($reserveBuyUpdate)) {												
											$reserveBuyUpdate = $this->Transactions->patchEntity($reserveBuyUpdate,['coin_amount'=>"-".($remainingAmt*$buy_per_price)]);
											$reserveBuyUpdate = $this->Transactions->save($reserveBuyUpdate); 
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
										$newTransArr['created']= $cudate;
										$newTransArr['updated']= $cudate;
										
										$resserveBuyerCompletedAccount = $this->Transactions->newEntity();
										$resserveBuyerCompletedAccount = $this->Transactions->patchEntity($resserveBuyerCompletedAccount,$newTransArr);
										$resserveBuyerCompletedAccount = $this->Transactions->save($resserveBuyerCompletedAccount);
									}
									
									/*************************
									resserve amount calculation end
									For Buyer and Seller
									*************************/
									
									$buyUpdate = $this->BuyExchange->get($buy_id);
									if(!empty($buyUpdate)) {	
										$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['buy_get_amount'=>$remainingAmt,'status'=>$remainingStatus]);
										$buyUpdate = $this->BuyExchange->save($buyUpdate);
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
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$sell_get_coin_id,$sell_id,$exchangeHistroyId,"sell_exchange");
							
								
								
								
								
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
								
							
								// add adminFees to Admin
								$this->Users->adminFees($adminFeesAmt,$transactionId,1,$buy_spend_coin_id,$buy_id,$exchangeHistroyId,"buy_exchange");
								
							}	
								
								
								
					}
				}
				
		
	}
}

?>