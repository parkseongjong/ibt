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

namespace App\Controller\Front;


use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;

class GalaxyController extends AppController
{
	
	public function bitcoinValue()
    {
		if($this->request->is('ajax'))
        {
			$this->loadModel('Token');
			$galaxy_amount = $this->request->data['coin'];
			$galaxy_arr = $this->Token->find('all')->hydrate(false)->first();

			$btc_amount = $galaxy_amount * $galaxy_arr['btc_value'];
			echo $btc_amount;
			
			die;
		
		}
	
	}
    
   
    public function getINR()
    {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://blockchain.info/ticker');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$contents = curl_exec($ch);
		$arr = json_decode($contents);
		echo "1 BTC  =".$arr->INR->buy." INR";
		die;
		
		  
	}
	
    public function galaxy()
    {
		$this->set('title','BUY');
		$limit = $this->setting['pagination'];
		$transaction = $this->Transactions->newEntity();
		if ($this->request->is(['post' ,'put'])) 
		{
			$galaxy_amount = $this->request->data['amount'];
			$galaxy_arr  =$this->getgalaxyfrombtcConvert($galaxy_amount,0);
			
			$btc_amount = $galaxy_amount * $galaxy_arr['rate'];
			$btc_available =  $this->checkUserAmount($this->Auth->user('id'),$this->coin_arr['BTC']);
			
			if($btc_available>=$btc_amount)
			{
				
				if($galaxy_arr['success'] == 1)
				{
				
					// debit
					$this->request->data['amount'] =$btc_amount;
					$this->request->data['coin_type'] ='B';
					$this->request->data['trans_type'] = 'S';
					$this->request->data['user_id'] = $this->Auth->user('id');
					$this->request->data['from_user_id'] = $this->Auth->user('id');
					$transaction = $this->Transactions->patchEntity($transaction, $this->request->data);
					
					if ($this->Transactions->save($transaction)) 
					{	
						$this->updateUserWallet($this->Auth->user('id'),'BTC','debit',$btc_amount);
						
						$ref_trans = $this->Transactions->newEntity();
						$data_ref['user_id'] = $this->Auth->user('id');
						$data_ref['from_user_id'] =$this->Auth->user('id');
						$data_ref['coin_type'] = 'Z';
						$data_ref['amount'] = $galaxy_amount;
						$data_ref['trans_type'] = 'R';
						$transaction_ref = $this->Transactions->patchEntity($ref_trans, $data_ref);
						$this->Transactions->save($transaction_ref);
						$this->updateUserWallet($this->Auth->user('id'),'ZUO','credit',$galaxy_amount);
						$this->updateAdminWallet($galaxy_arr['conversion_rate_id'],$galaxy_arr['amount']);
						
						//send referral amount to user
						$ref_user_id = $this->getReferralUser($this->Auth->user('id'));
						if ($ref_user_id != '' && ($this->referralTransactionEntry($this->Auth->user('id')) == 1) ) 
						{
							
						    $ref_per = $this->setting['referral_amount'];
							if ($ref_per > 0) {
								 $ref_amt = ($galaxy_arr['amount'] * $ref_per) / (100);
								// convert amount to galaxy 
								$galaxy_arr  =$this->getgalaxyfrombtcConvert($ref_amt,0);
								
								if($galaxy_arr['success'] == 1)
								{
									$ref_trans = $this->Transactions->newEntity();
									$data_ref['user_id'] = $ref_user_id;
									$data_ref['from_user_id'] = $this->Auth->user('id');
									$data_ref['coin_type'] = 'Z';
									$data_ref['conversion_rate_id'] = $galaxy_arr['conversion_rate_id'];
									$data_ref['amount'] = $galaxy_arr['amount'];
									$data_ref['trans_type'] = 'Ref';
									$transaction_ref = $this->Transactions->patchEntity($ref_trans, $data_ref);
									$this->Transactions->save($transaction_ref);
									//update user wallet for ref
									$this->updateUserWallet($data_ref['user_id'],'ZUO', 'credit', $data_ref['amount']);
									//update admin wallet
									$this->updateAdminWallet($galaxy_arr['conversion_rate_id'],$galaxy_arr['amount']);
									
								}
								
								
							}
						}
						$this->Flash->success(__('Sucessfully convert Bitcoin to Galaxy.'));
						return $this->redirect(['controller'=>'transactions','action' => 'galaxy']);
					}else{
						foreach($transaction->errors() as $field_key =>  $error_data)
						{
							foreach($error_data as $error_text)
							{
								$this->Flash->error(__($error_text));
								
							} 
						}
					}
				}else{
					if(array_key_exists('left',$galaxy_arr)) $this->Flash->error(__('Galaxy left to convert is: '.$galaxy_arr['left']));
					else $this->Flash->error(__('No galaxy available.'));
				}
			}else{
				$this->Flash->error(__('Insufficient bitcoins to convert.'));
			}
				
			
			
		}
		
		$btc = $this->checkUserAmount($this->Auth->user('id'),$this->coin_arr['BTC']);
		
		if($btc>0){
			$rate  = $this->bitInGalaxy();
			$this->set('btc',array('btc'=>$btc,'galaxy'=>$btc / $rate['rate']));

		}
		
		$this->set('transaction',$transaction);
	 }
    public function transaction($type=null)
    {
		$this->set('title','Transaction');
		if($type=='Galaxy'|| $type=='BTC' )
		{
			 $data = [];
			$data['filter_month'] = '';
			$data['filter_name'] = '';
			$data['filter_id'] = '';
			$data['filter_row'] = '';
			if($type=='Galaxy'){
				$type='ZUO';
				$this->set('display_type','Galaxy');
			}else  $this->set('display_type',$type);
			$limit = $this->setting['pagination'];
			$searchData = array();
			$searchData['AND'][] = array('coin_type'=>$this->coin_arr[$type],'user_id' => $this->Auth->user('id'),'trans_type !='=>'Re');
			if ($this->request->is(['post' ,'put'])) {
				// Filter
				$data = $this->request->data;
				
				if(isset($data['filter_month']))
				{
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
				}
				// Export
			}
			$this->set('listing',$this->Paginator->paginate($this->Transactions, [
						    'contain'=>['from_user'=>['fields'=>['name','unique_id']]],
						    'conditions'=>$searchData,
						    'order'=>['Transactions.id'=>'desc'],
							'limit' => $limit,
						]));
			$this->set('type',$type);
			 $this->set('data',$data);
			
		}else return $this->redirect(['controller'=>'pages','action' => 'dashboard']);
	}
	
	public function transactionSearch()
	{
		
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$data = $this->request->data;
			
			$searchData = array();
			$searchData['AND'][] = array('coin_type'=>$this->coin_arr[$data['type']],'user_id' => $this->Auth->user('id'),'trans_type !='=>'Re');
			
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
						   'contain'=>['from_user'=>['fields'=>['name','unique_id']]],
						    'conditions'=>$searchData,
						    'order'=>['Transactions.id'=>'desc'],
							'limit' => $limit
						]));
		
			
			$this->set('type',$data['type']);
		
		   
			
		}
	
	}

	
   
}
