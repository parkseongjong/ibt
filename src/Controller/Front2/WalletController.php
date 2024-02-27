<?php
namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager; 
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
use Google_Client;
use Google_Service_Plus;
use Google_Service_Oauth2;

class WalletController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['signup', 'logout','frontLogin','frontRegister','verify','forgotPassword','successregister','googlelogin','googlecallback']);
    }

	public function index()
	{
        $this->set('kind','wallet');
        $this->set('title', 'Transfer History');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $userId = $this->Auth->user('id');
        $principalTotalBalance = 0.0;
        $tradingTotalBalance = 0.0;
        $total_KRW_value = 0.0;
        $total_coins_value = 0.0;
        $mainRespArr = [];
        $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $coinShortName = $getCoin['short_name'];
            $principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId);

            $principalTotalBalance = $principalTotalBalance + $principalBalance;
            $tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
            $tradingTotalBalance = $tradingTotalBalance + $tradingBalance;
            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $currentKRWTotalVal = ($principalBalance*$getMyCustomPrice)+($tradingBalance*$getMyCustomPrice);
            $currentCoinsTotalVal  = ($principalBalance + $tradingBalance);
            $total_KRW_value = $total_KRW_value + $currentKRWTotalVal;
            $total_coins_value = $total_coins_value + $currentCoinsTotalVal;
