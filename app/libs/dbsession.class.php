<?php
/**
* DataBase Store Session Class
*  @author KevinChen
* 	@pubdate 2008-05-27
* 
*/
class dbsession{
	const COOKIEDOMAIN = DEFAULT_COOKIEDOMAIN;

	const COOKIEPATH = DEFAULT_COOKIEPATH;
	const SESSION_NAME = DEFAULT_SESSION_NAME;
	const SESSION_ACTIVE_TIME = USER_SESSION_ACTIVE_TIME;
	const STORE_IN_DB = true; // if false , using PHP session 
	static private $session_id;
	static private $session;
	static private $db;
	private $timestamp;
	
	/**
	* initial function
	*/
	function __construct( $sessionData , $refresh = false) {
		// if the visitor is spider, do not need to generate session
		/*
		if(isSearchEngine()==true){
			return false;
		}
		*/
		// if using php session,do not need to initial 
		if( self::STORE_IN_DB!=true ) {
			return true; 
		}
		
		if( self::$db == null ) { // Singleton
			global $DB;
			self::$db =  $DB;
		}
		$this->timestamp = NOWTIME ;
		//从cookie里面获得session_id
		
		$cookie_key = self::SESSION_NAME;
		if ( isset($_COOKIE[$cookie_key]) ) {
			self::$session_id = $_COOKIE[$cookie_key];
		} else {
			self::$session_id = "";
		}
		
		$valid = $this->validatesessionId();
		
		// if session is not valid ,generate a new session
// 		var_dump($sessionData);die();
		if( ($valid === false || $refresh == true) && !empty($sessionData) ){
			self::$session = $sessionData;
			$this->_generateSession();
		}
		// load the session
		
		$this->_initSession();
		
		// update active time
		$randNum = rand(0,10);
		
		if ( $randNum == 5 ) {
			$this->_updateActiveTime();
			$this->removeExpiredSession();
		}
	}

	function __destruct(){
	}

	/**
	* validate session_id,and check it whether has been expired
	*/
	function validatesessionId(){
		$session_id = self::$session_id;
		if( empty($session_id) ) {
			return false;
		}
		preg_match("/[0-9a-f]{16}/is",$session_id,$match);
		if( !empty($match) ){
			
			$ip = getClientIP();
			$expire_time = $this->timestamp - self::SESSION_ACTIVE_TIME;
			$session_exist = userSessionModel::getInstance()->getUserSession($session_id , $ip , $expire_time);
			if(!empty($session_exist)){
				return true;				
			}
		}
		return false;
	}
		
	/**
	* 如果没有session，生成新的session，其中customer_id,email应该为空
	*/
	function _generateSession(){
			$ip = getClientIP();
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$timestamp = $this->timestamp;
			$rand_num = rand(0,100); // 取 0 - 100 中的一个随机数
			self::$session_id = substr(md5($ip . "-" . $useragent . "-" . $timestamp . "-" .$rand_num),0,16); // 生成session_id
			$cookie_key = self::SESSION_NAME;
			$keyId = self::$session['user_id'];
			// the cookie will be available within the entire domain,will expire at the end of the session (when the browser closes).
			setcookie($cookie_key, self::$session_id, time()+3600*24, self::COOKIEPATH, self::COOKIEDOMAIN); 
			//delete the session which session_id is exsit in the session_pool
			userSessionModel::getInstance()->deleteUserSessionData( self::$session_id , self::$session['user_id'] );
			//insert the new session to the session pool
			$sessionData = serialize ( self::$session );
			$insertData = array();
			$insertData['session_id'] = self::$session_id;
			$insertData['keyid'] = $keyId;
			$insertData['last_active_time'] = $timestamp;
			$insertData['login_ip'] = $ip;
			$insertData['session_data'] = $sessionData;
			userSessionModel::getInstance()->insertUserSessionInfo($insertData);
			$this->removeExpiredSession();
			
			// delete error session 
			userSessionModel::getInstance()->deleteErrorSession();
			return true;
	}

	/**
	* load the session value to memory
	*/
	function _initSession(){
		
		$session_id = self::$session_id;
		
		$db_session = userSessionModel::getInstance()->getUserSession( $session_id );
		if ( isset($db_session) ) {
			self::$session = unserialize( $db_session['session_data'] );
		}
	}

	/**
	* remove the expired session
	*/
	function removeExpiredSession(){
		$expire_time = $this->timestamp - self::SESSION_ACTIVE_TIME;
		//是否存在logout的用户id
		$check_expired = userSessionModel::getInstance()->ifExistExpireUserIds($expire_time);
		if( $check_expired ){
			return userSessionModel::getInstance()->deleteExpireSessions($expire_time);
		}else{
			return true;;
		}
	}

	/**
	* 
	*/
	function _updateActiveTime(){
		$new_timestamp = $this->timestamp;
		$_session_id = self::$session_id;
// 		echo 1;die();
		return userSessionModel::getInstance()->updateUserSessionExpireTime( $_session_id , $new_timestamp );
	}

	/*
	 * 
	 */
	function _saveSessionToDB(){
		if(empty(self::$session_id)||strlen(self::$session_id)!=16){
			return false;
		}
		$session_id = self::$session_id;
		$timestamp = $this->timestamp;
		$ip = getClientIP();
		return userSessionModel::getInstance()->updateUsersSessionInfo(array('session_id'=>$session_id) , array('last_active_time'=>$timestamp , 'login_ip'=>$ip));
	}

	/**
	* API Function: get the session values
	*/
	function get_session(){
		if( self::STORE_IN_DB == true ){
			return self::$session;
		}else{
			session_start();
			return $_SESSION;
		}
	}
	
	/**
	* API Function: set the session values
	*/
	function set_session($data){
		//store session in database;
		if(self::STORE_IN_DB==true){
			return $this->_saveSessionToDB();
		}else{
		// using php default function
			session_start();
			if(!empty($data)&&is_array($data)){
				$k = $v = null;
				foreach($data as $k=>$v){
					$_SESSION[$k] = $v;
				}
				return true;
			}
			session_write_close();
		}
	}
	
	/**
	* API Function: remove the some customer's duplicate session
	*/
	function unique_customer($customer_id){
		$session_id = self::$session_id;
		return userSessionModel::getInstance()->uniqueCustomer( $session_id , $customer_id);
	}

	/**
	* API Function: destroy session
	*/
	function destroy_session(){
		if(self::STORE_IN_DB==true){
			$session_id = self::$session_id;
			
			userSessionModel::getInstance()->deleteUserSessionData( $session_id );
			// expire session_id
			$cookie_key = self::SESSION_NAME;
			@setcookie($cookie_key,''); // the cookie will be available within the entire domain,will expire at the end of the session (when the browser closes).
		}
	}

}
