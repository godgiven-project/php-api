<?php 
/************************************************************************************/
/* permission.php is a fat-free script that check user(s) permissions then serve it */
/************************************************************************************/

/************************************************************************************/
/******************************* get user token from headers ************************/ 
/************************************************************************************/
$enc_token = $f3->get('HEADERS')['Token'];
$dec_token = openssl_decrypt(base64_decode($enc_token), "AES-128-ECB", $f3->get('security_key'));
$token = json_decode($dec_token,true);

/************************************************************************************/
/******************************* checking token validation **************************/ 
/************************************************************************************/
if($token){
	$date1=date_create(date("Y-m-d"));
    $date2=date_create($token['date']);
    $diff=date_diff($date1,$date2);
    $diff = $diff->format("%a");
    if($diff > 0){
        $f3->set('user_Acl',$token['type']);
    	$f3->set('auth',$token);
    	$f3->set('enc_token',$enc_token);
    }
}
else{
	$f3->set('user_Acl','guest');
}

/************************************************************************************/
/******** function Acl_Check for checking user roles that return true|false *********/ 
/************************************************************************************/
global $f3;
function Acl_Check(){
	global $f3;
	$user_Acl = $f3->get('user_Acl');
	$Acl = [ 
		'guest' => [
			'ROOT'            => ['*'],
			'login'           => ['*'],
			'logout'          => ['*'],
			'clear'           => ['*'],
			'dologin'         => ['*'],
			'send_docs'       => ['*'],
			'register'        => ['*'],
			'register_mobile' => ['*'],
			'register_email'  => ['*']
		],
		'admin'  => [
			'ROOT' => ['*'],
			'admins' => ['*'],
			'delete_file' => ['*'],
			'notifications' => ['*'],
			'archives' => ['*'],
			'users' => ['*'],
			'login' => ['*'],
			'logout' => ['*'],
			'register_step2'  => ['*'],
			'profile' =>['*'],
			'register_step2'        => ['*'],
			'find_users'    => ['*'],
			'find_user'    => ['*'],

		], 
		'operator'  => [
			'ROOT' => ['*'],
			'operators' => ['*'],
			'notifications' => ['*'],
			'archives' => ['*'],
			'delete_file' => ['*'],
			'clear'     => ['*'],
			'logout' => ['*'],
			'profile' =>['*']
		], 
		'client' => [
	        'ROOT'       => ['*'],
		    'clients'    => ['*'],
		    'notifications' => ['*'],
		    'archives' => ['*'],
		    'clear'      => ['*'],
            'logout'     => ['*'],
            'profile' =>['*'],
            'register_step2'        => ['*'],
	    ],
		'admin_' => [
			'ROOT'   => ['*'],
			'logout' => ['*'],
			'clear'  => ['*'],
			'profile' =>['*']
		],
		'*' => [
		    'logout' => ['*'],
			'clear'  => ['*'],
		]
	];
	$ROOT_PATH = $f3->get('PATH');
	$base_url = explode('/', $ROOT_PATH);
	if(isset($base_url['1'])){
		if($base_url['1'] == ''){
			$base_controller = 'ROOT';
		}
		else{
			$base_controller = $base_url['1'];
		}
	}
	else{
		$base_controller = 'ROOT';
	}
	if(isset($base_url['2'])){
		$base_actions = $base_url['2'];
	}
	foreach ($Acl as $role => $permissions_Acl) {
		if($user_Acl==$role){
			foreach ($permissions_Acl as $controller_Acl => $actions_Acl) {
				if($base_controller == $controller_Acl){
					foreach ($actions_Acl as $action_Acl) {
						if($action_Acl == $base_actions)
						{
							return true;
						}elseif ($action_Acl == '*' or $base_actions == '') {
							return true;
						}
					}
					if($base_actions == ''){
						return true;
					}
				}
			}
		}
	}
	return false;
}
/************************************************************************************/
/************ check the user permission and allow|deny user to access ***************/ 
/************************************************************************************/
if(! Acl_Check()){
	$result['validate']   = 'false';
	$result['force_data'] = '';
    $result['token']      = '';
	die(json_encode($result));
}
?>