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

//use Cake\I18n\I18n;
//Changed index to home
class UsersController extends AppController {

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
			if($action_name == 'selfCertification' || $action_name == 'newChangePassword'){
				$this->viewBuilder()->layout(false);
			}
		}
	}
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);

        $this->Auth->allow(['signup', 'logout', 'frontLogin', 'frontRegister', 'verify', 'forgotPassword', 'forgetid', 'forgetpass', 'smsCodeCheck','successregister','sendsmscode','sendsmscodecheck','checkPhoneUnique','checkEmailUnique','sendsmscodeForgotPass', 'changepass','selfCertification','newChangePassword','undormant','undormantComplete','secondAuth','otpinfo']);
    }
	/* 마이페이지 */
    public function profile() {
        $this->set('kind', 'profile');
        $this->set('title', 'My profile');
        $user = $this->Users->get($this->Auth->user('id'));
        $this->set('user', $user);
    }

	/* 인증 변경 요청 통합 */
	public function newChangeAuth(){
		$this->loadModel('Users');
        $this->loadModel('ChangeAuth');
		$request_value = '';
		$returnArr = ['success'=>'false','message'=>'신청 실패'];
        if($this->request->is('ajax')){
			$userId = $this->Auth->user('id');
			$type = $this->request->data['type'];
			if($type == 'email'){
				$request_value = 'emailAuth_change';
			} else if($type == 'bank'){
				$request_value = 'bankAuth_change';
			} else if($type == 'otp'){
				$request_value = 'otpAuth_change';
			}
			$checkCount = $this->changeAuthCount($request_value);
			if($checkCount > 0){
				$returnArr = ['success'=>'false','message'=>'이미 신청한 내역이 있습니다. 관리자 승인까지 시간이 다소 소요될 수 있습니다.'];
				echo json_encode($returnArr); die;
			}
			$insertResult = $this->insertChangeAuth($request_value);
			if($insertResult != 'success'){
				$returnArr = ['success'=>'false','message'=>'알 수 없는 오류가 발생했습니다1. 잠시 후 다시 시도해주세요.'];
				echo json_encode($returnArr); die;
			}
			$updateResult = $this->updateUserAuth($type);
			if($updateResult != 'success'){
				$returnArr = ['success'=>'false','message'=>'알 수 없는 오류가 발생했습니다2. 잠시 후 다시 시도해주세요.'];
				echo json_encode($returnArr); die;
			}
			$returnArr = ['success'=>'true','message'=>'신청이 완료 되었습니다.'];
		}
		echo json_encode($returnArr); die;
	}
	/* 이미 신청한 요청이 있는지 확인 */
	private function changeAuthCount($request_value){
		$this->loadModel('ChangeAuth');
		$userId = $this->Auth->user('id');
		if(!empty($request_value) && !empty($userId)){
			$count = $this->ChangeAuth->find()->where([['user_id' => $userId,'request'=>$request_value,'status'=>'Pending']])->count();
			return $count; 
		}
		return 1;
	}
	/* 인증 변경 요청 추가 */
	private function insertChangeAuth($request_value){
		$insertArr = [];
		$insertArr['user_id'] = $this->Auth->user('id');
		$insertArr['user_name'] = $this->Auth->user('name');
		$insertArr['user_phone_number'] ='';
		$insertArr['user_email'] = '';
		$insertArr['user_bank_name'] = '';
		$insertArr['user_account_number'] = '';
		$insertArr['request'] = $request_value;

		$changeOTPAuth = $this->ChangeAuth->newEntity();
		$changeOTPAuth = $this->ChangeAuth->patchEntity($changeOTPAuth, $insertArr);
		if($this->ChangeAuth->save($changeOTPAuth)){
			return 'success';
		}
		return 'fail';
	}
	/* 인증 변경 요청으로 인한 유저 업데이트 */
	private function updateUserAuth($type){
		$userId = $this->Auth->user('id');
		$this->loadModel('Users');
		if(empty($type) || ($type != 'email' && $type != 'bank' && $type != 'otp') || empty($userId)){
			return 'fail';
		}
		$updateArr = [];
		$updateArr['modified'] = date('Y-m-d H:i:s');
		if($type == 'email'){
			$updateArr['email_auth'] = 'P';
		} else if($type == 'bank'){
			$updateArr['bank_verify'] = 'P';
		} else if($type == 'otp'){
			$updateArr['g_verify'] = 'P';
		}
		$query = $this->Users->query();
		$query->update()->set($updateArr)->where(['id'=>$userId])->execute();
		return 'success';
	}
	/* 인증 단계 */
    public function idVerification() {
        $this->set('kind', 'verification');
        $this->set('title', 'My profile');
        $this->loadModel('ChangeAuth');
		$this->loadModel('Users');
        $userId = $this->Auth->user('id');
		$new_ipinfo_ip_chk = $this->Users->new_ipinfo_ip_chk(1);
		$this->set('new_ipinfo_ip_chk',$new_ipinfo_ip_chk);

		$banklist = array('Shinhan Bank', 'Nonghyup Bank', 'Kakao Payco', 'Industrial Bank of Korea', 'K Bank');
        $this->set('banklist', $banklist);

		$user = $this->Users->find()
			->select(['id','phone_number','name','email','user_level','g_secret','g_verify','second_verification','g_auth_enable','email_auth','scan_copy_status','id_document_status','bank_verify','bank','account_number'])
			->where(['id'=>$userId])->first();
		if (empty($user->g_secret)) {
            $getSecret = $this->Users->createSecret();
            $user->g_secret = $getSecret;
            $this->Users->save($user);
			$user = $this->Users->find()
			->select(['id','phone_number','name','email','user_level','g_secret','g_verify','second_verification','g_auth_enable','email_auth','scan_copy_status','id_document_status','bank_verify','bank','account_number'])
			->where(['id'=>$userId])->first();
        }
        $googleAuthUrl = $this->Users->getQRCodeGoogleUrl('CoinIBT', $user->g_secret);
        $this->set('googleAuthUrl', $googleAuthUrl);
		$this->set('user', $user);
    }
	/* email 인증 시 메일 발송 */
    public function sendEmailCode() {
        if ($this->request->is('ajax')) {
            $userEmail = $this->request->data['email'];
			$userEmail = filter_var($userEmail, FILTER_SANITIZE_EMAIL);
			$returArr = ['success'=>'false','message'=>''];
			if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
				$returArr = ['success'=>'false','message'=>__('Enter a Valid Email')];
				echo json_encode($returArr);die;
			}
			$checkEmailExist = $this->Users->find()->where(['email'=>$userEmail,'email_auth'=>'Y'])->count();
			if($checkEmailExist > 0){
				$returArr = ['success'=>'false','message'=>__('This email is already registered.')];
				echo json_encode($returArr);die;
			}
            $newCode = rand(111111, 999999);
			$this->request->data['code'] = $newCode;
			$insertResult = $this->insert_email_code($this->request->data);
			if($insertResult['success'] != 'true'){
				echo json_encode($insertResult); die;
			}
			$user_name = $this->Auth->user('name');
            $data['new_code'] = $newCode;
            $email = new Email('default');
            $email->viewVars(['token' => $newCode]);
            $email->from([$this->setting['email_from'] => 'SMBIT'])
                    ->to($userEmail)
                    ->subject('[SMBIT] 이메일 인증 안내 메일입니다.')
                    ->emailFormat('html')
                    ->template('admin_email_code')
                    ->send();
			$returArr = ['success'=>'true','message'=>'메일 전송이 완료되었습니다. 인증번호를 입력해주세요'];
			echo json_encode($returArr);die;
        }
    }
	/* email 코드 DB에 insert */
	private function insert_email_code($data = array()){
		if(empty($data) || empty($data['email']) || empty($data['code'])){
			$returnArr = ["success"=>"false","message"=>'필수값이 누락되었습니다.'];
            return $returnArr;
		}
		$this->loadModel('SmsCode');
		$checkCount = $this->SmsCode->find()->where(['email'=>$data['email'],'TIMESTAMPDIFF(HOUR,created,NOW()) <= '=>2])->count();
		if($checkCount > 10){
			$returnArr = ["success"=>"false","message"=>'지속적인 메일 발송으로 인해 임시 차단되었습니다. 2시간 이후 재이용 가능합니다.'];
            return $returnArr;
		}
		$data['created'] = date('Y-m-d H:i:s');
		$smsCode = $this->SmsCode->newEntity();
		$smsCode = $this->SmsCode->patchEntity($smsCode, $data);
		if($this->SmsCode->save($smsCode)){
			$returnArr = ["success"=>"true","message"=>'']; 
			return $returnArr;
		} 
		$returnArr = ["success"=>"false","message"=>'저장 실패'];
		return $returnArr;
	}
	/* 이메일 인증 확인 */
	public function confirmEmailCode(){
		if ($this->request->is('ajax')) {
			$authcode = $this->request->data['authcode'];
			$email = $this->request->data['email'];
			$returnArr = ["success"=>"false","message"=>'인증번호가 일치하지 않습니다.'];
			if(empty($authcode) || empty($email)){
				$returnArr = ["success"=>"false","message"=>'필수값이 누락되었습니다.'];
				echo json_encode($returnArr); die;
			}
			$this->loadModel('SmsCode');
			$getSmsCode = $this->SmsCode->find()->select(['email','code'])->where(['email'=>$email,'TIMESTAMPDIFF(MINUTE,created,NOW()) <= '=>5])->order(['id'=>'desc'])->first();
			if(empty($getSmsCode)){
				$returnArr = ["success"=>"false","message"=>'인증 시간 초과 또는 발송 성공한 인증번호가 없습니다.'];
				echo json_encode($returnArr); die;
			}
			if($getSmsCode->code == $authcode){
				$updateArr = [];
				$updateArr['type'] = 'email';
				$updateArr['email'] = $email;
				$returnArr = $this->authUserUpdate($updateArr);
			}
			echo json_encode($returnArr); die;
		}
	}
	/* 계좌 번호 인증 */
    public function bankauth(){
        if ($this->request->is('ajax')) {
			$accountNum = strip_tags($this->request->data['account_number']);
			$bank = strip_tags($this->request->data['bank']);
			if (empty($accountNum) || empty($bank)) {
				$returnArr = ['status' => 'false', 'message' => '필수값이 누락 되었습니다'];
				echo json_encode($returnArr);
				die;
			}
			/* 실제 인증 API 로직 들어갈 영역 
				$this->bankAuthApi($checkData);
				해당 함수 결과 성공 시 DB 업데이트 시켜줘야함
			*/
			$updateArr = [];
			$updateArr['type'] = 'bank';
			$updateArr['bank'] = $bank;
			$updateArr['account_number'] = $accountNum;
			$returnArr = $this->authUserUpdate($updateArr);
            echo json_encode($returnArr);
			die;
        }
    }
	/* 금융감독원 실명 계좌 인증 API 통신 미작업 */
	public function bankAuthApi($data = array()){
		// 1. 
		$url = 'https://openapi.openbanking.or.kr/v2.0/inquiry/real_name';
		$sendData = [];
		$sendData['bank_tran_id'] = '';
		$sendData['bank_code_std'] = '';
		$sendData['account_num'] = '';
		$sendData['account_holder_info_type	'] = '';
		$sendData['account_holder_info'] = '';
		$sendData['tran_dtime'] = '';
		$result = $this->bankAuthApiCurl($url,'POST',$sendData);
		return $result;
	}
	/* 실제 통신 미작업 */
	public function bankAuthApiCurl($url,$method,$data = array()){
		$output = '';
		if(!empty($data)){
			$output = implode(', ', array_map( function ($v, $k) { return sprintf("%s=%s", $k, $v); }, $data, array_keys($data)));
		}
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_PORT => "80",
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => $output,
			CURLOPT_HTTPHEADER => [
				"cache-control: no-cache",
				"Accept: application/json",
				"Authorization: Bearer eyJhbGciOiJIUzUxMiJ9.eyJlbWFpbCI6ImNodW5nQG9uZWZhbWlseW1hbGwuY29tIiwiaWQiOiJjM2I3MmY1YWY4ODkxMGNmMDVlYzkyNmRjNjRjNWQ5NCIsInR5cGUiOiJMT05HIiwibG9uZ1R5cGUiOnRydWUsImlzcyI6ImhlbmVzaXMtd2FsbGV0LWlkZW50aXR5LXByb2QtdGVzdG5ldCIsImlhdCI6MTYyNDkzNTE5NywiZXhwIjoxNjU2NDcxMTk3fQ.fAvKXY0TX6F_42lnsvPQvOrFTCro7zYrpXX__Hv4E0I-65IYNlkSc5Ta0bHvC9PbmULBCvRdytR_gfq6z4r0vw",
				"X-Henesis-Secret: um1u3U/johqXAIMWyqlsNZ7Pep7uFZbLaa6IzXIwakc=",
			],
		]);
		// Client ID 7be10590-c772-4df3-b9f8-dc4b8d424302
		// Client Secret 6935ceb7-adb1-4b77-a93d-ec265af69e68
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return  "cURL Error #: " . $err;
		} else {
			return $response;
		}
	}
	/* OTP 인증 */
	public function otpAuth(){
		if ($this->request->is('ajax')) {
			$returnArr = ['success' => 'false', 'message' => '인증번호가 일치하지 않습니다.'];
			$authcode = strip_tags($this->request->data['authcode']);
			if (empty($authcode)) {
				$returnArr = ['success' => 'false', 'message' => '필수값이 누락 되었습니다'];
				echo json_encode($returnArr);
				die;
			}
			$user = $this->Users->find()->select(['g_secret'])->where(['id'=>$this->Auth->user('id')])->first();
			$secret = $user->g_secret;
			$checkResult = $this->Users->verifyCode($secret, $authcode, 2);    // 2 = 2*30sec clock tolerance
			if ($checkResult) {
				$updateArr = [];
				$updateArr['type'] = 'otp';
				$returnArr = $this->authUserUpdate($updateArr);
			}
            echo json_encode($returnArr);
			die;
        }
	}
	/* 인증 성공 시 유저 업데이트 */
	private function authUserUpdate($data = array()){
		$this->loadModel('Users');
		$userId = $this->Auth->user('id');
		$user = $this->Users->find()->select(['email_auth','g_verify','bank_verify','id_document_status','scan_copy_status'])->where(['id'=>$userId])->first();
		$returnArr = [];
		$updateArr = [];
		$updateArr['user_level'] = 1;
		$updateArr['modified'] = date('Y-m-d h:i:s');
		if(empty($data) || empty($data['type'])) {
			$returnArr = ['success'=>'false','message'=>'필수값 누락'];
			return $returnArr;
		}
		if($data['type'] == 'email'){
			if(empty($data['email'])){
				$returnArr = ['success'=>'false','message'=>'필수값 누락'];
				return $returnArr;
			}
			$updateArr['email_auth'] = 'Y';
			$updateArr['email'] = $data['email'];
			if($user->g_verify == 'Y' && $user->bank_verify == 'Y'){
				$updateArr['user_level'] = 2;
			}
			if($user->g_verify == 'Y' && $user->bank_verify == 'Y' && $user->id_document_status == 'A'){ 
				$updateArr['user_level'] = 3;
			}
			$returnArr = ['success'=>'true','message'=>'메일 인증이 완료 되었습니다'];
		} else if ($data['type'] == 'bank'){
			if(empty($data['bank']) || empty($data['account_number'])){
				$returnArr = ['success'=>'false','message'=>'필수값 누락'];
				return $returnArr;
			}
			$updateArr['bank_verify'] = 'Y';
			$updateArr['bank'] = $data['bank'];
			$updateArr['account_number'] = $this->Encrypt($data['account_number']);
			if($user->g_verify == 'Y' && $user->email_auth == 'Y'){
				$updateArr['user_level'] = 2;
			}
			if($user->g_verify == 'Y' && $user->email_auth == 'Y' && $user->id_document_status == 'A'){ 
				$updateArr['user_level'] = 3;
			}
			$returnArr = ['success'=>'true','message'=>'계좌 인증이 완료 되었습니다'];
		} else if ($data['type'] == 'otp'){
			$updateArr['g_verify'] = 'Y';
			if($user->bank_verify == 'Y' && $user->email_auth == 'Y'){
				$updateArr['user_level'] = 2;
			}
			if($user->bank_verify == 'Y' && $user->email_auth == 'Y' && $user->id_document_status == 'A'){ 
				$updateArr['user_level'] = 3;
			}
			$returnArr = ['success'=>'true','message'=>'OTP 인증이 완료 되었습니다'];
		}
		$query = $this->Users->query();
		$query->update()->set($updateArr)->where(['id'=>$userId])->execute();
		return $returnArr;
	}
	/* 인증번호 문자 전송 - 필수 phone, country, type(url) */
    public function sendsmscode() { 
		if ($this->request->is('ajax')) {
			$phone = $this->request->data['phone'];
			$country = $this->request->data['country'];

			$checkResult = $this->check_phone($this->request->data);
			if($checkResult['success'] != 'true'){
				echo json_encode($checkResult); die;
			}

            $newCode = rand(111111, 999999);
			$this->request->data['code'] = $newCode;
			$insertResult = $this->insert_sms_code($this->request->data);
			if($insertResult['success'] != 'true'){
				echo json_encode($insertResult); die;
			}

			$smsSendArr = ['to'=>$phone, 'text'=>'Coin IBT의 인증 번호는 ['.$newCode.'] 입니다.','country'=>$country];
			$result = $this->sendCoolSms($smsSendArr); // 210721 SMS messente 에서 COOLSMS 로 변경 이충현
			$resultArr = json_decode($result, true);
			if($resultArr['error_count'] > 0){
				$returnArr = ["success"=>"false","message"=>'문자 발송이 실패되었습니다. 다시 시도해주세요.'];
				echo json_encode($returnArr); die;
			}
            $returnArr = ["success"=>"true","message"=>'문자 전송이 완료되었습니다.',"resp"=>''];
			echo json_encode($returnArr); die;
        }
		$returnArr = ["success"=>"false","message"=>__('Invalid Request')];
		echo json_encode($returnArr); die;
    }
	/* sms 인증 시 가입 여부 및 휴면 계정 체크 */
	private function check_phone($data = array()){
		$type = $data['type'];
		$phone = $data['phone'];
		$country = $data['country'];
		if(empty($type) || empty($phone) || empty($country)){
			$returnArr = ["success"=>"false","message"=>'필수값이 누락되었습니다.'];
            return $returnArr;
		}
		$this->loadModel('Users');
		$this->loadModel('DormantUsers');
		$getUserDetail = $this->Users->find('all',['conditions'=>['phone_number'=>$phone]])->hydrate(false)->first();
		if($type == 'signup'){
            if(!empty($getUserDetail)){
                $returnArr = ["success"=>"false","message"=>'이미 가입된 번호입니다.'];
                return $returnArr;
            }
		} else if ($type == 'forgetpass'){
            if(empty($getUserDetail)){
                $returnArr = ["success"=>"false","message"=>'회원 정보가 없습니다.'];
                return $returnArr;
            }
		}
		$dormant = $this->DormantUsers->find()->select(['user_id'])->where(['phone_number'=>$phone])->first();
		if(!empty($dormant)){
			$this->request->session()->write('undormantId', $dormant->user_id);
			$returnArr = ["success"=>"dormant","message"=>__("휴면계정입니다.")];
			return $returnArr;
		}
		$returnArr = ["success"=>"true","message"=>''];
		return $returnArr;
	}
	/* sms 코드 DB에 insert */
	private function insert_sms_code($data = array()){
		if(empty($data) || empty($data['phone']) || empty($data['country']) || empty($data['code'])){
			$returnArr = ["success"=>"false","message"=>'필수값이 누락되었습니다.'];
            return $returnArr;
		}
		$this->loadModel('SmsCode');
		$checkCount = $this->SmsCode->find()->where(['phone'=>$data['phone'],'TIMESTAMPDIFF(HOUR,created,NOW()) <= '=>2])->count();
		if($checkCount > 10){
			$returnArr = ["success"=>"false","message"=>'지속적인 문자 발송으로 인해 임시 차단되었습니다. 2시간 이후 재이용 가능합니다.'];
            return $returnArr;
		}
		$data['created'] = date('Y-m-d H:i:s');
		$smsCode = $this->SmsCode->newEntity();
		$smsCode = $this->SmsCode->patchEntity($smsCode, $data);
		if($this->SmsCode->save($smsCode)){
			$returnArr = ["success"=>"true","message"=>'']; 
			return $returnArr;
		} 
		$returnArr = ["success"=>"false","message"=>'저장 실패'];
		return $returnArr;
	}
	/* 실제 SMS 인증 */
	public function smsCodeCheck(){
		if ($this->request->is('ajax')) {
			$authcode = $this->request->data['authcode'];
			$phone = $this->request->data['phone'];
			$returnArr = ["success"=>"false","message"=>'인증번호가 일치하지 않습니다.'];
			if(empty($authcode) || empty($phone)){
				$returnArr = ["success"=>"false","message"=>'필수값이 누락되었습니다.'];
				echo json_encode($returnArr); die;
			}
			$this->loadModel('SmsCode');
			$getSmsCode = $this->SmsCode->find()->select(['phone','code'])->where(['phone'=>$phone,'TIMESTAMPDIFF(MINUTE,created,NOW()) <= '=>5])->order(['id'=>'desc'])->first();
			if(empty($getSmsCode)){
				$returnArr = ["success"=>"false","message"=>'인증 시간 초과 또는 발송 성공한 인증번호가 없습니다.'];
				echo json_encode($returnArr); die;
			}
			if($getSmsCode->code == $authcode){
				$this->loadModel('Users');
				$user = $this->Users->find()->select(['id','phone_number'])->where(['phone_number'=>$phone])->first();
				if(!empty($user)){
					$this->request->session()->write('selfCertificationSuccess', 'success');
					$this->request->session()->write('selfCertificationId', $user->id);
					$this->request->session()->write('selfCertificationPhone', $phone);
				}
				$returnArr = ["success"=>"true","message"=>''];
			}
			echo json_encode($returnArr); die;
		}
	}
	//Basis ID KYC Implementation starts
    public function updatestatus(){
        $this->loadModel('Users');
        $userId = $this->Auth->user('id');
        if ($this->request->is('ajax')) {
            if(!empty($userId)){
                $query = $this->Users->query();
                $status = $this->request->data('status');
                if($status == "ok"){
                    $status = "P";
                } else {
                    $status = "N";
                }
                $query->update()->set(['id_document_status' => $status, 'user_hash' => $this->request->data('user_hash')])->where(['id' => $userId])->execute();
                echo 1;
            } else {
                echo 'Please Log in';
            }
            die;
        }
    }
	/* make radom str + number */
    public function getToken($length){
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
	/* User Login */
    public function login() {
        
        $this->set('title' , 'Login');
        $this->set('username',"");
        $this->set('password',"");
        $getUserCountryCode = $this->Users->new_ipinfo_ip_chk('1');
        if(empty($getUserCountryCode)){
            $getUserCountryCode = $this->Users->new_ipinfo_ip_chk('1');
        }
        $this->set('getUserCountryCode',$getUserCountryCode);
		$this->set('user_status','A');

        if ($this->request->is('post')) {
			$password = strip_tags($this->request->data['password']);
			$username = filter_var($this->request->data['username'], FILTER_SANITIZE_STRING);
			if(!empty($username)){
				if (Validation::email($username)) {
					$this->Auth->config('authenticate', [
						'Form' => [
							'fields' => ['username' => 'email']
						]
					]);
					$this->Auth->constructAuthenticate();
					$this->request->data['email'] = $username;
					unset($this->request->data['username']);
				}
				// call sso api end
				$password = $this->request->data("password");
				$checkUserExist = $this->Users->find("all",['conditions'=>["OR"=>[["email"=>$username],["username"=>$username],["phone_number"=>$username]]]])
					->select(['id','email','eth_address','username','name','password','phone_number','user_type','referral_code','unique_id','ip_address','btc_address',
						'annual_membership','last_login','is_deleted','permission_person_info','permission_adv','onesignal_id','device_id','user_status','modified','created',
						'last_pw_change_date','login_fail_count','dormant'])->hydrate(false)->first();


                //check 테스트

				if(!empty($checkUserExist)) {
					if($checkUserExist['user_status'] == 'B'){  // login block
						$this->set('user_status',$checkUserExist['user_status']);
						return;
					}
					if($checkUserExist['user_status'] == 'F'){  // 로그인 5회 이상 실패
						$this->request->session()->write('selfCertification', $checkUserExist['id']);
						return $this->redirect(['action' => 'selfCertification']);
					}
					$existedHassPass = $checkUserExist['password'];
					$checkPass = (new DefaultPasswordHasher)->check($password,$existedHassPass);
					if ($checkPass) {
						$deviceID = $this->request->data['dev_id2'];
						$onesignalID = $this->request->data['onesignal_id2'];
						$this->request->session()->write('deviceID', $deviceID);
						$this->request->session()->write('onesignalID', $onesignalID);

						$user = $checkUserExist;

						if ($user) {
							$this->loadModel('LoginSessions');
							$loginSession = $this->LoginSessions->find('all', ['conditions' => ['user_id'=>$user['id']],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
							$token = $this->getToken(10);
							//$token = $this->request->cookie('app_session');
							$this->request->session()->write('loginToken', $token);
							$this->request->session()->write('loginTokenUserId', $checkUserExist['id']);
							$query = $this->LoginSessions->query();
							if(!empty($loginSession)){
								$query->update()->set(['status'=>'ACTIVE','token' => $token,'updated'=>date('Y-m-d H:i:s')])->where(['id' => $loginSession['id']])->execute();
							} else {
								$query->insert(['user_id','token','status','created','updated'])
									->values(['user_id'=>$user['id'],'token'=>$token,'status'=>'ACTIVE','created'=>date('Y-m-d H:i:s'),'updated'=>date('Y-m-d H:i:s')])->execute();
							}
							//return $this->redirect(['action' => 'secondAuth']);

                            //OTP 인증 안걸치고 바로 로그인 처리

                            $user = $this->Users->find("all",['conditions'=>['id'=>$user['id']]])->hydrate(false)->first();
                            $user_level = $user['user_level'];
                            if($user_level == 1){
                                if($user['email_auth'] == 'Y' && $user['bank_verify'] == 'Y'){
                                    $user_level = 2;
                                }
                            }

                            $user = $this->Users->get($user['id']);
                            if (empty($user['referral_code'])) {
                                $user['referral_code'] = $this->Users->generateReferralCode();
                            }
                            $deviceID = $this->request->session()->read('deviceID');
                            $onesignalID = $this->request->session()->read('onesignalID');
                            $user = $this->Users->patchEntity($user, [
                                'last_login' => date("Y-m-d H:i:s"),
                                'onesignal_id' => $onesignalID,
                                'device_id' => $deviceID,
                                'g_verify'=>'Y',
                                'user_level'=>$user_level,
                                'login_fail_count'=> 0 // 로그인 실패 횟수 초기화
                            ]);
                            $this->Auth->setUser($user);
                            
                            $this->Users->save($user);
                            $this->request->session()->delete('loginTokenUserId');
                            $this->request->session()->delete('deviceID');
                            $this->request->session()->delete('onesignalID');
                            $this->request->session()->delete('selfCertification');
                            $this->request->session()->delete('selfCertificationSuccess');
                            $this->request->session()->delete('selfCertificationId');
                            $this->request->session()->delete('selfCertificationPhone');

                            return $this->redirect('/front2/exchange/index/TP3/USDT');


						} else if ($user && $user['enabled'] == 'N') {
							$this->Flash->error(__('Your account is not verified.'));
							$this->loadModel('ErrorLoginLogs');
							$new_loginLog = $this->ErrorLoginLogs->newEntity();
							$data['user_id'] = $checkUserExist['id'];
							$data['username'] = $username;
							$data['error'] = "Your account is not verified";
							$logs_patch = $this->ErrorLoginLogs->patchEntity($new_loginLog, $data);
							$this->ErrorLoginLogs->save($logs_patch);
						}
					} else {
						$this->Flash->error(__('Invalid username or password'));
						$this->loadModel('ErrorLoginLogs');
						$new_loginLog = $this->ErrorLoginLogs->newEntity();
						$data['user_id'] = $checkUserExist['id'];
						$data['username'] = $username;
						$data['error'] = "Invalid username or password";
						$logs_patch = $this->ErrorLoginLogs->patchEntity($new_loginLog, $data);
						$this->ErrorLoginLogs->save($logs_patch);
						//$this->check_login_fail($checkUserExist['id'], $checkUserExist['login_fail_count']); // 로그인 실패 횟수 추가 및 5회 될 경우 로그인 제한 걸기
						return;
					}
				} else {
					$this->check_dormant($username, $password); // 휴면계정 여부 확인
					return;
				}
			} else {
				$this->Flash->error(__('Please fill all the fields'));
			}
        }
    }
	/* 2차 로그인 페이지 */
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
            // 로그인 5회 이상 실패
            //임시주석처리
			if($user->user_status == 'F'){
				$this->request->session()->write('selfCertification', $user->id);
				return $this->redirect(['action' => 'selfCertification']);
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
			$user_level = $user['user_level'];
			if($user_level == 1){
				if($user['email_auth'] == 'Y' && $user['bank_verify'] == 'Y'){
					$user_level = 2;
				}
			}
			
			$this->loadModel('LoginLogs');
			$new_log = $this->LoginLogs->newEntity();
			$data['user_id'] = $user_id;
			$data['ip_address'] = $this->get_client_ip();
			$log_patch = $this->LoginLogs->patchEntity($new_log, $data);
			$this->LoginLogs->save($log_patch);
			$this->Auth->setUser($user);

			$this->check_password_date($user['last_pw_change_date']); //최근 비밀번호 변경 날짜 확인
			$this->check_dormant_email($user_id); // 휴면계정 메일 받았는지 확인
			if (SENDMAIL == 1) {
				$user['msg'] = 'Logged in successfully with IP address ' . $user['ip_address'];
				$email = new Email('default');
				$email->viewVars(['data' => $user]);
				$email->from([$this->setting['email_from']])
					->to($user['email'])
					->subject('You are Logged in successfully.')
					->emailFormat('html')
					->template('login')
					->send();
			}

			$user = $this->Users->get($user['id']);
			if (empty($user['referral_code'])) {
				$user['referral_code'] = $this->Users->generateReferralCode();
			}
			$deviceID = $this->request->session()->read('deviceID');
			$onesignalID = $this->request->session()->read('onesignalID');
			$user = $this->Users->patchEntity($user, [
				'last_login' => date("Y-m-d H:i:s"),
				'onesignal_id' => $onesignalID,
				'device_id' => $deviceID,
				'g_verify'=>'Y',
				'user_level'=>$user_level,
				'login_fail_count'=> 0 // 로그인 실패 횟수 초기화
			]);

			$this->Users->save($user);
			$this->request->session()->delete('loginTokenUserId');
			$this->request->session()->delete('deviceID');
			$this->request->session()->delete('onesignalID');
			$this->request->session()->delete('selfCertification');
			$this->request->session()->delete('selfCertificationSuccess');
			$this->request->session()->delete('selfCertificationId');
			$this->request->session()->delete('selfCertificationPhone');
			return $this->redirect('/front2/exchange/index/TP3/USDT');
		}
	}
	/* OTP 발급 안내 페이지 */
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
			//return $this->redirect('/front2/Users/login');
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
	// 2.5. 안내 메일 받고 로그인 하였다면, 안내 메일 테이블에서 해당 유저의 정보는 삭제해준다. ==> /front2/UsersController login() 에서 처리
	private function check_dormant_email($user_id){
		$this->loadModel('DormantEmail');
		$check_list = $this->DormantEmail->find()->where(['user_id'=>$user_id])->first();
		if(empty($check_list) || count($check_list) < 1){ return; }
		if(!empty($check_list)){
			$delete_query = $this->DormantEmail->query();
			$delete_query->delete()->where(['user_id'=>$user_id])->execute();
		}
		return;
	}
	/* 최근 비밀번호 변경일 확인 (6개월 단위 변경 알림)*/
	private function check_password_date($last_date){
		$six_month = date("Y-m-d H:i:s", strtotime("-6 month",time()));
		if(empty($last_date)){
			return $this->redirect(['action' => 'newChangePassword']);
		}
		if(strtotime($six_month) > strtotime($last_date)){
			//return $this->redirect(['action' => 'newChangePassword']);
			return 'fail';
		}
		return 'success';
	}
	/* 로그인 실패 횟수 확인 및 추가 -> 실패 카운트가 5번이면 차단 */
	private function check_login_fail($user_id, $fail_count){
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
	/* 휴면 계정 여부 확인 */
	private function check_dormant($username, $password){
		$this->loadModel('DormantUsers');
		$this->loadModel('Users');
		$dormant = $this->DormantUsers->find()->where(['username'=>$username])->first();
		if(!empty($dormant)){
			$origin_user = $this->Users->get($dormant->user_id);
			if(!empty($origin_user) || $origin_user->dormant == 'Y'){
				$checkPass = (new DefaultPasswordHasher)->check($password,$dormant->password);
				if($checkPass){
					$this->request->session()->write('undormantId', $dormant->user_id);
					return $this->redirect(['action' => 'undormant']);
				} else {
					$this->Flash->error('비밀번호가 일치하지 않습니다');
					return;
				}
			}
		}
		$this->Flash->error(__('User does not exist'));
		return;
	}
	/* 휴면 계정 해제 페이지 */
	public function undormant(){
		$session_user_id = $this->request->session()->read('undormantId');
		if(empty($session_user_id)){
			echo '<script>window.onload = function(){alert("잘못된 접근입니다."); location.href="/front2/users/login";};</script>';
		}
		$this->set('user_id',$session_user_id);
		$RetDomain           = "bitsomon.com";
	    $g_conf_home_dir     = "/var/www/html";
        $g_conf_site_cd      = "A95YT";
        $g_conf_web_siteid   = "J20080705761";
        //$g_conf_ENC_KEY      = "E66DCEB95BFBD45DF9DFAEEBCB092B5DC2EB3BF0";
		$g_conf_ENC_KEY      = "75e8d7793e1dc665ef540e783a61844d3bbfcd06720dfffc01a6f7d0d76e42f7";
	    //$g_conf_Ret_URL      = "https://www.coinibt.io/webroot/KcpcertCoinibt/WEB_ENC/kcpcert_proc_res.php";
		$g_conf_Ret_URL      = "https://www.bitsomon.com/webroot/KcpcertCoinibt/SMART_ENC/smartcert_proc_res.php";

	    //$g_conf_gw_url       = "https://testcert.kcp.co.kr/kcp_cert/cert_view.jsp";
	    $g_conf_gw_url       = "https://cert.kcp.co.kr/kcp_cert/cert_view.jsp";

	    $this->set('RetDomain',$RetDomain);
	    $this->set('g_conf_home_dir',$g_conf_home_dir);
	    $this->set('g_conf_site_cd',$g_conf_site_cd);
	    $this->set('g_conf_web_siteid',$g_conf_web_siteid);
	    $this->set('g_conf_ENC_KEY',$g_conf_ENC_KEY);
	    $this->set('g_conf_Ret_URL',$g_conf_Ret_URL);

		if($this->request->is('post')){
			$user_id = $this->request->data('user_id');
			$phone = $this->request->data('phone');
			if($session_user_id == $user_id){
				$status = $this->undormantData($user_id,$phone);// 휴면 계정 해제 처리
				if($status == 'success'){
					$this->Flash->success('휴면 계정이 해제되었습니다.');
					return $this->redirect(['action' => 'undormantComplete']);
				} else {
					$this->Flash->error('알 수 없는 오류가 발생했습니다. 처음부터 다시 시도해주세요.');
					return;
				}
			}
		}
	}
	/* 휴면 계정 해제 완료 페이지 */
	public function undormantComplete(){
	
	}
	/* 실제 휴면 계정 해제 처리 */
	// 4. 휴면 계정 사용자가 로그인 후 본인인증 및 해제 처리 시 복구, dormant_users 에서 해당 유저 정보 삭제 ==> /front2/UsersController 에서 처리해야함
	private function undormantData($user_id,$phone){
		$this->loadModel('DormantUsers');
		$this->loadModel('ChangeAuth');
		$this->loadModel('Users');
		$this->loadModel('WithdrawalWalletAddress');
		$this->loadModel('BoardQna');
		$dormant_user = $this->DormantUsers->find('all')->where(['user_id'=>$user_id])->hydrate(false)->first();
		$phone_number;
		if($phone != $dormant_user['phone_number']){
			$this->Flash->error('전화번호가 일치하지 않습니다.');
			return;
		}
		$columns = $this->Users->schema()->columns();

		if(!empty($dormant_user)){
			$data_arr = [];
			$change_auth_data = [];
			$wallet_address_data = [];
			$board_qna_data = [];
			foreach($dormant_user as $k=>$data){ // 칼럼을 key, 데이터를 value로 배열 만들기
				 if($k == 'auth_req_list'){ // ChangeAuth 에 업데이트할 배열과
					$change_auth_data = json_decode($data,true);
				} else if($k == 'wallet_address_list'){ // 지갑 주소에 업데이트 할 배열 
					$wallet_address_data = json_decode($data,true);
				} else if($k == 'board_qna_list'){ // 1대1문의 게시판에 업데이트할 배열
					$board_qna_data = json_decode($data,true);
				}
				foreach($columns as $key=>$value){
					if($k == $value){
						if($k == 'last_login') {
							$data_arr[$k] = date('Y-m-d H:i:s');
						} else {
							$data_arr[$k] = $data;
						}
						continue;
					}
				}
			}
			$data_arr['dormant'] = 'N';// 휴면계정 표시

			if(!empty($data_arr)){
				$update_query = $this->Users->query();
				$update_query->update()->set($data_arr)->where(['id'=>$user_id])->execute();

				$delete_query = $this->DormantUsers->query();
				$delete_query->delete()->where(['user_id'=>$user_id])->execute();

				$this->check_dormant_email($user_id);
				if(!empty($wallet_address_data)){ // 왈렛 주소 null=> 값으로 업데이트
					foreach($wallet_address_data as $k=>$data){
						$id = 0;
						foreach($data as $key=>$value){
							if($key == 'id'){
								$id = $value;
							} else {
								$wallet_update_query = $this->WithdrawalWalletAddress->query();
								$wallet_update_query->update()->set([$key=>$value])->where(['id'=>$id])->execute();
							}
						}
					}
				}
				if(!empty($change_auth_data)){
					foreach($change_auth_data as $k=>$data){ // 인증변경요청 null 처리된 부분 다시 원복
						$id = 0;
						foreach($data as $key=>$value){
							if($key == 'id'){
								$id = $value;
							} else {
								$auth_update_query = $this->ChangeAuth->query();
								$auth_update_query->update()->set([$key=>$value])->where(['id'=>$id])->execute();
							}
						}
					}
				}
				if(!empty($board_qna_data)){
					foreach($board_qna_data as $k=>$data){ // 1대1문의 null 처리된 부분 다시 원복
						$id = 0;
						foreach($data as $key=>$value){
							if($key == 'id'){
								$id = $value;
							} else {
								$board_update_query = $this->BoardQna->query();
								$board_update_query->update()->set([$key=>$value])->where(['id'=>$id])->execute();
							}
						}
					}
				}
				return 'success';
			}
		}
		return 'fail';
	}
	/* 비밀번호 변경 페이지 */
	public function newChangePassword(){
		$type = 'include_old_password';
		$prev_url = explode('/',$this->request->referer());
		if(in_array('selfCertification',$prev_url) || in_array('self-certification',$prev_url) || in_array('smartcert_proc_res.php',$prev_url)){
			$type = 'exclude_old_password';
		}
		$selfCertificationSuccess = $this->request->session()->read('selfCertificationSuccess');
		if( $selfCertificationSuccess == 'success'){
			$type = 'exclude_old_password';
		}
		$this->set('type',$type);

		if($this->request->is('post')){
			$type = $this->request->data['type'];
			$newPass = filter_var($this->request->data['new_password'], FILTER_SANITIZE_STRING);
			$confNewPass = filter_var($this->request->data['confirm_password'], FILTER_SANITIZE_STRING);
			if(empty($newPass) || empty($confNewPass)){
				$this->Flash->error('변경할 비밀번호를 입력해주세요');
				return;
			}
			if($type == 'include_old_password'){
				$otp_number = $this->request->data['otp_number'];
				$oldPass = filter_var($this->request->data['old_password'], FILTER_SANITIZE_STRING);
				if(empty($oldPass)){
					$this->Flash->error('현재 비밀번호를 입력해주세요');
					return;
				}
				if(empty($otp_number)){
					$this->Flash->error('OTP 번호를 입력해주세요');
					return;
				}
				$users = $this->Users->get($this->Auth->user('id'));
				if(empty($users)){
					$this->Flash->error('세션이 만료됐습니다. 처음부터 다시 시작해주세요');
					return $this->redirect(['controller' => 'users', 'action' => 'login']);
				}	

				$secret = $users->g_secret;
				if(empty($secret)){
					$this->Flash->error('등록된 OTP가 없습니다. OTP를 등록해주세요');
					return;
				}
				$checkResult = $this->Users->verifyCode($secret, $otp_number, 2);    // 2 = 2*30sec clock tolerance
				if (!$checkResult) {
					$this->Flash->error('OTP 번호가 일치하지 않습니다');
					return;
				}
				
				$validation_check = $this->validationPassword($users['id'], $type, $oldPass, $newPass, $confNewPass);
				if($validation_check['status'] != 'success'){
					$this->Flash->error($validation_check['message']);
					return;
				}
				
				$users = $this->Users->patchEntity($users, [
					'old_password' => $oldPass,
					'password' => $newPass,
					'new_password' => $newPass,
					'confirm_password' => $confNewPass,
					'last_pw_change_date'=>date('Y-m-d H:i:s')
				]);

				if ($this->Users->save($users)) { 
					$this->Flash->success(__('Password updated'));
					return $this->redirect(['controller' => 'users', 'action' => 'security']);
				} else {
					$this->Flash->error('알수없는 오류가 발생했습니다');
					return;
				}
			} else if ($type == 'exclude_old_password'){
				$this->loadModel('Users');
				$user_id = $this->request->session()->read('selfCertificationId');
				$phone = $this->request->session()->read('selfCertificationPhone');
				if(empty($user_id) || empty($phone)){
					$this->Flash->error('세션이 만료되었습니다. 처음부터 다시 시작해주세요.');
					return;
				}
				$users = $this->Users->find()->where(['phone_number'=>$phone,'id'=>$user_id])->first();
				if(empty($users)){
					$this->Flash->error('등록되지 않은 회원입니다.');
					return;
				}
				$validation_check = $this->validationPassword($user_id, $type, '', $newPass, $confNewPass);
				if($validation_check['status'] != 'success'){
					$this->Flash->error($validation_check['message']);
					return;
				}
				$users = $this->Users->patchEntity($users, [
					'user_status' => 'A',
					'login_fail_count' => 0,
					'password' => $newPass,
					'new_password' => $newPass,
					'confirm_password' => $confNewPass,
					'blocked'=>date('Y-m-d H:i:s'),
					'last_pw_change_date'=>date('Y-m-d H:i:s')
				]);
				if ($this->Users->save($users)) { 
					$this->Flash->success(__('Password updated'));
					return $this->redirect(['controller' => 'users', 'action' => 'login']);
				} else {
					$this->Flash->error('알수없는 오류가 발생했습니다');
					return;
				}
			}
		}
	}
	/* 본인인증 페이지 */
	public function selfCertification(){
		/*$RetDomain           = "coinibt.io";*/
        $RetDomain           = "bitsomon.com";
	    $g_conf_home_dir     = "/var/www/html";
        $g_conf_site_cd      = "A95YT";
        $g_conf_web_siteid   = "J20080705761";
        //$g_conf_ENC_KEY      = "E66DCEB95BFBD45DF9DFAEEBCB092B5DC2EB3BF0";
		$g_conf_ENC_KEY      = "75e8d7793e1dc665ef540e783a61844d3bbfcd06720dfffc01a6f7d0d76e42f7";
	    //$g_conf_Ret_URL      = "https://www.coinibt.io/webroot/KcpcertCoinibt/WEB_ENC/kcpcert_proc_res.php";
		$g_conf_Ret_URL      = "https://www.bitsomon.com/webroot/KcpcertCoinibt/SMART_ENC/smartcert_proc_res.php";

	    $g_conf_gw_url       = "https://cert.kcp.co.kr/kcp_cert/cert_view.jsp";

	    $this->set('RetDomain',$RetDomain);
	    $this->set('g_conf_home_dir',$g_conf_home_dir);
	    $this->set('g_conf_site_cd',$g_conf_site_cd);
	    $this->set('g_conf_web_siteid',$g_conf_web_siteid);
	    $this->set('g_conf_ENC_KEY',$g_conf_ENC_KEY);
	    $this->set('g_conf_Ret_URL',$g_conf_Ret_URL);
	    $this->set('g_conf_gw_url',$g_conf_gw_url);
		if($this->request->is('post')){
			$this->loadModel('Users');
			$phone = $this->request->data('phone');
			$selfCertification = $this->request->session()->read('selfCertification');
			$user_id = $this->Users->find()->select(['id'])->where(['phone_number'=>$phone])->first();
			$check_user_id = $this->Users->find()->select(['id','phone_number'])->where(['id'=>$selfCertification])->first();
			if(empty($user_id) || empty($check_user_id)){
				$this->Flash->error('등록되지 않은 회원입니다.');
				return;
			}
			if($user_id->id != $check_user_id->id){
				$this->Flash->error('알 수 없는 오류가 발생했습니다. 처음부터 다시 시작해주세요.');
				return $this->redirect(['action' => 'login']);
			}
			$this->request->session()->write('selfCertificationSuccess', 'success');
			$this->request->session()->write('selfCertificationId', $user_id->id);
			$this->request->session()->write('selfCertificationPhone', $phone);
			return $this->redirect(['action' => 'newChangePassword']);
		}
	}

    public function referral() {
        $user = $this->Users->get($this->Auth->user('id'));
        $this->set('user', $user);
        $this->set('title', 'Referrals');
        $this->set('listing', $this->Paginator->paginate($this->Users, [
                    'conditions' => ['referral_user_id' => $this->Auth->user('id'), 'enabled' => 'Y'],
                    'limit' => $this->setting['pagination'],
                    'order' => ['Users.id' => 'desc']
        ]));
    }

    function f_get_parm_str( $val )
    {
        if ( $val == null ) $val = "";
        if ( $val == ""   ) $val = "";
        return  $val;
    }

    public function signup() {

    /* ============================================================================== */
    /* =   PAGE : 인증 정보 환경 설정 PAGE                                          = */
    /* = -------------------------------------------------------------------------- = */
    /* = -------------------------------------------------------------------------- = */
    /* = -------------------------------------------------------------------------- = */
    /* =   Copyright (c)  2019   KCP Inc.   All Rights Reserved.                    = */
    /* ============================================================================== */
        /*$RetDomain           = "coinibt.io";*/
        $RetDomain           = "bitsomon.com";
	    $g_conf_home_dir     = "/var/www/html";
        $g_conf_site_cd      = "A95YT";
        $g_conf_web_siteid   = "J20080705761";
        //$g_conf_ENC_KEY      = "E66DCEB95BFBD45DF9DFAEEBCB092B5DC2EB3BF0";
		$g_conf_ENC_KEY      = "75e8d7793e1dc665ef540e783a61844d3bbfcd06720dfffc01a6f7d0d76e42f7";
	    //$g_conf_Ret_URL      = "https://www.coinibt.io/webroot/KcpcertCoinibt/WEB_ENC/kcpcert_proc_res.php";
		$g_conf_Ret_URL      = "https://www.bitsomon.com/webroot/KcpcertCoinibt/SMART_ENC/smartcert_proc_res.php";

	    //$g_conf_gw_url       = "https://testcert.kcp.co.kr/kcp_cert/cert_view.jsp";
	    $g_conf_gw_url       = "https://cert.kcp.co.kr/kcp_cert/cert_view.jsp";

	    $this->set('RetDomain',$RetDomain);
	    $this->set('g_conf_home_dir',$g_conf_home_dir);
	    $this->set('g_conf_site_cd',$g_conf_site_cd);
	    $this->set('g_conf_web_siteid',$g_conf_web_siteid);
	    $this->set('g_conf_ENC_KEY',$g_conf_ENC_KEY);
	    $this->set('g_conf_Ret_URL',$g_conf_Ret_URL);
	    $this->set('g_conf_gw_url',$g_conf_gw_url);

	    $user = $this->Users->newEntity();

		$getUserCountryCode = $this->Users->new_ipinfo_ip_chk('1');
		if(empty( $getUserCountryCode)){
			$getUserCountryCode = $this->Users->new_ipinfo_ip_chk('1');
		}
		$ipinfo_token = $this->Users->getIpinfoToken();
		$this->set('ipinfo_token',$ipinfo_token);

        if ($this->request->is('post')) {
			
			if ( isset($this->request->data['site_cd']) && !empty($this->request->data['site_cd']) ) {

				$phone_no = $this->request->data['phone_no'];
				$user_name = $this->request->data['user_name'];

                $checkUserExist = $this->Users->find("all",['conditions'=>['phone_number'=>$phone_no,'username'=>$phone_no,'name'=>$user_name]])->hydrate(false)->first();
                if($checkUserExist){
                    $this->Flash->error(__('This phone number is already registered.'));
                } else{
                    $this->set('phone_no',$phone_no);
                    $this->set('user_name',$user_name);
                }

			} else {

				$username = filter_var($this->request->data['user_name'], FILTER_SANITIZE_STRING);

				$password = strip_tags($this->request->data['password']);
				$passwordConf = strip_tags($this->request->data['password2']);

				if($password!=$passwordConf){
					$this->Flash->error(__('Password and confirm password should be same'));
				}
				else if(!isset($this->request->data['form_type'])){
					$this->Flash->error(__('Invalid Registration'));
				}
				else if(!in_array($this->request->data['form_type'],['korean','non_korean'])){
					$this->Flash->error(__('Invalid Registration'));
				}
				else {
                    $formType = $this->request->data['form_type'];
                    $username = $this->request->data['user_name'];
                    $name = $this->request->data['user_name'];
					$phone_number = filter_var($this->request->data['phone'], FILTER_SANITIZE_STRING);
					$contCode = "";
					if($formType == 'korean'){
						$phone_number = str_replace("-", "", $phone_number);
						$contCode = "82";
						//echo $result;
					} else {
						$phone_number = $this->request->data['phone'];
						$contCode = $getUserCountryCode;
					}
					$this->request->data['username'] = $phone_number;//$this->request->data['phone'];
					$this->request->data['name'] = $this->request->data['user_name'];
					$this->request->data['phone_number'] = $phone_number;//$this->request->data['phone'];
					$this->request->data['country_dialcode']= $contCode;
					$email = $this->request->data['email'];
					$email = filter_var($email, FILTER_SANITIZE_EMAIL);

					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$this->Flash->error(__('Enter a Valid Email'));
						return $this->redirect(['controller' => 'users', 'action' => 'signup']);
					}

					$checkEmailExist = $this->Users->find("all",['conditions'=>['email'=>$email]])->hydrate(false)->first();
					if($checkEmailExist){
						$this->Flash->error(__('This email is already registered.'));
					} else {
					}
                    $btc_address = $this->get_coin_address('btc_address', 'get', '', '');
                    $eth_address = $this->get_coin_address('eth_address', 'get', '', '');

                    $this->request->data['btc_address'] = $btc_address;
                    $this->request->data['eth_address'] = $eth_address;
                    $this->request->data['referral_code'] = $this->Users->generateReferralCode();
                    $this->request->data['unique_id'] = $this->getUniqueId();
                    $this->request->data['ip_address'] = $this->get_client_ip();
                    $this->request->data['enabled'] = 'Y';
                    $this->request->data['user_name'] = $username;
                    $this->request->data['name'] = $name;
                    $this->request->data['password'] = $password;
                    $this->request->data['phone'] = $phone_number;
                    $this->request->data['email'] = $email;
					$this->request->data['last_pw_change_date'] = date('Y-m-d H:i:s');
                    //동의 비동의 체크 데이터 넣기 (+ 동의한 날짜포함)
                    // 총 8개..?
                    $term = $this->request->data['term'];
                    $policy = $this->request->data['policy'];
                    $check4 = $this->request->data['check4'];
                    $check5 = $this->request->data['check5'];
                    $this->request->data['term'] = $term;
                    $this->request->data['term_date'] = date('Y-m-d H:i:s');;
                    $this->request->data['policy'] = $policy;
                    $this->request->data['policy_date'] = date('Y-m-d H:i:s');;
                    $this->request->data['check4'] = $check4;
                    $this->request->data['check4_date'] = date('Y-m-d H:i:s');;
                    $this->request->data['check5'] = $check5;
                    $this->request->data['check5_date'] = date('Y-m-d H:i:s');;

                    $data = $this->request->data;
                    $user = $this->Users->patchEntity($user, $this->request->data);

                    if ($user->errors()) {
                        $error_msg = [];
                        foreach ($user->errors() as $errors) {
                            if (is_array($errors)) {
                                foreach ($errors as $error) {
                                    $error_msg[] = $error;
                                }
                            } else {
                                $error_msg[] = $errors;
                            }
                        }

                        if (!empty($error_msg)) {
                            $this->Flash->error(
                                __(implode(" AND ", $error_msg))
                            );
                        }
                        return $this->redirect(['controller' => 'users', 'action' => 'signup']);
                    }

                    if ($usrDetail = $this->Users->save($user)) {
                        $this->get_coin_address('btc_address', 'update', $user['id'], $btc_address);
                        $this->get_coin_address('eth_address', 'update', $user['id'], $eth_address);

                        $data['userLink'] = $usrDetail->unique_id;
                        $email = new Email('default');
                        $email->viewVars(['data' => $data]);
                        $email->from([$this->setting['email_from']])
                            ->to($this->request->data['email'])
                            ->subject(__('You have been registered successfully at Coin IBT Exchange.'))
                            ->emailFormat('html')
                            ->template('signup')
                            ->send();

                        $this->Flash->success(__('You have successfully registered your account.'));

                        return $this->redirect(['controller' => 'users', 'action' => 'login']);
                    } else {
                        $this->Flash->error(__('Unable to register ! Please Try Again'));
                        return $this->redirect(['controller' => 'users', 'action' => 'signup']);
                    }
				}
			} //  -------------
        }
		$this->set('getUserCountryCode',$getUserCountryCode);
		// 폼처리
    }

    public function get_coin_address($coin_type, $call_type, $user_id, $value){
        $this->loadModel('Users');
        if($coin_type == 'btc_address'){
            $this->loadModel('TmpBtcAddress');
            if($call_type == 'get'){
                $btc_address = $this->TmpBtcAddress->find()->select(['btc_address'])->where(['is_use'=>'N'])->order(['id'=>'asc'])->first();
                if($btc_address){
                    return $btc_address->btc_address;
                } else {
                    return "";
                }
            } else if($call_type == 'update'){
                if($value != ''){
                    $query = $this->TmpBtcAddress->query();
                    $query->update()->set(['is_use' => 'Y','user_id'=>$user_id,'updated'=>date('Y-m-d H:i:s')])->where(['btc_address' => $value])->execute();
                    return "success";
                }
            }
        } else if($coin_type == 'eth_address'){
            $this->loadModel('TmpEthAddress');
            if($call_type == 'get'){
                $eth_address = $this->TmpEthAddress->find()->select(['eth_address'])->where(['is_use'=>'N'])->order(['id'=>'asc'])->first();
                if($eth_address){
                    return $eth_address->eth_address;
                } else {
                    return "";
                }
            } else if($call_type == 'update'){
                if($value != ''){
                    $query = $this->TmpEthAddress->query();
                    $query->update()->set(['is_use' => 'Y','user_id'=>$user_id,'updated'=>date('Y-m-d H:i:s')])->where(['eth_address' => $value])->execute();
                    return "success";
                }
            }
        }
    }

    public function download($id=null)
    {
        $file_path = WWW_ROOT.'downloads'.DS.$id;
        $this->autoRender=false;
        $this->response->file($file_path,array('download' => true));
    }

    public function changepassword() {
        $this->set('title', ' Change password');

        $users = $this->Users->get($this->Auth->user('id'));
        if ($this->request->is(['post', 'put'])) {

            //if (!empty($this->request->data['submitpass'])) {

                $newPass = filter_var($this->request->data['new_password'], FILTER_SANITIZE_STRING);

                // Declare a regular expression

                $regex = '/^[0-9]{6,}$/';
                if (!preg_match($regex, $newPass)) {
                    $this->Flash->error('Password should consist of 6 numbers');

                }

                $confNewPass = filter_var($this->request->data['confirm_password'], FILTER_SANITIZE_STRING);
                $oldPass = filter_var($this->request->data['old_password'], FILTER_SANITIZE_STRING);
                if ($newPass != $confNewPass) {
                    $this->Flash->error('New password and confirm password should be same');

                }


                $users = $this->Users->patchEntity($users, [
                    'old_password' => $oldPass,
                    'password' => $newPass,
                    'new_password' => $newPass,
                    'confirm_password' => $confNewPass
                ]);

                if ($user = $this->Users->save($users)) { 
                    $this->Flash->success('Your password has been updated');
                    return $this->redirect(['controller' => 'users', 'action' => 'security']);
                } else {
					
                    $this->Flash->error('You entered incorrect current password');
                }
            //}
        }

        $this->set('users', $users);
    }

    public function logout() {
        $this->loadModel('LoginSessions');
        $userId = $this->Auth->User("id");
		$tokenSession = $this->request->session()->read('loginToken');
		if(!empty($tokenSession)){
	        $loginSession = $this->LoginSessions->find('all', ['conditions' => ['token'=>$tokenSession],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
			if(!empty($loginSession)){
				$query = $this->LoginSessions->query();
				$query->update()->set(['status'=>'INACTIVE'])->where(['token'=>$tokenSession])->execute();
				$this->request->session()->destroy();
			}
		}
		if (isset($_COOKIE['app_session_token'])) {
			unset($_COOKIE['app_session_token']);
			setcookie('app_session_token', '', time() - 3600, '/');
		}
        $this->Auth->logout();
        $this->redirect('/');
    }

    public function changeGoogleauthVerification() {


        if ($this->request->is('ajax')) {
            $cuUserId = $this->Auth->User("id");
            $verificationStatus = $this->request->data['verification_status'];
            if (in_array($verificationStatus, ["Y", "N"])) {
                $user = $this->Users->get($cuUserId);
                $user->g_auth_enable = $verificationStatus;
                if ($this->Users->save($user)) {
                    echo "success";
                } else {
                    echo "error";
                }
            } else {
                echo " Invalid Verification Status";
            }

            die;
        }
        die;
    }
	/* 가입 시 폰 중복 체크 */
    public function checkPhoneUnique() {
		$this->loadModel('Users');
		$this->loadModel('DormantUsers');
		$this->loadModel('LeavingUsers');
        if ($this->request->is('ajax')) {
            $phone = $this->request->data['phone'];
            $users = $this->Users->find('all',['fields'=>'phone_number','conditions'=>['phone_number'=>$phone]])->hydrate(false)->first();
			$dormant_users = $this->DormantUsers->find('all',['fields'=>'phone_number','conditions'=>['phone_number'=>$phone]])->hydrate(false)->first();
			$leaving_users = $this->LeavingUsers->find('all',['fields'=>'phone_number','conditions'=>['phone_number'=>$phone]])->hydrate(false)->first();
            if($users || $dormant_users){
                $respArr=['status'=>'false','message'=>"이미 가입된 번호입니다"];
				echo json_encode($respArr);
                die;
            }
			if($leaving_users){
                $respArr=['status'=>'false','message'=>"탈퇴한 번호입니다"];
				echo json_encode($respArr);
                die;
            }
			$respArr=['status'=>'true','message'=>"sucess"];
			echo json_encode($respArr);
			die;
        }
        die;
    }
	/* 가입 시 메일 중복 체크 */
    public function checkEmailUnique() {
		$this->loadModel('Users');
		$this->loadModel('DormantUsers');
        if ($this->request->is('ajax')) {
            $email = $this->request->data['email'];
            $users = $this->Users->find('all',['fields'=>'email','conditions'=>['email'=>$email,'email_auth'=>'Y']])->hydrate(false)->first();
			$dormant_users = $this->DormantUsers->find('all',['fields'=>'email','conditions'=>['email'=>$email]])->hydrate(false)->first();
            if($users || $dormant_users){
                $respArr=['status'=>'false','message'=>"이미 가입된 메일 입니다"];
				echo json_encode($respArr);
                die;
            }else{
                $respArr=['status'=>'true','message'=>"success"];
				echo json_encode($respArr);
                die;
            }
            die;
        }
        die;
    }

    public function changeSecondVerification() {

        if ($this->request->is('ajax')) {
            $cuUserId = $this->Auth->User("id");
            $verificationStatus = $this->request->data['verification_status'];
            if (in_array($verificationStatus, ["Y", "N"])) {
                $user = $this->Users->get($cuUserId);
                $user->second_verification = $verificationStatus;
                if ($this->Users->save($user)) {
                    echo "success";
                } else {
                    echo "error";
                }
            } else {
                echo " Invalid Verification Status";
            }

            die;
        }
        die;
    }

    public function forgetid() {
        if ($this->request->is('post')) {

            // 폼처리

            $this->viewBuilder()->template('forgetid_complete');
        } else {
            
        }
    }
	/* 비밀번호 찾기 */
    public function forgetpass() {
		$ipinfo_token = $this->Users->getIpinfoToken();
		$this->set('ipinfo_token',$ipinfo_token);
    }


    public function changepass() {
        $this->loadModel('Users');
        if ($this->request->is('post')) {
            // 폼처리
            $authcode = $this->request->data['auth_key'];

            if(empty($authcode)){
                $this->Flash->error(__('All fields are required'));
                return $this->redirect('front2/users/changepass');
            }
            $smsCode = $this->request->session()->read('sms_code_forgot_pass');
            $smsPhone = $this->request->session()->read('sms_phone_forgot_pass');
            $firstChar = substr($smsPhone,0,3);
            $checkPhone = ($firstChar=="+82") ?  str_replace("+82","0",$smsPhone) : $smsPhone;

            if($smsCode != $authcode){
                $this->Flash->error(__('Invalid authentication code'));
                return $this->redirect('front2/users/changepass');
            }

            $user_record = $this->Users->find()->where(['phone_number' => $checkPhone])->first();
            if ($user_record && !empty($user_record)) {
                $new_password = $this->request->data['new_password'];
                $confirm_password = $this->request->data['confirm_password'];
                if(!empty($new_password) && !empty($confirm_password)){
					$validation_check = $this->validationPassword('', 'exclude_old_password', '', $new_password, $confirm_password);
					if($validation_check['status'] != 'success'){
						$this->Flash->error($validation_check['message']);
						return;
					}
                    if($new_password == $confirm_password){
                        $query = $this->Users->query();
                        $query->update()
                            ->set(['password' => (new DefaultPasswordHasher)->hash($new_password)])
                            ->where(['phone_number' => $checkPhone])
                            ->execute();
                    } else {
                        $this->Flash->error(__('New and confirm passwords are not same'));
                    }
                } else {
                    $this->Flash->error(__('Please fill all the fields'));
                }


                //print_r($user); die;
                $this->Flash->success(__('Password updated'));
                return $this->redirect('front2/users/login');
            }
            else {
                $this->Flash->error(__('Invalid User'));
                return $this->redirect('front2/users/login');
            }
            //  $this->viewBuilder()->template('forgetpass_complete');
        } else {

        }
    }
    /* 개인정보 관리 페이지 */
    public function security() {
        $this->set('kind', 'security');
        $this->set('title', 'Security');
        $user = $this->Users->get($this->Auth->user('id'));
        $this->set('user', $user);
    }

    public function support() {
        $this->loadModel('Support');
        $this->loadModel('SupportConversation');
        $this->set('title', 'Support');
        $userId = $this->Auth->user('id');
        $user = $this->Users->get($userId);
        $this->set('user', $user);

        $before_image = $user->image;
        if ($this->request->is(['post', 'put'])) {

            $issueType = filter_var($this->request->data['issue_type'], FILTER_SANITIZE_STRING);
            $issue = filter_var($this->request->data['issue'], FILTER_SANITIZE_STRING);
            $txId = filter_var(strip_tags($this->request->data['tx_id']), FILTER_SANITIZE_STRING);

            if (empty($issueType) || empty($issue)) {
                $this->Flash->error(__('* fields are required'));
                return $this->redirect(['action' => 'support']);
            }

            $newImageName = '';
            if (isset($_FILES['issue_file']) && $_FILES['issue_file']['tmp_name'] != '') {

                $filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['issue_file']['name']);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!in_array($ext, ['jpg', 'png', 'jpeg'])) {
                    $this->Flash->error(__('Invalid Image Format! Image format must Be JPG, JPEG or PNG.'));
                    return $this->redirect(['action' => 'support']);
                }

                if ($_FILES['issue_file']['size'] > 2097152) {
                    $this->Flash->error(__('File size should not exceed 2 MB.'));
                    return $this->redirect(['action' => 'support']);
                }
                $filename = time() . '.' . $ext;
                if ($this->uploadImage($_FILES['issue_file']['tmp_name'], $_FILES['issue_file']['type'], 'uploads/issue_file/', $filename)) {
                    $newImageName = $filename;
                }
            }


            $insertArr = [];
            $insertArr['issue_type'] = $issueType;
            $insertArr['issue'] = $issue;
            $insertArr['tx_id'] = $txId;
            $insertArr['user_id'] = $userId;
            $insertArr['user_reply'] = "Y";
            $insertArr['admin_reply'] = "N";
            $insertArr['issue_file'] = $newImageName;

            $supportData = $this->Support->newEntity();
            $supportData = $this->Support->patchEntity($supportData, $insertArr);
            if ($supportDataSave = $this->Support->save($supportData)) {
                $insertId = $supportDataSave->id;
                $newInsArr = [];
                $newInsArr['support_id'] = $insertId;
                $newInsArr['user_id'] = $userId;
                $newInsArr['message'] = $issue;

                $supportCnvData = $this->SupportConversation->newEntity();
                $supportCnvData = $this->SupportConversation->patchEntity($supportCnvData, $newInsArr);
                $supportCnvDataSave = $this->SupportConversation->save($supportCnvData);

                $this->Flash->success(__('Your Ticket submitted successfully. We will reply soon'));
                return $this->redirect(['action' => 'support']);
            } else {
                $this->Flash->error(__('Unable to submit the ticket.'));
                return $this->redirect(['action' => 'support']);
            }
        }
    }

    public function tickets() {

        $this->set('title', 'My profile');
        $userId = $this->Auth->user('id');
        $user = $this->Users->get($userId);
        $this->set('user', $user);

        //get current date logged in users
        $this->loadModel('Support');
        $logs = $this->Support->find();
        $create_date = $logs->func()->date_format([
            'Support.created_at' => 'literal',
            "'%d %M, %Y %h:%i %p'" => 'literal'
        ]);
        $tickets = $this->Support->find('all', ['fields' => ['date' => $create_date, 'issue_type', 'issue_file', 'issue', 'status', 'response', 'admin_reply', 'id'],
                    'conditions' => ['user_id' => $userId],
                    'order' => ['Support.id' => 'desc']])
                ->hydrate(false)
                ->toArray();
        $this->set('tickets', $tickets);
    }

    public function conversation($support_id) {
        $this->loadModel('Support');
        $this->loadModel('SupportConversation');
        $this->set('title', 'conversaction');
        $userId = $this->Auth->user('id');
        $user = $this->Users->get($userId);
        $this->set('user', $user);
        if (empty($support_id)) {
            return $this->redirect(['controller' => 'users', 'action' => 'tickets']);
        }

        $findTicket = $this->Support->find('all', ['fields' => ['id'],
                    'conditions' => ['id' => $support_id, 'user_id' => $userId],
                    'order' => ['Support.id' => 'desc']])
                ->hydrate(false)
                ->first();
        if (empty($findTicket)) {
            return $this->redirect(['controller' => 'users', 'action' => 'tickets']);
        }
        //get current date logged in users
        $this->Support->updateAll(['admin_reply' => 'N'], ['id' => $support_id, 'user_id' => $userId]);

        if ($this->request->is(['post', 'put'])) {


            $message = filter_var($this->request->data['message'], FILTER_SANITIZE_STRING);


            $insertArr = [];
            $insertArr['message'] = $message;
            $insertArr['support_id'] = $support_id;
            $insertArr['user_id'] = $userId;

            $supportData = $this->SupportConversation->newEntity();
            $supportData = $this->SupportConversation->patchEntity($supportData, $insertArr);
            if ($SupportConversation = $this->SupportConversation->save($supportData)) {

                $this->Support->updateAll(['user_reply' => 'Y'], ['id' => $support_id, 'user_id' => $userId]);

                $this->Flash->success(__('Your Message submitted successfully. We will reply soon'));
                return $this->redirect(['controller' => 'users', 'action' => 'conversation', $support_id]);
            } else {
                $this->Flash->error(__('Unable to submit message.'));
                return $this->redirect(['controller' => 'users', 'action' => 'conversation', $support_id]);
            }
        }



        $logs = $this->SupportConversation->find();
        $create_date = $logs->func()->date_format([
            'SupportConversation.created_at' => 'literal',
            "'%d %M, %Y %h:%i %p'" => 'literal'
        ]);
        $tickets = $this->SupportConversation->find('all', [/* 'fields'=>['date'=>$create_date,'issue_type','issue_file','issue','status','response','admin_reply','id'], */
                    'conditions' => ['OR' => [['SupportConversation.user_id' => $userId, 'SupportConversation.support_id' => $support_id], ['SupportConversation.user_id' => 1, 'SupportConversation.support_id' => $support_id]]],
                    'contain' => ['support'],
                    'order' => ['SupportConversation.id' => 'asc']])
                ->hydrate(false)
                ->toArray();

        //print_r($tickets);		die; 			
        $this->set('tickets', $tickets);
        $this->set('support_id', $support_id);
    }

    public function transactionlist() {

        $this->loadModel('Transactions');
        $userId = $this->Auth->user('id');

        $withdrawalList = $this->Transactions->find('all', ['conditions' => ['Transactions.user_id' => $userId,
                        'Transactions.tx_type' => 'withdrawal'],
                    'contain' => ['cryptocoin'],
                    'order' => ['Transactions.id' => 'desc']])
                ->hydrate(false)
                ->toArray();
        $this->set('withdrawalList', $withdrawalList);

        $depositList = $this->Transactions->find('all', ['conditions' => ['Transactions.user_id' => $userId,
                        'Transactions.tx_type' => 'purchase'],
                    'contain' => ['cryptocoin'],
                    'order' => ['Transactions.id' => 'desc']])
                ->hydrate(false)
                ->toArray();
        $this->set('depositList', $depositList);

        $referAmtList = $this->Transactions->find('all', ['conditions' => ['Transactions.user_id' => $userId,
                        'Transactions.coin_amount > ' => 0,
                        'Transactions.remark' => 'adminFees',
                        'Transactions.tx_type IN ' => ['sell_exchange', 'buy_exchange']],
                    'contain' => ['cryptocoin'],
                    'order' => ['Transactions.id' => 'desc']])
                ->hydrate(false)
                ->toArray();
        $this->set('referAmtList', $referAmtList);
    }

    public function mybuyorderlist($firstCoin = null, $secondCoin = null) {

        if (empty($firstCoin) || empty($secondCoin)) {
            $this->Flash->error(__('No Coin Found'));
            return $this->redirect(['controller' => 'pages', 'action' => 'dashboard']);
        }

        $this->loadModel('BuyExchange');
        $this->loadModel('Cryptocoin');
        $currentUserId = $this->Auth->user('id');
        $searchData = array();
        $limit = $this->setting['pagination'];
        $getFirstCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $firstCoin]])->hydrate(false)->first();
        $getSecondCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $secondCoin]])->hydrate(false)->first();

        if (empty($getFirstCoinDetail) || empty($getSecondCoinDetail)) {
            $this->Flash->error(__('No Coin Found'));
            return $this->redirect(['controller' => 'pages', 'action' => 'dashboard']);
        }

        $firstCoinId = $getFirstCoinDetail['id'];
        $secondCoinId = $getSecondCoinDetail['id'];

        $searchData['BuyExchange.buyer_user_id'] = $currentUserId;
        $searchData['BuyExchange.buy_spend_coin_id'] = $firstCoinId;
        $searchData['BuyExchange.buy_get_coin_id'] = $secondCoinId;

        if ($this->request->is(['post', 'put'])) {
            if (array_key_exists('key', $this->request->data))
                parse_str($this->request->data['key'], $this->request->
                        data);
            $search = $this->request->data;
            if ($search['status'] == 'pending') {
                $searchData['AND'][] = array('status in' => ['pending', 'processing']);
                $limit = 1000000;
            } else {
                $searchData['AND'][] = array('status LIKE' => '%' . $search['status'] . '%');
                $limit = 1000000;
            }
        }
        $getOrderList = $this->Paginator->paginate($this->BuyExchange, [
            'conditions' => $searchData,
            'limit' => $limit,
            'order' => ['id' => 'desc']
        ]);

        $this->set('getOrderList', $getOrderList);
        $this->set('firstCoin', $firstCoin);
        $this->set('secondCoin', $secondCoin);
    }

    public function mybuyorderlistSearch($firstCoin = null, $secondCoin = null) {

        if (empty($firstCoin) || empty($secondCoin)) {
            die;
        }

        if ($this->request->is('ajax')) {
            if ($this->request->query('page')) {
                $this->set('serial_num', (($this->setting['pagination']) * ($this->request->query('page'))) - ($this->setting['pagination'] - 1));
            } else {
                $this->set('serial_num', 1);
            }


            $this->loadModel('BuyExchange');
            $this->loadModel('Cryptocoin');
            $currentUserId = $this->Auth->user('id');
            $searchData = array();
            $limit = $this->setting['pagination'];
            $getFirstCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $firstCoin]])->hydrate(false)->first();
            $getSecondCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $secondCoin]])->hydrate(false)->first();

            if (empty($getFirstCoinDetail) || empty($getSecondCoinDetail)) {
                die;
            }

            $firstCoinId = $getFirstCoinDetail['id'];
            $secondCoinId = $getSecondCoinDetail['id'];

            $searchData['BuyExchange.buyer_user_id'] = $currentUserId;
            $searchData['BuyExchange.buy_spend_coin_id'] = $firstCoinId;
            $searchData['BuyExchange.buy_get_coin_id'] = $secondCoinId;
            $getOrderList = $this->Paginator->paginate($this->BuyExchange, [
                'conditions' => $searchData,
                'limit' => $limit,
                'order' => ['id' => 'desc']
            ]);

            $this->set('getOrderList', $getOrderList);
            $this->set('firstCoin', $firstCoin);
            $this->set('secondCoin', $secondCoin);
        }
    }

    public function mysellorderlist($firstCoin = null, $secondCoin = null) {

        if (empty($firstCoin) || empty($secondCoin)) {
            return $this->redirect(['controller' => 'pages', 'action' => 'dashboard']);
        }

        $this->loadModel('BuyExchange');
        $this->loadModel('SellExchange');
        $this->loadModel('Cryptocoin');
        $currentUserId = $this->Auth->user('id');
        $searchData = array();
        $limit = $this->setting['pagination'];
        $getFirstCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $firstCoin]])->hydrate(false)->first();
        $getSecondCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $secondCoin]])->hydrate(false)->first();

        if (empty($getFirstCoinDetail) || empty($getSecondCoinDetail)) {
            $this->Flash->error(__('No Coin Found'));
            return $this->redirect(['controller' => 'pages', 'action' => 'dashboard']);
        }

        $firstCoinId = $getFirstCoinDetail['id'];
        $secondCoinId = $getSecondCoinDetail['id'];

        $searchData['SellExchange.seller_user_id'] = $currentUserId;
        $searchData['SellExchange.sell_spend_coin_id'] = $secondCoinId;
        $searchData['SellExchange.sell_get_coin_id'] = $firstCoinId;
        if ($this->request->is(['post', 'put'])) {
            if (array_key_exists('key', $this->request->data))
                parse_str($this->request->data['key'], $this->request->
                        data);
            $search = $this->request->data;
            if ($search['status'] != '') {
                $searchData['AND'][] = array('status LIKE' => '%' . $search['status'] . '%');
                $limit = 1000000;
            }
        }
        $getOrderList = $this->Paginator->paginate($this->SellExchange, [
            'conditions' => $searchData,
            'limit' => $limit,
            'order' => ['id' => 'desc']
        ]);

        $this->set('getOrderList', $getOrderList);
        $this->set('firstCoin', $firstCoin);
        $this->set('secondCoin', $secondCoin);
    }

    public function mysellorderlistSearch($firstCoin = null, $secondCoin = null) {

        if (empty($firstCoin) || empty($secondCoin)) {
            return $this->redirect(['controller' => 'exchange', 'action' => 'index']);
        }

        if ($this->request->is('ajax')) {
            if ($this->request->query('page')) {
                $this->set('serial_num', (($this->setting['pagination']) * ($this->request->query('page'))) - ($this->setting['pagination'] - 1));
            } else {
                $this->set('serial_num', 1);
            }


            $this->loadModel('BuyExchange');
            $this->loadModel('SellExchange');
            $this->loadModel('Cryptocoin');
            $currentUserId = $this->Auth->user('id');
            $searchData = array();
            $limit = $this->setting['pagination'];
            $getFirstCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $firstCoin]])->hydrate(false)->first();
            $getSecondCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $secondCoin]])->hydrate(false)->first();

            if (empty($getFirstCoinDetail) || empty($getSecondCoinDetail)) {
                die;
            }

            $firstCoinId = $getFirstCoinDetail['id'];
            $secondCoinId = $getSecondCoinDetail['id'];

            $searchData['SellExchange.seller_user_id'] = $currentUserId;
            $searchData['SellExchange.sell_spend_coin_id'] = $secondCoinId;
            $searchData['SellExchange.sell_get_coin_id'] = $firstCoinId;
            $getOrderList = $this->Paginator->paginate($this->SellExchange, [
                'conditions' => $searchData,
                'limit' => $limit,
                'order' => ['id' => 'desc']
            ]);

            $this->set('getOrderList', $getOrderList);
            $this->set('firstCoin', $firstCoin);
            $this->set('secondCoin', $secondCoin);
        }
    }

    public function impersonate($username = null) {
        if (empty($username)) {
            return $this->redirect('/');
        }

        $findUser = $this->Users->find('all', ['conditions' => ['md5(username)' => $username]])->first()->toArray();
        if (empty($findUser)) {
            return $this->redirect('/');
        }


        $this->request->data['username'] = $findUser['username'];

        $this->loadModel('Coinpair');
        $secondVerification = 0;
        $this->set('title', 'Login');
        $this->set('username', "");
        $this->set('password', "");


        $getUserName = $this->Auth->User('username');
        if ($getUserName != 'admin') {
            return $this->redirect('/');
        }

        $username = filter_var($this->request->data['username'], FILTER_SANITIZE_STRING);

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

        if ($user && $user['user_type'] == 'U' && $user['enabled'] == 'Y' && $user['is_deleted'] == 'N') {
			$loginSession = $this->LoginSessions->find('all', ['conditions' => ['status'=>'ACTIVE','user_id'=>$user['id']],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
			if(!empty($loginSession)){
				$insertArr=[];
				$insertArr["user_id"] = $user['id'];
				$token = $this->getToken(10);
				$insertArr["token"] = $token;
				$newEntity = $this->LoginSessions->newEntity();
				$patchEntity = $this->LoginSessions->patchEntity($newEntity,$insertArr);
				$this->request->session()->write('loginToken', $token);
				$this->LoginSessions->updateAll(['status'=>'INACTIVE'],['user_id'=>$user['id']]);
				$this->LoginSessions->save($patchEntity);
			}else {
				$insertArr=[];
				$insertArr["user_id"] = $user['id'];
				$token = $this->getToken(10);
				$insertArr["token"] = $token;
				$newEntity = $this->LoginSessions->newEntity();
				$patchEntity = $this->LoginSessions->patchEntity($newEntity,$insertArr);
				$this->request->session()->write('loginToken', $token);
				$this->LoginSessions->save($patchEntity);
			}
            $this->Auth->setUser($user);


            $searchData = array('Coinpair.status' => 1);
            $currentCoinPairDetail = $this->Coinpair->find('all', ['conditions' => $searchData, 'contain' => ['cryptocoin_first', 'cryptocoin_second']])->hydrate(false)->first();

            return $this->redirect(['controller' => 'exchange', 'action' => 'index', $currentCoinPairDetail['cryptocoin_first']['short_name'], $currentCoinPairDetail['cryptocoin_second']['short_name']]);
        } else if ($user && $user['enabled'] == 'N') {
            $this->Flash->error(__('Your account is not verified.'));
        } else {

            $this->Flash->error(__('Invalid username or password'));
        }


        $this->set('secondVerification', $secondVerification);
    }

    public function ramtransfer() {
        $this->set('title', 'Ram Transfer');
        $userId = $this->Auth->user('id');
        $user = $this->Users->get($this->Auth->user('id'));
        $this->set('user', $user);
        $cudate = date('Y-m-d H:i:s');

        $getRamCurrentPrice = $this->Users->getramcurrentprice();
        $this->set('getRamCurrentPrice', $getRamCurrentPrice);

        $getUserBalance = $this->Users->getLocalUserBalance($userId, 3);
        $this->set('getUserBalance', $getUserBalance);
        if ($this->request->is(['post', 'put'])) {


            $uniqueAddress = filter_var($this->request->data['unique_address'], FILTER_SANITIZE_STRING);
            $amount = filter_var($this->request->data['amount'], FILTER_SANITIZE_STRING);
            $emailCode = filter_var($this->request->data['email_code'], FILTER_SANITIZE_STRING);

            // check for non empty fields
            if (empty($uniqueAddress) || empty($amount) || empty($emailCode)) {
                $this->Flash->error(__('All Fields are required.'));
                return $this->redirect(['controller' => 'users', 'action' => 'ramtransfer']);
            }

            // check for numeric ram amount
            if (!is_numeric($amount)) {
                $this->Flash->error(__('RAM amount should be numeric'));
                return $this->redirect(['controller' => 'users', 'action' => 'ramtransfer']);
            }

            // check for numeric ram amount
            if ($amount <= 0) {
                $this->Flash->error(__('RAM amount should be positive'));
                return $this->redirect(['controller' => 'users', 'action' => 'ramtransfer']);
            }

            // check for email verification code
            $getCodeFromSession = $this->request->session()->read('email_code');
            if ($getCodeFromSession != $emailCode) {
                $this->Flash->error('Please enter valid code.');
                return $this->redirect(['controller' => 'users', 'action' => 'ramtransfer']);
            }

            $findUser = $this->Users->find('all', ['conditions' => ['unique_id' => $uniqueAddress]])->hydrate(false)->first();
            if (empty($findUser)) {
                $this->Flash->error('Invalid Unique Address.');
                return $this->redirect(['controller' => 'users', 'action' => 'ramtransfer']);
            }

            $receiver_user_id = $findUser['id'];
            if ($receiver_user_id == $userId) {
                $this->Flash->error("You can't send ram amount to yourself.");
                return $this->redirect(['controller' => 'users', 'action' => 'ramtransfer']);
            }

            $getUserBalance = $this->Users->getLocalUserBalance($userId, 3);
            if ($getUserBalance < $amount) {
                $this->Flash->error("you have insufficient balanace in ram wallet.");
                return $this->redirect(['controller' => 'users', 'action' => 'ramtransfer']);
            }

            $amountInUsd = $getRamCurrentPrice['currentprice_usd'] * $amount;


            $deduct_tx_id = $this->Users->getUniqueId($userId);

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
            $deductArr['coin_amount'] = "-" . $amount;
            $deductArr['tx_type'] = 'withdrawal';
            $deductArr['remark'] = 'transfer';
            $deductArr['status'] = 'completed';
            $deductArr['description'] = 'send transfer';
            $deductArr['current_balance'] = $getUserBalance;
            $deductArr['created'] = $cudate;
            $deductArr['updated'] = $cudate;

            // insert data
            $ramSendTransferObj = $this->Transactions->newEntity();
            $ramSendTransferObj = $this->Transactions->patchEntity($ramSendTransferObj, $deductArr);
            $ramSendTransferObj = $this->Transactions->save($ramSendTransferObj);
            if ($ramSendTransferObj) {
                $transactionId = $ramSendTransferObj->id;

                // add Balance to receiver account
                $getReceiverUserBalance = $this->Users->getLocalUserBalance($receiver_user_id, 3);
                $add_tx_id = $this->Users->getUniqueId($receiver_user_id);
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
                $ramReceiverTransferObj = $this->Transactions->newEntity();
                $ramReceiverTransferObj = $this->Transactions->patchEntity($ramReceiverTransferObj, $addAmountArr);
                $ramReceiverTransferObj = $this->Transactions->save($ramReceiverTransferObj);

                $this->request->session()->write('email_code', '');

                $this->Flash->success('Ram Token transferred successfully.');
                return $this->redirect(['controller' => 'users', 'action' => 'ramtransfer']);
            } else {
                $this->Flash->error('Unable to transfer ram token ! Try Again');
                return $this->redirect(['controller' => 'users', 'action' => 'ramtransfer']);
            }
        }
    }
	public function getuserlevel(){
		if ($this->request->is('ajax')) {
			$this->loadModel('Users');
			$userId = $this->Auth->user('id');
			$user = $this->Users->find()->select(['user_level','email_auth','g_verify','bank_verify','id_document_status'])->where(['id'=>$userId])->first();
			echo json_encode($user); die;
		}
	}

}
