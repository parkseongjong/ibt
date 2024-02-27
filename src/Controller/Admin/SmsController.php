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
use Cake\Mailer\Email;

class SmsController extends AppController
{

    public function index()
    {
        $this->set('title','Send SMS');

        $users = $this->Users->find('list',['conditions'=>['id !='=>1],'keyField' =>'email','valueField' => 'name'])->hydrate(false)->toArray();
        if ($this->request->is(['post' ,'put']))
        {
            $data = $this->request->data;
            if(!empty($data['email']))
            {
                    if(SENDMAIL == 1)
                    {
                        $user['msg'] = $data['sms'];
                        $user['subject'] = $data['subject'];
                        $email = new Email('default');
                        $email->viewVars(['data'=>$user]);
                        $email->from([$this->setting['email_from']] )
                            ->to($data['email'])
                            ->subject($data['subject'])
                            ->emailFormat('html')
                            ->template('sms')
                            ->send();
                    }
                $this->Flash->success('Messages are sent.');
            }
            else
            {
                $this->Flash->error('No user selected.');
            }

        }
        $this->set('users',$users);
    }
}
