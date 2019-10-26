<?php
/***********************************************************************************/
/************* ggn_load.php file contained primary functions ***********************/
/***********************************************************************************/

/******************************/
/****   DataBase Function *****/
/******************************/
if( !$f3->get('f3_namedbm') ){ $f3->set('f3_namedbm',$f3->get('f3_namedb')); }
if( !$f3->get('f3_userdbm') ){ $f3->set('f3_userdbm',$f3->get('f3_userdb')); }
if( !$f3->get('f3_pasdbm' ) ){ $f3->set('f3_pasdbm' ,$f3->get('f3_pasdb' )); }
if( !$f3->get('f3_portdbm') ){ $f3->set('f3_portdbm',$f3->get('f3_portdb')); }
if( !$f3->get('f3_hostdbm') ){ $f3->set('f3_hostdbm',$f3->get('f3_hostdb')); }
if( !$f3->get('f3_namedbs') ){ $f3->set('f3_namedbs',$f3->get('f3_namedb')); }
if( !$f3->get('f3_userdbs') ){ $f3->set('f3_userdbs',$f3->get('f3_userdb')); }
if( !$f3->get('f3_pasdbs' ) ){ $f3->set('f3_pasdbs' ,$f3->get('f3_pasdb' )); }
if( !$f3->get('f3_portdbs') ){ $f3->set('f3_portdbs',$f3->get('f3_portdb')); }
if( !$f3->get('f3_hostdbs') ){ $f3->set('f3_hostdbs',$f3->get('f3_hostdb')); }
$f3->set('DB',new DB\SQL(
	'mysql:host='.$f3->get('f3_hostdb').';port='.$f3->get('f3_portdb').';dbname='.$f3->get('f3_namedb'),
	$f3->get('f3_userdb'),
	$f3->get('f3_pasdb')
));
$f3->set('DBM',new DB\SQL(
	'mysql:host='.$f3->get('f3_hostdbm').';port='.$f3->get('f3_portdbm').';dbname='.$f3->get('f3_namedbm'),
	$f3->get('f3_userdbm'),
	$f3->get('f3_pasdbm')
));
$f3->set('DBS',new DB\SQL(
	'mysql:host='.$f3->get('f3_hostdbs').';port='.$f3->get('f3_portdbs').';dbname='.$f3->get('f3_namedbs'),
	$f3->get('f3_userdbs'),
	$f3->get('f3_pasdbs')
));
function validate_data($data = ""){
	if ($data){
		foreach ($data as $key => $fild) {
			if(! is_array($fild)){
				$data[$key] = strip_tags( (trim($fild)) );
				$data[$key] = filter_var($fild, FILTER_SANITIZE_STRING);
			}else{
				foreach($fild as $key2 => $fild2){
					$data[$key][$key2] = strip_tags( (trim($fild2)) );
					$data[$key][$key2] = filter_var($fild2, FILTER_SANITIZE_STRING);
				}
			}
		}
	}
	return $data;
}
/***************************************************/
/**** validate string(s) array for XSS or more *****/
/***************************************************/
function validate_special_data($value = "", $validate_type = 'type') {
    switch($validate_type){
        case 'mobile' :
            $filtered_phone_number = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
            $phone_to_check = str_replace("-", "", $filtered_phone_number);
            if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
                return array(false,__("phone number isn't correct"));
            } 
            else {
                return array(true);
            }
            break;
        case 'email' :
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return array(false,__("email isn't correct"));
            }
            else {
                return array(true);
            }
            break;
        case 'personality' :
            if ($value == "single" or $value == "company"){
                return array(true);
            }
            else {
                return array(false,__("Please select one of options 'single' or 'company'"));
            }
            break;
        case 'tell' :
            $filtered_phone_number = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
            $phone_to_check = str_replace("-", "", $filtered_phone_number);
            if (strlen($phone_to_check) < 7 || strlen($phone_to_check) > 14) {
                return array(false,__("phone number isn't correct"));
            } 
            else {
                return array(true);
            }
        case 'national_id' :
            if(is_numeric($value)){
                if(strlen($value) < 9 || strlen($value) > 10) {
                    return array(false,__("national id isn't correct"));
                }
                else{
                    return array(true);
                }
            }
            else{
                return array(false,__("national id isn't correct"));
            }
            break;
        case 'education' : 
            $educ = $f3->get('educ');
            if (array_key_exists($value,$educ)){
                return array(true);
            }
            else{
                return array(false,__("education isn't correct"));
            }
            break;
        case 'pic' :
            // https://www.w3schools.com/php/filter_validate_url.asp
            return true;
            break;
        case 'checkbox':
            foreach($value as $key => $single_val){
                if ( $single_val != 'true'){
                    if($single_val != 'false' ){
                        return array(false);
                    }
                }
            }
            return array(true);
            break;
        default:
            return array(true);
            break;
    }
}
/*************************/
/**** upload scripts *****/
/*************************/
if($f3->get('enable_upload')){
	function ggn_check_file($filetemp,$file_size = 0,$file_name = 0){
		if($filetemp == ""){
			return 0;
		}
		$acceptedMime=array(
			/* picther	  */'image/jpeg' ,'image/png','image/bmp','image/gif' , 'image/x-icon' , 'image/tiff' ,
			/* word		  */'application/msword' , 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ,
			/* Exel		  */'application/excel' , 'application/x-excel' , 'application/vnd.ms-excel' ,  'application/x-msexcel' , 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ,
			/* PDF 		  */'application/pdf', 'application/x-pdf',
			/* solid work */'application/pro_eng',
			/* ZIP        */'application/zip' , 'application/x-7z-compressed' , 'application/x-rar-compressed' ,
			/* TEXT       */'text/plain' ,
			/* video      */'video/quicktime' , ''
		);
		global $f3;
		$max_upload_filesize=$f3->get('max_upload');
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mimetype = $finfo->file($filetemp);
		if(in_array($mimetype, $acceptedMime, true) === true && $file_size<=$max_upload_filesize){
			return 1;
		}
		else{
			return 0;
		}
	}
	function ggn_upload_files($files , $mod_r = "both"){
		if(empty($files)){
			return 0;
		}
		else{
			$i=0;
			$return  = array();
			$message = array();
			while(!empty($files['name'][$i])){
				$result=ggn_check_file($files['tmp_name'][$i],$files['size'][$i],$files['name'][$i]);
				if($result==0){
					$return[$i] = false;
					$message[$i] = "فایل ".$files['name']." ایراد دارد";
					$i++;continue;
				}else{
					$t=time();
					$t=date("Y/m",$t)."/";
					global $f3;
					$timefolder = $f3->get('upload_path').$t;
					if (!is_dir($timefolder) && !mkdir($timefolder, 0777, true)){
						return 0;
					}
					$random_filename=$timefolder.$files['name'][$i];
					if(file_exists($random_filename)){
						$exist = file_exists($random_filename);
						$file_count = 1;
						while($exist ){
							$t=time();
							$t=date("Y/m",$t)."/";
							$timefolder = $f3->get('upload_path').$t;
							$random_filename = $timefolder.$file_count."_".$files['name'][$i];
							$exist = file_exists($random_filename);
							$file_count++;
						}
					}
					move_uploaded_file($files['tmp_name'][$i],$random_filename);
					$return[$i]  = $random_filename;
					$message[$i] = "فایل ".$files['name']." آپلود شد";
					$last_t= $t;
					$i++;
				}
			}
			if($mod_r == "both"){
				return $return;
			}
		}
	}
}