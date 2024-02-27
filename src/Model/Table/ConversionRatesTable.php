<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class ConversionRatesTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
	}
	
	public function validationDefault(Validator $validator)
    {
		$validator
			->notEmpty('from_date', 'Missing from date')
			->notEmpty('to_date', 'Missing to date')
			->notEmpty('rate', 'Missing conversion rate')
			->notEmpty('total_coins', 'Missing limit')
			;
		$validator->add('rate', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Invalid conversion rate'
		]);
		$validator->add('total_coins', 'validFormat', [
			'rule' => 'numeric',
			'message' => 'Invalid limit'
		]);
		$validator
			->add('from_date','custom',[
				'rule'=>  function($value, $context){
					if($context['data']['to_date'] > $value) return true;
					return false;
				},
				'message'=>'Start date cannot be less than end date.',
			]);
		
		return $validator;
	}
	

}
?>
