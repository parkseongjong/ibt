<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class SellExchangeTable extends Table
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
		
		$this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'seller_user_id'
        ]);
		
		$this->belongsTo('spendcryptocoin', [
            'className' => 'Cryptocoin',
			'foreignKey' => 'sell_spend_coin_id'
        ]);
		
		$this->belongsTo('getcryptocoin', [
            'className' => 'Cryptocoin',
			'foreignKey' => 'sell_get_coin_id'
        ]);
		
		$this->hasOne('selltransactions', [
            'className' => 'Transactions',
			'foreignKey' => 'exchange_id',
			'conditions'=>['tx_type'=>'sell_exchange','remark'=>'sell_exchange']
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
