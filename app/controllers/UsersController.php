<?php use DB\SQL\ggn_Mapper;
class UsersController extends MainController{
	/***********************************************************************/
	/*                                                                     */
	/*     login function for user authentication  in `/login` route       */
	/*                                                                     */
	/***********************************************************************/
	public function login($f3) {
        // required data
		$data = $f3->get('POST');
		if($data['password'] == "" or $data['username'] == ''){
			$f3->set('show_title','ورود');
			$result = array(
                false,
                1
            );
	        die(json_encode($result));
		}
		$users = new ggn_Mapper($this->dbs, 'users_ggn');
		$user = $users->load(array("`user_login` = ? AND `user_pass` = ? ", $data['username'] , md5($data['password']) ));
		if ($user != false and $user->user_block == 'false' and $user->user_active == true) {
			$persons = new ggn_Mapper($this->dbs, 'persons_ggn');
			$person = $persons->load(array('per_user_id = ?',$user->user_id));
			$person_meta = new ggn_Mapper($this->dbs, 'person_meta_ggn');
		    $force_data = $person_meta->load(array("per_meta_ref = ? and per_meta_name = 'force_user_active'" , $user->user_id));
			$session = array('rand' => rand(10000,99999), 'id' => $user->user_id,'fname' => $person->per_Fname.' '.$person->per_Lname,
			    'type' => $user->user_type , 'date' => date('Y:m:d h:i:s',strtotime("+2 days")) , 'force_data' => $force_data->per_meta_value);
			$session = json_encode($session);
			$session_key = base64_encode(openssl_encrypt($session, "AES-128-ECB", $f3->get('security_key')));
            $result = array(
                true,
                $session_key,
                $force_data->per_meta_value,
                $user->user_type,
                unserialize($user->user_react_routes)
            );
			echo json_encode($result);
			die();
		} else {
		    if($user == false){
		        $result = array(
                    false,
                    2
                );
		        die(json_encode($result));
		    }
		    else if($user->user_active == false){
		        $result = array(
                    false,
                    3
                );
		        die(json_encode($result));
		    }
		    else if($user->user_block == true){
		        $result = array(
                    false,
                    4
                );
		        die(json_encode($result));
		    }
		    else{
		        $result = array(
                    false,
                    5
                );
		        die(json_encode($result));
		    }
		}
	}
	/***********************************************************************/
	/*                                                                     */
	/*    register function for user registration  in `/register` route    */
	/*                                                                     */
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
	/**************************************************************************************/
	/*                                                                                    */
	/*      register_step2 function to get userForceData in `/register_step2` route       */
	/*                                                                                    */
	/**************************************************************************************/
	public function register_step2($f3){
        $data = $f3->get('POST');
        $data = validate_data($data);
	    if($data){
	        $result['token']      = $f3->get('enc_token');
	        $result['validate']   = 'true';
            $result['force_data'] = $f3->get('auth')['force_data'];
            $array_fields         = $f3->get('force_user_meta');
            $get_data = array();
            foreach($array_fields as $key => $fields){
                switch ($fields['id']){
                    case 'location_in_town' :
                        if ( $data[$fields['id']] != "in" and $data[$fields['id']] != "out"){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا موقعیت مکانی زمین نسبت به روستای مورد نظر را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'full_name' :
                        if ( !( $data[$fields['id']] != "" ) and !($data[$fields['id']] != null) ){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا نام صاحب ملک یا صاحب شرکت را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'address' :
                        if ( !( $data[$fields['id']] != "" ) and !($data[$fields['id']] != null) ){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا آدرس را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'parent_name' :
                        if ( !( $data[$fields['id']] != "" ) and !($data[$fields['id']] != null) ){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا نام پدر / نام مدیرعامل را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'land_area' :
                        if ( $data[$fields['id']] != "less_than_500" and $data[$fields['id']] != "500_to_1500" and $data[$fields['id']] != "more_than_1500"){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'مساحت زمین مورد نظر یا اقامت گاه را به متر وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'area_branches' :
                        if(is_array($data[$fields['id']])){
                            $msg = 'لطفا انشعابات موجود در زمین یا اقامت گاه را به درستی وارد کنید';
                            $default = validate_special_data($data[$fields['id']], 'checkbox');
                            if ($default[0] != 'true'){
                                $result_validate['do'] = 'false';
                                $result_validate['notify']['msg'] = $msg;
                                $result_validate['notify']['type'] = 'danger';
                                $result['data'] = $result_validate;
                                die(json_encode($result));
                            }
                        }
                        break;
                    case 'number_of_room' :
                        if ( $data[$fields['id']] != "less_than_3" and $data[$fields['id']] != "3_to_6" and $data[$fields['id']] != "more_than_6"){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا تعداد اتاق را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'wc_and_bathroom' :
                        if ( $data[$fields['id']] != "one" and $data[$fields['id']] != "two" and $data[$fields['id']] != "three"){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا تعداد حمام و سرویس را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'available_places' :
                        if(is_array($data[$fields['id']])){
                            $msg = 'لطفا فضای موجود اقامت گاه را به درستی وارد کنید';
                            $default = validate_special_data($data[$fields['id']], 'checkbox');
                            if ($default[0] != 'true'){
                                $result_validate['do'] = 'false';
                                $result_validate['notify']['msg'] = $msg;
                                $result_validate['notify']['type'] = 'danger';
                                $result['data'] = $result_validate;
                                die(json_encode($result));
                            }
                        }
                        break;
                    case 'number_of_staffs' :
                        if ($data[$fields['id']] != "0" and $data[$fields['id']] != "1_to_5" and $data[$fields['id']] != "5_to_10" and $data[$fields['id']] != "more_than_10"){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا تعداد پرسنل مشغول در اقامت گاه را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'local_tour' :
                        if ( !( $data[$fields['id']] != "" ) and !($data[$fields['id']] != null) ){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا امکان برگزاری تور محلی و برپایی نمایشگاه بومی را وارد کنید در صورت عدم امکان نیز توضیحات را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'handicrafts' :
                        if ( !( $data[$fields['id']] != "" ) and !($data[$fields['id']] != null) ){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا صنایع موجود در منطقه را وارد کنید در صورت عدم وجود توضیحات را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    case 'tourist_attractions' :
                        if ( !( $data[$fields['id']] != "" ) and !($data[$fields['id']] != null) ){
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = 'لطفا جاذبه های گردشگری را وارد کنید در صورت عدم وجود توضیحات را وارد کنید';
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }
                        break;
                    default:
                        $default = validate_special_data($data[$fields['id']],$fields['id']);
                        if($default[0] != true) {
                            //var_dump($data[$fields['id']]."  ".$fields['id']);
                            $result_validate['do'] = 'false';
                            $result_validate['notify']['msg'] = $default[1];
                            $result_validate['notify']['type'] = 'danger';
                            $result['data'] = $result_validate;
                            die(json_encode($result));
                        }  
                        break;
                }
                if(is_array($data[$fields['id']])){
                    $get_data[$fields['id']] = serialize($data[$fields['id']]);
                }
                else{
                    $get_data[$fields['id']] = $data[$fields['id']];
                }
            }
            $get_data['force_user_active'] = 'true';
            $get_data['status'] = '1';
            $get_data['grade'] = '5';
            require_once($f3->get('MODEL').'/UserModel.php');
            $UserModel = new UserModel;
            $user_save = $UserModel->save_user_data($f3->get('auth')['id'], $get_data);
            if($user_save[0] == false){
                $result = array(
                    false,
                    $user_save[1]
                );
		        die(json_encode($result));
            }
            else{
                $result_validate['do'] = 'true';
                $result_validate['notify']['msg'] = 'درخواست شما با موفقیت انجام شد';
                $result_validate['notify']['type'] = 'success';
                $result['data'] = $result_validate;
                die(json_encode($result));
            }
	    }
	    else{
	        $result['validate']   = 'true';
            $result['force_data'] = $f3->get('auth')['force_data'];
	        $result['token']      = $f3->get('enc_token');
	        $result['data']       = $f3->get('force_user_meta');
	        die(json_encode($result));
	    }
	}
    /***********************************************************************/
	/*                                                                     */
	/*       users function for search|insert users in `/users` route      */
	/*                                                                     */
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
                $result['token']      = $f3->get('enc_token');
                $result['validate']   = 'true';
                $result['force_data'] = $f3->get('auth')['force_data'];
                $result['user_type'] = $f3->get('user_Acl');
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
	/*                                                                     */
	/*     user function for get|edit users data in `/user/@user` route    */
	/*                                                                     */
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
                $result['token']      = $f3->get('enc_token');
                $result['validate']   = 'true';
                $result['force_data'] = $f3->get('auth')['force_data'];
                $result['user_type'] = $f3->get('user_Acl');
                break;
            default :
                if(is_numeric($user_id)){
                    require_once($f3->get('MODEL').'/UserModel.php');
                    $UserModel = new UserModel;
                    $result['data'] = $UserModel->get_user_data($user_id);
                    $result['token']      = $f3->get('enc_token');
                    $result['validate']   = 'true';
                    $result['force_data'] = $f3->get('auth')['force_data'];
                    $result['user_type'] = $f3->get('user_Acl');
                }
                break;
        }
        die(json_encode($result));
    }
    /***********************************************************************/
	/*                                                                     */
	/*      profile function for user profile data in `/profile` route     */
	/*                                                                     */
	/***********************************************************************/
	public function profile($f3){
        require_once($f3->get('MODEL').'/UserModel.php');
        $UserModel = new UserModel;
        $result['data'] = $UserModel->get_user_data($f3->get('auth')['id']);
        $result['token']      = $f3->get('enc_token');
        $result['validate']   = 'true';
        $result['force_data'] = $f3->get('auth')['force_data'];
        $result['user_type'] = $f3->get('user_Acl');
        die(json_encode($result));
	}
}