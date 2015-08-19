<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ApplicationsFrameworksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ApplicationsFrameworksTable Test Case
 */
class ApplicationsFrameworksTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.applications_frameworks',
        'app.applications',
        'app.bugs',
        'app.status',
        'app.tasks',
        'app.servers',
        'app.instances',
        'app.frameworks',
        'app.framework_versions',
        'app.technologies',
        'app.applications_technologies',
        'app.users',
        'app.roles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ApplicationsFrameworks') ? [] : ['className' => 'App\Model\Table\ApplicationsFrameworksTable'];
        $this->ApplicationsFrameworks = TableRegistry::get('ApplicationsFrameworks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ApplicationsFrameworks);

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
