<?php
//global session variable
$session_helper = new dbsession( array() );

$_ZSESSION = $session_helper->get_session(); // the global session 

global $action;
global $method;

if  ( empty($_ZSESSION) ) {
	if( isset($_COOKIE[ITEA_VC_USER_COOKIE]) && !empty($_COOKIE[ITEA_VC_USER_COOKIE]) ) {
		$cookie_check = userExpansionModel::getInstance()->verifyUserCookie($_COOKIE[ITEA_VC_USER_COOKIE]);
		if(isset($cookie_check[0]) && !empty($cookie_check[0])) {
			userCache::getInstance()->_setUserSession($cookie_check[0]);
			$_ZSESSION = $session_helper->get_session();
		}
	}
}
if (!empty($_ZSESSION)){
	userCache::getInstance()->_setCookie($_ZSESSION['user_id'] , $_ZSESSION['email'] , $_ZSESSION['password']);}

$GLOBALS['SESS_USER_ID'] = isset($_ZSESSION['user_id'])?$_ZSESSION['user_id']:0;
