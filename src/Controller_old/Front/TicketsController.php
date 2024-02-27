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
use Cake\View\Helper\Utility;
use Cake\Mailer\Email;



class TicketsController extends AppController
{
    public function index()
    {

        $this->set('title',' Ticket');
        $this->loadModel('Subjects');
        $subjects = $this->Subjects->find('list',array('keyField'=>'id' , 'valueField'=> 'subject'))->toArray();
        $this->set('subjects',$subjects);
        $searchData['AND'][] =['user_id'=>$this->Auth->user('id')];
        $limit = $this->setting['pagination'];
        if ($this->request->is(['post' ,'put']) )
        {
            if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            //pr($search);die;
            if($search['pagination'] != '') $limit =  $search['pagination'];
            if($search['title'] != '') $searchData['AND'][] =array('Tickets.title LIKE' => '%'.$search['title'].'%');
            if($search['ticket_id'] != '') $searchData['AND'][] =array('Tickets.ticket_id' =>$search['ticket_id']);

            if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(LoginLogs.created) >= ' => $this->request->data['start_date'],'DATE(Tickets.created) <= ' => $this->request->data['end_date']);
            else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Tickets.created)' => $search['start_date']);
            else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Tickets.created)' => $search['end_date']);

        }
        $this->set('listing', $this->Paginator->paginate($this->Tickets, [
            'conditions'=>$searchData,
            'contain'=>['TicketMessages','user','subjects'],
            'order'=>['Tickets.id'=>'desc'],
            'limit' =>  $limit,

        ]));
    }
    public function addTickets()
    {
        if($this->request->is(['ajax'])){

            $ticket = $this->Tickets->newEntity();
            $data = $this->request->data;
            $data_ticket['ticket_id'] = $this->Auth->user('id').time();
            $data_ticket['user_id'] = $this->Auth->user('id');
            $data_ticket['subject_id'] = $data['subject_id'];
            $data_ticket['title'] = $data['title'];
            $data_ticket['media'] = $data['media'];
            if(isset($_FILES['media']) && $_FILES['media']['tmp_name'] !='')
            {
                $filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['media']['name']);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $filename = basename($filename, '.' . $ext) . time() . '.jpg';
                if ($this->uploadImage($_FILES['media']['tmp_name'], $_FILES['media']['type'], 'uploads/user_image/', $filename)){
                    $this->createThumbnail($filename, 'uploads/user_image', 'uploads/user_thumb',200,200);
                    $data_ticket['media'] = $filename;
                }
            }
            $tickets = $this->Tickets->patchEntity($ticket, $data_ticket);
            $this->loadModel('Users');
            $this->loadModel('Subjects');
            $this->loadModel('TicketMessages');
            if($tickets_data = $this->Tickets->save($tickets)){
                $data_msg['ticket_id'] = $tickets_data['ticket_id'];
                $data_msg['user_id'] = $tickets_data['user_id'];
                $data_msg['message'] = $data['message'];
                $data_msg['user_type'] = 'U';
                $ticket_msg = $this->TicketMessages->newEntity();
                $msgs = $this->TicketMessages->patchEntity($ticket_msg,$data_msg);
                if($ticket_m = $this->TicketMessages->save($msgs))
                {
                    if(SENDMAIL == 1)
                    {
                        //send mail to user
                        $subject =  $this->Subjects->get($tickets_data['subject_id']);
                        $messages = $this->TicketMessages->get($ticket_m->id,['contain'=>['user','ticket']]);
                        //   pr($messages);die;
                        $tickets_data['message'] = $messages;
                        $tickets_data['subject'] = $subject;
                        $tickets_data['msg'] = 'Your ticket has been generated successfully.';
                        $email = new Email('default');
                        $email->viewVars(['data'=>$tickets_data]);
                        $email->from([$this->setting['email_from']] )
                            ->to($this->Auth->user('email'))
                            ->subject('Ticket Success')
                            ->emailFormat('html')
                            ->template('ticket_query')
                            ->send();

                        //send mail to admin for new query
                        $admin = $this->Users->get(1);
                        $tickets_data['msg'] = 'New Ticket generated. Details are :';
                        $email = new Email('default');
                        $email->viewVars(['data'=>$tickets_data]);
                        $email->from([$this->setting['email_from']] )
                            ->to($admin->email)
                            ->subject('New Ticket')
                            ->emailFormat('html')
                            ->template('ticket_query')
                            ->send();

                    }
                }

                $res['success']= 1;
                $res['string'] = '<div class="alert-success"><strong>Success! </strong>Your query has been submitted successfully.</div>';
            }else{
                $res['success']= 0;
                foreach($tickets_data->errors() as $field_key =>  $error_data)
                {
                    foreach($error_data as $error_text)
                    {

                        $res['string'] = '<div class="alert-danger1"><strong>Error! </strong>'.$error_text.'</div>';
                        break 2;
                    }

                }
            }
            echo json_encode($res);
            exit;
        }
    }
    public function messages($ticket_id)
    {
        $this->set('title','Ticket: '.$ticket_id);
        $this->loadModel('TicketMessages');
        $ticket = $this->Tickets->find('all',['fields'=>['status'],'conditions'=>['user_id'=>$this->Auth->user('id'),'ticket_id'=>$ticket_id]])->first();
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
            $getTicket = $this->Tickets->find('all',['fields'=>['id'],'conditions'=>['user_id'=>$this->Auth->user('id'),'ticket_id'=>$data['ticket_id']]])->hydrate(false)->first();
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
    public function search()
    {

        if ($this->request->is('ajax'))
        {
            $limit = $this->setting['pagination'];
            $searchData['AND'][] = ['user_id'=>$this->Auth->user('id') ];
            if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            //pr($search);die;
            if($search['pagination'] != '') $limit =  $search['pagination'];
            if($search['title'] != '') $searchData['AND'][] =array('Tickets.title LIKE' => '%'.$search['title'].'%');
            if($search['ticket_id'] != '') $searchData['AND'][] =array('Tickets.ticket_id' =>$search['ticket_id']);

            if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Tickets.created) >= ' => $this->request->data['start_date'],'DATE(Tickets.created) <= ' => $this->request->data['end_date']);
            else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Tickets.created)' => $search['start_date']);
            else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Tickets.created)' => $search['end_date']);
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
            $this->set('listing', $this->Paginator->paginate($this->Tickets, [
                'conditions'=>$searchData,
                'contain'=>['TicketMessages','user','subjects'],
                'order'=>['Tickets.id'=>'desc'],
                'limit' =>  $limit,

            ]));

        }
    }


}
