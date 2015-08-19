<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FrameworkVersionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FrameworkVersionsTable Test Case
 */
class FrameworkVersionsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.framework_versions',
        'app.frameworks',
        'app.applications',
        'app.bugs',
        'app.status',
        'app.tasks',
        'app.servers',
        'app.instances',
        'app.applications_frameworks',
        'app.technologies',
        'app.applications_technologies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('FrameworkVersions') ? [] : ['className' => 'App\Model\Table\FrameworkVersionsTable'];
        $this->FrameworkVersions = TableRegistry::get('FrameworkVersions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FrameworkVersions);

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
