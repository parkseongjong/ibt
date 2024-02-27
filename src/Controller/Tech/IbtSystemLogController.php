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

namespace App\Controller\Tech;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Event\Event;
use Cake\View\Helper\SessionHelper;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\Log\Log;
use Cake\Network\Exception\NotFoundException;

class IbtSystemLogController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
		//$this->loadComponent('Security');
		//$this->loadComponent('Cookie');
	}
	/* 로그 리스트 */
	public function loglist(){
		$this->loadModel('IbtSystemLog');
		$this->loadModel('Users');
		$settings = array('limit' => 20);
		$query = $this->IbtSystemLog->find()->select(['id','log_level','admin_id','action','user_agent','user_ip','user_id','url','description','created','a.name','u.name']);
		$query = $query->join(['a' => ['table' => 'users','type' => 'left','conditions' => 'a.id = admin_id']]);
		$query = $query->join(['u' => ['table' => 'users','type' => 'left','conditions' => 'u.id = user_id']]);

		if($this->request->query('search_value')){ // 검색어
			$search_value = $this->request->query('search_value');
			if($this->request->query('search_type')){
				$search_type = $this->request->query('search_type');
				if($search_type != 'all'){
					$query = $query->where([$search_type => $search_value]);
				} else if ($search_type == 'all'){
					$query = $query -> where(['OR'=>[
						['user_id'=> $search_value]
						,['admin_id'=> $search_value]
						,['a.name'=> $search_value]
						,['user_ip'=> $search_value]
						,['url'=> $search_value]
						,['u.name'=> $search_value]
						,['description like'=> '%'.$search_value.'%']
					]]);
				}
			}
		}

		if ($this->request->query('start_date')) { 
			$query = $query -> where(['DATE(IbtSystemLog.created) >= ' => $this->request->query('start_date')]);
		}

		if ($this->request->query('end_date')) { 
			$query = $query -> where(['DATE(IbtSystemLog.created) <= ' => $this->request->query('end_date')]);
		}

		if($this->request->query('log_level')){ // 로그 레벨 (log level) 선택
			$query = $query->where(['log_level'=>$this->request->query('log_level')]);
		}

		if($this->request->query('action')){ // 동작(action) 선택
			$query = $query->where(['action'=>$this->request->query('action')]);
		}

		if($this->request->query('sort_value')){ // 최신순 오래된 순
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			if(empty($order_value)){
				$order_value = 'IbtSystemLog.id';
			}
			$query = $query->order([$order_value=>$sort_value]);
		}else{
			$query = $query->order(['IbtSystemLog.id'=> 'DESC']);
		}

		if($this->request->query('limit')){ // 페이지당 리스트 갯수
			$settings = array('limit' => $this->request->query('limit'));
		}

		try {
			$collectdata =  $this->Paginator->paginate($query,$settings);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$collectdata =  $this->Paginator->paginate($query,$settings);
		}

		$this->set('log_list',$collectdata);
	}
	/* 유저 로그인 로그  */
	public function userloginlog(){
		$this->loadModel('loginLogs');
		$this->loadModel('Users');
		$settings = array('limit' => 20);
		$query = $this->loginLogs->find()->select(['id','user_id','ip_address','created','modified','u.name','u.phone_number']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'left','conditions' => 'u.id = user_id']]);
		$query = $query->where(['u.user_type'=>'U']);

		if($this->request->query('search_value')){ // 검색어
			$search_value = $this->request->query('search_value');
			if(is_numeric($search_value)){
				$query = $query -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
			} else {
				$query = $query -> where(['u.name' => $search_value]);
			}
		}

		if ($this->request->query('start_date')) { 
			$query = $query -> where(['DATE(loginLogs.created) >= ' => $this->request->query('start_date')]);
		}

		if ($this->request->query('end_date')) { 
			$query = $query -> where(['DATE(loginLogs.created) <= ' => $this->request->query('end_date')]);
		}

		if($this->request->query('sort_value')){ // 최신순 오래된 순
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			if(empty($order_value)){
				$order_value = 'loginLogs.id';
			}
			$query = $query->order([$order_value=>$sort_value]);
		}else{
			$query = $query->order(['loginLogs.id'=> 'DESC']);
		}

		if($this->request->query('limit')){ // 페이지당 리스트 갯수
			$settings = array('limit' => $this->request->query('limit'));
		}

		try {
			$collectdata =  $this->Paginator->paginate($query,$settings);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$collectdata =  $this->Paginator->paginate($query,$settings);
		}

		$this->set('log_list',$collectdata);
	}
	/* 유저 로그인 실패 로그  */
	public function userloginfaillog(){
		$this->loadModel('ErrorLoginLogs');
		$this->loadModel('Users');
		$settings = array('limit' => 20);
		$query = $this->ErrorLoginLogs->find()->select(['id','user_id','created','u.name','u.phone_number','error']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'left','conditions' => 'u.id = user_id']]);
		$query = $query->where(['u.user_type'=>'U']);

		if($this->request->query('search_value')){ // 검색어
			$search_value = $this->request->query('search_value');
			if(is_numeric($search_value)){
				$query = $query -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
			} else {
				$query = $query -> where(['u.name' => $search_value]);
			}
		}

		if ($this->request->query('start_date')) { 
			$query = $query -> where(['DATE(ErrorLoginLogs.created) >= ' => $this->request->query('start_date')]);
		}

		if ($this->request->query('end_date')) { 
			$query = $query -> where(['DATE(ErrorLoginLogs.created) <= ' => $this->request->query('end_date')]);
		}

		if($this->request->query('sort_value')){ // 최신순 오래된 순
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			if(empty($order_value)){
				$order_value = 'ErrorLoginLogs.id';
			}
			$query = $query->order([$order_value=>$sort_value]);
		}else{
			$query = $query->order(['ErrorLoginLogs.id'=> 'DESC']);
		}

		if($this->request->query('limit')){ // 페이지당 리스트 갯수
			$settings = array('limit' => $this->request->query('limit'));
		}

		try {
			$collectdata =  $this->Paginator->paginate($query,$settings);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$collectdata =  $this->Paginator->paginate($query,$settings);
		}

		$this->set('error_log_list',$collectdata);
	}

}
