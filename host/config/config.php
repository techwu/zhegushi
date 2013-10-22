<?php

define('FRAME_PAGE_TYPE' , 'normal');

define('DEFAULT_ACTION_METHOD' , "story");
define('DEFAULT_METHOD_METHOD' , "index");

define('DEFAULT_SESSION_NAME',  'session_dd' );
define('ITEA_VC_USER_COOKIE',  'user_cookie' );
define('DEFAULT_SESSION_STATUS',  'session_st' );
define('DEFAULT_SESSION_USERINFO' , 'session_info');
define('RE_VC_SESSION_COOKIE',  'RE_SESSION_COOKIE' );

define('ISMOBILEVERSIONCOOKIE',  'ISMOBILEVERSIONCOOKIE' );

define('DEFAULT_ACTION_METHOD_CALLBACK' , "welcome");
define('ADMIN_USER_CHECK' , 'user_check_aucb_cc');

define ( "LOGON_URL" , "signin");

define('CONFIG_PATH', realpath(dirname(__FILE__).'/../../') . '/host/');
require_once CONFIG_PATH . 'config/config.driver.php';
require_once CONFIG_PATH . 'config/config.db.drive.php';
require_once CONFIG_PATH . 'config/config.status.php';
require_once CONFIG_PATH . 'config/config.global.php';
require_once CONFIG_PATH . 'config/config.pic.php';
require_once CONFIG_PATH . 'config/config.db.php';
require_once CONFIG_PATH . 'config/config.filetype.php';
require_once CONFIG_PATH . 'config/config.appkey.php';
require_once CONFIG_PATH . 'config/config.renren.php';
require_once CONFIG_PATH . 'config/config.memcache.php';
require_once CONFIG_PATH . 'config/config.stage.php';
require_once CONFIG_PATH . 'config/config.controller.php';
require_once CONFIG_PATH . 'config/config.email.php';
require_once CONFIG_PATH . 'config/config.qiniu.php';