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

	*********************

	- 작성자 : 이충현
	- 최초 작성일 : 2021-04-28
	- Pubic API 개발
	- 최근 수정일 : 2021-04-28 : 주석 및 에러코드 수정

	*********************
 */
class PublicApiController extends AppController
{
	
	public function beforeFilter(Event $event)
    {
		 $this->Auth->allow(['ticker','orderbook','transactionHistory','getCoinData']);
	}
	/* 1. ticker - 현재가 정보 제공 */
	public function ticker($coin_name = 'ctc_krw'){
		$respArr = [];
		$get_coin_info = $this->getCoinInfo($coin_name);
		if($get_coin_info['status'] != '200'){
			$respArr = $get_coin_info;
		}
		if($get_coin_info['status'] == '200'){
			$order_currency_check = $get_coin_info['order_currency_coin'];
			$payment_currency_check = $get_coin_info['payment_currency_coin'];

			if(!empty($order_currency_check) && !empty($payment_currency_check)){
				$firstCoinId = $order_currency_check['id'];
				$secondCoinId = $payment_currency_check['id'];
				$data = $this->getTicker($firstCoinId,$secondCoinId);
				$respArr = ['status'=>'200','message'=> "success",'data'=>$data];
			}
		}
		//크로스 도메인 허용
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Max-Age: 86400');
		header('Access-Control-Allow-Headers: x-requested-with');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		
		echo json_encode($respArr,JSON_UNESCAPED_UNICODE); die;
	}
	/* 2. orderbook - 매수/매도 정보 제공 */
	public function orderbook($coin_name = 'ctc_krw'){
		$respArr = [];
		$count = 30;
		if(!empty($this->request->query['count'])){
			$count = $this->request->query['count'];
			if($count > 30){
				$count = 30;
			}
		}
		$get_coin_info = $this->getCoinInfo($coin_name);
		if($get_coin_info['status'] != '200'){
			$respArr = $get_coin_info;
		}
		if($get_coin_info['status'] == '200'){
			$order_currency_check = $get_coin_info['order_currency_coin'];
			$payment_currency_check = $get_coin_info['payment_currency_coin'];

			if(!empty($order_currency_check) && !empty($payment_currency_check)){
				$firstCoinId = $order_currency_check['id'];
				$secondCoinId = $payment_currency_check['id'];
				$data = $this->getOrderBook($secondCoinId,$firstCoinId,$count);
				$respArr = ['status'=>'200','message'=> "success",'data'=>$data];
			}
		}
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Max-Age: 86400');
		header('Access-Control-Allow-Headers: x-requested-with');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		echo json_encode($respArr,JSON_UNESCAPED_UNICODE); die;
	}
	/* 3. transaction-history - 거래체결 완료 내역 */
	public function transactionHistory($coin_name = 'ctc_krw'){
		$respArr = [];
		$count = 20;
		if(!empty($this->request->query['count'])){
			$count = $this->request->query['count'];
			if($count > 100){
				$count = 100;
			}
		}
		$get_coin_info = $this->getCoinInfo($coin_name);
		if($get_coin_info['status'] != '200'){
			$respArr = $get_coin_info;
		}
		if($get_coin_info['status'] == '200'){
			$order_currency_check = $get_coin_info['order_currency_coin'];
			$payment_currency_check = $get_coin_info['payment_currency_coin'];

			if(!empty($order_currency_check) && !empty($payment_currency_check)){
				$firstCoinId = $order_currency_check['id'];
				$secondCoinId = $payment_currency_check['id'];
				$data = $this->getTransactionHistory($firstCoinId,$secondCoinId,$count);
				$respArr = ['status'=>'200','message'=> "success",'data'=>$data];
			}
		}
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Max-Age: 86400');
		header('Access-Control-Allow-Headers: x-requested-with');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		echo json_encode($respArr,JSON_UNESCAPED_UNICODE); die;
	}
	/* 코인 '_' 기준으로 나누기 */
	public function separateCoin($coin_name){
		$sendArr = [];
		if($coin_name == null || $coin_name == ''){
			$coin_name = 'ctc_krw';
		}
		if(strpos($coin_name,'_') === false){
			$coin_name = $coin_name.'_krw';
		}
		$order_currency = strtoupper(explode('_',$coin_name)[0]); // 주문 통화
		$payment_currency = strtoupper(explode('_',$coin_name)[1]); // 결제 통화
		if($payment_currency == '' || $payment_currency == null){
			$payment_currency = 'KRW';
		}
		$sendArr = ['order_currency'=>$order_currency,'payment_currency'=>$payment_currency];
		return $sendArr;
	}
	/* 코인 정보 확인 후 리턴 */
	public function getCoinInfo($coin_name){
		$sendArr = [];
		$separate_coin = $this->separateCoin($coin_name);
		$order_currency = $separate_coin['order_currency'];
		$payment_currency = $separate_coin['payment_currency'];
		$order_currency_coin = $this->getCoin($order_currency);
		$payment_currency_coin = $this->getCoin($payment_currency);
		if(empty($order_currency_coin) || empty($payment_currency_coin)){
			$this->cause_error(5301);die;
		}
		if(!empty($order_currency_coin) && !empty($payment_currency_coin)){
			$sendArr = ['status'=>'200','order_currency_coin'=>$order_currency_coin,'payment_currency_coin'=>$payment_currency_coin];
		}
		return $sendArr;
	}
	/* 코인 정보 가져오기 */
	public function getCoin($coin_short_name){
		$this->loadModel('Cryptocoin');
		$get_coin = $this->Cryptocoin->find()->select(['id','name','short_name'])->where(['status'=>1,'OR'=>[['name'=>$coin_short_name],['short_name'=>$coin_short_name]]])->first();
		return $get_coin;
	}
	/* 1-1. ticker - 실제 현재가 데이터 */
	public function getTicker($firstCoinId,$secondCoinId){
		$this->loadModel('Cryptocoin');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Coinpair');
		$currentPrice = $this->ExchangeHistory->find('all',[
			'conditions'=>[
				'OR'=>[
					['get_cryptocoin_id'=>$secondCoinId,'spend_cryptocoin_id'=>$firstCoinId],
					['spend_cryptocoin_id'=>$secondCoinId,'get_cryptocoin_id'=>$firstCoinId]]],
			'limit' => 2,
			'order' => ['id'=>'desc']])->hydrate(false)->toArray();
		$findCoinPair = $this->Coinpair->find('all',[
			'conditions'=>[
				'OR'=>[
					['coin_first_id'=>$firstCoinId,'coin_second_id'=>$secondCoinId,
					['coin_second_id'=>$secondCoinId,'coin_first_id'=>$firstCoinId]]]
				]])->hydrate(false)->first();
		if(empty($currentPrice)){
			$currentPrice = $this->Cryptocoin->find('all',['conditions'=>['id'=>$secondCoinId],'fields'=>['get_per_price'=>'usd_price','created_at'=>'created','get_amount'=>1]])->hydrate(false)->toArray();
		}
		
		if(!empty($findCoinPair) && $findCoinPair['binance_price'] == "Y" && !empty($findCoinPair['pair_price'])){
			$currentPrice[0]['get_per_price'] = $findCoinPair['pair_price'];
		}

		if(empty($findCoinPair)){
			$this->cause_error(5302);die;
		}
		
		$getOneDayBeforePrice = $this->ExchangeHistory->find('all',[
			'conditions'=> [
				'OR'=>[
					['get_cryptocoin_id'=>$secondCoinId,'spend_cryptocoin_id'=>$firstCoinId,'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24],
					['spend_cryptocoin_id'=>$secondCoinId,'get_cryptocoin_id'=>$firstCoinId,'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24]]],
			'limit' => 1,
			'order' => ['id'=>'desc']])->hydrate(false)->first();
		$units_traded = $this->ExchangeHistory->find('all',[
			'conditions'=> [
				'OR'=>[
					['get_cryptocoin_id'=>$secondCoinId,'spend_cryptocoin_id'=>$firstCoinId,'DATE_FORMAT(created_at, "%Y-%m-%d") = CURDATE()'],
					['spend_cryptocoin_id'=>$secondCoinId,'get_cryptocoin_id'=>$firstCoinId,'DATE_FORMAT(created_at, "%Y-%m-%d") = CURDATE()']]],
			'fields'=>['totalsum'=>'sum(get_amount*get_per_price)','created_at'],
			'limit' => 1,
			'order' => ['id'=>'desc']])->hydrate(false)->first();
																  
		$units_traded_24H = $this->ExchangeHistory->find('all',[
			'conditions'=> [
				'OR'=>[
					['get_cryptocoin_id'=>$secondCoinId,'spend_cryptocoin_id'=>$firstCoinId,'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24],
					['spend_cryptocoin_id'=>$secondCoinId,'get_cryptocoin_id'=>$firstCoinId,'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24]]],
			'fields'=>['totalsum'=>'sum(get_amount*get_per_price)','created_at'],
			'limit' => 1,
			'order' => ['id'=>'desc']])->hydrate(false)->first();
		$acc_trade_value_24H = $this->ExchangeHistory->find('all',[
			'conditions'=> [
				'OR'=>[
					['get_cryptocoin_id'=>$secondCoinId,'spend_cryptocoin_id'=>$firstCoinId,'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24],
					['spend_cryptocoin_id'=>$secondCoinId,'get_cryptocoin_id'=>$firstCoinId,'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24]]],
			'fields'=>['totalsum'=>'sum(get_per_price)','created_at'],
			'limit' => 1,
			'order' => ['id'=>'desc']])->hydrate(false)->first();
		$acc_trade_value = $this->ExchangeHistory->find('all',[
			'conditions'=> [
				'OR'=>[
					['get_cryptocoin_id'=>$secondCoinId,'spend_cryptocoin_id'=>$firstCoinId,'DATE_FORMAT(created_at, "%Y-%m-%d") = CURDATE()'],
					['spend_cryptocoin_id'=>$secondCoinId,'get_cryptocoin_id'=>$firstCoinId,'DATE_FORMAT(created_at, "%Y-%m-%d") = CURDATE()']]],
			'fields'=>['totalsum'=>'sum(get_per_price)','created_at'],
			'limit' => 1,
			'order' => ['id'=>'desc']])->hydrate(false)->first();
																
		$getRecentMaxMinPrice = $this->ExchangeHistory->find('all',[
			'conditions'=>[
				'OR'=>[['get_cryptocoin_id'=>$secondCoinId,'spend_cryptocoin_id'=>$firstCoinId,'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24],
					['spend_cryptocoin_id'=>$secondCoinId,'get_cryptocoin_id'=>$firstCoinId,'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24]]],
			'fields'=>['maxprice'=>'max(get_per_price)','minprice'=>'min(get_per_price)'],
			'limit' => 1,
			'order' => ['id'=>'desc']])->hydrate(false)->first();
		$opening_price = $this->ExchangeHistory->find('all',[
			'conditions'=> [
				'OR'=>[
					['get_cryptocoin_id'=>$secondCoinId,'spend_cryptocoin_id'=>$firstCoinId,'DATE_FORMAT(created_at, "%Y-%m-%d") = CURDATE()'],
					['spend_cryptocoin_id'=>$secondCoinId,'get_cryptocoin_id'=>$firstCoinId,'DATE_FORMAT(created_at, "%Y-%m-%d") = CURDATE()']]],
			'fields'=>['get_per_price'],
			'limit' => 1,
			'order' => ['id'=>'asc']])->hydrate(false)->first();
		
		$sendArr = [];
		$sendArr['trade_datetime'] = ''; // 최근 거래 시간
		$sendArr['trade_volume'] = 0; // 가장 최근 거래량
		$sendArr['trade_price'] = 0;// 가장 최근 거래 금액
		$sendArr['opening_price'] = 0;// 시가(00시 기준)
		$sendArr['closing_price'] = 0;// 종가 (현재가)
		$sendArr['min_price'] = 0;// 저가 (24시간 기준)
		$sendArr['max_price'] = 0;// 고가 (24시간 기준)
		$sendArr['prev_closing_price'] = 0;// 전일종가 (어제 종가)
		$sendArr['change'] = "EVEN"; // EVEN : 보합 / RISE : 상승 / FALL : 하락
		$sendArr['change_price'] = 0;// 전일대비 현재 변화 금액(현재가-종전가)
		$sendArr['change_rate'] = 0;// 전일대비 현재 변화율
		$sendArr['acc_trade_value'] = 0;// 금일 거래금액 (00시 부터 누적 금액)
		$sendArr['acc_trade_value_24H'] = 0;// 최근 24시간 누적 거래금액
		$sendArr['units_traded'] = 0;// 금일 거래량 (00시 부터 누적 금액)
		$sendArr['units_traded_24H'] = 0;// 최근 24시간 누적 거래량


		if(!empty($getRecentMaxMinPrice)){
			$sendArr['max_price'] = number_format($getRecentMaxMinPrice['maxprice'],2); // 고가
			$sendArr['min_price'] = number_format($getRecentMaxMinPrice['minprice'],2); // 저가 
		}
		if(!empty($acc_trade_value)){
			$sendArr['acc_trade_value'] = number_format($acc_trade_value['totalsum'],2); // 금일 거래금액
		}
		if(!empty($acc_trade_value_24H)){
			$sendArr['acc_trade_value_24H'] = number_format($acc_trade_value_24H['totalsum'],2); // 최근 24시간 누적 거래금액
		}
		if(!empty($units_traded)){
			$sendArr['units_traded'] = number_format($units_traded['totalsum'],2);// 금일 거래량 (00시 부터 누적 금액)
		}
		if(!empty($units_traded_24H)){
			$sendArr['units_traded_24H'] = number_format($units_traded_24H['totalsum'],2); // 최근 24시간 누적 거래량
		}

		if(count($currentPrice)>1) {
			if($currentPrice[0]['get_per_price']==$currentPrice[1]['get_per_price']){
				$sendArr['change'] = "EVEN";
			} else if ($currentPrice[0]['get_per_price']>$currentPrice[1]['get_per_price']){
				$sendArr['change'] = "RISE";
			} else if ($currentPrice[0]['get_per_price']<$currentPrice[1]['get_per_price']){
				$sendArr['change'] = "FALL";
			}
		}

		$sendArr['trade_datetime'] = $currentPrice[0]['created_at']; // 최근 거래 일자 
		$sendArr['trade_volume'] = number_format($currentPrice[0]['get_amount']*$currentPrice[0]['get_per_price'],2); // 가장 최근 거래량
		$sendArr['trade_price'] = number_format($currentPrice[0]['get_per_price'],2); // 가장 최근 거래 금액;
		$changeInOneDay = (($findCoinPair['current_pair_price']-$findCoinPair['mid_night_price'])/$findCoinPair['mid_night_price'])*100;
		$sendArr['change_price'] = number_format($findCoinPair['current_pair_price']-$findCoinPair['mid_night_price'],2); 
		$sendArr['change_rate'] = number_format($changeInOneDay,2); // 전일대비 현재 변화율
		$sendArr['opening_price'] = number_format($opening_price['get_per_price'],2); // 시가 
		$sendArr['closing_price'] = number_format($currentPrice[0]['get_per_price'],2); // 종가 
		$sendArr['prev_closing_price'] = number_format($findCoinPair['mid_night_price'],2); // 전일 종가

		return $sendArr;
	}
	/* 2-1. 실제 매수/매도 데이터 */
	public function getOrderBook($firstCoinId,$secondCoinId,$count){
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$sendArr = [];
		$sendArr['timestamp'] = time();
		$sendArr['total_ask_size'] = 0; // 매도 총 잔량
		$sendArr['total_bid_size'] = 0; // 매수 총 잔량
		$sendArr['asks'] = 0; // 매도 
		$sendArr['bids'] = 0; // 매수

		$total_bid_size = $this->BuyExchange->find('all',[
			'conditions'=>['buy_spend_coin_id '=>$firstCoinId,'buy_get_coin_id '=>$secondCoinId,'buy_get_amount >'=>0,'status '=>'pending'],
			'fields'=>['totalsum'=>'sum(buy_get_amount*per_price)']])->first(); // 매수
		$total_ask_size = $this->SellExchange->find('all',[
			'conditions'=>['sell_spend_coin_id '=>$secondCoinId,'sell_get_coin_id '=>$firstCoinId,'sell_spend_amount >'=>0,'status '=>'pending'],
			'fields'=>['totalsum'=>'sum(sell_spend_amount*per_price)']])->first(); // 매도
		$buyOrderList = $this->BuyExchange->find('all',[
			'conditions'=>['buy_spend_coin_id '=>$firstCoinId,'buy_get_coin_id '=>$secondCoinId,'buy_get_amount >'=>0,'status '=>'pending'],
			'fields'=>['quantity'=>'round(sum(buy_get_amount),2)','price'=>'round(per_price,2)'],				
			'group'=>['per_price'],
			'limit'=>$count,
			'order' => ['BuyExchange.per_price'=>'desc']])->hydrate(false)->toArray(); // 매수
		$sellOrderList = $this->SellExchange->find('all',[
			'conditions'=>['sell_spend_coin_id '=>$secondCoinId,'sell_get_coin_id '=>$firstCoinId,'sell_spend_amount >'=>0,'status '=>'pending'],
			'fields'=>['quantity'=>'round(sum(sell_spend_amount),2)','price'=>'round(per_price,2)'],				 
			'group'=>['per_price'],		
			'limit'=>$count,
			'order' => ['SellExchange.per_price'=>'desc']])->hydrate(false)->toArray(); // 매도

		$sendArr['total_ask_size'] = number_format($total_ask_size['totalsum'],2); // 매도 총 잔량
		$sendArr['total_bid_size'] = number_format($total_bid_size['totalsum'],2); // 매수 총 잔량
		$sendArr['asks'] = $sellOrderList; // 매도 
		$sendArr['bids'] = $buyOrderList; // 매수

		return $sendArr;
	}	
	/* 3-1. 실제 트랜잭션 데이터 */
	public function getTransactionHistory($firstCoinId,$secondCoinId,$count){
		$this->loadModel('ExchangeHistory');
		$sendArr = [];
		$list = $this->ExchangeHistory->find('all',[
			'conditions'=> [
				'status' => 'completed','get_cryptocoin_id'=>$firstCoinId,'spend_cryptocoin_id'=>$secondCoinId
			],
			'fields'=>['timestamp'=>'UNIX_TIMESTAMP(created_at)','type'=>'CASE WHEN (extype = "buy") THEN "bid" ELSE "ask" END','amount'=>'round(get_amount,2)','price'=>'round(get_per_price,2)','total'=>'round(get_amount*get_per_price,2)'],
			'limit' => $count,
			'order' => ['id'=>'desc']])->hydrate(false)->toArray();
		$sendArr = $list;

		return $sendArr;
	}

	/* 에러 코드 */
	public function cause_error($code){
		$error_arr = [];
		switch($code){
			case 5200 :
				$error_arr = ['status'=>'5200','message'=>'Not Member'];
				break;
			case 5300 :
				$error_arr = ['status'=>'5300','message'=>'시스템이 원활하지 않습니다. 잠시 후 다시 시도해 주세요.'];
				break;
			case 5301 :
				$error_arr = ['status'=>'5301','message'=>'This coin is not supported'];
				break;
			case 5302 :
				$error_arr = ['status'=>'5302','message'=>'This transaction is not supported'];
				break;
			default:
				$error_arr = ['status'=>'5500','message'=>'알 수 없는 오류가 발생했습니다'];
				break;
		}
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Max-Age: 86400');
		header('Access-Control-Allow-Headers: x-requested-with');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		echo json_encode($error_arr,JSON_UNESCAPED_UNICODE); die;
	}

	public function getCoinData($coin_name = 'ctc_krw'){
		$respArr = [];
		$get_coin_info = $this->getCoinInfo($coin_name);
		if($get_coin_info['status'] != '200'){
			$respArr = $get_coin_info;
		}
		if($get_coin_info['status'] == '200'){
			$order_currency_check = $get_coin_info['order_currency_coin'];
			$payment_currency_check = $get_coin_info['payment_currency_coin'];
			$secondCoinId = $order_currency_check['id'];
			$firstCoinId = $payment_currency_check['id'];

			$this->loadModel('ExchangeHistory');
			$getGrpDataList = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
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
																	    "datecol"=>"unix_timestamp(DATE_FORMAT(ExchangeHistory.created_at,'%Y-%m-%d %H:%i'))",
																		"created_at"=>"DATE_FORMAT(ExchangeHistory.created_at,'%Y-%m-%d %H:%i')"
																	   ],
															"group"=>["DATE_FORMAT(ExchangeHistory.created_at,'%Y-%m-%d')"],
															"order"=>["id"=>"ASC"],
															])
															->hydrate(false)
															->toArray();
		}
		$sendArr = [];								  
		if(!empty($getGrpDataList)){
			$sendArr['success'] = 'true';	
			$sendArr['data'] = $getGrpDataList;	
		}
		else {
			$sendArr['success'] = 'false';	
			$sendArr['data'] = "";	
		}
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Max-Age: 86400');
		header('Access-Control-Allow-Headers: x-requested-with');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		echo json_encode($sendArr,JSON_UNESCAPED_UNICODE); die;
	}
}
?>
