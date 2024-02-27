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
namespace App\Controller;  

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */


class A1Controller extends AppController
{ 
	public function beforeFilter(Event $event)
    {
		 parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['home','content','returndata','subscription','contact','changebtcstatus','comparebtc','updatewithrawalstatus','getcurrentprice','ethwithdrawalstatus','coincallback','btccallback','support','disablewithrawalstatus','listing','validateuser','depositram','getcurrentpriceusd','updatewithrawalstatususd','updatewithrawalstatuseth','depositrealram','depositeth','getallcurrentprice','validatetokens','depositethtokens','checkvalidatetokens','updatevalidatetokens','ethvalidatetokens','wccethdeposit','wccvalidateuser',"getusercurrentbalance",'withdrawalapi','faq','policy','getcurrentpricehome','termsandconditions','crosschainrecovery','amlpolicy','getethaddress','a1','view1','write1','add1','edit1','edit1_ok','del1','del_ok']);
    }
   	public function content($slug = null){
		$this->set('title',':: HedgeConnect ::'.ucwords(str_replace(array('_','-'),' ',$slug)));
		$pageContent = $this->Pages->find('all',[ 'fields'=>['id','title','slug','description'],'conditions'=>['slug' => $slug]])->first();
		if(!empty($pageContent)) $this->set('content',$pageContent);
		else return $this->redirect(['controller'=>'Pages','action' => 'home']);
	}
    public function home()
    { 
    	//$this->viewBuilder()->layout('login');
        $this->viewBuilder()->layout('front2');
		  $result = $this->loadModel('Z1_user');
		$data=$result->find('All');
		$this->set('viewData1',$data);
		$this->set('title','여기가 단품');	
		
    }
public function  del1($id=null){
 

	   $board_model = $this->loadModel('Z1_user');
       $board_del = $board_model->get($id);
		if($board_model->delete($board_del)) {
			//$this->Flash->success(__('삭제성공',h($id)));
		
			return $this->redirect(['action' => 'home']);
		}
	
	
	} //
	
public function  view1($id=null){
         $this->viewBuilder()->layout('front2');
         $board_model = $this->loadModel('Z1_user');
         $board_view = $board_model->get($id);
	      $this->set('board',$board_view);
	} //
	
	public function write1(){
		$this->viewBuilder()->layout('front2');
	}
	public function edit1($id = null){

		if (!empty($_POST)) { 
	     
			$board_model = $this->loadModel('Z1_user');
			 $idx = $_POST["idx"];
			$uid= $_POST["uid"];
			$uname= $_POST["uname"];
			$upass= $_POST["upass"];
			$umod= $_POST["umod"];
			 			// $created=now();
		 	$data1 = [];
			//echo $uid."<br>".$uname."<br>".$upass."<Br>".$umod; 
			 
			   $data1['upass'] = $upass; 
			   $data1['uid'] = $uid;
            $data1['uname'] = $uname;
			   $data1['created'] = date("Y-m-d H:i:s");
            $data1['umode']='1';
			 echo var_dump($data1)."<Br>".$idx;
            
   //exit();
			$updateIt = $board_model->updateAll($data1, ['idx' =>$idx]);
        
			


			return $this->redirect(['controller' => 'A1', 'action' => 'home']);
		}
	
		
		$this->viewBuilder()->layout('front2');
		  $board_model = $this->loadModel('Z1_user');
        $board1 = $board_model->get($id);
	   // $board1 = $this->Z1_user->get($id);
		$this->set(compact('board1'));   
		
		 //$this->set('board',$board_view);
	}
	public function add1(){
		 if (isset($_POST["upass"])) {
        //$board_model = $this->loadModel('Z1_user');
       // $board_model->addBoard($_POST["title"], $_POST["content"],  $_POST["writer"]);
			 $board_model = $this->loadModel('Z1_user');
			 
			$uid= $_POST["uid"];
			$uname= $_POST["uname"];
			$upass= $_POST["upass"];
			$umod= $_POST["umod"];
			 			// $created=now();
		 	$data1 = [];
			echo $uid."<br>".$uname."<br>".$upass."<Br>".$umod; 
			 
			   $data1['upass'] = $upass; 
			   $data1['uid'] = $uid;
            $data1['uname'] = $uname;
			   $data1['created'] = date("Y-m-d H:i:s");
            $data1['umode']='1';
			 echo var_dump($data);
            //$user = $this->Users->patchEntity($user, $this->request->data);
			 
			 
			 	$boardData = $this->Z1_user->newEntity();
				$boardData = $this->Z1_user->patchEntity($boardData, $data1);

				if ($this->Z1_user->save($boardData)) {
					//$this->Flash->success(__('You have successfully registered.'));
						return $this->redirect(['controller' => 'A1', 'action' => 'home' ]);
				} else {
					//$this->Flash->error(__('Unable to register. Try Again Later.'));
				}
		  //   	 $boardData = $this->board_model->newEntity();
			//	$boardData = $this->BoardNotice->patchEntity($boardData, $insertArr);

			 
    }

	}
	
    public function home2($ref_code=null)
    { 
		$this->set('title','Home');
    }

	
}
