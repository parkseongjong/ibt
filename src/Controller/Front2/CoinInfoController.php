<?php
namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\Mailer\Email;

use Cake\Event\Event;

	class CoinInfoController extends AppController
  {
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['index']);
    }
    public function index() {

    }
  }

?>