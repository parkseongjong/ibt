<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
//use Cake\Auth\DefaultPasswordHasher;
//use Cake\ORM\Rule\IsUnique;

class ContactUsTable extends Table
{
	public function initialize(array $config)
	{
		$this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'user_id'
        ]);
		$this->addBehavior('Timestamp');
		
	}
	public function validationDefault(Validator $validator)
    {
		
    	$validator
				->notEmpty('name', 'Please enter your name')
				->notEmpty('email', 'Please enter your email')
				->notEmpty('phone', 'Please enter your contact number')
				->notEmpty('message', 'Please enter your query');
		$validator->add('phone', 'validFormat', [
			'rule' => 'IsInteger',
			'message' => 'Phone Number should be numeric'
		]);
		$validator
			->add('phone', [
				'minLength' => [
					'rule' => ['minLength', 10],
					'last' => true,
					'message' => 'Phone Number should be 10 digits.'
				],
				'maxLength' => [
					'rule' => ['maxLength', 10],
					'message' => 'Phone Number should be 10 digits.'
				], 
				
			]);
		$validator->add('email', 'validFormat', [
			'rule' => 'email',
			'message' => 'E-mail must be a valid email'
		]);
		
		
		return $validator;

          
	}
	public function validationReply(Validator $validator)
    {
		
				
		$validator
		->notEmpty('id', 'Select query')
		->notEmpty('reply_subject', 'Please write subject')
		->notEmpty('reply_message', 'Please write message');
		
		return $validator;

          
	}
	
	
	
	
}
?>
