<?php
define('BASEPATH', realpath(dirname(__FILE__).'/../../') . '/');
define('HOST_PATH', realpath(dirname(__FILE__).'/../../') . '/host/');
define('ADMIN_HOST_PATH', realpath(dirname(__FILE__).'/../../') . '/admhost/');
define('APP_PATH', realpath(dirname(__FILE__).'/../../') . '/app/');
define('TASK_PATH', APP_PATH . "tasks/");
define('CORE_LIB_PATH' , APP_PATH . "libs/");

if(!isset($_SERVER['SERVER_NAME'])){
    $_SERVER['SERVER_NAME'] = 'www.actcoder.com';
}
$DOMAIN_NAME = "http://".$_SERVER['SERVER_NAME'] .'/';
$DOMAIN_MAIN_NAME = "http://".$_SERVER['SERVER_NAME'] .'/';

define('BASE_URL',$DOMAIN_NAME);
define('AVATAR_URL' , BASE_URL);
define('PICTURE_URL' , BASE_URL);
define('BASE_MAIN_URL',$DOMAIN_MAIN_NAME);

define('WWW_PATH' , 'http://www.zhegushi.com' );
define('API_PATH' , 'http://api.zhegushi.com' );
define('STATIC_PATH' , BASE_URL );
define('IMAGE_PATH' , 'http://actcoder.u.qiniudn.com/');

$cookie_domain = $_SERVER['SERVER_NAME'];
define('DEFAULT_COOKIEDOMAIN' , $cookie_domain);
define('DEFAULT_COOKIEPATH' , "/");


define('DEFAULT_COOKIE_LOGON','DEFAULT_COOKIE_LOGON');
$NEED_DOMAIN_REDIRCT = false;

define('FRAME_TITLE' , '这故事-分享你身边的故事');
define('FRAME_KEYWORD' , '这故事,故事,感人,美好,传奇');
define('FRAME_DESCRIPTION' , '山是水的故事，云是风的故事，你是我的故事，我也是你的故事！');
