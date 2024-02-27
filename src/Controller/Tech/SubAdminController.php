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
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;


class SubAdminController extends AppController
{
	public function permission()
    {
		
		
		$this->set('title' , 'Permision Access');
		
		if ($this->request->is(['post' ,'put'])) {
			$this->loadModel('PermisionModules');
			$this->loadModel('PermisionAccess');
			$all_modules = $this->PermisionModules->find('all',['fields'=>['id']])->hydrate(false)->toArray();
			$data = $this->request->data;
			//pr($data);die;
			foreach($all_modules as $val){
				if($data[$val['id']] ==1){
					$is_exist = $this->PermisionAccess->find('all',['conditions'=>['user_id'=>$data['user_id'],'permision_module_id'=>$val['id']]])->first();
					if(empty($is_exist)){
						$PermisionAccess = $this->PermisionAccess->newEntity();
						$PermisionAccess = 
						$this->PermisionAccess->patchEntity($PermisionAccess, array('user_id'=>$data['user_id'],'permision_module_id'=>$val['id']));
						$this->PermisionAccess->save($PermisionAccess);
					
					}
				}else{
					$query = $this->PermisionAccess->query();
					$query->delete()
					->where(['user_id'=>$data['user_id'],'permision_module_id'=>$val['id']])
					->execute();
				
				}
			}
			$this->Flash->success(__('Successfully updated.'));
			return $this->redirect(['controller'=>'sub_admin','action' => 'permission']);
						
		
		}
		
		$this->set('Users',$this->Users->find('list', ['keyField' =>'id','valueField' => 'name', 'conditions'=> ["user_type"=>'A','id !='=>1,'enabled'=>'Y']])->toArray());
				
		
	}
	public function form(){
		if ($this->request->is('ajax')) {
			// get all modules 
			$this->loadModel('PermisionModules');
			$all_modules = $this->PermisionModules->find();
			$this->set('all_modules', $all_modules);
			$this->loadModel('PermisionAccess');
			$this->set('all_access',$this->PermisionAccess->find('list', ['keyField' => 'permision_module_id','valueField' => 'access_type','conditions'=> ['user_id'=>$this->request->data['id']]])->hydrate(false)->toArray());
			
		}
	
	}
	
	 public function adminAdd()
    {
		$this->set('title' , 'Add Admin');
        $user = $this->Users->newEntity($this->request->data);
		if ($this->request->is('post')) {
			 $this->request->data['user_type']='A';
			 $this->request->data['enabled']='Y';
			$user = $this->Users->patchEntity($user, $this->request->data);
			
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Admin has been saved.'));
                return $this->redirect(['controller'=>'sub_admin','action' => 'admin_add']);
            }else{
				$this->Flash->error(__('Some Errors Occurred.'));
			}
        }
        
		$user->user_role = $this->Auth->user('role');
        $this->set('user', $user);
	}
	
	public function adminEdit($id = null){
		
		$this->set('title' , 'Edit Admin');
		$user  = $this->Users->get($id);
		if ($this->request->is(['post','put'])) {
			$user = $this->Users->patchEntity($user, $this->request->data);
		
			if ($this->Users->save($user)) {
				$this->Flash->success(__('Admin has been updated.'));
				return $this->redirect(['controller'=>'sub_admin','action' => 'adminManage']);
				
			}
			
		}
		
		$this->set('user',$user);
		
		
	}
	
	 public function adminManage(){
		$this->set('title' , 'Admin');
		$searchData = array();
		$searchData['AND'][] = array("user_type"=>'A','id !='=>1);
		
		$this->set('Users',$this->Paginator->paginate(
						$this->Users, [
							'limit' => $this->pagination_limit,
							'order'=>['id'=>'desc'],
								'conditions'=>$searchData,
						])
				);

		
	}
	public function adminsearch(){
		if ($this->request->is('ajax')) {
			$searchData = array();
			$searchData['AND'][] = array("user_type"=>'A','id !='=>1);
				if(isset($this->request->data['key'])){
					$search = $this->request->data['key'];
					$searchData['OR'][] = array('first_name LIKE' => '%'.$search.'%');
					$searchData['OR'][] = array('last_name LIKE' => '%'.$search.'%');
					$searchData['OR'][] = array('phone_number LIKE' => '%'.$search.'%');
					$searchData['OR'][] = array('email LIKE' => '%'.$search.'%');
			
					$this->set('key',$this->request->data['key']);
				}
			$this->set('Users',$this->Paginator->paginate(
						$this->Users, [
							'limit' => $this->pagination_limit,
							'order'=>['id'=>'desc'],
							'conditions'=>$searchData,
						])
				);

			
			
		}
	}
		public function status(){
		if ($this->request->is('ajax')) { 
			$user = $this->Users->get($this->request->data['id']); // Return article with id 12
			$user->enabled = $this->request->data['status'];
			$this->Users->save($user);
			echo 1;
		}
		die;
		
		
	}
	public function delete(){
		if ($this->request->is('ajax')) { 
			
			$query = $this->Users->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			echo 1;
		}
		die;
		
	}
	
	
	
	 
}
