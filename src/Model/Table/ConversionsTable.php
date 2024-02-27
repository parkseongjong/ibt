<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class ConversionsTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
		/* $this->belongsTo('sendby_user', [
            'className' => 'Users',
			'foreignKey' => 'sendby_user_id'
        ]); */
		
		$this->belongsTo('referral_user', [
            'className' => 'Users',
			'foreignKey' => 'referral_user_id'
        ]);
        
		$this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'user_id'
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
