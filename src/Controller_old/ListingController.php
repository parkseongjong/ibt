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

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */


class ListingController extends AppController
{ 
	public function beforeFilter(Event $event)
    {
		 parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['index']);
		$this->loadComponent('Csrf');
		//$this->loadComponent('Security');
    }
   
	
	public function index()
	{
		$this->viewBuilder()->layout(false);
		$this->loadModel('Support');
		$this->set('title','Support');
		$userId = $this->Auth->user('id');
		$user  = '';
		$this->set('user',$user);	
		$before_image = '';
		
		if ($this->request->is(['post','put'])) {
		  //print_r($this->request->data); die;
		   $captchaResp = $this->request->data['g-recaptcha-response'];

		   //$_site_key = '6Lfs8nYUAAAAAILsZdQk6Q7SIMT9OkixyO2NFNjW';
		   
		   $_secret_Key = '6Lfs8nYUAAAAAFUFMudJk_uZDk1iFEZX8GESEtNE';
           
			if (!empty( $captchaResp )) {
				
				$verifydata = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$_secret_Key.'&response='.$captchaResp);

				$response = json_decode( $verifydata );

				if ( $response->success == false ) {
					
					$this->Flash->error(__('You are a bot ! Go Away !'));
					return $this->redirect('/listing');
				
				} 
				
				
			}else if(empty( $captchaResp ))  {
				
				$this->Flash->error(__('please verify captcha.'));
				return $this->redirect('/listing');
			}
			
			$data['email'] = $this->request->data["email"];
			$data['nameCoin'] = $this->request->data["name_coin"];
			$data['tickerCoin'] = $this->request->data["ticker_coin"];
			$data['website'] = $this->request->data["website"];
			$data['gitub'] = $this->request->data["github"];
			$data['explorer'] = $this->request->data["explorer"];
			$data['bitcointalk'] = $this->request->data["bitcointalk"];
			$data['twitterTelegram'] = $this->request->data["twitter_telegram"];
			$data['select'] = $this->request->data["sel"];
			$data['ethCheck'] = "";
			$data['btcCheck'] = "";
			$data['ramCheck'] = "";
			$data['pairs'] = $this->request->data["eth"];
			
			if(!empty($this->request->data['eth'])){
			   
			   $data['ethCheck'] = $this->request->data["eth"];
			}
			
			if(!empty($this->request->data['eth'])){
			   
			   $data['btcCheck'] = $this->request->data["eth"];
			}
			
			if(!empty($this->request->data['eth'])){
			   
			   $data['ramCheck'] = $this->request->data["eth"];
			}
			
			if(empty($data['ethCheck']) && empty($data['btcCheck']) && empty($data['ramCheck'])) {
				$this->Flash->error("Select at least one pair");
				return $this->redirect('/listing');
			}
			
			$coin=$_POST["bitcointalk"];
			
			if($coin=="") {
			  $this->Flash->error("this is required");
			  return $this->redirect('/listing');				  
			}
			//$data['ramCheck'] = $usrDetail->unique_id;
			$email = new Email('default');
			$email->viewVars(['data'=>$data]);
			$email->from([$this->setting['email_from']])
				->to(array('pijush.sarkar@outlook.com','info@livecrypto.exchange'))
				->subject('Your request has submitted')
				->emailFormat('html')
				->template('listing')
				->send();
				
			
			
			$this->Flash->success("Your request has submitted");
			return $this->redirect('/listing');		
			die();
		}
	}
}
