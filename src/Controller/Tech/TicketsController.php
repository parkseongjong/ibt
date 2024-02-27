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
use Cake\View\Helper\Utility;



class TicketsController extends AppController
{
	public function index()
	{
        $this->set('title',' Ticket');

		$searchData = array();
		$limit = $this->setting['pagination'];
       if ($this->request->is(['post' ,'put'])) {

               if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
               $search = $this->request->data;
               //pr($search);die;
               if($search['pagination'] != '') $limit =  $search['pagination'];
               if($search['title'] != '') $searchData['AND'][] =array('Tickets.title LIKE' => '%'.$search['title'].'%');
               if($search['ticket_id'] != '') $searchData['AND'][] =array('Tickets.ticket_id' =>$search['ticket_id']);

               if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Tickets.created) >= ' => $this->request->data['start_date'],'DATE(Tickets.created) <= ' => $this->request->data['end_date']);
               else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Tickets.created)' => $search['start_date']);
               else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Tickets.created)' => $search['end_date']);

		}
			
           $this->set('listing', $this->Paginator->paginate($this->Tickets, [
				'contain'=>['user'=>['fields'=>['name']],'TicketMessages' ,'user','subjects'],
				 'conditions'=>$searchData,
				 'order'=>['Tickets.id'=>'desc'],
				 'limit' => $limit,
				
			]));
    }
     public function search()
	{
		
		if ($this->request->is('ajax')) 
		{
            $limit = $this->setting['pagination'];
            $searchData = [];
		    if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            //pr($search);die;
            if($search['pagination'] != '') $limit =  $search['pagination'];
            if($search['title'] != '') $searchData['AND'][] =array('Tickets.title LIKE' => '%'.$search['title'].'%');
            if($search['ticket_id'] != '') $searchData['AND'][] =array('Tickets.ticket_id' =>$search['ticket_id']);

            if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Tickets.created) >= ' => $this->request->data['start_date'],'DATE(Tickets.created) <= ' => $this->request->data['end_date']);
            else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Tickets.created)' => $search['start_date']);
            else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Tickets.created)' => $search['end_date']);
			else $this->set('serial_num',1);
			$this->set('listing', $this->Paginator->paginate($this->Tickets, [
				'contain'=>['TicketMessages','user','subjects'],
				 'conditions'=>$searchData,
				 'order'=>['Tickets.id'=>'desc'],
				 'limit' => $limit,
				
			]));
		
			
		}
	
	}
	
	public function messages($ticket_id)
    {
		$this->set('title','Ticket: '.$ticket_id);
        $this->loadModel('TicketMessages');
        $ticket = $this->Tickets->find('all',['fields'=>['status'],'conditions'=>['ticket_id'=>$ticket_id]])->first();
		if(!empty($ticket)){
        
			$msgs = $this->TicketMessages->find('all',['contain'=>['user'=>['fields'=>['name','image']]],'conditions'=>['ticket_id'=>$ticket_id]])->hydrate(false)->toArray();
			$this->set(['msgs'=>$msgs,'ticket'=>$ticket,'ticket_id'=>$ticket_id]);
		}
        else $this->redirect(['controller'=>'tickets','action'=>'index']);
        
    }

   
    public function updateStatus()
    {
        if($this->request->is('ajax'))
        {
            $data = $this->request->data;
            $getTicket = $this->Tickets->find('all',['fields'=>['id'],'conditions'=>['ticket_id'=>$data['ticket_id']]])->hydrate(false)->first();
            if(!empty($getTicket)){
				$trans = $this->Tickets->get($getTicket['id']);
				$trans->status = 'C';
			   if( $this->Tickets->save($trans))
			   {
				   $error = 'ok';
			   }
			   else
			   {
				   $error = 'Some error occurred';
			   }
			}else  $error = 'Some error occurred';
           
            echo json_encode($error);die;
        }
    }
     public function updateMessage()
    {
        if($this->request->is('ajax'))
        {
            $this->loadModel('TicketMessages');
            $data = $this->request->data;
            $message = $this->Tickets->newEntity();
            $messages = $this->TicketMessages->patchEntity($message, $data);
            if($this->Auth->user('image') != '') $image= BASEURL.'uploads/user_thumb/'.$this->Auth->user('image');
            else $image= BASEURL.'user200.jpg';
            if($this->TicketMessages->save($messages))
            {
                $dt['msg'] = '<li class="right clearfix"><span class="chat-img pull-right"><img width="50px" src="'.$image.'" class="img-circle" /></span><div class="chat-body clearfix"><div class="header"><small class=" text-muted"><span class="glyphicon glyphicon-time"></span>Now</small><strong class="pull-right primary-font">'.$this->Auth->user('name').'</strong> </div><p class="pull-right">'.$data["message"].'</p></div></li>';
                $dt['st'] = 'OK';
            }
            else
            {
                $dt['msg'] = '';
                $dt['st'] = 'nok';
            }
            echo json_encode($dt);die;
        }
    }


}
