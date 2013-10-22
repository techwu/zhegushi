<?php
// importer( "controller.UserBase" );
// $user = new UserBase();
$session_helper = new dbsession( array() );

$_ZSESSION = $session_helper->get_session(); // the global session
global $action;
global $method;
if  ( empty($_ZSESSION) ) {
	if( isset($_COOKIE[ITEA_VC_USER_COOKIE]) && !empty($_COOKIE[ITEA_VC_USER_COOKIE]) ) {
		$cookie_check = adminModel::getInstance()->verifyAdminCookie($_COOKIE[ITEA_VC_USER_COOKIE]);
		if(isset($cookie_check[0]) && !empty($cookie_check[0])) {
			userCache::getInstance()->_setUserSession($cookie_check[0]);
			$_ZSESSION = $session_helper->get_session();
			if ( $action == DEFAULT_ACTION_METHOD && $method == DEFAULT_METHOD_METHOD ) {
				$action = DEFAULT_ACTION_METHOD_CALLBACK;
				$method = DEFAULT_METHOD_METHOD;
			}
		}
	} else{
		if ( $action == DEFAULT_ACTION_METHOD_CALLBACK && $method == DEFAULT_METHOD_METHOD ) {
			$action = DEFAULT_ACTION_METHOD;
			$method = DEFAULT_METHOD_METHOD;
		}
	}
} else {
	if(!isset($_COOKIE[ITEA_VC_USER_COOKIE]) || empty($_COOKIE[ITEA_VC_USER_COOKIE])){
		$cookie_key = ITEA_VC_USER_COOKIE;
		$cookie_value = md5(encrypt::Sha1Md5($_ZSESSION['user_id'] .  $_ZSESSION['admin'] . $_ZSESSION['passwd'] ) . time());
		$cookie_expire = time() + 3601;
		$cookie_path = DEFAULT_COOKIEPATH;
		$cookie_domian = DEFAULT_COOKIEDOMAIN;
		setcookie($cookie_key, $cookie_value, $cookie_expire, $cookie_path, $cookie_domian);
		
		adminModel::getInstance()->updateAdminCookie($_ZSESSION['user_id'], $cookie_value);
		
		if ( $action == DEFAULT_ACTION_METHOD_CALLBACK && $method == DEFAULT_METHOD_METHOD ) {
			$action = DEFAULT_ACTION_METHOD;
			$method = DEFAULT_METHOD_METHOD;
		}
	}else{
		if ( $action == DEFAULT_ACTION_METHOD && $method == DEFAULT_METHOD_METHOD ) {
			$action = DEFAULT_ACTION_METHOD_CALLBACK;
			$method = DEFAULT_METHOD_METHOD;
		}
	}
}
$GLOBALS['SESS_USER_ID'] = isset($_ZSESSION['user_id'])?$_ZSESSION['user_id']:0;