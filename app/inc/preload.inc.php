<?php

require_once(CORE_LIB_PATH."common.func.php"); //common functions
require_once(CORE_LIB_PATH."curl.class.php");
require_once(CORE_LIB_PATH."commFun.class.php"); //common functions
require_once(CORE_LIB_PATH."importer.class.php"); 
require_once(CORE_LIB_PATH."URLHelper.php");
require_once(CORE_LIB_PATH."pdoDB.class.php"); //database handle class
require_once(CORE_LIB_PATH."dbsession.class.php"); // session handle class
require_once(CORE_LIB_PATH."CSmarty.class.php"); // view Base class
require_once(CORE_LIB_PATH."CAction.class.php"); // controller Base class
require_once(CORE_LIB_PATH."CCache.class.php"); //cache Base class
require_once(CORE_LIB_PATH."CModel.class.php"); //Model Base class
require_once(CORE_LIB_PATH."TimeHelper.class.php"); //TimeHelper class
require_once(CORE_LIB_PATH."Logger.class.php"); //Logger class
require_once(CORE_LIB_PATH."zx_log.class.php");
require_once(CORE_LIB_PATH."CookieHelper.class.php");
require_once(CORE_LIB_PATH."encrypt.class.php");

require_once(CORE_LIB_PATH."webTools.class.php");

require_once(CORE_LIB_PATH."memcached.class.php");

require_once(APP_PATH."inc/autoload.inc.php"); //autoload Model function
//filter the harm char,such as '
unset($_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);
$_GET     = zaddslashes($_GET, 1, TRUE);
//$_POST    = zaddslashes($_POST, 1, TRUE);
$_COOKIE  = zaddslashes($_COOKIE, 1, TRUE);
$_SERVER  = zaddslashes($_SERVER);
//$_FILES   = zaddslashes($_FILES);
$_REQUEST = zaddslashes($_REQUEST, 1, TRUE);

$PDODB = new pdoDB();
$DB = $PDODB;
$db = $PDODB;
global $MEMCACHE; 
$MEMCACHE = new memcached();
// var_dump($MEMCACHE);die();