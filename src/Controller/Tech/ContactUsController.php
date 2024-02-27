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
use Cake\Mailer\Email;
use Cake\Network\Exception\NotFoundException;

class ContactUsController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
    public function manage(){
		$this->set('title' , 'Contact us');
		$this->loadModel('BoardQna');
		$searchData = array();
		$limit = 10;
		if ($this->request->is(['post' ,'put']) ) 
		{
			$limit = 10;
			if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
			$search = $this->request->query;
			//pr($search);die;
			if($search['id'] != '') $searchData['AND'][] =array('BoardQna.id' => $search['id']);
			if($search['username'] != '') $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			if($search['email'] != '') $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			if($search['status'] != '') $searchData['AND'][] = array('BoardQna.status'=>$search['status']);
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Support.created_at) >= ' => $this->request->query['start_date'],'DATE(created_at) <= ' => $this->request->query['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['end_date']);
			
		}
		$searchData['AND'][] =array('BoardQna.users_id !=' => 1);
		$collectdata =  $this->Paginator->paginate(
			$this->BoardQna, [
				'contain'=>['user'],
				'limit' => $limit,
				'order'=>['id'=>'desc'],
				'conditions' => $searchData
			]);
		try {	
			$this->set('ContactUs', $collectdata);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$this->set('ContactUs', $collectdata);
		}
	}
	
	
	
	public function conversation($support_id)
	{
		$this->loadModel('Support');
		$this->loadModel('SupportConversation');
		$this->set('title','conversaction');
		//$userId = $this->Auth->user('id');
		
		if(empty($support_id)){
			return $this->redirect(['controller'=>'ContactUs','action'=>'manage']);
		}
		
		$findTicket=$this->Support->find('all',['fields'=>['id','user_id'],
											'conditions'=>['id'=>$support_id],
											'order'=>['Support.id'=>'desc']])
											->hydrate(false)
											->first();
		if(empty($findTicket)){
			return $this->redirect(['controller'=>'ContactUs','action'=>'manage']);
		}		
		if(empty($findTicket['user_id'])){
			echo "User Is Not Found";
			die;
		}
		$userId = $findTicket['user_id'];
		$user  = $this->Users->get($userId);
		$this->set('user',$user);		 
		//get current date logged in users
		
		$this->Support->updateAll(['admin_reply'=>'Y'],['id'=>$support_id]);
		if ($this->request->is(['post','put'])) {
			
			
			$message = filter_var($this->request->data['message'], FILTER_SANITIZE_STRING);

			
			$insertArr = [];
			$insertArr['message'] = $message;
			$insertArr['support_id'] = $support_id;
			$insertArr['user_id'] = 1;
			
			$supportData = $this->SupportConversation->newEntity();
			$supportData = $this->SupportConversation->patchEntity($supportData, $insertArr);
			if($SupportConversation = $this->SupportConversation->save($supportData)){
				
				$this->Support->updateAll(['admin_reply'=>'Y'],['id'=>$support_id]);
						
				$this->Flash->success(__('Your Message submitted successfully.'));
				return $this->redirect(['controller'=>'ContactUs','action'=>'conversation',$support_id]);
			}
			else {
				$this->Flash->error(__('Unable to submit message.'));
				return $this->redirect(['controller'=>'ContactUs','action'=>'conversation',$support_id]);
			}
					
			 
		}
		
		
		
		$logs = $this->SupportConversation->find();
		$create_date = $logs->func()->date_format([
                'SupportConversation.created_at' => 'literal',
                "'%d %M, %Y %h:%i %p'" => 'literal'
            ]);
		$tickets=$this->SupportConversation->find('all',[/* 'fields'=>['date'=>$create_date,'issue_type','issue_file','issue','status','response','admin_reply','id'], */
											'conditions'=>['OR'=>[['SupportConversation.user_id'=>$userId,'SupportConversation.support_id'=>$support_id],['SupportConversation.user_id'=>1,'SupportConversation.support_id'=>$support_id]]],
											'contain'=>['support'],
											'order'=>['SupportConversation.id'=>'asc']])
											->hydrate(false)
											->toArray();
											
						//print_r($tickets);		die; 			
		$this->set('tickets',$tickets);	
		$this->set('support_id',$support_id);	
	}	
	
	public function search()
	{
		$this->loadModel('Support');
		if ($this->request->is('ajax')) {
			$searchData = array();
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			
			$this->set('ContactUs',$this->Paginator->paginate(
				$this->Support, [
					'contain'=>['user'=>['fields'=>['username','email']]],
					'limit' => $this->setting['pagination'],
					'order'=>['id'=>'desc'],
					'conditions' => $searchData
				])
			);
			
			
		}
	}
	
	public function Detail($id = null)
    {
		$this->loadModel('Support');
		$this->set('title' , 'Contact Us');
		$ContactUsData = $this->Support->find('all',['conditions'=>['Support.id'=>$id],'contain'=>['user']])->hydrate(false)->first();
        $ContactUs = $this->Support->get($id);
		
		$getAllMessage = $this->Support->find('all',['conditions'=>['Support.user_id'=>$ContactUs->user_id],'contain'=>['user']])->hydrate(false)->toArray();
		
		$this->set('getAllMessage',$getAllMessage);
		
		if ($this->request->is(['post' ,'put'])) 
		{
			$ContactUs = $this->Support->patchEntity($ContactUs, $this->request->data,['validate' => 'reply']);
			if(!$ContactUs->errors()) $ContactUs->status ='resolved';
		
			if ($this->Support->save($ContactUs)) 
			{
				// send email
				/* if(SENDMAIL==1 )
				{
					// success email
					
					$email = new Email('default');
					$email->from([$this->setting['email_from'] =>$this->setting['email_name'] ] )
					->to([$ContactUs->email])
					->subject($this->request->data['reply_subject'])
					->emailFormat('html')
					->send($this->request->data['reply_message']); 
				} */
				$this->Flash->success(__('Reply sent.'));
                return $this->redirect(['controller'=>'contact_us','action' => 'manage']);
            }else{
				$this->Flash->error(__('Some Errors Occurred.'));
			}
        }
      
       $this->set('ContactUs', $ContactUs);
	   $this->set('ContactUsData', $ContactUsData);
    }
    
	
	public function delete()
	{
		if ($this->request->is('ajax')) { 
			$query = $this->ContactUs->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			echo 1;
		}
		die;
		
	}
	
	public function updatereply()
	{
		
		if ($this->request->is('ajax')) { 
			$this->loadModel('BoardQna');
			$insertArr['reply'] = $this->request->data['reply'];
			$updateIt = $this->BoardQna->updateAll($insertArr, ['id' =>$this->request->data['id']]);

			$this->add_system_log(200, $this->request->data['user_id'], 3, '1대1 문의 답변 완료 (문의 번호 : '.$this->request->data['id'].')');
			$respArr=['status'=>'true','message'=>"Sucess"];
			echo json_encode($respArr); die;
		}
		die;
		
	}
	
}
