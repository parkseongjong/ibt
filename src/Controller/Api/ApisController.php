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
 */
class ApisController extends AppController
{
	
	public function beforeFilter(Event $event)
    {
		 $this->Auth->allow(['getcurrentprice','graphdata','getinfo','getinfo','getticker','getdepth','gettrades']);
		 $this->loadModel('Settings');
		/*  $setting = $this->Settings->find('all',array('fields'=>['module_name','minimum_limit']))->hydrate(false)->toArray();
		 $this->setting = array_column($setting, 'minimum_limit','module_name'); */
	}
	
	
	
	/*
	Api Name - Get Info
	Description - This method provides all the information about currently active pairs, such as the maximum number of digits after the decimal point, the minimum price, the maximum price, the minimum transaction size, whether the pair is hidden, the commission for each pair.
	Fucntion Name - getinfo
	Parameters - 
	Return type - json
	Example - http://livecrypto.exchange/api/3/info
	Return Example - 
						{
						"server_time": 1538111168,
						"pairs": {
							"eth_ram": {
								"decimal_places": 8,
								"min_price": 0.02,
								"max_price": 800,
								"min_amount": 0.02,
								"hidden": 0,
								"fee": 0.5
							},
							"eth_admc": {
								"decimal_places": 8,
								"min_price": 0.02,
								"max_price": 800,
								"min_amount": 0.02,
								"hidden": 0,
								"fee": 0.5
							}
						}
					}
		decimal_places: number of decimals allowed during trading.
		min_price: minimum price allowed during trading.
		max_price: maximum price allowed during trading.
		min_amount: minimum sell / buy transaction size.
		hidden: whether the pair is hidden, 0 or 1.
		fee: commission for this pair.				
	*/	
	
	public function getinfo(){
		
		$returnArr =[];
		$coinPairs = [];
		$this->loadModel('Coinpair');
		$getData = $this->Coinpair->find('all',['conditions'=>['Coinpair.status'=>1],
												'contain'=>['cryptocoin_first','cryptocoin_second']])
												->hydrate(false)->toArray();
		
		if(!empty($getData)){
			foreach($getData as $data) {
				$getPair = strtolower($data['cryptocoin_first']['short_name'])."_".strtolower($data['cryptocoin_second']['short_name']);
				$coinPairs[$getPair]["decimal_places"] = 8; 
				$coinPairs[$getPair]["min_price"] = 0.02; 
				$coinPairs[$getPair]["max_price"] = 800; 
				$coinPairs[$getPair]["min_amount"] = 0.02; 
				$coinPairs[$getPair]["hidden"] = 0; 
				$coinPairs[$getPair]["fee"] = 0.5; 
			}
		}										
		
		$returnArr['server_time']= time();
		$returnArr['pairs']= $coinPairs;
		echo json_encode($returnArr); die;
		
	}



