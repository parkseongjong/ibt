<?php
namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
use Google_Client;
use Google_Service_Plus;
use Google_Service_Oauth2;

class ApiController extends AppController{
	public function beforeFilter(Event $event)
    {
		 $this->Auth->allow(['index','apiinfo','codeinfo','ticker','orderbook','transactionHistory']);
	}

	public function index(){

	}

	public function apiinfo(){

	}

	public function codeinfo(){

	}

	public function ticker(){

	}

	public function orderbook(){

	}

	public function transactionHistory(){

	}
}