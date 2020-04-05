<?php 
/************************************************************************************/
/* permission.php is a fat-free script that check user(s) permissions then serve it */
/************************************************************************************/
	
/************************************************************************************/
/******************************* get user token from headers ************************/ 
/************************************************************************************/
$enc_token = $f3->get('HEADERS')['Authorization'];
//var_dump($enc_token);die();
if( strpos($enc_token,'Bearer ')===0 ){
	$enc_token = substr($enc_token, 7,-1);
}
$dec_token = openssl_decrypt(base64_decode($enc_token), "AES-128-ECB", $f3->get('security_key'));
$token = json_decode($dec_token,true);
/************************************************************************************/
/******************************* checking token validation **************************/ 
/************************************************************************************/
if($token && $token['active'] == 'true'){
	$date1=date_create(date("Y-m-d"));
	$date2=date_create($token['date']);
	$diff=date_diff($date1,$date2);
	$diff = $diff->format("%a");
	if($diff > 0){
		$f3->set('auth',$token);
		$f3->set('permission',$token['permission']);
		$f3->set('enc_token',$enc_token);
	}
}
else{
	$f3->set('permission', 1 );
}

/************************************************************************************/
/******** function Acl_Check for checking user roles that return true|false *********/ 
/************************************************************************************/
global $f3;
function Acl_Check(){
	global $f3;
	$permission 	= $f3->get('permission');
	$ROOT_PATH  = $f3->get('PATH');
	if(IsAllow($permission , $ROOT_PATH)){
		return true;
	}
	else{
		return false;
	}
}
function IsAllow( $permissions , $current_route ) {
	global $f3;
	$current_route = str_replace("/", "\/" , $current_route);
	$current_route = str_replace("%s", "[a-z-A-Z-0-9-_]*" , $current_route);
	$current_route = "/^".$current_route."$/";
	$Allow = false;
	$loop_acl = $f3->Get('acl');
	foreach ($loop_acl as $key => $aclList) {
		if ($Allow != false){
			break;
		}
		$MatchString = array();
		preg_match($current_route,$key,$MatchString);
		if( count($MatchString) >  0 ) {
			if( ($permissions&$aclList[0]) > 0 ){
				$Allow = true;
				return $Allow;
			}
		}
	}
	return $Allow;
}

/************************************************************************************/
/************ check the user permission and allow|deny user to access ***************/ 
/************************************************************************************/
if(! Acl_Check()){
	$result['validate']   = 'false';
	$result['token']	  = '';
	die(json_encode($result));
}
?>