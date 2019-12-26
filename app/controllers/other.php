<?php	
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
?>
