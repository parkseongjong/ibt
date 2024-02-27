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
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Log\Log;
use Psy\Shell as PsyShell;
use Cake\Mailer\Email;

/**
 * Simple console wrapper around Psy\Shell.
 */
class UserTxnShell extends Shell
{

    /**
     * Start the shell and interactive console.
     *
     * @return int|null
     */
    public function main()
    {
		
		$this->loadModel('Users');
		$this->loadModel('Settings');
		ini_set('memory_limit','5000M');
		ini_set('max_execution_time', 1900);
		// Export
		$filename = time().'export.csv';
		
		 $file = fopen(WWW_ROOT."uploads/".$filename,"w");
                 $headers = array('#','Username','Email','ETH','ETH RESERVE','RAM','RAM RESERVE','ADMC','ADMC RESERVE','USD','USD RESERVE');
                fputcsv($file,$headers);
                $users =  $this->Users->find('all',[
                    'fields'=>['id','username','email'],
					'contain'=>['ethtransactions'=>['fields'=>['ethtransactions.coin_amount','user_id','remark']],
								'ramtransactions'=>['fields'=>['ramtransactions.coin_amount','user_id','remark']],
								'admctransactions'=>['fields'=>['admctransactions.coin_amount','user_id','remark']],
								'usdtransactions'=>['fields'=>['usdtransactions.coin_amount','user_id','remark']],
								'eth_reserve',
								'admc_reserve',
								'ram_reserve',
								'usd_reserve'
								
								],
					//'conditions' => ['Users.user_type'=>'U','Users.enabled'=>'Y'],
					'conditions' => ['Users.user_type'=>'U'],
					'order'=>['id'=>'asc']

                ])->hydrate(false)->toArray();
				

                 $k = 1;
                foreach ($users as $k=>$data)
                {
					 
					$ethTotal = 0;
					$ethReserve = 0;
					if(!empty($data['ethtransactions'])){
						foreach($data['ethtransactions'] as $ethTrans){
							if(!empty($ethTrans['coin_amount'])){
								$ethTotal = $ethTotal + $ethTrans['coin_amount'];
								
							}
						}
					}
					
					
					if(!empty($data['eth_reserve'])){
						foreach($data['eth_reserve'] as $ethSpend){
							if(!empty($ethSpend['total_buy_spend_amount'])){
								$ethReserve = $ethReserve + ($ethSpend['buy_get_amount']*$ethSpend['per_price']);
								//$ethReserve = $ethReserve + $ethSpend['total_buy_spend_amount'];
							}
						}
					}
					
					
					$ramTotal = 0;
					$ramReserve = 0;
					if(!empty($data['ramtransactions'])){
						foreach($data['ramtransactions'] as $ramTrans){
							if(!empty($ramTrans['coin_amount'])){
								$ramTotal = $ramTotal + $ramTrans['coin_amount'];
								
							}
						}
					}
					
					if(!empty($data['ram_reserve'])){
						foreach($data['ram_reserve'] as $ramSpend){
							if(!empty($ramSpend['total_sell_spend_amount'])){
								//$ramReserve = $ramReserve + $ramSpend['sell_spend_amount'];
								$ramReserve = $ramReserve + $ramSpend['total_sell_spend_amount'];
							}
						}
					}
					
					
					$admcTotal = 0;
					$admcReserve = 0;
					if(!empty($data['admctransactions'])){
						foreach($data['admctransactions'] as $admcTrans){
							if(!empty($admcTrans['coin_amount'])){
								$admcTotal = $admcTotal + $admcTrans['coin_amount'];
								
							}
						}
					}
					
					if(!empty($data['admc_reserve'])){
						foreach($data['admc_reserve'] as $admcSpend){
							if(!empty($admcSpend['total_sell_spend_amount'])){
								//$admcReserve = $admcReserve + $admcSpend['total_sell_spend_amount'];
								$admcReserve = $admcReserve + $admcSpend['sell_spend_amount'];
							}
						}
					}
					
					$usdTotal = 0;
					$usdReserve = 0;
					if(!empty($data['usdtransactions'])){
						foreach($data['usdtransactions'] as $usdTrans){
							if(!empty($usdTrans['coin_amount'])){
								$usdTotal = $usdTotal + $usdTrans['coin_amount'];
								
							}
						}
					}
					
					if(!empty($data['usd_reserve'])){
						foreach($data['usd_reserve'] as $usdSpend){
							if(!empty($usdSpend['total_sell_spend_amount'])){
								//$admcReserve = $admcReserve + $admcSpend['total_sell_spend_amount'];
								$usdReserve = $usdReserve + $usdSpend['sell_spend_amount'];
							}
						}
					}
					
                    $arr = [];
                    $arr['#'] = $k;
                    $arr['Username'] = $data['username'];
                    $arr['Email'] = $data['email'];
                
					
					$arr['ETH'] = number_format((float)$ethTotal,8);
                    $arr['ETH RESERVE'] = number_format((float)abs($ethReserve),8);
                    $arr['RAM'] = number_format((float)$ramTotal,8);
                    $arr['RAM RESERVE'] = number_format((float)abs($ramReserve),8);
                    $arr['ADMC'] =  number_format((float)$admcTotal,8);
                    $arr['ADMC RESERVE'] = number_format((float)abs($admcReserve),8);
					$arr['USD'] =  number_format((float)$usdTotal,8);
                    $arr['USD RESERVE'] = number_format((float)abs($usdReserve),8);
                    fputcsv($file,$arr);
                    $k++;
                }
                fclose($file);
				$myData = [];
				$myData['downloadLink'] = "https://livecrypto.exchange/uploads/".$filename;
				 try {
				$email = new Email('default');
				$email->viewVars(['data'=>$myData]);
				$email->from(["info@massconnects.com"])
						->to(["Pijush.sarkar@outlook.com","Wick9098@gmail.com","info@technoloader.com"])
						->subject('User Coin Transaction Report. Date=>'.date('Y-m-d H:i:s'))
						->emailFormat('html')
						->template('usertxn')
						->send();
				 } catch(SocketException $e) {
					print_r($e); die('fail');
				}
		
		
		/* $this->response->file("uploads/".$filename, array(
			'download' => true,
			'name' => 'UserReport'.$filename
		));
		return $this->response; */
		die;

        
    }

    /**
     * Display help for this console.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = new ConsoleOptionParser('console');
        $parser->description(
            'This shell provides a REPL that you can use to interact ' .
            'with your application in an interactive fashion. You can use ' .
            'it to run adhoc queries with your models, or experiment ' .
            'and explore the features of CakePHP and your application.' .
            "\n\n" .
            'You will need to have psysh installed for this Shell to work.'
        );
        return $parser;
    }
}
