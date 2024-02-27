<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;

class TicketMessagesTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		 $this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'user_id'
        ]);
		 $this->belongsTo('ticket', [
            'className' => 'Tickets',
			'foreignKey' => 'ticket_id',
			'bindingKey' => 'ticket_id'
        ]);
	}
	

}
?>
