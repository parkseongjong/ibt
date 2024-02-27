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

ini_set('memory_limit', '-1');
use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Event\Event;
use Cake\View\Helper\SessionHelper;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\Log\Log;
use Cake\Network\Exception\NotFoundException;

class DepositApplicationController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
	/* 투자 신청 리스트 */
	public function depositapplicationlist(){
        $this->loadModel('Users');
		$this->loadModel('DepositApplicationList');
		$this->loadModel('DepositApplicationStage');
		
		$limit =  $this->setting['pagination'];
		$search = $this->request->data;
		$search_value = '';
		$session = $this->request->session();

        if (!empty($search['pagination'])) $limit = $search['pagination'];
		if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        } else{
			$this->set('serial_num',1);
		} 

		$query = $this->DepositApplicationList->find()->select(['id','user_id','unit','quantity','service_period_month','previous_balance','u.name','u.phone_number','status','created','approval_date','cancelled_date','amount_received','number_of_received','investment_number','total_withdrawal_amount','total_amount_received'=>'amount_received+total_withdrawal_amount']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);

		$total_quantity_amount = $this->DepositApplicationList->find()->select(['total_quantity_amount'=>'sum(quantity)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);
		$total_withdrawal_amount = $this->DepositApplicationList->find()->select(['total_withdrawal_amount'=>'sum(total_withdrawal_amount)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);
		$total_send_amount = $this->DepositApplicationList->find()->select(['total_send_amount'=>'sum(amount_received)+sum(total_withdrawal_amount)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);
		$total_cancel_amount = $this->DepositApplicationList->find()->select(['total_cancel_amount'=>'sum(quantity)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);

		if($this->request->query('search_value')){
			$search_value = $this->request->query('search_value');
			if(is_numeric($search_value)){
				$query = $query -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
				$total_quantity_amount = $total_quantity_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
				$total_withdrawal_amount = $total_withdrawal_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
				$total_send_amount = $total_send_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
				$total_cancel_amount = $total_cancel_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
			} else {
				$query = $query -> where(['u.name' => $search_value]);
				$total_quantity_amount = $total_quantity_amount -> where(['u.name' => $search_value]);
				$total_withdrawal_amount = $total_withdrawal_amount -> where(['u.name' => $search_value]);
				$total_send_amount = $total_send_amount -> where(['u.name' => $search_value]);
				$total_cancel_amount = $total_cancel_amount -> where(['u.name' => $search_value]);
			}
		}

		if($this->request->query('investment_number')){
			$query = $query -> where(['investment_number' => $this->request->query('investment_number')]);
			$total_quantity_amount = $total_quantity_amount -> where(['investment_number' => $this->request->query('investment_number')]);
			$total_withdrawal_amount = $total_withdrawal_amount -> where(['investment_number' => $this->request->query('investment_number')]);
			$total_send_amount = $total_send_amount -> where(['investment_number' => $this->request->query('investment_number')]);
			$total_cancel_amount = $total_cancel_amount ->where(['investment_number' => $this->request->query('investment_number')]);
		}

		if($this->request->query('status')){
			$query = $query -> where(['status' => $this->request->query('status')]);
			$total_quantity_amount = $total_quantity_amount -> where(['status' => $this->request->query('status')]);
			$total_withdrawal_amount = $total_withdrawal_amount -> where(['status' => $this->request->query('status')]);
			$total_send_amount = $total_send_amount -> where(['status' => $this->request->query('status')]);
			$total_cancel_amount = $total_cancel_amount -> where(['status' => $this->request->query('status')]);
		}

		if($this->request->query('sort_value')){
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			if($session->read('list_sort') == $sort_value){
				$this->request->session()->write('list_sort', '');
			}else{
				$this->request->session()->write('list_sort', $sort_value);
			}
			$query = $query->order([$order_value=>$sort_value]);
		}else{
			$query = $query->order(['DepositApplicationList.id'=> 'DESC']);
		}

		if($this->request->is('ajax')) {
			$datas =  $query->all();
			echo json_encode($datas);
			die;
		}

		/* csv export */
		if($this->request->data('export')){
            
			$filename = time().'.csv';
			$file = fopen(WWW_ROOT."uploads/".$filename,"w");
			$headers = array('#','Investment Number','User Id','User Name','Phone number','Quantity','Unit','Service Period Month','Status','Created','Approval Date','Cancelled Date','Amount Received','Number Of Received','Total Withdrawal Amount','Total Amount Received');
			fputcsv($file,$headers);

			$datas =  $query->all();
			$this->add_system_log(200, 0, 5, '투자 목록 CSV 다운로드 (이름, 전화번호 등)');
			
			$k = 1;
			foreach ($datas as $k=>$data)
			{
				$arr = [];
				$arr['#'] = $data['id'];
				$arr['Investment Number'] = $data['investment_number'] != null ? $data['investment_number'] : '' ;
				$arr['User Id'] = $data['user_id'];
				$arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['u']['name']), "EUC-KR", "UTF-8" );
				$arr['Phone number'] = $data['u']['phone_number'];
				$arr['Quantity'] = number_format($data['quantity']);
				$arr['Unit'] = $data['unit'];
				//$arr['Previous Balance'] = number_format($data['previous_balance']);
				$arr['Service Period Month'] = $data['service_period_month'];
				$arr['Status'] = $data['status'];
				$arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created']));
				$arr['Approval Date'] = $data['approval_date'] != null ? date('Y-m-d H:i:s',strtotime($data['approval_date'])) : '' ;
				$arr['Cancelled Date'] = $data['cancelled_date'] != null ? date('Y-m-d H:i:s',strtotime($data['cancelled_date'])) : '' ;
				$arr['Number Of Received'] = $data['number_of_received'] != null ? $data['number_of_received'] : '' ;
				$arr['Amount Received'] = $data['amount_received'] != null ? number_format($data['amount_received']) : '' ;
				$arr['Total Withdrawal Amount'] = $data['total_withdrawal_amount'] != null ? number_format($data['total_withdrawal_amount']) : 0 ;
				$arr['Total Amount Received'] = $data['total_amount_received'] != null ? number_format($data['total_amount_received']) : 0 ;
				fputcsv($file,$arr);
				$k++;
			}
			fclose($file);
			$this->response->file("uploads/".$filename, array(
				'download' => true,
				'name' => 'DepositApplicationList'.$filename
			));
			return $this->response;die;
		}

		try {
			$collectdata =  $this->Paginator->paginate($query);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$collectdata =  $this->Paginator->paginate($query);
		}
		$stage_list = $this->DepositApplicationStage->find()->select(['stage'])->where(['id !='=>1])->all();
		$total_quantity_amount = $total_quantity_amount->where(['status !='=> 'C'])->first();
		$total_withdrawal_amount = $total_withdrawal_amount->first();
		$total_send_amount = $total_send_amount->first();
		$total_cancel_amount = $total_cancel_amount->where(['status'=> 'C'])->first();

        $this->set('listing',$collectdata);
		$this->set('stage_list',$stage_list);
		$this->set('total_quantity_amount',$total_quantity_amount);
		$this->set('total_withdrawal_amount',$total_withdrawal_amount);
		$this->set('total_send_amount',$total_send_amount);
		$this->set('total_cancel_amount',$total_cancel_amount);

	}


    public function depositapplicationlist2(){
        $this->loadModel('Users');
        $this->loadModel('DepositApplicationList');
        $this->loadModel('DepositApplicationStage');

        $limit =  $this->setting['pagination'];
        $search = $this->request->data;
        $search_value = '';
        $session = $this->request->session();

        if (!empty($search['pagination'])) $limit = $search['pagination'];
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        } else{
            $this->set('serial_num',1);
        }

        $query = $this->DepositApplicationList->find()->select(['id','user_id','unit','quantity','service_period_month','previous_balance','u.name','u.phone_number','status','created','approval_date','cancelled_date','amount_received','number_of_received','investment_number','total_withdrawal_amount','total_amount_received'=>'amount_received+total_withdrawal_amount']);
        $query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);

        $total_quantity_amount = $this->DepositApplicationList->find()->select(['total_quantity_amount'=>'sum(quantity)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);
        //MC코인 총 금액
        //$total_quantity_amount_mc = $this->DepositApplicationList->find()->select(['total_quantity_amount'=>'sum(quantity)'])->where();
        //TP3코인 총 금액
        //$total_quantity_amount_tp3 = $this->DepositApplicationList->find()->select(['total_quantity_amount'=>'sum(quantity)'])->where();

        $total_withdrawal_amount = $this->DepositApplicationList->find()->select(['total_withdrawal_amount'=>'sum(total_withdrawal_amount)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);
        $total_send_amount = $this->DepositApplicationList->find()->select(['total_send_amount'=>'sum(amount_received)+sum(total_withdrawal_amount)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);
        $total_cancel_amount = $this->DepositApplicationList->find()->select(['total_cancel_amount'=>'sum(quantity)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);

        if($this->request->query('search_value')){
            $search_value = $this->request->query('search_value');
            if(is_numeric($search_value)){
                $query = $query -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
                $total_quantity_amount = $total_quantity_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
                $total_withdrawal_amount = $total_withdrawal_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
                $total_send_amount = $total_send_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
                $total_cancel_amount = $total_cancel_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
            } else {
                $query = $query -> where(['u.name' => $search_value]);
                $total_quantity_amount = $total_quantity_amount -> where(['u.name' => $search_value]);
                $total_withdrawal_amount = $total_withdrawal_amount -> where(['u.name' => $search_value]);
                $total_send_amount = $total_send_amount -> where(['u.name' => $search_value]);
                $total_cancel_amount = $total_cancel_amount -> where(['u.name' => $search_value]);
            }
        }

        if($this->request->query('investment_number')){
            $query = $query -> where(['investment_number' => $this->request->query('investment_number')]);
            $total_quantity_amount = $total_quantity_amount -> where(['investment_number' => $this->request->query('investment_number')]);
            $total_withdrawal_amount = $total_withdrawal_amount -> where(['investment_number' => $this->request->query('investment_number')]);
            $total_send_amount = $total_send_amount -> where(['investment_number' => $this->request->query('investment_number')]);
            $total_cancel_amount = $total_cancel_amount ->where(['investment_number' => $this->request->query('investment_number')]);
        }

        if($this->request->query('status')){
            $query = $query -> where(['status' => $this->request->query('status')]);
            $total_quantity_amount = $total_quantity_amount -> where(['status' => $this->request->query('status')]);
            $total_withdrawal_amount = $total_withdrawal_amount -> where(['status' => $this->request->query('status')]);
            $total_send_amount = $total_send_amount -> where(['status' => $this->request->query('status')]);
            $total_cancel_amount = $total_cancel_amount -> where(['status' => $this->request->query('status')]);
        }

        if($this->request->query('sort_value')){
            $sort_value = $this->request->query('sort_value');
            $order_value = $this->request->query('order_value');
            if($session->read('list_sort') == $sort_value){
                $this->request->session()->write('list_sort', '');
            }else{
                $this->request->session()->write('list_sort', $sort_value);
            }
            $query = $query->order([$order_value=>$sort_value]);
        }else{
            $query = $query->order(['DepositApplicationList.id'=> 'DESC']);
        }

        if($this->request->is('ajax')) {
            $datas =  $query->all();
            echo json_encode($datas);
            die;
        }

        /* csv export */
        if($this->request->data('export')){

            $filename = time().'.csv';
            $file = fopen(WWW_ROOT."uploads/".$filename,"w");
            $headers = array('#','Investment Number','User Id','User Name','Phone number','Quantity','Unit','Service Period Month','Status','Created','Approval Date','Cancelled Date','Amount Received','Number Of Received','Total Withdrawal Amount','Total Amount Received');
            fputcsv($file,$headers);

            $datas =  $query->all();
            $this->add_system_log(200, 0, 5, '투자 목록 CSV 다운로드 (이름, 전화번호 등)');

            $k = 1;
            foreach ($datas as $k=>$data)
            {
                $arr = [];
                $arr['#'] = $data['id'];
                $arr['Investment Number'] = $data['investment_number'] != null ? $data['investment_number'] : '' ;
                $arr['User Id'] = $data['user_id'];
                $arr['User Name'] = mb_convert_encoding( htmlspecialchars($data['u']['name']), "EUC-KR", "UTF-8" );
                $arr['Phone number'] = $data['u']['phone_number'];
                $arr['Quantity'] = number_format($data['quantity']);
                $arr['Unit'] = $data['unit'];
                //$arr['Previous Balance'] = number_format($data['previous_balance']);
                $arr['Service Period Month'] = $data['service_period_month'];
                $arr['Status'] = $data['status'];
                $arr['Created'] = date('Y-m-d H:i:s',strtotime($data['created']));
                $arr['Approval Date'] = $data['approval_date'] != null ? date('Y-m-d H:i:s',strtotime($data['approval_date'])) : '' ;
                $arr['Cancelled Date'] = $data['cancelled_date'] != null ? date('Y-m-d H:i:s',strtotime($data['cancelled_date'])) : '' ;
                $arr['Number Of Received'] = $data['number_of_received'] != null ? $data['number_of_received'] : '' ;
                $arr['Amount Received'] = $data['amount_received'] != null ? number_format($data['amount_received']) : '' ;
                $arr['Total Withdrawal Amount'] = $data['total_withdrawal_amount'] != null ? number_format($data['total_withdrawal_amount']) : 0 ;
                $arr['Total Amount Received'] = $data['total_amount_received'] != null ? number_format($data['total_amount_received']) : 0 ;
                fputcsv($file,$arr);
                $k++;
            }
            fclose($file);
            $this->response->file("uploads/".$filename, array(
                'download' => true,
                'name' => 'DepositApplicationList'.$filename
            ));
            return $this->response;die;
        }

        try {
            $collectdata =  $this->Paginator->paginate($query);
        } catch (NotFoundException $e) {
            $this->request->query['page'] = 1;
            $collectdata =  $this->Paginator->paginate($query);
        }
        $stage_list = $this->DepositApplicationStage->find()->select(['stage'])->where(['id !='=>1])->all();
        $total_quantity_amount = $total_quantity_amount->where(['status !='=> 'C'])->first();
        $total_withdrawal_amount = $total_withdrawal_amount->first();
        $total_send_amount = $total_send_amount->first();
        $total_cancel_amount = $total_cancel_amount->where(['status'=> 'C'])->first();

        $this->set('listing',$collectdata);
        $this->set('stage_list',$stage_list);
        $this->set('total_quantity_amount',$total_quantity_amount);
        $this->set('total_withdrawal_amount',$total_withdrawal_amount);
        $this->set('total_send_amount',$total_send_amount);
        $this->set('total_cancel_amount',$total_cancel_amount);

    }
	/* 투자 한개 승인 */
	public function changedepositapplicationstatus(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationList');
			if (!empty($this->request->data['id'])) {
				$id = $this->request->data['id'];
				$query = $this->DepositApplicationList->query();
				$query->update()->set(['status' => 'A','approval_date'=>date('Y-m-d H:i:s')])->where(['id' => $id])->execute();
				$this->add_system_log(200, $get_info->user_id, 3, '투자번호 : '. $id . ' 승인');
				echo "success";
				die;
			}
		} 
		echo 'fail';
		die;
	}
	/* 투자 취소 */
	public function cancel(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationList');
			$this->loadModel('PrincipalWallet');
			$this->loadModel('Cryptocoin');
			$cryptocoin = $this->Cryptocoin->find()->select('id')->where(['short_name'=>'TP3'])->first();
			$coin_id = $cryptocoin->id;

			if (!empty($this->request->data['id'])) {
				$id = $this->request->data['id'];
				$datas = $this->DepositApplicationList->find()->select(['id','quantity','user_id','unit','status'])->where(['id IN' => $id])->all();
				foreach($datas as $d){
					if($d->status != 'C'){ // except already cancelled
						$this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
						$coinData = $this->PrincipalWallet->newEntity();

						$subQuantity = 0 + $d->quantity;
						$insertCoinArr = [];
						$insertCoinArr['user_id'] = $d->user_id;
						$insertCoinArr['cryptocoin_id'] = $coin_id;
						$insertCoinArr['amount'] = $subQuantity;
						$insertCoinArr['type'] = "cancel_deposit";
						$insertCoinArr['status'] = "completed";

						$coinData = $this->PrincipalWallet->patchEntity($coinData, $insertCoinArr);
						if ($this->PrincipalWallet->save($coinData)) {
							$query = $this->DepositApplicationList->query();
							$query->update()->set(['status'=>'C','cancelled_date'=>date('Y-m-d H:i:s')])->where(['id' => $d->id])->execute();
						}
						$this->add_system_log(200, $d->user_id, 3, '투자번호 : '. $d->id . ' 취소 ');
					}
				}
				echo "success";
			}
		}
		die;
	}
	/* 투자 중도 취소 */
	public function dropout(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationList');
			$this->loadModel('PrincipalWallet');
			$this->loadModel('Cryptocoin');
			$cryptocoin = $this->Cryptocoin->find()->select('id')->where(['short_name'=>'TP3'])->first();
			$coin_id = $cryptocoin->id;

			if (!empty($this->request->data['id'])) {
				$id = $this->request->data['id'];
				$datas = $this->DepositApplicationList->find()->select(['id','quantity','user_id','unit','status'])->where(['id IN' => $id])->all();
				foreach($datas as $d){
					if($d->status != 'C'){ // except already cancelled
						$this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
						$coinData = $this->PrincipalWallet->newEntity();

						$subQuantity = $d->quantity * 0.7;
						$insertCoinArr = [];
						$insertCoinArr['user_id'] = $d->user_id;
						$insertCoinArr['cryptocoin_id'] = $coin_id;
						$insertCoinArr['amount'] = $subQuantity;
						$insertCoinArr['type'] = "cancel_deposit(dropout)";
						$insertCoinArr['status'] = "completed";

						$coinData = $this->PrincipalWallet->patchEntity($coinData, $insertCoinArr);
						if ($this->PrincipalWallet->save($coinData)) {
							$query = $this->DepositApplicationList->query();
							$query->update()->set(['status'=>'C','cancelled_date'=>date('Y-m-d H:i:s')])->where(['id' => $d->id])->execute();
						}
						$this->add_system_log(200, $d->user_id, 3, '투자번호 : '. $d->id . ' 중도 취소 ');
					}
				}
				echo "success";
			}
		}
		die;
	}
	/* 수익금/수수료 설정 추가 */
	public function feecalculator(){
		$this->loadModel('DepositApplicationSetting');
		$this->loadModel('DepositApplicationStage');

		if ($this->request->is(['post' ,'put']) ) {
			$days = $this->request->data['days'];
			$earned_data = $this->request->data['earned_data'];
			$count_of_people = $this->request->data['count_of_people'];
			$investment_number = $this->request->data['investment_number'];
			$status = 'O';
			$is_exist = $this->DepositApplicationSetting->find()->select(['status'])->where(['investment_number'=>$investment_number,'OR'=>[['status'=>'O'],['status'=>'S']]])->count();
			if($is_exist > 0){
				$status = 'S';
			}
			$query = $this->DepositApplicationSetting->query();
			$query->insert(['days','count_of_people','data','days_of_remain','status','created','investment_number'])->
				values(['days'=>$days,'count_of_people'=>$count_of_people,'data'=>$earned_data,'days_of_remain'=>$days,'status'=>$status,'created'=>date('Y-m-d H:i:s'),'investment_number'=>$investment_number])->execute();

			$this->add_system_log(200, 0, 2, '투자 수익금 셋팅 추가');

			return $this->redirect(['controller'=>'DepositApplication','action' => 'feecalculator']);
		}
		$stage_list = $this->DepositApplicationStage->find()->select(['stage'])->where(['id !='=>1])->all();
		$this->set('stage_list',$stage_list);
	}
    /* 수익금/수수료 설정 추가 */
    public function feecalculator2(){
        $this->loadModel('DepositApplicationSetting');
        $this->loadModel('DepositApplicationStage');

        if ($this->request->is(['post' ,'put']) ) {
            $days = $this->request->data['days'];
            $earned_data = $this->request->data['earned_data'];
            $count_of_people = $this->request->data['count_of_people'];
            $investment_number = $this->request->data['investment_number'];
            $status = 'O';
            $is_exist = $this->DepositApplicationSetting->find()->select(['status'])->where(['investment_number'=>$investment_number,'OR'=>[['status'=>'O'],['status'=>'S']]])->count();
            if($is_exist > 0){
                $status = 'S';
            }
            $query = $this->DepositApplicationSetting->query();
            $query->insert(['days','count_of_people','data','days_of_remain','status','created','investment_number'])->
            values(['days'=>$days,'count_of_people'=>$count_of_people,'data'=>$earned_data,'days_of_remain'=>$days,'status'=>$status,'created'=>date('Y-m-d H:i:s'),'investment_number'=>$investment_number])->execute();

            $this->add_system_log(200, 0, 2, '투자 수익금 셋팅 추가');

            return $this->redirect(['controller'=>'DepositApplication','action' => 'feecalculator']);
        }
        $stage_list = $this->DepositApplicationStage->find()->select(['stage'])->where(['id !='=>1])->all();
        $this->set('stage_list',$stage_list);
    }
	/* 수익금/수수료 설정 리스트 */
	public function settinglist(){
		$this->loadModel('DepositApplicationSetting');
		$this->loadModel('DepositApplicationStage');

		$query = $this->DepositApplicationSetting->find()->select(['id','days','count_of_people','data','days_of_remain','status','created','updated','investment_number']);
		if($this->request->query('investment_number')){
			$query = $query->where(['investment_number' => $this->request->query('investment_number')]);
		}
		$query = $query->order(['id'=>'desc']);
		try {
			$setting_list = $this->Paginator->paginate($query);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$setting_list = $this->Paginator->paginate($query);
		}
		$this->set('setting_list',$setting_list);

		$stage_list = $this->DepositApplicationStage->find()->select(['stage'])->where(['id !='=>1])->all();
		$this->set('stage_list',$stage_list);
	}
    public function settinglist2(){
        $this->loadModel('DepositApplicationSetting');
        $this->loadModel('DepositApplicationStage');

        $query = $this->DepositApplicationSetting->find()->select(['id','days','count_of_people','data','days_of_remain','status','created','updated','investment_number']);
        if($this->request->query('investment_number')){
            $query = $query->where(['investment_number' => $this->request->query('investment_number')]);
        }
        $query = $query->order(['id'=>'desc']);
        try {
            $setting_list = $this->Paginator->paginate($query);
        } catch (NotFoundException $e) {
            $this->request->query['page'] = 1;
            $setting_list = $this->Paginator->paginate($query);
        }
        $this->set('setting_list',$setting_list);

        $stage_list = $this->DepositApplicationStage->find()->select(['stage'])->where(['id !='=>1])->all();
        $this->set('stage_list',$stage_list);
    }

	public function callajaxcalc(){
		if($this->request->is('ajax')) {
			$investment_number = $this->request->data['investment_number'];
			Log::write('error', __('Start paying interest on investment services'));
			$this->add_system_log(100, 0, 0, '투자 수익금 지급 시작');
			$result = $this->main_schedule_calc($investment_number);
			Log::write('error', __('End of Interest Payment for Investment Services'));
			$this->add_system_log(100, 0, 0, '투자 수익금 지급 종료');
			echo json_encode($result);
			die;
		}
	}
	/* 수수료 지급 */
	public function main_schedule_calc($investment_number){
		$this->loadModel('DepositApplicationSetting');
		$chk_count= $this->DepositApplicationSetting->find()->where(['OR'=>[['status'=>'O'],['status'=>'T']]])->where(['updated >='=>date('Y-m-d 00:00:00'),'investment_number'=>$investment_number])->count();
		if($chk_count > 0){
			Log::write('error', __('It is already been paid today'));
			$this->add_system_log(100, 0, 0, '투자 수익금 지급 실패 - 금일 이미 지급 완료');
			$respArr = ["success"=>"false","message"=>__('It is already been paid today')];
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
				$this->add_system_log(100, 0, 0, '투자 수익금 지급 실패 - 실패된 지급 있습니다');
				$respArr = ["success"=>"false","message"=>__('There is a failed payment. Please check the log') . " id : ". $ongoing->id];
				return $respArr;
			}

			$this->updatesetting($ongoing->id); // update
			Log::write('error', __('Payment completed')." ".date('Y-m-d H:i:s')." ". $ongoing->id);
			Log::write('error', $result);
			$respArr = ["success"=>"true","message"=>__('Payment completed')." ".date('Y-m-d H:i:s')." [ id :: ". $ongoing->id." ] "];
			$this->add_system_log(100, 0, 0, '투자 수익금 지급 성공');
			return $respArr; 
		} else {
			Log::write('error', __('No data is set data'));
			$this->add_system_log(100, 0, 0, '투자 수익금 지급 실패 - 설정된 수익금이 없습니다');
			$respArr = ["success"=>"false","message"=>__('No data is set data')];
			return $respArr; 
		}
	}
	/* 수익금 지급 계산 */
	public function calc($days, $earned_data, $count_of_people,$investment_number){
		$this->loadModel('DepositApplicationList');
		$this->loadModel('DepositApplicationAmountFee');
		$this->loadModel('DepositApplicationPeriodFee');
		$five_days_ago = strtotime("-5 days"); // 5일 이상된 데이터만 계산되도록 조건 추가
		$created = date("Y-m-d H:i:s", $five_days_ago);
		$list = $this->DepositApplicationList->find()->select(['quantity','service_period_month','id','user_id','number_of_received'])->where(['status'=>'A','created <='=>$created,'investment_number'=>$investment_number])->all();
		
		if(count($list) < 1){
			Log::write('error', __('No application list for this order'));
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
				$this->add_system_log(100, 0, 0, "오늘은 이미 지급 됐습니다( id : ".$id.") aleady received");
				$fail_cnt++;
			}
		}
		$respArr = ["success"=>$success_cnt,"fail"=>$fail_cnt];
		return $respArr;
	}
	/* 수익금 지급 계산 */
	public function calculationformula($days, $earned_data, $count_of_people, $cal_fee, $percent){
		$result = (($earned_data * $percent) * ($cal_fee / 100)) / $count_of_people ;
		return $result;
	}
	/* 수익금 지급 중복 체크 */
	public function duplicatecheckforlog($list_id){
		$this->loadModel('DepositApplicationLog');
		$data_count = $this->DepositApplicationLog->find()->where(['type'=>'S', 'created >='=>date('Y-m-d 00:00:00'), 'list_id'=>$list_id])->count();
		if($data_count > 0){
			return false;
		} else {
			return true;
		}
	}
	/* 수익금 로그 추가 */
	public function addlog($list_id,$user_id,$amount,$type,$investment_number){
		// only insert
		$this->loadModel('DepositApplicationLog');
		$query = $this->DepositApplicationLog->query();
		$query->insert(['list_id','user_id','amount','created','type','investment_number'])
			->values(['list_id'=>$list_id,'user_id'=>$user_id,'amount'=>$amount,'type'=>$type,'created'=>date('Y-m-d H:i:s'),'investment_number'=>$investment_number])->execute();
	}
	/* 투자 리스트에 받은 금액 / 받은 횟수 업데이트 */
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
	/* 지갑에 업데이트  */
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
	/* 설정 업데이트 */
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
	/* 투자 수익금 지급 셋팅 취소 */
	public function cancelsetting(){
		$this->loadModel('DepositApplicationSetting');
		if($this->request->is('ajax')) {
			$id = $this->request->data['id'];
			if(!empty($id)){
				$query = $this->DepositApplicationSetting->query();
				$query->update()->set(['status'=>'C','updated'=>date('Y-m-d H:i:s')])->where(['id' => $id])->execute();
				$this->add_system_log(200, 0, 3, "투자 수익금 지급 셋팅 취소 ( id :: " . $id ." ) ");
				$respArr = ["success"=>"true","message"=>__('Success')];
				echo json_encode($respArr);
			}
		}
		die;
	}
	/* 투자 신청 전체 승인 처리 */
	public function changestatusall(){
		$this->loadModel('DepositApplicationList');
		if($this->request->is('ajax')) {
			$data = $this->DepositApplicationList->find()->select(['status','id','user_id'])->where(['status'=>'P'])->all();
			
			if(count($data) > 0){
				$idArr = [];
				$update_count = 0;
				foreach($data as $d){
					array_push($idArr,$d->id);
					$update_count++;
				}
				$query = $this->DepositApplicationList->query();
				$query->update()->set(['status'=>'A','approval_date'=>date('Y-m-d H:i:s')])->where(['id IN'=>$idArr])->execute();
				$this->add_system_log(200, 0, 3, $update_count . '건의 투자 신청 승인 완료');
				$respArr = ["success"=>"true","message"=>$update_count];
			} else {
				$this->add_system_log(200, 0, 3, '투자 신청 승인 실패 (승인할 목록 없음)');
				$respArr = ["success"=>"fail","message"=>__('No history to approve')];
			}
			echo json_encode($respArr);
		}
		die;
	}
	/* 투자 수익금 수수료/기간/기수 설정 */
	public function getAmountPeriodList(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationAmountFee');
			$this->loadModel('DepositApplicationPeriodFee');
			$this->loadModel('DepositApplicationStage');
			$type = $this->request->data['type'];
			if($type == 'amount'){
				$list = $this->DepositApplicationAmountFee->find()->all();
			} else if ($type == 'period'){
				$list = $this->DepositApplicationPeriodFee->find()->all();
			} else if($type == 'stage'){
				$list = $this->DepositApplicationStage->find()->all();
			}
			echo json_encode($list);
		}
		die;
	}
	/* 투자 수익금 설정 추가 */
	public function addFeeSetting(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationAmountFee');
			$this->loadModel('DepositApplicationPeriodFee');
			$type = $this->request->data['type'];
			if($type == 'amount'){
				$query = $this->DepositApplicationAmountFee->query();
				$query->insert(['amount','fee','last_admin_id','created'])
					->values(['amount'=>$this->request->data['contents_value'],'fee'=>$this->request->data['fee'],'last_admin_id'=>$user['id'],'created'=>date('Y-m-d H:i:s')])->execute();
				$this->add_system_log(200, 0, 2, '투자 금액별 수수료 추가');
			} else if ($type == 'period'){
				$query = $this->DepositApplicationPeriodFee->query();
				$query->insert(['period','fee','last_admin_id','created'])
					->values(['period'=>$this->request->data['contents_value'],'fee'=>$this->request->data['fee'],'last_admin_id'=>$user['id'],'created'=>date('Y-m-d H:i:s')])->execute();
				$this->add_system_log(200, 0, 2, '투자 기간별 수수료 추가');
			}
			echo "success";
		}
		die;
	}
	/* 투자 수익금 설정 지우기 */
	public function deleteFeeSetting(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationAmountFee');
			$this->loadModel('DepositApplicationPeriodFee');
			$type = $this->request->data['type'];
			$id = $this->request->data['id'];
			if($type == 'amount'){
				$query = $this->DepositApplicationAmountFee->query();
				$query->delete()->where(['id'=>$id])->execute();
				$this->add_system_log(200, 0, 4, '투자 수익금 - 금액별 수수료 삭제 ( id : '. $id . ' )');
			} else if ($type == 'period'){
				$query = $this->DepositApplicationPeriodFee->query();
				$query->delete()->where(['id'=>$id])->execute();
				$this->add_system_log(200, 0, 4, '투자 수익금 - 기간별 수수료 삭제 ( id : '. $id . ' )');
			}
			echo "success";
		}
		die;
	}
	/* 투자 수익금 로그 리스트 */
	public function loglist(){
		$this->loadModel('DepositApplicationLog');
		$this->loadModel('DepositApplicationStage');
		$limit =  $this->setting['pagination'];
		$search = $this->request->data;
		$search_value = '';
		$session = $this->request->session();


		if ($this->request->is(['post' ,'put']) ) {
            if (!empty($search['pagination'])) $limit = $search['pagination'];
        }
		if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        } else{
			$this->set('serial_num',1);
		} 

		$query = $this->DepositApplicationLog->find()->select(['id','user_id','list_id','amount','type','created','u.name','u.phone_number','investment_number']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);
		
		if($this->request->query('search_value')){
			$search_value = $this->request->query('search_value');
			if(is_numeric($search_value)){
				$query = $query -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
			} else {
				$query = $query -> where(['u.name' => $search_value]);
			}
		}
		if($this->request->query('investment_number')){
			$query = $query->where(['investment_number' => $this->request->query('investment_number')]);
		}
		if($this->request->query('type')){
			$query = $query->where(['type' => $this->request->query('type')]);
		}

		if($this->request->query('sort_value')){
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			if($session->read('log_sort') == $sort_value){
				$this->request->session()->write('log_sort', '');
			}else{
				$this->request->session()->write('log_sort', $sort_value);
			}
			$query = $query->order([$order_value=>$sort_value]);
		}else{
			$query = $query->order(['DepositApplicationLog.id'=> 'DESC']);
		}

		$query = $query->limit($limit);
		try {
			$log_list =  $this->Paginator->paginate($query);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$log_list =  $this->Paginator->paginate($query);
		}
		$stage_list = $this->DepositApplicationStage->find()->select(['stage'])->where(['id !='=>1])->all();

		$this->set('stage_list',$stage_list);
        $this->set('log_list',$log_list);
	}
	/* 투자 수익금 지갑 리스트 */
	public function walletlist(){
		$this->loadModel('DepositApplicationWallet');
		$limit =  $this->setting['pagination'];
		$search = $this->request->data;
		$search_value = '';
		$session = $this->request->session();


		if ($this->request->is(['post' ,'put']) ) {
            if (!empty($search['pagination'])) $limit = $search['pagination'];
        }
		if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        } else{
			$this->set('serial_num',1);
		} 

		$query = $this->DepositApplicationWallet->find()->select(['id','user_id','amount','unit','created','updated','u.name','u.phone_number']);
		$query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);

		$total_amount = $this->DepositApplicationWallet->find()->select(['total_amount'=>'sum(amount)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);
		
		if($this->request->query('search_value')){
			$search_value = $this->request->query('search_value');
			if(is_numeric($search_value)){
				$query = $query -> where(['OR'=>[['u.phone_number' =>$search_value],['user_id'=> $search_value]]]);
				$total_amount = $total_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
			} else {
				$query = $query -> where(['u.name' => $search_value]);
				$total_amount = $total_amount -> where(['u.name' => $search_value]);
			}
		}
		if($this->request->query('sort_value')){
			$sort_value = $this->request->query('sort_value');
			$order_value = $this->request->query('order_value');
			if($session->read('wallet_sort') == $sort_value){
				$this->request->session()->write('wallet_sort', '');
			}else{
				$this->request->session()->write('wallet_sort', $sort_value);
			}
			$query = $query->order([$order_value=>$sort_value]);
		}else{
			$query = $query->order(['DepositApplicationWallet.id'=> 'DESC']);
		}

		$query = $query->limit($limit);
		try {
			$wallet_list =  $this->Paginator->paginate($query);
		} catch (NotFoundException $e) {
			$this->request->query['page'] = 1;
			$wallet_list =  $this->Paginator->paginate($query);
		}
		$total_amount = $total_amount->first();
        $this->set('wallet_list',$wallet_list);
		$this->set('total_amount',$total_amount);
	}

    /* 투자 수익금 로그 리스트 */
    public function loglist2(){
        $this->loadModel('DepositApplicationLog');
        $this->loadModel('DepositApplicationStage');
        $limit =  $this->setting['pagination'];
        $search = $this->request->data;
        $search_value = '';
        $session = $this->request->session();


        if ($this->request->is(['post' ,'put']) ) {
            if (!empty($search['pagination'])) $limit = $search['pagination'];
        }
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        } else{
            $this->set('serial_num',1);
        }

        $query = $this->DepositApplicationLog->find()->select(['id','user_id','list_id','amount','type','created','u.name','u.phone_number','investment_number']);
        $query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);

        if($this->request->query('search_value')){
            $search_value = $this->request->query('search_value');
            if(is_numeric($search_value)){
                $query = $query -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
            } else {
                $query = $query -> where(['u.name' => $search_value]);
            }
        }
        if($this->request->query('investment_number')){
            $query = $query->where(['investment_number' => $this->request->query('investment_number')]);
        }
        if($this->request->query('type')){
            $query = $query->where(['type' => $this->request->query('type')]);
        }

        if($this->request->query('sort_value')){
            $sort_value = $this->request->query('sort_value');
            $order_value = $this->request->query('order_value');
            if($session->read('log_sort') == $sort_value){
                $this->request->session()->write('log_sort', '');
            }else{
                $this->request->session()->write('log_sort', $sort_value);
            }
            $query = $query->order([$order_value=>$sort_value]);
        }else{
            $query = $query->order(['DepositApplicationLog.id'=> 'DESC']);
        }

        $query = $query->limit($limit);
        try {
            $log_list =  $this->Paginator->paginate($query);
        } catch (NotFoundException $e) {
            $this->request->query['page'] = 1;
            $log_list =  $this->Paginator->paginate($query);
        }
        $stage_list = $this->DepositApplicationStage->find()->select(['stage'])->where(['id !='=>1])->all();

        $this->set('stage_list',$stage_list);
        $this->set('log_list',$log_list);
    }
    /* 투자 수익금 지갑 리스트 */
    public function walletlist2(){
        $this->loadModel('DepositApplicationWallet');
        $limit =  $this->setting['pagination'];
        $search = $this->request->data;
        $search_value = '';
        $session = $this->request->session();


        if ($this->request->is(['post' ,'put']) ) {
            if (!empty($search['pagination'])) $limit = $search['pagination'];
        }
        if($this->request->query('page')) {
            $this->set('serial_num',(($limit)*($this->request->query('page'))) - ($limit -1));
        } else{
            $this->set('serial_num',1);
        }

        $query = $this->DepositApplicationWallet->find()->select(['id','user_id','amount','unit','created','updated','u.name','u.phone_number']);
        $query = $query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);

        $total_amount = $this->DepositApplicationWallet->find()->select(['total_amount'=>'sum(amount)'])->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = user_id']]);

        if($this->request->query('search_value')){
            $search_value = $this->request->query('search_value');
            if(is_numeric($search_value)){
                $query = $query -> where(['OR'=>[['u.phone_number' =>$search_value],['user_id'=> $search_value]]]);
                $total_amount = $total_amount -> where(['OR'=>[['u.phone_number' => $search_value],['user_id'=> $search_value]]]);
            } else {
                $query = $query -> where(['u.name' => $search_value]);
                $total_amount = $total_amount -> where(['u.name' => $search_value]);
            }
        }
        if($this->request->query('sort_value')){
            $sort_value = $this->request->query('sort_value');
            $order_value = $this->request->query('order_value');
            if($session->read('wallet_sort') == $sort_value){
                $this->request->session()->write('wallet_sort', '');
            }else{
                $this->request->session()->write('wallet_sort', $sort_value);
            }
            $query = $query->order([$order_value=>$sort_value]);
        }else{
            $query = $query->order(['DepositApplicationWallet.id'=> 'DESC']);
        }

        $query = $query->limit($limit);
        try {
            $wallet_list =  $this->Paginator->paginate($query);
        } catch (NotFoundException $e) {
            $this->request->query['page'] = 1;
            $wallet_list =  $this->Paginator->paginate($query);
        }
        $total_amount = $total_amount->first();
        $this->set('wallet_list',$wallet_list);
        $this->set('total_amount',$total_amount);
    }
	/* 투자 기수 추가 */
	public function addstage(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationStage');
			$query = $this->DepositApplicationStage->query();
			$query->insert(['stage','status','created','coin_name'])->values(['stage'=>$this->request->data['stage'],'status'=>'N','created'=>date('Y-m-d H:i:s'),'coin_name'=>$this->request->data['stage_coin']])->execute();
			$this->add_system_log(200, 0, 2, '투자 기수 추가 ('.$this->request->data['stage'].')');
			echo "success";
		}
		die;
	}
	/* 투자 기수 상태 변경 */
	public function stagechange(){
		if($this->request->is('ajax')) {
			$this->loadModel('DepositApplicationStage');
			$id = $this->request->data['id'];
			$origin_status = $this->request->data['status'];
			if($origin_status == 'N'){
				$change_status = 'Y';
			} else if($origin_status == 'Y'){
				$change_status = 'N';
			}
			$all_update_query = $this->DepositApplicationStage->query();
			$all_update_query->update()->set(['status'=>'N'])->execute();
			$query = $this->DepositApplicationStage->query();
			$query->update()->set(['status'=>$change_status])->where(['id'=>$id])->execute();
			$this->add_system_log(200, 0, 3, '투자 기수 상태 변경 (id : '.$id.')');

			$count = $this->DepositApplicationStage->find()->where(['status'=>'Y'])->count();
			if($count < 1){
				$query = $this->DepositApplicationStage->query();
				$query->update()->set(['status'=>'Y'])->where(['id'=>1])->execute();
			}
			echo "success";
		}
		die;
	}
	/* 인출 오류로 지갑/로그 꼬이는 현상-> 복구 위해 만들어짐 */
	public function personalupdate(){
		$this->loadModel('DepositApplicationLog');
		$this->loadModel('DepositApplicationList');
		$this->loadModel('DepositApplicationWallet');
		if($this->request->is('ajax')) {
			$list_select_sql = '(SELECT sum(amount_received) FROM deposit_application_list where user_id = DepositApplicationWallet.user_id)';
			$query = $this->DepositApplicationWallet->find()->select(['id','user_id','amount','unit','created','list_amount'=>$list_select_sql]);
			$wallet_list = $query -> all();
			
			$update_user_id_arr = [];
			$not_update_user_id_arr = [];
			$update_amount_arr = [];
			$update_list_id_arr = [];
			$update_count = 0;
			$not_update_count = 0;

			foreach($wallet_list as $l){
				$amount = $l->amount;
				$list_amount = $l->list_amount;
				$update_amount = 0;
				if($amount != $list_amount){
					array_push($update_user_id_arr,$l->user_id);
					$update_amount = (float)$amount - (float)$list_amount;
					$list_id = $this->update_list_amount($l->user_id,$update_amount);
					array_push($update_amount_arr,$update_amount);
					array_push($update_list_id_arr,$list_id);
					$update_count++;
				} else {
					array_push($not_update_user_id_arr,$l->user_id);
					$not_update_count++;
				}
			}
			$respArr = ["success"=>"true","update_user_id"=>$update_user_id_arr,"update_amount"=>$update_amount_arr,"update_list_id"=>$update_list_id_arr,"update_count"=>$update_count,"not_update_user_id"=>$not_update_user_id_arr,"not_update_count"=>$not_update_count];

			echo json_encode($respArr);
			die;
		}
	}
	/* 인출 오류로 지갑/로그 꼬이는 현상-> 복구 위해 만들어짐 */
	public function update_list_amount($user_id,$update_amount){
		$this->loadModel('DepositApplicationList');
		$total = 0;
		$list_id = $this->DepositApplicationList->find()->select(['id','amount_received'])->where(['user_id'=>$user_id,'status'=>'A'])->order(['id'=>'asc'])->first();
		if(!empty($list_id)){
			$total = (float)$update_amount + (float)($list_id->amount_received);
			$query = $this->DepositApplicationList->query();
			$query->update()->set(['amount_received'=>$total])->where(['id'=>$list_id->id])->execute();
			return $list_id->id;
		}
		return 'fail';
	}
	/* 일별 투자 신청 총금액 가져오기 */
	public function getdatetotalquantity(){
		$this->loadModel('DepositApplicationList');
		$respArr = [];
		if($this->request->is('ajax')) {
			$start_date = date($this->request->data['days'].' 00:00:00');
			$end_date = date($this->request->data['days'].' 23:59:59');
			$total_quantity = $this->DepositApplicationList->find()->select(['these_days_total'=>'ifnull(SUM(quantity),0)'])->where(['status !='=>'C','created >='=>$start_date,'created <'=>$end_date])->first();
			$respArr = ['status'=>'success','total_quantity'=>number_format($total_quantity->these_days_total,2)];
		}
		echo json_encode($respArr); die;
	}
	/* 일별 투자 수익금 총 지급액 가져오기 */
	public function getdatetotalprofits(){
		$this->loadModel('DepositApplicationLog');
		$respArr = [];
		if($this->request->is('ajax')) {
			$start_date = date($this->request->data['days'].' 00:00:00');
			$end_date = date($this->request->data['days'].' 23:59:59');
			$total_profits = $this->DepositApplicationLog->find()->select(['these_days_total'=>'ifnull(SUM(amount),0)'])->where(['type'=>'S','created >='=>$start_date,'created <'=>$end_date])->first();
			$respArr = ['status'=>'success','total_profits'=>number_format($total_profits->these_days_total,2)];
		}
		echo json_encode($respArr); die;
	}

    /* 투자기간 종료 후 투자금 고객에게 다시 보내주기 */
    public function returncoin(){
        //투자자 회원 리스트를 전체를 가져 온다
        $this->loadModel('DepositApplicationList');
        $this->loadModel('PrincipalWallet');
        //$query = $this->DepositApplicationList->find()->select(['*'])->where(['status !='=>'C','created >='=>$start_date,'created <'=>$end_date])->first();
        $query = $this->DepositApplicationList->find()->all();;

        //print_r($query);
        $today = date('Y-m-d');
        foreach ($query as $a){
            //실제 데이터 날짜있는 경우에만 데이터 계산을 시작
            if($a['approval_date']){
                $startday = date_format($a['approval_date'],"Y-m-d");//투자시작 기간
                $beday = $a['service_period_month']; //투자기간
                $coin = $a['unit']; //코인명
                $amount = $a['quantity']; //코인수량
                $retunday = date("Y-m-d",strtotime("+$beday day",strtotime($startday))); //투자 만료일짜
                if($today > $retunday){
                    echo "투자기간 만료<br>";
                }else{
                    //update 쿼리 실행
                    //log 지갑에 업데이트 및 데이터 삽입
                    echo "투자기간임<br>";
                    //update를 해주자 일단 기본적으로 내 아이디로 테스트
                    //3217아이디 status 값을 변경해준다
                    //$query->update()->set(['status'=>'C','updated'=>date('Y-m-d H:i:s')])->where(['id' => $id])->execute();
                    //$update_query = $this->DepositApplicationList->query()->update()->set(['status'=>'A'])->where(['user_id'=>'3217'])->execute();

                    if($coin == "TP3"){
                        $coin_number = '17';
                    }else if($coin == "MC"){
                        $coin_number = '19';
                    }

                    //Payback으로 지갑에 다시 돌려준다.
                    $query_insert = $this->PrincipalWallet->query();
                    $query_insert->insert(['user_id','cryptocoin_id','amount','withdrawal_send','type','status','multisign','created_at'])->
                    values(['user_id'=>$a['user_id'],'cryptocoin_id'=>$coin_number,'amount'=>$a['quantity'],'withdrawal_send'=>"N",'type'=>'payback','status'=>'completed','multisign'=>'N','created_at'=>date('Y-m-d H:i:s')])->execute();

                    $update_query = $this->DepositApplicationList->query()->update()->set(['payback'=>'Y','paybackdate'=>date('Y-m-d H:i:s')])->where(['user_id'=>$a['user_id']])->execute();
                }
                echo $a['id'].date_format($a['approval_date'],"Y-m-d")."투자일 :".$beday."투자만료기간 :".$retunday."<br>";
            }else{
                echo $a['id']."미승인"."<br>";
            }
        }
        //echo $today;

        echo "완료";
        die;
        //오늘 날짜와 신청 날짜 그리고 투자기간을 계산한다.

    }

    public function pointlog(){

        //로그를 쌓도록 하자 

    }


}
