<?php
use DB\SQL\ggn_Mapper;
require_once('MainModel.php');
class UserModel extends MainModel{
	/***************************************************** register function *****************************************************/
	function register($get_user,$by = "username", $get_type = 3, $get_active = "true"){
		$users	 = new ggn_Mapper($this->dbs, 'users_ggn');
		$persons   = new ggn_Mapper($this->dbs, 'persons_ggn');
		$user_name = "";
        if ($get_user['Fname'] == "" or $get_user['Lname'] == "") {
            return array(false,"Please enter your full name.");
        }
		switch ($by) {
			case 'username':
				if($get_user['username'] == ""){
					return array(false,"Please enter your username.");
				}
				$user = $users->load(array('user_login = ?',$get_user['username']));
				if($user){
					return array(false,"This username already exists.");
				}
				$users->reset();
				$user_name = $get_user['username'];
				break;
			case 'mobile':
				if($get_user['mobile'] == ""){
					return array(false,"Please enter your phone number.");
				}
				$person = $persons->load(array('per_mobile = ?',$get_user['mobile']));
				if($person){
					return array(false,"This phone number already exists.");
				}
				$persons->reset();
				$user_name = $get_user['mobile'];
				break;
			case 'email':
				if($get_user['email'] == ""){
					return array(false,"Please enter your email address.");
				}
				$person = $persons->load(array('per_email = ?',$get_user['email']));
				if($person){
					return array(false,"This email address already exists.");
				}
				$persons->reset();
				if (!filter_var($get_user['email'], FILTER_VALIDATE_EMAIL)) {
					return array(false,"Please enter correct email.");
				}
				$user_name = $get_user['email'];
				break;
			case 'complate':
				if($get_user['email'] == "" or $get_user['mobile'] == "" or $get_user['username'] == ""){
					return array(false,"Please enter your information.");
				}
				$user = $users->load(array('user_login = ?',$get_user['username']));
				if($user){
					return array(false,"This username already exists.");
				}
				$users->reset();
				$person = $persons->load(array('per_mobile = ?',$get_user['mobile']));
				if($person){
					return array(false,"This phone number already exists.");
				}
				$persons->reset();
				$person = $persons->load(array('per_email = ?',$get_user['email']));
				if($person){
					return array(false,"This email address already exists.");
				}
				$persons->reset();
				$user_name = $get_user['username'];
				break;
		}
		if ($get_user['password'] && $get_user['confirm']) {
			if ($get_user['password'] != $get_user['confirm']){
				return array(false,"Your password and confirm password do not match.");
			}
		} else {
			return array(false,"Pleas enter your password.");
		}
		$persons->reset();
		$persons->ggn_change_db($this->dbm);
		$persons->per_Fname  = $get_user['Fname'];
		$persons->per_Lname  = $get_user['Lname'];
		if($get_user['mobile'])   $persons->per_mobile = $get_user['mobile'];
		if($get_user['email'])    $persons->per_email  = $get_user['email'];
		$person = $persons->save();

		$users->reset();
		$users->ggn_change_db($this->dbm);
		$users->user_login = $user_name;
        $users->user_person_id = $person->per_id;
		$users->user_pass  = md5($get_user['password']);
		$users->user_permission  = $get_permission;
		$users->active	 = $get_active;
		$user = $users->save();
		
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
			}
		}else { return array(false,1); }
	}
	
	/************************************** save_user_data function to save all data **************************************/
	function save_user_data($user_id, $get_fields = ""){
	//single_all_datas
		$persons   = new ggn_Mapper($this->dbs, 'persons_ggn');
		$person	= $persons->load ( array( 'per_user_id = ?', $user_id ) );
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
				}
			}
			$person->save();
			return array(true);
		}else { return array(false,1); }
	}
	
	/************************************** get_user_meta function to get single meta **************************************/
	function get_user_meta($user_id, $name){
		$persons   = new ggn_Mapper($this->dbs, 'persons_ggn');
		$person	= $persons->load ( array( 'per_user_id = ?', $user_id ) );
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
			}
		}else { return array(false,1); }
	}
}