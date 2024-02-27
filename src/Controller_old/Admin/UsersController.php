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
use Cake\Mailer\Email;

class UsersController extends AppController
{
	public function dashboard(){ 
			
			$this->set('title' , 'Dashboard');
	}
   
    public function login()
    {
        $this->set('title' , 'GalaxyIco!: Login');
        if ($this->request->is('post')) {
            if (Validation::email($this->request->data['username'])) {
                $this->Auth->config('authenticate', [
                    'Form' => [
                        'fields' => ['username' => 'email']
                    ]
                ]);
                $this->Auth->constructAuthenticate();
                $this->request->data['email'] = $this->request->data['username'];
                unset($this->request->data['username']);
            }

            $user = $this->Auth->identify();

            if ($user && $user['enabled']=='Y' && $user['user_type']=='A' && $user['is_deleted']=='N') {
                $this->Auth->setUser($user);
                $this->loadModel('LoginLogs');
                $new_log = $this->LoginLogs->newEntity();
                $data['user_id'] = $user['id'];
                $data['ip_address'] = $this->get_client_ip();
                $log_patch = $this->LoginLogs->patchEntity($new_log,$data);
                $this->LoginLogs->save($log_patch);
                if(SENDMAIL == 1)
                {

                    $user['msg'] = 'Logged in successfully with IP address '.$user['ip_address'];
                    $email = new Email('default');
                    $email->viewVars(['data'=>$user]);
                    $email->from([$this->setting['email_from']] )
                        ->to($user['email'])
                        ->subject('You are Logged in successfully.')
                        ->emailFormat('html')
                        ->template('login')
                        ->send();
                }
                return $this->redirect($this->Auth->redirectUrl());
            }

            $this->Flash->error(__('Invalid username or password'));
        }
    }


    public function changePassword(){
        $this->set('title',' Change password');
        $users  = $this->Users->get($this->Auth->user('id'));

        if($this->request->is(['post','put'])){

            $users = $this->Users->patchEntity($users, [
                'old_password'  => $this->request->data['old_password'],
                'password'      => $this->request->data['new_password'],
                'new_password'     => $this->request->data['new_password'],
                'confirm_password'     => $this->request->data['confirm_password']
            ],
                ['validate' => 'password']
            );

            if($this->Users->save($users)){
                if(SENDMAIL == 1)
                {

                    $user['msg'] = 'Your password has been changed successfully.';
                    $email = new Email('default');
                    $email->viewVars(['data'=>$users]);
                    $email->from([$this->setting['email_from']] )
                        ->to($users['email'])
                        ->subject('Password reset')
                        ->emailFormat('html')
                        ->template('reset_password')
                        ->send();
                }
                $this->Flash->success('Your password has been updated.');
                $this->redirect(['controller'=>'users','action'=>'changePassword']);
            }else{
                $this->Flash->error('Some Errors Occurred.');
            }
        }
        $this->set('users',$users);
    }
	
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
	
	
	public function deleteProgram()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Users');
			$query = $this->Users->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			echo 1;
		}
		die; 
	
	} 
	
	
	public function profile($id=null)
	{
		if($id==null){
			$this->redirect(['controller'=>'reports','action'=>'users']);
		}
		
		$user  = $this->Users->get($id);
		$currentUserName = $user->username;
		$currentUserEmail = $user->email;
		$this->set('title',$currentUserName.' profile');
		$before_image = $user->image;
		if ($this->request->is(['post','put'])) {
			
			$findExist = $this->Users->find('all',array('conditions'=>array('OR'=>array('email'=>$currentUserEmail,'username'=>$currentUserName),
																			'id !='=>$id)))->first();
																			
			if(empty($findExist)) {
				if(isset($this->request->data['new_password'])){
					$users = $this->Users->patchEntity($user, [
					//'old_password'  => $this->request->data['old_password'],
					'password'      => $this->request->data['new_password'],
					'new_password'     => $this->request->data['new_password'],
					'confirm_password'     => $this->request->data['confirm_password']
					],
					['validate' => 'password']
					);
						
					if($user=$this->Users->save($users)){
						if(SENDMAIL == 1)
						{

							$user['msg'] = 'Your password has been changed successfully.';
							$email = new Email('default');
							$email->viewVars(['data'=>$user]);
							$email->from([$this->setting['email_from']] )
								->to($user['email'])
								->subject('Password reset')
								->emailFormat('html')
								->template('reset_password')
								->send();
						}
						$this->Flash->success('Your password has been updated.');
						return $this->redirect(['action' => 'profile',$id]);
					}else{
						$this->Flash->error('Some Errors Occurred.');
						return $this->redirect(['action' => 'profile',$id]);
					}
				
				}else{ 
					$user = $this->Users->patchEntity($user, $this->request->data);
					if ($this->Users->save($user)) {
						$user = $this->Users->get($id);
						
						if(isset($_FILES['image']) && $_FILES['image']['tmp_name'] !='')
						{
							$filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['image']['name']);
							$ext = pathinfo($filename, PATHINFO_EXTENSION);
							$filename = basename($filename, '.' . $ext) . time() . '.jpg';
							if ($this->uploadImage($_FILES['image']['tmp_name'], $_FILES['image']['type'], 'uploads/user_image/', $filename)){
								$this->createThumbnail($filename, 'uploads/user_image', 'uploads/user_thumb',200,200); 
								$user->image = $filename;
							}
							else  $user->image = $before_image;
						}else  $user->image = $before_image;
						
						
						$this->Users->save($user);
						
						/* $userExist = $this->Users->find('all',array('conditions'=>array('id'=>$id)))->first();
						$this->Auth->setUser($userExist->toArray()); */
						
						$this->Flash->success(__('User has been updated.'));
						return $this->redirect(['action' => 'profile',$id]);
						
					}
				
				
				}
			}
			else {
				$this->Flash->error('User Already Exist with same email, username OR password');
			}
		}
		$this->set('user',$user);	
		
		
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
	
	
	
     
	 
}
