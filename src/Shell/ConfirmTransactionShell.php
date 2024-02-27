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


class ConfirmTransactionShell extends Shell
{
    public function main()
    {
		//$conn = ConnectionManager::get('default');
		//$this->Settings = TableRegistry::get('Settings');
		$this->loadModel('Transactions');
		$this->loadModel('PrimaryWallet');
		$this->loadModel('Users');


		
		$getTransactions = $this->PrimaryWallet->find("all",['conditions'=>['PrimaryWallet.status'=>'pending','PrimaryWallet.cryptocoin_id'=>1,'Transactions.type'=>'deposit']])->hydrate(false)->toArray();
	
		if(!empty($getTransactions)){
			foreach($getTransactions as $singleTransactions){
				$txId = $singleTransactions['tx_id'];
				$userId = $singleTransactions['user_id'];
				$amount = $singleTransactions['amount'];
				$btcAddress = $singleTransactions['wallet_address'];
				if(empty($singleTransactions['tx_id']) || empty($singleTransactions['wallet_address'])){
					continue;
				}
				$id = $singleTransactions['id'];

				$getDetails = $this->Users->getBtcTxDetailFronNode($txId);
				$getDetailsDecode = json_decode($getDetails,true);
				// checking user confirmations
				$getConfirmations = $getDetailsDecode['result']['confirmations'];
				if($getConfirmations<6){  
					continue;
				} 
				// update status in transaction
				$this->Transactions->updateAll(['status'=>'completed'],['id'=>$id]);
				
			}
		}
		die;
	}
}

?>