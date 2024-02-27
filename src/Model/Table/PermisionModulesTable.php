<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class PermisionModulesTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
	}

	public function validationDefault(Validator $validator)
    {
		$validator
				->notEmpty('module_name', 'Please Select  Module Name');
	    return $validator;
	}
	
}
?>