//            $singleArr = ['principalBalance'=>number_format((float)$principalBalance,4),
//                'tradingBalance'=>number_format((float)$tradingBalance,4),
//                'coinId'=>$coinId,
//                'coinShortName'=>$coinShortName];
//            $mainRespArr[]=$singleArr;
        }
        $this->set('principalBalance', $principalBalance);
        $this->set('getMyCustomPrice', $getMyCustomPrice);
        $this->set('coinShortName', $coinShortName);
        $this->set('mainBalance', $principalTotalBalance);
        $this->set('tradingBalance', $tradingTotalBalance);
        $this->set('totalKRWBalance', $total_KRW_value);
        $this->set('totalCoinsBalance', $total_coins_value);

    }

    public function history()
    {
        $this->set('kind','wallet');
        $this->set('title', 'Withdrawal History');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $userId = $this->Auth->user('id');
        $principalTotalBalance = 0;
        $tradingTotalBalance = 0;
        $total_KRW_value = 0;
        $total_coins_value = 0;
        $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId);
            $principalTotalBalance = $principalTotalBalance + $principalBalance;
            $tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
            $tradingTotalBalance = $tradingTotalBalance + $tradingBalance;
            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $currentKRWTotalVal = ($principalBalance*$getMyCustomPrice)+($tradingBalance*$getMyCustomPrice);
            $currentCoinsTotalVal  = ($principalBalance + $tradingBalance);
            $total_KRW_value = $total_KRW_value + $currentKRWTotalVal;
            $total_coins_value = $total_coins_value + $currentCoinsTotalVal;
        }
        $this->set('mainBalance', $principalTotalBalance);
        $this->set('tradingBalance', $tradingTotalBalance);
        $this->set('totalKRWBalance', $total_KRW_value);
        $this->set('totalCoinsBalance', $total_coins_value);
    }

    public function transferHistory(){
        if ($this->request->is('ajax')) {
            $this->loadModel('Users');
            $this->loadModel('PrincipalWallet');
            $userId = $this->Auth->user('id');
            $transferHistoryList = $this->PrincipalWallet->find()
                ->where(['user_id'=>$userId,
                'OR' =>[['type' => 'transfer_to_trading_account'],['type' => 'transfer_from_trading_account']],])
                ->order(['id'=>'desc'])
                ->hydrate(false)->toArray();
            if(empty($transferHistoryList)){
            } else{
            }
            echo json_encode($transferHistoryList); die;
        }
    }



    public function transferHistoryHours($hours){
        if ($this->request->is('ajax')) {
            if (!empty($hours)) {
                $this->loadModel('Users');
                $this->loadModel('PrincipalWallet');
                $userId = $this->Auth->user('id');
                $transferHistoryList = $this->PrincipalWallet->find()
                    ->where(['user_id' => $userId, 'created_at > DATE_SUB(NOW(), INTERVAL ' . $hours . ' HOUR) AND created_at <= NOW()',
                        'OR' => [['type' => 'transfer_to_trading_account'], ['type' => 'transfer_from_trading_account']],])
                    ->order(['id' => 'desc'])
                    ->hydrate(false)->toArray();
                if (empty($transferHistoryList)) {
                } else {
                }
                echo json_encode($transferHistoryList);
                die;
            }
        }
    }

    public function transferHistoryCoin($coin){
        if ($this->request->is('ajax')) {
            if (!empty($coin)) {
                $this->loadModel('Users');
                $this->loadModel('PrincipalWallet');
                $userId = $this->Auth->user('id');
                $transferHistoryList = $this->PrincipalWallet->find()
                    ->where(['user_id' => $userId, 'cryptocoin_id'=>$coin,
                        'OR' => [['type' => 'transfer_to_trading_account'], ['type' => 'transfer_from_trading_account']],])
                    ->order(['id' => 'desc'])
                    ->hydrate(false)->toArray();
                if (empty($transferHistoryList)) {
                } else {
                }
                echo json_encode($transferHistoryList);
                die;
            }
        }
    }

    public function transferHistoryCalendar($startDate, $endDate){
        if ($this->request->is('ajax')) {
            if (!empty($startDate) && !empty($endDate)) {
                $this->loadModel('Users');
                $this->loadModel('PrincipalWallet');
                $userId = $this->Auth->user('id');
                $transferHistoryList = $this->PrincipalWallet->find()
                    ->where(['user_id' => $userId, 'DATE(created_at) >= '=>$startDate, 'DATE(created_at) <= '=>$endDate,
                        'OR' => [['type' => 'transfer_to_trading_account'], ['type' => 'transfer_from_trading_account']],])
                    ->order(['id' => 'desc'])
                    ->hydrate(false)->toArray();
                if (empty($transferHistoryList)) {
                } else {
                }
                echo json_encode($transferHistoryList);
                die;
            }
        }
    }

    public function withDrawHistory(){
        if ($this->request->is('ajax')) {
            $this->loadModel('Users');
            $this->loadModel('PrincipalWallet');
            $userId = $this->Auth->user('id');
            $withDrawHistoryList = $this->PrincipalWallet->find()
                ->where(['user_id'=>$userId, 'type' => 'withdrawal'])
                ->order(['id'=>'desc'])
                ->hydrate(false)->toArray();
            if(empty($withDrawHistoryList)){
            } else{
            }
            echo json_encode($withDrawHistoryList); die;
        }
    }

    public function withDrawHistoryHours($hours){
        if ($this->request->is('ajax')) {
            if (!empty($hours)) {
                $this->loadModel('Users');
                $this->loadModel('PrincipalWallet');
                $userId = $this->Auth->user('id');
                $transferHistoryList = $this->PrincipalWallet->find()
                    ->where(['user_id' => $userId, 'type' => 'withdrawal', 'created_at > DATE_SUB(NOW(), INTERVAL ' . $hours . ' HOUR) AND created_at <= NOW()',])
                    ->order(['id' => 'desc'])
                    ->hydrate(false)->toArray();
                if (empty($transferHistoryList)) {
                } else {
                }
                echo json_encode($transferHistoryList);
                die;
            }
        }
    }

    public function withDrawHistoryCalendar($startDate, $endDate){
        if ($this->request->is('ajax')) {
            if (!empty($startDate) && !empty($endDate)) {
                $this->loadModel('Users');
                $this->loadModel('PrincipalWallet');
                $userId = $this->Auth->user('id');
                $withDrawHistoryList = $this->PrincipalWallet->find()
                    ->where(['user_id' => $userId, 'type'=>'withdrawal', 'DATE(created_at) >= '=>$startDate, 'DATE(created_at) <= '=>$endDate])
                    ->order(['id' => 'desc'])
                    ->hydrate(false)->toArray();
                if (empty($withDrawHistoryList)) {
                } else {
                }
                echo json_encode($withDrawHistoryList);
                die;
            }
        }
    }


	public function mywallet(){
		$this->loadModel('Transactions');
		$this->loadModel('Cryptocoin');
		/* $authUserId = $this->Auth->user('id');
		$intrAddress  = $this->Auth->user('intr_address');
		$this->set('intrAddress',$intrAddress);
		
		$this->set('currentUserId',$authUserId);
		
		$getUserTotalCoin = $this->Transactions->find(); 
		$getUserTotalCoinCnt = $getUserTotalCoin
									->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id','cryptocoin.short_name','cryptocoin.icon'])
									->contain('cryptocoin')
									->where(['Transactions.user_id'=>$authUserId,'Transactions.status'=>'completed'])
									->group('cryptocoin_id')
									->toArray();

		
		$this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);
		
		
		$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
		$this->set('getCoinList',$getCoinList); */
	}

	public function mywalletajax(){
		$this->loadModel('Cryptocoin');
		$mainRespArr = [];
		$userId = $this->Auth->user('id');
		$mainBalance = 0;
		$tradeBalance = 0;
		$resrvBalance = 0;
		#$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();

        //TODO 20220628 SOJO 날쿼리 추가
        $getCoinList = $this->Users->getUserCryptocoins($userId);

		foreach($getCoinList as $getCoin){
				$coinId = $getCoin['id'];
				$coinName = $getCoin['name'];
				$coinShortName = $getCoin['short_name'];

				#$principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId);
				#$tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
                $tradingSave = $this->Users->getLocalUsersave($userId,$coinId);
				//$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
				#$reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
				#$reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);

                $principalBalance = $getCoin['wallet_amount'];
                $tradingBalance = $getCoin['trade_amount'];
                $reserveBuyBalance = $getCoin['buy_amount'];
                $reserveSellBalance = $getCoin['sell_amount'];
                $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
				if($coinShortName == "BTC"){
                    $mainBalance = number_format((float)$principalBalance,6);
                    $tradeBalance = number_format((float)$tradingBalance,6);
                    $tradingSave = number_format((float)$tradingSave,6);
                    $resrvBalance = number_format((float)$reserveBalance,6);
                } else if($coinShortName == "KRW" || $coinShortName == "MC" || $coinShortName == "CTC" || $coinShortName == "TP3"){
                    $mainBalance = number_format((float)$principalBalance,2);
                    $tradeBalance = number_format((float)$tradingBalance,2);
                    $tradingSave = number_format((float)$tradingSave,2);
                    $resrvBalance = number_format((float)$reserveBalance,2);
                } else {
                    $mainBalance = number_format((float)$principalBalance,4);
                    $tradeBalance = number_format((float)$tradingBalance,4);
                    $tradingSave = number_format((float)$tradingSave,4);
                    $resrvBalance = number_format((float)$reserveBalance,4);
                }
				$singleArr = ['principalBalance'=>$mainBalance,
							  'tradingBalance'=>$tradeBalance,
                              'tradingSave' =>$tradingSave,
							  'reserveBalance'=>$resrvBalance,
							  'reserveBuyBalance'=>$reserveBuyBalance,
							  'reserveSellBalance'=>$reserveSellBalance,
							  'coinId'=>$coinId,
							  'coinName'=>$coinName,
							  'coinShortName'=>$coinShortName							  
				];
				$mainRespArr[]=$singleArr;
				
		}
		$respArr=['status'=>'false','message'=>"coin list",'data'=>['coinlist'=>$mainRespArr]];
		
		echo json_encode($respArr); die;
	}


	public function transgetToAccount(){
		$this->loadModel('Transactions');
		$this->loadModel('Cryptocoin');
		$this->loadModel('PrincipalWallet');
		$this->loadModel('Users');
		$this->loadModel('NumberThreeSetting');
		$this->loadModel('TransferLimits');
		$this->loadModel('NumberFourSetting');
		if ($this->request->is('ajax')){
			$authUserId = $this->Auth->user('id');
			$cuDateTime = date("Y-m-d H:i:s");
			$getNightTime  = date("Y-m-d 00:00:00");
			/* if(!isset($this->request->data['amount']) || !isset($this->request->data['transfer_to']) || !isset(coin_id)){
				$respArr=['status'=>'false','message'=>"Amount, transfer Type and coin is required"];
				echo json_encode($respArr); die;
			} */
			$amount = $this->request->data['amount'];
			$transferTo = $this->request->data['transfer_to'];
			$coinId = $this->request->data['coin_id'];
			
			$userDetails = $this->Users->find("all",["conditions"=>['id'=>$authUserId]])->hydrate(false)->first();
			$annualMemberShip = $userDetails["annual_membership"];
			
			$btcWalletAddr = $userDetails['btc_address'];
			$ethWalletAddr = $userDetails['eth_address'];
			
			$userWalletAddr = ($coinId==1) ? $btcWalletAddr : $ethWalletAddr;
			
            //여기 추가 부분
            //savetrading
            //DB 관리자 페이지 정보를 일단 호출해서 가져온다



            if($transferTo == 'savetrading'){


                $this->loadModel("TransferLimits");
                /*
                $getUserTotalCoinCnt = $getUserTotalCoin
                    ->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id'])
                    ->where(['Transactions.user_id'=>$userId,'Transactions.status'=>'completed','Transactions.tx_type !='=>'bank_initial_deposit'])
                    ->group('cryptocoin_id')
                    ->toArray();
                */
                //$this->TransferLimits->find()->where(['user_id'=>'9998'])->all();

                $remainingAmt = $amount;
                $deductBalanceArr = ['coin_amount' => $remainingAmt,
                    'status' => 'completed',
                    'tx_type' => 'transfer_save',
                    'remark' => 'transfer_save',
                    'user_id' => $authUserId,
                    'wallet_address' => $userWalletAddr,
                    'cryptocoin_id' => $coinId];
                $newObj = $this->Transactions->newEntity();
                $newObj = $this->Transactions->patchEntity($newObj, $deductBalanceArr);
                $saveThisData = $this->Transactions->save($newObj);

                $deductBalanceArr = ['coin_amount' => -$remainingAmt,
                    'status' => 'completed',
                    'tx_type' => 'save',
                    'remark' => 'save_box',
                    'user_id' => $authUserId,
                    'wallet_address' => $userWalletAddr,
                    'cryptocoin_id' => $coinId];
                $newObj = $this->Transactions->newEntity();
                $newObj = $this->Transactions->patchEntity($newObj, $deductBalanceArr);
                $saveThisData = $this->Transactions->save($newObj);


                /*$respArr=['status'=>'true','message'=>'Succeeded'];*/
                $respArr=['status'=>'true','message'=>'Testing'];
                echo json_encode($respArr); die;
            }

            if($transferTo == 'save'){
                // deduct balance from main account




                $remainingAmt = $amount;
                // add balance from trading account
                $deductBalanceArr = ['coin_amount' => $remainingAmt,
                    'status' => 'completed',
                    'tx_type' => 'save',
                    'remark' => 'save_box',
                    'user_id' => $authUserId,
                    'wallet_address' => $userWalletAddr,
                    'cryptocoin_id' => $coinId];
                $newObj = $this->Transactions->newEntity();
                $newObj = $this->Transactions->patchEntity($newObj, $deductBalanceArr);
                $saveThisData = $this->Transactions->save($newObj);


                $deductBalanceArr2 = ['coin_amount' => -$remainingAmt,
                    'status' => 'completed',
                    'tx_type' => 'transfer_save',
                    'remark' => 'transfer_save',
                    'user_id' => $authUserId,
                    'wallet_address' => $userWalletAddr,
                    'cryptocoin_id' => $coinId];
                $newObj2 = $this->Transactions->newEntity();
                $newObj2 = $this->Transactions->patchEntity($newObj2, $deductBalanceArr2);
                $saveThisData2 = $this->Transactions->save($newObj2);


                /*$respArr=['status'=>'true','message'=>'Succeeded'];*/
                $respArr=['status'=>'true','message'=>'Testing'];
                echo json_encode($respArr); die;

                //저장함

                $deductBalanceArr=['amount'=>-$amount,
                    'status'=>'completed',
                    'type'=>'transfer_to_trading',
                    'user_id'=>$authUserId,
                    'wallet_address'=>$userWalletAddr,
                    'cryptocoin_id'=>$coinId];
                $newObj = $this->PrincipalWallet->newEntity();
                $newObj = $this->PrincipalWallet->patchEntity($newObj,$deductBalanceArr);
                $saveThisData = $this->PrincipalWallet->save($newObj);
                if($saveThisData) {
                    $remainingAmt = $amount;
                    // add balance from trading account
                    $deductBalanceArr = ['coin_amount' => $remainingAmt,
                        'status' => 'completed',
                        'tx_type' => 'save',
                        'remark' => 'transfer_from_save',
                        'user_id' => $authUserId,
                        'wallet_address' => $userWalletAddr,
                        'cryptocoin_id' => $coinId];
                    $newObj = $this->Transactions->newEntity();
                    $newObj = $this->Transactions->patchEntity($newObj, $deductBalanceArr);
                    $saveThisData = $this->Transactions->save($newObj);
                }



            }

			
			if(empty($amount) || empty($transferTo) || empty($coinId)){
				$respArr=['status'=>'false','message'=>__('All the fields are required')];
				echo json_encode($respArr); die;
			}
			else if($amount<=0){
				$respArr=['status'=>'false','message'=>__('Amount should be greater than 0')];
				echo json_encode($respArr); die;
			}
			else if(!in_array($transferTo,['trading','main'])){
				$respArr=['status'=>'false','message'=>__('Invalid transfer type')];
				echo json_encode($respArr); die;
			}
			
			$getCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$coinId]])->hydrate(false)->first();
			if(empty($getCoinDetail)){
				$respArr=['status'=>'false','message'=>__('Invalid currency')];
				echo json_encode($respArr); die;
			}
			$tradingLimit = $getCoinDetail['mainwallet_to_tradingwallet_transfer_limit'];
			
			
			
			
			
			$maxTradingLimit = 0;
			$minTradingLimit = 0;
			$checkOrderAfterDate = "";

            if($annualMemberShip=="Y"){
                // Annual Member Users Yearly Limit Start
                $foundAnnualUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>2,
                    "days"=>365,
                    "status"=>'active',
                    "cryptocoin_id"=>$coinId],
                    "order"=>["id"=>"DESC"]])
                    ->hydrate(false)
                    ->first();

                if(!empty($foundAnnualUserAnnualLimit)){

                    $maxTradingLimit = ($transferTo=="trading") ? $foundAnnualUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserAnnualLimit["trading_to_main_transfer_limit"];

                    $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                    $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"], "conditions"=>[
                        "remark"=>$remark,
                        "user_id"=>$authUserId,
                        "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 YEAR)",
                        "cryptocoin_id"=>$coinId]])->hydrate(false)->first();

                    $previouslyTransferAmt = !empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                    //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                    /*if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                        $respArr=['status'=>'false','message'=>__('Annual Member Annual Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                        echo json_encode($respArr); die;
                    }*/
                }
                // Annual Member Users Daily Limit Start
                $foundAnnualUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>2,
                    "days"=>1,
                    "status"=>'active',
                    "cryptocoin_id"=>$coinId],
                    "order"=>["id"=>"DESC"]])
                    ->hydrate(false)
                    ->first();

                if(!empty($foundAnnualUserOneDayLimit)){

                    $maxTradingLimit = ($transferTo=="trading") ? $foundAnnualUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserOneDayLimit["trading_to_main_transfer_limit"];

                    $remark = (`$transferTo`=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                    $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                        "conditions"=>[
                            "remark"=>$remark,
                            "user_id"=>$authUserId,
                            "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 DAY)",
                            "cryptocoin_id"=>$coinId]
                    ])->hydrate(false)->first();

                    $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                    $maxTradingLimitAnnual = ($transferTo=="trading") ? $foundAnnualUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserAnnualLimit["trading_to_main_transfer_limit"];
                    $previouslyTransferAnnual = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"], "conditions"=>[
                        "remark"=>$remark,
                        "user_id"=>$authUserId,
                        "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 YEAR)",
                        "cryptocoin_id"=>$coinId]])->hydrate(false)->first();

                    $previouslyTransferAmtAnnual = !empty($previouslyTransferAnnual['totalTransfer']) ? $previouslyTransferAnnual['totalTransfer'] : 0;


                    //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                    
                    /*
                     * 연간회원제거
                    if(($previouslyTransferAmt+$amount) > $maxTradingLimit || ($previouslyTransferAmtAnnual+$amount) > $maxTradingLimitAnnual){
                        $respArr=['status'=>'false','message'=>__('Annual Member One Day Trading Limit Exceed. Total transferred: ').number_format($previouslyTransferAmt,2)];
                        echo json_encode($respArr); die;
                    }*/
                    
                    
                }
            }

            else {
                if($coinId == 17 || $coinId == 19 || $coinId == 21){
                    $respArr=['status'=>'false','message'=>__('Please get annual membership to be eligible to transfer')];
                    echo json_encode($respArr); die;
                }else {
                    //Single General User One Day Limit
                    $foundSingleUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>[
                        "user_id"=>$authUserId,
                        "days"=>1,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])->hydrate(false)->first();

                    //All Users One Day Limit
                    $foundAllUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>[
                        "user_id"=>0,
                        "days"=>1,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])->hydrate(false)->first();

                    //Single General Annual Limit
                    $foundSingleUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>$authUserId,
                        "days"=>365,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])
                        ->hydrate(false)
                        ->first();

                    //All Users Annual Limit
                    $foundAllUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>0,
                        "days"=>365,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])
                        ->hydrate(false)
                        ->first();

                    if(!empty($foundSingleUserOneDayLimit)){//General User One Day Limit Start

                        $maxTradingLimit = ($transferTo=="trading") ? $foundSingleUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundSingleUserOneDayLimit["trading_to_main_transfer_limit"];

                        $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                        $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>$remark,
                                "user_id"=>$authUserId,
                                "created >="=>$getNightTime,
                                "cryptocoin_id"=>$coinId]])->hydrate(false)->first();

                        $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                        $maxTradingLimitAnnualSingleUser = ($transferTo=="trading") ? $foundSingleUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundSingleUserAnnualLimit["trading_to_main_transfer_limit"];

                        $previouslyTransferAnnualSingleUser = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>$remark,
                                "user_id"=>$authUserId,
                                "created >="=>$getNightTime,
                                "cryptocoin_id"=>$coinId]
                        ])->hydrate(false)->first();

                        $previouslyTransferAmtAnnualSingleUser = !empty($previouslyTransferAnnualSingleUser['totalTransfer']) ? $previouslyTransferAnnualSingleUser['totalTransfer'] : 0;

                        //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                        if(($previouslyTransferAmt+$amount) > $maxTradingLimit || ($previouslyTransferAmtAnnualSingleUser+$amount) > $maxTradingLimitAnnualSingleUser){
                            $respArr=['status'=>'false','message'=>__('General User 1 Day Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                            echo json_encode($respArr); die;
                        }
                    }else{// All Users One Day Limit Start
                        if(!empty($foundAllUserOneDayLimit)){

                            $maxTradingLimit = ($transferTo=="trading") ? $foundAllUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundAllUserOneDayLimit["trading_to_main_transfer_limit"];

                            $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                            $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                                "conditions"=>[
                                    "remark"=>$remark,
                                    "user_id"=>$authUserId,
                                    "created >="=>$getNightTime,
                                    "cryptocoin_id"=>$coinId]
                            ])
                                ->hydrate(false)
                                ->first();

                            $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                            //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                            /*if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                                $respArr=['status'=>'false','message'=>__('All Users Daily Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                                echo json_encode($respArr); die;
                            }*/
                        }
                    }



                    if(!empty($foundSingleUserAnnualLimit)){// General User Yearly Limit Start

                        $maxTradingLimit = ($transferTo=="trading") ? $foundSingleUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundSingleUserAnnualLimit["trading_to_main_transfer_limit"];

                        $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                        $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>$remark,
                                "user_id"=>$authUserId,
                                "created >="=>$getNightTime,
                                "cryptocoin_id"=>$coinId]
                        ])->hydrate(false)->first();

                        $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                        //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                        if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                            $respArr=['status'=>'false','message'=>__('General User Yearly Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                            echo json_encode($respArr); die;
                        }
                    }else {
                        if(!empty($foundAllUserAnnualLimit)){// All Users Annual Limit Start

                            $maxTradingLimit = ($transferTo=="trading") ? $foundAllUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAllUserAnnualLimit["trading_to_main_transfer_limit"];

                            $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                            $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                                "conditions"=>[
                                    "remark"=>$remark,
                                    "user_id"=>$authUserId,
                                    "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 Year)",
                                    "cryptocoin_id"=>$coinId]])
                                ->hydrate(false)
                                ->first();

                            $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                            //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                            if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                                $respArr=['status'=>'false','message'=>__('All Users Annual Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                                echo json_encode($respArr); die;
                            }
                        }
                    }
                }

            }
							
