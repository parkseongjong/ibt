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
use Cake\Filesystem\File;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Network\Exception\NotFoundException;

class ReportsController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
	// 사용여부 확인 필요
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
					if($search['to_user'] != '') $searchData['AND'][] =array('user.name' => $search['to_user']);
					if($search['from_user'] != '') $searchData['AND'][] =array('from_user.name' => $search['from_user']);
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
							'conditions'=>[$searchData,'Transactions.tx_type !='=>'bank_initial_deposit'],
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
                'conditions'=>[$searchData,'Transactions.tx_type !='=>'bank_initial_deposit'],
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
			if($search['to_user'] != '') $searchData['AND'][] =array('user.name' => $search['to_user']);
			if($search['from_user'] != '') $searchData['AND'][] =array('from_user.name' => $search['from_user']);
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
                'conditions'=>[$searchData,'Transactions.tx_type !='=>'bank_initial_deposit'],
                'order'=>['Transactions.id'=>'desc'],
                'limit' => $limit,
            ]));

        }

    }
	
	public function kyclist()
    {
        $this->set('title' , 'Users');
        $this->loadModel('Users');
		$searchData = array();
        $limit =  20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }else $this->set('serial_num',1);

        $searchData['AND'][] = ['Users.user_type'=>'U'];
		$searchData['AND']['OR'] = [['Users.id_document_status != '=>'N'], ['Users.scan_copy_status != '=>'N'], ['Users.user_hash !=' => 'NULL']];
        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;
        if(!empty($search['pagination'])) $limit =  $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_name']);
        }
        if (!empty($search['user_email'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_email']);
        }

        if(!empty($search['start_date']) && !empty($search['end_date'])) $searchData['AND'][] = array('DATE(Users.modified) >= ' => $this->request->query['start_date'],'DATE(Users.modified) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.modified)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.modified)' => $search['end_date']);

        if(!empty($search['export']) && $search['export'] !=''){
            // Export
                // Export
                if($search['export']=='c') $filename = time().'export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Username','Name','Email','Phone number','Status','Date Of Registration');
                fputcsv($file,$headers);
                $users =  $this->Users->find('all',[
                    'conditions' => $searchData,
                   // 'limit' => $limit,
                    'order'=>['Users.id'=>'desc']

                ]);
				$this->add_system_log(200, 0, 5, 'KYC List CSV 다운로드 (이름, 전화번호 등)');
				

                $k = 1;
                foreach ($users as $k=>$data)
                {
					$userStatus = ($data['enabled'] == 'Y') ? "Active" : "Deactive" ;
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Username'] = $data['username'];
                    $arr['Name'] = $data['name'];
                    $arr['Email'] = $data['email'];
                    $arr['User Level'] = $data['user_level'];
                    $arr['Annual Membership'] = $data['annual_membership'];
                    $arr['Category'] = $data['category'];
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

		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','name','phone_number','email','user_level','annual_membership','category','id_type','id_number','id_document_front','id_document_back','id_document_status','scan_copy','scan_copy_status','id_document_reject_reason','scan_copy_reject_reason','review_message','user_hash','access_token'],
            'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('users',$getUsers );
    }
	/* 미사용 */
    public function kyclistsearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Users.user_type'=>'U'];
			$searchData['AND']['OR'] =[['Users.id_document_status != '=>'N'],['Users.scan_copy_status != '=>'N']];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if(!empty($search))
			{
				if($search['pagination'] != '') $limit =  $search['pagination'];
				if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
				if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
				if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$this->set('users', $this->Paginator->paginate($this->Users, [
				'fields'=>['id','username','name','phone_number','email','annual_membership','user_level','category','id_type','id_number','id_document_front','id_document_back','id_document_status','scan_copy','scan_copy_status','id_document_reject_reason','scan_copy_reject_reason'],
				'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]));
			
			
		}
	}

	public function kycStatusUpdate(){
		if ($this->request->is(['post' ,'put'])){
			$id = $this->request->data['status_id'];
			$status = $this->request->data['status'];
			$type = $this->request->data['status_type'];
			$rejectReason =  $this->request->data['reject_reason'];
			
			$updateArr = [];
			$updateArr[$type.'_status'] = $status;
			$updateArr[$type.'_reject_reason'] = $rejectReason;
			echo $status;
			if($status == "A"){
                $updateArr['user_level'] = 3;
            }

			$getData = $this->Users->get($id);
			$getData = $this->Users->patchEntity($getData,$updateArr);
			$getData = $this->Users->save($getData);
			$this->add_system_log(200, $id, 3, '인증 단계 3레벨로 수정');
			$this->Flash->success(__('Status updated.'));
			return $this->redirect(['action' => 'kyclist']);
		}
		
		die;
	}	
	
	public function users()
    {
        $this->set('title' , 'Users');
        $this->loadModel('Users');
		$searchData = array();
		$limit =  20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }else $this->set('serial_num',1);

        $searchData['AND'][] = ['Users.user_type'=>'U'];
        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if(!empty($search['pagination'])) $limit =  $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_name']);
        }
        if (!empty($search['user_email'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_email']);
        }

        if(!empty($search['start_date']) && !empty($search['end_date'])) $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->query['start_date'],'DATE(Users.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);

		if (!empty($search['log_block_users'])) {
		   $searchData['AND'][] = array('Users.user_status' => $search['log_block_users']);
        }

		if(!empty($search['annual_members'])){
		    $searchData['AND'][] = array('Users.annual_membership' => $search['annual_members']);
        }

		if(!empty($search['deposit_disable_users'])){
		    $searchData['AND'][] = array('Users.deposit' => $search['deposit_disable_users']);
        }

        if(!empty($search['export']) && $search['export'] !=''){
			// Export
			if($search['export']=='c') $filename = time().'export.csv';
			else  $filename = 'export.xlsx';
			$file = fopen(WWW_ROOT."uploads/".$filename,"w");
			$headers = array('#','Username','Name','Email', 'Annual Membership','Annual Membership Date','Membership Expiry Date', 'User Level', 'Category', 'Phone number','Status','Date Of Registration','Token Wallet Address', 'BTC Address','ETH Address', 'ADMC Address', 'Intr Address', 'BCH Address', 'XRP Address', 'USDT Address', 'Referral Code', 'Unique ID', 'IP Address', 'Bank Name', 'Account Number', 'One Signal ID', 'Device ID');
			fputcsv($file,$headers);
			$users =  $this->Users->find('all',[
				'conditions' => $searchData,
				'order'=>['Users.id'=>'desc']
			]);
			$this->add_system_log(200, 0, 5, '고객 전체 목록 CSV 다운로드');
			
			$k = 1;
			foreach ($users as $k=>$data)
			{

				$userStatus = ($data['enabled'] == 'Y') ? "Active" : "Deactive" ;  		
				
				$arr = [];
				$arr['#'] = $k;
				$arr['Username'] = $data['username'];
				//$arr['Name'] = $data['name'];
				$arr['Name'] = mb_convert_encoding( htmlspecialchars($data['name']), "EUC-KR", "UTF-8" );
				$arr['Email'] = $data['email'];
				$arr['Annual Membership'] = $data['annual_membership'];
				$arr['Annual Membership Date'] = date('Y-m-d H:i:s',strtotime($data['annual_membership_date']));
				$arr['Annual Membership Expiry Date'] = date('Y-m-d H:i:s',strtotime($data['membership_expires_at']));
				$arr['User Level'] = $data['user_level'];
				$arr['Category'] = $data['category'];
				$arr['Phone number'] = $data['phone_number'];
				$arr['Status'] = $userStatus;
				$arr['Date Of Registration'] = date('Y-m-d H:i:s',strtotime($data['created']));
				$arr['Token Wallet Address'] = $data['token_wallet_address'];
				$arr['BTC Address'] = $data['btc_address'];
				$arr['ETH Address'] = $data['eth_address'];
				$arr['ADMC Address'] = $data['admc_address'];
				$arr['INTR Address'] = $data['intr_address'];
				$arr['BCH Address'] = $data['bch_address'];
				$arr['XRP Address'] = $data['xrp_address'];
				$arr['USDT Address'] = $data['usdt_address'];
				$arr['Referral Code'] = $data['referral_code'];
				$arr['Unique ID'] = $data['unique_id'];
				$arr['IP Address'] = $data['ip_address'];
				$arr['Deposit'] = $data['deposit'];
				$arr['Bank Name'] = $data['bank'];
				$arr['Account Number'] = $data['account_number'];
				$arr['One Signal ID'] = $data['onesignal_id'];
				$arr['Device ID'] = $data['device_id'];
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

		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','name','phone_number','annual_membership','annual_membership_date','membership_expires_at','deposit','user_level','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id','user_status'],
            'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);

		$this->set('users',$getUsers );
    }

    public function updateMembership(){
        $this->loadModel('Users');
        if ($this->request->is('ajax')) {
            $user = $this->Users->get($this->request->data['id']);
            $membership = $this->request->data['annual_membership'];

            if (in_array($membership, ["Y", "N"])) {
                $date = Time::now();
                $dates = $date->format('d M Y H:i:s');
                $expiry = "";
                $expiries = "";
                $user->annual_membership = $membership;
                if($membership == "Y"){
                    $user->annual_membership_date = Time::now();

                    $expiry = $date->modify('1 year -1 day');
                    $user->membership_expires_at = $expiry;
                    $expiries = $expiry->format('d M Y H:i:s');
                }
                if ($this->Users->save($user)) {
					$this->add_system_log(200, $this->request->data['id'], 3, '고객 연간회원 업데이트 ('.$membership.')');
                    $respArr = ["success"=>"true","message"=>"user list",'data'=>["timeNow"=>$dates,"expiry"=>$expiries]];
                    echo json_encode($respArr); die;
                } else {
					$this->add_system_log(200, $this->request->data['id'], 3, '고객 연간회원 업데이트 실패');
                    $respArr = ["success"=>"false","message"=>"No Data Found"];
                    echo json_encode($respArr); die;
                }
            } else {
                echo "else";
            }
        }
		die;
    }

    public function updatedeposit(){
        $this->loadModel('Users');
        if ($this->request->is('ajax')) {
            $user = $this->Users->get($this->request->data['id']);
            $deposit = $this->request->data['deposit'];
            if (in_array($deposit, ["Y", "N"])) {
                $user->deposit = $deposit;
                if ($this->Users->save($user)) {
					$this->add_system_log(200, $this->request->data['id'], 3, '고객 입금 차단 업데이트 ('.$deposit.')');
                    echo "success";
                } else {
					$this->add_system_log(200, $this->request->data['id'], 3, '고객 입금 차단 업데이트 실패');
                    echo "error";
                }
            } else {
                echo "else";
            }
        }
		die;
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
				if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
				if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
				if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$this->set('users', $this->Paginator->paginate($this->Users, [
				'fields'=>['id','username','name','phone_number','email','annual_membership','annual_membership_date','membership_expires_at','deposit','user_level','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id'],
				'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]));
			
			
		}
	}
	
	public function adminlist()
    {
        $this->set('title' , 'Users');
        $this->loadModel('Users');
        $searchData = array();
        $limit =  20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }else $this->set('serial_num',1);

        $usersFindList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => function ($e) {
                return $e->username . ' - ' . $e->name;
            },
            'conditions'=>['user_type'=>"A"]])->toArray();
        $this->set('usersFindList',$usersFindList);

        $usersEmailList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => function ($e) {
                return $e->email;
            },
            'conditions'=>['user_type'=>"A"]])->toArray();
        $this->set('usersEmailList',$usersEmailList);
        $searchData['AND'][] = ['Users.user_type'=>'A'];
        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if(!empty($search['pagination'])) $limit =  $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_name']);
        }
        if (!empty($search['user_email'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_email']);
        }

        if(!empty($search['start_date']) && !empty($search['end_date'])) $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->query['start_date'],'DATE(Users.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);

        if(!empty($search['export']) && $search['export'] !='') {
            // Export
            if ($search['export'] == 'c') $filename = time() . 'export.csv';
            else  $filename = 'export.xlsx';
            $file = fopen(WWW_ROOT . "uploads/" . $filename, "w");
            $headers = array('#', 'Username', 'Name', 'Email', 'Phone number', 'Status', 'Date Of Registration');
            fputcsv($file, $headers);
            $users = $this->Users->find('all', [
                'contain'=>['referusers','agctransactions','referral_user','cointransactions','level'],
                'conditions' => $searchData,
                'order' => ['Users.id' => 'desc']
            ]);
			$this->add_system_log(200, 0, 5, '관리자 목록 CSV 다운로드');
            $k = 1;
            foreach ($users as $k => $data) {
                $userStatus = ($data['enabled'] == 'Y') ? "Active" : "Deactive";

                $arr = [];
                $arr['#'] = $k;
                $arr['Username'] = $data['username'];
                $arr['Name'] = $data['name'];
                $arr['Email'] = $data['email'];
                $arr['Phone number'] = $data['phone_number'];
                $arr['Status'] = $userStatus;
                $arr['Date Of Registration'] = date('d M Y', strtotime($data['created']));
                fputcsv($file, $arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/" . $filename, array(
                'download' => true,
                'name' => 'AdminsReport' . time() . $filename
            ));
            return $this->response;
            die;
        }
		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id','level.level_name'],
            'contain'=>['referusers','agctransactions','referral_user','cointransactions','level'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']
        ]);

		$this->set('users',$getUsers );
    }
	/* 미사용 */
    public function adminlistsearch()
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
				if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
				if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
				if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->data['start_date'],'DATE(Users.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);
			}
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			$this->set('users', $this->Paginator->paginate($this->Users, [
				'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id','level.level_name'],
				'contain'=>['referusers','agctransactions','referral_user','cointransactions','level'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]));
			
			
		}
	}
	/* 미사용 */
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
			if($search['username'] != '') $searchData['AND'][] =array('user.username' => $search['username']);
            
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
	/* 미사용 */
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
				if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
				
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
	/* 미사용 */
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
			if($search['name'] != '') $searchData['AND'][] =array('user.name' => $search['name']);
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
				if($search['name'] != '') $searchData['AND'][] =array('user.name' => $search['name']);
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
			if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
			if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
			if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
			if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
			if($search['unique_id'] != '') $searchData['AND'][] =array('Users.unique_id' => $search['unique_id']);
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
				if(isset($search['name']) && $search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
				if(isset($search['email']) && $search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
				if(isset($search['username']) && $search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
				if(isset($search['phone_number']) && $search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
				if(isset($search['unique_id']) && $search['unique_id'] != '') $searchData['AND'][] =array('Users.unique_id' => $search['unique_id']);
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
	
	/* 미사용 */
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
			if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
			if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
			if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
			if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
			if($search['unique_id'] != '') $searchData['AND'][] =array('Users.unique_id' => $search['unique_id']);
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
	/* 미사용 */
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
				if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
				if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
				if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
				if($search['unique_id'] != '') $searchData['AND'][] =array('Users.unique_id' => $search['unique_id']);
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
			if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
			if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
			if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
			if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
			if($search['unique_id'] != '') $searchData['AND'][] =array('Users.unique_id' => $search['unique_id']);
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
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email' => $search['email']);
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username' => $search['username']);
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
	
	
	
//	public function withdrawal()
//    {
//        $this->set('title' , 'Users');
//		$this->loadModel('Transactions');
//		$this->loadModel('Cryptocoin');
//		$searchData = array();
//		$limit =  $this->setting['pagination'];
//		$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
//		//$searchData['AND'][] =['Transactions.cryptocoin_id'=>2];
//
//		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
//													 'valueField' => 'short_name'
//												])->toArray();
//		$this->set('coinList',$coinList);
//
//		if ($this->request->is(['post' ,'put']) )
//		{
//			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
//			$search = $this->request->data;
//			//pr($search);die;
//			if(!empty($search['pagination'])) $limit =  $search['pagination'];
//			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
//			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
//			if(!empty($search['cryptocoin_id'])) $searchData['AND'][] =array('Transactions.cryptocoin_id' => $search['cryptocoin_id']);
//
//
//			if($search['export'] !=''){
//                // Export
//                if($search['export']=='c') $filename = 'eth_withdrawal_export.csv';
//                else  $filename = 'eth_withdrawal_export.xlsx';
//                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
//                $headers = array('#','Username','Email','Tx Id', 'Amount','Wallet Address','Status','created');
//                fputcsv($file,$headers);
//                $users =  $this->Transactions->find('all',[
//                    'contain'=>['user','cryptocoin'],
//					'conditions' => ['Transactions.tx_type'=>'withdrawal','Transactions.cryptocoin_id'=>2],
//					//'limit' => $limit,
//					'order'=>['Transactions.id'=>'desc']
//
//                ])->hydrate(false)->toArray();
//
//
//                $ks = 1;
//                foreach ($users as $k=>$data)
//                {
//
//
//					if($data['withdrawal_send']=='N' && !empty($data['withdrawal_id'])){
//						$usedStatus = "Processing";
//						$txId = '';
//					}
//					else if($data['withdrawal_send']=='Y') {
//						$usedStatus = "Completed";
//						$txId = !empty($data['withdrawal_id']) ? $data['withdrawal_tx_id'] : '';
//					}
//					else if($data['withdrawal_send']=='N') {
//						$usedStatus = "Pending";
//						$txId = '';
//					}
//					else {
//						$usedStatus = "Nil";
//						$txId = '';
//					}
//
//
//                    $arr = [];
//                    $arr['#'] = $ks;
//                    $arr['Username'] = $data['user']['username'];
//                    $arr['Email'] = $data['user']['email'];
//                    $arr['Tx Id'] = $txId;
//                    $arr['Amount'] = abs($data['coin_amount'])." ".$data['cryptocoin']['short_name'];
//                    $arr['Wallet Address'] = $data['wallet_address'];
//                    $arr['Status'] = $usedStatus;
//					 $arr['created'] = $data['created'];
//                    fputcsv($file,$arr);
//                    $ks++;
//                }
//                fclose($file);
//                $this->response->file("uploads/".$filename, array(
//                    'download' => true,
//                    'name' => 'report'.time().$filename
//                ));
//                return $this->response;die;
//            }
//				$limit = 1000000000000;
//		}
//
//
//
//		$withdrawals = $this->Paginator->paginate($this->Transactions, [
//            'contain'=>['user','cryptocoin'],
//            'conditions' => $searchData,
//            'limit' => $limit,
//            'order'=>['id'=>'desc']
//
//        ]);
//		//print_r($withdrawals); die;
//		$this->set('withdrawals',$withdrawals );
//    }
   
//updated withdrawal:

    public function withdrawal()
    {
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');

        $authUserId = $this->Auth->user('id');
        $searchData = [];
        $limit = 20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        $coinList = $this->get_coin_list();
		$this->set('coinList',$coinList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        //$searchData['AND'][] = ['Transactions.tx_type'=>'withdrawal'];
//        if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
//			if(!empty($search['user_name'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
//			if(!empty($search['cryptocoin_id'])) $searchData['AND'][] =array('Transactions.cryptocoin_id' => $search['cryptocoin_id']);

        if(!empty($search['pagination'])) $limit =  $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('user.id' => $search['user_name']);
        }
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
        }

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(Transactions.created) >= ' => $this->request->query['start_date'],'DATE(Transactions.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);


        if($this->request->query('export')){
            // Export
            $filename = time().'withdrawal_export.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Email','Tx Id', 'Coin','Amount','Wallet Address','Status','Date & Time');
            fputcsv($file,$headers);
            $users =  $this->Transactions->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number','email']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions' => ['Transactions.tx_type'=>'withdrawal']+$searchData,
                'order'=>['Transactions.id'=>'desc']
            ]);
			$this->add_system_log(200, 0, 5, '인출 목록 CSV 다운로드');


            $k = 1;
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
                $arr['#'] = $k;
                $arr['User ID'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Email'] = $data['user']['email'];
                $arr['Tx ID'] = $txId;
                $arr['Amount'] = abs($data['coin_amount'])." ".$data['cryptocoin']['short_name'];
                $arr['Wallet Address'] = $data['wallet_address'];
                $arr['Status'] = $usedStatus;
                $arr['Created'] = $data['created'];
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'WithdrawalReport'.$filename
            ));
            return $this->response;die;
        }

        $withdrawals = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user'=>['fields'=>['name','phone_number','email']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions' => ['Transactions.tx_type'=>'withdrawal']+$searchData,
            'limit' => $limit,
            'order'=>['Transactions.id'=>'desc']

        ]);

        $this->set('withdrawals',$withdrawals );
    }

   public function withdrawalSearch()
	{
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			$this->loadModel("Transactions");

			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Transactions.tx_type'=>'withdrawal'];
			//$searchData['AND'][] =['Transactions.cryptocoin_id'=>2];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {
				
				$this->set('serial_num',1);
			
			}
		
			$withdrawals = $this->Paginator->paginate($this->Transactions, [
				'contain'=>['user','cryptocoin'],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			
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
			$this->add_system_log(200, 0, 3, 'Transactions status 수정 (Transactions id :: '.$id.')');
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
					$this->add_system_log(200, 0, 3, 'Transactions 수정 (Transactions id :: '.$id.')');
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
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email' => $search['email']);
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username' => $search['username']);
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
			if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
			if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
			
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
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email' => $search['email']);
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username' => $search['username']);
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


	
	
	
//	public function deposit()
//    {
//        $this->set('title' , 'Users');
//		$this->loadModel('Transactions');
//		$this->loadModel('Cryptocoin');
//		$searchData = array();
//		$limit =  $this->setting['pagination'];
//		$searchData['AND'][] =['Transactions.tx_type'=>'purchase'];
//		//$searchData['AND'][] =['Transactions.cryptocoin_id'=>2];
//
//		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
//													 'valueField' => 'short_name'
//												])->toArray();
//		$this->set('coinList',$coinList);
//
//		if ($this->request->is(['post' ,'put']) )
//		{
//			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
//			$search = $this->request->data;
//			//pr($search);die;
//			if(!empty($search['pagination'])) $limit =  $search['pagination'];
//			if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
//			if(!empty($search['username'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
//			if(!empty($search['tx_id'])) $searchData['AND'][] =array('Transactions.tx_id' => $search['tx_id']);
//			if(!empty($search['wallet_address'])) $searchData['AND'][] =array('Transactions.wallet_address' => $search['wallet_address']);
//			if(!empty($search['cryptocoin_id'])) $searchData['AND'][] =array('Transactions.cryptocoin_id' => $search['cryptocoin_id']);
//
//
//			if($search['export'] !=''){
//                // Export
//                if($search['export']=='c') $filename = 'eth_withdrawal_export.csv';
//                else  $filename = 'eth_withdrawal_export.xlsx';
//                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
//                $headers = array('#','Username','Email','Tx Id', 'Amount','Wallet Address','Status','created');
//                fputcsv($file,$headers);
//                $users =  $this->Transactions->find('all',[
//                    'contain'=>['user','cryptocoin'],
//					'conditions' => ['Transactions.tx_type'=>'purchase','Transactions.cryptocoin_id'=>2],
//					//'limit' => $limit,
//					'order'=>['Transactions.id'=>'desc']
//
//                ])->hydrate(false)->toArray();
//
//
//                $ks = 1;
//                foreach ($users as $k=>$data)
//                {
//
//
//					if($data['withdrawal_send']=='N' && !empty($data['withdrawal_id'])){
//						$usedStatus = "Processing";
//						$txId = '';
//					}
//					else if($data['withdrawal_send']=='Y') {
//						$usedStatus = "Completed";
//						$txId = !empty($data['withdrawal_id']) ? $data['withdrawal_tx_id'] : '';
//					}
//					else if($data['withdrawal_send']=='N') {
//						$usedStatus = "Pending";
//						$txId = '';
//					}
//					else {
//						$usedStatus = "Nil";
//						$txId = '';
//					}
//
//
//                    $arr = [];
//                    $arr['#'] = $ks;
//                    $arr['Username'] = $data['user']['username'];
//                    $arr['Email'] = $data['user']['email'];
//                    $arr['Tx Id'] = $txId;
//                    $arr['Amount'] = abs($data['coin_amount'])." ".$data['cryptocoin']['short_name'];
//                    $arr['Wallet Address'] = $data['wallet_address'];
//                    $arr['Status'] = $usedStatus;
//					$arr['created'] = $data['created'];
//                    fputcsv($file,$arr);
//                    $ks++;
//                }
//                fclose($file);
//                $this->response->file("uploads/".$filename, array(
//                    'download' => true,
//                    'name' => 'report'.time().$filename
//                ));
//                return $this->response;die;
//            }
//			$limit = 100000;
//		}
//
//
//
//		$getData = $this->Paginator->paginate($this->Transactions, [
//            'contain'=>['user','cryptocoin'],
//            'conditions' => $searchData,
//            'limit' => $limit,
//            'order'=>['id'=>'desc']
//
//        ]);
//		//print_r($getUsers); die;
//		$this->set('getData',$getData );
//    }
//updated deposit function:
    public function deposit()
    {
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');

        $authUserId = $this->Auth->user('id');
        $searchData = [];
        $limit = 20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        $coinList = $this->get_coin_list();
        $this->set('coinList',$coinList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        //$searchData['AND'][] = ['Transactions.tx_type'=>'withdrawal'];
//        if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
//			if(!empty($search['user_name'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
//			if(!empty($search['cryptocoin_id'])) $searchData['AND'][] =array('Transactions.cryptocoin_id' => $search['cryptocoin_id']);

        if(!empty($search['pagination'])) $limit =  $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('user.id' => $search['user_name']);
        }
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
        }

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(Transactions.created) >= ' => $this->request->query['start_date'],'DATE(Transactions.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);

        if($this->request->query('export')){
			$this->add_system_log(200, 0, 5, '예금 목록 CSV 다운로드');
            // Export
            $filename = time().'withdrawal_export.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Email','Tx Id', 'Coin','Amount','Wallet Address','Status','Date & Time');
            fputcsv($file,$headers);
            $users =  $this->Transactions->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number','email']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions' => ['Transactions.tx_type'=>'purchase']+$searchData,
                'order'=>['Transactions.id'=>'desc']
            ]);


            $k = 1;
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
                $arr['#'] = $k;
                $arr['User ID'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Email'] = $data['user']['email'];
                $arr['Tx ID'] = $txId;
                $arr['Amount'] = abs($data['coin_amount'])." ".$data['cryptocoin']['short_name'];
                $arr['Wallet Address'] = $data['wallet_address'];
                $arr['Status'] = $usedStatus;
                $arr['Created'] = $data['created'];
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'WithdrawalReport'.$filename
            ));
            return $this->response;die;
        }

        $getData = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user'=>['fields'=>['name','phone_number','email']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions' => ['Transactions.tx_type'=>'purchase']+$searchData,
            'limit' => $limit,
            'order'=>['Transactions.id'=>'desc']

        ]);

        $this->set('getData',$getData );
    }

   public function depositSearch()
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
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email' => $search['email']);
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username' => $search['username']);
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
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email' => $search['email']);
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username' => $search['username']);
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
			if(!empty($search['email'])) $searchData['AND'][] =array('user.email' => $search['email']);
			if(!empty($search['username'])) $searchData['AND'][] =array('user.username' => $search['username']);
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

	public function userbalnace()
    {
		$this->set('title' , 'Users');
        $this->loadModel('Users');
        $searchData = array();
        $limit =  20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }else $this->set('serial_num',1);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if(!empty($search['pagination'])) $limit =  $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_name']);
        }
        if (!empty($search['user_email'])) {
            $searchData['AND'][] = array('Users.id' => $search['user_email']);
        }

        if(!empty($search['start_date']) && !empty($search['end_date'])) $searchData['AND'][] = array('DATE(Users.created) >= ' => $this->request->query['start_date'],'DATE(Users.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Users.created)' => $search['end_date']);

        if(!empty($search['export']) && $search['export'] !=''){
            // Export
            if($search['export']=='c') $filename = time().'export.csv';
            else  $filename = 'export.xlsx';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','Username','Name','Email','Phone number','Status','Date Of Registration');
            fputcsv($file,$headers);
            $users =  $this->Users->find('all',[
                'conditions' => $searchData,
                'order'=>['Users.id'=>'desc']
            ]);

            $k = 1;
            foreach ($users as $k=>$data)
            {
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

		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','name','phone_number','user_level','annual_membership','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id'],
            'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);

		$this->set('users',$getUsers );
    }
   
	public function userbalnacesearch()
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
				if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
				if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
				if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
				if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
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
	
	public function usercoinbalance($coinShortName)
    {
	    $this->loadModel("Cryptocoin");
		$this->set('title' , 'Users');
		$searchData = array();
		
		
		$this->set('coinShortName',$coinShortName );
		$limit =  $this->setting['pagination'];
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		$searchData['AND'][] =['Users.user_type'=>'U'];
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
			if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['email']);
			if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
			if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
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
            //'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
            'conditions' => $searchData,
            'limit' => 10000000000000,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('users',$getUsers );
		
		//$userId = $this->Auth->user('id');
		
		

		
    }
   	
	
	public function usercoinbalancetest($coinShortName)
    {
	    $this->loadModel("Cryptocoin");
		$this->set('title' , 'Users');
		$searchData = array();
		
		
		$this->set('coinShortName',$coinShortName );
		$limit =  $this->setting['pagination'];
		//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
		$searchData['AND'][] =['Users.user_type'=>'U'];
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['name'] != '') $searchData['AND'][] =array('Users.name' => $search['name']);
			if($search['email'] != '') $searchData['AND'][] =array('Users.email' => $search['eml']);
			if($search['username'] != '') $searchData['AND'][] =array('Users.username' => $search['username']);
			if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number' => $search['phone_number']);
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
            //'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
            'conditions' => $searchData,
            'limit' => 10000000000000,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('users',$getUsers );
		
		//$userId = $this->Auth->user('id');
		
		

		
    }
	/* 고객 토탈 밸런스 */
	public function usercoinbalancefast($coinShortName)
    {
	    $this->loadModel("Cryptocoin");
		$this->loadModel("PrincipalWallet");
		$this->loadModel("Users");
		$this->set('title' , 'Users');
		$this->set('coinShortName',$coinShortName );
		$limit =  $this->setting['pagination'];
		$settings = array('limit' => 10);
		$session = $this->request->session();

		$coin = $this->Cryptocoin->find()->select(['id'])->where(['short_name' => $coinShortName])->first();

		$principal_sql = 'ifnull((select sum(amount) from principal_wallet where user_id = Users.id and cryptocoin_id = '.$coin->id.' and status = "completed"),0)';
		$withdraw_sql = 'ifnull((select sum(coin_amount) from transactions where user_id = Users.id and cryptocoin_id = '.$coin->id.' and status = "completed" and (tx_type != "bank_initial_deposit" and tx_type != "bank_initial_withdraw")),0)';
		$reserve_sql = 'ifnull((select ifnull(sum(coin_amount),0)*-1 FROM transactions JOIN buy_exchange on buy_exchange.id = transactions.exchange_id where user_id = Users.id and cryptocoin_id = '.$coin->id.' and transactions.status = "completed" and remark = "reserve for exchange" and buy_exchange.status in ("pending","processing")),0) + ifnull((select (ifnull(sum(coin_amount),0)*-1) FROM transactions JOIN sell_exchange on sell_exchange.id = transactions.exchange_id where user_id = Users.id and cryptocoin_id = '.$coin->id.' and transactions.status = "completed" and remark = "reserve for exchange" and sell_exchange.status in ("pending","processing")),0)';
		$investment_amount_sql = '0';
		$investment_wallet_amount_sql = '0';
		
		$total_sql = '( '.$principal_sql.' + '. $withdraw_sql.' + '. $reserve_sql; 
		if($coin->id == 20){
			$total_sql .= ' + ifnull((select sum(amount) from principal_wallet where user_id = Users.id and cryptocoin_id = '.$coin->id.' and status = "pending" and type = "bank_initial_withdraw"),0)'; //
			$investment_wallet_amount_sql = 'ifnull((select amount from deposit_application_wallet where user_id = Users.id),0)';
			$total_sql .= ' + '. $investment_wallet_amount_sql; //
		}
		if($coin->id == 17){
			$investment_amount_sql = 'ifnull((select sum(quantity) from deposit_application_list where user_id = Users.id and status != "C"),0)';
			$total_sql .= ' + '.$investment_amount_sql; //
		}
		$total_sql .= ' )';

		$erc20_sql = 'ifnull((select sum(amount) from principal_wallet where user_id = Users.id and cryptocoin_id = '.$coin->id.' and status = "completed" and remark = "erc20_purchase"),0)';

		$query = $this->Users->find('all')->select(['id','username','name','phone_number','email','totalBalance'=>$total_sql,'principalBalance'=>$principal_sql,'withdrawBalance'=>$withdraw_sql,'reserveBalance'=>$reserve_sql,'investment_amount'=>$investment_amount_sql,'investment_wallet_amount'=>$investment_wallet_amount_sql,'erc20Balance'=>$erc20_sql]);
		
		if($this->request->query('search_value')){
			$search_value = $this->request->query('search_value');
			if(is_numeric($search_value)){
				$query = $query -> where(['OR'=>[['phone_number' =>$search_value],['id'=> $search_value]]]);
			} else {
				$query = $query -> where(['name' =>$search_value]);
			}
		}

		$query = $query->where(['user_type'=>'U']);

		if($this->request->query('sort_value')){
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			$query = $query->order([$order_value=>$sort_value]);
		}else{
			$query = $query->order(['totalBalance'=> 'DESC']);
		}

		if($this->request->query('minus_check') == 'Y'){
			$settings = array('limit' => 100);
		}	
		try {
			$user_list = $this->Paginator->paginate($query,$settings);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$user_list = $this->Paginator->paginate($query,$settings);
		}
		$this->set('users',$user_list);
		$this->set('coin_id',$coin->id);

   	}

	/* 키생성 */
    public function generatekeys(){
        if ($this->request->is(['post','put'])){
            $row = 1;

            if(!empty($this->request->data('csv_file')['name'])){
                $file_path = WWW_ROOT.'uploads/private_keys/'.time().'_'.$this->request->data('csv_file')['name'];
                $tmp_name = $this->request->data('csv_file')['tmp_name'];
                $file_name = iconv("utf-8","EUC-KR",$file_path);
                //output file
                $filename = time().'_result.csv';
                $files = fopen(WWW_ROOT."uploads/private_keys/".$filename,"w");
                header('Content-type: text/csv');
                header('Content-Disposition: attachment; filename='.$filename);
                header("Pragma: no-cache");
                header("Expires: 0");
                $headers = array('Private Key','Result');
                fputcsv($files,$headers);

                if(move_uploaded_file($tmp_name,$file_name)){
                    if (($handle = fopen($file_name, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $num = count($data);
                            for ($c=0; $c < $num; $c++) {
                                $arr=[];
                                $userpvtkey = $data[$c];

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_PORT => "3000",
                                    CURLOPT_URL => "http://54.180.5.130:3000/generate_passcode",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => "",
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 60,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => "POST",
                                    CURLOPT_POSTFIELDS => "{\n\t\"pvtkey\":\"".$userpvtkey."\"\t\n}",
                                    CURLOPT_HTTPHEADER => array(
                                        "cache-control: no-cache",
                                        "content-type: application/json",
                                        "postman-token: eb0783a3-f404-9d7c-b9ba-32ebeefe2c65"
                                    ),
                                ));

                                $response = curl_exec($curl);
                                $err = curl_error($curl);
                                curl_close($curl);
                                if(!empty($response)){
                                    $resultDecode = json_decode($response,true);
                                    if(!empty($resultDecode['success'])) {
                                        //echo $response;
                                        $arr['Private Key'] = $userpvtkey;
                                        $arr['Result'] = "Success";
                                        fputcsv($files,$arr);
                                        //$this->Flash->success(__('Generated Successfully.'));
                                    }else {
                                        $arr['Private Key'] = $userpvtkey;
                                        $arr['Result'] = "Failed";
                                        fputcsv($files,$arr);
                                        //$this->Flash->error(__('Cannot generate the password for this key'));
                                    }
                                } else {
                                   // $this->Flash->error(__('Cannot generate the password for this key'));
                                }
                            }
                            $row++;
                        }
                        fclose($handle);
                        fclose($files);


                        $file = new File($file_path,false,0777);
                        $downFile = WWW_ROOT.'uploads/private_keys/'.$filename;

                        ob_clean();
                        readfile($downFile);

                        if($file->delete() && unlink($downFile)){
							$this->add_system_log(200, 0, 2, '키 생성 (csv : '.$file_name.')');
                            $this->Flash->success(__("Successfully generated the passwords and deleted the uploaded file!"));
                            //$this->redirect(['controller'=>'reports','action'=>'generatekeys']);

                        } else {
                            $this->Flash->error(__("Cannot delete!"));
                        }
                        die;
                        return $this->redirect(['controller'=>'reports','action'=>'generatekeys']);

                    } else {
						$this->add_system_log(200, 0, 2, '키 생성 오류(파일을 열 수 없음)');
                        $this->Flash->error(__('Cannot open the file'));
                    }

                }else {
					$this->add_system_log(200, 0, 2, '키 생성 오류(파일을 업로드 할 수 없음)');
                    $this->Flash->error(__('Cannot upload the file'));
                }
            }else {
				$this->add_system_log(200, 0, 2, '키 생성 오류(파일 미첨부)');
                $this->Flash->error(__('File is empty'));
            }

        }

    }

