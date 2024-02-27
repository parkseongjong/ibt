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

namespace App\Controller\Admin;


use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;

class TokenController extends AppController
{
	public function index()
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('Token');
		$conversion = $this->Token->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			$cudate = date("Y-m-d H:i:s");
			$this->request->data['modified'] = $cudate;
			if($this->request->data['id']==''){ 
				$this->request->data['created'] = $cudate;			
				$conversion = $this->Token->newEntity();
			}
			else { 
				$conversion = $this->Token->get($this->request->data['id']);
			}
			
			$conversion = $this->Token->patchEntity($conversion, $this->request->data); 
			if($this->Token->save($conversion)){
				$this->Flash->success(__('Add successfully.','conversion'));
				return $this->redirect(['controller'=>'Token','action'=>'index']);
			}else{
				foreach($conversion->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						$this->Flash->error(__($error_text,'conversion'));
					} 
				}
			}
			
			
		}
		$searchData =array();
        $settings = $this->Token->find('all')->toArray();
        $this->set('listing',$this->Paginator->paginate($this->Token, [
			'conditions'=>$searchData,
			'order'=>['Token.id'=>'desc'],
			'limit' => $this->setting['pagination'],
		]));
      
        $this->set('conversion',$conversion);
        $this->set('settings',$settings);
    }
  
  
  
	public function referalsetting()
    {
        $this->set('title' , 'Galaxyzuo!: Referal Setting');
		$this->loadModel('Referal');
		$conversion = $this->Referal->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			
			if($this->request->data['id']!='') {
				$this->request->data['left_coins'] = $this->request->data['total_coins'];
				if($this->request->data['id']=='') $conversion = $this->Referal->newEntity();
				else $conversion = $this->Referal->get($this->request->data['id']);
				
				$conversion = $this->Referal->patchEntity($conversion, $this->request->data); 
				if($this->Referal->save($conversion)){
					$this->Flash->success(__('Add successfully.','conversion'));
					return $this->redirect(['controller'=>'Token','action'=>'referalsetting']);
				}else{
					foreach($conversion->errors() as $field_key =>  $error_data)
					{
						foreach($error_data as $error_text)
						{
							$this->Flash->error(__($error_text,'conversion'));
						} 
					}
				}
			}
			
		}
		$searchData =array();
        $settings = $this->Referal->find('all')->toArray();
        $this->set('listing',$this->Paginator->paginate($this->Referal, [
			'conditions'=>$searchData,
			'order'=>['Referal.id'=>'desc'],
			'limit' => $this->setting['pagination'],
		]));
      
        $this->set('conversion',$conversion);
        $this->set('settings',$settings);
    }
     
	 
	public function deleteProgram()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Token');
			$query = $this->Token->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			echo 1;
		}
		die; 
	
	} 
	 
}
