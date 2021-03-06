<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\I18n\I18n;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Text;
use Cake\Database\Type;
use Cake\Http\ServerRequest;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Flash');
        $this->loadComponent('Security');
        $this->loadComponent('Csrf');
        $this->loadComponent('Auth');
        $this->loadComponent('RequestHandler', ['enableBeforeRedirect' => false]);

        $this->loadComponent('Alaxos.Filter');
        $this->loadComponent('Alaxos.Logger', ['days_to_keep_logs' => 30]);
        $this->loadComponent('Alaxos.UserLink');

        $this->helpers[] = 'Alaxos.AlaxosHtml';
        $this->helpers[] = 'Alaxos.AlaxosForm';
        $this->helpers[] = 'Alaxos.Navbars';

        $this->Logger->setConfig('log_categories.login', 4);
    }

    function beforeFilter(Event $event)
    {
        $controller = $this->getRequest()->getParam('controller');
        if($controller == 'Pages')
        {
            $this->Auth->allow();
        }

        $this->_set_authentication();
        $this->_set_logged_user();
    }

    private function _set_authentication()
    {
        $this->Auth->setConfig('loginAction', ['prefix' => false, 'controller' => 'Users', 'action' => 'shiblogin']);
        $this->Auth->setConfig('unauthorizedRedirect', '/');
        $this->Auth->setConfig('authError', __('you are not authorized to access this page'));
        $this->Auth->setConfig('flash', ['element' => 'Alaxos.error']);
        $this->Auth->setConfig('loginRedirect', ['prefix' => 'admin', 'controller' => 'Dashboard', 'action' => 'index']);
        $this->Auth->setConfig('logoutRedirect', '/');

        $complete_new_user_data_fonction = function(ServerRequest $request, $user_data){

            $user_data['role_id']     = ROLE_ID_USER; //default role is user
            $user_data['username']    = $user_data['external_uid'];
            $user_data['enabled']     = true;
            $user_data['password']    = Text::uuid();

            return $user_data;
        };

        $this->Auth->setConfig('authenticate',
                        [
                            'Form',
                            'Alaxos.Shibboleth'    => ['unique_id' => Configure::read('Shibboleth.unique_id_attribute'),
                                'mapping'              => Configure::read('Shibboleth.attributes_mapping'),
                                'updatable_properties' => Configure::read('Shibboleth.updatable_properties'),
                                'create_new_user'      => true,
                                'completeNewUserData'  => $complete_new_user_data_fonction,
                                'login_url'            => ['controller' => 'Users', 'action' => 'shiblogin'],
                            ]
                        ]);

        $this->Auth->setConfig('authorize', ['Controller']);
    }

    public function isAuthorized()
    {
        $prefix = $this->getRequest()->getParam('prefix');

        if(empty($prefix))
        {
            return true;
        }
        else
        {
            $user = $this->Auth->user();
            if(!empty($user))
            {
                if(in_array($user['role_id'], [ROLE_ID_ADMINISTRATOR]))
                {
                    return true;
                }
                elseif($prefix == 'admin' && in_array($user['role_id'], [ROLE_ID_ADMINISTRATOR]))
                {
                    return true;
                }
            }
        }

        return false;
    }

    private function _set_logged_user()
    {
        $user = $this->Auth->user();
        if(!empty($user))
        {
            $this->loadModel('Users');
            $user = $this->Users->get($user['id'], ['contain' => ['Roles']]);

            $this->set('logged_user', $user);
        }
    }
}
