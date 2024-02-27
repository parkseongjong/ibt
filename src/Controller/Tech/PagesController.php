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
	
	
	public function dashboardtest(){ 
		$this->set('title' , $this->coin.' : Dashboard');
		
	}
	
	public function dashboardtotalajax(){ 
		$this->set('title' , $this->coin.' : Dashboard');
		
		if ($this->request->is('ajax')) {
			$this->loadModel('Cryptocoin');
			$this->loadModel('Coinpair');
			$this->loadModel('ExchangeHistory');
			$this->loadModel('Transactions');
			$this->loadModel('PrincipalWallet');
			$returnArr = [];
			// get total Users 
			$totalUsers = $this->Users->find('all')->hydrate(false)->count();
            $returnArr["total_users"]=$totalUsers;

            $totallavel = $this->user->find('all',['user_lavel'=>'1'])->hydrate(false)->count();
            $returnArr['total_lavel']=$totallavel;


			// get total Coins 
			$totalCoins = $this->Cryptocoin->find('all')->hydrate(false)->count();
			$returnArr["total_coins"]=$totalCoins;
			
			// get total Coinpair 
			$totalCoinPairs = $this->Coinpair->find('all')->hydrate(false)->count();
			$returnArr["total_coin_pairs"]=$totalCoinPairs;
			
			// total Exchange 
			$totalExchange = $this->ExchangeHistory->find('all')->hydrate(false)->count();
			$returnArr["total_exchange"]=$totalExchange;
			echo json_encode($returnArr); die;
		}
	}
	
	public function dashboardajax(){ 
		$this->set('title' , $this->coin.' : Dashboard');
		
		if ($this->request->is('ajax')) {
			$this->loadModel('Cryptocoin');
			$this->loadModel('Coinpair');
			$this->loadModel('ExchangeHistory');
			$this->loadModel('Transactions');
			$this->loadModel('PrincipalWallet');
		
			$allCoins = $this->Cryptocoin
									->find('list', [
										'keyField' => 'id',
										'valueField' => 'short_name',
										"conditions"=>["status"=>1]
									])
									->toArray();
			
			$this->set('allCoins',$allCoins);
			
			$getGroupPrincipalWalletData = $this->PrincipalWallet->find('all',['fields'=>["coin_amt_sum"=>'sum(PrincipalWallet.amount)','coin_name'=>'cryptocoin.short_name','coin_id'=>'cryptocoin.id'],
															'contain'=>['cryptocoin'],
															'conditions'=>['PrincipalWallet.cryptocoin_id IN '=>array_keys($allCoins),
																		   'PrincipalWallet.status'=>'completed',
																		   'cryptocoin.status'=>1
																		   ],
															'group'=>['PrincipalWallet.cryptocoin_id']])
																		   ->hydrate(false)
																		   ->toArray();
			
			$this->set('getGroupPrincipalWalletData',$getGroupPrincipalWalletData);
			
			$getGroupPrincipalWalletDataWithKey = [];
			foreach($getGroupPrincipalWalletData as $getGroupPrincipalWalletDataSingle){
				$getGroupPrincipalWalletDataWithKey[$getGroupPrincipalWalletDataSingle['coin_id']] = $getGroupPrincipalWalletDataSingle;
			}
			
			$getGroupTradingWalletData = $this->Transactions->find('all',['fields'=>["coin_amt_sum"=>'sum(Transactions.coin_amount)','coin_name'=>'cryptocoin.short_name','coin_id'=>'cryptocoin.id'],
															'contain'=>['cryptocoin'],
															'conditions'=>['Transactions.cryptocoin_id IN '=>array_keys($allCoins),
																		   'Transactions.status'=>'completed',
																		   'Transactions.tx_type !='=>'bank_initial_deposit',
																		   'cryptocoin.status'=>1
																		   ],
															'group'=>['Transactions.cryptocoin_id']])
																		   ->hydrate(false)
																		   ->toArray();
																		   
			$this->set('getGroupTradingWalletData',$getGroupTradingWalletData);
			$getGroupTradingWalletDataWithKey = [];
			foreach($getGroupTradingWalletData as $getGroupTradingWalletDataSingle){
				$getGroupTradingWalletDataWithKey[$getGroupTradingWalletDataSingle['coin_id']] = $getGroupTradingWalletDataSingle;
			}

			
			 $showCoinTotal = [];
			foreach($allCoins as $coinId=>$coinShortName){
				
				
				$principalAmtSum = (!empty($getGroupPrincipalWalletDataWithKey[$coinId])) ? $getGroupPrincipalWalletDataWithKey[$coinId]['coin_amt_sum'] : 0;
				$tradingAmtSum = (!empty($getGroupTradingWalletDataWithKey[$coinId])) ?  $getGroupTradingWalletDataWithKey[$coinId]['coin_amt_sum'] : 0 ;
				$conTotalSum = $principalAmtSum + $tradingAmtSum ;
				$showCoinTotal[$coinId] = ["id"=>$coinId,"short_name"=>$coinShortName,"total_sum"=>$conTotalSum];
			} 
			
			echo json_encode($showCoinTotal); die;
			
		}
	}
	
	public function dashboard(){ 
		$this->set('title' , $this->coin.' : Dashboard');
		
		$this->loadModel('Cryptocoin');
		$this->loadModel('Coinpair');
		$this->loadModel('ExchangeHistory');
		$this->loadModel('Transactions');
		$this->loadModel('DepositApplicationList');
		$this->loadModel('DepositApplicationWallet');
		// get total Users 
		$totalUsers = $this->Users->find('all')->hydrate(false)->count();
		$this->set('totalUsers',$totalUsers);
		
		// get total Coins 
		$totalCoins = $this->Cryptocoin->find('all')->hydrate(false)->count();
		$this->set('totalCoins',$totalCoins);

/*        $query = $this->Users->find();

        $users = 	$query->select([
            'count' => '*'
        ])
            ->where(['user_level' => 2,])->hydrate(false)->count();

        print_r($users);
        exit;*/

        //$totallevel = $this->user->find()->select(['user_level'])->where(['user_level LIKE'=>'1'])->hydrate(false)->count();

        //$totallavel = $this->user->find('all',['user_level = '=>'1'])->hydrate(false)->count();
        //$this->set('totallevel',$totallevel);

        // get total Coinpair
        $totalCoinPairs = $this->Coinpair->find('all')->hydrate(false)->count();
        $this->set('totalCoinPairs',$totalCoinPairs);

        // total Exchange
        $totalExchange = $this->ExchangeHistory->find('all')->hydrate(false)->count();
		$this->set('totalExchange',$totalExchange);
		
		$allCoins = $this->Cryptocoin->find('list', ['keyField' => 'id','valueField' => 'short_name',"conditions"=>["status"=>1]])->toArray();
		
		$this->set('allCoins',$allCoins);
		
		$totalInvestments = $this->DepositApplicationList->find("all",["fields"=>["total_invest_deposit"=>"SUM(quantity)"],'conditions'=>['status !='=>'C']])->first();
		$this->set('totalInvestmentsShow',$totalInvestments["total_invest_deposit"]);

		$totalInvestmentProfits = $this->DepositApplicationWallet->find('all',['fields'=>['total_invest_profit'=>'SUM(amount)']])->hydrate(false)->first();
		$this->set('totalInvestmentsProfitsShow', $totalInvestmentProfits["total_invest_profit"]);


		
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

	/* 마스킹 해제 */
	public function unmasking(){
		$return_value = '';
		if ($this->request->is('ajax')) {
			$this->loadModel('Users');
			$id = $this->request->data('id');
			$type = $this->request->data('type');
			$select_value = '';
			switch($type){
				case 'P': $select_value = 'phone_number'; break;
				case 'N': $select_value = 'name'; break;
				case 'B': $select_value = 'account_number'; break;
				case 'E': $select_value = 'email'; break;
				case 'NB': $select_value = 'user_account_number'; break;// userauthreq
				case 'NN': $select_value = 'user_name';  break;
				case 'NP': $select_value = 'user_phone_number'; break;
				case 'NE': $select_value = 'user_email'; break;
				case 'LP': $select_value = 'phone_number'; break; // leaving
				case 'LN': $select_value = 'name'; break; 
				case 'LE': $select_value = 'email'; break;
				case 'LB': $select_value = 'account_number'; break;
				case 'DP': $select_value = 'phone_number'; break; // dormant
				case 'DN': $select_value = 'name'; break;
				case 'DE': $select_value = 'email'; break;
				case 'DB': $select_value = 'account_number'; break;
				default: $select_value = 'name'; break;
			}
			$value = $this->get_user_info($type,$select_value,$id);
			$return_value = $value->$type;
			if($type == 'B' || $type == 'NB'){
				$return_value = $this->Decrypt($return_value);
			}
			echo json_encode($return_value);
		}
		die;
	}
	public function get_user_info($type,$select_value,$id){
		if($type == 'P' || $type == 'N' || $type == 'B' || $type == 'E'){
			$this->loadModel('Users');
			$value = $this->Users->find()->select([$type=>$select_value])->where(['id'=>$id])->first();
			$this->add_system_log(200, $id, 1, $select_value.' 마스킹 해제');
		} else if($type == 'NP' || $type == 'NN' || $type == 'NB' || $type == 'NE'){
			$this->loadModel('ChangeAuth');
			$value = $this->ChangeAuth->find()->select([$type=>$select_value,'user_id'])->where(['id'=>$id])->first();
			$this->add_system_log(200, $value->user_id, 1, $select_value.' 인증 변경 요청 마스킹 해제');
		} else if($type == 'DP' || $type == 'DN' || $type == 'DB' || $type == 'DE'){
			$this->loadModel('DormantUsers');
			$value = $this->DormantUsers->find()->select([$type=>$select_value,'user_id'])->where(['user_id'=>$id])->first();
			$this->add_system_log(200, $value->user_id, 1, $select_value.' 휴면계정 마스킹 해제');
		} else if($type == 'LP' || $type == 'LN' || $type == 'LB' || $type == 'LE'){
			$this->loadModel('LeavingUsers');
			$value = $this->LeavingUsers->find()->select([$type=>$select_value,'id'])->where(['id'=>$id])->first();
			$this->add_system_log(200, $value->id, 1, $select_value.' 탈퇴 회원 마스킹 해제');
		}
		return $value;
	}
	/* 관리자 회원 검색 */
	public function getuserinfo(){
		$this->loadModel('Users');
		if ($this->request->is('ajax')) {
			$search_value = $this->request->data('q');
			if(is_numeric($this->request->data('q'))){
				$search_list = $this->Users->find()->select(['id','name'=>'CONCAT(phone_number, "-", name)'])->where(['phone_number'=>$search_value])->all();
			} else {
				$search_list = $this->Users->find()->select(['id','name'=>'CONCAT(phone_number, "-", name)'])->where(['name'=>$search_value])->all();
			}
		}
		echo json_encode($search_list);
		die;
	}
	/* 회원 아이디로 전화번호-이름 가져오기 */
	public function getuserinfobyid(){
		$this->loadModel('Users');
		if ($this->request->is('ajax')) {
			$user = $this->Users->find()->select(['id','name'=>'CONCAT(phone_number, "-", name)','email'])->where(['id'=>$this->request->data('user_id')])->first();
		}
		echo json_encode($user);
		die;
	}
	/* email 검색 미리보기 */
	public function getuseremail(){
		$this->loadModel('Users');
		if ($this->request->is('ajax')) {
			$search_value = $this->request->data('q');
			$search_list = $this->Users->find()->select(['id','email'])->where(['email'=>$search_value])->all();
		}
		echo json_encode($search_list);
		die;
	}
	/* 리스트 번호 가져오기 */
	public function getlistnumber(){
		$list = '';
		if ($this->request->is('ajax')) {
			$search_value = $this->request->data('q').'%';
			$type = $this->request->data('type');
			if($type == 'depositlist'){
				$this->loadModel('PrincipalWallet');
				$list = $this->PrincipalWallet->find()->select(['id'])->where(['id Like' => $search_value,'type'=>'bank_initial_deposit'],['id'=>'string'])->all();
			} else if($type == 'withdrawallist'){
				$this->loadModel('PrincipalWallet');
				$list = $this->PrincipalWallet->find()->select(['id'])->where(['id Like' => $search_value,'type'=>'bank_initial_withdraw'],['id'=>'string'])->all();
			} else if ($type == 'couponslist'){
				$this->loadModel('Transactions');
				$list = $this->Transactions->find()->select(['id'])->where(['id Like' => $search_value,'tx_type'=>'bought_coupon'],['id'=>'string'])->all();
			} else if($type == 'admincouponslist'){
				$this->loadModel('PrincipalWallet');
				$list = $this->PrincipalWallet->find()->select(['id'])->where(['id Like' => $search_value,'type'=>'deducted_coupon_krw'],['id'=>'string'])->all();
			} else if($type == 'userauthreq'){
				$this->loadModel('ChangeAuth');
				$list = $this->ChangeAuth->find()->select(['id'])->where(['id Like' => $search_value],['id'=>'string'])->all();
			}
		}
		echo json_encode($list);
		die;
	}
	/* 관리자 권한 비교 */
	public function getpermissionlevel(){
		if ($this->request->is('ajax')) {
			$level_type = $this->request->data('type');
			$status = 'fail';
			if($level_type == 'download'){
				$auth_level = $this->Auth->user('level_id');
				if($auth_level <= 2){
					$status = 'success';
				}
			}
			echo $status;
		}
		die;
	}
}
