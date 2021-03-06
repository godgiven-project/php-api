<?php
/*********************************************************  ROUTES  ***************************************************************/
	$f3->route('GET							/'									   ,	 'DashboardController->home');
	$f3->route('POST						/admin_login'						   ,	 'UsersController->login');
	$f3->route('POST						/login'								   ,	 'UsersController->login');
	$f3->route('GET|POST					/register'							   ,	 'UsersController->register');
	$f3->route('GET|POST					/users'								   ,	 'UsersController->users');
	$f3->route('GET|POST|PATCH|PUT|DELETE   /users/@user'						   ,	 'UsersController->user');
	$f3->route('GET|POST					/users/userinfo'					   ,	 'UsersController->userinfo');
	$f3->route('GET|POST					/notifications'						   ,	 'DashboardController->notifications');
/**********************************************************************************************************************************/
?>
