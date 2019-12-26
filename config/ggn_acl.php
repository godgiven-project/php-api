<?php
$Acl = [ 
	'/login'			=> [ 1	, "login" 		] ,
	'/logout'			=> [ 1	, "logout" 		] ,
	'/register'			=> [ 1	, "register" 	] ,
	'/' 				=> [ 1 	, "main page" 	] ,
	'/profile'			=> [ 2 	, "profile" 	] ,
	'/users'			=> [ 4	, "users"		] ,
	'/users/%s'			=> [ 4	, "users"		] ,
];
$f3->set('acl',$Acl);
?>