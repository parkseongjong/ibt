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
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager; 
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
//use Google\Authenticator\GoogleAuthenticator;


class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
            // Allow users to register and logout.
            // You should not add the "login" action to allow list. Doing so would
            // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['signup', 'logout','frontLogin','frontRegister','verify','forgotPassword','successregister']);
        }

	public function dashboard(){
			
			$this->set('title' , 'Dashboard');
	}
	
	
	public function profile()
	{
		
		$this->set('title','My profile');
		$user  = $this->Users->get($this->Auth->user('id'));
		$before_image = $user->image;
		if ($this->request->is(['post','put'])) {
			
			$newImageName = '';
			if(isset($_FILES['image']) && $_FILES['image']['tmp_name'] !='')
			{
				if($_FILES['image']['size'] > 104856) {
					$this->Flash->error(__('file size should be maximum 1 MB.'));
					return $this->redirect(['action' => 'profile']);
				}
				
				$filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['image']['name']);
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if(!in_array($ext,['jpg','png','jpeg','gif'])){
					$this->Flash->error(__('Please only upload images (gif, png, jpg).'));
					return $this->redirect(['action' => 'profile']);
				}
				
				
				
				$filename = time().'.'.$ext;
				if ($this->uploadImage($_FILES['image']['tmp_name'], $_FILES['image']['type'], 'uploads/user_image/', $filename)){
					$this->createThumbnail($filename, 'uploads/user_image', 'uploads/user_thumb',200,200); 
					$newImageName = $filename;
					unlink('uploads/user_image'.$before_image);
					unlink('uploads/user_thumb'.$before_image);
				}
				else  $newImageName = $before_image;
			}else  $newImageName = $before_image;
			
			
			$insertArr = [];
			$insertArr['username'] = filter_var($this->request->data['username'], FILTER_SANITIZE_STRING);
			$insertArr['name'] = filter_var($this->request->data['name'], FILTER_SANITIZE_STRING);
			$insertArr['phone_number'] = filter_var($this->request->data['phone_number'], FILTER_SANITIZE_STRING);
			$insertArr['image'] = $newImageName;
			
			$user = $this->Users->get($this->Auth->user('id'));
			$user = $this->Users->patchEntity($user, $insertArr);
			if($updateUser = $this->Users->save($user)){
				$userExist = $this->Users->find('all',array('conditions'=>array('id'=>$this->Auth->user('id'))))->first();
				$this->Auth->setUser($userExist->toArray());
				$this->Flash->success(__('User has been updated.'));
				return $this->redirect(['action' => 'profile']);
			}
			else {
				$this->Flash->error(__('Unable to update User. Try Again Later.'));
				return $this->redirect(['action' => 'profile']);
			}
			
			
		}
		$this->set('user',$user);	
		//get current date logged in users
		$this->loadModel('LoginLogs');
		$logs = $this->LoginLogs->find();
		$create_date = $logs->func()->date_format([
                'LoginLogs.created' => 'literal',
                "'%d %M, %Y %h:%i %p'" => 'literal'
            ]);
		$log_records = $this->LoginLogs->find('all',['fields'=>['date'=>$create_date,'user_id','ip_address'],'conditions'=>['date(LoginLogs.created)'=>date('Y-m-d'),'user_id'=>$this->Auth->user('id')],'limit'=>5,'order'=>['LoginLogs.id'=>'desc']])->hydrate(false)->toArray();
		$this->set('log_records',$log_records);	
	}
	
	
	
	public function idVerification()
	{
		
		$this->set('title','My profile');
		$user  = $this->Users->get($this->Auth->user('id'));
		
		$before_image = $user->id_document_front;
		if ($this->request->is(['post','put'])) {
			if(!isset($this->request->data['id_type'])){
				$this->Flash->error(__('All Fields are required.'));
				return $this->redirect(['action' => 'idVerification']);
			}
			$idType = filter_var($this->request->data['id_type'], FILTER_SANITIZE_STRING);
			$idNumber = filter_var($this->request->data['id_number'], FILTER_SANITIZE_STRING);
			if(empty($_FILES['image']['name']) || empty($idType) ||  empty($idNumber)){
				$this->Flash->error(__('All Fields are required.'));
				return $this->redirect(['action' => 'idVerification']);
			}
			$newImageName = '';
			if(isset($_FILES['image']) && $_FILES['image']['tmp_name'] !='')
			{
				if($_FILES['image']['size'] > 104856) {
					$this->Flash->error(__('file size should be maximum 1 MB.'));
					return $this->redirect(['action' => 'idVerification']);
				}
				
				$filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['image']['name']);
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if(!in_array($ext,['jpg','png','jpeg','gif'])){
					$this->Flash->error(__('Please only upload images (gif, png, jpg).'));
					return $this->redirect(['action' => 'idVerification']);
				}
				
				
				
				$filename = time().'.'.$ext;
				if ($this->uploadImage($_FILES['image']['tmp_name'], $_FILES['image']['type'], 'uploads/id_verification/', $filename)){
					//$this->createThumbnail($filename, 'uploads/user_image', 'uploads/user_thumb',200,200); 
					$newImageName = $filename;
					//unlink('uploads/user_image'.$before_image);
					//unlink('uploads/user_thumb'.$before_image);
				}
				else  $newImageName = $before_image;
			}else  $newImageName = $before_image;
			
			
			$insertArr = [];
			$insertArr['id_type'] = $idType;
			$insertArr['id_number'] = $idNumber;
			//$insertArr['phone_number'] = filter_var($this->request->data['phone_number'], FILTER_SANITIZE_STRING);
			$insertArr['id_document_front'] = $newImageName;
			$insertArr['id_verification_status'] = 'P';
			
			$user = $this->Users->get($this->Auth->user('id'));
			$user = $this->Users->patchEntity($user, $insertArr);
			if($updateUser = $this->Users->save($user)){
				$userExist = $this->Users->find('all',array('conditions'=>array('id'=>$this->Auth->user('id'))))->first();
				$this->Auth->setUser($userExist->toArray());
				$this->Flash->success(__('User Document has been updated.'));
				return $this->redirect(['action' => 'idVerification']);
			}
			else {
				$this->Flash->error(__('Unable to update User Document. Try Again Later.'));
				return $this->redirect(['action' => 'idVerification']);
			}
			
			
		}
		$this->set('user',$user);	
		
	}
	
	
	
	public function editProfile()
	{
		$this->set('title' , 'HC!: Edit profile');
		$user  = $this->Users->get($this->Auth->user('id'));
		$before_image = $user->image;
		if ($this->request->is(['post','put'])) {
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$user = $this->Users->get($this->Auth->user('id'));
				
				if(isset($_FILES['image']) && $_FILES['image']['tmp_name'] !='')
				{
					$filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['image']['name']);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$filename = basename($filename, '.' . $ext) . time() . '.jpg';
					if ($this->uploadImage($_FILES['image']['tmp_name'], $_FILES['image']['type'], 'uploads/user_image/', $filename)){
						$this->createThumbnail($filename, 'uploads/user_image', 'uploads/user_thumb',200,200); 
						$user->image = $filename;
					}
					else  $user->image = $before_image;
				}else  $user->image = $before_image;
				
				$this->Users->save($user);
				$userExist = $this->Users->find('all',array('conditions'=>array('id'=>$this->Auth->user('id'))))->first();
				$this->Auth->setUser($userExist->toArray());
				$this->Flash->success(__('User has been updated.'));
				return $this->redirect(['action' => 'profile']);
				
			}
			
		}
		$this->set('user',$user);	
	}
	
	
	
	public function register($getReferralCodeUrl = '')
	{
		$getEmail = (isset($_GET['email'])) ? $_GET['email'] : '';
		$this->set('title' , 'WinnerBank : Register');
		$user  = $this->Users->newEntity();
		
		if ($this->request->is(['post','put'])) {
		
			/* $this->Flash->error(__('Exchange is under maintenance'));
			return $this->redirect(['controller'=>'Users','action'=>'login']); */
			
			
			/*  $captchaResp = $this->request->data['g-recaptcha-response'];
			 if(empty($captchaResp)){
				$this->Flash->error(__('please verify captcha.'));
				return $this->redirect('front/register');
			}
			unset($this->request->data['g-recaptcha-response']); */
			
			//$ref_code = $this->request->data['refer_from'];
			
			
			$this->request->data['username'] = filter_var($this->request->data['username'], FILTER_SANITIZE_STRING);
			$username = $this->request->data['username'];
			$password = strip_tags($this->request->data['password']);
			$email = $this->request->data['email'];
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->Flash->error(__('Enter a Valid Email'));
				return $this->redirect('front/users/register');
			}
			
			if(preg_match('/[^a-z_\-0-9]/i', $username)) { // for english chars + numbers only
				// valid username, alphanumeric & longer than or equals 5 chars
				$this->Flash->error(__('Username should be alphanumeric'));
				return $this->redirect('front/users/register');
			}
			$parentId=null;
			 $getReferralCode = $this->request->data['referral_code_get'];
			 if(!empty($getReferralCode)){
				$findReferral = $this->Users->find("all",['conditions'=>['referral_code'=>$getReferralCode]])->hydrate(false)->first();
				if(empty($findReferral)){
					$this->Flash->error(__("Invalid Referral Code"));
					return $this->redirect('front/users/register');
				}
				$parentId = $findReferral['id'];
			}
			unset($this->request->data['referral_code_get']);
	
			
			
			
			
			$this->request->data['referral_user_id']= $parentId;
			$this->request->data['refer_from']= '';
			$this->request->data['referral_code']= $this->Users->generateReferralCode();
			$this->request->data['unique_id']= $this->getUniqueId();
            $this->request->data['ip_address'] = $this->get_client_ip();
            $this->request->data['enabled'] = 'N';
            $this->request->data['email'] = strip_tags($email);
			$this->request->data['username'] = $username;
			$this->request->data['password'] = $password;
            $data = $this->request->data;
			$user = $this->Users->patchEntity($user, $this->request->data);
			
			if($user->errors()){
                $error_msg = [];
                foreach( $user->errors() as $errors){
                    if(is_array($errors)){
                        foreach($errors as $error){
                            $error_msg[]    =   $error;
                        }
                    }else{
                        $error_msg[]    =   $errors;
                    }
                }
			

                if(!empty($error_msg)){
                    $this->Flash->error(
                        __(implode(" AND ", $error_msg))
                    );
                }
				return $this->redirect('front/users/register');
			}
			
		/* 	if(!$user->errors())
			{
				$refer_user = $this->Users->find('all',array('fields'=>['id'],'conditions'=>array('referral_code'=>$this->request->data['refer_from'])))->first();
				if(!empty($refer_user)){
					$user->referral_user_id = $refer_user['id'];
				}
			} */
			if ($usrDetail = $this->Users->save($user)) {
				
				//$data['userLink'] = BASEURL.'front/users/verify/'.$usrDetail->unique_id;
			/*	$data['userLink'] = $usrDetail->unique_id;
				$email = new Email('default');
				$email->viewVars(['data'=>$data]);
				$email->from([$this->setting['email_from']] )
					->to($this->request->data['email'])
					->subject('You are registered successfully at Exchange. Please verify your account')
					->emailFormat('html')
					->template('signup')
					->send();*/
				
				$this->Flash->success(__('You have successfully registered your account. Please check your inbox or spam folder'));
				return $this->redirect('front/users/verify');
				
			}
			else {
				$this->Flash->success(__('Unable to register ! Try Again'));
				return $this->redirect('front/users/register');
			}
			
		}
		$referralBoxReadOnly = (!empty($getReferralCodeUrl)) ? "readonly" : ""; 
		$this->set('user',$user);
		$this->set('getReferralCodeUrl',$getReferralCodeUrl);
		$this->set('referralBoxReadOnly',$referralBoxReadOnly);
		$this->set('getEmail',$getEmail);
	}
	
	public function successregister()
	{
		$this->set('title' , 'HC :Succss Register');
		$this->set('message' , 'Successfully registered. Please Check email to verify your email');
	}
	
    public function login()
    {
		
		$this->loadModel('Coinpair');
		$secondVerification = 0;
		$googleAuthVerification = 0;
        $this->set('title' , 'Login');
		$this->set('username',"");
		$this->set('password',"");
        if ($this->request->is('post')) {
			//$this->Flash->error(__('Exchange is under maintenance'));
			
			// for maintenance mode
			//return $this->redirect('front/login');
			
			$password = strip_tags($this->request->data['password']);
			$username = filter_var($this->request->data['username'], FILTER_SANITIZE_STRING);
			
			/*   $allowLoginArr = ['avinash1988','mass1'];
			if(!in_array($username,$allowLoginArr)) {
				$this->Flash->error(__('Exchange is under maintenance'));
				return $this->redirect(['controller'=>'Users','action'=>'login']);
			}  */
			
			
			/* $captchaResp = $this->request->data['g-recaptcha-response'];
			 if(empty($captchaResp)){
				$this->Flash->error(__('please verify captcha.'));
				return $this->redirect('front/login');
			} */
			 
			// call sso api start
			$ssoArr = [];
			$ssoArr['email'] = strip_tags($this->request->data['username']);
			$ssoArr['passwd'] = strip_tags($this->request->data['password']);
			
			/* 
			if($ssoRespDecode['success']==false){
				$respMsg = $ssoRespDecode['message'];
				$this->Flash->error(__($respMsg));
				return $this->redirect(['controller'=>'users','action'=>'login']);
			} 
			// call sso api end
			$encryptPassword = (new DefaultPasswordHasher)->hash($this->request->data['password']);
			$checkUserExist = $this->Users->find("all",['conditions'=>["OR"=>[["email"=>$username],["username"=>$username]]]])->hydrate(false)->first();
			if(empty($checkUserExist)){
				$newUserObj = $this->Users->newEntity();
				$getIntrAddress = $this->Users->getIntrAddressByAccount($ssoRespDecode['data']['email']);
			
				// register user start
				$this->request->data['refer_from']= '';
				$this->request->data['referral_code']= $this->Users->generateReferralCode();
				$this->request->data['unique_id']= $this->getUniqueId();
				$this->request->data['ip_address'] = $this->get_client_ip();
				$this->request->data['enabled'] = 'Y';
				$this->request->data['intr_address'] = $getIntrAddress;
				$this->request->data['email'] = strip_tags($ssoRespDecode['data']['email']);
				$this->request->data['username'] = $ssoRespDecode['data']['username'];
				$this->request->data['password'] = $password;
				$data = $this->request->data;
				$newUserObj = $this->Users->patchEntity($newUserObj, $this->request->data);

				$userInsert = $this->Users->save($newUserObj);
				// register user end
			} */
			
			
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
			
			
			
			// secondVerification
			$password = $this->request->data['password'];
			
			
			
			$findSecondVerificationSetting = $this->Users->find("all",['conditions'=>["OR"=>[["email"=>$username],["username"=>$username]]]])->hydrate(false)->first();
			
			$this->set('username',$username);
			$this->set('password',$password);
			if(!isset($this->request->data['second_verification']) && !isset($this->request->data['google_verification'])) {
				
				//$findSecondVerificationSetting = $this->Users->find("all",['conditions'=>["OR"=>[["email"=>$username],["username"=>$username]]]])->hydrate(false)->first();
				
				if(!empty($findSecondVerificationSetting)){
					$existedHassPass = $findSecondVerificationSetting['password'];
					$checkPass = (new DefaultPasswordHasher)->hash($password, $existedHassPass);
					if($checkPass){
						$checkSecondVerification = $findSecondVerificationSetting['second_verification'];
						$checkGoogleAuthVerification = $findSecondVerificationSetting['g_auth_enable'];
						if($checkGoogleAuthVerification=="Y"){
							$googleAuthVerification = 1;
							
							
						}
						if($checkSecondVerification=="Y"){
							$userEmail = $findSecondVerificationSetting['email'];
							$secondVerification = 1;
							$extraCode = rand(100000000,999999999);
							$this->request->session()->write('extracode', $extraCode);
							$data['extraCode'] = $extraCode;
							$data['username'] = $findSecondVerificationSetting['username'];
							// send verification code $data['userLink'] = BASEURL.'front/users/verify/'.$usrDetail->unique_id;
							$email = new Email('default');
							$email->viewVars(['data'=>$data]);
							$email->from([$this->setting['email_from']] )
								->to($userEmail)
								->subject('Second Verification code for login')
								->emailFormat('html')
								->template('second_verification')
								->send();
							
						}
					}
				}
			}
			else if(isset($this->request->data['second_verification'])) {
				$getSecondVerification = $this->request->data['second_verification'];
				$readVerifyCode = $this->request->session()->read('extracode');
				if($readVerifyCode != $getSecondVerification ){
					 $this->Flash->error(__('Invalid verification Code. Try Again'));
					 $this->redirect(['controller'=>'users','action'=>'login']);
				}
			}
			else if(isset($this->request->data['google_verification'])) {
				$getSecondGooAuthVerifyCode = $this->request->data['google_verification'];
				$getSecretCode = $findSecondVerificationSetting['g_secret'];
				$checkSecondGooAuthVerifyCode = $this->Users->verifyCode($getSecretCode,$getSecondGooAuthVerifyCode,2);
				if(!$checkSecondGooAuthVerifyCode){
					 $this->Flash->error(__('Invalid Google Auth Code. Try Again'));
					 $this->redirect(['controller'=>'users','action'=>'login']);
				}
			}
			
			if($secondVerification==0 && $googleAuthVerification==0) {
				$user = $findSecondVerificationSetting;
				
				if ($user) {
					$this->Auth->setUser($findSecondVerificationSetting);
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
					
					$user = $this->Users->get($user['id']);
					if(empty($user->referral_code)) {
						$user->referral_code= $this->Users->generateReferralCode();
					}
					$user->last_login = date("Y-m-d H:i:s");
					$this->Users->save($user);
					
					// assign coin at login if not assing at register verification
					//$this->Users->assginCoinOnLogin($user['id']);
					//return $this->redirect($this->Auth->redirectUrl());
					$searchData = array('Coinpair.status'=>1);
					$currentCoinPairDetail = $this->Coinpair->find('all',['conditions'=>$searchData,'contain'=>['cryptocoin_first','cryptocoin_second']])->hydrate(false)->first();
					
					return $this->redirect(['controller'=>'exchange','action'=>'index',$currentCoinPairDetail['cryptocoin_first']['short_name'],$currentCoinPairDetail['cryptocoin_second']['short_name']]);
				}else if($user && $user['enabled']=='N'){
					 $this->Flash->error(__('Your account is not verified.'));
				}else{

					$this->Flash->error(__('Invalid username or password.'));
				}
			}
        }
		$this->set('secondVerification',$secondVerification);
		$this->set('googleAuthVerification',$googleAuthVerification);
		/* $user  = $this->Users->newEntity();
		$this->set('user',$user); */
    }
    
    public function referral()
	{
		$user  = $this->Users->get($this->Auth->user('id'));
		$this->set('user',$user);
		$this->set('title','Referrals');
		$this->set('listing', $this->Paginator->paginate($this->Users, [
				
				'conditions' => ['referral_user_id'=>$this->Auth->user('id'),'enabled'=>'Y'],
				'limit' => $this->setting['pagination'],
				'order'=>['Users.id'=>'desc']
				
			]));
	}
	 
	
	
	public function changepassword(){
		$this->set('title',' Change password');
		$users  = $this->Users->get($this->Auth->user('id'));
		
		if($this->request->is(['post','put'])){
			
			$users = $this->Users->patchEntity($users, [
                'old_password'  => $this->request->data['old_password'],
                'password'      => $this->request->data['new_password'],
                'new_password'     => $this->request->data['new_password'],
                'confirm_password'     => $this->request->data['confirm_password']
            ],
            ['validate' => 'password']
			);
			
			if($this->Users->save($users)){

				$this->Flash->success('Your password has been updated.');
				$this->redirect(['controller'=>'users','action'=>'changepassword']);
			}else{
				$this->Flash->error('Some Errors Occurred.');
			}
		}
		$this->set('users',$users);
	}
	
    public function logout()
    {
        $this->Auth->logout();
		
		$this->redirect('/');
    }

    public function email()
    {
        /* $email = new Email('default');
        $email->from('ayushi.agrawal@brsoftech.org')
            ->to('ayushi@mailinator.com')
            ->subject('You are registered successfully.')
            ->send(); */
        die('sent');
    }


    public function verify()
    {
		
		$user  = $this->Users->newEntity();
		$this->set('user',$user);
		if ($this->request->is(['post','put'])) {
			 /* $captchaResp = $this->request->data['g-recaptcha-response'];
			 if(empty($captchaResp)){
				$this->Flash->error(__('please verify captcha.'));
				return $this->redirect('front/users/verify');
			}  */
			
			$code = $this->request->data['verify_code'];
			$code = strip_tags($this->request->data['verify_code']);
			
			if(!empty($code))
			{
				$check = $this->Users->find('all',['fields'=>['id','name'],'conditions'=>['unique_id'=>$code]])->hydrate(false)->first();
				if(!empty($check))
				{
					$user = $this->Users->get($check['id']);
					$userEmail = $user->email;
				
					if( $user->enabled=='N')
					{
						
						$getIntrAddress = $this->Users->getIntrAddress($user->email);
						
						$user->enabled = 'Y';
					//	$user->intr_address = "$getIntrAddress";
						if($this->Users->save($user))
						{
							
							
							$userId = $check['id'];
							//$assingCoin = $this->Users->assginCoinOnVerifition($userId);
							$this->Flash->success(__('Your account has been activated.'));
							return $this->redirect(['prefix'=>'front','controller'=>'Users','action' => 'login']);
						}
					}
					else
					{
						$this->Flash->error(__('Your Account already verified.'));
						return $this->redirect(['prefix'=>'front','controller'=>'Users','action' => 'login']);
					}

				}
				else
				{
					return $this->redirect(['prefix'=>'front','controller'=>'Users','action' => 'login']);
				}


			}
			else {
				$this->Flash->error(__('please enter verification code.'));
				return $this->redirect('front/users/verify');
			}
		}
       
    }
	
	
	public function changeGoogleauthVerification(){
		
		
		if($this->request->is('ajax')) {
			$cuUserId = $this->Auth->User("id");
			$verificationStatus = $this->request->data['verification_status'];
			if(in_array($verificationStatus,["Y","N"])) {
				$user = $this->Users->get($cuUserId);
				$user->g_auth_enable = $verificationStatus;
				if($this->Users->save($user))
				{
					echo "success";
				}
				else {
					echo "error";
				}
			}
			else {
				echo " Invalid Verification Status";
			}

			die;
		}
		die;
	}
	public function changeSecondVerification(){
		/* if($this->request->is('ajax')) {
			$g_auth = new GoogleAuthenticator();
			$cuUserId = $this->Auth->User("id");
			$verificationStatus = $this->request->data['verification_status'];
			if(in_array($verificationStatus,["Y","N"])) {
				$user = $this->Users->get($cuUserId);
				if (!$user->g_auth) {
                    $user->g_auth = $g_auth->generateSecret();
                }
				$user->second_verification = $verificationStatus;
				if ($this->Users->save($user)) {
                    $authQrCode = $g_auth->getUrl($user->username, "hedgeconnect.co", $user->g_auth);
                    if ($verificationStatus == "Y") {
                        $data = ['qr' => $authQrCode, 'success' => 1];
                    } else {
                        $data = ['qr' => "", 'success' => 0];
}
                    echo json_encode($data);
                    //echo "success";
                } else {
                    $data = ['qr' => "", 'success' => 0];
                    echo json_encode($data);
                     //echo "error";
                }
			}
			else {
				echo " Invalid Verification Status";
			}

			die;
		}
		die; */
		
		if($this->request->is('ajax')) {
			$cuUserId = $this->Auth->User("id");
			$verificationStatus = $this->request->data['verification_status'];
			if(in_array($verificationStatus,["Y","N"])) {
				$user = $this->Users->get($cuUserId);
				$user->second_verification = $verificationStatus;
				if($this->Users->save($user))
				{
					echo "success";
				}
				else {
					echo "error";
				}
			}
			else {
				echo " Invalid Verification Status";
			}

			die;
		}
		die;
	}


	public function forgetPassword()
	{
		
		$this->set('title' , 'WinnerBank: Forget Password');
		if ($this->request->is(['post','put'])) 
		{
			$captchaResp = $this->request->data['g-recaptcha-response'];
			if(empty($captchaResp)){
				$this->Flash->error(__('please verify captcha.'));
				return $this->redirect('front/forgot');
			}
			$email = trim($this->request->data['email']);
			$email = strip_tags($email);
			$user_record = $this->Users->find()
			->where(['email' => $email])->first();
			if($user_record && !empty($user_record))
			{
				$new_password = rand(111111,99999999);  

				// call sso api start
				$ssoArr = [];
				$ssoArr['email'] = strip_tags($email);
				$ssoArr['new_password'] = strip_tags($new_password);
				$callSso = $this->Users->sso_action($ssoArr,"forgot_pass");
				$ssoRespDecode = json_decode($callSso,true);  
				
				if($ssoRespDecode['success']==false){
					$respMsg = $ssoRespDecode['message'];
					$this->Flash->error(__($respMsg));
					return $this->redirect(['controller'=>'users','action'=>'forgot']);
				} 
				// call sso api end	
				$query = $this->Users->query();
				$query->update()
					->set(['password'  =>  (new DefaultPasswordHasher)->hash($new_password)])
					->where(['email' => $email])
					->execute();
				$user_record['new_pass'] = $new_password;
				
				
				
				
				$email = new Email('default');
				$email->viewVars(['data'=>$user_record]);
				$email->from([$this->setting['email_from']] )
					->to($user_record['email'])
					->subject('Your New Password Is.')
					->emailFormat('html')
					->template('forgot_password')
					->send();
				//$res['success']= 1;
				//$res['string'] = '<div class="alert alert-success"><strong>Successful! </strong>Please check your email to get your password.</div>';
				$this->Flash->success(__('Please check your email to get your password.'));
				return $this->redirect('front/login');
			}
			else{
				/* $res['success']= 0;
				$res['string'] = '<div class="alert alert-success"><strong>Error! </strong>Email does not exist.</div>'; */
				$this->Flash->error(__('Email does not exist.'));
				return $this->redirect('front/forgot');
			}
		}
		
	}
	
	public function security()
	{
		$this->set('title','Security');
		$user  = $this->Users->get($this->Auth->user('id'));
		if(empty($user->g_secret)){
			$getSecret = $this->Users->createSecret();
			$user->g_secret = $getSecret;
			$this->Users->save($user);
		}
		$this->set('user',$user);	
		$secret = $user->g_secret;
		$googleVerify = $user->g_verify;
		$secondVerification = $user->second_verification;
		$googleAuthEnable = $user->g_auth_enable;
		$googleAuthUrl =  $this->Users->getQRCodeGoogleUrl('WinnerBank', $secret);
		$this->set('googleAuthUrl',$googleAuthUrl);			
		$this->set('googleAuthEnable',$googleAuthEnable);			
		$this->set('googleVerify',$googleVerify);			
		$this->set('secondVerification',$secondVerification);			
		if ($this->request->is(['post','put'])) {
			
			if(!empty($this->request->data['submitpass']) && isset($this->request->data['old_password'])){
				
				$getInputCode = $this->request->data['email_code'];
				if(empty($getInputCode)){
					$this->Flash->error('Please enter security code.');
					return $this->redirect(['controller'=>'users','action'=>'security']);
				}
				$getCodeFromSession = $this->request->session()->read('email_code');
				/* if($getCodeFromSession != $getInputCode){
					$this->Flash->error('Please enter Valid code.');
					return $this->redirect(['controller'=>'users','action'=>'security']);
				} */
				
				$newPass =  filter_var($this->request->data['new_password'], FILTER_SANITIZE_STRING);
				$confNewPass =  filter_var($this->request->data['confirm_password'], FILTER_SANITIZE_STRING);
				$oldPass =  filter_var($this->request->data['old_password'], FILTER_SANITIZE_STRING);
				if($newPass != $confNewPass){
					$this->Flash->error('New Password and confirm password should be same.');
					return $this->redirect(['controller'=>'users','action'=>'security']);
				}
				
				// call sso api start
				$ssoArr = [];
				$ssoArr['email'] = strip_tags($user->email);
				$ssoArr['old_password'] = strip_tags($this->request->data['old_password']);
				$ssoArr['new_password'] = strip_tags($newPass);
				$callSso = $this->Users->sso_action($ssoArr,"change_pass");
				$ssoRespDecode = json_decode($callSso,true);  
				
				if($ssoRespDecode['success']==false){
					$respMsg = $ssoRespDecode['message'];
					$this->Flash->error(__($respMsg));
					return $this->redirect(['controller'=>'users','action'=>'security']);
				} 
				// call sso api end
				
				
				$users = $this->Users->patchEntity($user, [
                'old_password'  => $oldPass,
                'password'      => $newPass,
                'new_password'     => $newPass,
                'confirm_password'     => $confNewPass
				]);
				
				
				
				if($user=$this->Users->save($users)){
					$this->request->session()->write('email_code', '');
					$this->Flash->success('Your password has been updated.');
					return $this->redirect(['controller'=>'users','action'=>'security']);
				}else{
					$this->Flash->error('Your current password is wrong');
				}
			
			}
			
			
			if(!empty($this->request->data['submitauth'])){
				$getInputCode = strip_tags($this->request->data['authcode']);
				
				if(empty($getInputCode)){
					$this->Flash->error('Please enter auth code.');
					return $this->redirect(['controller'=>'users','action'=>'security']);
				}
				$checkResult = $this->Users->verifyCode($secret, $getInputCode, 2);    // 2 = 2*30sec clock tolerance
				if ($checkResult) {
					$users = $this->Users->patchEntity($user, [
					'g_verify'  => "Y"
					]);
					$user=$this->Users->save($users);
					$this->Flash->success('auth code verified');
					return $this->redirect(['controller'=>'users','action'=>'security']);
				} else {
					$this->Flash->error('invalid auth code.');
					return $this->redirect(['controller'=>'users','action'=>'security']);
				}
			}
			
			if(!empty($this->request->data['submitsecondlogin'])){
				
				
				$updatArr = [] ;
				$updatArr['second_verification'] =  "N";
				$updatArr['g_auth_enable'] = "N"  ;
				if(isset($this->request->data['secondauth']) && !empty($this->request->data['secondauth'])){
					$getInputCode = strip_tags($this->request->data['secondauth']);
					$updatArr[$getInputCode] = "Y"  ;
				}
				
				$users = $this->Users->patchEntity($user, $updatArr);
				if($user=$this->Users->save($users)) {
					$this->Flash->success('2FA option saved.');
					return $this->redirect(['controller'=>'users','action'=>'security']);
				} else {
					$this->Flash->error('Unbale to save 2FA ! Try Again.');
					return $this->redirect(['controller'=>'users','action'=>'security']);
				}
			}
		}
		
	
	}
	
	
	public function sendEmailCode(){
		if($this->request->is('ajax')){
			
			echo $new_code = rand(111111,99999999); 
			$this->request->session()->write('email_code', $new_code);
			$user  = $this->Users->get($this->Auth->user('id'));
			$userEmail = $user->email;
			$data['email'] = $user->email;
			$data['username'] = $user->username;
			$data['new_code'] = $new_code;
			$email = new Email('default');
			$email->viewVars(['data'=>$data]);
			$email->from([$this->setting['email_from']=>'LiveCrypto'])
				->to($userEmail)
				->subject('Verification code of LiveCrypto')
				->emailFormat('html')
				->template('email_code')
				->send();
		}
		
	}
	
	
	
	public function support()
	{
		$this->loadModel('Support');
		$this->set('title','Support');
		$userId = $this->Auth->user('id');
		$user  = $this->Users->get($userId);
		$this->set('user',$user);	
		
		$before_image = $user->image;
		if ($this->request->is(['post','put'])) {
			
			$issueType = filter_var($this->request->data['issue_type'], FILTER_SANITIZE_STRING);
			$issue = filter_var($this->request->data['issue'], FILTER_SANITIZE_STRING);
			$txId = filter_var(strip_tags($this->request->data['tx_id']), FILTER_SANITIZE_STRING);
			
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
	
	public function tickets()
	{
		
		$this->set('title','My profile');
		$userId = $this->Auth->user('id');
		$user  = $this->Users->get($userId);
		$this->set('user',$user);
		
		//get current date logged in users
		$this->loadModel('Support');
		$logs = $this->Support->find();
		$create_date = $logs->func()->date_format([
                'Support.created_at' => 'literal',
                "'%d %M, %Y %h:%i %p'" => 'literal'
            ]);
		$tickets=$this->Support->find('all',['fields'=>['date'=>$create_date,'issue_type','issue_file','issue','status','response'],
											'conditions'=>['user_id'=>$userId],
											'order'=>['Support.id'=>'desc']])
											->hydrate(false)
											->toArray();
		$this->set('tickets',$tickets);	
	}
	
	
	public function transactionlist(){
		
		$this->loadModel('Transactions');
		$userId = $this->Auth->user('id');
		
		$withdrawalList = $this->Transactions->find('all',['conditions'=>['Transactions.user_id'=>$userId,
																		  'Transactions.tx_type'=>'withdrawal'],
															'contain'=>['cryptocoin'],		
															'order' => ['Transactions.id'=>'desc']])
																	  ->hydrate(false)
																	  ->toArray();
		$this->set('withdrawalList',$withdrawalList);															  
		
		$depositList = $this->Transactions->find('all',['conditions'=>['Transactions.user_id'=>$userId,
																	   'Transactions.tx_type'=>'purchase'],
														'contain'=>['cryptocoin'],			
														'order' => ['Transactions.id'=>'desc']])
																  ->hydrate(false)
																  ->toArray();
		$this->set('depositList',$depositList);	

		$referAmtList = $this->Transactions->find('all',['conditions'=>['Transactions.user_id'=>$userId,
																	   'Transactions.coin_amount > '=>0,
																	   'Transactions.remark'=>'adminFees',
																	   'Transactions.tx_type IN '=>['sell_exchange','buy_exchange']],
														'contain'=>['cryptocoin'],			
														'order' => ['Transactions.id'=>'desc']])
																  ->hydrate(false)
																  ->toArray();
		$this->set('referAmtList',$referAmtList);			
		
	}
	
	
	
	
	
	public function mybuyorderlist($firstCoin=null,$secondCoin=null){
		
		if(empty($firstCoin) || empty($secondCoin)){
			$this->Flash->error(__('No Coin Found'));
			return $this->redirect(['controller'=>'pages','action' => 'dashboard']);
		}
		
		$this->loadModel('BuyExchange');
		$this->loadModel('Cryptocoin');
		$currentUserId = $this->Auth->user('id');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
		$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
		
		if(empty($getFirstCoinDetail) || empty($getSecondCoinDetail)){
			$this->Flash->error(__('No Coin Found'));
			return $this->redirect(['controller'=>'pages','action' => 'dashboard']);
		}
		
		$firstCoinId = $getFirstCoinDetail['id'];
		$secondCoinId = $getSecondCoinDetail['id'];
		
		$searchData['BuyExchange.buyer_user_id'] = $currentUserId;
		$searchData['BuyExchange.buy_spend_coin_id'] = $firstCoinId;
		$searchData['BuyExchange.buy_get_coin_id'] = $secondCoinId;
		
		 if($this->request->is(['post' ,'put']) ) 
		 {
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->
			data);
			$search = $this->request->data;
			if($search['status']=='pending') {
					$searchData['AND'][]=array('status in'=> ['pending','processing']);
					$limit=1000000;
				}
				else {
					$searchData['AND'][]=array('status LIKE'=> '%'.$search['status'].'%');
					$limit=1000000;
				}
			
		 }
		$getBuyOrderList = $this->Paginator->paginate($this->BuyExchange, [
           'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		
		$this->set('getBuyOrderList',$getBuyOrderList );
		$this->set('firstCoin',$firstCoin);
		$this->set('secondCoin',$secondCoin);
		
	}
	
	public function mybuyorderlistSearch($firstCoin=null,$secondCoin=null){
		
		if(empty($firstCoin) || empty($secondCoin)){
			die;
		}
		
		if ($this->request->is('ajax')) {
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			
			
			$this->loadModel('BuyExchange');
			$this->loadModel('Cryptocoin');
			$currentUserId = $this->Auth->user('id');
			$searchData = array();
			$limit =  $this->setting['pagination'];
			$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
			$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
			
			if(empty($getFirstCoinDetail) || empty($getSecondCoinDetail)){
				die;
			}
			
			$firstCoinId = $getFirstCoinDetail['id'];
			$secondCoinId = $getSecondCoinDetail['id'];
			
			$searchData['BuyExchange.buyer_user_id'] = $currentUserId;
			$searchData['BuyExchange.buy_spend_coin_id'] = $firstCoinId;
			$searchData['BuyExchange.buy_get_coin_id'] = $secondCoinId;
			$getBuyOrderList = $this->Paginator->paginate($this->BuyExchange, [
			   'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			
			$this->set('getBuyOrderList',$getBuyOrderList );
			$this->set('firstCoin',$firstCoin);
			$this->set('secondCoin',$secondCoin);
			
			
		}
	}



	public function mysellorderlist($firstCoin=null,$secondCoin=null){
		
		if(empty($firstCoin) || empty($secondCoin)){
			return $this->redirect(['controller'=>'pages','action' => 'dashboard']);
		}
		
		$this->loadModel('BuyExchange');
		$this->loadModel('SellExchange');
		$this->loadModel('Cryptocoin');
		$currentUserId = $this->Auth->user('id');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
		$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
		
		if(empty($getFirstCoinDetail) || empty($getSecondCoinDetail)){
			$this->Flash->error(__('No Coin Found'));
			return $this->redirect(['controller'=>'pages','action' => 'dashboard']);
		}
		
		$firstCoinId = $getFirstCoinDetail['id'];
		$secondCoinId = $getSecondCoinDetail['id'];
	
		$searchData['SellExchange.seller_user_id'] = $currentUserId;
		$searchData['SellExchange.sell_spend_coin_id'] = $secondCoinId;
		$searchData['SellExchange.sell_get_coin_id'] = $firstCoinId;
		if($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->
			data);
			$search = $this->request->data;
			if($search['status'] != '') {
				$searchData['AND'][]=array('status LIKE'=> '%'.$search['status'].'%');
				$limit=1000000;
			}
		}
		$getSellOrderList = $this->Paginator->paginate($this->SellExchange, [
           'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		
		$this->set('getSellOrderList',$getSellOrderList );
		$this->set('firstCoin',$firstCoin);
		$this->set('secondCoin',$secondCoin);
		
	}
	
	public function mysellorderlistSearch($firstCoin=null,$secondCoin=null){
		
		if(empty($firstCoin) || empty($secondCoin)){
			return $this->redirect(['controller'=>'exchange','action' => 'index']);
		}
		
		if ($this->request->is('ajax')) {
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			
			
			$this->loadModel('BuyExchange');
			$this->loadModel('SellExchange');
			$this->loadModel('Cryptocoin');
			$currentUserId = $this->Auth->user('id');
			$searchData = array();
			$limit =  $this->setting['pagination'];
			$getFirstCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$firstCoin]])->hydrate(false)->first();
			$getSecondCoinDetail = $this->Cryptocoin->find('all',['conditions'=>['short_name'=>$secondCoin]])->hydrate(false)->first();
			
			if(empty($getFirstCoinDetail) || empty($getSecondCoinDetail)){
				die;
			}
			
			$firstCoinId = $getFirstCoinDetail['id'];
			$secondCoinId = $getSecondCoinDetail['id'];
			
			$searchData['SellExchange.seller_user_id'] = $currentUserId;
			$searchData['SellExchange.sell_spend_coin_id'] = $secondCoinId;
			$searchData['SellExchange.sell_get_coin_id'] = $firstCoinId;
			$getSellOrderList = $this->Paginator->paginate($this->SellExchange, [
			   'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			
			$this->set('getSellOrderList',$getSellOrderList );
			$this->set('firstCoin',$firstCoin);
			$this->set('secondCoin',$secondCoin);
		}
	}		
	
	
	
	/* public function cancelwithdrawal($getId){
		
		$cudate = date('Y-m-d H:i:s');
		$this->loadModel('Transactions');
		if($this->request->is('ajax')) {
			$currentUserId = $this->Auth->user('id');
			
			$conditions = []; 
			$conditions['user_id'] = $currentUserId;
			$conditions['id'] = $getId;
			$conditions['withdrawal_id IS'] = NULL;
			$conditions['tx_type'] = 'withdrawal';
			$conditions['withdrawal_send'] = 'N';
			$conditions['cryptocoin_id'] = 2;
			
			$getRecord = $this->Transactions->find('all',['conditions'=>$conditions])->first();
			if(!empty($getRecord)){
				$result = $this->Transactions->delete($getRecord);
				echo "1";
			}
			else {
				echo "0";
			}
		}
		else {
			echo "0";
		}
		die; 
	} */
	
	
	public function impersonate($username = null)
    {
		if(empty($username)) {
			return $this->redirect('/');
		}
		
		$findUser = $this->Users->find('all',['conditions'=>['md5(username)'=>$username]])->first()->toArray();
		if(empty($findUser)){
			return $this->redirect('/');
		}
		
		
		$this->request->data['username'] = $findUser['username'];
		
		$this->loadModel('Coinpair');
		$secondVerification = 0;
        $this->set('title' , 'Login');
		$this->set('username',"");
		$this->set('password',"");
     
			
			$getUserName = $this->Auth->User('username');
			if($getUserName != 'admin'){
				return $this->redirect('/');
			}
			
			$username = filter_var($this->request->data['username'], FILTER_SANITIZE_STRING);
			/* $allowLoginArr = ['avinash1988','vipin1988'];
			if(!in_array($username,$allowLoginArr)) {
				$this->Flash->error(__('Exchange is under maintenance'));
				return $this->redirect(['controller'=>'Users','action'=>'login']);
			} */
			
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
			
				$user = $findUser;
				
				if ($user &&  $user['user_type']=='U' && $user['enabled']=='Y' && $user['is_deleted']=='N') {
					$this->Auth->setUser($user);
					
					
					$searchData = array('Coinpair.status'=>1);
					$currentCoinPairDetail = $this->Coinpair->find('all',['conditions'=>$searchData,'contain'=>['cryptocoin_first','cryptocoin_second']])->hydrate(false)->first();
					
					return $this->redirect(['controller'=>'exchange','action'=>'index',$currentCoinPairDetail['cryptocoin_first']['short_name'],$currentCoinPairDetail['cryptocoin_second']['short_name']]);
				}else if($user && $user['enabled']=='N'){
					 $this->Flash->error(__('Your account is not verified.'));
				}else{

					$this->Flash->error(__('Invalid username or password.'));
				}
			
       
		$this->set('secondVerification',$secondVerification);
		/* $user  = $this->Users->newEntity();
		$this->set('user',$user); */
    }
	
	
	public function ramtransfer()
	{
		$this->set('title','Ram Transfer');
		$userId  = $this->Auth->user('id');
		$user  = $this->Users->get($this->Auth->user('id'));
		$this->set('user',$user);	
		$cudate = date('Y-m-d H:i:s');
		
		$getRamCurrentPrice = $this->Users->getramcurrentprice();
		$this->set('getRamCurrentPrice',$getRamCurrentPrice);
		
		$getUserBalance = $this->Users->getLocalUserBalance($userId,3);
		$this->set('getUserBalance',$getUserBalance);
		if ($this->request->is(['post','put'])) {
			
				
				$uniqueAddress = filter_var($this->request->data['unique_address'], FILTER_SANITIZE_STRING);
				$amount	 = filter_var($this->request->data['amount'], FILTER_SANITIZE_STRING);
				$emailCode = filter_var($this->request->data['email_code'], FILTER_SANITIZE_STRING);
				
				// check for non empty fields
				if(empty($uniqueAddress) || empty($amount) || empty($emailCode)){
					$this->Flash->error('All Fields are required.');
					return $this->redirect(['controller'=>'users','action'=>'ramtransfer']);
				}
				
				// check for numeric ram amount
				if(!is_numeric($amount)){
					$this->Flash->error('Ram amount should be numeric.');
					return $this->redirect(['controller'=>'users','action'=>'ramtransfer']);
				}
				
				// check for numeric ram amount
				if($amount <= 0){
					$this->Flash->error('Ram amount should be positive.');
					return $this->redirect(['controller'=>'users','action'=>'ramtransfer']);
				}
				
				// check for email verification code
				$getCodeFromSession = $this->request->session()->read('email_code');
				if($getCodeFromSession != $emailCode){
					$this->Flash->error('Please enter Valid code.');
					return $this->redirect(['controller'=>'users','action'=>'ramtransfer']);
				}
				
				$findUser = $this->Users->find('all',['conditions'=>['unique_id'=>$uniqueAddress]])->hydrate(false)->first();
				if(empty($findUser)){
					$this->Flash->error('Invalid Unique Address.');
					return $this->redirect(['controller'=>'users','action'=>'ramtransfer']);	
				}
				
				$receiver_user_id = $findUser['id'];
				if($receiver_user_id == $userId){
					$this->Flash->error("You can't send ram amount to yourself.");
					return $this->redirect(['controller'=>'users','action'=>'ramtransfer']);	
				}
				
				$getUserBalance = $this->Users->getLocalUserBalance($userId,3);
				if($getUserBalance < $amount) {
					$this->Flash->error("you have insufficient balanace in ram wallet.");
					return $this->redirect(['controller'=>'users','action'=>'ramtransfer']);
				}
				
				$amountInUsd = $getRamCurrentPrice['currentprice_usd']*$amount;
				
				
				$deduct_tx_id =  $this->Users->getUniqueId($userId);
				
				// deduct balance from sender account
				$deductArr = [];
				$deductArr['user_id'] = $userId;
				$deductArr['withdrawa_send'] = "Y";
				$deductArr['withdrawal_tx_id'] = $deduct_tx_id;
				$deductArr['tx_id'] = $deduct_tx_id;
				$deductArr['cryptocoin_id'] = 3;
				$deductArr['wallet_address'] = $uniqueAddress;
				$deductArr['withdrawal_coin_price'] = $getRamCurrentPrice['currentprice_usd'];
				$deductArr['withdrawal_amount_in_usd'] = $amountInUsd;
				$deductArr['coin_amount'] = "-".$amount;
				$deductArr['tx_type'] = 'withdrawal';
				$deductArr['remark'] = 'transfer';
				$deductArr['status'] = 'completed';
				$deductArr['description'] = 'send transfer';
				$deductArr['current_balance'] = $getUserBalance;
				$deductArr['created'] = $cudate;
				$deductArr['updated'] = $cudate;
				
				// insert data
				$ramSendTransferObj=$this->Transactions->newEntity();
				$ramSendTransferObj=$this->Transactions->patchEntity($ramSendTransferObj,$deductArr);
				$ramSendTransferObj=$this->Transactions->save($ramSendTransferObj);
				if($ramSendTransferObj) {
					$transactionId = $ramSendTransferObj->id;
					
					// add Balance to receiver account
					$getReceiverUserBalance = $this->Users->getLocalUserBalance($receiver_user_id,3);
					$add_tx_id =  $this->Users->getUniqueId($receiver_user_id);
					$addAmountArr = [];
					$addAmountArr['user_id'] = $receiver_user_id;
					$addAmountArr['tx_id'] = $add_tx_id;
					$addAmountArr['wallet_address'] = $uniqueAddress;
					$addAmountArr['transaction_id'] = $transactionId;
					$addAmountArr['cryptocoin_id'] = 3;
					$addAmountArr['withdrawal_coin_price'] = $getRamCurrentPrice['currentprice_usd'];
					$addAmountArr['withdrawal_amount_in_usd'] = $amountInUsd;
					$addAmountArr['coin_amount'] = $amount;
					$addAmountArr['tx_type'] = 'purchase';
					$addAmountArr['remark'] = 'transfer';
					$addAmountArr['status'] = 'completed';
					$addAmountArr['description'] = 'receive transfer';
					$addAmountArr['current_balance'] = $getReceiverUserBalance;
					$addAmountArr['created'] = $cudate;
					$addAmountArr['updated'] = $cudate;
					
					// insert data
					$ramReceiverTransferObj=$this->Transactions->newEntity();
					$ramReceiverTransferObj=$this->Transactions->patchEntity($ramReceiverTransferObj,$addAmountArr);
					$ramReceiverTransferObj=$this->Transactions->save($ramReceiverTransferObj);
					
					$this->request->session()->write('email_code', '');
					
					$this->Flash->success('Ram Token transferred successfully.');
					return $this->redirect(['controller'=>'users','action'=>'ramtransfer']);
				}
				else{
					$this->Flash->error('Unable to transfer ram token ! Try Again');
					return $this->redirect(['controller'=>'users','action'=>'ramtransfer']);
				}
				
			
		}
		
	
	}
	
	 
}
