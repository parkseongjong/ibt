<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class InvestmentTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
		
        $this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'user_id'
        ]);
		
		$this->hasOne('cointransactions', [
            'className' => 'Cointransactions',
			'foreignKey' => 'investment_id'
        ]);
        
		
    }
   


}
?>
