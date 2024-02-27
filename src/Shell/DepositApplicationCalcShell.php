<?php 

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;


class DepositApplicationCalcShell extends Shell
{
    public function main(){
		$this->loadModel('DepositApplicationStage');
		$stage_list = $this->DepositApplicationStage->find()->select(['stage'])->where(['id !='=>1])->all();
		foreach($stage_list as $l){
			Log::write('debug', $l->stage. "차 투자서비스 이자 지급 시작");
			$this->main_schedule_calc($l->stage);
			Log::write('debug', $l->stage. "차 투자서비스 이자 지급 종료");
		}
	}

	public function main_schedule_calc($investment_number){
		$this->loadModel('DepositApplicationSetting');
		$chk_count= $this->DepositApplicationSetting->find()->where(['OR'=>[['status'=>'O'],['status'=>'T']]])->where(['updated >='=>date('Y-m-d 00:00:00'),'investment_number'=>$investment_number])->count();
		if($chk_count > 0){
			Log::write('error', "오늘은 이미 지급되었습니다. (Today already done)");
			$respArr = ["success"=>"false","message"=>"오늘은 이미 지급 되었습니다.(Today already done)"];
			return $respArr; 
		}
		$ongoing = $this->DepositApplicationSetting->find()->select(['id','days','count_of_people','data','days_of_remain','status','updated'])->where(['status'=>'O','investment_number'=>$investment_number])->first();
		if(empty($ongoing)){
			$ongoing = $this->DepositApplicationSetting->find()->select(['id','days','count_of_people','data','days_of_remain','status','updated'])
				->where(['status'=>'S','investment_number'=>$investment_number])->order(['id'=>'asc'])->first();
		}
		if(!empty($ongoing)){
			$result = $this->calc($ongoing->days, $ongoing->data, $ongoing->count_of_people,$investment_number); // calculate

			if($result['fail'] > 0 ){
				$respArr = ["success"=>"false","message"=>"실패된 지급이 있습니다. 로그를 확인해주세요 id : ". $ongoing->id];
				return $respArr;
			}

			$this->updatesetting($ongoing->id); // update
			Log::write('error', "지급 완료 ".date('Y-m-d H:i:s')." ". $ongoing->id);
			Log::write('error', $result);
			$respArr = ["success"=>"true","message"=>"지급 완료 ".date('Y-m-d H:i:s')." [ id :: ". $ongoing->id." ] "];
			return $respArr; 
		} else {
			Log::write('error', "설정된 데이터가 없습니다. (No data set)");
			$respArr = ["success"=>"false","message"=>"설정된 데이터가 없습니다.(No data set)"];
			return $respArr; 
		}
	}

	public function calc($days, $earned_data, $count_of_people,$investment_number){
		$this->loadModel('DepositApplicationList');
		$this->loadModel('DepositApplicationAmountFee');
		$this->loadModel('DepositApplicationPeriodFee');
		$five_days_ago = strtotime("-1 days"); // 5일 이상된 데이터만 계산되도록 조건 추가
		$created = date("Y-m-d H:i:s", $five_days_ago);
		$list = $this->DepositApplicationList->find()->select(['quantity','service_period_month','id','user_id','number_of_received'])->where(['status'=>'A','created <='=>$created,'investment_number'=>$investment_number])->all();
		$list_count = $this->DepositApplicationList->find()->select(['quantity','service_period_month','id','user_id','number_of_received'])->where(['status'=>'A','created <='=>$created,'investment_number'=>$investment_number])->count();
		if($list_count < 1){
			Log::write('error', "해당 차수 신청 목록 없음");
			$respArr = ["success"=>"false","fail"=>1];
			return $respArr;
		}
		$cal_fee = 0.00;
		$percent = 0;
		$success_cnt = 0;
		$fail_cnt = 0;
		foreach($list as $l){
			$id = $l->id;
			$user_id = $l->user_id;
			$quantity = $l->quantity;
			$service_period_month = $l->service_period_month;

			$amount_fee = $this->DepositApplicationAmountFee->find()->select(['fee'])->where(['amount'=>$quantity])->first();
			$period_fee = $this->DepositApplicationPeriodFee->find()->select(['fee'])->where(['period'=>$service_period_month])->first();
			$cal_fee = $amount_fee->fee;
			$percent = $period_fee->fee;
			
			// 중복 체크 시작 위치
			$duplicatecheck = $this->duplicatecheckforlog($id);
			if($duplicatecheck){
				$result = $this->calculationformula($days, $earned_data, $count_of_people, $cal_fee, $percent);
				$this->updatelist($id,$result);
				$this->updatewallet($user_id,$result);
				$this->addlog($id,$user_id,$result,'S',$investment_number);
				$success_cnt++;
			} else {
				Log::write('error', "오늘은 이미 지급 됐습니다( id : ".$id.") aleady received");
				$fail_cnt++;
			}
		}
		$respArr = ["success"=>$success_cnt,"fail"=>$fail_cnt];
		return $respArr;
	}

