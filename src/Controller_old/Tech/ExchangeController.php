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
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager; 
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
//use Google\Authenticator\GoogleAuthenticator;


class ExchangeController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		// Allow users to register and logout.
		// You should not add the "login" action to allow list. Doing so would
		// cause problems with normal functioning of AuthComponent.
        //$this->Auth->allow(['signup', 'logout','frontLogin','frontRegister','verify','forgotPassword','successregister']);
    }

	
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
		$type = "BTC";
		$searchData = array();
		
		//$searchData['AND'][] = array("Transactions.tx_type"=>'purchase'); 
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			ini_set('memory_limit','5000M');
				ini_set('max_execution_time', 9000);
			$limit = 10000;
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
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
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(BuyExchange.created_at) >= ' => $this->request->data['start_date'],'DATE(BuyExchange.created_at) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.created_at)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(BuyExchange.created_at)' => $search['end_date']);
			
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
						'order'=>['BuyExchange.ids'=>'desc'],
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
		$type = "BTC";
		$searchData = array();
		
		
		if ($this->request->is(['post' ,'put']) ) 
		{
			ini_set('memory_limit','5000M');
				ini_set('max_execution_time', 9000);
			$limit = 1000;
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
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
						'order'=>['SellExchange.id'=>'desc'],
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
						 'contain'=>[/*'user'=>['fields'=>['username']],*/
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
								'contain'=>[/* 'user'=>['fields'=>['username']], */
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
	
	 
}
