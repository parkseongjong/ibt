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

class LeftbarController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}
	public function leftbarlist(){
		$this->loadModel('LevelPages');
		$session = $this->request->session();

		$query = $this->LevelPages->find()->select(['id','level_id','treeview_cnt','status','treeview','treeview_name','treeview_sort','url','menu_name','sort_no','created']);
		if($this->request->data('sort_value')){
			if($session->read('leftbar_sort') == $this->request->data('sort_value')){
				$this->request->session()->write('leftbar_sort', '');
				$query = $query->order([$this->request->data('sort_value')=>'DESC']);
			}else{
				$this->request->session()->write('leftbar_sort', $this->request->data('sort_value'));
				$query = $query->order([$this->request->data('sort_value')=>'ASC']);
			}
		}else{
			$query = $query->order(['sort_no'=> 'ASC','treeview_sort'=> 'ASC']);
		}
		$collectdata = $query->all();
		$this->set('listingNew',$collectdata);
	}

	public function add(){
		if ($this->request->is(['post','put'])) {
			$this->loadModel('LevelPages');
			$user = $this->Auth->user();
			$treeview_sort = 0;
			$treeview_cnt = 1;

			$last_sort_no = $this->LevelPages->find()->select(['sort_no'])->order(['sort_no'=>'DESC'])->first();
			$sort_no = $last_sort_no->sort_no + 1;
			if($this->request->data['treeview_name'] != '' && $this->request->data['treeview'] == 'Y'){
				$last_sort_no = $this->LevelPages->find()->select(['sort_no'])->where(['treeview_name'=>$this->request->data['treeview_name']])->first();
				if($last_sort_no){
					$sort_no = $last_sort_no->sort_no;
				}
				$last_treeview_sort = $this->LevelPages->find()->select(['treeview_sort'])->where(['treeview_name'=>$this->request->data['treeview_name']])->order(['treeview_sort'=>'DESC'])->first();
				if($last_treeview_sort){
					$treeview_sort = $last_treeview_sort->treeview_sort + 1;
					$treeview_cnt = $treeview_sort+1;
				}
				$updateQuery = $this->LevelPages->query();
				$updateQuery->update()->set(['treeview_cnt'=>$treeview_cnt])->where(['treeview_name'=>$this->request->data['treeview_name']])->execute();
			}

			$query = $this->LevelPages->query();
			$query->insert(['level_id', 'page','status','treeview','treeview_name','treeview_sort','treeview_cnt','treeview_icon_class1','treeview_icon_class2','icon_class','url','menu_name','sort_no','created','created_id','last_id'])
				->values([
					'level_id' => $this->request->data['level_id'],
					'page' => $this->request->data['menu_name'],
					'status' => $this->request->data['status'],
					'treeview' => $this->request->data['treeview'],
					'treeview_name' => $this->request->data['treeview_name'],
					'treeview_sort' => $treeview_sort,
					'treeview_cnt'=> $treeview_cnt,
					'treeview_icon_class1' => $this->request->data['treeview_icon_class1'],
					'treeview_icon_class2' => $this->request->data['treeview_icon_class2'],
					'icon_class' => $this->request->data['icon_class'],
					'url' => $this->request->data['url'],
					'menu_name' => $this->request->data['menu_name'],
					'sort_no' => $sort_no,
					'created' => date('Y-m-d H:i:s'),
					'created_id' => $user['id'],
					'last_id' => $user['id']
				])
				->execute();
				$this->add_system_log(200, $user['id'], 2, '관리자 메뉴 : '.$this->request->data['menu_name'].' 추가');
				return $this->redirect(['controller'=>'Leftbar','action' => 'leftbarlist']);
		} 

		$this->loadModel('Levels');
		$level_list = $this->Levels->find()->select(['id','level_name'])->where(['status'=>'Y'])->all();
		$treeview_list = $this->LevelPages->find()->select(['treeview_name'])->distinct(['treeview_name'])->where(['treeview'=>'Y'])->all();
		$this->set('level',$level_list);
		$this->set('treeview_list',$treeview_list);
		
	}

	public function treeview_arrangement($id, $treeview_name,$type){
		$this->loadModel('LevelPages');
		if($type == 'minus'){
			$query = $this->LevelPages->query();
			$query->update()->set(['treeview'=>'N','treeview_name'=>'','treeview_cnt'=>0,'treeview_sort'=>0])->where(['id' => $id])->execute();

			$treeview_list = $this->LevelPages->find()->select(['id','treeview','treeview_name','treeview_cnt','treeview_sort'])->where(['treeview_name' => $treeview_name,'treeview'=>'Y'])->order(['treeview_sort'=>'asc'])->all();
			$treeview_cnt = count($treeview_list);
			$treeview_sort = 0;
			foreach($treeview_list as $l){
				$update_query = $this->LevelPages->query();
				$update_query->update()->set(['treeview_cnt'=>$treeview_cnt,'treeview_sort'=>$treeview_sort])->where(['id' => $l->id])->execute();
				$treeview_sort++;
			}
			return true;
		} else if ($type == 'get'){
			$treeview = $this->LevelPages->find()->select(['id','treeview','treeview_name','treeview_cnt','treeview_sort','sort_no'])
				->where(['treeview_name' => $treeview_name,'treeview'=>'Y'])->order(['treeview_sort'=>'DESC'])->first();
			// get sort no
			// update treeview cnt and get 
			// get treeview sort no
			$treeview_cnt = $treeview->treeview_cnt+1;
			$treeview_sort = $treeview->treeview_sort+1;

			$update_query = $this->LevelPages->query();
			$update_query->update()->set(['treeview_cnt'=>$treeview_cnt])->where(['treeview_name' => $treeview_name,'treeview'=>'Y'])->execute();
			$returnArr = ['sort_no'=>$treeview->sort_no,'treeview_sort'=>$treeview_sort,'treeview_cnt'=>$treeview_cnt ];
			return $returnArr;
		}
	}

	public function get_last_sort_no(){
		$this->loadModel('LevelPages');
		$last_sort_no = $this->LevelPages->find()->select(['sort_no'])->order(['sort_no'=>'desc'])->first();
		return $last_sort_no->sort_no+1;
	}

	public function edit($id) {
		$this->loadModel('LevelPages');
		$this->loadModel('Levels');
	
		if ($this->request->is(['post','put'])) {
			$user = $this->Auth->user();
			$get_origin_info = $this->LevelPages->find()->select(['treeview','treeview_name','treeview_cnt','treeview_sort','sort_no'])->where(['id' => $id])->first();
			$sort_no = $get_origin_info->sort_no;
			$treeview_cnt = $get_origin_info->treeview_cnt;
			$treeview_sort = $get_origin_info->treeview_sort;

			if($get_origin_info->treeview == 'Y'){ // if origin data is treeview
				$treeview_name = $this->request->data['treeview_name'];
				if($get_origin_info->treeview_name != $treeview_name){ // change or remove
					$this->treeview_arrangement($id,$get_origin_info->treeview,'minus'); // 원래 트리뷰 -> 트리뷰 제거 == 원래 트리뷰들 -1 treeview_cnt, -1 treeview_sort (중간꺼 빠져나갈 수 있으니 재정렬시켜야함..)

					if($this->request->data['treeview'] == 'Y'){ // change
						$get_treeview_cnt = $this->LevelPages->find()->where(['treeview_name'=>$treeview_name])->count();
						if($get_treeview_cnt > 0){ // 변경할 트리뷰가 이미 있는 트리뷰 일 경우
							$get_treeview_info = $this->treeview_arrangement($id,$treeview_name,'get'); 
							$treeview_cnt = $get_treeview_info['treeview_cnt'];
							$treeview_sort = $get_treeview_info['treeview_sort'];
							$sort_no = $get_treeview_info['sort_no'];
						} else {
							$treeview_cnt = 1;
							$treeview_sort = 0;
							$sort_no = $this->get_last_sort_no(); // get last sort no
						}
					} else if($this->request->data['treeview'] == 'N'){ // remove
						$treeview_cnt = 0;
						$treeview_sort = 0;
						$sort_no = $this->get_last_sort_no(); // get last sort no
					}				
				}
			} else if($get_origin_info->treeview == 'N') { // if origin data is not treeview
				if($this->request->data['treeview'] == 'Y'){ // chages treeview
					$treeview_name = $this->request->data['treeview_name'];
					$get_treeview_cnt = $this->LevelPages->find()->where(['treeview_name'=>$treeview_name])->count();
					if($get_treeview_cnt > 0){ // 변경할 트리뷰가 이미 있는 트리뷰 일 경우
						$get_treeview_info = $this->treeview_arrangement($id,$treeview_name,'get'); 
						$treeview_cnt = $get_treeview_info['treeview_cnt'];
						$treeview_sort = $get_treeview_info['treeview_sort'];
						$sort_no = $get_treeview_info['sort_no'];
					} else {
						$treeview_cnt = 1;
						$treeview_sort = 0;
						//$sort_no = $this->get_last_sort_no(); // get last sort no
					}
				}
			}
			// 1. 원래 트리뷰 -> 그대로 == same treeview_cnt, same treeview_sort
			// 2. 원래 트리뷰 -> 트리뷰 제거 == 원래 트리뷰들 -1 treeview_cnt, -1 treeview_sort (중간꺼 빠져나갈 수 있으니 재정렬시켜야함..)
			// 3. 원래 트리뷰 -> 다른 트리뷰 == 원래 트리뷰들 -1 treeview_cnt, -1 treeview_sort , 새 트리뷰 + 1 treeview cnt, sort
			// 4. 트리뷰 아님 -> 트리뷰 == 새 트리뷰 + 1 treeview cnt, sort
			// 5. 트리부 아님 -> 트리뷰 아님 == ''

			// 수정해야할 것 -> 1. treeview_cnt, 2. treeview_sort
			$query = $this->LevelPages->query();
			$query->update()->set([
					'level_id' => $this->request->data['level_id'],
					'menu_name' => $this->request->data['menu_name'],
					'page' => $this->request->data['menu_name'],
					'status' => $this->request->data['status'],
					'url' => $this->request->data['url'],
					'icon_class' => $this->request->data['icon_class'],
					'treeview' => $this->request->data['treeview'],
					'treeview_name' => $this->request->data['treeview_name'],
					'treeview_icon_class1' => $this->request->data['treeview_icon_class1'],
					'treeview_icon_class2' => $this->request->data['treeview_icon_class2'],
					'treeview_cnt'=>$treeview_cnt,
					'treeview_sort'=>$treeview_sort,
					'sort_no'=>$sort_no,
					'updated'=>date('Y-m-d H:i:s'),
					'last_id' => $user['id']
				])->where(['id' => $id])->execute();

			$this->add_system_log(200, $user['id'], 3, '관리자 메뉴 id : '.$id.' 수정');
			return $this->redirect(['controller'=>'Leftbar','action' => 'leftbarlist']);
		}

		$level_list = $this->Levels->find()->select(['id','level_name'])->where(['status'=>'Y'])->all();

		$select_query = $this->LevelPages->find()->select(['LevelPages.id','level_id','status','treeview','treeview_name','treeview_icon_class1','treeview_icon_class2','icon_class','url','menu_name','created','updated','last_id','u.name']);
		$select_query = $select_query->join(['u' => ['table' => 'users','type' => 'inner','conditions' => 'u.id = last_id']]);
		$left_bar =  $select_query->where(['LevelPages.id' => $id])->first();
		$treeview_list = $this->LevelPages->find()->select(['treeview_name'])->distinct(['treeview_name'])->where(['treeview'=>'Y'])->all();
		$this->set('level',$level_list);
		$this->set('left_bar',$left_bar);
		$this->set('treeview_list',$treeview_list);
    }

	public function changeStatus(){
		if($this->request->is('ajax')) {
			$this->loadModel('LevelPages');
			$user = $this->Auth->user();
			if (!empty($this->request->data['id'])) {
				$id = $this->request->data['id'];
				$status = $this->request->data['status'];
				if($status == 'Y'){
					$change_status = 'N';
				} else if ($status == 'N'){
					$change_status = 'Y';
				}
				$query = $this->LevelPages->query();
				$query->update()->set(['status'=>$change_status,'last_id' => $user['id']])->where(['id' => $id])->execute();
				$this->add_system_log(200, $user['id'], 3, '관리자 메뉴 id : '.$id.' 활성/비활성 수정');
				echo "success";
			}
		}
		die;
	}

	public function changeSort(){
		$this->loadModel('LevelPages');
		$user = $this->Auth->user();

		if($this->request->is('ajax')) {
			$type = $this->request->data['type'];
			$sort_no = $this->request->data['sort_no'];
			if($type == 'up'){
				$now_id = $this->LevelPages->find()->select(['id','sort_no'])->where(['sort_no'=>$sort_no])->all();
				$origin_id = $this->LevelPages->find()->select(['id','sort_no'])->where(['sort_no'=>$sort_no-1])->all();
				if(count($origin_id) > 0){
					$origin_arr = array();
					foreach($origin_id as $o){
						array_push($origin_arr,$o->id);
					}
					$query = $this->LevelPages->query();
					$query->update()->set(['sort_no' => $sort_no,'updated'=>date('Y-m-d H:i:s'),'last_id' => $user['id']])->where(['id IN' => $origin_arr])->execute();
				}
				$now_arr = array();
				foreach($now_id  as $n){
					array_push($now_arr,$n->id);			
				}
				$query = $this->LevelPages->query();
				$query->update()->set([ 'sort_no' => $sort_no-1, 'updated'=>date('Y-m-d H:i:s'), 'last_id' => $user['id'] ])->where(['id IN' => $now_arr])->execute();

				
			} else if($type == 'down'){
				$now_id = $this->LevelPages->find()->select(['id','sort_no'])->where(['sort_no'=>$sort_no])->all();
				$origin_id = $this->LevelPages->find()->select(['id','sort_no'])->where(['sort_no'=>$sort_no+1])->all();
				if(count($origin_id) > 0){
					$origin_arr = array();
					foreach($origin_id as $o){
						array_push($origin_arr,$o->id);
					}
					$query = $this->LevelPages->query();
					$query->update()->set(['sort_no' => $sort_no,'updated'=>date('Y-m-d H:i:s'),'last_id' => $user['id']])->where(['id IN' => $origin_arr])->execute();
				}
				$now_arr = array();
				foreach($now_id  as $n){
					array_push($now_arr,$n->id);			
				}
				$query = $this->LevelPages->query();
				$query->update()->set(['sort_no' => $sort_no+1,'updated'=>date('Y-m-d H:i:s'),'last_id' => $user['id']])->where(['id IN' => $now_arr])->execute();
			}
			$this->add_system_log(200, $user['id'], 3, '관리자 메뉴 순서 수정');
			echo "success";
		}

		die;
	}

	public function menuDelete(){
		if($this->request->is('ajax')) {
			$this->loadModel('LevelPages');
			if (!empty($this->request->data['id'])) {
				$id = $this->request->data['id'];
				$treeviews = $this->LevelPages->find()->select(['treeview','treeview_cnt','treeview_name'])->where(['id IN'=>$id])->all();
				foreach($treeviews as $t){
					if($t->treeview == 'Y'){
						$update_query = $this->LevelPages->query();
						$update_query->update()->set(['treeview_cnt'=>$t->treeview_cnt-1])->where(['treeview_name' => $t->treeview_name])->execute();
					}
				}
				$delete_query = $this->LevelPages->query();
				$delete_query->delete()->where(['id IN' => $id])->execute();
				$this->add_system_log(200, 0, 4, '관리자 메뉴 :' . implode(",",$id). ' 삭제');
				echo "success";
				die;
			}
			echo "fail";
			die;
		}
		echo "fail";
		die;
	}

	public function checkmenuname(){
		if($this->request->is('ajax')) {
			$this->loadModel('LevelPages');
			$returnArr = ['status' => 'fail', 'msg' => 'value is empty'];
			if (!empty($this->request->data['value']) && !empty($this->request->data['type'])) {
				$value = $this->request->data['value'];
				$type = $this->request->data['type'];
				$value_count = $this->LevelPages->find()->where([$type=>$value])->count();
				if($value_count > 0){
					$returnArr = ['status' => 'fail', 'msg' => 'already exist'];
				} else {
					$returnArr = ['status' => 'success', 'msg' => ''];
				}
			}
			echo json_encode($returnArr);
		}
		die;
	}

	public function get_sort(){
		$this->loadModel('LevelPages');
		$all_count = $this->LevelPages->find()->count();
		$list = $this->LevelPages->find()->select(['id','treeview_cnt','status','treeview','treeview_name','treeview_sort','sort_no'])->order(['sort_no'=>'asc'])->all();
		for($i = 0; $i < $all_count; $i++){
			if($list[$i]->treeview == 'Y'){
			
			} else if ($list[$i]->treeview == 'N'){
			
			}
		}
	}
	
	
}
