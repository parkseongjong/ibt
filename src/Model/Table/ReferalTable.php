<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class ReferalTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
	}
	
	public function validationDefault(Validator $validator)
    {
		$validator
			->notEmpty('referal_percent', 'Missing Token value');
		$validator->add('referal_percent', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Invalid Referal'
		]);
		
		
		return $validator;
	}
	

}
?>
