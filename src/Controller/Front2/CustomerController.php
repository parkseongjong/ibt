<?php
namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;


//use Cake\ORM\TableRegistry;
//use Cake\Datasource\ConnectionManager; 
//use Cake\Mailer\Email;
//use Cake\Auth\DefaultPasswordHasher;


class CustomerController extends AppController
{
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }

	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);

        $this->Auth->allow(['infoListed','board','view']);
    }

    public function index() {
    }

    ## Board
    public function board($kind='notice', $page=1) {
		
        $board_title = array(
            'notice' => __('Notice'),
            'faq' => __('FAQ'),
            'joininfo' => __('Membership Registration'),
            'authinfo' => __('Authentication method guide'),
            'qna' => __('1:1 Inquiries'),
        );

        $this->set('kind', $kind);
        $this->set('board_title', $board_title);
		$userId = $this->Auth->user('id');
		$lang = !empty($_COOKIE['Language']) ? $_COOKIE['Language'] : 'en_US';



		if ( $kind == 'notice' ) {
			$user_type = 'U';
			if(!empty($userId)){
				$user_type = $this->Auth->user('user_type');
			}
			$this->set('user_type', $user_type);
			$limit = 10;
			
			$this->loadModel('BoardNotice');

			/*$allBoardNoticeList = $this->BoardNotice->find('all',[
					'conditions'=>['lang '=>$lang],
					'order'=>['id'=>'desc'],
					'limit' => $limit,
				] )
				 ->hydrate(false)
				 ->toArray();
			$this->set('listing',$allBoardNoticeList);	
			*/

			//if ( $this->request->is('get') ) {
			if ( !empty( $this->request->data['keyword'] ) ) {
				$keyword = $this->request->data['keyword'];
				$conditions = ['subject LIKE'=>'%' . $keyword . '%', 'lang'=>$lang];
			} else if ( !empty( $_REQUEST['keyword'] ) ) {
				$keyword = $_REQUEST['keyword'];
				$conditions = ['subject LIKE'=>'%' . $keyword . '%', 'lang'=>$lang];
			} else {
				$conditions = ['lang '=>$lang];
			}
			
			$allBoardNoticeList = $this->Paginator->paginate($this->BoardNotice, [
				'conditions'=>$conditions,
				'order'=>['id'=>'desc'],
				'limit' => $limit
			]);
			$this->set('listing',$allBoardNoticeList);


			////if ( $this->request->is('post') ) {
			//	$this->set('posts', $this->Paginator->paginate());
			//}


			/*$allBoardNoticeListCount = $this->BoardNotice->find('all',[
					'conditions'=>$conditions])
					->hydrate(false)
					->count();
			$this->set('listingcount',$allBoardNoticeListCount);*/
			$this->set('limit',$limit);
			
			//if ( !empty( $this->request->data['page'] ) ) {
			//	$page = $this->request->data['page'];
			//}
			//$startnum = $allBoardNoticeListCount - ( $limit * ($page-1) );
			//$this->set('startnum',$page);

		} else if ( $kind == 'qna' ) {
            $limit = 10;

            $this->loadModel('Users');

            $user = $this->Users->get($userId);
            $name = $user['name'];
            $this->loadModel('BoardQna');



            if (!empty($this->request->data['keyword'])) {
                $keyword = $this->request->data['keyword'];
                $conditions = ['subject LIKE' => '%' . $keyword . '%', 'users_id' => $userId];
            } else if (!empty($_REQUEST['keyword'])) {
                $keyword = $_REQUEST['keyword'];
                $conditions = ['subject LIKE' => '%' . $keyword . '%', 'users_id' => $userId];
            } else {
				if($user['user_type']!='A'){
					$conditions = ['users_id ' => $userId];
				}else{
					$conditions ="";
				}
               
            }
            $allBoardQnaList = $this->Paginator->paginate($this->BoardQna, [
                'conditions' => $conditions,
                'order' => ['id' => 'desc'],
                'limit' => $limit,
            ]);

            $this->set('listing', $allBoardQnaList);
            $this->set('user', $user);
            $this->set('name', $name);

            $allBoardQnaListCount = $this->BoardQna->find('all', [
                'conditions' => $conditions])
                ->hydrate(false)
                ->count();
            
			if ( $allBoardQnaListCount == 0 ) {
				$this->redirect(['controller' => 'customer', 'action' => 'edit', $kind]);
			}

		}

    } //

	public function view($kind='notice', $board_id, $page, $keyword='') {
        $board_title = array(
            'notice' => __('Notice'),
            'faq' => __('FAQ'),
            'joininfo' => __('Membership Registration'),
            'authinfo' => __('Authentication method guide'),
            'qna' => __('1:1 Inquiries'),
        );

        $this->set('kind', $kind);
        $this->set('board_title', $board_title);
		
        $this->set('page', $page);
        $this->set('keyword', $keyword);

        $this->viewBuilder()->template('view');
		
		if ( $kind == 'notice' ) {
			$this->loadModel('BoardNotice');
			$boardNoticeInfos = $this->BoardNotice->find('all',['conditions'=>['id '=>$board_id]])->hydrate(false)->first();
			$this->set('boardinfos',$boardNoticeInfos);	

			$before_id = 0;
			$after_id = 0;
			
			$lang = !empty($_COOKIE['Language']) ? $_COOKIE['Language'] : 'en_US';
			
			if ( !empty($keyword) ) {
				$beforeInfos = $this->BoardNotice->find('all',['conditions'=>['id < '=>$board_id,'subject LIKE'=>'%' . $keyword . '%', 'lang'=>$lang],'order'=>['id'=>'desc']])->hydrate(false)->first();
				$afterInfos = $this->BoardNotice->find('all',['conditions'=>['id > '=>$board_id,'subject LIKE'=>'%' . $keyword . '%', 'lang'=>$lang],'order'=>['id'=>'asc']])->hydrate(false)->first();
			} else {
				$beforeInfos = $this->BoardNotice->find('all',['conditions'=>['id < '=>$board_id,'lang'=>$lang],'order'=>['id'=>'desc']])->hydrate(false)->first();
				$afterInfos = $this->BoardNotice->find('all',['conditions'=>['id > '=>$board_id,'lang'=>$lang],'order'=>['id'=>'asc']])->hydrate(false)->first();
			}

			if ( !empty($beforeInfos['id']) ) {
				$before_id = $beforeInfos['id'];
			}
			if ( !empty($afterInfos['id']) ) {
				$after_id = $afterInfos['id'];
			}
			$this->set('before_id',$before_id);	
			$this->set('after_id',$after_id);	
		} else if ( $kind == 'qna' ) {
			$this->loadModel('BoardQna');
			$userId = $this->Auth->user('id');
			$user = $this->Users->get($userId);

            $this->set('user', $user);
            $this->set('user_type',$user['user_type']);

			$boardQnaInfos = $this->BoardQna->find('all',['conditions'=>['id'=>$board_id, 'users_id'=>$userId]])->hydrate(false)->first();
			$this->set('boardinfos',$boardQnaInfos);

            $this->set('board_id',$board_id);
//			$boardQnaInfos2 = '';
//			if ( !empty($boardQnaInfos) ) {
//				$boardQnaInfos2 = $this->BoardQna->find('all',['conditions'=>['qna_type'=>'A', 'board_qna_id '=>$board_id]])->hydrate(false)->first();
//			}
//			$this->set('boardinfos2',$boardQnaInfos2);
           // print_r("Board ID: "+$board_id);
		}
	} //

	public function edit($kind='notice', $pid='') {
        $board_title = array(
            'notice' => __('Notice'),
            'faq' => __('FAQ'),
            'joininfo' => __('Membership Registration'),
            'authinfo' => __('Authentication method guide'),
            'qna' => __('1:1 Inquiries'),
        );

        $this->set('kind', $kind);
        $this->set('board_title', $board_title);
		
		$userId = $this->Auth->user('id');

		$user = $this->Users->get($userId);
		$this->set('user', $user);
		$name = $user['name'];

		
        $users = $this->Users->get($_SESSION['Auth']['User']['id']);


		// Notice : Only administrators can write.
		if ( $kind == 'notice' && $user['user_type'] != 'A' ) {
			$this->Flash->error(__('You do not have access.'));
			return $this->redirect(['controller' => 'customer', 'action' => 'board', $kind]);
		}


		if ($this->request->is(['post','put'])) {
		//if ($this->request->is('post')) {
			$category="";
			if(!empty($this->request->data['category'])){
				$category = strip_tags($this->request->data['category']);

			}
			$subject = filter_var(strip_tags($this->request->data['subject']), FILTER_SANITIZE_STRING);
			$contents = filter_var(strip_tags($this->request->data['contents']), FILTER_SANITIZE_STRING);
			
			// strip_tags : alert('go');
			// filter_val, filter_val(strip_tags) : alert(&#39;go&#39;);

			$insertArr = [];

			if ( $kind == 'notice' ) {

				$this->loadModel('BoardNotice');
				$lang = !empty($_COOKIE['Language']) ? $_COOKIE['Language'] : 'en_US';
				
				$insertArr['category'] = !empty($category) ? $category : '';
				$insertArr['subject'] = $subject;
				$insertArr['contents'] = $contents;
				$insertArr['users_id'] = $userId;
				$insertArr['lang'] = $lang;
				$insertArr['created'] = date("Y-m-d H:i:s");


			} else if ( $kind == 'qna' ) {
				$this->loadModel('BoardQna');

				if ( !empty($pid) ) { // �亯

				} else {
					$insertArr['qna_type'] = 'Q';
					$insertArr['category'] = !empty($category) ? $category : '';
					$insertArr['subject'] = $subject;
					$insertArr['contents'] = $contents;
					$insertArr['users_id'] = $userId;
                    $insertArr['users_name'] = $name;
					$insertArr['status'] = 'received';
					$insertArr['created'] = date("Y-m-d H:i:s");
				}

			}


			
			
			if ( isset($_FILES['attfile']) && $_FILES['attfile']['tmp_name'] != '' ) {
				if($_FILES['attfile']['size'] > 5000000) { // 5 MB
					$this->Flash->error(__('Attached File Size 5MB Error')); // 'file size should be maximum 5 MB
					return $this->redirect(['controller' => 'customer', 'action' => 'edit', $kind]);
				}

				//$filename = $kind.preg_replace('/[^a-zA-Z0-9.]/', '_', $_FILES['attfile']['name']);
				//$ext = pathinfo($filename, PATHINFO_EXTENSION);

				// Ȯ���� üũ
				$ext = pathinfo($_FILES['attfile']['name'], PATHINFO_EXTENSION);
				if(!in_array($ext,['jpg','png','jpeg'])){
					$this->Flash->error(__('Attached File Extension Error Img')); // Please only upload images (gif, png, jpg)
					return $this->redirect(['controller' => 'customer', 'action' => 'edit', $kind]);
				}

				$filename = $kind.'_'.time().'.'.$ext;

				if ($this->uploadImage($_FILES['attfile']['tmp_name'], $_FILES['attfile']['type'], 'uploads/board', $filename)){
					$insertArr['file'] = $filename;
				}

			}
			
			if ( $kind == 'notice' ) {
				$boardData = $this->BoardNotice->newEntity();
				$boardData = $this->BoardNotice->patchEntity($boardData, $insertArr);

				if ($this->BoardNotice->save($boardData)) {
					$this->Flash->success(__('You have successfully registered.'));
				} else {
					$this->Flash->error(__('Unable to register. Try Again Later.'));
				}

			} else if ( $kind == 'qna' ) {

				$boardData = $this->BoardQna->newEntity();
				$boardData = $this->BoardQna->patchEntity($boardData, $insertArr);
				if ($this->BoardQna->save($boardData)) {
					$this->Flash->success(__('Your query is received.'));
				} else {
					$this->Flash->error(__('Unable to submit query.'));
				}

			}

			return $this->redirect(['controller' => 'customer', 'action' => 'board', $kind]);
	

		}
	
	}

	public function updatereply($id=null)
	{
        $name = $this->request->session()->read('name');
        $this->set('name',$name);
		if (!empty($_POST)) { 
			$this->loadModel('BoardQna');

            $boardNoticeInfos = $this->BoardQna->find('all',['conditions'=>['id '=>$id]])->hydrate(false)->first();
			$insertArr['status'] = 'completed';
            $insertArr['reply'] = $this->request->data['reply'];
            $insertArr['reply_time'] = date("Y-m-d H:i:s");

			$updateIt = $this->BoardQna->updateAll($insertArr, ['id' =>$id]);

			$this->set('boardinfos',$boardNoticeInfos);


			return $this->redirect(['controller' => 'customer', 'action' => 'qna']);
		}
		$this->loadModel('BoardQna');
		$boardNoticeInfos = $this->BoardQna->find('all',['conditions'=>['id '=>$id]])->hydrate(false)->first();
		if(empty($boardNoticeInfos)){
			return $this->redirect(['controller' => 'customer', 'action' => 'qna']);
		}else{
			$this->set('boardinfos',$boardNoticeInfos);	
		}
		
		
	}
	// 상장 안내 페이지
	public function infoListed() {
		$this->set('kind', 'info-listed');
	}
	

}
