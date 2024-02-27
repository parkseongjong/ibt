<?php 

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;


class ArchieveShell extends Shell
{
    public function main()
    {
        $conn = ConnectionManager::get('default');
		$this->Users = TableRegistry::get('Users');
		$stmt = $conn->execute('SET time_zone = "+5:30";');
		 
		  
		$getUserslist = $this->Users->find('all',array('conditions'=>array('time_to_sec(timediff(NOW(), last_login ))/3600  >'=>3,'btc_address !='=>'','btc_address_status'=>'unarchieve')))->select(['last_login','btc_address','id'])->hydrate(false)->all()->toArray();
		
		//$getUserslist = $this->Users->find('all',array('conditions'=>array('btc_address'=>'38SsgNmvBfscwaaP5bYQ8wDvjwGhPj7ARg','btc_address_status'=>'unarchieve')))->select(['last_login','btc_address','id'])->hydrate(false)->all()->toArray();

		if(!empty($getUserslist)){
			foreach($getUserslist as $singleUser){
				
				if(!empty($singleUser["btc_address"])){
					$userBtcAddress = $singleUser["btc_address"];
					$getAddressBalance = $this->Users->getAddressBalance($userBtcAddress);
					 if(isset($getAddressBalance['data']) && isset($getAddressBalance['data']['available_balance']) && $getAddressBalance['data']['available_balance']=="0.00000000" && $getAddressBalance['data']['pending_received_balance']=="0.00000000") {
						$archieveAddress = $this->Users->archieveAddress($userBtcAddress);
						if($archieveAddress['status'] == "success") {
							$user = $this->Users->get($singleUser['id']);
							$user->btc_address_status = "archieve";
							$this->Users->save($user);
							Log::write('debug', 'Archieve Address => user ID :'.$singleUser["id"].', btc address : '.$userBtcAddress);
						}
					} 
				}
			}
		}
		
		
    }
}

?>