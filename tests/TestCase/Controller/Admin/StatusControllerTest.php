<?php
namespace App\Test\TestCase\Controller\Admin;

use App\Controller\Admin\StatusController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Admin\StatusController Test Case
 */
class StatusControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.status',
        'app.bugs',
        'app.applications',
        'app.instances',
        'app.tasks',
        'app.servers',
        'app.frameworks',
        'app.applications_frameworks',
        'app.technologies',
        'app.applications_technologies'
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
