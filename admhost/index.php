<?php
/**
 * index.php
 * @author wumin.itea <wumin.itea@gmail.com>
 */
session_start();
date_default_timezone_set("PRC"); 
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_PARSE );
//echo base64_encode('/home/work/webdata/pictures/00/65/53//1278038979449.jpg');die();
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

define("GENERAL_PHP_FRAMEWORK", 1 ); //available the entry

define("ADMIN_PROGRAM" , 1);
//include the necessary libraries

require_once("./config/config.php");

require_once(APP_PATH."inc/preload.inc.php");

set_error_handler('tracking');

//decode the url request
$urlArray = URL_DIVERSIONER( $_SERVER["REQUEST_URI"] );
//print_r(USE_DOMAIN);die();

//controller class name;
$action = array_shift($urlArray);
//controller class method name;
$method = array_shift($urlArray);


//get global $_ZSESSION;


//isSessionExpire();
if( empty($action) ){
	$action = DEFAULT_ACTION_METHOD;
}
 // default method 
if( empty($method) ){
	$method = DEFAULT_METHOD_METHOD;
}

$pos = strpos( $method ,"ajax_");
if( $pos !== false && isset($_POST['submit_method']) && $_POST['submit_method'] == 'js' ){
	header('Content-Type: text/plain; charset=utf-8');
}else{
	header('Content-Type: text/html; charset=utf-8');
}

require_once( APP_PATH."inc/gobackurl.inc.php");
require_once( ADMIN_HOST_PATH."inc/validate.inc.php");
// the other parms 
$args = $urlArray; 
// var_dump($action , $method);die();
if(!empty($_POST) || !empty($_GET)){
	extraData($_POST);
	extraData($_GET);
}
// if( empty( $GLOBALS['SESS_USER_ID']  ) ){
// 	importer('corelib.ipban');
// 	$ipan = new ipban( $action , $method );
// 	$ipan->forbiddenCrawl();
// }
//get class file name and path

$classFile = APP_PATH . "admcontroller/" . $action . ".class.php";
$timeHelper = new TimeHelper();
$timeHelper->start();

if(file_exists($classFile)){
	require_once( $classFile );
	if(class_exists($action)){
		$t = new $action();
		
		$t->init_args($args);
		if($action == 'url') {
			$t->go($method);
		} else {
			$t->do_method($method);
		}
	}else{
		show404();
	}
}else{
	show404();
}
function extraData( &$array ){
	if(empty($array)){
		return;
	}
	if( is_array($array) ){
		foreach ($array as &$a){
			extraData( $a );
		}
	}else{
// 		$array = htmlspecialchars($array);
	}
}