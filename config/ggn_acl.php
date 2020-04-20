<?php
$Acl = [ 
	'/admin_login'		=> [ 0	 , "login" 		] ,
	'/login'			=> [ 0	 , "login" 		] ,
	'/logout'			=> [ 0	 , "logout" 	] ,
	'/register'			=> [ 0	 , "register" 	] ,
	'/users/userinfo'	=> [ 1 	 , "userinfo" 	] ,
	'/' 				=> [ 50  , "main page" 	] ,
	'/users'			=> [ 51	 , "users"		] ,
	'/users/%s'			=> [ 52	 , "users"		] ,
];
$f3->set('acl',$Acl);
?>