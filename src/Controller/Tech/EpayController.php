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
//use Cake\ORM\TableRegistry;
//use Cake\Validation\Validation;
//use Cake\Datasource\ConnectionManager;

class EpayController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
	public function settings()
	{
	
        $this->set('title' , 'Epay Settings');
		$this->loadModel('Users');

		$addtypeList = array('inc' => '+', 'dec' => '-'); // +, -
		$category = array('Deposit' => 'Deposit');
		
		$this->loadModel('Epay');
		$epayList = $this->Epay->find('list', ['keyField' => 'id',
													 'valueField' => 'short_name'
												],['conditions'=>['id !='=>1]])->toArray();
		$this->set('coinList',$epayList);
        $this->set('addtypeList' , $addtypeList);
        $this->set('category' , $category);

        $this->loadModel('EpayLogs');	
		$multipleAddArr = [];
		$add=$this->EpayLogs->newEntity();
		if ($this->request->is(['post' ,'put'])) {
			$userIds=$this->request->data['user_ids'];
			if(!empty($userIds)){
				foreach($userIds as $userId){
					if(!empty($userId)){
						//$getTxid = $this->Users->getUniqueTxId();
						$purArr=[];
						$purArr['user_id']=$userId;
						$purArr['epay_id']=$this->request->data['epay_id'];
						$amount = $this->request->data['amount'];
						if ( $this->request->data['add_type'] == 'dec' ) {
							$amount = '-'.$amount;
						}
						$purArr['amount']=$amount;
						$purArr['type']=$this->request->data['e_type'];
						$multipleAddArr[]=$purArr;
					}
				}
			}
			else {
				$this->add_system_log(200, 0, 2,'E-pay 보내기 실패 - (보낼 고객 선택하지 않음)');
				$this->Flash->error(__('Please select at least one user.'));
				return $this->redirect(['controller'=>'Epay','action'=>'settings']);
			}
			
			//print_r($multipleAddArr); die;
			if(!empty($multipleAddArr)){
				$addEntity = $this->EpayLogs->newEntities($multipleAddArr);
				$save = $this->EpayLogs->saveMany($addEntity);
				if($save){
					foreach($userIds as $userId){
						$this->add_system_log(200, $userId, 2, 'E-pay 보내기 성공');
					}
					$this->Flash->success(__('epay send successfully.'));
					return $this->redirect(['controller'=>'Epay','action'=>'settings']);
				}
				else {
					$this->add_system_log(200, 0, 2,'E-pay 보내기 실패');
					$this->Flash->error(__('unable to send epay.'));
					return $this->redirect(['controller'=>'Epay','action'=>'settings']);
				}
			}
			else {
				$this->add_system_log(200, 0, 2,'E-pay 보내기 실패 - (빈항목)');
				$this->Flash->error(__('No epay found.'));
				return $this->redirect(['controller'=>'Epay','action'=>'settings']);
			}
			/**/
		 }
		 $this->set('add',$add); 
	}

	
	public function lists()
	{
	
        $this->set('title' , 'Epay');
		$searchData = array();
        $this->loadModel('Users');
        $searchData = array();
        $limit =  20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }else $this->set('serial_num',1);

		$searchData['AND'][] =['Users.user_type'=>'U'];

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
                // 'fields'=>['id','username','name','phone_number','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled'],
                //'contain'=>['referusers','agctransactions','referral_user'],
                'conditions' => $searchData,
                // 'limit' => $limit,
                'order'=>['Users.id'=>'desc']

            ]);
			$this->add_system_log(200, 0, 5, 'E-pay 고객 CSV 다운로드 (이름, 전화번호, 메일)');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $userStatus = ($data['enabled'] == 'Y') ? "Active" : "Deactive" ;

                $arr = [];
                $arr['#'] = $k;
                $arr['Name'] = $data['name'];
                $arr['Phone number'] = $data['phone_number'];
                $arr['Email'] = $data['email'];
                $arr['User Level'] = $data['user_level'];
                $arr['BTC Address'] = $data['btc_address'];
                $arr['ETH Address'] = $data['eth_address'];
                $arr['Status'] = $userStatus;
                $arr['Date Of Registration'] = $data['created'];
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'EpayUserReport'.time().$filename
            ));
            return $this->response;die;
        }

		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','name','phone_number','annual_membership','user_level','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id', 'eth_address', 'btc_address'],
            'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']
        ]);
		$this->set('users',$getUsers );

	}


	public function listsub()
	{

		if ($this->request->is('ajax'))
		{
			$searchData = array();
			$limit =  $this->setting['pagination'];
			//$searchData['AND'][] =['Users.user_type'=>'U','Users.enabled'=>'Y'];
			$searchData['AND'][] =['Users.user_type'=>'U'];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['name'] != '') $searchData['AND'][] =array('Users.name LIKE' => '%'.$search['name'].'%');
			if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
			if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
			if($search['phone_number'] != '') $searchData['AND'][] =array('Users.phone_number LIKE' => '%'.$search['phone_number'].'%');
		}

		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['id','username','name','phone_number','annual_membership','user_level','email','unique_id','ip_address','ZUO','BTC','sponser','created','enabled','referral_user_id', 'eth_address', 'btc_address'],
            'contain'=>['referusers','agctransactions','referral_user','cointransactions'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']
        ]);
		$this->set('users',$getUsers );

	}

    public function logs()
	{
        $this->set('title' , 'Epay');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $this->loadModel('EpayLogs');
        $this->loadModel('Epay');
        //$this->set('userId', $id);
        $searchData = array();
        $limit = 20;
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }

        $coinList = $this->Epay->find('list', ['keyField' => 'id', 'valueField' => 'short_name'])->toArray();
        $this->set('coinList',$coinList);

        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('EpayLogs.user_id' => $search['user_name']);
        }
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('EpayLogs.epay_id' => $search['coin_first_id']);
        }
        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(EpayLogs.created) >= ' => $this->request->query['start_date'],'DATE(EpayLogs.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(EpayLogs.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(EpayLogs.created)' => $search['end_date']);

        if($this->request->query('export')){

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Coin','Amount','Type','Target','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->EpayLogs->find('all',[
                'contain'=>['Users'=>['fields'=>['id', 'name','phone_number']],
                    'Epay'=>['fields'=>['short_name']]],
                'conditions' => $searchData,
                'order'=>['EpayLogs.id'=>'desc'],
                //'limit' => $limit,

            ]);
			$this->add_system_log(200, 0, 5, 'E-pay 로그 CSV 다운로드 (이름, 전화번호)');
            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Coin'] = $data['epay']['short_name'];
                $arr['Amount'] = round($data['amount'],0);
                $arr['Type'] = $data['type'];
                $arr['Target'] = $data['target'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'EpayLogs'.$filename
            ));
            return $this->response;die;
        }

		$getUsers = $this->Paginator->paginate($this->EpayLogs, [
			'contain'=>['Epay'=>['fields'=>['short_name']], 'Users'=>['fields'=>['name', 'phone_number']]],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']
        ]);
		$this->set('users',$getUsers);
		
	}
	
	public function logsub($id = null)
	{
	
        if ($this->request->is('ajax'))
		{
			//$limit =  $this->setting['pagination'];
			$limit = 20;
			parse_str($this->request->data['key'], $this->request->data);
			//$id = $this->request->data;
			$this->set('userId', $id);
			
			$this->loadModel('EpayLogs');	
			
			$searchData = array();
			if ( !empty($id) ) {
				$searchData['AND'][] =array('EpayLogs.user_id' => $id);
			}

			$getUsers = $this->Paginator->paginate($this->EpayLogs, [
				//'contain'=>['Epay'=>['fields'=>['short_name']]],
				'contain'=>['Epay'=>['fields'=>['short_name']], 'Users'=>['fields'=>['name', 'phone_number']]],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']
			]);
			$this->set('users',$getUsers);
		}
		
	}

}