//			if($annualMemberShip=="Y"){
//				// single Annul User One Day Limit Start
//				$foundSingleAnnualUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>2,
//																								"days"=>30,
//																								"status"=>'active',
//																								"cryptocoin_id"=>$coinId],
//																	"order"=>["id"=>"DESC"]])
//																	->hydrate(false)
//																	->first();
//
//				if(!empty($foundSingleAnnualUserOneDayLimit)){
//
//
//						$maxTradingLimit = ($transferTo=="trading") ? $foundSingleAnnualUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundSingleAnnualUserOneDayLimit["trading_to_main_transfer_limit"];
//
//						$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
//						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
//																	   "conditions"=>[
//																			"remark"=>$remark,
//																			"user_id"=>$authUserId,
//																			"created >= DATE_SUB(DATE(NOW()), INTERVAL 30 DAY)",
//																			"cryptocoin_id"=>$coinId]
//																		])
//																	   ->hydrate(false)
//																	   ->first();
//
//						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
//
//						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
//						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
//							$respArr=['status'=>'false','message'=>"Trading Limit Exceed. Today total transferred : ".$previouslyTransferAmt];
//							echo json_encode($respArr); die;
//						}
//
//
//				}
//			}
//			else {
//
//				// single User One Day Limit Start
//				$foundSingleUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>$authUserId,
//																								"days"=>1,
//																								"status"=>'active',
//																								"cryptocoin_id"=>$coinId],
//																	"order"=>["id"=>"DESC"]])
//																	->hydrate(false)
//																	->first();
//
//				if(!empty($foundSingleUserOneDayLimit)){
//
//
//						$maxTradingLimit = ($transferTo=="trading") ? $foundSingleUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundSingleUserOneDayLimit["trading_to_main_transfer_limit"];
//
//						$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
//						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
//																	   "conditions"=>[
//																			"remark"=>$remark,
//																			"user_id"=>$authUserId,
//																			"created >="=>$getNightTime,
//																			"cryptocoin_id"=>$coinId]
//																		])
//																	   ->hydrate(false)
//																	   ->first();
//
//						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
//
//						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
//						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
//							$respArr=['status'=>'false','message'=>"Trading Limit Exceed. Today total transferred : ".$previouslyTransferAmt];
//							echo json_encode($respArr); die;
//						}
//
//
//				}
//				else {
//
//					// all User One Day Limit Start
//					$foundAllUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>0,
//																								"days"=>1,
//																								"status"=>'active',
//																						   "cryptocoin_id"=>$coinId],
//																			"order"=>["id"=>"DESC"]])
//																		   ->hydrate(false)
//																		   ->first();
//					if(!empty($foundAllUserOneDayLimit)){
//
//						$maxTradingLimit = ($transferTo=="trading") ? $foundAllUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundAllUserOneDayLimit["trading_to_main_transfer_limit"];
//
//						$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
//						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
//																	   "conditions"=>[
//																			"remark"=>$remark,
//																			"user_id"=>$authUserId,
//																			"created >="=>$getNightTime,
//																			"cryptocoin_id"=>$coinId]
//																		])
//																	   ->hydrate(false)
//																	   ->first();
//
//						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
//
//						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
//						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
//							$respArr=['status'=>'false','message'=>"Max Trading Limit Exceed. Today total transferred : ".$previouslyTransferAmt];
//							echo json_encode($respArr); die;
//						}
//
//					}
//
//
//				}
//			}
			
			
			
			// deduct balance
			if($transferTo=="trading"){
				$adminFee = $this->Users->getAdninFee("main_to_trading_transfer_fee");
				// get transfer fee
				
				$findFromNumFourSetting = $this->NumberFourSetting->find('all',['conditions'=>['user_id'=>0,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
				
				if(!empty($findFromNumFourSetting) && !empty($findFromNumFourSetting["main_to_trading_transfer_fee"])){
					$adminFee = $findFromNumFourSetting["main_to_trading_transfer_fee"];
				}
				
				$findFromNumThreeSetting = $this->NumberThreeSetting->find('all',['conditions'=>['user_id'=>$authUserId,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
				if(!empty($findFromNumThreeSetting) && !empty($findFromNumThreeSetting['days'])){
					$getDays = $findFromNumThreeSetting['days'];
					$getThreeSettingDate = date("Y-m-d H:i:s",strtotime($findFromNumThreeSetting["created"]));
					$getFeeNextTime = date('Y-m-d H:i:s',strtotime($getThreeSettingDate.' + '.$getDays.' days'));
					if($cuDateTime < $getFeeNextTime){
						$adminFee = $findFromNumThreeSetting["user_fee"];
					}
				}
				
				
				$adminFeeAmt = $amount*$adminFee/100;
				$getMainBalance = $this->Users->getUserPricipalBalance($authUserId,$coinId);
				
				if($getMainBalance<$amount){
					$respArr=['status'=>'false','message'=>__('Insufficient Balance'),'data'=>["balance"=>$getMainBalance]];
					echo json_encode($respArr); die;
				}
				
				
				
				
				
				
				// deduct balance from main account
				$deductBalanceArr=['amount'=>-$amount,
								   'status'=>'completed',
								   'type'=>'transfer_to_trading_account',
                                    'fees'=>$adminFeeAmt,
								   'user_id'=>$authUserId,
								   'wallet_address'=>$userWalletAddr,
								   'cryptocoin_id'=>$coinId];
				$newObj = $this->PrincipalWallet->newEntity();
				$newObj = $this->PrincipalWallet->patchEntity($newObj,$deductBalanceArr);
				$saveThisData = $this->PrincipalWallet->save($newObj);
				if($saveThisData){
					$remainingAmt =  $amount-$adminFeeAmt;
					// add balance from trading account
					$deductBalanceArr=['coin_amount'=>$remainingAmt,
									   'status'=>'completed',
									   'tx_type'=>'purchase',
									   'remark'=>'transfer_from_main_account',
                                        'fees'=>$adminFeeAmt,
									   'user_id'=>$authUserId,
									   'wallet_address'=>$userWalletAddr,
									   'cryptocoin_id'=>$coinId];
					$newObj = $this->Transactions->newEntity();
					$newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArr);
					$saveThisData = $this->Transactions->save($newObj);
					if($saveThisData){
						// add fee to admin 
						$deductBalanceArrFee=['coin_amount'=>$adminFeeAmt,
										   'status'=>'completed',
										   'tx_type'=>'purchase',
                                            'fees'=>$adminFeeAmt,
										   'remark'=>'adminTranferFees',
										   'user_id'=>1,
										   'cryptocoin_id'=>$coinId];
						$newObj = $this->Transactions->newEntity();
						$newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArrFee);
						$saveThisData = $this->Transactions->save($newObj);
						
						$respArr=['status'=>'true','message'=>__('Amount transferred successfully to the trading account')];
						echo json_encode($respArr); die;
					}
					else {
						$respArr=['status'=>'false','message'=>__('Unable to transfer this amount to the trading account')];
						echo json_encode($respArr); die;
					}
				}
				else {
					$respArr=['status'=>'false','message'=>__('Unable to deduct this amount from the main account')];
					echo json_encode($respArr); die;
				}
				
				
			}
			else if($transferTo=="main"){
				$adminFee = $this->Users->getAdninFee("trading_to_main_transfer_fee");
				// get transfer fee
				
				$findFromNumFourSetting = $this->NumberFourSetting->find('all',['conditions'=>['user_id'=>0,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
				
				if(!empty($findFromNumFourSetting) && !empty($findFromNumFourSetting["tranding_to_main_transfer_fee"])){
					$adminFee = $findFromNumFourSetting["tranding_to_main_transfer_fee"];
				}
				
				$findFromNumThreeSetting = $this->NumberThreeSetting->find('all',['conditions'=>['user_id'=>$authUserId,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
				if(!empty($findFromNumThreeSetting) && !empty($findFromNumThreeSetting['days'])){
					$getDays = $findFromNumThreeSetting['days'];
					$getThreeSettingDate = date("Y-m-d H:i:s",strtotime($findFromNumThreeSetting["created"]));
					$getFeeNextTime = date('Y-m-d H:i:s',strtotime($getThreeSettingDate.' + '.$getDays.' days'));
					if($cuDateTime < $getFeeNextTime){
						$adminFee = $findFromNumThreeSetting["user_fee"];
					}
				}
				
				
				$adminFeeAmt = $amount*$adminFee/100;
				
				$geTradingtBalance = $this->Users->getLocalUserBalance($authUserId,$coinId);
				if($geTradingtBalance<$amount){
					$respArr=['status'=>'false','message'=>__('Insufficient Balance'),'data'=>["balance"=>$geTradingtBalance]];
					echo json_encode($respArr); die;
				}
				// deduct balance from trading account
				$deductBalanceArr=['coin_amount'=>-$amount,
								   'status'=>'completed',
								   'tx_type'=>'withdrawal',
								   'remark'=>'transfer_to_main_account',
                                    'fees'=>$adminFeeAmt,
								   'user_id'=>$authUserId,
								   'wallet_address'=>$userWalletAddr,
								   'cryptocoin_id'=>$coinId];
				$newObj = $this->Transactions->newEntity();
				$newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArr);
				$saveThisData = $this->Transactions->save($newObj);
				if($saveThisData){
					$remainingAmt =  $amount-$adminFeeAmt;
					// add balance from main account
					$deductBalanceArr=['amount'=>$remainingAmt,
									   'status'=>'completed',
									   'type'=>'transfer_from_trading_account',
                                        'fees'=>$adminFeeAmt,
									   'user_id'=>$authUserId,
									   'wallet_address'=>$userWalletAddr,
									   'cryptocoin_id'=>$coinId];
					$newObj = $this->PrincipalWallet->newEntity();
					$newObj = $this->PrincipalWallet->patchEntity($newObj,$deductBalanceArr);
					$saveThisData = $this->PrincipalWallet->save($newObj);
					if($saveThisData){
						
						// add fee to admin 
						$deductBalanceArrFee=['coin_amount'=>$adminFeeAmt,
											   'status'=>'completed',
											   'tx_type'=>'purchase',
											   'remark'=>'adminTranferFees',
                                                'fees'=>$adminFeeAmt,
											   'user_id'=>1,
											   'cryptocoin_id'=>$coinId];
						$newObj = $this->Transactions->newEntity();
						$newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArrFee);
						$saveThisData = $this->Transactions->save($newObj);
						
						$respArr=['status'=>'true','message'=>__('Amount transferred successfully to the main account')];
						echo json_encode($respArr); die;
					}
					else {
						$respArr=['status'=>'false','message'=>__('Unable to transfer this to the main account')];
						echo json_encode($respArr); die;
					}
				}
				else {
					$respArr=['status'=>'false','message'=>__('Unable to deduct this amount from the trading account')];
					echo json_encode($respArr); die;
				}
				
				
			}
			
			
			$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
			$intrAddress  = $this->Auth->user('intr_address');
			$this->set('intrAddress',$intrAddress);
			
			$this->set('currentUserId',$authUserId);
			
			$getUserTotalCoin = $this->Transactions->find(); 
			$getUserTotalCoinCnt = $getUserTotalCoin
										->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id','cryptocoin.short_name','cryptocoin.icon'])
										->contain('cryptocoin')
										->where(['Transactions.user_id'=>$authUserId,'Transactions.status'=>'completed','Transactions.tx_type !='=>'bank_initial_deposit'])
										->group('cryptocoin_id')
										->toArray();

			
			$this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);
			
			
			$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
			$this->set('getCoinList',$getCoinList);
		}
		else {
			$respArr=['status'=>'false','message'=>"Invalid Request"];
			echo json_encode($respArr); die;
		}
	}
    public function transgetToAccountNew(){
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('TransferLimits');
        $this->loadModel('NumberFourSetting');
        $this->loadModel('NumberThreeSetting');
        $this->loadModel('TransferLimits');
        $this->loadModel('Users');
        if ($this->request->is('ajax')){

            $cuDateTime = date("Y-m-d H:i:s");
            $getNightTime  = date("Y-m-d 00:00:00");

            $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1,
                'OR' => array(
                    'short_name' => $this->request->data['coin_id'],
                    'name' => $this->request->data['coin_id'],
                )]])->hydrate(false)->first();
            $authUserId = $this->Auth->user('id');

            /* if(!isset($this->request->data['amount']) || !isset($this->request->data['transfer_to']) || !isset(coin_id)){
                $respArr=['status'=>'false','message'=>"Amount, transfer Type and coin is required"];
                echo json_encode($respArr); die;
            } */
            $amount = $this->request->data['amount'];

            //트레이딩? 메인 ? 조회값
            $transferTo = $this->request->data['transfer_to'];
            $coinId = $getCoinList['id'];

            $userDetails = $this->Users->find("all",["conditions"=>['id'=>$authUserId]])->hydrate(false)->first();
            $annualMemberShip = $userDetails['annual_membership'];
            $btcWalletAddr = $userDetails['btc_address'];
            $ethWalletAddr = $userDetails['eth_address'];

            $userWalletAddr = ($coinId==1) ? $btcWalletAddr : $ethWalletAddr;


            if(empty($amount) || empty($transferTo) || empty($coinId)){
                $respArr=['status'=>'false','message'=>"Amount, transfer Type and coin is required"];
                echo json_encode($respArr); die;
            }
            else if($amount<=0){
                $respArr=['status'=>'false','message'=>"amount should be greater than 0"];
                echo json_encode($respArr); die;
            }
            else if(!in_array($transferTo,['trading','main'])){
                $respArr=['status'=>'false','message'=>" invalid transfer type"];
                echo json_encode($respArr); die;
            }

            $getCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$coinId]])->hydrate(false)->first();
            if(empty($getCoinDetail)){
                $respArr=['status'=>'false','message'=>" invalid Currency"];
                echo json_encode($respArr); die;
            }
            $tradingLimit = $getCoinDetail['mainwallet_to_tradingwallet_transfer_limit'];



            if($annualMemberShip=="Y"){
                // Annual Member Users Yearly Limit Start
                $foundAnnualUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>2,
                    "days"=>365,
                    "status"=>'active',
                    "cryptocoin_id"=>$coinId],
                    "order"=>["id"=>"DESC"]])
                    ->hydrate(false)
                    ->first();

                $foundAnnualmonthUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>2,
                    "days"=>30,
                    "status"=>'active',
                    "cryptocoin_id"=>$coinId],
                    "order"=>["id"=>"DESC"]])
                    ->hydrate(false)
                    ->first();


                if(!empty($foundAnnualUserAnnualLimit)){

                    //$maxTradingLimit = ($transferTo=="trading") ? $foundAnnualUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserAnnualLimit["trading_to_main_transfer_limit"];
                    if($transferTo == "main"){
                        $remark = 'transfer_to_main_account';
                        $maxTradingLimit = $foundAnnualUserAnnualLimit['trading_to_main_transfer_limit'];
                    }else if($transferTo == 'trading'){
                        $remark = 'transfer_from_main_account';
                        $maxTradingLimit = $foundAnnualUserAnnualLimit['main_to_trading_transfer_limit'];
                    }


                    //$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_to_main_account';

                    //$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                    $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"], "conditions"=>[
                        "remark"=>$remark,
                        "user_id"=>$authUserId,
                        "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 YEAR)",
                        "cryptocoin_id"=>$coinId]])->hydrate(false)->first();


                    $previouslyTransferAmt = !empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;


                    //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                    if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                        $respArr=['status'=>'false','message'=>"Annual Member Annual Trading Limit Exceeded. Total transferred : ".number_format($previouslyTransferAmt,2)];
                        echo json_encode($respArr); die;
                    }
                }

                //30일
                if(!empty($foundAnnualmonthUserAnnualLimit)){
                    //$maxTradingLimit = ($transferTo=="trading") ? $foundAnnualUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserAnnualLimit["trading_to_main_transfer_limit"];
                    if($transferTo == "main"){
                        $remark = 'transfer_to_main_account';
                        $maxTradingLimit = $foundAnnualUserAnnualLimit['trading_to_main_transfer_limit'];
                    }else if($transferTo == 'trading'){
                        $remark = 'transfer_from_main_account';
                        $maxTradingLimit = $foundAnnualUserAnnualLimit['main_to_trading_transfer_limit'];
                    }


                    //$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_to_main_account';

                    //$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                    $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"], "conditions"=>[
                        "remark"=>$remark,
                        "user_id"=>$authUserId,
                        "created >= DATE_SUB(DATE(NOW()), INTERVAL 30 DAY)",
                        "cryptocoin_id"=>$coinId]])->hydrate(false)->first();


                    $previouslyTransferAmt = !empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;


                    //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                    if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                        $respArr=['status'=>'false','message'=>"Annual Member Annual Trading Limit Exceeded. Total transferred : ".number_format($previouslyTransferAmt,2)];
                        echo json_encode($respArr); die;
                    }
                }

                // Annual Member Users Daily Limit Start
                //user_id 기본 값 2인 상태에서 0으로 조정 없어도 될꺼같기도..
                $foundAnnualUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>0,
                    "days"=>1,
                    "status"=>'active',
                    "cryptocoin_id"=>$coinId],
                    "order"=>["id"=>"DESC"]])
                    ->hydrate(false)
                    ->first();


                if(!empty($foundAnnualUserOneDayLimit)){

                    $maxTradingLimit = ($transferTo=="trading") ? $foundAnnualUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserOneDayLimit["trading_to_main_transfer_limit"];

                    $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_to_main_account';
                    $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                        "conditions"=>[
                            "remark"=>$remark,
                            "user_id"=>$authUserId,
                            "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 DAY)",
                            "cryptocoin_id"=>$coinId]
                    ])->hydrate(false)->first();

                    $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                    $maxTradingLimitAnnual = ($transferTo=="trading") ? $foundAnnualUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserAnnualLimit["trading_to_main_transfer_limit"];
                    $previouslyTransferAnnual = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"], "conditions"=>[
                        "remark"=>$remark,
                        "user_id"=>$authUserId,
                        "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 YEAR)",
                        "cryptocoin_id"=>$coinId]])->hydrate(false)->first();

                    $previouslyTransferAmtAnnual = !empty($previouslyTransferAnnual['totalTransfer']) ? $previouslyTransferAnnual['totalTransfer'] : 0;


                    //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                    /*if(($previouslyTransferAmt+$amount) > $maxTradingLimit || ($previouslyTransferAmtAnnual+$amount) > $maxTradingLimitAnnual){
                        $respArr=['status'=>'false','message'=>"Annual Member One Day Trading Limit Exceed. Total transferred(문제) : ".number_format($previouslyTransferAmt,2)];
                        echo json_encode($respArr); die;
                    }*/
                }

            }

            else {
                if($coinId == 17 || $coinId == 19 || $coinId == 21){
                    $respArr=['status'=>'false','message'=>"Please get annual membership to be eligible to transfer"];
                    echo json_encode($respArr); die;
                }else {
                    //Single General User One Day Limit
                    $foundSingleUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>[
                        "user_id"=>$authUserId,
                        "days"=>1,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])->hydrate(false)->first();

                    //All Users One Day Limit
                    $foundAllUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>[
                        "user_id"=>0,
                        "days"=>1,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])->hydrate(false)->first();

                    //Single General User One Day Limit
                    $foundSingleUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>[
                        "user_id"=>$authUserId,
                        "days"=>30,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])->hydrate(false)->first();

                    //All Users One Day Limit
                    $foundAllUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>[
                        "user_id"=>0,
                        "days"=>30,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])->hydrate(false)->first();


                    //Single General Annual Limit
                    $foundSingleUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>$authUserId,
                        "days"=>365,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])
                        ->hydrate(false)
                        ->first();

                    //All Users Annual Limit
                    $foundAllUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>0,
                        "days"=>365,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])
                        ->hydrate(false)
                        ->first();

                    if(!empty($foundSingleUserOneDayLimit)){//General User One Day Limit Start

                        $maxTradingLimit = ($transferTo=="trading") ? $foundSingleUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundSingleUserOneDayLimit["trading_to_main_transfer_limit"];

                        $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                        $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>$remark,
                                "user_id"=>$authUserId,
                                "created >="=>$getNightTime,
                                "cryptocoin_id"=>$coinId]])->hydrate(false)->first();

                        $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                        $maxTradingLimitAnnualSingleUser = ($transferTo=="trading") ? $foundSingleUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundSingleUserAnnualLimit["trading_to_main_transfer_limit"];

                        $previouslyTransferAnnualSingleUser = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>$remark,
                                "user_id"=>$authUserId,
                                "created >="=>$getNightTime,
                                "cryptocoin_id"=>$coinId]
                        ])->hydrate(false)->first();

                        $previouslyTransferAmtAnnualSingleUser = !empty($previouslyTransferAnnualSingleUser['totalTransfer']) ? $previouslyTransferAnnualSingleUser['totalTransfer'] : 0;

                        //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                        if(($previouslyTransferAmt+$amount) > $maxTradingLimit || ($previouslyTransferAmtAnnualSingleUser+$amount) > $maxTradingLimitAnnualSingleUser){
                            $respArr=['status'=>'false','message'=>"General User 1 Day Trading Limit Exceeded. Total transferred : ".number_format($previouslyTransferAmt,2)];
                            echo json_encode($respArr); die;
                        }
                    }else{// All Users One Day Limit Start
                        if(!empty($foundAllUserOneDayLimit)){

                            $maxTradingLimit = ($transferTo=="trading") ? $foundAllUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundAllUserOneDayLimit["trading_to_main_transfer_limit"];

                            $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                            $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                                "conditions"=>[
                                    "remark"=>$remark,
                                    "user_id"=>$authUserId,
                                    "created >="=>$getNightTime,
                                    "cryptocoin_id"=>$coinId]
                            ])
                                ->hydrate(false)
                                ->first();

                            $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                            //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                            if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                                $respArr=['status'=>'false','message'=>"All Users Daily Trading Limit Exceeded. Total transferred : ".number_format($previouslyTransferAmt,2)];
                                echo json_encode($respArr); die;
                            }
                        }
                    }


                    if(!empty($foundSingleUserAnnualLimit)){// General User Yearly Limit Start

                        $maxTradingLimit = ($transferTo=="trading") ? $foundSingleUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundSingleUserAnnualLimit["trading_to_main_transfer_limit"];

                        $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                        $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>$remark,
                                "user_id"=>$authUserId,
                                "created >="=>$getNightTime,
                                "cryptocoin_id"=>$coinId]
                        ])->hydrate(false)->first();

                        $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                        //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                        if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                            $respArr=['status'=>'false','message'=>"General User Yearly Trading Limit Exceeded. Total transferred : ".number_format($previouslyTransferAmt,2)];
                            echo json_encode($respArr); die;
                        }
                    }else {
                        if(!empty($foundAllUserAnnualLimit)){// All Users Annual Limit Start

                            $maxTradingLimit = ($transferTo=="trading") ? $foundAllUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAllUserAnnualLimit["trading_to_main_transfer_limit"];

                            $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                            $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                                "conditions"=>[
                                    "remark"=>$remark,
                                    "user_id"=>$authUserId,
                                    "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 Year)",
                                    "cryptocoin_id"=>$coinId]])
                                ->hydrate(false)
                                ->first();

                            $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                            //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                            if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                                $respArr=['status'=>'false','message'=>"All Users Annual Trading Limit Exceeded. Total transferred : ".number_format($previouslyTransferAmt,2)];
                                echo json_encode($respArr); die;
                            }
                        }
                    }
                }

            }
