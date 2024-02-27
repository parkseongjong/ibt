<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class ExchangeTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
		/* $this->belongsTo('sendby_user', [
            'className' => 'Users',
			'foreignKey' => 'sendby_user_id'
        ]); */
		
		$this->belongsTo('seller', [
            'className' => 'Users',
			'foreignKey' => 'seller_user_id'
        ]);
        
		$this->belongsTo('buyer', [
            'className' => 'Users',
			'foreignKey' => 'buyer_user_id'
        ]); 
	}
	
	public function validationDefault(Validator $validator)
    {
		$validator
			->notEmpty('title', 'Title Missing')
			->notEmpty('units', 'Mention Unit');
			;
		$validator->add('units', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Value should be numeric'
		]);
		return $validator;
	}
	

}
?>
