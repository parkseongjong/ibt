<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake $code=1 Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Api;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

use Cake\ORM\TableRegistry;
use Cake\Event\Event;


/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
	public function beforeFilter(Event $event)
    {
		 $this->Auth->allow();
	}
		
	public function banners()
	{
		$response = array();
		$this->loadModel('Categories');
		$adult = $this->Categories->find()->select(['adults'])->limit(1)->order(['adults'=>'desc'])->hydrate(false)->first();
		$kid = $this->Categories->find()->select(['child'])->limit(1)->order(['child'=>'desc'])->hydrate(false)->first();
				
		$this->loadModel('Banners');	
		$data = $this->Banners->find()->select(['file'])->order(['priority' =>'asc'])->hydrate(false)->toArray();
		foreach($data as $k=>$val){
			$response[$k] = BASEURL."uploads/gallery/".$val['file'];
		}
		$this->set(array('adults'=>$adult['adults'],'kids'=>$kid['child'],'response'=>$response,'code'=>0,'error'=>false,'message'=>'','_serialize'=>array('code','error','message','response','adults','kids')));
	}
	
	
	public function cms()
	{
		if($this->request->is(['post','put']))
    	{
			$error =true;$code=1;
			$message= $response = ''; 
			if(isset($this->request->data['slug']))
			{
				
				$slug = $this->request->data['slug'];
				$content = $this->Pages->find()->select(['title','description'])->where(['slug' =>$slug])->hydrate(false)->first();
				if(!empty($content)){
					$error = false;
					$code = 0;
					$response['title'] = $content['title'];
					$response['description'] = $content['description'];
				} 
				else $message =  'Not found';
			}
			else $message =  'Incomplete data';
			if($error == true) $this->set(array('code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message')));
			else $this->set(array('response'=>$response,'code'=>$code,'error'=>$error,'message'=> $message,'_serialize'=>array('code','error','message','response')));
		}
		
	}
	
	
}

	
