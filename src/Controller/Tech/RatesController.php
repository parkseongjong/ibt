<?php
namespace App\Controller\Admin;
use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Event\Event;
use Cake\ORM\TableRegistry;


class RatesController extends AppController
{ 
	
	public function updateRow($date,$val1,$val2,$val3)
	{
		
		//$val1 =  implode(',',str_split($val1)); 
		//$val2 =  implode(',',str_split($val2)); 
		//$val3 =  implode(',',str_split($val3)); 
		$is_entry = $this->rates->find('all')->select(['id'])->where(['date'=>$date])->hydrate(false)->first();
		if(empty($is_entry)){
			
			$rate= $this->rates->newEntity();
			$rate = $this->rates->patchEntity($rate, array('date'=>$date,'first_value'=>$val1,'second_value'=>$val2,'third_value'=>$val3));
			
			$this->rates->save($rate);
		}else{
			$rate = $this->rates->get($is_entry['id']); // Return article with id 12
			$rate->first_value = $val1;
			$rate->second_value = $val2;
			$rate->third_value = $val3;
			$this->rates->save($rate);
		}
		
	}
	public function deleteRow($date){
		$query = $this->rates->query();
		$query->delete()
		->where(['date' => $date])
		->execute();
	}
	public function date(){
		$this->set('title' , 'start Date');
		$this->loadModel('from_date');
		$query = $this->from_date->get(1);
		if ($this->request->is(['post','put'])) 
		{
			
			$query->date = $this->request->data['from_date'];
			$this->from_date->save($query);
			$this->Flash->success(__('Data updated successfully.'));
			return $this->redirect(['controller'=>'rates','action'=>'date']);
		
		}
		$this->set('query' ,$query);
	}
	public function update()
	{
		
		
		$this->set('title' , 'Update');
		$this->loadModel('from_date');
		$this->loadModel('rates');
		$query = $this->from_date->find('all')->hydrate(false)->first();
		$from_date = $query['date']->format('Y-m-d');
		$date_arr = $this->getDateForSpecificDayBetweenDates($from_date,date('Y-m-d'));
		
		
		if ($this->request->is(['post','put'])) 
		{
			$data = $this->request->data;
			
			foreach($data as $key=>$value){
				
				if( ($value[1] != '' && strlen($value[1]) == 3 ) || ($value[1] != '' ||  strlen($value[3]) == 3) && strlen($value[3]) <= 3 ){
					
					$this->updateRow($key,$value[1],$value[2],$value[3]);
				}else{
					$this->deleteRow($key);
				} 
				
			}
			
			$this->Flash->success(__('Data updated successfully.'));
			return $this->redirect(['controller'=>'rates','action'=>'update']);
			
		}
		$rate = $this->rates->find();
		$date = $rate->func()->date_format([
	   'date' => 'literal',
		"'%Y-%m-%d'" => 'literal'
		]);
		$record_date = array();
		$record  = $rate->select(['datee'=>$date,'first_value','second_value','third_value'])
			->where(['date >='=>$from_date,'date <='=>date('Y-m-d') ])
			->hydrate(false)->toArray();
		if(!empty($record)){
			$record_date = array_column($record,'datee');
		}
		
		//pr($date_arr);
	//	die;
		$this->set('date_arr' ,$date_arr);
		$this->set(array('record_date'=>$record_date ,'record'=>$record));
	}
	
	public function getDateForSpecificDayBetweenDates($startDate,$endDate){
			
			$endDate = strtotime($endDate);
			$date_array = array();
		
			for($i = strtotime('Monday', strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i))
			$date_array[]=date('Y-m-d',$i);

			return $date_array;
		 }
		public function date_sort($a, $b) {
			return strtotime($a) - strtotime($b);
		}
	
	
   
}
