<?php
$menu = [];

//debug($logged_user);

if(isset($logged_user))
{
	$menu[] = [
			'title'   => ___('applications'),
			'url'	 => array('prefix' => 'admin', 'controller' => 'Applications', 'action' => 'index'),
			'options' => ['id' => 'applications']
	];
	
	$menu[] = [
	    'title'   => ___('tasks'),
	    'url'	 => array('prefix' => 'admin', 'controller' => 'Tasks', 'action' => 'index'),
	    'options' => ['id' => 'tasks']
	];
	
	$menu[] = [
	    'title'   => ___('bugs'),
	    'url'	 => array('prefix' => 'admin', 'controller' => 'Bugs', 'action' => 'index'),
	    'options' => ['id' => 'bugs']
	];
	
	$menu[] = [
	    'title'   => ___('frameworks'),
	    'url'	 => array('prefix' => 'admin', 'controller' => 'Frameworks', 'action' => 'index'),
	    'options' => ['id' => 'frameworks']
	];
	
	$menu[] = [
	    'title'   => ___('technologies'),
	    'url'	 => array('prefix' => 'admin', 'controller' => 'Technologies', 'action' => 'index'),
	    'options' => ['id' => 'technologies']
	];
	
	$menu[] = [
	    'title'   => ___('servers'),
	    'url'	 => array('prefix' => 'admin', 'controller' => 'Servers', 'action' => 'index'),
	    'options' => ['id' => 'servers']
	];
}

if(isset($logged_user) && $logged_user->role_id == ROLE_ID_ADMINISTRATOR)
{
	$menu[] = [
				'title'   => ___('users'),
				'url'	 => array('prefix' => 'admin', 'controller' => 'Users', 'action' => 'index'),
				'options' => ['id' => 'users']
	];
		
	$menu[] = [
				'title'   => ___('roles'),
				'url'	 => array('prefix' => 'admin', 'controller' => 'Roles', 'action' => 'index'),
				'options' => ['id' => 'roles']
	];
	
	$menu[] = [
	    'title'   => ___('status'),
	    'url'	 => array('prefix' => 'admin', 'controller' => 'Status', 'action' => 'index'),
	    'options' => ['id' => 'status']
	];
}

$menu['_right_'] = [];
if(isset($logged_user))
{
	$menu['_right_'][] = [
				'title'   => ___('logout') . ' (' . $logged_user->fullname . ')',
				'url'     => array('prefix' => false, 'controller' => 'Users', 'action' => 'logout'),
				'options' => ['id' => 'logout']
	];
}
else
{
	$menu['_right_'][] = [
			'title'   => ___('login'),
			'url'     => array('prefix' => false, 'controller' => 'Users', 'action' => 'shiblogin'),
			'options' => ['id' => 'login']
	];
}

//debug($this->request->params['controller']);

$options = [];
$options['navbar_class'] = 'navbar navbar-default navbar-flat';

// if(in_array($this->request->params['controller'], ['Chamilo', 'ChamiloInactiveCourses']))
// {
// 	$options['selected'] = 'chamilo';
// }

/*******************************************/

if(!empty($menu))
{
	echo '<div class="row">';
		echo '<div class="col-md-12">';
		echo $this->Navbars->horizontalMenu($menu, $options);
		echo '</div>';
	echo '</div>';
}
