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


class TokenTransactionShell extends Shell
{
    public function main()
    {
		Log::write('debug',  'etherscan api 호출');
		$this->loadModel('Users');	
		$this->loadModel('PrincipalWallet');
		$this->loadModel('Cryptocoin');
		
		
		$contractAddressArr = [];		
		$coinData = $this->Cryptocoin->find('all',['conditions'=>['OR'=>[['contract_address != '=>NULL],['contract_address != '=>""]]]])->hydrate(false)->toArray();
		//$coinData = $this->Cryptocoin->find('all',['conditions'=>['id'=>20]])->hydrate(false)->toArray();
		
		foreach($coinData as $singleCoin){
			$contractAddressArr[$singleCoin['contract_address']] = ['cyptocoin_id'=>$singleCoin['id'],														  'contractAddress'=>$singleCoin['contract_address'],
																	'abi'=>$singleCoin['abi'],
																	'decimal'=>$singleCoin['decimal']
																	];
		}
		
			
		
		$findUser = $this->Users->find('all',['conditions'=>['OR'=>[['eth_address != '=>NULL],['eth_address != '=>""]]],'fields'=>["id","eth_address"]])->hydrate(false)->toArray();
		
		$apiKey = "ehtkey";
		foreach($contractAddressArr as $singleContract){
			sleep(1);
			$newInsertArrMultiple = [];
			
			$cryptocoinId = $singleContract['cyptocoin_id'];
			$contractAddress = $singleContract['contractAddress'];
			$curlUrl = "https://api.etherscan.io/api?module=account&action=tokentx&contractaddress=$contractAddress&page=1&offset=200&sort=desc&apikey=$apiKey";
			
			$curl = curl_init();
			
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $curlUrl,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			));

			$response = curl_exec($curl);

			curl_close($curl);
			
			$receiveWalletArr = [];
			$decodeResp = json_decode($response,true);
			//print_r($decodeResp);
			if(isset($decodeResp['result']) && !empty($decodeResp['result'])){
				foreach($decodeResp['result'] as $singleResult){
					if($singleResult["timeStamp"] > 1615459282) {
						
					
						$getAmt = $singleResult['value']/$singleContract['decimal'];
						$receiveWalletArr[strtolower($singleResult['to'])][] = ['from_address'=>$singleResult['from'],'tx_id'=>$singleResult['hash'],'amount'=>$getAmt];
					}
				}
			}

			//print_r($receiveWalletArr);
			$receiveWalletArrAddress = array_keys($receiveWalletArr);
			
			//print_r($receiveWalletArrAddress); die;
			
			// users check
			foreach($findUser as $singleUser){
				$userWalletLowerAddr = strtolower($singleUser['eth_address']);
				$userWalletAddr = $singleUser['eth_address'];
				if(in_array($userWalletLowerAddr,$receiveWalletArrAddress)){
					
					foreach($receiveWalletArr[$userWalletLowerAddr] as $receiveWalletArrSingle) {
						$txId = $receiveWalletArrSingle['tx_id'];
						
						$findExist = $this->PrincipalWallet->find('all',['conditions'=>['tx_id'=>$txId,'wallet_address'=>$userWalletAddr]])->hydrate(false)->first();
						
						if(empty($findExist)){
							$coinAmount =  $receiveWalletArrSingle['amount'];
							$cudate= date('Y-m-d H:i:s');
							$userId = $singleUser['id']; 
							 
							$newInsertArr = [];
							$newInsertArr['user_id'] = $userId;
							$newInsertArr['cryptocoin_id'] = $cryptocoinId;
							$newInsertArr['wallet_address'] = $userWalletAddr;
							$newInsertArr['type'] = 'purchase';
							
							$newInsertArr['tx_id'] = $txId;
							$newInsertArr['amount'] = abs($coinAmount);
							$newInsertArr['status'] = 'completed';
                            $newInsertArr['remark'] = 'erc20_purchase';
							$newInsertArr['created'] = $cudate;
							$newInsertArr['updated'] = $cudate; 
							//print_r($newInsertArr);
							$newInsertArrMultiple[] = $newInsertArr;
							
							
						}
					}
					
				}
			}
			//print_r($newInsertArrMultiple);
			
			if(!empty($newInsertArrMultiple)) {
				$entities = $this->PrincipalWallet->newEntities($newInsertArrMultiple);
				$result = $this->PrincipalWallet->saveMany($entities);
			}
			
		}
    }
}

?>