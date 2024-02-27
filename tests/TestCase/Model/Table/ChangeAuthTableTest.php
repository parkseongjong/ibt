<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChangeAuthTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChangeAuthTable Test Case
 */
class ChangeAuthTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChangeAuthTable
     */
    public $ChangeAuth;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.change_auth',
        'app.users',
        'app.ethtransactions',
        'app.from_user',
        'app.level',
        'app.levelpages',
        'app.levels',
        'app.eth_reserve',
        'app.user',
        'app.ram_reserve',
        'app.seller',
        'app.admc_reserve',
        'app.buyer',
        'app.usd_reserve',
        'app.spendcryptocoin',
        'app.getcryptocoin',
        'app.selltransactions',
        'app.cryptocoin',
        'app.sell_reserve',
        'app.buy_reserve',
        'app.buytransactions',
        'app.sell_exchange',
        'app.buy_exchange',
        'app.ramtransactions',
        'app.admctransactions',
        'app.usdtransactions',
        'app.agctransactions',
        'app.sendby_user',
        'app.cointransactions',
        'app.exchange',
        'app.tocointransfer',
        'app.to_user',
        'app.buyvolume',
        'app.sellvolume',
        'app.referral_user'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ChangeAuth') ? [] : ['className' => 'App\Model\Table\ChangeAuthTable'];
        $this->ChangeAuth = TableRegistry::get('ChangeAuth', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ChangeAuth);

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
