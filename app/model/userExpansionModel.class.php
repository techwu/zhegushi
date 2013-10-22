<?php
class userExpansionModel extends CModel{
	/**
	 * @return userExpansionModel
	 */
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	function __construct(){
		parent::__construct(array('user_expansion_id' , 'user_id' , 'cookie') , DB_TABLE_USER_EXPANSION);
	}

	public function __destruct(){}
	
	function insertUserExpansionInfo($iData){
		return $this->addNewData($iData);
	}
	
	function initUserExpansion($uids){
		$uids = DataToArray($uids , 'user_id');
		$this->addNewUserExpansion($uids);
	}
	
	function addNewUserExpansion($uid){
		if(!is_array($uid)){
			$uid = array($uid);
		}
		foreach ($uid as $id){
			$res = $this->insertUserExpansionInfo(array('user_id'=>$id));
		}
	}
	

	/**
	 * 更新用户的免登录cookie信息
	 * @param  $user_id
	 * @param  $cookie
	 */
	function updateUserCookie($user_id, $cookie) {//use
		if(!isset($cookie)) {
			return false;
		}
		$check = $this->checkIfUserIdExist($user_id);
		if($check === false){
			$field = array('user_id' , 'cookie');
			$data = array('user_id'=>$user_id , 'cookie'=>$cookie);
			$res = self::$db->inputField($field,$data,DB_TABLE_USER_EXPANSION,true);
			return $res;
		}else{
			$sqlComm = "update " . DB_TABLE_USER_EXPANSION . " set cookie=:cookie where user_id=:user_id ";
			$sqlData['user_id'] = $user_id;
			$sqlData['cookie'] = $cookie;
			$update_succ = self::$db->queryWrite ( $sqlComm, $sqlData );
			//var_dump ( $sqlData );die();
			return $update_succ;
		}
	}
	
	function checkIfUserIdExist($user_id){
		$sqlComm = "select * from ".DB_TABLE_USER_EXPANSION. " where user_id =:user_id";
		$sqlData['user_id'] = $user_id;
	
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if(isset($result[0])) {
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 验证用户的免的登录cookie
	 * @param $cookie
	 */
	function verifyUserCookie($cookie) {
		$sqlComm = "select t2.* , t1.cookie from ".DB_TABLE_USER_EXPANSION." t1 left join ".DB_TABLE_USER." t2 on t1.user_id = t2.user_id where t1.cookie=:cookie";
		$sqlData['cookie'] = $cookie;
		
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		
		if(isset($result[0]['cookie']) && !empty($result[0]['cookie'])) {
			return $result ;
		} else {
			return false;
		}
	
	}
}