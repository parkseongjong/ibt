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
use Cake\Mailer\Email;


class ContactUsController extends AppController
{
	
    
	
    public function manage(){
		$this->set('title' , 'Contact us');
		$searchData = array();
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['name'] != '') $searchData['AND'][] =array('name LIKE' => '%'.$search['name'].'%');
			if($search['email'] != '') $searchData['AND'][] =array('email LIKE' => '%'.$search['email'].'%');
			
			if($search['status'] != '') $searchData['AND'][] = array('status'=>$search['status']);
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(created) >= ' => $this->request->data['start_date'],'DATE(created) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(created)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(created)' => $search['end_date']);
			
		}
		$this->set('ContactUs',$this->Paginator->paginate(
			$this->ContactUs, [
				'limit' => $this->setting['pagination'],
				'order'=>['id'=>'desc'],
				'conditions' => $searchData
			])
		);
		
	}
	
	
	public function search()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				if($search['name'] != '') $searchData['AND'][] =array('name LIKE' => '%'.$search['name'].'%');
				if($search['email'] != '') $searchData['AND'][] =array('email LIKE' => '%'.$search['email'].'%');
				
				if($search['status'] != '') $searchData['AND'][] = array('status'=>$search['status']);
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(created) >= ' => $this->request->data['start_date'],'DATE(created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(created)' => $search['end_date']);
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$this->set('ContactUs',$this->Paginator->paginate(
			$this->ContactUs, [
				'limit' => $this->setting['pagination'],
				'order'=>['id'=>'desc'],
				'conditions' => $searchData
			])
		);
			
			
		}
	}
	
	public function Detail($id = null)
    {
		$this->set('title' , 'Contact Us');
        $ContactUs = $this->ContactUs->get($id);
		if ($this->request->is(['post' ,'put'])) 
		{
			$ContactUs = $this->ContactUs->patchEntity($ContactUs, $this->request->data,['validate' => 'reply']);
			if(!$ContactUs->errors()) $ContactUs->status =1;
		
			if ($this->ContactUs->save($ContactUs)) 
			{
				// send email
				if(SENDMAIL==1 )
				{
					// success email
					
					$email = new Email('default');
					$email->from([$this->setting['email_from'] =>$this->setting['email_name'] ] )
					->to([$ContactUs->email])
					->subject($this->request->data['reply_subject'])
					->emailFormat('html')
					->send($this->request->data['reply_message']); 
				}
				$this->Flash->success(__('Reply sent.'));
                return $this->redirect(['controller'=>'contact_us','action' => 'manage']);
            }else{
				$this->Flash->error(__('Some Errors Occurred.'));
			}
        }
      
       $this->set('ContactUs', $ContactUs);
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
	
	
}