	public function calculationformula($days, $earned_data, $count_of_people, $cal_fee, $percent){
		$result = (($earned_data * $percent) * ($cal_fee / 100)) / $count_of_people ;
		return $result;
	}

	public function duplicatecheckforlog($list_id){
		$this->loadModel('DepositApplicationLog');
		$data_count = $this->DepositApplicationLog->find()->where(['type'=>'S', 'created >='=>date('Y-m-d 00:00:00'), 'list_id'=>$list_id])->count();
		if($data_count > 0){
			return false;
		} else {
			return true;
		}
	}

	public function addlog($list_id,$user_id,$amount,$type,$investment_number){
		// only insert
		$this->loadModel('DepositApplicationLog');
		$query = $this->DepositApplicationLog->query();
		$query->insert(['list_id','user_id','amount','created','type','investment_number'])
			->values(['list_id'=>$list_id,'user_id'=>$user_id,'amount'=>$amount,'type'=>$type,'created'=>date('Y-m-d H:i:s'),'investment_number'=>$investment_number])->execute();
	}

	public function updatelist($id, $calc_result){
		$this->loadModel('DepositApplicationList');
		$data = $this->DepositApplicationList->find()->select(['status','amount_received','id','user_id','number_of_received','service_period_month'])->where(['id'=>$id])->first();
		$amount_received = $data->amount_received;
		$number_of_received = $data->number_of_received;
		$status = $data->status;
		
		$amount_received = $amount_received + $calc_result;
		$number_of_received = $number_of_received + 1;
		if($number_of_received == $data->service_period_month){
			$status = 'E';
		}

		$query = $this->DepositApplicationList->query();
		$query->update()->set(['status'=>$status,'amount_received'=>$amount_received,'number_of_received'=>$number_of_received])->where(['id' => $id])->execute();
		return;
	}

	public function updatewallet($user_id,$calc_result){
		$this->loadModel('DepositApplicationWallet');
		$origin = $this->DepositApplicationWallet->find()->select(['amount'])->where(['user_id'=>$user_id])->first();
		if(!empty($origin)){ // origin is not empty
			// then update
			$amount = $origin->amount + $calc_result;
			$query = $this->DepositApplicationWallet->query();
			$query->update()->set(['amount'=>$amount,'updated'=>date('Y-m-d H:i:s')])->where(['user_id' => $user_id])->execute();
		} else { // then insert
			$query = $this->DepositApplicationWallet->query();
			$query->insert(['user_id','amount','created','updated'])->values(['user_id'=>$user_id,'amount'=>$calc_result,'created'=>date('Y-m-d H:i:s'),'updated'=>date('Y-m-d H:i:s')])->execute();
		}
		return;
	}

	public function updatesetting($id){
		$this->loadModel('DepositApplicationSetting');
		$ongoing = $this->DepositApplicationSetting->find()->select(['id','days','count_of_people','data','days_of_remain','status'])->where(['id'=>$id])->first();
		$status = $ongoing->status;
		$days_of_remain = $ongoing->days_of_remain-1;

		if($status == 'S'){
			$status = 'O';
		}

		if($days_of_remain == 0){
			$status = 'T';
		}
		$query = $this->DepositApplicationSetting->query();
		$query->update()->set(['days_of_remain'=>$days_of_remain,'status'=>$status,'updated'=>date('Y-m-d H:i:s')])->where(['id'=>$id])->execute();
		return;
	}

	public function cancelsetting(){
		$this->loadModel('DepositApplicationSetting');
		if($this->request->is('ajax')) {
			$id = $this->request->data['id'];
			if(!empty($id)){
				$query = $this->DepositApplicationSetting->query();
				$query->update()->set(['status'=>'C','updated'=>date('Y-m-d H:i:s')])->where(['id' => $id])->execute();
				$respArr = ["success"=>"true","message"=>"성공"];
				echo json_encode($respArr);
			}
		}
		die;
	}
}

?>