<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;



//use Cake\Auth\DefaultPasswordHasher;
//use Cake\ORM\Rule\IsUnique;
//use Cake\ORM\TableRegistry;
//use Cake\Routing\Router;



class BoardNoticeTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		$this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'users_id'
        ]);
		
	}
	
	
     	
	public function validationDefault(Validator $validator)
    {
		$validator
            ->notEmpty('kind', 'Missing board id')
            ->notEmpty('subject', 'Missing subject')
            ->notEmpty('contents', 'Missing contents')
		;


		
		  $validator
			  -> add('subject', 'maxLength', [
			  	'rule' => ['maxLength', 200],
				'message' => 'Subject should be maximum 15 characters.'
		  ]);
		

			/*->add('subject','custom',[
				'rule'=>  function($value, $context){
						
					if($value >0 ) return true;
					return false;
				},
				'message'=>'Amount cannot be less than Zero.',
			]);*/



/*
        $validator
            ->notEmpty('module_name', 'Module name cannot be blank')
            ->notEmpty('value', 'Please define value');

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
			]);	*/


		return $validator; 

	}

		


}
?>
