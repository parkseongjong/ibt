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
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager; 
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
//use Google\Authenticator\GoogleAuthenticator;


class ExchangeController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		// Allow users to register and logout.
		// You should not add the "login" action to allow list. Doing so would
		// cause problems with normal functioning of AuthComponent.
        //$this->Auth->allow(['signup', 'logout','frontLogin','frontRegister','verify','forgotPassword','successregister']);
    }

    public function buyList(){
		$start_date = date('Y-m-d', strtotime('-1 month'));
		$end_date = date('Y-m-d');
		if(empty($this->request->query['start_date']) && empty($this->request->query['end_date'])){
			return $this->redirect(['action'=>'buyList','start_date'=>$start_date,'end_date'=>$end_date]);
		}
		$this->request->session()->write('buy_list_export', 'fail');
        $this->loadModel('BuyExchange');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $this->set('display_type','BTC');
        $authUserId = $this->Auth->user('id');
        $limit = 20;
        $searchData = [];
        $searchDataTotal = [];
        $type = "BTC";
		$this->set('serial_num',1);
		$order = 'BuyExchange.created_at';
        if($this->request->query('page')) { $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1)); }

        $coinList = $this->get_coin_list();
        $this->set('coinList',$coinList);

        $statusList = $this->BuyExchange->find('all',array('fields'=>'DISTINCT BuyExchange.status'));
        $this->set('statusList', $statusList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];

        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('user.id' => $search['user_name']);
            $searchDataTotal['AND'][] = array('BuyExchange.buyer_user_id' => $search['user_name']);
            $userId = $this->Users->find('all',['conditions'=>['id'=>$search['user_name']]])->hydrate(false)->first();
            $totalBuyAmount = $this->Users->getUserTotalBuy($userId['id']);
            $this->set('totalBuyAmount',$totalBuyAmount);
        }

        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('spendcryptocoin.id' => $search['coin_first_id']);
            $searchDataTotal['AND'][] = array('BuyExchange.buy_spend_coin_id' => $search['coin_first_id']);
            if(!empty($search['btn_status'])){
                $searchData['AND'][] = array('BuyExchange.status' => $search['btn_status']);
            }
        }

        if (!empty($search['coin_second_id'])){
            $searchData['AND'][] = array('getcryptocoin.id' => $search['coin_second_id']);
            $searchDataTotal['AND'][] = array('BuyExchange.buy_get_coin_id' => $search['coin_second_id']);
            if(!empty($search['btn_status'])){
                $searchData['AND'][] = array('BuyExchange.status' => $search['btn_status']);
            }
        }

        if(!empty($search['status'])){
            $searchData['AND'][] = array('BuyExchange.status' => $search['status']);
        }

		if(!empty($search['per_price'])){
            $searchData['AND'][] = array('BuyExchange.per_price >=' => $search['per_price']);
			$order = 'BuyExchange.per_price';
        }

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(BuyExchange.created_at) >= ' => $this->request->query['start_date'],'DATE(BuyExchange.created_at) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.created_at)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.created_at)' => $search['end_date']);

        if(empty($search['order_value'])){
            $search['order_value'] = 'desc';
        }

        if($this->request->query('export')){
            // Export
            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#', 'Username', 'Phone Number' ,'Total Spend Amount','Spend Amount','Spend Coin','Total Receive Amount','Receive Amount','Receive Coin','Rate','Admin Fee', 'Status', 'Date');
            fputcsv($file,$headers);

            $users = $this->BuyExchange->find('all', [
						'conditions'=>$searchData,
						'contain'=>['user'=>['fields'=>['name','phone_number']],
									'spendcryptocoin'=>['fields'=>['short_name']],
									'getcryptocoin'=>['fields'=>['short_name']]],
						'order'=>[$order=>$search['order_value']],
					]);
			$this->add_system_log(200, 0, 5, '구매 목록 CSV 다운로드 (이름, 전화번호 등)');


            $k = 1;
            foreach ($users as $k=>$data)
            {
                $username = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $phonenum = isset($data['user']['phone_number'])?$data['user']['phone_number']:'';
                $tbsa = isset($data['total_buy_spend_amount'])?$data['total_buy_spend_amount']:0;
                $bsa = isset($data['buy_spend_amount'])?$data['buy_spend_amount']:0;
                $short_name = isset($data['spendcryptocoin']['short_name'])?$data['spendcryptocoin']['short_name']:'';
                $bga = isset($data['buy_get_amount'])?$data['buy_get_amount']:0;
                $tbga = isset($data['total_buy_get_amount'])?$data['total_buy_get_amount']:0;
                $gshort_name = isset($data['getcryptocoin']['short_name'])?$data['getcryptocoin']['short_name']:'';
                $pprice = isset($data['per_price'])?$data['per_price']:0;
                $buyfee = isset($data['buy_fees'])?$data['buy_fees']:0;
                $status = isset($data['status'])?$data['status']:'';
                $created = $data['created_at'];

                $arr = [];
                $arr['#'] = $k;
                $arr['username'] =  $username;
                $arr['phonenum'] = $phonenum;
                $arr['tbsa'] =  number_format((float)$tbsa, 4);
                $arr['bsa'] =  number_format((float)$bsa, 4);
                $arr['short_name'] =  $short_name;
                $arr['bga'] =  number_format((float)$bga, 4);
                $arr['tbga'] =  number_format((float)$tbga, 4);
                $arr['gshort_name'] =  $gshort_name;
                $arr['pprice'] =  number_format((float)$pprice, 4);
                $arr['buyfee'] =  number_format((float)$buyfee, 4);
				$arr['status'] =  $status;
				$arr['created'] = $created;
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'BuyExchange'.$filename
            ));
            return $this->response;die;
        }

