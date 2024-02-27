<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class CointransactionsTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
		$this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'user_id'
        ]);
		
		$this->belongsTo('exchange', [
            'className' => 'Exchange',
			'foreignKey' => 'exchange_id'
        ]);
		
		$this->hasOne('agctransactions', [
            'className' => 'Agctransactions',
			'foreignKey' => 'cointransactions_id'
        ]);
		
	}
	
	/* public function validationDefault(Validator $validator)
    {
		$validator
			->notEmpty('total_token', 'Missing Total Token')
			->notEmpty('sold_token', 'Missing Sold Token')
			->notEmpty('token_value', 'Missing Token value');
		$validator->add('total_token', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Invalid Total Token'
		]);
		$validator->add('sold_token', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Invalid Sold Token'
		]);
		
		
		return $validator;
	} */
	

}
?>
