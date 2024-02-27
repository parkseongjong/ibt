<?php
namespace App\View\Helper;
 
use Cake\View\Helper;
use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
 
class CurrentPriceHelper extends Helper {
 
 
	public function getUserPricipalBalance($userId,$cryptoCoinId){
		
		$getUserTransferredCoinSum = 0;
		$this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
		$getUserTransferredCoin = $this->PrincipalWallet->find(); 
		$getUserTransferredCoinCnt = $getUserTransferredCoin
									->select(['sum' => $getUserTransferredCoin->func()->sum('amount')])
									->where(['user_id'=>$userId,
											 'cryptocoin_id'=>$cryptoCoinId,
											 'status'=>'completed'])
									->toArray();
		
		if(!empty($getUserTransferredCoinCnt)){
			$getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
		}
		return $getUserTransferredCoinSum;
	}







     
    public function getCurrentPrice($firstCoinId,$secondCoinId){       
		
		$this->ExchangeHistory = TableRegistry::get("ExchangeHistory");
		$this->Cryptocoin = TableRegistry::get("Cryptocoin");
		$this->Coinpair = TableRegistry::get("Coinpair");
		
		$getCoinPairSingle = $this->Coinpair->find("all",["conditions"=>['OR'=>[
																				  ['coin_first_id'=>$secondCoinId,
																				   'coin_second_id'=>$firstCoinId,
																				   'binance_price'=>'Y',
																				   ],
																				  ['coin_second_id'=>$secondCoinId,
																				   'coin_first_id'=>$firstCoinId,
																				   'binance_price'=>'Y',
																				   ]
																				]
																		]
														])
														->hydrate(false)
														->first();
		if($getCoinPairSingle['binance_price']=="Y"){
			
			return $price = $getCoinPairSingle['pair_price'];
		}
		else {												
			$currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																								  ['get_cryptocoin_id'=>$secondCoinId,
																								   'spend_cryptocoin_id'=>$firstCoinId],
																								  ['spend_cryptocoin_id'=>$secondCoinId,
																								   'get_cryptocoin_id'=>$firstCoinId]
																								  ]
																							],	
																			'limit' => 1,			 
																			'order' => ['id'=>'desc']
																			])	
																		  ->hydrate(false)
																		  ->first();
			if(empty($currentPrice)){
				$currentPrice = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId],'fields'=>['get_per_price'=>'usd_price']])->hydrate(false)->first();
				return $currentPrice['get_per_price'];
			}
			else {
				return $currentPrice['get_per_price'];
			} 
		}
		
		/* $currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																				  ['get_cryptocoin_id'=>$coinId],
																				  ['spend_cryptocoin_id'=>$coinId]
																				  ]
																			],	
															'limit' => 1,			 
															'order' => ['id'=>'desc']
															])	
														  ->hydrate(false)
														  ->first();
		
		if(!empty($currentPrice)){
			return $currentPrice['get_per_price'];
		}
		else {
			return "0.1";
		} */
        //your function code here
    }

    public function getUserTotalBuy($userId){

        $getUserTotalBuyCoinSum = 0;
        $this->BuyExchange = TableRegistry::get("BuyExchange");
        $getUserTotalBuyCoin = $this->BuyExchange->find();
        $getUserTotalBuyCoinCnt = $getUserTotalBuyCoin
            ->select(['sum' => $getUserTotalBuyCoin->func()->sum('buy_spend_amount')])
            ->where(array('buyer_user_id'=>$userId,'status' => 'completed'))
            ->toArray();

        if(!empty($getUserTotalBuyCoinCnt)){
            $getUserTotalBuyCoinSum = $getUserTotalBuyCoinCnt[0]['sum'];
        }
        return $getUserTotalBuyCoinSum;
    }

    public function getUserTotalSell($userId){

        $getUserTotalSellCoinSum = 0;
        $this->SellExchange = TableRegistry::get("SellExchange");
        $getUserTotalSellCoin = $this->SellExchange->find();
        $getUserTotalSellCoinCnt = $getUserTotalSellCoin
            ->select(['sum' => $getUserTotalSellCoin->func()->sum('sell_get_amount')])
            ->where(array('seller_user_id'=>$userId,'status' => 'completed'))
            ->toArray();

        if(!empty($getUserTotalSellCoinCnt)){
            $getUserTotalSellCoinSum = $getUserTotalSellCoinCnt[0]['sum'];
        }
        return $getUserTotalSellCoinSum;
    }

    public function getBuySellAmount($userId){

        $this->Users = TableRegistry::get("Users");

        $buyAmount = $this->Users->getUserTotalBuy($userId);
        $sellAmount = $this->Users->getUserTotalSell($userId);

        $returnArr = [];
        $returnArr['buyAmount'] = isset($buyAmount) ? $buyAmount : 0;
        $returnArr['sellAmount'] = isset($sellAmount) ? $sellAmount : 0;
        // $returnArr['getTotalBuyAndSell'] = isset($getTotalBuyAndSell) ? $getTotalBuyAndSell : 0;


        return $returnArr;
        //your function code here
    }
}
?>