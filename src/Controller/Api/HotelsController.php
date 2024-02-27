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
class HotelsController extends AppController
{
	public function beforeFilter(Event $event)
    {
		 $this->Auth->allow();
		 $this->loadModel('Settings');
		 $setting = $this->Settings->find('all',array('fields'=>['module_name','minimum_limit']))->hydrate(false)->toArray();
		 $this->setting = array_column($setting, 'minimum_limit','module_name');
		 
	}
	
	function rateCalculation($data,$date_arr,$rooms_arr)
	{
		
		$response = array();
		if($data['user_type'] =='N') $select = array('person_type','no_person','rate'=>'normal_rate','off'=>'off_normal','arco'=>'arco_normal','wallet'=>'wallet_normal');
		else  $select = array('person_type','no_person','rate'=>'agent_rate','off'=>'off_agent','arco'=>'arco_agent','wallet'=>'wallet_agent');
		
		$count = count($date_arr) * ($data['adult'] + $data['kid']);
		
		$this->loadModel('Rates');
		$record  = $this->Rates->find()->select($select)->where(['date >='=>$data['from_date'],'date <='=>$data['to_date'],'category_id' => $data['category_id'], 'plan_id' =>  $data['plan_id'],'OR' => [['person_type'=>'A','no_person <='=>$data['adult']], ['person_type'=>'K','no_person <='=>$data['kid']]]])->hydrate(false)->toArray();
		
		$response['total_price_per_night'] =$response['grand_total'] = $response['price_per_night'] = $response['arco']=$response['refer']=$response['off'] =$total_rate=$pay_rate= 0;
		
		$response['total_price_per_night_dollar'] =$response['grand_total_dollar'] = $response['price_per_night_dollar'] = $response['arco_dollar']=$response['refer_dollar']=$response['off_dollar'] =$total_rate=$pay_rate= 0;
		$is_arco = $is_wallet='Y';
		if(count($record)==$count)
		{
			foreach($rooms_arr as $room){
				$adult_count = $room->adults_count;
				$kid_count = $room->children_count;
			
				foreach($record as $rate)
				{
					if( ($rate['person_type']=='A' && $rate['no_person'] <=$adult_count) || ($rate['person_type']=='K' && $rate['no_person'] <=$kid_count) ){
					
						if($rate['arco']=='N') $is_arco= 'N';
						if($rate['wallet']=='N') $wallet= 'N';
						$total_rate=$total_rate+$rate['rate'];
						if($rate['off']>0){
							$off= round(($rate['off'] / 100) * $rate['rate'] ,2);
							$pay_rate=$pay_rate+($rate['rate'] - $off);
						}else $pay_rate=$pay_rate+$rate['rate'];
					}
				}
			}
		
			$response['price_per_night'] = round($pay_rate/count($date_arr),1);
			$response['price_per_night_dollar'] = $this->convertDollar($response['price_per_night']);
			if($total_rate != $pay_rate){
				$response['off'] = round(((($total_rate -$pay_rate )/$total_rate )  *100),1);
				$response['off_dollar'] = $this->convertDollar($response['off']);
			}
		}
		$response['grand_total'] =$pay_rate;
		$response['grand_total_dollar'] =$this->convertDollar($response['grand_total']);
		$this->loadModel('Transactions');
		if($is_arco=='Y' && $data['user_id'] > 0){
			$arco_amount = $this->arcoWallet($data['user_id'],'arco');
			if($arco_amount >0 && $this->setting['%_arco_wallet_booking'] >0){
				$response['arco'] = round(($this->setting['%_arco_wallet_booking'] / 100) * $arco_amount);
				$response['arco_dollar'] =$this->convertDollar($response['arco']);
				$response['grand_total'] = $response['grand_total'] - $response['arco'];
				$response['grand_total_dollar'] =$this->convertDollar($response['grand_total']);
				
			}
		}
		
		if($is_wallet=='Y' && $data['user_id'] >0){
			$refer_amount = $this->arcoWallet($data['user_id'],'refer');
			if($refer_amount >0 && $this->setting['%_wallet_booking'] >0){
				$response['refer'] = round(($this->setting['%_wallet_booking'] / 100) * $refer_amount);
				$response['refer_dollar'] =$this->convertDollar($response['refer_dollar']);
				$response['grand_total'] = $response['grand_total'] - $response['refer'];
				$response['grand_total_dollar'] =$this->convertDollar($response['grand_total']);
			}
		}
		$response['total_price_per_night'] = round($response['grand_total']/count($date_arr),1);
		$response['total_price_per_night_dollar'] =$this->convertDollar($response['total_price_per_night']);
		return $response;die;
	
	}
	
	
	public function promoCode()
	{
		$error =true;$code=1;
		$message=''; $response = array(); 
		if( isset($this->request->data['category_id']) &&  isset($this->request->data['promo']))
		{
			$this->loadModel('PromoCodes');
			$data =  $this->PromoCodes->find()->select(['amount'])->where(['FIND_IN_SET(\''. $this->request->data['category_id'] .'\',category_id)','promocode =' => $this->request->data['promo'],'enabled' =>'Y','start_date <= '=>date('Y-m-d'),'end_date >=' => date('Y-m-d') ])->hydrate(false)->first();
			
			if(empty($data)) $message = 'Invalid promocode';
			else{
				$error=false;
				$code = 0;
				$message= "Successfully applied";
				$response = array('inr'=>$data['amount'],'dollar'=>$this->convertDollar($data['amount']));
			}
		
		}
		else $message = 'Incomplete Data';			
		if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
		else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
	
	}
	
