<?php
namespace App\Test\TestCase\Controller\Admin;

use App\Controller\Admin\UsersController;
use Cake\ORM\TableRegistry;
use App\Test\TestCase\Controller\AppControllerTestCase;

/**
 * App\Controller\Admin\UsersController Test Case
 */
class UsersControllerTest extends AppControllerTestCase
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

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndexAuthAdmin()
    {
        $this->loginAdminUser();

        $this->get('/admin/users');

//         debug($this->_response->__debugInfo());

        $this->assertResponseOk();
        $this->assertResponseContains('Bob.Morane@unige.ch');
        $this->assertResponseContains('Alice.Wonderland@unige.ch');
    }

    public function testIndexAuthUser()
    {
        $this->loginUserUser();

        $this->get('/admin/users');

        //         debug($this->_response->__debugInfo());

        $this->assertRedirect('/');
    }

    public function testIndexWithoutAuth()
    {
        $this->get('/admin/users');

        $this->assertRedirect('/shiblogin?redirect=' . urlencode('/admin/users'));
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
        $user = TableRegistry::get('Users')->get($id);

        $this->get('/admin/users/view/' . $id);

        $this->assertResponseContains($user->firstname);
        $this->assertResponseContains($user->lastname);
        $this->assertResponseContains($user->email);

    }

    public function testViewWithoutAuth()
    {
        $id = 1;
        $user = TableRegistry::get('Users')->get($id);

        $this->get('/admin/users/view/' . $id);

        $this->assertRedirect('/shiblogin?redirect=' . urlencode('/admin/users/view/' . $id));
    }

    public function testViewAuthUser()
    {
        $this->loginUserUser();

        $id = 1;
        $user = TableRegistry::get('Users')->get($id);

        $this->get('/admin/users/view/' . $id);

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
        $user = TableRegistry::get('Users')->get($id);

        $this->get('/admin/users/edit/' . $id);

//         debug((string)$user->to_display_timezone('last_login_date'));

        $this->assertResponseContains($user->firstname);
        $this->assertResponseContains($user->lastname);
        $this->assertResponseContains((string)$user->to_display_timezone('last_login_date'));

        $edit_data = [];

        $edit_data['role_id']   = $user->role_id;
        $edit_data['firstname'] = $user->firstname;
        $edit_data['lastname']  = $user->lastname;
        $edit_data['email']     = 'email-edited@unige.ch';

        $this->post('/admin/users/edit/' . $id, $edit_data);

        $this->assertRedirect('/admin/users/view/' . $id);

        $this->get('/admin/users/view/' . $id);
        $this->assertResponseContains('email-edited@unige.ch');
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
        $user = TableRegistry::get('Users')->get($id);

        $this->post('/admin/users/delete/' . $id);

        $this->assertRedirect('/admin/users');

        $this->assertResponseNotContains($user->firstname);
    }

    public function testDeleteWithoutAuth()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $id = 1;
        $user = TableRegistry::get('Users')->get($id);

        $this->post('/admin/users/delete/' . $id);

        $this->assertRedirect('/shiblogin');

        $user = TableRegistry::get('Users')->get($id);
        $this->assertEquals($id, $user->id);
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

        $users = TableRegistry::get('Users')->find();
        $this->assertEquals(3, $users->count());

        $this->post('/admin/users/delete-all/', ['checked_ids' => [1, 2, 3]]);

        $this->assertRedirect('/admin/users');

        $users = TableRegistry::get('Users')->find();
        $this->assertEquals(0, $users->count());
    }

    public function testDeleteAllWithoutAuth()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $users = TableRegistry::get('Users')->find();
        $this->assertEquals(3, $users->count());

        $this->post('/admin/users/delete-all/', ['checked_ids' => [1, 2, 3]]);

        $this->assertRedirect('/shiblogin');

        $users = TableRegistry::get('Users')->find();
        $this->assertEquals(3, $users->count());
    }
}