//			if($annualMemberShip=="Y"){
//				// single Annul User One Day Limit Start
//				$foundSingleAnnualUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>2,
//																								"days"=>30,
//																								"status"=>'active',
//																								"cryptocoin_id"=>$coinId],
//																	"order"=>["id"=>"DESC"]])
//																	->hydrate(false)
//																	->first();
//
//				if(!empty($foundSingleAnnualUserOneDayLimit)){
//
//
//						$maxTradingLimit = ($transferTo=="trading") ? $foundSingleAnnualUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundSingleAnnualUserOneDayLimit["trading_to_main_transfer_limit"];
//
//						$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
//						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
//																	   "conditions"=>[
//																			"remark"=>$remark,
//																			"user_id"=>$authUserId,
//																			"created >= DATE_SUB(DATE(NOW()), INTERVAL 30 DAY)",
//																			"cryptocoin_id"=>$coinId]
//																		])
//																	   ->hydrate(false)
//																	   ->first();
//
//						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
//
//						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
//						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
//							$respArr=['status'=>'false','message'=>"Trading Limit Exceed. Today total transferred : ".$previouslyTransferAmt];
//							echo json_encode($respArr); die;
//						}
//
//
//				}
//			}
//			else {
//
//				// single User One Day Limit Start
//				$foundSingleUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>$authUserId,
//																								"days"=>1,
//																								"status"=>'active',
//																								"cryptocoin_id"=>$coinId],
//																	"order"=>["id"=>"DESC"]])
//																	->hydrate(false)
//																	->first();
//
//				if(!empty($foundSingleUserOneDayLimit)){
//
//
//						$maxTradingLimit = ($transferTo=="trading") ? $foundSingleUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundSingleUserOneDayLimit["trading_to_main_transfer_limit"];
//
//						$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
//						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
//																	   "conditions"=>[
//																			"remark"=>$remark,
//																			"user_id"=>$authUserId,
//																			"created >="=>$getNightTime,
//																			"cryptocoin_id"=>$coinId]
//																		])
//																	   ->hydrate(false)
//																	   ->first();
//
//						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
//
//						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
//						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
//							$respArr=['status'=>'false','message'=>"Trading Limit Exceed. Today total transferred : ".$previouslyTransferAmt];
//							echo json_encode($respArr); die;
//						}
//
//
//				}
//				else {
//
//					// all User One Day Limit Start
//					$foundAllUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>0,
//																								"days"=>1,
//																								"status"=>'active',
//																						   "cryptocoin_id"=>$coinId],
//																			"order"=>["id"=>"DESC"]])
//																		   ->hydrate(false)
//																		   ->first();
//					if(!empty($foundAllUserOneDayLimit)){
//
//						$maxTradingLimit = ($transferTo=="trading") ? $foundAllUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundAllUserOneDayLimit["trading_to_main_transfer_limit"];
//
//						$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
//						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
//																	   "conditions"=>[
//																			"remark"=>$remark,
//																			"user_id"=>$authUserId,
//																			"created >="=>$getNightTime,
//																			"cryptocoin_id"=>$coinId]
//																		])
//																	   ->hydrate(false)
//																	   ->first();
//
//						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
//
//						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
//						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
//							$respArr=['status'=>'false','message'=>"Max Trading Limit Exceed. Today total transferred : ".$previouslyTransferAmt];
//							echo json_encode($respArr); die;
//						}
//
//					}
//
//
//				}
//			}

            // deduct balance

            /**
             * 트레이딩 계정 관련
             */
            if($transferTo=="trading"){ //메인계정에서 -> 트레이딩계정으로 보낼경우
                $tonightDay = date("Y-m-d 00:00:00");
                $adminFee = $this->Users->getAdninFee("main_to_trading_transfer_fee");
                // get transfer fee

                $findFromNumFourSetting = $this->NumberFourSetting->find('all',['conditions'=>['user_id'=>0,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();

                if(!empty($findFromNumFourSetting) && !empty($findFromNumFourSetting["main_to_trading_transfer_fee"])){
                    $adminFee = $findFromNumFourSetting["main_to_trading_transfer_fee"];
                }

                $findFromNumThreeSetting = $this->NumberThreeSetting->find('all',['conditions'=>['user_id'=>$authUserId,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
                if(!empty($findFromNumThreeSetting) && !empty($findFromNumThreeSetting['days'])){
                    $getDays = $findFromNumThreeSetting['days'];
                    $getThreeSettingDate = date("Y-m-d H:i:s",strtotime($findFromNumThreeSetting["created"]));
                    $getFeeNextTime = date('Y-m-d H:i:s',strtotime($getThreeSettingDate.' + '.$getDays.' days'));
                    if($cuDateTime < $getFeeNextTime){
                        $adminFee = $findFromNumThreeSetting["user_fee"];
                    }
                }
                $adminFeeAmt = $amount*$adminFee/100;

                $getMainBalance = $this->Users->getUserPricipalBalance($authUserId,$coinId);

                if($getMainBalance<$amount){
                    $respArr=['status'=>'false','message'=>"insufficient balance",'data'=>["balance"=>$getMainBalance]];
                    echo json_encode($respArr); die;
                }

                $maxTradingLimit = 0;
                $minTradingLimit = 0;
                $checkOrderAfterDate = "";
                $singleUserSettingApplied = false;


                // single User One Day Limit Start
                $foundSingleUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>$authUserId,
                    "days"=>1,
                    "status"=>'active',
                    "cryptocoin_id"=>$coinId],
                    "order"=>["id"=>"DESC"]])
                    ->hydrate(false)
                    ->first();

                if(!empty($foundSingleUserOneDayLimit)){
                    $singleUserSettingApplied = true;
                    $foundDays = $foundSingleUserOneDayLimit['days'];
                    $existDate = date("Y-m-d H:i:s",strtotime($foundSingleUserOneDayLimit["created"]));
                    $getNextTime = date('Y-m-d H:i:s',strtotime($existDate.' + '.$foundDays.' days'));
                    if($cuDateTime < $getNextTime){
                        $checkOrderAfterDate = $foundSingleUserOneDayLimit["created"];
                        $maxTradingLimit = $foundSingleUserOneDayLimit["max_limit"];
                        $minTradingLimit = $foundSingleUserOneDayLimit["min_limit"];

                        $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>"transfer_from_main_account",
                                "created >="=>$checkOrderAfterDate,
                                "cryptocoin_id"=>$coinId]
                        ])
                            ->hydrate(false)
                            ->first();

                        $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                        //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                        if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                            $respArr=['status'=>'false','message'=>"Max Trading Limit Exceed"];
                            echo json_encode($respArr); die;
                        }
                        if(($amount) < $minTradingLimit){
                            $respArr=['status'=>'false','message'=>"Min Trading Limit Exceed"];
                            echo json_encode($respArr); die;
                        }
                    }
                }
                //print_r($foundSingleUserOneDayLimit); die;




                // deduct balance from main account
                $deductBalanceArr=['amount'=>-$amount,
                    'status'=>'completed',
                    'type'=>'transfer_to_trading_account',
                    'fees'=>$adminFeeAmt,
                    'user_id'=>$authUserId,
                    'wallet_address'=>$userWalletAddr,
                    'cryptocoin_id'=>$coinId];
                $newObj = $this->PrincipalWallet->newEntity();
                $newObj = $this->PrincipalWallet->patchEntity($newObj,$deductBalanceArr);
                $saveThisData = $this->PrincipalWallet->save($newObj);
                if($saveThisData){
                    $remainingAmt =  $amount-$adminFeeAmt;
                    // add balance from trading account
                    $deductBalanceArr=['coin_amount'=>$remainingAmt,
                        'status'=>'completed',
                        'tx_type'=>'purchase',
                        'fees'=>$adminFeeAmt,
                        'remark'=>'transfer_from_main_account',
                        'wallet_address'=>$userWalletAddr,
                        'user_id'=>$authUserId,
                        'cryptocoin_id'=>$coinId];
                    $newObj = $this->Transactions->newEntity();
                    $newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArr);
                    $saveThisData = $this->Transactions->save($newObj);
                    if($saveThisData){

                        // add fee to admin
                        $deductBalanceArrFee=['coin_amount'=>$adminFeeAmt,
                            'status'=>'completed',
                            'tx_type'=>'purchase',
                            'fees'=>$adminFeeAmt,
                            'remark'=>'adminTranferFees',
                            'user_id'=>1,
                            'cryptocoin_id'=>$coinId];
                        $newObj = $this->Transactions->newEntity();
                        $newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArrFee);
                        $saveThisData = $this->Transactions->save($newObj);

                        $respArr=['status'=>'true','message'=>"amount transferred to trading account"];
                        echo json_encode($respArr); die;
                    }
                    else {
                        $respArr=['status'=>'false','message'=>"Unable to transfer amount to trading account"];
                        echo json_encode($respArr); die;
                    }
                }
                else {
                    $respArr=['status'=>'false','message'=>"Unable to deduct amount from main account"];
                    echo json_encode($respArr); die;
                }


            }
            else if($transferTo=="main"){ //여긴가 출금 하는 부분 체크
                $adminFee = $this->Users->getAdninFee("trading_to_main_transfer_fee");
                // get transfer fee

                $findFromNumFourSetting = $this->NumberFourSetting->find('all',['conditions'=>['user_id'=>0,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();

                if(!empty($findFromNumFourSetting) && !empty($findFromNumFourSetting["tranding_to_main_transfer_fee"])){
                    $adminFee = $findFromNumFourSetting["tranding_to_main_transfer_fee"];
                }

                $findFromNumThreeSetting = $this->NumberThreeSetting->find('all',['conditions'=>['user_id'=>$authUserId,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
                if(!empty($findFromNumThreeSetting) && !empty($findFromNumThreeSetting['days'])){
                    $getDays = $findFromNumThreeSetting['days'];
                    $getThreeSettingDate = date("Y-m-d H:i:s",strtotime($findFromNumThreeSetting["created"]));
                    $getFeeNextTime = date('Y-m-d H:i:s',strtotime($getThreeSettingDate.' + '.$getDays.' days'));
                    if($cuDateTime < $getFeeNextTime){
                        $adminFee = $findFromNumThreeSetting["user_fee"];
                    }
                }
                $adminFeeAmt = $amount*$adminFee/100;

                $geTradingtBalance = $this->Users->getLocalUserBalance($authUserId,$coinId);;
                if($geTradingtBalance<$amount){
                    $respArr=['status'=>'false','message'=>"insufficient balance",'data'=>["balance"=>$geTradingtBalance]];
                    echo json_encode($respArr); die;
                }
                // deduct balance from trading account
                $deductBalanceArr=['coin_amount'=>-$amount,
                    'status'=>'completed',
                    'tx_type'=>'withdrawal',
                    'remark'=>'transfer_to_main_account',
                    'fees'=>$adminFeeAmt,
                    'wallet_address'=>$userWalletAddr,
                    'user_id'=>$authUserId,
                    'cryptocoin_id'=>$coinId];
                $newObj = $this->Transactions->newEntity();
                $newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArr);
                $saveThisData = $this->Transactions->save($newObj);
                if($saveThisData){
                    $remainingAmt =  $amount-$adminFeeAmt;
                    // add balance from main account
                    $deductBalanceArr=['amount'=>$remainingAmt,
                        'status'=>'completed',
                        'type'=>'transfer_from_trading_account',
                        'fees'=>$adminFeeAmt,
                        'user_id'=>$authUserId,
                        'wallet_address'=>$userWalletAddr,
                        'cryptocoin_id'=>$coinId];
                    $newObj = $this->PrincipalWallet->newEntity();
                    $newObj = $this->PrincipalWallet->patchEntity($newObj,$deductBalanceArr);
                    $saveThisData = $this->PrincipalWallet->save($newObj);
                    if($saveThisData){
                        // add fee to admin
                        $deductBalanceArrFee=['coin_amount'=>$adminFeeAmt,
                            'status'=>'completed',
                            'tx_type'=>'purchase',
                            'remark'=>'adminTranferFees',
                            'fees'=>$adminFeeAmt,
                            'user_id'=>1,
                            'cryptocoin_id'=>$coinId];
                        $newObj = $this->Transactions->newEntity();
                        $newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArrFee);
                        $saveThisData = $this->Transactions->save($newObj);

                        $respArr=['status'=>'true','message'=>"amount transferred to main account"];
                        echo json_encode($respArr); die;
                    }
                    else {
                        $respArr=['status'=>'false','message'=>"Unable to transfer amount to main account"];
                        echo json_encode($respArr); die;
                    }
                }
                else {
                    $respArr=['status'=>'false','message'=>"Unable to deduct amount from trading account"];
                    echo json_encode($respArr); die;
                }


            }


            $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
            $intrAddress  = $this->Auth->user('intr_address');
            $this->set('intrAddress',$intrAddress);

            $this->set('currentUserId',$authUserId);

            $getUserTotalCoin = $this->Transactions->find();
            $getUserTotalCoinCnt = $getUserTotalCoin
                ->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id','cryptocoin.short_name','cryptocoin.icon'])
                ->contain('cryptocoin')
                ->where(['Transactions.user_id'=>$authUserId,'Transactions.status'=>'completed','Transactions.tx_type !='=>'bank_initial_deposit'])
                ->group('cryptocoin_id')
                ->toArray();


            $this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);


            $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
            $this->set('getCoinList',$getCoinList);
        }
        else {
            $respArr=['status'=>'false','message'=>"Invalid Request"];
            echo json_encode($respArr); die;
        }
    }
	public function transgetToAccountNew2(){
		$this->loadModel('Transactions');
		$this->loadModel('Cryptocoin');
		$this->loadModel('PrincipalWallet');
		$this->loadModel('TransferLimits');
		$this->loadModel('NumberFourSetting');
		$this->loadModel('NumberThreeSetting');
		$this->loadModel('TransferLimits');
		$this->loadModel('Users');
		if ($this->request->is('ajax')){
		
			$cuDateTime = date("Y-m-d H:i:s");
            $getNightTime  = date("Y-m-d 00:00:00");
			
			$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1,
			'OR' => array(
				'short_name' => $this->request->data['coin_id'],
				'name' => $this->request->data['coin_id'],
			)]])->hydrate(false)->first();
			$authUserId = $this->Auth->user('id');
			
			/* if(!isset($this->request->data['amount']) || !isset($this->request->data['transfer_to']) || !isset(coin_id)){
				$respArr=['status'=>'false','message'=>"Amount, transfer Type and coin is required"];
				echo json_encode($respArr); die;
			} */
			$amount = $this->request->data['amount'];
			$transferTo = $this->request->data['transfer_to'];
			$coinId = $getCoinList['id'];
			
			$userDetails = $this->Users->find("all",["conditions"=>['id'=>$authUserId]])->hydrate(false)->first();
            $annualMemberShip = $userDetails['annual_membership'];
			$btcWalletAddr = $userDetails['btc_address'];
			$ethWalletAddr = $userDetails['eth_address'];
			
			$userWalletAddr = ($coinId==1) ? $btcWalletAddr : $ethWalletAddr;
			
			
			if(empty($amount) || empty($transferTo) || empty($coinId)){
				$respArr=['status'=>'false','message'=>__('All the fields are required')];
				echo json_encode($respArr); die;
			}
			else if($amount<=0){
				$respArr=['status'=>'false','message'=>__('Amount should be greater than 0')];
				echo json_encode($respArr); die;
			}
			else if(!in_array($transferTo,['trading','main'])){
				$respArr=['status'=>'false','message'=>__('Invalid transfer type')];
				echo json_encode($respArr); die;
			}
			
			$getCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$coinId]])->hydrate(false)->first();
			if(empty($getCoinDetail)){
				$respArr=['status'=>'false','message'=>__('Invalid currency')];
				echo json_encode($respArr); die;
			}
			$tradingLimit = $getCoinDetail['mainwallet_to_tradingwallet_transfer_limit'];

            if($annualMemberShip=="Y"){
                // Annual Member Users Yearly Limit Start
                $foundAnnualUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>2,
                    "days"=>365,
                    "status"=>'active',
                    "cryptocoin_id"=>$coinId],
                    "order"=>["id"=>"DESC"]])
                    ->hydrate(false)
                    ->first();

                if(!empty($foundAnnualUserAnnualLimit)){

                    $maxTradingLimit = ($transferTo=="trading") ? $foundAnnualUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserAnnualLimit["trading_to_main_transfer_limit"];

                    $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                    $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"], "conditions"=>[
                        "remark"=>$remark,
                        "user_id"=>$authUserId,
                        "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 YEAR)",
                        "cryptocoin_id"=>$coinId]])->hydrate(false)->first();

                    $previouslyTransferAmt = !empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                    //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                    /*if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                        $respArr=['status'=>'false','message'=>__('Annual Member Annual Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                        echo json_encode($respArr); die;
                    }*/
                }
                // Annual Member Users Daily Limit Start
                $foundAnnualUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>2,
                    "days"=>1,
                    "status"=>'active',
                    "cryptocoin_id"=>$coinId],
                    "order"=>["id"=>"DESC"]])
                    ->hydrate(false)
                    ->first();

                if(!empty($foundAnnualUserOneDayLimit)){

                    $maxTradingLimit = ($transferTo=="trading") ? $foundAnnualUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserOneDayLimit["trading_to_main_transfer_limit"];

                    $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                    $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                        "conditions"=>[
                            "remark"=>$remark,
                            "user_id"=>$authUserId,
                            "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 DAY)",
                            "cryptocoin_id"=>$coinId]
                    ])->hydrate(false)->first();

                    $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                    $maxTradingLimitAnnual = ($transferTo=="trading") ? $foundAnnualUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAnnualUserAnnualLimit["trading_to_main_transfer_limit"];
                    $previouslyTransferAnnual = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"], "conditions"=>[
                        "remark"=>$remark,
                        "user_id"=>$authUserId,
                        "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 YEAR)",
                        "cryptocoin_id"=>$coinId]])->hydrate(false)->first();

                    $previouslyTransferAmtAnnual = !empty($previouslyTransferAnnual['totalTransfer']) ? $previouslyTransferAnnual['totalTransfer'] : 0;


                    //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                    //연간회원제거
                    /*if(($previouslyTransferAmt+$amount) > $maxTradingLimit || ($previouslyTransferAmtAnnual+$amount) > $maxTradingLimitAnnual){
                        $respArr=['status'=>'false','message'=>__('Annual Member One Day Trading Limit Exceed. Total transferred: ').number_format($previouslyTransferAmt,2)];
                        echo json_encode($respArr); die;
                    }*/
                }
            }

            else {
                if($coinId == 17 || $coinId == 19 || $coinId == 21){
                    $respArr=['status'=>'false','message'=>__('Please get annual membership to be eligible to transfer')];
                    echo json_encode($respArr); die;
                }else {
                    //Single General User One Day Limit
                    $foundSingleUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>[
                        "user_id"=>$authUserId,
                        "days"=>1,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])->hydrate(false)->first();

                    //All Users One Day Limit
                    $foundAllUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>[
                        "user_id"=>0,
                        "days"=>1,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])->hydrate(false)->first();

                    //Single General Annual Limit
                    $foundSingleUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>$authUserId,
                        "days"=>365,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])
                        ->hydrate(false)
                        ->first();

                    //All Users Annual Limit
                    $foundAllUserAnnualLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>0,
                        "days"=>365,
                        "status"=>'active',
                        "cryptocoin_id"=>$coinId],
                        "order"=>["id"=>"DESC"]])
                        ->hydrate(false)
                        ->first();

                    if(!empty($foundSingleUserOneDayLimit)){//General User One Day Limit Start

                        $maxTradingLimit = ($transferTo=="trading") ? $foundSingleUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundSingleUserOneDayLimit["trading_to_main_transfer_limit"];

                        $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                        $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>$remark,
                                "user_id"=>$authUserId,
                                "created >="=>$getNightTime,
                                "cryptocoin_id"=>$coinId]])->hydrate(false)->first();

                        $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                        $maxTradingLimitAnnualSingleUser = ($transferTo=="trading") ? $foundSingleUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundSingleUserAnnualLimit["trading_to_main_transfer_limit"];

                        $previouslyTransferAnnualSingleUser = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>$remark,
                                "user_id"=>$authUserId,
                                "created >="=>$getNightTime,
                                "cryptocoin_id"=>$coinId]
                        ])->hydrate(false)->first();

                        $previouslyTransferAmtAnnualSingleUser = !empty($previouslyTransferAnnualSingleUser['totalTransfer']) ? $previouslyTransferAnnualSingleUser['totalTransfer'] : 0;

                        //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                        if(($previouslyTransferAmt+$amount) > $maxTradingLimit || ($previouslyTransferAmtAnnualSingleUser+$amount) > $maxTradingLimitAnnualSingleUser){
                            $respArr=['status'=>'false','message'=>__('General User 1 Day Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                            echo json_encode($respArr); die;
                        }
                    }else{// All Users One Day Limit Start
                        if(!empty($foundAllUserOneDayLimit)){

                            $maxTradingLimit = ($transferTo=="trading") ? $foundAllUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundAllUserOneDayLimit["trading_to_main_transfer_limit"];

                            $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                            $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                                "conditions"=>[
                                    "remark"=>$remark,
                                    "user_id"=>$authUserId,
                                    "created >="=>$getNightTime,
                                    "cryptocoin_id"=>$coinId]
                            ])
                                ->hydrate(false)
                                ->first();

                            $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                            //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                            /*if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                                $respArr=['status'=>'false','message'=>__('All Users Daily Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                                echo json_encode($respArr); die;
                            }*/
                        }
                    }



                    if(!empty($foundSingleUserAnnualLimit)){// General User Yearly Limit Start

                        $maxTradingLimit = ($transferTo=="trading") ? $foundSingleUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundSingleUserAnnualLimit["trading_to_main_transfer_limit"];

                        $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                        $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                            "conditions"=>[
                                "remark"=>$remark,
                                "user_id"=>$authUserId,
                                "created >="=>$getNightTime,
                                "cryptocoin_id"=>$coinId]
                        ])->hydrate(false)->first();

                        $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                        //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                        if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                            $respArr=['status'=>'false','message'=>__('General User Yearly Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                            echo json_encode($respArr); die;
                        }
                    }else {
                        if(!empty($foundAllUserAnnualLimit)){// All Users Annual Limit Start

                            $maxTradingLimit = ($transferTo=="trading") ? $foundAllUserAnnualLimit["main_to_trading_transfer_limit"] :  $foundAllUserAnnualLimit["trading_to_main_transfer_limit"];

                            $remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
                            $previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
                                "conditions"=>[
                                    "remark"=>$remark,
                                    "user_id"=>$authUserId,
                                    "created >= DATE_SUB(DATE(NOW()), INTERVAL 1 Year)",
                                    "cryptocoin_id"=>$coinId]])
                                ->hydrate(false)
                                ->first();

                            $previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;

                            //if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
                            if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
                                $respArr=['status'=>'false','message'=>__('All Users Annual Trading Limit Exceeded. Total transferred: ').number_format($previouslyTransferAmt,2)];
                                echo json_encode($respArr); die;
                            }
                        }
                    }
                }

            }

