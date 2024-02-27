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

class SettingsController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
	public function search(){
		if ($this->request->is('ajax')) {
				if(isset($this->request->data['cms_id'])){
					$this->loadModel('Pages');
					 $this->set('cmsDetails',$this->Pages->get($this->request->data['cms_id']));
				}
		}
	}
	public function deleteSubject()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Subjects');
			$query = $this->Subjects->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			echo 1;
		}
		die;
	}
	public function subject()
	{
		$this->set('title' , 'Galaxyzuo!: subject');
		$this->loadModel('Subjects');
		$Subjects = $this->Subjects->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			
			if($this->request->data['id']=='') $Subjects = $this->Subjects->newEntity();
			else $Subjects = $this->Subjects->get($this->request->data['id']);
			$Subjects = $this->Subjects->patchEntity($Subjects, $this->request->data);
			if ($this->Subjects->save($Subjects)) {
				if($this->request->data['id']=='') $this->Flash->success(__('Subject has been created.'));
				else $this->Flash->success(__('Subject has been updated.'));
				return $this->redirect(['controller'=>'Settings','action' => 'subject']);
			}
		
		}
		$this->set('listing',$this->Subjects->find('all', ['order'=>['id'=>'desc']])->toArray());
		 $this->set('Subjects',$Subjects);
	
	}
	
	public function manage()
	{
		$this->loadModel('Pages');
			
		$this->set('title' , 'Cms Pages');
		$this->set('Pages', $this->Pages->newEntity($this->request->data));
		$this->set('cmsPages',$this->Pages->find('list',array('keyField'=>'id' , 'valueField'=> 'title'))->toArray());
		
		if ($this->request->is(['post' ,'put'])) {
			$Pages  = $this->Pages->get($this->request->data['id']);
			$Pages = $this->Pages->patchEntity($Pages, $this->request->data);
			
			if ($this->Pages->save($Pages)) {
				$this->Flash->success(__('Cms page has been saved.'));
				return $this->redirect(['controller'=>'Settings','action' => 'manage']);
			}else{
				$this->Flash->error(__('Some Errors Occurred.'));
			}
		}
	
	}
    public function index()
    {
        $this->set('title' , 'Galaxyzuo!: Setting');
		$this->loadModel('ConversionRates');
		$conversion = $this->ConversionRates->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			$this->request->data['left_coins'] = $this->request->data['total_coins'];
			$conversion = $this->ConversionRates->newEntity();
			$conversion = $this->ConversionRates->patchEntity($conversion, $this->request->data); 
			if($this->ConversionRates->save($conversion)){
				$this->Flash->success(__('Add successfully.','conversion'));
				return $this->redirect(['controller'=>'Settings','action'=>'index']);
			}else{
				foreach($conversion->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						$this->Flash->error(__($error_text,'conversion'));
					} 
				}
			}
			
			
		}
		$searchData =array();
        $settings = $this->Settings->find('all')->toArray();
        $this->set('listing',$this->Paginator->paginate($this->ConversionRates, [
			'conditions'=>$searchData,
			'order'=>['ConversionRates.id'=>'desc'],
			'limit' => $this->setting['pagination'],
		]));
      
        $this->set('conversion',$conversion);
        $this->set('settings',$settings);
    }
    
	
    public function fee()
    {
        $this->set('title' , 'Coinibt!: Fee');
		$this->loadModel('Settings');
        if ($this->request->is(['post','put'])){
			$deposit_fee = $this->request->data['deposit_fee'];
			$withdrawal_fee = $this->request->data['withdrawal_fee'];
			$main_to_trading_transfer_fee = $this->request->data['main_to_trading_transfer_fee'];
			$trading_to_main_transfer_fee = $this->request->data['trading_to_main_transfer_fee'];
			$buy_sell_fee = $this->request->data['buy_sell_fee'];
			$loan_deposit_fee = $this->request->data['loan_deposit_fee'];
			
			$update = $this->Settings->updateAll(['value'=>$deposit_fee],['id'=>16]);
			$update = $this->Settings->updateAll(['value'=>$withdrawal_fee],['id'=>17]);
			$update = $this->Settings->updateAll(['value'=>$main_to_trading_transfer_fee],['id'=>18]);
			$update = $this->Settings->updateAll(['value'=>$trading_to_main_transfer_fee],['id'=>19]);
			$update = $this->Settings->updateAll(['value'=>$buy_sell_fee],['id'=>20]);
			$update = $this->Settings->updateAll(['value'=>$loan_deposit_fee],['id'=>21]);

			$this->add_system_log(200, 0, 3, '수수료 설정 수정');
			
			$this->Flash->success(__('Fee Updated Successfully.'));
			return $this->redirect(['controller'=>'Settings','action' => 'fee']);
		}
		$settings = $this->Settings->find('all')->hydrate(false)->toArray();
		
        $this->set('settings',$settings);
    }	
    public function deleteConversion()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Conversions');
			$query = $this->Conversions->query();
			$query->delete()
			->where(['id'=>$this->request->data['id']])
			->execute();
			echo 1;
		}
		die;
		
	}
	 
    public function forbidden(){
        if($this->request->referer()!='/') $this->request->session()->write('Config.referer', $this->request->referer());
        $this->set('title' , 'GalaxyIco!: Access forbidden');

    }

    public function update()
    {
        $error = [];
        if($this->request->is('ajax'))
        {

            $data = $this->request->data;

            foreach ($data as $k => $v)
            {
                $setting = $this->Settings->find('all',['fields'=>['id','type','show_name'],'conditions'=>['module_name'=>$k]])->hydrate(false)->first();
                $sett = $this->Settings->get($setting['id']);
                $set = $this->Settings->patchEntity($sett,array('value'=>$v,'type'=>$setting['type']));
                if($settings = $this->Settings->save($set)) {

                }
                else
                {
                    $error[$setting['id']] = 'Invalid value for '.$setting['show_name'];
                }
            }
            echo json_encode($error);die;
        }

	}
	

	public function notice(){
		$this->set('title' , 'Contact us');
		$this->loadModel('BoardNotice');
		$searchData = array();
		$limit = 10;
		if ($this->request->is(['post' ,'put']) ) 
		{
			$limit = 10;
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['id'] != '') $searchData['AND'][] =array('BoardQna.id' => $search['id']);
			if($search['username'] != '') $searchData['AND'][] =array('user.username' => $search['username']);
			if($search['email'] != '') $searchData['AND'][] =array('user.email' => $search['email']);
			//if($search['status'] != '') $searchData['AND'][] = array('BoardQna.status'=>$search['status']);
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Support.created_at) >= ' => $this->request->data['start_date'],'DATE(created_at) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['end_date']);
			
		}
		//$searchData['AND'][] =array('BoardQna.users_id !=' => 1);
		$this->set('BoardNotice',$this->Paginator->paginate(
			$this->BoardNotice, [
				'contain'=>['user'=>['fields'=>['username','email']]],
				'contain'=>['user'],
				'limit' => $limit,
				'order'=>['id'=>'desc'],
				'conditions' => $searchData
			])
		);
		
	}
	
	public function noticesearch(){
		$this->set('title' , 'Contact us');
		$this->loadModel('BoardNotice');
		$searchData = array();
		$limit = 10;
		if($this->request->query('page')) { 
				$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
			}
		else $this->set('serial_num',1);
		if ($this->request->is(['post' ,'put']) ) 
		{
			$limit = 10;
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			if($search['id'] != '') $searchData['AND'][] =array('BoardQna.id' => $search['id']);
			if($search['username'] != '') $searchData['AND'][] =array('user.username' => $search['username']);
			if($search['email'] != '') $searchData['AND'][] =array('user.email' => $search['email']);
			//if($search['status'] != '') $searchData['AND'][] = array('BoardQna.status'=>$search['status']);
			if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Support.created_at) >= ' => $this->request->data['start_date'],'DATE(created_at) <= ' => $this->request->data['end_date']);
			else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['start_date']);
			else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['end_date']);
			
		}
		//$searchData['AND'][] =array('BoardQna.users_id !=' => 1);
		$this->set('BoardNotice',$this->Paginator->paginate(
			$this->BoardNotice, [
				'contain'=>['user'=>['fields'=>['username','email']]],
				'contain'=>['user'],
				'limit' => $limit,
				'order'=>['id'=>'desc'],
				'conditions' => $searchData
			])
		);
		
	}
	
	

	public function addnotice()
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('BoardNotice');
		
		
		$conversion = $this->BoardNotice->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			//echo $this->request->params['_csrfToken'];
			//exit;
			//$coinShortName = $this->request->data['short_name'];
				$cudate = date("Y-m-d H:i:s");
				if(!empty($this->request->data['icon_img']['name'])){
					$imgName = $this->request->data['icon_img']['name'];
					$tmpName = $this->request->data['icon_img']['tmp_name'];
					$getExtension = pathinfo($imgName, PATHINFO_EXTENSION);
					$newImgName = time().".".$getExtension;
					if(move_uploaded_file($tmpName,"uploads/board/".$newImgName)){
						$this->request->data['file'] = $newImgName;
					}
				}
				$this->request->data['modified'] = $cudate;
				
				$conversion = $this->BoardNotice->patchEntity($conversion, $this->request->data); 
				if($this->BoardNotice->save($conversion)){
					$this->Flash->success(__('Add successfully.','conversion'));
					$this->add_system_log(200, 0, 2, '공지사항 추가');
					return $this->redirect(['controller'=>'settings','action'=>'notice']);
				}else{
					$this->add_system_log(200, 0, 2, '공지사항 추가 실패');
					foreach($conversion->errors() as $field_key =>  $error_data)
					{
						foreach($error_data as $error_text)
						{
							$this->Flash->error(__($error_text,'conversion'));
						} 
					}
				}
			
			
			
			
		}
		// $searchData =array();
        // $this->set('listing',$this->Paginator->paginate($this->Coin, [
		// 	'conditions'=>$searchData,
		// 	'order'=>['Coin.id'=>'desc'],
		// 	'limit' => $this->setting['pagination'],
		// ]));
      
        $this->set('conversion',$conversion);
    }
	public function editnotice($id=null)
    {
		$this->loadModel('BoardNotice');
		$BoardNotice=$this->BoardNotice->find('all',[
			'conditions'=>['id'=>$id],
			'order'=>['id'=>'desc']])
			->hydrate(false)
			->first();
			if(empty($BoardNotice)){
				return $this->redirect(['controller'=>'settings','action'=>'notice']);
			}

        $this->set('title' , 'ICO: Token');
		
		
		
		$conversion = $this->BoardNotice->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			//$coinShortName = $this->request->data['short_name'];
			
		
				$cudate = date("Y-m-d H:i:s");
				if(!empty($this->request->data['icon_img']['name'])){
					$imgName = $this->request->data['icon_img']['name'];
					$tmpName = $this->request->data['icon_img']['tmp_name'];
					$getExtension = pathinfo($imgName, PATHINFO_EXTENSION);
					$newImgName = time().".".$getExtension;
					if(move_uploaded_file($tmpName,"uploads/board/".$newImgName)){
						$this->request->data['file'] = $newImgName;
					}
				}
				unset($this->request->data['icon_img']);
				
				
				$updateIt = $this->BoardNotice->updateAll($this->request->data, ['id' =>$id]);
					$this->add_system_log(200, 0, 3, '공지사항 수정 (id : '.$id.')');
					$this->Flash->success(__('Add successfully.','conversion'));
					return $this->redirect(['controller'=>'settings','action'=>'notice']);
		}
		
		// $searchData =array();
        // $this->set('listing',$this->Paginator->paginate($this->Coin, [
		// 	'conditions'=>$searchData,
		// 	'order'=>['Coin.id'=>'desc'],
		// 	'limit' => $this->setting['pagination'],
		// ]));
      
		$this->set('BoardNotice',$BoardNotice);
		$this->set('conversion',$conversion);
    }

    public function deleteNotice()
    {
        if ($this->request->is('ajax')) {
            $this->loadModel('BoardNotice');
            $query = $this->BoardNotice->query();
            $query->delete()->where(['id' => $this->request->data['id']])->execute();
			$this->add_system_log(200, 0, 4, '공지사항 삭제 (id : '.$this->request->data['id'].')');
            echo 1;
        }
        die;
    }

    public function deleteFAQ()
    {
        if ($this->request->is('ajax')) {
            $this->loadModel('BoardFaq');
            $query = $this->BoardFaq->query();
            $query->delete()->where(['id' => $this->request->data['id']])->execute();
			$this->add_system_log(200, 0, 4, 'FAQ 삭제 (id : '.$this->request->data['id'].')');
            echo 1;
        }
        die;
    }

	public function faqs(){
		$this->set('title' , 'Contact us');
		$this->loadModel('BoardFaq');
		$searchData = array();
		$limit = 10;
		if ($this->request->is(['post' ,'put']) ) 
		{
			$limit = 10;
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			// if($search['id'] != '') $searchData['AND'][] =array('BoardQna.id' => $search['id']);
			// if($search['username'] != '') $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			// if($search['email'] != '') $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			// //if($search['status'] != '') $searchData['AND'][] = array('BoardQna.status'=>$search['status']);
			// if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Support.created_at) >= ' => $this->request->data['start_date'],'DATE(created_at) <= ' => $this->request->data['end_date']);
			// else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['start_date']);
			// else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['end_date']);
			
		}
		//$searchData['AND'][] =array('BoardQna.users_id !=' => 1);
		$this->set('BoardFaq',$this->Paginator->paginate(
			$this->BoardFaq, [
				
				
				'limit' => $limit,
				'order'=>['id'=>'desc'],
				'conditions' => $searchData
			])
		);
		
	}

	public function addfaqs()
    {
        $this->set('title' , 'ICO: Token');
		$this->loadModel('BoardFaq');
		$this->loadModel('FaqCategory');
		
		$conversion = $this->BoardFaq->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			//$coinShortName = $this->request->data['short_name'];
			$cudate = date("Y-m-d H:i:s");
			$conversion = $this->BoardFaq->patchEntity($conversion, $this->request->data); 
			if($this->BoardFaq->save($conversion)){
				$this->add_system_log(200, 0, 2, 'FAQ 추가');
				$this->Flash->success(__('Add successfully.','conversion'));
				return $this->redirect(['controller'=>'settings','action'=>'faqs']);
			}else{
				$this->add_system_log(200, 0, 2, 'FAQ 추가 실패');
				foreach($conversion->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						$this->Flash->error(__($error_text,'conversion'));
					} 
				}
			}
		}
        $this->set('conversion',$conversion);
    }

	public function editfaqs($id=null)
    {
		$this->loadModel('BoardFaq');
		$this->loadModel('FaqCategory');
		$BoardFaq=$this->BoardFaq->find('all',[
			'conditions'=>['id'=>$id],
			'order'=>['id'=>'desc']])
			->hydrate(false)
			->first();
			if(empty($BoardFaq)){
				return $this->redirect(['controller'=>'settings','action'=>'faqs']);
			}
        $this->set('title' , 'ICO: Token');
		
		$conversion = $this->BoardFaq->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			//$coinShortName = $this->request->data['short_name'];
			$cudate = date("Y-m-d H:i:s");
			$updateIt = $this->BoardFaq->updateAll($this->request->data, ['id' =>$id]);
			$this->add_system_log(200, 0, 3, 'FAQ 수정 (id : '.$id.')');
			$this->Flash->success(__('Add successfully.','conversion'));
			return $this->redirect(['controller'=>'settings','action'=>'faqs']);
		}

		$category_list = $this->FaqCategory->find()->where(['lang'=>$BoardFaq['lang']])->all();
      
		$this->set('BoardFaq',$BoardFaq);
		$this->set('category_list',$category_list);
		$this->set('conversion',$conversion);
    }

	public function getfaqcategory(){
		$this->loadModel('FaqCategory');
		if ($this->request->is('ajax')) {
			$lang = $this->request->data('lang');
			$category_list = $this->FaqCategory->find()->select(['category'])->where(['lang'=>$lang])->all();
			echo json_encode($category_list);
			die;
		}
		die;
	}

	public function addfaqcategory(){
		$this->loadModel('FaqCategory');
		if ($this->request->is('ajax')) {
			$newEntity = $this->FaqCategory->newEntity();
			$patchEntity = $this->FaqCategory->patchEntity($newEntity, $this->request->data); 
			if($this->FaqCategory->save($patchEntity)){
				echo "success";
				die;
			}
			echo "fail";
		}
		die;
	}


	public function faqspagination(){
		$this->set('title' , 'Contact us');
		$this->loadModel('BoardFaq');
		$searchData = array();
		$limit = 10;
		if($this->request->query('page')) { 
			$this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
		}else{
			$this->set('serial_num',1);
		}
		if ($this->request->is(['post' ,'put']) ) 
		{
			$limit = 10;
			if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
			$search = $this->request->data;
			//pr($search);die;
			// if($search['id'] != '') $searchData['AND'][] =array('BoardQna.id' => $search['id']);
			// if($search['username'] != '') $searchData['AND'][] =array('user.username LIKE' => '%'.$search['username'].'%');
			// if($search['email'] != '') $searchData['AND'][] =array('user.email LIKE' => '%'.$search['email'].'%');
			// //if($search['status'] != '') $searchData['AND'][] = array('BoardQna.status'=>$search['status']);
			// if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(Support.created_at) >= ' => $this->request->data['start_date'],'DATE(created_at) <= ' => $this->request->data['end_date']);
			// else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['start_date']);
			// else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(Support.created_at)' => $search['end_date']);
			
		}
		//$searchData['AND'][] =array('BoardQna.users_id !=' => 1);
		$this->set('BoardFaq',$this->Paginator->paginate(
			$this->BoardFaq, [
				
				
				'limit' => $limit,
				'order'=>['id'=>'desc'],
				'conditions' => $searchData
			])
		);
		
	}

	function fcn($value){
        return substr($value,-4);
    }

    public function settings(){
        $this->set('title' , 'Level Settings');

        $this->loadModel('Coinpair');
        $this->loadModel('Cryptocoin');
        $this->loadModel("Levels");
        $this->loadModel('NumberSevenSetting');
		
		
		$coinpairList = $this->Coinpair->find('all',['conditions'=>['Coinpair.status'=>1, 'OR'=>[['Coinpair.coin_second_id' => 20], ['Coinpair.coin_second_id' => 5]]],"contain"=>["cryptocoin_first","cryptocoin_second"]])->hydrate(false)->toArray();
        $this->set('coinpairList',$coinpairList);
		
		
        $levelList = $this->Levels->find('list', ['keyField' => 'id',
            'valueField' => 'level_name'
        ])->toArray();
        $this->set('levelList',$levelList);
        $this->loadModel('Users');
        $coinList = $this->Cryptocoin->find('list', ['keyField' => 'id',
            'valueField' => 'short_name'
        ],['conditions'=>['id !='=>1]])->toArray();
        $this->set('coinList',$coinList);
        //$conversion = $this->Cryptocoin->newEntity();
        $userFindList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => function ($e) {
                return $e->name . ' - ' . $e->username;
            },
            'conditions'=>['user_type'=>"U"]
        ])->toArray();
        $this->set('userFindList',$userFindList);

        $genUserFindList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => function ($e) {
                return $e->name . ' - ' . $e->username;
            },
            'conditions'=>['user_type'=>"U",'annual_membership'=>"N"]
        ])->toArray();
        $this->set('genUserFindList',$genUserFindList);

        $annUserFindList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => function ($e) {
                return $e->name . ' - ' . $e->username;
            },
            'conditions'=>['user_type'=>"U",'annual_membership'=>"Y"]
        ])->toArray();
        $this->set('annUserFindList',$annUserFindList);
        $getPercentage =  $this->NumberSevenSetting->find("all",["conditions"=>["status"=>"ACTIVE"],"order"=>["id"=>"DESC"]])->hydrate(false)->first();
        $this->set('percentage',$getPercentage['percentage']);
        $this->loadModel('Cryptocoin');
        $this->loadModel('Settings');
        if ($this->request->is(['post','put'])){
            $deposit_fee = $this->request->data['deposit_fee'];
            $withdrawal_fee = $this->request->data['withdrawal_fee'];
            $main_to_trading_transfer_fee = $this->request->data['main_to_trading_transfer_fee'];
            $trading_to_main_transfer_fee = $this->request->data['trading_to_main_transfer_fee'];
            $buy_sell_fee = $this->request->data['buy_sell_fee'];
            $loan_deposit_fee = $this->request->data['loan_deposit_fee'];

			if(!empty($deposit_fee) && !empty($withdrawal_fee) && !empty($main_to_trading_transfer_fee) && !empty($trading_to_main_transfer_fee) && !empty($buy_sell_fee) && !empty($loan_deposit_fee)) {
				$update = $this->Settings->updateAll(['value'=>$deposit_fee],['id'=>16]);
				$update = $this->Settings->updateAll(['value'=>$withdrawal_fee],['id'=>17]);
				$update = $this->Settings->updateAll(['value'=>$main_to_trading_transfer_fee],['id'=>18]);
				$update = $this->Settings->updateAll(['value'=>$trading_to_main_transfer_fee],['id'=>19]);
				$update = $this->Settings->updateAll(['value'=>$buy_sell_fee],['id'=>20]);
				$update = $this->Settings->updateAll(['value'=>$loan_deposit_fee],['id'=>21]);
				$this->add_system_log(200, 0, 3, '설정 수정');
			}
            $this->Flash->success(__('Fee Updated Successfully.'));
            return $this->redirect(['controller'=>'Settings','action' => 'fee']);
        }
        $settings = $this->Settings->find('all')->hydrate(false)->toArray();

        $this->set('settings',$settings);

