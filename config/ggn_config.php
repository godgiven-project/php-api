<?php
/***************************************************************************************/
/*                                                                                     */
/*              ggn_config.php file is for all sites configuration                     */
/*                                                                                     */
/***************************************************************************************/
require_once('ggn_acl.php');
/****************** date_j.php file is for jalalian(iranina) date **********************/
require_once('class/date_j.php');

/********************** ghasedak.php file is for sending sms ***************************/
/*require_once('class/sms/ghasedak.php');*/

/******************* email_normal.php file is for sending email ************************/
require_once('class/email/email_normal.php');

/****************************** API url configuration **********************************/
$f3->set('main_url','sampel url:http://localhost/php-api/');
$f3->set('sec_url' ,'sampel url:http://localhost/php-api2/');
$f3->set('shortcut_icon',$f3->get('main_url').'favicon.png');

/*************************** API Main Database configuration ********************************/
$f3->set('f3_namedb','godgiven');
$f3->set('f3_userdb','phpmyadmin');
$f3->set('f3_pasdb' ,'1');
$f3->set('f3_portdb','3306');
$f3->set('f3_hostdb','localhost');

/***************************************************************************************/
/*                         API Master Database configuration                           */
/*            coment Main Database above and uncomment these two configuration         */
/*                    if you want two database for Master and Slave usage              */
/*                                                                                     */
/*                                 Master Configuration                                */
/*                                                                                     */
/* $f3->set('f3_namedbm','master_database_name');                                      */
/* $f3->set('f3_userdbm','database_user');                                             */
/* $f3->set('f3_pasdbm' ,'database_password');                                         */
/* $f3->set('f3_portdbm','database_port');                                             */
/* $f3->set('f3_hostdbm','database_host');                                             */
/*                                                                                     */
/*                                 Slave Configuration                                 */
/*                                                                                     */
/* $f3->set('f3_namedbs','slave_database_name');                                       */
/* $f3->set('f3_userdbs','database_user');                                             */
/* $f3->set('f3_pasdbs' ,'database_password');                                         */
/* $f3->set('f3_portdbs','database_port');                                             */
/* $f3->set('f3_hostdbs','database_host');                                             */
/***************************************************************************************/
$f3->set('DEBUG',3);
/******************* API MasterKey for session configuration ***************************/
$f3->set('security_key' ,'your security_key');

/***************************** API Other configuration *********************************/
$f3->set('I18n'		     , array());
$f3->set('ggn_LANGUAGE'	 ,'en_EN'); 
$f3->set('enable_upload' , true);
$f3->set('max_upload'	 ,'20000000'); /*  set max upload size per file allow to user  */
$f3->set('MODEL'         ,'app/model/'); /*           set model(s) path                */
$f3->set('AUTOLOAD'		 ,'app/controllers/'); /*      set controller(s) path
                                            more than one? separated by semicolon      */
$f3->set('upload_path'	 ,'upload/'); /*           set the upload path                 */
$f3->set('sms_api_key'	 ,'your sms api Key'); /*             set sms api key          */
$f3->set('Money_Unit'    ,'$'); /*  set every parameter as you want, like Money_Unit   */
require_once('class/translate/translate.php'); /*              read mo file(s)         */
?>
