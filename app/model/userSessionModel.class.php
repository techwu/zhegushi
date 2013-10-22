<?php
class userSessionModel extends CModel{
	/**
	 * @return userSessionModel
	 */
		
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	function __construct(){
		parent::__construct(array('id' , 'session_id' , 'keyid' , 'last_active_time' , 'login_ip' , 'session_data') , DB_TABLE_USER_SESSIONS );
	}

	public function __destruct(){}
	
	function insertUserSessionInfo($iData){
		return $this->addNewData($iData);
	}
	
	function updateUsersSessionData($user_id, $session_data) {
		$sqlComm = "update " .DB_TABLE_USER_SESSIONS. " set session_data=:session_data where keyid=:keyid";
		$sqlData['keyid'] = $user_id;
		$sqlData['session_data'] = $session_data;
		return self::$db->queryWrite($sqlComm, $sqlData);
	}
	
	function updateUsersSessionInfo($whereArr , $updateArr) {
		$result = self::$db->updateField ( $whereArr, $updateArr ,DB_TABLE_USER_SESSIONS);
		return $result;
	}
	
	function updateUserSessionExpireTime( $sessionid , $new_time ){
		$query = "UPDATE  " .DB_TABLE_USER_SESSIONS. "  SET last_active_time = :last_active_time WHERE session_id = :session_id LIMIT 1";
		$sqlData['last_active_time'] = $new_time;
		$sqlData['session_id'] = $sessionid;
		return self::$db->queryWrite($query, $sqlData);
	}
	
	function deleteUserSessionData( $sessionid , $user_id = 0 ){
		$sqlComm = "DELETE IGNORE FROM  ".DB_TABLE_USER_SESSIONS."  WHERE session_id = :sessionid or keyid = :keyid LIMIT 1";
		$sqlData['keyid'] = $user_id;
		$sqlData['sessionid'] = $sessionid;
		return self::$db->queryWrite($sqlComm,$sqlData);
	}
	
	/**
	 * when generate a new session ,delete the error session;
	 */
	function deleteErrorSession(){
		$query = "select login_ip from (select login_ip,count(*) sum from  ".DB_TABLE_USER_SESSIONS."  group by login_ip) t where t.sum>100 order by sum desc limit 1";
		$sqlData = array();
		$row = array();
		self::$db->getQueryData($row,$query,$sqlData);
		if(isset($row['login_ip'])){
			$logon_ip = $row['login_ip'];
		}
		if(!empty($logon_ip)){
			$query = "DELETE IGNORE FROM  " .DB_TABLE_USER_SESSIONS. "  WHERE login_ip = '$logon_ip'";
			$sqlData = array();
			self::$db->queryWrite($query , $sqlData);
		}
		return true;
	}
	
	/**
	 * delete expired session
	 */
	function deleteExpireSessions( $expire_time ){
		$sqlComm = "DELETE IGNORE FROM  ".DB_TABLE_USER_SESSIONS."  WHERE  last_active_time < :last_active_time";
		$sqlData['last_active_time'] = $expire_time ;
		return self::$db->queryWrite($sqlComm,$sqlData);
	}
	
	/**
	 * API Function: remove the some customer's duplicate session
	 */
	function uniqueCustomer( $session_id , $customer_id){
		$query = "DELETE FROM  " .SESSION. "  WHERE keyid = $customer_id AND session_id <> '$session_id'";
		return self::$db->queryWrite($query);
	}
	
	
	function isValidateSession($session_id , $login_ip = ''){
		$expire_time = time() - USER_SESSION_ACTIVE_TIME ;
		$query = "SELECT true FROM ".DB_TABLE_USER_SESSIONS." WHERE session_id = :session_id AND login_ip = :login_ip AND last_active_time > '$expire_time'";
		$sqlData= array('login_ip'=>$login_ip , 'session_id'=>$session_id );
		$session_exist = null;
		self::$db->getQueryData( $session_exist, $query, $sqlData );
		// 		var_dump($session_exist);die();
		if(!empty($session_exist)){
			return true;
		}
	}
	
	function getUserSession($session_id , $login_ip = '' , $expire_time = 0){
		$sqlData= array();
		if (!empty($login_ip)){
			$sql_ip_ext = " AND login_ip=:login_ip ";
			$sqlData['login_ip'] = $login_ip;
		}else{
			$sql_ip_ext = '';
		}
		if( empty($expire_time) ){
			$expire_time = time() - 1805;
		}
		$query = "SELECT * FROM ".DB_TABLE_USER_SESSIONS." WHERE session_id = :session_id {$sql_ip_ext} AND last_active_time > :last_active_time ";
		$sqlData['session_id'] = $session_id;
		$sqlData['last_active_time'] = $expire_time ;
		$session_exist = array();
		self::$db->getQueryData( $session_exist, $query, $sqlData );
		if(!empty($session_exist)){
			return $session_exist[0];
		}
		return false;
	}
	
	function ifExistExpireUserIds($expire_time){
		$sqlComm = "select 	keyid from  " .DB_TABLE_USER_SESSIONS. "  where  last_active_time < :last_active_time";
		$sqlData = array('last_active_time'=>$expire_time);
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( empty( $result ) ) {
			return true;
		}else{
			return false;
		}
	}
}