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
	
    
    public function beforeFilter(Event $event)
    {
    	parent::beforeFilter($event);
    
    	$this->Auth->allow(array('shiblogin', 'logout'));
    }
    
    public function logout()
    {
    	$this->redirect($this->Auth->logout());
    }
    
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
    	$logged_user = $this->Auth->user();
    
    	if(empty($logged_user))
    	{
    		if($logged_user = $this->Auth->identify())
    		{
    			$this->Users->set_last_login_date($logged_user['id']);
    
    			$this->Auth->setUser($logged_user);
    
    			//$this->Logger->info('login', $this->Logger->config('log_categories.login'));
    			//debug($logged_user);
    			$this->Logger->login($logged_user['firstname'] . ' ' . $logged_user['lastname'] . ' logged in');
    
    			$shibboleth_auth = $this->Auth->authenticationProvider();
    
    			if($shibboleth_auth->isNewUser())
    			{
    				$this->Flash->set(__('Your account has been created'), ['element' => 'Alaxos.success']);
    			}
    			else
    			{
    				$this->Flash->set(__('You have been authenticated'), ['element' => 'Alaxos.success']);
    			}
    
    			$this->redirect($this->Auth->redirectUrl());
    		}
    		else
    		{
    			$this->Flash->set(__('unable to login'), ['element' => 'Alaxos.error']);
    		}
    	}
    	else
    	{
    		$this->Flash->set(__('you are already logged in'), ['element' => 'Alaxos.info']);
    		$this->redirect($this->referer('/'));
    	}
    }
}
