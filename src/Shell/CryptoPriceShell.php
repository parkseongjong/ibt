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


class CryptoPriceShell extends Shell
{
    public function main()
    {
		Log::write('debug',  'cashierest api 호출 시작');
		$getCoinList = [];
		$this->loadModel('Cryptocoin');
		$this->loadModel('Coinpair');
		$coinPairList = $this->Coinpair->find("all",['conditions'=>["binance_price"=>'Y'],
													 'contain'=>['cryptocoin_first','cryptocoin_second']])->hydrate(false)->toArray();
		if(!empty($coinPairList)){
			foreach($coinPairList as $coinPair){
				$getCoinList[strtoupper($coinPair['cryptocoin_first']['short_name'])] = $coinPair;
			}
		}
	
				
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.cashierest.com/V2/PbV12/TickerMarket?Market=KRW',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl); 


		curl_close($curl);
		
		$bom = pack('H*','EFBBBF');
		$response = preg_replace("/^$bom/", '', $response);
		$response = json_decode($response,true);

		foreach($response["ReturnData"] as $singelResp){
			$coinName = $singelResp["CoinCode"];
			if(isset($getCoinList[$coinName]) && !empty($getCoinList[$coinName])){
				echo $coinPairId = $getCoinList[$coinName]["id"];
				
				$price = $singelResp['NowPrice'];
				$pricePercent = $singelResp['DealRise'];
				$updateIt = $this->Coinpair->updateAll(['pair_price'=>$price,'price_percent'=>$pricePercent],['id'=>$coinPairId]);
			}
		}
		Log::write('debug',  'cashierest api 호출 종료');
		
		/* $conn = ConnectionManager::get('default');
		$this->Cryptocoin = TableRegistry::get('Cryptocoin');
		$this->Coinpair = TableRegistry::get('Coinpair');
		$coinPairList = $this->Coinpair->find("all",['conditions'=>["binance_price"=>'Y'],
													 'contain'=>['cryptocoin_first','cryptocoin_second']])->hydrate(false)->toArray();
		if(!empty($coinPairList)){
			foreach($coinPairList as $coinPair){
				$pairName = strtoupper($coinPair['cryptocoin_first']['short_name'])."B".strtoupper($coinPair['cryptocoin_second']['short_name']);
				$coinPairId = $coinPair['id'];
				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://api.binance.com/api/v3/ticker/price?symbol=".$pairName,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
				));

				$response = curl_exec($curl);

				curl_close($curl);
			
				 
				$response = json_decode($response,true);
				if(isset($response['price']) && !empty($response['price'])){
					$price = $response['price'];
					$updateIt = $this->Coinpair->updateAll(['pair_price'=>$price],['id'=>$coinPairId]);
					
				}
				
				
				
				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://api.binance.com/api/v3/ticker/24hr?symbol='.$pairName,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'GET',
				));

				$responseNew = curl_exec($curl);

				curl_close($curl);
				$responseNew = json_decode($responseNew,true);
				$pricePercent = $responseNew['priceChangePercent'];
				$updateItNew = $this->Coinpair->updateAll(['price_percent'=>$pricePercent],['id'=>$coinPairId]);
			}
		} */
		//$this->loadModel('Settings');
		
		/* $curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.binance.com/api/v3/ticker/price?symbol=ETHBKRW",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($curl);

		curl_close($curl);
	
		 
		$response = json_decode($response,true);
		$price = $response['price'];
			
		$btcp = $getbtc [0]['price_usd'];
		$ethp = $geteth [0]['price_usd']; 
		
		if(!empty($btcp)){
		
			$arr = [];
			$arr['usd_price'] = $btcp;
			$crypto = $this->Cryptocoin->get(1);
			$crypto = $this->Cryptocoin->patchEntity($crypto, $arr);
			$this->Cryptocoin->save($crypto);
		}
		
		if(!empty($ethp)){
			
			$arr = [];
			$arr['usd_price'] = $ethp;
			$crypto = $this->Cryptocoin->get(2);
			$crypto = $this->Cryptocoin->patchEntity($crypto, $arr);
			$this->Cryptocoin->save($crypto);
		} */
		
		
		die();
		

		
    }
}

?>