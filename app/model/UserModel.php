<?php
use DB\SQL\ggn_Mapper;
require_once('MainModel.php');
class UserModel extends MainModel{
    /***************************************************** register function *****************************************************/
	function register($get_user,$by = "username", $get_type = "client", $get_active = "true"){
	    $users     = new ggn_Mapper($this->dbs, 'users_ggn');
	    $persons   = new ggn_Mapper($this->dbs, 'persons_ggn');
	    $person_meta   = new ggn_Mapper($this->dbs, 'person_meta_ggn');
	    $user_name = "";
	    switch ($by) {
        	case 'username':
        	    if($get_user['username'] == ""){
        	        return array(false,1);
        	    }
                $user = $users->load(array('user_login = ?',$get_user['username']));
                if($user){
                    return array(false,2);
                }
                $users->reset();
        	    $user_name = $get_user['username'];
        		break;
        	case 'mobile':
        	    if($get_user['mobile'] == ""){
                    return array(false,3);
        	    }
        	    $person = $persons->load(array('per_mobile = ?',$get_user['mobile']));
        	    if($person){
        	        return array(false,4);
        	    }
        	    $persons->reset();
        	    $user_name = $get_user['mobile'];
        		break;
        	case 'email':
        	    if($get_user['email'] == ""){
                    return array(false,5);
        	    }
        	    $person = $persons->load(array('per_email = ?',$get_user['email']));
        	    if($person){
                    return array(false,6);
        	    }
        	    $persons->reset();
        	    if (!filter_var($get_user['email'], FILTER_VALIDATE_EMAIL)) {
                    return array(false,7);
                }
        	    $user_name = $get_user['email'];
        		break;
        	case 'complate':
        	    if($get_user['email'] == "" or $get_user['mobile'] == "" or $get_user['username'] == ""){
                    return array(false,8);
        	    }
        	    $user = $users->load(array('user_login = ?',$get_user['username']));
        	    if($user){
                    return array(false,2);
                }
                $users->reset();
                $person = $persons->load(array('per_mobile = ?',$get_user['mobile']));
                if($person){
                    return array(false,4);
        	    }
        	    $persons->reset();
        	    $person = $persons->load(array('per_email = ?',$get_user['email']));
        	    if($person){
                    return array(false,6);
        	    }
        	    $persons->reset();
        	    $user_name = $get_user['username'];
        		break;
        }
        if ($get_user['Fname'] == "" or $get_user['Lname'] == "") {
	        return array(false,9);
	    }
	    if ($get_user['password'] && $get_user['confirm']) {
	        if ($get_user['password'] != $get_user['confirm']){
	            return array(false,10);
	        }
	    } else {
	        return array(false,11);
	    }
	    $users->reset();
	    $users->ggn_change_db($this->dbm);
	    $users->user_login = $user_name;
	    $users->user_pass  = md5($get_user['password']);
	    $users->user_type  = $get_type;
	    $users->user_react_routes = serialize(array('/profile'));
	    $users->active     = $get_active;
	    $user = $users->save();
	    $persons->reset();
	    $persons->ggn_change_db($this->dbm);
	    $persons->per_user_id= $user->user_id;
	    $persons->per_Fname  = $get_user['Fname'];
	    $persons->per_Lname  = $get_user['Lname'];
	    if($get_user['mobile'])     $persons->per_mobile = $get_user['mobile'];
	    if($get_user['email'])      $persons->per_email  = $get_user['email'];
	    $persons->save();
	    
	    $meta_names = $this->f3->get('force_user_meta');
	    $meta_values = array();
	    foreach($meta_names as $key => $meta_name) {
	        if($meta_names['id'] != 'mobile' or $meta_names['id'] != 'email'){
	            $meta_values[$meta_name['id']] = '';   
	        }
	    }
	    $meta_values['status'] = 0;
	    $meta_values['grade'] = 0;
	    $meta_names = $this->save_user_data($user->user_id ,$meta_values);
	    return array(true);
	}
	/************************************** save_user_meta function to save single meta **************************************/
	function save_user_meta($user_id, $name, $value){
		$persons   = new ggn_Mapper($this->dbs, 'persons_ggn');
		$person	= $persons->load ( array( 'per_user_id = ?', $user_id ) );
		$person->ggn_change_db($this->dbm);
		if($person){
			switch ($name){
				case 'personality':
					$person->per_personality = $value;
					$person->save();
					return(true);
					break;
				case 'email':
					$person->per_email = $value;
					$person->save();
					return(true);
					break;
				case 'tell':
					$person->per_tell = $value;
					$person->save();
					return(true);
					break;
				case 'address':
					$person->per_address = $value;
					$person->save();
					return(true);
					break;
				case 'mobile':
					$person->per_mobile = $value;
					$person->save();
					return(true);
					break;
				case 'state':
					$person->per_state = $value;
					$person->save();
					return(true);
					break;
				case 'city':
					$person->per_city = $value;
					$person->save();
					return(true);
					break;
				case 'national_id':
					$person->per_national_id = $value;
					$person->save();
					return(true);
					break;
				case 'job':
					$person->per_job = $value;
					$person->save();
					return(true);
					break;
				case 'education':
					$person->per_education = $value;
					$person->save();
					return(true);
					break;
				case 'pic':
					$person->per_pic = $value;
					$person->save();
					return(true);
					break;
				default :
					$persons_meta = new ggn_Mapper($this->dbs, 'person_meta_ggn');
					$person_meta  = $persons_meta->load ( array( 'per_meta_ref = ? AND per_meta_name = ?' ,$user_id ,$name ) );
					if(! $person_meta){
						$person_meta->reset();
					}
					$person_meta->ggn_change_db($this->dbm);
					$person_meta->per_meta_ref   = $user_id;
					$person_meta->per_meta_name  = $name;
					$person_meta->per_meta_value = $value;
					$person_meta->save();
					break;
			}
		}else { return array(false,1); }
	}
	
