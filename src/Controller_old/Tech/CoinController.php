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

namespace App\Controller\Tech;


use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;

class CoinController extends AppController
{
	public function index()
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('Cryptocoin');
		$conversion = $this->Coin->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			$cudate = date("Y-m-d H:i:s");
			$this->request->data['modified'] = $cudate;
			if($this->request->data['id']==''){ 
				$this->request->data['created'] = $cudate;			
				$conversion = $this->Coin->newEntity();
			}
			else { 
				$conversion = $this->Coin->get($this->request->data['id']);
			}
			
			$conversion = $this->Coin->patchEntity($conversion, $this->request->data); 
			if($this->Coin->save($conversion)){
				$this->Flash->success(__('Add successfully.','conversion'));
				return $this->redirect(['controller'=>'Coin','action'=>'index']);
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
		$this->set('listing',$this->Paginator->paginate($this->Cryptocoin, [
			'conditions'=>$searchData,
			'order'=>['Cryptocoin.id'=>'desc'],
			'limit' => $this->setting['pagination'],
		]));
      
        $this->set('conversion',$conversion);
        //$this->set('settings',$settings);    
	}
	
	
	public function add()
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('Cryptocoin');
		
		
		$conversion = $this->Cryptocoin->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			$coinShortName = $this->request->data['short_name'];
			$coinName = $this->request->data['name'];
			$findExist = $this->Cryptocoin->find('all',['conditions'=>['OR'=>[['short_name'=>$coinShortName],['name'=>$coinName]]]])->hydrate(false)->first();
			if(empty($findExist)){	
				$cudate = date("Y-m-d H:i:s");
				if(!empty($this->request->data['icon_img']['name'])){
					$imgName = $this->request->data['icon_img']['name'];
					$tmpName = $this->request->data['icon_img']['tmp_name'];
					$getExtension = pathinfo($imgName, PATHINFO_EXTENSION);
					$newImgName = time().".".$getExtension;
					if(move_uploaded_file($tmpName,"uploads/cryptoicon/".$newImgName)){
						$this->request->data['icon'] = $newImgName;
					}
				}
				$this->request->data['modified'] = $cudate;
				
				$conversion = $this->Cryptocoin->patchEntity($conversion, $this->request->data); 
				if($this->Cryptocoin->save($conversion)){
					$this->Flash->success(__('Add successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'index']);
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
			else {
				$this->Flash->success(__('Coin Already Exist','conversion'));
				return $this->redirect(['controller'=>'Coin','action'=>'index']);
			}
			
			
		}
		$searchData =array();
        $this->set('listing',$this->Paginator->paginate($this->Coin, [
			'conditions'=>$searchData,
			'order'=>['Coin.id'=>'desc'],
			'limit' => $this->setting['pagination'],
		]));
      
        $this->set('conversion',$conversion);
    }
	public function sendreward()
    {
		
        $this->set('title' , 'Send Reward');
		$this->loadModel('Cryptocoin');
		$this->loadModel('Coinpair');
		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
													 'valueField' => 'short_name'
												],['conditions'=>['id !='=>1]])->toArray();
		$this->set('coinList',$coinList);
		//$conversion = $this->Cryptocoin->newEntity();
		
          $this->loadModel('Transactions');	
          $this->loadModel('Users');		  
			$add=$this->Transactions->newEntity();
			if ($this->request->is(['post' ,'put'])) {
			$username=strip_tags($this->request->data['username']);
			 $username1 = $this->Users->find('all', 
		                                 array(
                                         'fields' =>['id'], 
										 'conditions' => array('username' => $username), 
                                         ))->hydrate(false)->first();
											
				
				if(empty($username1)){
					$this->Flash->error(__('User not found.'));
					return $this->redirect(['controller'=>'Coin','action'=>'sendreward']);
				}
				
				$getTxid = $this->Users->getUniqueTxId();
				$user_id=$this->Auth->user('id');
				$coinId = $this->request->data['coin_first_id'];
				if(empty($coinId) && $coinId==1){
					$this->Flash->error(__('Please Select a coin.'));
					return $this->redirect(['controller'=>'Coin','action'=>'sendreward']);
				}
				$purArr=[];
				$purArr['cryptocoin_id']=$this->request->data['coin_first_id'];
				$purArr['user_id']=$username1['id'];
				$purArr['coin_amount']=$this->request->data['amount'];
				$purArr['remark']='airdrop reward';
				$purArr['tx_id']=$getTxid;
				$purArr['tx_type']="purchase";
				$purArr['status']="completed";
			
				
				$add=$this->Transactions->patchEntity($add, $purArr);
				$save=$this->Transactions->save($add);
				if($save){
					$this->Flash->success(__('reward send successfully.'));
					return $this->redirect(['controller'=>'Coin','action'=>'sendreward']);
				}
				else {
					$this->Flash->error(__('unable to send reward.'));
					return $this->redirect(['controller'=>'Coin','action'=>'sendreward']);
				}
				//print_r($save);
             }
			  $this->set('add',$add); 
      
    }
	
	
	public function edit($id)
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('Coin');
		$this->loadModel('Cryptocoin');
	
		
		if ($this->request->is(['post','put'])) 
		{
			$cudate = date("Y-m-d H:i:s");
			$this->request->data['modified'] = $cudate;
			$conversion = $this->Cryptocoin->get($this->request->data['id']);
			
			$id = $this->request->data['id'];
			$coinShortName = $this->request->data['short_name'];
			$coinName = $this->request->data['name'];
			$findExist = $this->Cryptocoin->find('all',['conditions'=>['OR'=>[['short_name'=>$coinShortName,'id !='=>$id],['name'=>$coinName,'id !='=>$id]]]])->hydrate(false)->first();
			if(empty($findExist)){
			
			
				if(!empty($this->request->data['icon_img']['name'])){
					$imgName = $this->request->data['icon_img']['name'];
					$tmpName = $this->request->data['icon_img']['tmp_name'];
					$getExtension = pathinfo($imgName, PATHINFO_EXTENSION);
					$newImgName = time().".".$getExtension;
					if(move_uploaded_file($tmpName,"uploads/cryptoicon/".$newImgName)){
						$this->request->data['icon'] = $newImgName;
					}
				}
				
				
				
				$conversion = $this->Cryptocoin->patchEntity($conversion, $this->request->data); 
				if($this->Cryptocoin->save($conversion)){
					$this->Flash->success(__('updated successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'index']);
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
			else {
				$this->Flash->success(__('unable to update coin. Coin Already Exist','conversion'));
				return $this->redirect(['controller'=>'Coin','action'=>'index']);
			}
			
			
		}
		else {
			$conversion = $this->Cryptocoin->find('all',['conditions'=>['id'=>$id]])->hydrate(false)->first();
			$this->request->data = $conversion; 
		}
		
    }
  
 
	 
	public function deleteProgram()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Cryptocoin');
			$query = $this->Cryptocoin->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			echo 1;
		}
		die; 
	
	} 
	
	public function changestatus($id)
	{
			$this->loadModel('Cryptocoin');
			$cudate = date("Y-m-d H:i:s");
			$this->request->data['modified'] = $cudate;
			
		
			$conversion = $this->Cryptocoin->get($id);
			$updatData = [];
			$updatData['status'] =  ($conversion->status==0) ? 1 : 0 ; 
			
				$conversion = $this->Cryptocoin->patchEntity($conversion, $updatData); 
				if($this->Cryptocoin->save($conversion)){
					$this->Flash->success(__('updated successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'index']);
				}else{
					foreach($conversion->errors() as $field_key =>  $error_data)
					{
						foreach($error_data as $error_text)
						{
							$this->Flash->error(__($error_text,'conversion'));
						} 
					}
				}
		
		
		die;
	
	} 
	
	
	
	
	
	
	public function coinpairIndex()
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('Cryptocoin');
		$this->loadModel('Coinpair');
		$conversion = $this->Coinpair->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			/* $cudate = date("Y-m-d H:i:s");
			$this->request->data['modified'] = $cudate;
			if($this->request->data['id']==''){ 
				$this->request->data['created'] = $cudate;			
				$conversion = $this->Coin->newEntity();
			}
			else { 
				$conversion = $this->Coin->get($this->request->data['id']);
			}
			
			$conversion = $this->Coin->patchEntity($conversion, $this->request->data); 
			if($this->Coin->save($conversion)){
				$this->Flash->success(__('Add successfully.','conversion'));
				return $this->redirect(['controller'=>'Coin','action'=>'index']);
			}else{
				foreach($conversion->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						$this->Flash->error(__($error_text,'conversion'));
					} 
				}
			} */
			
			
		}
		
		
		
		$searchData =array();
		$this->set('listing',$this->Paginator->paginate($this->Coinpair, [
			'conditions'=>$searchData,
			'contain'=>['cryptocoin_first','cryptocoin_second'],
			'order'=>['Coinpair.id'=>'desc'],
			'limit' => $this->setting['pagination'],
		]));
      
        $this->set('conversion',$conversion);
        //$this->set('settings',$settings);    
	}
	
	
	public function coinpairAdd()
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('Coinpair');
		$this->loadModel('Cryptocoin');
		
		$conversion = $this->Coinpair->newEntity();
		
		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
													 'valueField' => 'short_name'
												])->toArray();
		$this->set('coinList',$coinList);
        if($this->request->is(['post','put'])) 
		{
			
			$coinFirst = $this->request->data['coin_first_id'];
			$coinSecond = $this->request->data['coin_second_id'];
			
			if($coinFirst == $coinSecond){
				$this->Flash->success(__('Both Coin Can\'t be same.','conversion'));
				return $this->redirect(['controller'=>'Coin','action'=>'coinpairAdd',$id]);
			}
			
			$findExist = $this->Coinpair->find('all',['conditions'=>['OR'=>[
																				[
																					'coin_first_id'=>$coinFirst,
																					'coin_second_id'=>$coinSecond
																				],	
																				[	
																					'coin_first_id'=>$coinSecond,
																					'coin_second_id'=>$coinFirst
																				]
																			]
																	]
														])->hydrate(false)->first();
			if(empty($findExist)){
				$conversion = $this->Coinpair->newEntity();	
				$cudate = date("Y-m-d H:i:s");
				$this->request->data['updated'] = $cudate;
				
				$conversion = $this->Coinpair->patchEntity($conversion, $this->request->data); 
				if($this->Coinpair->save($conversion)){
					$this->Flash->success(__('Added successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'coinpair_index']);
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
			else {
				$this->Flash->success(__('Coin Pair Already Exist','conversion'));
				return $this->redirect(['controller'=>'Coin','action'=>'coinpair_index']);
			}
			
			
		}
		$this->set('conversion',$conversion);
    }
	
	
	public function coinpairEdit($id)
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('Coinpair');
		$this->loadModel('Cryptocoin');
		
		
		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
													 'valueField' => 'short_name'
												])->toArray();
		$this->set('coinList',$coinList);
        if($this->request->is(['post','put'])) 
		{
			$id = $this->request->data['id'];
			$coinFirst = $this->request->data['coin_first_id'];
			$coinSecond = $this->request->data['coin_second_id'];
			if($coinFirst == $coinSecond){
				$this->Flash->success(__('Both Coin Can\'t be same.','conversion'));
				return $this->redirect(['controller'=>'Coin','action'=>'coinpairEdit',$id]);
			}
			$findExist = $this->Coinpair->find('all',['conditions'=>['OR'=>[
																				[
																					'coin_first_id'=>$coinFirst,
																					'coin_second_id'=>$coinSecond,
																					'coin_second_id'=>$id,
																				],	
																				[	
																					'coin_first_id'=>$coinSecond,
																					'coin_second_id'=>$coinFirst,
																					'id'=>$id,
																				]
																			]
																	]
														])->hydrate(false)->first();
			if(empty($findExist)){
				$conversion = $this->Coinpair->get($id);	
				$cudate = date("Y-m-d H:i:s");
				$this->request->data['updated'] = $cudate;
				
				$conversion = $this->Coinpair->patchEntity($conversion, $this->request->data); 
				if($this->Coinpair->save($conversion)){
					$this->Flash->success(__('Added successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'coinpair_index']);
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
			else {
				$this->Flash->success(__('Coin Pair Already Exist','conversion'));
				return $this->redirect(['controller'=>'Coin','action'=>'coinpair_index']);
			}
			
			
		}
		else {
			$conversion = $this->Coinpair->find('all',['conditions'=>['id'=>$id]])->hydrate(false)->first();
			$this->request->data = $conversion;
		}
		
    }
  
 
	 
	public function coinpairDeleteProgram()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Cryptocoin');
			$this->loadModel('Coinpair');
			$query = $this->Coinpair->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			echo 1;
		}
		die; 
	
	} 
	
	public function coinpairChangestatus($id)
	{
			$this->loadModel('Coinpair');
			$this->loadModel('Cryptocoin');
			$cudate = date("Y-m-d H:i:s");
			$this->request->data['modified'] = $cudate;
			
		
			$conversion = $this->Coinpair->get($id);
			$updatData = [];
			$updatData['status'] =  ($conversion->status==0) ? 1 : 0 ; 
			
				$conversion = $this->Coinpair->patchEntity($conversion, $updatData); 
				if($this->Coinpair->save($conversion)){
					$this->Flash->success(__('updated successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'coinpairIndex']);
				}else{
					foreach($conversion->errors() as $field_key =>  $error_data)
					{
						foreach($error_data as $error_text)
						{
							$this->Flash->error(__($error_text,'conversion'));
						} 
					}
				}
		
		
		die;
	
	} 
	
	
	

	public function rewardlist()
    {
		$this->set('title','Transaction');
		$this->loadModel('Transactions');
			$this->set('display_type','BTC');
			
			$limit = $this->setting['pagination'];
			$type = "BTC";
			$searchData = array();
			
			$searchData['AND'][] = array("Transactions.remark"=>'airdrop reward'); 
			
			if ($this->request->is(['post' ,'put']) ) 
			{
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				
				if(!empty($search['pagination'])) $limit =  $search['pagination'];
				
				// search by username
				if(!empty($search['username'])){
					$searchData['AND'][] = array("user.username like"=>"%".$search['username']."%");
				}
				
				// search by date range
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
				
				// saarch by coin type
				if(!empty($search['coin_type'])){
					$searchData['AND'][] = array("cryptocoin.id"=>$search['coin_type']);
				}
				
				// saarch by coin type
				if(!empty($search['status'])){
					$searchData['AND'][] = array("Transactions.status"=>$search['status']);
				}
				
				
			}
			$collectdata = $this->Paginator->paginate($this->Transactions, [
						    'contain'=>['user'=>['fields'=>['username','unique_id']],
										'cryptocoin'=>['fields'=>['short_name']]],
							'conditions'=>$searchData,
						    'order'=>['Transactions.id'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		
	}
	
	public function rewardlistSearch()
	{
		$type = "BTC";
		$this->loadModel('Transactions');
		if ($this->request->is('ajax')) 
		{ 
			$limit = $this->setting['pagination'];
			$searchData = array();
			$searchData['AND'][] = array("Transactions.remark"=>'airdrop reward'); 
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$collectdata = $this->Paginator->paginate($this->Transactions, [
						    'contain'=>['user'=>['fields'=>['username','unique_id']],
										'cryptocoin'=>['fields'=>['short_name']]],
							'conditions'=>$searchData,
						    'order'=>['Transactions.id'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		}
	
	}
		
	
	
	 
}