	/*
	Api Name - Get Ticker
	Description - This method provides all the information about currently active pairs, such as: the maximum price, the minimum price, average price, trade volume, trade volume in currency, the last trade, Buy and Sell price. All information is provided over the past 24 hours.
	Fucntion Name - getticker
	Parameters - 
		pair - string
	Return type - json
	Example - http://livecrypto.exchange/api/3/ticker/btc_ram
	Return Example - 
			{
				"server_time": 1538111606,
				"pairs": {
					"eth_ram": {
						"high": "0.19999998",
						"low": "0.01480003",
						"avg": 0.107400005,
						"vol": "30242.20278857",
						"vol_cur": "30242.20278857",
						"last": "0.07499000",
						"buy": 0.07499,
						"sell": 0.0745,
						"updated": 1538111606
					}
				}
			}
					
		high: maximum price.
		low: minimum price.
		avg: average price.
		vol: trade volume.
		vol_cur: trade volume in currency.
		last: the price of the last trade.
		buy: buy price.
		sell: sell price.
		updated: last update of cache.			
	*/		
	public function getticker($coin_pair = null){
		
		$returnArr =[];
		$coinPairs = [];
		$this->loadModel('Coinpair');
		$this->loadModel('Cryptocoin');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		
		$getCoinMainData = $this->Users->checkpair($coin_pair);
		
		$firstCoinId = $getCoinMainData['firstCoinId'];
		$secondCoinId = $getCoinMainData['secondCoinId'];
		
		
		$getData = $this->Coinpair->find('all',['conditions'=>['Coinpair.coin_first_id'=>$firstCoinId,
															   'Coinpair.coin_second_id'=>$secondCoinId,
															   'Coinpair.status'=>1,
															   ]])
												->hydrate(false)->toArray();
		
		if(empty($getData)){
			$returnArr['success']= false;
			$returnArr['error']= true;
			$returnArr['message']= "Invalid Coin pair";
			$returnArr['data']= "";
			echo json_encode($returnArr); die;
		}
		$date = '2012-11-08';
		$day_before = date( 'Y-m-d H:i:s', strtotime(' -50 day' ) );
		if(!empty($getData)){
			foreach($getData as $data) {
				
				$getGrpData = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$secondCoinId,
																					   'spend_cryptocoin_id'=>$firstCoinId,
																					  
																					   ],
																					  ['spend_cryptocoin_id'=>$secondCoinId,
																					   'get_cryptocoin_id'=>$firstCoinId,
																					   ]
																					  ],
																				  'created_at >='=>$day_before
																				],
															 'fields'=>[
																		"last"=>"substring_index(group_concat(ExchangeHistory.get_per_price ORDER BY id DESC SEPARATOR ','), ',', 1)",
																	    "min_price"=>"min(ExchangeHistory.get_per_price)",
																	    "max_price"=>"max(ExchangeHistory.get_per_price)",
																		"vol"=>"sum(get_amount)",
																		"vol_cur"=>"sum(get_amount)",
																		"datecol"=>"created_at",
																		
																	   ],
															//"group"=>["DATE_FORMAT(ExchangeHistory.created_at,'%Y-%m-%d %H')"],
															"order"=>["id"=>"ASC"],
															//"limit"=>50
															])
															->hydrate(false)
															->first();
	
				
				$getBuyPrice = $this->BuyExchange->find('all',['conditions'=>[ 'BuyExchange.buy_spend_coin_id '=>$firstCoinId,
																									'BuyExchange.buy_get_coin_id '=>$secondCoinId,	
																								    'BuyExchange.status !='=>'pending',
																								    'BuyExchange.created_at >='=>$day_before],
																				'fields'=>['BuyExchange.per_price'],
																				"order"=>["id"=>"DESC"],
																				])
																				->hydrate(false)
																				->first();
																				
				$getSellPrice = $this->SellExchange->find('all',['conditions'=>[  'SellExchange.sell_spend_coin_id '=>$secondCoinId,
																									'SellExchange.sell_get_coin_id '=>$firstCoinId,	
																									'SellExchange.status !='=>'pending',
																								    'SellExchange.created_at >='=>$day_before],
																				'fields'=>['SellExchange.per_price'],
																				"order"=>["id"=>"DESC"],
																				])
																				->hydrate(false)
																				->first();																
				
				$getPair = $coin_pair;
				
				$coinPairs[$getPair]["high"] = $getGrpData['max_price']; 
				$coinPairs[$getPair]["low"] = $getGrpData['min_price']; 
				$coinPairs[$getPair]["avg"] = ($getGrpData['max_price']+$getGrpData['min_price'])/2; 
				$coinPairs[$getPair]["vol"] =$getGrpData['vol']; 
				$coinPairs[$getPair]["vol_cur"] = $getGrpData['vol_cur']; 
				$coinPairs[$getPair]["last"] = $getGrpData['last']; 
				$coinPairs[$getPair]["buy"] =  $getBuyPrice['per_price']; 
				$coinPairs[$getPair]["sell"] =  $getSellPrice['per_price']; 
				$coinPairs[$getPair]["updated"] = time();
			}
		}										
		
		$returnArr['server_time']= time();
		$returnArr['pairs']= $coinPairs;
		echo json_encode($returnArr); die;
		
	}
	
	
	/*
	Api Name - Get Depth
	Description - This method provides the information about active orders on the pair. Additionally it accepts an optional GET-parameter limit, which indicates how many orders should be displayed (150 by default). Is set to less than 5000.
	Fucntion Name - getdepth
	Parameters - 
		pair - string
		limit - integer	
	Return type - json
	Example - http://livecrypto.exchange/api/3/depth/eth_ram/10
	Return Example - 
			{
				"server_time": 1538115158,
				"pairs": {
					"eth_ram": {
						"asks": [
							[
								0.05,
								0.005
							],
							[
								3.247455,
								0.0745
							]
						],
						"bids": [
							[
								0.07499,
								0.07499
							],
							[
								0.07499,
								0.07499
							]
						]
					}
				}
			}
					
		asks: Sell orders.
		bids: Buy orders.			
	*/		
	
	public function getdepth($coin_pair = null,$limit = 150){
		$this->loadModel('Coinpair');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$getCoinMainData = $this->Users->checkpair($coin_pair);
		
		$firstCoinId = $getCoinMainData['firstCoinId'];
		$secondCoinId = $getCoinMainData['secondCoinId'];
		
		
		$getData = $this->Coinpair->find('all',['conditions'=>['Coinpair.coin_first_id'=>$firstCoinId,
															   'Coinpair.coin_second_id'=>$secondCoinId,
															   'Coinpair.status'=>1,
															   ]])
												->hydrate(false)->toArray();
		
		if(empty($getData)){
			$returnArr['success']= false;
			$returnArr['error']= true;
			$returnArr['message']= "Invalid Coin pair";
			$returnArr['data']= "";
			echo json_encode($returnArr); die;
		}
		
		if(!empty($getData)){
			foreach($getData as $data) {
				
	
				
				$getBuyPrice = $this->BuyExchange->find('all',['conditions'=>[  'BuyExchange.buy_spend_coin_id '=>$firstCoinId,
																				'BuyExchange.buy_get_coin_id '=>$secondCoinId,	
																				'BuyExchange.status !='=>'completed',],
																'fields'=>['BuyExchange.buy_spend_amount','BuyExchange.per_price'],
																"order"=>["id"=>"DESC"],
																"limit" =>$limit
																])
																->hydrate(false)
																->toArray();
																
				$buyDataArr = [] ; 	
				if(!empty($getBuyPrice)) {
					foreach($getBuyPrice as $getBuyPriceSingle){
						$buyDataArr[] = array($getBuyPriceSingle['buy_spend_amount'],$getBuyPriceSingle['per_price']);	
					}							
				}				
																				
				$getSellPrice = $this->SellExchange->find('all',['conditions'=>['SellExchange.sell_spend_coin_id '=>$secondCoinId,
																				'SellExchange.sell_get_coin_id '=>$firstCoinId,	
																				'SellExchange.status !='=>'completed'],
																'fields'=>['SellExchange.sell_get_amount','SellExchange.per_price'],
																"order"=>["id"=>"DESC"],
																"limit" =>$limit
																])
																->hydrate(false)
																->toArray();
				
				$sellDataArr = [] ; 	
				if(!empty($getSellPrice)) {
					foreach($getSellPrice as $getSellPriceSingle){
						$sellDataArr[] = array($getSellPriceSingle['sell_get_amount'],$getSellPriceSingle['per_price']);	
					}							
				}		
				
				$getPair = $coin_pair;
				
				$coinPairs[$getPair]["asks"] = $sellDataArr; 
				$coinPairs[$getPair]["bids"] = $buyDataArr;
			}
		}										
		
		$returnArr['server_time']= time();
		$returnArr['pairs']= $coinPairs;
		echo json_encode($returnArr); die;
		
	}
	
	
	
	
	
	/*
	Api Name - Get trades
	Description - This method provides the information about the last trades. Additionally it accepts an optional GET-parameter limit, which indicates how many orders should be displayed (150 by default). The maximum allowable value is 5000.
	Fucntion Name - gettades
	Parameters - 
		pair - string
		limit - integer	
	Return type - json
	Example - http://livecrypto.exchange/api/3/depth/eth_ram/10
	Return Example - 
			{
				"server_time": 1538115158,
				"pairs": {
					"eth_ram": {
						"asks": [
							[
								0.05,
								0.005
							],
							[
								3.247455,
								0.0745
							]
						],
						"bids": [
							[
								0.07499,
								0.07499
							],
							[
								0.07499,
								0.07499
							]
						]
					}
				}
			}
					
		asks: Sell orders.
		bids: Buy orders.			
	*/		
	
	public function gettrades($coin_pair = null,$limit = 150){
		$this->loadModel('Coinpair');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$getCoinMainData = $this->Users->checkpair($coin_pair);
		
		$firstCoinId = $getCoinMainData['firstCoinId'];
		$secondCoinId = $getCoinMainData['secondCoinId'];
		
		
		$getData = $this->Coinpair->find('all',['conditions'=>['Coinpair.coin_first_id'=>$firstCoinId,
															   'Coinpair.coin_second_id'=>$secondCoinId,
															   'Coinpair.status'=>1,
															   ]])
												->hydrate(false)->toArray();
		
		if(empty($getData)){
			$returnArr['success']= false;
			$returnArr['error']= true;
			$returnArr['message']= "Invalid Coin pair";
			$returnArr['data']= "";
			echo json_encode($returnArr); die;
		}
		
		if(!empty($getData)){
			foreach($getData as $data) {
				
	
				
				$getBuyPrice = $this->BuyExchange->find('all',['conditions'=>[  'BuyExchange.buy_spend_coin_id '=>$firstCoinId,
																				'BuyExchange.buy_get_coin_id '=>$secondCoinId,	
																				'BuyExchange.status !='=>'completed',],
																'fields'=>['BuyExchange.buy_spend_amount','BuyExchange.per_price'],
																"order"=>["id"=>"DESC"],
																"limit" =>$limit
																])
																->hydrate(false)
																->toArray();
																
				$buyDataArr = [] ; 	
				if(!empty($getBuyPrice)) {
					foreach($getBuyPrice as $getBuyPriceSingle){
						$buyDataArr[] = array($getBuyPriceSingle['buy_spend_amount'],$getBuyPriceSingle['per_price']);	
					}							
				}				
																				
				$getSellPrice = $this->SellExchange->find('all',['conditions'=>['SellExchange.sell_spend_coin_id '=>$secondCoinId,
																				'SellExchange.sell_get_coin_id '=>$firstCoinId,	
																				'SellExchange.status !='=>'completed'],
																'fields'=>['SellExchange.sell_get_amount','SellExchange.per_price'],
																"order"=>["id"=>"DESC"],
																"limit" =>$limit
																])
																->hydrate(false)
																->toArray();
				
				$sellDataArr = [] ; 	
				if(!empty($getSellPrice)) {
					foreach($getSellPrice as $getSellPriceSingle){
						$sellDataArr[] = array($getSellPriceSingle['sell_get_amount'],$getSellPriceSingle['per_price']);	
					}							
				}		
				
				$getPair = $coin_pair;
				
				$coinPairs[$getPair]["asks"] = $sellDataArr; 
				$coinPairs[$getPair]["bids"] = $buyDataArr;
			}
		}										
		
		$returnArr['server_time']= time();
		$returnArr['pairs']= $coinPairs;
		echo json_encode($returnArr); die;
		
	}
	
	
	
	

	
	/*
	Api Name - Get Current Price (in Eth)
	Fucntion Name - getcurrentprice
	Parameters - string (RAM/ADMC)
	Return type - json
	Example - http://livecrypto.local/api/apis/getcurrentprice/ETH
	Return Example - {"success":false,"error":true,"message":"RAM Current Price","data":{"ethprice":"0.07230000"}}
		success : boolen
		error : boolen
		message : string
		data : object/Array
	*/
	public function getcurrentprice($coinName = null){
		$returnArr =[];
		if(empty($coinName)){
			$returnArr['success']= false;
			$returnArr['error']= true;
			$returnArr['message']= "Coin Name is required";
			$returnArr['data']= "";
			echo json_encode($returnArr); die;
		}
		$this->loadModel('Cryptocoin');
		
		$getCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coinName]])->hydrate(false)->first();
		if(empty($getCoinDetail)){
			$returnArr['success']= false;
			$returnArr['error']= true;
			$returnArr['message']= "Invalid Coin Name. Please Select a valid coin";
			$returnArr['data']= "";
			echo json_encode($returnArr); die;
		}
		
		
		
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d');
		$firstCoinId = 2;
		$secondCoinId = $getCoinDetail['id'];
		$getPrice = 0;
		
		
		$baseCoinPriceInUsd = $getCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		
		$currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
		 ['get_cryptocoin_id'=>$secondCoinId,
		 'spend_cryptocoin_id'=>$firstCoinId],
		 ['spend_cryptocoin_id'=>$secondCoinId,
	      'get_cryptocoin_id'=>$firstCoinId]
				 ]
			],	'limit' => 1,			 
			'order' => ['id'=>'desc']
			])	
			 ->hydrate(false)
			->first();
			
		if(!empty($currentPrice)){
			$getPrice = $currentPrice['get_per_price'];
		}														  
		$dataArr = [];
		$dataArr['ethprice'] = number_format($getPrice,8);
		$returnArr['success']= false;
		$returnArr['error']= true;
		$returnArr['message']= $coinName." Current Price";
		$returnArr['data']= $dataArr;
		echo json_encode($returnArr); die;
	}
	
	/*
	Api Name - Get Graph Data
	Fucntion Name - graphdata
	Parameters - string (RAM/ADMC)
	Return type - json
	Example - https://livecrypto.exchange/api/apis/graphdata/RAM
	Return Example - 
		success : boolen
		error : boolen
		message : string
		data : object/Array
	*/
	
	public function graphdata($coinName = null){
		
		$returnArr =[];
		if(empty($coinName)){
			$returnArr['success']= false;
			$returnArr['error']= true;
			$returnArr['message']= "Coin Name is required";
			$returnArr['data']= "";
			echo json_encode($returnArr); die;
		}
		
		$this->loadModel('Cryptocoin');
		$getCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coinName]])->hydrate(false)->first();
		if(empty($getCoinDetail)){
			$returnArr['success']= false;
			$returnArr['error']= true;
			$returnArr['message']= "Invalid Coin Name. Please Select a valid coin";
			$returnArr['data']= "";
			echo json_encode($returnArr); die;
		}
		
		
		$this->loadModel('ExchangeHistory');
		
		$firstCoinId = 2;
		$secondCoinId = $getCoinDetail['id'];
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
															
		if(!empty($getGrpData)){
			$returnArr['success']= true;
			$returnArr['error']= false;
			$returnArr['message']= "Graph Data";
			$returnArr['data']= $getGrpData;
			echo json_encode($returnArr); die;
		}	
		else {
			$returnArr['success']= false;
			$returnArr['error']= true;
			$returnArr['message']= "No data found";
			$returnArr['data']= "";
			echo json_encode($returnArr); die;
		}
	}	
	
	

	/*
	Api Name - Create Buy Order
	Fucntion Name - buyOrder
	Parameters - 
		username - string
		password - string
		coin_name - string (RAM/ADMC)
		amount_to_buy - floatval
		per_price - floatval
	Return type - json
	Example - 
	Return Example -
		success : boolen
		error : boolen
		message : string
		data : object/Array
	*/
	
	public function buyorder(){
		
		
		if($this->request->is('post')){
			$cudate = date('Y-m-d H:i:s');
			$getUsername = $this->request->data['username'];
			$getPassword = $this->request->data['password'];
			$this->loadModel('Users');
			$this->loadModel('Cryptocoin');
			$this->loadModel('BuyExchange');
			$this->loadModel('Transactions');
			$isLoggedIn = $this->Users->validatelogin($getUsername,$getPassword);
			if($isLoggedIn) {
				
				
				$authUserId = $isLoggedIn['id'];
				
				$user = $this->Users->get($authUserId);
				$this->set('user',$user);
				$firstCoin = 'ETH';
				$secondCoin = $this->request->data['coin_name'];
				$currentUserId = $isLoggedIn['id'];
				$currentUserName = $isLoggedIn['name'];
				$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
				$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
				
				$firstCoinId = $getFirstCoinDetail['id'];
				$secondCoinId = $getSecondCoinDetail['id'];
				$getUserBalance = '';
				// for post request
			
				$adminFee = 0.50000000;
					
					$receiveCoins = $this->request->data['amount_to_buy'];
					$perPrice = $this->request->data['per_price'];
					
					$volume = $receiveCoins;
					
					if(($receiveCoins <= 0) || ($perPrice <= 0)){ 
						$returnArr['success']=false;
						$returnArr['error']=true;
						$returnArr['message']="Amount Or Price should be positive.";
						$returnArr['data']="";
						echo json_encode($returnArr);
					}
					
					$receiveCoins = round($receiveCoins,8);
					$perPrice = round($perPrice,8);
					$totalAmount = $receiveCoins*$perPrice;
					$adminFeeAmt = ($totalAmount*$adminFee)/100;
					$adminFeeAmt = round($adminFeeAmt,8);
					
					$totalAmtToPay = $totalAmount+$adminFeeAmt; // calculate total amount to pay with admin fee
					
					$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$firstCoinId); // check user account balance
					if($getUserBalance < $totalAmtToPay){
						$returnArr['success']=false;
						$returnArr['error']=true;
						$returnArr['message']="Insufficient Balance in account.";
						$returnArr['data']="";
						echo json_encode($returnArr);
						die;
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
			
				
				// after save into exchange table
				if($saveData){
					$exchangeId = $saveData->id;
					
						$newInsertArr = [];
						$newTransArr['exchange_id']= $exchangeId;
						$newInsertArr['user_id'] = $currentUserId;
						$newInsertArr['cryptocoin_id'] = $firstCoinId;
						$newInsertArr['coin_amount'] = "-".$totalAmount;
						$newInsertArr['tx_type'] = 'buy_exchange';
						$newInsertArr['exchange_id'] = $exchangeId;
						$newInsertArr['remark'] = 'reserve for exchange';
						$newInsertArr['status'] = 'completed';
						$newInsertArr['description'] = 'buy_button_api';
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
					
						$returnArr['success']=true;
						$returnArr['error']=false;
						$returnArr['message']="Buy Order Created Successfully.";
						$returnArr['data']="";
						echo json_encode($returnArr);
					
						$shell = new ShellDispatcher();
						$output = $shell->run(['cake', 'buyorder',$insertBuyId, $insertBuyStatus, $insertBuyUserId, $insertBuySpendAmount, $firstCoinId, $secondCoinId, $volume, $perPrice]);
		
						die;
					
				}
				else {
					$returnArr['success']=false;
					$returnArr['error']=true;
					$returnArr['message']="Unable to Create Buy Order ! Try Again..";
					$returnArr['data']="";
					echo json_encode($returnArr);
					die;
				}
			}
			else {
				$returnArr['success']=false;
				$returnArr['error']=true;
				$returnArr['message']="Invalid Usernme/Email or Password.";
				$returnArr['data']="";
				echo json_encode($returnArr);
			}
		}
		else {
			$returnArr['success']=false;
			$returnArr['error']=true;
			$returnArr['message']="Invalid Request found";
			$returnArr['data']="";
			echo json_encode($returnArr);
		}
	}
	
	
	
	/*
	Api Name - Create Sell Order
	Fucntion Name - sellorder
	Parameters - 
		username - string
		password - string
		coin_name - string (RAM/ADMC)
		amount_to_sell - floatval
		per_price - floatval
	Return type - json
	Example - 
	Return Example -
		success : boolen
		error : boolen
		message : string
		data : object/Array
	*/
	
	public function sellorder(){
		
		
		if($this->request->is('post')){
			$cudate = date('Y-m-d H:i:s');
			$getUsername = $this->request->data['username'];
			$getPassword = $this->request->data['password'];
			$this->loadModel('Users');
			$this->loadModel('Cryptocoin');
			$this->loadModel('SellExchange');
			$this->loadModel('Transactions');
			$isLoggedIn = $this->Users->validatelogin($getUsername,$getPassword);
			if($isLoggedIn) {
				
				
				$authUserId = $isLoggedIn['id'];
				
				$user = $this->Users->get($authUserId);
				$this->set('user',$user);
				$firstCoin = 'ETH';
				$secondCoin = $this->request->data['coin_name'];
				$currentUserId = $isLoggedIn['id'];
				$currentUserName = $isLoggedIn['name'];
				$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
				$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
				
				$firstCoinId = $getFirstCoinDetail['id'];
				$secondCoinId = $getSecondCoinDetail['id'];
				$getUserBalance = '';
				// for post request
			
				$adminFee = 0.50000000;
					
					$volume = $this->request->data['amount_to_sell'];
					$perPrice = $this->request->data['per_price'];
					
					if(($volume <= 0) || ($perPrice <= 0)){ 
						$returnArr['success']=false;
						$returnArr['error']=true;
						$returnArr['message']="Amount Or Price should be positive.";
						$returnArr['data']="";
						echo json_encode($returnArr);
					}
					
					$volume = round($volume,8);
					$perPrice = round($perPrice,8);
					$totalAmount = $volume*$perPrice;
					$adminFeeAmt = ($totalAmount*$adminFee)/100;
					$adminFeeAmt = round($adminFeeAmt,8);
					
					$totalAmtToReceive = $totalAmount-$adminFeeAmt; // calculate total amount to pay with admin fee
					
					$getUserBalance = $this->Users->getLocalUserBalance($currentUserId,$secondCoinId); // check user account balance
					if($getUserBalance < $volume){
						$returnArr['success']=false;
						$returnArr['error']=true;
						$returnArr['message']="Insufficient Balance in account.";
						$returnArr['data']="";
						echo json_encode($returnArr);
						die;
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
			
				
				// after save into exchange table
				if($saveData){
					$exchangeId = $saveData->id;
					
						$newInsertArr = [];
						$newTransArr['exchange_id']= $exchangeId;
						$newInsertArr['user_id'] = $currentUserId;
						$newInsertArr['cryptocoin_id'] = $secondCoinId;
						$newInsertArr['coin_amount'] = "-".$volume;
						$newInsertArr['tx_type'] = 'sell_exchange';
						$newInsertArr['exchange_id'] = $exchangeId;
						$newInsertArr['remark'] = 'reserve for exchange';
						$newInsertArr['status'] = 'completed';
						$newInsertArr['description'] = 'sell_button_api';
						$newInsertArr['current_balance'] = $getUserBalance;
						$newInsertArr['created'] = $cudate;
						$newInsertArr['updated'] = $cudate;
						
						// insert data
						$purchaseCoinTransactions=$this->Transactions->newEntity();
						$purchaseCoinTransactions=$this->Transactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
						$purchaseCoinTransactions=$this->Transactions->save($purchaseCoinTransactions);
					
						$returnArr['success']=true;
						$returnArr['error']=false;
						$returnArr['message']="Sell Order Created Successfully.";
						$returnArr['data']="";
						echo json_encode($returnArr);
						
						$shell = new ShellDispatcher();
						$output = $shell->run(['cake', 'sellorder',$insertSellId, $insertSellStatus, $insertSellerUserId, $insertSellGetAmount, $firstCoinId, $secondCoinId, $volume, $perPrice]);
						die;
					
				}
				else {
					$returnArr['success']=false;
					$returnArr['error']=true;
					$returnArr['message']="Unable to Create Sell Order ! Try Again..";
					$returnArr['data']="";
					echo json_encode($returnArr);
					die;
				}
			}
			else {
				$returnArr['success']=false;
				$returnArr['error']=true;
				$returnArr['message']="Invalid Usernme/Email or Password.";
				$returnArr['data']="";
				echo json_encode($returnArr);
			}
		}
		else {
			$returnArr['success']=false;
			$returnArr['error']=true;
			$returnArr['message']="Invalid Request found";
			$returnArr['data']="";
			echo json_encode($returnArr);
		}
	}
	
	
	
	
	
	
	
	public function setting()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = '';
			if(isset($this->request->data['user_id']) &&  isset($this->request->data['notify']))
			{
				$user_id  = $this->request->data['user_id'];
				$user = $this->Users->get($user_id);
				if(!empty($user)){
					$user->notify = $this->request->data['notify'];				
					$this->Users->save($user);
					$error = false;
					$code = 0;
					if($this->request->data['notify'] =='Y') $message = 'Notification turned ON';
					else  $message = 'Notification turned OFF';
				}else{
					$message = 'No record found';
				}
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function contactUs()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = ''; 
			
			$this->loadModel('ContactUs');
			$contact = $this->ContactUs->newEntity();		
			$contact = $this->ContactUs->patchEntity($contact, $this->request->data);	
			if($this->ContactUs->save($contact))
			{

				$error = false;
				$code = 0;
				$message= 'Thanking you for contacting with us, we will shorlty get back to you.';
			}
			else
			{
				

				foreach($contact->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						$message = $error_text;
						break 2;
					} 
				}

			}
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
			
		}
	
	}
	
	
	public function profile()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = '';
			if(isset($this->request->data['user_id']) )
			{
				$user_data = $this->Users->find()->where(['id'=>$this->request->data['user_id']])->hydrate(false)->first(); 
				if(empty($user_data)) $message = 'No record found';
				else
				{
					$user_id = $user_data['id'];
					$this->loadModel('BlockUsers');
					$query1 = $this->BlockUsers->find();
					$blocking = $query1->select(['count' => $query1->func()->count('*')])
								->where(['user_id'=>$user_id])->hydrate(false)->toArray();
					
					
					$response = $this->ratingSale($user_id);
					$response['id'] =$user_id;
					$response['full_name'] =$user_data['first_name']." ".$user_data['last_name'];
					$response['profile_type'] = $this->profile_type($user_data['profile_type']);
					$response['image'] =$this->userImage($user_data['image'],'thumb');
					$response['since'] =date('d.m.Y',strtotime($user_data['created']->format('Y-m-d H:i:s')));
					$response['block'] = $blocking[0]['count'];
					$this->loadModel('Followings');
					$response['favorite'] = $this->Followings->find('all' ,['contain'=>['post'],'conditions'=>['post.enabled'=>'Y','Followings.user_id'=>$user_id,'favorite_status'=>'Y']])->count();
					
					$this->loadModel('FollowingUsers');
					$query1 = $this->FollowingUsers->find();
					$following = $query1->select(['count' => $query1->func()->count('*')])
								->where(['user_id'=>$user_id])->hydrate(false)->toArray();
					$response['following'] = $following[0]['count'];
					$response['completion'] = $this->profileCompletion($user_data);
					//pr($response);die;
					$error = false;	
					$code = 0;		
				
				}
				
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	
	}
	
	public function verifyAccount()
	{
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset($this->request->data['otp']) && isset($this->request->data['user_id']))
			{
				$user_id =  $this->request->data['user_id'];
				$otp =  $this->request->data['otp'];
				$user  = $this->Users->find('all',array('conditions'=>array('id'=>$user_id,'otp'=>$otp,'is_verified'=>'N')))->hydrate(false)->first();
				
				if(!empty($user))
				{
					if($user['otp'] != $otp) $message = 'Otp is incorrect';
					else
					{
						
						$user = $this->Users->get($user_id);
						$user->is_verified = 'Y';
						$this->Users->save($user);
						$this->addWalletAmount($user_id,'S',$this->setting['registration_point'], $this->setting['amount_expire_in_days']);	
						if($user['referral_user_id'] != ''){
							
							$this->addWalletAmount($user['referral_user_id'],'R',$this->setting['referral_registration'], $this->setting['amount_expire_in_days']);	
						}
						$error = false;
						$code = 0;
						$message = 'Account verified successfully.';
					
					}
				}
				else $message = 'No Record Found';
				
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function updateToken()
	{
		if($this->request->is(['post','put']))
    	{
			$error = $message= $response = ''; $code=1;
			if(isset( $this->request->data['user_id'] ) && isset($this->request->data['device_token']) )
			{
				$users  = $this->Users->get($this->request->data['user_id']);
				$users->device_token = $this->request->data['device_token'];
				$this->Users->save($users);
				$error = false;
				$code = 0;
				$message =  'Device token update';
			}else{
				$error = true;
				$message =  'Incomplete Data';
			}
				
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function signup()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			 $message= $response = ''; 
			$email_exit = $this->Users->find('all',array('fields'=>['id','is_verified'],'conditions'=>array('email'=>$this->request->data['email'])))->first();
			$number_exit = $this->Users->find('all',array('fields'=>['id','is_verified'],'conditions'=>array('phone_number'=>$this->request->data['phone_number'])))->first();
			if(!empty($email_exit) && $email_exit['is_verified'] == 'Y')  $message= 'Email already exist';
			else if(!empty($number_exit && $number_exit['is_verified'] == 'Y'))  $message= 'Phone number exist';
			else{
				if(!empty($email_exit)) $user = $this->Users->get($email_exit['id']);	
				else if(!empty($number_exit)) $user = $this->Users->get($number_exit['id']);	
				else $user = $this->Users->newEntity();			
				
				$user = $this->Users->patchEntity($user, $this->request->data);
				
				if(!$user->errors())
				{
					$random_code = $this->getNewReferralCode();
					$user->referral_code = $random_code;
					//$otp = rand ( 1000 , 9999 );
					$otp =1234;
					$user->otp =$otp;
					$user->raw_password =$this->request->data['password'];
					$refer_user = $this->Users->find('all',array('fields'=>['id'],'conditions'=>array('referral_code'=>$this->request->data['refer_from'])))->first();
					if(!empty($refer_user)){
						$user->referral_user_id = $refer_user['id'];
					}
					
				}
				
				if($saved_user = $this->Users->save($user))
				{
					$user_id =  $saved_user->id;
					$response = $this->Users->find('all',array('conditions'=>array('id'=>$user_id)))->first();
					if(SENDMAIL == 1)
					{
						// success email
						$this->loadModel('EmailTemplate');
						$template = $this->EmailTemplate->find('all',array('conditions'=>array('title'=>'signup')))->hydrate(false)->first();
						$template['description'] = str_replace('{FULL_NAME}', $response['title']." ".$response['first_name']." ".$response['last_name'], $template['description']);
						$template['description'] = str_replace('{OTP}', $otp, $template['description']);
						$template['description'] = str_replace('{PROJECT}', $this->setting['project_title'], $template['description']);
						
						$email = new Email('default');
						$email->from([$this->setting['mail_email_address'] => $this->setting['mail_email_name']] )
						->to($this->request->data['email'])
						->subject($template['subject'])
						->emailFormat('html')
						->send($template['description']); 
					}
					
					$error = false;
					$code = 0;
					$message= 'Congratulations!! You have registered successfully, Please verify your account';
				}
				else
				{
					$error = true;
					
					foreach($user->errors() as $field_key =>  $error_data)
					{
						foreach($error_data as $error_text)
						{
							$message = $error_text;
							break 2;
						} 
					}
					
				}
				
			}
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'response'=> $response,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function logout()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset( $this->request->data['user_id'] ) && isset( $this->request->data['device_token'] ))
			{
				$user_data = $this->Users->find('all',array('conditions'=>array('id'=>$this->request->data['user_id'])))->first();
				if(!empty($user_data))
				{
					if($user_data['device_token'] ==$this->request->data['device_token']  ) $user_data['device_token'] ='';
					$user_data['last_login'] =date('Y-m-d');
					$user_data['is_logged'] =0;
					$this->Users->save($user_data);
					$error = false;
					$code = 0;
					$message =  'Logged out successfully.';
				}else $message =  'User not found.';
			
			}
			else $message =  'Incomplete data.';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('error','code','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('error','code','message','response')));
			
		}
		
	}
	
	public function login()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = ''; 
			
			if(isset( $this->request->data['username'] ) && isset($this->request->data['password']) && isset($this->request->data['device_token']))
			{
				$user = $this->Users->find('all',array('conditions'=>array('OR'=>[['email'=>$this->request->data['username']],['phone_number'=>$this->request->data['username']]] )))->first();
				if(empty($user)) $message =  'Invalid email or password';
				
				else{
					 $this->request->data['username']=$user['email'];
					 if (Validation::email($this->request->data['username'])) {
						$this->Auth->config('authenticate', [
							'Form' => [
								'fields' => ['username' => 'email']
							]
						]);
						$this->Auth->constructAuthenticate();
						$this->request->data['email'] = $this->request->data['username'];
						unset($this->request->data['username']);
					}

					$user = $this->Auth->identify();
					
					if(empty($user)) $message =  'Invalid email or password';
					else if($user['is_verified'] == 'N' ) $message =  'Your account is not verified';
					else if($user['enabled'] == 'N') $message =  'Your account is blocked';
					else
					{
						$error = false;
						$code = 0;
						$message =  'Logged in successfully.';
						$user_data = $this->Users->find('all',array('conditions'=>array('id'=>$user['id'])))->first();
						$user_data['device_token'] = $this->request->data['device_token'];
						$user_data['device_type'] = $this->request->data['device_type'];
						$this->Users->save($user_data);
						$response = $this->Users->find('all',array('conditions'=>array('id'=>$user['id'])))->first();
						$response['image'] = $this->userImage($user['image'],'thumb');
						$this->set(array('user_id'=>$user['id'],'response'=>$response,'code'=>$code,'error'=>false,'message'=> $message,'_serialize'=>array('error','code','message','response','user_id','emailverfied')));
					}
					
				}
			}
			else  $message =  'Incomplete data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			
			
		}
	}
	
	
	public function forgotPassword()
	{
		if($this->request->is('post'))
		{
			$error =true;$code=1;
			$message= $response = '';
			if( isset( $this->request->data['username'] ))
			{
				$username = $this->request->data['username'];
				$user_record = $this->Users->find()
				->select(['id'])
				->where(['enabled'=>'Y','OR'=>['email'=>$username,'phone_number'=>$username]])->first();
				
				if($user_record && !empty($user_record))
				{
					$error = false;
					$code = 0;
					$message= $user_record['id'];
					

				}
				else $message =  'Mobile number / Email does not exist';
			}
			else $message =  'Incomplete data.';
			
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('user_id'=>$message,'code'=>$code,'error'=>$error,'message'=> 'Success','_serialize'=>array('code','error','message','response','user_id')));
		}
	
	}
	
	
	
	public function changePassword()
    {
    	if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = ''; 
			if( isset( $this->request->data['user_id'] ) && isset( $this->request->data['new_password'] ) && isset( $this->request->data['confirm_password'] ) )
			{
			$users  = $this->Users->get($this->request->data['user_id']);
			$users = $this->Users->patchEntity($users, [
							'password'      => $this->request->data['new_password'],
							'new_password'     => $this->request->data['new_password'],
							'confirm_password'     => $this->request->data['confirm_password']
						],
            			['validate' => 'password']
        		);
			if(!$users->errors())
			{
				$users->raw_password = $this->request->data['new_password'];
			}
			if($this->Users->save($users))
			{
				$error=  false;
				$code = 0;
				$message =  'Password changed successfully';
			}
			else
			{
				foreach($users->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						$message = $error_text;
						break 2;
					} 
					
				}
			}
			
			}else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
		}
    }
    public function arcoRefer()
    {
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			
			if(isset($this->request->data['user_id']))
			{
				$error =false;
				$code = 0;
				$this->loadModel('Transactions');
				$add_arco = $this->Transactions->find()->select(['sub_arco_credit' => $query1->func()->count('*')])
								->where(['type'=>'B','credit_debit'=>'+','user_id'=>$user_id])->hydrate(false)->first();
				$minus_arco = $this->Transactions->find()->select(['sub_arco_debit' => $query1->func()->count('*')])
								->where(['type IN'=>[''],'credit_debit'=>'-','user_id'=>$user_id])->hydrate(false)->first();	
				
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
    public function walletAmount()
    {
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset($this->request->data['user_id']))
			{
				$error =false;
				$code = 0;
				$response['refer'] = $this->arcoWallet($this->request->data['user_id'],'refer');
				$response['arco'] = $this->arcoWallet($this->request->data['user_id'],'arco');
			
			}
			
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	
	}
    public function wallet()
    {
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			
			if(isset($this->request->data['user_id']))
			{
				$error =false;
				$code = 0;
				$this->loadModel('Transactions');
				$response['currency']=$this->setting['currency'];
				$response['refer_point'] = $this->arcoWallet($this->request->data['user_id'],'refer');
				$response['arco_point'] = $this->arcoWallet($this->request->data['user_id'],'arco');
				$transaction = $this->Transactions->find();
				$date = $transaction->func()->date_format([
				   'Transactions.created' => 'literal',
					"'%d %b %Y'" => 'literal'
				]);
				$expire = $transaction->func()->date_format([
				   'Transactions.expire_at' => 'literal',
					"'%d %b %Y'" => 'literal'
				]);
				$expire_date = $transaction->func()->date_format([
				   'Transactions.expire_at' => 'literal',
					"'%Y-%m-%d'" => 'literal'
				]);
				$response['earnings'] = 
				$transaction->select(['booking_id'=>'booking.booking_no','date'=>$date,'amount','type','expire'=>$expire,'expire_date'=>$expire_date,'credit_debit'])
				->contain(['booking'])
				->where(['expire_at >='=>date('Y-m-d'),'Transactions.user_id'=>$this->request->data['user_id']])
				->order(['Transactions.id'=>'desc'])
				->hydrate(false)->toArray(); 
				
				
				$arr = array('S'=>'Join point','R'=>'Refer Bonus','B'=>'Arco Bonus');
			
				foreach($response['earnings'] as $k=>$val)
				{
					$response['earnings'][$k]['currency'] = $response['currency'];
					$response['earnings'][$k]['type'] = $arr[$val['type']];
					if($val['expire_date']<date('Y-m-d')) $response['earnings'][$k]['expire']= "Expired at ".$val['expire'];
					else $response['earnings'][$k]['expire']= "Expires on ".$val['expire'];
					
						
				}
 				
			
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
    
	public function myProfile()
	{
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset($this->request->data['user_id']))
			{
				$id = $this->request->data['user_id'];
				$users  = $this->Users->find('all',array('conditions'=>array('id'=>$id,'access_level_id'=>2,'is_verified'=>'Y','enabled'=>'Y')))->hydrate(false)->first();
				if(!empty($users)){
					$users['image']= $this->userImage($users['image'],'thumb');
					$error = false;
					$code = 0;
					$response =  $users;
				} 
				else $message = 'No Record Found';
			
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function editUser()
    {
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset($this->request->data['user_id']))
			{
				$id = $this->request->data['user_id'];
				$users  = $this->Users->find('all',array('conditions'=>array('id'=>$id,'access_level_id'=>2)))->hydrate(false)->first();
				if(!empty($users))
				{
					$email_exit = $this->Users->find('all',array('fields'=>['id'],'conditions'=>array('email'=>$this->request->data['email'],'is_verified'=>'Y','id !='=>$id)))->first();
					$number_exit = $this->Users->find('all',array('fields'=>['id','is_verified'],'conditions'=>array('phone_number'=>$this->request->data['phone_number'],'is_verified'=>'Y','id !='=>$id)))->first();
					if(!empty($email_exit))  $message= 'Email already exist';
					else if(!empty($number_exit))  $message= 'Phone number exist';
					else
					{
						$users  = $this->Users->get($id);
						$user = $this->Users->patchEntity($users,$this->request->data);
						$before_image = $user->image;
						if($saveUser = $this->Users->save($user))
						{ 
							$user = $this->Users->get($id);
							if(isset($this->request->data['image']) && $_FILES['image']['tmp_name'] !='')
							{
								$filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['image']['name']);
								$ext = pathinfo($filename, PATHINFO_EXTENSION);
								$filename = basename($filename, '.' . $ext) . time() . '.jpg';
								if ($this->uploadImage($_FILES['image']['tmp_name'], $_FILES['image']['type'], 'uploads/user_image/', $filename)){
									$this->createThumbnail($filename, 'uploads/user_image', 'uploads/user_thumb',200,200); 
									$user->image  = $filename;
								}
								else  $user->image = $before_image;
							}else  $user->image = $before_image;
							$this->Users->save($user);
							if($this->request->data['change_password'] != '')
							{
								$users  = $this->Users->get($id);
								$users = $this->Users->patchEntity($users, [
										'password'      => $this->request->data['change_password'],
										'new_password'     => $this->request->data['change_password'],
										'confirm_password'     => $this->request->data['change_password']
									],
									['validate' => 'password']
								);
								if(!$users->errors())
								{
									$users->raw_password = $this->request->data['change_password'];
								}
								$this->Users->save($users);
							}
							$response  = $this->Users->find('all',array('conditions'=>array('id'=>$id)))->hydrate(false)->first();
							$response['image'] = $this->userImage($response['image'],'thumb'); 
							$message = "Profile updated successfully";
							$error = false;
							$code = 0;
						}
						else
						{
							foreach($user->errors() as $field_key =>  $error_data)
							{
								foreach($error_data as $error_text)
								{
									$message = $error_text;
									break 2;
								} 
								
							}
						}
						
					}
				}else $message = 'No Record Found';
			}else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	
	}
	

	
}

	
