<?php
//if(!defined('GENERAL_PHP_FRAMEWORK')){exit('Access Denied');}
class CCache{
	/**
	 * @var cache
	 */
	protected static $db = NULL;
	protected static $instances = array();
    protected static $mc  = null;
    var $smarty;
    var $survive_time = 600;
	protected function __construct($cache = false , $survive = 600){
		$this->smarty = new CSmarty();
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
}
