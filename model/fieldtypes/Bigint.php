<?php
/**
 * Represents a signed 32 bit integer field.
 *
 * @edited by Clay
 * @package framework
 * @subpackage model
 * @see Int
 */
class Bigint extends Int {

	function __construct($name, $defaultVal = 0) {
		$this->defaultVal = is_int($defaultVal) ? $defaultVal : 0;

		parent::__construct($name);
	}

	function requireField() {
		$parts=Array('datatype'=>'bigint', 'precision'=>20, 'null'=>'not null', 'default'=>$this->defaultVal, 'arrayValue'=>$this->arrayValue);
		$values=Array('type'=>'bigint', 'parts'=>$parts);
		DB::requireField($this->tableName, $this->name, $values);
	}
}

?>
