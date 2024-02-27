<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ChangeAuthFixture
 *
 */
class ChangeAuthFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'change_auth';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'user_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => 'NULL', 'comment' => '', 'precision' => null, 'fixed' => null],
        'user_phone_number' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => 'NULL', 'comment' => '', 'precision' => null, 'fixed' => null],
        'user_email' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => 'NULL', 'comment' => '', 'precision' => null, 'fixed' => null],
        'user_bank_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => 'NULL', 'comment' => '', 'precision' => null, 'fixed' => null],
        'user_account_number' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => 'NULL', 'comment' => '', 'precision' => null, 'fixed' => null],
        'request' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => 'NULL', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'user_id' => 1,
            'user_name' => 'Lorem ipsum dolor sit amet',
            'user_phone_number' => 'Lorem ipsum dolor sit amet',
            'user_email' => 'Lorem ipsum dolor sit amet',
            'user_bank_name' => 'Lorem ipsum dolor sit amet',
            'user_account_number' => 'Lorem ipsum dolor sit amet',
            'request' => 'Lorem ipsum dolor sit amet',
            'created' => '2021-02-05 13:16:27'
        ],
    ];
}
