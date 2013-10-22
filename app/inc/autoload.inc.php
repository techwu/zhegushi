<?php

if(!function_exists("__autoload")){
	function __autoload($class){
		if(!class_exists($class) && strpos($class,"Model")!==false){
			require(APP_PATH . "model/".$class.".class.php");
			return true;
		}elseif(!class_exists($class) && strpos($class,"Cache")!==false){
			require(APP_PATH . "cache/".$class.".class.php");
			return true;
		}
	}
} 