<?php
$Acl = [ 
	'/login'			=> [ 1	, "login" 		] ,
	'/logout'			=> [ 1	, "logout" 		] ,
	'/register'			=> [ 1	, "register" 	] ,
	'/' 				=> [ 1 	, "main page" 	] ,
	'/users/userinfo'	=> [ 2 	, "userinfo" 	] ,
	'/users'			=> [ 4	, "users"		] ,
	'/users/%s'			=> [ 4	, "users"		] ,
	
];
$f3->set('acl',$Acl);
?>