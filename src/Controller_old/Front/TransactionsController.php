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
// src/Controller/UsersController.php

namespace App\Controller\Front;

 
use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Filesystem\File;
use Cake\Routing\Router;

class TransactionsController extends AppController
{
	
	public function bitcoinValue()
    {
		if($this->request->is('ajax'))
        {
			$galaxy_amount = $this->request->data['coin'];
			
			$galaxy_arr  =$this->getgalaxyfrombtcConvert($galaxy_amount,0);
			$btcINR = $this->BTC_INR('val');
			if($galaxy_arr['success'] == 1)
			{
				$btc_amount = ($galaxy_amount * $galaxy_arr['rate']) /  $btcINR;
				echo  "Bit coin value is: ".$btc_amount;
			}else{
				if(array_key_exists('left',$galaxy_arr)) echo 'Galaxy coin left to convert is: '.$galaxy_arr['left'];
				else echo 'No galaxy coin available.';
			}
			die;
		}
	}
    
   
    public function getINR()
    {
		$getBitJsonData = file_get_contents("https://blockchain.info/ticker");
			$getDecode = json_decode($getBitJsonData,true); 
			$buyUsd = $getDecode['USD']['buy'];
			$buyEur = $getDecode['EUR']['buy'];
			$buyGbp = $getDecode['GBP']['buy'];
			
			
			echo '<div class="col-md-3">
      <div class="currency_cont_box">
        <h4>USD</h4>
        <h2><i class="fa fa-dollar"></i> <span class="count" id="header_usd_rate">'.$buyUsd.'</span></h2>
         <span class="bit_price_head"><i class="fa fa-btc"></i> 1.00000000</span> </div>
    </div>
    <div class="col-md-3">
      <div class="currency_cont_box">
        <h4>EUR</h4>
        <h2><i class="fa fa-eur"></i> <span class="count" id="header_usd_rate">'.$buyEur.'</span></h2>
        <span class="bit_price_head"><i class="fa fa-btc"></i> 1.00000000</span> </div>
    </div>
    <div class="col-md-3">
      <div class="currency_cont_box">
        <h4>GBP</h4>
        <h2><i class="fa fa-gbp"></i> <span class="count" id="header_usd_rate">'.$buyGbp.'</span></h2>
        <span class="bit_price_head"><i class="fa fa-btc"></i> 1.00000000</span> </div>
    </div>';
	die;
		
		/* return $getBitJsonData = file_get_contents("https://blockchain.info/ticker"); 
		return $getDecode = json_decode($getBitJsonData,true); die;
		
		$buyUsd = $getDecode['USD']['buy'];
		$buyEur = $getDecode['EUR']['buy'];
		$buyGbp = $getDecode['GBP']['buy'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://blockchain.info/ticker');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$contents = curl_exec($ch);
		$arr = json_decode($contents);
		echo "1 BTC  =".$arr->INR->buy." INR";
		die; */
		
		  
	}
	
	public function getbtcconvert($btcPerAgc,$agcCoin='')
    {
		if($agcCoin==''){ echo ""; die; }
		$multiply =  $agcCoin*$btcPerAgc; 
		echo $multiply = number_format((float)$multiply,8);
		die;
		
		  
	}
	
	public function getconvert($from="",$getVal="",$dollerPerBtc='',$dollerPerCoin=""){
		
		$arr['doller']="";
		$arr['btc']="";
		$arr['coin']="";
		switch($from){
			/* case "doller" :
				$arr['doller'] = $getVal;
				$arr['btc'] = $getVal/$dollerPerBtc;		
				$arr['coin']= $getVal/$dollerPerCoin; 	
			break; */
				
			case "coin" :
				$doller = $dollerPerCoin*$getVal;
				$arr['doller'] = $doller;
				$arr['btc'] = number_format((float)$doller/$dollerPerBtc,8);		
				$arr['coin']=$getVal; 	
			break;	
			
			case "btc" :
				$doller = $dollerPerBtc*$getVal;
				$arr['doller'] = $doller;
				$arr['btc'] = number_format((float)$getVal,8);		
				$arr['coin']=$doller/$dollerPerCoin; 	
			break;
			
			default :	
				$arr['doller']="";
				$arr['btc']="";
				$arr['coin']="";
		}
		
		$this->request->session()->write('calculatedBtc', $arr['btc']);
		echo json_encode($arr); die;
	}
	
	public function getagcconvert($btcPerAgc,$btcCoin='')
    {
		if($btcCoin==''){ echo ""; die; }
		echo $multiply =  $btcCoin/$btcPerAgc; 
		die;
		
		  
	}
	
    public function galaxy()
    {
		$this->set('title','BUY');
		$limit = $this->setting['pagination'];
		$transaction = $this->Transactions->newEntity();
		if ($this->request->is(['post' ,'put'])) 
		{
			$galaxy_amount = $this->request->data['amount'];
			$galaxy_arr  =$this->getgalaxyfrombtcConvert($galaxy_amount,0);
			$btcINR = $this->BTC_INR('val');
			$btc_amount = ($galaxy_amount * $galaxy_arr['rate']) /  $btcINR;
			$btc_available =  $this->checkUserAmount($this->Auth->user('id'),'BTC');
			
			if($btc_available>=$btc_amount)
			{
				
				if($galaxy_arr['success'] == 1)
				{
				
					// debit
					$this->request->data['amount'] =$btc_amount;
					$this->request->data['coin_type'] ='B';
					$this->request->data['trans_type'] = 'S';
					$this->request->data['user_id'] = $this->Auth->user('id');
					$this->request->data['from_user_id'] = $this->Auth->user('id');
					$transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
					
					if ($this->Transactions->save($transaction)) 
					{	
						$this->updateUserWallet($this->Auth->user('id'),'BTC','debit',$btc_amount);
						
						$ref_trans = $this->Transactions->newEntity();
						$data_ref['user_id'] = $this->Auth->user('id');
						$data_ref['from_user_id'] =$this->Auth->user('id');
						$data_ref['coin_type'] = 'Z';
						$data_ref['amount'] = $galaxy_amount;
						$data_ref['trans_type'] = 'R';
						$transaction_ref = $this->Transactions->patchEntity($ref_trans, $data_ref);
						$this->Transactions->save($transaction_ref);
						$this->updateUserWallet($this->Auth->user('id'),'ZUO','credit',$galaxy_amount);
						$this->updateAdminWallet($galaxy_arr['conversion_rate_id'],$galaxy_arr['amount']);

                        if(SENDMAIL == 1)
                        {
                            $user_wallet = $this->Users->find('all',['fields'=>['BTC','ZUO'],'conditions'=>['id'=>$this->Auth->user('id')]])->hydrate(false)->first();
                            //send mail to user for new query                          
                            $data['msg'] = 'You have successfully converted '.$btc_amount.' BTC coins to'.$galaxy_amount.' galaxy coins at rate of 1 btc = '.$btcINR.' INR';
                            $data['updated_wallet'] = $user_wallet;
                            $email = new Email('default');
                            $email->viewVars(['data'=>$data]);
                            $email->from([$this->setting['email_from']] )
                                ->to($this->Auth->user('email'))
                                ->subject('Sucessfully converted Bit coins to Galaxy coins')
                                ->emailFormat('html')
                                ->template('conversion')
                                ->send();

                        }
						
						//send referral amount to user
						$ref_user_id = $this->getReferralUser($this->Auth->user('id'));
						if ($ref_user_id != '' && ($this->referralTransactionEntry($this->Auth->user('id')) == 1) ) 
						{
							
						    $ref_per = $this->setting['referral_amount'];
							if ($ref_per > 0) {
								 $ref_amt = ($galaxy_arr['amount'] * $ref_per) / (100);
								// convert amount to galaxy 
								$galaxy_arr  =$this->getgalaxyfrombtcConvert($ref_amt,0);
								
								if($galaxy_arr['success'] == 1)
								{
									$ref_trans = $this->Transactions->newEntity();
									$data_ref['user_id'] = $ref_user_id;
									$data_ref['from_user_id'] = $this->Auth->user('id');
									$data_ref['coin_type'] = 'Z';
									$data_ref['conversion_rate_id'] = $galaxy_arr['conversion_rate_id'];
									$data_ref['amount'] = $galaxy_arr['amount'];
									$data_ref['trans_type'] = 'Ref';
									$transaction_ref = $this->Transactions->patchEntity($ref_trans, $data_ref);
									$this->Transactions->save($transaction_ref);
									//update user wallet for ref
									$this->updateUserWallet($data_ref['user_id'],'ZUO', 'credit', $data_ref['amount']);
									//update admin wallet
									$this->updateAdminWallet($galaxy_arr['conversion_rate_id'],$galaxy_arr['amount']);

								}
								
								
							}
						}
						$this->Flash->success(__('Sucessfully converted Bitcoin to Galaxy at rate of 1 btc = '.$btcINR.' INR'));
						return $this->redirect(['controller'=>'transactions','action' => 'galaxy']);
					}else{
						foreach($transaction->errors() as $field_key =>  $error_data)
						{
							foreach($error_data as $error_text)
							{
								$this->Flash->error(__($error_text));
								
							} 
						}
					}
				}else{
					if(array_key_exists('left',$galaxy_arr)) $this->Flash->error(__('Galaxy left to convert is: '.$galaxy_arr['left']));
					else $this->Flash->error(__('No galaxy available.'));
				}
			}else{
				$this->Flash->error(__('Insufficient bitcoins to convert.'));
			}
				
		}
		$rate  = $this->bitInGalaxy();
		
		if($rate=='') return $this->redirect(['controller'=>'transactions','action' => 'no_conversion']);
		$btc = $this->checkUserAmount($this->Auth->user('id'),$this->coin_arr['BTC']);
		
		if($btc>0){
			$btcINR = $this->BTC_INR('val');
			$this->set('btc',array('btc'=>$btc,'galaxy'=>($btc * $btcINR )  / $rate['rate']));
		}
		
		$this->set('transaction',$transaction);
	 }
	 
    public function noConversion(){
		$this->set('title','No conversion');
	}
    
	public function purchase()
    {
		$this->set('title','Purchase');
	}
	
	public function buybtc($type='btc')
    {
		$this->set('title','BUY');
		$this->loadModel('Agctransactions');
		$this->loadModel('LandingProgram');
		$this->loadModel('Referal');
		$this->loadModel('Token');
		$this->loadModel('Users');
		
		
			$totalAMXCoin = $this->Token->find('all')->hydrate(false)->first();
			$limit = $this->setting['pagination'];
			
			$transaction = $this->Transactions->newEntity();
			$searchData = array();
			$searchData['AND'][] = array('trans_type'=>'Re','user_id'=>$this->Auth->user('id'));
			if ($this->request->is(['post' ,'put'])) {
				
				
				$userId = $this->Auth->user('id');
				$userDetail = $this->Users->find('all',array('conditions'=>array('id '=>$userId)))->hydrate(false)->first();
				$userEmail = $userDetail['email'];
				$cuDate = date('Y-m-d H:i:s');
				
				
				
				$wallertAddress = $this->Users->createBtcAddress($userId);	// create btc Address
				
				
				
				//purchase Calculation
				$btcCoins = 0;
				$agcCoins = 0;
				$secret = rand(100000000,999999999).'ZzsMLGKe162CfA5EcG6j'.time();
				$my_callback_url = 'http://hedgeconnect.co/test.php';
				$notificationId = $this->Users->createNotification($wallertAddress,$my_callback_url);   // call Notification
				/* $qrImgName = time()."_qr_".$userId.".jpg";
				$barCodeUrl = "https://chart.googleapis.com/chart?chs=225x225&chld=L|2&cht=qr&chl=bitcoin:".$wallertAddress;
				$qrData = copy($barCodeUrl,'qrcodes/'.$qrImgName);
				
				$saveImg = 'qrcodes/'.$qrImgName;
				file_put_contents($saveImg, file_get_contents($barCodeUrl)); */
				//$existTrans = $this->Agctransactions->find('all',['conditions'=>['wallet_address'=>$wallertAddress]])->hydrate(false)->first();
					
				/* if(empty($existTrans)) { 
					$notificationId = $this->Users->createNotification($wallertAddress,$my_callback_url);   // call Notification		

					
					$newInsertArr = [];
					$newInsertArr['trans_id'] = '';
					$newInsertArr['user_id'] = $userId;
					$newInsertArr['wallet_address'] = $wallertAddress;
					$newInsertArr['agc_coins'] = $agcCoins;
					$newInsertArr['btc_coins'] = $btcCoins;
					$newInsertArr['trans_type'] = "purchase";
					$newInsertArr['qrimage'] = $qrImgName;
					$newInsertArr['notification_id'] = $notificationId;
					
					$purchaseAgctransactions=$this->Agctransactions->newEntity();
					$purchaseAgctransactions=$this->Agctransactions->patchEntity($purchaseAgctransactions,$newInsertArr);
					$saveData = $this->Agctransactions->save($purchaseAgctransactions);
					$lastTransId = $saveData->id;
				} */
				
				$existUserDetail= $this->Users->find('all',['conditions'=>['id'=>$userId]])->hydrate(false)->first();
				$qrImgName = $existUserDetail['qrimage'];
				$this->set('transId',$secret);
				$this->set('BtcCoin',$btcCoins);
				$this->set('wallertAddress',$wallertAddress);
				$this->set('qrImage',"qrcodes/".$qrImgName);
				/*Bonus Calculation*/
				/* $bonus_token_percent = 0;
				$cuDate = date('Y-m-d H:i:s');
				$landing_arr = $this->LandingProgram->find('all',array('conditions'=>array('from_date <='=>$cuDate,'to_date >='=>$cuDate)))->hydrate(false)->first();
				$bonus_token_percent = $landing_arr['bonus_token_percent'];
				$bonusAgcCoin = ($agcCoins*$bonus_token_percent)/100;
				$bonusBtcCoin = $bonusAgcCoin*$totalAMXCoin['btc_value']; 
				$bonusArr['trans_id'] = $secret;
				$bonusArr['user_id'] = $userId;
				$bonusArr['agc_coins'] = $bonusAgcCoin;
				$bonusArr['btc_coins'] = $bonusBtcCoin;
				$bonusArr['trans_type'] = "bonus";
				$bonusArr['notification_id'] = $notificationId;
				$bonusAgctransactions=$this->Agctransactions->newEntity();
				$bonusAgctransactions=$this->Agctransactions->patchEntity($bonusAgctransactions,$bonusArr);
				$saveData = $this->Agctransactions->save($bonusAgctransactions); */
				
				
				/*Referral Calculation*/
				/* $getReferalPercent  = $this->Referal->find('all')->hydrate(false)->first();
				$referalPercent  = $getReferalPercent['referal_percent'];
				$referalAgcCoin =($agcCoins*$referalPercent)/100;
				$referalBtcCoin = $referalAgcCoin*$totalAMXCoin['btc_value'];
				$referralUserId = $this->Auth->user('referral_user_id');
				$referralArr['trans_id'] = $secret;
				$referralArr['user_id'] = $referralUserId;
				$referralArr['referral_user_id'] = $userId;
				$referralArr['agc_coins'] = $referalAgcCoin;
				$referralArr['btc_coins'] = $referalBtcCoin;
				$referralArr['trans_type'] = "referral";
				$referralArr['notification_id'] = $notificationId;
				$referralAgctransactions=$this->Agctransactions->newEntity();
				$referralAgctransactions=$this->Agctransactions->patchEntity($referralAgctransactions,$referralArr);
				$saveData = $this->Agctransactions->save($referralAgctransactions); */
				
				
			}
			
			$transaction = $this->Transactions->find();
		
			 $this->set('listing', $this->Paginator->paginate($this->Transactions, [
				'conditions'=>$searchData,
				 'order'=>['Transactions.id'=>'desc'],
				 'limit' => $limit,
				
			]));
		
			
			
			$this->set('transaction',$transaction);
			$this->set('type',$type);
			if($type=='ZUO') $this->set('display_type','Galaxy');
			else $this->set('display_type',$type);
			
			
		
	}
	