//			if($annualMemberShip=="Y"){
//				// single Annul User One Day Limit Start
//				$foundSingleAnnualUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>2,
//																								"days"=>30,
//																								"status"=>'active',
//																								"cryptocoin_id"=>$coinId],
//																	"order"=>["id"=>"DESC"]])
//																	->hydrate(false)
//																	->first();
//
//				if(!empty($foundSingleAnnualUserOneDayLimit)){
//
//
//						$maxTradingLimit = ($transferTo=="trading") ? $foundSingleAnnualUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundSingleAnnualUserOneDayLimit["trading_to_main_transfer_limit"];
//
//						$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
//						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
//																	   "conditions"=>[
//																			"remark"=>$remark,
//																			"user_id"=>$authUserId,
//																			"created >= DATE_SUB(DATE(NOW()), INTERVAL 30 DAY)",
//																			"cryptocoin_id"=>$coinId]
//																		])
//																	   ->hydrate(false)
//																	   ->first();
//
//						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
//
//						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
//						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
//							$respArr=['status'=>'false','message'=>"Trading Limit Exceed. Today total transferred : ".$previouslyTransferAmt];
//							echo json_encode($respArr); die;
//						}
//
//
//				}
//			}
//			else {
//
//				// single User One Day Limit Start
//				$foundSingleUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>$authUserId,
//																								"days"=>1,
//																								"status"=>'active',
//																								"cryptocoin_id"=>$coinId],
//																	"order"=>["id"=>"DESC"]])
//																	->hydrate(false)
//																	->first();
//
//				if(!empty($foundSingleUserOneDayLimit)){
//
//
//						$maxTradingLimit = ($transferTo=="trading") ? $foundSingleUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundSingleUserOneDayLimit["trading_to_main_transfer_limit"];
//
//						$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
//						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
//																	   "conditions"=>[
//																			"remark"=>$remark,
//																			"user_id"=>$authUserId,
//																			"created >="=>$getNightTime,
//																			"cryptocoin_id"=>$coinId]
//																		])
//																	   ->hydrate(false)
//																	   ->first();
//
//						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
//
//						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
//						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
//							$respArr=['status'=>'false','message'=>"Trading Limit Exceed. Today total transferred : ".$previouslyTransferAmt];
//							echo json_encode($respArr); die;
//						}
//
//
//				}
//				else {
//
//					// all User One Day Limit Start
//					$foundAllUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>0,
//																								"days"=>1,
//																								"status"=>'active',
//																						   "cryptocoin_id"=>$coinId],
//																			"order"=>["id"=>"DESC"]])
//																		   ->hydrate(false)
//																		   ->first();
//					if(!empty($foundAllUserOneDayLimit)){
//
//						$maxTradingLimit = ($transferTo=="trading") ? $foundAllUserOneDayLimit["main_to_trading_transfer_limit"] :  $foundAllUserOneDayLimit["trading_to_main_transfer_limit"];
//
//						$remark = ($transferTo=="trading") ? 'transfer_from_main_account' : 'transfer_from_trading_account';
//						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
//																	   "conditions"=>[
//																			"remark"=>$remark,
//																			"user_id"=>$authUserId,
//																			"created >="=>$getNightTime,
//																			"cryptocoin_id"=>$coinId]
//																		])
//																	   ->hydrate(false)
//																	   ->first();
//
//						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
//
//						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
//						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
//							$respArr=['status'=>'false','message'=>"Max Trading Limit Exceed. Today total transferred : ".$previouslyTransferAmt];
//							echo json_encode($respArr); die;
//						}
//
//					}
//
//
//				}
//			}
			
			// deduct balance
			if($transferTo=="trading"){
				$tonightDay = date("Y-m-d 00:00:00");
				$adminFee = $this->Users->getAdninFee("main_to_trading_transfer_fee");
				// get transfer fee
				
				$findFromNumFourSetting = $this->NumberFourSetting->find('all',['conditions'=>['user_id'=>0,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
				
				if(!empty($findFromNumFourSetting) && !empty($findFromNumFourSetting["main_to_trading_transfer_fee"])){
					$adminFee = $findFromNumFourSetting["main_to_trading_transfer_fee"];
				}
				
				$findFromNumThreeSetting = $this->NumberThreeSetting->find('all',['conditions'=>['user_id'=>$authUserId,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
				if(!empty($findFromNumThreeSetting) && !empty($findFromNumThreeSetting['days'])){
					$getDays = $findFromNumThreeSetting['days'];
					$getThreeSettingDate = date("Y-m-d H:i:s",strtotime($findFromNumThreeSetting["created"]));
					$getFeeNextTime = date('Y-m-d H:i:s',strtotime($getThreeSettingDate.' + '.$getDays.' days'));
					if($cuDateTime < $getFeeNextTime){
						$adminFee = $findFromNumThreeSetting["user_fee"];
					}
				}
				$adminFeeAmt = $amount*$adminFee/100;
				
				$getMainBalance = $this->Users->getUserPricipalBalance($authUserId,$coinId);
				
				if($getMainBalance<$amount){
					$respArr=['status'=>'false','message'=>__('Insufficient Balance'),'data'=>["balance"=>$getMainBalance]];
					echo json_encode($respArr); die;
				}
				
				$maxTradingLimit = 0;
				$minTradingLimit = 0;
				$checkOrderAfterDate = "";
				$singleUserSettingApplied = false;
								
																	   
				
				
				// single User One Day Limit Start 
				$foundSingleUserOneDayLimit = $this->TransferLimits->find("all",["conditions"=>["user_id"=>$authUserId,
																								"days"=>1,
																								"status"=>'active',
																					            "cryptocoin_id"=>$coinId],
																	"order"=>["id"=>"DESC"]])
																	->hydrate(false)
																	->first();
				
				if(!empty($foundSingleUserOneDayLimit)){
					$singleUserSettingApplied = true;
					$foundDays = $foundSingleUserOneDayLimit['days'];
					$existDate = date("Y-m-d H:i:s",strtotime($foundSingleUserOneDayLimit["created"]));
					$getNextTime = date('Y-m-d H:i:s',strtotime($existDate.' + '.$foundDays.' days'));
					if($cuDateTime < $getNextTime){
						$checkOrderAfterDate = $foundSingleUserOneDayLimit["created"];
						$maxTradingLimit = $foundSingleUserOneDayLimit["max_limit"];
						$minTradingLimit = $foundSingleUserOneDayLimit["min_limit"];
						
						$previouslyTransfer = $this->Transactions->find("all",["fields"=>["totalTransfer"=>"SUM(coin_amount)"],
																	   "conditions"=>[
																			"remark"=>"transfer_from_main_account",
																			"created >="=>$checkOrderAfterDate,
																			"cryptocoin_id"=>$coinId]
																		])
																	   ->hydrate(false)
																	   ->first();
															   
						$previouslyTransferAmt = 	!empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;	
						
						//if(in_array($coinId,[17,19,21]) && ($previouslyTransferAmt+$amount) > $tradingLimit){
						if(($previouslyTransferAmt+$amount) > $maxTradingLimit){
							$respArr=['status'=>'false','message'=>__('Max Trading Limit Exceed')];
							echo json_encode($respArr); die;
						}	
						if(($amount) < $minTradingLimit){
							$respArr=['status'=>'false','message'=>__('Min Trading Limit Exceed')];
							echo json_encode($respArr); die;
						}	
					}
				} 
				//print_r($foundSingleUserOneDayLimit); die;
				
			
				
				
				// deduct balance from main account
				$deductBalanceArr=['amount'=>-$amount,
								   'status'=>'completed',
								   'type'=>'transfer_to_trading_account',
                                    'fees'=>$adminFeeAmt,
								   'user_id'=>$authUserId,
								   'wallet_address'=>$userWalletAddr,
								   'cryptocoin_id'=>$coinId];
				$newObj = $this->PrincipalWallet->newEntity();
				$newObj = $this->PrincipalWallet->patchEntity($newObj,$deductBalanceArr);
				$saveThisData = $this->PrincipalWallet->save($newObj);
				if($saveThisData){
					$remainingAmt =  $amount-$adminFeeAmt;
					// add balance from trading account
					$deductBalanceArr=['coin_amount'=>$remainingAmt,
									   'status'=>'completed',
									   'tx_type'=>'purchase',
                                        'fees'=>$adminFeeAmt,
									   'remark'=>'transfer_from_main_account',
									   'wallet_address'=>$userWalletAddr,
									   'user_id'=>$authUserId,
									   'cryptocoin_id'=>$coinId];
					$newObj = $this->Transactions->newEntity();
					$newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArr);
					$saveThisData = $this->Transactions->save($newObj);
					if($saveThisData){
						
						// add fee to admin 
						$deductBalanceArrFee=['coin_amount'=>$adminFeeAmt,
											   'status'=>'completed',
											   'tx_type'=>'purchase',
                                                'fees'=>$adminFeeAmt,
											   'remark'=>'adminTranferFees',
											   'user_id'=>1,
											   'cryptocoin_id'=>$coinId];
						$newObj = $this->Transactions->newEntity();
						$newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArrFee);
						$saveThisData = $this->Transactions->save($newObj);
						
						$respArr=['status'=>'true','message'=>__('Amount transferred successfully to the trading account')];
						echo json_encode($respArr); die;
					}
					else {
						$respArr=['status'=>'false','message'=>__('Unable to transfer this amount to the trading account')];
						echo json_encode($respArr); die;
					}
				}
				else {
					$respArr=['status'=>'false','message'=>__('Unable to deduct this amount from the main account')];
					echo json_encode($respArr); die;
				}
				
				
			}
			else if($transferTo=="main"){
				$adminFee = $this->Users->getAdninFee("trading_to_main_transfer_fee");
				// get transfer fee
				
				$findFromNumFourSetting = $this->NumberFourSetting->find('all',['conditions'=>['user_id'=>0,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
				
				if(!empty($findFromNumFourSetting) && !empty($findFromNumFourSetting["tranding_to_main_transfer_fee"])){
					$adminFee = $findFromNumFourSetting["tranding_to_main_transfer_fee"];
				}
				
				$findFromNumThreeSetting = $this->NumberThreeSetting->find('all',['conditions'=>['user_id'=>$authUserId,'cryptocoin_id'=>$coinId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
				if(!empty($findFromNumThreeSetting) && !empty($findFromNumThreeSetting['days'])){
					$getDays = $findFromNumThreeSetting['days'];
					$getThreeSettingDate = date("Y-m-d H:i:s",strtotime($findFromNumThreeSetting["created"]));
					$getFeeNextTime = date('Y-m-d H:i:s',strtotime($getThreeSettingDate.' + '.$getDays.' days'));
					if($cuDateTime < $getFeeNextTime){
						$adminFee = $findFromNumThreeSetting["user_fee"];
					}
				}
				$adminFeeAmt = $amount*$adminFee/100;
				
				$geTradingtBalance = $this->Users->getLocalUserBalance($authUserId,$coinId);;
				if($geTradingtBalance<$amount){
					$respArr=['status'=>'false','message'=>__('Insufficient Balance'),'data'=>["balance"=>$geTradingtBalance]];
					echo json_encode($respArr); die;
				}
				// deduct balance from trading account
				$deductBalanceArr=['coin_amount'=>-$amount,
								   'status'=>'completed',
								   'tx_type'=>'withdrawal',
								   'remark'=>'transfer_to_main_account',
                                    'fees'=>$adminFeeAmt,
								   'wallet_address'=>$userWalletAddr,
								   'user_id'=>$authUserId,
								   'cryptocoin_id'=>$coinId];
				$newObj = $this->Transactions->newEntity();
				$newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArr);
				$saveThisData = $this->Transactions->save($newObj);
				if($saveThisData){
					$remainingAmt =  $amount-$adminFeeAmt;
					// add balance from main account
					$deductBalanceArr=['amount'=>$remainingAmt,
									   'status'=>'completed',
									   'type'=>'transfer_from_trading_account',
                                        'fees'=>$adminFeeAmt,
									   'user_id'=>$authUserId,
									   'wallet_address'=>$userWalletAddr,
									   'cryptocoin_id'=>$coinId];
					$newObj = $this->PrincipalWallet->newEntity();
					$newObj = $this->PrincipalWallet->patchEntity($newObj,$deductBalanceArr);
					$saveThisData = $this->PrincipalWallet->save($newObj);
					if($saveThisData){
						// add fee to admin 
						$deductBalanceArrFee=['coin_amount'=>$adminFeeAmt,
											   'status'=>'completed',
											   'tx_type'=>'purchase',
											   'remark'=>'adminTranferFees',
                                                'fees'=>$adminFeeAmt,
											   'user_id'=>1,
											   'cryptocoin_id'=>$coinId];
						$newObj = $this->Transactions->newEntity();
						$newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArrFee);
						$saveThisData = $this->Transactions->save($newObj);
						
						$respArr=['status'=>'true','message'=>__('Amount transferred to the main account')];
						echo json_encode($respArr); die;
					}
					else {
						$respArr=['status'=>'false','message'=>__('Unable to transfer this amount to the main account')];
						echo json_encode($respArr); die;
					}
				}
				else {
					$respArr=['status'=>'false','message'=>__('Unable to deduct this amount from the trading account')];
					echo json_encode($respArr); die;
				}
				
				
			}
			
			
			$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
			$intrAddress  = $this->Auth->user('intr_address');
			$this->set('intrAddress',$intrAddress);
			
			$this->set('currentUserId',$authUserId);
			
			$getUserTotalCoin = $this->Transactions->find(); 
			$getUserTotalCoinCnt = $getUserTotalCoin
										->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id','cryptocoin.short_name','cryptocoin.icon'])
										->contain('cryptocoin')
										->where(['Transactions.user_id'=>$authUserId,'Transactions.status'=>'completed','Transactions.tx_type !='=>'bank_initial_deposit'])
										->group('cryptocoin_id')
										->toArray();

			
			$this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);
			
			
			$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
			$this->set('getCoinList',$getCoinList);
		}
		else {
			$respArr=['status'=>'false','message'=>__('Invalid Request')];
			echo json_encode($respArr); die;
	 	}
	 }
}
