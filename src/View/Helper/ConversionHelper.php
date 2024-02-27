<?php 
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

class ConversionHelper extends Helper
{
    public function convert($price)
    {
	
        $amount = 0;
		if($price>=10000000) $amount= round(($price/10000000),1).' Cr';
		else if($price>=100000) $amount= round(($price/100000),1).' Lac';
		else if($price>=1000) $amount= round(($price/1000),1).' K';
		else if($price>=100) $amount= round(($price/100),1).' h';
		else $amount= $price;
		return $amount;
    }
	
	public function getbitInGalaxy(){
		
		$cudate = date('Y-m-d');
		$conn = ConnectionManager::get('default');
		$stmt = $conn->execute('SELECT * FROM conversion_rates where from_date <="'.$cudate.'" and to_date >="'.$cudate.'" order by id desc limit 1');
		$results = $stmt->fetchAll('assoc');
		if(!empty($results)){
			return $results[0]['rate'];
		}
		else { return ''; } 
		
	}
	
	public function getTotalBtc($userId){
		$this->Users = TableRegistry::get("Users");
		return $this->Users->getUserTotalBtc($userId);
		
	}
}
?>
