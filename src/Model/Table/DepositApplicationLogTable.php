<?php
namespace App\Model\Table;

use App\Model\Entity\DepositApplicationList;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DepositApplicationList Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class DepositApplicationLogTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('deposit_application_log');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
		$this->belongsTo('user', [
            'className' => 'Users',
			'foreignKey' => 'user_id'
        ]);
    }
}
