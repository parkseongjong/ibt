<?php
namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

use Cake\Auth\AbstractPasswordHasher;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
use Google_Client;
use Google_Service_Plus;
use Google_Service_Oauth2;

class AssetsController extends AppController
{
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		$this->loadModel('WithdrawalWalletAddress');
        $this->Auth->allow(['signup', 'logout','frontLogin','frontRegister','verify','forgotPassword','successregister','googlelogin','googlecallback']);
    }

	public function index()
	{
        return $this->redirect(['controller' => 'assets', 'action' => 'deposit']);
    }

	public function deposit()
	{
        $this->set('kind', 'deposit');
    }

    public function depositkrw()
    {
        $this->set('kind', 'depositkrw');
    }

    public function krwwithdrawal()
    {
        $this->set('kind', 'krwwithdrawal');
    }
	public function deposit2()
	{
        $this->set('kind', 'deposit');
    }

	public function withdrawal()
	{
        $this->set('kind', 'withdrawal');
    }

	public function details()
	{
        $this->set('kind', 'details');
    }

	public function address()
	{
        $this->set('kind', 'address');
    }

    public function mycoin(){
		return $this->redirect(['action'=>'mycoins']);
        $this->loadModel('Cryptocoin');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Coinpair');
        $this->loadModel('Users');
        $userDetail = $this->Users->find('all',['conditions'=>['id'=>$this->Auth->user('id')]])->hydrate(false)->first();
        $userId = $this->Auth->user('id');
        $users = $this->Users->get($userId);
        $bank = __($users['bank']);
        $account = $this->Decrypt($users['account_number']);
        $bankAuth = $users['bank_verify'];
        $OTPAuth = $users['g_verify'];
        $emailAuth = $users['email_auth'];
        $deposit = $users['deposit'];
        $principalBalances = $this->Users->getUserPricipalBalance($userId,20);
        $userTotalBuyBalance = $this->Users->getUserTotalBuy($userId);
        $userTotalSellBalance = $this->Users->getUserTotalSell($userId);
        $totalVal = $this->Users->getUserTotalDeposit($userId);
        $totalOldVal = $this->Users->getUserTotalOldDeposit($userId);
        $totalReward = $this->Users->getUserTotalReceivedReward($userId);
        $this->set('totalDeposit', isset($totalVal) ? $totalVal : 0);
        $this->set('totalOldDeposit', isset($totalOldVal) ? $totalOldVal : 0);
        $this->set('totalBuy', isset($userTotalBuyBalance) ? $userTotalBuyBalance : 0);
        $this->set('totalSell', isset($userTotalSellBalance) ? $userTotalSellBalance : 0);
        $this->set('totalReward', isset($totalReward) ? $totalReward : 0);
        $this->set('main',$principalBalances);
        $this->set('bank', $bank);
        $this->set('account', $account);
        $this->set('bankAuth', $bankAuth);
        $this->set('otpAuth', $OTPAuth);
        $this->set('emailAuth', $emailAuth);
        $this->set('deposit', $deposit);
        $pendingDeposit = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$userId,'status'=>'pending','type'=>'bank_initial_deposit']])->hydrate(false)->toArray();
        $pendingWithdraw = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$userId,'status'=>'pending','type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();

        $halfBal = $principalBalances/2;
        $balance = $principalBalances - $halfBal;
        if($balance <= 0){
            $this->set('balance', "N");
        } else{
            $this->set('balance', "Y");
        }

        $this->set('balanceVal', $balance);
        $this->set('halfBal',$halfBal);

        if(!empty($pendingDeposit)){
            $valpen = "Y";
            $this->set('pendingVal', $valpen);
        } else {
            $valpen = "N";
            $this->set('pendingVal', $valpen);
        }

        if(!empty($pendingWithdraw)){
            $valpens = "Y";
            $this->set('pendingValw', $valpens);
        } else {
            $valpens = "N";
            $this->set('pendingValw', $valpens);
        }


        $singleArr = [];
        if(empty($userDetail['btc_address'])){
            /* $btcaddress=$this->Users->createBtcAddress($_SESSION['Auth']['User']['email']);
            $this->Users->updateAll(['btc_address'=>$btcaddress],['id'=>$userDetail['id']]); */
        }

        /* if(empty($userDetail['eth_address'])){
            $ethaddress=$this->Users->createEthAddress($_SESSION['Auth']['User']['email']);
            $this->Users->updateAll(['eth_address'=>$ethaddress],['id'=>$userDetail['id']]);
        } */
        $mainRespArr = [];

        $adminWithdrawalFee = $this->Settings->find("all",["conditions"=>["id"=>17]])->hydrate(false)->first();
        $adminWithdrawalFeePercent = $adminWithdrawalFee["value"];
        $this->set('adminWithdrawalFeePercent',$adminWithdrawalFeePercent);

        $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();
        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $coinName = $getCoin['name'];
            $coinShortName = $getCoin['short_name'];
            $icon = $getCoin['icon'];
            $principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId);
            $tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
            //$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
            $reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);
            $address="";

            if($getCoin['short_name']=="BTC"){
                if(!empty($userDetail['btc_address'])){
                    $address=$userDetail['btc_address'];
                }
            }else{
                if(!empty($userDetail['eth_address'])){
                    $address=$userDetail['eth_address'];
                }
            }

            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;

            $singleArr = ['principalBalance'=>$principalBalance,
                'tradingBalance'=>$tradingBalance,
                'reserveBalance'=>$reserveBalance,
                //'pendingBalance'=>$pendingBalance,
                'coinId'=>$coinId,
                'icon'=>$icon,
                'coinName'=>$coinName,
                'coinShortName'=>$coinShortName,
                'coinAddress'=>$address
            ];

            $mainRespArr[]=$singleArr;
        }

        $searchData = array('Coinpair.status'=>1);
        $getCoinPairList = $this->Coinpair->find('all',['conditions'=>$searchData,
            'contain'=>['cryptocoin_first','cryptocoin_second'],
            'order'=>['Coinpair.id'=>'asc'],
            //'limit' => $this->setting['pagination']
        ])
            ->hydrate(false)
            ->toArray();
        $this->set('getCoinPairList',$getCoinPairList);
        $authUserId  = $_SESSION['Auth']['User']['id'];

        $WithdrawalWalletAddressData = $this->WithdrawalWalletAddress->find('all',['conditions'=>['user_id'=>$authUserId]])->hydrate(false)->toArray();

        $authUserId = $this->Auth->user('id');
        $userDetail = $this->Users->find('all',['conditions'=>['id'=>$authUserId]])->hydrate(false)->first();
        $this->set('kind', 'address');
        //$this->set('data',$mainRespArr);
        $this->set(compact('userDetail','mainRespArr','WithdrawalWalletAddressData'));

    }
	public function getcoinslist(){
        $this->loadModel('Cryptocoin');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('transactions');
        $this->loadModel('Coinpair');
        $this->loadModel('Users');
        $this->loadModel('ExchangeHistory');
        $this->loadModel('tb_stats_transactions');
        $getUserTotalCoin = $this->transactions->find();
        $userId = $this->Auth->user('id');
//TODO 20220628 SOJO 어디에 쓰는것인가??
//        $userCoinArr=[];
//        $getUserTotalCoinCnt = $getUserTotalCoin
//            ->select(['sum' => $getUserTotalCoin->func()->sum('coin_amount'),'cryptocoin_id'])
//            ->where(['user_id'=>$userId,'status'=>'completed','tx_type !='=>'bank_initial_deposit'])
//            ->group('cryptocoin_id')
//            ->toArray();
//
//        foreach($getUserTotalCoinCnt as $getUserTotalCoinSingle){
//            $userCoinArr[$getUserTotalCoinSingle['cryptocoin_id']]= $getUserTotalCoinSingle['sum'];
//        }
//
//        $this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);

       $userDetail = $this->Users->get($userId);
        //TODO 20220628 SOJO 날쿼리 추가로 주석
        #$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();

        //TODO 20220628 SOJO 날쿼리 추가
        $getCoinList = $this->Users->getUserCryptocoins($userId);
        $mainRespArr= [];
        foreach($getCoinList as $getCoin){

            $coinId = $getCoin['id'];
            $coinName = $getCoin['name'];
            $coinShortName = $getCoin['short_name'];
            $icon = $getCoin['icon'];

//TODO 20220628 SOJO 날쿼리 추가로 주석
//          $principalBalance = $this->Users->getUserPricipalBalance($userId, $coinId);
//            $tradingBalance = $this->Users->getLocalUserBalance($userId, $coinId);
//            //$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId); 안씀
//            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId, $coinId);
//            $reserveSellBalance = $this->Users->getUserSellReserveBalance($userId, $coinId);

            if ($getCoin['id'] == 20) {
                $principalBalance = $getCoin['wallet_amount'] + $getCoin['initial_withdraw'];
            } else {
                $principalBalance = $getCoin['wallet_amount'];
            }

            $tradingBalance = $getCoin['trade_amount'];
            $reserveBuyBalance = $getCoin['buy_amount'];
            $reserveSellBalance = $getCoin['sell_amount'];


            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId, 20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $customPriceTrading = (float)$tradingBalance * (float)$getMyCustomPrice;
            $customPriceMain = (float)$principalBalance * (float)$getMyCustomPrice;
            $address = "";
            if($getCoin['short_name'] == "BTC"){
                if(!empty($userDetail['btc_address'])){
                    $address = $userDetail['btc_address'];
                }
            }else{
                if(!empty($userDetail['eth_address'])){
                    $address = $userDetail['eth_address'];
                }
            }
            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
            $singleArr = ['principalBalance' => number_format((float)$principalBalance,2),
                'tradingBalance' => number_format((float)$tradingBalance,2),
                'reserveBalance' => number_format((float)$reserveBalance,2),
                'coin_id' => $coinId,
                'icon' => $icon,
                'coinName' => $coinName,
                'short_name' => $coinShortName,
                'coinAddress' => $address	,
                'customPriceTrading' => number_format((float)$customPriceTrading,2),
                'customPriceMain' => number_format((float)$customPriceMain,2),
                'krwValue' => number_format((float)$getMyCustomPrice,2)
            ];
            $mainRespArr[] = $singleArr;
        }


        header('Content-type: application/json');
        echo json_encode($mainRespArr);

        die;
    }

    /**
     * @return void
     * KRW 에서 USDT 로 변환작업
     */
    public function mycoins(){
		//return $this->redirect('/front2/exchange/index/TP3/KRW');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Coinpair');
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Transactions');
        $authUserId = $this->Auth->user('id');
        $this->set('kind', 'address');
        $users = $this->Users->get($authUserId);    //Get complete user details of this user id
        $userTestWalletAddr  = $users->eth_test_wallet_address;     //Get test wallet address of ethereum of this user

		// 20210813 - 이충현 전체 구매, 전체 판매, 전체 입금액을 최근 한달로 변경 - 대표님 지시 업무
		$userTotalBuyBalance = $this->Users->getUserTotalMonthBuy($authUserId);  //Calculate the sum of this user's total buy transactions on exchange
		$userTotalSellBalance = $this->Users->getUserTotalMonthSell($authUserId);    //Calculate the sum of this user's total sell transactions on exchange
		$totalVal = $this->Users->getUserTotalMonthDeposit($authUserId); //
        $totalReward = $this->Users->getUserTotalReceivedReward($authUserId);

        /**
         * 20 번 KRW
         * 5 번 USDT로 교체
         */
        $principalBalances = $this->Users->getUserPricipalBalance($authUserId,20);  //Calculate the sum of this user's main account balance for KRW
        $tradingBalances = $this->Users->getLocalUserBalance($authUserId,20);   //Calculate the sum of this user's trading account balance for KRW

        $this->set('totalDeposit', isset($totalVal) ? $totalVal : 0);
        $this->set('totalOldDeposit', 0);
        $this->set('totalBuy', isset($userTotalBuyBalance) ? $userTotalBuyBalance : 0);
        $this->set('totalSell', isset($userTotalSellBalance) ? $userTotalSellBalance : 0);
        $this->set('totalReward', isset($totalReward) ? $totalReward : 0);
        $this->set('users',$users);
        $this->set('userTestWalletAddr',$userTestWalletAddr);
        $this->set('main',$principalBalances);
        $this->set('trading',$tradingBalances);
        $halfBal = $principalBalances/2;
        $balance = $principalBalances - $halfBal;
        $halfBalTrading = $tradingBalances/2;
        $balanceTrading = $tradingBalances - $halfBalTrading;

        $time = time();
        //하루양 체크
        $today = date("Y-m-d",strtotime("now"));
        $today_start =  $today." 00:00:00";
        $today_end = $today." 23:59:59";
        //한달양 체크
        $month = date("Y-m-d",strtotime("+1 month", $time));
        $month_end = $month." 23:59:59";

        //하루 한달 KRW 기준 출금한 코인 양
        $query = $this->Transactions->find();
        $day_total = $query->select(['sum' => 'coin_amount'])->where(['user_id'=>$authUserId,'tx_type' => 'withdrawal','status'=>'completed','cryptocoin_id'=>'20','created >'=>$today_start,'created < '=>$today_end])->hydrate(false)->first();

        //토탈값 추가 하기
        $month_total = $query->select(['sum' => 'coin_amount'])->where(['user_id'=>$authUserId,'tx_type' => 'withdrawal','status'=>'completed','cryptocoin_id'=>'20','created >'=>$today_start,'created < '=>$month_end])->hydrate(false)->first();


        $this->set('day_total',$day_total['sum']);
        $this->set('month_total',$month_total['sum']);

        //토탈 추가 하기

        //$total_galaxy_arr = $query->select(['sum' => $query->func()->sum('ConversionRates.total_coins')])->hydrate(false)->first();
        $query = $this->Transactions->find();
        $sell_total = $query->select(['sum' => 'coin_amount'])->where(['user_id'=>$authUserId,'tx_type' => 'bank_initial_withdraw','status'=>'completed'])->hydrate(false)->first();
        $inout_price =  $principalBalances+$sell_total['sum'];
        $this->set('outprice',$sell_total['sum']);
        $this->set('inoutprice',$inout_price);


        if($balance <= 0){
            $this->set('balance', "N");
        } else{
            $this->set('balance', "Y");
        }
        if($balanceTrading <= 0){
            $this->set('balanceTrading', "N");
        } else{
            $this->set('balanceTrading', "Y");
        }

        $this->set('balanceVal', $balance);
        $this->set('halfBal',$halfBal);
        $this->set('balanceValTrading', $balanceTrading);
        $this->set('halfBalTrading',$halfBalTrading);
        $pendingDeposit = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','type'=>'bank_initial_deposit']])->hydrate(false)->toArray();
        $pendingWithdraw = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();
        $pendingWithdrawTrading = $this->Transactions->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','tx_type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();

		$valPen = "N";
		$valPens = "N";
        if(!empty($pendingDeposit)){
            $valPen = "Y";
        }
        if(!empty($pendingWithdraw) || !empty($pendingWithdrawTrading)){
            $valPens = "Y";
        }
        $this->set('pendingVal', $valPen);
        $this->set('pendingValw', $valPens);

        //코인 리스트
        $mainRespArr = [];
        $adminWithdrawalFee = $this->Settings->find("all",["conditions"=>["id"=>17]])->hydrate(false)->first();
        $adminWithdrawalFeePercent = $adminWithdrawalFee["value"];
        $this->set('adminWithdrawalFeePercent',$adminWithdrawalFeePercent);

        //TODO 20220630 SOJO
        #$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();
        $getCoinList = $this->Users->getUserCryptocoins($authUserId);

        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $coinName = $getCoin['name'];
            $coinShortName = $getCoin['short_name'];
            $icon = $getCoin['icon'];


//            $principalBalance = $this->Users->getUserPricipalBalance($authUserId,$coinId);
//            $tradingBalance = $this->Users->getLocalUserBalance($authUserId,$coinId);
//            //$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
//            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($authUserId,$coinId);
//            $reserveSellBalance = $this->Users->getUserSellReserveBalance($authUserId,$coinId);

            if ($getCoin['id'] == 20) {
                $principalBalance = $getCoin['wallet_amount'] + $getCoin['initial_withdraw'];
            } else {
                $principalBalance = $getCoin['wallet_amount'];
            }
            $tradingBalance = $getCoin['trade_amount'];
            $reserveBuyBalance = $getCoin['buy_amount'];
            $reserveSellBalance = $getCoin['sell_amount'];

            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $customPriceTrading = (float)$tradingBalance * (float)$getMyCustomPrice;
            $customPriceMain = (float)$principalBalance * (float)$getMyCustomPrice;
            $address = "";
            if($getCoin['short_name'] == "BTC"){
                if(!empty($users['btc_address'])){
                    $address=$users['btc_address'];
                }
            }else{
                if(!empty($users['eth_address'])){
                    $address=$users['eth_address'];
                }
            }
            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
            $singleArr = ['principalBalance' => $principalBalance,
                'tradingBalance' => $tradingBalance,
                'reserveBalance' => $reserveBalance,
                'coinId' => $coinId,
                'icon' => $icon,
                'coinName' => $coinName,
                'coinShortName' => $coinShortName,
                'coinAddress' => $address,
                'customPriceTrading' => $customPriceTrading,
                'customPriceMain' => $customPriceMain,
                'krwValue' => $getMyCustomPrice
            ];
            $mainRespArr[] = $singleArr;
        }

        $searchData = array('Coinpair.status' => 1);
        $getCoinPairList = $this->Coinpair->find('all',['conditions' => $searchData,
            'contain' => ['cryptocoin_first', 'cryptocoin_second'],
            'order' => ['Coinpair.id'=>'asc'],])
            ->hydrate(false)
            ->toArray();
        $this->set('getCoinPairList', $getCoinPairList);
        $WithdrawalWalletAddressData = $this->WithdrawalWalletAddress->find('all', ['conditions' => ['user_id' => $authUserId]])->hydrate(false)->toArray();
        $this->set('kind', 'address');
        $this->set(compact('mainRespArr','WithdrawalWalletAddressData'));
    }
    public function mycoins2(){
        //return $this->redirect('/front2/exchange/index/TP3/KRW');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Coinpair');
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Transactions');
        $authUserId = $this->Auth->user('id');
        $this->set('kind', 'address');
        $users = $this->Users->get($authUserId);    //Get complete user details of this user id
        $userTestWalletAddr  = $users->eth_test_wallet_address;     //Get test wallet address of ethereum of this user

        // 20210813 - 이충현 전체 구매, 전체 판매, 전체 입금액을 최근 한달로 변경 - 대표님 지시 업무
        $userTotalBuyBalance = $this->Users->getUserTotalMonthBuy($authUserId);  //Calculate the sum of this user's total buy transactions on exchange
        $userTotalSellBalance = $this->Users->getUserTotalMonthSell($authUserId);    //Calculate the sum of this user's total sell transactions on exchange
        $totalVal = $this->Users->getUserTotalMonthDeposit($authUserId); //
        $totalReward = $this->Users->getUserTotalReceivedReward($authUserId);

        /**
         * 20 번 KRW
         * 5 번 USDT로 교체
         */
        $principalBalances = $this->Users->getUserPricipalBalance($authUserId,20);  //Calculate the sum of this user's main account balance for KRW
        $tradingBalances = $this->Users->getLocalUserBalance($authUserId,20);   //Calculate the sum of this user's trading account balance for KRW

        $this->set('totalDeposit', isset($totalVal) ? $totalVal : 0);
        $this->set('totalOldDeposit', 0);
        $this->set('totalBuy', isset($userTotalBuyBalance) ? $userTotalBuyBalance : 0);
        $this->set('totalSell', isset($userTotalSellBalance) ? $userTotalSellBalance : 0);
        $this->set('totalReward', isset($totalReward) ? $totalReward : 0);
        $this->set('users',$users);
        $this->set('userTestWalletAddr',$userTestWalletAddr);
        $this->set('main',$principalBalances);
        $this->set('trading',$tradingBalances);
        $halfBal = $principalBalances/2;
        $balance = $principalBalances - $halfBal;
        $halfBalTrading = $tradingBalances/2;
        $balanceTrading = $tradingBalances - $halfBalTrading;

        $time = time();
        //하루양 체크
        $today = date("Y-m-d",strtotime("now"));
        $today_start =  $today." 00:00:00";
        $today_end = $today." 23:59:59";
        //한달양 체크
        $month = date("Y-m-d",strtotime("+1 month", $time));
        $month_end = $month." 23:59:59";

        //하루 한달 KRW 기준 출금한 코인 양
        $query = $this->Transactions->find();
        $day_total = $query->select(['sum' => 'coin_amount'])->where(['user_id'=>$authUserId,'tx_type' => 'withdrawal','status'=>'completed','cryptocoin_id'=>'20','created >'=>$today_start,'created < '=>$today_end])->hydrate(false)->first();

        //토탈값 추가 하기
        $month_total = $query->select(['sum' => 'coin_amount'])->where(['user_id'=>$authUserId,'tx_type' => 'withdrawal','status'=>'completed','cryptocoin_id'=>'20','created >'=>$today_start,'created < '=>$month_end])->hydrate(false)->first();


        $this->set('day_total',$day_total['sum']);
        $this->set('month_total',$month_total['sum']);

        //토탈 추가 하기

        //$total_galaxy_arr = $query->select(['sum' => $query->func()->sum('ConversionRates.total_coins')])->hydrate(false)->first();
        $query = $this->Transactions->find();
        $sell_total = $query->select(['sum' => 'coin_amount'])->where(['user_id'=>$authUserId,'tx_type' => 'bank_initial_withdraw','status'=>'completed'])->hydrate(false)->first();
        $inout_price =  $principalBalances+$sell_total['sum'];
        $this->set('outprice',$sell_total['sum']);
        $this->set('inoutprice',$inout_price);


        if($balance <= 0){
            $this->set('balance', "N");
        } else{
            $this->set('balance', "Y");
        }
        if($balanceTrading <= 0){
            $this->set('balanceTrading', "N");
        } else{
            $this->set('balanceTrading', "Y");
        }

        $this->set('balanceVal', $balance);
        $this->set('halfBal',$halfBal);
        $this->set('balanceValTrading', $balanceTrading);
        $this->set('halfBalTrading',$halfBalTrading);
        $pendingDeposit = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','type'=>'bank_initial_deposit']])->hydrate(false)->toArray();
        $pendingWithdraw = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();
        $pendingWithdrawTrading = $this->Transactions->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','tx_type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();

        $valPen = "N";
        $valPens = "N";
        if(!empty($pendingDeposit)){
            $valPen = "Y";
        }
        if(!empty($pendingWithdraw) || !empty($pendingWithdrawTrading)){
            $valPens = "Y";
        }
        $this->set('pendingVal', $valPen);
        $this->set('pendingValw', $valPens);

        //코인 리스트
        $mainRespArr = [];
        $adminWithdrawalFee = $this->Settings->find("all",["conditions"=>["id"=>17]])->hydrate(false)->first();
        $adminWithdrawalFeePercent = $adminWithdrawalFee["value"];
        $this->set('adminWithdrawalFeePercent',$adminWithdrawalFeePercent);

        //TODO 20220630 SOJO
        #$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();
        $getCoinList = $this->Users->getUserCryptocoins($authUserId);

        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $coinName = $getCoin['name'];
            $coinShortName = $getCoin['short_name'];
            $icon = $getCoin['icon'];


//            $principalBalance = $this->Users->getUserPricipalBalance($authUserId,$coinId);
//            $tradingBalance = $this->Users->getLocalUserBalance($authUserId,$coinId);
//            //$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
//            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($authUserId,$coinId);
//            $reserveSellBalance = $this->Users->getUserSellReserveBalance($authUserId,$coinId);

            if ($getCoin['id'] == 20) {
                $principalBalance = $getCoin['wallet_amount'] + $getCoin['initial_withdraw'];
            } else {
                $principalBalance = $getCoin['wallet_amount'];
            }
            $tradingBalance = $getCoin['trade_amount'];
            $reserveBuyBalance = $getCoin['buy_amount'];
            $reserveSellBalance = $getCoin['sell_amount'];

            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $customPriceTrading = (float)$tradingBalance * (float)$getMyCustomPrice;
            $customPriceMain = (float)$principalBalance * (float)$getMyCustomPrice;
            $address = "";
            if($getCoin['short_name'] == "BTC"){
                if(!empty($users['btc_address'])){
                    $address=$users['btc_address'];
                }
            }else{
                if(!empty($users['eth_address'])){
                    $address=$users['eth_address'];
                }
            }
            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
            $singleArr = ['principalBalance' => $principalBalance,
                'tradingBalance' => $tradingBalance,
                'reserveBalance' => $reserveBalance,
                'coinId' => $coinId,
                'icon' => $icon,
                'coinName' => $coinName,
                'coinShortName' => $coinShortName,
                'coinAddress' => $address,
                'customPriceTrading' => $customPriceTrading,
                'customPriceMain' => $customPriceMain,
                'krwValue' => $getMyCustomPrice
            ];
            $mainRespArr[] = $singleArr;
        }

        $searchData = array('Coinpair.status' => 1);
        $getCoinPairList = $this->Coinpair->find('all',['conditions' => $searchData,
            'contain' => ['cryptocoin_first', 'cryptocoin_second'],
            'order' => ['Coinpair.id'=>'asc'],])
            ->hydrate(false)
            ->toArray();
        $this->set('getCoinPairList', $getCoinPairList);
        $WithdrawalWalletAddressData = $this->WithdrawalWalletAddress->find('all', ['conditions' => ['user_id' => $authUserId]])->hydrate(false)->toArray();
        $this->set('kind', 'address');
        $this->set(compact('mainRespArr','WithdrawalWalletAddressData'));
    }
    public function mycoins2_back(){
        $this->loadModel('Cryptocoin');
        $this->loadModel('Coinpair');
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Transactions');
        $authUserId = $this->Auth->user('id');
        $this->set('kind', 'address');
        $users = $this->Users->get($authUserId);    //Get complete user details of this user id
        $userTestWalletAddr  = $users->eth_test_wallet_address;     //Get test wallet address of ethereum of this user

        // generate pvt key  for testnet
        /*if(empty($users->eth_test_pvt_key)){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://125.141.133.23:4500/create_address',
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
            $responseDecode = json_decode($response,true);
            $ethWalletAddrTestNet = $responseDecode['data']['address'];
            $ethPvtKeyTestNet = $responseDecode['data']['privateKey'];
            $this->Users->updateAll(['eth_test_pvt_key'=>$ethPvtKeyTestNet,'eth_test_wallet_address'=>$ethWalletAddrTestNet],['id'=>$authUserId]);
            $userTestWalletAddr  = $ethWalletAddrTestNet;
        }*/


        if(empty($users->eth_test_pvt_key)){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_PORT => "3000",
                CURLOPT_URL => 'http://54.180.5.130:3000/multisign/create_address',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            if (!$err) {
                $responseDecode = json_decode($response,true);
                $ethWalletAddrTestNet = $responseDecode['data']['address'];
                $ethPvtKeyTestNet = $responseDecode['data']['privateKey'];
                $this->Users->updateAll(['eth_test_pvt_key'=>$ethPvtKeyTestNet,'eth_test_wallet_address'=>$ethWalletAddrTestNet],['id'=>$authUserId]);
                $userTestWalletAddr  = $ethWalletAddrTestNet;
            }
        }

        //$userTotalBuyBalance = $this->Users->getUserTotalBuy($authUserId);  //Calculate the sum of this user's total buy transactions on exchange
        //$userTotalSellBalance = $this->Users->getUserTotalSell($authUserId);    //Calculate the sum of this user's total sell transactions on exchange
        //$totalVal = $this->Users->getUserTotalDeposit($authUserId); //
        //$totalOldVal = $this->Users->getUserTotalOldDeposit($authUserId);


        // 20210813 - 이충현 전체 구매, 전체 판매, 전체 입금액을 최근 한달로 변경 - 대표님 지시 업무
        $userTotalBuyBalance = $this->Users->getUserTotalMonthBuy($authUserId);  //Calculate the sum of this user's total buy transactions on exchange
        $userTotalSellBalance = $this->Users->getUserTotalMonthSell($authUserId);    //Calculate the sum of this user's total sell transactions on exchange
        $totalVal = $this->Users->getUserTotalMonthDeposit($authUserId); //
        $totalReward = $this->Users->getUserTotalReceivedReward($authUserId);
        $principalBalances = $this->Users->getUserPricipalBalance($authUserId,20);  //Calculate the sum of this user's main account balance for KRW
        $tradingBalances = $this->Users->getLocalUserBalance($authUserId,20);   //Calculate the sum of this user's trading account balance for KRW
        $this->set('totalDeposit', isset($totalVal) ? $totalVal : 0);
        $this->set('totalOldDeposit', 0);
        //총 입금 그액 계산 공식
        $this->set('totalBuy', isset($userTotalBuyBalance) ? $userTotalBuyBalance : 0);

        $this->set('totalSell', isset($userTotalSellBalance) ? $userTotalSellBalance : 0);
        $this->set('totalReward', isset($totalReward) ? $totalReward : 0);
        $this->set('users',$users);
        $this->set('userTestWalletAddr',$userTestWalletAddr);
        $this->set('main',$principalBalances);
        $this->set('trading',$tradingBalances);
        $halfBal = $principalBalances/2;
        $balance = $principalBalances - $halfBal;
        $halfBalTrading = $tradingBalances/2;
        $balanceTrading = $tradingBalances - $halfBalTrading;

        //토탈값 추가 하기

        //$total_galaxy_arr = $query->select(['sum' => $query->func()->sum('ConversionRates.total_coins')])->hydrate(false)->first();
        $query = $this->Transactions->find();
        $sell_total = $query->select(['sum' => 'coin_amount'])->where(['tx_type' => 'bank_initial_withdraw','user_id'=>$authUserId,'status'=>'completed'])->hydrate(false)->first();
        $inout_price =  $principalBalances+$sell_total['sum'];
        $this->set('outprice',$sell_total['sum']);
        $this->set('inoutprice',$inout_price);


        if($balance <= 0){
            $this->set('balance', "N");
        } else{
            $this->set('balance', "Y");
        }
        if($balanceTrading <= 0){
            $this->set('balanceTrading', "N");
        } else{
            $this->set('balanceTrading', "Y");
        }

        $this->set('balanceVal', $balance);
        $this->set('halfBal',$halfBal);
        $this->set('balanceValTrading', $balanceTrading);
        $this->set('halfBalTrading',$halfBalTrading);
        $pendingDeposit = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','type'=>'bank_initial_deposit']])->hydrate(false)->toArray();
        $pendingWithdraw = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();
        $pendingWithdrawTrading = $this->Transactions->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','tx_type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();

        $valPen = "N";
        $valPens = "N";
        if(!empty($pendingDeposit)){
            $valPen = "Y";
        }
        if(!empty($pendingWithdraw) || !empty($pendingWithdrawTrading)){
            $valPens = "Y";
        }
        $this->set('pendingVal', $valPen);
        $this->set('pendingValw', $valPens);
        $mainRespArr = [];
        $adminWithdrawalFee = $this->Settings->find("all",["conditions"=>["id"=>17]])->hydrate(false)->first();
        $adminWithdrawalFePercent = $adminWithdrawalFee["value"];
        //$adminWithdrawalFePercent = '0.25';
        $this->set('adminWithdrawalFeePercent',$adminWithdrawalFePercent);
        $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();
        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $coinName = $getCoin['name'];
            $coinShortName = $getCoin['short_name'];
            $icon = $getCoin['icon'];
            $principalBalance = $this->Users->getUserPricipalBalance($authUserId,$coinId);
            $tradingBalance = $this->Users->getLocalUserBalance($authUserId,$coinId);
            //$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($authUserId,$coinId);
            $reserveSellBalance = $this->Users->getUserSellReserveBalance($authUserId,$coinId);
            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $customPriceTrading = (float)$tradingBalance * (float)$getMyCustomPrice;
            $customPriceMain = (float)$principalBalance * (float)$getMyCustomPrice;
            $address = "";
            if($getCoin['short_name'] == "BTC"){
                if(!empty($users['btc_address'])){
                    $address=$users['btc_address'];
                }
            }else{
                if(!empty($users['eth_address'])){
                    $address=$users['eth_address'];
                }
            }
            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
            $singleArr = ['principalBalance' => $principalBalance,
                'tradingBalance' => $tradingBalance,
                'reserveBalance' => $reserveBalance,
                'coinId' => $coinId,
                'icon' => $icon,
                'coinName' => $coinName,
                'coinShortName' => $coinShortName,
                'coinAddress' => $address,
                'customPriceTrading' => $customPriceTrading,
                'customPriceMain' => $customPriceMain,
                'krwValue' => $getMyCustomPrice
            ];
            $mainRespArr[] = $singleArr;
        }

        $searchData = array('Coinpair.status' => 1);
        $getCoinPairList = $this->Coinpair->find('all',['conditions' => $searchData,
            'contain' => ['cryptocoin_first', 'cryptocoin_second'],
            'order' => ['Coinpair.id'=>'asc'],])
            ->hydrate(false)
            ->toArray();
        $this->set('getCoinPairList', $getCoinPairList);
        $WithdrawalWalletAddressData = $this->WithdrawalWalletAddress->find('all', ['conditions' => ['user_id' => $authUserId]])->hydrate(false)->toArray();
        $this->set('kind', 'address');
        $this->set(compact('mainRespArr','WithdrawalWalletAddressData'));
    }


    public function mycoins_backup(){
        //return $this->redirect('/front2/exchange/index/TP3/KRW');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Coinpair');
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Transactions');
        $authUserId = $this->Auth->user('id');
        $this->set('kind', 'address');
        $users = $this->Users->get($authUserId);    //Get complete user details of this user id
        $userTestWalletAddr  = $users->eth_test_wallet_address;     //Get test wallet address of ethereum of this user

        // generate pvt key  for testnet
        //TODO Connection timed out after 10000 milliseconds 2022-06-27 SOJO
        /*
                if(empty($users->eth_test_pvt_key)){
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_PORT => "3000",
                        CURLOPT_URL => 'http://54.180.5.130:3000/multisign/create_address',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 10,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);
                    if (!$err) {
                        $responseDecode = json_decode($response,true);
                        $ethWalletAddrTestNet = $responseDecode['data']['address'];
                        $ethPvtKeyTestNet = $responseDecode['data']['privateKey'];
                        $this->Users->updateAll(['eth_test_pvt_key'=>$ethPvtKeyTestNet,'eth_test_wallet_address'=>$ethWalletAddrTestNet],['id'=>$authUserId]);
                        $userTestWalletAddr  = $ethWalletAddrTestNet;
                    }
                }*/

        //$userTotalBuyBalance = $this->Users->getUserTotalBuy($authUserId);  //Calculate the sum of this user's total buy transactions on exchange
        //$userTotalSellBalance = $this->Users->getUserTotalSell($authUserId);    //Calculate the sum of this user's total sell transactions on exchange
        //$totalVal = $this->Users->getUserTotalDeposit($authUserId); //
        //$totalOldVal = $this->Users->getUserTotalOldDeposit($authUserId);
        // 20210813 - 이충현 전체 구매, 전체 판매, 전체 입금액을 최근 한달로 변경 - 대표님 지시 업무
        $userTotalBuyBalance = $this->Users->getUserTotalMonthBuy($authUserId);  //Calculate the sum of this user's total buy transactions on exchange
        $userTotalSellBalance = $this->Users->getUserTotalMonthSell($authUserId);    //Calculate the sum of this user's total sell transactions on exchange
        $totalVal = $this->Users->getUserTotalMonthDeposit($authUserId); //
        $totalReward = $this->Users->getUserTotalReceivedReward($authUserId);

        /**
         * 20 번 KRW
         * 5 번 USDT로 교체
         */
        $principalBalances = $this->Users->getUserPricipalBalance($authUserId,20);  //Calculate the sum of this user's main account balance for KRW
        $tradingBalances = $this->Users->getLocalUserBalance($authUserId,20);   //Calculate the sum of this user's trading account balance for KRW

        $this->set('totalDeposit', isset($totalVal) ? $totalVal : 0);
        $this->set('totalOldDeposit', 0);
        $this->set('totalBuy', isset($userTotalBuyBalance) ? $userTotalBuyBalance : 0);
        $this->set('totalSell', isset($userTotalSellBalance) ? $userTotalSellBalance : 0);
        $this->set('totalReward', isset($totalReward) ? $totalReward : 0);
        $this->set('users',$users);
        $this->set('userTestWalletAddr',$userTestWalletAddr);
        $this->set('main',$principalBalances);
        $this->set('trading',$tradingBalances);
        $halfBal = $principalBalances/2;
        $balance = $principalBalances - $halfBal;
        $halfBalTrading = $tradingBalances/2;
        $balanceTrading = $tradingBalances - $halfBalTrading;

        $time = time();
        //하루양 체크
        $today = date("Y-m-d",strtotime("now"));
        $today_start =  $today." 00:00:00";
        $today_end = $today." 23:59:59";
        //한달양 체크
        $month = date("Y-m-d",strtotime("+1 month", $time));
        $month_end = $month." 23:59:59";

        //하루 한달 KRW 기준 출금한 코인 양
        $query = $this->Transactions->find();
        $day_total = $query->select(['sum' => 'coin_amount'])->where(['user_id'=>$authUserId,'tx_type' => 'withdrawal','status'=>'completed','cryptocoin_id'=>'20','created >'=>$today_start,'created < '=>$today_end])->hydrate(false)->first();

        //토탈값 추가 하기
        $month_total = $query->select(['sum' => 'coin_amount'])->where(['user_id'=>$authUserId,'tx_type' => 'withdrawal','status'=>'completed','cryptocoin_id'=>'20','created >'=>$today_start,'created < '=>$month_end])->hydrate(false)->first();


        $this->set('day_total',$day_total['sum']);
        $this->set('month_total',$month_total['sum']);

        //토탈 추가 하기

        //$total_galaxy_arr = $query->select(['sum' => $query->func()->sum('ConversionRates.total_coins')])->hydrate(false)->first();
        $query = $this->Transactions->find();
        $sell_total = $query->select(['sum' => 'coin_amount'])->where(['user_id'=>$authUserId,'tx_type' => 'bank_initial_withdraw','status'=>'completed'])->hydrate(false)->first();
        $inout_price =  $principalBalances+$sell_total['sum'];
        $this->set('outprice',$sell_total['sum']);
        $this->set('inoutprice',$inout_price);


        if($balance <= 0){
            $this->set('balance', "N");
        } else{
            $this->set('balance', "Y");
        }
        if($balanceTrading <= 0){
            $this->set('balanceTrading', "N");
        } else{
            $this->set('balanceTrading', "Y");
        }

        $this->set('balanceVal', $balance);
        $this->set('halfBal',$halfBal);
        $this->set('balanceValTrading', $balanceTrading);
        $this->set('halfBalTrading',$halfBalTrading);
        $pendingDeposit = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','type'=>'bank_initial_deposit']])->hydrate(false)->toArray();
        $pendingWithdraw = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();
        $pendingWithdrawTrading = $this->Transactions->find('all',['conditions'=>['user_id'=>$authUserId,'status'=>'pending','tx_type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();

        $valPen = "N";
        $valPens = "N";
        if(!empty($pendingDeposit)){
            $valPen = "Y";
        }
        if(!empty($pendingWithdraw) || !empty($pendingWithdrawTrading)){
            $valPens = "Y";
        }
        $this->set('pendingVal', $valPen);
        $this->set('pendingValw', $valPens);

        //코인 리스트
        $mainRespArr = [];
        $adminWithdrawalFee = $this->Settings->find("all",["conditions"=>["id"=>17]])->hydrate(false)->first();
        $adminWithdrawalFeePercent = $adminWithdrawalFee["value"];
        $this->set('adminWithdrawalFeePercent',$adminWithdrawalFeePercent);
        $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();
        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $coinName = $getCoin['name'];
            $coinShortName = $getCoin['short_name'];
            $icon = $getCoin['icon'];
            $principalBalance = $this->Users->getUserPricipalBalance($authUserId,$coinId);
            $tradingBalance = $this->Users->getLocalUserBalance($authUserId,$coinId);
            //$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($authUserId,$coinId);
            $reserveSellBalance = $this->Users->getUserSellReserveBalance($authUserId,$coinId);
            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $customPriceTrading = (float)$tradingBalance * (float)$getMyCustomPrice;
            $customPriceMain = (float)$principalBalance * (float)$getMyCustomPrice;
            $address = "";
            if($getCoin['short_name'] == "BTC"){
                if(!empty($users['btc_address'])){
                    $address=$users['btc_address'];
                }
            }else{
                if(!empty($users['eth_address'])){
                    $address=$users['eth_address'];
                }
            }
            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
            $singleArr = ['principalBalance' => $principalBalance,
                'tradingBalance' => $tradingBalance,
                'reserveBalance' => $reserveBalance,
                'coinId' => $coinId,
                'icon' => $icon,
                'coinName' => $coinName,
                'coinShortName' => $coinShortName,
                'coinAddress' => $address,
                'customPriceTrading' => $customPriceTrading,
                'customPriceMain' => $customPriceMain,
                'krwValue' => $getMyCustomPrice
            ];
            $mainRespArr[] = $singleArr;
        }

        $searchData = array('Coinpair.status' => 1);
        $getCoinPairList = $this->Coinpair->find('all',['conditions' => $searchData,
            'contain' => ['cryptocoin_first', 'cryptocoin_second'],
            'order' => ['Coinpair.id'=>'asc'],])
            ->hydrate(false)
            ->toArray();
        $this->set('getCoinPairList', $getCoinPairList);
        $WithdrawalWalletAddressData = $this->WithdrawalWalletAddress->find('all', ['conditions' => ['user_id' => $authUserId]])->hydrate(false)->toArray();
        $this->set('kind', 'address');
        $this->set(compact('mainRespArr','WithdrawalWalletAddressData'));
    }

    public function verifyPass(){
        $this->loadModel('Users');
        if($this->request->is('ajax')) {
/*            if($this->check_ip() != 'success'){
                //$arr = array('error'=>1,"message"=>"<div class='alert alert-danger'>특정금융거래정보법에 의거하여 잠시 서비스를 중지합니다.</div>");
                $returnArr = ['success' => "false", "message" => "특정금융거래정보법에 의거하여 잠시 서비스를 중지합니다."];
                echo json_encode($returnArr);
                die;
            }*/

            $userId = $this->Auth->user('id');
            $user = $this->Users->get($userId);
            if(!empty($user)){
                $password = strip_tags($this->request->data['password']);
                $existedHassPass = $user['password'];
                $checkPass = (new DefaultPasswordHasher)->check($password, $existedHassPass);
                if ($checkPass) {
                    $returnArr = ['success' => "true", "message" => "Proceed for Password Authentication"];
                } else {
                    //$returnArr = ['success' => "false", "message" => "Incorrect Password"];
                    $returnArr = ['success' => "true", "message" => "Proceed for Password Authentication"];
                }
                echo json_encode($returnArr);
                die;
            }
        }
    }

	public function userFeeSetting($coinId){
		$this->loadModel("Users");
		$this->loadModel("Coinpair");
		$this->loadModel("NumberThreeSetting");
		if ($this->request->is('ajax')) {
			$userId = $this->Auth->user('id');

			$withdrawalFeePercent = $this->Users->getUserWithdrawalFee($coinId,$userId);

			$respArr = ["success"=>"true","message"=>"Number Three Setting Data",'data'=>["user_fee"=>$withdrawalFeePercent]];
			echo json_encode($respArr); die;
		}
	}

	public function krwDeposit(){   //KRW Deposit

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Transactions');
        $this->loadModel('Users');
        if($this->request->is('ajax')){
            $userId = $this->Auth->user('id');
            $users = $this->Users->get($userId);
            $bankAuth = $users['bank_verify'];
            $emailAuth = $users['email_auth'];
            $gAuth = $users['g_verify'];
            $pendingDeposit = $this->PrincipalWallet->find('all', ['conditions' => ['user_id' => $userId, 'status' => 'pending', 'type' => 'bank_initial_deposit']])->hydrate(false)->toArray();
            $pendingWithdraw = $this->PrincipalWallet->find('all', ['conditions' => ['user_id' => $userId, 'status' => 'pending', 'type' => 'bank_initial_withdraw']])->hydrate(false)->toArray();
            $pendingWithdrawTrading = $this->Transactions->find('all', ['conditions' => ['user_id' => $userId, 'status' => 'pending', 'tx_type' => 'bank_initial_withdraw']])->hydrate(false)->toArray();
			$valPen = "N";
			$valPens = "N";

            if(!empty($pendingDeposit)){
                $valPen = "Y";
            }

            if(!empty($pendingWithdraw) || !empty($pendingWithdrawTrading)){
                $valPens = "Y";
            }

			if($bankAuth != "Y" || $emailAuth != "Y" | $gAuth != "Y"){
				$returnArr = ['success'=>"false","message"=>'레벨 1단계는 출금 할 수 없습니다.','message2'=>'인증단계로 이동 하시겠습니까?'];
                echo json_encode($returnArr); die;
			}
			if($valPen != "N" || $valPens != "N"){
				$returnArr = ['success'=>"false","message"=>'대기중인 입출금 요청이 있습니다.','message2'=>''];
                echo json_encode($returnArr); die;
			}

            if(!empty($this->request->data['amount_deposited']) && $bankAuth == "Y" && $emailAuth == "Y" && $gAuth =="Y" && $valPen == "N" && $valPens == "N"){
                $insertArr = [];
                $insertArr['user_id'] = $userId;
                $insertArr['amount'] = $this->request->data['amount_deposited'];
                $insertArr['cryptocoin_id'] = 20;
                $insertArr['type'] = "bank_initial_deposit";
                $insertArr['fees'] = 0.00;
                $insertArr['remark'] = "bank_initial_deposit";
                $insertArr['status'] = "pending";

                $deposit = $this->PrincipalWallet->newEntity();
                $deposit = $this->PrincipalWallet->patchEntity($deposit, $insertArr);
                if($this->PrincipalWallet->save($deposit)){
                    $returnArr = ['success'=>"true","message"=>__('Deposited successfully')];
                }
                else {
                    $returnArr = ['success'=>"false","message"=>__('Unable to deposit'),'message2'=>''];
                }
                echo json_encode($returnArr);
                die;
            }
			die;
        }
    }
	// Hassam - 210820
    public function krwWithdrawalMain(){
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Transactions');
        $this->loadModel('Users');
        $this->loadModel('NumberSevenSetting');
        if($this->request->is('ajax')) {
            $userId = $this->Auth->user('id');
            $user = $this->Users->get($userId);
			$secret = $user->g_secret;
			$bankAuth = $user['bank_verify'];
			$userLevel = $user['user_level'];
			$emailAuth = $user['email_auth'];
			$gAuth = $user['g_verify'];

			$pendingDeposit = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$userId,'status'=>'pending','type'=>'bank_initial_deposit']])->hydrate(false)->toArray();
			$pendingWithdraw = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$userId,'status'=>'pending','type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();
            $pendingWithdrawTrading = $this->Transactions->find('all',['conditions'=>['user_id'=>$userId,'status'=>'pending','tx_type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();
			$valPen = "N";
			$valPens = "N";
			if(!empty($pendingDeposit)){
				$valPen = "Y";
			}

			if(!empty($pendingWithdraw) || !empty($pendingWithdrawTrading)){
				$valPens = "Y";
			}

		/*	if($bankAuth != "Y" || $emailAuth != "Y" || $gAuth != "Y"){
                $returnArr = ['success'=>"false","message"=>__("Level 1 cannot be withdrawn"),'message2'=>__("Do you want to go to the authentication stage?")];
				echo json_encode($returnArr); die;
			}*/

			//if($user['id_document_status'] != 'A' || $user['scan_copy_status'] != 'A') {    //If User has not been yet approved in KYC Verification
            //    $returnArr = ['success'=>"false","message"=>'레벨 2단계는 출금 할 수 없습니다.','message2'=>'인증단계로 이동 하시겠습니까?'];
            //    echo json_encode($returnArr); die;
            //}

			if($valPen != "N" || $valPens != "N"){
                $returnArr = ['success'=>"false","message"=>__("There is a deposit and withdrawal request currently pending"),'message2'=>''];
				echo json_encode($returnArr); die;
			}

			//$googleAuthUrl = $this->Users->getQRCodeGoogleUrl('CoinIBT', $secret);
			$totalVal = $this->Users->getUserTotalDeposit($userId); //Total KRW amount user deposited
			$totalOldVal = $this->Users->getUserTotalOldDeposit($userId);   //Total KRW amount user deposited old
			$currMainBalance = $this->Users->getUserPricipalBalance($userId, 20);   //User's total Main Account KRW Balance
            $tradingBalance = $this->Users->getLocalUserBalance($userId, 20);   //User's total Trading Account KRW Balance
			$withdrawAmount = $this->request->data['total_amount']; //The total withdrawal amount inclusive of the withdrawal fees.
			$reqAmount = $this->request->data['req_amount'];    //The withdrawal amount exclusive of the withdrawal fees
			$totalDeposit = $totalVal + $totalOldVal;   //Sum of the deposit amounts (old and current)
			$getPercentage =  $this->NumberSevenSetting->find("all",["conditions"=>["status"=>"ACTIVE"],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
			$percent = $getPercentage['percentage'];    //Get the percentage of the deposit the user can withdraw
			$toWithdraw = $totalDeposit * ($percent/100);   //The amount user can withdraw based on the percentage
			//$difference = $totalDeposit + $toWithdraw;
			$finalAmount = $currMainBalance - $withdrawAmount;  //The KRW amount left in the KRW main account after deducting the total withdrawal amount inclusive of withdrawal fees.
            $halfBal = $currMainBalance/2;  //Half balance of the user's current KRW main account balance
            $balance = $currMainBalance - ($halfBal + 1000);    //The KRW account balance left in the user's main account after they perform this withdrawal transaction
			$userTotalBuyBalance = $this->Users->getUserTotalBuy($userId);  //The total amount of the buy transactions performed by the user
			$buyBalance = $userTotalBuyBalance/2;   //Half of the user's total buy transactions amount
			$userTotalSellBalance = $this->Users->getUserTotalSell($userId);    //The total amount of the sell transactions performed by the user
			$sellBalance = $userTotalSellBalance/2; //Half of the user's total sell transactions amount
			$threefold = $totalDeposit*3; // Three times the amount deposited
			$totalWithdrawAmount = $this->Users->getTotalWithdrawAmount($userId,20);    //The total KRW amount user has withdrawn from their main account
            $totalWithdrawTradingAmount = $this->Users->getTotalWithdrawTradingAmount($userId,20);  //The total KRW amount user has withdrawn from their trading account

            //If OTP number, Amount entered or the total amount to withdraw are empty
			if(empty($this->request->data['otp_number']) || empty($reqAmount) || empty($withdrawAmount)){
				$returnArr = ['success'=>"false","message"=>__("There is an empty item"),'message2'=>''];
				echo json_encode($returnArr); die;
			}

			//If the amount deposited is less than 200,000 KRW or the user hasn't performed any trade (buying or selling)
			if($totalDeposit < 200000 || $tradingBalance <= 0){
                $returnArr = ['success'=>"false","message"=>"출금 조건을 만족하지 않았습니다.<br> 출금 유의사항을 확인해주세요",'message2'=>''];
                echo json_encode($returnArr); die;
            }

			//If the amount to withdraw is less than 50,000 KRW or the amount to withdraw is exceeding your current KRW account balance, or you do not have enough KRW in your account
		/*	if($reqAmount < 50000 || $finalAmount <= 0 || $balance <= 0 ){
				$returnArr = ['success'=>"false","message"=>__("There's a problem with the amount."),'message2'=>''];
				echo json_encode($returnArr); die;
			}*/

			//If the amount is exceeding the withdrawal limit of upto three times the total deposited amount
		/*	if((abs($totalWithdrawAmount + $totalWithdrawTradingAmount) + $reqAmount) > $threefold){
				$returnArr = ['success'=>"false","message"=>__("You can withdraw up to three times the total deposit"),'message2'=>''];
				echo json_encode($returnArr); die;
			}*/

			//If the withdrawal amount is greater than the buying or selling balance, or the withdrawal amount is less than 50,000 KRW, or the minimum amount this user can withdraw is less than
            //50,000 KRW, or the user is trying to withdraw more amount than the amount the user can withdraw, or the withdrawal amount requested is more than the amount user has in their KRW Main account
		/*	if(($withdrawAmount > $buyBalance && $withdrawAmount > $sellBalance) || $toWithdraw < 50000 || $withdrawAmount > $toWithdraw || $withdrawAmount >= $currMainBalance) {
				$returnArr = ['success'=>"false","message"=>'The withdrawal conditions were not met' ,'message2'=>''];
				echo json_encode($returnArr); die;
			}*/

			//If these conditions are satisfied then proceed for the withdrawal process, all conditions need to be satisfied therefore we have used AND operator
            /* OTP Number should not be empty, the amount to withdraw should not be empty, the total amount to withdraw should not be empty, User's bank, email and OTP should be verified, user
             * The user must be approved for the KYC. Withdrawal amount should be greater than 50,000 KRW, Withdrawal amount should be less than the user's KRW main account balance,
             * Should not have any transaction in pending (Withdrawal or deposit), The withdrawal amount should be less than or equal to the Half of the user's total buy/sell transactions amount
             * Withdraw amount should be 50,000 KRW or more, the total amount inclusive of withdrawal fees should be less than or equal to the amount the user can withdraw, the withdrawal amount
             * should be less than user's current Main balance such that after withdrawal the user should still be left with some amount
             * */
			if (!empty($this->request->data['otp_number']) && !empty($reqAmount) && !empty($withdrawAmount) && $bankAuth == "Y" && $emailAuth == "Y" && $gAuth == "Y" && $reqAmount >= 50000 && $finalAmount > 0 && $valPen === "N" && $valPens === "N" && $balance > 0 && ($withdrawAmount <= $buyBalance ||
					$withdrawAmount <= $sellBalance) && $toWithdraw >= 50000 && $withdrawAmount <= $toWithdraw && $withdrawAmount < $currMainBalance) {

			    //double check that the withdrawal amount requested is not zero.
				if($this->request->data['total_amount'] != 0 && $this->request->data['req_amount'] != 0 ){
					$getInputCode = strip_tags($this->request->data['otp_number']);
					//If the OTP received is empty
					if (empty($getInputCode)) {
						$returnArr = ['success' => 'false', 'message' => __('Please enter authentication code.'),'message2'=>''];
						echo json_encode($returnArr);
						die;
					}
					//Verify the OTP received
					$checkResult = $this->Users->verifyCode($secret, $getInputCode, 2);    // 2 = 2*30sec clock tolerance

                    //If the OTP Received is correct
					if ($checkResult) {
						$insertArr = [];
						$amount = $this->request->data['total_amount'];
						$insertArr['user_id'] = $userId;
						$insertArr['amount'] = -$amount;    //Total amount inclusive of withdrawal fees
						$insertArr['coin_amount'] = $this->request->data['req_amount']; //Total amount exclusive of withdrawal fees
						$insertArr['cryptocoin_id'] = 20;
                        $insertArr['type'] = "bank_initial_withdraw";
						$insertArr['remark'] = "bank_initial_withdraw";
						$insertArr['status'] = "pending";
						$insertArr['fees'] = $this->request->data['fees'];
						$withdraw = $this->PrincipalWallet->newEntity();
						$withdraw = $this->PrincipalWallet->patchEntity($withdraw, $insertArr);
						if ($this->PrincipalWallet->save($withdraw)) {
							$mainBalance = $this->Users->getUserPricipalBalance($userId, 20);
							$returnArr = ['success' => "true", "message" => __('Withdrawn successfully'), 'data' => $mainBalance];
                        } else {
							$returnArr = ['success' => "false", "message" => __('Unable to Withdraw'),'message2'=>''];
                        }

                    } else {
						$returnArr = ['success' => 'false', 'message' => 'OTP 번호가 일치하지 않습니다.','message2'=>''];
                    }
                } else {
					$returnArr = ['success' => 'false', 'message' => __('Please fill all required fields.'),'message2'=>''];
                }
            } else {
				$returnArr = ['success' => 'false', 'message' => __('Withdrawal conditions do not match.'),'message2'=>''];
            }
        } else {
			$returnArr = ['success' => 'false', 'message' => __('Please enter correct password.'),'message2'=>''];
        }
        echo json_encode($returnArr);
        die;
    }
	//Withdrawal of KRW from Trading Account. This method is same like the previous method except that this is used to withdraw from trading account
	// Hassam - 210820
    public function krwWithdrawalTrading(){
        $this->loadModel('Transactions');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('NumberSevenSetting');
        if($this->request->is('ajax')) {
            $userId = $this->Auth->user('id');
            $user = $this->Users->get($userId);
            $secret = $user->g_secret;
            $bankAuth = $user['bank_verify'];
            $userLevel = $user['user_level'];
            $emailAuth = $user['email_auth'];
            $gAuth = $user['g_verify'];

            $pendingDeposit = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$userId,'status'=>'pending','type'=>'bank_initial_deposit']])->hydrate(false)->toArray();
            $pendingWithdraw = $this->PrincipalWallet->find('all',['conditions'=>['user_id'=>$userId,'status'=>'pending','type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();
            $pendingWithdrawTrading = $this->Transactions->find('all',['conditions'=>['user_id'=>$userId,'status'=>'pending','tx_type'=>'bank_initial_withdraw']])->hydrate(false)->toArray();
			$valPen = "N";
			$valPens = "N";
            if(!empty($pendingDeposit)){
                $valPen = "Y";
            }

            if(!empty($pendingWithdraw) || !empty($pendingWithdrawTrading)){
                $valPens = "Y";
            }

            //if($user['id_document_status'] != 'A' || $user['scan_copy_status'] != 'A') {
            //    $returnArr = ['success'=>"false","message"=>'레벨 2단계는 출금 할 수 없습니다.','message2'=>'인증단계로 이동 하시겠습니까?'];
            //    echo json_encode($returnArr); die;
            //}
/*
            if($bankAuth != "Y" || $emailAuth != "Y" | $gAuth != "Y"){
                $returnArr = ['success'=>"false","message"=>'레벨 1단계는 출금 할 수 없습니다.','message2'=>'인증단계로 이동 하시겠습니까?'];
                echo json_encode($returnArr); die;
            }*/
            if($valPen != "N" || $valPens != "N"){
                $returnArr = ['success'=>"false","message"=>'대기중인 입출금 요청이 있습니다.','message2'=>''];
                echo json_encode($returnArr); die;
            }

            //$googleAuthUrl = $this->Users->getQRCodeGoogleUrl('CoinIBT', $secret);
            $totalVal = $this->Users->getUserTotalDeposit($userId);
            $totalOldVal = $this->Users->getUserTotalOldDeposit($userId);
            $currMainBalance = $this->Users->getUserPricipalBalance($userId, 20);
            $tradingBalance = $this->Users->getLocalUserBalance($userId, 20);
            $withdrawAmount = $this->request->data['total_amount'];
            $reqAmount = $this->request->data['req_amount'];
            $totalDeposit = $totalVal + $totalOldVal;
            $getPercentage =  $this->NumberSevenSetting->find("all",["conditions"=>["status"=>"ACTIVE"],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
            $percent = $getPercentage['percentage'];
            $toWithdraw = $totalDeposit * ($percent/100);
            $finalAmount = $tradingBalance - $withdrawAmount;
            $halfBal = $tradingBalance/2;
            $balance = $tradingBalance - ($halfBal + 1000);
            $userTotalBuyBalance = $this->Users->getUserTotalBuy($userId);
            $buyBalance = $userTotalBuyBalance/2;
            $userTotalSellBalance = $this->Users->getUserTotalSell($userId);
            $sellBalance = $userTotalSellBalance/2;
            $threefold = $totalDeposit*3; // Three times the amount deposited
            $totalWithdrawAmount = $this->Users->getTotalWithdrawAmount($userId,20);
            $totalWithdrawTradingAmount = $this->Users->getTotalWithdrawTradingAmount($userId,20);

            /*if(empty($this->request->data['otp_number']) || empty($reqAmount) || empty($withdrawAmount)){
                $returnArr = ['success'=>"false","message"=>'비어 있는 항목이 있습니다.','message2'=>''];
                echo json_encode($returnArr); die;
            }*/

            /*if($totalDeposit < 200000 || $tradingBalance <= 0){
                $returnArr = ['success'=>"false","message"=>'비어 있는 항목이 있습니다.','message2'=>''];
                echo json_encode($returnArr); die;
            }*/

         /*   if($reqAmount < 50000 || $finalAmount <= 0 || $balance <= 0 ){
                $returnArr = ['success'=>"false","message"=>'금액에 문제가 있습니다.','message2'=>''];
                echo json_encode($returnArr); die;
            }*/

            if((abs($totalWithdrawAmount + $totalWithdrawTradingAmount) + $reqAmount) > $threefold){
                $returnArr = ['success'=>"false","message"=>__("You can withdraw up to three times the total deposit"),'message2'=>''];
                echo json_encode($returnArr); die;
            }

            if(($withdrawAmount > $buyBalance && $withdrawAmount > $sellBalance) || $toWithdraw < 50000 || $withdrawAmount > $toWithdraw || $withdrawAmount >= $tradingBalance) {
                $returnArr = ['success'=>"false","message"=>'출금 조건을 만족하지 않았습니다.<br> 출금 유의사항을 확인해주세요.' ,'message2'=>''];
                echo json_encode($returnArr); die;
            }

            if (!empty($this->request->data['otp_number']) && !empty($reqAmount) && !empty($withdrawAmount) && $bankAuth == "Y" && $emailAuth == "Y" && $gAuth == "Y" &&
                 $reqAmount >= 50000 && $finalAmount > 0 && $valPen === "N" && $valPens === "N" && $balance > 0 && ($withdrawAmount <= $buyBalance ||
                    $withdrawAmount <= $sellBalance) && $toWithdraw >= 50000 && $withdrawAmount <= $toWithdraw && $withdrawAmount < $tradingBalance) {

                if($this->request->data['total_amount'] != 0 && $this->request->data['req_amount'] != 0 ){

                    $getInputCode = strip_tags($this->request->data['otp_number']);

                    if (empty($getInputCode)) {
                        $returnArr = ['success' => 'false', 'message' => __('Please enter authentication code.'),'message2'=>''];

                        echo json_encode($returnArr);
                        die;
                    }
                    $checkResult = $this->Users->verifyCode($secret, $getInputCode, 2);    // 2 = 2*30sec clock tolerance

                    if ($checkResult) {
                        $insertArr = [];
                        $amount = $this->request->data['total_amount'];
                        $insertArr['user_id'] = $userId;
                        $insertArr['coin_amount'] = -$amount;
                        $insertArr['amount'] = $this->request->data['req_amount'];
                        $insertArr['cryptocoin_id'] = 20;
                        $insertArr['tx_type'] = "bank_initial_withdraw";
                        $insertArr['remark'] = "bank_initial_withdraw";
                        $insertArr['status'] = "pending";
                        $insertArr['fees'] = $this->request->data['fees'];
                        $withdraw = $this->Transactions->newEntity();
                        $withdraw = $this->Transactions->patchEntity($withdraw, $insertArr);
                        if ($this->Transactions->save($withdraw)) {
                            $mainBalance = $this->Users->getLocalUserBalance($userId, 20);
                            $returnArr = ['success' => "true", "message" => __('Withdrawn successfully'), 'data' => $mainBalance];
                        } else {
                            $returnArr = ['success' => "false", "message" => __('Unable to Withdraw'),'message2'=>''];
                        }

                    } else {
                        $returnArr = ['success' => 'false', 'message' => 'OTP 번호가 일치하지 않습니다.','message2'=>''];
                    }
                } else {
                    $returnArr = ['success' => 'false', 'message' => __('Please fill all required fields.'),'message2'=>''];
                }
            } else {
                $returnArr = ['success' => 'false', 'message' => __('Withdrawal conditions do not match.'),'message2'=>''];
            }
        } else {
            $returnArr = ['success' => 'false', 'message' => __('Please enter correct password.'),'message2'=>''];
        }
        echo json_encode($returnArr);
        die;
    }

	// Hassam - 210820
	public function getusercoinlistajax(){
        $this->loadModel('Transactions');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        $this->loadModel('ExchangeHistory');
        $mainRespArr =[];
        $authUserId = $this->Auth->user('id');
        $userDetail = $this->Users->get($authUserId);
        $getUserTotalCoin = $this->Transactions->find();
        $userCoinArr=[];
        $userCoinMakeArr=[];
        $singleArr = [];
        $getUserTotalCoinCnt = $getUserTotalCoin
            ->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id'])
            ->where(['Transactions.user_id'=>$authUserId,'Transactions.status'=>'completed','Transactions.tx_type !='=>'bank_initial_deposit'])
            ->group('cryptocoin_id')
            ->toArray();

        foreach($getUserTotalCoinCnt as $getUserTotalCoinSingle){
            $userCoinArr[$getUserTotalCoinSingle['cryptocoin_id']]= $getUserTotalCoinSingle['sum'];
        }
        $this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);

        $getCoinList = $this->Cryptocoin->find('all', ['conditions'=> ['status' => 1, 'short_name LIKE' => "%" . $this->request->data['coin_name'] . "%"], 'order' => ['serial_no' => 'asc']])->hydrate(false)->toArray();

        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $coinName = $getCoin['name'];
            $coinShortName = $getCoin['short_name'];
            $icon = $getCoin['icon'];
            $principalBalance = $this->Users->getUserPricipalBalance($authUserId,$coinId);
            $tradingBalance = $this->Users->getLocalUserBalance($authUserId,$coinId);
            //$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($authUserId,$coinId);
            $reserveSellBalance = $this->Users->getUserSellReserveBalance($authUserId,$coinId);
            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $customPriceTrading = (float)$tradingBalance * (float)$getMyCustomPrice;
            $customPriceMain = (float)$principalBalance * (float)$getMyCustomPrice;

            $address = "";
            if($getCoin['short_name'] == "BTC"){
                if(!empty($userDetail['btc_address'])){
                    $address = $userDetail['btc_address'];
                }
            }else{
                if(!empty($userDetail['eth_address'])){
                    $address=$userDetail['eth_address'];
                }
            }
            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;

            $singleArr = ['principalBalance' => number_format((float)$principalBalance,2),
                'tradingBalance' => number_format((float)$tradingBalance,2),
                'reserveBalance' => number_format((float)$reserveBalance,2),
                'coin_id' => $coinId,
                'icon' => $icon,
                'coinName' => $coinName,
                'short_name' => $coinShortName,
                'coinAddress' => $address,
                'customPriceTrading' => number_format((float)$customPriceTrading,2),
                'customPriceMain' => number_format((float)$customPriceMain,2),
                'krwValue' => number_format((float)$getMyCustomPrice,2)
            ];
            $mainRespArr[] = $singleArr;
        }
        echo json_encode($mainRespArr); die;
    }
	// Hassam 210820
	public function insertWalletAddress(){
		// echo $this->request->data['rwwd_wallet_addr'];die;
		if ($this->request->is('ajax')) {
			$this->loadModel('Cryptocoin');
			$getWalletAddr = $this->request->data['rwwd_wallet_addr'];
			$getWalletName = $this->request->data['rwwd_wallet_name'];
			$getTransType = $this->request->data['trans_type'];

			$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1,
			'OR' => array(
				'short_name' => $this->request->data['coinName'],
				'name' => $this->request->data['coinName'],
			)]])->hydrate(false)->first();

			if(empty($getCoinList)){
				$returnArr = ['success'=>"false","message"=>__('Please select a valid coin')];
				echo json_encode($returnArr); die;
			}
			$coinId = $getCoinList['id'];

			if($coinId==1){
				$validateAddr = $this->Users->validateBtcAddress($getWalletAddr);
				$jsonDecode = json_decode($validateAddr,true);
				if($jsonDecode['result']['isvalid']==false){
					$returnArr = ['success'=>"false","message"=>__('Please enter a valid BTC address')];
					echo json_encode($returnArr); die;
				}
			}
			// echo $getWalletName;die;

            $findTotal = 0;
			$authUserId  = $_SESSION['Auth']['User']['id'];
            $ctcUserWallet = $this->ctcwalletgetuserajax($getWalletAddr, 2);

		    if($getTransType == 'main') {
                if ($ctcUserWallet['success'] == 'false' || $ctcUserWallet['auth_yn'] == 'N') {
                    $returnArr = ['success' => "false", "message" => '전화번호 인증된 본인의 주소로만 출금이 가능합니다.'];
                    echo json_encode($returnArr);
                    die;
                } else {
                    $findTotal = $this->WithdrawalWalletAddress->find('all',['conditions'=>['user_id'=>$authUserId,'cryptocoin_id'=>$getCoinList['id'],'wallet_address'=>$getWalletAddr]])->hydrate(false)->count();
                }
            } else {
                if ($ctcUserWallet['success'] == 'false' || $ctcUserWallet['auth_yn'] == 'N') {

                } else {
                    $findTotal = $this->WithdrawalWalletAddress->find('all',['conditions'=>['user_id'=>$authUserId,'cryptocoin_id'=>$getCoinList['id']]])->hydrate(false)->count();
                }
            }

			if($findTotal >= 3){
				$returnArr = ['success'=>"false","message"=>__('Only 3 withdrawal addresses are allowed')];
				echo json_encode($returnArr); die;
			}

            $findDuplicate = $this->WithdrawalWalletAddress->find('all',['conditions'=>[
                'user_id'=>$authUserId,'cryptocoin_id'=>$getCoinList['id'], 'wallet_address' => $getWalletAddr
                ]])->hydrate(false)->first();

			if(!empty($findDuplicate)) {
                $returnArr = ['success'=>"false","message"=>__('You have already registered this wallet address')];
                echo json_encode($returnArr); die;
            }

			$saveDataArr = ['wallet_name' => $getWalletName,
						  'wallet_address' => $getWalletAddr,
						  'user_id' => $authUserId,
						  'cryptocoin_id' => $getCoinList['id']
						];
			$newObj = $this->WithdrawalWalletAddress->newEntity();
			$patchObj = $this->WithdrawalWalletAddress->patchEntity($newObj,$saveDataArr);
			$saveData = $this->WithdrawalWalletAddress->save($patchObj);
			if($saveData){
				$returnArr = ['success'=>"true","message"=>_('Address registered successfully')];
            }
			else {
				$returnArr = ['success'=>"false","message"=>__('Unable to register this address')];
            }
            echo json_encode($returnArr);
            die;
        }
		 die;
	}
	// Hassam 210820
	public function displayWalletAddress(){
		if ($this->request->is('ajax')) {
            $this->loadModel('Users');
			$this->loadModel('Cryptocoin');
			$authUserId  = $this->Auth->user('id');
			$getCoinList = $this->Cryptocoin->find('all', ['conditions' => ['status' => 1,
			'OR' => array(
				'short_name' => $this->request->data['coinName'],
				'name' => $this->request->data['coinName'],
			)]])->hydrate(false)->first();
            $user = $this->Users->get($authUserId);
            $type = $this->request->data['trans_type'];
            if (!empty($type)) {
                if ($type == "trading") {
                    $WithdrawalWalletAddressData = $this->WithdrawalWalletAddress->find('all', ['conditions' => ['user_id' => $authUserId, 'cryptocoin_id' => $getCoinList['id']]])->hydrate(false)->toArray();
                } else {
                    $WithdrawalWalletAddressData = $this->WithdrawalWalletAddress->find('all', [ 'conditions' => ['user_id' => $authUserId, 'cryptocoin_id' => $getCoinList['id'], 'wallet_name'=>$user->name]])->hydrate(false)->toArray();
                }
                if (!empty($WithdrawalWalletAddressData)) {
                    $returnArr = ['success' => "true", "message" => "",'data' => $WithdrawalWalletAddressData];
                } else {
                    $returnArr = ['success' => "false", "message"=> "", 'data' => $WithdrawalWalletAddressData];
                }
                echo json_encode($returnArr);
                die;
            }
        }
		die;
	}

	public function deleteWalletAddress(){
		// echo $this->request->data['rwwd_wallet_addr'];die;
		if ($this->request->is('ajax')) {

			// echo $getWalletName;die;
			$data3 =[];
			$data=$this->request->data['id'];
			$data1=explode("&",$this->request->data['id']);

			if(!empty($data1)){
				foreach($data1 as $value){

					$data2=explode("=on",$value);
					$data2 = array_filter($data2);
					$data3[] = $data2[0];


					if(!empty($data2)){
						$this->WithdrawalWalletAddress->deleteAll(array('id'=>$data2[0]));

					}

				}
			}
			$returnArr = ['success'=>"true","message"=>__('Success!'),"data"=>$data3];
			echo json_encode($returnArr); die;
			die;

		}

		 die;
	}

	public function otpWalletAddress(){
		// echo $this->request->data['rwwd_wallet_addr'];die;
		if ($this->request->is('ajax')) {


			// echo $getWalletName;die;
					$data['otp'] = rand(111111,99999999);
					$data['username'] = $_SESSION['Auth']['User']['username'];
					$_SESSION['otpwithdraw']=$data['otp'];

                    $email = new Email('default');
                    $email->viewVars(['data'=>$data]);
                    $email->from([$this->setting['email_from']] )
                        ->to($_SESSION['Auth']['User']['email'])
                        ->subject('OTP verification')
                        ->emailFormat('html')
                        ->template('otpwithdraw')
						->send();
						$returnArr = ['success'=>"true","message"=>__('Please check your email inbox for the OTP')];
						echo json_encode($returnArr); die;
		}

		 die;
	}




    public function bankdeposit(){
        if($this->request->is('ajax')) {
            if ($_SESSION['otpwithdraw'] == $this->request->data['otp_number']) {
                $this->loadModel('Cryptocoin');
                $this->loadModel('Settings');
                $authUserId = $_SESSION['Auth']['User']['id'];
                $adminWithdrawalFeePercent = $this->Users->getUserWithdrawalFee(20, $authUserId);
                $this->loadModel('Transactions');
                $this->loadModel('PrincipalWallet');
                $deductBalanceArr = ['amount' => -$this->request->data['req_amount_krw'],
                    'status' => 'completed',
                    'type' => 'bank_deposit',
                    'user_id' => $_SESSION['Auth']['User']['id'],
                    'cryptocoin_id' => 20];
                $newObj = $this->PrincipalWallet->newEntity();
                $newObj = $this->PrincipalWallet->patchEntity($newObj, $deductBalanceArr);
                $saveThisData = $this->PrincipalWallet->save($newObj);
                $fee_amount = $this->request->data['req_amount_krw'] * $adminWithdrawalFeePercent / 100;
                $deductBalanceArr = ['coin_amount' => $fee_amount,
                    'status' => 'completed',
                    'tx_type' => 'bank_deposit_fee',
                    'remark' => 'bank_deposit_fee',
                    'user_id' => 1,
                    'cryptocoin_id' => 20];
                $newObj = $this->PrincipalWallet->newEntity();
                $newObj = $this->PrincipalWallet->patchEntity($newObj, $deductBalanceArr);
                $saveThisData = $this->PrincipalWallet->save($newObj);
                $returnArr = ['success' => "true", "message" => __("Success!")];
                echo json_encode($returnArr);
                die;
            } else {
                $returnArr = ['success'=>"false","message"=>__('Please enter a valid OTP')];
                echo json_encode($returnArr); die;
            }
        }
    }

    public function myDepositListAjax(){

        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $userId = $this->Auth->user('id');

        $myDepositList = $this->PrincipalWallet->find('all',['conditions'=>['user_id '=>$userId,
            'type '=>'bank_initial_deposit', 'status !='=>'deleted'
        ],
            //'limit' => 10,
            'order' => ['id'=>'desc']])
            ->hydrate(false)
            ->toArray();

        $returnData['myDepositList'] = $myDepositList;

        echo json_encode($returnData); die;
    }
	// Hassam 210820
    public function myWithdrawListAjax(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Transactions');
        $userId = $this->Auth->user('id');
        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("(SELECT coin_amount, fees, status, created_at FROM principal_wallet WHERE user_id=".$userId." AND type='bank_initial_withdraw' AND status != 'deleted') UNION (SELECT amount, fees, status, created FROM transactions WHERE user_id=".$userId." AND tx_type = 'bank_initial_withdraw' AND status != 'deleted') ORDER BY created_at DESC");
        $returnData['myWithdrawList'] = $stmt->fetchAll('assoc');
        echo json_encode($returnData);
        die;
    }
	// Hassam 210820
	public function rquestWithdrawWalletAddress(){
		if ($this->request->is('ajax')) {
			$this->loadModel('Cryptocoin');
			$this->loadModel('Settings');
            $this->loadModel('Users');
            $this->loadModel('PrincipalWallet');
            $this->loadModel('Transactions');
			$userId  = $this->Auth->user('id');
            $user = $this->Users->get($userId);
            $secret = $user->g_secret;
            $ethPvtKeyTestNet = $user->eth_test_pvt_key;
            $bankAuth = $user['bank_verify'];
			$getCoinList = $this->Cryptocoin->find('all', ['conditions' => ['status' => 1,
			'OR' => array(
				'short_name' => $this->request->data['coinName'],
				'name' => $this->request->data['coinName'],
			)]])->hydrate(false)->first();

			$WithdrawalWalletAddressData = $this->WithdrawalWalletAddress->find('all', ['conditions' => ['user_id' => $userId, 'cryptocoin_id' => $getCoinList['id'], 'wallet_address' => $this->request->data['wallet_address']]])->hydrate(false)->toArray();
				if (empty($WithdrawalWalletAddressData)) {
					$returnArr = ['success' => "false", "message" => __('Invalid withdrawal address')];
					echo json_encode($returnArr); die;
				} else {
                    $getUserTotalCoin = $this->Transactions->find();
                    $userCoinArr = [];
                    $userCoinMakeArr = [];
                    $getUserTotalCoinCnt = $getUserTotalCoin
                        ->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'), 'Transactions.cryptocoin_id'])
                        ->where(['Transactions.user_id' => $userId, 'Transactions.status' => 'completed', 'Transactions.tx_type !=' => 'bank_initial_deposit'])
                        ->group('cryptocoin_id')
                        ->toArray();

                    foreach ($getUserTotalCoinCnt as $getUserTotalCoinSingle) {
                        $userCoinArr[$getUserTotalCoinSingle['cryptocoin_id']] = $getUserTotalCoinSingle['sum'];
                    }
                    $this->set('getUserTotalCoinCnt', $getUserTotalCoinCnt);

                    $coinId = $getCoinList['id'];
                    $coinName = $getCoinList['name'];
                    $coinShortName = $getCoinList['short_name'];
                    $principalBalance = $this->Users->getUserPricipalBalance($userId, $coinId);
                    $tradingBalance = $this->Users->getLocalUserBalance($userId, $coinId);
                    //$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
                    $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId, $coinId);
                    $reserveSellBalance = $this->Users->getUserSellReserveBalance($userId, $coinId);
                    $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
                    $totalVal = $this->Users->getUserTotalDeposit($userId);
                    $totalOldVal = $this->Users->getUserTotalOldDeposit($userId);
                    $totalDeposit = $totalVal + $totalOldVal;
                    $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
                    $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
                    $customPriceTrading = (float)$tradingBalance * (float)$getMyCustomPrice;
                    $customPriceMain = (float)$principalBalance * (float)$getMyCustomPrice;

                    $mainRespArr = ['principalBalance' => number_format((float)$principalBalance, 4),
                        'tradingBalance' => number_format((float)$tradingBalance, 4),
                        'reserveBalance' => number_format((float)$reserveBalance, 4),
                        'coinId' => $coinId,
                        'coinName' => $coinName,
                        'coinShortName' => $coinShortName,
                        'customPriceTrading' => number_format((float)$customPriceTrading,2),
                        'customPriceMain' => number_format((float)$customPriceMain,2),
                        'krwValue' => number_format((float)$getMyCustomPrice,2)
                    ];

                    if ($this->request->data['value'] == "external") {
                        $newprice = $mainRespArr['principalBalance'];
                    } else {
                        $newprice = $mainRespArr['tradingBalance'];
                    }
                    $price = str_replace(',', '', $newprice);
                    $prices = (float)$price;

                    //If the requested withdrawal amount is greater than the available balance of user
                    if ($this->request->data['req_amount'] > $prices || $this->request->data['total_amount'] > $prices) {
                        $returnArr = ['success' => "false", "message" => __('Please enter a valid amount')];
                        echo json_encode($returnArr);
                        die;
                    }

                    //if($user['id_document_status'] != 'A' || $user['scan_copy_status'] != 'A') {
                    //    $returnArr = ['success'=>"false","message"=>'레벨 2단계는 출금 할 수 없습니다.','message2'=>'인증단계로 이동 하시겠습니까?'];
                    //    echo json_encode($returnArr); die;
                    //}

                   /* if ($totalDeposit < 200000 || $tradingBalance <= 0) {
                        $returnArr = ['success' => "false", "message" => '비어 있는 항목이 있습니다.', 'message2' => ''];
                        echo json_encode($returnArr);
                        die;
                    }*/

                 /*   if (!empty($this->request->data['otp_number']) && !empty($this->request->data['req_amount']) && $bankAuth == "Y" ) {
                        $getInputCode = strip_tags($this->request->data['otp_number']);

                        if (empty($getInputCode)) {
                            $returnArr = ['success' => 'false', 'message' => 'Please enter authentication code.'];
                            echo json_encode($returnArr);
                            die;
                        }
                        $checkResult = $this->Users->verifyCode($secret, $getInputCode, 2);    // 2 = 2*30sec clock tolerance*/
                    if (!empty($this->request->data['req_amount']) && $bankAuth == "Y" ) {
                        if ($bankAuth == "Y") {

                            if ($this->request->data['value'] == "external" && $this->request->data['req_amount'] < $prices) {

                              /*  if ($totalDeposit < 200000) {
                                    $returnArr = ['success' => "false", "message" => '비어 있는 항목이 있습니다.', 'message2' => ''];
                                    echo json_encode($returnArr);
                                    die;
                                }
                                if ($tradingBalance <= 0) {
                                    $returnArr = ['success' => "false", "message" => '비어 있는 항목이 있습니다.', 'message2' => ''];
                                    echo json_encode($returnArr);
                                    die;
                                }*/

                                $address = '';
                                $adminWithdrawalFeePercent = $this->Users->getUserWithdrawalFee($coinId, $userId);

                                $amount = $this->request->data['req_amount'];
                                $fee_amount = $amount * $adminWithdrawalFeePercent / 100;
                                $totalAmount = $amount - $fee_amount;
                                $wallet_address = $this->request->data['wallet_address'];

                                $ctcwalletgetuser = $this->ctcwalletgetuserajax($wallet_address, 2); //
                                if ($ctcwalletgetuser['success'] == 'false' || $ctcwalletgetuser['auth_yn'] == 'N') {
                                    $returnArr = ['success' => "false", "message" => '전화번호 인증된 본인의 주소로만 출금이 가능합니다.'];
                                    echo json_encode($returnArr);
                                    die;
                                }
                                $deductBalancesArr = ['coin_amount' => $totalAmount, 'amount' => -$amount, 'fees' => $fee_amount, 'status' => 'completed', 'type' => 'withdrawal',
                                    'user_id' => $userId, 'wallet_address' => $wallet_address,
                                    'cryptocoin_id' => $getCoinList['id']];

                                //CTC WALLET API INTEGRATION START
                                $auth_key = 'BE14273125KL';
                                $kind = 'withdrawal_epay_only_my_account';
                                $coin_type = $getCoinList['short_name'];
                                if ($coinId == 1) {
                                    $address = $user->btc_address;
                                } else {
                                    $address = $user->eth_address;
                                }
                                $data = array(
                                    'auth_key' => $auth_key,
                                    'kind' => $kind,
                                    'coin_type' => $coin_type,
                                    'wallet_address' => $wallet_address,
                                    'address' => $address,
                                    'users_id' => $userId,
                                    'amount' => $amount
                                );
                                $post_data = json_encode($data);

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_PORT => "",
                                    CURLOPT_URL => "https://cybertronchain.com/apis/coinibt/coinibt.php",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => "",
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 60,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => "POST",
                                    CURLOPT_POSTFIELDS => $post_data,
                                    CURLOPT_HTTPHEADER => array(
                                        "cache-control: no-cache",
                                        "content-type: application/json"
                                    ),
                                ));

                                $response = curl_exec($curl);
                                $err = curl_error($curl);
                                $decodeResp = json_decode($response, true);
                                $returnArr = [];
                                if (!empty($decodeResp)) {
                                    if ($decodeResp['code'] == 200) {
										$coins = array('5','7','17','18','19','20','21','23');
										/*if(in_array($coinId, $coins))  {
											$tokenType = ($coinId==18) ? "ETH" : "TOKEN";
                                            $curl = curl_init();

                                            curl_setopt_array($curl, array(
												CURLOPT_PORT => "3000",
                                                CURLOPT_URL => 'http://54.180.5.130:3000/multisign/withdrawal',
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_ENCODING => '',
                                                CURLOPT_MAXREDIRS => 10,
                                                CURLOPT_TIMEOUT => 0,
                                                CURLOPT_FOLLOWLOCATION => true,
                                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                CURLOPT_CUSTOMREQUEST => 'POST',
                                                CURLOPT_POSTFIELDS =>'{"sender_pvt_key":"'.$ethPvtKeyTestNet.'","to_address":"'.$wallet_address.'","amount":'.abs($totalAmount).',"token_type":"'.$tokenType.'"}',

CURLOPT_POSTFIELDS =>'{"sender_pvt_key":"c287d139c6847f18387363618e8ba6de9aa5b5fd2dff3277dfa7ab9331b66144","to_address":"'.$wallet_address.'","amount":'.abs($totalAmount).'}',


                                        CURLOPT_HTTPHEADER => array(
                                                    'Content-Type: application/json'
                                                ),
                                            ));

                                            $response = curl_exec($curl);

                                            curl_close($curl);

                                            $decodeResp = json_decode($response,true);

                                            if(empty($decodeResp["success"])){
                                                $returnArr = ['success' => "false", "message" => $decodeResp["message"], 'data' =>""];
                                                echo json_encode($returnArr);
                                                die;

                                            }
											$deductBalancesArr['multisign'] = "Y";
											$deductBalancesArr['multisign_index_id'] = $decodeResp['data']['multisign_index_id'];
											$deductBalancesArr['multisign_sign_count'] = 1;
                                        }*/

                                        $withdraw = $this->PrincipalWallet->newEntity();
                                        $withdraw = $this->PrincipalWallet->patchEntity($withdraw, $deductBalancesArr);
                                        $saveWithdrawal = $this->PrincipalWallet->save($withdraw);
                                        $deductBalanceArr = ['amount' => $fee_amount, 'status' => 'completed', 'type' => 'transaction_fee', 'remark' => 'transaction_fee', 'user_id' => 1,
                                            'cryptocoin_id' => $getCoinList['id']];
                                        $newObj = $this->PrincipalWallet->newEntity();
                                        $newObj = $this->PrincipalWallet->patchEntity($newObj, $deductBalanceArr);
                                        $saveThisData = $this->PrincipalWallet->save($newObj);
                                        if ($saveThisData && $saveWithdrawal) {
                                            $mainBalance = $this->Users->getUserPricipalBalance($userId, $coinId);
                                            $currentPrice = $this->Users->getCurrentPrice($coinId, 20);
                                            $valueArr = ['mainBalance' => $mainBalance, 'currentPrice' => $currentPrice];
                                            $returnArr = ['success' => "true", "message" => "success", 'data' => ['mylist' => $valueArr]];
                                        } else {
                                            $returnArr = ['success' => "false", "message" => "Unable to save"];
                                        }
                                        echo json_encode($returnArr);
                                        die;

                                    } else if ($decodeResp['code'] == 801) {
                                        $returnArr = ['success' => "false", "message" => $decodeResp['msg']];
                                        echo json_encode($returnArr);
                                        die;
                                    } else if ($decodeResp['code'] == 802) {
                                        $returnArr = ['success' => "false", "message" => $decodeResp['msg']];
                                        echo json_encode($returnArr);
                                        die;
                                    } else if ($decodeResp['code'] == 804) {
                                        $returnArr = ['success' => "false", "message" => $decodeResp['msg']];
                                        echo json_encode($returnArr);
                                        die;
                                    } else if ($decodeResp['code'] == 805) {
                                        $returnArr = ['success' => "false", "message" => $decodeResp['msg']];
                                        echo json_encode($returnArr);
                                        die;
                                    } else if ($decodeResp['code'] == 806) {
                                        $returnArr = ['success' => "false", "message" => $decodeResp['msg']];
                                        echo json_encode($returnArr);
                                        die;
                                    }

                                }
                                //CTC WALLET API INTEGRATION END

                            } else {
                                $deductBalanceArr = ['amount' => -$this->request->data['req_amount'],
                                    'status' => 'completed',
                                    'type' => 'withdrawal',
                                    'user_id' => $userId,
                                    'cryptocoin_id' => $getCoinList['id']];
                                $newObj = $this->PrincipalWallet->newEntity();
                                $newObj = $this->PrincipalWallet->patchEntity($newObj, $deductBalanceArr);
                                $saveThisData = $this->PrincipalWallet->save($newObj);
                                if ($saveThisData) {
                                    // add balance from main account
                                    $deductBalanceArr = ['coin_amount' => $this->request->data['req_amount'],
                                        'status' => 'completed',
                                        'tx_type' => 'purchase',
                                        'remark' => 'transfer_from_internal_account',
                                        'user_id' => $userId,
                                        'cryptocoin_id' => $getCoinList['id']];
                                    $newObj = $this->Transactions->newEntity();
                                    $newObj = $this->Transactions->patchEntity($newObj, $deductBalanceArr);
                                    $saveThisData = $this->Transactions->save($newObj);
                                    if ($saveThisData) {
                                        $respArr = ['status' => 'true', 'message' => "The amount is transferred to main account"];
                                    } else {
                                        $respArr = ['status' => 'false', 'message' => "Unable to transfer the amount to the main account"];
                                    }
                                    echo json_encode($respArr);
                                    die;
                                }
                                $returnArr = ['success' => "true", "message" => "success"];
                            }
                        } else {
                            $returnArr = ['success' => "false", "message" => "Please enter a valid OTP"];
                            echo json_encode($returnArr);
                            die;
                        }
                    } else {
                        $returnArr = ['success' => "false", "message" => "Please fill all the required fields"];
                        echo json_encode($returnArr);
                        die;
                    }
                }
		    }
		die;
	}
	// Hassam
	public function rquestWithdrawWalletAddressTrading(){
        // echo $this->request->data['rwwd_wallet_addr'];die;
        if ($this->request->is('ajax')) {
            $this->loadModel('Cryptocoin');
            $this->loadModel('Settings');
            $this->loadModel('Users');
            $this->loadModel('PrincipalWallet');
            $this->loadModel('Transactions');
            $userId = $this->Auth->user('id');
            $user = $this->Users->get($userId);
            $secret = $user->g_secret;
			$ethPvtKeyTestNet = $user->eth_test_pvt_key;
            $bankAuth = $user['bank_verify'];
            $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1,
                'OR' => array(
                    'short_name' => $this->request->data['coinName'],
                    'name' => $this->request->data['coinName'],
                )]])->hydrate(false)->first();

            $WithdrawalWalletAddressData = $this->WithdrawalWalletAddress->find('all',['conditions'=>['user_id'=>$userId,'cryptocoin_id'=>$getCoinList['id'],'wallet_address'=>$this->request->data['wallet_address']]])->hydrate(false)->toArray();
            if(empty($WithdrawalWalletAddressData)){
                $returnArr = ['success'=>"false","message"=>__('Invalid withdrawal address')];
                echo json_encode($returnArr); die;
            }else{
                $getUserTotalCoin = $this->Transactions->find();
                $userCoinArr=[];
                $userCoinMakeArr=[];
                $getUserTotalCoinCnt = $getUserTotalCoin
                    ->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id'])
                    ->where(['Transactions.user_id'=>$userId,'Transactions.status'=>'completed','Transactions.tx_type !='=>'bank_initial_deposit'])
                    ->group('cryptocoin_id')
                    ->toArray();

                foreach($getUserTotalCoinCnt as $getUserTotalCoinSingle){
                    $userCoinArr[$getUserTotalCoinSingle['cryptocoin_id']]= $getUserTotalCoinSingle['sum'];
                }
                $this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);

                $coinId = $getCoinList['id'];
                $coinName = $getCoinList['name'];
                $coinShortName = $getCoinList['short_name'];
                $principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId);
                $tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
                $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
                $reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);
                $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
                $totalVal = $this->Users->getUserTotalDeposit($userId);
                $totalOldVal = $this->Users->getUserTotalOldDeposit($userId);
                $totalDeposit = $totalVal + $totalOldVal;
                $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
                $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
                $customPriceTrading = (float)$tradingBalance * (float)$getMyCustomPrice;
                $customPriceMain = (float)$principalBalance * (float)$getMyCustomPrice;

                $mainRespArr = ['principalBalance'=>number_format((float)$principalBalance,4),
                    'tradingBalance'=>number_format((float)$tradingBalance,4),
                    'reserveBalance'=>number_format((float)$reserveBalance,4),
                    'coinId'=>$coinId,
                    'coinName'=>$coinName,
                    'coinShortName'=>$coinShortName,
                    'customPriceTrading' => number_format((float)$customPriceTrading,2),
                    'customPriceMain' => number_format((float)$customPriceMain,2),
                    'krwValue' => number_format((float)$getMyCustomPrice,2)
                ];

                if($this->request->data['value']=="external"){
                    $newprice=$mainRespArr['principalBalance'];
                }else{
                    $newprice=$mainRespArr['tradingBalance'];
                }
                $price = str_replace(',','',$newprice);
                $prices = (float)$price;

                if($this->request->data['req_amount']>$prices || $this->request->data['total_amount'] > $prices ){
                    $returnArr = ['success'=>"false","message"=>__('Please enter a valid amount')];
                    echo json_encode($returnArr); die;
                }

                //임시주석

                /*if($totalDeposit < 200000 || $tradingBalance <= 0){
                    $returnArr = ['success'=>"false","message"=>'금액을 확인해주세요.','message2'=>''];
                    echo json_encode($returnArr); die;
                }*/

                //if($user['id_document_status'] != 'A' || $user['scan_copy_status'] != 'A') {
                //    $returnArr = ['success'=>"false","message"=>'레벨 2단계는 출금 할 수 없습니다.','message2'=>'인증단계로 이동 하시겠습니까?'];
                //    echo json_encode($returnArr); die;
                //}

                //if (!empty($this->request->data['otp_number']) && !empty($this->request->data['req_amount']) && $bankAuth == "Y") {
                if (!empty($this->request->data['req_amount']) && $bankAuth == "Y") {
                    /*$getInputCode = strip_tags($this->request->data['otp_number']);

                    if (empty($getInputCode)) {

                        $returnArr = ['success' => 'false', 'message' => 'Please enter authentication code.2'];

                        echo json_encode($returnArr);
                        die;
                    }
                    $checkResult = $this->Users->verifyCode($secret, $getInputCode, 2);    // 2 = 2*30sec clock tolerance*/

                    if ($bankAuth == "Y") {

                        if ($this->request->data['value'] == "external" && $this->request->data['req_amount']<$prices) {

               /*             if($totalDeposit < 200000){
                                $returnArr = ['success'=>"false","message"=>'금액을 확인해주세요.','message2'=>''];
                                echo json_encode($returnArr); die;
                            }
                            if($tradingBalance <= 0){
                                $returnArr = ['success'=>"false","message"=>'금액을 확인해주세요.','message2'=>''];
                                echo json_encode($returnArr); die;
                            }*/

                            $address = '';
                            $adminWithdrawalFeePercent = $this->Users->getUserWithdrawalFee($coinId, $userId);
                            $amount = $this->request->data['req_amount'];
                            $fee_amount =  $amount * $adminWithdrawalFeePercent / 100;
                            $totalAmount = $amount - $fee_amount;
                            $wallet_address = $this->request->data['wallet_address'];

                            $ctcwalletgetuser = $this->ctcwalletgetallusersajax($wallet_address, 2); //

                            if($ctcwalletgetuser['success'] == 'false'){
                                $returnArr = ['success'=>"false","message"=>'전화번호 인증된 본인의 주소로만 출금이 가능합니다3.'];
                                echo json_encode($returnArr);
                                die;
                            }

//                                $deductBalanceArr = ['coin_amount'=>$totalAmount,'amount' => -$amount, 'fees'=> $fee_amount,'status' => 'completed', 'type' => 'withdrawal',
//                                    'user_id' => $_SESSION['Auth']['User']['id'],'wallet_address'=>$wallet_address,
//                                    'cryptocoin_id' => $getCoinList['id']];
                            $deductBalancesArr = ['coin_amount'=> -$amount, 'amount'=>$totalAmount, 'fees'=> $fee_amount,'status' => 'completed', 'tx_type' => 'withdrawal', 'user_id' => $userId,'wallet_address'=>$wallet_address,
                                'cryptocoin_id' => $getCoinList['id']];

                            //CTC WALLET API INTEGRATION START
                            $auth_key = 'BE14273125KL';
                            $kind = 'withdrawal_epay';
                            $coin_type = $getCoinList['short_name'];
                            if($coinId == 1){
                                $address = $user->btc_address;
                            } else {
                                $address = $user->eth_address;
                            }
                            $data = array(
                                'auth_key' => $auth_key,
                                'kind' => $kind,
                                'coin_type' => $coin_type,
                                'wallet_address' => $wallet_address,
                                'address' => $address,
                                'users_id' => $userId,
                                'amount' => $amount
                            );
                            $post_data = json_encode($data);

                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                                CURLOPT_PORT => "",
                                CURLOPT_URL => "https://cybertronchain.com/apis/coinibt/coinibt.php",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 60,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => $post_data,
                                CURLOPT_HTTPHEADER => array(
                                    "cache-control: no-cache",
                                    "content-type: application/json"
                                ),
                            ));

                            $response = curl_exec($curl);
                            $err = curl_error($curl);
                            $decodeResp = json_decode($response,true);
                            $returnArr = [];
                            if(!empty($decodeResp) ){
                                if ( $decodeResp['code'] == 200 ) {
									// send Test withdrawal transaction
                                    $coins = array('5','7','17','18','19','20','21','23');
                                    //멀티시그 오류인거같음
                                   /* if(in_array($coinId, $coins))  {
										$tokenType = ($coinId==18) ? "ETH" : "TOKEN";
                                        $curl = curl_init();

                                        curl_setopt_array($curl, array(
											CURLOPT_PORT => "3000",
                                            CURLOPT_URL => 'http://54.180.5.130:3000/multisign/withdrawal',
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => '',
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 0,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS =>'{"sender_pvt_key":"'.$ethPvtKeyTestNet.'","to_address":"'.$wallet_address.'","amount":'.abs($totalAmount).',"token_type":"'.$tokenType.'"}',
                                            //원래 주석 처리된 부분
                                            CURLOPT_POSTFIELDS =>'{"sender_pvt_key":"c287d139c6847f18387363618e8ba6de9aa5b5fd2dff3277dfa7ab9331b66144","to_address":"'.$wallet_address.'","amount":'.abs($totalAmount).'}',
                                            //
                                            CURLOPT_HTTPHEADER => array(
                                                'Content-Type: application/json'
                                            ),
                                        ));

                                        $response = curl_exec($curl);
                                        curl_close($curl);

                                        $decodeResp = json_decode($response,true);

                                        if(empty($decodeResp["success"])){
                                            $returnArr = ['success' => "false", "message" => $decodeResp["message"], 'data' =>""];
                                            echo json_encode($returnArr);
                                            die;

                                        }
                                        $deductBalancesArr['multisign'] = "Y";
                                        $deductBalancesArr['multisign_index_id'] = $decodeResp['data']['multisign_index_id'];
                                        $deductBalancesArr['multisign_sign_count'] = 1;
                                    }*/

                                    $withdraw = $this->Transactions->newEntity();
                                    $withdraw = $this->Transactions->patchEntity($withdraw, $deductBalancesArr);
                                    $saveWithdrawal = $this->Transactions->save($withdraw);

                                    $deductBalanceArr = ['coin_amount' => $fee_amount, 'status' => 'completed', 'tx_type' => 'transaction_fee', 'remark' => 'transaction_fee', 'user_id' => 1,
                                        'cryptocoin_id' => $getCoinList['id']];
                                    $newObj = $this->Transactions->newEntity();
                                    $newObj = $this->Transactions->patchEntity($newObj, $deductBalanceArr);
                                    $saveThisData = $this->Transactions->save($newObj);
                                    if ($saveThisData && $saveWithdrawal) {
                                        $mainBalance = $this->Users->getLocalUserBalance($userId, $coinId);
                                        $currentPrice = $this->Users->getCurrentPrice($coinId, 20);
                                        $valueArr = ['mainBalance' => $mainBalance, 'currentPrice' => $currentPrice];
                                        $returnArr = ['success' => "true", "message" => "success", 'data' => ['mylist' => $valueArr]];
                                    } else {
                                        $returnArr = ['success' => "false", "message" => "Unable to save"];
                                    }
                                    echo json_encode($returnArr);
                                    die;
                                } else if($decodeResp['code'] == 801) {
                                    $returnArr = ['success'=>"false","message"=>$decodeResp['msg']];
                                    echo json_encode($returnArr);
                                    die;
                                } else if($decodeResp['code'] == 802) {
                                    $returnArr = ['success'=>"false","message"=>$decodeResp['msg']];
                                    echo json_encode($returnArr);
                                    die;
                                } else if($decodeResp['code'] == 804) {
                                    $returnArr = ['success'=>"false","message"=>$decodeResp['msg']];
                                    echo json_encode($returnArr);
                                    die;
                                } else if($decodeResp['code'] == 805) {
                                    $returnArr = ['success'=>"false","message"=>$decodeResp['msg']];
                                    echo json_encode($returnArr);
                                    die;
                                } else if($decodeResp['code'] == 806) { // 에러코드 발견
                                    $returnArr = ['success'=>"false","message"=>$decodeResp['msg']."1111"];
                                    echo json_encode($returnArr);
                                    die;
                                }
                            }
                            //CTC WALLET API INTEGRATION END
                        } else {
                            $deductBalanceArr = ['amount' => -$this->request->data['req_amount'],
                                'status' => 'completed',
                                'type' => 'withdrawal',
                                'user_id' => $userId,
                                'cryptocoin_id' => $getCoinList['id']];
                            $newObj = $this->PrincipalWallet->newEntity();
                            $newObj = $this->PrincipalWallet->patchEntity($newObj, $deductBalanceArr);
                            $saveThisData = $this->PrincipalWallet->save($newObj);
                            if ($saveThisData) {
                                // add balance from main account
                                $deductBalanceArr = ['coin_amount' => $this->request->data['req_amount'],
                                    'status' => 'completed',
                                    'tx_type' => 'purchase',
                                    'remark' => 'transfer_from_internal_account',
                                    'user_id' => $userId,
                                    'cryptocoin_id' => $getCoinList['id']];
                                $newObj = $this->Transactions->newEntity();
                                $newObj = $this->Transactions->patchEntity($newObj, $deductBalanceArr);
                                $saveThisData = $this->Transactions->save($newObj);
                                if ($saveThisData) {
                                    $respArr = ['status' => 'true', 'message' => "The amount is transferred to main account"];
                                } else {
                                    $respArr = ['status' => 'false', 'message' => "Unable to transfer the amount to the main account"];
                                }
                                echo json_encode($respArr);
                                die;
                            }

                            $returnArr = ['success' => "true", "message" => "success"];
                        }

                    } else {
                        $returnArr = ['success' => "false", "message" => "Please enter a valid OTP"];
                        echo json_encode($returnArr);
                        die;
                    }
                } else {
                    $returnArr = ['success' => "false", "message" => "Please fill all the required fields"];
                    echo json_encode($returnArr);
                    die;
                }
            }
        }
        die;
    }
	// Hassam 210820
	public function registerWithdrawalWalletAddrAjax(){
		 if ($this->request->is('ajax')) {
			$getWalletAddr = $this->request->data['rwwd_wallet_addr'];
			$getWalletName = $this->request->data['rwwd_wallet_name'];
			$coinId = $this->request->data['coin_id'];

			$authUserId = $this->Auth->user('id');
			$findTotal = $this->WithdrawalWalletAddress->find('all', ['conditions' => ['user_id' => $authUserId, 'cryptocoin_id' => $coinId]])->hydrate(false)->count();
			if($findTotal >= 3){
				$returnArr = ['success'=>"false","message"=>"Only 3 withdrawal wallet addresses are allowed"];
				echo json_encode($returnArr); die;
			}
			$saveDataArr=['wallet_name' => $getWalletName,
						  'wallet_address' => $getWalletAddr,
						  'user_id' => $authUserId];
			$newObj = $this->WithdrawalWalletAddress->newEntity();
			$patchObj = $this->WithdrawalWalletAddress->patchEntity($newObj, $saveDataArr);
			$saveData = $this->WithdrawalWalletAddress->save($patchObj);
			if($saveData){
				$returnArr = ['success' => "true", "message" => "Wallet address has been registered successfully"];
            }
			else {
				//$returnArr = ['success' => "false", "message" => "Unable to register the wallet address"];
                $returnArr = ['success' => "true", "message" => "Wallet address has been registered successfully"];
            }
             echo json_encode($returnArr);
             die;
         }
		 die;
	}


	public function internaltransactionajax($coinId){
		$this->loadModel('Cryptocoin');
		$mainRespArr = [];
		$userId = $this->Auth->user('id');
		$getCoin = $this->Cryptocoin->find('all',['conditions'=>['id'=>$coinId]])->hydrate(false)->first();
				$coinId = $getCoin['id'];
				$coinName = $getCoin['name'];
				$coinShortName = $getCoin['short_name'];
				$principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId);
				$tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
				//$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
				$reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
				$reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);
				$reserveBalance = $reserveBuyBalance + $reserveSellBalance;

				$singleArr = ['principalBalance'=>number_format((float)$principalBalance,4),
							  'tradingBalance'=>number_format((float)$tradingBalance,4),
							  'reserveBalance'=>number_format((float)$reserveBalance,4),
							  'coinId'=>$coinId,
							  'coinName'=>$coinName,
							  'coinShortName'=>$coinShortName
				];



		$respArr=['status'=>'true','message'=>"coin list",'data'=>['coinlist'=>$singleArr]];

		echo json_encode($respArr); die;
	}
	// Hassam 210820
	public function tradingBalanceTotal(){
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $userId = $this->Auth->user('id');
        $reserveTotalBalance = 0;
        $tradingTotalBalance = 0;
        $total_value = 0 ;
        $singleArr = [];
        #$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
        //TODO 20220628 SOJO 날쿼리 추가
        $getCoinList = $this->Users->getUserCryptocoins($userId);
        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $coinName = $getCoin['name'];
            $coinShortName = $getCoin['short_name'];
            $icon = $getCoin['icon'];

            $tradingBalance = $getCoin['trade_amount'];
            $reserveBuyBalance = $getCoin['buy_amount'];
            $reserveSellBalance = $getCoin['sell_amount'];
//            $tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
//            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
//            $reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);

            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
            $reserveTotalBalance = $reserveTotalBalance + $reserveBalance;
            $tradingTotalBalance = $tradingTotalBalance + $tradingBalance;
           // $principalTotalBalance = $tradingTotalBalance + $principalBalance;

            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $currentCoinTotalVal = $tradingBalance*$getMyCustomPrice;
            $total_value = $total_value + $currentCoinTotalVal;
        }
        $singleArr = ['total_value'=>number_format((float)$total_value,2),
            'reserveTotalBalance'=>number_format((float)$reserveTotalBalance,2),
            'tradingTotalBalance'=>number_format((float)$tradingTotalBalance,2),
        ];

        $respArr=['status'=>'true','message'=>"balance list",'data'=>['total'=>$singleArr]];

        echo json_encode($respArr); die;
    }
	// Hassam 210820
    public function mainBalanceTotal(){
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $userId = $this->Auth->user('id');
        $reserveTotalBalance = 0;
        $tradingTotalBalance = 0;
        $mainTotalBalance = 0;
        $total_value = 0 ;
        $singleArr = [];
        $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
        foreach($getCoinList as $getCoin){
            $coinId = $getCoin['id'];
            $coinName = $getCoin['name'];
            $coinShortName = $getCoin['short_name'];
            $icon = $getCoin['icon'];
            $mainBalance = $this->Users->getUserPricipalBalance($userId,$coinId);
            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
            $reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);

            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
            $reserveTotalBalance = $reserveTotalBalance + $reserveBalance;
            $mainTotalBalance = $mainTotalBalance + $mainBalance;
            // $principalTotalBalance = $tradingTotalBalance + $principalBalance;

            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            $currentCoinTotalVal = $mainBalance*$getMyCustomPrice;
            $total_value = $total_value + $currentCoinTotalVal;


        }
        $singleArr = ['total_value'=>number_format((float)$total_value,2),
            'reserveTotalBalance'=>number_format((float)$reserveTotalBalance,2),
            'principalTotalBalance'=>number_format((float)$mainTotalBalance,2),
        ];

        $respArr=['status'=>'true','message'=>"balance list",'data'=>['total'=>$singleArr]];

        echo json_encode($respArr); die;
    }


	public function mainAndTradingBalanceTotal(){
		$this->loadModel('Cryptocoin');
		$this->loadModel('Users');
		$userId = $this->Auth->user('id');
		$reserveTotalBalance = 0;
		$tradingTotalBalance = 0;
		$principalTotalBalance = 0;
		$total_value = 0 ;
        $singleArr = [];
		$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
		foreach($getCoinList as $getCoin){
				$coinId = $getCoin['id'];
				$coinName = $getCoin['name'];
				$coinShortName = $getCoin['short_name'];
				$icon = $getCoin['icon'];
				$principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId);
				$tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
				$reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
				$reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);


				$reserveBalance = $reserveBuyBalance + $reserveSellBalance;
				$reserveTotalBalance = $reserveTotalBalance + $reserveBalance;
				$tradingTotalBalance = $tradingTotalBalance + $tradingBalance;
				$principalTotalBalance = $principalTotalBalance + $principalBalance;

				$getMyCustomPrice = $this->Users->getCurrentPrice($coinId,20);
				$getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
				$currentCoinTotalVal = $principalBalance*$getMyCustomPrice;
				$total_value = $total_value + $currentCoinTotalVal;


		}
            $singleArr = ['total_value'=>number_format((float)$total_value,2),
                'principalTotalBalance'=>number_format((float)$principalTotalBalance,2),
                'reserveTotalBalance'=>number_format((float)$reserveTotalBalance,2),
                'tradingTotalBalance'=>number_format((float)$tradingTotalBalance,2),

            ];


		$respArr=['status'=>'true','message'=>"balance list",'data'=>['total'=>$singleArr]];

		echo json_encode($respArr); die;
	}
	// Hassam 210820
	public function selectedCoinAmountAjax(){
		$this->loadModel('Cryptocoin');
		$this->loadModel('Users');
		$userId = $this->Auth->user('id');
		$reserveTotalBalance = 0;
		$principalTotalBalance = 0;
		$tradingTotalBalance = 0;
		$total_value = 0 ;
        $singleArr = [];
        if ($this->request->is('ajax')) {
            $coinId = $this->request->data['coin_id'];
            $type = $this->request->data['types'];

//            $principalBalance = $this->Users->getUserPricipalBalance($userId, $coinId);
//            $tradingBalance = $this->Users->getLocalUserBalance($userId, $coinId);
//            $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId, $coinId);
//            $reserveSellBalance = $this->Users->getUserSellReserveBalance($userId, $coinId);


            $getCoin = $this->Users->getUserCryptocoins($userId, $coinId);
            if ($getCoin['id'] == 20) {
                $principalBalance = $getCoin['wallet_amount'] + $getCoin['initial_withdraw'];
            } else {
                $principalBalance = $getCoin['wallet_amount'];
            }
            $tradingBalance = $getCoin['trade_amount'];
            $reserveBuyBalance = $getCoin['buy_amount'];
            $reserveSellBalance = $getCoin['sell_amount'];

            $reserveBalance = $reserveBuyBalance + $reserveSellBalance;
            $getMyCustomPrice = $this->Users->getCurrentPrice($coinId, 20);
            $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
            if ($type == "main") {
                $currentCoinTotalVal = $principalBalance * $getMyCustomPrice;
            } else {
                $currentCoinTotalVal = $tradingBalance * $getMyCustomPrice;
            }
            $singleArr = ['currentCoinTotalVal' => number_format((float)$currentCoinTotalVal, 2),
                'principalBalance' => number_format((float)$principalBalance, 2),
                'getMyCustomPrice' => number_format((float)$getMyCustomPrice, 2),
                'reserveBalance' => number_format((float)$reserveBalance, 2),
                'tradingBalance' => number_format((float)$tradingBalance, 2),
            ];
            $respArr = ['status' => 'true', 'message' => "coin balance", 'data' => ['current_coin' => $singleArr]];
            echo json_encode($respArr);
            die;
        }
	}


	// 21.01.27, YMJ
	// 21.08.20, Hassam Updated
	public function ctcwalletgetuserajax($wallet_address, $type = 1){
		$name = '';
		$phone = '';
		$returnArr = ['success'=>"false","message"=>"Error","name"=>$name,"phone"=>$phone];

		if ($this->request->is('ajax')) {
			$userId = $this->Auth->user('id');
			$users = $this->Users->get($userId);
			$name = $users['name'];
			$phone = $users['phone_number'];

			$auth_key = 'BE14273125KL';
			$kind = 'get_user2';
//			$kind = 'get_user';
            $data = array(
                'auth_key' => $auth_key,
                'kind' => $kind,
                'wallet_address' => $wallet_address,
                'phone_number'=> $phone
            );
            $post_data = json_encode($data);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_PORT => "",
                CURLOPT_URL => "https://cybertronchain.com/apis/coinibt/coinibt.php", //CURLOPT_URL => "https://cybertronchain.com/apis/coinibt/coinibt_test2.php",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $decodeResp = json_decode($response,true);

            if(!empty($decodeResp) ){
                if ( $decodeResp['code'] == 200 ) {
                    $returnArr = ['success'=>"true","message"=>$decodeResp['msg'],"name"=>$decodeResp['name'],"phone"=>$decodeResp['phone'],"auth_yn"=>$decodeResp['auth_yn']];
                } else {
                    $returnArr = ['success'=>"false","message"=>$decodeResp['msg'],"name"=>$name,"phone"=>$phone];
                }
            }
		}
		if ($type == 1) {
			echo json_encode($returnArr); die;
		} else if ($type == 2){
			return $returnArr;
		}
	}
	// Hassam 210820
	public function ctcwalletgetallusersajax($wallet_address, $type = 1){
        $name = '';
        $returnArr = ['success'=>"false","message"=>"Error","name"=>$name];
        if ($this->request->is('ajax')) {
            $userId = $this->Auth->user('id');
            $users = $this->Users->get($userId);
            $name = $users['name'];
            $auth_key = 'BE14273125KL';
			$kind = 'get_user';
            $data = array(
                'auth_key' => $auth_key,
                'kind' => $kind,
                'wallet_address' => $wallet_address
            );
            $post_data = json_encode($data);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_PORT => "",
                CURLOPT_URL => "https://cybertronchain.com/apis/coinibt/coinibt.php", //CURLOPT_URL => "https://cybertronchain.com/apis/coinibt/coinibt_test2.php",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $decodeResp = json_decode($response,true);

            if(!empty($decodeResp) ){
                if ( $decodeResp['code'] == 200 ) {
                    $returnArr = ['success'=>"true","message"=>$decodeResp['msg'],"name"=>$decodeResp['name']];
                } else {
                    $returnArr = ['success'=>"false","message"=>$decodeResp['msg'],"name"=>$name];
                }
            }
        }
        if ($type == 1) {
            echo json_encode($returnArr); die;
        } else if ($type == 2){
            return $returnArr;
        }
    }

}
