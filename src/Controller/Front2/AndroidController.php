<?php
namespace App\Controller\Front2;
use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class AndroidController extends AppController
{

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['authenticate']);
    }

    public function index() {
    }

    public function authenticate(){
        $this->loadModel('Users');
        $APP_PUBLIC_SECRET_KEY = 'EB:62:B6:4A:EE:C3:E9:6D:16:11:EA:10:45:93:42:FC:DC:9C:0F:54:42:5D:6A:D8:7D:DA:33:42:5C:7D:3E:E0';
        $APP_ALIAS = "CyberTronChain";
        $APP_ALIAS_PW = "ctc1234";
        $signature = hash_hmac('sha256', $APP_PUBLIC_SECRET_KEY . $APP_ALIAS,$APP_ALIAS_PW);
        $rawData = file_get_contents("php://input");
        file_put_contents("auth.txt",$rawData."\r\n");
        // write post data to file

        if(!empty($rawData)){
            if($rawData == $signature){
                echo 'OK';
            } else {
                echo 'ERROR';
            }
        } else {
            echo 'Empty Object';
        }
        die;
    }

}

