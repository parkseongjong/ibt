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

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Network\Exception\NotFoundException;

/**
	*********************

	- 작성자 : 이충현
	- 최초 작성일 : 2021-07-12
	- 한스바이오텍 내부 직원 거래소 이용 불가하도록 설정하는 테이블 조회, 추가, 수정, 삭제
	- 최근 수정일 : 

	*********************
 */
class EmployeesController extends AppController
{
	// 1. 리스트
	public function employeesList (){
		$this->loadModel('Employees');
		$settings = array('limit' => 20);
		$query = $this->Employees->find()->select(['id','name','phone_number','created','updated','admin_name'=>'u.name']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = last_id']]);
		if($this->request->query('sort_value')){ // 최신순 오래된 순
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			if(empty($order_value)){
				$order_value = 'Employees.id';
			}
			$query = $query->order([$order_value=>$sort_value]);
		}else{
			$query = $query->order(['Employees.id'=> 'DESC']);
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

		$this->set('employees_list',$collectdata);
	}
	// 2. 추가
	public function addEmployee(){
		if($this->request->is('post')){
			$this->loadModel('Employees');
			$data = $this->request->data;
			if(empty($data) || empty($data['name']) || empty($data['phone_number'])){
				$this->Flash->error('비어 있는 값이 있습니다');
				return;
			}
			$insertArr = [];
			$insertArr['name'] = strip_tags($data['name']);
			$insertArr['phone_number'] = strip_tags($data['phone_number']);
			$insertArr['created'] = date('Y-m-d H:i:s');
			$insertArr['updated'] = date('Y-m-d H:i:s');
			$insertArr['last_id'] = $this->Auth->user('id');
			$employees = $this->Employees->newEntity();
			$employees = $this->Employees->patchEntity($employees, $insertArr);
			if($this->Employees->save($employees)){
				$this->add_system_log(200, 0, 2, '거래소 내부 임직원 추가');
				$this->Flash->success('추가 완료!');
				return $this->redirect(['action'=>'employeesList']);
			}
			$this->add_system_log(200, 0, 2, '거래소 내부 임직원 추가 실패');
			$this->Flash->error('추가 실패! 다시 시도해주세요');
			return;
		}
	}
	// 3. 수정
	public function editEmployee($id = null){
		if(empty($id)){
			return $this->redirect(['action'=>'employeesList']);
		}
		$this->set('id',$id);
		$this->loadModel('Employees');
		$query = $this->Employees->find()->select(['id','name','phone_number','created','updated','admin_name'=>'u.name']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = last_id']]);
		$employee = $query->where(['Employees.id'=>$id])->first();
		$this->set('employee',$employee);
		if($this->request->is('post')){
			$data = $this->request->data;
			if(empty($data) || empty($data['name']) || empty($data['phone_number'])){
				$this->Flash->error('비어 있는 값이 있습니다');
				return;
			}
			$updateArr = [];
			$updateArr['name'] = strip_tags($data['name']);
			$updateArr['phone_number'] = strip_tags($data['phone_number']);
			$updateArr['updated'] = date('Y-m-d H:i:s');
			$updateArr['last_id'] = $this->Auth->user('id');

			$employees = $this->Employees->get($id);
			$employees = $this->Employees->patchEntity($employees, $updateArr);
			if($this->Employees->save($employees)){
				$this->add_system_log(200, 0, 3, '거래소 내부 임직원 ('.$id.') 수정');
				//$this->Flash->success('수정 완료!');
				return $this->redirect(['action'=>'employeesList']);
			}
			$this->add_system_log(200, 0, 3, '거래소 내부 임직원 수정 ('.$id.') 실패');
			$this->Flash->error('수정 실패! 다시 시도해주세요');
			return;
		}
	}
	// 4. 삭제
	public function deleteEmployee(){
		if($this->request->is('ajax')){
			$this->loadModel('Employees');
			$id = $this->request->data('id');
			if(empty($id)){
				echo '삭제할 내용이 없습니다';
				die;
			}
			$query = $this->Employees->query();
			$query->delete()->where(['id IN'=>$id])->execute();
			$this->add_system_log(200, 0, 4, '거래소 내부 임직원 ('.implode(',',$id).') 삭제');
			echo 'success';
			die;
		}
		die;
	}

}