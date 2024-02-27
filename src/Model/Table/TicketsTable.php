<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class TicketsTable extends Table
{
	
	
    
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		$this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'user_id'
        ]);
		$this->belongsTo('subjects', [
            'className' => 'Subjects',
			'foreignKey' => 'subject_id'
        ]);
		$this->hasMany('TicketMessages', [
            'className' => 'TicketMessages',
			'foreignKey' => 'ticket_id',
			'bindingKey'=>'ticket_id'
        ]);
	}
	

}
?>
