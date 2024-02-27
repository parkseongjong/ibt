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

namespace App\Controller\Front;


use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;
use Cake\View\Helper\Utility;



class LoginLogsController extends AppController
{
    public function index()
    {
        $this->loadModel('LoginLogs');
        $this->set('title' , 'Login Logs');
        $searchData = array();
        $limit =  $this->setting['pagination'];
        $searchData['AND'][] =['user.enabled'=>'Y','user_id'=>$this->Auth->user('id')];
        if ($this->request->is(['post' ,'put']) )
        {
            if(array_key_exists('key',$this->request->data)) parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;

            if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(LoginLogs.created) >= ' => $this->request->data['start_date'],'DATE(LoginLogs.created) <= ' => $this->request->data['end_date']);
            else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(LoginLogs.created)' => $search['start_date']);
            else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(LoginLogs.created)' => $search['end_date']);

        }
        $this->set('logs', $this->Paginator->paginate($this->LoginLogs, [
            'fields'=>['user.name','ip_address','LoginLogs.created'],
            'contain'=>['user'],
            'conditions' => $searchData,
            'limit' => $limit,
            'order'=>['LoginLogs.id'=>'desc']

        ]));
    }
    public function logSearch()
    {
        if ($this->request->is('ajax')) {
            $searchData = array();
            $limit =  $this->setting['pagination'];
            $searchData['AND'][] =['user.enabled'=>'Y','user_id'=>$this->Auth->user('id')];
            parse_str($this->request->data['key'], $this->request->data);
            $search = $this->request->data;
            if(!empty($search))
            {
                //if($search['pagination'] != '') $limit =  $search['pagination'];

                if($search['start_date'] != '' && $search['end_date'] != '') $searchData['AND'][] = array('DATE(LoginLogs.created) >= ' => $this->request->data['start_date'],'DATE(LoginLogs.created) <= ' => $this->request->data['end_date']);
                else if($search['start_date'] != '')	$searchData['AND'][] = array('DATE(LoginLogs.created)' => $search['start_date']);
                else if($search['end_date'] != '')	$searchData['AND'][] = array('DATE(LoginLogs.created)' => $search['end_date']);
            }
            if($this->request->query('page')) {
                $this->set('serial_num',(($this->setting['pagination'])*($this->request->query('page'))) - ($this->setting['pagination'] -1));
            }
            else {$this->set('serial_num',1);}
            $this->set('logs', $this->Paginator->paginate($this->LoginLogs, [
                'fields'=>['user.name','ip_address','LoginLogs.created'],
                'contain'=>['user'],
                'conditions' => $searchData,
                'limit' => $limit,
                'order'=>['LoginLogs.id'=>'desc']

            ]));


        }
    }

}
