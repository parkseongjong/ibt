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

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validation;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

class UsersController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['signup', 'logout','frontLogin','frontRegister','verify','forgotPassword','contact','userCron']);
    }
    public function contact()
    {
		if ($this->request->is('ajax')) 
		{
			$this->loadModel('ContactUs');
			$contactus = $this->ContactUs->newEntity();
			if ($this->request->is(['post' ,'put'])) 
			{
				//pr($this->request->data);die;
				$contactus = $this->ContactUs->patchEntity($contactus, $this->request->data);
				if ($newNetwork = $this->ContactUs->save($contactus)) {
					
					$to = "mighty.ambrish@gmail.com";
					$subject = "HTML email";

					$message = "
					<html>
					<head>
					<title>HTML email</title>
					</head>
					<body>
					<p>This email contains HTML Tags!</p>
					<table>
					<tr>
					<th>Firstname</th>
					<th>Lastname</th>
					</tr>
					<tr>
					<td>John</td>
					<td>Doe</td>
					</tr>
					</table>
					</body>
					</html>
					";

					// Always set content-type when sending HTML email
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

					// More headers
					$headers .= 'From: <amaxgoldcoin@gmail.com>';

					if(mail($to,$subject,$message,$headers)){
					
					
					$res['success']= 1;
					$res['string'] = '<div class="alert-success1"><strong>Success! </strong>Your query has been submitted successfully.</div>';
					}
					else {
						
						$res['string'] = '<div class="alert-danger1"><strong>Error! </strong>Unable to send email ! Try Again</div>';
						
					}
				
				}else{
					$res['success']= 0;
					foreach($contactus->errors() as $field_key =>  $error_data)
					{
						foreach($error_data as $error_text)
						{
							
							$res['string'] = '<div class="alert-danger1"><strong>Error! </strong>'.$error_text.'</div>';
							break 2;
						} 
						
					}
				
				}
				echo json_encode($res);
				exit;
			}	
		}
	}
    
	
	public function frontLogin()
    {
		if ($this->request->is('ajax')) 
		{
			
			if(isset($this->request->data['username']) && isset($this->request->data['password']))
			{
				$userName = $this->request->data['username'];
				$res = array();
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
				$this->loadModel('Users');
				if ($user && $user['enabled']=='Y'){
						$userEmail = $user['email'];
						if(empty($user['btc_address'])) { 
							$createBtcAddr = $this->Users->createBtcAddress($userEmail); // for create btc address
						}
						$res['string'] = '<div class="alert-success1"><strong>Successful! </strong>Logged in successfully.</div>';
						$res['success']= 1;
						$res['user_type'] = $user['user_type'];
						$userId = $this->Auth->user('id');
						$userDetail = $this->Users->find('all',array('conditions'=>array('username'=>$userName)))->hydrate(false)->first();
						
						$wallertAddress = $userDetail['btc_address'];
						$this->Users->getBtcAddressBalance($wallertAddress);
						//$this->Users->updateTransaction();
						
						
						//
						$tablename = TableRegistry::get("Users");
						$query = $tablename->query();
						$result = $query->update()
								->set(['btc_address_status' => 'active'])
								->where(['username' => $userName])
								->execute();
						
						
						
                        $this->loadModel('LoginLogs');
                        $new_log = $this->LoginLogs->newEntity();
                        $data['user_id'] = $user['id'];
                        $data['ip_address'] = $this->get_client_ip();
                        $log_patch = $this->LoginLogs->patchEntity($new_log,$data);
                        $this->LoginLogs->save($log_patch);
                        if(SENDMAIL == 1)
                        {

                            $user['msg'] = 'Logged in successfully with IP address '.$user['ip_address'];
                            $email = new Email('default');
                            $email->viewVars(['data'=>$user]);
                            $email->from([$this->setting['email_from']] )
                                ->to($user['email'])
                                ->subject('You are Logged in successfully.')
                                ->emailFormat('html')
                                ->template('login')
                                ->send();
                        }
						$this->Auth->setUser($user);
					
				}else if($user && $user['enabled']=='N'){
					$res['success']= 0;
					$res['string'] = '<div class="alert-danger1"><strong>Error! </strong>Please verify your account.</div>';
				}else{
					$res['success']= 0;
					$res['string'] = '<div class="alert-danger1"><strong>Error! </strong>Invalid login credentails, try again</div>';
				
				}

			}else{
				$res['success']= 0;
				$res['string'] = '<div class="alert-danger1"><strong>Error! </strong>Invalid login credentails, try again</div>';
			
			}
			echo json_encode($res);
			die;
		
		}
	}
	
   public function frontRegister()
    {
		if ($this->request->is('ajax')) 
		{
			$user = $this->Users->newEntity();
			$ref_code = $this->request->data['refer_from'];
			$ref_code = ($ref_code!='') ? $ref_code : "amaxgold";
			$userEmail = $this->request->data['email'];	
			//$this->request->data['refer_from']= $ref_code;
			$this->request->data['referral_code']= $this->getNewReferralCode();
			$this->request->data['unique_id']= $this->getUniqueId();
            $this->request->data['ip_address'] = $this->get_client_ip();
            //$this->request->data['enabled'] = 'Y';
			$this->request->data['enabled'] = 'N';
            $data = $this->request->data;
			$user = $this->Users->patchEntity($user, $this->request->data);

			if(!$user->errors())
			{
				$refer_user = $this->Users->find('all',array('fields'=>['id'],'conditions'=>array('referral_code'=>$this->request->data['refer_from'])))->first();
				if(!empty($refer_user)){
					$user->referral_user_id = $refer_user['id'];
				}
			}
		
			if ($usrDetail = $this->Users->save($user))
			{
				//$createBtcAddr = $this->Users->createBtcAddress($userEmail); // for create btc address
				$res['success']= 1;
				//if(SENDMAIL == 1)
                //{
					
					//$usrDetail->password = $pass;
					//$txtmessage  ='Congratulation! your accout successfully created with username: '.$data['username'].' and password: '.$data['password'].'@https://galaxycoin.co';

					//$txtmessage= "Register successfully at Galaxycoin. Start using the portal. ";        
					//$mobno = "91".$usrDetail->phone_number; 
					//$txtmessage = urlencode($txtmessage);        
					//$txtmessage=str_replace(' ','%20',$txtmessage);
					// to replace the space in message with '%20'
					/* $url='http://sms.xpressdndsms.com/api/mt/SendSMS?user=mkuberindore&password=mkuber&senderid=GLXCOI&channel=trans&DCS=0&flashsms=0&number='.$mobno.'&text='.$txtmessage.'message&route=2';
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,$url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
					$output = curl_exec($ch); //This is the result from Textlocal 
					curl_close($ch); */

                    $usrDetail->link = BASEURL.'front/users/verify/'.$usrDetail->unique_id;
					$data['userLink'] = $usrDetail->link;
                    $email = new Email('default');
                    $email->viewVars(['data'=>$data]);
                    $email->from([$this->setting['email_from']] )
                        ->to($this->request->data['email'])
                        ->subject('You are registered successfully.')
                        ->emailFormat('html')
                        ->template('signup')
                        ->send();
                //}
                $res['string'] = '<div class="alert-success1"><strong>Success! </strong>
				Successfully registered. Please check emai for verify account.</div>';
					
				
			}else{
				$res['success']= 0;
				foreach($user->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						
						$res['string'] = '<div class="alert-danger1"><strong>Error! </strong>'.$error_text.'</div>';
						break 2;
					} 
					
				}
			}
			echo json_encode($res);
			exit;
		}
	}

	public function logout()
    {
		
        return $this->redirect($this->Auth->logout());
    }


    public function forgotPassword()
    {
        if($this->request->is('post') && isset($this->request->data['email']))
        {
            $email = $this->request->data['email'];
            if($email == "")
            {
                $message =  array('error'=>1,'message'=>'Incomplete Data');
            }
            else
            {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false)
                {
                    $user_record = $this->Users->find('all',array('conditions'=>array('email'=>$email)))->first();
                    if($user_record && !empty($user_record))
                    {
                        $firstname = $user_record['name'];

                        $new_password = rand(111111,99999999);
                        //$new_password = 123456;
                        $query = $this->Users->query();
                        $query->update()
                            ->set(['password'  =>  (new DefaultPasswordHasher)->hash($new_password)])
                            ->where(['email' => $email])
                            ->execute();
                        $user_record['new_pass'] = $new_password;
                        $email = new Email('default');
                        $email->viewVars(['data'=>$user_record]);
                        $email->from([$this->setting['email_from']] )
                            ->to($this->request->data['email'])
                            ->subject('Your New Password Is.')
                            ->emailFormat('html')
                            ->template('forgot_password')
                            ->send();
                        $message =  array('error'=>0,'message'=>'Please check your Email to get your password');
                    }
                    else
                    {
                        $message =  array('error'=>1,'message'=>'Email does not exist');

                    }
                }
                else
                {
                    $message =  array('error'=>1,'message'=>'Not a valid email address');

                }
            }
           echo json_encode($message);die;


        }
    }
	
	public function userCron(){
    
		$this->loadModel('Cointransactions');
		$this->loadModel('Cointransfer');
		$this->loadModel('Users');
		
	
		
		
		
		
		
		
		
		
		
		
		$Query = $this->Cointransactions->find('all');
		$cuDate = '2018-04-03';
		$getAllUserCoin = $Query->select([ 
					  'user_id',
					  'coin_sum' => $Query->func()->sum('coin'),
					  'user.token_wallet_address'
					])
					
			 ->where(['status' => 1,
					  'user_id !='=>1,	
					  /* 'DATE(`created_at`)'=>$cuDate,
					  'type in'=>['lending_interest','lending_interest'], */
					  'user.token_wallet_address !='=>''])
			 ->contain(['user'])		  
			 ->group('user_id')
			 /* ->offset(490)
			 ->limit(40) */
			 ->all();
		
		$i=1;
		print_r(count($getAllUserCoin)); die;
		
		if(!empty($getAllUserCoin)){
			
			foreach($getAllUserCoin as $singleUser){
				
				$userId = $singleUser['user_id'];
				$coinSum = $singleUser['coin_sum'];
				
				$getUserTransferredHc = $this->Users->getUserTransferredHc($userId);
				$coinToTransfer = $coinSum -$getUserTransferredHc; 
				
				if($coinToTransfer>0) {
					$i++;
					/* $password = 'mighty_admin@gmail.com';
					$fromWalletAddress = '0x2c6bc9db73fd67956b187149babc1b1360aae59d';
					$toWalletAddress = $singleUser['user']['token_wallet_address'];
					$coinAmount = $coinToTransfer;
					echo "\n";
					echo $tx_id = $this->Users->transferCoinToAddress($password,$fromWalletAddress,$toWalletAddress,$coinAmount);
					echo "\n";
					sleep(1);
					if(!empty($tx_id)) {
						$cuDate = date('Y-m-d H:i:s');
						$coinTransferArr=[];
						$coinTransferArr['tx_id']        = $tx_id;
						$coinTransferArr['from_user_id'] = 1;
						$coinTransferArr['to_user_id']   = $userId;
						$coinTransferArr['coin_amount']  = $coinAmount;
						$coinTransferArr['status']  = 1;
						$coinTransferArr['created_at']  = $cuDate;
						$coinTransferArr['updated_at']  = $cuDate;
						
						
						$coinTransferOdj = $this->Cointransfer->newEntity();
						$coinTransferOdj = $this->Cointransfer->patchEntity($coinTransferOdj,$coinTransferArr);
						$saveData = $this->Cointransfer->save($coinTransferOdj);
					}  */
				}
			}
		}	
		echo $i;
		die;
		//debug($Query);	 
		print_r(count($getAllUserCoin->all())); die;
	}
}
