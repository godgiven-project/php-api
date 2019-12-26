<?php use DB\SQL\ggn_Mapper;
class UsersController extends MainController{
	/***********************************************************************/
	/*																	 */
	/*	 login function for user authentication  in `/login` route	   */
	/*																	 */
	/***********************************************************************/
	public function login($f3) {
		// required data
		$data = $f3->get('POST');
		if($data['password'] == "" or $data['username'] == ''){
			$f3->set('show_title','ورود');
			$result = array(
				false,
				"Username or password is not correct"
			);
			die(json_encode($result));
		}
		$users = new ggn_Mapper($this->dbs, 'users_ggn');
		$user = $users->load(array("`user_login` = ? AND `user_pass` = ? ", $data['username'] , md5($data['password']) ));
		if ($user != false and $user->user_block == 'false' and $user->user_active == true) {
			$session = array(
				'rand'   => rand(100000,999999), 
				'id'	 => $user->user_id,
				'fname'  => $person->per_Fname.' '.$person->per_Lname,
				'type'   => $user->user_type , 
				'date'   => date('Y:m:d h:i:s',strtotime("+2 days")) , 
				'permit' => $user->user_per,
				'active' => true,
			);
			$session	 = json_encode($session);
			$session_key = base64_encode(openssl_encrypt($session, "AES-128-ECB", $f3->get('security_key')));
			$result = array(
				true,
				$session_key,
			);
			die(json_encode($result));
		} else {
			if($user == false){
				$result = array(
					false,
					"Username or password is not correct"
				);
				die(json_encode($result));
			}
			else if($user->user_active == false){
				$result = array(
					false,
					"Your account is inactive"
				);
				die(json_encode($result));
			}
			else if($user->user_block == true){
				$result = array(
					false,
					"Your account is block"
				);
				die(json_encode($result));
			}
			else{
				$result = array(
					false,
					"There was a problem logging in"
				);
				die(json_encode($result));
			}
		}
	}
	/***********************************************************************/
	/*																	 */
	/*	register function for user registration  in `/register` route	*/
	/*																	 */
	/***********************************************************************/
	public function register($f3){
		$data = $f3->get('POST');
		$data = validate_data($data);
		if($data) {
			require_once($f3->get('MODEL').'/UserModel.php');
			$UserModel = new UserModel;
			$user_save = $UserModel->register($data);
			if($user_save[0] == false){
				$result = array(
					false,
					$user_save[1]
				);
				die(json_encode($result));
			}
			else{
				$result = array(
					true
				);
				die(json_encode($result));
			}
		}
	}
	/***********************************************************************/
	/*																	 */
	/*	   users function for search|insert users in `/users` route	  */
	/*																	 */
	/***********************************************************************/
	function users($f3){
		// React Function   
		$data = validate_data($f3->get('POST'));
		$action = $f3->get('HEADERS')['Action'];
		$query= "SELECT * FROM(
					SELECT per_meta_ref AS user_id
						, MAX( CASE WHEN per_meta_name = 'full_name' THEN per_meta_value END) full_name
						, MAX( CASE WHEN per_meta_name = 'status' THEN per_meta_value END) `status`
						, MAX( CASE WHEN per_meta_name = 'grade' THEN per_meta_value END) `grade` 
					FROM person_meta_ggn GROUP BY per_meta_ref
				) AS users_meta_view";
		switch($action){
			case 'edit':
				if($data){
					echo 'under construction';
				}
				echo 'under construction';
				break;
			default :
				$filter = array();
				$filter_string = "";
				if($data){
					if(is_numeric($data['status']) && $data['status'] > -1){
						$filter[] = ' `status` = '.$data['status'];
					}
					if($data['name'] != null && $data['name'] != ""){
						$filter[] = "`full_name` LIKE '%".$data['name']."%' ";					  
					}
					foreach($filter as $key => $single_filter ){
						$filter_string .= $single_filter.' AND ';
					}
					if($filter_string != ""){
						$filter_string = ' WHERE '.$filter_string;
						$filter_string = substr($filter_string, 0 , -5);
					}
				}
				$result['token']	  = $f3->get('enc_token');
				$result['validate']   = 'true';
				$query_result['data'] = $this->dbs->exec($query.$filter_string);
				if($query_result){
					$query_result['columns']=array(
						array('title' => 'شناسه کاربری'   , 'field' => 'user_id'),
						array('title' => 'نام تجاری'   , 'field' => 'full_name'),
						array('title' => 'وضعیت'   , 'field' => 'status'),
						array('title' => 'رتبه'   , 'field' => 'grade'),
						
					);
					$result['data'] = $query_result;
				}
				else{
					$result['data']='درخواست اشتباه';
				}
				die(json_encode($result));
				break;
		}
	}
	/***********************************************************************/
	/*																	 */
	/*	 user function for get|edit users data in `/user/@user` route	*/
	/*																	 */
	/***********************************************************************/
	function user($f3){
		//second react function
		$user_id = $f3->get('PARAMS.user');
		$action = $f3->get('HEADERS')['Action'];
		$action = $_SERVER['REQUEST_METHOD'];
		switch($action){
			case 'PATCH':
				$data = json_decode($f3->get('BODY') , true);
				$result['data'] = 'false';
				if(is_numeric($user_id)){
					require_once($f3->get('MODEL').'/UserModel.php');
					$UserModel = new UserModel;
					foreach($data as $key => $value){
						$UserModel->save_user_meta($user_id, $key, (int)$value);
					}
					$result['data'] = 'True';
				}
				$result['token']	  = $f3->get('enc_token');
				$result['validate']   = 'true';
				$result['force_data'] = $f3->get('auth')['force_data'];
				$result['user_type'] = $f3->get('user_Acl');
				break;
			default :
				if(is_numeric($user_id)){
					require_once($f3->get('MODEL').'/UserModel.php');
					$UserModel = new UserModel;
					$result['data'] = $UserModel->get_user_data($user_id);
					$result['token']	  = $f3->get('enc_token');
					$result['validate']   = 'true';
				}
				break;
		}
		die(json_encode($result));
	}
	/***********************************************************************/
	/*																	 */
	/*	  profile function for user profile data in `/profile` route	 */
	/*																	 */
	/***********************************************************************/
	public function profile($f3){
		require_once($f3->get('MODEL').'/UserModel.php');
		$UserModel = new UserModel;
		var_dump($f3->get('auth')['id']);
		$result['data'] = $UserModel->get_user_data($f3->get('auth')['id']);
		$result['token']	  = $f3->get('enc_token');
		$result['validate']   = 'true';
		die(json_encode($result));
	}
}