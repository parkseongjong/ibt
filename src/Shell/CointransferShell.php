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


class CointransferShell extends Shell
{
    public function main()
    {
        $conn = ConnectionManager::get('default');
		$this->Users = TableRegistry::get('Users');
		$this->Settings = TableRegistry::get('Settings');
		$this->Cointransfer = TableRegistry::get('Cointransfer');
		$this->Cointransactions = TableRegistry::get('Cointransactions');
		
		
		$offset = 0; 
		$limit = 500;
		$offsetId = 15;
		
		$QueryTotal = $this->Cointransactions->find('all');
		$getAllUserCoinTotal = $QueryTotal->select([ 
					  'user_id',
					  'coin_sum' => $QueryTotal->func()->sum('coin'),
					  'user.token_wallet_address'
					])
					
			 ->where(['status' => 1,
					  'user_id !='=>1,	
					  'user.token_wallet_address !='=>''])
			 ->contain(['user'])		  
			 ->group('user_id')
			 ->all();
		
		$i=0;
		
		
		/* if(!empty($getAllUserCoinTotal)){
			
			foreach($getAllUserCoinTotal as $singleUserTotal){
				
				$userIdTotal = $singleUserTotal['user_id'];
				$coinSumTotal = $singleUserTotal['coin_sum'];
				
				$getUserTransferredHcTotal = $this->Users->getUserTransferredHc($userIdTotal);
				$coinToTransferTotal = $coinSumTotal -$getUserTransferredHcTotal; 
				
				if($coinToTransferTotal>0) {
					//echo "\n";
					//echo $coinToTransferTotal;
					//echo "\n";
					$i++;
					
				}
			}
		}
		//	echo $i; die;
		if($i==0){
			$updateOffset=$this->Settings->get($offsetId);
			$updateOffset=$this->Settings->patchEntity($updateOffset,['value'=>0]);
			$updateData = $this->Settings->save($updateOffset);
			die;
		} */
		
		
	
		
		// get offset 
		$getSettingData = $this->Settings->find('all',['conditions'=>['module_name'=>'coin_transfer_offset']])->hydrate(false)->first();;
		if(!empty($getSettingData)){
			$offset = $getSettingData['value'];
			$offsetId = $getSettingData['id'];
		}
		
		
		
		/* $this->loadModel('Cointransactions');
		$this->loadModel('Cointransfer');
		$this->loadModel('Users'); */
		
		$Query = $this->Cointransactions->find('all');
		$cuDate = '2018-04-03';
		$getAllUserCoin = $Query->select([ 
					  'user_id',
					  'coin_sum' => $Query->func()->sum('coin'),
					  'user.token_wallet_address'
					])
					
			 ->where(['status' => 1,
					  'user_id !='=>1,	
					  /* 'DATE(`created_at`)'=>$cuDate,
					  'type in'=>['lending_interest','lending_interest'], */
					  'user.token_wallet_address !='=>''])
			 ->contain(['user'])		  
			 ->group('user_id')
			 /* ->offset($offset)
			 ->limit($limit) */ 
			 ->all();
		
		//print_r($getAllUserCoin); die;
		
		if(!empty($getAllUserCoin)){
			
			foreach($getAllUserCoin as $singleUser){
				
				$userId = $singleUser['user_id'];
				$coinSum = $singleUser['coin_sum'];
				
				$getUserTransferredHc = $this->Users->getUserTransferredHc($userId);
				$coinToTransfer = $coinSum -$getUserTransferredHc; 
				
				/* if($coinToTransfer>0) {
					
					$password = 'mighty_admin@gmail.com';
					$fromWalletAddress = '0x2c6bc9db73fd67956b187149babc1b1360aae59d';
					$toWalletAddress = $singleUser['user']['token_wallet_address'];
					$coinAmount = $coinToTransfer;
					echo "\n";
					echo $tx_id = $this->Users->transferCoinToAddress($password,$fromWalletAddress,$toWalletAddress,$coinAmount);
					echo "\n";
					sleep(1);
					if(!empty($tx_id)) {
						$cuDate = date('Y-m-d H:i:s');
						$coinTransferArr=[];
						$coinTransferArr['tx_id']        = $tx_id;
						$coinTransferArr['from_user_id'] = 1;
						$coinTransferArr['to_user_id']   = $userId;
						$coinTransferArr['coin_amount']  = $coinAmount;
						$coinTransferArr['status']  = 1;
						$coinTransferArr['created_at']  = $cuDate;
						$coinTransferArr['updated_at']  = $cuDate;
						
						
						$coinTransferOdj = $this->Cointransfer->newEntity();
						$coinTransferOdj = $this->Cointransfer->patchEntity($coinTransferOdj,$coinTransferArr);
						$saveData = $this->Cointransfer->save($coinTransferOdj);
					} 
				} */
			}
			
			
		}	
		
		// update offset
		$newOffsetValue = $offset+$limit;
		/* $updateOffset=$this->Settings->get($offsetId);
		$updateOffset=$this->Settings->patchEntity($updateOffset,['value'=>$newOffsetValue]);
		$updateData = $this->Settings->save($updateOffset); */
		
		
    }
}

?>