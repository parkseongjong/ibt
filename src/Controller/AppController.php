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

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\Auth\DefaultPasswordHasher;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
		date_default_timezone_set('Asia/Seoul');
        $this->loadComponent('RequestHandler');
		$this->loadComponent('Paginator');
        $this->loadComponent('Flash');
        $this->loadModel('Settings');
        $this->loadModel('Transactions');
        $this->loadModel('ConversionRates');
        $this->loadModel('PermisionAccess');
        $this->loadModel('PermisionModules');
        $this->loadModel('Users');
        
		if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'tech') {
			$this->loadComponent('Auth', [
				'authorize' => ['Controller'],
				'loginRedirect' => [
					'controller' => 'pages',
					'action' => 'dashboard'
				],
				'logoutRedirect' => [
					'controller' => 'users',
					'action' => 'login',
				],
				'authenticate' => [
					'Form' => [
						'fields' => ['username' => 'username']
					]
				],
				
			]);
		}else if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'front') {
			$this->loadComponent('Auth', [
				'authorize' => ['Controller'],
				'loginRedirect' => [
					'controller' => 'pages',
					'action' => 'dashboard'
				],
				'logoutRedirect' => [
					'controller' => 'users',
					'action' => 'login',
				],
				'authenticate' => [
					'Form' => [
						'fields' => ['username' => 'username']
					]
				],
				
			]);
		}else if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'front2') {
			$this->loadComponent('Auth', [
				'authorize' => ['Controller'],
				'loginRedirect' => [
					'controller' => 'pages',
					'action' => 'dashboard'
				],
				'logoutRedirect' => [
					'controller' => 'users',
					'action' => 'login',
				],
				'authenticate' => [
					'Form' => [
						'fields' => ['username' => 'username']
					]
				],
				
			]);
		}else{
			$this->loadComponent('Auth', [
				'authorize' => ['Controller'],
				'loginRedirect' => [
					'controller' => 'pages',
					'action' => 'home'
				],
				'logoutRedirect' => [
					'controller' => 'pages',
					'action' => 'home',
				],
				'authenticate' => [
					'Form' => [
						'fields' => ['username' => 'username']
					]
				],
				
			]);
		}
    }
	
	public function isAuthorized($user)
	{
		
		if (empty($this->request->params['prefix'])) {
            return true;
        }
        
         if ($this->request->params['prefix'] === 'tech' && isset($user['user_type']) && $user['user_type'] == 'A') 
         {
			 return true;
			/* if($this->Auth->user('id') ==1){
				
			}else{	
				$module = $this->request->params['controller'];
				if($module == 'Pages' || $module == 'Users' ||  $module == 'Transactions'){
					$is_allow =1;
				}else{
					$is_allow =1;
					$module_id = $this->PermisionAccess->find()
						->select(['id'])
						->contain(['PermisionModules'])
						->where(['PermisionAccess.user_id'=>$this->Auth->user('id'),'PermisionModules.module_name' => $module])->hydrate(false)->first();
					if(empty($module_id)) $is_allow =0;
					
				}
				if($is_allow == 1){
					return true;
				}else{				
					
					if($this->request->is(['ajax'])) { echo "forbidden";die;}					
					return $this->redirect(['controller'=>'Pages','action' => 'forbidden']);
				}
				
			} */
			
		}
		if ($this->request->params['prefix'] === 'front' && isset($user['user_type']) && ($user['user_type'] == 'U' || $user['user_type'] == 'A')) {
			return true;
		}
		if ($this->request->params['prefix'] === 'front2' && isset($user['user_type']) && ($user['user_type'] == 'U' || $user['user_type'] == 'A')) {
			return true;
		}

   
		return $this->redirect($this->Auth->logout());
	}
	
    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
		$onMaintainance = 1;
	
		if($onMaintainance==0){
			 $this->viewBuilder()->layout('maintenance');
			//$this->layout = 'maintenance';
            $this->set('title_for_layout', __('Site_down_for_maintenance_title'));
		}
		
		if($this->Auth->user('id'))
		{
			$this->set('authUser',$this->Auth->user());
			$this->set('setting',$this->setting);
			$this->set('coin_arr',$this->coin_arr);
		}
		
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
		
		if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'tech') {
			
			
			// Permission
			if($this->Auth->user('id') == 1){
				 $this->set('accessModulesRecords',array('all'));
			}else{
				$query = $this->PermisionAccess->find('all',array('fields'=>array('module'=>'PermisionModules.module_name'),'conditions'=>['user_id'=>$this->Auth->user('id')],'contain'=>'PermisionModules'))->hydrate(false)->toArray();
				$this->set('accessModulesRecords',array_column($query,'module'));
			}
				
			if(isset($this->request->params['action'])  && $this->request->params['action']== 'login'){
				$this->viewBuilder()->layout('login');
			}else if(isset($this->request->params['action'])  && $this->request->params['action']== 'forbidden'){
				$this->viewBuilder()->layout('403');
			}else{
				if($this->request->is('ajax')){
					$this->viewBuilder()->layout('ajax');
				}else{
					 $this->loadModel('LevelPages');
					 $left_bars = $this->LevelPages->find()
						 ->select(['icon_class','level_id','treeview_cnt','treeview','treeview_name','treeview_sort','url','menu_name','sort_no','treeview_icon_class1','treeview_icon_class2'])
						 ->where(['status'=>'Y'])->order(['sort_no'=> 'ASC','treeview_sort'=>'asc'])->all();
					 $this->set('left_bars',$left_bars);
					 $this->viewBuilder()->layout('admin');
				}
				
			}
		}else if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'front') {
			
			if(isset($this->request->params['action'])  && ($this->request->params['action']== 'login' || $this->request->params['action']== 'register' || $this->request->params['action']== 'successregister' || $this->request->params['action']== 'forgetPassword' || $this->request->params['action']== 'verify')){
                if(isset($this->request->params['controller']) && (
                    $this->request->params['controller']=='Customer' ||
                    $this->request->params['controller']=='Document' ||
                    $this->request->params['controller']=='ExchangeNew' ||
                    $this->request->params['controller']=='UsersNew')) {
                    $this->viewBuilder()->layout('front_new');
                }else{
				    $this->viewBuilder()->layout('login');
                }
			}else if(isset($this->request->params['action'])  && $this->request->params['action']== 'forbidden'){
				$this->viewBuilder()->layout('403');
			}else{
				if($this->request->is('ajax')){
					$this->viewBuilder()->layout('ajax');
				}else{
                    if(isset($this->request->params['controller']) && (
                        $this->request->params['controller']=='Customer' ||
                        $this->request->params['controller']=='Document' ||
                        $this->request->params['controller']=='ExchangeNew' ||
                        $this->request->params['controller']=='UsersNew')) {
                        $this->viewBuilder()->layout('front_new');
                    }else{
					    $this->viewBuilder()->layout('front');
                    }
				}
			}
		}else if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'front2') {
			
			if(isset($this->request->params['action'])  && ($this->request->params['action']== 'login' || $this->request->params['action']== 'register' || $this->request->params['action']== 'successregister' || $this->request->params['action']== 'forgetPassword' || $this->request->params['action']== 'verify')){
                //$this->viewBuilder()->layout('login');
                $this->viewBuilder()->layout('front2');
			}else if(isset($this->request->params['action'])  && $this->request->params['action']== 'forbidden'){
				$this->viewBuilder()->layout('403');
			}else{
				if($this->request->is('ajax')){
					$this->viewBuilder()->layout('ajax');
				}else{
                    $this->viewBuilder()->layout('front2');
				}
			}

         
        }

		if($onMaintainance==0){
			$this->viewBuilder()->layout('maintenance');
            $this->set('title_for_layout', __('Site_down_for_maintenance_title'));
		}
        $_COOKIE['Language'] = 'ko_KR';
		//$lang = isset($_COOKIE['Language']) ? $_COOKIE['Language'] : 'en_US';
        $lang = isset($_COOKIE['Language']) ? $_COOKIE['Language'] : 'ko_KR';

        I18n::locale($lang);
		
    }
    
    public function beforeFilter(Event $event)
    {
		$this->sessionCookieCheck();
		if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'front2') {
			$this->login_session_check();
		}
		$query = $this->Settings->find()->select(['module_name','value'])->hydrate(false)->toArray();
		$this->setting = array_column($query, 'value','module_name');
		$this->coin_arr = array('ZUO'=>'Z','BTC'=>'B');
		$this->pagination_limit=$this->setting['pagination'];
		$this->Auth->allow(['home','login','logout','register','forgetPassword']);
		
		$authUserId = $this->Auth->user('id');
		$secondVerification = "N";
		$this->loadModel('Users');
		if(!empty($authUserId)){
			$getCurrentUser = $this->Users->get($authUserId);
			$secondVerification = $getCurrentUser->second_verification;
		}
		$this->set('secondVerification',$secondVerification);
		/* admin level permission */
		if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'tech') {
			$this->set('coinNameStatic', "HansBlock");
			$user = $this->Auth->user();
			if(!empty($user)){
				$this->loadModel('LevelPages');
				if(!empty($user['level_id']) && $user['level_id'] != null && $user['user_type'] == 'A'){
					$controller_name = $this->camel_to_snake($this->request->params['controller']);
					$function_name = $this->camel_to_snake($this->request->params['action']);
					// if($controller_name != 'users' && $function_name != 'login'){
					// 	if($this->check_ip() == 'fail'){
					// 		$this->Auth->logout();
					// 		$this->Flash->error('접속 허용된 IP가 아닙니다');
					// 		return $this->redirect(['controller'=>'Users','action' => 'login']);
					// 	}
					// }
					if($user['level_id'] > 1 ){
						if($controller_name != 'pages' && $function_name != 'dashboard'){
							$levels = $this->LevelPages->find()->select(['level_id','url','menu_name'])->where(['url LIKE'=>'%/'.$controller_name.'%'])->all();
							$allowArr = array();
							$allArr = array();
							foreach($levels as $l){
								$url = explode('/',$l->url);
								if(end($url) == $controller_name){
									if($controller_name != 'settings'){
										$last_url = 'index';
									} else {
										$last_url = end($url);
									}
								} else {
									$last_url = end($url);
								}
								if($l->level_id >= $user['level_id']){
									$allowArr[] = $this->camel_to_snake($last_url);
								}
								$allArr[] = $this->camel_to_snake($last_url);
							}
							
							if (!in_array($function_name, $allowArr) && in_array($function_name, $allArr)) {
								return $this->redirect(['controller'=>'Pages','action' => 'dashboard']);
							}
						}
					}
				}
			}
		}
    }
	public function check_ip(){
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
	public function camel_to_snake($input){
		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
		$ret = $matches[0];
		foreach ($ret as &$match) {
			$match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
		}
		return implode('-', $ret);
	}

	public function snake_to_camel($string, $capitalizeFirstCharacter = false){
		$str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
		if (!$capitalizeFirstCharacter) {
			$str[0] = strtolower($str[0]);
		}
		return $str;
	}

	public function login_session_check(){
		$this->set('ServerCheckValue', "N");
		$this->set('ServerCheckMsg', "");
		$authUserId = $this->Auth->user('id');
		$authUserType = $this->Auth->user('user_type');
		if(!empty($authUserId)){
			if($authUserType != 'A'){
				$this->loadModel('LoginSessions');
				$loginSession = $this->LoginSessions->find('all', ['conditions' => ['status'=>'ACTIVE','user_id'=>$authUserId]])->hydrate(false)->count();
				if($loginSession < 1){
					$this->Auth->logout();
					$this->request->session()->destroy();
				}
			}
        }
		$this->loadModel('ServerCheck');
		$server_check = $this->ServerCheck->find()->select(['status','message'])->where(['status'=>'Y'])->first();
		if(!empty($server_check)){
			$this->set('ServerCheckValue', $server_check->status);
			$this->set('ServerCheckMsg', $server_check->message);
			if($this->check_ip() == 'fail'){ //112.171.120.140
				$this->Auth->logout();
				$this->request->session()->destroy();
			}
		}
	}
    
    public function tofloat($num) {
		$dotPos = strrpos($num, '.');
		
		$commaPos = strrpos($num, ',');
		$sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : 
			((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
	    
		if (!$sep) {
			return floatval(preg_replace("/[^0-9]/", "", $num));
		} 

		return floatval(
			preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
			preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
		);
	}
	
	public function getgalaxyfrombtcConvert($amount,$is_convert=1)
    {
		
		$rate = $this->bitInGalaxy();
	
		if($rate =='' ) return array('success'=>0);
		else{
			
			$amount=(float)$amount;
			
			if($is_convert==0) $new_galaxy = $amount;
			else $new_galaxy = $amount / $rate['rate'];
			$new_galaxy =(float)$new_galaxy;
			
			if($rate['left_coins']>=$new_galaxy) return array('success'=>1,'rate'=>number_format($rate['rate'],8),'left'=>$rate['left_coins'],'amount'=>$new_galaxy,'conversion_rate_id'=>$rate['id']);
			else return array('success'=>0,'left'=>$rate['left_coins']);
			
		}
	}
	public function BTC_INR()
    {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://blockchain.info/ticker');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$contents = curl_exec($ch);
		$arr = json_decode($contents);
		return $arr->INR->buy;
		die;
		
		  
	}
	
	
	public function checkUserAmount($user_id,$type){
		$data = $this->Users->find('all',['fields'=>['BTC','ZUO'],'conditions'=>['id'=>$this->Auth->user('id')]])->hydrate(false)->first();
		if($type=='ZUO') $val =  number_format($data['ZUO'],8);
		else $val =  number_format($data['BTC'],8) ;
		return (float)$val;
	}
	
	public function bitInGalaxy(){
		$current_bitInGalaxy = $this->ConversionRates->find('all',array(		
		'conditions'=>array('from_date <='=>date('Y-m-d'),'to_date >='=>date('Y-m-d'))
		))->hydrate(false)->last();
		
		if(!empty($current_bitInGalaxy)){
			$current_bitInGalaxy['rate'] = (float)$current_bitInGalaxy['rate'];
			$current_bitInGalaxy['left_coins'] = (float)$current_bitInGalaxy['left_coins'];
			$current_bitInGalaxy['total_coins'] = (float)$current_bitInGalaxy['total_coins'];
			return $current_bitInGalaxy;
		}else return '';
	}
	
	
    public function updateUserWallet($user_id,$coin_type,$opr_type,$amount){
	
		$this->loadModel('Users');
		$row  = $this->Users->get($user_id);
		
		if($opr_type=='credit') $row->$coin_type = $row->$coin_type + $amount;
		else  $row->$coin_type = $row->$coin_type - $amount;
		$this->Users->save($row);
		
		
	}
	
	public function referralTransactionEntry($user_id)
	{
		
		$isthere = $this->Transactions->find()->select(['id'])->where(['user_id'=>$user_id,'from_user_id'=>$user_id,'trans_type'=>'R'])->hydrate(false)->first();
		if(count($isthere) > 1) return 0;
		else return 1;
	}
	
	public function getReferralUser($user_id)
	{
		$refer_user = $this->Users->find()->select(['refer_user_idd'=>'referral_user.id'])->contain(['referral_user'])->where(['Users.id'=>$user_id])->hydrate(false)->first();
		return $refer_user['refer_user_idd'];
	}

	public function updateAdminWallet($id,$amount)
    {
	
		 $sett = $this->ConversionRates->get($id);
		 $sett->left_coins = $sett->left_coins - $amount;
		 $this->ConversionRates->save($sett);
	}
	
    public function getNewReferralCode($leng = 6) 
	{
		$alphabet ='3456789abcdef451897ghijklmnABCDEFopqJKLDYRERTWrstuTHYRSHJKIGFvwxyzABCDEFGHI9876543JKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < $leng; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		$referral_code =  implode($pass); //turn the array into a string
		
		$this->loadModel('User');
		$is_exist = $this->Users->find('all',array(
		'conditions'=>array('referral_code'=>$referral_code),
		'fields'=>array('id')
		))->hydrate(false)->first();
		if($is_exist)
		{
			$this->getNewReferralCode();
		}
		else
		{
			return $referral_code;
		}
		
	}
	
	public function getUniqueId($leng = 30) 
	{
		$alphabet ='3456789abcdef451897ghijklmnABCDEFopqJKLDYRERTWrstuTHYRSHJKIGFvwxyzABCDEFGHI9876543JKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < $leng; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		$referral_code =  implode($pass); //turn the array into a string
		$this->loadModel('User');
		$is_exist = $this->Users->find('all',array(
		'conditions'=>array('unique_id'=>$referral_code),
		'fields'=>array('id')
		))->hydrate(false)->first();
		if($is_exist)
		{
			$this->getNewReferralCode();
		}
		else
		{
			return $referral_code;
		}
		
	}
	/***********************************************************************************
	 * Upload  only image
	 * *********************************************************************************/
	 
	 public function uploadImage($pfile,$ptfile,$directory,$filename) {  
		$fileTemp = $pfile;
        $image_name = $filename;        
        $imageType = $ptfile;
        $allowed = array('image/jpeg', 'image/png', 'image/gif', 'image/JPG', 'image/jpg', 'image/pjpeg','text/csv','application/csv');
        //To check if the file are image file
        if (!in_array($imageType, $allowed)) {
            return false;
        } else { 
		if (!file_exists($directory)){
			mkdir($directory, 0777, true);
			$directory=$directory;
		}
	    	if(move_uploaded_file($fileTemp,WWW_ROOT.$directory."/".basename($image_name))) {
	        	return true;
	    	}
	    	else {
	        	return false;
	    	}
         }
     }
	
	/***********************************************************************************
	 * Create Thumbnail only image
	 * *********************************************************************************/
	 public function createThumbnail($filename,$imageDirName, $dirName, $nx, $ny) {
		 error_reporting(0);
		  $path_to_thumbs_directory = WWW_ROOT . $dirName . "/";
        
        $path_to_image_directory = WWW_ROOT .  $imageDirName. "/";
        $filename = $filename;
		$final_width_of_image = 193;
        if (preg_match('/[.](jpg)$/', $filename)) {
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);
        } else if (preg_match('/[.](jpeg)$/', $filename)) {
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);
        } else if (preg_match('/[.](gif)$/', $filename)) {
            $im = imagecreatefromgif($path_to_image_directory . $filename);
        } else if (preg_match('/[.](png)$/', $filename)) {
            $im = imagecreatefrompng($path_to_image_directory . $filename);
        } else if (preg_match('/[.](JPG)$/', $filename)) {
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);
        }else if (preg_match('/[.](mp4)$/', $filename)) {
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);
        }
		if(!$im){return false;}
		
        $ox = imagesx($im);
        $oy = imagesy($im);
        $nm = imagecreatetruecolor($nx, $ny);
        imagesavealpha($nm, true);
        $trans_colour = imagecolorallocatealpha($nm, 0, 0, 0, 127);
        imagefill($nm, 0, 0, $trans_colour);
        imagecopyresampled($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);
        if (!file_exists($path_to_thumbs_directory)) {
            if (!mkdir($path_to_thumbs_directory)) {
                return false;
            }
        }
		if (preg_match('/[.](jpg)$/', $filename)) {
            imagejpeg($nm, $path_to_thumbs_directory . $filename);
        } else if (preg_match('/[.](jpeg)$/', $filename)) {
            imagejpeg($nm, $path_to_thumbs_directory . $filename);
        } else if (preg_match('/[.](gif)$/', $filename)) {
            imagegif($nm, $path_to_thumbs_directory . $filename);
        } else if (preg_match('/[.](png)$/', $filename)) {
            imagepng($nm, $path_to_thumbs_directory . $filename);
        } else if (preg_match('/[.](JPG)$/', $filename)) {
            imagejpeg($nm, $path_to_thumbs_directory . $filename);
        } else if (preg_match('/[.](mp4)$/', $filename)) {
            imagejpeg($nm, $path_to_thumbs_directory . $filename);
        }
		return true;
    }

    function get_client_ip() {
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
	
	
	public function coinConvert($coinAmount,$convertInto){
		$this->loadModel('Token');
		$totalAMXCoin = $this->Token->find('all')->hydrate(false)->first();
		$btcValInOneAgc = $totalAMXCoin['btc_value'];
		if($convertInto=="amaxgold"){
			$returnType = $coinAmount/$btcValInOneAgc;
		}
		else {
			$returnType = $coinAmount*$btcValInOneAgc;
		}
		return $returnType;
	}
	
	public function btcBasUrl(){
		$url  ="http://localhost:3000/merchant/8346cb72-2b3a-4ca1-9b5b-85c572ea6154/";
		return $url;
	}
	/* 관리자 로그 추가 */
	public function add_system_log($log_level, $user_id, $action, $description){
		$this->loadModel('IbtSystemLog');
		$admin_id = $this->Auth->user('id');
		$url = $this->request->here();
		$user_ip = $this->get_client_ip();
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$created = date('Y-m-d H:i:s');
		$logArr = ['log_level'=>$log_level,'log_level'=>$log_level,'admin_id'=>$admin_id,'user_id'=>$user_id,'url'=>$url,'action'=>$action,'user_agent'=>$user_agent,'user_ip'=>$user_ip,'description'=>$description,'created'=>$created];

		$this->IbtSystemLog->addSystemLog($logArr);
	}
	/* 코인 리스트 */
	public function get_coin_list(){
		$this->loadModel('Cryptocoin');
		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id', 'valueField' => 'short_name'],['conditions'=>['id !='=>1]])->toArray();
		return $coinList;
	}
	/* 암호화 */
	public static function Encrypt($str, $secret_key='secret key', $secret_iv='secret iv') {
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16)    ;
		return str_replace("=", "", base64_encode(openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv)));
	}
	/* 복호화 */
	public static function Decrypt($str, $secret_key='secret key', $secret_iv='secret iv') {
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		return openssl_decrypt(base64_decode($str), "AES-256-CBC", $key, 0, $iv);
	}
	/* 비밀번호 정규식 */
	public function validationPassword($user_id, $type, $old_pw, $new_pw, $cofirm_pw) {
		$respArr = [];
		if($type == 'include_old_password'){
			$user = $this->Users->get($user_id);
			if ($user) {
				if (!(new DefaultPasswordHasher)->check($old_pw, $user->password)) {
					$respArr = ['status'=>'fail','message'=>__("The current password is not same")];
					return $respArr;
				}
			}
			if($old_pw == $new_pw){
				$respArr = ['status'=>'fail','message'=>__("It's the same as the old password")];
				return $respArr;
			}
		}
		$pw = $new_pw;
		$num = preg_match('/[0-9]/u', $pw);
		$eng = preg_match('/[a-zA-Z]/u', $pw);
		$spe = preg_match("/[\!\@\#\$\%\^\&\*]/u",$pw);
	 
		if(strlen($pw) < 8 || strlen($pw) > 20) {
			$respArr = ['status'=>'fail','message'=>__("Please enter a minimum of 8 digits to a maximum of 20 digits")];
			return $respArr;
		}
		if(preg_match("/\s/u", $pw) == true) {
			$respArr = ['status'=>'fail','message'=>__("Please enter the password without spaces")];
			return $respArr;
		}
		if(strlen($pw) >= 8 && strlen($pw) < 10) {
			if( $num == 0 || $eng == 0 || $spe == 0) {
				$respArr = ['status'=>'fail','message'=>__("You can enter a mixture of English, numbers, and special characters with less than 10 digits")];
				return $respArr;
			}
		}
		if(strlen($pw) >= 10){
			if( ($num == 0 && $eng == 0) || ($num == 0 && $spe == 0) || ( $eng == 0 && $spe == 0 ) ) {
				$respArr = ['status'=>'fail','message'=>__("Please enter at least 10 digits mixed with English, numbers, and special characters")];
				return $respArr;
			}
		}
		if($new_pw != $cofirm_pw){
			$respArr = ['status'=>'fail','message'=>__("The new password and password verification do not match")];
			return $respArr;
		}
		$respArr = ['status'=>'success','message'=>''];
		return $respArr;
	}
	/* 개인정보 마스킹 */
	public function masking($_type, $_data){
		$_data = str_replace('-','',$_data);
		$strlen = mb_strlen($_data, 'utf-8');
		$maskingValue = "";

		$useHyphen = "-";

		if($_type == 'N'){ // Name
			switch($strlen){
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
					$maskingValue = mb_substr($_data, 0, 2)."****".mb_substr($_data, 6, 4);
					break;
				case 11:
					$maskingValue = mb_substr($_data, 0, 3)."****".mb_substr($_data, 7, 4);
					break;
				default:
					$maskingValue = mb_substr($_data, 0, 3)."****".mb_substr($_data, 7, $strlen);
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
			$email_strlen = mb_strlen($email, 'utf-8');
			switch($email_strlen){
				case 0:
					$maskingValue = '';
					break;
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
	public function permissionlevelcheck($type){
		$status = 'fail';
		if($type == 'download'){
			$auth_level = $this->Auth->user('level_id');
			if($auth_level <= 2){
				$status = 'success';
			}
		}
		return $status;
	}
	/* CoolSMS 문자 전송 */
	public function sendCoolSms($data = array()){
		if(empty($data) || empty($data['to']) || empty($data['text'])){
			return '메세지 전송 실패 (필수 값 미입력)';
		}
		if(empty($data['country'])){
			$data['country'] = 82;
		}
		$data['from'] ='0234893237';
		$formData = $this->makeFormData($data);
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.coolsms.co.kr/sms/2/send",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS =>$formData,
			CURLOPT_HTTPHEADER => array(
				//"Content-Type: application/json"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			return  "cURL Error #: " . $err;
		} else {
			return $response;
		}
	}
	/* CoolSMS 문자 전송 시 Form Data 생성 */
	public function makeFormData($data = array()){
		$apiSignature = $this->makeApiSignature();
		$returnArr = $apiSignature + $data;
		return http_build_query($returnArr);
	}
	/* CoolSMS 문자 연동 시 API 시그니처 생성 */
	public function makeApiSignature(){
		/*$api_secret = 'UDEOTKFFNSUGOBGLKY3VRVMXUMO3HQVA';*/
        $api_secret = '8XLKTCBYGZNOHFBVNT5ZP5HH3OPFIA5V';
		$timestamp = time();
		$salt = uniqid();
		$hmac_data = $timestamp.$salt;
		$signature = hash_hmac('md5', $hmac_data, $api_secret);

		$returnArr = [];
		/*$returnArr['api_key'] = 'NCS9WK274ENW6UER';*/
        $returnArr['api_key'] = 'NCS0KG2HDFM4JEWM';
		$returnArr['timestamp'] = $timestamp;
		$returnArr['salt'] = $salt;
		$returnArr['signature'] = $signature;
		return $returnArr;
	}
	/* 로그인 세션 및 쿠키 변조 확인 -> 비교하는 appSessionTokenCookie 은 AppView.php 에서 생성, loginSession 은 UserController 로그인 시 */
	public function sessionCookieCheck(){
		$authUserId = $this->Auth->user('id');
		if(!empty($authUserId)){
			$this->loadModel('LoginSessions');
			$loginSession = $this->LoginSessions->find('all', ['conditions' => ['status'=>'ACTIVE','user_id'=>$authUserId],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
			$tokenSession = $this->request->session()->read('loginToken');
			$appSessionCookie = $this->request->cookie('app_session');
			$appSessionTokenCookie = $this->Decrypt($this->request->cookie('app_session_token'));
			if(!empty($loginSession) && !empty($tokenSession)){
				if($tokenSession != $loginSession['token']){
					echo '<script>window.onload = function(){alert("다른 기기에서 접속되어 로그아웃 됩니다.");window.location.href="/front2/users/logout"}</script>';
				}
			}
			if(!empty($appSessionCookie) && !empty($appSessionTokenCookie)){
				$cookieArr = explode('^||^',$appSessionTokenCookie);
				$user_ip = $this->get_client_ip();
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				//$key = 'Q1w2E3r4!@';
				if($appSessionCookie != $cookieArr[0] || $user_ip != $cookieArr[1] || $user_agent != $cookieArr[2]){ // || $key != $cookieArr[3]
					//echo '<script>window.onload = function(){alert("위변조가 감지되어 로그아웃 됩니다.");window.location.href="/front2/users/logout"}</script>';
				}
			}
        }
	}
}