//    public function download($id)
//    {
//        $file_path = WWW_ROOT.'uploads/private_keys'.DS.$id;
//        $this->autoRender=true;
//        $this->response->file($file_path,array('download' => true));
//    }
	/* 고객 로그인 차단 기능 */
	public function loginblock(){
		$this->loadModel('Users');
		$this->loadModel('LoginSessions');
		if ($this->request->is('ajax')) {
			$id = $this->request->data['id'];
			$type = $this->request->data['type'];
			$user_status = 'A';
			if($type == 'A'){
				$user_status = 'B';
			} else if ($type == 'B'){
				$user_status = 'A';
			}
			$query = $this->Users->query();
			$query->update()->set(['user_status' => $user_status,'blocked'=>date('Y-m-d H:i:s')])->where(['id' => $id])->execute();

			$new_query = $this->LoginSessions->query();
			$new_query->update()->set(['status' => 'INACTIVE'])->where(['user_id' => $id])->execute();
			$this->add_system_log(200, $id, 3, '로그인 차단 업데이트 ('.$user_status.')');

			echo "success";
		}
		die;
	}
	public function multisignwithdrawal(){
		
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');

        $authUserId = $this->Auth->user('id');
        $searchData = [];
		$searchData['AND'][] = array('cryptocoin.id IN ' => [5,18,17,19,20,21]);
		$searchData['AND'][] = array('Transactions.multisign' => 'Y');
        $limit = 20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        $coinList = $this->get_coin_list();
        $this->set('coinList',$coinList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        //$searchData['AND'][] = ['Transactions.tx_type'=>'withdrawal'];
//        if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
//			if(!empty($search['user_name'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
//			if(!empty($search['cryptocoin_id'])) $searchData['AND'][] =array('Transactions.cryptocoin_id' => $search['cryptocoin_id']);

        if(!empty($search['pagination'])) $limit =  $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('user.id' => $search['user_name']);
        }
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
        }

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(Transactions.created) >= ' => $this->request->query['start_date'],'DATE(Transactions.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);

        if($this->request->query('export')){
            // Export
            $filename = time().'withdrawal_export.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Email','Tx Id', 'Coin','Amount','Wallet Address','Status','Date & Time');
            fputcsv($file,$headers);
            $users =  $this->Transactions->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number','email']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions' => ['Transactions.tx_type'=>'withdrawal']+$searchData,
                'order'=>['Transactions.id'=>'desc']
            ]);
			$this->add_system_log(200, 0, 5, '인출 목록 CSV 다운로드');

            $k = 1;
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
                $arr['#'] = $k;
                $arr['User ID'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Email'] = $data['user']['email'];
                $arr['Tx ID'] = $txId;
                $arr['Amount'] = abs($data['coin_amount'])." ".$data['cryptocoin']['short_name'];
                $arr['Receiver Wallet Address'] = $data['wallet_address'];
                $arr['Status'] = $usedStatus;
                $arr['Created'] = $data['created'];
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'WithdrawalReport'.$filename
            ));
            return $this->response;die;
        }

        $withdrawals = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user'=>['fields'=>['name','phone_number','email']],
                'cryptocoin'=>['fields'=>['short_name']],'withdrawals'=>['fields'=>['user_id','wallet_name','wallet_address','cryptocoin_id']]],
            'conditions' => ['Transactions.tx_type'=>'withdrawal']+$searchData,
            'limit' => $limit,
            'order'=>['Transactions.id'=>'desc']

        ]);

        $this->set('withdrawals',$withdrawals);
    }

    public function multisignwithdrawalmain(){

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');

        $authUserId = $this->Auth->user('id');
        $searchData = [];
        $searchData['AND'][] = array('cryptocoin.id IN ' => [5,18,17,19,20,21]);
        $searchData['AND'][] = array('PrincipalWallet.multisign' => 'Y');
        $limit = 20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        $coinList = $this->get_coin_list();
        $this->set('coinList',$coinList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        //$searchData['AND'][] = ['Transactions.tx_type'=>'withdrawal'];
//        if(!empty($search['email'])) $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
//			if(!empty($search['user_name'])) $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
//			if(!empty($search['cryptocoin_id'])) $searchData['AND'][] =array('Transactions.cryptocoin_id' => $search['cryptocoin_id']);

        if(!empty($search['pagination'])) $limit =  $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('user.id' => $search['user_name']);
        }
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
        }

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);

        if($this->request->query('export')){
            // Export
            $filename = time().'multisig_withdrawal_export.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Email','Tx Id', 'Coin','Amount','Wallet Address','Status','Date & Time');
            fputcsv($file,$headers);
            $users =  $this->PrincipalWallet->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number','email']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions' => ['PrincipalWallet.type'=>'withdrawal']+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc']
            ]);
            $this->add_system_log(200, 0, 5, '인출 목록 CSV 다운로드');

            $k = 1;
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
                $arr['#'] = $k;
                $arr['User ID'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Email'] = $data['user']['email'];
                $arr['Tx ID'] = $txId;
                $arr['Amount'] = abs($data['coin_amount'])." ".$data['cryptocoin']['short_name'];
                $arr['Wallet Address'] = $data['wallet_address'];
                $arr['Status'] = $usedStatus;
                $arr['Created'] = $data['created'];
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'WithdrawalReport'.$filename
            ));
            return $this->response;die;
        }

        $withdrawals = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number','email']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions' => ['PrincipalWallet.type'=>'withdrawal']+$searchData,
            'limit' => $limit,
            'order'=>['PrincipalWallet.id'=>'desc']

        ]);

        $this->set('withdrawals',$withdrawals );
    }
	
	public function signwithdrawal(){
		if ($this->request->is('ajax')){
			$getIndexId = $this->request->data['get_index_id'];
			$getList = explode(",",$getIndexId);
			$getTransctions = $this->Transactions->find("all",["conditions"=>["multisign_index_id IN "=>$getList]])->hydrate(false)->toArray();
			
			//if(!empty($getTransctions) && (count($getTransctions) == count($getList))) {
			if(!empty($getTransctions)) {
				 // send Test withdrawal transaction
				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_PORT => "3000",
					CURLOPT_URL => 'http://54.180.5.130:3000/multisign/sign_transactions',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS =>'{"tx_index_id":"'.$getIndexId.'"}',
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json'
					),
				));

				$response = curl_exec($curl);
				curl_close($curl);

				$decodeResp = json_decode($response,true);

				if(empty($decodeResp["success"])){
					$returnArr = ['success' => false, "message" => $decodeResp["message"], 'data' =>""];
					echo json_encode($returnArr);
					die;

				}
				$this->Transactions->updateAll(['multisign_sign_count'=>2,'withdrawal_send'=>'Y','tx_id'=>$decodeResp['data']],['multisign_index_id IN '=>$getList]);
				echo json_encode($decodeResp); die;
			}
			else {
				$respArr = ["success"=>false,"message"=>"Invalid Transaction","data"=>""];
				echo json_encode($respArr); die;
			}
		}
	}

    public function signwithdrawalmain(){
        if ($this->request->is('ajax')){
            $this->loadModel('PrincipalWallet');
            $getIndexId = $this->request->data['get_index_id'];
            $getTransctions = $this->PrincipalWallet->find("all",["conditions"=>["multisign_index_id"=>$getIndexId]])->hydrate(false)->first();
            if(!empty($getTransctions)){
                // send Test withdrawal transaction
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_PORT => "3000",
					CURLOPT_URL => 'http://54.180.5.130:3000/multisign/sign_transactions',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{"tx_index_id":'.$getIndexId.'}',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);

                $decodeResp = json_decode($response,true);
				
                if(empty($decodeResp["success"])){
                    $returnArr = ['success' => false, "message" => $decodeResp["message"], 'data' =>""];
                    echo json_encode($returnArr);
                    die;

                }
                $this->PrincipalWallet->updateAll(['multisign_sign_count'=>2,'withdrawal_send'=>'Y','tx_id'=>$decodeResp['data']],['multisign_index_id'=>$getIndexId]);
                echo json_encode($decodeResp);
            }
            else {
                $respArr = ["success"=>false,"message"=>"Invalid Transaction","data"=>""];
                echo json_encode($respArr);
            }
            die;
        }
    }
	// 20210826 Hassam
	//Transfer users main account balance to CTC Wallet
    public function transfer() {
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('WithdrawalWalletAddress');
        if ($this->request->is('post')) {
            $userId = $this->request->data['id'];
            $user = $this->Users->get($userId);
            $returnArr = [];
            $getCoinList = $this->Cryptocoin->find('all', ['conditions' => ['status' => 1], 'order' => ['serial_no' => 'asc']])->hydrate(false)->toArray();
            foreach ($getCoinList as $getCoin) {
                $coinId = $getCoin['id'];
                $principalBalance = $this->Users->getUserPricipalBalance($userId, $coinId);
                $btcWalletAddr = $user['btc_address'];
                $ethWalletAddr = $user['eth_address'];
                $userWalletAddr = ($coinId == 1) ? $btcWalletAddr : $ethWalletAddr;

                $walletAddress = $this->WithdrawalWalletAddress->find('all', ['conditions' => ['user_id' => $userId]])->hydrate(false)->first();
                if (!empty($principalBalance)) {
                    $deductBalanceArrs = ['amount' => -$principalBalance, 'status' => 'completed', 'type' => 'withdrawal',
                        'user_id' => $userId, 'wallet_address' => $walletAddress['wallet_address'], 'cryptocoin_id' => $coinId];

                    //CTC WALLET API INTEGRATION START
                    $auth_key = 'BE14273125KL';
                    $kind = 'withdrawal_epay';
                    $coin_type = $getCoin['short_name'];
                    if ($coinId == 1) {
                        $address = $user->btc_address;
                    } else {
                        $address = $user->eth_address;
                    }
                    $data = array(
                        'auth_key' => $auth_key,
                        'kind' => $kind,
                        'coin_type' => $coin_type,
                        'wallet_address' => $walletAddress['wallet_address'],
                        'address' => $address,
                        'users_id' => $userId,
                        'amount' => $principalBalance
                    );
                    $post_data = json_encode($data);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_PORT => "",
                        CURLOPT_URL => "https://cybertronchain.com/apis/coinibt/coinibt.php",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 60,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $post_data,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json"
                        ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    $decodeResp = json_decode($response, true);

                    if (!empty($decodeResp)) {
                        if ($decodeResp['code'] == 200) {
                            $newObj = $this->PrincipalWallet->newEntity();
                            $newObj = $this->PrincipalWallet->patchEntity($newObj, $deductBalanceArrs);
                            $saveThisData = $this->PrincipalWallet->save($newObj);
                            if ($saveThisData){
                                $returnArr = ['success'=>"true","message"=>$decodeResp['msg']];
                            }else {
                                $returnArr = ['success'=>"false","message"=>'Error Saving'];
                            }
                            //$this->Flash->success(__('Sent successfully All from main!'));
                        } else if($decodeResp['code'] == 801) {
                            $returnArr = ['success'=>"false","message"=>$decodeResp['msg'].'801'];
                        } else if($decodeResp['code'] == 802) {
                            $returnArr = ['success'=>"false","message"=>$decodeResp['msg'].'802'];
                        } else if($decodeResp['code'] == 804) {
                            $returnArr = ['success'=>"false","message"=>$decodeResp['msg'].'804'];
                        } else if($decodeResp['code'] == 805) {
                            $returnArr = ['success'=>"false","message"=>$decodeResp['msg'].'805'];
                        } else if($decodeResp['code'] == 806) {
                            $returnArr = ['success'=>"false","message"=>$decodeResp['msg'].'806'];
                        }
                    } else {
                        $returnArr = ['success'=>"false","message"=>'Error'];
                    }
                }
            }
            echo json_encode($returnArr);
            die;
        }
    }
}
