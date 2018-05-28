<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class UsersController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = ['Alaxos.Filter'];

    /**
     * {@inheritDoc}
     * @see \App\Controller\AppController::beforeFilter()
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(array('shiblogin', 'logout'));
    }

    /**
     * Logout user
     * @return void
     */
    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    /**
     * Performs the Shibboleth login
     * @return void
     */
    public function shiblogin()
    {
        /*
         * If the user comes here, it means the user is gone through the Shibboleth authentication
            *
            * -> login() can be called
            *
            *  Note: if
            *             $this->Auth->allow('shiblogin');
            *
            *        is not called in beforeFilter(), the authentication is done automatically.
            *        But in this case we could not manage the sucess/error manually
            */
        $loggedUser = $this->Auth->user();

        if (empty($loggedUser)) {

            if ($loggedUser = $this->Auth->identify()) {
                /*
                 * Remove unauthorized messages that have not been shown yet
                 */
                $this->getRequest()->getSession()->delete('Flash.flash');

                $this->Users->set_last_login_date($loggedUser['id']);

                $this->Auth->setUser($loggedUser);

                $this->Logger->login($loggedUser['firstname'] . ' ' . $loggedUser['lastname'] . ' logged in');

                $shibbolethAuth = $this->Auth->authenticationProvider();

                if ($shibbolethAuth->isNewUser()) {
                    $this->Flash->success(__('Your account has been created'));
                } else {
                    $this->Flash->success(__('You have been authenticated'));
                }

                $this->redirect($this->Auth->redirectUrl());

            } else {
                $this->Flash->error(__('unable to login'));
                $this->redirect($this->referer('/'));
            }

        } else {
            $this->Flash->info(__('you are already logged in'));

            $this->redirect($this->referer('/'));
        }
    }
}
