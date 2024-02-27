<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class TransactionsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
		$this->belongsTo('from_user', [
            'className' => 'Users',
			'foreignKey' => 'from_user_id'
        ]);
        $this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('cryptocoin', [
            'className' => 'Cryptocoin',
			'foreignKey' => 'cryptocoin_id'
        ]);

		$this->belongsTo('withdrawals', [
			'className' => 'WithdrawalWalletAddress',
			'foreignKey' => 'user_id'
		]);
        
		$this->belongsTo('sellReserve', [
            'className' => 'sellExchange',
			'foreignKey' => 'exchange_id',
			'conditions'=>['Transactions.tx_type'=>'sell_exchange','Transactions.remark'=>'reserve for exchange']
        ]);
		
		$this->belongsTo('buyReserve', [
            'className' => 'buyExchange',
			'foreignKey' => 'exchange_id',
			'conditions'=>['Transactions.tx_type'=>'buy_exchange','Transactions.remark'=>'reserve for exchange']
        ]);
		
		
		$this->belongsTo('sell_exchange', [
            'className' => 'sellExchange',
			'foreignKey' => 'exchange_id',
			'conditions'=>['Transactions.tx_type'=>'sell_exchange']
        ]);
		
		$this->belongsTo('buy_exchange', [
            'className' => 'buyExchange',
			'foreignKey' => 'exchange_id',
			'conditions'=>['Transactions.tx_type'=>'buy_exchange']
        ]);
		
		
    }
    public function validationDefault(Validator $validator)
    {


        $validator
            ->notEmpty('user_id', 'Missing user id')
            ->notEmpty('from_user_id', 'Missing from user id')
            ->notEmpty('coin_type', 'Missing coin type')
            ->notEmpty('amount', 'Please define number of coins')
            ->notEmpty('trans_type',  'Missing transaction type')
            ->notEmpty('transaction_id',  'Missing transaction id')
           ;
	


       	$validator->add('amount', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Invalid amount.'
		]);
		  $validator
			->add('amount','custom',[
				'rule'=>  function($value, $context){
						
					if($value >0 ) return true;
					return false;
				},
				'message'=>'Amount cannot be less than Zero.',
			]);
		 $validator
			->add('transaction_id','custom',[
				'rule'=>  function($value, $context){
						
					if(preg_match("/^[a-zA-Z\d]+$/", $value)) return true;
					return false;
				},
				'message'=>'Invalid transaction id.',
			]);

        return $validator;


    }



}
?>
