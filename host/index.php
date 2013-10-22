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

//include the necessary libraries
require_once("./config/config.php");

require_once(APP_PATH."inc/preload.inc.php");

set_error_handler('tracking');

//decode the url request
$urlArray = URL_DIVERSIONER( $_SERVER["REQUEST_URI"] );
//controller class name;
$action = array_shift($urlArray);

$key = $method = array_shift($urlArray);
if(in_array($action , $GLOBALS['ACTION_NAME'])){
	if(in_array($action , $GLOBALS['SPECIAL_ACTION'])){ //此处对于 special action进行路由
		if(!in_array($method , $GLOBALS['ROUTIN_METHOD'])){
			$method = DEFAULT_METHOD_METHOD;
			$urlArray[0] = $key ;
		}
	}
}elseif (!empty($action)){//此处对 项目名进行路由
	show404();
}

if( empty($action) ){	$action = DEFAULT_ACTION_METHOD;}// default methodif( empty($method) ){	$method = DEFAULT_METHOD_METHOD;}
//controller class method name;

$pos = strpos( $method ,"ajax_");
if( $pos !== false && isset($_POST['submit_method']) && $_POST['submit_method'] == 'js' ){
	header('Content-Type: text/plain; charset=utf-8');
}else{
	header('Content-Type: text/html; charset=utf-8');
}

require_once( APP_PATH."inc/validateUser.inc.php" );
require_once( APP_PATH."inc/gobackurl.inc.php");

// the other parms 
$args = $urlArray; 
//过滤xss攻击

if(!empty($_POST) || !empty($_GET)){
	extraData($_POST);
	extraData($_GET);
}

// if( empty( $GLOBALS['SESS_USER_ID']  ) ){
// 	importer('corelib.ipban');
// 	$ipan = new ipban( $action , $method );
// 	$ipan->forbiddenCrawl();
// }
$classFile = APP_PATH . "controller/" . $action . ".class.php";


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
$timeHelper->stop();
if ( $GLOBALS['DEBUG_INFO_SWITCH'] == "ON") {
	$referStr = empty($_SERVER['HTTP_REFERER']) ?  "" : $_SERVER['HTTP_REFERER'] ;
	$agentStr = empty($_SERVER['HTTP_USER_AGENT']) ?  "" : $_SERVER['HTTP_USER_AGENT'] ;
	
	$pStr = "";
	$pStr .= "[".$action."]\t";
	$pStr .= "[".$method."]\t";
	$pSqlStr = $pStr;
	$pStr .= "[".$referStr."]\t";
	$pStr .= "[".$agentStr."]\t";
	$pStr .= "[MEM:".commFun::getMemUsedInHuanReadable()."]\t";
	$pStr .= "[time:".$timeHelper->spent()."]\t";
	$pStr .= "[".sqlMonitor::getInstance()->getSqlRunNumber()."]\t";
	$pStr .= "[".sqlMonitor::getInstance()->getAverageRunTime()."]\t";
	
	$logHandle = new zx_log("log/db_syslog", "normal");
	foreach ( sqlMonitor::getInstance()->readMonQueue() as $q ) { 
		$q['sql'] = str_replace( "\n" , " " , $q['sql']);
		$pSqlStrLine = $pSqlStr."[{$q['sql']}\t{$q['time']}]\n";
		$logHandle->w_log( $pSqlStrLine );
	} 
	
	$pStr .= "[ ".session_id()." ]\t";
	$pStr .= "[ ".post2Str()." ]\t";
	$pStr .= "[ ".trim($_SERVER["REQUEST_URI" ])."]\t";
	$pStr .= "[".getClientIP(2)."]\t";
	$pStr .= "\n";
	$logHandle = new zx_log("log/syslog", "normal");
	$logHandle->w_log( $pStr );
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
		$array = htmlspecialchars(trim($array));
	}
}
