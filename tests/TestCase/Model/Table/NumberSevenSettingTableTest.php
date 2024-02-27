<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NumberSevenSettingTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NumberSevenSettingTable Test Case
 */
class NumberSevenSettingTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NumberSevenSettingTable
     */
    public $NumberSevenSetting;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.number_seven_setting',
        'app.admins'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('NumberSevenSetting') ? [] : ['className' => 'App\Model\Table\NumberSevenSettingTable'];
        $this->NumberSevenSetting = TableRegistry::get('NumberSevenSetting', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NumberSevenSetting);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
