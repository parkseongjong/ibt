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
// src/Controller/UsersController.php

namespace App\Controller\Admin;


use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;

class WalletController extends AppController
{
    public function wallet()
    {

    }
    public function deposit()
    {

    }
    public function transaction()
    {

    }
    public function forbidden(){
        if($this->request->referer()!='/') $this->request->session()->write('Config.referer', $this->request->referer());
        $this->set('title' , 'GalaxyIco!: Access forbidden');

    }
    public function dashboard(){
        $this->set('title' , 'GalaxyIco!: Dashboard');

        $this->loadModel('Users');
        $this->set('totalUsers',$this->Users->find('all' ,['conditions'=>[ 'is_deleted'=>'N' ]])->count());
    }

    public function add()
    {
        $this->set('title' , 'GalaxyIco!: Add Cms Page');
        $Pages = $this->Pages->newEntity();

        if ($this->request->is(['post' ,'put'])) {

            $CardTypes = $this->Pages->patchEntity($Pages, $this->request->data);

            if ($this->Pages->save($Pages)) {
                $this->Flash->success(__('Cms page has been saved.'));
                return $this->redirect(['controller'=>'Pages','action' => 'add']);
            }else{
                $this->Flash->error(__('Some Errors Occurred.'));
            }
        }
        $this->set('Pages', $Pages);
    }

    public function faq(){

        $this->set('title' , 'GalaxyIco!: FAQ');
        $this->set('cmsDetails',$this->Pages->get(1));

        if ($this->request->is(['post' ,'put'])) {
            $Pages  = $this->Pages->get(1);
            $Pages = $this->Pages->patchEntity($Pages, $this->request->data);

            if ($this->Pages->save($Pages)) {
                $this->Flash->success(__('Faq has been saved.'));
                return $this->redirect(['controller'=>'Pages','action' => 'faq']);
            }else{
                $this->Flash->error(__('Some Errors Occurred.'));
            }
        }

    }
    public function manage(){
        /* pr($this->Pages->get(1)->toArray());
        echo json_encode($this->Pages->get(1)->toArray(), JSON_HEX_QUOT | JSON_HEX_TAG);	die; */
        $this->set('title' , 'GalaxyIco!: Cms Pages');
        $this->set('Pages', $this->Pages->newEntity($this->request->data));
        $this->set('cmsPages',$this->Pages->find('list',array('keyField'=>'id' , 'valueField'=> 'name'))->toArray());

        if ($this->request->is(['post' ,'put'])) {
            unset($this->request->data['name']);
            $Pages  = $this->Pages->get($this->request->data['id']);
            $Pages = $this->Pages->patchEntity($Pages, $this->request->data);

            if ($this->Pages->save($Pages)) {
                $this->Flash->success(__('Cms page has been saved.'));
                return $this->redirect(['controller'=>'Pages','action' => 'manage']);
            }else{
                $this->Flash->error(__('Some Errors Occurred.'));
            }
        }

    }

    public function search(){
        if ($this->request->is('ajax')) {
            if(isset($this->request->data['cms_id'])){

                $this->set('cmsDetails',$this->Pages->get($this->request->data['cms_id']));
            }
        }
    }


    public function chart()
    {

        if ($this->request->is('ajax')) {
            if(isset($this->request->data['mode'])){

                $to_date = date('Y-m-d', strtotime($this->request->data['to']));
                $from_date = date('Y-m-d', strtotime($this->request->data['from']));
                $this->loadModel('Users');
                $query = $this->Users->find();
                $charts = $ven = $usr = $tv =   array();
                $users = 	$query->select([
                    'count' => $query->func()->count('id'),
                    'published_date' => 'DATE(created)'
                ])
                    ->where(['access_level_id' => 2,'is_deleted' => 'N','DATE(created) >=' => $to_date,'DATE(created) <=' =>  $from_date,/* function ($exp, $q) {

									return $exp->between('created', date('Y-m-d H:i:s', strtotime($this->request->data['to'])), date('Y-m-d H:i:s', strtotime($this->request->data['from'])));
								} */])
                    ->group('published_date')->hydrate(false)->toArray();
                //pr($users);die;
                if($users){
                    foreach($users as $value){
                        $a = array();
                        $a[0]  = strtotime($value['published_date'])*1000;
                        $a[1]  = $value['count'];

                        $usr[] = $a;
                    }
                }

                $charts['Users'] = $usr;


                echo json_encode($charts);die;
            }
        }
    }


}
