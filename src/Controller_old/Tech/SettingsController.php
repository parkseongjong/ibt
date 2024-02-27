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

namespace App\Controller\Tech;


use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

//namespace App\Controller;

//use App\Controller\AppController;
use Cake\Event\Event;

class SettingsController extends AppController
{
	public function search(){
		if ($this->request->is('ajax')) {
				if(isset($this->request->data['cms_id'])){
					$this->loadModel('Pages');
					 $this->set('cmsDetails',$this->Pages->get($this->request->data['cms_id']));
				}
		}
	}
	public function deleteSubject()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Subjects');
			$query = $this->Subjects->query();
			$query->delete()
			->where(['id' => $this->request->data['id']])
			->execute();
			echo 1;
		}
		die; 
	
	}
	public function subject()
	{
		$this->set('title' , 'Galaxyzuo!: subject');
		$this->loadModel('Subjects');
		$Subjects = $this->Subjects->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			
			if($this->request->data['id']=='') $Subjects = $this->Subjects->newEntity();
			else $Subjects = $this->Subjects->get($this->request->data['id']);
			$Subjects = $this->Subjects->patchEntity($Subjects, $this->request->data);
			if ($this->Subjects->save($Subjects)) {
				if($this->request->data['id']=='') $this->Flash->success(__('Subject has been created.'));
				else $this->Flash->success(__('Subject has been updated.'));
				return $this->redirect(['controller'=>'Settings','action' => 'subject']);
			}
		
		}
		$this->set('listing',$this->Subjects->find('all', ['order'=>['id'=>'desc']])->toArray());
		 $this->set('Subjects',$Subjects);
	
	}
	
	public function manage()
	{
		$this->loadModel('Pages');
			
		$this->set('title' , 'Cms Pages');
		$this->set('Pages', $this->Pages->newEntity($this->request->data));
		$this->set('cmsPages',$this->Pages->find('list',array('keyField'=>'id' , 'valueField'=> 'title'))->toArray());
		
		if ($this->request->is(['post' ,'put'])) {
			$Pages  = $this->Pages->get($this->request->data['id']);
			$Pages = $this->Pages->patchEntity($Pages, $this->request->data);
			
			if ($this->Pages->save($Pages)) {
				$this->Flash->success(__('Cms page has been saved.'));
				return $this->redirect(['controller'=>'Settings','action' => 'manage']);
			}else{
				$this->Flash->error(__('Some Errors Occurred.'));
			}
		}
	
	}
    public function index()
    {
        $this->set('title' , 'Galaxyzuo!: Setting');
		$this->loadModel('ConversionRates');
		$conversion = $this->ConversionRates->newEntity();
        if ($this->request->is(['post','put'])) 
		{
			$this->request->data['left_coins'] = $this->request->data['total_coins'];
			$conversion = $this->ConversionRates->newEntity();
			$conversion = $this->ConversionRates->patchEntity($conversion, $this->request->data); 
			if($this->ConversionRates->save($conversion)){
				$this->Flash->success(__('Add successfully.','conversion'));
				return $this->redirect(['controller'=>'Settings','action'=>'index']);
			}else{
				foreach($conversion->errors() as $field_key =>  $error_data)
				{
					foreach($error_data as $error_text)
					{
						$this->Flash->error(__($error_text,'conversion'));
					} 
				}
			}
			
			
		}
		$searchData =array();
        $settings = $this->Settings->find('all')->toArray();
        $this->set('listing',$this->Paginator->paginate($this->ConversionRates, [
			'conditions'=>$searchData,
			'order'=>['ConversionRates.id'=>'desc'],
			'limit' => $this->setting['pagination'],
		]));
      
        $this->set('conversion',$conversion);
        $this->set('settings',$settings);
    }
    
    public function deleteConversion()
	{
		if ($this->request->is('ajax')) { 
			$this->loadModel('Conversions');
			$query = $this->Conversions->query();
			$query->delete()
			->where(['id'=>$this->request->data['id']])
			->execute();
			echo 1;
		}
		die;
		
	}
	 
    public function forbidden(){
        if($this->request->referer()!='/') $this->request->session()->write('Config.referer', $this->request->referer());
        $this->set('title' , 'GalaxyIco!: Access forbidden');

    }

    public function update()
    {
        $error = [];
        if($this->request->is('ajax'))
        {

            $data = $this->request->data;

            foreach ($data as $k => $v)
            {
                $setting = $this->Settings->find('all',['fields'=>['id','type','show_name'],'conditions'=>['module_name'=>$k]])->hydrate(false)->first();
                $sett = $this->Settings->get($setting['id']);
                $set = $this->Settings->patchEntity($sett,array('value'=>$v,'type'=>$setting['type']));
                if($settings = $this->Settings->save($set)) {

                }
                else
                {
                    $error[$setting['id']] = 'Invalid value for '.$setting['show_name'];
                }
            }
            echo json_encode($error);die;
        }

    }
}
