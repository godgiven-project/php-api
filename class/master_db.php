<?php
/*
	Copyright (c) 2009-2017 F3::Factory/Bong Cosca, All rights reserved.

	This file is part of the Fat-Free Framework (http://fatfreeframework.com).

	This is free software: you can redistribute it and/or modify it under the
	terms of the GNU General Public License as published by the Free Software
	Foundation, either version 3 of the License, or later.

	Fat-Free Framework is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	General Public License for more details.

	You should have received a copy of the GNU General Public License along
	with Fat-Free Framework.  If not, see <http://www.gnu.org/licenses/>.

*/
namespace DB\SQL;
//! SQL data mapper
class ggn_Mapper extends Mapper {
	protected
		//! PDO wrapper
		$db,
		//! Database engine
		$engine,
		//! SQL table
		$source,
		//! SQL table (quoted)
		$table,
		//! Last insert ID
		$_id,
		//! Defined fields
		$fields,
		//! Adhoc fields
		$adhoc=[],
		//! Dynamic properties
		$props=[];
	function ggn_change_db($db_ggn){
	    $this->db = $db_ggn;
        $this->engine=$db_ggn->driver();
        if ($this->engine=='oci'){
            $table=strtoupper($table);
	    }
	}
}
?>