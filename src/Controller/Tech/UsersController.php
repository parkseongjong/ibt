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

namespace App\Controller\Tech;


use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Network\Exception\NotFoundException;

ini_set('memory_limit','1024M');
ini_set('max_execution_time', '60');

class UsersController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
	public function beforeRender(Event $event)
    {
		parent::beforeRender($event);
		if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'tech'){
			$action_name = $this->request->params['action'];
			if($action_name == 'authEmailConfirm' ||  $action_name == 'secondAuth' ||  $action_name == 'otpinfo' ){
				//$this->viewBuilder()->layout(false);
				$this->viewBuilder()->layout('login');
			}
		}
	}
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['authEmailConfirm','secondAuth','otpinfo','adminpasscheck']);
    }
	public function dashboard(){ 
			
			$this->set('title' , 'Dashboard');
	}
   
    // 1. IP 체크 => 실패시 login_fail_count + 1
	// 2. ID/PW 체크 => 실패시 login_fail_count + 1
	// 3. 로그인 5회 실패 시 잠김
	// 4. 잠겼을 경우는 email 인증 통해 풀기 -> 메일 인증 페이지 만들어야함.
	// 5. 
    public function login()
    {
		$this->loadModel('Users');
        if ($this->request->is('post')) {
			$username = filter_var($this->request->data['username'], FILTER_SANITIZE_STRING);
			$checkUserExist = $this->Users->find("all",['conditions'=>["OR"=>[["email"=>$username],["username"=>$username]],'user_type'=>'A']])
					->select(['id','email','username','name','user_type','ip_address','last_login','is_deleted','user_status','last_pw_change_date','login_fail_count','dormant'])->hydrate(false)->first();
			if(empty($checkUserExist)){
				$this->Flash->error(__('Invalid username or password'));
				return;
			}
			if($checkUserExist['user_status'] == 'B'){  // login block
				$this->set('user_status',$checkUserExist['user_status']);
				return;
			}
			if($checkUserExist['user_status'] == 'F'){  // 로그인 5회 이상 실패
				//$this->request->session()->write('selfCertification', $checkUserExist['id']);
				//return $this->redirect(['action' => 'selfCertification']);
				$result = $this->send_auth_email($checkUserExist['id'],$checkUserExist['email']);
				if($result == 'success'){
					$this->Flash->success('메일 전송이 완료되었습니다. 확인 후 메일 인증해주세요');
					return $this->redirect(['action'=>'authEmailConfirm']);
				}
				$this->Flash->error('연속적인 로그인 실패로 메일 인증 후에 이용 가능합니다');
				return;
			}

			// if($this->confirm_ip() == 'fail'){
			// 	$this->check_login_fail($checkUserExist['id'], $checkUserExist['login_fail_count']);
			// 	$this->Flash->error('접속 허용된 IP가 아닙니다');
			// 	return;
			// }
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

            if ($user && $user['enabled']=='Y' && $user['user_type']=='A' && $user['is_deleted']=='N') {
              /*  $this->loadModel('LoginSessions');
                $loginSession = $this->LoginSessions->find('all', ['conditions' => ['status'=>'ACTIVE','user_id'=>$user['id']],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
                $token = $this->getToken(10);
                $this->request->session()->write('loginToken', $token);
                $this->request->session()->write('loginTokenUserId', $checkUserExist['id']);
                $query = $this->LoginSessions->query();
                if(!empty($loginSession)){
                    $query->update()->set(['token' => $token,'updated'=>date('Y-m-d H:i:s')])->where(['id' => $loginSession['id']])->execute();
                } else {
                    $query->insert(['user_id','token','status','created','updated'])
                        ->values(['user_id'=>$user['id'],'token'=>$token,'status'=>'ACTIVE','created'=>date('Y-m-d H:i:s'),'updated'=>date('Y-m-d H:i:s')])->execute();
                }
                return $this->redirect(['action' => 'secondAuth']);*/

                $this->Auth->setUser($user);
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
				$this->add_system_log(200, $user['id'], 0, '관리자 로그인');
				return $this->redirect(['controller'=>'Pages','action'=>'dashboard']);
            }
			$this->add_system_log(300, 0, 0, 'username : '.$this->request->data['username'] .' 관리자 로그인 실패 (id 또는 pw 불일치)');
			$this->check_login_fail($checkUserExist['id'], $checkUserExist['login_fail_count']);
            $this->Flash->error(__('Invalid username or password'));
        }
    }
	/* 로그인 실패 횟수 확인 및 추가 -> 실패 카운트가 5번이면 차단 */
	private function check_login_fail($user_id, $fail_count){
		$this->loadModel('Users');
		$user = $this->Users->get($user_id);
		if($fail_count < 4){ // 로그인 실패 횟수 추가 후 리턴
			$user = $this->Users->patchEntity($user, ['login_fail_count' => $fail_count + 1]);
            $this->Users->save($user);
			return;
		}
		if($fail_count >= 4){ // 로그인 실패 횟수 추가 후 차단
			$user = $this->Users->patchEntity($user, ['login_fail_count' => $fail_count + 1,'user_status'=>'F','blocked'=>date('Y-m-d H:i:s')]);
            $this->Users->save($user);
			return;
		}
	}
	/* 로그인 차단 시 인증 메일 발송 */
	private function send_auth_email($user_id,$user_email){
		// 유저 테이블 plain_pass 를 이메일 인증 용으로 사용
		// 토큰 만들어서 디비에 넣고, 메일 발송시키기
		$this->loadModel('Users');
		$token = $this->getToken(6);

		$email = new Email('default');
		$email->viewVars(['token'=>$token]);
		$email->from(['cs@onefamilymall.com'=>'Coin IBT'])
			->to($user_email)
			->subject('[COIN IBT] PASSWORD CHANGE INFO')
			->emailFormat('html')
			->template('admin_email_code');
		if($email->send()){
			$query = $this->Users->query();
			$query->update()->set(['plain_pass'=>$token])->where(['id'=>$user_id])->execute();
			return 'success';
		}
		return 'fail';
	}


    public function adminpasscheck(){
        $this->loadModel('Users');

            //$username = $this->request->data('username');

            $username = 'admin';
            //$code = $this->request->data('auth_code');

            $user = $this->Users->find()->select(['id','plain_pass','username','user_type'])->where(["OR"=>[["email"=>$username],["username"=>$username]],'user_type'=>'A'])->first();


    
            //$user_code = $user->plain_pass;
                $query = $this->Users->query();
                $query->update()->set(['login_fail_count' => 0,'user_status'=>'A','blocked'=>date('Y-m-d H:i:s')])->where(['id'=>$user->id])->execute();
                //$this->Flash->success('계정 잠금이 해제 되었습니다.');

                echo "성공";
                //return $this->redirect(['action'=>'login']);

    }


	/* 메일 인증 확인 페이지 */
	public function authEmailConfirm(){
		$this->loadModel('Users');
		if($this->request->is('post')){
			$username = $this->request->data('username');
			$code = $this->request->data('auth_code');
			$user = $this->Users->find()->select(['id','plain_pass'])->where(["OR"=>[["email"=>$username],["username"=>$username]],'user_type'=>'A'])->first();
			$user_code = $user->plain_pass;
			if($code == $user_code){
				$query = $this->Users->query();
				$query->update()->set(['login_fail_count' => 0,'user_status'=>'A','blocked'=>date('Y-m-d H:i:s')])->where(['id'=>$user->id])->execute();
				$this->Flash->success('계정 잠금이 해제 되었습니다.');
				return $this->redirect(['action'=>'login']);
			}
			$this->Flash->error('인증 코드가 일치하지 않습니다.');
			return ;
		}
	}
	/* 2차 인증 */
	public function secondAuth(){
		$loginTokenUserId = $this->request->session()->read('loginTokenUserId');
		if(empty($loginTokenUserId)){
			$this->Flash->error('세션이 만료되었습니다 처음부터 다시 시작해주세요');
			return $this->redirect(['action' => 'login']);
		}
		if($this->request->is('post')){
			$otp_number = strip_tags($this->request->data['otp_number']);
			$user_id = strip_tags($this->Decrypt($this->request->data['loginTokenUserId']));
			if (empty($otp_number) ) {
				$this->Flash->error('OTP 번호를 입력해주세요');
				return;
			}
			if(strlen($otp_number) < 6 ){
				$this->Flash->error('OTP 번호를 6자로 입력해주세요');
				return;
			}
            $this->loadModel('LoginSessions');
            $tokenSession = $this->request->session()->read('loginToken');
            $loginSession = $this->LoginSessions->find('all', ['conditions' => ['status'=>'ACTIVE','token'=>$tokenSession],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
            if(empty($user_id) || empty($loginSession) || empty($tokenSession)){
				$this->Flash->error('세션이 만료되었습니다 처음부터 다시 시작해주세요');
				return $this->redirect(['action' => 'login']);
            }
			$loginSessionUser = $this->LoginSessions->find('all', ['conditions' => ['status'=>'ACTIVE','user_id'=>$user_id],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
			if($tokenSession != $loginSession['token'] || $loginSession['token'] != $loginSessionUser['token'] || $tokenSession != $loginSessionUser['token']){
				$query = $this->LoginSessions->query();
				$query->update()->set(['status'=>'INACTIVE'])->where(['user_id'=>$user_id])->execute();
				$this->request->session()->destroy();
				$this->Flash->error('세션이 만료되었습니다 처음부터 다시 시작해주세요');
				return $this->redirect(['action' => 'login']);
			}
			$user = $this->Users->get($user_id);
			$secret = $user->g_secret;
			if(empty($secret)){
				$this->Flash->error('등록된 OTP가 없습니다. OTP를 등록해주세요');
				return;
			}
			if($user->user_status == 'F'){  // 로그인 5회 이상 실패
				$result = $this->send_auth_email($user->id,$user->email);
				if($result == 'success'){
					$this->Flash->success('메일 전송이 완료되었습니다. 확인 후 메일 인증해주세요');
					return $this->redirect(['action'=>'authEmailConfirm']);
				}
				$this->Flash->error('연속적인 로그인 실패로 메일 인증 후에 이용 가능합니다');
				return;
			}
			$checkResult = $this->Users->verifyCode($secret, $otp_number, 2);    // 2 = 2*30sec clock tolerance
			if (!$checkResult) {
				$this->check_login_fail($user->id, $user->login_fail_count); // 로그인 실패 횟수 추가 및 5회 될 경우 로그인 제한 걸기
				$this->loadModel('ErrorLoginLogs');
				$new_loginLog = $this->ErrorLoginLogs->newEntity();
				$data['user_id'] = $user->id;
				$data['username'] = $user->username;
				$data['error'] = "OTP number does not match";
				$logs_patch = $this->ErrorLoginLogs->patchEntity($new_loginLog, $data);
				$this->ErrorLoginLogs->save($logs_patch);
				$this->Flash->error('OTP 번호가 일치하지 않습니다');
				return;
			}

			$user = $this->Users->find("all",['conditions'=>['id'=>$user_id]])->hydrate(false)->first();
			$this->loadModel('LoginLogs');
			$new_log = $this->LoginLogs->newEntity();
			$data['user_id'] = $user_id;
			$data['ip_address'] = $this->get_client_ip();
			$log_patch = $this->LoginLogs->patchEntity($new_log, $data);
			$this->LoginLogs->save($log_patch);
			$this->Auth->setUser($user);
			$this->add_system_log(200, $user['id'], 0, '관리자 로그인');
			// 세션 삭제 시키기
			$this->request->session()->delete('loginToken');
			$this->request->session()->delete('loginTokenUserId');

			//$this->check_password_date($user['last_pw_change_date']); //최근 비밀번호 변경 날짜 확인

			$user = $this->Users->get($user['id']);
			$user = $this->Users->patchEntity($user, [
				'last_login' => date("Y-m-d H:i:s"),
				'g_verify'=>'Y',
				'login_fail_count'=> 0 // 로그인 실패 횟수 초기화
			]);
			$this->Users->save($user);
			return $this->redirect(['controller'=>'Pages','action'=>'dashboard']);
		}
	}
	/* OTP 안내 */
	public function otpinfo(){
		$user_id = $this->request->session()->read('loginTokenUserId');
		if(empty($user_id)){
			$this->Flash->error('세션이 만료되었습니다 처음부터 다시 시작해주세요');
			return $this->redirect(['action' => 'login']);
		}
		$this->loadModel('LoginSessions');
		$tokenSession = $this->request->session()->read('loginToken');
		$loginSession = $this->LoginSessions->find('all', ['conditions' => ['status'=>'ACTIVE','token'=>$tokenSession],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
		if(empty($user_id) || empty($loginSession) || empty($tokenSession)){
			$this->Flash->error('세션이 만료되었습니다 처음부터 다시 시작해주세요');
			return $this->redirect(['action' => 'login']);
		}
		$loginSessionUser = $this->LoginSessions->find('all', ['conditions' => ['status'=>'ACTIVE','user_id'=>$user_id],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
		if($tokenSession != $loginSession['token'] || $loginSession['token'] != $loginSessionUser['token'] || $tokenSession != $loginSessionUser['token']){
			$query = $this->LoginSessions->query();
			$query->update()->set(['status'=>'INACTIVE'])->where(['user_id'=>$user_id])->execute();
			$this->request->session()->destroy();
			$this->Flash->error('세션이 만료되었습니다 처음부터 다시 시작해주세요');
			return $this->redirect(['action' => 'login']);
		}
		$user = $this->Users->get($user_id);
		if($user->g_verify != 'N'){
			//$this->Flash->error('이미 등록된 OTP가 있습니다.');
			//return $this->redirect(['action' => 'login']);
		}
		if (empty($user->g_secret)) {
            $getSecret = $this->Users->createSecret();
            $user->g_secret = $getSecret;
            $this->Users->save($user);
        }

		$secret = $user->g_secret;
		$googleAuthUrl = $this->Users->getQRCodeGoogleUrl('CoinIBT', $secret);
		$googleAuthEnable = $user->g_auth_enable;
        $googleVerify = $user->g_verify;
		$this->set('secret',$secret);
		$this->set('googleAuthUrl',$googleAuthUrl);
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

	public function confirm_ip(){
		$this->loadModel("AdminAccessIp");
		$this_ip = $this->get_client_ip();
		$ip_list = $this->AdminAccessIp->find()->select(['access_ip'])->where(['status'=>0])->all();
		$ip_check = 'fail';
		foreach($ip_list as $l){
			if($l->access_ip == $this_ip){
				$ip_check = 'success';
				break;
			}
		}
		return $ip_check;
	}


    public function changePassword(){
        $this->set('title',' Change password');
        $users  = $this->Users->get($this->Auth->user('id'));

        if($this->request->is(['post','put'])){
			$result = $this->validationPassword($users['id'], 'include_old_password', $this->request->data['old_password'], $this->request->data['new_password'], $this->request->data['confirm_password']);
			if($result['status'] != 'success'){
				$this->Flash->error($result['message']);
				return;
			}

            $users = $this->Users->patchEntity($users, [
                'old_password'  => $this->request->data['old_password'],
                'password'      => $this->request->data['new_password'],
                'new_password'     => $this->request->data['new_password'],
                'confirm_password'     => $this->request->data['confirm_password']
            ],
                ['validate' => 'password']
            );

            if($this->Users->save($users)){
                if(SENDMAIL == 1)
                {
                    $user['msg'] = 'Your password has been changed successfully.';
                    $email = new Email('default');
                    $email->viewVars(['data'=>$users]);
                    $email->from([$this->setting['email_from']] )
                        ->to($users['email'])
                        ->subject('Password reset')
                        ->emailFormat('html')
                        ->template('reset_password')
                        ->send();
                }
				$this->add_system_log(200, $users['id'], 3, '관리자 비밀번호 변경');
                $this->Flash->success('Your password has been updated.');
                $this->redirect(['controller'=>'users','action'=>'changePassword']);
            }else{
				$this->add_system_log(300, $users['id'], 3, '관리자 비밀번호 변경 오류');
                $this->Flash->error('Some Errors Occurred.');
            }
        }
        $this->set('users',$users);
    }
	
    public function logout()
    {
		if (isset($_COOKIE['app_session_token'])) {
			unset($_COOKIE['app_session_token']);
			setcookie('app_session_token', '', time() - 3600, '/');
		}
		$this->add_system_log(200, $this->Auth->user('id'), 0, '관리자 로그아웃');
        return $this->redirect($this->Auth->logout());
    }
	
	public function deleteProgram()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Users');
			$query = $this->Users->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			$this->add_system_log(300, $this->request->data['id'], 4, '유저 삭제');
			echo 1;
		}
		die; 
	}
	/* 인증 변경 요청 처리 통일화 필요.... */
	public function confirmChangeAuth(){
		if ($this->request->is('ajax')) {
			$updateResult = $this->removeAuthType($this->request->data);
			echo json_encode($updateResult); die;
        }
	}
	/* 업데이트 */
	private function removeAuthType($data = array()){
		$this->loadModel('Users');
        $this->loadModel('ChangeAuth');
		$type = $data['type'];
		$userId = $data['userId'];
		$changeAuthId = $data['id'];
		$returnArr = ['success'=>'false','message'=>'알 수 없는 오류가 발생했습니다. 잠시 후 다시 시도해주세요'];
		if(empty($type) || empty($userId) || empty($changeAuthId)){
			$returnArr = ['success'=>'false','message'=>'필수값이 누락 되었습니다.'];
			return $returnArr;
		}
		$user = $this->Users->find()->select(['email_auth','g_verify','bank_verify','id_document_status','scan_copy_status','user_level'])->where(['id'=>$userId])->hydrate(false)->first();
		$updateArr = [];
		$updateArr['user_level'] = 1;
		$updateArr['modified'] = date('Y-m-d h:i:s');
		if($type == 'email'){
			$updateArr['email_auth'] = 'N';
			$updateArr['email'] = '';
		} else if ($type == 'bank'){
			$updateArr['bank_verify'] = 'N';
			$updateArr['bank'] = '';
			$updateArr['account_number'] = '';
		} else if ($type == 'otp'){
			$updateArr['g_verify'] = 'N';
			$updateArr['g_secret'] = '';
		}
		if($user['id_document_status'] == 'A'){
			$updateArr['user_level'] = 3;
		}

		$query = $this->Users->query();
		$query->update()->set($updateArr)->where(['id'=>$userId])->execute();
		$this->add_system_log(200, $userId, 3, $type.' 인증 초기화 및 인증 단계 변경 (change_auth id :: '.$changeAuthId.')');

		$change = $this->ChangeAuth->get($changeAuthId);
        $changeAuth = $this->ChangeAuth->patchEntity($change,['status' => 'Completed','updated'=>date('Y-m-d h:i:s')]);
        if($this->ChangeAuth->save($changeAuth)){
			$returnArr = ['success'=>'true','message'=>'인증 초기화가 완료 되었습니다.'];
		}
		return $returnArr;
	}

    public function addadmin()
	{
		$this->loadModel("Levels");
		$levelList = $this->Levels->find('list', ['keyField' => 'id',
													 'valueField' => 'level_name'
												])->toArray();
		$this->set('levelList',$levelList);										
		$user  = $this->Users->newEntity();
		
		
		if ($this->request->is(['post','put'])) {
			
			$this->request->data['username'] = filter_var($this->request->data['username'], FILTER_SANITIZE_STRING);
			$username = $this->request->data['username'];
			$level_id = $this->request->data['level_id'];
			$password = strip_tags($this->request->data['password']);
			$confirm_password = strip_tags($this->request->data['confirm_password']);
			$email = $this->request->data['email'];
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->Flash->error(__('Enter a Valid Email'));
				return $this->redirect('tech/users/addadmin');
			}

			$validation_check = $this->validationPassword(0, 'exclude_old_password', '', $password, $confirm_password);
			if($validation_check['status'] != 'success'){
				$this->Flash->error($validation_check['message']);
				return;
			}
			
			if(preg_match('/[^a-z_\-0-9]/i', $username)) { // for english chars + numbers only
				// valid username, alphanumeric & longer than or equals 5 chars
				$this->Flash->error(__('Username should be alphanumeric'));
				return $this->redirect('tech/users/addadmin');
			}
			
			
			$this->request->data['unique_id']= $this->getUniqueId();
            $this->request->data['user_type'] = 'A';
            $this->request->data['enabled'] = 'Y';
            $this->request->data['level_id'] = $level_id;
            $this->request->data['email'] = strip_tags($email);
			$this->request->data['username'] = $username;
			$this->request->data['password'] = $password;
			$this->request->data['created'] = date('Y-m-d H:i:s');
			$this->request->data['last_pw_change_date'] = date('Y-m-d H:i:s');
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
                    $this->Flash->error(__(implode(" AND ", $error_msg)));
					$this->add_system_log(300, 0, 2, '관리자 생성 실패 오류 ('.$error_msg.')');
                }
				return $this->redirect('tech/users/addadmin');
			}

			if ($usrDetail = $this->Users->save($user)) {	
				$this->add_system_log(200, $user['id'], 2, '관리자 생성');
				$this->Flash->success(__('Admin Added Successfully'));
				return $this->redirect('tech/reports/adminlist');
				
			}
			else {
				$this->add_system_log(300, 0, 2, '관리자 생성 실패 오류');
				$this->Flash->success(__('Unable to add Admin ! Try Again'));
				return $this->redirect('tech/users/addadmin');
			}
			
		}
		$this->set('user',$user);
		
		
		
	}

    //users auth req start

    public function userauthreq()
    {
        $this->loadModel('ChangeAuth');
        $this->loadModel('Users');
        $authUserId = $this->Auth->user('id');
        $limit = 20;

        $searchData = [];
        $search = $this->request->query;

        if (!empty($search['ChangeAuth.id'])) $searchData['AND'][] = array('ChangeAuth.id' => $search['user_id']);
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user_id' => $search['user_name']);

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] = array('DATE(ChangeAuth.created) >= ' => $this->request->query['start_date'],'DATE(ChangeAuth.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(ChangeAuth.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(ChangeAuth.created)' => $search['end_date']);

        if($this->request->query('export')){
			$this->add_system_log(200, 0, 5, '고객 정보 전체 리스트 CSV 다운로드 (이름, 전화번호, 이메일, 계좌 등)');

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User Id','User Name','Phone number','Bank Name','Account Number','Requested','Status','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->ChangeAuth->find('all',[
                'conditions'=>$searchData,
                'order'=>['ChangeAuth.id'=>'desc'],
            ]);

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user_name']), "EUC-KR", "UTF-8" );
                $arr['Phone number'] = $data['user_phone_number'];
                $arr['Email'] = $data['user_email'];
                $arr['Bank Name'] = $data['user_bank_name'];
                $arr['Account Number'] = $this->Decrypt($data['user_account_number']);
                $arr['Requested'] = $data['request'];
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'UsersRequested'.$filename
            ));
            return $this->response;die;
        }

        $collectdata = $this->Paginator->paginate($this->ChangeAuth, [
            'conditions'=>$searchData,
            'order'=>['ChangeAuth.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);

    }

    public function userauthreqpagination()
    {

        $this->loadModel('ChangeAuth');
        $this->loadModel('Users');

        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            //$searchData['AND'][] = array('conditions'=> ['PrincipalWallet.type'=>'bank_initial_withdraw']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(ChangeAuth.created) >= ' =>
                    $this->request->data['start_date'],'DATE(ChangeAuth.created) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(ChangeAuth.created)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(ChangeAuth.created)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->ChangeAuth, [
                'conditions'=>$searchData,
                'order'=>['ChangeAuth.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function userauthreqlistajax(){
        $this->loadModel('Users');
        $this->loadModel('ChangeAuth');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->ChangeAuth->find("all",['conditions'=>['ChangeAuth.id'=>$id],
                'order'=>['ChangeAuth.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function userauthreqlistajaxname(){
        $this->loadModel('Users');
        $this->loadModel('ChangeAuth');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->ChangeAuth->find("all",['conditions'=>['ChangeAuth.user_id'=>$userId],'order'=>['ChangeAuth.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user_name'];
                $phone = $getUser['user_phone_number'];
                $email = $getUser['user_email'];
                $bank = $getUser['user_bank_name'];
                $accountNum = $getUser['user_account_number'];
                $request = $getUser['request'];
                $status = $getUser['status'];
                $created = $getUser['created'];
                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'email' => $email,
                    'bank'=>$bank,
                    'accountNum'=>$accountNum,
                    'request' => $request,
                    'status' => $status,
                    'created'=>$created
                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;
        }
    }

    //users auth req end
	
	public function editadmin($id=null)
	{
		if($id==null){
			$this->redirect(['controller'=>'reports','action'=>'adminlist']);
		}
		
		$this->loadModel("Levels");
		$levelList = $this->Levels->find('list', ['keyField' => 'id',
													 'valueField' => 'level_name'
												])->toArray();
		$this->set('levelList',$levelList);	
		$user  = $this->Users->get($id);
		$currentUserName = $user->username;
		$currentUserEmail = $user->email;
		$this->set('title',$currentUserName.' Edit');
		$before_image = $user->image;
		$this->set('user',$user);	
		if ($this->request->is(['post','put'])) {
			
			$findExist = $this->Users->find('all',array('conditions'=>array('OR'=>array('email'=>$currentUserEmail,'username'=>$currentUserName),
																			'id !='=>$id)))->first();
																			
			if(empty($findExist)) {
				if(isset($this->request->data['new_password'])){
					$validation_check = ['status'=>''];
					if($this->Auth->user('id') == 1){
						$validation_check = $this->validationPassword(0, 'exclude_old_password', '', $this->request->data['new_password'], $this->request->data['confirm_password']);
					} else if ($this->Auth->user('id') == $id){
						$validation_check = $this->validationPassword($id, 'include_old_password', $this->request->data['old_password'], $this->request->data['new_password'], $this->request->data['confirm_password']);
					}
					if($validation_check['status'] != 'success'){
						$this->Flash->error($validation_check['message']);
						return;
					}
					$users = $this->Users->patchEntity($user, [
					//'old_password'  => $this->request->data['old_password'],
					'password'      => $this->request->data['new_password'],
					'new_password'     => $this->request->data['new_password'],
					'confirm_password'     => $this->request->data['confirm_password']
					],
					['validate' => 'password']
					);
						
					if($user=$this->Users->save($users)){
						if(SENDMAIL == 1)
						{

							$user['msg'] = 'Your password has been changed successfully.';
							$email = new Email('default');
							$email->viewVars(['data'=>$user]);
							$email->from([$this->setting['email_from']] )
								->to($user['email'])
								->subject('Password reset')
								->emailFormat('html')
								->template('reset_password')
								->send();
						}
						$this->add_system_log(200, $id, 3, '관리자 비밀번호 변경');
						$this->Flash->success('Admin password has been updated.');
						return $this->redirect(['action' => 'editadmin',$id]);
					}else{
						$this->add_system_log(300, $id, 3, '관리자 비밀번호 변경 오류');
						$this->Flash->error('Some Errors Occurred.');
						return $this->redirect(['action' => 'editadmin',$id]);
					}
				
				}else{ 
					$user = $this->Users->patchEntity($user, $this->request->data);
					if ($this->Users->save($user)) {
						$user = $this->Users->get($id);
						
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
						
						/* $userExist = $this->Users->find('all',array('conditions'=>array('id'=>$id)))->first();
						$this->Auth->setUser($userExist->toArray()); */
						$this->add_system_log(200, $id, 3, '관리자 정보 수정');
						$this->Flash->success(__('Admin has been updated.'));
						return $this->redirect(['action' => 'editadmin',$id]);
						
					}
				
				
				}
			}
			else {
				$this->add_system_log(300, $id, 3, '관리자 정보 수정 오류 (User Already Exist with same email, username OR password)');
				$this->Flash->error('User Already Exist with same email, username OR password');
			}
		}
	}	
	public function profile($id=null)
	{
		if($id==null){
			$this->redirect(['controller'=>'reports','action'=>'users']);
		}
		
		$user  = $this->Users->get($id);
		$currentUserName = $user->username;
		$currentUserEmail = $user->email;
		$this->set('title',$currentUserName.' profile');
		$before_image = $user->image;
		if ($this->request->is(['post','put'])) {
			
			$findExist = $this->Users->find('all',array('conditions'=>array('OR'=>array('email'=>$currentUserEmail,'username'=>$currentUserName),
																			'id !='=>$id)))->first();
																			
			if(empty($findExist)) {
				if(isset($this->request->data['new_password'])){
					$users = $this->Users->patchEntity($user, [
					//'old_password'  => $this->request->data['old_password'],
					'password'      => $this->request->data['new_password'],
					'new_password'     => $this->request->data['new_password'],
					'confirm_password'     => $this->request->data['confirm_password']
					],
					['validate' => 'password']
					);
						
					if($user=$this->Users->save($users)){
						if(SENDMAIL == 1)
						{

							$user['msg'] = 'Your password has been changed successfully.';
							$email = new Email('default');
							$email->viewVars(['data'=>$user]);
							$email->from([$this->setting['email_from']] )
								->to($user['email'])
								->subject('Password reset')
								->emailFormat('html')
								->template('reset_password')
								->send();
						}
						$this->add_system_log(200, $id, 3, '관리자 비밀번호 변경');
						$this->Flash->success('Your password has been updated.');
						return $this->redirect(['action' => 'profile',$id]);
					}else{
						$this->add_system_log(300, $id, 3, '관리자 비밀번호 변경 오류');
						$this->Flash->error('Some Errors Occurred.');
						return $this->redirect(['action' => 'profile',$id]);
					}
				
				}else{ 
					$user = $this->Users->patchEntity($user, $this->request->data);
					if ($this->Users->save($user)) {
						$user = $this->Users->get($id);
						
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
						$this->add_system_log(200, $id, 3, '관리자 정보 수정');
						
						/* $userExist = $this->Users->find('all',array('conditions'=>array('id'=>$id)))->first();
						$this->Auth->setUser($userExist->toArray()); */
						
						$this->Flash->success(__('User has been updated.'));
						return $this->redirect(['action' => 'profile',$id]);
						
					}
				
				
				}
			}
			else {
				$this->add_system_log(300, $id, 3, '관리자 정보 수정 오류 (User Already Exist with same email, username OR password)');
				$this->Flash->error('User Already Exist with same email, username OR password');
			}
		}
		$this->set('user',$user);	
		
		
	}
	
	
	public function status(){
		if ($this->request->is('ajax')) { 
			$user = $this->Users->get($this->request->data['id']); // Return article with id 12
			$user->enabled = $this->request->data['status'];
			$user->unique_id = $user->unique_id."clo";
			$this->Users->save($user);
			$this->add_system_log(200, $this->request->data['id'], 3, '유저 enabled, unique_id 수정');
			echo 1;
		}
		die;
		
		
	}
	
	public function addlevel()
    	{
    		$this->loadModel("Levels");
    		$this->loadModel("LevelPages");
    		$user = $this->Auth->user();
    		$levelObj  = $this->Levels->newEntity();

    		if ($this->request->is(['post','put'])) {
    			$level_name = filter_var($this->request->data['level_name'], FILTER_SANITIZE_STRING);
    			if(empty($level_name)){
    				$this->Flash->success(__('Level Name is required'));
    				return $this->redirect(["controller"=>"Users","action"=>"addlevel"]);
    			}

    			if(!isset($this->request->data['pages'])){
    				$this->Flash->success(__('Select at least one page'));
    				return $this->redirect(["controller"=>"Users","action"=>"addlevel"]);
    			}

    			$levelObj = $this->Levels->patchEntity($levelObj,["level_name"=>$level_name]);

    			if ($saveLevel = $this->Levels->save($levelObj)) {
					$this->add_system_log(200, 0, 2, '관리자 레벨 추가');
    				$levelId = $saveLevel->id;
    				$pagesList = $this->request->data['pages'];
    				$updatePagesArr = [];
    				foreach($pagesList as $pageId){
    					array_push($updatePagesArr,$pageId);
    				}
    				if(!empty($updatePagesArr)){
    					$updateQuery = $this->LevelPages->query();
    					$updateQuery->update()->set(['level_id'=>$levelId,'updated'=>date('Y-m-d H:i:s'),'last_id'=>$user['id']])->where(['id IN' => $updatePagesArr])->execute();
						$this->add_system_log(200, 0, 3, '관리자 레벨 추가로 관리자 메뉴의 level_id 수정');
    				}
    				$this->Flash->success(__('Level Added Successfully'));
    			} else {
					$this->add_system_log(200, 0, 2, '관리자 레벨 추가 불가 오류');
    				$this->Flash->success(__('Unable to add Level ! Try Again'));
    				return $this->redirect(["controller"=>"Users","action"=>"addlevel"]);
    			}
    		}

    		$list = $this->LevelPages->find()->select(['id','menu_name','level_id'])->where(['status'=>'Y'])->order(['sort_no'=>'asc'])->all();
    		$query = $this->Levels->find()->select(['id'])->order(['id'=>'desc'])->first();
    		$start_idx= $query->id+1;
    		$this->set('levelObj',$levelObj);
    		$this->set('list',$list);
    		$this->set('start_idx',$start_idx);
    	}


    	public function editlevel($id)
    	{
    		$this->loadModel("Levels");
    		$this->loadModel("LevelPages");
    		$user = $this->Auth->user();

    		$levelObj  = $this->Levels->find('all',['conditions'=>['id'=>$id],"contain"=>["levelpages"]])->first();

    		if ($this->request->is(['post','put'])) {

    			$levelName = filter_var($this->request->data['level_name'], FILTER_SANITIZE_STRING);
    			if(empty($levelName)){
    				$this->Flash->success(__('Level Name is required'));
    				return $this->redirect(["controller"=>"Users","action"=>"addlevel"]);
    			}

    			if(!isset($this->request->data['pages'])){
    				$this->Flash->success(__('Select at least one page'));
    				return $this->redirect(["controller"=>"Users","action"=>"addlevel"]);
    			}

    			$pagesList = $this->request->data['pages'];
    			$updatePagesArr = [];
    			foreach($pagesList as $pageId){
    				array_push($updatePagesArr,$pageId);
    			}

    			$originPagesArr = [];
    			$origin_list = $this->LevelPages->find()->select(['id'])->where(['level_id'=>$id])->all();
    			foreach($origin_list as $l){
    				array_push($originPagesArr,$l->id);
    			}

    			$diffArr = array_diff($originPagesArr,$updatePagesArr);

    			if(!empty($diffArr)){ // checked false update
    				$updateQuery1 = $this->LevelPages->query();
    				$updateQuery1->update()->set(['level_id'=>$id-1,'updated'=>date('Y-m-d H:i:s'),'last_id'=>$user['id']])->where(['id IN' => $diffArr])->execute();
    			}

    			if(!empty($updatePagesArr)){ // checked true update
    				$updateQuery2 = $this->LevelPages->query();
    				$updateQuery2->update()->set(['level_id'=>$id,'updated'=>date('Y-m-d H:i:s'),'last_id'=>$user['id']])->where(['id IN' => $updatePagesArr])->execute();
    			}
				$this->add_system_log(200, 0, 3, '관리자 레벨 수정');
    			$this->Flash->success(__('Level Updated Successfully'));
    			return $this->redirect(["controller"=>"Users","action"=>"level"]);
    		}

    		$list = $this->LevelPages->find()->select(['id','menu_name','level_id'])->where(['status'=>'Y'])->order(['sort_no'=>'asc'])->all();
    		$this->set('levelObj',$levelObj);
    		$this->set('list',$list);

    	}

    	public function level() {
            $this->set('title' , 'level');
    		$this->loadModel('Levels');
    		$query = $this->Levels->find()->select(['id','level_name','status','created']);
    		$listing = $query->all();
    		$this->set('listing',$listing);
    	}

    	public function getlevelpages(){
    		$this->loadModel('LevelPages');
    			if($this->request->is('ajax')) {
    				if (!empty($this->request->data['id'])) {
    					$id = $this->request->data['id'];
    					$list = $this->LevelPages->find()->select(['menu_name','url'])->where(['level_id >='=>$id,'status'=>'Y'])->order(['sort_no'=>'asc'])->all();
    					$status = "true";
    				} else {
    					$status = "false";
    					$list = '';
    				}
    			$returnArr = ['status' => $status, 'data' => $list];
    			echo json_encode($returnArr);
    			die;
    		}
    	}

	public function userdetails(){
        $this->set('title' , 'Users');
        $this->loadModel('Users');
        $searchData = array();
        $limit =  10;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }else $this->set('serial_num',1);

        $searchData['AND'][] = ['Users.user_type'=>'U'];
        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if(!empty($search['pagination'])) $limit =  $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_name']);
        }
        if (!empty($search['user_email'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_email']);
        }
		if (!empty($search['user_level'])) {
            $searchData['AND'][] = array('Users.user_level' => $search['user_level']);
        }

        if(!empty($search['start_date']) && !empty($search['end_date'])) $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->query['start_date'],'DATE(Users.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);

        if(!empty($search['export']) && $search['export'] !=''){
            // Export
            if($search['export']=='c') $filename = time().'export.csv';
            else  $filename = 'export.xlsx';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','Name','Username','Membership','Category','User Level','Auth Level 1','Email','Phone number','OTP','Bank Name','Status','Date Of Registration');
            fputcsv($file,$headers);
            $users =  $this->Users->find('all',[
                // 'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled'],
                //'contain'=>['referusers','agctransactions','referral_user'],
                'conditions' => $searchData,
                // 'limit' => $limit,
                'order'=>['Users.id'=>'desc']
            ]);
			$this->add_system_log(200, 0, 5, '고객 정보 전체 리스트 CSV 다운로드 (이름, 전화번호, 이메일 등)');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $userStatus = ($data['enabled'] == 'Y') ? "Active" : "Deactive" ;

                $arr = [];
                $arr['#'] = $k;
                $arr['Username'] = $data['username'];
                $arr['Name'] = $data['name'];
                $arr['Email'] = $data['email'];
                $arr['User Level'] = $data['user_level'];
                $arr['Annual Membership'] = $data['annual_membership'];
                $arr['Category'] = $data['category'];
                $arr['Phone number'] = $data['phone_number'];
                $arr['Status'] = $userStatus;
                $arr['Date Of Registration'] = date('Y-m-d',strtotime($data['created']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'UserReport'.time().$filename
            ));
            return $this->response;die;
        }
		try {
			$getUsers = $this->Paginator->paginate($this->Users, [
				'fields'=>['id','username','name','phone_number','email','user_level','annual_membership','category','g_verify','bank','account_number','id_type','id_number','id_document_front','id_document_back','id_document_status','scan_copy','scan_copy_status','id_document_reject_reason','scan_copy_reject_reason'],
//				'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']
			]);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$getUsers = $this->Paginator->paginate($this->Users, [
				'fields'=>['id','username','name','phone_number','email','user_level','annual_membership','category','g_verify','bank','account_number','id_type','id_number','id_document_front','id_document_back','id_document_status','scan_copy','scan_copy_status','id_document_reject_reason','scan_copy_reject_reason'],
//				'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']
			]);
		}
        $this->set('users',$getUsers);
    }

    public function userdetailsearch()
    {
        if ($this->request->is('ajax')) {
            $searchData = array();
            $limit =  $this->setting['pagination'];
            //$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
            $searchData['AND'][] =['Users.user_type'=>'U'];

            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
                if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
                if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
                if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
                if($search['user_level'] != '') $searchData['AND'][] =array('Users.user_level' => $search['user_level']);
                if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
            }
            else {$this->set('serial_num',1);}
            $this->set('users', $this->Paginator->paginate($this->Users, [
                'fields'=>['id','username','name','phone_number','email','annual_membership','user_level','category','g_verify','bank','account_number','id_type','id_number','id_document_front','id_document_back','id_document_status','scan_copy','scan_copy_status','id_document_reject_reason','scan_copy_reject_reason'],
                'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
                'conditions' => $searchData,
                'limit' => $limit,
                'order'=>['id'=>'desc']

            ]));

        }
    }

    public function usersStatusUpdate(){
        if ($this->request->is(['post' ,'put'])){
            $id = $this->request->data['status_id'];
            $status = $this->request->data['status'];
            $type = $this->request->data['status_type'];
            $rejectReason =  $this->request->data['reject_reason'];

            $updateArr = [];
            $updateArr[$type.'_status'] = $status;
            $updateArr[$type.'_reject_reason'] = $rejectReason;
            echo $status;
            if($status == "A"){
                $updateArr['user_level'] = 3;
            }

            $getData = $this->Users->get($id);
            $getData = $this->Users->patchEntity($getData,$updateArr);
            $getData = $this->Users->save($getData);
			$this->add_system_log(200, $id, 3, '고객 상태 수정');
            $this->Flash->success(__('Status updated.'));
            return $this->redirect(['action' => 'userdetails']);
        }

        die;
    }

    public function updateMembership(){
        $this->loadModel('Users');
        if ($this->request->is('ajax')) {
            $user = $this->Users->get($this->request->data['id']);
            $membership = $this->request->data['annual_membership'];
            if (in_array($membership, ["Y", "N"])) {
                $user->annual_membership = $membership;
                if ($this->Users->save($user)) {
					$this->add_system_log(200, $user['id'], 3, '고객 연간회원 상태 수정');
                    echo "success";
                } else {
					$this->add_system_log(400, $user['id'], 3, '고객 연간회원 상태 수정 오류');
                    echo "error";
                }
            } else {
                echo "else";
            }
        }
		die;
    }
	
	
	public function mywallet(){
		
		$this->loadModel('Transactions');
		$this->loadModel('PrincipalWallet');
		$this->loadModel('Cryptocoin');
		$this->loadModel('Users');
		$authUserId = $this->Auth->user('id');
		$intrAddress  = $this->Auth->user('intr_address');
		$this->set('intrAddress',$intrAddress);
		$limit = 20;
		$this->set('currentUserId',$authUserId);
		
		$getUserTotalCoin = $this->Transactions->find(); 
		$getUserTotalCoinCnt = $getUserTotalCoin
									->select(['sum' => $getUserTotalCoin->func()->sum('Transactions.coin_amount'),'Transactions.cryptocoin_id','cryptocoin.short_name','cryptocoin.icon'])
									->contain('cryptocoin')
									->where(['Transactions.user_id'=>$authUserId,'Transactions.status'=>'completed','Transactions.tx_type !='=>'bank_initial_deposit'])
									->group('cryptocoin_id')
									->toArray();

		
		$this->set('getUserTotalCoinCnt',$getUserTotalCoinCnt);
		
		
		$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();
		$this->set('getCoinList',$getCoinList);
		
        if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
        $search = $this->request->data;
        if ($this->request->is(['post' ,'put']) ) {
            if (!empty($search['pagination'])) $limit = $search['pagination'];
        }
        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.status'=>'completed',
                    'OR' =>[['PrincipalWallet.type' => 'transfer_to_trading_account'],['PrincipalWallet.type' => 'transfer_from_trading_account']]],
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);
		
	
        $this->set('listing',$collectdata);
		
	}
	
	public function mywalletajax($userId){

		$this->loadModel('Cryptocoin');
		$mainRespArr = [];
		$mainBalance = 0;
		$tradeBalance = 0;
		$resrvBalance = 0;
		$getCoinList = $this->Cryptocoin->find('all',['conditions'=>['status'=>1],'order'=>['serial_no'=>'asc']])->hydrate(false)->toArray();
		foreach($getCoinList as $getCoin){
				$coinId = $getCoin['id'];
				$coinName = $getCoin['name'];
				$coinShortName = $getCoin['short_name'];
				$principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId);
				$tradingBalance = $this->Users->getLocalUserBalance($userId,$coinId);
				//$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId);
				$reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId);
				$reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId);
				$reserveBalance = $reserveBuyBalance + $reserveSellBalance;
				if($coinShortName == "BTC"){
                    $mainBalance = number_format((float)$principalBalance,6);
                    $tradeBalance = number_format((float)$tradingBalance,6);
                    $resrvBalance = number_format((float)$reserveBalance,6);
                } else if($coinShortName == "KRW" || $coinShortName == "MC" || $coinShortName == "CTC" || $coinShortName == "TP3"){
                    $mainBalance = number_format((float)$principalBalance,2);
                    $tradeBalance = number_format((float)$tradingBalance,2);
                    $resrvBalance = number_format((float)$reserveBalance,2);
                } else {
                    $mainBalance = number_format((float)$principalBalance,4);
                    $tradeBalance = number_format((float)$tradingBalance,4);
                    $resrvBalance = number_format((float)$reserveBalance,4);
                }
				$singleArr = ['principalBalance'=>$mainBalance,
							  'tradingBalance'=>$tradeBalance,
							  'reserveBalance'=>$resrvBalance,
							  'coinId'=>$coinId,
							  'coinName'=>$coinName,
							  'coinShortName'=>$coinShortName							  
				];
				$mainRespArr[]=$singleArr;
				
		}
		$respArr=['status'=>'false','message'=>"coin list",'data'=>['coinlist'=>$mainRespArr]];
		
		echo json_encode($respArr); die;
	}
	 
	 
	 
	public function transferToAccount(){
		$this->loadModel('Transactions');
		$this->loadModel('Cryptocoin');
		$this->loadModel('PrincipalWallet');
		$this->loadModel('Users');
		$this->loadModel('NumberThreeSetting');
		$this->loadModel('TransferLimits');
		$this->loadModel('NumberFourSetting');
		if ($this->request->is('ajax')){
			$authUserId = $this->request->data['user_id'];
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
			$btcWalletAddr = $userDetails['btc_address'];
			$ethWalletAddr = $userDetails['eth_address'];
			
			$userWalletAddr = ($coinId==1) ? $btcWalletAddr : $ethWalletAddr;
			
			
			
			if(empty($amount) || empty($transferTo) || empty($coinId)){
				$respArr=['status'=>'false','message'=>"Amount, transfer type and coin is required"];
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
			
			
			// deduct balance
			if($transferTo=="trading"){
				$adminFee = 0;
				// get transfer fee
			
				$adminFeeAmt = $amount*$adminFee/100;
				$getMainBalance = $this->Users->getUserPricipalBalance($authUserId,$coinId);
				
				if($getMainBalance<$amount){
					$respArr=['status'=>'false','message'=>"insufficient balance",'data'=>["balance"=>$getMainBalance]];
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
				$this->add_system_log(200, $authUserId, 1, 'transfer_to_trading_account');
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
					$this->add_system_log(200, $authUserId, 1, 'transfer_from_main_account');
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
						$this->add_system_log(200, 1, 1, 'adminTranferFees');
						
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
			else if($transferTo=="main"){
				$adminFee = 0;
				// get transfer fee
			
				$adminFeeAmt = $amount*$adminFee/100;
				
				$geTradingtBalance = $this->Users->getLocalUserBalance($authUserId,$coinId);
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
								   'user_id'=>$authUserId,
								   'wallet_address'=>$userWalletAddr,
								   'cryptocoin_id'=>$coinId];
				$newObj = $this->Transactions->newEntity();
				$newObj = $this->Transactions->patchEntity($newObj,$deductBalanceArr);
				$saveThisData = $this->Transactions->save($newObj);
				$this->add_system_log(200, $authUserId, 1, 'transfer_to_main_account');

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
					$this->add_system_log(200, $authUserId, 1, 'transfer_from_trading_account');

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
						$this->add_system_log(200, 1, 1, 'adminTranferFees');
						
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

		}
		else {
			$respArr=['status'=>'false','message'=>"Invalid Request"];
			echo json_encode($respArr); die;
		}
	}

    public function transferHistory(){
        if ($this->request->is('ajax')) {
            $limit = $this->setting['pagination'];
            $this->loadModel('Users');
            $this->loadModel('PrincipalWallet');

            $transferHistoryList = $this->PrincipalWallet->find()
                ->contain(['user'])
                ->where(['status'=>'completed',
                    'OR' =>[['type' => 'transfer_to_trading_account'],['type' => 'transfer_from_trading_account']],])
                ->order(['PrincipalWallet.id'=>'desc'])
                ->limit(20)
                ->hydrate(false)->toArray();

            /* if(empty($transferHistoryList)){
            } else{
            } */
            $this->set('listing',$transferHistoryList);
            echo json_encode($transferHistoryList); die;
        }
    }

    public function mywalletPagination()
    {
		
        $this->loadModel('Transactions');
        $this->loadModel('PrincipalWallet');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            $searchData['AND'][] = array('OR'=> ["PrincipalWallet.type"=>'transfer_to_trading_account'],["PrincipalWallet.type"=>'transfer_from_trading_account']);
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.status'=>'completed',
                    'OR' =>[['PrincipalWallet.type' => 'transfer_to_trading_account'],['PrincipalWallet.type' => 'transfer_from_trading_account']]],
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);
			

            $this->set('listing',$collectdata);

        }
		
    }

    //PrincipalWallet Details Start
    public function principalwalletdetails()
    {
       $start_date = date('Y-m-d', strtotime('-1 month'));
		$end_date = date('Y-m-d');
		if(empty($this->request->query['start_date']) && empty($this->request->query['end_date'])){
			return $this->redirect(['action'=>'principalwalletdetails','start_date'=>$start_date,'end_date'=>$end_date]);
		}
		$this->request->session()->write('principalwalletdetails_export', 'fail');

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $authUserId = $this->Auth->user('id');
        $limit = 20;

        $searchData = array();
        if($this->request->query('page')) {

            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);
		if (!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '' && empty($search['export_date'])) { 
			$searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
		}


        if($this->request->query('export')){
            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Wallet Address','Coin','Amount','Coin Amount','Type','Transaction ID','Fees','Remark','Status','Date & Time');
            fputcsv($file,$headers);
			if(!empty($search['export_date'])){
				$searchData['AND'][] = array("DATE_FORMAT(PrincipalWallet.created_at,'%Y-%m')" =>$search['export_date']);
			}

            $users =  $this->PrincipalWallet->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'eth_address', 'name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                //'limit' => $limit,

            ]);
			$this->add_system_log(200, 0, 5, '고객 지갑 로그 다운로드 - '.$search['start_date'].' ~ '.$search['end_date'].'(이름, 전화번호, 코인 주소 등)');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Wallet Address'] = $data['wallet_address'];
                $arr['Coin'] = $data['cryptocoin']['short_name'];
                $arr['KRW Amount'] = round($data['amount'],0);
                $arr['Coin Amount'] = round($data['coin_amount'],0);
                $arr['Type'] = $data['type'];
                $arr['Remark'] = $data['remark'];
                $arr['Transaction ID'] = $data['tx_id'];
                $arr['fees'] = round($data['fees'],0);
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'UsersPrincipalWalletDetails'.$filename
            ));
			$this->request->session()->write('principalwalletdetails_export', 'success');
            return $this->response;die;
        }
		try {
			$collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
				'contain'=>['user'=>['fields'=>['eth_address','name','phone_number']],
					'cryptocoin'=>['fields'=>['short_name']]],
				'conditions'=>$searchData,
				'order'=>['PrincipalWallet.id'=>'desc'],
				'limit' => $limit,
			]);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
				'contain'=>['user'=>['fields'=>['eth_address','name','phone_number']],
					'cryptocoin'=>['fields'=>['short_name']]],
				'conditions'=>$searchData,
				'order'=>['PrincipalWallet.id'=>'desc'],
				'limit' => $limit,
			]);
		}
		$select_query1 = $this->Transactions->find()->select(['created'])->order(['id'=>'asc'])->first();
		$select_query2 = $this->Transactions->find()->select(['created'])->order(['id'=>'desc'])->first();
		$select_start_date = $select_query1->created;
		$select_end_date = $select_query2->created;
		$this->set('select_start_date',$select_start_date->format('Y-m'));
		$this->set('select_end_date',$select_end_date->format('Y-m'));
        $this->set('listing',$collectdata);

    }

    public function principalwalletdetailspagination()
    {

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            //$searchData['AND'][] = array('conditions'=> ['PrincipalWallet.type'=>'deducted_coupon_krw']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(PrincipalWallet.created) >= ' =>
                    $this->request->data['start_date'],'DATE(PrincipalWallet.created) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
                'contain'=>['user'=>['fields'=>['eth_address','name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function principalwalletdetailsajax(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.id'=>$id],
                'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function principalwalletdetailsajaxname(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.user_id'=>$userId],'order'=>
                ['PrincipalWallet.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $userWalletAddress = $getUser['wallet_address'];
                $coinAmount = $getUser['coin_amount'];
                $coin = $getUser['cryptocoin']['short_name'];
                $amount = $getUser['amount'];
                $type = $getUser['type'];
                $remark = $getUser['remark'];
                $txid = $getUser['tx_id'];
                $fees = $getUser['fees'];
                $status = $getUser['status'];
                $created = $getUser['created_at'];

                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'userWallet'=>isset($userWalletAddress) ? $userWalletAddress : $getUser['user']['eth_address'],
                    'coinAmount'=>isset($coinAmount) ? $coinAmount : 0,
                    'coin'=>$coin,
                    'amount'=>isset($amount) ? $amount : 0,
                    'type'=>$type,
                    'remark'=>$remark,
                    'fees'=>isset($fees) ? $fees : 0,
                    'txId'=>isset($txid) ? $txid : "",
                    'status'=>$status,
                    'created'=>$created,

                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;

        }
    }

    //Principal Wallet Details End


    //Transactions Details Start
    public function transactionsdetails()
    {
		$start_date = date('Y-m-d', strtotime('-1 month'));
		$end_date = date('Y-m-d');
		if(empty($this->request->query['start_date']) && empty($this->request->query['end_date'])){
			return $this->redirect(['action'=>'transactionsdetails','start_date'=>$start_date,'end_date'=>$end_date]);
		}
		$this->request->session()->write('transactionsdetails_export', 'fail');
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $authUserId = $this->Auth->user('id');
        $limit = 20;

        $searchData = [];
        if($this->request->query('page')) {

            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);
        if (!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '' && empty($search['export_date'])) { 
			$searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->query['start_date'],'DATE(Transactions.created) <= ' => $this->request->query['end_date']);
		}

        if($this->request->query('export')){
            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone number','Wallet Address','Transaction ID','Coin','Coin Amount','Type','Remark','Description',
                'Current Balance','Exchange ID','Exchange History ID','Fees','Status','Date & Time Created', 'Date & Time Updated');
            fputcsv($file,$headers);
			if(!empty($search['export_date'])){
				$searchData['AND'][] = array("DATE_FORMAT(Transactions.created,'%Y-%m')" =>$search['export_date']);
			}

            $users =  $this->Transactions->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>$searchData,
                'order'=>['Transactions.id'=>'desc'],
                //'limit' => $limit,

            ]);
			$this->add_system_log(200, 0, 5, '트랜잭션 로그 다운로드 - '.$search['start_date'].' ~ '.$search['end_date'].'(이름, 전화번호, 코인 주소 등)');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User ID'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone number'] = $data['user']['phone_number'];
                $arr['Wallet Address'] = isset($data['wallet_address']) ? $data['wallet_address'] : $data['user']['eth_address'];
                $arr['Transaction ID'] = $data['transaction_id'];
                $arr['Coin'] = $data['cryptocoin']['short_name'];
                $arr['Amount'] = round($data['coin_amount'],0);
                $arr['Type'] = $data['tx_type'];
                $arr['Remark'] = $data['remark'];
                $arr['Description'] = $data['description'];
                $arr['Current Balance'] = $data['current_balance'];
                $arr['Exchange ID'] = $data['exchange_id'];
                $arr['Exchange History ID'] = $data['exchange_history_id'];
                $arr['Fees'] = $data['fees'];
                $arr['Status'] = $data['status'];
                $arr['Date & Time Created'] = date('Y-m-d H:i:s',strtotime($data['created']));
                $arr['Date & Time Updated'] = date('Y-m-d H:i:s',strtotime($data['updated']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'TransactionsDetails'.$filename
            ));
			$this->request->session()->write('transactionsdetails_export', 'success');
            return $this->response;die;
        }
		try {
			$collectdata = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user'=>['fields'=>['name','phone_number']],
					'cryptocoin'=>['fields'=>['short_name']]],
				'conditions'=>$searchData,
				'order'=>['Transactions.id'=>'desc'],
				'limit' => $limit,
			]);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$collectdata = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user'=>['fields'=>['name','phone_number']],
					'cryptocoin'=>['fields'=>['short_name']]],
				'conditions'=>$searchData,
				'order'=>['Transactions.id'=>'desc'],
				'limit' => $limit,
			]);
		}
		$select_query1 = $this->Transactions->find()->select(['created'])->order(['id'=>'asc'])->first();
		$select_query2 = $this->Transactions->find()->select(['created'])->order(['id'=>'desc'])->first();
		$select_start_date = $select_query1->created;
		$select_end_date = $select_query2->created;
		$this->set('select_start_date',$select_start_date->format('Y-m'));
		$this->set('select_end_date',$select_end_date->format('Y-m'));
        $this->set('listing',$collectdata);
    }

	public function downloadcheckajax(){
		if ($this->request->is('ajax')){
			$session = $this->request->session();
			$type = $this->request->data['type'];
			if($type == 'transactionsdetails'){
				$check = $session->read('transactionsdetails_export');
			} else if ($type == 'principalwalletdetails'){
				$check = $session->read('principalwalletdetails_export');
			}
			echo $check;
		}
		die;
	}

    public function transactionsdetailspagination()
    {

        $this->loadModel('Transactions');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            // $searchData['AND'][] = array('conditions'=> ['Transactions.tx_type'=>'bought_coupon']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' =>
                    $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->Transactions, [
                'contain'=>['user'=>['fields'=>['name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>$searchData,
                'order'=>['Transactions.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function transactionsdetailsajax(){
        $this->loadModel('Users');
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->Transactions->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['Transactions.user_id'=>$id],
                'order'=>['Transactions.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }

    public function transactionsdetailsajaxname(){
        $this->loadModel('Users');
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->Transactions->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['Transactions.user_id'=>$userId],
                'order'=>['Transactions.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $walletAddress = $getUser['wallet_address'];
                $trxId = $getUser['transaction_id'];
                $coin = $getUser['cryptocoin']['short_name'];
                $amount = $getUser['coin_amount'];
                $type = $getUser['tx_type'];
                $remark = $getUser['remark'];
                $description = $getUser['description'];
                $currentBal = $getUser['current_balance'];
                $exchId = $getUser['exchange_id'];
                $exchHistId = $getUser['exchange_history_id'];
                $fees = $getUser['fees'];
                $status = $getUser['status'];
                $created = $getUser['created'];
                $updated = $getUser['updated'];
                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'walletAdd'=>isset($walletAddress) ? $walletAddress : $getUser['user']['eth_address'],
                    'trxId'=>isset($trxId) ? $trxId : "",
                    'remark'=>$remark,
                    'description'=>isset($description) ? $description : "",
                    'currentBal'=>isset($currentBal) ? $currentBal : 0,
                    'exchgId'=>isset($exchId) ? $exchId : "",
                    'exchgHistId'=>isset($exchHistId) ? $exchHistId : "",
                    'fees'=>isset($fees) ? $fees : 0,
                    'status'=>$status,
                    'coin'=>$coin,
                    'amount'=>isset($amount) ? $amount : 0,
                    'type'=>$type,
                    'created'=>$created,
                    'updated'=>$updated

                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;

        }
    }
	/* 사용자 - 계좌번호 전체 암호화 */
	public function allaccountnumberupdate(){
		if ($this->request->is('ajax')) {
			$this->loadModel('Users');
			$list = $this->Users->find()->select(['id','account_number'])->where(['account_number is not null','account_number !='=>''])->all();
			foreach($list as $l){
				$id = $l->id;
				$old_account = $l->account_number;
				$new_account = $this->Encrypt($l->account_number);
				$update_query = $this->Users->query();
				$update_query->update()->set(['account_number'=>$new_account])->where(['id'=>$id])->execute();
			}
			$update_list = $this->Users->find()->select(['id','account_number'])->where(['account_number is not null','account_number !='=>''])->all();

			echo json_encode($update_list);
            die;
		}
	}
	/* 사용자 - 인증 변경 요청 내 계좌번호 전체 암호화 */
	public function allaccountnumberupdatereq(){
		if ($this->request->is('ajax')) {
			$this->loadModel('ChangeAuth');
			$list = $this->ChangeAuth->find()->select(['id','user_account_number'])->where(['user_account_number is not null','user_account_number !='=>''])->all();
			foreach($list as $l){
				$id = $l->id;
				$old_account = $l->user_account_number;
				$new_account = $this->Encrypt($l->user_account_number);
				$update_query = $this->ChangeAuth->query();
				$update_query->update()->set(['user_account_number'=>$new_account])->where(['id'=>$id])->execute();
			}
			$update_list = $this->ChangeAuth->find()->select(['id','user_account_number'])->where(['user_account_number is not null','user_account_number !='=>''])->all();

			echo json_encode($update_list);
            die;
		}
	}
}
