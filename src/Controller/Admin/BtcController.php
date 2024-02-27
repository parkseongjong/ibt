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

class BtcController extends AppController
{
    public function wallet()
    {

    }
	
    public function request()
    {
		$this->set('title','Request');
        $transaction = $this->Transactions->find();
		$this->set('listing', $this->Paginator->paginate($this->Transactions, [
			'conditions' => ['trans_type'=>'Re','status'=>'P','coin_type'=>'B'],
			'contain'=>'user',
			'limit' => $this->setting['pagination'],
			'order'=>['id'=>'desc']

		]));
		$this->set('transaction',$transaction);
	}
    public function requestUpdate()
    {
		$error = [];
        if($this->request->is('ajax'))
        {

            $data = $this->request->data;

                if($data['val'] == 0)
                {
                    $trans = $this->Transactions->get($data['id']);
                    $trans->status = 'R';
                    $this->Transactions->save($trans);
                }
                else if($data['val'] == 1)
                {
                   
                    $trans = $this->Transactions->get($data['id']);
                    $transaction_id =  $trans->transaction_id;
                    $trans->status = 'A';
					$this->Transactions->save($trans);

					//new entry for recieve details
					$trans_new = $this->Transactions->newEntity();
					$data_new['user_id'] = $trans->user_id;
					$data_new['from_user_id'] = '1';
					$data_new['coin_type'] = $trans->coin_type;
					$data_new['transaction_id'] = $transaction_id;
					$data_new['amount'] = $trans->amount;
					$data_new['trans_type'] = 'R';
					$data_new['status'] = 'T';
					$transaction_new = $this->Transactions->patchEntity($trans_new, $data_new);
					$this->Transactions->save($transaction_new);
					
					//update user coin
					$this->updateUserWallet($data_new['user_id'], 'BTC', 'credit', $data_new['amount']);
				}
                else
                {
                    $error = 'Some error occurred';
                }
            echo json_encode($error);die;
        }

    }
    public function send()
    {
		$this->set('title','Send');
      
		$transaction = $this->Transactions->newEntity();
		$limit=$this->setting['pagination'];
		$searchData = array();
		$searchData['AND'][] = array('coin_type'=>'B','trans_type'=>'S','user_id'=>$this->Auth->user('id'));
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
					
					$transaction = $this->Transactions->newEntity();
					$transaction = $this->Transactions->patchEntity($transaction, array('user_id'=>1,'from_user_id'=>$user['id'],'amount'=>$amount,'coin_type'=>'B','trans_type'=>'S','transaction_id'=>$this->request->data['transaction_id']));
					if($this->Transactions->save($transaction)){
						// Credit
						$transaction = $this->Transactions->newEntity();
						$transaction = $this->Transactions->patchEntity($transaction, array('user_id'=>$user['id'],'from_user_id'=>1,'amount'=>$amount,'coin_type'=>'B','trans_type'=>'R','transaction_id'=>$this->request->data['transaction_id']));
						$this->Transactions->save($transaction);
						$this->updateUserWallet($user['id'],'BTC','credit',$amount);
						$this->Flash->success(__('Successfully transferred coin.'));
						return $this->redirect(['controller'=>'Btc','action' => 'send']);
					}else{
						foreach($transaction->errors() as $field_key =>  $error_data)
						{
							foreach($error_data as $error_text)
							{
								$this->Flash->error(__($error_text));
								
							} 
						}
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

		$this->set('listing', $this->Paginator->paginate($this->Transactions, [
			'contain'=>['from_user'],
			'conditions' => $searchData,
			'limit' => $limit,
			'order'=>['id'=>'desc']

		]));

		$this->set('transaction',$transaction);
        

    }

    public function SendSearch()
    {

        if ($this->request->is('ajax'))
        {
			$searchData = array();
            $searchData['AND'][] = array('coin_type'=>'B','trans_type'=>'S','user_id'=>$this->Auth->user('id'));
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
    
    public function transaction()
    {
		$this->set('title','Transaction');
       $this->loadModel('Agctransactions');
		$limit = $this->setting['pagination'];
		$searchData = array();
		$searchData['AND'][] = array('from_user_id'=>1,'coin_type'=>'B','OR'=>[['trans_type'=>'Re','status'=>'A'],['trans_type'=>'R','status'=>'N']]);
        if ($this->request->is(['post' ,'put'])) 
		{
			// Filter
			
			if ($this->request->is(['post' ,'put']) ) 
			{
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				if($search['pagination'] != '') $limit =  $search['pagination'];
				//pr($search);die;
				if($search['name'] != '') $searchData['AND'][] =array('user.name LIKE' => '%'.$search['name'].'%');
				if($search['amount'] != '') $searchData['AND'][] =array('amount' => $search['amount']);
				if(isset($search['transaction_id']) && ($search['transaction_id']!='')) $searchData['AND'][] =array('transaction_id' => $search['transaction_id']);
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
				
			}
			

		}

		$this->set('listing',$this->Paginator->paginate($this->Agctransactions, [

			'contain'=>['user'=>['fields'=>['name','unique_id']]],
			'conditions'=>$searchData,
			'order'=>['Agctransactions.id'=>'desc'],
			'limit' => $limit,
		]));
        
      }

    public function transactionSearch()
    {

        if ($this->request->is('ajax'))
        {
            parse_str($this->request->data['key'], $this->request->data);
            $searchData = array();
            $searchData['AND'][] = array('from_user_id'=>1,'coin_type'=>'B','OR'=>[['trans_type'=>'Re','status'=>'A'],['trans_type'=>'R','status'=>'N']]);

			$limit = $this->setting['pagination'];
			$search = $this->request->data;
			if($search['pagination'] != '') $limit =  $search['pagination'];
				//pr($search);die;
			if($search['name'] != '') $searchData['AND'][] =array('user.name LIKE' => '%'.$search['name'].'%');
			if($search['amount'] != '') $searchData['AND'][] =array('amount' => $search['amount']);
			if(isset($search['transaction_id']) && ($search['transaction_id']!='')) $searchData['AND'][] =array('transaction_id' => $search['transaction_id']);
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
	  
          
          

            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $this->set('listing',$this->Paginator->paginate($this->Transactions, [
                'contain'=>['user'=>['fields'=>['name','unique_id']]],
                'conditions'=>$searchData,
                'order'=>['Transactions.id'=>'desc'],
                'limit' => $limit,
            ]));


        }

    }
    public function reference()
    {
        $this->set('title','Transaction');
        
		$limit = $this->setting['pagination'];
		$searchData = array();
		$searchData['AND'][] = array('coin_type'=>'B','trans_type'=>'Ref');

		if ($this->request->is(['post' ,'put'])) {
			// Filter
			$data = $this->request->data;

		}

		$this->set('listing',$this->Paginator->paginate($this->Transactions, [
			'contain'=>['user'=>['fields'=>['name','unique_id']],'from_user'=>['fields'=>['name','unique_id']]],
			'conditions'=>$searchData,
			'order'=>['Transactions.id'=>'desc'],
			'limit' => $limit,
		]));
            
    }
    public function referenceSearch()
    {

        if ($this->request->is('ajax'))
        {
            parse_str($this->request->data['key'], $this->request->data);
            $data = $this->request->data;

            $searchData = array();
            $searchData['AND'][] = array('coin_type'=>'B','trans_type'=>'Ref');
            if($data['filter_month'] == 'today' ){
                $searchData['AND'][] =['DATE(Transactions.created)'=>date('Y-m-d')];
            }else if($data['filter_month'] == 'yesterday'){
                $date = date('Y-m-d', strtotime('-1 days'));
                $searchData['AND'][] =['DATE(Transactions.created)'=>$date];
            }else if($data['filter_month'] == '7_day'){
                $date = date('Y-m-d', strtotime('-7 days'));
                $searchData['AND'][] =['DATE(Transactions.created) >='=>$date];
            }else if($data['filter_month'] == 'this_month'){
                $date  =date('Y-m-d', strtotime('first day of this month'));
                $searchData['AND'][] =['DATE(Transactions.created) >='=>$date];
            }else if($data['filter_month'] == 'last_month'){
                $from_date  =date('Y-m-d', strtotime('first day of last month'));
                $to_date  =date('Y-m-d', strtotime('last day of last month'));
                $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $from_date,'DATE(Transactions.created) <= ' => $to_date);
            }

            if($data['filter_row'] !='') $limit = $data['filter_row'];
            else $limit = $this->setting['pagination'];

            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $this->set('listing',$this->Paginator->paginate($this->Transactions, [
                'contain'=>['user'=>['fields'=>['name','unique_id']]],
                'conditions'=>$searchData,
                'order'=>['Transactions.id'=>'desc'],
                'limit' => $limit,
            ]));


            $this->set('type','BTC');
        }

    }
    
}
