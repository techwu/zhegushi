<?php
//if(!defined('GENERAL_PHP_FRAMEWORK')){exit('Access Denied');}
class CModel{
	/**
	 * @var pdoDB
	 */
	protected static $db = NULL;
	protected static $instances = array();
    protected static $mc  = null;
	var $fields  = false;
    var $table  = false;
    var $survive_time = 600;
	protected function __construct( $fields = false , $table = false , $cache = false , $survive = 600){
		$this->fields = $fields;
		$this->table = $table;
		if( $cache ){
			/*
			 * memcache 使用
			 */
			$this->survive_time = $survive ;
			if(self::$mc == null){
				global $MEMCACHE;
				self::$mc = $MEMCACHE;
			}
		}
		if(self::$db == null){
			global $DB;
			self::$db = $DB;
		}
	}
	
	protected function __destruct(){	}
	
	protected static function getInstance($class){
		if( !isset(self::$instances[$class]) ) {
			self::$instances[$class] = new $class();
		}
		return self::$instances[$class];
	}
	
	public function processFields( $fields = '' ){
		if ( empty($fields) || $fields == '*' ){
			$fields = '*';
		}else{
			$fields = explode('|', $fields);
			$fields = arrayIntersect($this->fields , $fields);
			$fields = implode(',', $fields);
		}
		return $fields;
	}
	public function addNewData($iExtData = false , $table = false){
		if (empty($table)){
			$table = $this->table ;
		}
		if (empty($table)){
			return false;
		}
		if(is_array($iExtData)){
			$field = arrayIntersect($this->fields , array_keys($iExtData));
			if ( empty($field) ){
				return false;
			}
			$res = self::$db->inputField($field , $iExtData , $table , true );
			return $res;
		}else{
			return false;
		}
	}
}
