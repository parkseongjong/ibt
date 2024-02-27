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

	class PagesController extends AppController
	{
		public $coin = "HC";
		public function index()
		{
			$this->dashboard();	
		}
		public function forbidden(){
			if($this->request->referer()!='/') $this->request->session()->write('Config.referer', $this->request->referer());
			$this->set('title' , 'GalaxyIco!: Access forbidden');
			
		}
		
		public function dashboard(){ 
				$this->set('title' , $this->coin.' : Dashboard');
				$this->loadModel('Cointransactions');
				$this->loadModel('Token');
				$this->loadModel('Agctransactions');
				$this->loadModel('Users');
				$getWalletBalance = $this->Users->walletBalance();
				// my custom code
				$tokenWalletAddress = $this->Auth->user('token_wallet_address');
				$this->set('tokenWalletAddress',$tokenWalletAddress);
				$totalAMXCoin = $this->Token->find('all')->hydrate(false)->first();
				$this->set('totalAMXCoin',$totalAMXCoin);
				$totalAgc =  $totalAMXCoin['total_token'];
				$this->set('totalAgc',$totalAgc);
				
				//$totalSoldCoin = $this->Agctransactions->find('all', array('fields' => array('sum' =>'sum(agc_coins)'), 'conditions'=>array('status IN'=>['completed'])))->hydrate(false)->first();
				$totalSoldCoin = $this->Users->find('all', array('fields' => array('sum' =>'sum(total_agc)'), 'conditions'=>array('user_type'=>'U')))->hydrate(false)->first();
				$totalAgcSoldCoin = $totalSoldCoin['sum'];
				$this->set('totalAgcSoldCoin',$totalAgcSoldCoin);
				 
				$availabelAgcCoin = $totalAgc - $totalAgcSoldCoin;		
				$this->set('availabelAgcCoin',$availabelAgcCoin);
				
				
				$totalCollectBtcCoin = $this->Agctransactions->find('all', array('fields' => array('sum' =>'sum(btc_coins)'), 'conditions'=>array('status'=>'completed','trans_type'=>'credit')))->hydrate(false)->first(); 
				$totalCollectBtcCoin = $totalCollectBtcCoin['sum'];
				//$totalCollectBtcCoin = $getWalletBalance['btcBalance'];
				$this->set('totalCollectBtcCoin',$totalCollectBtcCoin);
				
				
				$lastCompletedCoin = $this->Agctransactions->find('all',['contain'=>['user'=>['fields'=>['name','unique_id']]],'conditions'=>['status'=>'completed'],'limit'=>5,'order'=>['Agctransactions.id'=>'desc']])->hydrate(false)->toArray();
				$lastPendingCoin = $this->Agctransactions->find('all',['contain'=>['user'=>['fields'=>['name','unique_id']]],'conditions'=>['status'=>'pending'],'limit'=>5,'order'=>['Agctransactions.id'=>'desc']])->hydrate(false)->toArray();
				
				$this->set('lastCompletedCoin',$lastCompletedCoin);
				$this->set('lastPendingCoin',$lastPendingCoin);
				
				//Galaxy
			    $query = $this->ConversionRates->find();
				$total_galaxy_arr = $query->select(['sum' => $query->func()->sum('ConversionRates.total_coins')])->hydrate(false)->first();
                $total_galaxy = 0;
				if(!empty($total_galaxy_arr) && $total_galaxy_arr['sum']!='')
                {
                    $total_galaxy =   $total_galaxy_arr['sum'];
                }
                
				$this->set('totalGalaxyCoins',$total_galaxy);
				 
				           


             	//  total sold
                $query = $this->ConversionRates->find();
				$total_sold_arr = $query->select(['sum' => $query->func()->sum('ConversionRates.total_coins'),'left_coins' => $query->func()->sum('ConversionRates.left_coins')])->hydrate(false)->first();
                $total_sold = 0;
				if(!empty($total_sold_arr))
                {
                    $total_sold =   $total_sold_arr['sum']-$total_sold_arr['left_coins'];
                }
				 
                $this->set('total_sold',($total_sold==''?0:$total_sold));


          
            //get current date logged in users
            $this->loadModel('LoginLogs');
            $logs = $this->LoginLogs->find();
            $create_date = $logs->func()->date_format([
                'LoginLogs.created' => 'literal',
                "'%h:%i %p'" => 'literal'
            ]);

            $log_records = $this->LoginLogs->find('all',['fields'=>['date'=>$create_date,'user_id','ip_address','user.name'],
																	'contain'=>['user'],
																	'conditions'=>['date(LoginLogs.created)'=>date('Y-m-d')],
																	//'group'=>'LoginLogs.user_id',
																	'limit'=>5,
																	'order'=>['LoginLogs.id'=>'desc']])
											->hydrate(false)->toArray();

         
          
            $this->set(array('log_records'=>$log_records));
				
			$getUserTotalCoin = $this->Cointransactions->find(); 
			$getUserTotalCoinCnt = $getUserTotalCoin
										->select(['sum' => $getUserTotalCoin->func()->sum('coin')])
										->where(array('type'=>'purchase'))
										->toArray();
			
			$getCompletedAgcCoinCount = $getUserTotalCoinCnt[0]['sum'];
			$this->set('getCompletedAgcCoinCount',$getCompletedAgcCoinCount);

			// get balance
			$getBalanceOfRealToken = $this->Users->getCoinBalance($tokenWalletAddress);
			$this->set('getBalanceOfRealToken',$getBalanceOfRealToken);
			
		}
		
		public function add()
		{
			$this->set('title' , 'GalaxyIco!: Add Cms Page');
			$Pages = $this->Pages->newEntity();
			
			if ($this->request->is(['post' ,'put'])) {
				
				$CardTypes = $this->Pages->patchEntity($Pages, $this->request->data);
				
				if ($this->Pages->save($Pages)) {
					$this->Flash->success(__('Cms page has been saved.'));
					return $this->redirect(['controller'=>'Pages','action' => 'add']);
				}else{
					$this->Flash->error(__('Some Errors Occurred.'));
				}
			}
			$this->set('Pages', $Pages);
		}
		
		public function faq(){
			
			$this->set('title' , 'GalaxyIco!: FAQ');
			$this->set('cmsDetails',$this->Pages->get(1));
			
			if ($this->request->is(['post' ,'put'])) {
				$Pages  = $this->Pages->get(1);
				$Pages = $this->Pages->patchEntity($Pages, $this->request->data);
				
				if ($this->Pages->save($Pages)) {
					$this->Flash->success(__('Faq has been saved.'));
					return $this->redirect(['controller'=>'Pages','action' => 'faq']);
				}else{
					$this->Flash->error(__('Some Errors Occurred.'));
				}
			}
		
		}
		public function manage(){
			/* pr($this->Pages->get(1)->toArray());
			echo json_encode($this->Pages->get(1)->toArray(), JSON_HEX_QUOT | JSON_HEX_TAG);	die; */		
			$this->set('title' , 'GalaxyIco!: Cms Pages');
			$this->set('Pages', $this->Pages->newEntity($this->request->data));
			$this->set('cmsPages',$this->Pages->find('list',array('keyField'=>'id' , 'valueField'=> 'name'))->toArray());
			
			if ($this->request->is(['post' ,'put'])) {
				unset($this->request->data['name']);
				$Pages  = $this->Pages->get($this->request->data['id']);
				$Pages = $this->Pages->patchEntity($Pages, $this->request->data);
				
				if ($this->Pages->save($Pages)) {
					$this->Flash->success(__('Cms page has been saved.'));
					return $this->redirect(['controller'=>'Pages','action' => 'manage']);
				}else{
					$this->Flash->error(__('Some Errors Occurred.'));
				}
			}
		
		}
		
		public function search(){
			if ($this->request->is('ajax')) {
					if(isset($this->request->data['cms_id'])){
						
						 $this->set('cmsDetails',$this->Pages->get($this->request->data['cms_id']));
					}
			}
		}
		
		
		public function chart()
		{
			
			if ($this->request->is('ajax')) {
					if(isset($this->request->data['mode'])){
						
						$to_date = date('Y-m-d', strtotime($this->request->data['to']));
						$from_date = date('Y-m-d', strtotime($this->request->data['from']));
						$this->loadModel('Users');
						$query = $this->Users->find();
						$charts = $ven = $usr = $tv =   array();
						$users = 	$query->select([
								'count' => $query->func()->count('id'),
								'published_date' => 'DATE(created)'
							])
							->where(['access_level_id' => 2,'is_deleted' => 'N','DATE(created) >=' => $to_date,'DATE(created) <=' =>  $from_date,/* function ($exp, $q) {
								
									return $exp->between('created', date('Y-m-d H:i:s', strtotime($this->request->data['to'])), date('Y-m-d H:i:s', strtotime($this->request->data['from'])));
								} */])
							->group('published_date')->hydrate(false)->toArray();
							//pr($users);die;
						if($users){
								foreach($users as $value){
										$a = array();
										$a[0]  = strtotime($value['published_date'])*1000;
										$a[1]  = $value['count'];
										
										$usr[] = $a;
								}
						} 
						
						$charts['Users'] = $usr;
						
						
						echo json_encode($charts);die;
					}
			}
		}

	  
	}
