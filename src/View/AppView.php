<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;
use Cake\ORM\TableRegistry;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link http://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
		parent::initialize();
        $this->loadHelper('Conversion');
    }
	/* 관리자 로그 추가 함수 */
	public function add_system_log($log_level, $user_id, $action, $description){
		$this->IbtSystemLog = TableRegistry::get("IbtSystemLog");
		$admin_id = $this->request->session()->read('Auth.User.id');
		$url = $this->request->here();
		$user_ip = $this->get_client_ip();
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$created = date('Y-m-d H:i:s');
		$logArr = ['log_level'=>$log_level,'log_level'=>$log_level,'admin_id'=>$admin_id,'user_id'=>$user_id,'url'=>$url,'action'=>$action,'user_agent'=>$user_agent,'user_ip'=>$user_ip,'description'=>$description,'created'=>$created];

		$this->IbtSystemLog->addSystemLog($logArr);
	}
	/* 클라이언트 ip */
	public function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
	/* 로그 레벨별 coment */
	public function get_log_level($log_level){
		$return_value = '';
		switch ( $log_level ) {
			case 100:
				$return_value = 'debug';
				break;
			case 200:
				$return_value = 'info';
				break;
			case 250:
				$return_value = 'notice';
				break;
			case 300:
				$return_value = 'warning';
				break;
			case 400:
				$return_value = 'error';
				break;
			case 500:
				$return_value = 'critical';
				break;
			case 550:
				$return_value = 'alter';
				break;
			case 600:
				$return_value = 'emergency';
				break;
			default:
			$return_value = 'info';
		}
		return $return_value;
	}
	/* 로그 액션별 coment */
	public function get_log_action($action){
		$return_value = '';
		switch ( $action ) {
			case 0:
				$return_value = 'default';
				break;
			case 1:
				$return_value = 'search';
				break;
			case 2:
				$return_value = 'add';
				break;
			case 3:
				$return_value = 'edit';
				break;
			case 4:
				$return_value = 'delete';
				break;
			case 5:
				$return_value = 'download';
				break;
			default:
			$return_value = 'default';
		}
		return $return_value;
	}
	/* transfer type */
	public function get_transfer_type($type){
		$return_value = $type;
		if($type == 'bank_initial_withdraw'){
			$return_value = __('Wallet ← Main Account');
		}
		if($type == 'withdrawal'){
			$return_value =  __('Wallet ← Main Account');
		}
		if($type == 'transfer_to_trading_account'){
			$return_value =  __('Main Account → Trading Account');
		}
		if($type == 'transfer_from_trading_account'){
			$return_value =  __('Trading Account → Main Account');
		}
		if($type == 'loan_deposit'){
			$return_value =  __('Loan Deposit');
		}
		if($type == 'purchase'){
			$return_value =  __('Purchase');
		}
		if($type == 'bank_initial_deposit'){
			$return_value =  __('Wallet → Main Account');
		}
		if($type == 'cancel_deposit'){
			$return_value =  __('Deposit Cancelled');
		}
		if($type == 'bought_coupon_krw'){
			$return_value =  __('Bought Coupon');
		}
		if($type == 'coupon_transfer_to_trading'){
			$return_value =  __('Coupon → Trading Account');
		}
		if($type == 'deducted_coupon_krw'){
			$return_value =  __('Coupon ← Main Account');
		}
		if($type == 'investment_profits'){
			$return_value =  __('Investment Profits');
		}
		return $return_value;
	}

	public function get_transfer_remark($remark){
		$return_value = $remark;
		if($remark == 'bank_initial_withdraw'){
			$return_value = __('Wallet ← Main Account');
		}
		if($remark == 'bank_initial_deposit'){
			$return_value = __('Wallet → Main Account');
		}
		if($remark == 'airdrop reward'){
			$return_value = __('Wallet → Main Account');
		}
		if($remark == 'withdrawal airdrop reward'){
			$return_value = __('Wallet ← Main Account');
		}
		if($remark == 'bought_coupon_krw'){
			$return_value = __('Bought Coupon');
		}
		if($remark == 'coupon_transfer_to_trading'){
			$return_value = __('Coupon → Trading Account');
		}
		if($remark == 'deducted_coupon_krw'){
			$return_value = __('Coupon ← Main Account');
		}
		if($remark == 'investments_profits_krw'){
			$return_value = __('Investment Profits');
		}
		return $return_value;
	}

	public function get_transaction_description($description){
		$return_value = __(ucfirst($description));
		if($description == 'sell_button_click'){
			$return_value = __('Sell');
		} 
		if($description == 'buy_button_click'){
			$return_value = __('Buy');
		} 
		if($description == 'type is buy add spend amount for seller'){
			$return_value = __('Buy add spend amount for seller');
		}  
		if($description == 'type is sell add spend amount for seller'){
			$return_value = __('Sell add spend amount for seller');
		}  
		if($description == 'user deposited the initial amount'){
			$return_value = __('User deposited the initial amount');
		}  
		if($description == 'user requested for the withdrawal'){
			$return_value = __('User requested for the withdrawal');
		}
		return $return_value;
	}

	public function get_transaction_txtype($type){
		$return_value = $type;
		if($type == 'bank_initial_withdraw'){
			$return_value = __('Wallet ← Main Account');
		}
		if($type == 'withdrawal'){
			$return_value = __('Wallet ← Main Account');
		}
		if($type == 'purchase'){
			$return_value = __('Purchase');
		}
		if($type == 'bank_initial_deposit'){
			$return_value = __('Wallet → Main Account');
		}
		if($type == 'bought_coupon'){
			$return_value = __('Coupon → Trading Account');
		}
		if($type == 'sell_exchange'){
			$return_value = __('Sell');
		}
		if($type == 'buy_exchange'){
			$return_value = __('Buy');
		}
		return $return_value;
	}

	public function get_transaction_remark($remark){
		$return_value = $remark;
		if($remark == 'bank_initial_withdraw'){
			$return_value = __('Wallet ← Main Account');
		}
		if($remark == 'bank_initial_deposit'){
			$return_value = __('Wallet → Main Account');
		}
		if($remark == 'transfer_to_main_account'){
			$return_value = __('Trading Account → Main Account');
		}
		if($remark == 'transfer_from_main_account'){
			$return_value = __('Trading Account ← Main Account');
		}
		if($remark == 'bought_coupon'){
			$return_value = __('Bought Coupon');
		}
		if($remark == 'reserve_completed'){
			$return_value = __('Reserve Completed');
		}
		if($remark == 'adminTranferFees'){
			$return_value = __('Admin Transfer Fees');
		}
		if($remark == 'adminFees'){
			$return_value = __('Admin Fees');
		}
		if($remark == 'sell_exchange'){
			$return_value = __('Sell');
		}
		if($remark == 'buy_exchange'){
			$return_value = __('Buy');
		}
		if($remark == 'reserve for exchange'){
			$return_value = __('Exchange Reserve');
		}
		return $return_value;
	}
	/* 개인정보 마스킹 */
	public function masking($_type, $_data){
		$_data = str_replace('-','',$_data);
		$strlen = mb_strlen($_data, 'utf-8');
		$maskingValue = "";

		$useHyphen = "-";

		if($_type == 'N'){ // Name
			switch($strlen){
				case 0:
					$maskingValue = '';
					break;
				case 2:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'*';
					break;
				case 3:
					//$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'*'.mb_strcut($_data, 8, 11, "UTF-8");
					$maskingValue = mb_substr($_data, 0, 1)."*".mb_substr($_data, 2, 3);
					break;
				case 4:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'**'.mb_strcut($_data, 12, 15, "UTF-8");
					break;
				case 5:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'***'.mb_strcut($_data, 12, 15, "UTF-8");
					break;
				default:
					$maskingValue = mb_strcut($_data, 0, 3, "UTF-8").'****'.mb_strcut($_data, 8, $strlen, "UTF-8");
					break;
			}
		} else if($_type == 'P'){ // Phone
			switch($strlen){
				case 0:
					$maskingValue = '';
					break;
				case 10:
					$maskingValue = mb_substr($_data, 0, 2)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 6, 4);
					break;
				case 11:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, 4);
					break;
				default:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, $strlen);
					break;
			}
		} else if($_type == 'B') { // Bank Name
			switch($strlen){
				case 0:
					$maskingValue = '';
					break;
				case 8:
					$maskingValue = mb_substr($_data, 0, 2)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, 4);
					break;
				case 9:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}***{$useHyphen}".mb_substr($_data, 6, 4);
					break;
				case 10:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, 4);
					break;
				case 11:
					$maskingValue = mb_substr($_data, 0, 3)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 7, 4);
					break;
				case 12:
					$maskingValue = mb_substr($_data, 0, 4)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 8, 4);
					break;
				case 13:
					$maskingValue = mb_substr($_data, 0, 4)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 8, 5);
					break;
				case 14:
					$maskingValue = mb_substr($_data, 0, 4)."{$useHyphen}****{$useHyphen}".mb_substr($_data, 9, 10);
					break;
				case 15:
					$maskingValue = mb_substr($_data, 0, 5)."{$useHyphen}*****{$useHyphen}".mb_substr($_data, 9, 10);
					break;
				case 16:
					$maskingValue = mb_substr($_data, 0, 5)."{$useHyphen}*****{$useHyphen}".mb_substr($_data, 9, 10);
					break;
				default:
					$maskingValue = mb_substr($_data, 0, 5)."{$useHyphen}*****{$useHyphen}".mb_substr($_data, 9, $strlen);
					break;
			}
		} else if($_type == 'E') { // Email
			$email = explode('@',$_data)[0];
			if(empty(explode('@',$_data)[1])){
				return;
			}
			$email_strlen = mb_strlen($email, 'utf-8');
			switch($email_strlen){
				case 2:
					$maskingValue = mb_strcut($email, 0, 1, "UTF-8").'*';
					break;
				case 3:
					$maskingValue = mb_strcut($email, 0, 1, "UTF-8").'**';
					break;
				case 4:
					$maskingValue = mb_strcut($email, 0, 2, "UTF-8").'**';
					break;
				case 5:
					$maskingValue = mb_strcut($email, 0, 2, "UTF-8").'***';
					break;
				case 6:
					$maskingValue = mb_strcut($email, 0, 3, "UTF-8").'***';
					break;
				default:
					$maskingValue = mb_strcut($email, 0, 3, "UTF-8").'****'.mb_strcut($email, 8, $email_strlen, "UTF-8");
					break;
			}
			$maskingValue = $maskingValue. '@' .explode('@',$_data)[1];

		}
		return $maskingValue;
	}
	/* 복호화 */
	public static function Decrypt($str, $secret_key='secret key', $secret_iv='secret iv') {
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		return openssl_decrypt(base64_decode($str), "AES-256-CBC", $key, 0, $iv);
	}
	/* 암호화 */
	public static function Encrypt($str, $secret_key='secret key', $secret_iv='secret iv') {
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16)    ;
		return str_replace("=", "", base64_encode(openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv)));
	}
	/* 로그인 세션 쿠키를 암호화 하여 새 쿠키로 저장 - AppController에서 쿠키 변경 여부 확인 위해 만들어짐 */
	public function setSessionCookie(){
		$authUserId = $this->request->session()->read('Auth.User.id');
		$appSessionCookie = $this->request->cookie('app_session');
		$appSessionToken = $this->request->cookie('app_session_token');
		if(!empty($authUserId) && !empty($appSessionCookie) && empty($appSessionToken)){
			$user_ip = $this->get_client_ip();
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$str = $appSessionCookie.'^||^'.$user_ip.'^||^'.$user_agent;
			$token = $this->Encrypt($str);
			setcookie('app_session_token',$token,0,'/');
		}
		if(empty($authUserId)){
			if (isset($_COOKIE['app_session_token'])) {
				unset($_COOKIE['app_session_token']);
				setcookie('app_session_token', '', time() - 3600, '/');
			}
		}
	}
	public function check_ip(){
		$this->AdminAccessIp = TableRegistry::get("AdminAccessIp");
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
}
