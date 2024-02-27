<?php
namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Mailer\Email;

use Cake\Event\Event;

	class PagesController extends AppController
	{
		public function initialize()
		{
			parent::initialize();
			$this->loadComponent('Csrf');
		}
		public function index()
		{
			$this->dashboard();	
		}
		public function forbidden(){
			if($this->request->referer()!='/') $this->request->session()->write('Config.referer', $this->request->referer());
			$this->set('title' , 'GalaxyIco!: Access forbidden');
			
		}
		
		
		public function dashboard(){ 
			$this->loadModel('Coinpair');
			$getAllPair = $this->Coinpair->find('all',['conditions'=>['Coinpair.id'=>4],
													  'contain'=>['cryptocoin_first','cryptocoin_second']
													  ])->hydrate(false)->first();
			$this->set('getAllPair',$getAllPair);
			return $this->redirect(['controller'=>'exchange','action'=>'index',$getAllPair['cryptocoin_first']['short_name'],$getAllPair['cryptocoin_second']['short_name']]);
		}
		
		public function add()
		{
			$this->set('title' , 'GalaxyIco!: Add Cms Page');
			$Pages = $this->Pages->newEntity();
			
			if ($this->request->is(['post' ,'put'])) {
				
				$CardTypes = $this->Pages->patchEntity($Pages, $this->request->data);
				
				if ($this->Pages->save($Pages)) {
					$this->Flash->success(__('CMS page has been saved.'));
					return $this->redirect(['controller'=>'Pages','action' => 'add']);
				}else{
					$this->Flash->error(__('Some Errors Occurred.'));
				}
			}
			$this->set('Pages', $Pages);
		}
		
		public function faq(){
			
			$this->set('title' , 'GalaxyIco!: FAQ');
			$this->set('cmsDetails',$this->Pages->get(1));
			
			if ($this->request->is(['post' ,'put'])) {
				$Pages  = $this->Pages->get(1);
				$Pages = $this->Pages->patchEntity($Pages, $this->request->data);
				
				if ($this->Pages->save($Pages)) {
					$this->Flash->success(__('FAQ has been saved.'));
					return $this->redirect(['controller'=>'Pages','action' => 'faq']);
				}else{
					$this->Flash->error(__('Some Errors Occurred.'));
				}
			}
		
		}
		public function manage(){
			/* pr($this->Pages->get(1)->toArray());
			echo json_encode($this->Pages->get(1)->toArray(), JSON_HEX_QUOT | JSON_HEX_TAG);	die; */		
			$this->set('title' , 'GalaxyIco!: Cms Pages');
			$this->set('Pages', $this->Pages->newEntity($this->request->data));
			$this->set('cmsPages',$this->Pages->find('list',array('keyField'=>'id' , 'valueField'=> 'name'))->toArray());
			
			if ($this->request->is(['post' ,'put'])) {
				unset($this->request->data['name']);
				$Pages  = $this->Pages->get($this->request->data['id']);
				$Pages = $this->Pages->patchEntity($Pages, $this->request->data);
				
				if ($this->Pages->save($Pages)) {
					$this->Flash->success(__('CMS page has been saved.'));
					return $this->redirect(['controller'=>'Pages','action' => 'manage']);
				}else{
					$this->Flash->error(__('Some Errors Occurred.'));
				}
			}
		
		}
		
		public function search(){
			if ($this->request->is('ajax')) {
					if(isset($this->request->data['cms_id'])){
						
						 $this->set('cmsDetails',$this->Pages->get($this->request->data['cms_id']));
					}
			}
		}
		
		
		public function chart()
		{
			
			if ($this->request->is('ajax')) {
					if(isset($this->request->data['mode'])){
						
						$to_date = date('Y-m-d', strtotime($this->request->data['to']));
						$from_date = date('Y-m-d', strtotime($this->request->data['from']));
						$this->loadModel('Users');
						$query = $this->Users->find();
						$charts = $ven = $usr = $tv =   array();
						$users = 	$query->select([
								'count' => $query->func()->count('id'),
								'published_date' => 'DATE(created)'
							])
							->where(['access_level_id' => 2,'is_deleted' => 'N','DATE(created) >=' => $to_date,'DATE(created) <=' =>  $from_date,/* function ($exp, $q) {
								
									return $exp->between('created', date('Y-m-d H:i:s', strtotime($this->request->data['to'])), date('Y-m-d H:i:s', strtotime($this->request->data['from'])));
								} */])
							->group('published_date')->hydrate(false)->toArray();
							//pr($users);die;
						if($users){
								foreach($users as $value){
										$a = array();
										$a[0]  = strtotime($value['published_date'])*1000;
										$a[1]  = $value['count'];
										
										$usr[] = $a;
								}
						} 
						
						$charts['Users'] = $usr;
						
						
						echo json_encode($charts); die;
					}
			}
		}
	
	
	public function tradeRates(){
		$this->loadModel('ExchangeHistory');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		
		$returnArr = [];
		$getExchangHistoryData = $this->ExchangeHistory->find('all',array('conditions'=>array('ExchangeHistory.status in'=>['processing','completed']),
																		  'contain'=>['sell_exchange','buy_exchange']))
														->order(['ExchangeHistory.id'=>'desc'])
														->limit(2)
														->hydrate(false)
														->all()->toArray();

		$buy_diff = $getExchangHistoryData[0]['buy_exchange']['price_per_hc']-$getExchangHistoryData[1]['buy_exchange']['price_per_hc'];
		$sell_diff = $getExchangHistoryData[0]['sell_exchange']['price_per_hc']-$getExchangHistoryData[1]['sell_exchange']['price_per_hc'];	
		
		$buyColor = ($buy_diff>=0) ? "green" : "red" ;
		$sellColor = ($sell_diff>=0) ? "green" : "red" ;
		
		$returnArr['buy_exchange'] = array('price'=>$getExchangHistoryData[0]['buy_exchange']['price_per_hc'],'color'=>$buyColor);
		$returnArr['sell_exchange'] = array('price'=>$getExchangHistoryData[0]['sell_exchange']['price_per_hc'],'color'=>$sellColor);
		
		echo json_encode($returnArr); die;
		  
		
	}
	
	
	public function mywallet(){
		
		$this->loadModel('Transactions');
		$this->loadModel('Cryptocoin');
		$authUserId = $this->Auth->user('id');
		$intrAddress  = $this->Auth->user('intr_address');
		$this->set('intrAddress',$intrAddress);
		
		$this->set('currentUserId',$authUserId);
        $userDetail = $this->Users->find('all',['conditions'=>['id'=>$authUserId]])->hydrate(false)->first();
        $annualMember = $userDetail['annual_membership'];
        $this->set('annualMember',$annualMember);
		$getUserTotalCoin = $this->Transactions->find(); 
		$getUserTotalCoinCnt = $getUserTotalCoin
									->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id','cryptocoin.short_name','cryptocoin.icon'])
									->contain('cryptocoin')
									->where(['Transactions.user_id'=>$authUserId,'Transactions.status'=>'completed','Transactions.tx_type !='=>'bank_initial_deposit'])
									->group('cryptocoin_id')
									->toArray();

		
		$this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);

        //추가 메인과 트레이드 가 있고 트레이드에서 보관함으로 이동하게 끔  추가
        



		
		$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();
		$this->set('getCoinList',$getCoinList);
		
		
	}

        public function mywallet2(){

            $this->loadModel('Transactions');
            $this->loadModel('Cryptocoin');
            $authUserId = $this->Auth->user('id');
            $intrAddress  = $this->Auth->user('intr_address');
            $this->set('intrAddress',$intrAddress);

            $this->set('currentUserId',$authUserId);
            $userDetail = $this->Users->find('all',['conditions'=>['id'=>$authUserId]])->hydrate(false)->first();
            $annualMember = $userDetail['annual_membership'];
            $this->set('annualMember',$annualMember);
            $getUserTotalCoin = $this->Transactions->find();
            $getUserTotalCoinCnt = $getUserTotalCoin
                ->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id','cryptocoin.short_name','cryptocoin.icon'])
                ->contain('cryptocoin')
                ->where(['Transactions.user_id'=>$authUserId,'Transactions.status'=>'completed','Transactions.tx_type !='=>'bank_initial_deposit'])
                ->group('cryptocoin_id')
                ->toArray();
            /*print_r($_SESSION);
            exit;*/
            $this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);


            $getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();
            $this->set('getCoinList',$getCoinList);

        }
	public function withdrawal($coin=null){
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		
		$authUserId = $this->Auth->user('id');
		$currentUserEmail = $this->Auth->user('email');
		$cudate = date('Y-m-d H:i:s');
		
		if($coin==null){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$user  = $this->Users->get($authUserId);
		$googleVerify = $user->g_verify;
		if($googleVerify=='N'){
			$this->Flash->error(__('Please verify google authenticator'));
			return $this->redirect(['controller'=>'pages','action'=>'mywallet']);
		}
		$secret = $user->g_secret;
		$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
		if(empty($findCoinDetail)){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$coinType = $findCoinDetail['type'];
		$this->set('coinDetail',$findCoinDetail);
		$coindId = $findCoinDetail['id'];
		$this->set('coindId',$coindId);
		$this->set('coinType',$coinType);
		
		$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
		if(empty($userBalance)) {
			$userBalance = 0;
		}
		$this->set('userbalance',$userBalance);
		
		$transFeesArr = [1=>0.005,2=>0.02,3=>0.1,4=>0.1]; 
		//$transFee = $transFeesArr[$coindId];
		$transFee = 0.005;
		$this->set('transFee',$transFee);
		
		$secondCoinId = 3;
		$firstCoinId = 2;
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		$currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$coindId],
																					  ['spend_cryptocoin_id'=>$coindId]
																					  ]
																				],	
																	'limit' => 1,			 
																	'order' => ['id'=>'desc']
																	])	
																  ->hydrate(false)
																  ->first();
		
		
		$currentPrice = !empty($currentPrice['get_per_price']) ? $currentPrice['get_per_price']*$baseCoinPriceInUsd : 1;
		$this->set('currentPrice',$currentPrice);
		
		if ($this->request->is(['post','put'])) {
			
				/* 
				$getInputCode = $this->request->data['email_code'];
				
				if(empty($getInputCode)){
					$this->Flash->error('Please enter security code.');
					return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
				} */
				$getInputCode = strip_tags($this->request->data['email_code']);
				
				if(empty($getInputCode)){
					$this->Flash->error(__('Please enter authentication code.'));
					return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
				}
				$checkResult = $this->Users->verifyCode($secret, $getInputCode, 2);    // 2 = 2*30sec clock tolerance
				if (!$checkResult) {
					$this->Flash->error(__('You entered invalid authentication code.'));
					return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
				} 
				
				
				/* $getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $getInputCode){
					$this->Flash->error('Please enter Valid code.');
					return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
				}
				 */
				$withdrawalAmount = strip_tags($this->request->data['quantity']);
				
				if($withdrawalAmount<=0.02){
					$this->Flash->error(__('Amount should be greater than 0.02.'));
					return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
				}	
				 
				
				if($coinType=="flat"){
					
					$flatAccountNo = strip_tags($this->request->data['flat_account_no']);
					$flatBankName = strip_tags($this->request->data['flat_bank_name']);
					$flatAccountOwner = strip_tags($this->request->data['flat_account_owner']);
					$flatBankAddress = strip_tags($this->request->data['flat_bank_address']);
					
					if(empty($flatAccountNo) || empty($flatBankName) || empty($flatAccountOwner) || empty($flatBankAddress)){
						$this->Flash->error(__('All fields are required.'));
						return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
					}
					
					$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);	
					if($withdrawalAmount>$userBalance){
						$this->Flash->error(__('Insufficient balance in wallet.'));
						return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
					}
					
					$tx_id =  $this->Users->getUniqueId($authUserId);
					$realWithdrawalAmount = $withdrawalAmount - $transFee;
					
					$toWalletAddress = "";
					
					if(!empty($tx_id)){
						// save withdrawal amount in transaction table
						$newInsertArr = [];
						$withdrawalTxId = time().uniqid(mt_rand(),true);
						$newInsertArr['user_id'] = $authUserId;
						$newInsertArr['wallet_address'] = $toWalletAddress;
						$newInsertArr['withdrawal_tx_id'] = $tx_id;
						$newInsertArr['withdrawal_coin_price'] = $currentPrice;
						$newInsertArr['withdrawal_amount_in_usd'] = $realWithdrawalAmount*$currentPrice;
						$newInsertArr['withdrawal_send'] = 'N';
						$newInsertArr['cryptocoin_id'] = $coindId;
						$newInsertArr['coin_amount'] = "-".$withdrawalAmount;
						$newInsertArr['tx_type'] = 'withdrawal';
						$newInsertArr['remark'] = 'withdrawal';
						$newInsertArr['flat_account_no'] = $flatAccountNo;
						$newInsertArr['flat_bank_name'] = $flatBankName;
						$newInsertArr['flat_account_owner'] = $flatAccountOwner;
						$newInsertArr['flat_bank_address'] = $flatBankAddress;
						$newInsertArr['status'] = 'completed';
						$newInsertArr['created'] = $cudate;
						$newInsertArr['updated'] = $cudate;
						
						// insert data
						$insertIntoTransactions=$this->Transactions->newEntity();
						$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
						$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
						$transactionId = $insertIntoTransactions->id;
						if($insertIntoTransactions){
							
							// send email to admin
							
							$data['amount'] =  $realWithdrawalAmount;
							$data['wallet_address'] =  $toWalletAddress;
							$data['user_email'] = $currentUserEmail;
							
							
							$email = new Email('default');
							$email->viewVars(['data'=>$data]);
							$email->from([$this->setting['email_from']] )
								->to("info@intaro.com")
								->subject('Withdrawal Request at winnerbank')
								->emailFormat('html')
								->template('withdrawal')
								->send();
							
							
							
							// save withdrawal Fee amount in transaction table
							$newInsertArr = [];
							
							$newInsertArr['user_id'] = 1;
							$newInsertArr['transaction_id'] = $transactionId;
							$newInsertArr['cryptocoin_id'] = $coindId;
							$newInsertArr['coin_amount'] = $transFee;
							$newInsertArr['tx_type'] = 'withdrawal_fee';
							$newInsertArr['remark'] = 'withdrawal_fee';
							$newInsertArr['status'] = 'completed';
							$newInsertArr['created'] = $cudate;
							$newInsertArr['updated'] = $cudate;
							
							// insert data
							$insertWithdrawalFee=$this->Transactions->newEntity();
							$insertWithdrawalFee=$this->Transactions->patchEntity($insertWithdrawalFee,$newInsertArr);
							$insertWithdrawalFee=$this->Transactions->save($insertWithdrawalFee);
							
							$this->Flash->success($coin.__(' withdrawal request generated.'));
							return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
						}
						else {
							$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
							return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
						}
					}
					else {
							$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
							return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
					}
					
					
					
				}
				else {
					$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
					if(empty($getWalletAddress) || empty($withdrawalAmount)){
						$this->Flash->error(__('All fields are required.'));
						return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
					}
					
						
					$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);	
					if($withdrawalAmount>$userBalance){
						$this->Flash->error(__('Insufficient balance in wallet.'));
						return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
					}
					
					if($coindId==2) {
						$checkValidate = $this->Users->ntrCheckAddressValid($getWalletAddress);
						if($checkValidate==0){
							$this->Flash->error(__('Invalid wallet address.'));
							return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
						}
					}
					
					$tx_id =  $this->Users->getUniqueId($authUserId);
					$realWithdrawalAmount = $withdrawalAmount - $transFee;
					
					
					$exchangeWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
					
					/* if($exchangeWalletAddress != $getWalletAddress){
						$fromWalletAddress = "0xd7276a4eb1792836777d882c7bec1581d43496c5";
						$password = "vip#f3sd3AJMBf0a6@4344";
						$toWalletAddress = $getWalletAddress;
						$transferType = ($coindId==2) ? "eth_transfer" : "coin_transfer";
						$tx_id = $this->Users->transferCoinToAddress($password,$fromWalletAddress,$toWalletAddress,$realWithdrawalAmount,$transferType);
					}
					else {
						$toWalletAddress = $exchangeWalletAddress;
					} */
					
					$toWalletAddress = $getWalletAddress;
					
					if(!empty($tx_id)){
						// save withdrawal amount in transaction table
						$newInsertArr = [];
						$withdrawalTxId = time().uniqid(mt_rand(),true);
						$newInsertArr['user_id'] = $authUserId;
						$newInsertArr['wallet_address'] = $toWalletAddress;
						$newInsertArr['withdrawal_tx_id'] = $tx_id;
						$newInsertArr['withdrawal_coin_price'] = $currentPrice;
						$newInsertArr['withdrawal_amount_in_usd'] = $realWithdrawalAmount*$currentPrice;
						$newInsertArr['withdrawal_send'] = 'N';
						$newInsertArr['cryptocoin_id'] = $coindId;
						$newInsertArr['coin_amount'] = "-".$withdrawalAmount;
						$newInsertArr['tx_type'] = 'withdrawal';
						$newInsertArr['remark'] = 'withdrawal';
						$newInsertArr['status'] = 'completed';
						$newInsertArr['fees'] = $transFee;
						$newInsertArr['created'] = $cudate;
						$newInsertArr['updated'] = $cudate;
						
						// insert data
						$insertIntoTransactions=$this->Transactions->newEntity();
						$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
						$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
						$transactionId = $insertIntoTransactions->id;
						if($insertIntoTransactions){
							
							// send email to admin
							
							$data['amount'] =  $realWithdrawalAmount;
							$data['wallet_address'] =  $toWalletAddress;
							$data['user_email'] = $currentUserEmail;
							
							
							$email = new Email('default');
							$email->viewVars(['data'=>$data]);
							$email->from([$this->setting['email_from']] )
								->to("info@intaro.com")
								->subject('Withdrawal Request at winnerbank')
								->emailFormat('html')
								->template('withdrawal')
								->send();
							
							
							
							// save withdrawal Fee amount in transaction table
							$newInsertArr = [];
							
							$newInsertArr['user_id'] = 1;
							$newInsertArr['transaction_id'] = $transactionId;
							$newInsertArr['cryptocoin_id'] = $coindId;
							$newInsertArr['coin_amount'] = $transFee;
							$newInsertArr['tx_type'] = 'withdrawal_fee';
							$newInsertArr['remark'] = 'withdrawal_fee';
							$newInsertArr['status'] = 'completed';
                            $newInsertArr['fees'] = $transFee;
							$newInsertArr['created'] = $cudate;
							$newInsertArr['updated'] = $cudate;
							
							// insert data
							$insertWithdrawalFee=$this->Transactions->newEntity();
							$insertWithdrawalFee=$this->Transactions->patchEntity($insertWithdrawalFee,$newInsertArr);
							$insertWithdrawalFee=$this->Transactions->save($insertWithdrawalFee);
							
							$this->Flash->success($coin.__(' withdrawal request generated.'));
							return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
						}
						else {
							$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
							return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
						}
					}
					else {
							$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
							return $this->redirect(['controller'=>'pages','action'=>'withdrawal',$coin]);
					}
				
				}
		}
		
		
	}
	
	
	public function ramethwithdrawal(){
		$coin = "ETH";
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		
		$authUserId = $this->Auth->user('id');
		if($authUserId!=10003090){
			//$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('authUserId',$authUserId);
		$cudate = date('Y-m-d H:i:s');
		
		if($coin==null){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
		if(empty($findCoinDetail)){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('coinDetail',$findCoinDetail);
		$coindId = $findCoinDetail['id'];
		$this->set('coindId',$coindId);
		
		$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
		if(empty($userBalance)) {
			$userBalance = 0;
		}
		$this->set('userbalance',$userBalance);
		
		$transFeesArr = [1=>0.005,2=>0.02,3=>0.1,4=>0.1]; 
		$transFee = $transFeesArr[$coindId];
		$this->set('transFee',$transFee);
		
		$secondCoinId = 3;
		$firstCoinId = 2;
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);

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
		
		
		$currentPrice = !empty($currentPrice['get_per_price']) ? $currentPrice['get_per_price']*$baseCoinPriceInUsd : 1;
		
		$getCoinData = $this->Cryptocoin->get(2);
		$currentPrice = $getCoinData->usd_price;
		$this->set('currentPrice',$currentPrice);
		//$this->set('currentPrice',$currentPrice);
		
		if ($this->request->is(['post','put'])) {
			
				
				$getInputCode = $this->request->data['email_code'];
				//$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
				if(empty($getInputCode)){
					$this->Flash->error(__('Please enter security code.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramethwithdrawal']);
				}
				$getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $getInputCode){
					$this->Flash->error(__('Please enter valid code.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramethwithdrawal']);
				}
				
				
				
				$amountInUsd = strip_tags($this->request->data['amount_in_usd']);
				
				if(empty($amountInUsd)){
						$this->Flash->error(__('All fields are required.'));
						return $this->redirect(['controller'=>'pages','action'=>'ramethwithdrawal']);
				}
				
				//$ramUsdArr = [30,100,200];
				$ramUsdArr = [100,200];
				if(!in_array($amountInUsd,$ramUsdArr)){
					$this->Flash->error(__('Amount should be 100 or 200.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramethwithdrawal']);
				}
				
				$withdrawalAmount = $amountInUsd/$currentPrice;
				
				if($withdrawalAmount>$userBalance){
					$this->Flash->error(__('Insufficient balance in wallet.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramethwithdrawal']);
				}
				
				
				
				$tx_id =  $this->Users->getUniqueId($authUserId);
				$realWithdrawalAmount = $withdrawalAmount;
				
				
				$exchangeWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				$getWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				if($exchangeWalletAddress != $getWalletAddress){
					$fromWalletAddress = "0xd7276a4eb1792836777d882c7bec1581d43496c5";
					$password = "vip#f3sd3AJMBf0a6@4344";
					$toWalletAddress = $getWalletAddress;
					$transferType =  "coin_transfer";
					$tx_id = $this->Users->transferCoinToAddress($password,$fromWalletAddress,$toWalletAddress,$realWithdrawalAmount,$transferType);
				}
				else {
					$toWalletAddress = $exchangeWalletAddress;
				}
				
				
				
				if(!empty($tx_id)){
					// save withdrawal amount in transaction table
					$newInsertArr = [];
					$withdrawalTxId = time().uniqid(mt_rand(),true);
					$newInsertArr['user_id'] = $authUserId;
					//$newInsertArr['wallet_address'] = $toWalletAddress;
					$newInsertArr['withdrawal_amount_in_usd'] = $amountInUsd;
					$newInsertArr['withdrawal_tx_id'] = $tx_id;
					$newInsertArr['withdrawal_coin_price'] = $currentPrice;
					$newInsertArr['withdrawal_send'] = 'N';
					$newInsertArr['cryptocoin_id'] = $coindId;
					$newInsertArr['coin_amount'] = "-".$realWithdrawalAmount;
					$newInsertArr['tx_type'] = 'withdrawal';
					$newInsertArr['remark'] = 'withdrawal';
					$newInsertArr['status'] = 'completed';
                    $newInsertArr['fees'] = $transFee;
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$insertIntoTransactions=$this->Transactions->newEntity();
					$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
					$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
					$transactionId = $insertIntoTransactions->id;
					if($insertIntoTransactions){
						
						$this->Flash->success($coin.__(' withdrawal successfully. Transactions ID: ').$tx_id);
						return $this->redirect(['controller'=>'pages','action'=>'ramethwithdrawal']);
					}
					else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'ramethwithdrawal']);
					}
				}
				else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'ramethwithdrawal']);
				}
		}
		
		
	}
	
	
	
	public function ramwithdrawal($coin=null){
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		
		$authUserId = $this->Auth->user('id');
		if($authUserId!=10003090){
			//$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('authUserId',$authUserId);
		$cudate = date('Y-m-d H:i:s');
		
		if($coin==null){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
		if(empty($findCoinDetail)){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('coinDetail',$findCoinDetail);
		$coindId = $findCoinDetail['id'];
		$this->set('coindId',$coindId);
		
		$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
		if(empty($userBalance)) {
			$userBalance = 0;
		}
		$this->set('userbalance',$userBalance);
		
		$transFeesArr = [1=>0.005,2=>0.02,3=>0.1,4=>0.1]; 
		$transFee = $transFeesArr[$coindId];
		$this->set('transFee',$transFee);
		
		$secondCoinId = 3;
		$firstCoinId = 2;
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		/* $currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$coindId],
																					  ['spend_cryptocoin_id'=>$coindId]
																					  ]
																				],	
																	'limit' => 1,			 
																	'order' => ['id'=>'desc']
																	])	
																  ->hydrate(false)
															  ->first(); */
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
		
		
		$currentPrice = !empty($currentPrice['get_per_price']) ? $currentPrice['get_per_price']*$baseCoinPriceInUsd : 1;
		$this->set('currentPrice',$currentPrice);
		
		if ($this->request->is(['post','put'])) {
			
				
				$getInputCode = $this->request->data['email_code'];
				//$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
				if(empty($getInputCode)){
					$this->Flash->error(__('Please enter security code.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
				$getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $getInputCode){
					$this->Flash->error(__('Please enter valid code.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
				
				
				
				$amountInUsd = strip_tags($this->request->data['amount_in_usd']);
				
				if(empty($amountInUsd)){
						$this->Flash->error(__('All fields are required.'));
						return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
				
				//$ramUsdArr = [30,100,200];
				//$ramUsdArr = [100,200];
				$ramUsdArr = [30];
				if(!in_array($amountInUsd,$ramUsdArr)){
					$this->Flash->error(__('Amount should be 30 Only.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
				
				$withdrawalAmount = $amountInUsd/$currentPrice;
				
				if($withdrawalAmount>$userBalance){
					$this->Flash->error(__('Insufficient balance in wallet.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
				
				
				
				$tx_id =  $this->Users->getUniqueId($authUserId);
				$realWithdrawalAmount = $withdrawalAmount;
				
				
				$exchangeWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				$getWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				if($exchangeWalletAddress != $getWalletAddress){
					$fromWalletAddress = "0xd7276a4eb1792836777d882c7bec1581d43496c5";
					$password = "vip#f3sd3AJMBf0a6@4344";
					$toWalletAddress = $getWalletAddress;
					$transferType =  "coin_transfer";
					$tx_id = $this->Users->transferCoinToAddress($password,$fromWalletAddress,$toWalletAddress,$realWithdrawalAmount,$transferType);
				}
				else {
					$toWalletAddress = $exchangeWalletAddress;
				}
				
				
				
				if(!empty($tx_id)){
					// save withdrawal amount in transaction table
					$newInsertArr = [];
					$withdrawalTxId = time().uniqid(mt_rand(),true);
					$newInsertArr['user_id'] = $authUserId;
					$newInsertArr['wallet_address'] = $toWalletAddress;
					$newInsertArr['withdrawal_amount_in_usd'] = $amountInUsd;
					$newInsertArr['withdrawal_tx_id'] = $tx_id;
					$newInsertArr['withdrawal_coin_price'] = $currentPrice;
					$newInsertArr['withdrawal_send'] = 'N';
					$newInsertArr['cryptocoin_id'] = $coindId;
					$newInsertArr['coin_amount'] = "-".$realWithdrawalAmount;
					$newInsertArr['tx_type'] = 'withdrawal';
					$newInsertArr['remark'] = 'withdrawal';
					$newInsertArr['status'] = 'completed';
                    $newInsertArr['fees'] = $transFee;
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$insertIntoTransactions=$this->Transactions->newEntity();
					$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
					$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
					$transactionId = $insertIntoTransactions->id;
					if($insertIntoTransactions){
						
						$this->Flash->success($coin.__(' withdrawal successfully. Transactions ID: ').$tx_id);
						return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
					}
					else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
					}
				}
				else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
		}
		
		
	}
	
	public function usdwithdrawal(){
	
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		
		$authUserId = $this->Auth->user('id');
		/* if($authUserId!=10003090){
			$this->redirect(['controller'=>'pages','action'=>'mywallet']);
		} */
		$cudate = date('Y-m-d H:i:s');
		$coin = "USD";
		if($coin==null){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
		if(empty($findCoinDetail)){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('coinDetail',$findCoinDetail);
		$coindId = $findCoinDetail['id'];
		$this->set('coindId',$coindId);
		
		$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
		if(empty($userBalance)) {
			$userBalance = 0;
		}
		$this->set('userbalance',$userBalance);
		
		$transFeesArr = [1=>0.005,2=>0.02,3=>0.1,4=>0.1,5=>0.1]; 
		$transFee = $transFeesArr[$coindId];
		$this->set('transFee',$transFee);
		
		$secondCoinId = 3;
		$firstCoinId = 2;
		
		/*$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		 $currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$coindId],
																					  ['spend_cryptocoin_id'=>$coindId]
																					  ]
																				],	
																	'limit' => 1,			 
																	'order' => ['id'=>'desc']
																	])	
																  ->hydrate(false)
																  ->first();
		
		
		$currentPrice = !empty($currentPrice['get_per_price']) ? $currentPrice['get_per_price']*$baseCoinPriceInUsd : 1; */
		
		$currentPrice = 1 ;
		$this->set('currentPrice',$currentPrice);
		
		if ($this->request->is(['post','put'])) {
			
				
				$getInputCode = $this->request->data['email_code'];
				//$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
				if(empty($getInputCode)){
					$this->Flash->error(__('Please enter security code.'));
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawal']);
				}
				$getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $getInputCode){
					$this->Flash->error(__('Please enter valid code.'));
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawal']);
				}
				
				
				
				$amountInUsd = strip_tags($this->request->data['amount_in_usd']);
				
				if(empty($amountInUsd)){
						$this->Flash->error(__('All fields are required.'));
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawal']);
				}
				
				//$ramUsdArr = [30,100,200];
				//$ramUsdArr = [100,200];
				$ramUsdArr = [30];
				if(!in_array($amountInUsd,$ramUsdArr)){
					$this->Flash->error(__('Amount should be 30 only.'));
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawal']);
				}
				
				$withdrawalAmount = $amountInUsd/$currentPrice;
				
				if($withdrawalAmount>$userBalance){
					$this->Flash->error(__('Insufficient balance in wallet.'));
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawal']);
				}
				
				
				
				$tx_id =  $this->Users->getUniqueId($authUserId);
				$realWithdrawalAmount = $withdrawalAmount;
				
				
				$exchangeWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				$getWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				if($exchangeWalletAddress != $getWalletAddress){
					$fromWalletAddress = "0xd7276a4eb1792836777d882c7bec1581d43496c5";
					$password = "vip#f3sd3AJMBf0a6@4344";
					$toWalletAddress = $getWalletAddress;
					$transferType =  "coin_transfer";
					$tx_id = $this->Users->transferCoinToAddress($password,$fromWalletAddress,$toWalletAddress,$realWithdrawalAmount,$transferType);
				}
				else {
					$toWalletAddress = $exchangeWalletAddress;
				}
				
				
				
				if(!empty($tx_id)){
					// save withdrawal amount in transaction table
					$newInsertArr = [];
					$withdrawalTxId = time().uniqid(mt_rand(),true);
					$newInsertArr['user_id'] = $authUserId;
					$newInsertArr['wallet_address'] = $toWalletAddress;
					$newInsertArr['withdrawal_amount_in_usd'] = $amountInUsd;
					$newInsertArr['withdrawal_tx_id'] = $tx_id;
					$newInsertArr['withdrawal_coin_price'] = $currentPrice;
					$newInsertArr['withdrawal_send'] = 'N';
					$newInsertArr['cryptocoin_id'] = $coindId;
					$newInsertArr['coin_amount'] = "-".$realWithdrawalAmount;
					$newInsertArr['tx_type'] = 'withdrawal';
					$newInsertArr['remark'] = 'withdrawal';
					$newInsertArr['status'] = 'completed';
                    $newInsertArr['fees'] = $transFee;
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$insertIntoTransactions=$this->Transactions->newEntity();
					$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
					$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
					$transactionId = $insertIntoTransactions->id;
					if($insertIntoTransactions){
						
						$this->Flash->success($coin.__(' withdrawal successfully. Transactions ID: ').$tx_id);
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawal']);
					}
					else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawal']);
					}
				}
				else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawal']);
				}
		}
		
		
	}	
	
	public function usdwithdrawalamo(){
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		
		$authUserId = $this->Auth->user('id');
		/* if($authUserId!=10003090){
			$this->redirect(['controller'=>'pages','action'=>'mywallet']);
		} */
		$cudate = date('Y-m-d H:i:s');
		$coin = "USD";
		if($coin==null){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
		if(empty($findCoinDetail)){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('coinDetail',$findCoinDetail);
		$coindId = $findCoinDetail['id'];
		$this->set('coindId',$coindId);
		
		$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
		if(empty($userBalance)) {
			$userBalance = 0;
		}
		$this->set('userbalance',$userBalance);
		
		$transFeesArr = [1=>0.005,2=>0.02,3=>0.1,4=>0.1,5=>0.1]; 
		$transFee = $transFeesArr[$coindId];
		$this->set('transFee',$transFee);
		
		$secondCoinId = 3;
		$firstCoinId = 2;
		
		/*$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		 $currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$coindId],
																					  ['spend_cryptocoin_id'=>$coindId]
						  ]
					],	
		'limit' => 1,			 
		'order' => ['id'=>'desc']
		])	
	  ->hydrate(false)
	  ->first();
		
		
		$currentPrice = !empty($currentPrice['get_per_price']) ? $currentPrice['get_per_price']*$baseCoinPriceInUsd : 1; */
		
		$currentPrice = 1 ;
		$this->set('currentPrice',$currentPrice);
		
		/*=================1=========================*/
		$firstCoinId = 2;
		$secondCoinId = 3;
		$getPrice = 0;
		
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		
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
		if(!empty($currentPrice)){
			$getPrice = $currentPrice['get_per_price'];
		}														  
		$returnArr = [];
		//$returnArr['ram_currentprice_eth'] = $getPrice;
		$returnArr['ram_currentprice_usd'] = $getPrice*$baseCoinPriceInUsd;
		$ramCurrentPrice=$returnArr['ram_currentprice_usd'];
	    $this->set('ramCurrentPrice',$ramCurrentPrice);
		/*========================1===================================*/
		
		/*======================2=============================*/
		$firstCoinId = 2;
		$secondCoinId = 4;
		$getPrice = 0;
		
		
		
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
		if(!empty($currentPrice)){
	     $getPrice = $currentPrice['get_per_price'];
		}														  
		$returnArr1 = [];
		//$returnArr1['admc_currentprice_eth'] = $getPrice;
		$returnArr['admc_currentprice_usd'] = $getPrice*$baseCoinPriceInUsd;
		$admcCurrentPrice =$returnArr['admc_currentprice_usd'];
		$this->set('admcCurrentPrice',$admcCurrentPrice);
		/*=======================2============================*/
		
		/*======================3=============================*/
	
		$returnArr2 = [];
		//$returnArr2['eth_currentprice_eth'] = $baseCoinPriceInUsd;
		$returnArr['eth_currentprice_usd'] = $baseCoinPriceInUsd;
		$ethCurrentPrice = $returnArr['eth_currentprice_usd'];
		$this->set('ethCurrentPrice',$ethCurrentPrice);
		/*=======================3============================*/
		
		/*=====================4==============================*/
		$returnArr['usd_currentprice_usd'] = 1;
		$usdCurrentPrice = 1; 
		$this->set('usdCurrentPrice',$usdCurrentPrice);
		/*===================================================*/
		
		
		
		
		  if ($this->request->is(['post','put'])) {
			  
			  
				$getSelectType = $this->request->data['amount_in_usd'];
				if(empty($getSelectType)){
					$this->Flash->error(__('Please Select Type'));
					return $this->redirect(['controller'=>'pages','action'=>
					'usdwithdrawalamo']);
				 }
				$selectValue=array(2,3,4,5);
			    if (!in_array($getSelectType,$selectValue)) {
				 $this->Flash->error(__('Please Select a correct type.'));
				 return $this->redirect(['controller'=>'pages','action'=>
					'usdwithdrawalamo']);
				}
				
				$getSelectAmount = $this->request->data['amount'];
				if(empty($getSelectAmount)){
					$this->Flash->error(__('Please Select Amount.'));
					return $this->redirect(['controller'=>'pages','action'=>
					'usdwithdrawalamo']);
				 }
				$getSelectType = $this->request->data['amount_in_usd'];
				  if($getSelectType==2){
						$selectAmountEth=array(100,150);
						if (!in_array($getSelectAmount,$selectAmountEth))  {
							$this->Flash->error(__('Please select the correct ETH amount.'));
							return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
						}
				   }
				   
				   /* else if($getSelectType==5){
						$selectAmountEth=array(100);
						if (!in_array($getSelectAmount,$selectAmountEth))  {
							$this->Flash->error('Please Select a correct usd amount.');
							return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
						}
				   }
				   
				   else if($getSelectType==4){
						$selectAmountEth=array(50);
						if (!in_array($getSelectAmount,$selectAmountEth))  {
							$this->Flash->error('Please Select a correct usd amount.');
							return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
						}
				   } */
				   
				   else {
						$selectAmountOther=array(200);
						if (!in_array($getSelectAmount,$selectAmountOther))  {
							$this->Flash->error(__('Please select a correct amount.'));
							return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
						} 
				   }
				  
			   $getSelectAmount = $this->request->data['amount'];
		       $getSelectType = $this->request->data['amount_in_usd'];
		        if($getSelectType == 2) {
				 $allAmount = $ethCurrentPrice; 
				}
				if($getSelectType == 3) {
				 $allAmount = $ramCurrentPrice; 
				}
				if($getSelectType ==4) {
				 $allAmount = $admcCurrentPrice;
				}
				if($getSelectType == 5) {
				 $allAmount = $usdCurrentPrice;
				}
				$quantity = $getSelectAmount/$allAmount;
				
				$currentPrice = $allAmount;
				
				$userBalance = $this->Users->getLocalUserBalance($authUserId,$getSelectType);
				if($userBalance<$quantity){
					$this->Flash->error(__('Insufficient balance in wallet'));
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				/* $getInputCode = $this->request->data['email_code'];
				//print_r($getInputCode); die;
				//$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
				if(empty($getInputCode)){
					$this->Flash->error('Please enter security code.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				$getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $getInputCode){
					$this->Flash->error('Please enter Valid code.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}*/
				
				
				/* $amountInUsd = strip_tags($this->request->data['amount_in_usd']); 
				
				if(empty($amountInUsd)){
						$this->Flash->error('All fields are required.');
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				
				//$ramUsdArr = [30,100,200];
				//$ramUsdArr = [100,200];
				$ramUsdArr = [30];
				if(!in_array($amountInUsd,$ramUsdArr)){
					$this->Flash->error('Amount should be 30 only.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				
				$withdrawalAmount = $amountInUsd/$currentPrice;
				
				if($withdrawalAmount>$userBalance){
					$this->Flash->error('Insufficient balance in wallet.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				} */
				
				
				
				$tx_id =  $this->Users->getUniqueId($authUserId);
				//$realWithdrawalAmount = $withdrawalAmount;
				
				
				$exchangeWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				$getWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				/* if($exchangeWalletAddress != $getWalletAddress){
					$fromWalletAddress = "0xd7276a4eb1792836777d882c7bec1581d43496c5";
					$password = "vip#f3sd3AJMBf0a6@4344";
					$toWalletAddress = $getWalletAddress;
					$transferType =  "coin_transfer";
					$tx_id = $this->Users->transferCoinToAddress($password);
					//$fromWalletAddress,$toWalletAddress,$realWithdrawalAmount,$transferType);
				}
				else {
					$toWalletAddress = $exchangeWalletAddress;
				} */
				$toWalletAddress = "";
				
				
				if(!empty($tx_id)){
					// save withdrawal amount in transaction table
					$newInsertArr = [];
					$withdrawalTxId = time().uniqid(mt_rand(),true);
					$newInsertArr['user_id'] = $authUserId;
					$newInsertArr['wallet_address'] = $toWalletAddress;
					$newInsertArr['withdrawal_amount_in_usd'] = $getSelectAmount;
					$newInsertArr['withdrawal_tx_id'] = $tx_id;
					$newInsertArr['withdrawal_coin_price'] = $currentPrice;
					$newInsertArr['withdrawal_send'] = 'N';
					$newInsertArr['cryptocoin_id'] = $getSelectType;
					$newInsertArr['coin_amount'] = "-".$quantity;
					$newInsertArr['tx_type'] = 'withdrawal';
					$newInsertArr['remark'] = 'withdrawal';
					$newInsertArr['description'] = 'massconnect_withdrawal';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$insertIntoTransactions=$this->Transactions->newEntity();
					$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
					$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
					$transactionId = $insertIntoTransactions->id;
					if($insertIntoTransactions){
						
						$this->Flash->success($coin.__(' withdrawal successfully. Transactions ID: ').$tx_id);
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
					}
					else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
					}
				}
				else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
		}
		
		
	}
	
	/*created start*/
	
	public function wccwithdrawal(){
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		
		$authUserId = $this->Auth->user('id');
		$this->set('currentUserId',$authUserId);
		/* if($authUserId!=10003090){
			$this->redirect(['controller'=>'pages','action'=>'mywallet']);
		} */
		$cudate = date('Y-m-d H:i:s');
		$coin = "USD";
		if($coin==null){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
		if(empty($findCoinDetail)){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('coinDetail',$findCoinDetail);
		$coindId = $findCoinDetail['id'];
		$this->set('coindId',$coindId);
		
		$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
		if(empty($userBalance)) {
			$userBalance = 0;
		}
		$this->set('userbalance',$userBalance);
		
		$transFeesArr = [1=>0.005,2=>0.02,3=>0.1,4=>0.1,5=>0.1]; 
		$transFee = $transFeesArr[$coindId];
		$this->set('transFee',$transFee);
		
		$secondCoinId = 3;
		$firstCoinId = 2;
		
		/*$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		 $currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$coindId],
																					  ['spend_cryptocoin_id'=>$coindId]
						  ]
					],	
		'limit' => 1,			 
		'order' => ['id'=>'desc']
		])	
	  ->hydrate(false)
	  ->first();
		
		
		$currentPrice = !empty($currentPrice['get_per_price']) ? $currentPrice['get_per_price']*$baseCoinPriceInUsd : 1; */
		
		$currentPrice = 1 ;
		$this->set('currentPrice',$currentPrice);
		
		/*=================1=========================*/
		$firstCoinId = 2;
		$secondCoinId = 3;
		$getPrice = 0;
		
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		
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
		if(!empty($currentPrice)){
			$getPrice = $currentPrice['get_per_price'];
		}														  
		$returnArr = [];
		//$returnArr['ram_currentprice_eth'] = $getPrice;
		$returnArr['ram_currentprice_usd'] = $getPrice*$baseCoinPriceInUsd;
		$ramCurrentPrice=$returnArr['ram_currentprice_usd'];
	    $this->set('ramCurrentPrice',$ramCurrentPrice);
		/*========================1===================================*/
		
		/*======================2=============================*/
		$firstCoinId = 2;
		$secondCoinId = 4;
		$getPrice = 0;
		
		
		
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
		if(!empty($currentPrice)){
	     $getPrice = $currentPrice['get_per_price'];
		}														  
		$returnArr1 = [];
		//$returnArr1['admc_currentprice_eth'] = $getPrice;
		$returnArr['admc_currentprice_usd'] = $getPrice*$baseCoinPriceInUsd;
		$admcCurrentPrice =$returnArr['admc_currentprice_usd'];
		$this->set('admcCurrentPrice',$admcCurrentPrice);
		/*=======================2============================*/
		
		/*======================3=============================*/
	
		$returnArr2 = [];
		//$returnArr2['eth_currentprice_eth'] = $baseCoinPriceInUsd;
		$returnArr['eth_currentprice_usd'] = $baseCoinPriceInUsd;
		$ethCurrentPrice = $returnArr['eth_currentprice_usd'];
		$this->set('ethCurrentPrice',$ethCurrentPrice);
		/*=======================3============================*/
		
		/*=====================4==============================*/
		$returnArr['usd_currentprice_usd'] = 1;
		$usdCurrentPrice = 1; 
		$this->set('usdCurrentPrice',$usdCurrentPrice);
		/*===================================================*/
		
		
		
		
		  if ($this->request->is(['post','put'])) {
			  
			  
				$getSelectType = $this->request->data['amount_in_usd'];
				if(empty($getSelectType)){
					$this->Flash->error(__('Please select type'));
					return $this->redirect(['controller'=>'pages','action'=>
					'wccwithdrawal']);
				 }
				$selectValue=array(2,3,4,5);
			    if (!in_array($getSelectType,$selectValue)) {
				 $this->Flash->error(__('Please select the correct type.'));
				 return $this->redirect(['controller'=>'pages','action'=>
					'wccwithdrawal']);
				}
				
				$getSelectAmount = $this->request->data['amount'];
		        if($this->request->data['wallet_type'] != 'vip_express' && $this->request->data['wallet_type'] != 'life_style'){
					
					if(empty($getSelectAmount)){
					$this->Flash->error(__('Please select the amount.'));
					return $this->redirect(['controller'=>'pages','action'=>
					'wccwithdrawal']);
				 }
				 
				}
			
				
				
				 /* else{
					$getSelectAmount = 0;
				}  */
					
				
				
				$getSelectType = $this->request->data['amount_in_usd'];
				  if($getSelectType==2){
					  //echo $getSelectAmount.','.$selectAmountOther;die;
						if($this->request->data['wallet_type'] == 'vip_express') {
							$selectAmountEth=array(30,0.3);
							if (!in_array($getSelectAmount,$selectAmountEth))  {
								
								$this->Flash->error(__('Please select the correct ETH amount.'));
								return $this->redirect(['controller'=>'pages','action'=>'wccwithdrawal']);
							}
						}
				   }
				   
				   else {
						$selectAmountOther=array(40);
						
						
						if (!in_array($getSelectAmount,$selectAmountOther))  {
							$this->Flash->error(__('Please select the correct amount.'));
							return $this->redirect(['controller'=>'pages','action'=>'wccwithdrawal']);
						} 
				   }
				// echo $getSelectAmount.',';pr($selectAmountOther);die;
			   $getSelectAmount = $this->request->data['amount'];
		       $getSelectType = $this->request->data['amount_in_usd'];
		        if($getSelectType == 2) {
				 $allAmount = $ethCurrentPrice; 
				}
				if($getSelectType == 3) {
				 $allAmount = $ramCurrentPrice; 
				}
				if($getSelectType ==4) {
				 $allAmount = $admcCurrentPrice;
				}
				if($getSelectType == 5) {
				 $allAmount = $usdCurrentPrice;
				}
				if($this->request->data['wallet_type'] == 'life_style'){
				if($getSelectAmount > 0){
					$quantity = $getSelectAmount/$allAmount;
				}
				else{
					
					$quantity = 1.5;
					//print_r($currentPrice); die;
					$getSelectAmount = $allAmount*$quantity;
				}
				
				}
				if($this->request->data['wallet_type'] == 'vip_express'){
					
					if($getSelectAmount==30){
						$quantity = $getSelectAmount/$allAmount;
					}
					if($getSelectAmount==0.3){
						$quantity = 0.3;
					}
					
					/* if($getSelectAmount > 0){
						
						
						if($this->request->data['amount_in_usd'] == 2){
							$quantity =61000;	
						}
						else {
							$quantity = $getSelectAmount/$allAmount;
						}
						
					}
					else{
						if($this->request->data['amount_in_usd'] == 3){
						$getSelectAmount = 40;	
						}
						else {
							$quantity = 0.3;
						//print_r($currentPrice); die;
						$getSelectAmount = $allAmount*$quantity;
						}
						
					} */
				
				}
				
				 if($this->request->data['wallet_type'] == 'wcc_express'){
					
					if($getSelectAmount==20){
						$quantity = $getSelectAmount/$allAmount;
					}
				 }
				if($this->request->data['wallet_type'] == 'wcc'){
					$quantity = 61000;
				/* if($getSelectAmount > 0){
					$quantity = $getSelectAmount/$allAmount;
				}
				else{
					
					$quantity = 60;
					//print_r($currentPrice); die;
					$getSelectAmount = $allAmount*$quantity;
				} */
				}
				
				$currentPrice = $allAmount;
				
				$userBalance = $this->Users->getLocalUserBalance($authUserId,$getSelectType);
				if($userBalance<$quantity){
					$this->Flash->error(__('Insufficient Balance'));
					return $this->redirect(['controller'=>'pages','action'=>'wccwithdrawal']);
				}
				/* $getInputCode = $this->request->data['email_code'];
				//print_r($getInputCode); die;
				//$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
				if(empty($getInputCode)){
					$this->Flash->error('Please enter security code.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				$getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $getInputCode){
					$this->Flash->error('Please enter Valid code.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}*/
				
				
				/* $amountInUsd = strip_tags($this->request->data['amount_in_usd']); 
				
				if(empty($amountInUsd)){
						$this->Flash->error('All fields are required.');
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				
				//$ramUsdArr = [30,100,200];
				//$ramUsdArr = [100,200];
				$ramUsdArr = [30];
				if(!in_array($amountInUsd,$ramUsdArr)){
					$this->Flash->error('Amount should be 30 only.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				
				$withdrawalAmount = $amountInUsd/$currentPrice;
				
				if($withdrawalAmount>$userBalance){
					$this->Flash->error('Insufficient balance in wallet.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				} */
				
				
				
				$tx_id =  $this->Users->getUniqueId($authUserId);
				//$realWithdrawalAmount = $withdrawalAmount;
				
				
				$exchangeWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				$getWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				/* if($exchangeWalletAddress != $getWalletAddress){
					$fromWalletAddress = "0xd7276a4eb1792836777d882c7bec1581d43496c5";
					$password = "vip#f3sd3AJMBf0a6@4344";
					$toWalletAddress = $getWalletAddress;
					$transferType =  "coin_transfer";
					$tx_id = $this->Users->transferCoinToAddress($password);
					//$fromWalletAddress,$toWalletAddress,$realWithdrawalAmount,$transferType);
				}
				else {
					$toWalletAddress = $exchangeWalletAddress;
				} */
				$toWalletAddress = "";
				
				
				if(!empty($tx_id)){
					// save withdrawal amount in transaction table
					$newInsertArr = [];
					$withdrawalTxId = time().uniqid(mt_rand(),true);
					$newInsertArr['user_id'] = $authUserId;
					$newInsertArr['wallet_address'] = $toWalletAddress;
					$newInsertArr['withdrawal_amount_in_usd'] = $getSelectAmount;
					$newInsertArr['withdrawal_tx_id'] = $tx_id;
					$newInsertArr['withdrawal_coin_price'] = $currentPrice;
					$newInsertArr['withdrawal_send'] = 'N';
					$newInsertArr['cryptocoin_id'] = $getSelectType;
					$newInsertArr['coin_amount'] = "-".$quantity;
					$newInsertArr['tx_type'] = 'withdrawal';
					$newInsertArr['remark'] = 'withdrawal';
					if(!empty($this->request->data['wallet_type'])){
					$newInsertArr['description'] = $this->request->data['wallet_type'];
					}
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$insertIntoTransactions=$this->Transactions->newEntity();
					$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
					$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
					$transactionId = $insertIntoTransactions->id;
					if($insertIntoTransactions){
						
						$this->Flash->success($coin.__(' withdrawal successfully. Transactions ID: ').$tx_id);
						return $this->redirect(['controller'=>'pages','action'=>'wccwithdrawal']);
					}
					else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'wccwithdrawal']);
					}
				}
				else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'wccwithdrawal']);
				}
		}
		
		
	}
	/*created end*/
	

	/*start code*/
	public function worldwithdrawal(){
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		
		$authUserId = $this->Auth->user('id');
		/* if($authUserId!=10003090){
			$this->redirect(['controller'=>'pages','action'=>'mywallet']);
		} */
		$cudate = date('Y-m-d H:i:s');
		$coin = "USD";
		if($coin==null){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
		if(empty($findCoinDetail)){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('coinDetail',$findCoinDetail);
		$coindId = $findCoinDetail['id'];
		$this->set('coindId',$coindId);
		
		$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
		if(empty($userBalance)) {
			$userBalance = 0;
		}
		$this->set('userbalance',$userBalance);
		
		$transFeesArr = [1=>0.005,2=>0.02,3=>0.1,4=>0.1,5=>0.1]; 
		$transFee = $transFeesArr[$coindId];
		$this->set('transFee',$transFee);
		
		$secondCoinId = 3;
		$firstCoinId = 2;
		
		/*$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		 $currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$coindId],
																					  ['spend_cryptocoin_id'=>$coindId]
						  ]
					],	
		'limit' => 1,			 
		'order' => ['id'=>'desc']
		])	
	  ->hydrate(false)
	  ->first();
		
		
		$currentPrice = !empty($currentPrice['get_per_price']) ? $currentPrice['get_per_price']*$baseCoinPriceInUsd : 1; */
		
		$currentPrice = 1 ;
		$this->set('currentPrice',$currentPrice);
		
		/*=================1=========================*/
		$firstCoinId = 2;
		$secondCoinId = 3;
		$getPrice = 0;
		
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		
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
		if(!empty($currentPrice)){
			$getPrice = $currentPrice['get_per_price'];
		}														  
		$returnArr = [];
		//$returnArr['ram_currentprice_eth'] = $getPrice;
		$returnArr['ram_currentprice_usd'] = $getPrice*$baseCoinPriceInUsd;
		$ramCurrentPrice=$returnArr['ram_currentprice_usd'];
	    $this->set('ramCurrentPrice',$ramCurrentPrice);
		/*========================1===================================*/
		
		/*======================2=============================*/
		$firstCoinId = 2;
		$secondCoinId = 4;
		$getPrice = 0;
		
		
		
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
		if(!empty($currentPrice)){
	     $getPrice = $currentPrice['get_per_price'];
		}														  
		$returnArr1 = [];
		//$returnArr1['admc_currentprice_eth'] = $getPrice;
		$returnArr['admc_currentprice_usd'] = $getPrice*$baseCoinPriceInUsd;
		$admcCurrentPrice =$returnArr['admc_currentprice_usd'];
		$this->set('admcCurrentPrice',$admcCurrentPrice);
		/*=======================2============================*/
		
		/*======================3=============================*/
	
		$returnArr2 = [];
		//$returnArr2['eth_currentprice_eth'] = $baseCoinPriceInUsd;
		$returnArr['eth_currentprice_usd'] = $baseCoinPriceInUsd;
		$ethCurrentPrice = $returnArr['eth_currentprice_usd'];
		$this->set('ethCurrentPrice',$ethCurrentPrice);
		/*=======================3============================*/
		
		/*=====================4==============================*/
		$returnArr['usd_currentprice_usd'] = 1;
		$usdCurrentPrice = 1; 
		$this->set('usdCurrentPrice',$usdCurrentPrice);
		/*===================================================*/
		
		
		
		
		  if ($this->request->is(['post','put'])) {
			  
			  
				$getSelectType = $this->request->data['amount_in_usd'];
				if(empty($getSelectType)){
					$this->Flash->error(__('Please select type'));
					return $this->redirect(['controller'=>'pages','action'=>
					'worldwithdrawal']);
				 }
				$selectValue=array(2,3,4,5);
			    if (!in_array($getSelectType,$selectValue)) {
				 $this->Flash->error(__('Please select the correct type.'));
				 return $this->redirect(['controller'=>'pages','action'=>
					'worldwithdrawal']);
				}
				
				$getSelectAmount = $this->request->data['amount'];
				if(empty($getSelectAmount)){
					$this->Flash->error(__('Please select the amount.'));
					return $this->redirect(['controller'=>'pages','action'=>
					'worldwithdrawal']);
				 }
				$getSelectType = $this->request->data['amount_in_usd'];
				  if($getSelectType==2){
						$selectAmountEth=array(30,40);
						if (!in_array($getSelectAmount,$selectAmountEth))  {
							$this->Flash->error(__('Please select the correct ETH amount.'));
							return $this->redirect(['controller'=>'pages','action'=>'worldwithdrawal']);
						}
				   }
				   
				   else if($getSelectType==3){
						$selectAmountEth=array(20);
						if (!in_array($getSelectAmount,$selectAmountEth))  {
							$this->Flash->error(__('Please select the correct RAM amount.'));
							return $this->redirect(['controller'=>'pages','action'=>'worldwithdrawal']);
						}
				   }
				   
				   else if($getSelectType==4){
						$selectAmountEth=array(20);
						if (!in_array($getSelectAmount,$selectAmountEth))  {
							$this->Flash->error(__('Please select the correct ADMC amount.'));
							return $this->redirect(['controller'=>'pages','action'=>'worldwithdrawal']);
						}
				   }
				  
				   
				    /* else {
						$selectAmountOther=array(50);
						if (!in_array($getSelectAmount,$selectAmountOther))  {
							$this->Flash->error('Please Select a correct amount.');
							return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
						}  
				   } */
				  
			   $getSelectAmount = $this->request->data['amount'];
		       $getSelectType = $this->request->data['amount_in_usd'];
		        if($getSelectType == 2) {
				 $allAmount = $ethCurrentPrice; 
				}
				if($getSelectType == 3) {
				 $allAmount = $ramCurrentPrice; 
				}
				if($getSelectType ==4) {
				 $allAmount = $admcCurrentPrice;
				}
				if($getSelectType == 5) {
				 $allAmount = $usdCurrentPrice;
				}
				$quantity = $getSelectAmount/$allAmount;
				
				$currentPrice = $allAmount;
				
				$userBalance = $this->Users->getLocalUserBalance($authUserId,$getSelectType);
				if($userBalance<$quantity){
					$this->Flash->error(__('Insufficient Balance'));
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				/* $getInputCode = $this->request->data['email_code'];
				//print_r($getInputCode); die;
				//$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
				if(empty($getInputCode)){
					$this->Flash->error('Please enter security code.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				$getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $getInputCode){
					$this->Flash->error('Please enter Valid code.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}*/
				
				
				/* $amountInUsd = strip_tags($this->request->data['amount_in_usd']); 
				
				if(empty($amountInUsd)){
						$this->Flash->error('All fields are required.');
						return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				
				//$ramUsdArr = [30,100,200];
				//$ramUsdArr = [100,200];
				$ramUsdArr = [30];
				if(!in_array($amountInUsd,$ramUsdArr)){
					$this->Flash->error('Amount should be 30 only.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				}
				
				$withdrawalAmount = $amountInUsd/$currentPrice;
				
				if($withdrawalAmount>$userBalance){
					$this->Flash->error('Insufficient balance in wallet.');
					return $this->redirect(['controller'=>'pages','action'=>'usdwithdrawalamo']);
				} */
				
				
				
				$tx_id =  $this->Users->getUniqueId($authUserId);
				//$realWithdrawalAmount = $withdrawalAmount;
				
				
				$exchangeWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				$getWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				/* if($exchangeWalletAddress != $getWalletAddress){
					$fromWalletAddress = "0xd7276a4eb1792836777d882c7bec1581d43496c5";
					$password = "vip#f3sd3AJMBf0a6@4344";
					$toWalletAddress = $getWalletAddress;
					$transferType =  "coin_transfer";
					$tx_id = $this->Users->transferCoinToAddress($password);
					//$fromWalletAddress,$toWalletAddress,$realWithdrawalAmount,$transferType);
				}
				else {
					$toWalletAddress = $exchangeWalletAddress;
				} */
				$toWalletAddress = "";
				
				
				if(!empty($tx_id)){
					// save withdrawal amount in transaction table
					$newInsertArr = [];
					$withdrawalTxId = time().uniqid(mt_rand(),true);
					$newInsertArr['user_id'] = $authUserId;
					$newInsertArr['wallet_address'] = $toWalletAddress;
					$newInsertArr['withdrawal_amount_in_usd'] = $getSelectAmount;
					$newInsertArr['withdrawal_tx_id'] = $tx_id;
					$newInsertArr['withdrawal_coin_price'] = $currentPrice;
					$newInsertArr['withdrawal_send'] = 'N';
					$newInsertArr['cryptocoin_id'] = $getSelectType;
					$newInsertArr['coin_amount'] = "-".$quantity;
					$newInsertArr['tx_type'] = 'withdrawal';
					$newInsertArr['remark'] = 'withdrawal';
					$newInsertArr['description'] = 'massconnect_withdrawal';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$insertIntoTransactions=$this->Transactions->newEntity();
					$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
					$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
					$transactionId = $insertIntoTransactions->id;
					if($insertIntoTransactions){
						
						$this->Flash->success($coin.__(' withdrawal successfully. Transactions ID: ').$tx_id);
						return $this->redirect(['controller'=>'pages','action'=>'worldwithdrawal']);
					}
					else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'worldwithdrawal']);
					}
				}
				else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'worldwithdrawal']);
				}
		}
		
		
	}

	/* end code */

	
	public function ercwithdrawal($coin=null){
	
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		
		$authUserId = $this->Auth->user('id');
		$cudate = date('Y-m-d H:i:s');
		
		if($coin==null){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
		if(empty($findCoinDetail)){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('coinDetail',$findCoinDetail);
		$coindId = $findCoinDetail['id'];
		$this->set('coindId',$coindId);
		
		$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
		if(empty($userBalance)) {
			$userBalance = 0;
		}
		$this->set('userbalance',$userBalance);
		
		$transFeesArr = [1=>0.005,2=>0.02,3=>0.1]; 
		$transFee = $transFeesArr[$coindId];
		$this->set('transFee',$transFee);
		
		$secondCoinId = 3;
		$firstCoinId = 2;
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		$currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$coindId],
																					  ['spend_cryptocoin_id'=>$coindId]
																					  ]
																				],	
																	'limit' => 1,			 
																	'order' => ['id'=>'desc']
																	])	
																  ->hydrate(false)
																  ->first();
		
		
		$currentPrice = !empty($currentPrice['get_per_price']) ? $currentPrice['get_per_price']*$baseCoinPriceInUsd : 1;
		$this->set('currentPrice',$currentPrice);
		
		if ($this->request->is(['post','put'])) {
			
			
				$getInputCode = $this->request->data['email_code'];
				$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
				if(empty($getInputCode)){
					$this->Flash->error(__('Please enter security code.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
				$getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $getInputCode){
					$this->Flash->error(__('Please enter valid code.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
				
				$withdrawalAmount = strip_tags($this->request->data['quantity']);
				
					
				if(empty($getWalletAddress) || empty($withdrawalAmount)){
					$this->Flash->error(__('All fields are required.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
				
					
				
				if($withdrawalAmount>$userBalance){
					$this->Flash->error(__('Insufficient balance in wallet.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
				
			
				
				$tx_id =  $this->Users->getUniqueId($authUserId);
				$realWithdrawalAmount = $withdrawalAmount - $transFee;
				
				
				$exchangeWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";
				/* if($exchangeWalletAddress==$getWalletAddress){
					$this->Flash->error("For withdrawal in ramtrex address click on remtrex withdrawal");
					return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				} */
				
				
				$fromWalletAddress = "0x85262c96cc46d38ded840309af80eeb8f8ae1d31";
				$password = "masscrypt02#D4854fh9@ds";
				$toWalletAddress = $getWalletAddress;
				$transferType = ($coindId==2) ? "eth_transfer" : "coin_transfer";
				$tx_id = $this->Users->transferCoinToAddress($password,$fromWalletAddress,$toWalletAddress,$realWithdrawalAmount,$transferType);
				
				$toWalletAddress = $getWalletAddress;
				
				if(!empty($tx_id)){
					// save withdrawal amount in transaction table
					$newInsertArr = [];
					$withdrawalTxId = time().uniqid(mt_rand(),true);
					$newInsertArr['user_id'] = $authUserId;
					$newInsertArr['wallet_address'] = $toWalletAddress;
					$newInsertArr['withdrawal_tx_id'] = $tx_id;
					$newInsertArr['withdrawal_coin_price'] = $currentPrice;
					$newInsertArr['withdrawal_amount_in_usd'] = $realWithdrawalAmount*$currentPrice;
					$newInsertArr['withdrawal_send'] = 'Y';
					$newInsertArr['cryptocoin_id'] = $coindId;
					$newInsertArr['coin_amount'] = "-".$realWithdrawalAmount;
					$newInsertArr['tx_type'] = 'withdrawal';
					$newInsertArr['tx_id'] = $tx_id;
					$newInsertArr['remark'] = 'withdrawal';
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate;
					
					// insert data
					$insertIntoTransactions=$this->Transactions->newEntity();
					$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
					$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
					$transactionId = $insertIntoTransactions->id;
					if($insertIntoTransactions){
						
						// save withdrawal Fee amount in transaction table
						$newInsertArr = [];
						
						$newInsertArr['user_id'] = 1;
						$newInsertArr['transaction_id'] = $transactionId;
						$newInsertArr['cryptocoin_id'] = $coindId;
						$newInsertArr['coin_amount'] = $transFee;
						$newInsertArr['tx_type'] = 'withdrawal_fee';
						$newInsertArr['remark'] = 'withdrawal_fee';
						$newInsertArr['status'] = 'completed';
						$newInsertArr['created'] = $cudate;
						$newInsertArr['updated'] = $cudate;
						
						// insert data
						$insertWithdrawalFee=$this->Transactions->newEntity();
						$insertWithdrawalFee=$this->Transactions->patchEntity($insertWithdrawalFee,$newInsertArr);
						$insertWithdrawalFee=$this->Transactions->save($insertWithdrawalFee);
						
						$this->Flash->success($coin.__(' withdrawal successfully. Transaction ID: ').$tx_id);
						return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
					}
					else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
					}
				}
				else {
						$this->Flash->error($coin.__(' withdrawal failed, Please Try Again.'));
						return $this->redirect(['controller'=>'pages','action'=>'ramwithdrawal',$coin]);
				}
		}
		
		
	}
	
	
	public function ramdeposit(){
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Ramtrexapilog');
		
		$authUserId = $this->Auth->user('id');
		$uniqueAddress = $this->Auth->user('unique_id');
		$this->set('uniqueAddress',$uniqueAddress);
		if($authUserId!=10003090){
			//$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$cudate = date('Y-m-d H:i:s');
		$coin = 'RAM';
		if($coin==null){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
		if(empty($findCoinDetail)){
			$this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		$this->set('coinDetail',$findCoinDetail);
		$coindId = $findCoinDetail['id'];
		$this->set('coindId',$coindId);
		
		$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
		if(empty($userBalance)) {
			$userBalance = 0;
		}
		$this->set('userbalance',$userBalance);
		
		$transFeesArr = [1=>0.005,2=>0.02,3=>0.1]; 
		$transFee = $transFeesArr[$coindId];
		$this->set('transFee',$transFee);
		
		$secondCoinId = 3;
		$firstCoinId = 2;
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		$this->set('baseCoinPriceInUsd',$baseCoinPriceInUsd);
		
		$currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
																					  ['get_cryptocoin_id'=>$coindId],
																					  ['spend_cryptocoin_id'=>$coindId]
																					  ]
																				],	
																	'limit' => 1,			 
																	'order' => ['id'=>'desc']
																	])	
																  ->hydrate(false)
																  ->first();
		
		
		$currentPrice = !empty($currentPrice['get_per_price']) ? $currentPrice['get_per_price']*$baseCoinPriceInUsd : 1;
		$this->set('currentPrice',$currentPrice);
		
		if ($this->request->is(['post','put'])) {
			
				
				$getInputCode = $this->request->data['email_code'];
				//$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
				if(empty($getInputCode)){
					$this->Flash->error(__('Please enter security code.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramdeposit',$coin]);
				}
				$getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $getInputCode){
					$this->Flash->error(__('Please enter valid code.'));
					return $this->redirect(['controller'=>'pages','action'=>'ramdeposit',$coin]);
				}
			
				$tx_id = strip_tags($this->request->data['ramtrex_tx_id']); 
				
				$callRamtrexApi = $this->Users->callRamTrexApi($tx_id);
				
				// save data to log table
				$newLog = $this->Ramtrexapilog->newEntity();
				$newLog = $this->Ramtrexapilog->patchEntity($newLog,['user_id'=>$authUserId,'tx_id'=>$tx_id,'returndata'=>$callRamtrexApi]);
				$newLog = $this->Ramtrexapilog->save($newLog);
				
				
				/* if($callRamtrexApi==0){
					$this->Flash->error('Invalid Transaction id.');
					return $this->redirect(['controller'=>'pages','action'=>'ramdeposit']);
				} */
				
				
				if(!empty($callRamtrexApi)){
					$callRamtrexApi  = strip_tags($callRamtrexApi);
					$callRamtrexApi =preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $callRamtrexApi);
					$returnData = json_decode($callRamtrexApi,true);
					$returnData = $returnData[0];
					
					if($returnData['Status']=="success"){
						$coinAmt = $returnData['Tokennumer'];
						$txId = $returnData['Hashcode'];
						// save withdrawal amount in transaction table
						$newInsertArr = [];
						
						$newInsertArr['user_id'] = $authUserId;
						$newInsertArr['tx_id'] = $txId;
						$newInsertArr['cryptocoin_id'] = $coindId;
						$newInsertArr['coin_amount'] = $coinAmt;
						$newInsertArr['tx_type'] = 'purchase';
						$newInsertArr['remark'] = 'deposit';
						$newInsertArr['status'] = 'completed';
						$newInsertArr['created'] = $cudate;
						$newInsertArr['updated'] = $cudate;
						
						// insert data
						$insertIntoTransactions=$this->Transactions->newEntity();
						$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
						$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
						$transactionId = $insertIntoTransactions->id;
						if($insertIntoTransactions){
							
							$this->Flash->success($coin.__(' deposited successfully.'));
							return $this->redirect(['controller'=>'pages','action'=>'ramdeposit']);
						}
						else {
							$this->Flash->error(__('Unable to deposit RAM Token'));
							return $this->redirect(['controller'=>'pages','action'=>'ramdeposit']);
						}
					}
					else {
						$this->Flash->error($returnData['Message']);
						return $this->redirect(['controller'=>'pages','action'=>'ramdeposit']);
					}
				}
				else {
						$this->Flash->error(__('Unable to deposit RAM Token'));
						return $this->redirect(['controller'=>'pages','action'=>'ramdeposit']);
				}
		}
		
		
	}
	
	
	
	public function getcoinaddress(){
		
		$authUserId = $this->Auth->user('id');
		$getUserDetail = $this->Users->get($authUserId);
		$newAddress  = '';
		if(empty($getUserDetail['admc_address'])) {
		
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_PORT => "20155",
			  CURLOPT_URL => "http://178.128.223.236:20155/",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => '{"jsonrpc":"1.0","method":"getnewaddress"}',
			  CURLOPT_HTTPHEADER => array(
				"accept: application/json",
				"authorization: Basic cnBjcmFtdXNyYW1iOmExMkVFRW9wM1RyZWQzNDN3UWU0NTZiMXo3OGV2YjQ0NA==",
				"cache-control: no-cache",
				"content-type: application/json",
				"postman-token: 9424e70d-3859-47e8-8ee2-e630f7d18321"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if(!empty($response)){
				$resultDecode = json_decode($response,true);
				if(!empty($resultDecode['result'])){
					$newAddress  = $resultDecode['result'];
					
					//save admc address to database
					$getUserDetail = $this->Users->patchEntity($getUserDetail,['admc_address'=>$newAddress]);
					$getUserDetail = $this->Users->save($getUserDetail);
					
					
				}
			}
		
		}
		else {
			$newAddress  = $getUserDetail['admc_address'];
		}
		
		echo $newAddress; die;
		
	}
	
	
	
	public function validateAdmcAddress(){
		
		
		if ($this->request->is(['ajax'])) {
			$address = $this->request->data['address'];
			echo $checkValidate = $this->Users->admcCheckAddressValid($address); die;
		
		}
		echo 0; die;
	}
	
	
	
	
	public function flatExchange(){
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Users');
		$authUserId = $this->Auth->user('id');
		$cudate = date('Y-m-d H:i:s');
		$flatCurrencyArr = $this->Cryptocoin ->find('list', [
												'keyField' => 'id',
												'valueField' => 'short_name'
											])
											->where(['type =' => 'flat'])
											->toArray();
		
		$this->set('flatCurrencyArr',$flatCurrencyArr);
		if ($this->request->is(['post','put'])) {
			$getInputCode = $this->request->data['email_code'];
			$firstCoinId = $this->request->data['flat'];
			$amount = $this->request->data['amount'];
			 					
			if(empty($getInputCode)){
				$this->Flash->error(__('Please enter security code.'));
				return $this->redirect(['controller'=>'pages','action'=>'flatExchange']);
			}
			
			if(empty($amount) || $amount <= 0){
				$this->Flash->error(__('Please enter valid amount.'));
				return $this->redirect(['controller'=>'pages','action'=>'flatExchange']);
			}
			
			$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId,'type'=>'flat']])->hydrate(false)->first();
			if(empty($findCoinDetail)){
				$this->Flash->error(__('Please enter valid currency.'));
				return $this->redirect(['controller'=>'pages','action'=>'flatExchange']);
			}
			
			/*$getCodeFromSession = $this->request->session()->read('email_code');
			if($getCodeFromSession != $getInputCode){
				$this->Flash->error('Please enter Valid code.');
				return $this->redirect(['controller'=>'pages','action'=>'flatExchange']);
			} */
			
			$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
			if($amount > $userBalance){
				$this->Flash->error(__('Insufficient Balance'));
				return $this->redirect(['controller'=>'pages','action'=>'flatExchange']);
			}
			$secondCoinId = 2;
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
			}	
			$getCurrPrice =$currentPrice['get_per_price'];

			$getNtrVal= $getCurrPrice*$amount;
			$txId = $this->Users->getUniqueTxId();
			$newInsertArr = [];
			
			$newInsertArr['user_id'] = $authUserId;
			$newInsertArr['tx_id'] = $txId;
			$newInsertArr['cryptocoin_id'] = $firstCoinId;
			$newInsertArr['coin_amount'] = "-".$amount;
			$newInsertArr['tx_type'] = 'purchase';
			$newInsertArr['remark'] = 'flatExchange';
			$newInsertArr['status'] = 'completed';
			$newInsertArr['created'] = $cudate;
			$newInsertArr['updated'] = $cudate;
			
			// insert data
			$insertIntoTransactions=$this->Transactions->newEntity();
			$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
			$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
			if($insertIntoTransactions){
				$txId = $this->Users->getUniqueTxId();
				$newInsertArr = [];
			
				$newInsertArr['user_id'] = $authUserId;
				$newInsertArr['transaction_id'] = $insertIntoTransactions->id;
				$newInsertArr['tx_id'] = $txId;
				$newInsertArr['cryptocoin_id'] = $secondCoinId;
				$newInsertArr['coin_amount'] = $getNtrVal;
				$newInsertArr['flat_exchange_rate'] = $getCurrPrice;
				$newInsertArr['tx_type'] = 'purchase';
				$newInsertArr['remark'] = 'flatExchange';
				$newInsertArr['status'] = 'completed';
				$newInsertArr['created'] = $cudate;
				$newInsertArr['updated'] = $cudate;
				
				// insert data
				$insertIntoTransactions=$this->Transactions->newEntity();
				$insertIntoTransactions=$this->Transactions->patchEntity($insertIntoTransactions,$newInsertArr);
				$insertIntoTransactions=$this->Transactions->save($insertIntoTransactions);
				
				$this->Flash->success(__('Exchange With NTR Successfully.'));
				return $this->redirect(['controller'=>'pages','action'=>'flatExchange']);
			}
				
		}
		
	}
	
	public function getUserLocalBalance($coinId = null){
		$this->loadModel('Cryptocoin');
		$authUserId = $this->Auth->user('id');
		if ($this->request->is(['ajax'])) {
			if($coinId==null){
				echo 0; die;
			}
			
			$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$coinId]])->hydrate(false)->first();
			if(empty($findCoinDetail)){
				echo 0; die;
			}
			$coindId = $findCoinDetail['id'];
		
			
			echo $userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId); die;
		}
		echo 0; die;
	}
	
	
	  
}