	public function details()
	{
		$error =true;$code=1;
		$message=''; $response = array(); 
		if( isset($this->request->data['from_date']) && isset( $this->request->data['to_date']) && isset( $this->request->data['rooms'])   && isset( $this->request->data['user_type']) && isset( $this->request->data['category_plan_id']) )
		{
			$rooms_arr = json_decode($this->request->data['rooms']);
			$adult_arr= $kid_arr=array();
			$this->request->data['total_room'] = 0;
			$this->request->data['dollar_rate'] = $this->setting['dollar_price'];
			foreach($rooms_arr as $room){
				 if($room->adults_count >0 || $room->children_count >0 ){
					array_push($adult_arr,$room->adults_count);
					array_push($kid_arr,$room->children_count);
					$this->request->data['total_room']++;
				}
			}
			$this->request->data['adult'] = $this->request->data['kid'] =0 ;
			if(!empty($adult_arr)) $this->request->data['adult'] = max($adult_arr);
			if(!empty($kid_arr)) $this->request->data['kid'] = max($kid_arr);
			if($this->request->data['adult'] > 0 || $this->request->data['kid'] > 0 )
			{
				$this->request->data['total_adult'] = array_sum($adult_arr);
				$this->request->data['total_kid'] = array_sum($kid_arr);
				$this->request->data['decode_room'] = $rooms_arr;	
				if(isset($this->request->data['user_id']) && $this->request->data['user_id'] > 0) $this->request->data['user_id'] = $this->request->data['user_id'];
				else  $this->request->data['user_id']= 0;
				$from_date=$start_date = $this->request->data['from_date'];
				$end_date = $this->request->data['to_date'];
				
				$date_arr = array();
				
				while (strtotime($from_date) <= strtotime($end_date)) {
					$date_arr[] = $from_date;
					$from_date=date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
				}
				$response_arr = $this->categoryData($this->request->data,$date_arr);
				
				if(!empty($response_arr))
				{
					$response = array_merge($this->request->data,$response_arr);
					$rate_arr = $this->rateCalculation($response,$date_arr,$rooms_arr);
					$response = array_merge($response,$rate_arr);
					$code = 0;
					$error=false;
					
				}else $message = 'Rooms has been booked, pls try again.';			 
				
			}
		}
		else $message = 'Incomplete Data';			
		if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
		else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
	
	}
	
	
	public function minPrice($data)
	{
		
		$per_day_price= 0;
		$this->request->data = $data;
		$this->loadModel('Categories');
		$getCategory = $this->Categories->find()
			->select(['id'])
			->contain(['myplans' => function (\Cake\ORM\Query $query){
						return $query->select(['myplans.plan_id', 'myplans.category_id'])
						->contain(['plan'])
						->where(['myplans.enabled' =>'Y'])
						->andWhere(['not exists '.
						'(SELECT id FROM block_plans where block_plans.category_id = myplans.category_id and block_plans.plan_id=myplans.plan_id and (date >= "'.$this->request->data['from_date'].'"
						 AND date <= "'.$this->request->data['to_date'].'" ) GROUP BY category_id ,plan_id )']);
					}
				
			])
			->where(['rooms >'=>0,'adults >='=>$this->request->data['adult'],'child >= '=>$this->request->data['kid']])
			->andWhere(['not exists '.
						'(SELECT rooms.category_id FROM rooms
						WHERE rooms.category_id = Categories.id and ( Categories.rooms  - rooms.booked < '.$this->request->data['total_room'].')  and (rooms.date >= "'.$this->request->data['from_date'].'" AND rooms.date <= "'.$this->request->data['to_date'].'")  )'])
			->hydrate(false)->toArray(); 
		if(!empty($getCategory))
		{					
			$from_date=$start_date = $this->request->data['from_date'];
			$end_date = $this->request->data['to_date'];
			$date_arr = array();
			
			while (strtotime($from_date) <= strtotime($end_date)) {
				$date_arr[] = $from_date;
				$from_date=date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
			}
			$this->loadModel('Rates');
			if($this->request->data['user_type'] =='N') $select = array('person_type','no_person','rate'=>'normal_rate','off'=>'off_normal');
			else  $select = array('person_type','no_person','rate'=>'agent_rate','off'=>'off_agent');
			$count = count($date_arr) * ($this->request->data['adult'] + $this->request->data['kid']);
			$rates= array();
			//pr($this->request->data);
			foreach($getCategory  as $k=>$cate)
			{
				foreach($cate['myplans'] as $l=>$plan)
				{
					
					$record  = $this->Rates->find()->select($select)->where(['date >='=>$start_date,'date <='=>$end_date,'category_id' => $cate['id'], 'plan_id' =>  $plan['plan_id'],'OR' => [['person_type'=>'A','no_person <='=>$this->request->data['adult']], ['person_type'=>'K','no_person <='=>$this->request->data['kid']]]])->hydrate(false)->toArray();
					
					if(count($record)==$count)
					{ 
						
						$payment_rate = 0;
						foreach($this->request->data['decode_room'] as $room){
							$adult_count = $room->adults_count;
							$kid_count = $room->children_count;
							foreach($record as $rate){
								if( ($rate['person_type']=='A' && $rate['no_person'] <=$adult_count) || ($rate['person_type']=='K' && $rate['no_person'] <=$kid_count) ){
									
									
									if($rate['off']>0){
										$off= round(($rate['off'] / $rate['rate']) * 100 ,2);
										$payment_rate=$payment_rate+($rate['rate'] - $off);
									}else $payment_rate=$payment_rate+$rate['rate'];
								}
							}
							
						}
						$rate_c = round($payment_rate/count($date_arr),1);
						if($per_day_price==0) $per_day_price=$rate_c;
						else if($rate_c<$per_day_price) $per_day_price=$rate_c;
					}
				}
			}
		}
		
		return $per_day_price;
		
	}
	
	public function category()
	{
		$error =true;$code=1;
		$message=''; $response = array(); 
		if(isset( $this->request->data['from_date']) && isset( $this->request->data['to_date']) && isset( $this->request->data['rooms']) && isset( $this->request->data['user_type']) )
		{
			$rooms = json_decode($this->request->data['rooms']);
			$adult_arr= $kid_arr=array();
			$this->request->data['total_room'] = 0;
			
			foreach($rooms as $room){
			
				 if($room->adults_count >0 || $room->children_count >0 ){
					array_push($adult_arr,$room->adults_count);
					array_push($kid_arr,$room->children_count);
					$this->request->data['total_room']++;
				}
			}
			$this->request->data['adult'] = $this->request->data['kid'] =0 ;
			if(!empty($adult_arr)) $this->request->data['adult'] = max($adult_arr);
			if(!empty($kid_arr)) $this->request->data['kid'] = max($kid_arr);
			if($this->request->data['adult'] > 0 || $this->request->data['kid'] > 0 )
			{
				$this->request->data['total_adult'] = array_sum($adult_arr);
				$this->request->data['total_kid'] = array_sum($kid_arr);
				$this->request->data['decode_room'] = $rooms;
				$error = false;
				$code = 0;
				$this->loadModel('Categories');
				$getCategory = $this->Categories->find()
				->select(['id','category_name'])
				->contain(['gallery'=>['fields'=>['file','category_id'], 'sort' => ['priority' => 'ASC']],'free_service'=>['fields'=>['value','type_id']],'facility'=>['fields'=>['value','type_id']],
				'myplans' => function (\Cake\ORM\Query $query){
							return $query->select(['category_plan_id'=>'myplans.id','planDescription'=>'plan.plan_description','myplans.plan_id', 'myplans.category_id','planName'=>'plan.plan_title'])
							->contain(['plan'])
							->where(['myplans.enabled' =>'Y'])
							->andWhere(['not exists '.
							'(SELECT id FROM block_plans where block_plans.category_id = myplans.category_id and block_plans.plan_id=myplans.plan_id and (date >= "'.$this->request->data['from_date'].'"
							 AND date <= "'.$this->request->data['to_date'].'" ) GROUP BY category_id ,plan_id )']);
						}
					
				])
				->where(['rooms >'=>0,'adults >='=>$this->request->data['adult'],'child >= '=>$this->request->data['kid']])
				->andWhere(['not exists '.
						'(SELECT rooms.category_id FROM rooms
						WHERE rooms.category_id = Categories.id and ( Categories.rooms  - rooms.booked < '.$this->request->data['total_room'].')  and (rooms.date >= "'.$this->request->data['from_date'].'" AND rooms.date <= "'.$this->request->data['to_date'].'")  )'])
				->hydrate(false)->toArray(); 
			
				if(!empty($getCategory))
				{
					$hotel  = $this->Hotels->find('all',array('fields'=>['hotel_phone'],'conditions'=>array('id'=>1)))->hydrate(false)->first();					
					
					$from_date=$start_date = $this->request->data['from_date'];
					$end_date = $this->request->data['to_date'];
					$date_arr = array();
					
					while (strtotime($from_date) <= strtotime($end_date)) {
						$date_arr[] = $from_date;
						$from_date=date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
					}
					$this->loadModel('Rates');
					if($this->request->data['user_type'] =='N') $select = array('person_type','no_person','rate'=>'normal_rate','off'=>'off_normal');
					else  $select = array('person_type','no_person','rate'=>'agent_rate','off'=>'off_agent');
					$count = count($date_arr) * ($this->request->data['adult'] + $this->request->data['kid']);
					foreach($getCategory  as $k=>$cate)
					{
						foreach($cate['myplans'] as $l=>$plan)
						{
							$record  = $this->Rates->find()->select($select)->where(['date >='=>$start_date,'date <='=>$end_date,'category_id' => $cate['id'], 'plan_id' =>  $plan['plan_id'],'OR' => [['person_type'=>'A','no_person <='=>$this->request->data['adult']], ['person_type'=>'K','no_person <='=>$this->request->data['kid']]]])->hydrate(false)->toArray();
					
							if(count($record)==$count)
							{
								$total_rate=$off_rate =$pay_rate=0;
								foreach($this->request->data['decode_room'] as $room)
								{
									$adult_count = $room->adults_count;
									$kid_count = $room->children_count;
									foreach($record as $rate)
									{
										if( ($rate['person_type']=='A' && $rate['no_person'] <=$adult_count) || ($rate['person_type']=='K' && $rate['no_person'] <=$kid_count) )
										{
											$total_rate=$total_rate+$rate['rate'];
											
											if($rate['off']>0){
												$off= round(($rate['off'] / 100) * $rate['rate'] ,2);
												$pay_rate=$pay_rate+($rate['rate'] - $off);
											}else $pay_rate=$pay_rate+$rate['rate'];
										}
									}
								}
							
								$getCategory[$k]['myplans'][$l]['total_rate'] = round($total_rate/count($date_arr),1);
								$getCategory[$k]['myplans'][$l]['pay_rate'] = round($pay_rate/count($date_arr),1);
								$getCategory[$k]['myplans'][$l]['off'] =0;
								if($getCategory[$k]['myplans'][$l]['total_rate'] != $getCategory[$k]['myplans'][$l]['pay_rate']){
									$getCategory[$k]['myplans'][$l]['off'] = round(((($getCategory[$k]['myplans'][$l]['total_rate'] -$getCategory[$k]['myplans'][$l]['pay_rate'] )/$getCategory[$k]['myplans'][$l]['total_rate'] )  *100),1);
								}
								$getCategory[$k]['myplans'][$l]['total_rate_dollar'] = $this->convertDollar($getCategory[$k]['myplans'][$l]['total_rate']);
								$getCategory[$k]['myplans'][$l]['pay_rate_dollar'] = $this->convertDollar($getCategory[$k]['myplans'][$l]['pay_rate']);
								
							}else unset($getCategory[$k]['myplans'][$l]);
						}
					
						if(empty($getCategory[$k]['myplans'])) unset($getCategory[$k]);
						else{
							
							$getCategory[$k]['hotel_number']  = $this->setting['master_number'];
							if(!empty($getCategory[$k]['free_service'])) $getCategory[$k]['free_service'] = array_column($getCategory[$k]['free_service'],'value');
							if(!empty($getCategory[$k]['facility'])) $getCategory[$k]['facility'] = array_column($getCategory[$k]['facility'],'value');
							if(!empty($getCategory[$k]['gallery'])){
								;
								$getCategory[$k]['gallery'] = array_column($getCategory[$k]['gallery'],'file');
								array_walk($getCategory[$k]['gallery'], function(&$value, $key,$str = BASEURL."uploads/gallery/") { $value = $str.$value; } );
							} 
							
						}
					}
					$plan_array = array();
					$count = 0;
					foreach($getCategory as $val){
						$plan_array[$count] = $val;
						$count++;
					}
					$response=$this->request->data;
					$response['currency']=$this->setting['currency'];
					$response['total_guest'] = $this->request->data['total_adult'] +$this->request->data['total_kid']; 
					$response['check_in_date'] = date('d M Y D',strtotime($this->request->data['from_date'])); 
					$response['check_out_date'] =  date('d M Y D',strtotime($this->request->data['to_date'])); 
					
					$response['rooms'] = $plan_array;
				
				}else $message = 'No room available.';	
				
			} 
			else $message = 'Please select no of people.';	
		
		
		}
		else $message = 'Incomplete Data';			
		if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
		else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
	
	}
	
	public function convertDollar($val)
	{
		return round( ($val/$this->setting['dollar_price']) , 2);
	
	}
	
	public function overview()
	{
		
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message=''; $response = array(); 
			if(isset( $this->request->data['from_date']) && isset( $this->request->data['to_date']) && isset( $this->request->data['rooms'])  && isset( $this->request->data['user_type']) )
			{
				$rooms = json_decode($this->request->data['rooms']);
				$room_final = $adult_arr= $kid_arr=array();
				$this->request->data['total_room'] = 0;
				foreach($rooms as $room){
					if($room->adults_count >0 || $room->children_count >0 ){
						array_push($adult_arr,$room->adults_count);
						array_push($kid_arr,$room->children_count);
						$this->request->data['total_room']++;
					}
					
				}
				$this->request->data['adult'] = $this->request->data['kid'] =0 ;
				if(!empty($adult_arr)) $this->request->data['adult'] = max($adult_arr);
				if(!empty($kid_arr)) $this->request->data['kid'] = max($kid_arr);
				if($this->request->data['adult'] > 0 || $this->request->data['kid'] > 0 )
				{
					$this->request->data['total_adult'] = array_sum($adult_arr);
					$this->request->data['total_kid'] = array_sum($kid_arr);
					$this->request->data['decode_room'] = $rooms;
					
					$error = false;
					$code = 0;
					$data = $this->Hotels->find()->where(['id'=>1])->contain(['images','amenities','free_service','facility','reviews' => function ($q){
							   return $q
									->select(['reviews.hotel_id','count' => $q->func()->count('reviews.id'),'avg_money' => $q->func()->avg('reviews.value_for_money'),'avg_cleanliness' => $q->func()->avg('reviews.cleanliness'),'avg_food' => $q->func()->avg('reviews.food'),'avg_facility' => $q->func()->avg('reviews.facility'),'avg_location' => $q->func()->avg('reviews.location')])
									->group(['reviews.hotel_id']);
							}
					])->hydrate(false)->first(); 
					
					
					$response=$this->request->data;
					$response['total_guest'] = $this->request->data['total_adult'] +$this->request->data['total_kid']; 
					$response['total_room'] = $this->request->data['total_room']; 
					$response['check_in_date'] = date('d M Y D',strtotime($this->request->data['from_date'])); 
					$response['check_out_date'] =  date('d M Y D',strtotime($this->request->data['to_date'])); 
					$response['hotel_number'] = $this->setting['master_number'];
					$response['price'] = $this->minPrice($this->request->data);
					$response['price_in_dollar'] = $this->convertDollar($response['price']);
					$response['rating'] = $data['star_rating'];
					$response['currency']=$this->setting['currency'];
					$response['hotel_check_in'] = $data['check_in_time'];
					$response['hotel_check_out'] = $data['check_out_time'];
					
					$response['floor'] = $data['no_of_floor'];
					$response['total_rooms'] = $data['no_of_rooms'];
					$response['description'] = $data['about_us'];
					$response['hotel_location'] = $data['hotel_location'];
					$response['latitude'] = $data['latitude'];
					$response['longitude'] = $data['longitude'];
					$response['total_reviews'] = (!empty($data['reviews']) ? $data['reviews'][0]['count'] : 0);
					$response['avg_money'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_money']) : 0 );
					$response['avg_cleanliness'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_cleanliness']) : 0);
					$response['avg_food'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_food']) : 0);
					$response['avg_facility'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_facility']) : 0 );
					$response['avg_location'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_location']) : 0 );
					$response['avg_review'] = (!empty($data['reviews']) ? round(($response['avg_money'] + $response['avg_cleanliness'] + $response['avg_food'] + $response['avg_facility']+ $response['avg_location'])  / 5) : 0);
					$response['distance'] = $response['amenity'] = $response['free_service'] =$response['facility']= $response['images'] =array(); 
					foreach($data['amenities'] as $k=>$val){
						$response['amenity'][$k] = $val['value'];
					}
					foreach($data['free_service'] as $k=>$val){
						$response['free_service'][$k] = $val['value'];
					}
					foreach($data['facility'] as $k=>$val){
						$response['facility'][$k] = $val['value'];
					}
					foreach($data['images'] as $k=>$val){
						$response['images'][$k] = BASEURL."/uploads/gallery/".$val['file'];
					}
					$distance = json_decode($data['hotel_distance']);
					$l=0;
					foreach($distance as $k=>$val){
						$response['distance'][$l]['place'] = $k;
						$response['distance'][$l]['distance'] = $val;
						$l++;
					}
					
				}else $message = 'Please select the number of person';		
				
				
			}
			else $message = 'Incomplete Data';			
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
		
	}
	
	public function partners()
	{
		if($this->request->is(['post','put']))
    	{
			
			$message= $response = '';
			$this->loadModel('Partners');
			$response = $this->Partners->find()->select(['file'])->order(['priority'=>'asc'])->hydrate(false)->toArray(); 
			foreach($response as $k=>$data){
				$response[$k] = BASEURL."/uploads/gallery/".$data['file']; 
			}
			$this->set(array('response'=>$response,'code'=>0,'error'=>false,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function cms()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = '';
			if(isset( $this->request->data['slug'] ))
			{
				$error= false;
				$code = 0;
				$data = $this->Hotels->find()->select([$this->request->data['slug']])->where(['id'=>1])->hydrate(false)->first(); 
				if(!empty($data)) $response = $data[$this->request->data['slug']];
				
			
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
	}
	
	public function reviewNext()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = '';
			if(isset( $this->request->data['page_no'] ))
			{
				$error =false;
				$code = 0;
				$this->loadModel('Reviews');
				$review = $this->Reviews->find();
				$reviewdate = $review->func()->date_format([
				   'Reviews.created' => 'literal',
					"'%d %b %Y'" => 'literal'
				]);
				 $response = $review->select(['Reviews.id','reviewdate'=>$reviewdate,'Reviews.title','Reviews.review','first_name'=>'user.first_name','last_name'=>'user.last_name','booking_number'=>'booking.booking_no'])
				->contain(['booking'=>['user']])
				//->where(['Reviews.hotel_id'=>1])
				->order(['Reviews.id'=>'DESC'])
				->limit(20)
				->page($this->request->data['page_no'])
				->hydrate(false)->toArray(); 
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		
		}
		
	}
	
	public function reviews()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = '';
				
			if(isset( $this->request->data['page_no'] ))
			{
				$error=false;
				$code = 0;
				$response = array(); 
				
				$data = $this->Hotels->find()->select(['Hotels.id'])->where(['id'=>1])->contain(['reviews' => function ($q){
						   return $q
								->select(['reviews.hotel_id','count' => $q->func()->count('reviews.id'),'avg_money' => $q->func()->avg('reviews.value_for_money'),'avg_cleanliness' => $q->func()->avg('reviews.cleanliness'),'avg_food' => $q->func()->avg('reviews.food'),'avg_facility' => $q->func()->avg('reviews.facility'),'avg_location' => $q->func()->avg('reviews.location')])
								->group(['reviews.hotel_id']);
						}
				])->hydrate(false)->first(); 
				
				$response['total_reviews'] = (!empty($data['reviews']) ? $data['reviews'][0]['count'] : 0);
				$response['avg_money'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_money']) : 0 );
				$response['avg_cleanliness'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_cleanliness']) : 0);
				$response['avg_food'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_food']) : 0);
				$response['avg_facility'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_facility']) : 0 );
				$response['avg_location'] =  (!empty($data['reviews']) ? round($data['reviews'][0]['avg_location']) : 0 );
				$response['avg_review'] = (!empty($data['reviews']) ? round(($response['avg_money'] + $response['avg_cleanliness'] + $response['avg_food'] + $response['avg_facility']+ $response['avg_location'])  / 5,1) : 0);
				
				$response['reviews'] = array();
				$this->loadModel('Reviews');
				$review = $this->Reviews->find();
				$reviewdate = $review->func()->date_format([
				   'Reviews.created' => 'literal',
					"'%d %b %Y'" => 'literal'
				]);
				$response['reviews'] = $review->select(['Reviews.reply','Reviews.id','reviewdate'=>$reviewdate,'Reviews.title','Reviews.review','first_name'=>'user.first_name','last_name'=>'user.last_name','booking_number'=>'booking.booking_no'])
				->contain(['booking'=>['user']])
				->where(['Reviews.hotel_id'=>1])
				->order(['Reviews.id'=>'DESC'])
				->limit(20)
				->page( $this->request->data['page_no'])
				->hydrate(false)->toArray(); 
			
			}
			else $message = 'Incomplete Data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
		
	}
	
	public function location()
	{
		if($this->request->is(['post','put']))
    	{
			$response = array(); 
			$response = $this->Hotels->find()->select(['hotel_email','hotel_location','latitude','longitude','hotel_contact'])->where(['id'=>1])->hydrate(false)->first(); 
			$distance = json_decode($response['hotel_contact']);
			$l=0;
			foreach($distance as $k=>$val){
				$response['contact'][$l]['type'] = $k;
				$response['contact'][$l]['value'] = $val;
				$l++;
			}
			$this->set(array('response'=>$response,'code'=>0,'error'=>false,'message'=> '','_serialize'=>array('code','error','message','response')));
		
		}
		
	}
	
	public function gallery()
	{
		if($this->request->is(['post','put']))
    	{
			
			
			$this->loadModel('HotelGalleries');
			$gallery = $this->HotelGalleries->find()
			->select(['file','type'])
			->where(['hotel_id' => 1])->order(['priority'=>'asc'])->hydrate(false)->toArray();
			$response['images'] = $response['videos'] = array(); 
			$i=$v=0;
			foreach($gallery as $k=>$img){
				if($img['type']=='I'){
					$response['images'][$i] =  BASEURL."/uploads/gallery/".$img['file']; $i++;
				} 
				else if($img['type']=='V'){
					 $response['videos'][$v] =  BASEURL."/uploads/gallery/".$img['file']; $v++;
				}
			}
			$this->set(array('response'=>$response,'code'=>0,'error'=>false,'message'=> '','_serialize'=>array('code','error','message','response')));
		}
	}
	
	
	
}

	
