<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CoinpairTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
			
		$this->belongsTo('cryptocoin_first', [
            'className' => 'Cryptocoin',
			'foreignKey' => 'coin_first_id'
        ]);	
		
		$this->belongsTo('cryptocoin_second', [
            'className' => 'Cryptocoin',
			'foreignKey' => 'coin_second_id'
        ]);	
	}
	
	

	

}
?>
