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
		date_default_timezone_set('Asia/Kolkata');
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
			if($this->Auth->user('id') ==1){
				return true;
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
				
			}
			
		}
		if ($this->request->params['prefix'] === 'front' && isset($user['user_type']) && ($user['user_type'] == 'U' || $user['user_type'] == 'A')) {
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
		/* $get = $_SERVER['REQUEST_URI'];
		$exp = explode("/",$get);
		if(in_array("tech",$exp)){
			$onMaintainance = 1;
		} */
		
		/*
		 $myip = $this->get_client_ip();
		$myip = explode(",",$myip);
		
		if(in_array('122.176.83.150',$myip)) { 
			$onMaintainance = 1;
		} */
	/*	if(in_array('42.110.156.185',$myip)) { 
			$onMaintainance = 0;
		}
		if(in_array('2405:204:a02c:ceb2:1805:fba9:2120:4f7b',$myip)) { 
			$onMaintainance = 1;
		}
	
                if(in_array('115.248.132.50',$myip)) {
                        $onMaintainance = 1;
                }
				 */
				
	
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
					 $this->viewBuilder()->layout('admin');
				}
				
			}
		}else if (isset($this->request->params['prefix'])  && $this->request->params['prefix']== 'front') {
			
			if(isset($this->request->params['action'])  && ($this->request->params['action']== 'login' || $this->request->params['action']== 'register' || $this->request->params['action']== 'successregister' || $this->request->params['action']== 'forgetPassword' || $this->request->params['action']== 'verify')){
				$this->viewBuilder()->layout('login');
			}else if(isset($this->request->params['action'])  && $this->request->params['action']== 'forbidden'){
				$this->viewBuilder()->layout('403');
			}else{
				if($this->request->is('ajax')){
					$this->viewBuilder()->layout('ajax');
				}else{
					$this->viewBuilder()->layout('front');
				}
				
			}
		
		}
		
		if($onMaintainance==0){
			 $this->viewBuilder()->layout('maintenance');
			//$this->layout = 'maintenance';
            $this->set('title_for_layout', __('Site_down_for_maintenance_title'));
		}
		
    }
    
    public function beforeFilter(Event $event)
    {
		
		
		
		$query = $this->Settings->find()
		->select(['module_name','value'])->hydrate(false)->toArray();
		$this->setting = array_column($query, 'value','module_name');
		$this->coin_arr = array('ZUO'=>'Z','BTC'=>'B');
		$this->pagination_limit=$this->setting['pagination'];
		$this->Auth->allow(['home','login','logout','register','forgetPassword']);
		
		$this->set('bitToGalaxy',$this->bitInGalaxy());
		// my custom
		$this->set('coinNameStatic', "Globex");
		
		$authUserId = $this->Auth->user('id');
		$secondVerification = "N";
		$this->loadModel('Users');
		if(!empty($authUserId)){
			$getCurrentUser = $this->Users->get($authUserId);
			$secondVerification = $getCurrentUser->second_verification;
		}
		$this->set('secondVerification',$secondVerification);
		
		
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
}
