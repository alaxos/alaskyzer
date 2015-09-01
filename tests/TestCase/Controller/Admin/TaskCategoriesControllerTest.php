<?php
namespace App\Test\TestCase\Controller\Admin;

use App\Controller\Admin\TaskCategoriesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Admin\TaskCategoriesController Test Case
 */
class TaskCategoriesControllerTest extends IntegrationTestCase
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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete_all method
     *
     * @return void
     */
    public function testDeleteAll()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test copy method
     *
     * @return void
     */
    public function testCopy()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}