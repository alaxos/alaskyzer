<?php
namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;

class AppControllerTestCase extends IntegrationTestCase
{
    public function loginAdminUser()
    {
        $user = TableRegistry::get('Users')->find()->enableHydration(false)->where(['role_id' => ROLE_ID_ADMINISTRATOR])->first();
        $this->session(['Auth' => ['User' => $user]]);
    }

    public function loginUserUser()
    {
        $user = TableRegistry::get('Users')->find()->enableHydration(false)->where(['role_id' => ROLE_ID_USER])->first();
        $this->session(['Auth' => ['User' => $user]]);
    }
}
