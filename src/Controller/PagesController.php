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
namespace App\Controller;  

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use DateTime;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */


class PagesController extends AppController
{ 
	public function beforeFilter(Event $event)
    {
		 parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['home','content','returndata','subscription','contact','checkusermembership','changebtcstatus','comparebtc','updatewithrawalstatus','getcurrentprice','ethwithdrawalstatus','coincallback','btccallback','support','disablewithrawalstatus','listing','validateuser','depositram','getcurrentpriceusd','updatewithrawalstatususd','updatewithrawalstatuseth','depositrealram','depositeth','getallcurrentprice','validatetokens','depositethtokens','checkvalidatetokens','updatevalidatetokens','ethvalidatetokens','wccethdeposit','wccvalidateuser',"getusercurrentbalance",'withdrawalapi','faq','policy','getcurrentpricehome','termsandconditions','crosschainrecovery','amlpolicy','getethaddress','home2','home3','priceUpdate','priceUpdatnight','hometest']);
    }
   	public function content($slug = null){
		$this->set('title',':: HedgeConnect ::'.ucwords(str_replace(array('_','-'),' ',$slug)));
		$pageContent = $this->Pages->find('all',[ 'fields'=>['id','title','slug','description'],'conditions'=>['slug' => $slug]])->first();
		if(!empty($pageContent)) $this->set('content',$pageContent);
		else return $this->redirect(['controller'=>'Pages','action' => 'home']);
	}
	
    public function home($ref_code=null)
    {
		$this->set('title','Home');
        $name = '';
        $user = [];
		$this->loadModel('Users');
		$referExist = ($ref_code!='') ? 1: 0;
		$this->set('referExist',$referExist);

		$ref_code = ($ref_code!='') ? $ref_code : "";

		if($ref_code != null)
        {

            $user = $this->Users->find('all',['fields'=>['id','name'],'conditions'=>['referral_code'=>$ref_code]])->hydrate(false)->first();

            if(!empty($user))
            {
                $name = $user['name'];
            }

        }
        $this->loadModel('ContactUs');
		$contactus = $this->ContactUs->newEntity();
		if ($this->request->is(['post' ,'put']))
		{
			//pr($this->request->data);die;
			$contactus = $this->ContactUs->patchEntity($contactus, $this->request->data);
			if ($newNetwork = $this->ContactUs->save($contactus)) {
				$this->Flash->success(__('Successfully submitted !!'));
				return $this->redirect(['controller'=>'pages','action' =>'contact']);
			}else $this->Flash->error(__('Someting went wrong !!'));
		}


		$this->set('contactus',$contactus);
        $this->set('sponser',$name);
        $this->set('ref_code',$ref_code);
		$this->viewBuilder()->layout(false);
		$user  = $this->Users->newEntity();
		$this->set('user',$user);
    }
	
	
	 public function hometest($ref_code=null)
    { 

		$this->set('title','Home');
        $name = '';
        $user = [];
		$this->loadModel('Users');
		$referExist = ($ref_code!='') ? 1: 0;
		$this->set('referExist',$referExist);

		$ref_code = ($ref_code!='') ? $ref_code : "";

		if($ref_code != null)
        {

            $user = $this->Users->find('all',['fields'=>['id','name'],'conditions'=>['referral_code'=>$ref_code]])->hydrate(false)->first();

            if(!empty($user))
            {
                $name = $user['name'];
            }

        }
        $this->loadModel('ContactUs');
		$contactus = $this->ContactUs->newEntity();
		if ($this->request->is(['post' ,'put']))
		{
			//pr($this->request->data);die;
			$contactus = $this->ContactUs->patchEntity($contactus, $this->request->data);
			if ($newNetwork = $this->ContactUs->save($contactus)) {
				$this->Flash->success(__('Successfully submitted !!'));
				return $this->redirect(['controller'=>'pages','action' =>'contact']);
			}else $this->Flash->error(__('Someting went wrong !!'));
		}


		$this->set('contactus',$contactus);
        $this->set('sponser',$name);
        $this->set('ref_code',$ref_code);
		$this->viewBuilder()->layout(false);
		$user  = $this->Users->newEntity();
		$this->set('user',$user);
		
    }

    
        public function home2($ref_code=null)
    { 

		$this->set('title','Home');
        $name = '';
        $user = [];
		$this->loadModel('Users');
		$referExist = ($ref_code!='') ? 1: 0;
		$this->set('referExist',$referExist);
		
		$ref_code = ($ref_code!='') ? $ref_code : "";
		
		if($ref_code != null)
        {
            
            $user = $this->Users->find('all',['fields'=>['id','name'],'conditions'=>['referral_code'=>$ref_code]])->hydrate(false)->first();

            if(!empty($user))
            {
                $name = $user['name'];
            }

        }
        $this->loadModel('ContactUs');
		$contactus = $this->ContactUs->newEntity();
		if ($this->request->is(['post' ,'put'])) 
		{
			//pr($this->request->data);die;
			$contactus = $this->ContactUs->patchEntity($contactus, $this->request->data);
			if ($newNetwork = $this->ContactUs->save($contactus)) {
				$this->Flash->success(__('Successfully submitted !!'));
				return $this->redirect(['controller'=>'pages','action' =>'contact']);
			}else $this->Flash->error(__('Someting went wrong !!'));
		}	
		
		
		$this->set('contactus',$contactus);
        $this->set('sponser',$name);
        $this->set('ref_code',$ref_code);
		$this->viewBuilder()->layout(false);
		$user  = $this->Users->newEntity();
		$this->set('user',$user);
    }
	
    public function home3($ref_code=null)
    { 

		$this->set('title','Home');
        $name = '';
        $user = [];
		$this->loadModel('Users');
		$referExist = ($ref_code!='') ? 1: 0;
		$this->set('referExist',$referExist);
		
		$ref_code = ($ref_code!='') ? $ref_code : "";
		
		if($ref_code != null)
        {
            
            $user = $this->Users->find('all',['fields'=>['id','name'],'conditions'=>['referral_code'=>$ref_code]])->hydrate(false)->first();

            if(!empty($user))
            {
                $name = $user['name'];
            }

        }
        $this->loadModel('ContactUs');
		$contactus = $this->ContactUs->newEntity();
		if ($this->request->is(['post' ,'put'])) 
		{
			//pr($this->request->data);die;
			$contactus = $this->ContactUs->patchEntity($contactus, $this->request->data);
			if ($newNetwork = $this->ContactUs->save($contactus)) {
				$this->Flash->success(__('Successfully submitted !!'));
				return $this->redirect(['controller'=>'pages','action' =>'contact']);
			}else $this->Flash->error(__('Someting went wrong !!'));
		}	
		
		
		$this->set('contactus',$contactus);
        $this->set('sponser',$name);
        $this->set('ref_code',$ref_code);
		$this->viewBuilder()->layout(false);
		$user  = $this->Users->newEntity();
		$this->set('user',$user);
    }	
	
	
	/*
	Demo Url - amaxgoldcoin.com/returndata?secret=198085554ZzsMLGKe162CfA5EcG6j1512917645&address=14h9zCRZCYqwLJYrcNcZd8dXdd28iLonJP&transaction_hash=1f3f52c87a4c7526e8f3fe4bc50a247b17753937f2eff8eab84d9b49f18d2d68&value=60810&confirmations=2
	*/
	public function returndata(){
		$this->loadModel('Agctransactions');
		$this->loadModel('Token');
		$this->loadModel('LandingProgram');
		$this->loadModel('Referal');
		$totalAMXCoin = $this->Token->find('all')->hydrate(false)->first();
		
		
		$getUrl = json_encode($_GET);
		$getArr = json_decode($getUrl,true);
		$secret = $getArr['secret'];
		$trans_id = $getArr['trans_id'];
		$getAddress = $getArr['address'];
		$transaction_hash = $getArr['transaction_hash'];
		$satoshiValue = $getArr['value'];
		$confirmations = $getArr['confirmations'];
		$getValueInBtc = $satoshiValue/100000000; 
		$getInAgc = $getValueInBtc/$totalAMXCoin['btc_value'];
			
		$findWalletAddess = $this->Agctransactions->find('all',['conditions'=>['wallet_address' => $getAddress,'status' =>'pending']])->hydrate(false)->toArray();
		if(!empty($findWalletAddess)) {
			
		$findtransactionId = $this->Agctransactions->find('all',['conditions'=>['wallet_address' => $getAddress]])->hydrate(false)->first();	
		//$transactionId = $findtransactionId['trans_id']; 	
		$transactionId = $trans_id; 	
			//file_put_contents("/returndata.txt",print_r($_GET)); die;
		$tablename = TableRegistry::get("Agctransactions");
		$query = $tablename->query();
		$result = $query->update()
				->set(['btc_transaction' => $transaction_hash,'btc_coins'=>$getValueInBtc,'agc_coins'=>$getInAgc,'status'=>'completed'])
				->where(['id' => $trans_id])
				->execute();
							
							
							
		/*Bonus Calculation*/					
		$cuDate = date('Y-m-d H:i:s');
		$landing_arr = $this->LandingProgram->find('all',array('conditions'=>array('from_date <='=>$cuDate,'to_date >='=>$cuDate)))->hydrate(false)->first();
		$bonus_token_percent = $landing_arr['bonus_token_percent'];
		$bonusAgcCoin = ($getInAgc*$bonus_token_percent)/100;
		$bonusBtcCoin = $bonusAgcCoin*$totalAMXCoin['btc_value']; 	
		
		$tablename = TableRegistry::get("Agctransactions");
		$query = $tablename->query();
		$result = $query->update()
				->set(['btc_coins'=>$bonusBtcCoin,'agc_coins'=>$bonusAgcCoin,'status'=>'completed'])
				->where(['trans_id' => $transactionId,'trans_type'=>'bonus'])
				->execute();					
		
		
		/*Referral Calculation*/
		$getReferalPercent  = $this->Referal->find('all')->hydrate(false)->first();
		$referalPercent  = $getReferalPercent['referal_percent'];
		$referalAgcCoin =($getInAgc*$referalPercent)/100;
		$referalBtcCoin = $referalAgcCoin*$totalAMXCoin['btc_value'];
		
		$tablename = TableRegistry::get("Agctransactions");
		$query = $tablename->query();
		$result = $query->update()
				->set(['btc_coins'=>$referalBtcCoin,'agc_coins'=>$referalAgcCoin,'status'=>'completed'])
				->where(['trans_id' => $transactionId,'trans_type'=>'referral'])
				->execute();					
		}	
		
							
		die;					
		
	}
	
	
	public function subscription(){
		$this->viewBuilder()->layout('login');
		$this->set('title','HC : Subscription');
		if ($this->request->is('post')) 
		{
			$data = $this->request->data;
			$toEmail = "hedgeconnect@gmail.com";
			//$toEmail = "mighty.ambrish@gmail.com";
			$email = new Email('default');
			$email->viewVars(['data'=>$data]);
			$email->from([$this->setting['email_from']] )
				->to($toEmail)
				->subject('New User Subscriped to HC.')
				->emailFormat('html')
				->template('subscription')
				->send();
		}
	}
	
	
	public function contact(){
		$this->viewBuilder()->layout('ajax');
		$this->set('title','HC : Contact Success');
		if ($this->request->is('post')) 
		{
			$captchaResp = $this->request->data['g-recaptcha-response'];
			if(empty($captchaResp)){
				$this->Flash->error(__('please verify captcha.'));
				return $this->redirect('/#contact');
			}
			$data = $this->request->data;
			$toEmail = "hedgeconnect@gmail.com";
			$email = new Email('default');
			$email->viewVars(['data'=>$data]);
			$email->from([$this->setting['email_from']] )
				->to($toEmail)
				->subject($data['name']." Contact on Hedge Connect")
				->emailFormat('html')
				->template('contact')
				->send();
			$this->Flash->success(__('Thanks For Contact Us.'));
            return $this->redirect('/#contact');	
		}
	}
	public function faq(){
		$this->viewBuilder()->layout('ajax');
		$this->set('title','FAQ');
		
	}
	
	public function policy(){
		$this->viewBuilder()->layout('ajax');
		$this->set('title','Policy');

	}	
	public function termsandconditions(){
		$this->viewBuilder()->layout('ajax');
		$this->set('title','termsandconditions');

	}	
	public function crosschainrecovery(){
		$this->viewBuilder()->layout('ajax');
		$this->set('title','crosschainrecovery');

	}	
	public function amlpolicy(){
		$this->viewBuilder()->layout('ajax');
		$this->set('title','amlpolicy');

	}	

	
	
	public function changebtcstatus(){
		
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Transactionlog');
		
		$updated_date = date("Y-m-d H:i:s");
		$cudate = $updated_date;
		file_put_contents("returndata.txt", json_encode($_POST,true).$updated_date,FILE_APPEND);
	
	
		$ipn_version    = $_POST['ipn_version']; 
		$ipn_id         = $_POST['ipn_id']; 
		$ipn_mode       = $_POST['ipn_mode']; 
		$merchant       = $_POST['merchant']; 
		$ipn_type       = $_POST['ipn_type']; 
		$address        = $_POST['address'];
		$txn_id         = $_POST['txn_id']; 
		$status         = $_POST['status'];
		$currency         = $_POST['currency'];
		$status_text    = $_POST['status_text']; 
		
		$amount         = $_POST['amount']; 
		
		$getTransNew = $this->Transactionlog->newEntity();
		$getTransNewData = $this->Transactionlog->patchEntity($getTransNew,$_POST);
		$updateBtcWallet = $this->Transactionlog->save($getTransNewData);
		
		$findAgcTrans = $this->Transactions->find('all',['conditions'=>['wallet_address'=>$address]])->hydrate(false)->first();
		$currentUser = $findAgcTrans['user_id'];
		
		//if(!empty($findAgcTrans) && ($status==100 || $status==2) && $currency=="ETH") {
			if(!empty($findAgcTrans) && ($status==100 || $status==2)) {
			// if amount aleady existed
			if(!empty($findAgcTrans['coin_amount']) && $findAgcTrans['coin_amount']>0){
				
				$userId = $findAgcTrans['user_id'];
				$cryptocoinId = $findAgcTrans['cryptocoin_id'];
				$getRealAddress = $address;
				
				$newInsertArr = [];
				$newInsertArr['user_id'] = $userId;
				$newInsertArr['cryptocoin_id'] = $cryptocoinId;
				$newInsertArr['wallet_address'] = $getRealAddress;
				$newInsertArr['tx_type'] = 'purchase';
				
				$newInsertArr['tx_id'] = $txn_id;
				$newInsertArr['coin_amount'] = $amount;
				$newInsertArr['status'] = 'completed';
				$newInsertArr['created'] = $cudate;
				$newInsertArr['updated'] = $cudate;
				
				$findTxId = $this->Transactions->find('all',['conditions'=>['tx_id'=>$txn_id]])->hydrate(false)->first();
				if(!empty($findTxId)){
					$newInsertArr['tx_id'] = $txn_id."_".$ipn_type;
				}
				$getTransNew = $this->Transactions->newEntity();
				$getTransNewData=$this->Transactions->patchEntity($getTransNew,$newInsertArr);
				$updateBtcWallet = $this->Transactions->save($getTransNewData);	
				
			}
			else {
				
				$getAgcId = $findAgcTrans['id'];

				$newInsertArr = [];
				
				$newInsertArr['tx_id'] = $txn_id;
				$newInsertArr['coin_amount'] = $amount;
				$newInsertArr['status'] = 'completed';
				$newInsertArr['updated'] = $cudate;
				
				$findTxId = $this->Transactions->find('all',['conditions'=>['tx_id'=>$txn_id]])->hydrate(false)->first();
				if(!empty($findTxId)){
					$newInsertArr['tx_id'] = $txn_id."_".$ipn_type;
				}
				
				$getTransNew = $this->Transactions->get($getAgcId);
				$getTransNewData=$this->Transactions->patchEntity($getTransNew,$newInsertArr);
				$updateBtcWallet = $this->Transactions->save($getTransNewData);		
			}
		}
		die;
	}
	
	
	public function comparebtc(){
		$this->loadModel('Users');
		$this->loadModel('Agctransactions');
		//$this->Users->find("all",array('conditions'=>array('trans_type'=>"credit","btc_coins >"=>0.00000000,'user.btc_address_status'=>'archieve'),'contain'=>['user'],"limit"=>1120))->all()->toArray();
		$getUsers = $this->Users->find("all",['conditions'=>['btc_address !='=>'','notification_id !='=>''],'contain'=>['agctransactions'],'limit'=>5])->hydrate(false)->all()->toArray();
		if(!empty($getUsers)){
			foreach($getUsers as $singleUser){
				print_r($singleUser);
			}
		} die;
	}
	
	
	public function getcurrentprice(){
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d');
		$firstCoinId = 2;
		//$secondCoinId = $currencyId;
		$getPrice = 0;
		if ($this->request->is(['post','put'])) {
			$currency = $this->request->data['coin'];
			if(empty($currency)){
				echo json_encode(['success'=>false,"message"=>"Invalid Coin","data"=>""]); die;
			}
			
			$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
			$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>strtoupper($currency)]])->hydrate(false)->first();
			if(empty($getSecondCoinDetail)){
				echo json_encode(['success'=>false,"message"=>"Invalid Coin","data"=>""]); die;
			}
			$secondCoinId = $getSecondCoinDetail['id'];
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
			$returnArr['success'] = true;
			$returnArr['message'] = "Coin Price";
			$returnArr['data'][strtolower($currency).'_per_ntr'] =  number_format($getPrice,8);
			//$returnArr[strtolower($currency).'_per_ntr'] = number_format($getPrice,8);
			//$returnArr['ntr_per_usd'] = number_format($getPrice*$baseCoinPriceInUsd,8);
			echo json_encode($returnArr); die;
		}
		else {
			echo json_encode(['success'=>false,"message"=>"Invalid Request","data"=>""]); die;
		}
		
	}
	
	public function getcurrentpricehome(){
		 
		$this->loadModel('Cryptocoin');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d');
		$firstCoinId = 14;
		//$secondCoinId = $currencyId;
		$currencyArr = ['btc','eth','ltc','xrp','bch'];
		$getPrice = 0;
		if ($this->request->is(['post','put'])) {
			/* $currency = $this->request->data['coin'];
			if(empty($currency)){
				echo json_encode(['success'=>false,"message"=>"Invalid Coin","data"=>""]); die;
			} */
			
			$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
			$currenPriceArr = [];
			foreach($currencyArr as $currency) {
				$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>strtoupper($currency)]])->hydrate(false)->first();
				
				/* if(empty($getSecondCoinDetail)){
					echo json_encode(['success'=>false,"message"=>"Invalid Coin","data"=>""]); die;
				} */
				$secondCoinId = $getSecondCoinDetail['id'];
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
				else {
					$getPrice = $getSecondCoinDetail['usd_price'];
				}
				$currenPriceArr[strtolower($currency).'_per_inr']= 	number_format($getPrice,8);		
			}		
			
			$returnArr = [];
			$returnArr['success'] = true;
			$returnArr['message'] = "Coin Price";
			$returnArr['data'] =  $currenPriceArr;
			//$returnArr[strtolower($currency).'_per_ntr'] = number_format($getPrice,8);
			//$returnArr['ntr_per_usd'] = number_format($getPrice*$baseCoinPriceInUsd,8);
			echo json_encode($returnArr); die;
		}
		else {
			echo json_encode(['success'=>false,"message"=>"Invalid Request","data"=>""]); die;
		}
		
	}
	
	public function getallcurrentprice() {
		$this->loadModel('Cryptocoin');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d');
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
		
		$firstCoinId = 2;
		$secondCoinId = 4;
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
		$returnArr1 = [];
		//$returnArr1['admc_currentprice_eth'] = $getPrice;
		$returnArr['admc_currentprice_usd'] = $getPrice*$baseCoinPriceInUsd;
		 
		 $firstCoinId = 2;
		$secondCoinId = 3;
		$getPrice = 0;
		
		
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['id'=>$firstCoinId]])->hydrate(false)->first();
		$baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];
		 											  
		$returnArr2 = [];
		//$returnArr2['eth_currentprice_eth'] = $baseCoinPriceInUsd;
		$returnArr['eth_currentprice_usd'] = $baseCoinPriceInUsd;
		 
		$returnArr['usd_currentprice_usd'] = 1;
		 
		
		
		echo json_encode($returnArr); die;
		
	}
	
	public function getcurrentpriceusd(){
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d');
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
		$returnArr['currentprice_eth'] = $getPrice;
		$returnArr['currentprice_usd'] = 1;
		echo json_encode($returnArr); die;
	}
	
	public function updatewithrawalstatuseth(){
		
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d H:i:s');
		if ($this->request->is(['post','put'])) {
			$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlf";
			$getSecurityId = $this->request->data['get_security_id'];
			$getAmount = $this->request->data['amount'];
			$txId = $this->request->data['tx_id'];
			if($securityId != $getSecurityId){
				
				$returnArr = [];
				$returnArr['success'] = "false";
				$returnArr['message'] = "Invalid Security Code";
				echo json_encode($returnArr); die;	
			}
			else {
				
				$update = $this->Transactions->find('all',['conditions'=>['withdrawal_tx_id'=>$txId,'withdrawal_send'=>'N','cryptocoin_id'=>'2']])->first();
				if(!empty($update)){
					$myAmount =$update['withdrawal_amount_in_usd'];
					
					$myAmount = (string)$myAmount;
					$getAmount = (string)$getAmount;
					
					if($myAmount==$getAmount) {
						$update = $this->Transactions->patchEntity($update,['withdrawal_send'=>'Y','updated'=>$cudate]);
						$update = $this->Transactions->save($update);
						
						$returnArr = [];
						$returnArr['success'] = "true";
						$returnArr['message'] = "withdrawal status updated successfully";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
					else {
						$returnArr = [];
						$returnArr['success'] = "false";
						$returnArr['message'] = "Transaction Id or Amount is different";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
				}
				else {
					$returnArr = [];
					$returnArr['success'] = "false";
					$returnArr['message'] = "Invalid Transaction Id Or Withdrawal Already Completed";
					echo json_encode($returnArr); die;
				}
				
			}
			
		}		
		$returnArr = [];
		$returnArr['currentprice'] = $getPrice;
		echo json_encode($returnArr); die;
	}
	
	public function updatewithrawalstatus(){
		
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d H:i:s');
		if ($this->request->is(['post','put'])) {
			$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlf";
			$getSecurityId = $this->request->data['get_security_id'];
			$getAmount = $this->request->data['amount'];
			$txId = $this->request->data['tx_id'];
			if($securityId != $getSecurityId){
				
				$returnArr = [];
				$returnArr['success'] = "false";
				$returnArr['message'] = "Invalid Security Code";
				echo json_encode($returnArr); die;	
			}
			else {
				
				$update = $this->Transactions->find('all',['conditions'=>['withdrawal_tx_id'=>$txId,'withdrawal_send'=>'N','cryptocoin_id'=>'3']])->first();
				if(!empty($update)){
					$myAmount =$update['withdrawal_amount_in_usd'];
					
					$myAmount = (string)$myAmount;
					$getAmount = (string)$getAmount;
					
					if($myAmount==$getAmount) {
						$update = $this->Transactions->patchEntity($update,['withdrawal_send'=>'Y','updated'=>$cudate]);
						$update = $this->Transactions->save($update);
						
						$returnArr = [];
						$returnArr['success'] = "true";
						$returnArr['message'] = "withdrawal status updated successfully";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
					else {
						$returnArr = [];
						$returnArr['success'] = "false";
						$returnArr['message'] = "Transaction Id or Amount is different";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
				}
				else {
					$returnArr = [];
					$returnArr['success'] = "false";
					$returnArr['message'] = "Invalid Transaction Id Or Withdrawal Already Completed";
					echo json_encode($returnArr); die;
				}
				
			}
			
		}		
		$returnArr = [];
		$returnArr['currentprice'] = $getPrice;
		echo json_encode($returnArr); die;
	}	
	
	public function validatetokens(){
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d H:i:s');
		if ($this->request->is(['post','put'])) {
			$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlf";
			$getSecurityId = $this->request->data['get_security_id'];
			$getAmount = $this->request->data['amount'];
			$txId = $this->request->data['tx_id'];
			$coinId = $this->request->data['cryptocoin_id'];
			if($securityId != $getSecurityId){
				$returnArr = [];
				$returnArr['success'] = "false";
				$returnArr['message'] = "Invalid Security Code";
				echo json_encode($returnArr); die;	
			}
			else {
				
				$update = $this->Transactions->find('all',['conditions'=>['withdrawal_tx_id'=>$txId,'withdrawal_send'=>'N','cryptocoin_id'=>$coinId]])->first();
				if(!empty($update)){
					$myAmount =$update['withdrawal_amount_in_usd'];
					
					$myAmount = (string)$myAmount;
					$getAmount = (string)$getAmount;
					
					if($myAmount==$getAmount) {
						$update = $this->Transactions->patchEntity($update,['withdrawal_send'=>'Y','updated'=>$cudate]);
						$update = $this->Transactions->save($update);
						
						$returnArr = [];
						$returnArr['success'] = "true";
						$returnArr['message'] = "withdrawal status updated successfully";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
					else {
						$returnArr = [];
						$returnArr['success'] = "false";
						$returnArr['message'] = "Transaction Id or Amount is different";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
				}
				else {
					$returnArr = [];
					$returnArr['success'] = "false";
					$returnArr['message'] = "Invalid Transaction Id Or Withdrawal Already Completed";
					echo json_encode($returnArr); die;
				}
			}
		}		
		$returnArr = [];
		$returnArr['currentprice'] = $getPrice;
		echo json_encode($returnArr); die;
	}
	
	public function ethvalidatetokens(){
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d H:i:s');
		if ($this->request->is(['post','put'])) {
			$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlf";
			$getSecurityId = $this->request->data['get_security_id'];
			$getAmount = $this->request->data['amount'];
			$txId = $this->request->data['tx_id'];
			$coinId = $this->request->data['cryptocoin_id'];
			if($securityId != $getSecurityId){
				$returnArr = [];
				$returnArr['success'] = "false";
				$returnArr['message'] = "Invalid Security Code";
				echo json_encode($returnArr); die;	
			}
			else {
				
				$update = $this->Transactions->find('all',['conditions'=>['withdrawal_tx_id'=>$txId,'withdrawal_send'=>'N','cryptocoin_id'=>$coinId]])->first();
				if(!empty($update)){
					$myAmount =$update['coin_amount'];
					
					$myAmount = (string)abs($myAmount);
					$getAmount = (string)abs($getAmount);
					
					if($myAmount==$getAmount) {
						$update = $this->Transactions->patchEntity($update,['withdrawal_send'=>'Y','updated'=>$cudate]);
						$update = $this->Transactions->save($update);
						
						$returnArr = [];
						$returnArr['success'] = "true";
						$returnArr['message'] = "withdrawal status updated successfully";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
					 else {
						$returnArr = [];
						$returnArr['success'] = "false";
						$returnArr['message'] = "Transaction Id or Amount is different";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
				}
				else {
					$returnArr = [];
					$returnArr['success'] = "false";
					$returnArr['message'] = "Invalid Transaction Id Or Withdrawal Already Completed";
					echo json_encode($returnArr); die;
				}
			}
		}		
		$returnArr = [];
		$returnArr['currentprice'] = $getPrice;
		echo json_encode($returnArr); die;
	}
	
	
	public function checkvalidatetokens(){
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d H:i:s');
		if ($this->request->is(['post','put'])) {
			$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlf";
			$getSecurityId = $this->request->data['get_security_id'];
			$getAmount = $this->request->data['amount'];
			$txId = $this->request->data['tx_id'];
			$coinId = $this->request->data['cryptocoin_id'];
			if($securityId != $getSecurityId){
				$returnArr = [];
				$returnArr['success'] = "false";
				$returnArr['message'] = "Invalid Security Code";
				echo json_encode($returnArr); die;	
			}
			else {
				
				$update = $this->Transactions->find('all',['conditions'=>['withdrawal_tx_id'=>$txId,'withdrawal_send'=>'N','cryptocoin_id'=>$coinId]])->first();
				if(!empty($update)){
					$myAmount =$update['withdrawal_amount_in_usd'];
					
					$myAmount = (string)$myAmount;
					$getAmount = (string)$getAmount;
					
					if($myAmount==$getAmount) {
						/* $update = $this->Transactions->patchEntity($update,['withdrawal_send'=>'Y','updated'=>$cudate]);
						$update = $this->Transactions->save($update); */
						
						$returnArr = [];
						$returnArr['success'] = "true";
						$returnArr['message'] = "withdrawal status updated successfully";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
					else {
						$returnArr = [];
						$returnArr['success'] = "false";
						$returnArr['message'] = "Transaction Id or Amount is different";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
				}
				else {
					$returnArr = [];
					$returnArr['success'] = "false";
					$returnArr['message'] = "Invalid Transaction Id Or Withdrawal Already Completed";
					echo json_encode($returnArr); die;
				}
			}
		}		
		$returnArr = [];
		$returnArr['currentprice'] = $getPrice;
		echo json_encode($returnArr); die;
	}
	
	
	
	
	public function updatevalidatetokens(){
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d H:i:s');
		if ($this->request->is(['post','put'])) {
			$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlf";
			$getSecurityId = $this->request->data['get_security_id'];
			$getAmount = $this->request->data['amount'];
			$txId = $this->request->data['tx_id'];
			$coinId = $this->request->data['cryptocoin_id'];
			if($securityId != $getSecurityId){
				$returnArr = [];
				$returnArr['success'] = "false";
				$returnArr['message'] = "Invalid Security Code";
				echo json_encode($returnArr); die;	
			}
			else {
				
				$update = $this->Transactions->find('all',['conditions'=>['withdrawal_tx_id'=>$txId,'withdrawal_send'=>'N','cryptocoin_id'=>$coinId]])->first();
				if(!empty($update)){
					$myAmount =$update['withdrawal_amount_in_usd'];
					
					$myAmount = (string)$myAmount;
					$getAmount = (string)$getAmount;
					
					if($myAmount==$getAmount) {
						$update = $this->Transactions->patchEntity($update,['withdrawal_send'=>'Y','updated'=>$cudate]);
						$update = $this->Transactions->save($update);
						
						$returnArr = [];
						$returnArr['success'] = "true";
						$returnArr['message'] = "withdrawal status updated successfully";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
					else {
						$returnArr = [];
						$returnArr['success'] = "false";
						$returnArr['message'] = "Transaction Id or Amount is different";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
				}
				else {
					$returnArr = [];
					$returnArr['success'] = "false";
					$returnArr['message'] = "Invalid Transaction Id Or Withdrawal Already Completed";
					echo json_encode($returnArr); die;
				}
			}
		}		
		$returnArr = [];
		$returnArr['currentprice'] = $getPrice;
		echo json_encode($returnArr); die;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function updatewithrawalstatususd(){
		
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d H:i:s');
		if ($this->request->is(['post','put'])) {
			$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlf";
			$getSecurityId = $this->request->data['get_security_id'];
			$getAmount = $this->request->data['amount'];
			$txId = $this->request->data['tx_id'];
			if($securityId != $getSecurityId){
				
				$returnArr = [];
				$returnArr['success'] = "false";
				$returnArr['message'] = "Invalid Security Code";
				echo json_encode($returnArr); die;	
			}
			else {
				
				$update = $this->Transactions->find('all',['conditions'=>['withdrawal_tx_id'=>$txId,'withdrawal_send'=>'N','cryptocoin_id'=>'5']])->first();
				if(!empty($update)){
					$myAmount =$update['withdrawal_amount_in_usd'];
					
					$myAmount = (string)$myAmount;
					$getAmount = (string)$getAmount;
					
					if($myAmount==$getAmount) {
						$update = $this->Transactions->patchEntity($update,['withdrawal_send'=>'Y','updated'=>$cudate]);
						$update = $this->Transactions->save($update);
						
						$returnArr = [];
						$returnArr['success'] = "true";
						$returnArr['message'] = "withdrawal status updated successfully";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
					else {
						$returnArr = [];
						$returnArr['success'] = "false";
						$returnArr['message'] = "Transaction Id or Amount is different";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
				}
				else {
					$returnArr = [];
					$returnArr['success'] = "false";
					$returnArr['message'] = "Invalid Transaction Id Or Withdrawal Already Completed";
					echo json_encode($returnArr); die;
				}
				
			}
			
		}		
		$returnArr = [];
		$returnArr['currentprice'] = $getPrice;
		echo json_encode($returnArr); die;
	}	
	
	
	public function disablewithrawalstatus(){
		
		$this->loadModel('Transactions');
		$this->loadModel('ExchangeHistory');
		$cudate = date('Y-m-d H:i:s');
		if ($this->request->is(['post','put'])) {
			$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlf";
			$getSecurityId = $this->request->data['get_security_id'];
			$getAmount = $this->request->data['amount'];
			$txId = $this->request->data['tx_id'];
			if($securityId != $getSecurityId){
				
				$returnArr = [];
				$returnArr['success'] = "false";
				$returnArr['message'] = "Invalid Security Code";
				echo json_encode($returnArr); die;	
			}
			else {
				
				$update = $this->Transactions->find('all',['conditions'=>['withdrawal_tx_id'=>$txId]])->first();
				if(!empty($update)){
					$myAmount =$update['withdrawal_amount_in_usd'];
					
					$myAmount = (string)$myAmount;
					$getAmount = (string)$getAmount;
					
					if($myAmount==$getAmount) {
						$update = $this->Transactions->patchEntity($update,['withdrawal_send'=>'N','updated'=>$cudate]);
						$update = $this->Transactions->save($update);
						
						$returnArr = [];
						$returnArr['success'] = "true";
						$returnArr['message'] = "withdrawal status updated successfully";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
					else {
						$returnArr = [];
						$returnArr['success'] = "false";
						$returnArr['message'] = "Transaction Id or Amount is different";
						$returnArr['amount'] = $update['withdrawal_amount_in_usd'];
						echo json_encode($returnArr); die;
					}
				}
				else {
					$returnArr = [];
					$returnArr['success'] = "false";
					$returnArr['message'] = "Invalid Transaction Id Or Withdrawal Already Completed";
					echo json_encode($returnArr); die;
				}
				
			}
			
		}		
		$returnArr = [];
		$returnArr['currentprice'] = $getPrice;
		echo json_encode($returnArr); die;
	}	
	
	
	
	
	public function ethwithdrawalstatus(){
	
		$this->loadModel('Transactionlog');
		$this->loadModel("Transactions");
		$updated_date = date("Y-m-d H:i:s");
		
		$cudate = $updated_date;
		file_put_contents("eth_withdrawal_status.txt", json_encode($_POST,true).$updated_date,FILE_APPEND);
	
	
		$ipn_version    = $_POST['ipn_version']; 
		$ipn_id         = $_POST['ipn_id']; 
		$ipn_mode       = $_POST['ipn_mode']; 
		$merchant       = $_POST['merchant']; 
		$ipn_type       = $_POST['ipn_type']; 
		$address        = $_POST['address'];
		$txn_id         = $_POST['txn_id']; 
		$status         = $_POST['status'];
		$status_text    = $_POST['status_text']; 
		$withdrawalId    = $_POST['id']; 
		$amount         = $_POST['amount'];  
		$_POST['get_id'] = $_POST['id'];
		
		$getTransNew = $this->Transactionlog->newEntity();
		$getTransNewData = $this->Transactionlog->patchEntity($getTransNew,$_POST);
		$updateBtcWallet = $this->Transactionlog->save($getTransNewData);
		
		$getTxDetail = $this->Transactions->find('all',['conditions'=>['withdrawal_id'=>$withdrawalId]])->first();
		$getTxDetail = $this->Transactions->patchEntity($getTxDetail,['withdrawal_send'=>'Y',
																	  'withdrawal_tx_id'=>$txn_id,
																	  'tx_id'=>$txn_id
																	 ]);
		$getTxDetail = $this->Transactions->save($getTxDetail);
		die;
	}
	
	
	
	
	
	public function btccallback(){
		
		$this->loadModel('Coincallback');
		$this->loadModel('Transactions');
		$this->loadModel('PrimaryWallet');
		$this->loadModel('Users');
		$updated_date = date("Y-m-d H:i:s");
		
		$cudate = $updated_date;
		file_put_contents("btccallback.log", json_encode($_POST,true).$updated_date,FILE_APPEND); 
		 
		$decodePostData = $_POST;
		if(!isset($decodePostData['txid']) || empty($decodePostData['txid'])){
			die('Tx Id Not Found');
		} 
		//print_r($decodePostData); die;
		$txId = trim($decodePostData['txid']);
		
			$callBackObj = $this->Coincallback->newEntity();
			$callBackObj = $this->Coincallback->patchEntity($callBackObj,['tx_id'=>$txId]);
			$callBackObj = $this->Coincallback->save($callBackObj);
			
			 
		
				//get transaction detail =  
				
				//$getBtcTxDetail = $this->Users->getBtcTxDetail($txId);
				$getBtcTxDetail = $this->Users->getBtcTxDetailFronNode($txId);
			
				//file_put_contents("txdetail.log", $getBtcTxDetail.$updated_date,FILE_APPEND); 
				if(!empty($getBtcTxDetail)){
					
					$getBtcTxDetailDecode = json_decode($getBtcTxDetail,true);
					if(!empty($getBtcTxDetailDecode['result']['details'])){
						
						foreach($getBtcTxDetailDecode['result']['details'] as $getBtcTxDetailDecodeSingle) {
							
							
							$getTxType = $getBtcTxDetailDecodeSingle['category'];
							if($getTxType=="receive"){
								$getAddress = $getBtcTxDetailDecodeSingle['address'];
								
								$userId = '';
								$getUserDetail = $this->Users->find('all',['conditions'=>['btc_address'=>$getAddress]])->hydrate(false)->first();
								if(empty($getUserDetail)){
									continue;
								}
								$checkTxIdCnt  =  $this->PrimaryWallet->find('all',['conditions'=>['tx_id'=>$txId,'type'=>'deposit','wallet_address'=>$getAddress]])->hydrate(false)->count();
								if($checkTxIdCnt>0){
									continue;
								}
								$userId = $getUserDetail['id'];
								$coinAmount = $getBtcTxDetailDecodeSingle['amount'];
								$cryptocoinId = 1;
						
								$newInsertArr = [];
								$newInsertArr['user_id'] = $userId;
								$newInsertArr['cryptocoin_id'] = $cryptocoinId;
								$newInsertArr['wallet_address'] = $getAddress;
								$newInsertArr['type'] = 'deposit';
								
								$newInsertArr['tx_id'] = $txId;
								$newInsertArr['amount'] = abs($coinAmount);
								$newInsertArr['status'] = 'completed';
								$newInsertArr['created'] = $cudate;
								$newInsertArr['updated'] = $cudate; 
						 		
								$transactionsArr1[]=$newInsertArr1;
								$fees=abs($coinAmount)*0.02/100;
								$newInsertArr1 = [];
								$newInsertArr1['user_id'] = 1;
								$newInsertArr1['cryptocoin_id'] = $cryptocoinId;
								$newInsertArr['type'] = 'deposit';
								//$newInsertArr1['fees'] = 'transaction_fee';
								$newInsertArr1['amount'] = $fees;
								$newInsertArr1['status'] = 'completed';
								$newInsertArr1['created'] = $cudate;
								$newInsertArr1['updated'] = $cudate; 
						 		
								$transactionsArr1[]=$newInsertArr1;





							}
						}
					}
					if(!empty($transactionsArr)){
						/* $entities = $this->PrimaryWallet->newEntities($transactionsArr);
						$result = $this->PrimaryWallet->saveMany($entities); */
						
						$entities1 = $this->PrimaryWallet->newEntities($transactionsArr1);
						$result1 = $this->PrimaryWallet->saveMany($entities1);

						echo "save"; die;
					}
					
				}
		die;
	}	
	
		
	
	/*
	Call back from secondary daemon
	*/
/* 	public function btccallback(){
		
		$this->loadModel('Coincallback');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$updated_date = date("Y-m-d H:i:s");
		
		$cudate = $updated_date;
		file_put_contents("coincallback.log", json_encode($_POST,true).$updated_date,FILE_APPEND); 
		
		$decodePostData = $_POST;
		
		//print_r($decodePostData); die;
		$txId = trim($decodePostData['txid']);
		$countConfirmation =  $this->Coincallback->find('all',['conditions'=>['tx_id'=>$txId]])->hydrate(false)->count();
		//echo $txId;
		
		if($countConfirmation=1){ 
			
			$callBackObj = $this->Coincallback->newEntity();
			$callBackObj = $this->Coincallback->patchEntity($callBackObj,['tx_id'=>$txId]);
			$callBackObj = $this->Coincallback->save($callBackObj);
			
			
			$checkTxIdCnt  =  $this->Transactions->find('all',['conditions'=>['tx_id'=>$txId."_deposit",'tx_type'=>'purchase']])->hydrate(false)->count();
			if($checkTxIdCnt==0){
				//get transaction detail = 
				
				$getBtcTxDetail = $this->Users->getBtcTxDetail($txId);
			
				file_put_contents("txdetail.log", $getBtcTxDetail.$updated_date,FILE_APPEND); 
				if(!empty($getBtcTxDetail)){
					
					$getBtcTxDetailDecode = json_decode($getBtcTxDetail,true);
					
					$getVout = $getBtcTxDetailDecode['outputs'];
					foreach($getVout as $getVoutSingle){
						
						if(!isset($getVoutSingle['addresses'])){
							continue;
						}
						
						$getAddress = $getVoutSingle['addresses'][0];
						$userId = '';
						$getUserDetail = $this->Users->find('all',['conditions'=>['btc_address'=>$getAddress]])->hydrate(false)->first();
						if(empty($getUserDetail)){
							continue;
						}
						$userId = $getUserDetail['id'];
						$coinAmount = $getVoutSingle['value']/100000000;
						$cryptocoinId = 1;
						
						$newInsertArr = [];
						$newInsertArr['user_id'] = $userId;
						$newInsertArr['cryptocoin_id'] = $cryptocoinId;
						$newInsertArr['wallet_address'] = $getAddress;
						$newInsertArr['tx_type'] = 'purchase';
						
						$newInsertArr['tx_id'] = $txId."_deposit";
						$newInsertArr['coin_amount'] = abs($coinAmount);
						$newInsertArr['status'] = 'completed';
						$newInsertArr['created'] = $cudate;
						$newInsertArr['updated'] = $cudate;
						
						
						$getTransNew = $this->Transactions->newEntity();
						$getTransNewData=$this->Transactions->patchEntity($getTransNew,$newInsertArr);
						$updateBtcWallet = $this->Transactions->save($getTransNewData);	
					
						
					}
					
				}
			}
			
		}
		
		
		die;
	} */
	/*
	Call back from secondary daemon
	*/
	public function coincallback(){
		
		$this->loadModel('Coincallback');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$updated_date = date("Y-m-d H:i:s");
		
		$cudate = $updated_date;
		file_put_contents("coincallback.log", json_encode($_POST,true).$updated_date,FILE_APPEND); 
		
		$decodePostData = $_POST;
		
		//print_r($decodePostData); die;
		$txId = trim($decodePostData['txid']);
		$countConfirmation =  $this->Coincallback->find('all',['conditions'=>['tx_id'=>$txId]])->hydrate(false)->count();
		//echo $txId;
		
		if($countConfirmation=1){ 
			
			$callBackObj = $this->Coincallback->newEntity();
			$callBackObj = $this->Coincallback->patchEntity($callBackObj,['tx_id'=>$txId]);
			$callBackObj = $this->Coincallback->save($callBackObj);
			
			
			$checkTxIdCnt  =  $this->Transactions->find('all',['conditions'=>['tx_id'=>$txId."_deposit",'tx_type'=>'purchase']])->hydrate(false)->count();
			if($checkTxIdCnt==0){
				//get transaction detail = 
				
				$getAdmcTxDetail = $this->Users->getBchTxDetail($txId);
			
				file_put_contents("txdetail.log", $getAdmcTxDetail.$updated_date,FILE_APPEND); 
				if(!empty($getAdmcTxDetail)){
					
					$getAdmcTxDetailDecode = json_decode($getAdmcTxDetail,true);
					
					$getVout = $getAdmcTxDetailDecode['vout'];
					foreach($getVout as $getVoutSingle){
						
						if(!isset($getVoutSingle['scriptPubKey']['cashAddrs'])){
							continue;
						}
						
						$getAddress = $getVoutSingle['scriptPubKey']['cashAddrs'][0];
						$userId = '';
						$getUserDetail = $this->Users->find('all',['conditions'=>['bch_address'=>$getAddress]])->hydrate(false)->first();
						if(empty($getUserDetail)){
							continue;
						}
						$userId = $getUserDetail['id'];
						$coinAmount = $getVoutSingle['value'];
						$cryptocoinId = 6;
						
						$newInsertArr = [];
						$newInsertArr['user_id'] = $userId;
						$newInsertArr['cryptocoin_id'] = $cryptocoinId;
						$newInsertArr['wallet_address'] = $getAddress;
						$newInsertArr['tx_type'] = 'purchase';
						
						$newInsertArr['tx_id'] = $txId."_deposit";
						$newInsertArr['coin_amount'] = abs($coinAmount);
						$newInsertArr['status'] = 'completed';
						$newInsertArr['created'] = $cudate;
						$newInsertArr['updated'] = $cudate;
						
						
						
						$getTransNew = $this->Transactions->newEntity();
						$getTransNewData=$this->Transactions->patchEntity($getTransNew,$newInsertArr);
						$updateBtcWallet = $this->Transactions->save($getTransNewData);	
						
						
					}
					
				}
			}
			
		}
		
		
		die;
	}
	
	public function support()
	{
		die;
		/*  $this->loadModel("Users");
		$row = 1;
		$getEthAddr = [];
		if (($handle = fopen("btc_address.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$getEthAddr[] = $data[0];
			
			}
			fclose($handle);
		}
		die;
		//$getEthAddr = array_reverse($getEthAddr);
		
		$getUsers = $this->Users->find("all",["fields"=>["id"],"conditions"=>["id >= "=>508]])->hydrate(false)->toArray();
		
		foreach($getUsers as $key=>$getUser){
			 $ethAddr = $getEthAddr[$key]; 
			$userId = $getUser['id'];
			if(isset($getEthAddr[$key]) && !empty($getEthAddr[$key])){
				$updateAddr = $this->Users->updateAll(["btc_address"=>$ethAddr],["id"=>$userId]);
			}
		}  */
		die;
		$this->viewBuilder()->layout(false);
		$this->loadModel('Support');
		$this->set('title','Support');
		$userId = '';
		$user  = '';
		$this->set('user',$user);	
		
		$before_image = '';
		if ($this->request->is(['post','put'])) {
			
			$issueType = filter_var($this->request->data['issue_type'], FILTER_SANITIZE_STRING);
			$issue = filter_var($this->request->data['issue'], FILTER_SANITIZE_STRING);
			$email = filter_var(strip_tags($this->request->data['email']), FILTER_SANITIZE_STRING);
			$txId = filter_var(strip_tags($this->request->data['tx_id']), FILTER_SANITIZE_STRING);
			
			$captchaResp = $this->request->data['g-recaptcha-response'];
			 if(empty($captchaResp)){
				$this->Flash->error(__('please verify captcha.'));
				return $this->redirect('/support');
			}
			
			if(empty($issueType) || empty($issue)){
				$this->Flash->error(__('* fields are required.'));
				return $this->redirect(['action' => 'support']);
			}
			
			$newImageName = '';
			if(isset($_FILES['issue_file']) && $_FILES['issue_file']['tmp_name'] !='')
			{
				
				$filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['issue_file']['name']);
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if(!in_array($ext,['jpg','png','jpeg','gif'])){
					$this->Flash->error(__('Please only upload images (gif, png, jpg).'));
					return $this->redirect(['action' => 'support']);
				}
				
				if($_FILES['issue_file']['size'] > 524280) {
					$this->Flash->error(__('file size should be maximum 5 MB.'));
					return $this->redirect(['action' => 'support']);
				}
				$filename = time().'.'.$ext;
				if ($this->uploadImage($_FILES['issue_file']['tmp_name'], $_FILES['issue_file']['type'], 'uploads/issue_file/', $filename)){
					$newImageName = $filename;
				}
			}
			
			
			$insertArr = [];
			$insertArr['issue_type'] = $issueType;
			$insertArr['issue'] = $issue;
			$insertArr['tx_id'] = $txId;
			$insertArr['user_id'] = $userId;
			$insertArr['issue_file'] = $newImageName;
			$insertArr['email'] = $email;
			
			$supportData = $this->Support->newEntity();
			$supportData = $this->Support->patchEntity($supportData, $insertArr);
			if($supportDataSave = $this->Support->save($supportData)){
				$this->Flash->success(__('Your Ticket submitted successfully. We will reply soon'));
				return $this->redirect(['action' => 'support']);
			}
			else {
				$this->Flash->error(__('Unable to submit ticket.'));
				return $this->redirect(['action' => 'support']);
			}
					
			
		}
		
	}
	
	
	
	
	
	
	
		public function validateuser()
		{
			$this->loadModel('Users');
			if($this->request->is(['post','put'])) {
			   $username = $this->request->data['username'];
			   if(!empty($username)) {
					$findUser = $this->Users->find('all',['conditions'=>['Users.username'=>$username]])->hydrate(false)->first();
					if(!empty($findUser)){
						$returnArr['success']=true;
						$returnArr['message']="valid username";
					}
					else {
						$returnArr['success']=false;
						$returnArr['message']="invalid username";
					}		
			   }
			   else {
					$returnArr['success']=false;
					$returnArr['message']="username is required";
			   }
			}
			else {
				$returnArr['success']=false;
				$returnArr['message']="invalid request";
			}
			echo json_encode($returnArr); die;
			
		}	
		
		
		public function getusercurrentbalance(){
			$this->loadModel("Transactions");
			$this->loadModel("Users");
			$this->loadModel("Cryptocoin");
			$returnArr = [];
			if ($this->request->is(['post'])) {
				$userId = $this->request->data['my_user_id'];
				
				$findUser = $this->Users->find('all',['conditions'=>['id'=>$userId]])->hydrate(false)->first();
				
				if(!empty($findUser)){
					$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1]])->hydrate(false)->toArray();
					foreach($getCoinList as $singleCoin) { 							
						$coinId = $singleCoin['id'];
						$coinName = $singleCoin['short_name'];
						$withdrawBalance = $this->Users->getLocalUserBalance($userId,$coinId);
						$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
						$reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
						$reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);
						$reserveBalance = $reserveBuyBalance + $reserveSellBalance;
						$totalWithPendingBalance = $this->Users->getUserTotalWithPendingBalance($userId,$coinId);
						
						$returnNewArr = [];
						$returnNewArr['withdrawBalance'] = ($withdrawBalance==null) ? 0 : $withdrawBalance;
						$returnNewArr['pendingBalance'] = ($pendingBalance==null) ? 0 : $pendingBalance;
						$returnNewArr['reserveBalance'] = ($reserveBalance==null) ? 0 : abs($reserveBalance);
						$returnNewArr['totalWithPendingBalance'] = ($totalWithPendingBalance==null) ? 0 : $totalWithPendingBalance;
						$mainArr[$coinName][] = $returnNewArr;
					}
					$returnArr['success']=true;
					$returnArr['message']="Current Balance";
					$returnArr['data']=$mainArr;
				}
				else {
					$returnArr['success']=false;
					$returnArr['message']="No User Found";
					$returnArr['data']="";
				}
			}
			else {
				$returnArr['success']=false;
				$returnArr['message']="Invalid Request";
				$returnArr['data']="";
			}
			echo json_encode($returnArr); die;
		}

		
	public function withdrawalapi(){
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('Transactions');
		$this->loadModel('Users');
		$this->loadModel('ExchangeHistory');
		
		$returnArr = [];
		if ($this->request->is(['post','put'])) {
			$authUserId = $this->request->data['user_id'];
			$coin = "NTR";
			
			if(empty($authUserId)){
				$returnArr['success'] = false;
				$returnArr['message'] = "Invalid User";
				$returnArr['data'] = "";
				echo json_encode($returnArr,true); die;
			}
			$getUserDetail = $this->Users->find('all',['conditions'=>['id'=>$authUserId]])->hydrate(false)->first();
			if(empty($getUserDetail)){
				$returnArr['success'] = false;
				$returnArr['message'] = "Invalid User";
				$returnArr['data'] = "";
				echo json_encode($returnArr,true); die;
			}
			$currentUserEmail = $getUserDetail['email'];
			$cudate = date('Y-m-d H:i:s');
			
			if(empty($coin)){
				$returnArr['success'] = false;
				$returnArr['message'] = "Invalid Currency";
				$returnArr['data'] = "";
				echo json_encode($returnArr,true); die;
			}
			
			$findCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$coin]])->hydrate(false)->first();
			if(empty($findCoinDetail)){
				$returnArr['success'] = false;
				$returnArr['message'] = "Invalid Currency";
				$returnArr['data'] = "";
				echo json_encode($returnArr,true); die;
			}
			$coinType = $findCoinDetail['type'];
			
			$coindId = $findCoinDetail['id'];
			
			$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);
			if(empty($userBalance)) {
				$userBalance = 0;
			}
			
			$transFeesArr = [1=>0.005,2=>0.02,3=>0.1,4=>0.1]; 
			//$transFee = $transFeesArr[$coindId];
			$transFee = 0.005;
			
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
			
			$withdrawalAmount = strip_tags($this->request->data['quantity']);
			
			if($withdrawalAmount<=0.02){
				$returnArr['success'] = false;
				$returnArr['message'] = "Amount should be grater than 0.02";
				$returnArr['data'] = "";
				echo json_encode($returnArr,true); die;
			}	
			
			
			if($coinType=="flat"){
				
				$flatAccountNo = strip_tags($this->request->data['flat_account_no']);
				$flatBankName = strip_tags($this->request->data['flat_bank_name']);
				$flatAccountOwner = strip_tags($this->request->data['flat_account_owner']);
				$flatBankAddress = strip_tags($this->request->data['flat_bank_address']);
				
				if(empty($flatAccountNo) || empty($flatBankName) || empty($flatAccountOwner) || empty($flatBankAddress)){
					$returnArr['success'] = false;
					$returnArr['message'] = "All fields are required";
					$returnArr['data'] = "";
					echo json_encode($returnArr,true); die;
					
				}
				
				$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);	
				if($withdrawalAmount>$userBalance){
					$returnArr['success'] = false;
					$returnArr['message'] = "Insufficient balance in wallet";
					$returnArr['data'] = "";
					echo json_encode($returnArr,true); die;
					
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
						
						$returnArr['success'] = true;
						$returnArr['message'] = $coin." withdrawal request generated";
						$returnArr['data'] = "";
						echo json_encode($returnArr,true); die;
					}
					else {
						$returnArr['success'] = false;
						$returnArr['message'] = $coin." withdrawal failed, Try Again";
						$returnArr['data'] = "";
						echo json_encode($returnArr,true); die;
					}
				}
				else {
						$returnArr['success'] = false;
						$returnArr['message'] = $coin." withdrawal failed, Try Again";
						$returnArr['data'] = "";
						echo json_encode($returnArr,true); die;
				}
				
				
				
			}
			else {
				$getWalletAddress = strip_tags($this->request->data['withdrawal_address']);
				if(empty($getWalletAddress) || empty($withdrawalAmount)){
					$returnArr['success'] = false;
					$returnArr['message'] = " All fields are required";
					$returnArr['data'] = "";
					echo json_encode($returnArr,true); die;
				}
				
					
				$userBalance = $this->Users->getLocalUserBalance($authUserId,$coindId);	
				if($withdrawalAmount>$userBalance){
					$returnArr['success'] = false;
					$returnArr['message'] = "Insufficient balance in wallet";
					$returnArr['data'] = "";
					echo json_encode($returnArr,true); die;
				}
				
				if($coindId==2) {
					$checkValidate = $this->Users->ntrCheckAddressValid($getWalletAddress);
					if($checkValidate==0){
						$returnArr['success'] = false;
						$returnArr['message'] = "Invalid address";
						$returnArr['data'] = "";
						echo json_encode($returnArr,true); die;
					}
				}
				
				$tx_id =  $this->Users->getUniqueId($authUserId);
				$realWithdrawalAmount = $withdrawalAmount - $transFee;
				
				
				$exchangeWalletAddress = "0xb47348577a7ac9881b3605d32f54df3f17b99617";

				
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
						
						$returnArr['success'] = true;
						$returnArr['message'] = $coin.' withdrawal request generated.';
						$returnArr['data'] = "";
						echo json_encode($returnArr,true); die;
					}
					else {
						$returnArr['success'] = false;
						$returnArr['message'] = $coin.' withdrawal failed, Try Again.';
						$returnArr['data'] = "";
						echo json_encode($returnArr,true); die;
					}
				}
				else {
						$returnArr['success'] = false;
						$returnArr['message'] = $coin.' withdrawal failed, Try Again.';
						$returnArr['data'] = "";
						echo json_encode($returnArr,true); die;
				}
			
			}
		}
		
	}
	
	
	
		
	public function getethaddress(){
		$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlfradfmapi";
			
		$this->loadModel("Users");
		$returnArr = [];
		if ($this->request->is(['post'])) {
			$getSecurityId = $this->request->data['security_id'];
		
			
			if($securityId != $getSecurityId){
				
				$returnArr = [];
				$returnArr['success'] = "false";
				$returnArr['message'] = "Invalid Security Code";
				echo json_encode($returnArr); die;	
			}
			$findUser = $this->Users->find('all',['conditions'=>['OR'=>[['eth_address != '=>NULL],['eth_address != '=>""]]],'fields'=>['allethaddress'=>'group_concat(LOWER(eth_address))']])->hydrate(false)->first();
			
			if(!empty($findUser)){
				
				$returnArr['success']=true;
				$returnArr['message']="address";
				$returnArr['data']=$findUser['allethaddress'];
			}
			else {
				$returnArr['success']=false;
				$returnArr['message']="No Data Found";
				$returnArr['data']="";
			}
		}
		else {
			$returnArr['success']=false;
			$returnArr['message']="Invalid Request";
			$returnArr['data']="";
		}
		echo json_encode($returnArr); die;
	}	
		
	public function depositeth(){
		$securityId = "dsO324!d9a@sdfd3rsd9#023dsfsdlfradfmapi";
		$cudate = date('Y-m-d H:i:s');	
		$this->loadModel("Users");
		$this->loadModel("Transactions");
		$returnArr = [];
		if ($this->request->is(['post'])) {
			$getSecurityId = $this->request->data['security_id'];
			$txId = $this->request->data['tx_id'];
			$coinAmount = $this->request->data['amount'];
			$fromWalletAddr = $this->request->data['from'];
			$toWalletAddr = $this->request->data['to'];
			//$date = $this->request->data['date'];
			$findTx = $this->Transactions->find('all',['conditions'=>['tx_id'=>$txId,'wallet_address'=>$toWalletAddr]])->hydrate(false)->first();
			if(empty($findTx)){
				$cryptocoinId = 18;
				if($securityId != $getSecurityId){
					
					$returnArr = [];
					$returnArr['success'] = "false";
					$returnArr['message'] = "Invalid Security Code";
					echo json_encode($returnArr); die;	
				}
				
				$findUser = $this->Users->find('all',['conditions'=>['eth_address'=>$toWalletAddr]])->hydrate(false)->first();
				
				if(!empty($findUser)){
					$userId = $findUser['id'];
					$newInsertArr = [];
					$newInsertArr['user_id'] = $userId;
					$newInsertArr['cryptocoin_id'] = $cryptocoinId;
					$newInsertArr['wallet_address'] = $toWalletAddr;
					$newInsertArr['tx_type'] = 'purchase';
					
					$newInsertArr['tx_id'] = $txId;
					$newInsertArr['coin_amount'] = abs($coinAmount);
					$newInsertArr['status'] = 'completed';
					$newInsertArr['created'] = $cudate;
					$newInsertArr['updated'] = $cudate; 
					
					
					
					// insert data
					$insertData=$this->Transactions->newEntity();
					$insertData=$this->Transactions->patchEntity($insertData,$newInsertArr);
					$insertData=$this->Transactions->save($insertData);
					
					$returnArr['success'] = true;
					$returnArr['message'] = 'eth inserted';
					$returnArr['data'] = "";
					echo json_encode($returnArr,true); die;
				}
				else {
					$returnArr['success']=false;
					$returnArr['message']="No Data Found";
					$returnArr['data']="";
					echo json_encode($returnArr,true); die;
				}
			}
			else {
				$returnArr['success']=false;
				$returnArr['message']="Tx Exist";
				$returnArr['data']="";
				echo json_encode($returnArr,true); die;
			}
		}
		else {
			$returnArr['success']=false;
			$returnArr['message']="Invalid Request";
			$returnArr['data']="";
		}
		echo json_encode($returnArr); die;
	}
	


	public function priceUpdate(){

		$firstCoinId ="20";
		$this->loadModel("Coinpair");
		$this->loadModel("ExchangeHistory");
		$this->loadModel("Cryptocoin");
		$searchData = array('Coinpair.status'=>1,'Coinpair.binance_price'=>'N','Coinpair.coin_second_id'=>$firstCoinId);
		$returnArr = [];
		
		$getCoinPairList = $this->Coinpair->find('all',['conditions'=>$searchData,
														'contain'=>['cryptocoin_first','cryptocoin_second'],
														'order'=>['Coinpair.id'=>'asc'],
														//'limit' => $this->setting['pagination']
														])
														->hydrate(false)
														->toArray();
		foreach($getCoinPairList as $getCoinPairSingle){
			$firstCoinId = $getCoinPairSingle['cryptocoin_first']['id'];
			$secondCoinId = $getCoinPairSingle['cryptocoin_second']['id'];
			
			$firstCoinSrtName = $getCoinPairSingle['cryptocoin_first']['short_name'];
			$secondCoinSrtName = $getCoinPairSingle['cryptocoin_second']['short_name'];
			
			
			if($getCoinPairSingle['binance_price']=="Y"){
				$price = $getCoinPairSingle['pair_price'];
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
					$price = $currentPrice['get_per_price'];
				}
				else {
					$price = $currentPrice['get_per_price'];
				} 
			}
		
			$price1= (($price- $getCoinPairSingle['mid_night_price'])/$getCoinPairSingle['mid_night_price'])*100;
			
		/* 	$price2 = $price1 * 100;
			$price2 = $price2 / $getCoinPairSingle['mid_night_price']; */
			$price1=number_format((float)$price1, 2); 
			$this->Coinpair->updateAll(['current_pair_price'=>$price,'price_percent'=>$price1],['coin_first_id'=>$firstCoinId,'coin_second_id'=>$secondCoinId]);
			
		}
		echo "sucesss";
		die;
		

	}



	public function priceUpdatnight(){

		$firstCoinId ="20";
		$this->loadModel("Coinpair");
		$this->loadModel("ExchangeHistory");
		$this->loadModel("Cryptocoin");
		$searchData = array('Coinpair.status'=>1,'Coinpair.binance_price'=>'N','Coinpair.coin_second_id'=>$firstCoinId);
		$returnArr = [];
		
		$getCoinPairList = $this->Coinpair->find('all',['conditions'=>$searchData,
														'contain'=>['cryptocoin_first','cryptocoin_second'],
														'order'=>['Coinpair.id'=>'asc'],
														//'limit' => $this->setting['pagination']
														])
														->hydrate(false)
														->toArray();
		foreach($getCoinPairList as $getCoinPairSingle){
			$firstCoinId = $getCoinPairSingle['cryptocoin_first']['id'];
			$secondCoinId = $getCoinPairSingle['cryptocoin_second']['id'];
			
			$firstCoinSrtName = $getCoinPairSingle['cryptocoin_first']['short_name'];
			$secondCoinSrtName = $getCoinPairSingle['cryptocoin_second']['short_name'];
			
			
			if($getCoinPairSingle['binance_price']=="Y"){
				$price = $getCoinPairSingle['pair_price'];
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
					$price = $currentPrice['get_per_price'];
				}
				else {
					$price = $currentPrice['get_per_price'];
				} 
			}
			$price1= (($price- $price)/$price)*100;
			$price1=number_format((float)$price1, 2); 
			$this->Coinpair->updateAll(['current_pair_price'=>$price,'price_percent'=>$price1,'mid_night_price'=>$price],['coin_first_id'=>$firstCoinId,'coin_second_id'=>$secondCoinId]);
			
		}
		echo "sucesss";
		die;
	}


    public function checkusermembership()
    {
        $this->loadModel("Users");
        $getUsersList = $this->Users->find("all", ['conditions' => ['annual_membership' => 'Y', 'user_type' => 'U','membership_expires_at !='=>'NULL']])->hydrate(false)->toArray();
        foreach ($getUsersList as $getUser){
            $id = $getUser['id'];
            $membership_expires_at = new DateTime($getUser['membership_expires_at']);
            $differ = $membership_expires_at->diff(Time::now());
            $difference = $differ->format('%a');
            $days = (int) $difference;
            if($days == 30 || $days == 15 || $days == 10 || $days == 5 || $days == 1){
                $data['email'] =  $getUser['email'];
                $data['username'] = $getUser['name'];
                $email = new Email('default');
                $email->viewVars(['data' => $data]);
                $email->from([$this->setting['email_from'] => 'CoinIBT'])
                    ->to($email)
                    ->subject('Membership is expiring! Only '.$days.' days left! Please renew your membership')
                    ->emailFormat('html')
                    ->template('membership')
                    ->send();
            }

            if($days == 0){
                $query = $this->Users->query();
                $query->update()->set(['annual_membership' => 'N'])->where(['id' => $id])->execute();
                echo "success";
                die;
            }
        }
    }


}