//        $order = ['BuyExchange.status DESC, BuyExchange.created_at DESC'];

        $collectdata = $this->Paginator->paginate($this->BuyExchange, [
						'conditions'=>$searchData+$searchDataTotal,
						'contain'=>['user'=>['fields'=>['id','name','phone_number']],
									'spendcryptocoin'=>['fields'=>['short_name']],
									'getcryptocoin'=>['fields'=>['short_name']]],
						'order'=>[$order=>$search['order_value']],
					]);
		$this->set('listing',$collectdata);
		$this->set('type',$type);
    }

    public function sellList(){
		$start_date = date('Y-m-d', strtotime('-1 month'));
		$end_date = date('Y-m-d');
		if(empty($this->request->query['start_date']) && empty($this->request->query['end_date'])){
		//	return $this->redirect(['action'=>'sellList','start_date'=>$start_date,'end_date'=>$end_date]);
		}
		$this->request->session()->write('sell_list_export', 'fail');
        $this->loadModel('SellExchange');
        $this->loadModel('Cryptocoin');
        $this->loadModel('Users');
        $this->set('display_type','BTC');
        $limit = 20;
        $searchData = [];
        $searchDataTotal = [];
        $type = "BTC";
		$this->set('serial_num',1);
		$order = 'SellExchange.created_at';
        if($this->request->query('page')) { $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1)); }

        $coinList = $this->get_coin_list();
        $this->set('coinList',$coinList);

        $statusList = $this->SellExchange->find('all',array('fields'=>'DISTINCT SellExchange.status'));
        $this->set('statusList', $statusList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['user_name'])) {
            $searchData['AND'][] = array('user.id' => $search['user_name']);
            $searchDataTotal['AND'][] = array('SellExchange.seller_user_id' => $search['user_name']);
            $userId = $this->Users->find('all',['conditions'=>['id'=>$search['user_name']]])->hydrate(false)->first();
            $totalSellAmount = $this->Users->getUserTotalSell($userId['id']);
            $this->set('totalSellAmount',$totalSellAmount);
        }
        if (!empty($search['coin_first_id'])){
            $searchData['AND'][] = array('spendcryptocoin.id' => $search['coin_first_id']);
            $searchDataTotal['AND'][] = array('SellExchange.sell_spend_coin_id' => $search['coin_first_id']);
        }
        if (!empty($search['coin_second_id'])){
            $searchData['AND'][] = array('getcryptocoin.id' => $search['coin_second_id']);
            $searchDataTotal['AND'][] = array('SellExchange.sell_get_coin_id' => $search['coin_second_id']);
        }
        if(!empty($search['status'])){
            $searchData['AND'][] = array("SellExchange.status"=>$search['status']);
        }
		if(!empty($search['per_price'])){
            $searchData['AND'][] = array('SellExchange.per_price >=' => $search['per_price']);
			$order = 'SellExchange.per_price';
        }

        if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] =
            array('DATE(SellExchange.created_at) >= ' => $this->request->query['start_date'],'DATE(SellExchange.created_at) <= ' => $this->request->query['end_date']);
        else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(SellExchange.created_at)' => $search['start_date']);
        else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(SellExchange.created_at)' => $search['end_date']);

        if(empty($search['order_value'])){
            $search['order_value'] = 'desc';
        }

        if($this->request->query('export')){
            // Export
            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#', 'Username','Phone Number', 'Total Spend Amount','Spend Amount','Spend Coin','Total Receive Amount','Receive Amount','Receive Coin','Rate','Admin Fee', 'Status', 'Date');
            fputcsv($file,$headers);

            $users = $this->SellExchange->find('all', [
                'conditions'=>$searchData,
                'contain'=>['user'=>['fields'=>['name','phone_number']],
                    'spendcryptocoin'=>['fields'=>['short_name']],
                    'getcryptocoin'=>['fields'=>['short_name']]],
                'order'=>[$order=>$search['order_value']],
            ]);
			$this->add_system_log(200, 0, 5, '판매 목록 CSV 다운로드 (이름, 전화번호 등)');

            $k = 1;
            foreach ($users as $k=>$data)
            {
                $username = mb_convert_encoding( htmlspecialchars($data['user']['name']), "EUC-KR", "UTF-8" );
                $phonenum = isset($data['user']['phone_number'])?$data['user']['phone_number']:'';
                $tbsa = isset($data['total_sell_spend_amount'])?$data['total_sell_spend_amount']:0;
                $bsa = isset($data['sell_spend_amount'])?$data['sell_spend_amount']:0;
                $short_name = isset($data['spendcryptocoin']['short_name'])?$data['spendcryptocoin']['short_name']:'';
                $bga = isset($data['sell_get_amount'])?$data['sell_get_amount']:0;
                $tbga = isset($data['total_sell_get_amount'])?$data['total_sell_get_amount']:0;
                $gshort_name = isset($data['getcryptocoin']['short_name'])?$data['getcryptocoin']['short_name']:'';
                $pprice = isset($data['per_price'])?$data['per_price']:0;
                $buyfee = isset($data['buy_fees'])?$data['buy_fees']:0;
                $status = isset($data['status'])?$data['status']:'';
                $created = $data['created_at'];

                $arr = [];
                $arr['#'] = $k;
                $arr['username'] =  $username;
                $arr['phonenum'] = $phonenum;
                $arr['tbsa'] =  number_format((float)$tbsa, 4);
                $arr['bsa'] =  number_format((float)$bsa, 4);
                $arr['short_name'] =  $short_name;
                $arr['bga'] =  number_format((float)$bga, 4);
                $arr['tbga'] =  number_format((float)$tbga, 4);
                $arr['gshort_name'] =  $gshort_name;
                $arr['pprice'] =  number_format((float)$pprice, 4);
                $arr['buyfee'] =  number_format((float)$buyfee, 4);
                $arr['status'] =  $status;
                $arr['created'] = $created;
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'SellExchange'.$filename
            ));
            return $this->response;die;
        }

      //  $order = ['SellExchange.status DESC, SellExchange.created_at DESC'];

        $collectdata = $this->Paginator->paginate($this->SellExchange, [
            'conditions'=>$searchData+$searchDataTotal,
            'contain'=>['user'=>['fields'=>['id','name','phone_number']],
                'spendcryptocoin'=>['fields'=>['short_name']],
                'getcryptocoin'=>['fields'=>['short_name']]],
            'order'=>[$order=>$search['order_value']],
            'limit' => $limit,
        ]);

        $this->set('listing',$collectdata);
        $this->set('type',$type);
    }

    public function transaction()
    {
		$start_date = date('Y-m-d', strtotime('-1 month'));
		$end_date = date('Y-m-d');
		if(empty($this->request->query['start_date']) && empty($this->request->query['end_date'])){
			return $this->redirect(['action'=>'transaction','start_date'=>$start_date,'end_date'=>$end_date]);
		}
		$this->request->session()->write('ex_transaction_export', 'fail');
		$this->set('title','Transaction');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Cryptocoin');
		$this->set('display_type','BTC');
		$limit = 20;
		$type = "BTC";
        $searchData = [];
		$this->set('serial_num',1);

        if($this->request->query('page')) { $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1)); }

        $coinList = $this->get_coin_list();
        $this->set('coinList',$coinList);

        if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
        $search = $this->request->query;

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if (!empty($search['coin_first_id'])) {
            $searchData['AND'][] = array('ExchangeHistory.spend_cryptocoin_id' => $search['coin_first_id']);
        }
        if (!empty($search['coin_second_id'])) {
            $searchData['AND'][] = array('ExchangeHistory.get_cryptocoin_id' => $search['coin_second_id']);
        }
        if (!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '')
            $searchData['AND'][] = array('DATE(ExchangeHistory.created_at) >= ' => $this->request->query['start_date'], 'DATE(ExchangeHistory.created_at) <= ' => $this->request->query['end_date']);
        else if (!empty($search['start_date']) && $search['start_date'] != '') $searchData['AND'][] = array('DATE(ExchangeHistory.created_at)' => $search['start_date']);
        else if (!empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] = array('DATE(ExchangeHistory.created_at)' => $search['end_date']);

        if(empty($search['order_value'])){
            $search['order_value'] = 'desc';
        }

		try {
			$collectdata = $this->Paginator->paginate($this->ExchangeHistory, [
                'conditions' => $searchData,
                'contain' => [/*'user'=>['fields'=>['username']],*/
                    'spendcryptocoin' => ['fields' => ['short_name']],
                    'getcryptocoin' => ['fields' => ['short_name']]],
                'order' => ['ExchangeHistory.created_at' => $search['order_value']],
                'limit' => $limit,
            ]);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$collectdata = $this->Paginator->paginate($this->ExchangeHistory, [
                'conditions' => $searchData,
                'contain' => [/*'user'=>['fields'=>['username']],*/
                    'spendcryptocoin' => ['fields' => ['short_name']],
                    'getcryptocoin' => ['fields' => ['short_name']]],
                'order' => ['ExchangeHistory.created_at' => $search['order_value']],
                'limit' => $limit,
            ]);
		}

		$this->set('listing',$collectdata);
		$this->set('type',$type);
		
	}

	/*
	public function buyList()
    {
		$this->set('title','Transaction');
		$this->loadModel('BuyExchange');
		$this->loadModel('Cryptocoin');
		$this->set('display_type','BTC');
		
		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
													 'valueField' => 'short_name'
												])->toArray();
		$this->set('coinList',$coinList);
		
		
		$limit = $this->setting['pagination'];
		$searchData = array();
		if($this->request->query('page')) {
			
			$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
		}
		else $this->set('serial_num',1);
		$type = "BTC";
		$searchData = array();
		
		//$searchData['AND'][] = array("Transactions.tx_type"=>'purchase'); 
		
		
		if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
			$search = $this->request->query;
			
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			
			// search by username
			if(!empty($search['username'])){
				$searchData['AND'][] = array("user.username like"=>"%".$search['username']."%");
                $userId = $this->Users->find('all',['conditions'=>['username'=>$search['username']]])->hydrate(false)->first();
                $totalBuyAmount = $this->Users->getUserTotalBuy($userId['id']);
                $this->set('totalBuyAmount',$totalBuyAmount);
			}
			
			if(!empty($search['email'])){
				$searchData['AND'][] = array("user.email like"=>"%".$search['email']."%");
			}
			
			// search by date range
			if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] = array('DATE(BuyExchange.created_at) >= ' => $this->request->query['start_date'],'DATE(BuyExchange.created_at) <= ' => $this->request->query['end_date']);
			else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.created_at)' => $search['start_date']);
			else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.created_at)' => $search['end_date']);
			
			// saarch by spend coin type
			if(!empty($search['spend_coin_id'])){
				$searchData['AND'][] = array("BuyExchange.buy_spend_coin_id"=>$search['spend_coin_id']);
			}
			
			// saarch by get coin type
			if(!empty($search['get_coin_id'])){
				$searchData['AND'][] = array("BuyExchange.buy_get_coin_id"=>$search['get_coin_id']);
			}
			
			// saarch by coin type
			if(!empty($search['status'])){
				$searchData['AND'][] = array("BuyExchange.status"=>$search['status']);
			}
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			ini_set('memory_limit','5000M');
				ini_set('max_execution_time', 9000);
			$limit = 10000;
			
			
			
			if($search['export'] !=''){
                // Export
				
                if($search['export']=='c') $filename = time().'buy_export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                 $headers = array('#', 'Username', 'Total Spend Amount','Spend Amount','Spend Coin','Total Receive Amount','Receive Amount','Receive Coin','Rate','Admin Fee', 'Status', 'Date');
                fputcsv($file,$headers);
				
                $collectdata = $this->Paginator->paginate($this->BuyExchange, [
						'conditions'=>$searchData,
						'contain'=>['user'=>['fields'=>['username']],
									'spendcryptocoin'=>['fields'=>['short_name']],
									'getcryptocoin'=>['fields'=>['short_name']]],
						'order'=>['BuyExchange.ids'=>'desc'],
						'limit' => $limit,
					]);
				
              
                 $k = 1;
                foreach ($collectdata as $k=>$data)
                {  
				    $username = isset($data['user']['username'])?$data['user']['username']: '';
				    $tbsa = isset($data['total_buy_spend_amount'])?$data['total_buy_spend_amount']:0;
				    $bsa = isset($data['buy_spend_amount'])?$data['buy_spend_amount']:0;
				    $short_name = isset($data['spendcryptocoin']['short_name'])?$data['spendcryptocoin']['short_name']:'';
					$bga = isset($data['buy_get_amount'])?$data['buy_get_amount']:0;
					$tbga = isset($data['total_buy_get_amount'])?$data['total_buy_get_amount']:0;
					$gshort_name = isset($data['getcryptocoin']['short_name'])?$data['getcryptocoin']['short_name']:'';           
					$pprice = isset($data['per_price'])?$data['per_price']:0;
					$buyfee = isset($data['buy_fees'])?$data['buy_fees']:0; 
					$status = isset($data['status'])?$data['status']:'';
				    $created = $data['created_at']; 
					 
					
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['username'] =  $username; 
                    $arr['tbsa'] =  number_format((float)$tbsa, 8); 
                    $arr['bsa'] =  number_format((float)$bsa, 8); 
                    $arr['short_name'] =  $short_name; 
					$arr['bga'] =  number_format((float)$bga, 8); 
					$arr['tbga'] =  number_format((float)$tbga, 8); 
					$arr['gshort_name'] =  $gshort_name;
					$arr['pprice'] =  number_format((float)$pprice, 8);
					$arr['buyfee'] =  number_format((float)$buyfee, 8); 
					$arr['status'] =  $status; 
                    $arr['created'] =  $created; 
                    fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'UserReport'.$filename
                ));
                return $this->response;die;
            }
			
		}
		
		$collectdata = $this->Paginator->paginate($this->BuyExchange, [
						'conditions'=>$searchData,
						'contain'=>['user'=>['fields'=>['username']],
									'spendcryptocoin'=>['fields'=>['short_name']],
									'getcryptocoin'=>['fields'=>['short_name']]],
						'order'=>['BuyExchange.status'=>'desc','BuyExchange.crated_at'=>'desc'],
						'limit' => $limit,
					]);
		
		$this->set('listing',$collectdata);
		$this->set('type',$type);
		
	}
	
	public function buyListSearch()
	{
		
		$this->loadModel('BuyExchange');
		if ($this->request->is('ajax')) 
		{ 
			$limit = $this->setting['pagination'];
			$searchData = array();
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$collectdata = $this->Paginator->paginate($this->BuyExchange, [
								'contain'=>['user'=>['fields'=>['username']],
											'spendcryptocoin'=>['fields'=>['short_name']],
											'getcryptocoin'=>['fields'=>['short_name']]],
								'conditions'=>$searchData,
								'order'=>['BuyExchange.id'=>'desc'],
								'limit' => $limit,
							]);
		
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		}
	
	}
	
	
	
	
	public function sellList()
    {
		
		$this->set('title','Transaction');
		$this->loadModel('SellExchange');
		$this->loadModel('Cryptocoin');
		$this->set('display_type','BTC');
		
		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
													 'valueField' => 'short_name'
												])->toArray();
		$this->set('coinList',$coinList);
		
		
		$limit = $this->setting['pagination'];
		$searchData = array();
		if($this->request->query('page')) { 
			$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
		}
		else $this->set('serial_num',1);
		$type = "BTC";
		$searchData = array();
		
		
		
		if(array_key_exists('key',$this->request->query)) parse_str($this->request->query['key'], $this->request->query);
			$search = $this->request->query;
			
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			
			// search by username
			if(!empty($search['username'])){
				$searchData['AND'][] = array("user.username like"=>"%".$search['username']."%");
                $userId = $this->Users->find('all',['conditions'=>['username'=>$search['username']]])->hydrate(false)->first();
                $totalSellAmount = $this->Users->getUserTotalSell($userId['id']);
                $this->set('totalSellAmount',$totalSellAmount);
			}
			
			if(!empty($search['email'])){
				$searchData['AND'][] = array("user.email like"=>"%".$search['email']."%");
			}
			
			// search by date range
			if(!empty($search['start_date']) && $search['start_date'] != '' && !empty($search['end_date']) && $search['end_date'] != '') $searchData['AND'][] = array('DATE(SellExchange.created_at) >= ' => $this->request->query['start_date'],'DATE(SellExchange.created_at) <= ' => $this->request->query['end_date']);
			else if(!empty($search['start_date']) && $search['start_date'] != '')	$searchData['AND'][] = array('DATE(SellExchange.created_at)' => $search['start_date']);
			else if(!empty($search['end_date']) && $search['end_date'] != '')	$searchData['AND'][] = array('DATE(SellExchange.created_at)' => $search['end_date']);
			
			// saarch by spend coin type
			if(!empty($search['spend_coin_id'])){
				$searchData['AND'][] = array("SellExchange.sell_spend_coin_id"=>$search['spend_coin_id']);
			}
			
			// saarch by get coin type
			if(!empty($search['get_coin_id'])){
				$searchData['AND'][] = array("SellExchange.sell_get_coin_id"=>$search['get_coin_id']);
			}
			
			// saarch by coin type
			if(!empty($search['status'])){
				$searchData['AND'][] = array("SellExchange.status"=>$search['status']);
			}
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			ini_set('memory_limit','5000M');
				ini_set('max_execution_time', 9000);
			$limit = 1000;
			/*if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			
			// search by username
			if(!empty($search['username'])){
				$searchData['AND'][] = array("user.username like"=>"%".$search['username']."%");
			}
			
			if(!empty($search['email'])){
				$searchData['AND'][] = array("user.email like"=>"%".$search['email']."%");
			}
			
			// search by date range
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(SellExchange.created_at) >= ' => $this->request->data['start_date'],'DATE(SellExchange.created_at) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(SellExchange.created_at)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(SellExchange.created_at)' => $search['end_date']);
			
			// saarch by spend coin type
			if(!empty($search['spend_coin_id'])){
				$searchData['AND'][] = array("SellExchange.sell_spend_coin_id"=>$search['spend_coin_id']);
			}
			
			// saarch by get coin type
			if(!empty($search['get_coin_id'])){
				$searchData['AND'][] = array("SellExchange.sell_get_coin_id"=>$search['get_coin_id']);
			}
			
			// saarch by coin type
			if(!empty($search['status'])){
				$searchData['AND'][] = array("SellExchange.status"=>$search['status']);
			}
			
			
			if($search['export'] !=''){
				
			
                // Export
                if($search['export']=='c') $filename = time().'sell_export.csv';
                else  $filename = 'export.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
				$headers = array('#', 'Username', 'Total Spend Amount','Spend Amount','Spend Coin','Total Receive Amount','Receive Amount','Receive Coin','Rate','Admin Fee', 'Status', 'Date');
                fputcsv($file,$headers);
                $users = $this->Paginator->paginate($this->SellExchange, [
						'conditions'=>$searchData,
						'contain'=>['user'=>['fields'=>['username']],
									'spendcryptocoin'=>['fields'=>['short_name']],
									'getcryptocoin'=>['fields'=>['short_name']]],
						'order'=>['SellExchange.id'=>'desc'],
						'limit' => $limit,
					]);
				

                 $k = 1;
				 //pr($users); die;
                foreach ($users as $k=>$data)
                {
					$username = $data['user']['username'];
				    $tbsa = number_format((float)$data['total_sell_spend_amount'],8);
				    $bsa = number_format((float)$data['sell_spend_amount'],8);
				    $short_name = $data['spendcryptocoin']['short_name'];
					$bga = number_format((float)$data['total_sell_get_amount'],8);
					$tbga = number_format((float)$data['sell_get_amount'],8);
					$gshort_name = $data['getcryptocoin']['short_name'];           
					$pprice = number_format((float)$data['per_price'],8);
					$buyfee = number_format((float)$data['sell_fees'],8); 
					$status = ucfirst($data['status']);
				    //$created = $data['created_at']->format('d M Y H:i:s'); 
				    $created =   date('d M Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($data['created_at']))); 
					
					
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['username'] =  $username; 
                    $arr['tbsa'] =  number_format((float)$tbsa, 8); 
                    $arr['bsa'] =  number_format((float)$bsa, 8); 
                    $arr['short_name'] =  $short_name; 
					$arr['bga'] =  number_format((float)$bga, 8); 
					$arr['tbga'] =  number_format((float)$tbga, 8); 
					$arr['gshort_name'] =  $gshort_name;
					$arr['pprice'] =  number_format((float)$pprice, 8);
					$arr['buyfee'] =  number_format((float)$buyfee, 8); 
					$arr['status'] =  $status; 
                    $arr['created'] =  $created; 
                    fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'UserReport'.time().$filename
                ));
                return $this->response; 
				die;
            }
			
		}
		
		$collectdata = $this->Paginator->paginate($this->SellExchange, [
						'conditions'=>$searchData,
						'contain'=>['user'=>['fields'=>['username']],
									'spendcryptocoin'=>['fields'=>['short_name']],
									'getcryptocoin'=>['fields'=>['short_name']]],
						'order'=>['SellExchange.status'=>'desc','SellExchange.created_at'=>'desc'],
						'limit' => $limit,
					]);
		
		$this->set('listing',$collectdata);
		$this->set('type',$type);
		
	}
	
	public function sellListSearch()
	{
		
		$this->loadModel('SellExchange');
		if ($this->request->is('ajax')) 
		{ 
			$limit = $this->setting['pagination'];
			$searchData = array();
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$collectdata = $this->Paginator->paginate($this->SellExchange, [
								'contain'=>['user'=>['fields'=>['username']],
											'spendcryptocoin'=>['fields'=>['short_name']],
											'getcryptocoin'=>['fields'=>['short_name']]],
								'conditions'=>$searchData,
								'order'=>['SellExchange.id'=>'desc'],
								'limit' => $limit,
							]);
		
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		}
	
	}
	


	public function transaction()
    {
		$this->set('title','Transaction');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Cryptocoin');
		$this->set('display_type','BTC');
		
		$coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
													 'valueField' => 'short_name'
												])->toArray();
		$this->set('coinList',$coinList);
		
		
		$limit = $this->setting['pagination'];
		$type = "BTC";
		$searchData = array();
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if(!empty($search['pagination'])) $limit =  $search['pagination'];
			
			
			
			// search by date range
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(ExchangeHistory.created_at) >= ' => $this->request->data['start_date'],'DATE(ExchangeHistory.created_at) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(ExchangeHistory.created_at)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(ExchangeHistory.created_at)' => $search['end_date']);
			
			
			
			
		}
		
		$collectdata = $this->Paginator->paginate($this->ExchangeHistory, [
						'conditions'=>$searchData,
						 'contain'=>['user'=>['fields'=>['username']],
									'spendcryptocoin'=>['fields'=>['short_name']],
									'getcryptocoin'=>['fields'=>['short_name']]], 
						'order'=>['ExchangeHistory.id'=>'desc'],
						'limit' => $limit,
					]);
		
		$this->set('listing',$collectdata);
		$this->set('type',$type);
		
	}
	
	public function transactionSearch()
	{
		
		$this->loadModel('ExchangeHistory');
		if ($this->request->is('ajax')) 
		{ 
			$limit = $this->setting['pagination'];
			$searchData = array();
			if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
			else $this->set('serial_num',1);
			
			$collectdata = $this->Paginator->paginate($this->ExchangeHistory, [
								'contain'=>[ 'user'=>['fields'=>['username']],
											'spendcryptocoin'=>['fields'=>['short_name']],
											'getcryptocoin'=>['fields'=>['short_name']]],
								'conditions'=>$searchData,
								'order'=>['ExchangeHistory.id'=>'desc'],
								'limit' => $limit,
							]);
		
			$this->set('listing',$collectdata);
			$this->set('type',$type);
		}
	
	}
   
   */
   
      
    public function volume($coinId)
    {
        $this->set('title' , 'Users');
		$searchData = array();
		$startDate = date('Y-m-d');
		$endDate = date('Y-m-d');
		$limit =  $this->setting['pagination'];
		$searchData['AND'][] =['Users.user_type'=>'U'];
		//$searchData['AND'][] =['Users.id'=>10003992];
		if ($this->request->is(['post' ,'put']) ) 
		{
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['pagination'] != '') $limit =  $search['pagination'];
			if($search['email'] != '') $searchData['AND'][] =array('Users.email LIKE' => '%'.$search['email'].'%');
			if($search['username'] != '') $searchData['AND'][] =array('Users.username LIKE' => '%'.$search['username'].'%');
			if($search['start_date'] != '' && $search['end_date'] != '') {
				
				$startDate = $this->request->data['start_date'];
				$endDate = $this->request->data['end_date'];
			}
			else if($search['start_date'] != '') {	
				$startDate = $this->request->data['start_date'];
			}
			else if($search['end_date'] != '') {	
				$endDate = $this->request->data['end_date'];
			}
			
			
            if($search['export'] !=''){
                // Export
                if($search['export']=='c') $filename = time().'vl.csv';
                else  $filename = time().'vl.xlsx';
                $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                $headers = array('#','Username','Email','Buy Volume','Sell Volume','Total Volume','Start Date','End Date');
                fputcsv($file,$headers);
                $users =  $this->Users->find('all',[
										'fields'=>['username','email','id'],
										'contain'=>['buyvolume'=>['conditions'=>['status'=>'completed',
																				'DATE(created_at) >='=>$startDate,
																				'DATE(created_at) <='=>$endDate, 
																				'buy_spend_coin_id'=>2,
																				'buy_get_coin_id'=>$coinId,
																				],
																	//'fields'=>['buySum'=>'SUM(total_buy_spend_amount)','buyer_user_id']],
																	'fields'=>['total_buy_spend_amount','buyer_user_id']],
													'sellvolume'=>['conditions'=>['sell_spend_coin_id'=>$coinId,
																				'sell_get_coin_id'=>2,
																				'DATE(created_at) >='=>$startDate,
																				'DATE(created_at) <='=>$endDate, 
																				'status'=>'completed'],
																	//'fields'=>['sellSum'=>'SUM(total_sell_get_amount)','seller_user_id']]
																	'fields'=>['total_sell_get_amount','seller_user_id']]
													],
										'conditions' => $searchData,
										//'limit' => $limit,
										'order'=>['id'=>'desc']

									]);
				

                $k = 1;
                foreach ($users as $k=>$data)
                {
					$sellvolume = 0;
					if(!empty($data['sellvolume'])){
						foreach($data['sellvolume'] as $sellSingle){
							$sellvolume = $sellvolume + $sellSingle['total_sell_get_amount'];
						}
					}
					
					$buyvolume = 0;
					if(!empty($data['buyvolume'])){
						foreach($data['buyvolume'] as $buySingle){
							$buyvolume = $buyvolume + $buySingle['total_buy_spend_amount'];
						}
					}
					
					
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Username'] = $data['username'];
                    $arr['Email'] = $data['email'];
                    $arr['Buy Volume'] = $buyvolume;
                    $arr['Sell Volume'] = $sellvolume;
					$arr['Total Volume'] = $buyvolume+$sellvolume;
                    $arr['Start Date'] = $startDate;
                    $arr['End Date'] = $endDate;;
                    fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
                $this->response->file("uploads/".$filename, array(
                    'download' => true,
                    'name' => 'VolumeReport'.$filename
                ));
                return $this->response;die;
            }
		}
		
		$getUsers = $this->Paginator->paginate($this->Users, [
            'fields'=>['username','email','id'],
            'contain'=>['buyvolume'=>['conditions'=>['status'=>'completed',
													'DATE(created_at) >='=>$startDate,
													'DATE(created_at) <='=>$endDate, 
													'buy_spend_coin_id'=>2,
													'buy_get_coin_id'=>$coinId,
													],
										//'fields'=>['buySum'=>'SUM(total_buy_spend_amount)','buyer_user_id']],
										'fields'=>['total_buy_spend_amount','buyer_user_id']],
						'sellvolume'=>['conditions'=>['sell_spend_coin_id'=>$coinId,
													'sell_get_coin_id'=>2,
													'DATE(created_at) >='=>$startDate,
													'DATE(created_at) <='=>$endDate, 
													'status'=>'completed'],
										//'fields'=>['sellSum'=>'SUM(total_sell_get_amount)','seller_user_id']]
										'fields'=>['total_sell_get_amount','seller_user_id']]
						],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['id'=>'desc']

        ]);
		//print_r($getUsers); die;
		$this->set('users',$getUsers );
		$this->set('startDate',$startDate );
		$this->set('endDate',$endDate);
		$this->set('coinId',$coinId);
    }
    
	public function volumeSearch($coinId)
	{
		$startDate = date('Y-m-d');
		$endDate = date('Y-m-d');
		if ($this->request->is('ajax')) {
			$searchData = array();
			$limit =  $this->setting['pagination'];
			$searchData['AND'][] =['Users.user_type'=>'U'];
			parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			
			if($this->request->query('page')) { 
				$this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
			}
			else {$this->set('serial_num',1);}
			
			$getUsers = $this->Paginator->paginate($this->Users, [
				'fields'=>['username','email','id'],
				'contain'=>['buyvolume'=>['conditions'=>['status'=>'completed',
														'DATE(created_at) >='=>$startDate,
														'DATE(created_at) <='=>$endDate, 
														'buy_spend_coin_id'=>2,
														'buy_get_coin_id'=>$coinId,
														],
											'fields'=>['total_buy_spend_amount','buyer_user_id']],
							'sellvolume'=>['conditions'=>['sell_spend_coin_id'=>$coinId,
														'sell_get_coin_id'=>2,
														'DATE(created_at) >='=>$startDate,
														'DATE(created_at) <='=>$endDate, 
														'status'=>'completed'],
											'fields'=>['total_sell_get_amount','seller_user_id']]
							],
				'conditions' => $searchData,
				'limit' => $limit,
				'order'=>['id'=>'desc']

			]);
			//print_r($getUsers); die;
			$this->set('users',$getUsers );
			$this->set('startDate',$startDate );
			$this->set('endDate',$endDate );
			$this->set('coinId',$coinId);
			
		}
	}

	/* 거래 pending 취소 */
	public function deleteMyOrder(){
		$returnArr = ['success'=>'false','message'=>'잘못된 요청입니다'];
		if ($this->request->is('ajax')) {
			$tableId = $this->request->data('tableId');
			$tableType = $this->request->data('tableType');
			$userId = $this->request->data('userId');
			$returnArr = array();
			if(empty($tableId ) || empty($tableType)|| empty($userId)){
				$returnArr = ['success'=>'false','message'=>'필수 값이 누락되었습니다.'];
				echo json_encode($returnArr);
				die;
			}
			$this->loadModel('Transactions');
			$update_at = date('Y-m-d H:i:s');
			$returnArr = ['success'=>'false','message'=>'타입 오류'];

			if($tableType=="buy") {	
				$adminFeePercent = $this->Users->getAdninFee("buy_sell_fee");
				$this->loadModel('BuyExchange');
				$query = $this->BuyExchange->find('all',['conditions'=>['buyer_user_id'=>$userId,'id'=>$tableId,'status'=>'pending']])->hydrate(false)->first();
				if(!empty($query)){
					$buyUpdate = $this->BuyExchange->get($tableId);
					$buyUpdate = $this->BuyExchange->patchEntity($buyUpdate,['status'=>'deleted','update_at'=>$update_at]);
					$buyUpdate = $this->BuyExchange->save($buyUpdate);
					
					$transactionsUpdate = $this->Transactions->find('all',["conditions"=>["exchange_id"=>$tableId,"tx_type"=>'buy_exchange',"remark"=>"reserve for exchange"]])->first();
					if(!empty($transactionsUpdate)){
						$getTxId = $transactionsUpdate['id'];
						$result = $this->Transactions->delete($transactionsUpdate);
						// delete admin fees 
						$transactionsFees = $this->Transactions->find('all',["conditions"=>["exchange_id"=>$tableId,"transaction_id"=>$getTxId,"tx_type"=>'buy_exchange',"remark"=>"adminFees"]])->first();
						if(!empty($transactionsFees)){
							$result = $this->Transactions->delete($transactionsFees);
						}		
						
					}													 
					$returnArr = ['success'=>'true','message'=>$update_at];
					echo json_encode($returnArr);
					die;
				}
				$returnArr = ['success'=>'false','message'=>'취소 가능한 주문이 없습니다'];
			}
			if($tableType=="sell") {
				$this->loadModel('SellExchange');
				$query = $this->SellExchange->find('all',['conditions'=>['seller_user_id'=>$userId,'id'=>$tableId,'status'=>'pending']])->hydrate(false)->first();
				if(!empty($query)){
					$sellUpdate = $this->SellExchange->get($tableId);
					$sellUpdate = $this->SellExchange->patchEntity($sellUpdate,['status'=>'deleted','update_at'=>$update_at]);
					$sellUpdate = $this->SellExchange->save($sellUpdate);
					
					$transactionsUpdate = $this->Transactions->find('all',["conditions"=>["exchange_id"=>$tableId,"tx_type"=>'sell_exchange',"remark"=>"reserve for exchange"]])->first();
					if(!empty($transactionsUpdate)){
						$getTxId = $transactionsUpdate['id'];
						$result = $this->Transactions->delete($transactionsUpdate);
					}						
					$returnArr = ['success'=>'true','message'=>$update_at];
					echo json_encode($returnArr);
					die;
				}
				$returnArr = ['success'=>'false','message'=>'취소 가능한 주문이 없습니다'];
			}
		}
		echo json_encode($returnArr);
		die; 
	}
	
	 
}
