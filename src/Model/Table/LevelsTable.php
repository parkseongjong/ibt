<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class LevelsTable extends Table
{
	
	
    
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');

		$this->hasMany('levelpages', [
            'className' => 'LevelPages',
			'foreignKey' => 'level_id'
        ]);
	}
	

}
?>
