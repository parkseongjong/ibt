<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class ExchangeHistoryTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
		/* $this->belongsTo('sendby_user', [
            'className' => 'Users',
			'foreignKey' => 'sendby_user_id'
        ]); */
		
		/* $this->belongsTo('seller', [
            'className' => 'Users',
			'foreignKey' => 'seller_user_id'
        ]);
        
		$this->belongsTo('buyer', [
            'className' => 'Users',
			'foreignKey' => 'buyer_user_id'
        ]);  */
		
		$this->belongsTo('sell_exchange', [
            'className' => 'SellExchange',
			'foreignKey' => 'sell_exchange_id'
        ]);
        
		$this->belongsTo('buy_exchange', [
            'className' => 'BuyExchange',
			'foreignKey' => 'buy_exchange_id'
        ]);

		$this->belongsTo('spendcryptocoin', [
            'className' => 'Cryptocoin',
			'foreignKey' => 'spend_cryptocoin_id'
        ]);
		
		$this->belongsTo('getcryptocoin', [
            'className' => 'Cryptocoin',
			'foreignKey' => 'get_cryptocoin_id'
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
