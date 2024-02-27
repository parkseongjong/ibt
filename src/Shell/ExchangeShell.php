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


class ExchangeShell extends Shell
{
    public function main()
    {
		
        $conn = ConnectionManager::get('default');
		$this->ExchangeHistory = TableRegistry::get('ExchangeHistory');
		$this->BuyExchange = TableRegistry::get('BuyExchange');
		$this->SellExchange = TableRegistry::get('SellExchange');
		$this->ExchangeLog = TableRegistry::get('ExchangeLog');
		$this->Transactions = TableRegistry::get('Transactions');
		$this->Agctransactions = TableRegistry::get('Agctransactions');
		$this->Cointransactions = TableRegistry::get('Cointransactions');
		$this->Users = TableRegistry::get('Users');
		$stmt = $conn->execute('SET time_zone = "+5:30";');
		
		$buyData = $this->BuyExchange->find('all',['conditions'=>['status !='=>'completed']])->hydrate(false)->toArray();
		//print_r(count($buyData)); die;
		
		$cudate = date('Y-m-d H:i:s');
		$i=0;
		if(!empty($buyData)){
			foreach($buyData as $singleBuy){
				
				
				
				$buyer_user_id = $singleBuy['buyer_user_id'];
				$buy_spend_coin_id = $singleBuy['buy_spend_coin_id'];
				$buy_spend_amount = $singleBuy['buy_spend_amount'];
				$buy_get_amount = $singleBuy['buy_get_amount'];
				$buy_get_coin_id = $singleBuy['buy_get_coin_id'];
				$buy_per_price = $singleBuy['per_price'];
				$buy_id = $singleBuy['id'];
				$buy_status = $singleBuy['status'];
				
				$sellData = $this->SellExchange->find('all',['conditions'=>['status !='=>'completed',
																			//'seller_user_id !='=>$buyer_user_id,
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
						
						if($buy_get_amount!=0.0000000) {
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
									$remainingStatus = "processing";
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
									$remainingStatus = "processing";
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
			}
		}
		
		die;
		
		
    }
}

?>