<?php
class adminCache extends CCache{
	/**
	 * @return adminCache
	* */
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct(true);
	}
	
	function adminSignin( $user , $password ){
		$check_status = adminModel::getInstance()->checkCrmAdminAndPassword($user , $password);
		if($check_status !== false){
			$cookie_key = ITEA_VC_USER_COOKIE;
			$cookie_expire = time() + 3601;
			 
			$setStatus = userCache::getInstance()->_logonSetSession( $check_status );
			if ( $check_status === false ) {
				return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST, 'error_text' =>''));
			}
		
			$cookie_path = DEFAULT_COOKIEPATH;
			$cookie_domian = DEFAULT_COOKIEDOMAIN;
			$cookie_value = md5(encrypt::Sha1MD5($check_status['user_id'].$user . $password) . time());
		
			setcookie($cookie_key, $cookie_value, $cookie_expire, $cookie_path, $cookie_domian);
			adminModel::getInstance()->updateAdminCookie($check_status['user_id'], $cookie_value);
			setcookie(DEFAULT_COOKIE_LOGON, 0, time() + 600, '/');
		
			$author = isset($check_status['status'])?intval($check_status['status']):0;
			return (array('code'=>HTTP_ERROR_STATUS_OK , 'error_text' =>''));
		}else{
			$DEFAULT_COOKIE_LOGON = isset($_COOKIE[DEFAULT_COOKIE_LOGON])?$_COOKIE[DEFAULT_COOKIE_LOGON]:0;
			$DEFAULT_COOKIE_LOGON ++;
			setcookie(DEFAULT_COOKIE_LOGON, $DEFAULT_COOKIE_LOGON, time() + 600, '/');
			if($DEFAULT_COOKIE_LOGON <= 2){
				return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST, 'error_text' =>''));
			}else{
				return (array('code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR, 'error_text' =>''));
			}
		}
	}
	
	function __destruct(){}
}