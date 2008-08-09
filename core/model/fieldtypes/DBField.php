<?php
/**
 * Single field in the database.
 * Every field from the database is represented as a sub-class of DBField.  In addition to supporting
 * the creation of the field in the database,
 * 
 * @package sapphire
 * @subpackage model
 */
abstract class DBField extends ViewableData {
	
	protected $value;
	
	protected $tableName;
	
	protected $name;
	
	/**
	 * @var $default mixed Default-value in the database.
	 * Might be overridden on DataObject-level, but still useful for setting defaults on
	 * already existing records after a db-build.
	 */
	protected $defaultVal;
	
	function __construct($name) {
		$this->name = $name;
		
		parent::__construct();
	}
	
	/**
	 * Create a DBField object that's not bound to any particular field.
	 * Useful for accessing the classes behaviour for other parts of your code.
	 */
	static function create($className, $value, $name = null) {
		$dbField = Object::create($className, $name);
		$dbField->setValue($value);
		return $dbField;
	}
	
	function setVal($value, $record = null) {
		return $this->setValue($value);
	}
	
	/**
	 * Set the value on the field.
	 * Optionally takes the whole record as an argument,
	 * to pick other values.
	 *
	 * @param mixed $value
	 * @param array $record
	 */
	function setValue($value, $record = null) {
		$this->value = $value;
	}
	
	/**
	 * Determines if the field has a value which
	 * is not considered to be 'null' in
	 * a database context.
	 * 
	 * @return boolean
	 */
	function hasValue() {
		return ($this->value);
	}
	
	/**
	 * Prepare the current field for usage in a 
	 * database-manipulation (works on a manipulation reference).
	 * 
	 * Make value safe for insertion into
	 * a SQL SET statement by applying addslashes() - 
	 * can also be used to apply
	 * special SQL-commands to the raw value
	 * (e.g. for GIS functionality).
	 * 
	 * @param array $manipulation
	 */
	function writeToManipulation(&$manipulation) {
		$manipulation['fields'][$this->name] = $this->hasValue() ? "'" . addslashes($this->value) . "'" : $this->nullValue();
	}
	
	/**
	 * Add custom query parameters for this field,
	 * mostly SELECT statements for multi-value fields. 
	 * 
	 * By default, the ORM layer does a
	 * SELECT <tablename>.* which
	 * gets you the default representations
	 * of all columns.
	 *
	 * @param Query $query
	 */
	function addToQuery(&$query) {}
	
	function setTable($tableName) {
		$this->tableName = $tableName;
	}
	
	function forTemplate() {
		return $this->value;
	}

	function HTMLATT() {
		return Convert::raw2htmlatt($this->value);
	}
	function URLATT() {
		return urlencode($this->value);
	}
	function RAWURLATT() {
		return rawurlencode($this->value);
	}
	function ATT() {
		return Convert::raw2att($this->value);
	}
	
	function RAW() {
		return $this->value;
	}
	
	function JS() {
		return Convert::raw2js($this->value);
	}
	
	function HTML(){
		return Convert::raw2xml($this->value);
	}
	
	function XML(){
		return Convert::raw2xml($this->value);
	}
	
	/**
	 * Returns the value to be set in the database to blank this field.
	 * Usually it's a choice between null, 0, and ''
	 */
	function nullValue() {
		return "null";
	}

	/**
	 * Saves this field to the given data object.
	 */
	function saveInto($dataObject) {
		$fieldName = $this->name;
		if($fieldName) {
			$dataObject->$fieldName = $this->value;
		} else {
			user_error("DBField::saveInto() Called on a nameless '" . get_class($this) . "' object", E_USER_ERROR);
		}
	}
	
	/**
	 * Returns a FormField instance used as a default
	 * for form scaffolding.
	 *
	 * @usedby {@link SearchContext}
	 * @usedby {@link ModelAdmin}
	 * @usedby {@link DataObject::scaffoldFormFields()}
	 * 
	 * @param string $title Optional. Localized title of the generated instance
	 * @return FormField
	 */
	public function scaffoldFormField($title = null) {
		$field = new TextField($this->name, $title);
		
		return $field;
	}
	
	/**
	 * Returns a FormField instance used as a default
	 * for searchform scaffolding.
	 *
	 * @usedby {@link SearchContext}
	 * @usedby {@link ModelAdmin}
	 * @usedby {@link DataObject::scaffoldFormFields()}
	 * 
	 * @param string $title Optional. Localized title of the generated instance
	 * @return FormField
	 */
	public function scaffoldSearchField($title = null) {
		return $this->scaffoldFormField($title);
	}
	
	/**
	 * @todo documentation
	 * 
	 * @todo figure out how we pass configuration parameters to
	 * search filters
	 *
	 * @return SearchFilter
	 */
	public function defaultSearchFilter() {
		return new ExactMatchSearchFilter();
	}
	
	/**
	 * Add the field to the underlying database.
	 */
	abstract function requireField();
	
	function debug() {
		return <<<DBG
<ul>
	<li><b>Name:</b>{$this->name}</li>
	<li><b>Table:</b>{$this->tableName}</li>
	<li><b>Value:</b>{$this->value}</li>
</ul>
DBG;
	}
}
?>