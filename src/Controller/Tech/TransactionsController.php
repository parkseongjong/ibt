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

ini_set('memory_limit', '-1');
use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Mailer\Email;

class TransactionsController extends AppController
{
    public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
	public function getINR()
    {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://blockchain.info/ticker');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$contents = curl_exec($ch);
		$arr = json_decode($contents);
		echo "1 BTC  =".$arr->INR->buy." INR";
		die;
	}


    public function depositlist()
    {
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $limit = 20;
		$searchData = [];
		$totalDepositAmount = 0;

		$search = $this->request->query;
        if (!empty($search['user_name'])){
			$searchData['AND'][] = array('user.id' => $search['user_name']);
			$userId = $search['user_name'];
            $totalVal = $this->Users->getUserTotalDeposit($userId);
            $totalOldVal = $this->Users->getUserTotalOldDeposit($userId);
            $totalDepositAmount = $totalOldVal + $totalVal;
		}

        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership','bank','account_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.type' => 'bank_initial_deposit']+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);

     //   $key = hash('sha256', 'secret key');
       // 20703252130105


        $this->set('listing',$collectdata);
		$this->set('totalDepositAmount',$totalDepositAmount);
    }

    public function depositlistpagination()
    {

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            $searchData['AND'][] = array('conditions'=> ['PrincipalWallet.type'=>'bank_initial_deposit']);
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
                'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership','bank','account_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['PrincipalWallet.type' => 'bank_initial_deposit'],
                'order'=>['PrincipalWallet.id'=>'desc'],
                'limit' => $limit,
            ]);




            $this->set('listing',$collectdata);

        }
    }

    public function depositlistajax(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.id'=>$id,'PrincipalWallet.type'=>'bank_initial_deposit'],
                'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }


    public function depositlistajaxname(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $totalVal = $this->Users->getUserTotalDeposit($userId);
            $totalOldVal = $this->Users->getUserTotalOldDeposit($userId);
            $totalDepositAmount = $totalOldVal + $totalVal;
            $getDataList =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.user_id'=>$userId,
                'PrincipalWallet.type'=>'bank_initial_deposit'],'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $membership = $getUser['user']['annual_membership'];
                $bank = $getUser['user']['bank'];
                $accountnum = $getUser['user']['account_number'];
                $coin = $getUser['cryptocoin']['short_name'];
                $amount = $getUser['amount'];
                $created = $getUser['created_at'];
                $status = $getUser['status'];
                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'membership'=>$membership,
                    'bank'=>$bank,
                    'accountnum'=>$accountnum,
                    'coin'=>$coin,
                    'amount'=>$amount,
                    'created'=>$created,
                    'status'=>$status
                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr,'totalDepositAmount'=>$totalDepositAmount]];

            echo json_encode($respArr); die;
            /*  if(!empty($mainRespArr)){
                  $respArr = ["success"=>"true","message"=>"user record list",'data'=>['userlist'=>$mainRespArr]];
                  echo json_encode($respArr); die;
              }
              else {
                  print_r('error');
                  $respArr = ["success"=>"false","message"=>"No Data Found"];
                  echo json_encode($respArr); die;
              } */
        }
    }

    public function status(){
        if ($this->request->is('ajax')) {
            $this->loadModel('Transactions');
            $transaction = $this->Transactions->get($this->request->data['id']); // Return article with id 12
            $transaction->status = $this->request->data['status'];
            $this->Transactions->save($transaction);
			$this->add_system_log(200, 0, 3, '트랜잭션 status 수정 (id :: '.$this->request->data['id'].')');
            echo 1;
        }
        die;
    }

    public function statuswallet(){
        if ($this->request->is('ajax')) {
			$this->loadModel('PrincipalWallet');
            //$principalwallet = $this->PrincipalWallet->get($this->request->data['id']); // Return article with id 12
            //$principalwallet->status = $this->request->data['status'];
            //$principalwallet->created_at = Time::now();
            //$this->PrincipalWallet->save($principalwallet);
			$query = $this->PrincipalWallet->query();
			$query->update()->set(['status'=>$this->request->data['status'],'updated_at'=>date('Y-m-d H:i:s')])->where(['id' =>$this->request->data['id']])->execute();
			$this->add_system_log(200, 0, 3, '지갑 status 수정 (id :: '.$this->request->data['id'].')');
            echo 1;
        }
        die;
    }


    public function withdrawallist()
    {
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $limit = 20;
		$totalWithdrawnAmount = 0;

        $searchData = [];
        $search = $this->request->query;

		if (!empty($search['user_name'])){
            $searchData['AND'][] = array('user.id' => $search['user_name']);
            $userId = $search['user_name'];
            $totalWithdrawnAmount = $this->Users->getUserTotalWithdrawnWithoutFees($userId);
        }
		if (!empty($search['user_id'])) $searchData['AND'][] = array('PrincipalWallet.id' => $search['user_id']);

		if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
		else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
		else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);

		if($this->request->query('export')){
			// Export
//                if($search['export']=='c') $filename = time().'export.csv';
//                else  $filename = 'export.xlsx';

			$filename = time().'.csv';
			$file = fopen(WWW_ROOT."uploads/".$filename,"w");
			$headers = array('#','User Id','User Name','Phone number','Annual Member','Bank Name','Account Number','Currency','Total Amount','Amount','Fees','Status','Date & Time');
			fputcsv($file,$headers);

			$users =  $this->PrincipalWallet->find('all',[
				'contain'=>['user'=>['fields'=>['id', 'name','phone_number','annual_membership', 'bank', 'account_number']],
					'cryptocoin'=>['fields'=>['short_name']]],
				'conditions'=>['PrincipalWallet.type' => 'bank_initial_withdraw']+$searchData,
				'order'=>['PrincipalWallet.id'=>'desc'],
				//'limit' => $limit,

			]);
			$this->add_system_log(200, 0, 5, '고객 KRW 출금 요청 목록 CSV 다운로드');

			$k = 1;
			foreach ($users as $k=>$data)
			{
				$arr = [];
				$arr['#'] = $data['id'];
				$arr['User Id'] = $data['user_id'];
				$arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
				$arr['Phone number'] = $data['user']['phone_number'];
				$arr['Annual Member'] = $data['user']['annual_membership'];
				$arr['Bank Name'] = __($data['user']['bank']);
				$arr['Account Number'] = $this->Decrypt($data['user']['account_number']);
				$arr['Currency'] = $data['cryptocoin']['short_name'];
				$arr['Total Amount'] = round($data['amount'],0);
				$arr['Amount'] = round($data['coin_amount'],0);
				$arr['Fees'] = $data['fees'];
				$arr['Status'] = $data['status'];
				$arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
				fputcsv($file,$arr);
				$k++;
			}
			fclose($file);
			$this->response->file("uploads/".$filename, array(
				'download' => true,
				'name' => 'WithdrawalAmountList'.$filename
			));
			return $this->response;die;
		}
        //}

        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
			'fields'=>['id','user_id','amount','coin_amount','fees','created_at','updated_at','status','coupon_user_id','cryptocoin_id','coupon_cryptocoin_id','wallet_address','type','tx_id','remark'],
            'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership', 'bank', 'account_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.type' => 'bank_initial_withdraw']+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);
		$this->set('totalWithdrawnAmount',$totalWithdrawnAmount);

    }

    public function withdrawallistpagination()
    {

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            $searchData['AND'][] = array('conditions'=> ['PrincipalWallet.type'=>'bank_initial_withdraw']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' =>
                    $this->request->data['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
                'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership','bank','account_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['PrincipalWallet.type'=>'bank_initial_withdraw']+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function withdrawallistajax(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.id'=>$id,'PrincipalWallet.type'=>'bank_initial_withdraw'],
                'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function withdrawallistajaxname(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.user_id'=>$userId,
                'PrincipalWallet.type'=>'bank_initial_withdraw'],'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $membership = $getUser['user']['annual_membership'];
                $bank = $getUser['user']['bank'];
                $accountnum = $getUser['user']['account_number'];
                $coin = $getUser['cryptocoin']['short_name'];
                $amount = $getUser['amount'];
                $coinAmount = $getUser['coin_amount'];
                $fees = $getUser['fees'];
                $created = $getUser['created_at'];
                $status = $getUser['status'];
                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'membership'=>$membership,
                    'bank'=>$bank,
                    'accountnum'=>$accountnum,
                    'coin'=>$coin,
                    'amount'=>isset($amount) ? $amount : 0,
                    'coinAmount'=> isset($coinAmount) ? $coinAmount : 0,
                    'fees' => $fees,
                    'created'=>$created,
                    'status'=>$status
                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;
            /*  if(!empty($mainRespArr)){
                  $respArr = ["success"=>"true","message"=>"user record list",'data'=>['userlist'=>$mainRespArr]];
                  echo json_encode($respArr); die;
              }
              else {
                  print_r('error');
                  $respArr = ["success"=>"false","message"=>"No Data Found"];
                  echo json_encode($respArr); die;
              } */
        }
    }

    //Old withdrawal list
    public function withdrawallistold()
    {
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $authUserId = $this->Auth->user('id');
        $limit = 20;
        $userFindList = $this->Transactions->find('list', ['keyField' => 'id',
            'valueField' => 'id',
            'conditions'=>['tx_type'=>"bank_initial_deposit"]
        ])->toArray();
        $this->set('userFindList',$userFindList);

        $searchData = [];
        if($this->request->query('page')) {

            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);
        $usersFindList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => function ($e) {
                return $e->username . ' - ' . $e->name;
            },
            'conditions'=>['user_type'=>"U"]
        ])->toArray();
        $this->set('usersFindList',$usersFindList);

        //if ($this->request->is(['post' ,'put']) ) {

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created_at)' => $search['end_date']);

        if($this->request->query('export')){
            // Export
//                if($search['export']=='c') $filename = time().'export.csv';
//                else  $filename = 'export.xlsx';

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User Id','User Name','Phone number','Annual Member','Bank Name','Account Number','Currency','Total Amount','Amount','Fees','Status','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->Transactions->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number','annual_membership', 'bank', 'account_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['Transactions.tx_type' => 'bank_initial_deposit'],
                'order'=>['Transactions.id'=>'desc'],
                //'limit' => $limit,

            ]);

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone number'] = $data['user']['phone_number'];
                $arr['Annual Member'] = $data['user']['annual_membership'];
                $arr['Bank Name'] = $data['user']['bank'];
                $arr['Account Number'] = $data['user']['account_number'];
                $arr['Currency'] = $data['cryptocoin']['short_name'];
                $arr['Amount'] = round($data['coin_amount'],0);
                $arr['Fees'] = $data['fees'];
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'WithdrawalAmountList'.$filename
            ));
            return $this->response;die;
        }
        //}

        $collectdata = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership', 'bank', 'account_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['Transactions.tx_type' => 'bank_initial_deposit'],
            'order'=>['Transactions.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);

    }

    public function withdrawallistpaginationold()
    {

        $this->loadModel('Transactions');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            $searchData['AND'][] = array('conditions'=> ['Transactions.tx_type'=>'bank_initial_deposit']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' =>
                    $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->Transactions, [
                'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership','bank','account_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['Transactions.tx_type'=>'bank_initial_deposit'],
                'order'=>['Transactions.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function withdrawallistajaxold(){
        $this->loadModel('Users');
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->Transactions->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['Transactions.id'=>$id,'Transactions.tx_type'=>'bank_initial_deposit'],
                'order'=>['Transactions.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    //old withdrawal list end

    public function coinswithdrawallist()
    {
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
		$searchData = [];
		
        $search = $this->request->query;
        if (!empty($search['user_name'])){ //'PrincipalWallet.user_id'=>user_name,
			$searchData['AND'][] = array('PrincipalWallet.user_id' => $search['user_name']);
		}
        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.type' => 'withdrawal']+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
        ]);

        $this->set('listing',$collectdata);
    }

    public function coinswithdrawallistpagination()
    {

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            $searchData['AND'][] = array('conditions'=> ['PrincipalWallet.type'=>'withdrawal']);
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
                'contain'=>['user'=>['fields'=>['name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['PrincipalWallet.type'=>'withdrawal'],
                'order'=>['PrincipalWallet.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function coinswithdrawallistajax(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.id'=>$id,'PrincipalWallet.type'=>'withdrawal'],
                'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function coinswithdrawallistajaxname(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.user_id'=>$userId,
                'PrincipalWallet.type'=>'withdrawal'],'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $coin = $getUser['cryptocoin']['short_name'];
                $coinAmount = $getUser['coin_amount'];
                $amount = $getUser['amount'];
                $walletAddress = $getUser['wallet_address'];
                $fees = $getUser['fees'];
                $created = $getUser['created_at'];
                $status = $getUser['status'];
                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'coinAmount'=>isset($coinAmount) ? $coinAmount : 0,
                    'amount'=>isset($amount) ? $amount : 0,
                    'coin'=>$coin,
                    'walletAddress'=>$walletAddress,
                    'fees'=>$fees,
                    'created'=>$created,
                    'status'=>$status
                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;
            /*  if(!empty($mainRespArr)){
                  $respArr = ["success"=>"true","message"=>"user record list",'data'=>['userlist'=>$mainRespArr]];
                  echo json_encode($respArr); die;
              }
              else {
                  print_r('error');
                  $respArr = ["success"=>"false","message"=>"No Data Found"];
                  echo json_encode($respArr); die;
              } */
        }
    }

    public function ico(){
		
		$this->set('title' , 'HC : Lending');
		$this->loadModel('Users');
		$this->loadModel('Transactions');
		$this->loadModel('Token');
		$this->loadModel('Agctransactions');
		$this->loadModel('Cointransactions');
		$this->loadModel('Referal');
		
		$authUser = $this->Auth->user();
		$this->set('authUser',$authUser);
		
		$type = "purchase";
		
		$coin_arr=['referral','purchase','bonus'];
		if(!in_array($type,$coin_arr)){
			$type = "purchase";
		}	
		$cudate = date("Y-m-d H:i:s");	
		$this->set('display_type','AGC');
		// for purchase
		$currentUser = $this->Auth->user('id');
		$referralUserId = $this->Auth->user('referral_user_id');
		
		
		$currentUserWallet = $this->Auth->user('unique_id');
		$this->set('currentUserWallet',$currentUserWallet);
		
		$getUserTotalCoin = $this->Cointransactions->find(); 
		$getUserTotalCoinCnt = $getUserTotalCoin
									->select(['sum' => $getUserTotalCoin->func()->sum('coin')])
									->where(array('type'=>'purchase'))
									->toArray();
		
		$getUserTotalCoinCount = $getUserTotalCoinCnt[0]['sum'];
		$this->set('getUserTotalCoinCount',$getUserTotalCoinCount);
		
		
		$limit = $this->setting['pagination'];
		
		$searchData = array();
		$searchData['AND'][] = array('type'=>'purchase');
		
		
		
		$this->set('listing',$this->Paginator->paginate($this->Cointransactions, [
						 'conditions'=>$searchData,
						'order'=>['Cointransactions.id'=>'desc'],
						'contain'=>['user'],
						'limit' => $limit,
					]));
		$this->set('type',$type);
		
		
		$getBitJsonData = file_get_contents("https://blockchain.info/ticker");
		$getDecode = json_decode($getBitJsonData,true); 
		$buyUsd = $getDecode['USD']['buy'];
		$this->set('buyUsd',$buyUsd);
		
		$query = $this->Agctransactions->find(); 
			
		
		$totalAMXCoin = $this->Token->find('all')->hydrate(false)->first();
		$this->set('totalAMXCoin',$totalAMXCoin);
		
		$coinPrice = $totalAMXCoin['price'];
		$this->set('coinPrice',$coinPrice);
		
	}
	
	public function icoSearch()
	{
		
		$this->loadModel('Cointransactions');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			$coin_arr=['referral','purchase','bonus'];
			
			$search = $this->request->data;
			$type = "purchase";
			if(!in_array($type,$coin_arr)){
				$type = "purchase";
			}
			
			
			
			
			$searchData = array();
			/* if($type=="referral" || $type=="bonus"){
				$searchData['AND'][] = array('status'=>'completed');	
			} */
			$searchData['AND'][] = array('type' => 'purchase');
			
			$limit = $this->setting['pagination'];
			
			/*  if($search['pagination'] != ''){
				 $limit =  $search['pagination'];
			 } */
			
			/* if($search['start_date'] != '' && $search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
			}
			else if($search['start_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
			}
			else if($search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
			} */
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$getData = $this->Paginator->paginate($this->Cointransactions, [
						    'conditions'=>$searchData,
							'contain'=>['user'],
						    'order'=>['Cointransactions.id'=>'desc'],
							'limit' => $limit
						]);
			//print_r($getData); die;			
			$this->set('listing',$getData);
		
			
			//$this->set('type',$search['type']);
		}
	
	}
	
	public function send()
    {
		$this->set('title','Send');
		$this->loadModel('Agctransactions');
		$this->loadModel('Users');
		$this->loadModel('Referal');
		$this->loadModel('Token');
		$this->loadModel('Cointransactions');
		$transaction = $this->Transactions->newEntity();
		$limit=500;
		$cudate = date("Y-m-d H:i:s");
		$searchData = array();
		//$searchData['AND'][] = array('coin_type'=>'B','trans_type'=>'S','user_id'=>$this->Auth->user('id'));
		if ($this->request->is(['post' ,'put'])) 
		{
			
				$currentCoinPrice = $this->Token->find('all')->hydrate(false)->first();
				$dollerPerCoin = $currentCoinPrice['price'];
				
				$getBitJsonData = file_get_contents("https://blockchain.info/ticker");
				$getDecode = json_decode($getBitJsonData,true); 
				$dollerPerBtc = $getDecode['USD']['buy'];
				
				$coinPurchaseVal = $this->request->data['amount'];
				$wallet_address = $this->request->data['wallet_address'];
				$payment_date = $this->request->data['payment_date'];
				
				if(isset($this->request->data['amount']))
				{
				
				$user = $this->Users->find('all',['fields'=>['id','referral_user_id'],'conditions'=>['id !='=>$this->Auth->user('id'),'unique_id'=>$wallet_address]])->hydrate(false)->first();

				if(!empty($user))
				{
					$referralUserId = $user['referral_user_id'];
				
					$newInsertArr = [];
					$newInsertArr['user_id'] = $user['id'];
					//$newInsertArr['btc'] = $btcPurchaseVal;
					$newInsertArr['coin'] = $coinPurchaseVal;
					$newInsertArr['dollar'] = $dollerPerCoin*$coinPurchaseVal;
					$newInsertArr['doller_per_hc'] = $dollerPerCoin;
					$newInsertArr['type'] = 'send_by_admin';
					$newInsertArr['admin_send_date'] = $payment_date;
					$newInsertArr['updated_at'] = $cudate;
					
					//print_r($newInsertArr); die;
					
					// insert data
					$purchaseCoinTransactions=$this->Cointransactions->newEntity();
					$purchaseCoinTransactions=$this->Cointransactions->patchEntity($purchaseCoinTransactions,$newInsertArr);
					$saveData = $this->Cointransactions->save($purchaseCoinTransactions);
					$cointransactionsId = $saveData->id;
					
					if($saveData){
							
							// calculation for referral user
							if(!empty($referralUserId)){
								$findReferralUser = $this->Users->find("all",['conditions'=>["id"=>$referralUserId]])->hydrate(false)->first(); 
								$getReferalSetting = $this->Referal->find("all")->hydrate(false)->first();  
								$referralPercent = $getReferalSetting['referal_percent']; 
								$dollerReferral = $coinPurchaseVal*($referralPercent/100);
								$btcReferral = $dollerReferral/$dollerPerBtc;
								$coinReferral = $dollerReferral/$dollerPerCoin;
								
								$newReferalArr = [];
								$newReferalArr['user_id'] = $referralUserId;
								$newReferalArr['referral_user_id'] = $user['id'];
								$newReferalArr['btc'] = $btcReferral;
								$newReferalArr['coin'] = $coinReferral;
								$newReferalArr['dollar'] = $dollerReferral;
								$newReferalArr['doller_per_hc'] = $dollerPerCoin;
								$newReferalArr['type'] = 'referral';
								$newReferalArr['updated_at'] = $cudate;
								
								$referalTransactions=$this->Cointransactions->newEntity();
								$referalTransactions=$this->Cointransactions->patchEntity($referalTransactions,$newReferalArr);
								$saveReferaData = $this->Cointransactions->save($referalTransactions);
								//$cointransactionsId = $saveData->id;
									
							}
							$this->Flash->success(__('HC coin transfered successfully.'));
							return $this->redirect('admin/transactions/send');
					}
					else {
						$this->Flash->error(__('Unable to send HC. Try Again.'));
						return $this->redirect('admin/transactions/send');
					}
				}
				else {
					$this->Flash->error(__('Invalid wallet address'));
					return $this->redirect('admin/transactions/send');
				}
			
			}
			/* else{
				//Filter
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				if($search['pagination'] != '') $limit =  $search['pagination'];
				//pr($search);die;
				if($search['name'] != '') $searchData['AND'][] =array('from_user.name LIKE' => '%'.$search['name'].'%');
				if($search['unique_id'] != '') $searchData['AND'][] =array('from_user.unique_id' => $search['unique_id']);
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Cointransactions.created) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Cointransactions.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Cointransactions.created)' => $search['end_date']);
			} */
		}
		
		$transaction = $this->Cointransactions->find();
		$searchData['AND'][]= array('type'=>'send_by_admin');
		$this->set('listing', $this->Paginator->paginate($this->Cointransactions, [
			'contain'=>['user'],
			'conditions' => $searchData,
			'limit' => $limit,
			'order'=>['id'=>'desc']

		]));

		$this->set('transaction',$transaction);
        

    }
	
	
	public function btcSend()
    {   
	
		$this->set('title','Send');
		$this->loadModel('Agctransactions');
		$this->loadModel('Users');
		$this->loadModel('Referal');
		$this->loadModel('Token');
		$this->loadModel('Cointransactions');
		$this->loadModel('WithdrawalLog');
		$transaction = $this->Transactions->newEntity();
		$limit=500;
		$cudate = date("Y-m-d H:i:s");
		$searchData = array();
		if ($this->request->is(['post' ,'put'])) 
		{
			
			$requestType = $this->request->data['request_type']; 
			$securePin = $this->request->data['secure_pin']; 
	
			// for transaction request start
			if($requestType == "admin_trans") {
				
				if(!isset($this->request->data['agc_ids'])){
					$this->Flash->error("Select Atleast One User");
					return $this->redirect(['controller'=>'transactions','action'=>'btcSend']);
				}
				$getAgcIds = $this->request->data['agc_ids'];
				
				
				
				$checkAllAgcId = $this->Agctransactions->find("all",['conditions'=>['Agctransactions.id in'=>$getAgcIds,																	 'Agctransactions.admin_withdrawl_transfer'=>'yes',																	  'Agctransactions.coin_type'=>'withdrawal']])
																->hydrate(false)->all()->toArray();
				if(!empty($checkAllAgcId)){
					$this->Flash->error("All Transaction should be pending. Try Again");
					return $this->redirect(['controller'=>'transactions','action'=>'btcSend']);
				}					
				
				
				$wallet_address = [];
				$btcAmountToSend = [];
				$findAllAgcData = $this->Agctransactions->find("all",['conditions'=>['Agctransactions.id IN'=>$getAgcIds,																	 'Agctransactions.admin_withdrawl_transfer'=>'no',																	  'Agctransactions.coin_type'=>'withdrawal']])
																->hydrate(false)
																->all()
																->toArray();
				/* $wallet_address []= '2N9VCVvUziZPs3cfrW2NXWB6aovwamceNiL';
				$btcAmountToSend [] = 0.004; */
				
				foreach($findAllAgcData as $singelRecord){
					//var_dump($singelRecord['btc_coins']); die;
					$wallet_address[] = $singelRecord['wallet_address'];
					$btcAmountToSend[] = number_format(abs($singelRecord['btc_coins']),8); 
				}
				
				
				$callWithDrewlApi = $this->Users->withdrawBtcAmount($wallet_address,$btcAmountToSend,$securePin);
				
				
				file_put_contents("withdrawal_log.txt","===============>".date('Y-m-d H:i:s').json_encode($callWithDrewlApi).PHP_EOL,FILE_APPEND); // add response to log file
				
				
				
				if(!empty($callWithDrewlApi)) {
					// save log of withdrawal start
					
					if($callWithDrewlApi['status']=='fail'){
						$withdrawalArr['status'] = $callWithDrewlApi['status'];
						$withdrawalArr['error_message'] = $callWithDrewlApi['data']['error_message'];
					}
					else {
						$withdrawalArr['status'] = $callWithDrewlApi['status'];
						$withdrawalArr['network'] = $callWithDrewlApi['data']['network'];
						$withdrawalArr['txid'] = $callWithDrewlApi['data']['txid'];
						$withdrawalArr['amount_withdrawn'] = $callWithDrewlApi['data']['amount_withdrawn'];
						$withdrawalArr['amount_sent'] = $callWithDrewlApi['data']['amount_sent'];
						$withdrawalArr['network_fee'] = $callWithDrewlApi['data']['network_fee'];
						$withdrawalArr['blockio_fee'] = $callWithDrewlApi['data']['blockio_fee'];
					}
					
					$addData=$this->WithdrawalLog->newEntity();
					$addData=$this->WithdrawalLog->patchEntity($addData,$withdrawalArr);
					$addData = $this->WithdrawalLog->save($addData);
					// save log of withdrawal end
				}
				
				
				if(!empty($callWithDrewlApi) && $callWithDrewlApi['status']!='fail'){
					$transId = $callWithDrewlApi['data']['txid'];
					foreach($getAgcIds as $btc_transaction_id){
					
						//echo $btc_transaction_id; die;
						$btcData = $this->Agctransactions->get($btc_transaction_id);
						$btcData->admin_withdrawl_transfer = 'yes';
						$btcData->trans_id = $transId;
						$btcData->payment_date = $cudate;
						$btcSaveData=$this->Agctransactions->save($btcData);
							$userId = $btcSaveData['user_id'];
							$userData = $this->Users->find('all',array('conditions'=>array('id'=>$userId)))->first();
							
							$emailData = ['btc_amount'=>abs($btcSaveData['btc_coins']),
										  'name'=>$userData['name'],
										  'payment_date'=>$btcSaveData['payment_date'],
										  'wallet_address'=>$btcSaveData['wallet_address'],
										  'trans_id'=>$transId];
							
								$email = new Email('default');
								$email->viewVars(['data'=>$emailData]);
								$email->from([$this->setting['email_from']] )
									->to($userData['email'])
									->subject('Btc withdrawal From HedgeConnect')
									->emailFormat('html')
									->template('withdrawal')
									->send();
						
						
					}
					
					$this->Flash->success('Transaction Completed.');
					$this->redirect(['controller'=>'transactions','action'=>'btcSend']);
				}
				else {
					$this->Flash->error('Unable to make transaction. Try Again !! Error : '.$callWithDrewlApi['data']['error_message']);
					$this->redirect(['controller'=>'transactions','action'=>'btcSend']);
				}
			}
		}	
		
		$limit =  $this->setting['pagination'];
		$searchData = array();
		//$searchData['AND'][] = array('user_id' => $currentUser);
		$searchData['AND'][] = array('coin_type' => 'withdrawal');
		$searchData['AND'][] = array('trans_type' => 'debit');
		
		$limit = $this->setting['pagination'];
		
		
		$query = $this->Paginator->paginate($this->Agctransactions, [
						 'conditions'=>$searchData,
						 'contain'=>['user'=>['fields'=>['id','username','unique_id']]],
						'order'=>['Agctransactions.id'=>'desc'],
						'limit' => $limit,
					]);
		
		$this->set('listing',$query);
        

    }
	
	
	public function btcSendSearch()
	{
		
		$this->loadModel('Agctransactions');
		if ($this->request->is('ajax')) 
		{ 
			parse_str($this->request->data['key'], $this->request->data);
			
			$search = $this->request->data;
			
			
			
			$searchData = array();
			
			//$searchData['AND'][] = array('user_id' => $currentUser);
			$searchData['AND'][] = array('coin_type' => 'withdrawal');
			$searchData['AND'][] = array('trans_type' => 'debit');
			if (isset($search['search_keyword']) && $search['search_keyword'] != '') {
                $searchData['AND'][] = array('username' => $search['search_keyword']);
            }
			$limit = $this->setting['pagination'];
			
			 if(isset($search['pagination']) && $search['pagination'] != ''){
				 $limit =  $search['pagination'];
			 }
			
			/* if($search['start_date'] != '' && $search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at) >= ' => $this->request->data['start_date'],'DATE(Cointransactions.created_at) <= ' => $this->request->data['end_date']);
			}
			else if($search['start_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['start_date']);
			}
			else if($search['end_date'] != ''){
				$searchData['AND'][] = array('DATE(Cointransactions.created_at)' => $search['end_date']);
			}
			 */
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$getData = $this->Paginator->paginate($this->Agctransactions, [
						    'conditions'=>$searchData,
						    'order'=>['Agctransactions.id'=>'desc'],
							'contain'=>['user'=>['fields'=>['id','username','unique_id']]],
							'limit' => $limit
						]);
			//print_r($getData); die;			
			$this->set('listing',$getData);
		
		}
	
	}
	
	
	
	public function transaction()
    {
		die;
	// 	$this->set('title','Transaction');
	// 	$this->loadModel('Transactions');
	// 		$this->set('display_type','BTC');
			
	// 		$limit = $this->setting['pagination'];
	// 		$type = "BTC";
	// 		$searchData = array();
			
	// 		$searchData['AND'][] = array("Transactions.tx_type"=>'purchase'); 
			
	// 		if ($this->request->is(['post' ,'put']) ) 
	// 		{
	// 			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
	// 			$search = $this->request->data;
				
	// 			if(!empty($search['pagination'])) $limit =  $search['pagination'];
				
	// 			// search by username
	// 			if(!empty($search['username'])){
	// 				$searchData['AND'][] = array("user.name like"=>"%".$search['username']."%");
	// 			}
				
	// 			// search by date range
	// 			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
	// 			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
	// 			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
				
	// 			// saarch by coin type
	// 			if(!empty($search['coin_type'])){
	// 				$searchData['AND'][] = array("cryptocoin.id"=>$search['coin_type']);
	// 			}
				
	// 			// saarch by coin type
	// 			if(!empty($search['status'])){
	// 				$searchData['AND'][] = array("Transactions.status"=>$search['status']);
	// 			}
				
				
	// 		}
	// 		$collectdata = $this->Paginator->paginate($this->Transactions, [
	// 					    'contain'=>['user'=>['fields'=>['username','unique_id']],
	// 									'cryptocoin'=>['fields'=>['short_name']]],
	// 						'conditions'=>$searchData,
	// 					    'order'=>['Transactions.id'=>'desc'],
	// 						'limit' => $limit,
	// 					]);
			
	// 		$this->set('listing',$collectdata);
	// 		$this->set('type',$type);
		
	// }
	}
	public function transactionSearch()
	{
	
		$this->loadModel('Transactions');
		if ($this->request->is('ajax')) 
		{ 
			$limit = $this->setting['pagination'];
			$searchData = array();
			$searchData['AND'][] = array("Transactions.tx_type"=>'purchase'); 
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$collectdata = $this->Paginator->paginate($this->Transactions, [
						    'contain'=>['user'=>['fields'=>['username','unique_id']],
										'cryptocoin'=>['fields'=>['short_name']]],
							'conditions'=>$searchData,
						    'order'=>['Transactions.id'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		}
	
	}
	
	
	
	public function translist($userId=null)
    {
		if($userId==null){
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$this->set('title','Transaction');
		$this->loadModel('Transactions');
			$this->set('display_type','BTC');
			
			$limit = $this->setting['pagination'];
			$type = "BTC";
			$searchData = array();
			
			//$searchData['AND'][] = array("Transactions.tx_type"=>'purchase'); 
			$searchData['AND'][] = array("Transactions.cryptocoin_id"=>2); 
			$searchData['AND'][] = array("Transactions.user_id"=>$userId); 
			
			if ($this->request->is(['post' ,'put']) ) 
			{
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				
				if(!empty($search['pagination'])) $limit =  $search['pagination'];
				
				// search by username
				/*if(!empty($search['username'])){
					$searchData['AND'][] = array("user.name like"=>"%".$search['username']."%");
				}
				
				// search by date range
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
				
				// saarch by coin type
				if(!empty($search['coin_type'])){
					$searchData['AND'][] = array("cryptocoin.id"=>$search['coin_type']);
				}
				
				// saarch by coin type
				if(!empty($search['status'])){
					$searchData['AND'][] = array("Transactions.status"=>$search['status']);
				}*/
				
				
			}
			
		
			$collectdata = $this->Paginator->paginate($this->Transactions, [
						    'contain'=>['user'=>['fields'=>['username','unique_id']],
										'cryptocoin'=>['fields'=>['short_name']],
										'sell_exchange',
										'buy_exchange'
										],
							'conditions'=>$searchData,
						    'order'=>['Transactions.id'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
			$this->set('type',$type);
			$this->set('userId',$userId);
			
			$searchData['AND'][] = array("Transactions.status"=>'completed'); 
			$ethSum = $this->Transactions->find('all',['fields'=>['totalsum'=>'sum(coin_amount)'],
													  'conditions'=>$searchData
													 ])->hydrate(false)->toArray();
													 
			$ethtotal = $ethSum[0]['totalsum'];										 
			$this->set('ethtotal',$ethtotal);
		
	}
	
	public function translistSearch($userId=null)
	{
		
		
		$this->loadModel('Transactions');
		if ($this->request->is('ajax')) 
		{ 
			$limit = $this->setting['pagination'];
			$searchData = array();
			//$searchData['AND'][] = array("Transactions.tx_type"=>'purchase'); 
			$searchData['AND'][] = array("Transactions.cryptocoin_id"=>2);
			$searchData['AND'][] = array("Transactions.user_id"=>$userId);			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$collectdata = $this->Paginator->paginate($this->Transactions, [
						    'contain'=>['user'=>['fields'=>['username','unique_id']],
										'cryptocoin'=>['fields'=>['short_name']],
										'sell_exchange',
										'buy_exchange'
										],
							'conditions'=>$searchData,
						    'order'=>['Transactions.id'=>'desc'],
							'limit' => $limit,
						]);
			
			$this->set('listing',$collectdata);
			
			$this->set('userId',$userId);
		}
	
	}
	
	
	
	
	public function alltranslist($userId=null,$coinType=2)
    {
		if($userId==null){
			return $this->redirect(['controller'=>'pages','action'=>'dashboard']);
		}
		
		$this->set('title','Transaction');
		$this->loadModel('Transactions');
			$this->set('display_type','BTC');
			
			$coinArr = [];
			$coinArr[2] = 'ETH';
			$coinArr[3] = 'RAM';
			$coinArr[4] = 'ADMC';
			
			$this->set('showCoinType',$coinArr[$coinType]);
			
			$limit = $this->setting['pagination'];
			$type = "BTC";
			$searchData = array();
			
			//$searchData['AND'][] = array("Transactions.tx_type"=>'purchase'); 
			$searchData['AND'][] = array("Transactions.cryptocoin_id"=>$coinType); 
			$searchData['AND'][] = array("Transactions.user_id"=>$userId); 
			
			if ($this->request->is(['post' ,'put']) ) 
			{
				if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
				$search = $this->request->data;
				
				if(!empty($search['pagination'])) $limit =  $search['pagination'];
				// saarch by coin type
				/* if(!empty($search['coin_type'])){
					$searchData['AND'][] = array("Transactions.cryptocoin_id"=>$search['coin_type']);
				} */
				// search by username
				/*if(!empty($search['username'])){
					$searchData['AND'][] = array("user.name like"=>"%".$search['username']."%");
				}
				
				// search by date range
				if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
				else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
				else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
				
				// saarch by coin type
				if(!empty($search['coin_type'])){
					$searchData['AND'][] = array("cryptocoin.id"=>$search['coin_type']);
				}
				
				// saarch by coin type
				if(!empty($search['status'])){
					$searchData['AND'][] = array("Transactions.status"=>$search['status']);
				}*/
				
				
			}
			
		
			$collectdata = $this->Transactions->find('all',[
							'fields'=>['coin_amount','cryptocoin_id','tx_type','remark','created','status','withdrawal_send'],
						    'contain'=>['user'=>['fields'=>['username','unique_id']],
										'cryptocoin'=>['fields'=>['short_name']]],
							'conditions'=>$searchData,
						    'order'=>['Transactions.id'=>'asc'],
							//'limit' => $limit,
						])->hydrate(false)->toArray();
			
			//print_r($collectdata); die;
			
			$this->set('listing',$collectdata);
			$this->set('type',$type);
			$this->set('userId',$userId);
			
			$searchData['AND'][] = array("Transactions.status"=>'completed'); 
			$ethSum = $this->Transactions->find('all',['fields'=>['totalsum'=>'sum(coin_amount)'],
													  'conditions'=>$searchData
													 ])->hydrate(false)->toArray();
													 
			$ethtotal = $ethSum[0]['totalsum'];										 
			$this->set('ethtotal',$ethtotal);
		
	}

	public function depositapplicationlist(){
        $this->loadModel('Users');
		$this->loadModel('DepositApplicationList');
		$limit =  $this->setting['pagination'];
		$search = $this->request->data;
		$search_value = '';

		if ($this->request->is(['post' ,'put']) ) {
            if (!empty($search['pagination'])) $limit = $search['pagination'];
        }
		if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        } else{
			$this->set('serial_num',1);
		} 
		/*
		$collectdata = $this->Paginator->paginate($this->DepositApplicationList->find('all', [
            'contain'=>['user'=>['fields'=>['name','phone_number']]],
            'order'=>['DepositApplicationList.id'=>'desc'],
			'limit'=>$limit
        ]));
		*/

		$query = $this->DepositApplicationList->find()->select(['id','user_id','unit','quantity','service_period_month','previous_balance','u.name','u.phone_number','status','created']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);
		if($this->request->data('search_value')){
			$search_value = $search['search_value'];
			if(is_numeric($search_value)){
				$query = $query -> where(['u.phone_number' => $search_value]);
			} else {
				$query = $query -> where(['u.name' => $search_value]);
			}
		}
		$this->set('search_value',$search_value);

		/* csv export */
		if($this->request->data('export') == 'c'){
			// Export
			if($search['export']=='c') $filename = time().'export.csv';
			else  $filename = 'export.xlsx';
			$file = fopen(WWW_ROOT."uploads/".$filename,"w");
			$headers = array('#','User Id','User Name','Phone number','Quantity','Unit','Previous Balance','Service Period Month','Status','Created');
			fputcsv($file,$headers);
			$datas =  $query->all();
			

			$k = 1;
			foreach ($datas as $k=>$data)
			{
				$arr = [];
				$arr['#'] = $k;
				$arr['User Id'] = $data['user_id'];
				$arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['u']['name']), "EUC-KR", "UTF-8" );
				$arr['Phone number'] = $data['u']['phone_number'];
				$arr['Quantity'] = $data['quantity'];
				$arr['Unit'] = $data['unit'];
				$arr['Previous Balance'] = $data['previous_balance'];
				$arr['Service Period Month'] = $data['service_period_month'];
				$arr['Status'] = $data['status'];
				$arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created']));
				fputcsv($file,$arr);
				$k++;
			}
			fclose($file);
			$this->response->file("uploads/".$filename, array(
				'download' => true,
				'name' => 'DepositApplicationList'.time().$filename
			));
			return $this->response;die;
		}

		$query = $query->order(['DepositApplicationList.id'=>'desc'])->limit($limit);
		$collectdata =  $this->Paginator->paginate($query);

        $this->set('listing',$collectdata);


	}
	public function changedepositapplicationstatus(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationList');
			if (!empty($this->request->data['id'])) {
				$id = $this->request->data['id'];
				$query = $this->DepositApplicationList->query();
				$query->update()->set(['status' => 'A'])->where(['id' => $id])->execute();
				echo "success";
				die;
			}
		} 
		echo 'fail';
		die;
	}

    //coupons start
    public function couponslist()
    {
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $limit = 20;

        $searchData = [];
        if($this->request->query('page')) {

            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(Transactions.created) >= ' => $this->request->query['start_date'],'DATE(Transactions.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);

        if($this->request->query('export')){
            // Export
//                if($search['export']=='c') $filename = time().'export.csv';
//                else  $filename = 'export.xlsx';

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User Id','User Name','Phone number','Annual Member','Bank Name','Account Number','Coupon Currency','Coupon Amount','Type','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->Transactions->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number','annual_membership', 'bank', 'account_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['Transactions.tx_type'=>'bought_coupon']+$searchData,
                'order'=>['Transactions.id'=>'desc'],
                //'limit' => $limit,

            ]);
			$this->add_system_log(200, 0, 5, '고객 쿠폰 목록 CSV 다운로드');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone number'] = $data['user']['phone_number'];
                $arr['Annual Member'] = $data['user']['annual_membership'];
                $arr['Bank Name'] = $data['user']['bank'];
                $arr['Account Number'] = $this->Decrypt($data['user']['account_number']);
                $arr['Coupon Currency'] = $data['cryptocoin']['short_name'];
                $arr['Coupon Amount'] = round($data['coin_amount'],0);
                $arr['Type'] = $data['type'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'CouponsAmountList'.$filename
            ));
            return $this->response;die;
        }
        //}

        $collectdata = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership', 'bank', 'account_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['Transactions.tx_type'=>'bought_coupon']+$searchData,
            'order'=>['Transactions.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);

    }

    public function couponslistpagination()
    {

        $this->loadModel('Transactions');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            $searchData['AND'][] = array('conditions'=> ['Transactions.tx_type'=>'bought_coupon']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' =>
                    $this->request->data['start_date'],'DATE(Transactions.created) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->Transactions, [
                'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership','bank','account_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['Transactions.tx_type'=>'bought_coupon']+$searchData,
                'order'=>['Transactions.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function couponslistajax(){
        $this->loadModel('Users');
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->Transactions->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['Transactions.tx_type'=>'bought_coupon', 'Transactions.user_id'=>$id],
                'order'=>['Transactions.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function couponslistajaxname(){
        $this->loadModel('Users');
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->Transactions->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['Transactions.tx_type'=>'bought_coupon',
                'Transactions.user_id'=>$userId],'order'=>['Transactions.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $membership = $getUser['user']['annual_membership'];
                $bank = $getUser['user']['bank'];
                $accountnum = $getUser['user']['account_number'];
                $coin = $getUser['cryptocoin']['short_name'];
                $amount = $getUser['coin_amount'];
                $type = $getUser['tx_type'];
                $created = $getUser['created'];

                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'membership'=>$membership,
                    'bank'=>$bank,
                    'accountnum'=>$accountnum,
                    'coin'=>$coin,
                    'amount'=>$amount,
                    'type'=>$type,
                    'created'=>$created,

                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;

        }
    }
    //coupons end


    //Admin coupons start
    public function admincouponslist()
    {
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $authUserId = $this->Auth->user('id');
        $limit = 20;

        $searchData = [];
        if($this->request->query('page')) {

            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) $searchData['AND'][] = array('coupon_user_id' => $search['user_name']);

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);

        if($this->request->query('export')){
            // Export
//                if($search['export']=='c') $filename = time().'export.csv';
//                else  $filename = 'export.xlsx';

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','Admin ID','Admin Name','Admin Phone Number','Admin Wallet Address','User ID','User Name','Phone Number','User Wallet Address','Annual Member','Bank Name','Account Number','Coupon Currency','Coupon Amount','Currency','KRW Amount','Type','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->PrincipalWallet->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'eth_address', 'name','phone_number','annual_membership', 'bank', 'account_number']],
                    'cryptocoin'=>['fields'=>['short_name']],'cryptocoinsa'=>['fields'=>['short_name']],
                    'usersa'=>['fields'=>['id','eth_address','name','phone_number','annual_membership', 'bank', 'account_number']]],
                'conditions'=>['PrincipalWallet.type'=>'deducted_coupon_krw']+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                //'limit' => $limit,

            ]);
			$this->add_system_log(200, 0, 5, '관리자 쿠폰 목록 CSV 다운로드');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['Admin ID'] = $data['user_id'];
                $arr['Admin Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Admin Phone Number'] = $data['user']['phone_number'];
                $arr['Admin Wallet Address'] = $data['user']['eth_address'];
                $arr['User Id'] = $data['coupon_user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['usersa']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone number'] = $data['usersa']['phone_number'];
                $arr['User Wallet Address'] = $data['usersa']['eth_address'];
                $arr['Annual Member'] = $data['usersa']['annual_membership'];
                $arr['Bank Name'] = $data['usersa']['bank'];
                $arr['Account Number'] = $this->Decrypt($data['usersa']['account_number']);
                $arr['Coupon Currency'] = $data['cryptocoinsa']['short_name'];
                $arr['Coupon Amount'] = round($data['coin_amount'],0);
                $arr['KRW'] = $data['cryptocoin']['short_name'];
                $arr['KRW Amount'] = round($data['amount'],0);
                $arr['Type'] = $data['type'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'AdminCouponsAmountList'.$filename
            ));
            return $this->response;die;
        }
        //}

        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['eth_address','name','phone_number','annual_membership', 'bank', 'account_number']],
                'cryptocoin'=>['fields'=>['short_name']],'cryptocoinsa'=>['fields'=>['short_name']],
                'usersa'=>['fields'=>['eth_address','name','phone_number','annual_membership', 'bank', 'account_number']]],
            'conditions'=>['PrincipalWallet.type'=>'deducted_coupon_krw']+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);

    }

    public function admincouponslistpagination()
    {

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            $searchData['AND'][] = array('conditions'=> ['PrincipalWallet.type'=>'deducted_coupon_krw']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' =>
                    $this->request->data['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
                'contain'=>['user'=>['fields'=>['eth_address','name','phone_number','annual_membership','bank','account_number']],
                    'cryptocoin'=>['fields'=>['short_name']],'cryptocoinsa'=>['fields'=>['short_name']],
                    'usersa'=>['fields'=>['eth_address','name','phone_number','annual_membership', 'bank', 'account_number']]],
                'conditions'=>['PrincipalWallet.type'=>'deducted_coupon_krw']+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function admincouponslistajax(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin','usersa','cryptocoinsa'],'conditions'=>['PrincipalWallet.type'=>'deducted_coupon_krw', 'PrincipalWallet.coupon_user_id'=>$id],
                'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }

    public function admincouponslistajaxname(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin','usersa','cryptocoinsa'],'conditions'=>['PrincipalWallet.type'=>'deducted_coupon_krw',
                'PrincipalWallet.coupon_user_id'=>$userId],'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $adminId = $getUser['user_id'];
                $adminName = $getUser['user']['name'];
                $adminPhone = $getUser['user']['phone_number'];
                $adminWalletAddress = $getUser['user']['eth_address'];
                $userId = $getUser['coupon_user_id'];
                $userName = $getUser['usersa']['name'];
                $phone = $getUser['usersa']['phone_number'];
                $userWalletAddress = $getUser['usersa']['eth_address'];
                $membership = $getUser['usersa']['annual_membership'];
                $bank = $getUser['usersa']['bank'];
                $accountnum = $getUser['usersa']['account_number'];
                $couponCoin = $getUser['cryptocoinsa']['short_name'];
                $coinAmount = $getUser['coin_amount'];
                $coin = $getUser['cryptocoin']['short_name'];
                $amount = $getUser['amount'];
                $type = $getUser['type'];
                $created = $getUser['created_at'];

                $singleArr = ['id'=>$id,
                    'adminId'=> $adminId,
                    'adminName'=>$adminName,
                    'adminPhone' => $adminPhone,
                    'adminWallet' => $adminWalletAddress,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'userWallet'=>$userWalletAddress,
                    'membership'=>$membership,
                    'bank'=>$bank,
                    'accountnum'=>$accountnum,
                    'couponCoin'=>$couponCoin,
                    'coinAmount'=>$coinAmount,
                    'coin'=>$coin,
                    'amount'=>$amount,
                    'type'=>$type,
                    'created'=>$created,

                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;

        }
    }
    //Admin coupons end
     //Buy Fees Start
    public function fees()
    {
        $this->loadModel('BuyExchange');
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
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('getcryptocoin.id' => $search['coin_first_id']);
            $searchDataTotal['AND'][] = array('BuyExchange.buy_get_coin_id' => $search['coin_first_id']);
        }
        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != ''){
            $searchData['AND'][] = array('DATE(BuyExchange.update_at) >= ' => $this->request->query['start_date'],'DATE(BuyExchange.update_at) <= ' => $this->request->query['end_date']);
            $searchDataTotal['AND'][] = array('DATE(BuyExchange.update_at) >= ' => $this->request->query['start_date'],'DATE(BuyExchange.update_at) <= ' => $this->request->query['end_date']);
            $totalBuyFees = $this->BuyExchange->find("all",["fields"=>["total_buyFees"=>"SUM(buy_fees)"],'conditions'=>$searchDataTotal])->hydrate(false)->first();
            $totalBuyFeesShow = !empty($totalBuyFees["total_buyFees"]) ? $totalBuyFees["total_buyFees"] : 0;
            $this->set('totalBuyFeesShow',$totalBuyFeesShow);
        }
        else if(!empty($search['start_date']) && $search['start_date'] != '')	{
            $searchData['AND'][] = array('DATE(BuyExchange.update_at)' => $search['start_date']);
            $searchDataTotal['AND'][] = array('DATE(BuyExchange.update_at)' => $search['start_date']);
            $totalBuyFees = $this->BuyExchange->find("all",["fields"=>["total_buyFees"=>"SUM(buy_fees)"],'conditions'=>$searchDataTotal])->hydrate(false)->first();
            $totalBuyFeesShow = !empty($totalBuyFees["total_buyFees"]) ? $totalBuyFees["total_buyFees"] : 0;
            $this->set('totalBuyFeesShow',$totalBuyFeesShow);
        }
        else if(!empty($search['end_date']) && $search['end_date'] != '')	{
            $searchData['AND'][] = array('DATE(BuyExchange.update_at)' => $search['end_date']);
            $searchDataTotal['AND'][] = array('DATE(BuyExchange.update_at)' => $search['end_date']);
            $totalBuyFees = $this->BuyExchange->find("all",["fields"=>["total_buyFees"=>"SUM(buy_fees)"],'conditions'=>$searchDataTotal])->hydrate(false)->first();
            $totalBuyFeesShow = !empty($totalBuyFees["total_buyFees"]) ? $totalBuyFees["total_buyFees"] : 0;
            $this->set('totalBuyFeesShow',$totalBuyFeesShow);

        }

        if($this->request->query('export')){

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Spent Coin','Spent Amount','Bought Coin','Bought Amount','Description','Per Price','Fees',
                'Status','Created', 'Updated');
            fputcsv($file,$headers);

            $users =  $this->BuyExchange->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']], 'spendcryptocoin'=>['fields'=>['short_name']],'getcryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>$searchData,
                'order'=>['BuyExchange.id'=>'desc'],
                //'limit' => $limit,
            ]);
			$this->add_system_log(200, 0, 5, '고객 구매 수수료 목록 CSV 다운로드');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['buyer_user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Spent Coin'] = isset($data['spendcryptocoin']['short_name'])?$data['spendcryptocoin']['short_name']:'';
                $arr['Spent Amount'] = isset($data['buy_spend_amount'])?$data['buy_spend_amount']:0;
                $arr['Bought Coin'] = isset($data['getcryptocoin']['short_name'])?$data['getcryptocoin']['short_name']:'';
                $arr['Bought Amount'] =  isset($data['buy_get_amount'])?$data['buy_get_amount']:0;
                $arr['Description'] = $data['buy_description'];
                $arr['Per Price'] = isset($data['per_price'])?$data['per_price']:0;
                $arr['Fees'] = round($data['buy_fees'],2);
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                $arr['Updated'] = date('Y-m-d H:i:s',strtotime($data['update_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'BuyFeesDetails'.$filename
            ));
            return $this->response;die;
        }
        //}
        $totalBuyFees = $this->BuyExchange->find("all",["fields"=>["total_buyFees"=>"SUM(buy_fees)"],'conditions'=>$searchDataTotal])->hydrate(false)->first();
        $totalBuyFeesShow = !empty($totalBuyFees["total_buyFees"]) ? $totalBuyFees["total_buyFees"] : 0;
        $this->set('totalBuyFeesShow',$totalBuyFeesShow);

        $dailyTotalBuyFees = $this->BuyExchange->find("all",["fields"=>["total_buyFees"=>"SUM(buy_fees)"],'conditions'=>['status'=>'completed','created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)']+$searchDataTotal])->hydrate(false)->first();
        $dailyTotalBuyFeesShow = !empty($dailyTotalBuyFees["total_buyFees"]) ? $dailyTotalBuyFees["total_buyFees"] : 0;
        $this->set('dailyTotalBuyFeesShow',$dailyTotalBuyFeesShow);

        $collectdata = $this->Paginator->paginate($this->BuyExchange, [
            'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                'spendcryptocoin'=>['fields'=>['short_name']],'getcryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>$searchData,
            'order'=>['BuyExchange.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);
    }

    public function feespagination()
    {
        $this->loadModel('BuyExchange');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            //$searchData['AND'][] = array('conditions'=> ['PrincipalWallet.type'=>'deducted_coupon_krw']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
//                if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);
//                if (!empty($search['coin_first_id'])) $searchData['AND'][] = array('getcryptocoin.id' => $search['coin_first_id']);
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(BuyExchange.update_at) >= ' =>
                    $this->request->data['start_date'],'DATE(BuyExchange.update_at) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.update_at)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.update_at)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->BuyExchange, [
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                    'spendcryptocoin'=>['fields'=>['short_name']],'getcryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>$searchData,
                'order'=>['BuyExchange.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function feesajax(){
        $this->loadModel('Users');
        $this->loadModel('BuyExchange');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->BuyExchange->find("all",['contain'=>['user','spendcryptocoin','getcryptocoin'],'conditions'=>['BuyExchange.id'=>$id],
                'order'=>['BuyExchange.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function feesajaxname(){
        $this->loadModel('Users');
        $this->loadModel('BuyExchange');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->BuyExchange->find("all",['contain'=>['user','spendcryptocoin','getcryptocoin'],'conditions'=>['BuyExchange.buyer_user_id'=>$userId],'order'=>
                ['BuyExchange.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['buyer_user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $getCoin = $getUser['getcryptocoin']['short_name'];
                $spentCoin = $getUser['spendcryptocoin']['short_name'];
                $spentAmount = $getUser['buy_spend_amount'];
                $buyAmount = $getUser['buy_get_amount'];
                $description = $getUser['buy_description'];
                $fees = $getUser['buy_fees'];
                $perPrice = $getUser['per_price'];
                $status = $getUser['status'];
                $created = $getUser['created_at'];
                $updated = $getUser['update_at'];
                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'getCoin'=>$getCoin,
                    'spentCoin'=>$spentCoin,
                    'buyAmount'=>isset($buyAmount) ? $buyAmount : 0,
                    'spentAmount'=>isset($spentAmount) ? $spentAmount : 0,
                    'type'=>$description,
                    'fees'=>isset($fees) ? $fees : 0,
                    'perPrice'=>isset($perPrice) ? $perPrice : 0,
                    'status'=>$status,
                    'created'=>$created,
                    'updated'=>$updated
                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;

        }
    }

    //Buy Fees End

    //Sell Fees Start
    public function sellfees()
    {
        $this->loadModel('SellExchange');
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
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('spendcryptocoin.id' => $search['coin_first_id']);
            $searchDataTotal['AND'][] = array('SellExchange.sell_spend_coin_id' => $search['coin_first_id']);
        }
        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') {
            $searchData['AND'][] = array('DATE(SellExchange.update_at) >= ' => $this->request->query['start_date'],'DATE(SellExchange.update_at) <= ' => $this->request->query['end_date']);
            $searchDataTotal['AND'][] = array('DATE(SellExchange.update_at) >= ' => $this->request->query['start_date'],'DATE(SellExchange.update_at) <= ' => $this->request->query['end_date']);
            $totalSellFees = $this->SellExchange->find("all",["fields"=>["total_sellFees"=>"SUM(sell_fees)"],'conditions'=>$searchDataTotal])->hydrate(false)->first();
            $totalSellFeesShow = !empty($totalSellFees["total_sellFees"]) ? $totalSellFees["total_sellFees"] : 0;
            $this->set('totalSellFeesShow',$totalSellFeesShow);
        }else if(!empty($search['start_date']) && $search['start_date'] != '')	{
            $searchData['AND'][] = array('DATE(SellExchange.update_at)' => $search['start_date']);
            $searchDataTotal['AND'][] = array('DATE(SellExchange.update_at)' => $search['start_date']);
            $totalSellFees = $this->SellExchange->find("all",["fields"=>["total_sellFees"=>"SUM(sell_fees)"],'conditions'=>$searchDataTotal])->hydrate(false)->first();
            $totalSellFeesShow = !empty($totalSellFees["total_sellFees"]) ? $totalSellFees["total_sellFees"] : 0;
            $this->set('totalSellFeesShow',$totalSellFeesShow);
        }else if(!empty($search['end_date']) && $search['end_date'] != '')	{
            $searchData['AND'][] = array('DATE(SellExchange.update_at)' => $search['end_date']);
            $searchDataTotal['AND'][] = array('DATE(SellExchange.update_at)' => $search['end_date']);
            $totalSellFees = $this->SellExchange->find("all",["fields"=>["total_sellFees"=>"SUM(sell_fees)"],'conditions'=>$searchDataTotal])->hydrate(false)->first();
            $totalSellFeesShow = !empty($totalSellFees["total_sellFees"]) ? $totalSellFees["total_sellFees"] : 0;
            $this->set('totalSellFeesShow',$totalSellFeesShow);
        }

        if($this->request->query('export')){
            // Export
//                if($search['export']=='c') $filename = time().'export.csv';
//                else  $filename = 'export.xlsx';

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Spent Coin','Spent Amount','Sell Get Coin','Sell Get Amount','Description','Per Price','Fees',
                'Status','Created', 'Updated');
            fputcsv($file,$headers);

            $users =  $this->SellExchange->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']], 'spendcryptocoin'=>['fields'=>['short_name']],'getcryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>$searchData,
                'order'=>['SellExchange.id'=>'desc'],
                //'limit' => $limit,
            ]);
			$this->add_system_log(200, 0, 5, '고객 판매 수수료 목록 CSV 다운로드');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['seller_user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Spent Coin'] = isset($data['spendcryptocoin']['short_name'])?$data['spendcryptocoin']['short_name']:'';
                $arr['Spent Amount'] = isset($data['sell_spend_amount'])?$data['sell_spend_amount']:0;
                $arr['Bought Coin'] = isset($data['getcryptocoin']['short_name'])?$data['getcryptocoin']['short_name']:'';
                $arr['Bought Amount'] =  isset($data['sell_get_amount'])?$data['sell_get_amount']:0;
                $arr['Description'] = $data['sell_description'];
                $arr['Per Price'] = isset($data['per_price'])?$data['per_price']:0;
                $arr['Fees'] = round($data['sell_fees'],2);
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                $arr['Updated'] = date('Y-m-d H:i:s',strtotime($data['update_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'SellFeesDetails'.$filename
            ));
            return $this->response;die;
        }

        $totalSellFees = $this->SellExchange->find("all",["fields"=>["total_sellFees"=>"SUM(sell_fees)"],'conditions'=>$searchDataTotal])->hydrate(false)->first();
        $totalSellFeesShow = !empty($totalSellFees["total_sellFees"]) ? $totalSellFees["total_sellFees"] : 0;
        $this->set('totalSellFeesShow',$totalSellFeesShow);

        $dailyTotalSellFees = $this->SellExchange->find("all",["fields"=>["total_sellFees"=>"SUM(sell_fees)"],'conditions'=>['status'=>'completed','created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)']+$searchDataTotal])->hydrate(false)->first();
        $dailyTotalSellFeesShow = !empty($dailyTotalSellFees["total_sellFees"]) ? $dailyTotalSellFees["total_sellFees"] : 0;
        $this->set('dailyTotalSellFeesShow',$dailyTotalSellFeesShow);

        $collectdata = $this->Paginator->paginate($this->SellExchange, [
            'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                'spendcryptocoin'=>['fields'=>['short_name']],'getcryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>$searchData,
            'order'=>['SellExchange.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);
    }

    public function sellfeespagination()
    {

        $this->loadModel('SellExchange');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
//                if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);
//                if (!empty($search['coin_first_id'])) $searchData['AND'][] = array('getcryptocoin.id' => $search['coin_first_id']);
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(SellExchange.update_at) >= ' =>
                    $this->request->data['start_date'],'DATE(SellExchange.update_at) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.update_at)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.update_at)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->SellExchange, [
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                    'spendcryptocoin'=>['fields'=>['short_name']],'getcryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>$searchData,
                'order'=>['SellExchange.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function sellfeesajax(){
        $this->loadModel('Users');
        $this->loadModel('SellExchange');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->BuyExchange->find("all",['contain'=>['user','spendcryptocoin','getcryptocoin'],'conditions'=>['SellExchange.id'=>$id],
                'order'=>['SellExchange.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function sellfeesajaxname(){
        $this->loadModel('Users');
        $this->loadModel('SellExchange');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->SellExchange->find("all",['contain'=>['user','spendcryptocoin','getcryptocoin'],'conditions'=>['SellExchange.seller_user_id'=>$userId],'order'=>
                ['BuyExchange.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['seller_user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $getCoin = $getUser['getcryptocoin']['short_name'];
                $spentCoin = $getUser['spendcryptocoin']['short_name'];
                $spentAmount = $getUser['sell_spend_amount'];
                $buyAmount = $getUser['sell_get_amount'];
                $description = $getUser['sell_description'];
                $fees = $getUser['sell_fees'];
                $perPrice = $getUser['per_price'];
                $status = $getUser['status'];
                $created = $getUser['created_at'];
                $updated = $getUser['update_at'];
                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'getCoin'=>$getCoin,
                    'spentCoin'=>$spentCoin,
                    'buyAmount'=>isset($buyAmount) ? $buyAmount : 0,
                    'spentAmount'=>isset($spentAmount) ? $spentAmount : 0,
                    'type'=>$description,
                    'fees'=>isset($fees) ? $fees : 0,
                    'perPrice'=>isset($perPrice) ? $perPrice : 0,
                    'status'=>$status,
                    'created'=>$created,
                    'updated'=>$updated
                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;

        }
    }

    //Sell Fees End

    //Internal Account Transfer Fees Start
    public function accounttransferfees()
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
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
            $searchDataTotal['AND'][] = array('PrincipalWallet.cryptocoin_id' => $search['coin_first_id']);
        }
        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') {
            $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
            $searchDataTotal['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
            $totalTransferFees = $this->PrincipalWallet->find("all",["fields"=>["total_transfersFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'transfer_to_trading_account'],['type'=>'transfer_from_trading_account']]]+$searchDataTotal])->hydrate(false)->first();
            $totalTransferFeesShow = !empty($totalTransferFees["total_transfersFees"]) ? $totalTransferFees["total_transfersFees"] : 0;
            $this->set('totalTransferFeesShow',$totalTransferFeesShow);
            $totalTransfers = $this->PrincipalWallet->find("all",["fields"=>["total_transfers"=>"SUM(amount)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'transfer_to_trading_account'],['type'=>'transfer_from_trading_account']]]+$searchDataTotal])->hydrate(false)->first();
            $totalTransfersShow = !empty($totalTransfers["total_transfers"]) ? $totalTransfers["total_transfers"] : 0;
            $this->set('totalTransfersShow',$totalTransfersShow);
        } else if(!empty($search['start_date']) && $search['start_date'] != '')	{
            $searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
            $searchDataTotal['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
            $totalTransferFees = $this->PrincipalWallet->find("all",["fields"=>["total_transfersFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'transfer_to_trading_account'],['type'=>'transfer_from_trading_account']]]+$searchDataTotal])->hydrate(false)->first();
            $totalTransferFeesShow = !empty($totalTransferFees["total_transfersFees"]) ? $totalTransferFees["total_transfersFees"] : 0;
            $this->set('totalTransferFeesShow',$totalTransferFeesShow);
            $totalTransfers = $this->PrincipalWallet->find("all",["fields"=>["total_transfers"=>"SUM(amount)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'transfer_to_trading_account'],['type'=>'transfer_from_trading_account']]]+$searchDataTotal])->hydrate(false)->first();
            $totalTransfersShow = !empty($totalTransfers["total_transfers"]) ? $totalTransfers["total_transfers"] : 0;
            $this->set('totalTransfersShow',$totalTransfersShow);
        } else if(!empty($search['end_date']) && $search['end_date'] != '')	{
            $searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
            $searchDataTotal['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
            $totalTransferFees = $this->PrincipalWallet->find("all",["fields"=>["total_transfersFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'transfer_to_trading_account'],['type'=>'transfer_from_trading_account']]]+$searchDataTotal])->hydrate(false)->first();
            $totalTransferFeesShow = !empty($totalTransferFees["total_transfersFees"]) ? $totalTransferFees["total_transfersFees"] : 0;
            $this->set('totalTransferFeesShow',$totalTransferFeesShow);
            $totalTransfers = $this->PrincipalWallet->find("all",["fields"=>["total_transfers"=>"SUM(amount)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'transfer_to_trading_account'],['type'=>'transfer_from_trading_account']]]+$searchDataTotal])->hydrate(false)->first();
            $totalTransfersShow = !empty($totalTransfers["total_transfers"]) ? $totalTransfers["total_transfers"] : 0;
            $this->set('totalTransfersShow',$totalTransfersShow);
        }

        if($this->request->query('export')){

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Coin','Amount','Type','Remark','Fees','Status','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->PrincipalWallet->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['OR' =>[['PrincipalWallet.type' => 'transfer_to_trading_account'],['PrincipalWallet.type' => 'transfer_from_trading_account']]]+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                //'limit' => $limit,
            ]);
			$this->add_system_log(200, 0, 5, '고객 내부 이체 수수료 목록 CSV 다운로드');

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
                $arr['fees'] = round($data['fees'],0);
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'AccountTransferFeesDetails'.$filename
            ));
            return $this->response;die;
        }
        $dailyTotalTransferFees = $this->PrincipalWallet->find("all",["fields"=>["total_transferFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)','OR' =>[['type' => 'transfer_to_trading_account'],['type'=>'transfer_from_trading_account']]]+$searchDataTotal])->hydrate(false)->first();
        $dailyTotalTransferFeesShow = !empty($dailyTotalTransferFees["total_transferFees"]) ? $dailyTotalTransferFees["total_transferFees"] : 0;
        $this->set('dailyTotalTransferFeesShow',$dailyTotalTransferFeesShow);

        $dailyTotalTransfer = $this->PrincipalWallet->find("all",["fields"=>["total_transfer"=>"SUM(amount)"],'conditions'=>['status'=>'completed','type'=>'transfer_to_trading_account','created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)']+$searchDataTotal])->hydrate(false)->first();
        $dailyTotalTransferShow = !empty($dailyTotalTransfer["total_transfer"]) ? $dailyTotalTransfer["total_transfer"] : 0;
        $this->set('dailyTotalTransferShow',$dailyTotalTransferShow);

        $totalTransferFees = $this->PrincipalWallet->find("all",["fields"=>["total_transfersFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'transfer_to_trading_account'],['type'=>'transfer_from_trading_account']]]+$searchDataTotal])->hydrate(false)->first();
        $totalTransferFeesShow = !empty($totalTransferFees["total_transfersFees"]) ? $totalTransferFees["total_transfersFees"] : 0;
        $this->set('totalTransferFeesShow',$totalTransferFeesShow);

        $totalTransfers = $this->PrincipalWallet->find("all",["fields"=>["total_transfers"=>"SUM(amount)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'transfer_to_trading_account'],['type'=>'transfer_from_trading_account']]]+$searchDataTotal])->hydrate(false)->first();
        $totalTransfersShow = !empty($totalTransfers["total_transfers"]) ? $totalTransfers["total_transfers"] : 0;
        $this->set('totalTransfersShow',$totalTransfersShow);

        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['OR' =>[['PrincipalWallet.type' => 'transfer_to_trading_account'],['PrincipalWallet.type' => 'transfer_from_trading_account']]]+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);

    }

    public function accounttransferfeespagination()
    {

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            //$searchData['AND'][] = array('conditions'=> ['PrincipalWallet.type'=>'deducted_coupon_krw']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
//                if (!empty($search['coin_first_id'])) $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' =>
                    $this->request->data['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
                'contain'=>['user'=>['fields'=>['eth_address','name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['OR' =>[['PrincipalWallet.type' => 'transfer_to_trading_account'],['PrincipalWallet.type' => 'transfer_from_trading_account']]]+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function accounttransferfeesajax(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.id'=>$id,
                'OR' =>[['PrincipalWallet.type' => 'transfer_to_trading_account'],['PrincipalWallet.type' => 'transfer_from_trading_account']]],
                'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function accounttransferfeesajaxname(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.user_id'=>$userId,
                'OR' =>[['PrincipalWallet.type' => 'transfer_to_trading_account'],['PrincipalWallet.type' => 'transfer_from_trading_account']]],'order'=>
                ['PrincipalWallet.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $coin = $getUser['cryptocoin']['short_name'];
                $amount = $getUser['amount'];
                $type = $getUser['type'];
                $fees = $getUser['fees'];
                $status = $getUser['status'];
                $created = $getUser['created_at'];

                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'coin'=>$coin,
                    'amount'=>isset($amount) ? $amount : 0,
                    'type'=>$type,
                    'fees'=>isset($fees) ? $fees : 0,
                    'status'=>$status,
                    'created'=>$created,

                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;

        }
    }

    //Internal Account Transfer Fees End

    //Withdrawal Fees Start
    public function withdrawfees()
    {
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');

        $authUserId = $this->Auth->user('id');
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
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);
        if (!empty($search['coin_first_id']))
        {
            $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
            $searchDataTotal['AND'][] = array('PrincipalWallet.cryptocoin_id' => $search['coin_first_id']);
        }
        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') {
            $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
            $searchDataTotal['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
            $totalWithdrawFees = $this->PrincipalWallet->find("all",["fields"=>["total_withdrawalFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'withdrawal'],['type'=>'bank_initial_withdraw']]]+$searchDataTotal])->hydrate(false)->first();
            $totalWithdrawalFeesShow = !empty($totalWithdrawFees["total_withdrawalFees"]) ? $totalWithdrawFees["total_withdrawalFees"] : 0;
            $this->set('totalWithdrawalFeesShow',$totalWithdrawalFeesShow);

            $totalWithdrawn = $this->PrincipalWallet->find("all",["fields"=>["total_withdrawn"=>"SUM(amount)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'withdrawal'],['type'=>'bank_initial_withdraw']]]+$searchDataTotal])->hydrate(false)->first();
            $totalWithdrawnShow = !empty($totalWithdrawn["total_withdrawn"]) ? $totalWithdrawn["total_withdrawn"] : 0;
            $this->set('totalWithdrawnShow',$totalWithdrawnShow);
        }else if(!empty($search['start_date']) && $search['start_date'] != '')	{
            $searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
            $searchDataTotal['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
            $totalWithdrawFees = $this->PrincipalWallet->find("all",["fields"=>["total_withdrawalFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'withdrawal'],['type'=>'bank_initial_withdraw']]]+$searchDataTotal])->hydrate(false)->first();
            $totalWithdrawalFeesShow = !empty($totalWithdrawFees["total_withdrawalFees"]) ? $totalWithdrawFees["total_withdrawalFees"] : 0;
            $this->set('totalWithdrawalFeesShow',$totalWithdrawalFeesShow);

            $totalWithdrawn = $this->PrincipalWallet->find("all",["fields"=>["total_withdrawn"=>"SUM(amount)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'withdrawal'],['type'=>'bank_initial_withdraw']]]+$searchDataTotal])->hydrate(false)->first();
            $totalWithdrawnShow = !empty($totalWithdrawn["total_withdrawn"]) ? $totalWithdrawn["total_withdrawn"] : 0;
            $this->set('totalWithdrawnShow',$totalWithdrawnShow);
        }else if(!empty($search['end_date']) && $search['end_date'] != '')	{
            $searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
            $searchDataTotal['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
            $totalWithdrawFees = $this->PrincipalWallet->find("all",["fields"=>["total_withdrawalFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'withdrawal'],['type'=>'bank_initial_withdraw']]]+$searchDataTotal])->hydrate(false)->first();
            $totalWithdrawalFeesShow = !empty($totalWithdrawFees["total_withdrawalFees"]) ? $totalWithdrawFees["total_withdrawalFees"] : 0;
            $this->set('totalWithdrawalFeesShow',$totalWithdrawalFeesShow);

            $totalWithdrawn = $this->PrincipalWallet->find("all",["fields"=>["total_withdrawn"=>"SUM(amount)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'withdrawal'],['type'=>'bank_initial_withdraw']]]+$searchDataTotal])->hydrate(false)->first();
            $totalWithdrawnShow = !empty($totalWithdrawn["total_withdrawn"]) ? $totalWithdrawn["total_withdrawn"] : 0;
            $this->set('totalWithdrawnShow',$totalWithdrawnShow);
        }

        if($this->request->query('export')){
            // Export
//                if($search['export']=='c') $filename = time().'export.csv';
//                else  $filename = 'export.xlsx';

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Coin','Amount','Coin Amount','Type','Fees','Remark','Status','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->PrincipalWallet->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['OR' =>[['PrincipalWallet.type' => 'bank_initial_withdraw'],['PrincipalWallet.type' => 'withdrawal']]]+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                //'limit' => $limit,
            ]);

			$this->add_system_log(200, 0, 5, '고객 출금 수수료 목록 CSV 다운로드');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone Number'] = $data['user']['phone_number'];
                $arr['Coin'] = $data['cryptocoin']['short_name'];
                $arr['KRW Amount'] = round($data['amount'],2);
                $arr['Coin Amount'] = round($data['coin_amount'],2);
                $arr['Type'] = $data['type'];
                $arr['Remark'] = $data['remark'];
                $arr['fees'] = round($data['fees'],0);
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'UsersPrincipalWalletDetails'.$filename
            ));
            return $this->response;die;
        }
        $dailyTotalWithdrawFees = $this->PrincipalWallet->find("all",["fields"=>["total_withdrawFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)','OR' =>[['type' => 'withdrawal'],['type'=>'bank_initial_withdraw']]]+$searchDataTotal])->hydrate(false)->first();
        $dailyTotalWithdrawFeesShow = !empty($dailyTotalWithdrawFees["total_withdrawFees"]) ? $dailyTotalWithdrawFees["total_withdrawFees"] : 0;
        $this->set('dailyTotalWithdrawFeesShow',$dailyTotalWithdrawFeesShow);

        $totalWithdrawFees = $this->PrincipalWallet->find("all",["fields"=>["total_withdrawalFees"=>"SUM(fees)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'withdrawal'],['type'=>'bank_initial_withdraw']]]+$searchDataTotal])->hydrate(false)->first();
        $totalWithdrawalFeesShow = !empty($totalWithdrawFees["total_withdrawalFees"]) ? $totalWithdrawFees["total_withdrawalFees"] : 0;
        $this->set('totalWithdrawalFeesShow',$totalWithdrawalFeesShow);

        $totalWithdrawn = $this->PrincipalWallet->find("all",["fields"=>["total_withdrawn"=>"SUM(amount)"],'conditions'=>['status'=>'completed','OR' =>[['type' => 'withdrawal'],['type'=>'bank_initial_withdraw']]]+$searchDataTotal])->hydrate(false)->first();
        $totalWithdrawnShow = !empty($totalWithdrawn["total_withdrawn"]) ? $totalWithdrawn["total_withdrawn"] : 0;
        $this->set('totalWithdrawnShow',$totalWithdrawnShow);

        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['eth_address','name','phone_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['OR' =>[['PrincipalWallet.type' => 'bank_initial_withdraw'],['PrincipalWallet.type' => 'withdrawal']]]+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);
    }

    public function withdrawfeespagination()
    {

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            $searchData['AND'][] = array('conditions'=>['OR' =>[['PrincipalWallet.type' => 'bank_initial_withdraw'],['PrincipalWallet.type' => 'withdrawal']]]);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
//                if (!empty($search['coin_first_id'])) $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' =>
                    $this->request->data['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
                'contain'=>['user'=>['fields'=>['eth_address','name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['OR' =>[['PrincipalWallet.type' => 'bank_initial_withdraw'],['PrincipalWallet.type' => 'withdrawal']]]+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function withdrawfeesajax(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.id'=>$id,'OR' =>[['PrincipalWallet.type'
            => 'bank_initial_withdraw'],['PrincipalWallet.type' => 'withdrawal']]], 'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function withdrawfeesajaxname(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.user_id'=>$userId,
                'OR' =>[['PrincipalWallet.type' => 'bank_initial_withdraw'],['PrincipalWallet.type' => 'withdrawal']]],'order'=>
                ['PrincipalWallet.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $coinAmount = $getUser['coin_amount'];
                $coin = $getUser['cryptocoin']['short_name'];
                $amount = $getUser['amount'];
                $type = $getUser['type'];
                $remark = $getUser['remark'];
                $fees = $getUser['fees'];
                $status = $getUser['status'];
                $created = $getUser['created_at'];

                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'coinAmount'=>isset($coinAmount) ? $coinAmount : 0,
                    'coin'=>$coin,
                    'amount'=>isset($amount) ? $amount : 0,
                    'type'=>$type,
                    'remark'=>$remark,
                    'fees'=>isset($fees) ? $fees : 0,
                    'status'=>$status,
                    'created'=>$created,

                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;

        }
    }

    //Withdrawal Fees End


    //Loan Deposit Fees Start
    public function loandepositfees()
    {
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');

        $authUserId = $this->Auth->user('id');
        $limit = 20;
        $searchData = [];
        if($this->request->query('page')) {

            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);
        $usersFindList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => function ($e) {
                return $e->username . ' - ' . $e->name;
            },
            'conditions'=>['user_type'=>"U"]
        ])->toArray();
        $this->set('usersFindList',$usersFindList);

        $coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
            'valueField' => 'short_name'
        ],['conditions'=>['id !='=>1]])->toArray();
        $this->set('coinList',$coinList);
        $coinsList = $this->Cryptocoin->find('all', array('fields'=>array('Cryptocoin.id','Cryptocoin.short_name')));
        $this->set('coinsList',$coinsList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) $searchData['AND'][] = array('user.id' => $search['user_name']);
        if (!empty($search['coin_first_id'])) $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(PrincipalWallet.created_at) >= ' => $this->request->query['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);

        if($this->request->query('export')){
            // Export
//                if($search['export']=='c') $filename = time().'export.csv';
//                else  $filename = 'export.xlsx';

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User ID','User Name','Phone Number','Coin','Amount','Type','Fees','Status','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->PrincipalWallet->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['PrincipalWallet.type' => 'loan_deposit']+$searchData,
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
                $arr['Amount'] = round($data['amount'],0);
                $arr['Type'] = $data['type'];
                $arr['fees'] = round($data['fees'],0);
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created_at']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'LoanDepositDetails'.$filename
            ));
            return $this->response;die;
        }
        //}

        $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
            'contain'=>['user'=>['fields'=>['name','phone_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['PrincipalWallet.type' => 'loan_deposit']+$searchData,
            'order'=>['PrincipalWallet.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);

    }

    public function loandepositfeespagination()
    {

        $this->loadModel('PrincipalWallet');
        $this->loadModel('Users');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax'))
        {
            $limit = 20;
            $searchData = array();
            //$searchData['AND'][] = array('conditions'=> ['PrincipalWallet.type'=>'deducted_coupon_krw']);
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                if($search['pagination'] != '') $limit =  $search['pagination'];
//                if (!empty($search['coin_first_id'])) $searchData['AND'][] = array('cryptocoin.id' => $search['coin_first_id']);
                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(PrincipalWallet.created_at) >= ' =>
                    $this->request->data['start_date'],'DATE(PrincipalWallet.created_at) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(PrincipalWallet.created_at)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
            }
            else $this->set('serial_num',1);

            $collectdata = $this->Paginator->paginate($this->PrincipalWallet, [
                'contain'=>['user'=>['fields'=>['eth_address','name','phone_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['PrincipalWallet.type' => 'loan_deposit']+$searchData,
                'order'=>['PrincipalWallet.id'=>'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing',$collectdata);

        }
    }

    public function loandepositfeesajax(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $getData =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.id'=>$id,
                'PrincipalWallet.type' => 'loan_deposit'], 'order'=>['PrincipalWallet.id'=>'desc']])->hydrate(false)->first();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"user record",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                print_r('error');
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }
    }
    public function loandepositfeesajaxname(){
        $this->loadModel('Users');
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Cryptocoin');

        if ($this->request->is('ajax')) {
            $mainRespArr = [];
            $userId = $this->request->data['user_id'];
            $getDataList =  $this->PrincipalWallet->find("all",['contain'=>['user','cryptocoin'],'conditions'=>['PrincipalWallet.user_id'=>$userId,
                'PrincipalWallet.type' => 'loan_deposit'],'order'=> ['PrincipalWallet.id'=>'desc']])->hydrate(false)->toArray();
            foreach($getDataList as $getUser){
                $id = $getUser['id'];
                $userId = $getUser['user_id'];
                $userName = $getUser['user']['name'];
                $phone = $getUser['user']['phone_number'];
                $coinAmount = $getUser['coin_amount'];
                $coin = $getUser['cryptocoin']['short_name'];
                $amount = $getUser['amount'];
                $type = $getUser['type'];
                $fees = $getUser['fees'];
                $status = $getUser['status'];
                $created = $getUser['created_at'];

                $singleArr = ['id'=>$id,
                    'userId'=>$userId,
                    'userName'=>$userName,
                    'phone'=>$phone,
                    'coinAmount'=>isset($coinAmount) ? $coinAmount : 0,
                    'coin'=>$coin,
                    'amount'=>isset($amount) ? $amount : 0,
                    'type'=>$type,
                    'fees'=>isset($fees) ? $fees : 0,
                    'status'=>$status,
                    'created'=>$created

                ];
                $mainRespArr[]=$singleArr;
            }
            $respArr=['status'=>'false','message'=>"user list",'data'=>['userlist'=>$mainRespArr]];

            echo json_encode($respArr); die;

        }
    }

    //Loan Deposit Fees End
	public function withdrawaltradinglist()
    {
        $this->loadModel('Trasactions');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $limit = 20;
        $totalWithdrawnAmount = 0;
        $searchData = [];
        if($this->request->query('page')) {

            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        }
        else $this->set('serial_num',1);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])){
            $searchData['AND'][] = array('user.id' => $search['user_name']);
            $userId = $search['user_name'];
            $totalWithdrawnAmount = $this->Users->getUserTotalWithdrawnTradingWithoutFees($userId);
        }

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Transactions.created) >= ' => $this->request->query['start_date'],'DATE(Transactions.created) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(Transactions.created)' => $search['end_date']);

        if($this->request->query('export')){

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','User Id','User Name','Phone number','Annual Member','Bank Name','Account Number','Currency','Total Amount','Amount','Fees','Status','Date & Time');
            fputcsv($file,$headers);

            $users =  $this->Transactions->find('all',[
                'contain'=>['user'=>['fields'=>['id', 'name','phone_number','annual_membership', 'bank', 'account_number']],
                    'cryptocoin'=>['fields'=>['short_name']]],
                'conditions'=>['Transactions.tx_type' => 'bank_initial_withdraw']+$searchData,
                'order'=>['Transactions.id'=>'desc'],
            ]);
            $this->add_system_log(200, 0, 5, '고객 KRW 출금 요청 목록 CSV 다운로드');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone number'] = $data['user']['phone_number'];
                $arr['Annual Member'] = $data['user']['annual_membership'];
                $arr['Bank Name'] = $data['user']['bank'];
                $arr['Account Number'] = $this->Decrypt($data['user']['account_number']);
                $arr['Currency'] = $data['cryptocoin']['short_name'];
                $arr['Total Amount'] = number_format($data['coin_amount'],2);
                $arr['Amount'] = number_format($data['amount'],2);
                $arr['Fees'] = $data['fees'];
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created']));
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'WithdrawalTradingAmountList'.$filename
            ));
            return $this->response;die;
        }
        //}

        $collectdata = $this->Paginator->paginate($this->Transactions, [
            'fields'=>['id','user_id','amount','coin_amount','fees','created','updated','status','cryptocoin_id','wallet_address','tx_type','tx_id','remark'],
            'contain'=>['user'=>['fields'=>['name','phone_number','annual_membership', 'bank', 'account_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['Transactions.tx_type' => 'bank_initial_withdraw']+$searchData,
            'order'=>['Transactions.id'=>'desc'],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);
        $this->set('totalWithdrawnAmount',$totalWithdrawnAmount);
    }
	public function coinswithdrawaltradinglist()
    {
        $this->loadModel('Transactions');
        $this->loadModel('Cryptocoin');
        $searchData = [];

        $search = $this->request->query;
        if (!empty($search['user_name'])){ //'PrincipalWallet.user_id'=>user_name,
            $searchData['AND'][] = array('Transactions.user_id' => $search['user_name']);
        }
        $collectdata = $this->Paginator->paginate($this->Transactions, [
            'contain'=>['user'=>['fields'=>['name','phone_number']],
                'cryptocoin'=>['fields'=>['short_name']]],
            'conditions'=>['Transactions.tx_type' => 'withdrawal']+$searchData,
            'order'=>['Transactions.id'=>'desc'],
        ]);

        $this->set('listing',$collectdata);
    }
	public function statustradingwallet(){
        if ($this->request->is('ajax')) {
            $this->loadModel('Transactions');
            //$principalwallet = $this->PrincipalWallet->get($this->request->data['id']); // Return article with id 12
            //$principalwallet->status = $this->request->data['status'];
            //$principalwallet->updated_at = date('Y-m-d H:i:s');
            //$this->PrincipalWallet->save($principalwallet);
            $query = $this->Transactions->query();
            $query->update()->set(['status'=>$this->request->data['status'],'updated'=>date('Y-m-d H:i:s')])->where(['id' =>$this->request->data['id']])->execute();
            $this->add_system_log(200, 0, 3, '지갑 status 수정 (id :: '.$this->request->data['id'].')');
            echo 1;
        }
        die;
    }
}
