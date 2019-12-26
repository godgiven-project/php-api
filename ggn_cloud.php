<?php
/***************************************************************************************/
/*************** ggn_cloud.php file is a cloud for all global variables ****************/
/*****************************************************************/
/****************** date_j.php file is for jalalian(iranina) date **********************/
require_once('class/date_j.php');
/********************** ghasedak.php file is for sending sms ***************************/
/*require_once('class/sms/ghasedak.php');*/

/******************* email_normal.php file is for sending email ************************/
require_once('class/email/email_normal.php');
/***************************************************************************************/
$place_ggn = file_get_contents('class/json/Province.json'); 
$place_ggn = json_decode($place_ggn, true);
$state_ggn = array();
$city_ggn  = array(); 
foreach ($place_ggn as $key => $state) {
	$state_ggn[] = array($state['name'],$state['id']);
	$city_ggn[$state['id']]  = $state['Cities'];
}
$f3->set('state', $state_ggn);
$f3->set('city' , $city_ggn);

/*****************************/
/******   Educations   *******/
/*****************************/
$educ = array(
	"No formal education"                => __("No formal education"),
	"Primary education"                  => __("Primary education"),
	"Secondary education or high school" => __("Secondary education or high school"),
	"GED or Vocational qualification"    => __("GED or Vocational qualification"),
	"Bachelor's degree"                  => __("Bachelor's degree"),
	"Master's degree"                    => __("Master's degree"),
	"Doctorate"                          => __("Doctorate"),
	"Doctorate and Higher"               => __("Doctorate or higher")
);
$f3->set('educ', $educ);

/*****************************/
/*****  force user meta  *****/
/*****************************/
$f3->set('force_user_meta',array(
    array(
        'id'    =>  'full_name',
        'label' =>  'نام و نام خانوادگی / نام شرکت‬',
        // 'desc'  =>  'لطفا نام مالک مکان را وارد نمایید',
        'type'  =>  'text',
    ),
    array(
        'id'    => 'personality',
        'label' => 'شخصیت',
        // 'desc'  => 'حقیقی/حقوقی',
        'type'  => 'select',
        'choices'     => [         
            array(
            	'value'       => 'single',
            	'label'       => 'حقیقی'
            ),
            array(
            	'value'       => 'company',
            	'label'       => 'حقوقی'
            ),
        ]
    ),
    array(
        'id'    => 'available_places',
        'label' => 'فضاهای موجود',
        // 'desc'  => '‫گاه‬ ‫اقامت‬ ‫در‬ ‫موجود‬ ‫فضاهای‬ اعم از‬‫‬‬‬',
        'type'  => 'checkbox',
        'choices'     => [         
            array(
            	'value'       => 'kitchen',
            	'label'       => 'آشپزخانه'
            	),
            array(
            	'value'       => 'pantry',
            	'label'       => 'آبدارخانه'
            ),
            array(
            	'value'       => 'tea_room',
            	'label'       => 'فضای پذیرایی و چایخانه'
            ),
            array(
            	'value'       => 'show_room',
            	'label'       => '‫نمایش‬ ‫فضای‬'
            ),
        ]
    ),
));
?>