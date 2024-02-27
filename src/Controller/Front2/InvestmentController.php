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


class InvestmentController extends AppController
{
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }
	public function beforeRender(Event $event)
    {
		parent::beforeRender($event);
		if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'front2'){
			$action_name = $this->request->params['action'];
			if($action_name == 'mywalletlog'){
				$this->viewBuilder()->layout(false);
			}
		}
	}
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
        $this->Auth->allow(['index2','testindex2']);
		if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'front2'){
			$action_name = $this->request->params['action'];
			if($action_name == 'mywalletlog'){
				$this->viewBuilder()->template('mywalletlog');
			}
		}
	}

	public function index()
	{
		$this->loadModel('DepositApplicationStage');
		$get_stage = $this->DepositApplicationStage->find()->select(['stage'])->where(['status'=>'Y'])->first();
		$stage = 0;
		if(!empty($get_stage)){
			$stage = $get_stage->stage;
		}

        $this->set('kind', 'investment');
		$this->set('stage', $stage);
    }

    public function loans()
    {
        $this->set('kind', 'loans');
    }

    public function index2(){

        @$get_number = $_GET['userid'];
        @$get_name = $_GET['name'];
        @$get_userid = $_GET['loginid'];

        //특수기호 체크
        $special_pattern = "/[`~!@#$%^&*|\\\'\";:\/?^=^+_()<>]/";

        $data2 = array();

        if($get_number){

            if(preg_match($special_pattern,$get_number)){
                array_push($data2,"email='".$get_number."'");
            }else{
                array_push($data2,"phone_number='".$get_number."'");
            }
        }
        if($get_name){
            array_push($data2,"name='".$get_name."'");
        }
        if($get_userid){
            if(preg_match($special_pattern,$get_userid)){
                array_push($data2,"email='".$get_userid."'");
            }else{
                array_push($data2,"username='".$get_userid."'");
            }
        }


        //print_r($data2);
        //exit;

        //데이터 인증
        //받은 데이터로 id 체크
        $flag = $_GET['flag'];
        if($flag == 1){
            //내지갑
            $controller = 'Pages';
            $action = 'mywallet2';
        }else if($flag == 2){
            //트레이딩
            $controller = 'assets';
            $action = 'mycoins2';
        }else if($flag == 3){
            //스테이킹
            $controller = 'investment';
            $action = 'application2';
        }else if($flag == 4){
            //쿠폰
            $controller = 'document';
            $action = 'priceinfo2';
        }

        $this->loadModel('Users');
        $query = $this->Users->find();

        //$users_check = $query->select(['id', 'email', 'username', 'name', 'password', 'phone_number', 'user_type', 'referral_code', 'unique_id', 'ip_address', 'annual_membership','last_login','is_deleted','permission_person_info','permission_adv','onesignal_id','device_id','fingerprint_auth','fingerprint_hash','user_status','last_pw_change_date','login_fail_count','dormant','enabled'])->where([$data2])->hydrate(false)->first();;
       /* if($_GET['auth'] == "N"){
            echo '<script type="text/javascript">';
            echo 'alert("본인인증 완료후 사용가능합니다..");';
            echo 'window.location.href = "https://cybertronchain.com/wallet2/profile.php";';
            echo '</script>';
            exit;
        }*/
        if(count($data2) == 0){
            echo '<script type="text/javascript">';
            echo 'alert("본인인증 완료후 사용가능합니다..");';
            echo 'window.location.href = "https://cybertronchain.com/wallet2/profile.php";';
            echo '</script>';
            exit;
        }
        $users_check = $query->select()->where([$data2])->hydrate(false)->first();

        /*->where(['user_id'=>$userId,
            'OR' =>[['type' => 'transfer_to_trading_account'],['type' => 'transfer_from_trading_account']],])
            ->order(['id'=>'desc'])
            ->hydrate(false)->toArray();*/

        if(count($users_check)){
            $this->loadModel('loginSessions');
            $user = $users_check;
            $loginSessionsResult = $this->loginSessions($user);
            if($loginSessionsResult == 'success'){
                //print_r($_SESSION);
                $this->Auth->setUser($user);
                return $this->redirect(['controller'=>$controller,'action' => $action]);
            }
        }else{
            echo '<script type="text/javascript">';
            echo 'alert("CoinIBT 회원정보가없습니다.");';
            echo 'window.location.href = "https://cybertronchain.com/wallet2/index.php";';
            echo '</script>';
            exit;
        }
    }
    public function testindex2(){
        $this->loadModel('loginSessions');
    }

    private function getToken($length){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[rand(0, $max-1)];
        }
        return $token;
    }

    private function loginSessions ($user) {
        $this->loadModel('LoginSessions');
        $loginSession = $this->LoginSessions->find('all', ['conditions' => ['user_id'=>$user['id']],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
        $token = $this->getToken(10);
        //$token = $this->request->cookie('app_session');
        $this->request->session()->write('loginToken', $token);
        $this->request->session()->write('loginTokenUserId', $user['id']);
        $query = $this->LoginSessions->query();
        if(!empty($loginSession)){
            $query->update()->set(['status'=>'ACTIVE','token' => $token,'updated'=>date('Y-m-d H:i:s')])->where(['id' => $loginSession['id']])->execute();
        } else {
            $query->insert(['user_id','token','status','created','updated'])
                ->values(['user_id'=>$user['id'],'token'=>$token,'status'=>'ACTIVE','created'=>date('Y-m-d H:i:s'),'updated'=>date('Y-m-d H:i:s')])->execute();
        }
        return 'success';
    }

    //스테이킹 테스트 개발
    public function applicationdev(){
        $this->set('kind', 'investment');
        $this->set('title', 'Deposit Application');
        $this->loadModel('DepositApplicationList');
        $this->loadModel('DepositApplicationList2');
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('DepositApplicationAmountFee');
        $this->loadModel('DepositApplicationPeriodFee');
        $this->loadModel('DepositApplicationStage');
        $this->loadModel('NumberSixSetting');
        $this->loadModel('Transactions');

        $quantity_list = $this->DepositApplicationAmountFee->find()->select(['id','amount'])->order(['amount'=>'asc'])->all();
        $period_list = $this->DepositApplicationPeriodFee->find()->select(['id','period'])->order(['period'=>'asc'])->all();
        $get_stage = $this->DepositApplicationStage->find()->select(['stage'])->where(['status'=>'Y'])->first();
        $get_coin = $this->DepositApplicationStage->find()->select(['coin_name'])->where(['status'=>'Y'])->first();

        //$query = $this->NumberSixSetting->find()->select(['id','cryptocoin_id','amount','krw','short_name'=>'coin.short_name','time_limit']);
        //$settingList = $query->join(['coin' => ['table' => 'cryptocoin','type' => 'inner','conditions' => 'coin.id = cryptocoin_id']])->where(['NumberSixSetting.status'=>"ACTIVE"])->toArray();


        $coin = $get_coin->coin_name;
        $stage = 0;
        if(!empty($get_stage)){
            $stage = $get_stage->stage;
        }

        $this->set('quantity_list', $quantity_list);
        $this->set('period_list', $period_list);
        $this->set('stage', $stage);
        $this->set('coin',$coin);

        //17 Tp3 19 MC 21 CTC
        if($coin == 'TP3'){
            $coin_number = 17;
        }else if($coin == "MC"){
            $coin_number = 19;
        }else if($coin == "CTC"){
            $coin_number = 21;
        }

        $get_setting = $this->NumberSixSetting->find()->select(['id','cryptocoin_id','amount','krw','time_limit'])->where(['cryptocoin_id'=>$coin_number,'status'=>'ACTIVE'])->first();

        $this->set('amount',$get_setting->amount);
        $this->set('krw',$get_setting->krw);
        //echo $get_setting->krw;
        //exit;
        //원래는 TP3 였다
        //변경 코인명은 TP3가 아닌 현재 기수 형태로 진행 몇기수가 진행되고있는지 데이터를 가져오자
        //stage에서 현재 진행중인 코인명 데이터를 불러보자

        $Cryptocoin = $this->Cryptocoin->find()->select('id')->where(['short_name'=>$coin])->first();
        //$Cryptocoin = $this->Cryptocoin->find()->select('id')->where(['short_name'=>'KRW'])->first();
        $coinId = $Cryptocoin->id;


        $userId = $this->Auth->user('id');
        $user = $this->Users->get($userId);
        $secondaryBalance = $this->Users->getLocalUserBalance($userId, $coinId);
        $mainBalance = $this->Users->getUserPricipalBalance($userId, $coinId);
        $krw_query = $this->PrincipalWallet->find()->select(['amount'=>'sum(amount)'])->where(['cryptocoin_id'=>20,'user_id'=>$userId,'status'=>'completed'])->first();
        $total_krw = $krw_query->amount; //내 KRW
        $totalBalance = $mainBalance + $secondaryBalance;
        //내 토탈 KRW 코인값을 알아야겠지? 얼마만큼 있는지 체크
        $tp3_value = $this->Users->getUserPricipalBalance($userId, 17);
        $mc_value = $this->Users->getUserPricipalBalance($userId, 19);
        //지갑내에 지금된 코인만 가져온다

        $tp3_query = $this->PrincipalWallet->find()->select(['amount'=>'sum(amount)'])->where(['cryptocoin_id'=>17,'user_id'=>$userId,'type'=>'event_coin'])->first();
        $mc_query = $this->PrincipalWallet->find()->select(['amount'=>'sum(amount)'])->where(['cryptocoin_id'=>19,'user_id'=>$userId,'type'=>'event_coin'])->first();

        $mc_new_value = $mc_query->amount;
        $tp3_new_value = $tp3_query->amount;


        //$this->set('tp3_value',$tp3_value);
        //$this->set('mc_value',$mc_value);
        $this->set('tp3_value',$tp3_new_value);
        $this->set('mc_value',$mc_new_value);
        $this->set('mainBalance', $mainBalance);
        $this->set('totalBalance', $totalBalance);
        $this->set('total_krw',$total_krw);

        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $coinData = $this->PrincipalWallet->newEntity();

        $password = $this->request->data("password");
        $existedHassPass = $user['password'];
        $checkPass = (new DefaultPasswordHasher)->check($password,$existedHassPass);


        if ($this->request->is(['post', 'put'])) {
        //if ($checkPass == true) {
            if($stage == 0){
                $this->Flash->error(__('Service Check Information Text'));
                return $this->redirect(['action' => 'applicationdev']);
            }
            if (!empty($this->request->data)) {
                if(!empty($this->request->data)) {
                    $servicePeriod = $this->request->data['servicePeriod'];
                    $quantity = $this->request->data['quantity'];
                    $investment_number = $this->request->data['investment_number'];
                    $coin_quantity = $this->request->data['coin_value'];
                    $krw_quantity = $this->request->data['krw_value'];
                    $coupon_cnt = $this->request->data['coupon_cnt'];
                    //추가 코인값
                    $coin_type = $this->request->data['coin_type'];
                    $coin_value_mc = $this->request->data['coin_value_mc'];
                    $coin_value_tp3 = $this->request->data['coin_value_tp3'];
                    $subQuantity = 0 - $quantity;
                    $insertCoinArr = [];
                    $insertCoinArr['user_id'] = $userId;
                    $insertCoinArr['cryptocoin_id'] = $coinId;
                    $insertCoinArr['amount'] = $subQuantity;
                    $insertCoinArr['type'] = "loan_deposit";
                    $insertCoinArr['wallet_address'] = "";
                    $insertCoinArr['status'] = "completed";


                    //데이터 저장 추출

/*
                    echo "KRW ->".$krw_query->amount; //KRW 소비 쿠폰값
                    echo "<br>";
                    echo "COIN ->".$coin_quantity; // 신청수량
                    echo "<br>";
                    echo "KRW ->".$krw_quantity; // 신청수량
                    echo "<br>";
                    echo "신청날짜 ->".$servicePeriod; // 신청날짜
                    echo "<br>";
                    echo "신청한 쿠폰수량 ->".$coupon_cnt;
                    echo "<br>";*/

                    $coinData = $this->PrincipalWallet->patchEntity($coinData, $insertCoinArr);

                    if ($this->PrincipalWallet->save($coinData)) {
                        $query = $this->DepositApplicationList->query();


                        //echo 'user_id=>'.$userId.'quantity=>'.$coin_value.'unit=>'.$coin_type.'service_period_month=>'.$servicePeriod.'previous_balance=>'.$mainBalance.'status=>'."A".'investment_number=>'.$investment_number.'created=>'.date('Y-m-d H:i:s').'approval_date=>'.date('Y-m-d H:i:s');
                          //  exit;
                        $query->insert(['user_id','quantity','unit','service_period_month','previous_balance','status','investment_number','created','approval_date'])
                            ->values(['user_id'=>$userId,'quantity'=>$coin_quantity,'unit'=>$coin_type,'service_period_month'=>$servicePeriod,'previous_balance'=>$mainBalance,'status'=>"A",'investment_number'=>'111','created'=>date('Y-m-d H:i:s'),'approval_date'=>date('Y-m-d H:i:s')])
                            ->execute();


                        //쿠폰 부분 적용 작업
                        $query2 = $this->PrincipalWallet->query();
                        //$quantity_list = $this->NumberSixSetting->find()->select(['cryptocoin_id','amount','krw'])->where(['status'=>'ACTIVE','cryptocoin_id'=>'17'])->first();

                        /*$query2->insert(['user_id','cryptocoin_id','amount','type','remark','status','created_at'])
                                ->values(['user_id'=>$userId,'cryptocoin_id'=>"20",'amount'=>-$krw_quantity,'type'=>'bought_coupon_krw','remark'=>"bought_coupon_krw",'status'=>'completed','created_at'=>date('Y-m-d H:i:s')])
                                ->execute();*/

                        //메인밸런스값도 조정한다

                        if($coin_type == 'TP3'){
                            $coin_number2 = 17;
                        }else if($coin_type == "MC"){
                            $coin_number2 = 19;
                        }else if($coin_type == "CTC"){
                            $coin_number2 = 21;
                        }


                        $query2->insert(['user_id','cryptocoin_id','amount','type','remark','status','created_at'])
                            ->values(['user_id'=>$userId,'cryptocoin_id'=>20,'amount'=>-$krw_quantity,'type'=>'event_coin','remark'=>"loan_deposit",'status'=>'completed','created_at'=>date('Y-m-d H:i:s')])
                            ->execute();


                        $this->Flash->success(__('You have deposited successfully. We will verify it soon'));
                        return $this->redirect(['action' => 'history2']);
                    } else {
                        $this->Flash->error(__('Unable to submit deposit.'));
                        return $this->redirect(['action' => 'applicationdev']);
                    }
                } else {
                    $this->Flash->error(__('Unable to submit deposit.'));
                    return $this->redirect(['action' => 'applicationdev']);

                }
                $this->Session->delete('Flash');
            }
        }
    }




	public function application()
	{
        $this->set('kind', 'investment');
        $this->set('title', 'Deposit Application');
        $this->loadModel('DepositApplicationList');
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
		$this->loadModel('Cryptocoin');
		$this->loadModel('DepositApplicationAmountFee');
		$this->loadModel('DepositApplicationPeriodFee');
		$this->loadModel('DepositApplicationStage');

		$quantity_list = $this->DepositApplicationAmountFee->find()->select(['id','amount'])->order(['amount'=>'asc'])->all();
		$period_list = $this->DepositApplicationPeriodFee->find()->select(['id','period'])->order(['period'=>'asc'])->all();
		$get_stage = $this->DepositApplicationStage->find()->select(['stage'])->where(['status'=>'Y'])->first();
		$stage = 0;
		if(!empty($get_stage)){
			$stage = $get_stage->stage;
		}

		$this->set('quantity_list', $quantity_list);
        $this->set('period_list', $period_list);
		$this->set('stage', $stage);

		$Cryptocoin = $this->Cryptocoin->find()->select('id')->where(['short_name'=>'TP3'])->first();
		$coinId = $Cryptocoin->id;
        $userId = $this->Auth->user('id');
        $user = $this->Users->get($userId);
        $secondaryBalance = $this->Users->getLocalUserBalance($userId, $coinId);
        $mainBalance = $this->Users->getUserPricipalBalance($userId, $coinId);
        $totalBalance = $mainBalance + $secondaryBalance;
        $this->set('mainBalance', $mainBalance);
        $this->set('totalBalance', $totalBalance);

        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $coinData = $this->PrincipalWallet->newEntity();

        $password = $this->request->data("password");
        $existedHassPass = $user['password'];
        $checkPass = (new DefaultPasswordHasher)->check($password,$existedHassPass);

        if ($this->request->is(['post', 'put'])) {
			if($stage == 0){
				$this->Flash->error(__('Service Check Information Text'));
                return $this->redirect(['action' => 'application']);
			}
            if (!empty($this->request->data)) {
                if($checkPass) {
                    $servicePeriod = $this->request->data['servicePeriod'];
                    $quantity = $this->request->data['quantity'];
					$investment_number = $this->request->data['investment_number'];

                    $subQuantity = 0 - $quantity;
                    $insertCoinArr = [];
                    $insertCoinArr['user_id'] = $userId;
                    $insertCoinArr['cryptocoin_id'] = $coinId;
                    $insertCoinArr['amount'] = $subQuantity;
                    $insertCoinArr['type'] = "loan_deposit";
                   // $insertCoinArr['wallet_address'] = "";
                    $insertCoinArr['status'] = "completed";

                    $coinData = $this->PrincipalWallet->patchEntity($coinData, $insertCoinArr);
                    if ($this->PrincipalWallet->save($coinData)) {
						$query = $this->DepositApplicationList->query();
						$query->insert(['user_id','quantity','unit','service_period_month','previous_balance','status','investment_number','created'])
							->values(['user_id'=>$userId,'quantity'=>$quantity,'unit'=>"TP3",'service_period_month'=>$servicePeriod,'previous_balance'=>$mainBalance,'status'=>"P",'investment_number'=>$investment_number,'created'=>date('Y-m-d H:i:s')])
							->execute();
                        $this->Flash->success(__('You have deposited successfully. We will verify it soon'));
                        return $this->redirect(['action' => 'history']);
                    } else {
                        $this->Flash->error(__('Unable to submit deposit.'));
                        return $this->redirect(['action' => 'application']);
                    }
                } else {
                    $this->Flash->error(__('Unable to submit deposit.'));
                    return $this->redirect(['action' => 'application']);

                }
                $this->Session->delete('Flash');
            }
        }

	}
    public function application2()
    {
        $this->set('kind', 'investment');
        $this->set('title', 'Deposit Application');
        $this->loadModel('DepositApplicationList');
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('DepositApplicationAmountFee');
        $this->loadModel('DepositApplicationPeriodFee');
        $this->loadModel('DepositApplicationStage');

        $quantity_list = $this->DepositApplicationAmountFee->find()->select(['id','amount'])->order(['amount'=>'asc'])->all();
        $period_list = $this->DepositApplicationPeriodFee->find()->select(['id','period'])->order(['period'=>'asc'])->all();
        $get_stage = $this->DepositApplicationStage->find()->select(['stage'])->where(['status'=>'Y'])->first();
        $stage = 0;
        if(!empty($get_stage)){
            $stage = $get_stage->stage;
        }

        $this->set('quantity_list', $quantity_list);
        $this->set('period_list', $period_list);
        $this->set('stage', $stage);

        $Cryptocoin = $this->Cryptocoin->find()->select('id')->where(['short_name'=>'TP3'])->first();
        $coinId = $Cryptocoin->id;
        $userId = $this->Auth->user('id');
        $user = $this->Users->get($userId);
        $secondaryBalance = $this->Users->getLocalUserBalance($userId, $coinId);
        $mainBalance = $this->Users->getUserPricipalBalance($userId, $coinId);
        $totalBalance = $mainBalance + $secondaryBalance;
        $this->set('mainBalance', $mainBalance);
        $this->set('totalBalance', $totalBalance);

        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $coinData = $this->PrincipalWallet->newEntity();

        $password = $this->request->data("password");
        $existedHassPass = $user['password'];
        $checkPass = (new DefaultPasswordHasher)->check($password,$existedHassPass);

        if ($this->request->is(['post', 'put'])) {
            if($stage == 0){
                $this->Flash->error(__('Service Check Information Text'));
                return $this->redirect(['action' => 'application2']);
            }
            if (!empty($this->request->data)) {
                if($checkPass) {
                    $servicePeriod = $this->request->data['servicePeriod'];
                    $quantity = $this->request->data['quantity'];
                    $investment_number = $this->request->data['investment_number'];

                    $subQuantity = 0 - $quantity;
                    $insertCoinArr = [];
                    $insertCoinArr['user_id'] = $userId;
                    $insertCoinArr['cryptocoin_id'] = $coinId;
                    $insertCoinArr['amount'] = $subQuantity;
                    $insertCoinArr['type'] = "loan_deposit";
                    // $insertCoinArr['wallet_address'] = "";
                    $insertCoinArr['status'] = "completed";

                    $coinData = $this->PrincipalWallet->patchEntity($coinData, $insertCoinArr);
                    if ($this->PrincipalWallet->save($coinData)) {
                        $query = $this->DepositApplicationList->query();
                        $query->insert(['user_id','quantity','unit','service_period_month','previous_balance','status','investment_number','created'])
                            ->values(['user_id'=>$userId,'quantity'=>$quantity,'unit'=>"TP3",'service_period_month'=>$servicePeriod,'previous_balance'=>$mainBalance,'status'=>"P",'investment_number'=>$investment_number,'created'=>date('Y-m-d H:i:s')])
                            ->execute();
                        $this->Flash->success(__('You have deposited successfully. We will verify it soon'));
                        return $this->redirect(['action' => 'history']);
                    } else {
                        $this->Flash->error(__('Unable to submit deposit.'));
                        return $this->redirect(['action' => 'application2']);

                    }
                } else {
                    $this->Flash->error(__('Unable to submit deposit.'));
                    return $this->redirect(['action' => 'application2']);

                }
                $this->Session->delete('Flash');
            }
        }

    }
    public function depositHistory(){
        if ($this->request->is('ajax')) {
            $this->loadModel('DepositApplicationList');
            $this->loadModel('Users');
			$this->loadModel('Cryptocoin');
//            $this->loadModel('Transactions');
//            $this->loadModel('PrincipalWallet');
            $userId = $this->Auth->user('id');
			$Cryptocoin = $this->Cryptocoin->find()->select('id')->where(['short_name'=>'TP3'])->first();
			$coinId = $Cryptocoin->id;
            $mainBalance = $this->Users->getUserPricipalBalance($userId, $coinId);
            $this->set('mainBalance', $mainBalance);
            $depositHistoryList = $this->DepositApplicationList->find('all',['conditions'=>['user_id'=>$userId],'order'=>['id'=>'desc']])->hydrate(false)->toArray();
            if(empty($depositHistoryList)){

                //$this->Flash->error(__('Unable to load.'));
            } else{

                //$this->Flash->success(__('loaded successfully'));
            }
            echo json_encode($depositHistoryList); die;
        }
    }

	public function history(){
		$this->loadModel('DepositApplicationWallet');
		$userId = $this->Auth->user('id');
		$my_profits = $this->DepositApplicationWallet->find()->where(['user_id'=>$userId])->first();

		$this->set('my_profits',$my_profits);
        $this->set('kind','investment');
        $this->set('title', 'Deposit History');
    }

    public function history2(){
        $this->loadModel('DepositApplicationWallet');
        $userId = $this->Auth->user('id');
        $my_profits = $this->DepositApplicationWallet->find()->where(['user_id'=>$userId])->first();

        $this->set('my_profits',$my_profits);
        $this->set('kind','investment');
        $this->set('title', 'Deposit History');
    }

	public function googleAuth(){
		if($this->request->is('ajax')) {
			$this->loadModel('Users');
			$userId = $this->Auth->user('id');
			$user = $this->Users->get($userId);

			//QR code and Secret Key
			if (empty($user->g_secret)) {
				$getSecret = $this->Users->createSecret();
				$user->g_secret = $getSecret;
				$this->Users->save($user);
			}

			$this->set('user', $user);
			$secret = $user->g_secret;
			$googleAuthUrl = $this->Users->getQRCodeGoogleUrl('CoinIBT', $secret);

			$returnArr = ['secret' => $secret, 'googleAuthUrl' => $googleAuthUrl];

			echo json_encode($returnArr);
			die;
		}
	}
	public function otpAuthOk(){
		if($this->request->is('ajax')) {
			$this->loadModel('Users');
			$userId = $this->Auth->user('id');
			$user = $this->Users->get($userId);
			$secret = $user->g_secret;


            $users = $this->Users->patchEntity($user, ['g_verify' => "Y"]);
            $user = $this->Users->save($users);
            $status = "true";
            $message = 'Authentication code verified';
            //if($user['g_verify'] == 'Y' && $user['email_auth']=='Y' && $user['bank_verify'] == 'Y'){
            if($user['email_auth']=='Y' && $user['bank_verify'] == 'Y'){
                $users = $this->Users->patchEntity($user, [ 'user_level' => 2 ]);
                $user = $this->Users->save($users);
            }

            $returnArr = ['status' => $status, 'message' => $message];
            echo json_encode($returnArr);
            die;
            //아래는 무시처리
			if (!empty($this->request->data['authcode'])) {
                $getInputCode = strip_tags($this->request->data['authcode']);
                $checkResult = $this->Users->verifyCode($secret, $getInputCode, 2);    // 2 = 2*30sec clock tolerance

                if ($checkResult) {
                    $users = $this->Users->patchEntity($user, ['g_verify' => "Y"]);
                    $user = $this->Users->save($users);
					$status = "true";
					$message = 'Authentication code verified';
                    if($user['g_verify'] == 'Y' && $user['email_auth']=='Y' && $user['bank_verify'] == 'Y'){
                        $users = $this->Users->patchEntity($user, [ 'user_level' => 2 ]);
                        $user = $this->Users->save($users);
                    }
                } else {
                    //기존 실패 코드 변경
					/*$status = "false";
					$message = 'invalidCode';*/
                }
            } else {
                //기존 실패 코드 변경
				/*$status = "false";
				$message = 'enterCode';*/
			}
			$returnArr = ['status' => $status, 'message' => $message];
			echo json_encode($returnArr);
            die;
		}
	}

	public function withdraw(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationWallet');
			$this->loadModel('Cryptocoin');
			$this->loadModel('PrincipalWallet');
			$this->loadModel('DepositApplicationLog');
			$this->loadModel('DepositApplicationList');
			$user_id = $this->Auth->user('id');
			$user = $this->Users->get($user_id);

			$wallet = $this->DepositApplicationWallet->find()->select(['id','unit','user_id','amount'])->where(['user_id'=>$user_id])->first();
			if($wallet->amount < 10000){
				$status = "fail";
				$message = '10,000 KRW 이상부터 출금이 가능합니다.';
			} else {
				$cryptocoin_id = $this->Cryptocoin->find()->select(['id'])->where(['short_name'=>$wallet->unit])->first();
				$calc = $this->listwithdraw($user_id,$wallet->amount);
				if($calc == 'success'){
					$this->insertprincipalwallet($user_id,$cryptocoin_id->id,$wallet->amount,$user['eth_address']);
					$this->updatewallet($wallet->id);
					$status = "success";
					$message = '인출성공.';
				} else {
					$status = "fail";
					$message = '인출실패.';
				}
			}
			$returnArr = ['status' => $status, 'message' => $message];
			echo json_encode($returnArr);
            die;
		}
		die;
	}

	public function listwithdraw($user_id,$wallet_amount){
		$this->loadModel('DepositApplicationList');
		$invest_list = $this->DepositApplicationList->find()->select(['id','amount_received','total_withdrawal_amount','investment_number'])->where(['user_id'=>$user_id,'OR'=>[['status'=>'A'],['status'=>'E']]])->all();

		$query = $this->DepositApplicationList->find();
		$sum = $query->select(['sum' => $query->func()->sum('amount_received')])->where(['user_id'=>$user_id,'OR'=>[['status'=>'A'],['status'=>'E']]])->first();

		if((float)$wallet_amount == (float)$sum->sum){
			foreach($invest_list as $l){
				$this->listamountupdate($l->id,$l->amount_received,$l->total_withdrawal_amount);
				$this->addlog($l->id,$user_id,$l->amount_received,'W',$l->investment_number);
			}
			return "success";
		}
		return "fail";

	}

	public function listamountupdate($id,$amount_received,$total_withdrawal_amount){
		$this->loadModel('DepositApplicationList');
		$total = $amount_received + $total_withdrawal_amount;
		$query = $this->DepositApplicationList->query();
		$query->update()->set(['amount_received'=>0,'total_withdrawal_amount'=>$total])->where(['id' => $id])->execute();
		return "success";
	}

	public function addlog($list_id,$user_id,$amount,$type,$investment_number){
		// only insert
		$this->loadModel('DepositApplicationLog');
		$query = $this->DepositApplicationLog->query();
		$query->insert(['list_id','user_id','amount','created','type','investment_number'])
			->values(['list_id'=>$list_id,'user_id'=>$user_id,'amount'=>$amount,'type'=>$type,'created'=>date('Y-m-d H:i:s'),'investment_number'=>$investment_number])->execute();
		return "success";
	}

	public function insertprincipalwallet($userId,$cryptocoin_id,$amount,$eth_address){
		$this->loadModel('PrincipalWallet');
		$query = $this->PrincipalWallet->query();
		$query->insert(['user_id', 'coupon_user_id' ,'cryptocoin_id','coupon_cryptocoin_id','amount','coin_amount','wallet_address','type','fees','remark','status','created_at'])
			->values([
				'user_id' => $userId,
				'coupon_user_id' => 0,
				'cryptocoin_id' => $cryptocoin_id,
				'coupon_cryptocoin_id' => 0,
				'amount' => $amount,
				'coin_amount' => '',
				'wallet_address' => $eth_address,
				'type' => 'investment_profits',
				'fees' => 0,
				'remark' => 'investment_profits_krw',
				'status' => 'completed',
				'created_at' => date('Y-m-d H:i:s')
			])->execute();
		return "success";
	}

	public function updatewallet($id){
		$this->loadModel('DepositApplicationWallet');
		$query = $this->DepositApplicationWallet->query();
		$query->update()->set(['amount'=>0,'updated'=>date('Y-m-d H:i:s')])->where(['id' => $id])->execute();
		return "success";
	}

	public function mywalletlog(){
		$this->loadModel('DepositApplicationLog');
		$this->loadModel('DepositApplicationList');
		$limit =  $this->setting['pagination'];
		$userId = $this->Auth->user('id');

		$settings = array(
           'limit' => 10
        );

		if ($this->request->is(['post' ,'put']) ) {
            if (!empty($search['pagination'])) $limit = $search['pagination'];
        }
		if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        } else{
			$this->set('serial_num',1);
		}

		$query = $this->DepositApplicationLog->find()->select(['list_id', 'user_id', 'amount','type','created','alist.id','alist.user_id','alist.unit','alist.quantity', 'alist.service_period_month', 'alist.created']);
		$query = $query->join(['alist' => ['table' => 'deposit_application_list','type' => 'inner','conditions' => 'alist.id = DepositApplicationLog.list_id']]);
		$query = $query->where(['DepositApplicationLog.user_id'=>$userId]);
		$query = $query->order(['DepositApplicationLog.id'=> 'desc']);
		$query = $query->limit($limit);
		$log_list =  $this->Paginator->paginate($query,$settings);

		$this->set('log_list',$log_list);
	}


    //스테이킹 이자 계산

    public function devcale(){
        $this->loadModel('DepositApplicationWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('DepositApplicationLog');
        $this->loadModel('DepositApplicationList');
        $this->loadModel('DepositApplicationSetting');

        //처음 신청자가 몇명인지 조회를 시작한다


        //진행중인 기수인지 먼저 체크한다
        //$number_list = $this->


        //생각해보니 기수존재도 필요하지않다.
        //단 이제부터 신청하는 사람들은 따로 체크 리스트를 뿌려야할듯

        $list_query = $this->DepositApplicationList->find('all')->where(['investment_number'=>'111'])->all();

        //계산 함수를 하나 생성
        $today = date('Y-m-d');

        //print_r($list_query);
        /*
         * 1238 , 1237 만 승인된 상태
         * 나머진 미승인 맞음
         */
        $resp_arr = [];
        foreach($list_query->toArray() as $k=>$data){
            $resp_arr[$k] = $data;
            //echo $data['id'];
            if($data['status'] == "A" ){
                //echo $data['id']." ----> 승인";
                //횟수를 체크한다
                //승인된 회원중 또 한번 체크를 한다 오늘 지급했는지? 안했는지?
                $paycheck = $this->paylog($data['user_id'],$data['id']);
                $payback = $this->paycale($data['quantity'],$data['service_period_month']);
                //echo $paycheck;
                if($paycheck == "F"){
                //지급이 안된걸 확인했으니 금액을 지급해보자
                //echo $payback."원 이자입니다";

                //이자 확인 이걸 insert 시켜주자
                $insert_query = $this->DepositApplicationLog->query();
                $insert_query->insert(['list_id','user_id','amount','created','type','investment_number'])
                    ->values(['list_id'=>$data['id'],'user_id'=>$data['user_id'],'amount'=>$payback,'type'=>'S','created'=>date('Y-m-d H:i:s'),'investment_number'=>'2022'])->execute();

                //이자가 들어간 로그도 확인시켜준다
                //회원 데이터 업데이트를 해준다 해줄 내역 이자 받은 횟수 2가지 추가
                $amount_received = $data['amount_received'] + $payback;
                $number_of_received = $data['number_of_received'] + 1;
                $update_query = $this->DepositApplicationList->query();
                $update_query->update()->set(['amount_received'=>$amount_received,'number_of_received'=>$number_of_received])->where(['id'=>$data['id']])->execute();


                }else if($paycheck == 'T'){
                    echo "이미 지금되었습니다.";
                    //실패사유
                }
                echo"<br>";
            }else{
                echo $data['id']." ----> 미 승인";
                echo "<br>";
            }
        }
        echo "<br><br><br><br><br><br><br>";

        exit;

    }

    //추후 수수료 및 이자율 계산식 적용 예정
    public function paycale($pay,$day){
        //공식 이게 맞나? 금액(신청금액)/날짜(신청날짜)
        $payback = $pay/$day;

        return $payback;

    }
    //지급체크
    public function paylog($userid,$list_id){
        $this->loadModel('DepositApplicationLog');

        $today = date('Y-m-d');
        
        $query = $this->DepositApplicationLog->find()->select()->where(['list_id'=>$list_id,'user_id'=>$userid,'investment_number'=>'2022','created >='=>$today])->count();

        if($query > 0){
            $paycheck = 'T';
        }else{
            $paycheck = 'F';
        }


        return $paycheck;


    }

    //신청 날짜와 승인날짜 종료일 계산식식

    //이자지급 리스트
    public function loglist(){
        $this->loadModel('DepositApplicationLog');
        //회원정보 ID값을 가져와서 여태 스테이킹 매일 지급 되는 이자를 체크한다

        $userId = $this->Auth->user('id');

        $query = $this->DepositApplicationLog->find()->select()->where(['user_id'=>$userId,'investment_number'=>'2022'])->all();

        print_r($query);

        exit;

    }

}