	/************************************** save_user_data function to save all data **************************************/
    function save_user_data($user_id, $get_fields = ""){
    //single_all_datas
        $persons   = new ggn_Mapper($this->dbs, 'persons_ggn');
        $person    = $persons->load ( array( 'per_user_id = ?', $user_id ) );
        $person->ggn_change_db($this->dbm);
        if($person){
            foreach ($get_fields as $name => $value){
                switch ($name){
                    case 'personality':
                        $person->per_personality = $value;
                        break;
                    case 'email':
                        $person->per_email = $value;
                        break;
                    case 'tell':
                        $person->per_tell = $value;
                        break;
                    case 'address':
                        $person->per_address = $value;
                        break;
                    case 'mobile':
                        $person->per_mobile = $value;
                        break;
                    case 'state':
                        $person->per_state = $value;
                        break;
                    case 'city':
                        $person->per_city = $value;
                        break;
                    case 'national_id':
                        $person->per_national_id = $value;
                        break;
                    case 'job':
                        $person->per_job = $value;
                        break;
                    case 'education':
                        $person->per_education = $value;
                        break;
                    case 'pic':
                        $person->per_pic = $value;
                        break;
                    default :
                        $persons_meta = new ggn_Mapper($this->dbs, 'person_meta_ggn');
                        $person_meta  = $persons_meta->load ( array( 'per_meta_ref = ? AND per_meta_name = ?' ,$user_id ,$name ) );
                        if(! $person_meta){
                            $persons_meta->reset();
                            $persons_meta->per_meta_ref   = $user_id;
                            $persons_meta->per_meta_name  = $name;
                        }
                        $persons_meta->ggn_change_db($this->dbm);
                        $persons_meta->per_meta_value = $value;
                        $persons_meta->save();
                        break;
                }
            }
            $person->save();
            return array(true);
        }else { return array(false,1); }
    }
    
	/************************************** get_user_meta function to get single meta **************************************/
	function get_user_meta($user_id, $name){
	    $persons   = new ggn_Mapper($this->dbs, 'persons_ggn');
        $person    = $persons->load ( array( 'per_user_id = ?', $user_id ) );
        if($person){
            switch ($name){
                case 'personality':
    	            return($person->per_personality);
    	            break;
                case 'email':
    	            return($person->per_email);
    	            break;
                case 'tell':
    	            return($person->per_tell);
    	            break;
                case 'address':
    	            return($person->per_address);
    	            break;
                case 'mobile':
    	            return($person->per_mobile);
    	            break;
                case 'state':
    	            return($person->per_state);
    	            break;
                case 'city':
    	            return($person->per_city);
    	            break;
                case 'national_id':
    	            return($person->per_national_id);
    	            break;
                case 'job':
    	            return($person->per_job);
    	            break;
                case 'education':
    	            return($person->per_education);
    	            break;
                case 'pic':
    	            return($person->per_pic);
    	            break;
	            default :
                    $persons_meta = new ggn_Mapper($this->dbs, 'person_meta_ggn');
                    $person_meta  = $persons_meta->load ( array( 'per_meta_ref = ? AND per_meta_name = ?' ,$user_id ,$name ) );
                    if($person_meta){
                        return ($person_meta->per_meta_value);
                    }
                    else{ return array(false,2); }
	                break;
    	    }
        }else { return array(false,1); }
	}
	
	/************************************** get_user_data function to get all data **************************************/
	function get_user_data($user_id){
	    $person = $this->dbs->exec( 'SELECT * FROM persons_ggn WHERE per_user_id = '.$user_id );
	    $person = $person[0];
	    $persons_meta = new ggn_Mapper( $this->dbs, 'person_meta_ggn' );
	    $person_meta = $persons_meta->find (array ( 'per_meta_ref = ?', $user_id ) );
	    foreach($person_meta as $key => $meta){
	        if(unserialize($meta['per_meta_value']) != false){
	            $person[$meta['per_meta_name']] = unserialize($meta['per_meta_value']);
	        }else{
	            $person[$meta['per_meta_name']] = $meta['per_meta_value'];
	        }
	    }
	    return($person);
	}
}