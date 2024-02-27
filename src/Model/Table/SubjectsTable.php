<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class SubjectsTable extends Table
{



    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
       
    }
    public function validationDefault(Validator $validator)
    {
		 $validator
			->notEmpty('subject', 'Please enter subject')
 	     ;
		$validator->add('subject', 'unique', [
			'rule' => 'validateUnique',
			'provider' => 'table',
			'message'=>'subject already exist'
		]);   
		return $validator;
	}


}
?>
