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
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
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
				$this->add_system_log(200, 0, 2, '코인 추가');
				$this->Flash->success(__('Add successfully.','conversion'));
				return $this->redirect(['controller'=>'Coin','action'=>'index']);
			}else{
				$this->add_system_log(200, 0, 2, '코인 추가 실패');
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
			'order'=>['Cryptocoin.serial_no'=>'asc'],
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
					$this->add_system_log(200, 0, 2, '코인 추가 (' .$coinName. ')');
					$this->Flash->success(__('Add successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'index']);
				}else{
					$this->add_system_log(200, 0, 2, '코인 추가 실패 (' .$coinName. ')');
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
				$this->add_system_log(200, 0, 2, '코인 추가 실패 (이미 있는 코인)');
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
		$this->loadModel('Users');
        $this->loadModel('Transactions');
        $this->loadModel('PrincipalWallet');

		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id','valueField' => 'short_name'],['conditions'=>['id !='=>1]])->toArray();
		$this->set('coinList',$coinList);

		$multipleAddArr = [];
		$add = $this->PrincipalWallet->newEntity();
		if ($this->request->is(['post' ,'put'])) {
			$userIds=$this->request->data['user_ids'];
			if(!empty($userIds)){
				foreach($userIds as $userId){
					if(!empty($userId)){
						$getTxid = $this->Users->getUniqueTxId();
						$purArr=[];
						$purArr['cryptocoin_id']=$this->request->data['coin_first_id'];
						$purArr['user_id']=$userId;
						$purArr['amount']=$this->request->data['amount'];
						$purArr['remark']='airdrop reward';
						$purArr['tx_id']=$getTxid;
						$purArr['type']="purchase";
						$purArr['status']="completed";
						$multipleAddArr[]=$purArr;
					}
				}
			}
			else {
				$this->add_system_log(200, 0, 2,'코인 Reward 보내기 실패 - (보낼 고객 선택하지 않음)');
				$this->Flash->error(__('Please select at least one user.'));
				return $this->redirect(['controller'=>'Coin','action'=>'sendreward']);
			}
			//print_r($multipleAddArr); die;
			if(!empty($multipleAddArr)){
				$addEntity = $this->PrincipalWallet->newEntities($multipleAddArr);
				$save=$this->PrincipalWallet->saveMany($addEntity);
				if($save){
					foreach($userIds as $userId){
						$this->add_system_log(200, $userId, 2, '코인 Reward 보내기 성공');
					}
					$this->Flash->success(__('Reward sent successfully!'));
					return $this->redirect(['controller'=>'Coin','action'=>'sendreward']);
				}
				else {
					$this->add_system_log(200, 0, 2, '코인 Reward 보내기 실패');
					$this->Flash->error(__('Unable to send reward.'));
					return $this->redirect(['controller'=>'Coin','action'=>'sendreward']);
				}
			}
			else {
				$this->add_system_log(200, 0, 2, '코인 Reward 보내기 실패 (빈항목)');
				$this->Flash->error(__('Reward not found.'));
				return $this->redirect(['controller'=>'Coin','action'=>'sendreward']);
			}
			
		 }
		  $this->set('add',$add); 
    }
	
	
	public function withdrawalreward()
    {
		
        $this->set('title' , 'Withdrawal Reward');
		$this->loadModel('Cryptocoin');
		$this->loadModel('Coinpair');
        $this->loadModel('Transactions');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
		$coinList = $this->get_coin_list();
		$this->set('coinList',$coinList);

		$multipleAddArr = [];
		$add=$this->PrincipalWallet->newEntity();
		if ($this->request->is(['post' ,'put'])) {
			$userIds=$this->request->data['user_ids'];
			if(!empty($userIds)){
				foreach($userIds as $userId){
					if(!empty($userId)){
						$getTxid = $this->Users->getUniqueTxId();
						$purArr=[];
						$purArr['cryptocoin_id']=$this->request->data['coin_first_id'];
						$purArr['user_id']=$userId;
						
						$purArr['amount']= -$this->request->data['amount'];
						$purArr['remark']='withdrawal airdrop reward';
						$purArr['tx_id']=$getTxid;
						$purArr['type']="withdrawal";
						$purArr['status']="completed";
						$multipleAddArr[]=$purArr;
					}
				}
			}
			else {
				$this->add_system_log(200, 0, 2,'인출 보상 (Withdrawal Reward) 보내기 실패 - (보낼 고객 선택하지 않음)');
				$this->Flash->error(__('Please select at least one user.'));
				return $this->redirect(['controller'=>'Coin','action'=>'withdrawalreward']);
			}
			//print_r($multipleAddArr); die;
			if(!empty($multipleAddArr)){
				$addEntity = $this->PrincipalWallet->newEntities($multipleAddArr);
				$save=$this->PrincipalWallet->saveMany($addEntity);
				if($save){
					foreach($userIds as $userId){
						$this->add_system_log(200, $userId, 2, '인출 보상 (Withdrawal Reward) 보내기 성공');
					}
					$this->Flash->success(__('Reward withdrawn successfully!'));
					return $this->redirect(['controller'=>'Coin','action'=>'withdrawalreward']);
				}
				else {
					$this->add_system_log(200, 0, 2, '인출 보상 (Withdrawal Reward) 보내기 실패');
					$this->Flash->error(__('Unable to withdraw reward!'));
					return $this->redirect(['controller'=>'Coin','action'=>'withdrawalreward']);
				}
			}
			else {
				$this->add_system_log(200, 0, 2, '인출 보상 (Withdrawal Reward) 보내기 실패 (빈항목)');
				$this->Flash->error(__('No reward found.'));
				return $this->redirect(['controller'=>'Coin','action'=>'withdrawalreward']);
			}
			
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
					
					if(move_uploaded_file($tmpName,WWW_ROOT."uploads/cryptoicon/".$newImgName)){
						$this->request->data['icon'] = $newImgName;
					}
					
				}
				
				
				
				$conversion = $this->Cryptocoin->patchEntity($conversion, $this->request->data); 
				if($this->Cryptocoin->save($conversion)){
					$this->add_system_log(200, 0, 3,'코인 수정 (id : ' . $id . ')');
					$this->Flash->success(__('updated successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'index']);
				}else{
					$this->add_system_log(200, 0, 3,'코인 수정 실패 (id : ' . $id . ')');
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
			$query->delete()->where(['id' => $this->request->data['id']])->execute();
			$this->add_system_log(200, 0, 4,'Cryptocoin 삭제 ( id : ' . $this->request->data['id'] . ')');
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
					$this->add_system_log(200, 0, 3,'Cryptocoin 상태 수정 ( id : '. $id. ')');
					$this->Flash->success(__('updated successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'index']);
				}else{
					$this->add_system_log(200, 0, 3,'Cryptocoin 상태 수정 실패 ( id : '. $id. ')');
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
			//'limit' => $this->setting['pagination'],
			'limit' => 150,
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
					$this->add_system_log(200, 0, 2,'Coinpair 추가');
					$this->Flash->success(__('Added successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'coinpair_index']);
				}else{
					$this->add_system_log(200, 0, 2,'Coinpair 추가 실패');
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
				$this->add_system_log(200, 0, 2,'Coinpair 추가 실패 (이미 존재)');
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
																					'id !='=>$id,
																				],	
																				[	
																					'coin_first_id'=>$coinSecond,
																					'coin_second_id'=>$coinFirst,
																					'id !='=>$id,
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
					$this->add_system_log(200, 0, 3,'Coinpair 수정 (id : '.$id.')');
					$this->Flash->success(__('pair updated successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'coinpair_index']);
				}else{
					$this->add_system_log(200, 0, 3,'Coinpair 수정 실패(id : '.$id.')');
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
				$this->add_system_log(200, 0, 3,'Coinpair 수정 실패 (이미 존재)');
				$this->Flash->error(__('Coin Pair Already Exist','conversion'));
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
			$query->delete()->where(['id' => $this->request->data['id']])->execute();
			$this->add_system_log(200, 0, 4,'Coinpair 삭제 (id : '.$this->request->data['id'].')');
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
					$this->add_system_log(200, 0, 3,'Coinpair 상태 수정 (id : '.$id.')');
					$this->Flash->success(__('updated successfully.','conversion'));
					return $this->redirect(['controller'=>'Coin','action'=>'coinpairIndex']);
				}else{
					$this->add_system_log(200, 0, 3,'Coinpair 상태 수정 실패 (id : '.$id.')');
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
	
	
	

//	public function rewardlist()
//    {
//		$this->set('title','Transaction');
//		$this->loadModel('Transactions');
//		$this->loadModel('PrincipalWallet');
//			$this->set('display_type','BTC');
//
//			$limit = $this->setting['pagination'];
//			$type = "BTC";
//			$searchData = array();
//
//			$searchData['AND'][] = array("PrincipalWallet.remark"=>'airdrop reward');
//			$searchDataNew['AND'][] = array("PrincipalWallet.type"=>'bank_initial_deposit', "PrincipalWallet.status"=>'completed');
//
//			if ($this->request->is(['post' ,'put']) )
//			{
//				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
//				$search = $this->request->data;
//
//				if(!empty($search['pagination'])) $limit =  $search['pagination'];
//
//				// search by username
//				if(!empty($search['username'])){
//					$searchData['AND'][] = array("user.username like"=>"%".$search['username']."%");
//					$searchDataNew['AND'][] = array("user.username like"=>"%".$search['username']."%");
//				}
//
//                if(!empty($search['name'])){
//                    $searchData['AND'][] = array("user.name like"=>"%".$search['name']."%");
//                    $searchDataNew['AND'][] = array("user.name like"=>"%".$search['name']."%");
//                }
//
//				// search by date range
////				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' => $this->request->data['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->data['end_date']);
////				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
////				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
//
//				// saarch by coin type
//				if(!empty($search['coin_type'])){
//					$searchData['AND'][] = array("cryptocoin.id"=>$search['coin_type']);
//					$searchDataNew['AND'][] = array("cryptocoin.id"=>$search['coin_type']);
//				}
//
//				// saarch by coin type
//				if(!empty($search['status'])){
//					$searchData['AND'][] = array("PrincipalWallet.status"=>$search['status']);
//					$searchDataNew['AND'][] = array("PrincipalWallet.status"=>$search['status']);
//				}
//
//
//			}
//			$collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
//						    'contain'=>['user'=>['fields'=>['username','unique_id', 'name']],
//										'cryptocoin'=>['fields'=>['short_name']]],
//							'conditions'=>["OR"=>[$searchData,$searchDataNew]],
//						    'order'=>['PrincipalWallet.created_at'=>'desc'],
//							'limit' => $limit,
//						]);
//
//			$this->set('listing',$collectdata);
//			$this->set('type',$type);
//
//	}

//Reward List Start
    public function rewardlist()
    {
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');

        $limit = 20;
        $searchData = [];
        $searchDataTotal = [];
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        $coinList = $this->get_coin_list();
        $this->set('coinList',$coinList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) {
			$searchData['AND'][] = array('user.id' => $search['user_name']);
			$searchDataTotal['AND'][] = array('PrincipalWallet.user_id' => $search['user_name']);
		}
        if (!empty($search['coin_first_id'])){
			$searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
			$searchDataTotal['AND'][] = array('PrincipalWallet.cryptocoin_id' => $search['coin_first_id']);
		}
        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);

        if($this->request->query('export')){
            // Export
			$this->add_system_log(200, 0, 5, 'Reward List (보상 리스트) CSV 다운로드 (이름, 전화번호 등)');

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Coin','Amount','Type','Remark','Fees','Status','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->PrincipalWallet->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['PrincipalWallet.status'=>'completed',
                    'OR' =>[['PrincipalWallet.type' => 'bank_initial_deposit'],['PrincipalWallet.type' => 'purchase'],['PrincipalWallet.remark' => 'withdrawal airdrop reward']]]+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                //'limit' => $limit,
            ]);

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Coin'] = $data['cryptocoin']['short_name'];
                $arr['KRW Amount'] = round($data['amount'],0);
                $arr['Type'] = $data['type'];
                $arr['Remark'] = $data['remark'];
                $arr['Fees'] = round($data['fees'],0);
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'RewardsDetails'.$filename
            ));
            return $this->response;die;
        }

		$bankDepositSum = $this->PrincipalWallet->find("all",["fields"=>["total_bank_deposit"=>"SUM(amount)"],'conditions'=>['PrincipalWallet.status'=>'completed','PrincipalWallet.type' => 'bank_initial_deposit']+$searchDataTotal])->hydrate(false)->first();
		$bankDepositSumShow = !empty($bankDepositSum["total_bank_deposit"]) ? $bankDepositSum["total_bank_deposit"] : 0;
		 $this->set('bankDepositSumShow',$bankDepositSumShow);

		$purchaseSum = $this->PrincipalWallet->find("all",["fields"=>["total_purchase"=>"SUM(amount)"],'conditions'=>['PrincipalWallet.status'=>'completed','PrincipalWallet.remark' => 'airdrop reward']+$searchDataTotal])->hydrate(false)->first();
		$purchaseSumShow = !empty($purchaseSum["total_purchase"]) ? $purchaseSum["total_purchase"] : 0;
		 $this->set('purchaseSumShow',$purchaseSumShow);

        $adminWithdrawSum = $this->PrincipalWallet->find("all",["fields"=>["total_withdraw"=>"SUM(amount)"],'conditions'=>['PrincipalWallet.status'=>'completed','PrincipalWallet.remark' => 'withdrawal airdrop reward']+$searchDataTotal])->hydrate(false)->first();
        $adminWithdrawSumShow = !empty($adminWithdrawSum["total_withdraw"]) ? $adminWithdrawSum["total_withdraw"] : 0;
        $this->set('adminWithdrawSumShow',$adminWithdrawSumShow);

        $CTCWalletTransferSum = $this->PrincipalWallet->find("all",["fields"=>["total_purchase_ctc"=>"SUM(amount)"],'conditions'=>['PrincipalWallet.status'=>'completed','PrincipalWallet.remark' => 'CTC Wallet transfer']+$searchDataTotal])->hydrate(false)->first();
        $CTCWalletTransferSumShow = !empty($CTCWalletTransferSum["total_purchase_ctc"]) ? $CTCWalletTransferSum["total_purchase_ctc"] : 0;
        $this->set('ctcwWalletSumShow',$CTCWalletTransferSumShow);

		//print_r($bankDepositSum); die;
		 $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.status'=>'completed',
                'OR' =>[['PrincipalWallet.type' => 'bank_initial_deposit'],['PrincipalWallet.type' => 'purchase'],['PrincipalWallet.remark' => 'withdrawal airdrop reward'],['PrincipalWallet.remark' => 'erc20_purchase']]]+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);
        $this->set('listing',$collectdata);
    }

    //Reward List End


    public function usertransferslist()
    {
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');

        $limit = 20;
        $searchData = [];
        $searchDataTotal = [];
        if($this->request->query('page')) {

            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        $coinList = $this->get_coin_list();
        $this->set('coinList',$coinList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;
        $cases = 0;
        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('user.id' => $search['user_name']);
            $searchDataTotal['AND'][] = array('PrincipalWallet.user_id' => $search['user_name']);
        }
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
            $searchDataTotal['AND'][] = array('PrincipalWallet.cryptocoin_id' => $search['coin_first_id']);
        }
        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);

        if($this->request->query('export')){
            // Export
//                if($search['export']=='c') $filename = time().'export.csv';
//                else  $filename = 'export.xlsx';
			$this->add_system_log(200, 0, 5, '고객 이체 기록 (Account transfers by Users) - CSV 다운로드 (이름, 전화번호 등)');

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Coin','Amount','Type','Remark','Fees','Status','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->PrincipalWallet->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['PrincipalWallet.status'=>'completed', 'PrincipalWallet.remark !='=>'withdrawal airdrop reward', 'PrincipalWallet.type IN ' =>
                        ['transfer_to_trading_account', 'transfer_from_trading_account','bank_initial_withdraw','withdrawal']],
                'order'=>['PrincipalWallet.id'=>'desc'],
                //'limit' => $limit,

            ]);

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Coin'] = $data['cryptocoin']['short_name'];
                $arr['KRW Amount'] = round($data['amount'],0);
                $arr['Type'] = $data['type'];
                $arr['Remark'] = $data['remark'];
                $arr['Fees'] = round($data['fees'],0);
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'UserTransfersDetails'.$filename
            ));
            return $this->response;die;
        }
        //}

      /*   $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.status'=>'completed', 'PrincipalWallet.remark !='=>'withdrawal airdrop reward', 'PrincipalWallet.type IN ' =>
                    ['transfer_to_trading_account', 'transfer_from_trading_account','bank_initial_withdraw','withdrawal']],
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata); */

        $mainToTradingSum = $this->PrincipalWallet->find("all",["fields"=>["total_main_trading"=>"SUM(amount)"],'conditions'=>['PrincipalWallet.status'=>'completed','PrincipalWallet.type' => 'transfer_to_trading_account']+$searchDataTotal])->hydrate(false)->first();
        $mainToTradingSumShow = !empty($mainToTradingSum["total_main_trading"]) ? $mainToTradingSum["total_main_trading"] : 0;
        $this->set('mainToTradingSumShow',$mainToTradingSumShow);

        $tradingToMainSum = $this->PrincipalWallet->find("all",["fields"=>["total_trading_main"=>"SUM(amount)"],'conditions'=>['PrincipalWallet.status'=>'completed','PrincipalWallet.type' => 'transfer_from_trading_account']+$searchDataTotal])->hydrate(false)->first();
        $tradingToMainSumShow = !empty($tradingToMainSum["total_trading_main"]) ? $tradingToMainSum["total_trading_main"] : 0;
        $this->set('tradingToMainSumShow',$tradingToMainSumShow);

        $withdrawSum = $this->PrincipalWallet->find("all",["fields"=>["total_withdraw"=>"SUM(amount)"],'conditions'=>['PrincipalWallet.status'=>'completed', 'PrincipalWallet.remark is NULL', 'OR' =>[['PrincipalWallet.type' => 'bank_initial_withdraw'],['PrincipalWallet.type' => 'withdrawal']]]+$searchDataTotal])->hydrate(false)->first();
        $withdrawSumShow = !empty($withdrawSum["total_withdraw"]) ? $withdrawSum["total_withdraw"] : 0;
        $this->set('withdrawSumShow',$withdrawSumShow);

        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.status'=>'completed',
						   'PrincipalWallet.type IN ' =>['transfer_to_trading_account', 'transfer_from_trading_account','bank_initial_withdraw','withdrawal'],
						   "OR"=>[['PrincipalWallet.remark IS NULL'],['PrincipalWallet.remark'=>"bank_initial_withdraw"]],
						   ]+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);
    }


	public function rewardlistSearch()
	{
		$type = "BTC";
		$this->loadModel('Transactions');
		$this->loadModel('PrincipalWallet');
		if ($this->request->is('ajax')) 
		{ 
			$limit = $this->setting['pagination'];
			$searchData = array();
			$searchData['AND'][] = array("PrincipalWallet.remark"=>'airdrop reward'); 
			$searchDataNew['AND'][] = array("PrincipalWallet.type"=>'bank_initial_deposit', "PrincipalWallet.status"=>'completed');
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
						    'contain'=>['user'=>['fields'=>['username','unique_id', 'name']],
										'cryptocoin'=>['fields'=>['short_name']]],
							'conditions'=>["OR"=>[$searchData,$searchDataNew]],
						    'order'=>['PrincipalWallet.created_at'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		}
	
	}

    public function withdrawalrewardlist()
    {
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');

        $limit = 20;
        $searchData = [];
        $searchDataTotal = [];
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        $coinList = $this->get_coin_list();
        $this->set('coinList',$coinList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('user.id' => $search['user_name']);
            $searchDataTotal['AND'][] = array('PrincipalWallet.user_id' => $search['user_name']);
        }
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
            $searchDataTotal['AND'][] = array('PrincipalWallet.cryptocoin_id' => $search['coin_first_id']);
        }
        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);

        if($this->request->query('export')){
            // Export
			$this->add_system_log(200, 0, 5, 'Withdrawn Rewards List - CSV 다운로드 (이름, 전화번호 등)');

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Coin','Amount','Type','Remark','Fees','Status','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->PrincipalWallet->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['PrincipalWallet.status'=>'completed', 'PrincipalWallet.remark' => 'withdrawal airdrop reward']+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                //'limit' => $limit,
            ]);

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Coin'] = $data['cryptocoin']['short_name'];
                $arr['KRW Amount'] = round($data['amount'],0);
                $arr['Type'] = $data['type'];
                $arr['Remark'] = $data['remark'];
                $arr['Fees'] = round($data['fees'],0);
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'WithdrawalRewardsDetails'.$filename
            ));
            return $this->response;die;
        }

        $adminWithdrawSum = $this->PrincipalWallet->find("all",["fields"=>["total_withdraw"=>"SUM(amount)"],'conditions'=>['PrincipalWallet.status'=>'completed','PrincipalWallet.remark' => 'withdrawal airdrop reward']+$searchDataTotal])->hydrate(false)->first();
        $adminWithdrawSumShow = !empty($adminWithdrawSum["total_withdraw"]) ? $adminWithdrawSum["total_withdraw"] : 0;
        $this->set('adminWithdrawSumShow',$adminWithdrawSumShow);

        //print_r($bankDepositSum); die;
        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.status'=>'completed', 'PrincipalWallet.remark' => 'withdrawal airdrop reward']+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);
		
	}

	/*
	public function  withdrawalrewardlist()
    {
		$this->set('title','Transaction');
		$this->loadModel('Transactions');
		$this->loadModel('PrincipalWallet');
			$this->set('display_type','BTC');
			
			$limit = $this->setting['pagination'];
			$type = "BTC";
			$searchData = array();
			
			$searchData['AND'][] = array("PrincipalWallet.remark"=>'withdrawal airdrop reward'); 
			
			if ($this->request->is(['post' ,'put']) ) 
			{
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				
				if(!empty($search['pagination'])) $limit =  $search['pagination'];
				
				// search by username
				if(!empty($search['username'])){
					$searchData['AND'][] = array("user.username like"=>"%".$search['username']."%");
				}
                if(!empty($search['name'])){
                    $searchData['AND'][] = array("user.name like"=>"%".$search['name']."%");
                }
				// search by date range
//				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' => $this->request->data['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->data['end_date']);
//				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
//				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
				
				// saarch by coin type
				if(!empty($search['coin_type'])){
					$searchData['AND'][] = array("cryptocoin.id"=>$search['coin_type']);
				}
				
				// saarch by coin type
				if(!empty($search['status'])){
					$searchData['AND'][] = array("PrincipalWallet.status"=>$search['status']);
				}
				
				
			}
			$collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
						    'contain'=>['user'=>['fields'=>['username','unique_id','name']],
										'cryptocoin'=>['fields'=>['short_name']]],
							'conditions'=>$searchData,
						    'order'=>['PrincipalWallet.id'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		
	}
	*/
	public function withdrawalrewardlistSearch()
	{
		$type = "BTC";
		$this->loadModel('Transactions');
		$this->loadModel('PrincipalWallet');
		if ($this->request->is('ajax')) 
		{ 
			$limit = $this->setting['pagination'];
			$searchData = array();
			$searchData['AND'][] = array("PrincipalWallet.remark"=>'withdrawal airdrop reward'); 
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
						    'contain'=>['user'=>['fields'=>['username','unique_id','name']],
										'cryptocoin'=>['fields'=>['short_name']]],
							'conditions'=>$searchData,
						    'order'=>['PrincipalWallet.id'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		}
	
	}
		
	
	
	 
}
