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
	
	
    public function coinTransferHistorySearch()
	{
		$this->loadModel('Coin');
		$this->loadModel('Users');
		$this->loadModel('Cointransfer');
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Cointransfer.status'=>1];
			//$searchData['AND'][] =['Users.token_wallet_address IS NOT NULL'];
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
			$this->set('listing', $this->Paginator->paginate($this->Cointransfer, [
				/*'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id','token_wallet_address'],*/
				'contain'=>['from_user','to_user'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]));
			
			
		}
	}
	
    
}
