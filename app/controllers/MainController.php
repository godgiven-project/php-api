<?php
use DB\SQL\ggn_Mapper;
class MainController{
	protected $f3;
	protected $db;
	protected $dbm;
	protected $dbs;
	protected $user_login;
	protected $user_Acl;
	function __construct() {
		global $f3;
		$this->f3 = $f3;
		$this->db  = $f3->get('DB') ;
		$this->dbm = $f3->get('DBM');
		$this->dbs = $f3->get('DBS');
		$this->user_login = $f3->get('SESSION.auth');
		$this->user_Acl = $f3->get('user_Acl');
	}
	function ggn_log($do , $log_user_id = 0, $log_user_object = 0 ,$qr_ID = 0){
		global $f3;
		$logs_ms =  $f3->get('logs_ms');
		$db = $f3->get('DBM');
		$log = new ggn_Mapper($db , 'users_log_ggn');
		$log->log_user_id		= $log_user_id;
		$log->log_user_object	= $log_user_object;
		$log->log_IP			= $_SERVER['REMOTE_ADDR'];
		$log->log_QR_id			= $qr_ID;
		$log->log_do = $do;
		return $log->save();
	}
	function ggn_echo($show_path_ggn){
		$view=new View;
		//return $view->render($this->f3->get('UI').$show_path_ggn);
		return $view->render($show_path_ggn);
	}
}