<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class LevelPagesTable extends Table
{
	
	
    
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');

		$this->belongsTo('levels', [
            'className' => 'Levels',
			'foreignKey' => 'level_id'
        ]);
	}
	

}
?>
