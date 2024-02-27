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
namespace App\Controller\Api;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\Datasource\ConnectionManager;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class TestsController extends AppController
{
	
	public function beforeFilter(Event $event)
    {
		
		 $this->Auth->allow();
	}
	
	 
	
	public function notification()
	{  	
 		#API access key from Google API's Console
		define( 'API_ACCESS_KEY', 'AIzaSyBTPs7UE35Ip50wDr6x0bX0jaZQKK7eriI' );
		$registrationIds = 'db8hn6y6j3Q:APA91bE_Dh-nnlW9i9SMc7dzRubIXKPcLUxaQp44NrmJKnpFiLNp_wLtVFaa3_GUiM9NmM1VdVplnnL5JDPqB6G7u2zlStLeDVSjj-g75TOEVklvoLfjLZ9VJ6UV3KmhQR3VftQRkSRu';
		#prep the bundle
		 $msg = array
			  (
			'body' 	=> 'Body  Of Notification',
			'title'	=> 'Title Of Notification',
					'icon'	=> 'myicon',/*Default Icon*/
					'sound' => 'mySound'/*Default sound*/
			  );
		$fields = array
				(
					'to'		=> $registrationIds,
					'notification'	=> $msg
				);
		
		
		$headers = array
				(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
		#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		#Echo Result Of FireBase Server
		echo $result;die;
 		
 		
 		 
	}
	
 
	
}
