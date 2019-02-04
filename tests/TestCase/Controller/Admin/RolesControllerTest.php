<?php
namespace App\Test\TestCase\Controller\Admin;

use App\Controller\Admin\RolesController;
use Cake\ORM\TableRegistry;
use App\Test\TestCase\Controller\AppControllerTestCase;

/**
 * App\Controller\Admin\RolesController Test Case
 */
class RolesControllerTest extends AppControllerTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Roles',
        'app.Users',
        'plugin.Alaxos.LogCategories',
        'plugin.Alaxos.LogLevels',
        'plugin.Alaxos.LogEntries',
    ];

    public function testIndexAuthAdmin()
    {
        $this->loginAdminUser();

        $this->get('/admin/roles');

        //         debug($this->_response->__debugInfo());

        $this->assertResponseOk();
        $this->assertResponseContains('administrateur');
        $this->assertResponseContains('validateur');
    }

    public function testIndexAuthUser()
    {
        $this->loginUserUser();

        $this->get('/admin/roles');

        //         debug($this->_response->__debugInfo());

        $this->assertRedirect('/');
    }

    public function testIndexWithoutAuth()
    {
        $this->get('/admin/roles');

        $this->assertRedirect('/shiblogin?redirect=' . urlencode('/admin/roles'));
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->loginAdminUser();

        $id = 1;
        $role = TableRegistry::get('Roles')->get($id);

        $this->get('/admin/roles/view/' . $id);

        $this->assertResponseContains($role->name);

    }

    public function testViewWithoutAuth()
    {
        $id = 1;
        $role = TableRegistry::get('Roles')->get($id);

        $this->get('/admin/roles/view/' . $id);

        $this->assertRedirect('/shiblogin?redirect=' . urlencode('/admin/roles/view/' . $id));
    }

    public function testViewAuthUser()
    {
        $this->loginUserUser();

        $id = 1;
        $role = TableRegistry::get('Roles')->get($id);

        $this->get('/admin/roles/view/' . $id);

        $this->assertRedirect('/');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->loginAdminUser();
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $id = 1;
        $role = TableRegistry::get('Roles')->get($id);

        $this->get('/admin/roles/edit/' . $id);

        $this->assertResponseContains($role->name);

        $edit_data = [];

        $edit_data['name'] = 'edited role';

        $this->post('/admin/roles/edit/' . $id, $edit_data);

        $this->assertRedirect('/admin/roles/view/' . $id);

        $this->get('/admin/roles/view/' . $id);
        $this->assertResponseContains('edited role');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->loginAdminUser();
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $id = 1;
        $role = TableRegistry::get('Roles')->get($id);

        $this->post('/admin/roles/delete/' . $id);

        $this->assertRedirect('/admin/roles');

        $this->assertResponseNotContains($role->name);
    }

    public function testDeleteWithoutAuth()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $id = 1;
        $role = TableRegistry::get('Roles')->get($id);

        $this->post('/admin/roles/delete/' . $id);

        $this->assertRedirect('/shiblogin');

        $role = TableRegistry::get('Roles')->get($id);
        $this->assertEquals($id, $role->id);
    }

    /**
     * Test delete_all method
     *
     * @return void
     */
    public function testDeleteAll()
    {
        $this->loginAdminUser();
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        /*
         * Delete users 2 and 3 in order to be able to delete their roles
         */
        TableRegistry::get('Users')->deleteAll(['id IN' => [2,3]]);

        $roles = TableRegistry::get('Roles')->find();
        $this->assertEquals(3, $roles->count());

        $this->post('/admin/roles/delete-all/', ['checked_ids' => [2, 3]]);

        $this->assertRedirect('/admin/roles');

        $roles = TableRegistry::get('Roles')->find();
        $this->assertEquals(1, $roles->count());
    }

    public function testDeleteAllWithoutAuth()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $roles = TableRegistry::get('Roles')->find();
        $this->assertEquals(3, $roles->count());

        $this->post('/admin/roles/delete-all/', ['checked_ids' => [1, 2, 3]]);

        $this->assertRedirect('/shiblogin');

        $roles = TableRegistry::get('Roles')->find();
        $this->assertEquals(3, $roles->count());
    }
}
