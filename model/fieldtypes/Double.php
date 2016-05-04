<?php
/**
 *
 * @package framework
 * @subpackage model
 */
class Double extends DBFloat {

	public function requireField() {
		// HACK: MSSQL does not support double so we're using float instead
		// @todo This should go into MSSQLDatabase ideally somehow
		if(DB::getConn() instanceof MySQLDatabase) {
			DB::requireField($this->tableName, $this->name, "double");
		} else {
			DB::requireField($this->tableName, $this->name, "float");
		}
	}
}
