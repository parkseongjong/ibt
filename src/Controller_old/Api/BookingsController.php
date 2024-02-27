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

use Cake\ORM\TableRegistry;
use Cake\Event\Event;


/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class BookingsController extends AppController
{
	public function beforeFilter(Event $event)
    {
		 $this->Auth->allow();
		 $this->loadModel('Settings');
		 $setting = $this->Settings->find('all',array('fields'=>['module_name','minimum_limit']))->hydrate(false)->toArray();
		 $this->setting = array_column($setting, 'minimum_limit','module_name');
		 
	}
	public function stayReview()
	{
		if($this->request->is(['post','put']))
    	{
			
			$error =true;$code=1;
			$message=''; $response = array(); 
			if(isset( $this->request->data['booking_id']) && isset( $this->request->data['review']) && isset( $this->request->data['rating'])  )
			{
				
				$booking  = $this->Bookings->find()->select(['id'])->where(['from_date <='=>date('Y-m-d'),'to_date >='=>date('Y-m-d'),'id' => $this->request->data['booking_id']])->hydrate(false)->toArray();
				if(!empty($booking))
				{
					$this->loadModel('StayReviews');
					$review_exist  = $this->StayReviews->find('all',array('fields'=>['id'],'conditions'=>array('booking_id'=>$this->request->data['booking_id'])))->hydrate(false)->first();	
					if(!empty($review_exist)) $message ="You have already given the review for this booking";
					else
					{
						$review = $this->StayReviews->newEntity();			
						$review= $this->StayReviews->patchEntity($review,$this->request->data);
						if($save_review = $this->StayReviews->save($review))
						{
							$error = false;
							$code = 0;
							$message= "Thank you for your valuable feedback";
						
						}
						else
						{
							foreach($review->errors() as $field_key =>  $error_data)
							{
								foreach($error_data as $error_text)
								{
									$message = $error_text;
									break 2;
								} 
							}
						}
						
					}
				}else $message= "No current booking found";
			
			}
			else $message = 'Incomplete Data';			
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
				
		}
			

	}
	
	public function details()
	{
		if($this->request->is(['post','put']))
    	{
			
			$error =true;$code=1;
			$message=''; $response = array(); 
			if(isset( $this->request->data['user_id']) && isset( $this->request->data['booking_id']) )
			{
				$error=false;
				$code = 0;
				$response = $this->Bookings->find()
				->contain(['review'])
				->where(['Bookings.user_id'=>$this->request->data['user_id'],'Bookings.id'=>$this->request->data['booking_id']])
				->hydrate(false)->first(); 
				
				if(!empty($response))
				{
					$date_arr = array();
					$from_date= $response['from_date']  =date('Y-m-d',strtotime($response['from_date']->format('Y-m-d')));
					$end_date = $response['to_date']  =date('Y-m-d',strtotime($response['to_date']->format('Y-m-d')));
					
					while (strtotime($from_date) <= strtotime($end_date)) {
						$date_arr[] = $from_date;
						$from_date=date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
					}
					
					$response_arr = $this->categoryData($response,$date_arr);
					$response = array_merge($response,$response_arr);
					
				
				}
				else $message = 'No record found';		
				
			}
		}
		else $message = 'Incomplete Data';			
		if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
		else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
	}
	
	public function listing()
	{
		if($this->request->is(['post','put']))
    	{
			
			$error =true;$code=1;
			$message=''; $response = array(); 
			if(isset( $this->request->data['user_id']) && isset( $this->request->data['type']) )
			{
				$error=false;
				$code = 0;
				$user_id = $this->request->data['user_id'];
				$type = $this->request->data['type'];
				$date= date('Y-m-d');
				if($type=='upcoming'){
					$where = array('is_cancel'=>'N','from_date >'=>$date,'user_id' => $user_id);
				}else if($type=='completed'){
					$where = array('is_cancel'=>'N','to_date <'=>$date,'user_id' => $user_id);
				}else if($type=='today'){
					$where = array('is_cancel'=>'N','from_date <='=>$date,'to_date >='=>$date,'user_id' => $user_id);
				}else if($type="cancelled"){
					$where = array('is_cancel'=>'Y');
				}else $where = array('user_id' => 0);
				$booking = $this->Bookings->find();
				$from = $booking->func()->date_format([
			   'from_date' => 'literal',
				"'%d %b %Y, %a'" => 'literal'
				]);
				$to = $booking->func()->date_format([
				   'to_date' => 'literal',
					"'%d %b %Y, %a'" => 'literal'
				]);
				/*$response  = $booking->select(['Bookings.category_plan_id','booking_id'=>'Bookings.id','Bookings.booking_no','Bookings.adult','Bookings.kid','fromdate'=>$from,'todate'=>$to,'final_rate_to_pay','checkin'=>'booking_detail.check_in','checkout'=>'booking_detail.check_out','category_plan.id','plantitle'=>'plan.plan_title','planname'=>'plan.plan_name','categoryname'=>'category.category_name'])
				->contain(['category_plan'=>['plan','category']])
				->where($where)->hydrate(false)->toArray();*/
				$response  = $booking->select(['booking_id'=>'Bookings.id','Bookings.booking_no','Bookings.check_in','Bookings.check_out','fromdate'=>$from,'todate'=>$to,'total_adult','total_kid','review_id'=>'review.id','stay_review_id'=>'stay_review.id'])
				->contain(['review','stay_review'])
				->where($where)->hydrate(false)->toArray();
				
				
				
			}
			else $message = 'Incomplete Data';			
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	
	public function review()
	{
		if($this->request->is(['post','put']))
    	{
			
			$error =true;$code=1;
			$message=''; $response = array(); 
			if(isset( $this->request->data['booking_id']) && isset( $this->request->data['value_for_money']) && isset( $this->request->data['cleanliness']) && isset( $this->request->data['food']) && isset( $this->request->data['facility']) && isset( $this->request->data['location']) && isset( $this->request->data['title']) && isset( $this->request->data['review']))
			{
				$this->loadModel('Reviews');
				$review_exist  = $this->Reviews->find('all',array('fields'=>['id'],'conditions'=>array('booking_id'=>$this->request->data['booking_id'])))->hydrate(false)->first();	
				if(!empty($review_exist)) $message ="You have already given the review for this booking";
				
				else
				{			
				
					if($this->request->data['value_for_money'] >5 ) $this->request->data['value_for_money']=5;
					if($this->request->data['cleanliness'] >5 ) $this->request->data['cleanliness']=5;
					if($this->request->data['food'] >5 ) $this->request->data['food']=5;
					if($this->request->data['facility'] >5 ) $this->request->data['facility']=5;
					if($this->request->data['location'] >5 ) $this->request->data['location']=5;
					$this->request->data['avg_rating']  = round(($this->request->data['value_for_money'] + $this->request->data['cleanliness'] + $this->request->data['food'] + $this->request->data['facility']+ $this->request->data['location'])/5);
					$review = $this->Reviews->newEntity();			
					$review= $this->Reviews->patchEntity($review,$this->request->data);
					if($save_review = $this->Reviews->save($review))
					{
						if(isset($this->request->data['image']) && $_FILES['image']['tmp_name'] !='')
						{
							$filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['image']['name']);
							$ext = pathinfo($filename, PATHINFO_EXTENSION);
							$filename = basename($filename, '.' . $ext) . time() . '.jpg';
							if ($this->uploadImage($_FILES['image']['tmp_name'], $_FILES['image']['type'], 'uploads/gallery/', $filename)){
								$review = $this->Reviews->get($save_review->id);
								$review->image  = $filename;
								$this->Reviews->save($review);
							}
							
						}
						else if(isset($this->request->data['video']))
						{
							$filename = preg_replace('/[^a-zA-Z0-9.]/', '_', $this->request->data['video']['name']);
							$ext = pathinfo($filename, PATHINFO_EXTENSION);
							$filename = basename($filename, '.' . $ext) . time() . '.' . $ext;
							if ($this->uploadVideo($_FILES['video']['tmp_name'], $_FILES['video']['type'], 'uploads/gallery/', $filename)) 
							{
								$review = $this->Reviews->get($save_review->id);
								$review->video  = $filename;
								$this->Reviews->save($review);
							}
						}
						$error = false;
						$code = 0;
						$message="Thank you for your valuable feedback";
					}else{
						foreach($review->errors() as $field_key =>  $error_data)
						{
							foreach($error_data as $error_text)
							{
								$message = $error_text;
								break 2;
							} 
						}
					}
				}
				
			}
			else $message = 'Incomplete Data';			
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function success()
	{
		if($this->request->is(['post','put']))
    	{
			
			$error =true;$code=1;
			$message=''; $response = array(); 
			if( isset($this->request->data['from_date']) && isset( $this->request->data['to_date']) && isset( $this->request->data['rooms'])   && isset( $this->request->data['user_type']) && isset( $this->request->data['category_plan_id']) && isset( $this->request->data['category_plan_id'])  && isset( $this->request->data['refer'])  && isset( $this->request->data['arco'])  && isset( $this->request->data['off'])  && isset( $this->request->data['price_per_night'])  && isset( $this->request->data['total_price_per_night']) && isset( $this->request->data['grand_total']) && isset( $this->request->data['pay_at_hotel']) && isset( $this->request->data['payment']) && isset( $this->request->data['promo_off']) && isset( $this->request->data['promo_code']) && isset( $this->request->data['title']) && isset( $this->request->data['first_name']) && isset( $this->request->data['last_name']) && isset( $this->request->data['email']) && isset( $this->request->data['country_code']) && isset( $this->request->data['phone_number']) && isset( $this->request->data['check_in']) && isset( $this->request->data['check_out'])  && isset( $this->request->data['dollar_rate']) )
			{
				
				$rooms_arr = json_decode($this->request->data['rooms']);
				$adult_arr= $kid_arr=array();
				$this->request->data['total_rooms'] = 0;
				foreach($rooms_arr as $room){
					  if($room->adults_count >0 || $room->children_count >0 ){
						array_push($adult_arr,$room->adults_count);
						array_push($kid_arr,$room->children_count);
						$this->request->data['total_rooms']++;
					}
				}
				if(!empty($adult_arr) && !empty($kid_arr) && max($adult_arr) >0)
				{
					
					$error = false;
					$this->request->data['adult'] = max($adult_arr);
					$this->request->data['kid'] = max($kid_arr);
					$this->request->data['total_adult'] = array_sum($adult_arr);
					$this->request->data['total_kid'] = array_sum($kid_arr);
					$this->request->data['total_rooms'] = count($rooms_arr);
					$from_date=$start_date = $this->request->data['from_date'];
					$end_date = $this->request->data['to_date'];
					
					$date_arr = array();
					
					while (strtotime($from_date) <= strtotime($end_date)) {
						$date_arr[] = $from_date;
						$from_date=date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
					}
					$this->request->data['no_days'] = count($date_arr);
					if(isset($this->request->data['user_id']) && $this->request->data['user_id'] > 0) $this->request->data['user_id'] = $this->request->data['user_id'];
					$this->request->data['booking_no'] = $this->bookingid();
					$booking= $this->Bookings->newEntity();
					$booking= $this->Bookings->patchEntity($booking,$this->request->data);
					if($saved_booking = $this->Bookings->save($booking))
					{
						$error=false;
						$code = 0;
						$booking_id =  $saved_booking->id;
						$user_id =  $saved_booking->user_id;
						// Referral and points
						if($user_id != '')
						{
							if($saved_booking->arco > 0) $this->debitWalletAmount($user_id,'B',$saved_booking->arco,$booking_id);	
							if($saved_booking->refer > 0) $this->debitWalletAmount($user_id,'R',$saved_booking->refer,$booking_id);
							if($this->setting['booking_point'] > 0)	$this->addWalletAmount($user_id,'B',$this->setting['booking_point'], $this->setting['amount_expire_in_days'],$booking_id);	
							$referral_user_id = $this->get_referral_user($user_id);
							if($this->setting['referral_booking_point'] > 0 && $referral_user_id !='') $this->addWalletAmount($referral_user_id,'B',$this->setting['referral_booking_point'], $this->setting['amount_expire_in_days'],$booking_id);
						}
						
						$response=  $this->request->data;
						
						$response_arr = $this->categoryData($this->request->data,$date_arr);
						$response = array_merge($this->request->data,$response_arr);
						// Book room 
						$this->bookRooms($response['category_id'],$this->request->data['from_date'],$this->request->data['to_date'],$this->request->data['total_rooms']);
						
						$message = 'Your booking has been confirmed.';		
					
					}else{
						
						foreach($booking->errors() as $field_key =>  $error_data)
						{
							foreach($error_data as $error_text)
							{
								$message = $error_text;
								break 2;
							} 
						}
					
					}
				}
				else $message = 'No booking found';		
				
			}	
				
			else $message = 'Incomplete Data';			
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	
	
}

	
