<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EpayTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EpayTable Test Case
 */
class EpayTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EpayTable
     */
    public $Epay;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.epay'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Epay') ? [] : ['className' => 'App\Model\Table\EpayTable'];
        $this->Epay = TableRegistry::get('Epay', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Epay);

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
}
