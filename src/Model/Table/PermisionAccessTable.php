<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class PermisionAccessTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
		$this->belongsTo('PermisionModules', [
            'className' => 'PermisionModules',
			'foreignKey' => 'permision_module_id'
        ]);
	}

	
	
}
?>
