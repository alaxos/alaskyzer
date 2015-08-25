<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TaskCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TaskCategoriesTable Test Case
 */
class TaskCategoriesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.task_categories',
        'app.tasks',
        'app.status',
        'app.applications',
        'app.instances',
        'app.applications_frameworks',
        'app.frameworks',
        'app.framework_versions',
        'app.technologies',
        'app.applications_technologies',
        'app.users',
        'app.roles',
        'app.servers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TaskCategories') ? [] : ['className' => 'App\Model\Table\TaskCategoriesTable'];
        $this->TaskCategories = TableRegistry::get('TaskCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TaskCategories);

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
