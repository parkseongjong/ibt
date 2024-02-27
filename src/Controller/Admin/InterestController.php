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

class InterestController extends AppController
{
	public function index()
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('Interest');
		$conversion = $this->Interest->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			$cudate = date("Y-m-d H:i:s");
			$this->request->data['modified'] = $cudate;
			if($this->request->data['id']==''){ 
				$this->request->data['created'] = $cudate;			
				$conversion = $this->Interest->newEntity();
			}
			else { 
				$conversion = $this->Interest->get($this->request->data['id']);
			}
			
			$conversion = $this->Interest->patchEntity($conversion, $this->request->data); 
			if($this->Interest->save($conversion)){
				$this->Flash->success(__('Add successfully.','conversion'));
				return $this->redirect(['controller'=>'Interest','action'=>'index']);
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
        $settings = $this->Interest->find('all')->toArray();
        $this->set('listing',$this->Paginator->paginate($this->Interest, [
			'conditions'=>$searchData,
			'order'=>['Interest.id'=>'desc'],
			'limit' => $this->setting['pagination'],
		]));
      
        $this->set('conversion',$conversion);
        $this->set('settings',$settings);
		$this->render('index');
    }
  
  
  
    public function search()
	{ die('sdf');
		$this->loadModel('Interest');
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			//$searchData['AND'][] =['Users.user_type'=>'U'];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				/* if($search['pagination'] != '') $limit =  $search['pagination'];
				if($search['name'] != '') $searchData['AND'][] =array('Users.name LIKE' => '%'.$search['name'].'%');
				if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
				if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number LIKE' => '%'.$search['phone_number'].'%');
				if($search['unique_id'] != '') $searchData['AND'][] =array('Users.unique_id LIKE' => '%'.$search['unique_id'].'%');
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']); */
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			/* $this->set('users', $this->Paginator->paginate($this->Interest, [
				'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id'],
				'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			])); */
			
			
			$this->set('listing',$this->Paginator->paginate($this->Interest, [
				'conditions'=>$searchData,
				'order'=>['Interest.id'=>'desc'],
				'limit' => $this->setting['pagination'],
			]));
			print_r($listing); die;
			
			
		}
		$this->render('search');
	} 
  
	
	public function deleteProgram()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Interest');
			$query = $this->Interest->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			echo 1;
		}
		die; 
	
	} 
	 
}