    public function buy($type=null)
    {
		$this->set('title','BUY');
		$this->loadModel('Agctransactions');
		$this->loadModel('LandingProgram');
		$this->loadModel('Referal');
		$this->loadModel('Token');
		$this->loadModel('Users');
		
		if($type=='BTC' )
		{ 
			$totalAMXCoin = $this->Token->find('all')->hydrate(false)->first();
			$limit = $this->setting['pagination'];
			
			$transaction = $this->Transactions->newEntity();
			$searchData = array();
			$searchData['AND'][] = array('coin_type'=>$this->coin_arr[$type],'trans_type'=>'Re','user_id'=>$this->Auth->user('id'));
			if ($this->request->is(['post' ,'put'])) {
				$agcCoins = $this->request->data['agc_amount'];
				if($agcCoins<1000){
					return $this->redirect('/front/dashboard?min=1');
				}
				
				$userId = $this->Auth->user('id');
				$userDetail = $this->Users->find('all',array('conditions'=>array('id '=>$userId)))->hydrate(false)->first();
				$cuDate = date('Y-m-d H:i:s');
				
				//delete pending transactions 
				$entity = $this->Agctransactions->find('all',array('conditions'=>array('user_id'=>$userId,'status'=>'pending')))->hydrate(false)->first();
				//print_r($entity); die;
				if(!empty($entity)){
					$entityOne = $this->Agctransactions->get($entity['id']);
					$result = $this->Agctransactions->delete($entityOne);
					
				}
				
				$entityReferal = $this->Agctransactions->find('all',array('conditions'=>array('referral_user_id'=>$userId,'status'=>'pending')))->hydrate(false)->first();
				if(!empty($entityReferal)){
					$entityReferalOne = $this->Agctransactions->get($entityReferal['id']);
					$result = $this->Agctransactions->delete($entityReferalOne);
				}
				//$this->Agctransactions->delete(array('user_id'=>$userId,'status'=>'pending'));
				//$this->Agctransactions->delete(array('refer_id'=>$userId,'status'=>'pending'));

				
				//purchase Calculation
				$btcCoins = $this->request->data['btc_amount'];
				$agcCoins = $this->request->data['agc_amount'];
				$secret = rand(100000000,999999999).'ZzsMLGKe162CfA5EcG6j'.time();
				$my_api_key = '74d387b3-eb20-4095-aebd-c9ab9b366e04';
				/* $my_xpub = 'xpub6D1iDy8NcUvDYs4fH4cdu9tgVKo4GHdwLMrWqTvdgcoBjiXM2qz2ogn4ZYFMAE8eQTJu8tkjekarkDN1qhJwiqcDhAe3rXLG4bEAyuAd1nG';
				$my_api_key = '74d387b3-eb20-4095-aebd-c9ab9b366e04';
				$my_callback_url = 'https://www.amaxgoldcoin.com/returndata?secret='.$secret;
				$root_url = 'https://api.blockchain.info/v2/receive';
				$gap_limit=1000;
				$parameters = 'xpub=' .$my_xpub. '&callback=' .urlencode($my_callback_url). '&key=' .$my_api_key. '&gap_limit=' .$gap_limit;
				$response = file_get_contents($root_url . '?' . $parameters);
				$object = json_decode($response); */
				$wallertAddress = $userDetail['btc_address'];
				//$wallertAddress = "ZzsMLGKe162CfA5EcG6j";
				$qrImgName = time()."_qr_".$userId.".jpg";
				//$barCodeUrl = "https://blockchain.info/qr?data=".$wallertAddress."&size=200&amount=".$btcCoins;
				#$barCodeUrl = "https://blockchain.info/qr?data=".$wallertAddress."&size=200&amount=".$btcCoins;
				$barCodeUrl = "https://chart.googleapis.com/chart?chs=225x225&chld=L|2&cht=qr&chl=bitcoin:".$wallertAddress."?amount=".$btcCoins;
				
				//$qrData = copy('https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.$wallertAddress,'qrcodes/'.$qrImgName);
				$qrData = copy($barCodeUrl,'qrcodes/'.$qrImgName);
				
				$newInsertArr = [];
				
				$newInsertArr['trans_id'] = $secret;
				$newInsertArr['user_id'] = $userId;
				$newInsertArr['wallet_address'] = $wallertAddress;
				$newInsertArr['agc_coins'] = $agcCoins;
				$newInsertArr['btc_coins'] = $btcCoins;
				$newInsertArr['trans_type'] = "purchase";
				$newInsertArr['qrimage'] = $qrImgName;
				$this->set('transId',$secret);
				$this->set('BtcCoin',$btcCoins);
				$this->set('wallertAddress',$wallertAddress);
				$this->set('qrImage',"qrcodes/".$qrImgName);
				$purchaseAgctransactions=$this->Agctransactions->newEntity();
				$purchaseAgctransactions=$this->Agctransactions->patchEntity($purchaseAgctransactions,$newInsertArr);
				$saveData = $this->Agctransactions->save($purchaseAgctransactions);
				$lastTransId = $saveData->id;
				/* $fields_string ='';	
				$newCallbackUrl = 'https://www.amaxgoldcoin.com/returndata?secret='.$secret."&trans_id=".$lastTransId;
				$url = 'https://api.blockchain.info/v2/receive/balance_update';
				$fields = array(
					'address' => urlencode($wallertAddress),
					'callback' => urlencode($newCallbackUrl),
					'key' => urlencode($my_api_key),
					'onNotification' => "KEEP"
				);

				//url-ify the data for the POST
				foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				rtrim($fields_string, '&');

				//open connection
				$ch = curl_init();

				//set the url, number of POST vars, POST data
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_POST, count($fields));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

				//execute post
				$result = curl_exec($ch);

				//close connection
				curl_close($ch);
				 */
				
				/*Bonus Calculation*/
				$bonus_token_percent = 0;
				$cuDate = date('Y-m-d H:i:s');
				$landing_arr = $this->LandingProgram->find('all',array('conditions'=>array('from_date <='=>$cuDate,'to_date >='=>$cuDate)))->hydrate(false)->first();
				$bonus_token_percent = $landing_arr['bonus_token_percent'];
				$bonusAgcCoin = ($agcCoins*$bonus_token_percent)/100;
				$bonusBtcCoin = $bonusAgcCoin*$totalAMXCoin['btc_value']; 
				$bonusArr['trans_id'] = $secret;
				$bonusArr['user_id'] = $userId;
				$bonusArr['agc_coins'] = $bonusAgcCoin;
				$bonusArr['btc_coins'] = $bonusBtcCoin;
				$bonusArr['trans_type'] = "bonus";
				$bonusAgctransactions=$this->Agctransactions->newEntity();
				$bonusAgctransactions=$this->Agctransactions->patchEntity($bonusAgctransactions,$bonusArr);
				$saveData = $this->Agctransactions->save($bonusAgctransactions);
				
				
				/*Referral Calculation*/
				$getReferalPercent  = $this->Referal->find('all')->hydrate(false)->first();
				$referalPercent  = $getReferalPercent['referal_percent'];
				$referalAgcCoin =($agcCoins*$referalPercent)/100;
				$referalBtcCoin = $referalAgcCoin*$totalAMXCoin['btc_value'];
				$referralUserId = $this->Auth->user('referral_user_id');
				$referralArr['trans_id'] = $secret;
				$referralArr['user_id'] = $referralUserId;
				$referralArr['referral_user_id'] = $userId;
				$referralArr['agc_coins'] = $referalAgcCoin;
				$referralArr['btc_coins'] = $referalBtcCoin;
				$referralArr['trans_type'] = "referral";
				$referralAgctransactions=$this->Agctransactions->newEntity();
				$referralAgctransactions=$this->Agctransactions->patchEntity($referralAgctransactions,$referralArr);
				$saveData = $this->Agctransactions->save($referralAgctransactions);
				
				
				
				
				/* if(isset($this->request->data['transaction_id_search']))
				{
					if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
					$search = $this->request->data;					
					if($search['pagination'] != '') $limit =  $search['pagination'];
					//pr($search);die;
					if(isset($search['transaction_id_search']) && $search['transaction_id_search'] != '') $searchData['AND'][] =array('transaction_id' => $search['transaction_id_search']);
					if($search['status'] != '') $searchData['AND'][] =array('status' => $search['status']);
					if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
					else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
					else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
				}else{
					 $this->request->data['coin_type'] = $this->coin_arr[$type];
					 $this->request->data['trans_type'] = 'Re';
					 $this->request->data['status'] = 'P';
					 $this->request->data['user_id'] = $this->Auth->user('id');
					
					$transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
					
					if ($this->Transactions->save($transaction)) {
						$this->Flash->success(__('Your request has been send.'));
						return $this->redirect(['controller'=>'transactions','action' => 'buy',$type]);
					}else{
						foreach($transaction->errors() as $field_key =>  $error_data)
						{
							foreach($error_data as $error_text)
							{
								$this->Flash->error(__($error_text));
								
							} 
						}
					}
				} */
			}
			
			$transaction = $this->Transactions->find();
		
			 $this->set('listing', $this->Paginator->paginate($this->Transactions, [
				'conditions'=>$searchData,
				 'order'=>['Transactions.id'=>'desc'],
				 'limit' => $limit,
				
			]));
		
			
			
			$this->set('transaction',$transaction);
			$this->set('type',$type);
			if($type=='ZUO') $this->set('display_type','Galaxy');
			else $this->set('display_type',$type);
			
			
		}else return $this->redirect(['controller'=>'pages','action' => 'dashboard']);
	}
	
	
	public function pay($id=null)
    {
		if($id == null){
			return $this->redirect(['controller'=>'Transaction','action' => 'transaction','purchase']);
		}
		$this->set('title','Pay');
		$this->loadModel('Agctransactions');
		$this->loadModel('LandingProgram');
		$this->loadModel('Referal');
		$this->loadModel('Token');
		$userId = $this->Auth->user('id');
		$findTrans = $this->Agctransactions->find('all',['conditions'=>['id'=>$id,'user_id'=>$userId]])->hydrate(false)->first();
		if(empty($findTrans)){
			return $this->redirect(['controller'=>'Transaction','action' => 'transaction','purchase']);
		}
		$this->set('findTrans',$findTrans);
		
	}
	
	
    public function buySearch()
	{
		
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			$searchData = array();
			$limit = $this->setting['pagination'];
			$searchData['AND'][] = array('coin_type'=>$this->coin_arr[$search['type']],'trans_type'=>'Re','user_id'=>$this->Auth->user('id'));
			if($search['pagination'] != '') $limit =  $search['pagination'];
			//pr($search);die;
			if(isset($search['transaction_id_search']) && $search['transaction_id_search'] != '') $searchData['AND'][] =array('transaction_id' => $search['transaction_id_search']);
			if($search['status'] != '') $searchData['AND'][] =array('status' => $search['status']);
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
			
			
		
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			 $this->set('listing', $this->Paginator->paginate($this->Transactions, [
				'conditions'=>$searchData,
				 'order'=>['Transactions.id'=>'desc'],
				 'limit' => $limit,
				
			]));
			
			$this->set('type',$search['type']);
		
		   
			
		}
	
	}
	
	
	public function bulktransaction($type=null)
    {
		$currentUser = $this->Auth->user('id');
		/* if($currentUser!=372) { 
			$this->Flash->error(__("All Coin Sold Out"));
			return $this->redirect('front/transactions/ico'); 
		} */
		$this->set('title','Transaction');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Referal');
		$this->loadModel('Token');
		$coin_arr=['referral','purchase','bonus'];
		if(!in_array($type,$coin_arr)){
			$type = "purchase";
		}	
		$cudate = date("Y-m-d H:i:s");	

			$this->set('display_type','AGC');
			// for purchase
			$currentUser = $this->Auth->user('id');
			$referralUserId = $this->Auth->user('referral_user_id');
			if ($this->request->is(['post' ,'put']) ) {
				

				
				$isSearch = $this->request->data['type'];
				if($isSearch == "no"){
					// calculateTotalPurchanse start
					$getGrandTotalCoin = $this->Cointransactions->find(); 
					$getGrandTotalCoinCnt = $getGrandTotalCoin->select(['sum' => $getGrandTotalCoin->func()->sum('coin')])->where(array('type'=>"bulk_purchase"))->toArray();
					
					$getGrandTotalCoinCount = $getGrandTotalCoinCnt[0]['sum'];
					
					
					
					$coinPurchaseVal = $this->request->data['bulk_coin_amount'];
					$coinPurchaseVal = abs($coinPurchaseVal);
					$thisRoundMaxCoinSellRange = 500000;
					
					if($getGrandTotalCoinCount >= $thisRoundMaxCoinSellRange){
						$this->Flash->error(__("All Coin Sold Out"));
						return $this->redirect('front/transactions/ico'); 
					} 
					
					// if total coin greater than 15 lakh to sold
					$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
					if($getGrandTotalCoinCountWithPurchase > $thisRoundMaxCoinSellRange){
						$canPurchase = $thisRoundMaxCoinSellRange - $getGrandTotalCoinCount; 
						$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
						return $this->redirect('front/transactions/ico'); 
					} 
					
					
					$cuDateTime = time();
					$usersLastCoin = 0;
					//$coinPurchaseVal = 500000;
					$dollerPerCoin = 5;
					
					$getBitJsonData = file_get_contents("https://blockchain.info/ticker");
					$getDecode = json_decode($getBitJsonData,true); 
					$dollerPerBtc = $getDecode['USD']['buy'];
					
					
					$dollerPurchaseVal = $dollerPerCoin*$coinPurchaseVal;
					//$btcPurchaseVal = $this->request->session()->read('bulkBtc');;
					$btcPurchaseVal = $dollerPurchaseVal/$dollerPerBtc;
					
					$getUserBtcAmt  = $this->Users->getUserTotalBtc($currentUser);
					if($getUserBtcAmt < $btcPurchaseVal){
						$this->Flash->error(__('You have insufficient balance in Btc wallet.'));
						return $this->redirect('front/transactions/ico');
					}
					
					
					
					$newInsertArr = [];
					$newInsertArr['user_id'] = $currentUser;
					$newInsertArr['btc'] = $btcPurchaseVal;
					$newInsertArr['coin'] = $coinPurchaseVal;
					$newInsertArr['dollar'] = $dollerPurchaseVal;
					$newInsertArr['doller_per_hc'] = $dollerPerCoin;
					$newInsertArr['type'] = 'bulk_purchase';
					$newInsertArr['updated_at'] = $cudate;
					
					// insert data
					$purchaseCoinTransactions=$this->Cointransactions->newEntity();
					$purchaseCoinTransactions=$this->Cointransactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$saveData = $this->Cointransactions->save($purchaseCoinTransactions);
					$cointransactionsId = $saveData->id;
					
					if($saveData){
							
							// update user btc wallet start
							$remainingUserBtcAmount = $getUserBtcAmt-$btcPurchaseVal;
							$getUserBtcNew = $this->Users->get($currentUser);
							$getUserBtcData = $this->Users->patchEntity($getUserBtcNew,['total_btc'=>$remainingUserBtcAmount]);
							$updateBtcWallet = $this->Users->save($getUserBtcData);
							// update user btc wallet end
							
							// insert into transctions table Start
							$newTransArr['cointransactions_id']= $cointransactionsId;
							$newTransArr['user_id']= $currentUser;
							$newTransArr['wallet_address']= $this->Auth->user('btc_address');
							$newTransArr['btc_coins']= "-".$btcPurchaseVal;
							$newTransArr['trans_type']= "debit";
							$newTransArr['payment_date']= $cudate;
							$newTransArr['status']= "completed";
							$newTransArr['updated_at']= $cudate;
							$newTransArr['coin_type']= "hc purchase";
							
							$getTransNew = $this->Agctransactions->newEntity();
							$getTransNewData = $this->Agctransactions->patchEntity($getTransNew,$newTransArr);
							$updateBtcWallet = $this->Agctransactions->save($getTransNewData);
							// insert into transctions table End
							
							// calculation for referral user
							if(!empty($referralUserId)){
								$findReferralUser = $this->Users->find("all",['conditions'=>["id"=>$referralUserId]])->hydrate(false)->first(); 
								$getReferalSetting = $this->Referal->find("all")->hydrate(false)->first();  
								$referralPercent = $getReferalSetting['referal_percent']; 
								$dollerReferral = $dollerPurchaseVal*($referralPercent/100);
								$btcReferral = $dollerReferral/$dollerPerBtc;
								$coinReferral = $dollerReferral/$dollerPerCoin;
								
								$newReferalArr = [];
								$newReferalArr['user_id'] = $referralUserId;
								$newReferalArr['referral_user_id'] = $currentUser;
								$newReferalArr['btc'] = $btcReferral;
								$newReferalArr['coin'] = $coinReferral;
								$newReferalArr['dollar'] = $dollerReferral;
								$newReferalArr['doller_per_hc'] = $dollerPerCoin;
								$newReferalArr['type'] = 'referral';
								$newReferalArr['updated_at'] = $cudate;
								
								$referalTransactions=$this->Cointransactions->newEntity();
								$referalTransactions=$this->Cointransactions->patchEntity($referalTransactions,$newReferalArr);
								$saveReferaData = $this->Cointransactions->save($referalTransactions);
								//$cointransactionsId = $saveData->id;
									
							}
							$this->Flash->success(__('HC Bulk coin purchased successfully.'));
							return $this->redirect('front/transactions/ico');
					}
					else {
						$this->Flash->error(__('Unable to purchase HC. Try Again.'));
						return $this->redirect('front/transactions/ico');
					}
				}
			}
			
			
			
			return $this->redirect('front/transactions/ico');
			$currentUserWallet = $this->Auth->user('unique_id');
			$this->set('currentUserWallet',$currentUserWallet);
			
			$getUserTotalCoin = $this->Cointransactions->find(); 
			$getUserTotalCoinCnt = $getUserTotalCoin
										->select(['sum' => $getUserTotalCoin->func()->sum('coin')])
										->where(array('user_id'=>$currentUser))
										->toArray();
			
			$getUserTotalCoinCount = $getUserTotalCoinCnt[0]['sum'];
			$this->set('getUserTotalCoinCount',$getUserTotalCoinCount);
			
			
			$limit = $this->setting['pagination'];
			
			$searchData = array();
			$searchData['AND'][] = array('user_id' => $this->Auth->user('id'));
			/* if($type=="referral" || $type=="bonus"){
				$searchData['AND'][] = array('status'=>'completed');	
			} */
			
			
			if ($this->request->is(['post' ,'put'])){
				
				$isSearch = $this->request->data['type'];
				
				if($isSearch == "purchase"){
					if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
					$search = $this->request->data;
					
					if($search['pagination'] != '') $limit =  $search['pagination'];
					//pr($search);die;
					
					if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
					else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
					else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
				}
				
				
				
			}
			$this->set('listing',$this->Paginator->paginate($this->Cointransactions, [
						     'conditions'=>$searchData,
						    'order'=>['Cointransactions.id'=>'desc'],
							'limit' => $limit,
						]));
			$this->set('type',$type);
			

	}
	

    public function transaction($type=null)
    {
		$this->set('title','Transaction');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Referal');
		$this->loadModel('Token');
		$coin_arr=['referral','purchase','bonus'];
		if(!in_array($type,$coin_arr)){
			$type = "purchase";
		}	
		$cudate = date("Y-m-d H:i:s");	
		/* if($type=='Galaxy'|| $type=='BTC' )
		{ */
			
			/* if($type=='Galaxy'){
				$type='ZUO';
				$this->set('display_type','Galaxy');
			}else  $this->set('display_type',$type); */
			$this->set('display_type','AGC');
			// for purchase
			$currentUser = $this->Auth->user('id');
			$referralUserId = $this->Auth->user('referral_user_id');
			if ($this->request->is(['post' ,'put']) ) {
				
				$isSearch = $this->request->data['type'];
				if($isSearch == "no") {
					// calculateTotalPurchanse start
					$getGrandTotalCoin = $this->Cointransactions->find(); 
					$getGrandTotalCoinCnt = $getGrandTotalCoin->select(['sum' => $getGrandTotalCoin->func()->sum('coin')])->where(array('type'=>"purchase",'status'=>1))->toArray();
					
					$getGrandTotalCoinCount = $getGrandTotalCoinCnt[0]['sum'];
					$roundSecondFirstDate = "2018-01-21 08:30:00";
					
					$roundTenthStartDate = ($currentUser==372) ? strtotime("2018-02-13 08:00:00") : strtotime("2018-02-13 13:30:00");
					$roundNinthStartDate = strtotime("2018-02-12 13:30:00");
					$roundEighthStartDate = strtotime("2018-02-06 13:30:00");
					$roundSeventhStartDate = strtotime("2018-02-05 13:30:00");
					$roundSixthStartDate = strtotime("2018-01-31 13:30:00");
					$roundFifthStartDate = strtotime("2018-01-30 13:30:00");
					$roundFourthStartDate = strtotime("2018-01-26 13:30:00");
					$roundThirdStartDate = strtotime("2018-01-25 13:30:00");
					$roundSecondStartDate = strtotime("2018-01-21 13:30:00");
					//$roundFirstEndDate = strtotime("2018-01-21 10:20:00");
					
					$cuDateTime = time();
					$getUserGrandTotalCoinCountInThisRound = 0;
					$coinPurchaseVal = $this->request->data['coin_amount'];
					$coinPurchaseVal = abs($coinPurchaseVal);
					
					
					/// for tenth round
					if($roundTenthStartDate < $cuDateTime){
						//$thisRoundMaxCoinSellRange = 2025333;
						//$thisRoundMaxCoinSellRange = 2438433; 
						$thisRoundMaxCoinSellRange = 2267754;    
						$userPurchaseCoinLimit = 10000;
						// if 15 lakh coin already sold
						if($getGrandTotalCoinCount >= $thisRoundMaxCoinSellRange){
							$this->Flash->error(__("Tenth round's All Coins Sold Out. Now wait for next round. Next round will Start Soon"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// if total coin greater than 15 lakh to sold
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >= $thisRoundMaxCoinSellRange){
							$canPurchase = $thisRoundMaxCoinSellRange - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// allow user to purchase only 10000 coin in fourth round
						$getUserGrandTotalCoin = $this->Cointransactions->find(); 
						$getUserGrandTotalCoinCnt = $getUserGrandTotalCoin->select(['sum' => $getUserGrandTotalCoin->func()->sum('coin')])->where(['status'=>1,'type'=>'purchase','user_id'=>$currentUser,'created_at >='=>$roundTenthStartDate])->toArray();
						if(!empty($getUserGrandTotalCoinCnt) && isset($getUserGrandTotalCoinCnt[0]['sum'])) {
							$getUserGrandTotalCoinCountInThisRound = $getUserGrandTotalCoinCnt[0]['sum'];
						}
						
						$totalToPurchase = $getUserGrandTotalCoinCountInThisRound+$coinPurchaseVal;
						if($totalToPurchase>$userPurchaseCoinLimit){
							$remainingCoin = $userPurchaseCoinLimit-$getUserGrandTotalCoinCountInThisRound; 
							$this->Flash->error(__("You can purchase ".$userPurchaseCoinLimit." coins in 10th round. So you can purchase only ".$remainingCoin." coin More"));
							return $this->redirect('front/transactions/ico');
						}
						
					}
					
					
					
					
					/// for ninth round
					if($roundNinthStartDate < $cuDateTime && $cuDateTime < $roundTenthStartDate){
						//$thisRoundMaxCoinSellRange = 2025333;
						//$thisRoundMaxCoinSellRange = 2438433; 
						$thisRoundMaxCoinSellRange = 2241456;   
						$userPurchaseCoinLimit = 10000;
						// if 15 lakh coin already sold
						if($getGrandTotalCoinCount >= $thisRoundMaxCoinSellRange){
							$this->Flash->error(__("Ninth round's All Coins Sold Out. Now wait for next round. Next round will Start Soon"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// if total coin greater than 15 lakh to sold
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >= $thisRoundMaxCoinSellRange){
							$canPurchase = $thisRoundMaxCoinSellRange - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// allow user to purchase only 10000 coin in fourth round
						$getUserGrandTotalCoin = $this->Cointransactions->find(); 
						$getUserGrandTotalCoinCnt = $getUserGrandTotalCoin->select(['sum' => $getUserGrandTotalCoin->func()->sum('coin')])->where(['type'=>'purchase','user_id'=>$currentUser,'created_at >='=>$roundNinthStartDate])->toArray();
						if(!empty($getUserGrandTotalCoinCnt) && isset($getUserGrandTotalCoinCnt[0]['sum'])) {
							$getUserGrandTotalCoinCountInThisRound = $getUserGrandTotalCoinCnt[0]['sum'];
						}
						/* $userLastTrans = $this->Cointransactions->find('all',['conditions'=>['type'=>'purchase','user_id'=>$currentUser,'created_at >='=>$roundSeventhStartDate],'limit'=>1,'order'=>['id'=>'DESC']])->hydrate(false)->first();
						if(!empty($userLastTrans)) {
							$usersLastCoin = $userLastTrans['coin'];
						} */
						$totalToPurchase = $getUserGrandTotalCoinCountInThisRound+$coinPurchaseVal;
						if($totalToPurchase>$userPurchaseCoinLimit){
							$remainingCoin = $userPurchaseCoinLimit-$getUserGrandTotalCoinCountInThisRound; 
							$this->Flash->error(__("You can purchase ".$userPurchaseCoinLimit." coins in 9th round. So you can purchase only ".$remainingCoin." coin More"));
							return $this->redirect('front/transactions/ico');
						}
						
					}
					
					
					
					
					/// for eighth round
					if($roundEighthStartDate < $cuDateTime && $cuDateTime < $roundNinthStartDate){
						//$thisRoundMaxCoinSellRange = 2025333;
						//$thisRoundMaxCoinSellRange = 2438433; 
						$thisRoundMaxCoinSellRange = 2202412;   
						$userPurchaseCoinLimit = 10000;
						// if 15 lakh coin already sold
						if($getGrandTotalCoinCount >= $thisRoundMaxCoinSellRange){
							$this->Flash->error(__("Eighth round's All Coins Sold Out. Now wait for next round. Next round will Start Soon"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// if total coin greater than 15 lakh to sold
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >= $thisRoundMaxCoinSellRange){
							$canPurchase = $thisRoundMaxCoinSellRange - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// allow user to purchase only 10000 coin in fourth round
						$getUserGrandTotalCoin = $this->Cointransactions->find(); 
						$getUserGrandTotalCoinCnt = $getUserGrandTotalCoin->select(['sum' => $getUserGrandTotalCoin->func()->sum('coin')])->where(['type'=>'purchase','user_id'=>$currentUser,'created_at >='=>$roundEighthStartDate])->toArray();
						if(!empty($getUserGrandTotalCoinCnt) && isset($getUserGrandTotalCoinCnt[0]['sum'])) {
							$getUserGrandTotalCoinCountInThisRound = $getUserGrandTotalCoinCnt[0]['sum'];
						}
						/* $userLastTrans = $this->Cointransactions->find('all',['conditions'=>['type'=>'purchase','user_id'=>$currentUser,'created_at >='=>$roundSeventhStartDate],'limit'=>1,'order'=>['id'=>'DESC']])->hydrate(false)->first();
						if(!empty($userLastTrans)) {
							$usersLastCoin = $userLastTrans['coin'];
						} */
						$totalToPurchase = $getUserGrandTotalCoinCountInThisRound+$coinPurchaseVal;
						if($totalToPurchase>$userPurchaseCoinLimit){
							$remainingCoin = $userPurchaseCoinLimit-$getUserGrandTotalCoinCountInThisRound; 
							$this->Flash->error(__("You can purchase ".$userPurchaseCoinLimit." coins in 8th round. So you can purchase only ".$remainingCoin." coin More"));
							return $this->redirect('front/transactions/ico');
						}
						
					}
					
					
					/// for seventh round
					if($roundSeventhStartDate < $cuDateTime && $cuDateTime < $roundEighthStartDate){
						//$thisRoundMaxCoinSellRange = 2025333;
						//$thisRoundMaxCoinSellRange = 2438433; 
						$thisRoundMaxCoinSellRange = 2123823;   
						$userPurchaseCoinLimit = 10000;
						// if 15 lakh coin already sold
						if($getGrandTotalCoinCount >= $thisRoundMaxCoinSellRange){
							$this->Flash->error(__("Seventh round's All Coins Sold Out. Now wait for next round. Next round will Start Soon"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// if total coin greater than 15 lakh to sold
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >= $thisRoundMaxCoinSellRange){
							$canPurchase = $thisRoundMaxCoinSellRange - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// allow user to purchase only 10000 coin in fourth round
						$getUserGrandTotalCoin = $this->Cointransactions->find(); 
						$getUserGrandTotalCoinCnt = $getUserGrandTotalCoin->select(['sum' => $getUserGrandTotalCoin->func()->sum('coin')])->where(['type'=>'purchase','user_id'=>$currentUser,'created_at >='=>$roundSeventhStartDate])->toArray();
						if(!empty($getUserGrandTotalCoinCnt) && isset($getUserGrandTotalCoinCnt[0]['sum'])) {
							$getUserGrandTotalCoinCountInThisRound = $getUserGrandTotalCoinCnt[0]['sum'];
						}
						/* $userLastTrans = $this->Cointransactions->find('all',['conditions'=>['type'=>'purchase','user_id'=>$currentUser,'created_at >='=>$roundSeventhStartDate],'limit'=>1,'order'=>['id'=>'DESC']])->hydrate(false)->first();
						if(!empty($userLastTrans)) {
							$usersLastCoin = $userLastTrans['coin'];
						} */
						$totalToPurchase = $getUserGrandTotalCoinCountInThisRound+$coinPurchaseVal;
						if($totalToPurchase>$userPurchaseCoinLimit){
							$remainingCoin = $userPurchaseCoinLimit-$getUserGrandTotalCoinCountInThisRound; 
							$this->Flash->error(__("You can purchase ".$userPurchaseCoinLimit." coins in Seventh round. So you can purchase only ".$remainingCoin." coin More"));
							return $this->redirect('front/transactions/ico');
						}
						
					}
					
					
					/// for Sixth round
					if($roundSixthStartDate < $cuDateTime && $cuDateTime < $roundSeventhStartDate){
						//$thisRoundMaxCoinSellRange = 2025333;
						$thisRoundMaxCoinSellRange = 1938428; 
						$userPurchaseCoinLimit = 10000;
						// if 15 lakh coin already sold
						if($getGrandTotalCoinCount >= $thisRoundMaxCoinSellRange){
							$this->Flash->error(__("Sixth round's All Coins Sold Out. Now wait for Seventh round. Seventh round will Start Soon"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// if total coin greater than 15 lakh to sold
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >= $thisRoundMaxCoinSellRange){
							$canPurchase = $thisRoundMaxCoinSellRange - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// allow user to purchase only 10000 coin in fourth round
						$userLastTrans = $this->Cointransactions->find('all',['conditions'=>['type'=>'purchase','user_id'=>$currentUser,'created_at >='=>$roundFifthStartDate],'limit'=>1,'order'=>['id'=>'DESC']])->hydrate(false)->first();
						if(!empty($userLastTrans)) {
							$usersLastCoin = $userLastTrans['coin'];
						}
						$totalToPurchase = $usersLastCoin+$coinPurchaseVal;
						if($totalToPurchase>$userPurchaseCoinLimit){
							$remainingCoin = $userPurchaseCoinLimit-$usersLastCoin; 
							$this->Flash->error(__("You can purchase ".$userPurchaseCoinLimit." coins in sixth round. So you can purchase only ".$remainingCoin." coin More"));
							return $this->redirect('front/transactions/ico');
						}
						
					}
					
					/// for Fifth round
					if($roundFifthStartDate < $cuDateTime && $cuDateTime < $roundSixthStartDate){
						//$thisRoundMaxCoinSellRange = 2025333;
						$thisRoundMaxCoinSellRange = 1838303; 
						$userPurchaseCoinLimit = 10000;
						// if 15 lakh coin already sold
						if($getGrandTotalCoinCount >= $thisRoundMaxCoinSellRange){
							$this->Flash->error(__("Fifth round's All Coins Sold Out. Now wait for Sixth round. Sixth round will Start Soon"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// if total coin greater than 15 lakh to sold
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >= $thisRoundMaxCoinSellRange){
							$canPurchase = $thisRoundMaxCoinSellRange - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// allow user to purchase only 10000 coin in fourth round
						$userLastTrans = $this->Cointransactions->find('all',['conditions'=>['user_id'=>$currentUser,'created_at >='=>$roundThirdStartDate],'limit'=>1,'order'=>['id'=>'DESC']])->hydrate(false)->first();
						if(!empty($userLastTrans)) {
							$usersLastCoin = $userLastTrans['coin'];
						}
						$totalToPurchase = $usersLastCoin+$coinPurchaseVal;
						if($totalToPurchase>$userPurchaseCoinLimit){
							$remainingCoin = $userPurchaseCoinLimit-$usersLastCoin; 
							$this->Flash->error(__("You can purchase ".$userPurchaseCoinLimit." coins in fourth round. So you can purchase only ".$remainingCoin." coin More"));
							return $this->redirect('front/transactions/ico');
						}
						
					}
					
					/// for Fourth round
					if($roundFourthStartDate < $cuDateTime && $cuDateTime < $roundFifthStartDate){
						$thirdRoundMaxCoinSellRange = 1525000;
						$userPurchaseCoinLimit = 10000;
						// if 15 lakh coin already sold
						if($getGrandTotalCoinCount >= $thirdRoundMaxCoinSellRange){
							$this->Flash->error(__("Fourth round's All Coins Sold Out. Now wait for Fifth round. Fifth round will Start Soon"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// if total coin greater than 15 lakh to sold
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >= $thirdRoundMaxCoinSellRange){
							$canPurchase = $thirdRoundMaxCoinSellRange - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// allow user to purchase only 10000 coin in fourth round
						$userLastTrans = $this->Cointransactions->find('all',['conditions'=>['user_id'=>$currentUser,'created_at >='=>$roundThirdStartDate],'limit'=>1,'order'=>['id'=>'DESC']])->hydrate(false)->first();
						if(!empty($userLastTrans)) {
							$usersLastCoin = $userLastTrans['coin'];
						}
						$totalToPurchase = $usersLastCoin+$coinPurchaseVal;
						if($totalToPurchase>$userPurchaseCoinLimit){
							$remainingCoin = $userPurchaseCoinLimit-$usersLastCoin; 
							$this->Flash->error(__("You can purchase ".$userPurchaseCoinLimit." coins in fourth round. So you can purchase only ".$remainingCoin." coin More"));
							return $this->redirect('front/transactions/ico');
						}
						
					}
					
					
					
					
					/// for Third round
					if($roundThirdStartDate < $cuDateTime &&  $cuDateTime < $roundFourthStartDate){
						$thirdRoundMaxCoinSellRange = 1475000;
						$userPurchaseCoinLimit = 10000;
						// if 15 lakh coin already sold
						if($getGrandTotalCoinCount >= $thirdRoundMaxCoinSellRange){
							$this->Flash->error(__("Third round's All Coins Sold Out. Now wait for Fourth round. Fourth round will Start Soon"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// if total coin greater than 15 lakh to sold
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >= $thirdRoundMaxCoinSellRange){
							$canPurchase = $thirdRoundMaxCoinSellRange - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// allow user to purchase only 10000 coin in second round
						$userLastTrans = $this->Cointransactions->find('all',['conditions'=>['user_id'=>$currentUser,'created_at >='=>$roundThirdStartDate],'limit'=>1,'order'=>['id'=>'DESC']])->hydrate(false)->first();
						if(!empty($userLastTrans)) {
							$usersLastCoin = $userLastTrans['coin'];
						}
						$totalToPurchase = $usersLastCoin+$coinPurchaseVal;
						if($totalToPurchase>$userPurchaseCoinLimit){
							$remainingCoin = $userPurchaseCoinLimit-$usersLastCoin; 
							$this->Flash->error(__("You can purchase ".$userPurchaseCoinLimit." coins in third round. So you can purchase only ".$remainingCoin." coin More"));
							return $this->redirect('front/transactions/ico');
						}
						
					}
					
					
					
					/// for second round
					if($roundSecondStartDate < $cuDateTime &&  $cuDateTime < $roundThirdStartDate){
						
						// if 10 lakh coin already sold
						if($getGrandTotalCoinCount >=1000000){
							$this->Flash->error(__("Second round's All Coins Sold Out. Now wait for third round. third round will Start Soon"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// if total coin greater than 10 lakh to sold
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >=1000000){
							$canPurchase = 1000000 - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						
						// allow user to purchase only 10000 coin in second round
						$userLastTrans = $this->Cointransactions->find('all',['conditions'=>['user_id'=>$currentUser,'created_at >='=>$roundSecondStartDate],'limit'=>1,'order'=>['id'=>'DESC']])->hydrate(false)->first();
						if(!empty($userLastTrans)) {
							$usersLastCoin = $userLastTrans['coin'];
						}
						$totalToPurchase = $usersLastCoin+$coinPurchaseVal;
						if($totalToPurchase>10000){
							$remainingCoin = 10000-$usersLastCoin; 
							$this->Flash->error(__("You can purchase only 10000 coins in second round. So you can purchase only ".$remainingCoin." coin More"));
							return $this->redirect('front/transactions/ico');
						}
						
					}
					
					
					if($roundSecondStartDate > $cuDateTime){
						// first round condition 
						if($getGrandTotalCoinCount >=500000){
							
							$this->Flash->error(__("First round's All Coins Sold Out. Now wait for second round. Second round will Start From 21-01-2018 13:30 IST"));
							return $this->redirect('front/transactions/ico'); 
						} 
						
						$getGrandTotalCoinCountWithPurchase = $getGrandTotalCoinCount+$coinPurchaseVal;
						if($getGrandTotalCoinCountWithPurchase >=500000){
							$canPurchase = 500000 - $getGrandTotalCoinCount; 
							$this->Flash->error(__("Now maximum coin for purchase is ".(int)$canPurchase));
							return $this->redirect('front/transactions/ico'); 
						} 
					}
					
					/* if($cuDateTime <= $roundFirstEndDate && $getGrandTotalCoinCount >=20){
						
						$this->Flash->error(__('All Coins Sold Out. Now Purchasing Start From 21-01-2018 01:30'));
						return $this->redirect('front/transactions/ico'); 
					}  */
					
					/* if($cuDateTime > $roundFirstEndDate){
						
						$userTotalCoin = $this->Cointransactions->find(); 
						$userTotalCoinCnt = $userTotalCoin
													->select(['sum' => $userTotalCoin->func()->sum('coin')])
													->where(array('user_id'=>$currentUser,'type'=>"purchase"))
													->toArray();
						
						$userTotalCoinCount = $userTotalCoinCnt[0]['sum'];
						
					} */
					
					// calculateTotalPurchanse end
					//$dollerPurchaseVal = $this->request->data['usd_amount'];
					$coinPurchaseVal = $this->request->data['coin_amount'];
					$coinPurchaseVal = abs($coinPurchaseVal); 
					if(empty($coinPurchaseVal) || $coinPurchaseVal<1){
						$this->Flash->error(__('Coin amount is required and should be greater than 0.'));
						return $this->redirect('front/transactions/ico');
					}
					
					
					
					$currentCoinPrice = $this->Token->find('all',['condition'=>['id'=>4]])->hydrate(false)->first();
					//$dollerPerCoin = $currentCoinPrice['price'];  
					$dollerPerCoin = $this->Users->getCoinPrice();
					
					$getBitJsonData = file_get_contents("https://blockchain.info/ticker");
					$getDecode = json_decode($getBitJsonData,true); 
					$dollerPerBtc = $getDecode['USD']['buy'];
					
					
					$dollerPurchaseVal = $dollerPerCoin*$coinPurchaseVal;
					$btcPurchaseVal = $dollerPurchaseVal/$dollerPerBtc;
					
					/* $btcPurchaseVal = $dollerPurchaseVal/$dollerPerBtc;
					$coinPurchaseVal = $dollerPurchaseVal/$dollerPerCoin; */
					
					//$btcPurchaseVal = $this->request->data['btc_amount'];
					//$coinPurchaseVal = $this->request->data['coin_amount'];
					
					
					//$btcPurchaseVal = $this->request->session()->read('calculatedBtc');
					
					//$btcPurchaseVal = $dollerPurchaseVal/$dollerPerBtc;
					//$coinPurchaseVal = $dollerPurchaseVal/$dollerPerCoin; 
					
					/* $getUserBtc = $this->Agctransactions->find('all',['conditions'=>['user_id'=>$currentUser,'status'=>'completed']])->hydrate(false)->first();
					if(!empty($getUserBtc)){
						$getUserBtcAmt = $getUserBtc['btc_coins'];
					} */
					
					$getUserBtcAmt  = $this->Users->getUserTotalBtc($currentUser);
					if($getUserBtcAmt < $btcPurchaseVal){
						$this->Flash->error(__('You have insufficient balance in Btc wallet.'));
						return $this->redirect('front/transactions/ico');
					}
					
					
					
					$newInsertArr = [];
					$newInsertArr['user_id'] = $currentUser;
					$newInsertArr['btc'] = $btcPurchaseVal;
					$newInsertArr['coin'] = $coinPurchaseVal;
					$newInsertArr['dollar'] = $dollerPurchaseVal;
					$newInsertArr['doller_per_hc'] = $dollerPerCoin;
					$newInsertArr['type'] = 'purchase';
					$newInsertArr['updated_at'] = $cudate;
					
					// insert data
					$purchaseCoinTransactions=$this->Cointransactions->newEntity();
					$purchaseCoinTransactions=$this->Cointransactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$saveData = $this->Cointransactions->save($purchaseCoinTransactions);
					$cointransactionsId = $saveData->id;
					
					
					
					//free coin insert 
					if($coinPurchaseVal>100){
						
						$getExpectFirst = substr($coinPurchaseVal,1);
						$remainAmt = $coinPurchaseVal-$getExpectFirst;
						$freeCoinPurchaseVal = $remainAmt/10;
						
						$freeInsertArr = [];
						$freeInsertArr['user_id'] = $currentUser;
						$freeInsertArr['btc'] = "";
						$freeInsertArr['coin'] = $freeCoinPurchaseVal;
						$freeInsertArr['dollar'] = "";
						$freeInsertArr['doller_per_hc'] = "";
						$freeInsertArr['type'] = 'free_coin';
						$freeInsertArr['updated_at'] = $cudate;
						
						// insert data
						$freeCoinTransactions=$this->Cointransactions->newEntity();
						$freeCoinTransactions=$this->Cointransactions->patchEntity($freeCoinTransactions,$freeInsertArr);
						$saveData = $this->Cointransactions->save($freeCoinTransactions);
						
					}
					
					if($saveData){
							
							// update user btc wallet start
							$remainingUserBtcAmount = $getUserBtcAmt-$btcPurchaseVal;
							$getUserBtcNew = $this->Users->get($currentUser);
							$getUserBtcData = $this->Users->patchEntity($getUserBtcNew,['total_btc'=>$remainingUserBtcAmount]);
							$updateBtcWallet = $this->Users->save($getUserBtcData);
							// update user btc wallet end
							
							// insert into transctions table Start
							$newTransArr['cointransactions_id']= $cointransactionsId;
							$newTransArr['user_id']= $currentUser;
							$newTransArr['wallet_address']= $this->Auth->user('btc_address');
							$newTransArr['btc_coins']= "-".$btcPurchaseVal;
							$newTransArr['trans_type']= "debit";
							$newTransArr['payment_date']= $cudate;
							$newTransArr['status']= "completed";
							$newTransArr['updated_at']= $cudate;
							$newTransArr['coin_type']= "hc purchase";
							
							$getTransNew = $this->Agctransactions->newEntity();
							$getTransNewData = $this->Agctransactions->patchEntity($getTransNew,$newTransArr);
							$updateBtcWallet = $this->Agctransactions->save($getTransNewData);
							// insert into transctions table End
							
							// calculation for referral user
							if(!empty($referralUserId)){
								$findReferralUser = $this->Users->find("all",['conditions'=>["id"=>$referralUserId]])->hydrate(false)->first(); 
								$getReferalSetting = $this->Referal->find("all")->hydrate(false)->first();  
								$referralPercent = $getReferalSetting['referal_percent']; 
								$dollerReferral = $dollerPurchaseVal*($referralPercent/100);
								$btcReferral = $dollerReferral/$dollerPerBtc;
								$coinReferral = $dollerReferral/$dollerPerCoin;
								
								$newReferalArr = [];
								$newReferalArr['user_id'] = $referralUserId;
								$newReferalArr['referral_user_id'] = $currentUser;
								$newReferalArr['btc'] = $btcReferral;
								$newReferalArr['coin'] = $coinReferral;
								$newReferalArr['dollar'] = $dollerReferral;
								$newReferalArr['doller_per_hc'] = $dollerPerCoin;
								$newReferalArr['type'] = 'referral';
								$newReferalArr['updated_at'] = $cudate;
								
								$referalTransactions=$this->Cointransactions->newEntity();
								$referalTransactions=$this->Cointransactions->patchEntity($referalTransactions,$newReferalArr);
								$saveReferaData = $this->Cointransactions->save($referalTransactions);
								//$cointransactionsId = $saveData->id;
									
							}
							$this->Flash->success(__('HC coin purchased successfully.'));
					}
					else {
						$this->Flash->error(__('Unable to purchase HC. Try Again.'));
						return $this->redirect('front/transactions/ico');
					}
				}
			}
			
			
			
			
			$currentUserWallet = $this->Auth->user('unique_id');
			$this->set('currentUserWallet',$currentUserWallet);
			
			/* $getUserTotalCoin = $this->Cointransactions->find(); 
			$getUserTotalCoinCnt = $getUserTotalCoin
										->select(['sum' => $getUserTotalCoin->func()->sum('coin')])
										->where(array('user_id'=>$currentUser))
										->toArray();
			
			$getUserTotalCoinCount = $getUserTotalCoinCnt[0]['sum']; */
			$getUserTotalCoinCount = $this->Users->getUserTotalCoin($currentUser);
			$this->set('getUserTotalCoinCount',$getUserTotalCoinCount);
			
			
			$limit = $this->setting['pagination'];
			
			$searchData = array();
			$searchData['AND'][] = array('user_id' => $this->Auth->user('id'),'coin !='=>0.0000000);
			$searchData['AND'][] = array('status' => 1);
			/* if($type=="referral" || $type=="bonus"){
				$searchData['AND'][] = array('status'=>'completed');	
			} */
			
			
			if ($this->request->is(['post' ,'put'])){
				
				$isSearch = $this->request->data['type'];
				
				if($isSearch == "purchase"){
					if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
					$search = $this->request->data;
					
					if($search['pagination'] != '') $limit =  $search['pagination'];
					//pr($search);die;
					
					if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
					else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
					else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
				}
				
				
				
			}
			$this->set('listing',$this->Paginator->paginate($this->Cointransactions, [
						     'conditions'=>$searchData,
						    'order'=>['Cointransactions.id'=>'desc'],
							'limit' => $limit,
						]));
			$this->set('type',$type);
			
			
		/* }else return $this->redirect(['controller'=>'pages','action' => 'dashboard']); */
	}
	
	public function transactionSearch()
	{
		
		$this->loadModel('Cointransactions');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = $search['type'];
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			
			
			$searchData = array();
			/* if($type=="referral" || $type=="bonus"){
				$searchData['AND'][] = array('status'=>'completed');	
			} */
			$searchData['AND'][] = array('user_id' => $this->Auth->user('id'),'coin !='=>0.0000000);
			$searchData['AND'][] = array('status' => 1);
			
			$limit = $this->setting['pagination'];
			
			 if($search['pagination'] != ''){
				 $limit =  $search['pagination'];
			 }
			
			if($search['start_date'] != '' && $search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
			}
			else if($search['start_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
			}
			else if($search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
			}
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$getData = $this->Paginator->paginate($this->Cointransactions, [
						    'conditions'=>$searchData,
						    'order'=>['Cointransactions.id'=>'desc'],
							'limit' => $limit
						]);
			//print_r($getData); die;			
			$this->set('listing',$getData);
		
			
			$this->set('type',$search['type']);
		}
	
	}
	
	public function withdrawal()
	{
		
	}
		
	
	public function btcDeposit(){
		$this->loadModel('Agctransactions');
		$userId = $this->Auth->user('id');
		$getCompletedAgcCoin = $this->Agctransactions->find(); 
		$getUserBtcCoin = $getCompletedAgcCoin
									->select(['sum' => $getCompletedAgcCoin->func()->sum('btc_coins')])
									->where(array('user_id'=>$userId,'status'=>'completed'))
									->toArray();
		
		$totalBtcCoin = $getUserBtcCoin[0]['sum'];
		$this->set('totalBtcCoin',$totalBtcCoin);
		
		$limit =  $this->setting['pagination'];
		$btcTrans = $this->Paginator->paginate($this->Agctransactions, [
            'conditions' => array('status'=>'completed',"user_id"=>$userId),
            'limit' => $limit,
            'order'=>['id'=>'desc']
		]);
		$this->set('btcTrans',$btcTrans);
	}
	
	public function btcsearch(){
		if ($this->request->is('ajax')) {
			$this->loadModel('Agctransactions');
			$userId = $this->Auth->user('id');
			$limit =  $this->setting['pagination'];
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$this->set('btcTrans', $this->Paginator->paginate($this->Agctransactions, [
				'conditions' => array('status'=>'completed',"user_id"=>$userId),
				'limit' => $limit,
				'order'=>['id'=>'desc']
			]));
		}
	}	
	
	public function btcWithdrawl(){
		$currentUser = $this->Auth->user('id');
		
		$this->loadModel('Agctransactions');
		$this->loadModel('Users');
		$this->loadModel('WithdrawalLog');
        $cudate = date("Y-m-d H:i:s");	
		
		$getUserBtcAmt  = $this->Users->getUserTotalBtc($currentUser);
		$this->set('getUserBtcAmt',$getUserBtcAmt);	
			$referralUserId = $this->Auth->user('referral_user_id');
			// for post request
			if ($this->request->is(['post'])) {
				/*if($currentUser!=372){
					$this->Flash->error(__('We are updating this feature. Try Again Later.'));
					return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'btcWithdrawl']);
				}*/
				$requestType = $this->request->data['request_type']; 
				// for transaction request start
				if($requestType == "transaction") {
					$btcWithdrawalVal = $this->request->data['btc_amount'];
					$btcWithdrawalVal = abs($btcWithdrawalVal);					
					$btcWithdrawalWalletAddr = $this->request->data['target_wallet_address'];		
					
					if($btcWithdrawalVal < 0.001){
						$this->Flash->error(__('Minimum withdrawal amount is 0.001.'));
						return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'btcWithdrawl']);
					}
					
					
					// get User Remaining Btc
					
					if($getUserBtcAmt < $btcWithdrawalVal){
						$this->Flash->error(__('You have insufficient balance in Btc wallet.'));
						return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'btcWithdrawl']);
					}
					
					// check for pending withdrawal transaction
					$withdrawalConditions = ["user_id"=>$currentUser,'coin_type'=>'withdrawal','trans_type'=>'debit','status'=>'pending'];
					$findWithDeawlTrans = $this->Agctransactions->find("all",['conditions'=>$withdrawalConditions])->hydrate(false)->first(); 
					if(!empty($findWithDeawlTrans)){
						$this->Flash->error(__('You have one pending withdrawal transaction. When it completed then you can request for new withdrawal transaction'));
						return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'btcWithdrawl']);
					} 
					
					
					$callWithDrewlApi = $this->Users->withdrawSingleBtcAmount($btcWithdrawalWalletAddr,$btcWithdrawalVal);
					
					file_put_contents("withdrawal_log.txt","===============>".date('Y-m-d H:i:s').json_encode($callWithDrewlApi).PHP_EOL,FILE_APPEND); // add response to log file
					
					
					if(!empty($callWithDrewlApi)) {
					// save log of withdrawal start
					
						if($callWithDrewlApi['status']=='fail'){
							$withdrawalArr['status'] = $callWithDrewlApi['status'];
							$withdrawalArr['error_message'] = $callWithDrewlApi['data']['error_message'];
						}
						else {
							$withdrawalArr['status'] = $callWithDrewlApi['status'];
							$withdrawalArr['network'] = $callWithDrewlApi['data']['network'];
							$withdrawalArr['txid'] = $callWithDrewlApi['data']['txid'];
							$withdrawalArr['amount_withdrawn'] = $callWithDrewlApi['data']['amount_withdrawn'];
							$withdrawalArr['amount_sent'] = $callWithDrewlApi['data']['amount_sent'];
							$withdrawalArr['network_fee'] = $callWithDrewlApi['data']['network_fee'];
							$withdrawalArr['blockio_fee'] = $callWithDrewlApi['data']['blockio_fee'];
						}
						
						$addData=$this->WithdrawalLog->newEntity();
						$addData=$this->WithdrawalLog->patchEntity($addData,$withdrawalArr);
						$addData = $this->WithdrawalLog->save($addData);
						// save log of withdrawal end
					}
					
					if(!empty($callWithDrewlApi) && $callWithDrewlApi['status']!='fail'){
						$transId = $callWithDrewlApi['data']['txid'];
						// insert into transactions table Start
						$newTransArr['cointransactions_id']= '';
						$newTransArr['user_id']= $currentUser;
						$newTransArr['wallet_address']= $btcWithdrawalWalletAddr;
						$newTransArr['btc_coins']= "-".$btcWithdrawalVal;
						$newTransArr['trans_type']= "debit";
						$newTransArr['payment_date']= $cudate;
						$newTransArr['status']= "completed";
						$newTransArr['admin_withdrawl_transfer']= "yes";
						$newTransArr['updated_at']= $cudate;
						$newTransArr['coin_type']= "withdrawal";
						$newTransArr['trans_id']= $transId;
						
						$getTransNew = $this->Agctransactions->newEntity();
						$getTransNewData = $this->Agctransactions->patchEntity($getTransNew,$newTransArr);
						$saveData = $this->Agctransactions->save($getTransNewData);
						// insert into transctions table End
						if($saveData){
							$this->Flash->success(__('Btc withdrawal request send successfully.'));
							return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'btcWithdrawl']);
						}
						else {
							$this->Flash->error(__('Unable to send Btc withdrawal request. Try Again.'));
							return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'btcWithdrawl']);
						}
					}
					else {
						$this->Flash->error('Unable to make transaction. Try Again !! Error : '.$callWithDrewlApi['data']['error_message']);
						$this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'btcWithdrawl']);
					}
				}
				
				
			}
			
		$searchData = array();
		$searchData['AND'][] = array('user_id' => $currentUser);
		$searchData['AND'][] = array('coin_type' => 'withdrawal');
		$searchData['AND'][] = array('trans_type' => 'debit');
		
		$limit = $this->setting['pagination'];
		
		$this->set('listing',$this->Paginator->paginate($this->Agctransactions, [
						 'conditions'=>$searchData,
						'order'=>['Agctransactions.id'=>'desc'],
						'limit' => $limit,
					]));
		//$this->set('type',$type);
			
	}
	
	
	
	public function btcWithdrawalSearch()
	{
		
		$this->loadModel('Agctransactions');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			
			$search = $this->request->data;
			
			
			
			$searchData = array();
			
			$searchData['AND'][] = array('user_id' => $currentUser);
			$searchData['AND'][] = array('coin_type' => 'withdrawal');
			$searchData['AND'][] = array('trans_type' => 'debit');
			
			$limit = $this->setting['pagination'];
			
			 if(isset($search['pagination']) & $search['pagination'] != ''){
				 $limit =  $search['pagination'];
			 }
			
			/* if($search['start_date'] != '' && $search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
			}
			else if($search['start_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
			}
			else if($search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
			}
			 */
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$getData = $this->Paginator->paginate($this->Agctransactions, [
						    'conditions'=>$searchData,
						    'order'=>['Agctransactions.id'=>'desc'],
							'limit' => $limit
						]);
			//print_r($getData); die;			
			$this->set('listing',$getData);
		
		}
	
	}

	public function ico(){
		
		$this->set('title' , 'HC : Lending');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Referal');
		
		$authUser = $this->Auth->user();
		$this->set('authUser',$authUser);
		
		$type = "purchase";
		
		$coin_arr=['referral','purchase','bonus'];
		if(!in_array($type,$coin_arr)){
			$type = "purchase";
		}	
		$cudate = date("Y-m-d H:i:s");	
		$this->set('display_type','AGC');
		// for purchase
		$currentUser = $this->Auth->user('id');
		$referralUserId = $this->Auth->user('referral_user_id');
		
		
		$currentUserWallet = $this->Auth->user('unique_id');
		$this->set('currentUserWallet',$currentUserWallet);
		
		/*$ getUserTotalCoin = $this->Cointransactions->find(); 
		$getUserTotalCoinCnt = $getUserTotalCoin
									->select(['sum' => $getUserTotalCoin->func()->sum('coin')])
									->where(array('user_id'=>$currentUser))
									->toArray(); */
		
		$getUserTotalCoinCount = $this->Users->getUserTotalCoin($currentUser);
		$this->set('getUserTotalCoinCount',$getUserTotalCoinCount);
		
		
		$limit = $this->setting['pagination'];
		
		$searchData = array();
		$searchData['AND'][] = array('user_id' => $this->Auth->user('id'));
		$searchData['AND'][] = array('status' => 1);
		
		
		
		$this->set('listing',$this->Paginator->paginate($this->Cointransactions, [
						 'conditions'=>$searchData,
						'order'=>['Cointransactions.id'=>'desc'],
						'limit' => $limit,
					]));
		$this->set('type',$type);
		
		
		$getBitJsonData = file_get_contents("https://blockchain.info/ticker");
		$getDecode = json_decode($getBitJsonData,true); 
		$buyUsd = $getDecode['USD']['buy'];
		$this->set('buyUsd',$buyUsd);
		
		$query = $this->Agctransactions->find(); 
			
		
		$totalAMXCoin = $this->Token->find('all',['conditions'=>['id'=>4]])->hydrate(false)->first();
		$this->set('totalAMXCoin',$totalAMXCoin);
		
		//$coinPrice = $totalAMXCoin['price'];
		$coinPrice =  $this->Users->getCoinPrice();
		$this->set('coinPrice',$coinPrice);
		
		
		// bulk calculation
		$bulkCoins = 500000;
		$bulkPerCoinRate = 5;
		$this->set('bulkPerCoinRate',$bulkPerCoinRate); 
		$bulkTotalPrice = $bulkCoins*$bulkPerCoinRate; 
		$bulkBtc = $bulkTotalPrice/$buyUsd;
		$bulkBtc = number_format((float)$bulkBtc,8);
		
		
	}
	
	
	public function icoSearch()
	{
		
		$this->loadModel('Cointransactions');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			
			
			$searchData = array();
			/* if($type=="referral" || $type=="bonus"){
				$searchData['AND'][] = array('status'=>'completed');	
			} */
			$searchData['AND'][] = array('user_id' => $this->Auth->user('id'));
			$searchData['AND'][] = array('status' => 1);
			
			$limit = $this->setting['pagination'];
			
			 if( isset($search['pagination']) && $search['pagination'] != ''){
				 $limit =  $search['pagination'];
			 }
			
			if( isset($search['start_date']) && isset($search['end_date']) && $search['start_date'] != '' && $search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
			}
			else if( isset($search['start_date']) && $search['start_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
			}
			else if( isset($search['end_date']) && $search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
			}
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$getData = $this->Paginator->paginate($this->Cointransactions, [
						    'conditions'=>$searchData,
						    'order'=>['Cointransactions.id'=>'desc'],
							'limit' => $limit
						]);
			//print_r($getData); die;			
			$this->set('listing',$getData);
		
			
			$this->set('type',$search['type']);
		}
	
	}


	public function lending(){
		
		$this->set('title' , 'HC : Lending');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Investment');
		$this->loadModel('LandingProgram');
		$authUser = $this->Auth->user();
		$this->set('authUser',$authUser);
		$userId = $this->Auth->user('id');
		$this->set('userId',$userId);
		
		$LchTime = strtotime("2018-02-16 13:30:00"); 
		$cudate = date("Y-m-d H:i:s");
		$getUserTotalCoin = $this->Users->getUserTotalCoin($userId);
		$this->set('getUserTotalCoin',$getUserTotalCoin);
		
		if ($this->request->is(['post' ,'put']) ) {
			
			/* if($userId!=372){
				//$this->Flash->error(__('This functionality is disabled. Try Again Later'));
				if($cudate<$LchTime){
					//return $this->redirect('front/transactions/ico');
				}
			} */
			
			
			
			$getAmount = $this->request->data['amount'];
			$getAmount = abs($getAmount);
			if($getAmount<100){
				$this->Flash->error(__('Minimum Landing Amount is 100 USD.'));
				return $this->redirect('front/transactions/lending');
			}
			
			
			$pricePerCoin = 6;
			
			$coinByInvestment = $getAmount/6; 
			$coinByInvestment = round($coinByInvestment,8);
			//$coinByInvestment = number_format((float)$coinByInvestment, 8); 
			/*  if($userId==372){
			 echo $coinByInvestment; die;
			 } */
			
			
			if($coinByInvestment > $getUserTotalCoin){
				$this->Flash->error(__('You have insufficient balance in HC wallet.'));
				return $this->redirect('front/transactions/lending');
			}
			
			
			$getPercent = $this->LandingProgram->find("all",["conditions"=>["start_range <="=>$getAmount,"end_range >="=>$getAmount]])->hydrate(false)->first();
			$landingPercent = $getPercent['percent'];
			$landingReserveDays = $getPercent['reserve_days'];
			$landingId = $getPercent['id'];
			
				
			
			$insertArr = []; 
			$insertArr['user_id'] = $userId; 
			$insertArr['amount'] = $getAmount;
			$insertArr['type'] = "investment";
			$insertArr['amount_reserve_days'] = $landingReserveDays;
			$insertArr['percent'] = $landingPercent;
			$insertArr['status'] = "completed";			

			// save investment data
			$insertInvestment=$this->Investment->newEntity();
			$insertInvestment=$this->Investment->patchEntity($insertInvestment,$insertArr);
			$saveData = $this->Investment->save($insertInvestment);
			if($saveData) {
				
				if($userId==372){
					// total investment of user 
					$updateUserData = $this->Users->updateReserveDays($userId);
				}
				
				$investmentId = $saveData->id;
				
				
				// insert into transctions table Start
				$newTransArr['investment_id']= $investmentId;
				$newTransArr['user_id']= $userId;
				$newTransArr['wallet_address']= $this->Auth->user('btc_address');
				$newTransArr['coin']= "-".$coinByInvestment;
				$newTransArr['type']= "investment";
				$newTransArr['status']= 1;
				$newTransArr['updated_at']= $cudate;
				$newTransArr['coin_type']= "investment";
				
				$getTransNew = $this->Cointransactions->newEntity();
				$getTransNewData = $this->Cointransactions->patchEntity($getTransNew,$newTransArr);
				$getTransNewDataResult = $this->Cointransactions->save($getTransNewData);
				// insert into transctions table End
				
				
				$this->Flash->success(__('Investment Made Successfully'));
				return $this->redirect('front/transactions/lending');
			}
			else {
				$this->Flash->error(__('Unable to make Investment. Try Again'));
				return $this->redirect('front/transactions/lending');
			}

			//
			
			
		}
		
		
		
		$getBitJsonData = file_get_contents("https://blockchain.info/ticker");
		$getDecode = json_decode($getBitJsonData,true); 
		$buyUsd = $getDecode['USD']['buy'];
		$this->set('buyUsd',$buyUsd);
		
		$query = $this->Agctransactions->find(); 
			
		
		$totalAMXCoin = $this->Token->find('all')->hydrate(false)->first();
		$this->set('totalAMXCoin',$totalAMXCoin);
		
		$coinPrice = $totalAMXCoin['price'];
		$this->set('coinPrice',$coinPrice);
		$getUserTotalInvestmentAmount = 0.00;
		$getUserTotalInvestment = $this->Investment->find(); 
		$getUserTotalInvestmentAmount = $getUserTotalInvestment
									->select(['sum' => $getUserTotalInvestment->func()->sum('amount')])
									->where(array('user_id'=>$userId))
									->toArray();
		if(!empty($getUserTotalInvestmentAmount) && $getUserTotalInvestmentAmount[0]['sum']) {
			$getUserTotalInvestmentAmount = $getUserTotalInvestmentAmount[0]['sum'];
		}
		$this->set('getUserTotalInvestmentAmount',$getUserTotalInvestmentAmount);
		
		
		$limit = $this->setting['pagination'];
		$searchData = array();
		$searchData['AND'][] = array('user_id' => $userId);
		$this->set('listing',$this->Paginator->paginate($this->Investment, [
									'conditions'=>$searchData,
									'order'=>['Investment.id'=>'desc'],
									'limit' => $limit,
								]));
		
		
	}



	public function lendingSearch()
	{
		$userId = $this->Auth->user('id');
		$this->loadModel('Investment');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			
			$search = $this->request->data;
			
			
			
			$searchData = array();
			
			$searchData['AND'][] = array('user_id' => $userId);
			
			$limit = $this->setting['pagination'];
			
			 if(isset($search['pagination']) && $search['pagination'] != ''){
				 $limit =  $search['pagination'];
			 }
			
			/* if($search['start_date'] != '' && $search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
			}
			else if($search['start_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
			}
			else if($search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
			}
			 */
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			
			$this->set('listing',$this->Paginator->paginate($this->Investment, [
									'conditions'=>$searchData,
									'order'=>['Investment.id'=>'desc'],
									'limit' => $limit,
								]));
		
		}
	
	}
	
	
	public function updatedays(){
		$this->loadModel('Users');
		$this->loadModel('Investment');
		$this->loadModel('LandingProgram');
		
		$getUserTotalCoin = $this->Investment->find(); 
		$getUserTotalCoinCnt = $getUserTotalCoin
									->select(['amount','id'])
									->where(array('type'=>'investment'))
									//->group('user_id')
									->toArray();
									
		foreach($getUserTotalCoinCnt as $singleVal){
			
			$getAmount = $singleVal['amount'];
			$getInvestmentId = $singleVal['id'];
			
			$getData = $this->LandingProgram->find("all",["conditions"=>["start_range <="=>$getAmount,"end_range >="=>$getAmount]])->hydrate(false)->first();
			$getReserveDays = $getData['reserve_days'];
			
			$users  = $this->Investment->get($getInvestmentId);
			$users = $this->Investment->patchEntity($users, [
					'amount_reserve_days'  => $getReserveDays
				]);
			$this->Investment->save($users);
			
			
			//$this->Users->updateReserveDays($getUserId);
		}							
									
		die('s');							
		
	}
    

   public function exchange(){
		die("Wrong Way");
		$this->set('title' , 'HC : Exchange');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Exchange');
		$this->loadModel('Referal');
		
		$this->Flash->error(__("Internal Exchange relaunch April 15."));
			return $this->redirect('front/transactions/ico');
		$showUserList = [311,372,376,600,1794,11978,12461,15842,26267,44104,54522,54523,54524,54525,54526];
		
		$authUser = $this->Auth->user();
		
		
		$this->set('authUser',$authUser);
		
		$type = "purchase";
		
		$coin_arr=['referral','purchase','bonus'];
		if(!in_array($type,$coin_arr)){
			$type = "purchase";
		}	
		$cudate = date("Y-m-d H:i:s");	
		$this->set('display_type','AGC');
		// for purchase
		$currentUser = $this->Auth->user('id');
		
		/* if(!in_array($currentUser,$showUserList)){
			$this->Flash->error(__("Exchange Functionality has disabled For 1 day"));
			return $this->redirect('front/transactions/ico');
		} */
		
		$notShowSellOrderBox = 0;
		$otherUsers ="no";
		if(!in_array($currentUser,$showUserList)){
			//$this->Flash->error(__("Exchange Functionality is disabled"));
			//return $this->redirect('front/transactions/ico');
			//$notShowSellOrderBox = 1;
			//$otherUsers ="yes";
			$notShowSellOrderBox = 0;
			$otherUsers ="no";
		}
		$this->set('notShowSellOrderBox',$notShowSellOrderBox);
		$this->set('otherUsers',$otherUsers);
		
		$referralUserId = $this->Auth->user('referral_user_id');
		// code start
		$gerUserTotalBtc = $this->Users->getUserTotalBtc($currentUser);
		$this->set('gerUserTotalBtc',$gerUserTotalBtc);
		
		$btcPerHcBuy = $this->Users->getBtcPricePerHcBuy();
		$this->set('btcPerHcBuy',$btcPerHcBuy);
		
		$gerUserTotalHc = $this->Users->getUserTotalHc($currentUser);
		$this->set('gerUserTotalHc',$gerUserTotalHc);
		
		$btcPerHcSell = $this->Users->getBtcPricePerHcSell();
		$this->set('btcPerHcSell',$btcPerHcSell);
		$buy_fee = 0.25;
		
		
		if($this->request->is('post')){
			$getFromType = $this->request->data['type'];
			
			if($getFromType=="buy"){
				$opertationType = "insert";
				$btcSpend = abs($this->request->data['btc_spend']);
				$pricePerHc = abs($this->request->data['price_per_hc']);
				$hcReceive = abs($this->request->data['hc_receive']);				
				
				// check for empty values
				if(empty($btcSpend) || empty($pricePerHc) || empty($hcReceive)){
					$this->Flash->error(__("All Field are required"));
					return $this->redirect('front/transactions/exchange');
				}
				
				// check for btc balance
				if($btcSpend > $gerUserTotalBtc){
					$this->Flash->error(__("Insufficient Balance in BTC wallet"));
					return $this->redirect('front/transactions/exchange');
				}
				$btcSpend = round($btcSpend,8);
				$pricePerHc = round($pricePerHc,8);
				$adminFee = $btcSpend*($buy_fee/100);
				$adminFee = round($adminFee,8);
				$remainingBtcSpend = $btcSpend-$adminFee;
				$remainingBtcSpend = round($remainingBtcSpend,8);
				$hcReceiveAmt = $remainingBtcSpend/$pricePerHc;
				$hcReceiveAmt = round($hcReceiveAmt,8);
				//$hcReceiveAmt = round($hcReceiveAmt,8);
				
				
				//find exist exchange for buy
				$findCoinditions = [];
				$findCoinditions['sell_btc_amount'] = $btcSpend;
				$findCoinditions['price_per_hc'] = $pricePerHc;
				//$findCoinditions['buy_hc_amount'] = $hcReceiveAmt;
				$findCoinditions['seller_user_id IS NOT'] = NULL;
				$findCoinditions['buyer_user_id IS'] = NULL;
				$findCoinditions['status'] = "pending";
				
				
			    
				$newInsertArr = [];
				$newInsertArr['buyer_user_id'] = $currentUser;
				$newInsertArr['buy_btc_amount'] = $btcSpend;
				$newInsertArr['price_per_hc'] = $pricePerHc;
				$newInsertArr['buy_hc_amount'] = $hcReceiveAmt;
				$newInsertArr['buy_fees'] = $adminFee;
				$newInsertArr['status'] = 'pending';
				
				
				// insert data
				$findExchange = $this->Exchange->find('all',['conditions'=>$findCoinditions])->hydrate(false)->first();
				
				$exchangeTransactions=$this->Exchange->newEntity();
				if(!empty($findExchange)){
					$exchangeId = $findExchange['id'];
					$exchangeTransactions = $this->Exchange->get($exchangeId);
					$newInsertArr = [];
					$newInsertArr['buyer_user_id'] = $currentUser;
					$newInsertArr['buy_btc_amount'] = $btcSpend;
					$newInsertArr['buy_hc_amount'] = $hcReceiveAmt;
					$newInsertArr['buy_fees'] = $adminFee;
					$newInsertArr['status'] = 'completed';
					$newInsertArr['updated_at']= $cudate;
					$opertationType = "update";
				}
				
				$exchangeTransactions=$this->Exchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->Exchange->save($exchangeTransactions);
				
				$hc = $hcReceiveAmt;
				$btc = $btcSpend;
				$hcSign = "";
				$btcSign = "-";
				$transType = "debit";
				$exchangeType = "buy_exchange";
			}
			
			if($getFromType=="sell"){
				$opertationType = "insert";
				$btcSpend = abs($this->request->data['btc_receive']);
				$pricePerHc = abs($this->request->data['price_per_hc_sell']);
				$hcReceive = abs($this->request->data['hc_spend']);				
				
				$existSellHcPending = $this->Users->getUserSellExchangeCoin($currentUser);
				
				if($existSellHcPending > 250){
					$this->Flash->error(__("Maximun Sell Order Limit is 250"));
					return $this->redirect('front/transactions/exchange');
				}
				
				// check for empty values
				if(empty($btcSpend) || empty($pricePerHc) || empty($hcReceive)){
					$this->Flash->error(__("All Field are required"));
					return $this->redirect('front/transactions/exchange');
				}
				
				// check for hc balance
				if($hcReceive > $gerUserTotalHc){
					$this->Flash->error(__("Insufficient Balance in HC wallet"));
					return $this->redirect('front/transactions/exchange');
				}
				
				
				
				$pricePerHc = round($pricePerHc,8);
				$hcReceive = round($hcReceive,8);
				$btcReceive =  $hcReceive*$pricePerHc;
				$btcReceive = round($btcReceive,8);
				$adminFee = $btcReceive*($buy_fee/100);
				$adminFee = round($adminFee,8);
				$remainingBtcReceive = $btcReceive-$adminFee;
				$remainingBtcReceive = round($remainingBtcReceive,8);
				
				
				
				
				//find exist exchange for buy
				$findCoinditions = [];
				$findCoinditions['buy_btc_amount'] = $remainingBtcReceive;
				$findCoinditions['price_per_hc'] = $pricePerHc;
				//$findCoinditions['hc_amount'] = $hcReceive;
				$findCoinditions['buyer_user_id IS NOT'] = NULL;
				$findCoinditions['seller_user_id IS'] = NULL;
				$findCoinditions['status'] = "pending";
				
				
			    
				$newInsertArr = [];
				$newInsertArr['seller_user_id'] = $currentUser;
				$newInsertArr['sell_btc_amount'] = $remainingBtcReceive;
				$newInsertArr['price_per_hc'] = $pricePerHc;
				$newInsertArr['sell_hc_amount'] = $hcReceive;
				$newInsertArr['sell_fees'] = $adminFee;
				$newInsertArr['status'] = 'pending';
				
				 
				// insert data
				$findExchange = $this->Exchange->find('all',['conditions'=>$findCoinditions])->hydrate(false)->first();
				
				$exchangeTransactions=$this->Exchange->newEntity();
				if(!empty($findExchange)){
					$exchangeId = $findExchange['id'];
					$exchangeTransactions = $this->Exchange->get($exchangeId);
					$newInsertArr = [];
					$newInsertArr['seller_user_id'] = $currentUser;
					$newInsertArr['sell_btc_amount'] = $remainingBtcReceive;
					$newInsertArr['sell_hc_amount'] = $hcReceive;
					$newInsertArr['sell_fees'] = $adminFee;
					$newInsertArr['status'] = 'completed';
					$newInsertArr['updated_at']= $cudate;
					$opertationType = "update";
				}
				
				$exchangeTransactions=$this->Exchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->Exchange->save($exchangeTransactions);
				
				$hc = $hcReceive;
				$btc = $remainingBtcReceive;
				$hcSign = "-";
				$btcSign = "";
				$transType = "credit";
				$exchangeType = "sell_exchange";
			}
			
			
			// after save into exchange table
			if($saveData){
				$exchangeId = $saveData->id;
				
				
				$agcStautus = "pending";
				// calculation for btc exchange
				$findAgcTrans = $this->Agctransactions->find('all',['conditions'=>['exchange_id'=>$exchangeId]])->hydrate(false)->first();
				if(!empty($findAgcTrans)){
					$agcStautus = "completed";
					$getAgcId = $findAgcTrans['id'];
					$getTransNew = $this->Agctransactions->get($getAgcId);
					$newTransArr = [];
					$newTransArr['status']= "completed";
					$newTransArr['updated_at']= $cudate;
					$getTransNewData = $this->Agctransactions->patchEntity($getTransNew,$newTransArr);
					$updateBtcWallet = $this->Agctransactions->save($getTransNewData);
				}
				
				
				$newTransArr = [];
				$newTransArr['exchange_id']= $exchangeId;
				$newTransArr['user_id']= $currentUser;
				$newTransArr['btc_coins']= $btcSign.$btc;
				$newTransArr['trans_type']= $transType;
				$newTransArr['status']= $agcStautus;
				$newTransArr['updated_at']= $cudate;
				$newTransArr['coin_type']= $exchangeType;
				
				
				$getTransNew = $this->Agctransactions->newEntity();
				/* if(!empty($findAgcTrans)){
					$getAgcId = $findAgcTrans['id'];
					$getTransNew = $this->Agctransactions->get($getAgcId);
					$newTransArr = [];
					$newTransArr['status']= "completed";
					$newTransArr['updated_at']= $cudate;
				} */
				
				$getTransNewData = $this->Agctransactions->patchEntity($getTransNew,$newTransArr);
				$updateBtcWallet = $this->Agctransactions->save($getTransNewData);
				// insert into transctions table End
				
				// calculation for coin exchange
				$coinStatus = 0;
				$findCoinTrans = $this->Cointransactions->find('all',['conditions'=>['exchange_id'=>$exchangeId]])->hydrate(false)->first();
				
				if(!empty($findCoinTrans)){
					$coinStatus = 1;
					$getCoinId = $findCoinTrans['id'];
					$coinTransactions = $this->Cointransactions->get($getCoinId);
					$newReferalArr = [];
					$newReferalArr['status']= 1;
					$newReferalArr['updated_at']= $cudate;
					$updateaCoinTransactions=$this->Cointransactions->patchEntity($coinTransactions,$newReferalArr);
					$updaetCoinData = $this->Cointransactions->save($updateaCoinTransactions);
				} 
				
				$newReferalArr = [];
				$newReferalArr['exchange_id']= $exchangeId;
				$newReferalArr['user_id'] = $currentUser;
				$newReferalArr['btc'] = $btc;
				$newReferalArr['coin'] = $hcSign.$hc;
				$newReferalArr['doller_per_hc'] = $pricePerHc;
				$newReferalArr['type'] = $exchangeType;
				$newReferalArr['status'] = $coinStatus;
				$newReferalArr['updated_at'] = $cudate;
				
				$coinTransactions=$this->Cointransactions->newEntity();
				
				
				$coinTransactions=$this->Cointransactions->patchEntity($coinTransactions,$newReferalArr);
				$saveReferaData = $this->Cointransactions->save($coinTransactions);
					
				$this->Flash->success(__(ucfirst($getFromType)." Order Created Successfully."));
				return $this->redirect('front/transactions/exchange');
			}
			else {
				$this->Flash->error(__("Unable to Create ".$getFromType." Order ! Try Again."));
				return $this->redirect('front/transactions/exchange');
			}
				
		}
		
		// code end
		
		
		$limit = $this->setting['pagination'];
		
		// for Buy Order List Of Current User
		$searchData = array();
		$searchData['status']= "pending";
		$searchData['buyer_user_id !=']= $currentUser;
		
	
		$this->set('buyListing',$this->Paginator->paginate($this->Exchange, [
						 'conditions'=>$searchData,
						'order'=>['Exchange.id'=>'desc'],
						'limit' => $limit,
					]));
		
					
		// for seller Order List Of Current User
		$searchData = array();
		$searchData['status']= "pending";
		$searchData['seller_user_id !=']= $currentUser;
		
		
	
		$this->set('sellListing',$this->Paginator->paginate($this->Exchange, [
						 'conditions'=>$searchData,
						'order'=>['Exchange.id'=>'desc'],
						'limit' => $limit,
					]));		
		$this->set('type',$type);
		
		
		
		// current User Orders
		// for Buy Order List Of Current User
		$searchData = array();
		//$searchData['status']= "pending";
		$searchData['buyer_user_id =']= $currentUser;
		
	
		$this->set('myBuyListing',$this->Paginator->paginate($this->Exchange, [
						 'conditions'=>$searchData,
						'order'=>['Exchange.id'=>'desc'],
						'limit' => $limit,
					]));
		
					
		// for seller Order List Of Current User
		$searchData = array();
		//$searchData['status']= "pending";
		$searchData['seller_user_id =']= $currentUser;
		
		
	
		$this->set('mySellListing',$this->Paginator->paginate($this->Exchange, [
						 'conditions'=>$searchData,
						'order'=>['Exchange.id'=>'desc'],
						'limit' => $limit,
					]));		
		$this->set('type',$type);
		
		
		
		
		
		
	}
	
	
	public function buyExchangeSearch()
	{
		
		$this->loadModel('Exchange');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			$currentUser = $this->Auth->user('id');
		
			$searchData = array();
			
			$searchData['AND'][] = array('buyer_user_id !=' => $currentUser);
			$searchData['AND'][] = array('status ' => "pending");
			
			$limit = $this->setting['pagination'];
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$this->set('listing',$this->Paginator->paginate($this->Exchange, [
						'conditions'=>$searchData,
						'order'=>['Exchange.id'=>'desc'],
						'limit' => $limit,
					]));
			//print_r($getData); die;			
			//$this->set('listing',$getData);
		
			
			$this->set('type',$search['type']);
		}
	
	}
	
	
	public function sellExchangeSearch()
	{
		
		$this->loadModel('Exchange');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			$currentUser = $this->Auth->user('id');
		
			$searchData = array();
			
			$searchData['AND'][] = array('seller_user_id !=' => $currentUser);
			$searchData['AND'][] = array('status ' => "pending");
			
			$limit = $this->setting['pagination'];
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$this->set('listing',$this->Paginator->paginate($this->Exchange, [
						'conditions'=>$searchData,
						'order'=>['Exchange.id'=>'desc'],
						'limit' => $limit,
					]));
			//print_r($getData); die;			
			//$this->set('listing',$getData);
		
			
			$this->set('type',$search['type']);
		}
	
	}



	public function myBuyExchangeSearch()
	{
		
		$this->loadModel('Exchange');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			$currentUser = $this->Auth->user('id');
		
			$searchData = array();
			
			$searchData['AND'][] = array('buyer_user_id =' => $currentUser);
			//$searchData['AND'][] = array('status ' => "pending");
			
			$limit = $this->setting['pagination'];
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$this->set('listing',$this->Paginator->paginate($this->Exchange, [
						'conditions'=>$searchData,
						'order'=>['Exchange.id'=>'desc'],
						'limit' => $limit,
					]));
			//print_r($getData); die;			
			//$this->set('listing',$getData);
		
			
			$this->set('type',$search['type']);
		}
	
	}
	
	
	public function mySellExchangeSearch()
	{
		
		$this->loadModel('Exchange');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			$currentUser = $this->Auth->user('id');
		
			$searchData = array();
			
			$searchData['AND'][] = array('seller_user_id =' => $currentUser);
			//$searchData['AND'][] = array('status ' => "pending");
			
			$limit = $this->setting['pagination'];
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$this->set('listing',$this->Paginator->paginate($this->Exchange, [
						'conditions'=>$searchData,
						'order'=>['Exchange.id'=>'desc'],
						'limit' => $limit,
					]));
			//print_r($getData); die;			
			//$this->set('listing',$getData);
		
			
			$this->set('type',$search['type']);
		}
	
	}	
	
	
	 public function exchangeDelete($id=null){
		 
		if($id==null && $id == ''){
			$this->Flash->error(__("Exchange Can't be delete"));
			return $this->redirect('front/transactions/exchange'); 
		}
		
		$this->set('title' , 'HC : Exchange');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Exchange');
		$this->loadModel('Referal');
		
		$currentUser = $this->Auth->user('id');
		
		$findCoinditions = [];
		$findCoinditions['id'] = $id;
		$findCoinditions['status'] = 'pending';
		$findCoinditions['OR'][] = ['seller_user_id'=>$currentUser];
		$findCoinditions['OR'][] = ['buyer_user_id'=>$currentUser];
		
		 
		// insert data
		$findExchange = $this->Exchange->find('all',['conditions'=>$findCoinditions])->hydrate(false)->toArray();
		//print_r($findExchange); die;
		if(empty($findExchange)){
			$this->Flash->error(__("Unable to delete exchange, Try Again"));
			return $this->redirect('front/transactions/exchange'); 
		}
		
		$exchangeRecord = $this->Exchange->query()->delete()->where(['id' => $id])->execute();
		if($exchangeRecord){
			// delete agc Transaction
			$findAgcTrans = $this->Agctransactions->find('all',['conditions'=>['exchange_id'=>$id,'status'=>'pending']])->hydrate(false)->first();
			if(!empty($findAgcTrans)){
				$agcTransId = $findAgcTrans['id'];
				$delteAgcTrans = $this->Agctransactions->query()->delete()->where(['id' => $agcTransId])->execute();
				//$delteAgcTrans = $delteAgcTrans->delete();
			}
			
			// delete agc Transaction
			$findCoinTrans = $this->Cointransactions->find('all',['conditions'=>['exchange_id'=>$id,'status'=>0]])->hydrate(false)->first();
			if(!empty($findCoinTrans)){
				$coinTransId = $findCoinTrans['id'];
				$delteCoinTrans = $this->Cointransactions->query()->delete()->where(['id' => $coinTransId])->execute();
				
			}
			$this->Flash->success(__("Exchange Deleted Successfully"));
			return $this->redirect('front/transactions/exchange');  
		}
		else {
			$this->Flash->error(__("Unable to delete exchange, Try Again"));
			return $this->redirect('front/transactions/exchange');
		}
		
		
		
	 }
	 
	 
	 
	public function newexchange(){
		$curerntDate = time(); 

		$LchTime = strtotime("2018-04-15 13:30:00"); 

		$getDiff = $LchTime - $curerntDate;
		
		

		$this->set('title' , 'HC : Exchange');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Exchange');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('Referal');
		$this->loadModel('ExchangeHistory');
		
		//$this->Flash->error(__("We are working on your orders. Exchange will open again soon.".));
		//	return $this->redirect('front/transactions/ico');
		$showUserList = [311,372,376,600,1794,11978,12461,15842,26267,44104,54522,54523,54524,54525,54526];
		
		$authUser = $this->Auth->user();
		
		
		$this->set('authUser',$authUser);
		
		$type = "purchase";
		
		$coin_arr=['referral','purchase','bonus'];
		if(!in_array($type,$coin_arr)){
			$type = "purchase";
		}	
		$cudate = date("Y-m-d H:i:s");	
		$this->set('display_type','AGC');
		// for purchase
		$currentUser = $this->Auth->user('id');
		
		/* if(!in_array($currentUser,$showUserList)){
			$this->Flash->error(__("Exchange Functionality has disabled For 1 day"));
			return $this->redirect('front/transactions/ico');
		} */
		
		$notShowSellOrderBox = 0;
		$otherUsers ="no";
		if(!in_array($currentUser,$showUserList)){
			//$this->Flash->error(__("Exchange Functionality is disabled"));
			//return $this->redirect('front/transactions/ico');
			//$notShowSellOrderBox = 1;
			//$otherUsers ="yes";
			$notShowSellOrderBox = 0;
			$otherUsers ="no";
		}
		$this->set('notShowSellOrderBox',$notShowSellOrderBox);
		$this->set('otherUsers',$otherUsers);
		
		$referralUserId = $this->Auth->user('referral_user_id');
		// code start
		$gerUserTotalBtc = $this->Users->getUserTotalBtc($currentUser);
		$this->set('gerUserTotalBtc',$gerUserTotalBtc);
		
		$btcPerHcBuy = $this->Users->getBtcPricePerHcBuy();
		$this->set('btcPerHcBuy',$btcPerHcBuy);
		
		$gerUserTotalHc = $this->Users->getUserTotalHc($currentUser);
		$this->set('gerUserTotalHc',$gerUserTotalHc);
		
		$btcPerHcSell = $this->Users->getBtcPricePerHcSell();
		$this->set('btcPerHcSell',$btcPerHcSell);
		$buy_fee = 0.25;
		
		$tokenWalletAddress = $this->Auth->user('token_wallet_address');
		$this->set('tokenWalletAddress',$tokenWalletAddress);
		
		// get balance
		$getBalanceOfRealToken = $this->Users->getCoinBalance($tokenWalletAddress);
		$this->set('getBalanceOfRealToken',$getBalanceOfRealToken);
		$getBalanceOfRealToken = json_decode($getBalanceOfRealToken,true);
		
		if($this->request->is('post')){
			
			if($getDiff>0){ die("Exchange will open soon"); }

			$getBitJsonData = file_get_contents("https://blockchain.info/ticker"); 
			$getDecode = json_decode($getBitJsonData,true);
			$pricePerBtc = $getDecode['USD']['buy'];
			$btcAmountInTenCent = 0.1/$getDecode['USD']['buy'];
			
			$getFromType = $this->request->data['type'];
			
			//get trade rates 
			
			$getExchangHistoryData = $this->ExchangeHistory->find('all',array('conditions'=>array('ExchangeHistory.status in'=>['processing','completed']),
																		  'contain'=>['sell_exchange','buy_exchange']))
														->order(['ExchangeHistory.id'=>'desc'])
														->limit(2)
														->hydrate(false)
														->first();
														
			$getBuyTradePrice = $getExchangHistoryData['buy_exchange']['price_per_hc'];											
			$getSellTradePrice = $getExchangHistoryData['sell_exchange']['price_per_hc'];											
			
			$getFivePercentOfBuyTradePrice = ($getBuyTradePrice*5)/100;
			$plusBuyFivePercentAmt = $getBuyTradePrice+$getFivePercentOfBuyTradePrice;
			$minusBuyFivePercentAmt = $getBuyTradePrice-$getFivePercentOfBuyTradePrice;
			
			$getFivePercentOfSellTradePrice = ($getSellTradePrice*5)/100;
			$plusSellFivePercentAmt = $getSellTradePrice+$getFivePercentOfSellTradePrice;
			$minusSellFivePercentAmt = $getSellTradePrice-$getFivePercentOfSellTradePrice;
			
			
			
			
			if($getFromType=="buy"){
				$opertationType = "insert";
				$btcSpend = abs($this->request->data['btc_spend']);
				$pricePerHc = abs($this->request->data['price_per_hc']);
				$hcReceive = abs($this->request->data['hc_receive']);				
				
				if($btcAmountInTenCent > $pricePerHc){
					$this->Flash->error(__("Minimum set price 0.10 USD"));
					return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'newexchange']);
				}
				
				// check for empty values
				if(empty($btcSpend) || empty($pricePerHc) || empty($hcReceive)){
					$this->Flash->error(__("All Field are required"));
					return $this->redirect('front/transactions/newexchange');
				}
				
				// check for 5% minimum and maximum price
				/* if($pricePerHc >$plusBuyFivePercentAmt || $pricePerHc<$minusBuyFivePercentAmt){
					$this->Flash->error(__("Price entered by you is beyond the price range permitted by exchange. Please modify your price."));
					return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'newexchange']);
				} */
				
				// check for btc balance
				if($btcSpend > $gerUserTotalBtc){
					$this->Flash->error(__("Insufficient Balance in BTC wallet"));
					return $this->redirect('front/transactions/newexchange');
				}
				$btcSpend = round($btcSpend,8);
				$pricePerHc = round($pricePerHc,8);
				$adminFee = $btcSpend*($buy_fee/100);
				$adminFee = round($adminFee,8);
				$remainingBtcSpend = $btcSpend-$adminFee;
				$remainingBtcSpend = round($remainingBtcSpend,8);
				$hcReceiveAmt = $remainingBtcSpend/$pricePerHc;
				$hcReceiveAmt = round($hcReceiveAmt,8);
				//$hcReceiveAmt = round($hcReceiveAmt,8);
				
				
			
			    
				$newInsertArr = [];
				$newInsertArr['buyer_user_id'] = $currentUser;
				$newInsertArr['buy_btc_amount'] = $btcSpend;
				$newInsertArr['price_per_hc'] = $pricePerHc;
				$newInsertArr['price_per_btc'] = $pricePerBtc;
				$newInsertArr['buy_hc_amount'] = $hcReceiveAmt;
				$newInsertArr['buy_fees'] = $adminFee;
				$newInsertArr['status'] = 'pending';
				
				
				
				$exchangeTransactions=$this->BuyExchange->newEntity();
				$exchangeTransactions=$this->BuyExchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->BuyExchange->save($exchangeTransactions);
				
				$hc = $hcReceiveAmt;
				$btc = $btcSpend;
				$hcSign = "";
				$btcSign = "-";
				$transType = "debit";
				$exchangeType = "buy_exchange";
				$agcStatus = 'completed';
				$coinStatus = 0;
			}
			
			if($getFromType=="sell"){
				
				
				if($getBalanceOfRealToken['ether']<0.00006){
					$this->Flash->error(__("Please add ether(as gas fees) in your ether wallet"));
					return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'newexchange']);
				}
				
				$opertationType = "insert";
				$btcSpend = abs($this->request->data['btc_receive']);
				$pricePerHc = abs($this->request->data['price_per_hc_sell']);
				$hcReceive = abs($this->request->data['hc_spend']);				
				
				
				if($btcAmountInTenCent > $pricePerHc){
					$this->Flash->error(__("Minimum set price 0.10 USD"));
					return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'newexchange']);
				}
				
				/* if($hcReceive > 250){
					$this->Flash->error(__("Maximum Sell Order Limit is 250"));
					return $this->redirect('front/transactions/newexchange');
				} */
				
				$existSellHcPending = $this->Users->getUserSellExchangeCoin($currentUser);
				
				/* if($existSellHcPending > 250){
					$this->Flash->error(__("Maximum Sell Order Limit is 250"));
					return $this->redirect('front/transactions/newexchange');
				} */
				
				// check for empty values
				if(empty($btcSpend) || empty($pricePerHc) || empty($hcReceive)){
					$this->Flash->error(__("All Field are required"));
					return $this->redirect('front/transactions/newexchange');
				}
				
				
				// check for 5% minimum and maximum price
				/* if($pricePerHc > $plusSellFivePercentAmt || $pricePerHc < $minusSellFivePercentAmt){
					$this->Flash->error(__("Price entered by you is beyond the price range permitted by exchange. Please modify your price."));
					return $this->redirect(['prefix'=>'front','controller'=>'transactions','action'=>'newexchange']);
				} */
				
				// check for hc balance
				if($hcReceive > $gerUserTotalHc){
					$this->Flash->error(__("Insufficient Balance in HC wallet"));
					return $this->redirect('front/transactions/newexchange');
				}
				
				
				
				$pricePerHc = round($pricePerHc,8);
				$hcReceive = round($hcReceive,8);
				$btcReceive =  $hcReceive*$pricePerHc;
				$btcReceive = round($btcReceive,8);
				$adminFee = $btcReceive*($buy_fee/100);
				$adminFee = round($adminFee,8);
				$remainingBtcReceive = $btcReceive-$adminFee;
				$remainingBtcReceive = round($remainingBtcReceive,8);
				
			    
				$newInsertArr = [];
				$newInsertArr['seller_user_id'] = $currentUser;
				$newInsertArr['sell_btc_amount'] = $remainingBtcReceive;
				$newInsertArr['price_per_hc'] = $pricePerHc;
				$newInsertArr['price_per_btc'] = $pricePerBtc;
				$newInsertArr['sell_hc_amount'] = $hcReceive;
				$newInsertArr['sell_fees'] = $adminFee;
				$newInsertArr['status'] = 'pending';
				
				 
				
				$exchangeTransactions=$this->SellExchange->newEntity();
				
				$exchangeTransactions=$this->SellExchange->patchEntity($exchangeTransactions,$newInsertArr);
				$saveData = $this->SellExchange->save($exchangeTransactions);
				
				$hc = $hcReceive;
				$btc = $remainingBtcReceive;
				$hcSign = "-";
				$btcSign = "";
				$transType = "credit";
				$exchangeType = "sell_exchange";
				$agcStatus = 'pending';
				$coinStatus = 1;
			}
			
			
			// after save into exchange table
			if($saveData){
				$exchangeId = $saveData->id;
				
				if($getFromType=="buy"){
					$newTransArr = [];
					$newTransArr['exchange_id']= $exchangeId;
					$newTransArr['user_id']= $currentUser;
					$newTransArr['btc_coins']= $btcSign.$btc;
					$newTransArr['trans_type']= $transType;
					$newTransArr['status']= $agcStatus;
					$newTransArr['updated_at']= $cudate;
					$newTransArr['coin_type']= $exchangeType;
					
					
					$getTransNew = $this->Agctransactions->newEntity();
					$getTransNewData = $this->Agctransactions->patchEntity($getTransNew,$newTransArr);
					$updateBtcWallet = $this->Agctransactions->save($getTransNewData);
				}
				
				
				if($getFromType=="sell"){
					$newReferalArr = [];
					$newReferalArr['exchange_id']= $exchangeId;
					$newReferalArr['user_id'] = $currentUser;
					$newReferalArr['btc'] = $btc;
					$newReferalArr['coin'] = $hcSign.$hc;
					$newReferalArr['doller_per_hc'] = $pricePerHc;
					$newReferalArr['type'] = $exchangeType;
					$newReferalArr['status'] = $coinStatus;
					$newReferalArr['updated_at'] = $cudate;
					
					$coinTransactions=$this->Cointransactions->newEntity();
					
					
					$coinTransactions=$this->Cointransactions->patchEntity($coinTransactions,$newReferalArr);
					$saveReferaData = $this->Cointransactions->save($coinTransactions);
				}
				
				$redirectTo = ($getFromType=="buy") ? "mybuyexchangeorder" : "mysellexchangeorder";
				
				$this->Flash->success(__(ucfirst($getFromType)." Order Created Successfully."));
				return $this->redirect('front/transactions/'.$redirectTo);
			}
			else {
				$this->Flash->error(__("Unable to Create ".$getFromType." Order ! Try Again."));
				return $this->redirect('front/transactions/'.$redirectTo);
			}
				
		}
		
		// code end
		
		
		$limit = $this->setting['pagination'];
		
		// for Buy Order List Of Current User
		$searchData = array();
		$searchData['status']= "pending";
		$searchData['buyer_user_id !=']= $currentUser;
		
	
		$this->set('buyListing',$this->Paginator->paginate($this->BuyExchange, [
						 'conditions'=>$searchData,
						'order'=>['BuyExchange.id'=>'desc'],
						'limit' => $limit,
					]));
		
					
		// for seller Order List Of Current User
		$searchData = array();
		$searchData['status']= "pending";
		$searchData['seller_user_id !=']= $currentUser;
		
		
	
		$this->set('sellListing',$this->Paginator->paginate($this->SellExchange, [
						 'conditions'=>$searchData,
						'order'=>['SellExchange.id'=>'desc'],
						'limit' => $limit,
					]));		
		$this->set('type',$type);
		
		
		
		
		
	}
	
	
	
	public function mybuyexchangeorder(){
		
		$this->set('title' , 'HC : Exchange');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Exchange');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('Referal');
		
		//$this->Flash->error(__("We are working on your orders. Exchange will open again soon.".));
		//	return $this->redirect('front/transactions/ico');
		$showUserList = [311,372,376,600,1794,11978,12461,15842,26267,44104,54522,54523,54524,54525,54526];
		
		$authUser = $this->Auth->user();
		
		
		$this->set('authUser',$authUser);
		
		$type = "purchase";
		
		$coin_arr=['referral','purchase','bonus'];
		if(!in_array($type,$coin_arr)){
			$type = "purchase";
		}	
		$cudate = date("Y-m-d H:i:s");	
		$this->set('display_type','AGC');
		// for purchase
		$currentUser = $this->Auth->user('id');
		
		/* if(!in_array($currentUser,$showUserList)){
			$this->Flash->error(__("Exchange Functionality has disabled For 1 day"));
			return $this->redirect('front/transactions/ico');
		} */
		
		$notShowSellOrderBox = 0;
		$otherUsers ="no";
		if(!in_array($currentUser,$showUserList)){
			//$this->Flash->error(__("Exchange Functionality is disabled"));
			//return $this->redirect('front/transactions/ico');
			//$notShowSellOrderBox = 1;
			//$otherUsers ="yes";
			$notShowSellOrderBox = 0;
			$otherUsers ="no";
		}
		$this->set('notShowSellOrderBox',$notShowSellOrderBox);
		$this->set('otherUsers',$otherUsers);
	
		
		
		
		// code end
		
		
		$limit = $this->setting['pagination'];
		
		
		
		// current User Orders
		// for Buy Order List Of Current User
		$searchData = array();
		//$searchData['status']= "pending";
		$searchData['buyer_user_id']= $currentUser;
		
	
		$this->set('myBuyListing',$this->Paginator->paginate($this->BuyExchange, [
						 'conditions'=>$searchData,
						'order'=>['BuyExchange.id'=>'desc'],
						'limit' => $limit,
					]));
		
					
			
		$this->set('type',$type);
		
	}
	
	
	
	public function mysellexchangeorder(){
		
		$this->set('title' , 'HC : Exchange');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Exchange');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('Referal');
		
		//$this->Flash->error(__("We are working on your orders. Exchange will open again soon.".));
		//	return $this->redirect('front/transactions/ico');
		$showUserList = [311,372,376,600,1794,11978,12461,15842,26267,44104,54522,54523,54524,54525,54526];
		
		$authUser = $this->Auth->user();
		
		
		$this->set('authUser',$authUser);
		
		$type = "purchase";
		
		$coin_arr=['referral','purchase','bonus'];
		if(!in_array($type,$coin_arr)){
			$type = "purchase";
		}	
		$cudate = date("Y-m-d H:i:s");	
		$this->set('display_type','AGC');
		// for purchase
		$currentUser = $this->Auth->user('id');
		
		/* if(!in_array($currentUser,$showUserList)){
			$this->Flash->error(__("Exchange Functionality has disabled For 1 day"));
			return $this->redirect('front/transactions/ico');
		} */
		
		$notShowSellOrderBox = 0;
		$otherUsers ="no";
		if(!in_array($currentUser,$showUserList)){
			//$this->Flash->error(__("Exchange Functionality is disabled"));
			//return $this->redirect('front/transactions/ico');
			//$notShowSellOrderBox = 1;
			//$otherUsers ="yes";
			$notShowSellOrderBox = 0;
			$otherUsers ="no";
		}
		$this->set('notShowSellOrderBox',$notShowSellOrderBox);
		$this->set('otherUsers',$otherUsers);
	
		
		
		
		// code end
		
		
		$limit = $this->setting['pagination'];
		
		
		
		
		
					
		// for seller Order List Of Current User
		$searchData = array();
		//$searchData['status']= "pending";
		$searchData['seller_user_id']= $currentUser;
		
		
	
		$this->set('mySellListing',$this->Paginator->paginate($this->SellExchange, [
						 'conditions'=>$searchData,
						'order'=>['SellExchange.id'=>'desc'],
						'limit' => $limit,
					]));		
		$this->set('type',$type);
		
	}
	
	
	/*
	Get Latest ten record of sell and buy exchange
	*/
	
	public function exchangeDataAjax(){
		
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('ExchangeHistory');
		$returnArr = [];
		if ($this->request->is('ajax')) 
		{ 
			$currentUser = $this->Auth->user('id');
			
			// buy exchange data
			$searchData = array();
			$searchData['AND'][] = array('buyer_user_id !=' => $currentUser);
			$searchData['AND'][] = array('status ' => "pending");
			$limit = $this->setting['pagination'];
			
			$buyData = $this->BuyExchange->find("all",['conditions'=>$searchData,
											'order'=>['BuyExchange.id'=>'desc'],
											'limit' => $limit,
										])->all()->toArray();
		
			$returnArr['buy_data'] = $buyData;
			
			// sell exchange data		
			
			$searchData = array();
			$searchData['AND'][] = array('seller_user_id !=' => $currentUser);
			$searchData['AND'][] = array('status ' => "pending");
			$limit = $this->setting['pagination'];
			
			$sellData = $this->SellExchange->find("all",['conditions'=>$searchData,
											'order'=>['SellExchange.id'=>'desc'],
											'limit' => $limit,
										])->all()->toArray();
			
			$returnArr['sell_data'] = $sellData;
			
			
			//  exchange history data		
			
			$searchData = array();
			$searchData['AND'][] = array('btc_amount !=' => 0.00000000);
			$searchData['AND'][] = array('hc_amount !=' => 0.00000000);
			$limit = $this->setting['pagination'];
			
			$historyData = $this->ExchangeHistory->find("all",['conditions'=>$searchData,
											'order'=>['ExchangeHistory.id'=>'desc'],
											'limit' => $limit,
										])->all()->toArray();
			
			$returnArr['history_data'] = $historyData;
			
			
			echo json_encode($returnArr); die;
			
		}
		
		
	}
	
	
	
	public function newbuyExchangeSearch()
	{
		
		$this->loadModel('BuyExchange');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			$currentUser = $this->Auth->user('id');
		
			$searchData = array();
			
			$searchData['AND'][] = array('buyer_user_id !=' => $currentUser);
			$searchData['AND'][] = array('status ' => "pending");
			
			$limit = $this->setting['pagination'];
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$this->set('listing',$this->Paginator->paginate($this->BuyExchange, [
						'conditions'=>$searchData,
						'order'=>['BuyExchange.id'=>'desc'],
						'limit' => $limit,
					]));
			//print_r($getData); die;			
			//$this->set('listing',$getData);
		
			
			//$this->set('type',$search['type']);
		}
	
	}
	
	
	public function newsellExchangeSearch()
	{
		
		$this->loadModel('SellExchange');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			$currentUser = $this->Auth->user('id');
		
			$searchData = array();
			
			$searchData['AND'][] = array('seller_user_id !=' => $currentUser);
			$searchData['AND'][] = array('status ' => "pending");
			
			$limit = $this->setting['pagination'];
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$this->set('listing',$this->Paginator->paginate($this->SellExchange, [
						'conditions'=>$searchData,
						'order'=>['SellExchange.id'=>'desc'],
						'limit' => $limit,
					]));
			//print_r($getData); die;			
			//$this->set('listing',$getData);
		
			
			//$this->set('type',$search['type']);
		}
	
	}



	public function newmyBuyExchangeSearch()
	{
		
		$this->loadModel('BuyExchange');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			$currentUser = $this->Auth->user('id');
		
			$searchData = array();
			
			$searchData['AND'][] = array('buyer_user_id =' => $currentUser);
			//$searchData['AND'][] = array('status ' => "pending");
			
			$limit = $this->setting['pagination'];
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$this->set('listing',$this->Paginator->paginate($this->BuyExchange, [
						'conditions'=>$searchData,
						'order'=>['BuyExchange.id'=>'desc'],
						'limit' => $limit,
					]));
			//print_r($getData); die;			
			//$this->set('listing',$getData);
		
			
			$this->set('type',$search['type']);
		}
	
	}
	
	
	public function newmySellExchangeSearch()
	{
		
		$this->loadModel('SellExchange');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			$currentUser = $this->Auth->user('id');
		
			$searchData = array();
			
			$searchData['AND'][] = array('seller_user_id =' => $currentUser);
			//$searchData['AND'][] = array('status ' => "pending");
			
			$limit = $this->setting['pagination'];
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$this->set('listing',$this->Paginator->paginate($this->SellExchange, [
						'conditions'=>$searchData,
						'order'=>['SellExchange.id'=>'desc'],
						'limit' => $limit,
					]));
			//print_r($getData); die;			
			//$this->set('listing',$getData);
		
			
			//$this->set('type',$search['type']);
		}
	
	}	
	
	
	 public function newbuyexchangeDelete($id=null){
		 
		if($id==null && $id == ''){
			$this->Flash->error(__("Exchange Can't be delete"));
			return $this->redirect('front/transactions/mybuyexchangeorder'); 
		}
		
		$this->set('title' , 'HC : Exchange');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('BuyExchange');
		$this->loadModel('Referal');
		
		$currentUser = $this->Auth->user('id');
		
		$findCoinditions = [];
		$findCoinditions['id'] = $id;
		$findCoinditions['status'] = 'pending';
		$findCoinditions['buyer_user_id']= $currentUser;
		
		 
		// insert data
		$findExchange = $this->BuyExchange->find('all',['conditions'=>$findCoinditions])->hydrate(false)->toArray();
		//print_r($findExchange); die;
		if(empty($findExchange)){
			$this->Flash->error(__("Unable to delete exchange, Try Again"));
			return $this->redirect('front/transactions/mybuyexchangeorder'); 
		}
		
		$exchangeRecord = $this->BuyExchange->query()->delete()->where(['id' => $id])->execute();
		if($exchangeRecord){
			// delete agc Transaction
			$findAgcTrans = $this->Agctransactions->find('all',['conditions'=>['exchange_id'=>$id]])->hydrate(false)->first();
			if(!empty($findAgcTrans)){
				$agcTransId = $findAgcTrans['id'];
				$delteAgcTrans = $this->Agctransactions->query()->delete()->where(['id' => $agcTransId])->execute();
				//$delteAgcTrans = $delteAgcTrans->delete();
			}
			
			
			$this->Flash->success(__("Buy Exchange Record Deleted Successfully"));
			return $this->redirect('front/transactions/mybuyexchangeorder');  
		}
		else {
			$this->Flash->error(__("Unable to delete exchange, Try Again"));
			return $this->redirect('front/transactions/mybuyexchangeorder');
		}
		
		
		
	 } 
	
	 public function newsellexchangeDelete($id=null){
		 
		if($id==null && $id == ''){
			$this->Flash->error(__("Exchange Can't be delete"));
			return $this->redirect('front/transactions/mysellexchangeorder'); 
		}
		
		$this->set('title' , 'HC : Exchange');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('SellExchange');
		$this->loadModel('Referal');
		
		$currentUser = $this->Auth->user('id');
		
		$findCoinditions = [];
		$findCoinditions['id'] = $id;
		$findCoinditions['status'] = 'pending';
		$findCoinditions['seller_user_id'] = $currentUser;
		//$findCoinditions['OR'][] = ['buyer_user_id'=>$currentUser];
		
		 
		// insert data
		$findExchange = $this->SellExchange->find('all',['conditions'=>$findCoinditions])->hydrate(false)->toArray();
		//print_r($findExchange); die;
		if(empty($findExchange)){
			$this->Flash->error(__("Unable to delete exchange, Try Again"));
			return $this->redirect('front/transactions/mysellexchangeorder'); 
		}
		
		$exchangeRecord = $this->SellExchange->query()->delete()->where(['id' => $id])->execute();
		if($exchangeRecord){
			
			// delete agc Transaction
			$findCoinTrans = $this->Cointransactions->find('all',['conditions'=>['exchange_id'=>$id]])->hydrate(false)->first();
			if(!empty($findCoinTrans)){
				$coinTransId = $findCoinTrans['id'];
				$delteCoinTrans = $this->Cointransactions->query()->delete()->where(['id' => $coinTransId])->execute();
				
			}
			$this->Flash->success(__("Sell Exchange Record Deleted Successfully"));
			return $this->redirect('front/transactions/mysellexchangeorder');  
		}
		else {
			$this->Flash->error(__("Unable to delete exchange, Try Again"));
			return $this->redirect('front/transactions/mysellexchangeorder');
		}
		
		
		
	 } 	
	
	
	public function allbuyorder(){
		
		$currentUser = $this->Auth->user('id');

		$this->set('title' , 'HC : Exchange');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Exchange');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('Referal');
		
		
		
		
		
		$limit = $this->setting['pagination'];
		
		// for Buy Order List Of Current User
		$searchData = array();
		$searchData['status']= "pending";
		$searchData['buyer_user_id !=']= $currentUser;
		
	
		$this->set('buyListing',$this->Paginator->paginate($this->BuyExchange, [
						 'conditions'=>$searchData,
						'order'=>['BuyExchange.id'=>'desc'],
						'limit' => $limit,
					]));
		
					
	}	
	
	public function allsellorder(){
		
		$currentUser = $this->Auth->user('id');

		$this->set('title' , 'HC : Exchange');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Exchange');
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('Referal');
		
		
		$limit = $this->setting['pagination'];
		
					
		// for seller Order List Of Current User
		$searchData = array();
		$searchData['status']= "pending";
		$searchData['seller_user_id !=']= $currentUser;
		
		
	
		$this->set('sellListing',$this->Paginator->paginate($this->SellExchange, [
						 'conditions'=>$searchData,
						'order'=>['SellExchange.id'=>'desc'],
						'limit' => $limit,
					]));		
		
		
	}
	
	
	public function dashboardPost(){ 
	
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Coin');
		$this->loadModel('Cryptocoin');
		$this->loadModel('Settings');
		
		$currentUser = $this->Auth->user('id');
		
		$cudate = date('Y-m-d H:i:s');
		
		//print_r($bonus);die;
		
		if($this->request->is('post')){ 
			$flatCurrencyArr = [13,14,15];
			$getCurrency = $this->request->data['currency']; // crypto type (btc/eth)
			$userDetail = $this->Users->get($currentUser);
			if($getCurrency==1){
				if(!empty($userDetail->btc_address)){
					echo $userDetail->btc_address; die;	
				}
				else {
					$userEmail = $userDetail->email;
					$getBtcAddrResp = $this->Users->createBtcAddress($userEmail);
					$responseDecode = json_decode($getBtcAddrResp,true);
					$getBtcAddr = $responseDecode['result'];
					$userDetail->btc_address = $getBtcAddr;
					$this->Users->save($userDetail);
					echo $getBtcAddr = $getBtcAddr; die;
				}
			} 
			
			die;
			
			
		}
		die;
	}
	
	
	
	
	
}
