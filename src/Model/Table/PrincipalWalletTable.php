<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PrincipalWalletTable extends Table
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
        $this->belongsTo('usersa', [
            'className' => 'Users',
            'foreignKey' => 'coupon_user_id'
        ]);
        $this->belongsTo('cryptocoinsa', [
            'className' => 'Cryptocoin',
            'foreignKey' => 'coupon_cryptocoin_id'
        ]);
		$this->belongsTo('withdrawal_wallet_address', [
            'className' => 'WithdrawalWalletAddress',
            'foreignKey' => 'user_id'
        ]);
    }
   /*  public function validationDefault(Validator $validator)
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


    } */



}
?>
