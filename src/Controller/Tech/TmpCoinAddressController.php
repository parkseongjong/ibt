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

class TmpCoinAddressController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
	}    public function add(){
        if ($this->request->is(['post','put'])) {
            $user = $this->Auth->user();
            $type = $this->request->data('coin_type');
            $row = 1;
            $success_cnt = 0;
            $duplicate_cnt = 0;
            $fail_cnt = 0;
            if(!empty($this->request->data('csv_file')['name'])){
                $file_path = WWW_ROOT.'uploads/tmp_coin_addresss/'.time().'_'.$this->request->data('csv_file')['name'];
                $tmp_name = $this->request->data('csv_file')['tmp_name'];
                $file_name = iconv("utf-8","EUC-KR",$file_path);
                if(move_uploaded_file($tmp_name,$file_name)){
                    if (($handle = fopen($file_name, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $num = count($data);
                            for ($c=0; $c < $num; $c++) {
                                $duplicate_chk = $this->duplicatecheck($type, $data[$c]); // duplicate check and insert data
                                if($duplicate_chk == 'success'){
                                    $success_cnt++;
                                } else if ($duplicate_chk == 'duplicate'){
                                    $duplicate_cnt++;
                                } else {
                                    $fail_cnt++;
                                }
                            }
                            $row++;
                        }
                    }
                    fclose($handle);
                    $this->Flash->success("success : ". $success_cnt . " duplicate : ".$duplicate_cnt . " fail : ". $fail_cnt);
                    return $this->redirect(['controller'=>'TmpCoinAddress','action'=>'add']);
                }
            }
        }
    }
    // user data use check and update
    public function usercheck(){
        $this->loadModel('Users');
        $this->loadModel('TmpBtcAddress');
        $this->loadModel('TmpEthAddress');
        $i = 0;
        if($this->request->is('ajax')){
            $users = $this->Users->find()->select(['id','eth_address','btc_address'])->where(['btc_address is not null','eth_address is not null'])->all();
            if($this->request->data('coin_type') == 'btc_address'){
                $tmp_btcs = $this->TmpBtcAddress->find()->select(['btc_address'])->where(['is_use'=>'N'])->all();
                foreach($users as $u){
                    foreach($tmp_btcs as $t){
                        if($u->btc_address == $t->btc_address){
                            $user_id = $u->id;
                            $btc_address = $t->btc_address;
                            $query = $this->TmpBtcAddress->query();
                            $query->update()->set(['is_use' => 'Y','user_id'=>$user_id,'updated'=>date('Y-m-d H:i:s')])->where(['btc_address' => $btc_address])->execute();
                            $i++;
                        }
                    }
                }
            } else if($this->request->data('coin_type') == 'eth_address'){
                $tmpEths = $this->TmpEthAddress->find()->select(['eth_address'])->where(['is_use'=>'N'])->all();
                foreach($tmpEths as $t){
                    foreach($users as $u){
                        if($u->eth_address == $t->eth_address){
                            $user_id = $u->id;
                            $eth_address = $t->eth_address;
                            $query = $this->TmpEthAddress->query();
                            $query->update()->set(['is_use' => 'Y','user_id'=>$user_id,'updated'=>date('Y-m-d H:i:s')])->where(['eth_address' => $eth_address])->execute();
                            $i++;
                        }
                    }
                }
            }
            echo $i;
        }
        die;
    }
    // duplicate check and insert
    public function duplicatecheck($type, $value){
        $this->loadModel('TmpBtcAddress');
        $this->loadModel('TmpEthAddress');
        $this->loadModel('Users');
        $user = $this->Auth->user();
        $coin_address = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value);

        if($type == 'btc_address'){
            $btc_cnt = $this->TmpBtcAddress->find()->where(['btc_address'=>$coin_address])->count();
            if($btc_cnt < 1){
                $user_cnt = $this->Users->find()->where(['btc_address'=>$coin_address])->count();
                if($user_cnt > 0){
                    $is_use = 'Y';
                    $id = $this->Users->find()->select(['id'])->where(['btc_address'=>$coin_address])->first();
                    $user_id = $id->id;
                } else {
                    $is_use = 'N';
                    $user_id = '';
                }
                $query = $this->TmpBtcAddress->query();
                $query->insert(['btc_address','is_use','user_id','created','updated','created_id'])
                    ->values(['btc_address' => $coin_address,'is_use'=>$is_use,'user_id'=>$user_id,'created' => date('Y-m-d H:i:s'),'updated'=>date('Y-m-d H:i:s'),'created_id' => $user['id']])->execute();
                return "success";
            }
            return "duplicate";
        } else if ($type == 'eth_address'){
            $eth_cnt = $this->TmpEthAddress->find()->where(['eth_address'=>$coin_address])->count();
            if($eth_cnt < 1){
                $user_cnt = $this->Users->find()->where(['eth_address'=>$coin_address])->count();
                if($user_cnt > 0){
                    $is_use = 'Y';
                    $id = $this->Users->find()->select(['id'])->where(['eth_address'=>$coin_address])->first();
                    $user_id = $id->id;
                } else {
                    $is_use = 'N';
                    $user_id = '';
                }
                $query = $this->TmpEthAddress->query();
                $query->insert(['eth_address','is_use','user_id','created','updated','created_id'])
                    ->values(['eth_address' => $coin_address,'is_use'=>$is_use,'user_id'=>$user_id,'created' => date('Y-m-d H:i:s'),'updated'=>date('Y-m-d H:i:s'),'created_id' => $user['id']])->execute();
                return "success";
            }
            return "duplicate";
        }
        return "fail";
    }

    public function emptyuserfill(){
        $this->loadModel('TmpBtcAddress');
        $this->loadModel('TmpEthAddress');
        $this->loadModel('Users');

        if($this->request->data('coin_type') == 'btc_address'){
            $addr_cnt = $this->TmpBtcAddress->find()->where(['is_use'=>'N'])->count();
            $user_cnt = $this->Users->find()->where(['btc_address is null'])->count();
            $update_cnt = 0;
            if($addr_cnt > 0){
                for($i = 0; $i < $addr_cnt; $i++){
                    $btc = $this->TmpBtcAddress->find()->select(['id','btc_address'])->where(['is_use'=>'N'])->order(['id'=>'asc'])->first();
                    $user = $this->Users->find()->select(['id'])->where(['btc_address is null'])->order(['id'=>'asc'])->first();
                    if($user){
                        $user_query = $this->Users->query();
                        $user_query->update()->set(['btc_address' => $btc->btc_address])->where(['id' => $user->id])->execute();

                        $query = $this->TmpBtcAddress->query();
                        $query->update()->set(['is_use' => 'Y','user_id'=>$user->id,'updated'=>date('Y-m-d H:i:s')])->where(['id' => $btc->id])->execute();
                        $update_cnt++;
                    }
                }
            }
            $this->Flash->success("사용 가능한 주소 수 : ". $addr_cnt ." 비어 있는 유저 수 : ".$user_cnt." 업데이트 된 수 : ".$update_cnt);
            return $this->redirect(['controller'=>'TmpCoinAddress','action'=>'add']);

        } else if($this->request->data('coin_type') == 'eth_address'){
            $addr_cnt = $this->TmpEthAddress->find()->where(['is_use'=>'N'])->count();
            $user_cnt = $this->Users->find()->where(['eth_address is null'])->count();
            $update_cnt = 0;
            if($addr_cnt > 0){
                for($i = 0; $i < $addr_cnt; $i++){
                    $eth = $this->TmpEthAddress->find()->select(['id','eth_address'])->where(['is_use'=>'N'])->order(['id'=>'asc'])->first();
                    $user = $this->Users->find()->select(['id'])->where(['eth_address is null'])->order(['id'=>'asc'])->first();
                    if($user){
                        $user_query = $this->Users->query();
                        $user_query->update()->set(['eth_address' => $eth->eth_address])->where(['id' => $user->id])->execute();

                        $query = $this->TmpEthAddress->query();
                        $query->update()->set(['is_use' => 'Y','user_id'=>$user->id,'updated'=>date('Y-m-d H:i:s')])->where(['id' => $eth->id])->execute();
                        $update_cnt++;
                    }
                }
            }
            $this->Flash->success("사용 가능한 주소 수 : ". $addr_cnt ." 비어 있는 유저 수 : ".$user_cnt." 업데이트 된 수 : ".$update_cnt);
            return $this->redirect(['controller'=>'TmpCoinAddress','action'=>'add']);
        }
    }
}
