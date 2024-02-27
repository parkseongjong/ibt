<?php

namespace App\Controller\Tech;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Exception\NotFoundException;

class LeavingController extends AppController {

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }
	public function leavingList(){
		$this->loadModel('LeavingUsers');
		$settings = array('limit' => 20);
		$query = $this->LeavingUsers->find()->select(['id','email','name','phone_number','last_login','created','annual_membership','leave_date']);
		//$query = $query->join(['a' => ['table' => 'asset_waiver','type' => 'left','conditions' => ['a.user_id = LeavingUsers.id','is_leaving'=>'Y']]]);
		if($this->request->query('search_value')){ // 검색어
			$query = $query->where(['name' => $this->request->query('search_value')]);
		}

		if ($this->request->query('start_date')) { 
			$query = $query->where(['DATE(LeavingUsers.created) >= ' => $this->request->query('start_date')]);
		}

		if ($this->request->query('end_date')) { 
			$query = $query->where(['DATE(LeavingUsers.created) <= ' => $this->request->query('end_date')]);
		}

		if($this->request->query('sort_value')){ // 최신순 오래된 순
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			if(empty($order_value)){
				$order_value = 'LeavingUsers.id';
			}
			$query = $query->order([$order_value=>$sort_value]);
		} else {
			$query = $query->order(['LeavingUsers.id'=> 'DESC']);
		}

		if($this->request->query('pagination')){ // 페이지당 리스트 갯수
			$settings = array('limit' => $this->request->query('pagination'));
		}

		try {
			$collectdata =  $this->Paginator->paginate($query,$settings);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$collectdata =  $this->Paginator->paginate($query,$settings);
		}
		$this->set('leaving_list',$collectdata);
	}
}
?>