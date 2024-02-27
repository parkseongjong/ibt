<?php

namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

use Cake\Event\Event;

class HowtouseController extends AppController
{
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);

		$this->Auth->allow(['normaluser']);
		/*
		$lang = isset($_COOKIE['Language']) ? $_COOKIE['Language'] : 'en_US';
		I18n::locale($lang);
		*/
	}

	public function index()
	{
	}

	public function normaluser()
	{
		$this->set('page_title', 'How To Use');
	}

	public function precautions($type=1) {
		$this->set('kind', 'precautions'.$type);

		$this->viewBuilder()->template('precautions'.$type);
	}
}