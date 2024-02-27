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

class GalaxyController extends AppController
{
   
   public function send()
    {
		$this->set('title','Send');
      
		$transaction = $this->Transactions->newEntity();
		if ($this->request->is(['post' ,'put'])) 
		{
			
			if(isset($this->request->data['amount']))
			{
				// Send
				$amount = $this->request->data['amount'];
				$wallet_address = $this->request->data['wallet_address'];
				$this->loadModel('Users');

				$user = $this->Users->find('all',['fields'=>['id'],'conditions'=>['id !='=>$this->Auth->user('id'),'unique_id'=>$wallet_address]])->hydrate(false)->first();
				if(!empty($user))
				{
					
					$galaxy_arr  =$this->getgalaxyfrombtcConvert($amount,0);
					
					if($galaxy_arr['success'] == 1)
					{
					
						$transaction = $this->Transactions->newEntity();
						$transaction = $this->Transactions->patchEntity($transaction, array('user_id'=>1,'from_user_id'=>$user['id'],'amount'=>$amount,'coin_type'=>'Z','trans_type'=>'S'));
						if($this->Transactions->save($transaction)){
							$transaction = $this->Transactions->newEntity();
							$transaction = $this->Transactions->patchEntity($transaction, array('conversion_rate_id'=>$galaxy_arr['conversion_rate_id'],'user_id'=>$user['id'],'from_user_id'=>1,'amount'=>$amount,'coin_type'=>'Z','trans_type'=>'R'));
							$this->Transactions->save($transaction);
							$this->updateUserWallet($user['id'],'ZUO','credit',$amount);
							$this->updateAdminWallet($galaxy_arr['conversion_rate_id'],$galaxy_arr['amount']);
							$this->Flash->success(__('Successfully transferred coin.'));
							return $this->redirect(['controller'=>'Galaxy','action' => 'send']);
							
						}else{
							foreach($transaction->errors() as $field_key =>  $error_data)
							{
								foreach($error_data as $error_text)
								{
									$this->Flash->error(__($error_text));
									
								} 
							}
						}
						// Credit
						
					}else{
						$this->Flash->error(__('Insufficient funds to transfer.'));
						return $this->redirect(['controller'=>'Galaxy','action' => 'send']);
					}
					
			
				}else if(empty($user)) $this->Flash->error(__('Invalid wallet address'));
			
			
			}else{
				//Filter
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				if($search['pagination'] != '') $limit =  $search['pagination'];
				//pr($search);die;
				if($search['name'] != '') $searchData['AND'][] =array('from_user.name LIKE' => '%'.$search['name'].'%');
				if($search['unique_id'] != '') $searchData['AND'][] =array('from_user.unique_id' => $search['unique_id']);
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
				
				
			
			}
			
		
		}
		$transaction = $this->Transactions->find();

		$this->set('transaction',$transaction);
        

    }

   
    public function transaction()
    {
		$this->set('title','Transaction');
       
		$limit=$this->setting['pagination'];
		$searchData = array();
		$searchData['AND'][] = array('coin_type'=>'Z','trans_type'=>'S','user_id'=>$this->Auth->user('id'));
		
        if ($this->request->is(['post' ,'put'])) 
		{
			// Filter
			
			if ($this->request->is(['post' ,'put']) ) 
			{
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				if($search['pagination'] != '') $limit =  $search['pagination'];
				//pr($search);die;
				if($search['name'] != '') $searchData['AND'][] =array('from_user.name LIKE' => '%'.$search['name'].'%');
				if($search['unique_id'] != '') $searchData['AND'][] =array('from_user.unique_id' => $search['unique_id']);
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
			}
			

		}

		$this->set('listing', $this->Paginator->paginate($this->Transactions, [
			'contain'=>['from_user'],
			'conditions' => $searchData,
			'limit' => $limit,
			'order'=>['id'=>'desc']

		]));
        
      }
 public function transactionSearch()
    {

        if ($this->request->is('ajax'))
        {
			$searchData = array();
            $searchData['AND'][] = array('coin_type'=>'Z','trans_type'=>'S','user_id'=>$this->Auth->user('id'));
			$limit = $this->setting['pagination'];
			parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			//pr($search);die;
			if($search['name'] != '') $searchData['AND'][] =array('from_user.name LIKE' => '%'.$search['name'].'%');
			if($search['unique_id'] != '') $searchData['AND'][] =array('from_user.unique_id' => $search['unique_id']);
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);


            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $this->set('listing', $this->Paginator->paginate($this->Transactions, [
                'contain'=>['from_user'],
                'conditions'=>$searchData,
                'order'=>['id'=>'desc'],
                'limit' => $limit,

            ]));

		}

    }
    
    
}
