<?php
use DB\SQL\ggn_Mapper;
class MainModel{
    protected $f3;
	protected $db;
	protected $dbm;
	protected $dbs;
	function __construct() {
		global $f3;
		$this->f3  = $f3;
		$this->db  = $f3->get('DB') ;
		$this->dbm = $f3->get('DBM');
		$this->dbs = $f3->get('DBS');
	}
}