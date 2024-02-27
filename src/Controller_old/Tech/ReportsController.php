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
use Cake\Routing\Router;

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
						if($search['export']=='c') $filename = 'export.csv';
						else  $filename = 'export.xlsx';
						$file = fopen(WWW_ROOT."uploads/".$filename,"w");
						if($search['type']=='Z') $headers = array('#','User','From','Coins','Reason','Date');
						else $headers = array('#','User','From','Coins','Transaction id','Reason','Date');
						
						fputcsv($file,$headers);
						
						$query = $this->Transactions->find('all',[
							'contain'=>['user'=>['fields'=>['name','unique_id']],'from_user'=>['fields'=>['name','unique_id']]],
							'conditions'=>$searchData,
							'order'=>['Transactions.id'=>'desc']
						])->hydrate(false)->toArray();
						$k=1;
						foreach($query as $data){
							$reason = '';
							 if($data['coin_type'] == 'Z')
							 {
								 if($data['from_user_id'] == 1) $reason = 'Admin sent';
								 elseif($data['from_user_id'] == $data['user_id']) $reason = 'Convert Btc to Galaxy';
								 else $reason = 'Referral Galaxy';
							 }
							 else
							 {
								 if($data['user_id'] ==$data['from_user_id']) $reason = 'Convert Btc to Galaxy.';
								 else if($data['from_user_id']==1 && $data['status'] =='T') $reason = 'Buy Btc';
								 else $reason = 'Admin sent.';
							}
							$arr = array();
							$arr['#'] = $k;
							$arr['User'] = $data['user']['name'];
							$arr['From'] = $data['from_user']['name'];
							$arr['Coins'] = $data['amount'];
							if($search['type']=='B')  $arr['Transaction_id'] =  $data['transaction_id'];
							$arr['Reason'] = $reason;
							$arr['Date'] = $data['created']->format('d M Y');
							fputcsv($file,$arr);
							$k++;
						}
				
						fclose($file);
						$this->response->file("uploads/".$filename, array(
						 'download' => true,
						 'name' => 'GalaxyReport'.time().$filename
						)); 
						return $this->response;die;
						
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
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		$searchData['AND'][] =['Users.user_type'=>'U'];
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
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);
            if($search['export'] !=''){
                // Export
                if($search['export']=='c') $filename = time().'export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Username','Name','Email','Phone number','Status','Date Of Registration');
                fputcsv($file,$headers);
                $users =  $this->Users->find('all',[
                   // 'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled'],
                    //'contain'=>['referusers','agctransactions','referral_user'],
                    'conditions' => $searchData,
                   // 'limit' => $limit,
                    'order'=>['Users.id'=>'desc']

                ]);
				

                $k = 1;
                foreach ($users as $k=>$data)
                {
					$agcTotal = 0;	
					$btcTotal = 0;	
					//	print_r($data['agctransactions']); die;
					/* if(!empty($data['agctransactions'])){
						foreach($data['agctransactions'] as $trans){
							$agcTotal = $agcTotal + $trans['agc_coins'];
							$btcTotal = $btcTotal + $trans['btc_coins'];
						}
					}	 */
					$userStatus = ($data['enabled'] == 'Y') ? "Active" : "Deactive" ;  		
					
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Username'] = $data['username'];
                    $arr['Name'] = $data['name'];
                    $arr['Email'] = $data['email'];
                    $arr['Phone number'] = $data['phone_number'];
                    $arr['Status'] = $userStatus;
                    $arr['Date Of Registration'] = date('d M Y',strtotime($data['created']));
                    fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'UserReport'.time().$filename
                ));
                return $this->response;die;
            }
		}
		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id'],
            'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('users',$getUsers );
    }
    public function search()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Users.user_type'=>'U'];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				if($search['pagination'] != '') $limit =  $search['pagination'];
				if($search['name'] != '') $searchData['AND'][] =array('Users.name LIKE' => '%'.$search['name'].'%');
				if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
				if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number LIKE' => '%'.$search['phone_number'].'%');
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$this->set('users', $this->Paginator->paginate($this->Users, [
				'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id'],
				'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]));
			
			
		}
	}
	
	
	
	public function lendingReport()
    {
		$this->loadModel('Investment');
        $this->set('title' , 'Lending Report');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		$searchData['AND'][] =['Investment.type'=>'investment'];
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['username'] != '') $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
            
		}
		$getReport = $this->Paginator->paginate($this->Investment, [
            'fields'=>['Investment.id','user.username','Investment.amount','cointransactions.coin','Investment.created_at'],
            'contain'=>['user','cointransactions'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('reports',$getReport );
    }
    public function lendingReportSearch()
	{
		$this->loadModel('Investment');
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Investment.type'=>'investment'];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				if($search['pagination'] != '') $limit =  $search['pagination'];
				if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
				
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$this->set('reports', $this->Paginator->paginate($this->Investment, [
				//'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id'],
				'fields'=>['Investment.id','user.username','Investment.amount','cointransactions.coin','Investment.created_at'],
				'contain'=>['user','cointransactions'],
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
            if($search['export'] !=''){

                // Export
                if($search['export']=='c') $filename = 'export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','User Name','Ip Address','Date Time');

                fputcsv($file,$headers);

                $query = $this->LoginLogs->find('all',[
                    'fields'=>['user.name','ip_address','LoginLogs.created'],
                    'contain'=>['user'],
                    'conditions' => $searchData,
                    'limit' => $limit,
                    'order'=>['LoginLogs.id'=>'desc']

                ])->hydrate(false)->toArray();
                $k=1;
                foreach($query as $data){

                    $arr = array();
                    $arr['#'] = $k;
                    $arr['User Name'] = $data['user']['name'];
                    $arr['Ip Address'] = $data['ip_address'];
                    $arr['Date Time'] = $data['created']->format('d M Y');
                    fputcsv($file,$arr);
                    $k++;
                }

                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'LogReport'.time().$filename
                ));
                return $this->response;die;

            }
			
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
	
	
	
   public function exchange()
    {
		$this->loadModel('Exchange');
        $this->set('title' , 'Exchange');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		//$searchData['AND'][] =['Users.user_type'=>'U'];
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
                if($search['export']=='c') $filename = 'export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Username','Name','Email','Phone number','Wallet','Date Of Registration','IP','AGC Tokens','BTC coins');
                fputcsv($file,$headers);
                $users =  $this->Users->find('all',[
                    'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled'],
                    'contain'=>['referusers','agctransactions','referral_user'],
                    'conditions' => $searchData,
                    'limit' => $limit,
                    'order'=>['Users.id'=>'desc']

                ]);
				

                $k = 1;
                foreach ($users as $k=>$data)
                {
					$agcTotal = 0;	
					$btcTotal = 0;	
					//	print_r($data['agctransactions']); die;
					if(!empty($data['agctransactions'])){
						foreach($data['agctransactions'] as $trans){
							$agcTotal = $agcTotal + $trans['agc_coins'];
							$btcTotal = $btcTotal + $trans['btc_coins'];
						}
					}	
						
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Username'] = $data['username'];
                    $arr['Name'] = $data['name'];
                    $arr['Email'] = $data['email'];
                    $arr['Phone number'] = $data['phone_number'];
                    $arr['Wallet'] = $data['btc_address'];
                    $arr['Date Of Registration'] = date('d M Y',strtotime($data['created']));
                    $arr['IP'] = $data['ip_address'];
                    $arr['HC Tokens'] = $agcTotal;
                    $arr['BTC coins'] = $btcTotal;
                   fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'UserReport'.time().$filename
                ));
                return $this->response;die;
            }
		}
		$getExchange = $this->Paginator->paginate($this->Exchange, [
           /*  'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id'], */
            'contain'=>['seller','buyer'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('getExchange',$getExchange);
    }
	
	
    public function exchangeSearch()
	{
		$this->loadModel('Exchange');
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			//$searchData['AND'][] =['Users.user_type'=>'U'];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				if(isset($search['pagination']) && $search['pagination'] != '') $limit =  $search['pagination'];
				if(isset($search['name']) && $search['name'] != '') $searchData['AND'][] =array('Users.name LIKE' => '%'.$search['name'].'%');
				if(isset($search['email']) && $search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
				if(isset($search['username']) && $search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
				if(isset($search['phone_number']) && $search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number LIKE' => '%'.$search['phone_number'].'%');
				if(isset($search['unique_id']) && $search['unique_id'] != '') $searchData['AND'][] =array('Users.unique_id LIKE' => '%'.$search['unique_id'].'%');
				if(isset($search['start_date']) && isset($search['end_date']) && $search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
				else if(isset($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
				else if(isset($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			
			
			$getExchange = $this->Paginator->paginate($this->Exchange, [
			   /*  'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id'], */
				'contain'=>['seller','buyer'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('getExchange',$getExchange);
			
			
		}
	}
	
	
   public function usersCoin()
    {
        $this->set('title' , 'Users');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		$searchData['AND'][] =['Users.user_type'=>'U'];
		$searchData['AND'][] =['Users.token_wallet_address !='=>''];
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
                if($search['export']=='c') $filename = 'export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Username','Name','Email','Phone number','Wallet','Date Of Registration','IP','AGC Tokens','BTC coins');
                fputcsv($file,$headers);
                $users =  $this->Users->find('all',[
                    'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled'],
                    'contain'=>['referusers','agctransactions','referral_user'],
                    'conditions' => $searchData,
                    'limit' => $limit,
                    'order'=>['Users.id'=>'desc']

                ]);
				

                $k = 1;
                foreach ($users as $k=>$data)
                {
					$agcTotal = 0;	
					$btcTotal = 0;	
					//	print_r($data['agctransactions']); die;
					if(!empty($data['agctransactions'])){
						foreach($data['agctransactions'] as $trans){
							$agcTotal = $agcTotal + $trans['agc_coins'];
							$btcTotal = $btcTotal + $trans['btc_coins'];
						}
					}	
						
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Username'] = $data['username'];
                    $arr['Name'] = $data['name'];
                    $arr['Email'] = $data['email'];
                    $arr['Phone number'] = $data['phone_number'];
                    $arr['Wallet'] = $data['btc_address'];
                    $arr['Date Of Registration'] = date('d M Y',strtotime($data['created']));
                    $arr['IP'] = $data['ip_address'];
                    $arr['HC Tokens'] = $agcTotal;
                    $arr['BTC coins'] = $btcTotal;
                   fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'UserReport'.time().$filename
                ));
                return $this->response;die;
            }
		}
		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id','token_wallet_address'],
            'contain'=>['cointransactions','tocointransfer'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('users',$getUsers );
    }
	
	
    public function usersCoinSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Users.user_type'=>'U'];
			$searchData['AND'][] =['Users.token_wallet_address !='=>''];
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
				'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id','token_wallet_address'],
				'contain'=>['cointransactions','tocointransfer'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]));
			
			
		}
	}
	
	
	public function cointransfer($userId){
		
		$this->loadModel('Coin');
		$this->loadModel('Users');
		$this->loadModel('Cointransfer');
		$adminTokenWallet = $this->Auth->User('token_wallet_address');
		if($this->request->is(['post','put'])) {
			
			$getUserTotalCoin = $this->Users->getUserTotalHc($userId);
			$getUserTransferredHc = $this->Users->getUserTransferredHc($userId);
			$coinToTransfer = $getUserTotalCoin - $getUserTransferredHc;
			if($coinToTransfer==0){
				$this->Flash->error(__('All Coins have transferred Already.'));
				return $this->redirect(['controller'=>'reports','action' => 'usersCoin']);
			}
			
			
			$getToUserId = $this->request->data['to_user_id'];
			$getCoinAmountToTranfser = $this->request->data['coin_amount'];
			$getToUserData = $this->Users->get($getToUserId);
			$getTokenAddress = $getToUserData->token_wallet_address;
			
			
			if($getCoinAmountToTranfser > $coinToTransfer){
				$this->Flash->error(__('You can transger only '.$coinToTransfer.' tokens'));
				return $this->redirect(['controller'=>'reports','action' => 'usersCoin']);
			}
			
			if(!empty($getTokenAddress)){
				
				$getUserTotalCoin = $this->Users->getUserTotalHc($getToUserId);
				if($getCoinAmountToTranfser>$getUserTotalCoin){
					$this->Flash->error(__('This User have only '.$getUserTotalCoin.' coins for transfer'));
					return $this->redirect('reports/cointransfer/'.$getToUserId);
				}
				
				$password = $this->Auth->User('email');
				$fromWalletAddress = $adminTokenWallet;
				$toWalletAddress = $getTokenAddress;
				$coinAmount = $getCoinAmountToTranfser;
				$tx_id = $this->Users->transferCoinToAddress($password,$fromWalletAddress,$toWalletAddress,$coinAmount);
				
				$cuDate = date('Y-m-d H:i:s');
				$coinTransferArr=[];
				$coinTransferArr['tx_id']        = $tx_id;
				$coinTransferArr['from_user_id'] = 1;
				$coinTransferArr['to_user_id']   = $getToUserId;
				$coinTransferArr['coin_amount']  = $coinAmount;
				$coinTransferArr['status']  = 1;
				$coinTransferArr['created_at']  = $cuDate;
				$coinTransferArr['updated_at']  = $cuDate;
				
				
				$coinTransferOdj = $this->Cointransfer->newEntity();
				$coinTransferOdj=$this->Cointransfer->patchEntity($coinTransferOdj,$coinTransferArr);
				$saveData = $this->Cointransfer->save($coinTransferOdj);
				if($saveData){
					$this->Flash->success(__('Coin Transfer Successfully.'));
					return $this->redirect(['controller'=>'reports','action' => 'coinTransferHistory']);
				}
				else {
					$this->Flash->success(__('unable to transfer coin.'));
					return $this->redirect(['controller'=>'reports','action' => 'coinTransferHistory']);
				}
			}
		}
		// get balance
		$getBalanceOfRealToken = $this->Users->getCoinBalance($adminTokenWallet);
		$this->set('getBalanceOfRealToken',$getBalanceOfRealToken);
		
		$this->set('to_user_id',$userId);
		
		$getUserDetail = $this->Users->get($userId);
		$this->set('getUserDetail',$getUserDetail);
		
		$getUserTotalCoin = $this->Users->getUserTotalHc($userId);
		$getUserTransferredHc = $this->Users->getUserTransferredHc($userId);
		$coinToTransfer = $getUserTotalCoin - $getUserTransferredHc;
		if($coinToTransfer==0){
			$this->Flash->error(__('All Coins have transferred Already.'));
			return $this->redirect(['controller'=>'reports','action' => 'usersCoin']);
		}
		$this->set('coinToTransfer',$coinToTransfer);
	}
	
	
	 public function coinTransferHistory()
    {
		$this->loadModel('Coin');
		$this->loadModel('Users');
		$this->loadModel('Cointransfer');
        $this->set('title' , 'Users');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		$searchData['AND'][] =['Cointransfer.status'=>1];
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
                if($search['export']=='c') $filename = 'export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Username','Name','Email','Phone number','Wallet','Date Of Registration','IP','AGC Tokens','BTC coins');
                fputcsv($file,$headers);
                $users =  $this->Users->find('all',[
                    'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled'],
                    'contain'=>['referusers','agctransactions','referral_user'],
                    'conditions' => $searchData,
                    'limit' => $limit,
                    'order'=>['Users.id'=>'desc']

                ]);
				

                $k = 1;
                foreach ($users as $k=>$data)
                {
					$agcTotal = 0;	
					$btcTotal = 0;	
					//	print_r($data['agctransactions']); die;
					if(!empty($data['agctransactions'])){
						foreach($data['agctransactions'] as $trans){
							$agcTotal = $agcTotal + $trans['agc_coins'];
							$btcTotal = $btcTotal + $trans['btc_coins'];
						}
					}	
						
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Username'] = $data['username'];
                    $arr['Name'] = $data['name'];
                    $arr['Email'] = $data['email'];
                    $arr['Phone number'] = $data['phone_number'];
                    $arr['Wallet'] = $data['btc_address'];
                    $arr['Date Of Registration'] = date('d M Y',strtotime($data['created']));
                    $arr['IP'] = $data['ip_address'];
                    $arr['HC Tokens'] = $agcTotal;
                    $arr['BTC coins'] = $btcTotal;
                   fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'UserReport'.time().$filename
                ));
                return $this->response;die;
            }
		}
		$getUsers = $this->Paginator->paginate($this->Cointransfer, [
            /* 'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id','token_wallet_address'], */
            'contain'=>['from_user','to_user'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('listing',$getUsers );
    }
	
		public function ramWithdrawal()
    {
        $this->set('title' , 'Users');
		$this->loadModel('Transactions');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
		$searchData['AND'][] =['Transactions.cryptocoin_id'=>3];
		
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			if(!empty($search['tx_id'])) $searchData['AND'][] =array('Transactions.withdrawal_tx_id' => $search['tx_id']);
		}
		
		
		
		$withdrawals = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user','cryptocoin'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('withdrawals',$withdrawals );
    }
   

   public function ramWithdrawalSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
			$searchData['AND'][] =['Transactions.cryptocoin_id'=>3];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$withdrawals = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user','cryptocoin'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('withdrawals',$withdrawals );
			
			
		}
	}  

	public function ramWithdrawalUpdate(){
		if ($this->request->is('ajax')) {
			$this->loadModel("Transactions");
			
			$id = $this->request->data['id'];
			$getData = $this->Transactions->get($id);
			$getData = $this->Transactions->patchEntity($getData,['withdrawal_send'=>'Y']);
			$getData = $this->Transactions->save($getData);
			if($getData){ echo 1; }
			else { echo 0; }
		}
		else {
			echo 0;
		}
		die;
	}
	
	public function ramWithdrawalUpdateNotUsed(){
		if ($this->request->is('ajax')) {
			$this->loadModel("Transactions");
			
			$id = $this->request->data['id'];
			$getData = $this->Transactions->get($id);
			$getData = $this->Transactions->patchEntity($getData,['withdrawal_send'=>'N']);
			$getData = $this->Transactions->save($getData);
			if($getData){ echo 1; }
			else { echo 0; }
		}
		else {
			echo 0;
		}
		die;
	}
	
	
	
	public function ethWithdrawal()
    {
        $this->set('title' , 'Users');
		$this->loadModel('Transactions');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
		//$searchData['AND'][] =['Transactions.cryptocoin_id'=>2];
		
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			
			
			if($search['export'] !=''){
                // Export
                if($search['export']=='c') $filename = 'eth_withdrawal_export.csv';
                else  $filename = 'eth_withdrawal_export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Username','Email','Tx Id', 'Amount','Wallet Address','Status','created');
                fputcsv($file,$headers);
                $users =  $this->Transactions->find('all',[
                    'contain'=>['user','cryptocoin'],
					'conditions' => ['Transactions.tx_type'=>'withdrawal','Transactions.cryptocoin_id'=>2],
					//'limit' => $limit,
					'order'=>['Transactions.id'=>'desc']

                ])->hydrate(false)->toArray();
				

                $ks = 1;
                foreach ($users as $k=>$data)
                {
					 
					
					if($data['withdrawal_send']=='N' && !empty($data['withdrawal_id'])){
						$usedStatus = "Processing";
						$txId = '';
					}
					else if($data['withdrawal_send']=='Y') {
						$usedStatus = "Completed";
						$txId = !empty($data['withdrawal_id']) ? $data['withdrawal_tx_id'] : '';
					}
					else if($data['withdrawal_send']=='N') {
						$usedStatus = "Pending";
						$txId = '';
					}
					else {
						$usedStatus = "Nil";
						$txId = '';
					}
					
					
                    $arr = [];
                    $arr['#'] = $ks;
                    $arr['Username'] = $data['user']['username'];
                    $arr['Email'] = $data['user']['email'];
                    $arr['Tx Id'] = $txId;
                    $arr['Amount'] = abs($data['coin_amount'])." ".$data['cryptocoin']['short_name'];
                    $arr['Wallet Address'] = $data['wallet_address'];
                    $arr['Status'] = $usedStatus;
					 $arr['created'] = $data['created'];
                    fputcsv($file,$arr);
                    $ks++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'report'.time().$filename
                ));
                return $this->response;die;
            }
				$limit = 1000000000000;
		}
		
		
		
		$withdrawals = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user','cryptocoin'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($withdrawals); die;
		$this->set('withdrawals',$withdrawals );
    }
   

   public function ethWithdrawalSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
			$searchData['AND'][] =['Transactions.cryptocoin_id'=>2];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$withdrawals = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user','cryptocoin'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('withdrawals',$withdrawals );
			
			
		}
	}  

	public function ethDepositUpdate(){
		if ($this->request->is('ajax')) {
			//die;
			$this->loadModel("Transactions");
			$this->loadModel("Users");
			
			$id = $this->request->data['id'];
			$getTxDetail = $this->Transactions->get($id);
			$updated_date = date("Y-m-d H:i:s");
			$getTxDetail = $this->Transactions->patchEntity($getTxDetail,['status'=>"completed",'updated'=>$updated_date]);
			$getTxDetail = $this->Transactions->save($getTxDetail);
			echo '1';
			
		}
		else {
			echo 0;
		}
		die;
	}
	
	
	public function ethWithdrawalUpdate(){
		if ($this->request->is('ajax')) {
			//die;
			$this->loadModel("Transactions");
			$this->loadModel("Users");
			$this->loadModel("Cryptocoin");
			
			$id = $this->request->data['id'];
			$withdrawal_type = $this->request->data['withdrawal_type'];
			$model_tx_id = $this->request->data['model_tx_id'];
			$model_withdrawal_date = $this->request->data['model_withdrawal_date'];
			$comment = $this->request->data['comment'];
			$getTxDetail = $this->Transactions->get($id);
		
			$coinId = $getTxDetail['cryptocoin_id'];
			$cryptoDetail = $this->Cryptocoin->get($coinId);
			$userDetail = $this->Users->find("all",['conditions'=>['id'=>$getTxDetail['user_id']]])->hydrate(false)->first();
		
			$userEmail = $userDetail['email'];
			$userId = $userDetail['id'];
			$updated_date = date("Y-m-d H:i:s");
			$cudate = $updated_date;
			
			if($cryptoDetail['short_name']=="NTR") {
				$getWithdrawalId = "";
				$transFee = 0.02;
				$amount = abs($getTxDetail['coin_amount']);
				$amount = $amount-$transFee;
				$currency = strtoupper($cryptoDetail['short_name']);
				//$currency = "LTCT";
				$address = $getTxDetail['wallet_address'];
				
				$getLiveBalanceResp = $this->Users->getNtrChainBalance($userEmail);
				$getLiveBalanceResp = json_decode($getLiveBalanceResp,true);
				$getLiveBalance = $getLiveBalanceResp['result'];
				
				if($getLiveBalance >= $amount){
					$getWithdrawalResp = $this->Users->ntrWithdrawal($userEmail,$address,$amount);
					$getWithdrawalResp = json_decode($getWithdrawalResp,true);
					$getWithdrawalId = $getWithdrawalResp['result'];
					if($getWithdrawalId==null){
						echo $getWithdrawalResp['error']['message'];
					}
					
				}
				else {
					if($getLiveBalance==0){
						$getWithdrawalResp = $this->Users->ntrWithdrawal("",$address,$amount);
						$getWithdrawalResp = json_decode($getWithdrawalResp,true);
						$getWithdrawalId = $getWithdrawalResp['result'];
					}
					else {
						
						$userCurrentBalance = $this->Users->getLocalUserBalance($userId,$coinId);
						$moveAmountToAdmin = $getLiveBalance  - $userCurrentBalance;
						$moveNtrToAdminBalance = $this->Users->moveNtrToAdminBalance($userEmail,$moveAmountToAdmin);
						
						$getWithdrawalResp = $this->Users->ntrWithdrawal("",$address,$amount);
						$getWithdrawalResp = json_decode($getWithdrawalResp,true);
						$getWithdrawalId = $getWithdrawalResp['result'];
						
					}
				}
				if($getWithdrawalId!="") {
					$dataToUpdate = [];
					$dataToUpdate["withdrawal_id"] = uniqid();
					$dataToUpdate["updated"] = $cudate;
					$dataToUpdate["withdrawal_send"] = 'Y';
					$dataToUpdate["withdrawal_tx_id"] = $getWithdrawalId;
					$dataToUpdate["withdrawal_date"] = $model_withdrawal_date;
					$dataToUpdate['tx_id'] = $getWithdrawalId;
					$dataToUpdate['withdrawal_type'] = $withdrawal_type;
					$dataToUpdate['withdrawal_comment'] = $comment;
					
					$getTxDetail = $this->Transactions->patchEntity($getTxDetail,$dataToUpdate);
					$getTxDetail = $this->Transactions->save($getTxDetail);
					echo '1';
				}
				else {
					echo "Unable to complete with";
				}
				
			}
			else {
				if($withdrawal_type == "coinpayment") {
					$transFee = 0.02;
					$amount = abs($getTxDetail['coin_amount']);
					$amount = $amount-$transFee;
					$currency = strtoupper($cryptoDetail['short_name']);
					//$currency = "LTCT";
					$address = $getTxDetail['wallet_address'];
					//$address = "mhaQV6UZrMCYirj8iY7edPW4nJjrBH58tJ";
					$ipn_url = Router::url('/', true)."pages/ethwithdrawalstatus";
					$getWithdrawalId = '';
					$returnData = $this->Users->createWithdrawal($amount, $currency, $address,  FALSE, $ipn_url );
					file_put_contents("eth_withdrawal_status.txt", json_encode($_POST,true).$updated_date,FILE_APPEND);
					
					if(!empty($returnData) && $returnData['error']=='ok'){
						$getWithdrawalId = $returnData['result']['id'];
						$getTxDetail = $this->Transactions->patchEntity($getTxDetail,['withdrawal_id'=>$getWithdrawalId,
																					  'withdrawal_type'=>$withdrawal_type,
																					  'withdrawal_comment'=>$comment,
																					  'updated'=>$cudate]);
						$getTxDetail = $this->Transactions->save($getTxDetail);
						echo '1';
					}
					else {
						echo $returnData['error'];
					}
				}
				else {
					$dataToUpdate = [];
					$dataToUpdate["withdrawal_id"] = uniqid();
					$dataToUpdate["updated"] = $cudate;
					$dataToUpdate["withdrawal_send"] = 'Y';
					$dataToUpdate["withdrawal_tx_id"] = $model_tx_id;
					$dataToUpdate["withdrawal_date"] = $model_withdrawal_date;
					$dataToUpdate['tx_id'] = $model_tx_id;
					$dataToUpdate['withdrawal_type'] = $withdrawal_type;
					$dataToUpdate['withdrawal_comment'] = $comment;
					
					$getTxDetail = $this->Transactions->patchEntity($getTxDetail,$dataToUpdate);
					$getTxDetail = $this->Transactions->save($getTxDetail);
					echo '1';
				}
			}
			
		}
		else {
			echo 0;
		}
		die;
	}
    
	
	

	
	public function admcWithdrawal()
    {
        $this->set('title' , 'Users');
		$this->loadModel('Transactions');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
		$searchData['AND'][] =['Transactions.cryptocoin_id'=>4];
		
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			$limit =  100000000000;
		}
		
		
		
		$withdrawals = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user','cryptocoin'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('withdrawals',$withdrawals );
    }
   

   public function admcWithdrawalSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
			$searchData['AND'][] =['Transactions.cryptocoin_id'=>4];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$withdrawals = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user','cryptocoin'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('withdrawals',$withdrawals );
			
			
		}
	}  

	public function admcWithdrawalUpdate(){
		if ($this->request->is('ajax')) {
			//die;
			$this->loadModel("Transactions");
			$this->loadModel("Users");
			
			$id = $this->request->data['id'];
			$getTxDetail = $this->Transactions->get($id);
			$amount = abs($getTxDetail['coin_amount']);
			$currency = "ETH";
			//$currency = "LTCT";
			$address = $getTxDetail['wallet_address'];
			//$address = "mhaQV6UZrMCYirj8iY7edPW4nJjrBH58tJ";
			$ipn_url = Router::url('/', true)."pages/ethwithdrawalstatus";
			$getWithdrawalId = '';
			$returnData = $this->Users->admcWithdrawal($address, $amount);
			
			$updated_date = date("Y-m-d H:i:s");
			$cudate = $updated_date;
			file_put_contents("ram_withdrawal.log", json_encode($returnData,true).$updated_date,FILE_APPEND);
			
			if(!empty($returnData)){
				$returnData = json_decode($returnData,true);
				if(!empty($returnData['result'])) {
					$getWithdrawalTxId = $returnData['result'];
					$getTxDetail = $this->Transactions->patchEntity($getTxDetail,['withdrawal_tx_id'=>$getWithdrawalTxId,
																				  'withdrawal_id'=>$getWithdrawalTxId,  		
																				  'withdrawal_send'=>'Y',  		
																				  'tx_id'=>$getWithdrawalTxId,  		
																				  'updated'=>$cudate]);
					$getTxDetail = $this->Transactions->save($getTxDetail);
					echo '1';
				}
				else {
					//return $returnData['error']['message'];	
					echo $returnData['error']['message'];
				}
			}
			else {
				echo "Unable to complete withdrawal";
			}
			
		}
		else {
			echo "Invalid Request";
		}
		die;
	}
    	
	
	
	
	public function usertxn()
    {
        $this->set('title' , 'Users');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		$searchData['AND'][] =['Users.user_type'=>'U'];
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
			if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
			
            if($search['export'] !=''){
				exec('/home/livecrypto/public_html/bin/cake UserTxn > /dev/null 2>&1 &');
				$this->Flash->success(__('You will receive email when report will generate.'));
				//die;
				/* ini_set('memory_limit','500M');
				ini_set('max_execution_time', 900);
                // Export
                if($search['export']=='c') $filename = time().'export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                 $headers = array('#','Username','Email','ETH','ETH RESERVE','RAM','RAM RESERVE','ADMC','ADMC RESERVE');
                fputcsv($file,$headers);
                $users =  $this->Users->find('all',[
                    'fields'=>['id','username','email'],
					'contain'=>['ethtransactions'=>['fields'=>['ethtransactions.coin_amount','user_id','remark']],
								'ramtransactions'=>['fields'=>['ramtransactions.coin_amount','user_id','remark']],
								'admctransactions'=>['fields'=>['admctransactions.coin_amount','user_id','remark']],
								'eth_reserve',
								'admc_reserve',
								'ram_reserve' 
								
					'conditions' => ['Users.user_type'=>'U','Users.enabled'=>'Y'],
					'order'=>['id'=>'asc']

                ])->hydrate(false)->toArray();
				

                 $k = 1;
                foreach ($users as $k=>$data)
                {
					 
					$ethTotal = 0;
					$ethReserve = 0;
					if(!empty($data['ethtransactions'])){
						foreach($data['ethtransactions'] as $ethTrans){
							if(!empty($ethTrans['coin_amount'])){
								$ethTotal = $ethTotal + $ethTrans['coin_amount'];
								
							}
						}
					}
					
					
					if(!empty($data['eth_reserve'])){
						foreach($data['eth_reserve'] as $ethSpend){
							if(!empty($ethSpend['total_buy_spend_amount'])){
								$ethReserve = $ethReserve + ($ethSpend['buy_get_amount']*$ethSpend['per_price']);
								//$ethReserve = $ethReserve + $ethSpend['total_buy_spend_amount'];
							}
						}
					}
					
					
					$ramTotal = 0;
					$ramReserve = 0;
					if(!empty($data['ramtransactions'])){
						foreach($data['ramtransactions'] as $ramTrans){
							if(!empty($ramTrans['coin_amount'])){
								$ramTotal = $ramTotal + $ramTrans['coin_amount'];
								
							}
						}
					}
					
					if(!empty($data['ram_reserve'])){
						foreach($data['ram_reserve'] as $ramSpend){
							if(!empty($ramSpend['total_sell_spend_amount'])){
								//$ramReserve = $ramReserve + $ramSpend['sell_spend_amount'];
								$ramReserve = $ramReserve + $ramSpend['total_sell_spend_amount'];
							}
						}
					}
					
					
					$admcTotal = 0;
					$admcReserve = 0;
					if(!empty($data['admctransactions'])){
						foreach($data['admctransactions'] as $admcTrans){
							if(!empty($admcTrans['coin_amount'])){
								$admcTotal = $admcTotal + $admcTrans['coin_amount'];
								
							}
						}
					}
					
					if(!empty($data['admc_reserve'])){
						foreach($data['admc_reserve'] as $admcSpend){
							if(!empty($admcSpend['total_sell_spend_amount'])){
								//$admcReserve = $admcReserve + $admcSpend['total_sell_spend_amount'];
								$admcReserve = $admcReserve + $admcSpend['sell_spend_amount'];
							}
						}
					}
					
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Username'] = $data['username'];
                    $arr['Email'] = $data['email'];
            
					
					$arr['ETH'] = number_format((float)$ethTotal,8);
                    $arr['ETH RESERVE'] = number_format((float)abs($ethReserve),8);
                    $arr['RAM'] = number_format((float)$ramTotal,8);
                    $arr['RAM RESERVE'] = number_format((float)abs($ramReserve),8);
                    $arr['ADMC'] =  number_format((float)$admcTotal,8);
                    $arr['ADMC RESERVE'] = number_format((float)abs($admcReserve),8);
					
                    fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'UserReport'.$filename
                ));
                return $this->response;die; */
            }
		}
		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','email'],
            /* 'contain'=>['ethtransactions'=>['fields'=>['eth_sum'=>'SUM(ethtransactions.coin_amount)','ethtransactions.user_id']],'ramtransactions'=>['fields'=>['ram_sum'=>'SUM(ramtransactions.coin_amount)','ramtransactions.user_id']]], */
			'contain'=>['ethtransactions'=>['fields'=>['ethtransactions.coin_amount','user_id','remark']],
						'ramtransactions'=>['fields'=>['ramtransactions.coin_amount','user_id','remark']],
						'admctransactions'=>['fields'=>['admctransactions.coin_amount','user_id','remark']],
						'usdtransactions'=>['fields'=>['usdtransactions.coin_amount','user_id','remark']],
						'eth_reserve',
						'admc_reserve',
						'ram_reserve',
						'usd_reserve'
						],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'asc']

        ]);
		
		//print_r($getUsers); die;
		$this->set('users',$getUsers );
		
		$allTotal= $this->Transactions->find('all',['coinditions'=>['status !='=>'deleted'],
													'fields'=>['totalsum'=>'SUM(coin_amount)','cryptocoin.short_name'],
													'group'=>['cryptocoin_id'],
													'contain'=>['cryptocoin']
												   ])->hydrate(false)->toArray();
		$this->set('allTotal',$allTotal );
		
    }
	
	
    public function usertxnSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Users.user_type'=>'U'];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				if($search['pagination'] != '') $limit =  $search['pagination'];
			/* 	if($search['name'] != '') $searchData['AND'][] =array('Users.name LIKE' => '%'.$search['name'].'%');
				if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
				if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number LIKE' => '%'.$search['phone_number'].'%');
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']); */
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			
			
			$getUsers = $this->Paginator->paginate($this->Users, [
				'fields'=>['id','username','email'],
				'contain'=>['ethtransactions'=>['fields'=>['ethtransactions.coin_amount','user_id','remark']],
						'ramtransactions'=>['fields'=>['ramtransactions.coin_amount','user_id','remark']],
						'admctransactions'=>['fields'=>['admctransactions.coin_amount','user_id','remark']],
						'usdtransactions'=>['fields'=>['usdtransactions.coin_amount','user_id','remark']],
						'eth_reserve',
						'admc_reserve',
						'ram_reserve',
						'usd_reserve'
						],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			$this->set('users',$getUsers );
			
		}
	}
	
	public function ethReport() 
    {
        $this->set('title' , 'Users');
		$this->loadModel("Transactions");
		$searchData = array();
		
		$limit =  $this->setting['pagination'];
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		$searchData['AND'][] =['Transactions.cryptocoin_id'=>2];
		//$searchData['AND'][] =['Transactions.tx_id !='=>''];
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.updated) >= ' => $this->request->data['start_date'],'DATE(Transactions.updated) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.updated)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.updated)' => $search['end_date']);
			/* if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
			if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
			 */
            if($search['export'] !=''){
                // Export
                if($search['export']=='c') $filename = time().'export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Date','Eth Deposit','Eth Withdrawal');
                fputcsv($file,$headers);
                $users =  $this->Transactions->find('all',[
                    'fields'=>["showdate"=>"DATE_FORMAT(updated,'%Y-%m-%d') ",
						   "totalpurchase"=>"SUM(CASE WHEN tx_type = 'purchase' AND tx_id!='' THEN coin_amount END)",
						   "totalwithdrawal"=>"SUM(CASE WHEN tx_type = 'withdrawal' THEN coin_amount END)"],
					'conditions' => $searchData,
					'group' => "DATE_FORMAT(updated,'%Y-%m-%d')",
					//'limit' => $limit,
					'order'=>['updated'=>'desc']

                ])->hydrate(false)->toArray();
				

                $k = 1;
                foreach ($users as $k=>$data)
                {
					 
					
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Date'] = $data['showdate'];
                    $arr['Eth Deposit'] = number_format((float)$data['totalpurchase'],8);
                    $arr['Eth Withdrawal'] = number_format((float)abs($data['totalwithdrawal']),8);
                    fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'EthReport'.time().$filename
                ));
                return $this->response;die;
            }
		}
		$getDateTrans = $this->Paginator->paginate($this->Transactions, [
				'fields'=>["showdate"=>"DATE_FORMAT(updated,'%Y-%m-%d') ",
						   "totalpurchase"=>"SUM(CASE WHEN tx_type = 'purchase' AND tx_id!=''  THEN coin_amount END)",
						   "totalwithdrawal"=>"SUM(CASE WHEN tx_type = 'withdrawal' THEN coin_amount END)"],
				'conditions' => $searchData,
				'group' => "DATE_FORMAT(updated,'%Y-%m-%d')",
				'limit' => $limit,
				'order'=>['updated'=>'desc']

			]);
		$this->set('getDateTrans',$getDateTrans );
		
		
	}
	
	
    public function ethReportSearch()
	{
		$this->loadModel('Transactions');
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.cryptocoin_id'=>2];
			//$searchData['AND'][] =['Transactions.tx_id !='=>''];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				if($search['pagination'] != '') $limit =  $search['pagination'];
			/* 	if($search['name'] != '') $searchData['AND'][] =array('Users.name LIKE' => '%'.$search['name'].'%');
				if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
				if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number LIKE' => '%'.$search['phone_number'].'%');
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']); */
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			
			
			$getDateTrans = $this->Paginator->paginate($this->Transactions, [
					'fields'=>["showdate"=>"DATE_FORMAT(updated,'%Y-%m-%d') ",
							   "totalpurchase"=>"SUM(CASE WHEN tx_type = 'purchase' AND tx_id!='' THEN coin_amount END)",
							   "totalwithdrawal"=>"SUM(CASE WHEN tx_type = 'withdrawal' THEN coin_amount END)"],
					'conditions' => $searchData,
					'group' => "DATE_FORMAT(updated,'%Y-%m-%d')",
					'limit' => $limit,
					'order'=>['updated'=>'desc']

				]);
			$this->set('getDateTrans',$getDateTrans );
			
		}
	}	
	
	
	
	public function coinReport() 
    {
        $this->set('title' , 'Users');
		$this->loadModel("Transactions");
		$searchData = array();
		
		$limit =  $this->setting['pagination'];
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		$searchData['AND'][] =['Transactions.cryptocoin_id in'=>[2,3,4]];
		//$searchData['AND'][] =['Transactions.status'=>'completed'];
		//$searchData['AND'][] =['Transactions.tx_id !='=>''];
		
		$usersList = $this->Users->find('list', [
							'keyField' => 'id',
							'valueField' => 'username'
						])->hydrate(false)->toArray();
		
		$this->set('usersList',$usersList);
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.updated) >= ' => $this->request->data['start_date'],'DATE(Transactions.updated) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.updated)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.updated)' => $search['end_date']);
			if($search['username'] != '') $searchData['AND'][] =array('Transactions.user_id' => $search['username']);
			//if($search['username'] != '') $searchData['AND'][] =array('user.username' => $search['username']);
			/* if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
			if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
			 */
            if($search['export'] !=''){
                // Export
                if($search['export']=='c') $filename = time().'export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Date','Eth Deposit','Eth Withdrawal');
                fputcsv($file,$headers);
                $users =  $this->Transactions->find('all',[
                    'fields'=>["showdate"=>"DATE_FORMAT(updated,'%Y-%m-%d') ",
						   "totalpurchase"=>"SUM(CASE WHEN tx_type = 'purchase' AND tx_id!='' THEN coin_amount END)",
						   "totalwithdrawal"=>"SUM(CASE WHEN tx_type = 'withdrawal' THEN coin_amount END)"],
					'conditions' => $searchData,
					'group' => "DATE_FORMAT(updated,'%Y-%m-%d')",
					//'limit' => $limit,
					'order'=>['updated'=>'desc']

                ])->hydrate(false)->toArray();
				

                $k = 1;
                foreach ($users as $k=>$data)
                {
					 
					
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Date'] = $data['showdate'];
                    $arr['Eth Deposit'] = number_format((float)$data['totalpurchase'],8);
                    $arr['Eth Withdrawal'] = number_format((float)abs($data['totalwithdrawal']),8);
                    fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'EthReport'.time().$filename
                ));
                return $this->response;die;
            }
			
			
			$getDateTrans = $this->Paginator->paginate($this->Transactions, [
				'fields'=>["showdate"=>"DATE_FORMAT(updated,'%Y-%m-%d') ",
						   "totalethpurchase"=>"SUM(CASE WHEN tx_type = 'purchase' AND tx_id!='' and cryptocoin_id=2  THEN coin_amount END)",
						   "totalethwithdrawal"=>"SUM(CASE WHEN tx_type = 'withdrawal' and cryptocoin_id=2 THEN coin_amount END)",
						   "totalrampurchase"=>"SUM(CASE WHEN tx_type = 'purchase' and cryptocoin_id=3  THEN coin_amount END)",
						   "totalramwithdrawal"=>"SUM(CASE WHEN tx_type = 'withdrawal' and cryptocoin_id=3 THEN coin_amount END)",
						   "totaladmcpurchase"=>"SUM(CASE WHEN tx_type = 'purchase' and cryptocoin_id=4  THEN coin_amount END)",
						   "totaladmcwithdrawal"=>"SUM(CASE WHEN tx_type = 'withdrawal' and cryptocoin_id=4 THEN coin_amount END)"
						   ],
				'conditions' => $searchData,
				'contain'=>['user'],
				'group' => "DATE_FORMAT(updated,'%Y-%m-%d')",
				//'limit' => $limit,
				'order'=>['updated'=>'desc']

			]);
			$this->set('getDateTrans',$getDateTrans );
		}
		else {
		
			$this->set('getDateTrans',[]);
		}
		
	}	
	
	
	
	
	
	public function ramDeposit()
    {
        $this->set('title' , 'Users');
		$this->loadModel('Transactions');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Transactions.tx_type'=>'purchase'];
		$searchData['AND'][] =['Transactions.cryptocoin_id'=>3];
		
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			if(!empty($search['withdrawal_tx_id'])) $searchData['AND'][] =array('Transactions.tx_id' => $search['withdrawal_tx_id']);
			$limit = 100000;
		}
		
		
		
		$getData = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user','cryptocoin'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('getData',$getData );
    }
   

   public function ramDepositSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.tx_type'=>'purchase'];
			$searchData['AND'][] =['Transactions.cryptocoin_id'=>3];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$getData = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user','cryptocoin'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('getData',$getData );
			
			
		}
	}  


	
	
	
	public function ethDeposit()
    {
        $this->set('title' , 'Users');
		$this->loadModel('Transactions');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Transactions.tx_type'=>'purchase'];
		//$searchData['AND'][] =['Transactions.cryptocoin_id'=>2];
		
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			if(!empty($search['tx_id'])) $searchData['AND'][] =array('Transactions.tx_id' => $search['tx_id']);
			if(!empty($search['wallet_address'])) $searchData['AND'][] =array('Transactions.wallet_address' => $search['wallet_address']);
			
			
			if($search['export'] !=''){
                // Export
                if($search['export']=='c') $filename = 'eth_withdrawal_export.csv';
                else  $filename = 'eth_withdrawal_export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Username','Email','Tx Id', 'Amount','Wallet Address','Status','created');
                fputcsv($file,$headers);
                $users =  $this->Transactions->find('all',[
                    'contain'=>['user','cryptocoin'],
					'conditions' => ['Transactions.tx_type'=>'purchase','Transactions.cryptocoin_id'=>2],
					//'limit' => $limit,
					'order'=>['Transactions.id'=>'desc']

                ])->hydrate(false)->toArray();
				

                $ks = 1;
                foreach ($users as $k=>$data)
                {
					 
					
					if($data['withdrawal_send']=='N' && !empty($data['withdrawal_id'])){
						$usedStatus = "Processing";
						$txId = '';
					}
					else if($data['withdrawal_send']=='Y') {
						$usedStatus = "Completed";
						$txId = !empty($data['withdrawal_id']) ? $data['withdrawal_tx_id'] : '';
					}
					else if($data['withdrawal_send']=='N') {
						$usedStatus = "Pending";
						$txId = '';
					}
					else {
						$usedStatus = "Nil";
						$txId = '';
					}
					
					
                    $arr = [];
                    $arr['#'] = $ks;
                    $arr['Username'] = $data['user']['username'];
                    $arr['Email'] = $data['user']['email'];
                    $arr['Tx Id'] = $txId;
                    $arr['Amount'] = abs($data['coin_amount'])." ".$data['cryptocoin']['short_name'];
                    $arr['Wallet Address'] = $data['wallet_address'];
                    $arr['Status'] = $usedStatus;
					$arr['created'] = $data['created'];
                    fputcsv($file,$arr);
                    $ks++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'report'.time().$filename
                ));
                return $this->response;die;
            }
			$limit = 100000;
		}
		
		
		
		$getData = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user','cryptocoin'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('getData',$getData );
    }
   

   public function ethDepositSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.tx_type'=>'purchase'];
			//$searchData['AND'][] =['Transactions.cryptocoin_id'=>2];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$getData = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user','cryptocoin'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('getData',$getData );
			
			
		}
	}  


	
	public function admcDeposit()
    {
        $this->set('title' , 'Users');
		$this->loadModel('Transactions');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Transactions.tx_type'=>'purchase'];
		$searchData['AND'][] =['Transactions.cryptocoin_id'=>4];
		
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			$limit = 100000;
		}
		
		
		
		$getData = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user','cryptocoin'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('getData',$getData );
    }
   

   public function admcDepositSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.tx_type'=>'purchase'];
			$searchData['AND'][] =['Transactions.cryptocoin_id'=>4];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$getData = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user','cryptocoin'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('getData',$getData );
			
			
		}
	} 



	public function usdDeposit()
    {
        $this->set('title' , 'Users');
		$this->loadModel('Transactions');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Transactions.tx_type'=>'purchase'];
		$searchData['AND'][] =['Transactions.cryptocoin_id'=>5];
		
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			if(!empty($search['withdrawal_tx_id'])) $searchData['AND'][] =array('Transactions.tx_id' => $search['withdrawal_tx_id']);
			$limit = 100000;
		}
		
		
		
		$getData = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user','cryptocoin'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('getData',$getData );
    }
   

   public function usdDepositSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.tx_type'=>'purchase'];
			$searchData['AND'][] =['Transactions.cryptocoin_id'=>5];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$getData = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user','cryptocoin'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('getData',$getData );
			
			
		}
	}  
	
	
	
	
	public function usdWithdrawal()
    {
        $this->set('title' , 'Users');
		$this->loadModel('Transactions');
		$searchData = array();
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
		$searchData['AND'][] =['Transactions.cryptocoin_id'=>5];
		
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			if(!empty($search['tx_id'])) $searchData['AND'][] =array('Transactions.withdrawal_tx_id' => $search['tx_id']);
		}
		
		
		
		$withdrawals = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user','cryptocoin'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('withdrawals',$withdrawals );
    }
   

   public function usdWithdrawalSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
			$searchData['AND'][] =['Transactions.cryptocoin_id'=>5];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$withdrawals = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user','cryptocoin'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('withdrawals',$withdrawals );
			
			
		}
	}  

	public function usdWithdrawalUpdate(){
		if ($this->request->is('ajax')) {
			$this->loadModel("Transactions");
			
			$id = $this->request->data['id'];
			$getData = $this->Transactions->get($id);
			$getData = $this->Transactions->patchEntity($getData,['withdrawal_send'=>'Y']);
			$getData = $this->Transactions->save($getData);
			if($getData){ echo 1; }
			else { echo 0; }
		}
		else {
			echo 0;
		}
		die;
	}
	
	public function usdWithdrawalUpdateNotUsed(){
		if ($this->request->is('ajax')) {
			$this->loadModel("Transactions");
			
			$id = $this->request->data['id'];
			$getData = $this->Transactions->get($id);
			$getData = $this->Transactions->patchEntity($getData,['withdrawal_send'=>'N']);
			$getData = $this->Transactions->save($getData);
			if($getData){ echo 1; }
			else { echo 0; }
		}
		else {
			echo 0;
		}
		die;
	}


 	
	
}