//        if($this->request->is(['post','put']))
//        {
//            $id = $this->request->data['id'];
//            $coinFirst = $this->request->data['coin_first_id'];
//            $coinSecond = $this->request->data['coin_second_id'];
//            if($coinFirst == $coinSecond){
//                $this->Flash->success(__('Both Coin Can\'t be same.','conversion'));
//                return $this->redirect(['controller'=>'Coin','action'=>'coinpairEdit',$id]);
//            }
//            $findExist = $this->Coinpair->find('all',['conditions'=>['OR'=>[
//                [
//                    'coin_first_id'=>$coinFirst,
//                    'coin_second_id'=>$coinSecond,
//                    'id !='=>$id,
//                ],
//                [
//                    'coin_first_id'=>$coinSecond,
//                    'coin_second_id'=>$coinFirst,
//                    'id !='=>$id,
//                ]
//            ]
//            ]
//            ])->hydrate(false)->first();
//
//            if(empty($findExist)){
//                $conversion = $this->Coinpair->get($id);
//                $cudate = date("Y-m-d H:i:s");
//                $this->request->data['updated'] = $cudate;
//
//                $conversion = $this->Coinpair->patchEntity($conversion, $this->request->data);
//                if($this->Coinpair->save($conversion)){
//                    $this->Flash->success(__('pair updated successfully.','conversion'));
//                    return $this->redirect(['controller'=>'Coin','action'=>'coinpair_index']);
//                }else{
//                    foreach($conversion->errors() as $field_key =>  $error_data)
//                    {
//                        foreach($error_data as $error_text)
//                        {
//                            $this->Flash->error(__($error_text,'conversion'));
//                        }
//                    }
//                }
//            }
//            else {
//
//                $this->Flash->error(__('Coin Pair Already Exist','conversion'));
//                return $this->redirect(['controller'=>'Coin','action'=>'coinpair_index']);
//            }
//
//
//        }
//        else {
//            $conversion = $this->Coinpair->find('all',['conditions'=>['id'=>$id]])->hydrate(false)->first();
//            $this->request->data = $conversion;
//        }
//
    }

    public function numonesetting(){
		$this->loadModel("Coinpair");
		if ($this->request->is('ajax')) {
			$updateDataArr = [];
			$coinpair_id = $this->request->data['coinpair_id'];
			$updateDataArr['max_buysell_per'] = $this->request->data['max_buysell_per'];
			$updateDataArr['min_buysell_per'] = $this->request->data['min_buysell_per'];
			$updateDataArr['max_market_per'] = $this->request->data['max_market_per'];
			$updateDataArr['min_market_per'] = $this->request->data['min_market_per'];
			
			$this->Coinpair->updateAll($updateDataArr,["id"=>$coinpair_id]);
			$respArr = ["success"=>"true","message"=>"Setting Updated Successfully"];
			$this->add_system_log(200, 0, 3,'메인 셋팅 -> NO.1 수정 (coin id : '.$coinpair_id.')');
			echo json_encode($respArr); die;
		}
		
    }   
	
	public function numonesettingGet($coinpairId){
		$this->loadModel("Coinpair");
		if ($this->request->is('ajax')) {
			$getData =  $this->Coinpair->find("all",["conditions"=>["id"=>$coinpairId]])->hydrate(false)->first();;
			$respArr = ["success"=>"true","message"=>"Setting Updated Successfully",'data'=>$getData];
			echo json_encode($respArr); die;
		}
		
    }
	
	
	public function numTwoSettingGet(){
		$this->loadModel("Coinpair");
		$this->loadModel("TransferLimits");
		if ($this->request->is('ajax')) {
			$userId = $this->request->data["user_id"];
			$coinId = $this->request->data["coin_id"];
			$days = $this->request->data["days"];
			if($userId=="" || $coinId=="" || $days==""){
				$respArr = ["success"=>"false","message"=>"All Field Required"];
				echo json_encode($respArr); die;
			}
			$getData =  $this->TransferLimits->find("all",["conditions"=>[
																			"user_id"=>$userId,
																			"cryptocoin_id"=>$coinId,
																			"days"=>$days,
																			"status"=>"active"
																		 ],
															"order"=>["id"=>"DESC"]			 
														   ])
														   ->hydrate(false)
														   ->first();
			if(!empty($getData)){											   
				$respArr = ["success"=>"true","message"=>"Number Two Setting Data",'data'=>$getData];
				echo json_encode($respArr); die;
			}
			else {
				$respArr = ["success"=>"false","message"=>"No Data Found"];
				echo json_encode($respArr); die;
			}
		}
		
    }

    public function numTwoAnnSettingGet(){
        $this->loadModel("Coinpair");
        $this->loadModel("TransferLimits");
        $this->loadModel("Users");

        if ($this->request->is('ajax')) {
           /*  $getAnnUsers =  $this->Users->find("all",["fields"=>["id"],"conditions"=>["annual_membership"=>"Y"]])
                ->hydrate(false)
                ->toArray(); */
          //  foreach($getAnnUsers as $getAnnUser) {
                $userId = 2;
                $coinId = $this->request->data["coin_id"];
                $days = $this->request->data["days"];
                if ($userId == "" || $coinId == "" || $days == "") {
                    $respArr = ["success" => "false", "message" => "All Field Required"];
                    echo json_encode($respArr);
                    die;
                }
                $getData = $this->TransferLimits->find("all", ["conditions" => [
                    "user_id" => $userId,
                    "cryptocoin_id" => $coinId,
                    "days" => $days,
                    "status" => "active"],
                    "order" => ["id" => "DESC"]
                ])
                    ->hydrate(false)
                    ->first();
            //}
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"Number Two Setting Data",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }

    }

    public function showNumberTwoSettingData(){
		$this->loadModel("Coinpair");
		$this->loadModel("TransferLimits");
		if ($this->request->is('ajax')) {
			$userId = $this->request->data["user_id"];
			$coinId = $this->request->data["coin_id"];
			$days = $this->request->data["days"];
			if($userId=="" || $coinId=="" || $days==""){
				$respArr = ["success"=>"false","message"=>"All Field Required"];
				echo json_encode($respArr); die;
			}
			$getData =  $this->TransferLimits->find("all",["conditions"=>[
																			"user_id"=>$userId,
																			"cryptocoin_id"=>$coinId,
																			"days"=>$days
																		 ],
															 "contain"=>["cryptocoin","admin_user"]	,		 
															"order"=>["TransferLimits.id"=>"DESC"]			 
														   ])
														   ->hydrate(false)
														   ->toArray();
			if(!empty($getData)){											   
				$respArr = ["success"=>"true","message"=>"Number Two Setting Data",'data'=>$getData];
				echo json_encode($respArr); die;
			}
			else {
				$respArr = ["success"=>"false","message"=>"No Data Found"];
				echo json_encode($respArr); die;
			}
		}
		
    }

    public function showNumberTwoAnnSettingData(){
        $this->loadModel("Coinpair");
        $this->loadModel("TransferLimits");
        $this->loadModel("Users");
        if ($this->request->is('ajax')) {
            $getAnnUsers =  $this->Users->find("all",["fields"=>["id"],"conditions"=>["annual_membership"=>"Y"]])
                ->hydrate(false)
                ->toArray();
            foreach($getAnnUsers as $getAnnUser) {
                $userId = $getAnnUser["id"];
                $coinId = $this->request->data["coin_id"];
                $days = $this->request->data["days"];
                if ($userId == "" || $coinId == "" || $days == "") {
                    $respArr = ["success" => "false", "message" => "All Field Required"];
                    echo json_encode($respArr);
                    die;
                }
                $getData = $this->TransferLimits->find("all", ["conditions" => [
                    "user_id" => $userId,
                    "cryptocoin_id" => $coinId,
                    "days" => $days
                ],
                    "contain" => ["cryptocoin", "admin_user"],
                    "order" => ["TransferLimits.id" => "DESC"]
                ])->hydrate(false)->toArray();
            }
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"Number Two Setting Data",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }

    }


	public function numberTwoSettingCancel(){
		$this->loadModel("Coinpair");
		$this->loadModel("TransferLimits");
		if ($this->request->is('ajax')) {
			$date = date("Y-m-d H:i:s");
			$userId = $this->request->data["user_id"];
			$coinId = $this->request->data["coin_id"];
			$days = $this->request->data["days"];
			if($userId=="" || $coinId=="" || $days==""){
				$respArr = ["success"=>"false","message"=>"All Field Required"];
				$this->add_system_log(200, 0, 3,'메인 셋팅 -> NO.2 설정 취소 실패 (빈항목)');
				echo json_encode($respArr); die;
			}
			$updateOld = $this->TransferLimits->updateAll(['status'=>'cancelled','updated'=>$date],['user_id'=>$userId,'cryptocoin_id'=>$coinId,'days'=>$days]);	

			$this->add_system_log(200, $userId, 3,'메인 셋팅 -> NO.2 설정 취소 성공(coin id : '.$coinId.') numberTwoSettingCancel');
			
			$respArr = ["success"=>"true","message"=>"Setting Data Cancelled"];
			echo json_encode($respArr); die;
			
		}
		
    }


    public function numberTwoAnnSettingCancel(){
        $this->loadModel("Coinpair");
        $this->loadModel("TransferLimits");
        $this->loadModel("Users");

        if ($this->request->is('ajax')) {
            $getAnnUsers =  $this->Users->find("all",["fields"=>["id"],"conditions"=>["annual_membership"=>"Y"]])
                ->hydrate(false)
                ->toArray();

            foreach($getAnnUsers as $getAnnUser) {
                $date = date("Y-m-d H:i:s");
                $userId = $getAnnUser["id"];
                $coinId = $this->request->data["coin_id"];
                $days = $this->request->data["days"];
//                if ($userId == "" || $coinId == "" || $days == "") {
//                    $respArr = ["success" => "false", "message" => "All Field Required"];
//                    echo json_encode($respArr);
//                    die;
//                }
                $updateOld = $this->TransferLimits->updateAll(['status' => 'cancelled', 'updated' => $date], ['user_id' => $userId, 'cryptocoin_id' => $coinId, 'days' => $days]);
				$this->add_system_log(200, $userId, 3,'메인 셋팅 -> NO.2 설정 취소 성공(coin id : '.$coinId.') numberTwoAnnSettingCancel');
            }
            $respArr = ["success"=>"true","message"=>"Setting Data Cancelled"];
            echo json_encode($respArr); die;

        }

    }



    public function numtwosetting(){
		$this->loadModel("TransferLimits");
		if ($this->request->is('ajax')) {
			$currentUserId = $this->Auth->user('id');
			
			
				
				$date = date("Y-m-d H:i:s");
				$insertArr=[];
				$insertArr["user_id"] = $this->request->data["user_id"];
				$insertArr["cryptocoin_id"] = $this->request->data["cryptocoin_id"];
				$insertArr["trading_to_main_transfer_limit"] = $this->request->data["trading_to_main_transfer_amount"];
				$insertArr["main_to_trading_transfer_limit"] = $this->request->data["main_to_trading_transfer_amount"];
				$insertArr["days"] = $this->request->data["days"];
				$insertArr["admin_id"] = $currentUserId;
				$insertArr["created"] = $date;
				$insertArr["updated"] = $date;
				
				$updateOld = $this->TransferLimits->updateAll(['status'=>'cancelled','updated'=>$date],['user_id'=>$insertArr["user_id"],'cryptocoin_id'=>$insertArr["cryptocoin_id"],'days'=>$insertArr["days"]]);	
				$this->add_system_log(200, $insertArr["user_id"], 3,'메인 셋팅 -> NO.2 설정 수정 (coin id : '.$insertArr["cryptocoin_id"].')');
				
				$newEntity = $this->TransferLimits->newEntity();
				$patchEntity = $this->TransferLimits->patchEntity($newEntity,$insertArr);
				if($save = $this->TransferLimits->save($patchEntity)){
					$this->add_system_log(200, $insertArr["user_id"], 2,'메인 셋팅 -> NO.2 설정 추가');
					$respArr = ["success"=>"true","message"=>"Data Saved Successfully"];
					echo json_encode($respArr); die;
				}
				else {
					$respArr = ["success"=>"false","message"=>"unable to save data ! Try again"];
					echo json_encode($respArr); die;
				}
				
				
			
		}

    }


    public function numtwoAnnSetting(){
        $this->loadModel("TransferLimits");
        $this->loadModel("Users");
        if ($this->request->is('ajax')) {
            $currentUserId = $this->Auth->user('id');

           /*  $getAnnUsers =  $this->Users->find("all",["fields"=>["id"],"conditions"=>["annual_membership"=>"Y"]])
                ->hydrate(false)
                ->toArray();
			
		    $mainArr = [];
            
			foreach($getAnnUsers as $getAnnUser){	 */

                $date = date("Y-m-d H:i:s");
                $insertArr=[];
                $insertArr["user_id"] = 2; 
                $insertArr["cryptocoin_id"] = $this->request->data["cryptocoin_id"];
                $insertArr["trading_to_main_transfer_limit"] = $this->request->data["trading_to_main_transfer_amount"];
                $insertArr["main_to_trading_transfer_limit"] = $this->request->data["main_to_trading_transfer_amount"];
                $insertArr["days"] = $this->request->data["days"];
                $insertArr["admin_id"] = $currentUserId;
                $insertArr["created"] = $date;
                $insertArr["updated"] = $date;
				//$mainArr[]=$insertArr;
                $updateOld = $this->TransferLimits->updateAll(['status'=>'cancelled','updated'=>$date],['user_id'=>2,'cryptocoin_id'=>$insertArr["cryptocoin_id"],'days'=>$insertArr["days"]]);
				$this->add_system_log(200, $insertArr["user_id"], 3,'메인 셋팅 -> NO.2 설정 수정');

				$newEntity = $this->TransferLimits->newEntity();
                $patchEntity = $this->TransferLimits->patchEntity($newEntity,$insertArr);
				$result = $this->TransferLimits->save($patchEntity);
				$this->add_system_log(200, $insertArr["user_id"], 2,'메인 셋팅 -> NO.2 설정 추가');
				$respArr = ["success"=>"true","message"=>"Data Save Successfully"];
				echo json_encode($respArr); die;
            /* }

             if(!empty($mainArr)){
				$entities = $this->TransferLimits->newEntities($mainArr);
				$result = $this->TransferLimits->saveMany($entities);
                $respArr = ["success"=>"true","message"=>"Data Save Successfully"];
                echo json_encode($respArr); die;
            }
            else {
                $respArr = ["success"=>"false","message"=>"unable to save data ! Try again"];
                echo json_encode($respArr); die;
            } */



        }

    }
	
	public function userCategoryGet($user_id){
		$this->loadModel("Users");
		if ($this->request->is('ajax')) {
			
			
			$getData =  $this->Users->find("all",["conditions"=>["id"=>$user_id]])->hydrate(false)->first();
			if(!empty($getData)){
				$respArr = ["success"=>"true","message"=>"UserData",'data'=>$getData];
				echo json_encode($respArr); die;
			}else {
				$respArr = ["success"=>"false","message"=>"No User Found"];
				echo json_encode($respArr); die;
			}
		}
		
    }
	
	public function numThreeSettingGet(){
		$this->loadModel("Coinpair");
		$this->loadModel("NumberThreeSetting");
		if ($this->request->is('ajax')) {
			$userId = $this->request->data["user_id"];
			$coinId = $this->request->data["coin_id"];
			
			if($userId=="" || $coinId==""){
				$respArr = ["success"=>"false","message"=>"All Field Required"];
				echo json_encode($respArr); die;
			}
			$getData =  $this->NumberThreeSetting->find("all",["conditions"=>[
																			"user_id"=>$userId,
																			"cryptocoin_id"=>$coinId
																		 ],
															"order"=>["id"=>"DESC"]			 
														   ])
														   ->hydrate(false)
														   ->first();
			if(!empty($getData)){											   
				$respArr = ["success"=>"true","message"=>"Number Three Setting Data",'data'=>$getData];
				echo json_encode($respArr); die;
			}
			else {
				$respArr = ["success"=>"false","message"=>"No Data Found"];
				echo json_encode($respArr); die;
			}
		}
		
    }

    public function numthreesetting(){
		$this->loadModel("NumberThreeSetting");
		if ($this->request->is('ajax')) {
			$currentUserId = $this->Auth->user('id');
			
			
				
				$date = date("Y-m-d H:i:s");
				$insertArr=[];
				$insertArr["user_id"] = $this->request->data["user_id"];
				$insertArr["cryptocoin_id"] = $this->request->data["cryptocoin_id"];
				$insertArr["user_fee"] = $this->request->data["no_three_user_fee"];
				$insertArr["days"] = $this->request->data["days"];
				$insertArr["admin_id"] = $currentUserId;
				$insertArr["created"] = $date;
				$insertArr["updated"] = $date;
				
				$newEntity = $this->NumberThreeSetting->newEntity();
				$patchEntity = $this->NumberThreeSetting->patchEntity($newEntity,$insertArr);
				if($save = $this->NumberThreeSetting->save($patchEntity)){
					$this->add_system_log(200, $insertArr["user_id"], 2,'메인 셋팅 -> NO.3 설정 추가');
					$respArr = ["success"=>"true","message"=>"Data Save Successfully"];
					echo json_encode($respArr); die;
				}
				else {
					$this->add_system_log(200, $insertArr["user_id"], 2,'메인 셋팅 -> NO.3 설정 추가 실패');
					$respArr = ["success"=>"false","message"=>"unable to save data ! Try again"];
					echo json_encode($respArr); die;
				}
				
				
			
		}	
    }
	
	
	public function numFourSettingGet(){
		$this->loadModel("Coinpair");
		$this->loadModel("NumberFourSetting");
		$this->loadModel("UserBuySellFee");
		if ($this->request->is('ajax')) {
			$userId = $this->request->data["user_id"];
			$coinId = $this->request->data["coin_id"];
			$coinpair_id = $this->request->data["coinpair_id"];
			
			/* if($userId=="" || $coinId==""){
				$respArr = ["success"=>"false","message"=>"All Field Required"];
				echo json_encode($respArr); die;
			} */
			$myData = ["number_four_deposit_fee"=>"",
					   "number_four_withdrawal_fee"=>"",
					   "number_four_trading_to_main_transfer_fee"=>"",
					   "number_four_main_to_trading_transfer_fee"=>"",
					   "number_four_load_deposit_fee"=>"",
					   "number_four_buy_sell_fee"=>""];
			$getData =  $this->NumberFourSetting->find("all",["conditions"=>[
																			"user_id"=>$userId,
																			"cryptocoin_id"=>$coinId
																		 ],
															"order"=>["id"=>"DESC"]			 
														   ])
														   ->hydrate(false)
														   ->first();
			if(!empty($getData)){
				$myData["number_four_deposit_fee"]=$getData['deposit_fee'];
				$myData["number_four_withdrawal_fee"]=$getData['withdrawal_fee'];
				$myData["number_four_trading_to_main_transfer_fee"]=$getData['tranding_to_main_transfer_fee'];
				$myData["number_four_main_to_trading_transfer_fee"]=$getData['main_to_trading_transfer_fee'];
				$myData["number_four_load_deposit_fee"]=$getData['load_deposit_fee'];
			}		
			$getBuySellData =  $this->UserBuySellFee->find("all",["conditions"=>[
																			"user_id"=>$userId,
																			"coinpair_id"=>$coinpair_id
																		 ],
															"order"=>["id"=>"DESC"]			 
														   ])
														   ->hydrate(false)
														   ->first();
			if(!empty($getBuySellData)){
				$myData["number_four_buy_sell_fee"]=$getBuySellData['buy_sell_fee'];
			}				
			$respArr = ["success"=>"true","message"=>"Number Three Setting Data",'data'=>$myData];
			echo json_encode($respArr); die;
		}
		
    }

    public function numfoursetting(){
		$this->loadModel("NumberFourSetting");
		$this->loadModel("UserBuySellFee");
		if ($this->request->is('ajax')) {
			$currentUserId = $this->Auth->user('id');
			
			
				
				$date = date("Y-m-d H:i:s");
				$insertArr=[];
				$insertArr["user_id"] = 0;
				$insertArr["cryptocoin_id"] = $this->request->data["number_four_coin_id"];
				$insertArr["deposit_fee"] = $this->request->data["number_four_deposit_fee"];
				$insertArr["withdrawal_fee"] = $this->request->data["number_four_withdrawal_fee"];
				$insertArr["tranding_to_main_transfer_fee"] = $this->request->data["number_four_trading_to_main_transfer_fee"];
				$insertArr["main_to_trading_transfer_fee"] = $this->request->data["number_four_main_to_trading_transfer_fee"];
				$insertArr["load_deposit_fee"] = $this->request->data["number_four_load_deposit_fee"];
				$insertArr["admin_id"] = $currentUserId;
				$insertArr["created"] = $date;
				$insertArr["updated"] = $date;
				
				$newEntity = $this->NumberFourSetting->newEntity();
				$patchEntity = $this->NumberFourSetting->patchEntity($newEntity,$insertArr);
				if($save = $this->NumberFourSetting->save($patchEntity)){
					$this->add_system_log(200, 0, 2,'메인 셋팅 -> NO.4 설정 추가');
					$insertArr=[];
					$insertArr["user_id"] = 0;
					$insertArr["coinpair_id"] = $this->request->data["number_four_buy_sell_fee_coinpair_id"];
					$insertArr["buy_sell_fee"] = $this->request->data["number_four_buy_sell_fee"];
					$insertArr["admin_id"] = $currentUserId;
					$insertArr["created"] = $date;
					$insertArr["updated"] = $date;
					$newEntity = $this->UserBuySellFee->newEntity();
					$patchEntity = $this->UserBuySellFee->patchEntity($newEntity,$insertArr);
					$save = $this->UserBuySellFee->save($patchEntity);
					$this->add_system_log(200, 0, 2,'메인 셋팅 -> NO.4 설정 추가하여 UserBuySellFee 추가');
				
					$respArr = ["success"=>"true","message"=>"Data Save Successfully"];
					echo json_encode($respArr); die;
				}
				else {
					$respArr = ["success"=>"false","message"=>"unable to save data ! Try again"];
					echo json_encode($respArr); die;
				}
				
				
			
		}
    }

    public function numfivesetting(){

    }

    //coupon settings
    public function numsixsettingget(){
        $this->loadModel("Coinpair");
        $this->loadModel("NumberSixSetting");
        if ($this->request->is('ajax')) {
            $coinId = $this->request->data["coin_id"];
            $myData=[];
            $getData =  $this->NumberSixSetting->find("all",["conditions"=>[
                "cryptocoin_id"=>$coinId,
                "status"=>"ACTIVE"
            ], "order"=>["id"=>"DESC"]])
                ->hydrate(false)
                ->first();
            if(!empty($getData)){
                $myData["amount"]=$getData['amount'];
				$myData["krw"]=$getData['krw'];
				$myData["time_limit"]=$getData['time_limit'];
                $myData["coupon_limit"] = $getData["coupon_limit"];
            }

            $respArr = ["success"=>"true","message"=>__("Number six setting data"),'data'=>$myData];
            echo json_encode($respArr); die;
        }

    }

    public function numsixsetting(){
        $this->loadModel("NumberSixSetting");
        $this->loadModel("Users");
        if ($this->request->is('ajax')) {
            $currentUserId = $this->Auth->user('id');
            $coinId = $this->request->data["coupons_cryptocoin_id"];
            $insertArr=[];
            $insertArr["user_id"] = 0;
            $insertArr["cryptocoin_id"] = $this->request->data["coupons_cryptocoin_id"];
            $insertArr["amount"] = $this->request->data["coupon_amount"];
			$insertArr["krw"] = $this->request->data["krw"];
            $insertArr["coupon_limit"] = $this->request->data["coupon_limit"];
			$insertArr["time_limit"] = $this->request->data["time_limit"];
            $insertArr["admin_id"] = $currentUserId;
			$insertArr["updated"] = date('Y-m-d H:i:s');
			$entity = $this->NumberSixSetting->find('all')->where(['status'=>'ACTIVE','cryptocoin_id'=>$coinId])->first();
			// 있으면 update, 없으면 insert
			if(!$entity){
	            $entity = $this->NumberSixSetting->newEntity();
			}
            $patchEntity = $this->NumberSixSetting->patchEntity($entity,$insertArr);
            if($this->NumberSixSetting->save($patchEntity)){
				$this->add_system_log(200, 0, 2,'메인 셋팅 -> NO.6 설정 추가');
                $respArr = ["success"=>"true","message"=>__("Saved successfully!")];
                echo json_encode($respArr); die;
            }
            else {
				$this->add_system_log(200, 0, 2,'메인 셋팅 -> NO.6 설정 추가 실패');
                $respArr = ["success"=>"false","message"=>__("Unable to save data! Please try again")];
                echo json_encode($respArr); die;
            }
        }
    }

    public function shownumbersixsettingdata(){
        $this->loadModel("Coinpair");
        $this->loadModel("NumberSixSetting");
        if ($this->request->is('ajax')) {
            $getData =  $this->NumberSixSetting->find("all",["conditions"=>['NumberSixSetting.status'=>'ACTIVE'],"contain"=>["cryptocoin"],"order"=>["NumberSixSetting.id"=>"DESC"]])->hydrate(false)->toArray();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>__("Number six setting data"),'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                $respArr = ["success"=>"false","message"=>__("Record not found")];
                echo json_encode($respArr); die;
            }
        }
    }

    public function numberSixSettingCancel(){
        $this->loadModel("Coinpair");
        $this->loadModel("NumberSixSetting");
        if ($this->request->is('ajax')) {
            $currentUserId = $this->Auth->user('id');
            $date = date("Y-m-d H:i:s");
            $coinId = $this->request->data["coin_id"];
            if($coinId==""){
                $respArr = ["success"=>"false","message"=>__("All fields are required")];
				$this->add_system_log(200, 0, 3,'메인 셋팅 -> NO.6 설정 취소 실패');
                echo json_encode($respArr); die;
            }
            $updateOld = $this->NumberSixSetting->updateAll(['status'=>'INACTIVE','updated'=>$date],['cryptocoin_id'=>$coinId,'admin_id'=>$currentUserId]);
			$this->add_system_log(200, 0, 3,'메인 셋팅 -> NO.6 설정 취소');
            $respArr = ["success"=>"true","message"=>__("Settings data cancelled")];
            echo json_encode($respArr); die;
        }
    }

    //withdrawal % settings

    public function numsevensetting(){
        $this->loadModel("NumberSevenSetting");
        $this->loadModel("Users");
        if ($this->request->is('ajax')) {
            $currentUserId = $this->Auth->user('id');
            $insertArr=[];
            $insertArr["percentage"] = $this->request->data["percentage"];
            $insertArr["admin_id"] = $currentUserId;
            $newEntity = $this->NumberSevenSetting->newEntity();
            $patchEntity = $this->NumberSevenSetting->patchEntity($newEntity,$insertArr);
            $updateOld = $this->NumberSevenSetting->updateAll(['status'=>'INACTIVE'],['admin_id'=>$currentUserId]);
            if($this->NumberSevenSetting->save($patchEntity) && $updateOld == true){
				$this->add_system_log(200, 0, 2,'메인 셋팅 -> NO.7 설정 추가');
                $respArr = ["success"=>"true","message"=>"Data Saved Successfully"];
                echo json_encode($respArr); die;
            }
            else {
				$this->add_system_log(200, 0, 2,'메인 셋팅 -> NO.7 설정 실패');
                $respArr = ["success"=>"false","message"=>"Unable to save data! Please try again"];
                echo json_encode($respArr); die;
            }
        }
    }

    public function shownumbersevensettingdata(){
        $this->loadModel("NumberSevenSetting");
        if ($this->request->is('ajax')) {

            $percentage = $this->request->data["percentage"];
            if($percentage == ""){
                $respArr = ["success"=>"false","message"=>"All Field Required"];
                echo json_encode($respArr); die;
            }
            $getData =  $this->NumberSevenSetting->find("all",["order"=>["NumberSevenSetting.id"=>"DESC"]])->hydrate(false)->toArray();
            if(!empty($getData)){
                $respArr = ["success"=>"true","message"=>"Number Seven Setting Data",'data'=>$getData];
                echo json_encode($respArr); die;
            }
            else {
                $respArr = ["success"=>"false","message"=>"No Data Found"];
                echo json_encode($respArr); die;
            }
        }

    }

    public function numberSevenSettingCancel(){
        $this->loadModel("NumberSevenSetting");
        if ($this->request->is('ajax')) {
            $currentUserId = $this->Auth->user('id');
            $date = date("Y-m-d H:i:s");
            $percentage = $this->request->data["percentage"];
            if($percentage == ""){
                $respArr = ["success"=>"false","message"=>"All Fields Required"];
				$this->add_system_log(200, 0, 3,'메인 셋팅 -> NO.7 설정 취소 실패 (빈항목)');
                echo json_encode($respArr); die;
            }
            $updateOld = $this->NumberSevenSetting->updateAll(['status'=>'INACTIVE','updated'=>$date],['admin_id'=>$currentUserId]);
            if($updateOld){
				$this->add_system_log(200, 0, 3,'메인 셋팅 -> NO.7 설정 취소');
                $respArr = ["success"=>"true","message"=>"Setting Data Cancelled"];
                echo json_encode($respArr); die;
            } else {
				$this->add_system_log(200, 0, 3,'메인 셋팅 -> NO.7 설정 취소 실패');
                $respArr = ["success"=>"false","message"=>"Cannot cancel"];
                echo json_encode($respArr); die;
            }


        }
    }
	/* 관리자 접속 ip 설정 */
	public function adminIpList(){
		$this->loadModel("AdminAccessIp");
		if ($this->request->is(['post','put'])) {
			$access_ip = $this->request->data('access_ip');
			if(empty($access_ip)){ 
				$this->Flash->error(__('IP를 입력해주세요.'));
				$this->add_system_log(200, 0, 2, '관리자 접속 IP 추가 실패 (빈값)');
				return $this->redirect(['action' => 'adminIpList']);
			}
			$already_exits = $this->AdminAccessIp->find()->where(['access_ip'=>$access_ip])->count();
			if($already_exits > 0 ){ 
				$this->Flash->error(__('이미 존재하는 IP 입니다.'));
				$this->add_system_log(200, 0, 2, '관리자 접속 IP 추가 실패 (이미 존재하는 IP)');
				return $this->redirect(['action' => 'adminIpList']);
			}

			$last_id = $this->Auth->user('id');
			$query = $this->AdminAccessIp->query();
			$query->insert(['access_ip','created','updated','last_id'])->values(['access_ip'=>$access_ip,'created'=>date('Y-m-d H:i:s'),'updated'=>date('Y-m-d H:i:s'),'last_id'=>$last_id])->execute();
			$this->add_system_log(200, 0, 2, '관리자 접속 IP '.$access_ip.' 추가');
			return $this->redirect(['action' => 'adminIpList']);
		}
		$list = $this->AdminAccessIp->find()->select(['id','access_ip','status','created','updated','last_id'])->all();
		$this->set('list',$list);
	}
	/* 관리자 접속 ip 상태 변경 */
	public function ipstatuschange(){
		$this->loadModel("AdminAccessIp");
		if($this->request->is('ajax')){
			$id = $this->request->data('id');
			$status = $this->request->data('status');
			$update_status = 0;
			if($status == 0){
				$update_status = 1;
			}
			$query = $this->AdminAccessIp->query();
			$query->update()->set(['status'=>$update_status,'last_id'=>$this->Auth->user('id'),'updated'=>date('Y-m-d H:i:s')])->where(['id'=>$id])->execute();
			$this->add_system_log(200, 0, 2, '관리자 접속 IP (id : '.$id.') 상태 수정');
			echo 'success';
		}
		die;
	}
	/* 로그인세션 INACTIVE 삭제 */
	public function deleteLoginSession(){
		if ($this->request->is('ajax')) {
			$this->loadModel('LoginSessions');
			$delete_count = $this->LoginSessions->find()->where(['status'=>'INACTIVE'])->count();
			$query = $this->LoginSessions->query();
			$query->delete()->where(['status'=>'INACTIVE'])->execute();
			$this->add_system_log(200, 0, 4, '로그인세션 INACTIVE 삭제 ( 삭제수 : '. $delete_count . ' )');
			echo $delete_count;
		}
		die;
	}

    /* 보관함 넣는 양 */

    function safeininsert(){


        //데이터를 받아서 처리한다
        $safein = $this->request->data("safein"); //코인 용량
        $coin_id = $this->request->data("coinname"); // 코인번호
        //limit_type

        if($safein){
            $this->loadModel("TransferLimits");

            $date = date("Y-m-d H:i:s");
            $insertArr=[];
            /*$insertArr["user_id"] = $this->Auth->user('id');*/
            $insertArr["user_id"] = '99999';
            $insertArr["cryptocoin_id"] = $coin_id;
            $insertArr["trading_to_main_transfer_limit"] = $safein;
            $insertArr["main_to_trading_transfer_limit"] = $safein;
            $insertArr["status"] = "active";
            $insertArr["days"] = '30';
            $insertArr['limit_type'] = "save";
            $insertArr["admin_id"] =$this->Auth->user('id');

            $insertArr["created"] = $date;
            $insertArr["updated"] = $date;

            $newEntity = $this->TransferLimits->newEntity();
            $patchEntity = $this->TransferLimits->patchEntity($newEntity,$insertArr);
            $save = $this->TransferLimits->save($patchEntity);


            echo "success";
            die;

        }else{
            echo "Fail";
            die;
        }
        die;




    }

    /* 보관함 빼는 양 */

    function safeoutinert(){

        //데이터를 받아서 처리한다
        $safein = $this->request->data("safein"); //코인 용량
        $coin_id = $this->request->data("coinname"); // 코인번호

        if($safein){
            $this->loadModel("TransferLimits");

            $date = date("Y-m-d H:i:s");
            $insertArr=[];
            /*$insertArr["user_id"] = $this->Auth->user('id');*/
            $insertArr["user_id"] = '99998';
            $insertArr["cryptocoin_id"] = $coin_id;
            $insertArr["trading_to_main_transfer_limit"] = $safein;
            $insertArr["main_to_trading_transfer_limit"] = $safein;
            $insertArr["status"] = "active";
            $insertArr["days"] = '30';
            $insertArr['limit_type'] = "savetrading";
            $insertArr["admin_id"] =$this->Auth->user('id');

            $insertArr["created"] = $date;
            $insertArr["updated"] = $date;

            $newEntity = $this->TransferLimits->newEntity();
            $patchEntity = $this->TransferLimits->patchEntity($newEntity,$insertArr);
            $save = $this->TransferLimits->save($patchEntity);


            echo "success";
            die;

        }else{
            echo "Fail";
            die;
        }
        die;

    }

	/* 서버 체크 메세지 추가 */
	public function addServerCheckMsg(){
		if ($this->request->is('ajax')) {
			$this->loadModel('ServerCheck');
			$message = $this->request->data('message');
			if(empty($message)){
				echo 'fail';
				die;
			}
			$data = array();
			$data['message'] = $message;
			$data['status'] = 'N';
			$data['created'] = date('Y-m-d H:i:s');
			$data['updated'] = date('Y-m-d H:i:s');
			$data['last_id'] = $this->Auth->user('id');
			$new_server_check = $this->ServerCheck->newEntity();
			$save_data = $this->ServerCheck->patchEntity($new_server_check, $data);
			if($this->ServerCheck->save($save_data)){
				$this->add_system_log(200, 0, 2, '서버 점검 메세지 추가');
				echo 'success';
				die;
			}
			$this->add_system_log(200, 0, 2, '서버 점검 메세지 추가 실패');
			echo 'fail';
			die;
		}
		die;
	}
	/* 서버 체크 메세지 수정 */
	public function editServerCheckMsg(){
		if ($this->request->is('ajax')) {
			$this->loadModel('ServerCheck');
			$id = $this->request->data('id');
			$message = $this->request->data('message');
			if(empty($id) || empty($message)){
				echo 'fail';
				die;
			}
			$data = array();
			$data['message'] = $message;
			$data['updated'] = date('Y-m-d H:i:s');
			$data['last_id'] = $this->Auth->user('id');

			$update_server_check = $this->ServerCheck->get($id);
			$save_data = $this->ServerCheck->patchEntity($update_server_check, $data);
			if($this->ServerCheck->save($save_data)) {
				$this->add_system_log(200, 0, 3, '서버 점검 메세지 수정 ('.$id.')');
				echo 'success';
				die;
			}
			$this->add_system_log(200, 0, 3, '서버 점검 메세지 수정 실패('.$id.')');
			echo 'fail';
			die;
		}
	}
	/* 서버 체크 메세지 삭제 */
	public function deleteServerCheckMsg(){
		if ($this->request->is('ajax')) {
			$this->loadModel('ServerCheck');
			$id = $this->request->data('id');
			if(empty($id)){
				echo 'fail';
				die;
			}
			$query = $this->ServerCheck->query();
			$query->delete()->where(['id'=>$id])->execute();
			$this->add_system_log(200, 0, 4, '서버 점검 메세지 삭제 ('.$id.')');
			echo 'success';
			die;
		}
	}
	/* 서버 체크 시작 / 중지 */
	public function statusServerCheckMsg(){
		if ($this->request->is('ajax')) {
			$this->loadModel('ServerCheck');
			$id = $this->request->data('id');
			$status = $this->request->data('status');
			if(empty($id) || empty($status)){
				echo 'fail';
				die;
			}
			if($status == 'Y'){
				$query = $this->ServerCheck->query();
				$query->update()->set(['status' => 'N'])->where(['status' => 'Y'])->execute();
			}
			$data = array();
			$data['status'] = $status;
			$data['updated'] = date('Y-m-d H:i:s');
			$data['last_id'] = $this->Auth->user('id');

			$update_server_check = $this->ServerCheck->get($id);
			$save_data = $this->ServerCheck->patchEntity($update_server_check, $data);
			if($this->ServerCheck->save($save_data)) {
				$this->add_system_log(200, 0, 3, '서버 점검 동작 ('.$id.' : '.$status.')');
				echo 'success';
				die;
			}
			$this->add_system_log(200, 0, 3, '서버 점검 동작 실패 ('.$id.' : '.$status.')');
			echo 'fail';
			die;
		}
	}
	/* 서버 체크 메세지 리스트 */
	public function getServerCheckMsg(){
		if ($this->request->is('ajax')) {
			$this->loadModel('ServerCheck');
			$query = $this->ServerCheck->find()->select(['id','message','status','created','updated','last_admin'=>'u.name']);
			$list = $query->join(['u' => ['table' => 'users','type' => 'left','conditions' => 'u.id = last_id']])->all();
			echo json_encode($list); 
			die;
		}
	}

}
