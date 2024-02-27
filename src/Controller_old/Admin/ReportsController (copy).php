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

class ReportsController extends AppController
{
	 public function report($type=null)
    {
        $this->set('title','Transaction');
      
        if( $type=='BTC' || $type=='Galaxy')
        {
            $limit =  $this->setting['pagination'];
            $searchData = array();
            $coin_arr = array('Galaxy'=>'Z','BTC'=>'B');
            $searchData['AND'][] = array('user_id !='=>1,'coin_type'=>$coin_arr[$type],'trans_type IN'=>['R','S','Ref']);
			
         
				if ($this->request->is(['post' ,'put']) ) 
				{
					if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
					$search = $this->request->data;
					
					
					if($search['pagination'] != '') $limit =  $search['pagination'];
					//pr($search);die;
					if($search['to_user'] != '') $searchData['AND'][] =array('user.name LIKE' => '%'.$search['to_user'].'%');
					if($search['from_user'] != '') $searchData['AND'][] =array('from_user.name LIKE' => '%'.$search['from_user'].'%');
					if($search['amount'] != '') $searchData['AND'][] =array('amount' => $search['amount']);
					if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
					else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
					else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
					
					if($search['export'] !=''){
						// Export
						
					}
				}
              
            

            $this->set('listing',$this->Paginator->paginate($this->Transactions, [

                'contain'=>['user'=>['fields'=>['name','unique_id']],'from_user'=>['fields'=>['name','unique_id']]],
                'conditions'=>$searchData,
                'order'=>['Transactions.id'=>'desc'],
                'limit' => $limit,
            ]));
           
            $this->set('type',$coin_arr[$type]);
            $this->set('display_type',$type);
        }else return $this->redirect(['controller'=>'pages','action' => 'dashboard']);
    }

    public function reportSearch()
    {

        if ($this->request->is('ajax'))
        {
          
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;

            $limit =  $this->setting['pagination'];
            $searchData = array();
            $searchData['AND'][] = array('user_id !='=>1,'coin_type'=>$search['type'],'trans_type IN'=>['R','S','Ref']);

            if($search['pagination'] != '') $limit =  $search['pagination'];
			//pr($search);die;
			if($search['to_user'] != '') $searchData['AND'][] =array('user.name LIKE' => '%'.$search['to_user'].'%');
			if($search['from_user'] != '') $searchData['AND'][] =array('from_user.name LIKE' => '%'.$search['from_user'].'%');
			if($search['amount'] != '') $searchData['AND'][] =array('Transactions.amount' => $search['amount']);
			if(isset($search['transaction_id']) && ($search['transaction_id']!='')) $searchData['AND'][] =array('transaction_id' => $search['transaction_id']);
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
					

            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);
			$this->set('type',$search['type']);
           $this->set('listing',$this->Paginator->paginate($this->Transactions, [

                'contain'=>['user'=>['fields'=>['name','unique_id']],'from_user'=>['fields'=>['name','unique_id']]],
                'conditions'=>$searchData,
                'order'=>['Transactions.id'=>'desc'],
                'limit' => $limit,
            ]));

        }

    }


	public function users()
    {
        $this->set('title' , 'Users');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['name'] != '') $searchData['AND'][] =array('Users.name LIKE' => '%'.$search['name'].'%');
			if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
			if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
			if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number LIKE' => '%'.$search['phone_number'].'%');
			if($search['unique_id'] != '') $searchData['AND'][] =array('Users.unique_id LIKE' => '%'.$search['unique_id'].'%');
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);
            if($search['export'] !=''){
                // Export
                if($search['export'] == 'c')
                {
                    $filename = 'export.csv';
                }
                $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."upload/".$filename);
                $headers = array('#','Username','Name','Email','Phone number','Wallet','Date Of Registration','IP','Galaxy coins','BTC coins','Sponser');
                fputcsv($file,$headers);
                $users =  $this->Users->find('all',[
                    'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','referral_user.name'],
                    'contain'=>['referral_user'],
                    'conditions' => $searchData,
                    'limit' => $limit,
                    'order'=>['id'=>'desc']

                ]);

                $k = 1;
                foreach ($users as $k=>$data)
                {
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Username'] = $data['username'];
                    $arr['Name'] = $data['name'];
                    $arr['Email'] = $data['email'];
                    $arr['Phone number'] = $data['phone_number'];
                    $arr['Wallet'] = $data['unique_id'];
                    $arr['Date Of Registration'] = date('d M Y',strtotime($data['created']));
                    $arr['IP'] = $data['ip_address'];
                    $arr['Galaxy coins'] = $data['ZUO'];
                    $arr['BTC coins'] = $data['BTC'];
                    $arr['Sponser'] = $data['referral_user']['name'];
                    fputcsv($file,$arr);
                    $k++;
                }
                pr($arr);die;
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'Users Report'.time().'.xls'
                ));
                return $this->response;die;
            }

		}
		$this->set('users', $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','referral_user.name'],
            'contain'=>['referral_user'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]));
    }
    public function search()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				if($search['pagination'] != '') $limit =  $search['pagination'];
				if($search['name'] != '') $searchData['AND'][] =array('Users.name LIKE' => '%'.$search['name'].'%');
				if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
				if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number LIKE' => '%'.$search['phone_number'].'%');
				if($search['unique_id'] != '') $searchData['AND'][] =array('Users.unique_id LIKE' => '%'.$search['unique_id'].'%');
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$this->set('users', $this->Paginator->paginate($this->Users, [
				'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','referral_user.name'],
				'contain'=>['referral_user'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]));
			
			
		}
	}
	public function logs()
    {
		$this->loadModel('LoginLogs');
        $this->set('title' , 'Login Logs');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['user.enabled'=>'Y'];
		if ($this->request->is(['post' ,'put']) )
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['name'] != '') $searchData['AND'][] =array('user.name LIKE' => '%'.$search['name'].'%');
			if($search['ip'] != '') $searchData['AND'][] =array('LoginLogs.ip_address' =>$search['ip']);
		
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(LoginLogs.created) >= ' => $this->request->data['start_date'],'DATE(LoginLogs.created) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(LoginLogs.created)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(LoginLogs.created)' => $search['end_date']);
			
		}
		$this->set('logs', $this->Paginator->paginate($this->LoginLogs, [
            'fields'=>['user.name','ip_address','LoginLogs.created'],
            'contain'=>['user'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['LoginLogs.id'=>'desc']

        ]));
    }
	public function logSearch()
	{
		if ($this->request->is('ajax')) {
			$this->loadModel('LoginLogs');
			$searchData = array();
			$limit =  $this->setting['pagination'];
			$searchData['AND'][] =['user.enabled'=>'Y'];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				if($search['pagination'] != '') $limit =  $search['pagination'];
				if($search['name'] != '') $searchData['AND'][] =array('user.name LIKE' => '%'.$search['name'].'%');
				if($search['ip'] != '') $searchData['AND'][] =array('LoginLogs.ip_address' =>$search['ip']);
			
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(LoginLogs.created) >= ' => $this->request->data['start_date'],'DATE(LoginLogs.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(LoginLogs.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(LoginLogs.created)' => $search['end_date']);
				}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$this->set('logs', $this->Paginator->paginate($this->LoginLogs, [
				'fields'=>['user.name','ip_address','LoginLogs.created'],
				'contain'=>['user'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['LoginLogs.id'=>'desc']

			]));
			
			
		}
	}
	
	
   
  
    
}
