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
namespace App\Controller\Api;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\Validation\Validation;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Datasource\ConnectionManager;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController
{
	
	public function beforeFilter(Event $event)
    {
		 $this->Auth->allow();
		 $this->loadModel('Settings');
		 $setting = $this->Settings->find('all',array('fields'=>['module_name','minimum_limit']))->hydrate(false)->toArray();
		 $this->setting = array_column($setting, 'minimum_limit','module_name');
	}
	
	
	public function setting()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = '';
			if(isset($this->request->data['user_id']) &&  isset($this->request->data['notify']))
			{
				$user_id  = $this->request->data['user_id'];
				$user = $this->Users->get($user_id);
				if(!empty($user)){
					$user->notify = $this->request->data['notify'];				
					$this->Users->save($user);
					$error = false;
					$code = 0;
					if($this->request->data['notify'] =='Y') $message = 'Notification turned ON';
					else  $message = 'Notification turned OFF';
				}else{
					$message = 'No record found';
				}
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function contactUs()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = ''; 
			
			$this->loadModel('ContactUs');
			$contact = $this->ContactUs->newEntity();		
			$contact = $this->ContactUs->patchEntity($contact, $this->request->data);	
			if($this->ContactUs->save($contact))
			{

				$error = false;
				$code = 0;
				$message= 'Thanking you for contacting with us, we will shorlty get back to you.';
			}
			else
			{
				

				foreach($contact->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						$message = $error_text;
						break 2;
					} 
				}

			}
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
			
		}
	
	}
	
	
	public function profile()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = '';
			if(isset($this->request->data['user_id']) )
			{
				$user_data = $this->Users->find()->where(['id'=>$this->request->data['user_id']])->hydrate(false)->first(); 
				if(empty($user_data)) $message = 'No record found';
				else
				{
					$user_id = $user_data['id'];
					$this->loadModel('BlockUsers');
					$query1 = $this->BlockUsers->find();
					$blocking = $query1->select(['count' => $query1->func()->count('*')])
								->where(['user_id'=>$user_id])->hydrate(false)->toArray();
					
					
					$response = $this->ratingSale($user_id);
					$response['id'] =$user_id;
					$response['full_name'] =$user_data['first_name']." ".$user_data['last_name'];
					$response['profile_type'] = $this->profile_type($user_data['profile_type']);
					$response['image'] =$this->userImage($user_data['image'],'thumb');
					$response['since'] =date('d.m.Y',strtotime($user_data['created']->format('Y-m-d H:i:s')));
					$response['block'] = $blocking[0]['count'];
					$this->loadModel('Followings');
					$response['favorite'] = $this->Followings->find('all' ,['contain'=>['post'],'conditions'=>['post.enabled'=>'Y','Followings.user_id'=>$user_id,'favorite_status'=>'Y']])->count();
					
					$this->loadModel('FollowingUsers');
					$query1 = $this->FollowingUsers->find();
					$following = $query1->select(['count' => $query1->func()->count('*')])
								->where(['user_id'=>$user_id])->hydrate(false)->toArray();
					$response['following'] = $following[0]['count'];
					$response['completion'] = $this->profileCompletion($user_data);
					//pr($response);die;
					$error = false;	
					$code = 0;		
				
				}
				
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	
	}
	
	public function verifyAccount()
	{
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset($this->request->data['otp']) && isset($this->request->data['user_id']))
			{
				$user_id =  $this->request->data['user_id'];
				$otp =  $this->request->data['otp'];
				$user  = $this->Users->find('all',array('conditions'=>array('id'=>$user_id,'otp'=>$otp,'is_verified'=>'N')))->hydrate(false)->first();
				
				if(!empty($user))
				{
					if($user['otp'] != $otp) $message = 'Otp is incorrect';
					else
					{
						
						$user = $this->Users->get($user_id);
						$user->is_verified = 'Y';
						$this->Users->save($user);
						$this->addWalletAmount($user_id,'S',$this->setting['registration_point'], $this->setting['amount_expire_in_days']);	
						if($user['referral_user_id'] != ''){
							
							$this->addWalletAmount($user['referral_user_id'],'R',$this->setting['referral_registration'], $this->setting['amount_expire_in_days']);	
						}
						$error = false;
						$code = 0;
						$message = 'Account verified successfully.';
					
					}
				}
				else $message = 'No Record Found';
				
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function updateToken()
	{
		if($this->request->is(['post','put']))
    	{
			$error = $message= $response = ''; $code=1;
			if(isset( $this->request->data['user_id'] ) && isset($this->request->data['device_token']) )
			{
				$users  = $this->Users->get($this->request->data['user_id']);
				$users->device_token = $this->request->data['device_token'];
				$this->Users->save($users);
				$error = false;
				$code = 0;
				$message =  'Device token update';
			}else{
				$error = true;
				$message =  'Incomplete Data';
			}
				
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function signup()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			 $message= $response = ''; 
			$email_exit = $this->Users->find('all',array('fields'=>['id','is_verified'],'conditions'=>array('email'=>$this->request->data['email'])))->first();
			$number_exit = $this->Users->find('all',array('fields'=>['id','is_verified'],'conditions'=>array('phone_number'=>$this->request->data['phone_number'])))->first();
			if(!empty($email_exit) && $email_exit['is_verified'] == 'Y')  $message= 'Email already exist';
			else if(!empty($number_exit && $number_exit['is_verified'] == 'Y'))  $message= 'Phone number exist';
			else{
				if(!empty($email_exit)) $user = $this->Users->get($email_exit['id']);	
				else if(!empty($number_exit)) $user = $this->Users->get($number_exit['id']);	
				else $user = $this->Users->newEntity();			
				
				$user = $this->Users->patchEntity($user, $this->request->data);
				
				if(!$user->errors())
				{
					$random_code = $this->getNewReferralCode();
					$user->referral_code = $random_code;
					//$otp = rand ( 1000 , 9999 );
					$otp =1234;
					$user->otp =$otp;
					$user->raw_password =$this->request->data['password'];
					$refer_user = $this->Users->find('all',array('fields'=>['id'],'conditions'=>array('referral_code'=>$this->request->data['refer_from'])))->first();
					if(!empty($refer_user)){
						$user->referral_user_id = $refer_user['id'];
					}
					
				}
				
				if($saved_user = $this->Users->save($user))
				{
					$user_id =  $saved_user->id;
					$response = $this->Users->find('all',array('conditions'=>array('id'=>$user_id)))->first();
					if(SENDMAIL == 1)
					{
						// success email
						$this->loadModel('EmailTemplate');
						$template = $this->EmailTemplate->find('all',array('conditions'=>array('title'=>'signup')))->hydrate(false)->first();
						$template['description'] = str_replace('{FULL_NAME}', $response['title']." ".$response['first_name']." ".$response['last_name'], $template['description']);
						$template['description'] = str_replace('{OTP}', $otp, $template['description']);
						$template['description'] = str_replace('{PROJECT}', $this->setting['project_title'], $template['description']);
						
						$email = new Email('default');
						$email->from([$this->setting['mail_email_address'] => $this->setting['mail_email_name']] )
						->to($this->request->data['email'])
						->subject($template['subject'])
						->emailFormat('html')
						->send($template['description']); 
					}
					
					$error = false;
					$code = 0;
					$message= 'Congratulations!! You have registered successfully, Please verify your account';
				}
				else
				{
					$error = true;
					
					foreach($user->errors() as $field_key =>  $error_data)
					{
						foreach($error_data as $error_text)
						{
							$message = $error_text;
							break 2;
						} 
					}
					
				}
				
			}
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'response'=> $response,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function logout()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset( $this->request->data['user_id'] ) && isset( $this->request->data['device_token'] ))
			{
				$user_data = $this->Users->find('all',array('conditions'=>array('id'=>$this->request->data['user_id'])))->first();
				if(!empty($user_data))
				{
					if($user_data['device_token'] ==$this->request->data['device_token']  ) $user_data['device_token'] ='';
					$user_data['last_login'] =date('Y-m-d');
					$user_data['is_logged'] =0;
					$this->Users->save($user_data);
					$error = false;
					$code = 0;
					$message =  'Logged out successfully.';
				}else $message =  'User not found.';
			
			}
			else $message =  'Incomplete data.';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('error','code','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('error','code','message','response')));
			
		}
		
	}
	
	public function login()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = ''; 
			
			if(isset( $this->request->data['username'] ) && isset($this->request->data['password']) && isset($this->request->data['device_token']))
			{
				$user = $this->Users->find('all',array('conditions'=>array('OR'=>[['email'=>$this->request->data['username']],['phone_number'=>$this->request->data['username']]] )))->first();
				if(empty($user)) $message =  'Invalid email or password';
				
				else{
					 $this->request->data['username']=$user['email'];
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
					
					if(empty($user)) $message =  'Invalid email or password';
					else if($user['is_verified'] == 'N' ) $message =  'Your account is not verified';
					else if($user['enabled'] == 'N') $message =  'Your account is blocked';
					else
					{
						$error = false;
						$code = 0;
						$message =  'Logged in successfully.';
						$user_data = $this->Users->find('all',array('conditions'=>array('id'=>$user['id'])))->first();
						$user_data['device_token'] = $this->request->data['device_token'];
						$user_data['device_type'] = $this->request->data['device_type'];
						$this->Users->save($user_data);
						$response = $this->Users->find('all',array('conditions'=>array('id'=>$user['id'])))->first();
						$response['image'] = $this->userImage($user['image'],'thumb');
						$this->set(array('user_id'=>$user['id'],'response'=>$response,'code'=>$code,'error'=>false,'message'=> $message,'_serialize'=>array('error','code','message','response','user_id','emailverfied')));
					}
					
				}
			}
			else  $message =  'Incomplete data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			
			
		}
	}
	
	
	public function forgotPassword()
	{
		if($this->request->is('post'))
		{
			$error =true;$code=1;
			$message= $response = '';
			if( isset( $this->request->data['username'] ))
			{
				$username = $this->request->data['username'];
				$user_record = $this->Users->find()
				->select(['id'])
				->where(['enabled'=>'Y','OR'=>['email'=>$username,'phone_number'=>$username]])->first();
				
				if($user_record && !empty($user_record))
				{
					$error = false;
					$code = 0;
					$message= $user_record['id'];
					

				}
				else $message =  'Mobile number / Email does not exist';
			}
			else $message =  'Incomplete data.';
			
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('user_id'=>$message,'code'=>$code,'error'=>$error,'message'=> 'Success','_serialize'=>array('code','error','message','response','user_id')));
		}
	
	}
	
	
	
	public function changePassword()
    {
    	if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = ''; 
			if( isset( $this->request->data['user_id'] ) && isset( $this->request->data['new_password'] ) && isset( $this->request->data['confirm_password'] ) )
			{
			$users  = $this->Users->get($this->request->data['user_id']);
			$users = $this->Users->patchEntity($users, [
							'password'      => $this->request->data['new_password'],
							'new_password'     => $this->request->data['new_password'],
							'confirm_password'     => $this->request->data['confirm_password']
						],
            			['validate' => 'password']
        		);
			if(!$users->errors())
			{
				$users->raw_password = $this->request->data['new_password'];
			}
			if($this->Users->save($users))
			{
				$error=  false;
				$code = 0;
				$message =  'Password changed successfully';
			}
			else
			{
				foreach($users->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						$message = $error_text;
						break 2;
					} 
					
				}
			}
			
			}else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
		}
    }
    public function arcoRefer()
    {
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			
			if(isset($this->request->data['user_id']))
			{
				$error =false;
				$code = 0;
				$this->loadModel('Transactions');
				$add_arco = $this->Transactions->find()->select(['sub_arco_credit' => $query1->func()->count('*')])
								->where(['type'=>'B','credit_debit'=>'+','user_id'=>$user_id])->hydrate(false)->first();
				$minus_arco = $this->Transactions->find()->select(['sub_arco_debit' => $query1->func()->count('*')])
								->where(['type IN'=>[''],'credit_debit'=>'-','user_id'=>$user_id])->hydrate(false)->first();	
				
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
    public function walletAmount()
    {
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset($this->request->data['user_id']))
			{
				$error =false;
				$code = 0;
				$response['refer'] = $this->arcoWallet($this->request->data['user_id'],'refer');
				$response['arco'] = $this->arcoWallet($this->request->data['user_id'],'arco');
			
			}
			
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	
	}
    public function wallet()
    {
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			
			if(isset($this->request->data['user_id']))
			{
				$error =false;
				$code = 0;
				$this->loadModel('Transactions');
				$response['currency']=$this->setting['currency'];
				$response['refer_point'] = $this->arcoWallet($this->request->data['user_id'],'refer');
				$response['arco_point'] = $this->arcoWallet($this->request->data['user_id'],'arco');
				$transaction = $this->Transactions->find();
				$date = $transaction->func()->date_format([
				   'Transactions.created' => 'literal',
					"'%d %b %Y'" => 'literal'
				]);
				$expire = $transaction->func()->date_format([
				   'Transactions.expire_at' => 'literal',
					"'%d %b %Y'" => 'literal'
				]);
				$expire_date = $transaction->func()->date_format([
				   'Transactions.expire_at' => 'literal',
					"'%Y-%m-%d'" => 'literal'
				]);
				$response['earnings'] = 
				$transaction->select(['booking_id'=>'booking.booking_no','date'=>$date,'amount','type','expire'=>$expire,'expire_date'=>$expire_date,'credit_debit'])
				->contain(['booking'])
				->where(['expire_at >='=>date('Y-m-d'),'Transactions.user_id'=>$this->request->data['user_id']])
				->order(['Transactions.id'=>'desc'])
				->hydrate(false)->toArray(); 
				
				
				$arr = array('S'=>'Join point','R'=>'Refer Bonus','B'=>'Arco Bonus');
			
				foreach($response['earnings'] as $k=>$val)
				{
					$response['earnings'][$k]['currency'] = $response['currency'];
					$response['earnings'][$k]['type'] = $arr[$val['type']];
					if($val['expire_date']<date('Y-m-d')) $response['earnings'][$k]['expire']= "Expired at ".$val['expire'];
					else $response['earnings'][$k]['expire']= "Expires on ".$val['expire'];
					
						
				}
 				
			
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
    
	public function myProfile()
	{
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset($this->request->data['user_id']))
			{
				$id = $this->request->data['user_id'];
				$users  = $this->Users->find('all',array('conditions'=>array('id'=>$id,'access_level_id'=>2,'is_verified'=>'Y','enabled'=>'Y')))->hydrate(false)->first();
				if(!empty($users)){
					$users['image']= $this->userImage($users['image'],'thumb');
					$error = false;
					$code = 0;
					$response =  $users;
				} 
				else $message = 'No Record Found';
			
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function editUser()
    {
		if ($this->request->is(array('post','put')))
		{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset($this->request->data['user_id']))
			{
				$id = $this->request->data['user_id'];
				$users  = $this->Users->find('all',array('conditions'=>array('id'=>$id,'access_level_id'=>2)))->hydrate(false)->first();
				if(!empty($users))
				{
					$email_exit = $this->Users->find('all',array('fields'=>['id'],'conditions'=>array('email'=>$this->request->data['email'],'is_verified'=>'Y','id !='=>$id)))->first();
					$number_exit = $this->Users->find('all',array('fields'=>['id','is_verified'],'conditions'=>array('phone_number'=>$this->request->data['phone_number'],'is_verified'=>'Y','id !='=>$id)))->first();
					if(!empty($email_exit))  $message= 'Email already exist';
					else if(!empty($number_exit))  $message= 'Phone number exist';
					else
					{
						$users  = $this->Users->get($id);
						$user = $this->Users->patchEntity($users,$this->request->data);
						$before_image = $user->image;
						if($saveUser = $this->Users->save($user))
						{ 
							$user = $this->Users->get($id);
							if(isset($this->request->data['image']) && $_FILES['image']['tmp_name'] !='')
							{
								$filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['image']['name']);
								$ext = pathinfo($filename, PATHINFO_EXTENSION);
								$filename = basename($filename, '.' . $ext) . time() . '.jpg';
								if ($this->uploadImage($_FILES['image']['tmp_name'], $_FILES['image']['type'], 'uploads/user_image/', $filename)){
									$this->createThumbnail($filename, 'uploads/user_image', 'uploads/user_thumb',200,200); 
									$user->image  = $filename;
								}
								else  $user->image = $before_image;
							}else  $user->image = $before_image;
							$this->Users->save($user);
							if($this->request->data['change_password'] != '')
							{
								$users  = $this->Users->get($id);
								$users = $this->Users->patchEntity($users, [
										'password'      => $this->request->data['change_password'],
										'new_password'     => $this->request->data['change_password'],
										'confirm_password'     => $this->request->data['change_password']
									],
									['validate' => 'password']
								);
								if(!$users->errors())
								{
									$users->raw_password = $this->request->data['change_password'];
								}
								$this->Users->save($users);
							}
							$response  = $this->Users->find('all',array('conditions'=>array('id'=>$id)))->hydrate(false)->first();
							$response['image'] = $this->userImage($response['image'],'thumb'); 
							$message = "Profile updated successfully";
							$error = false;
							$code = 0;
						}
						else
						{
							foreach($user->errors() as $field_key =>  $error_data)
							{
								foreach($error_data as $error_text)
								{
									$message = $error_text;
									break 2;
								} 
								
							}
						}
						
					}
				}else $message = 'No Record Found';
			}else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	
	}
	

	
}

	
