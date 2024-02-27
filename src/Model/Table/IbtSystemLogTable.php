<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * DepositApplicationList Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class IbtSystemLogTable extends Table
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

        $this->table('ibt_system_log');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
    }
	// insert log data
	public function addSystemLog($data = array()){
		$this->IbtSystemLog = TableRegistry::get("IbtSystemLog");
		$IbtSystemLog = $this->IbtSystemLog->newEntity();
		$IbtSystemLog = $this->IbtSystemLog->patchEntity($IbtSystemLog,$data);
		$saveData = $this->IbtSystemLog->save($IbtSystemLog);
	}
}
