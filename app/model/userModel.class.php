<?php
/*
 * some function to communicate with user table
 */
class userModel extends CModel{
	/**
	 * @return userModel
	 */
	
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	function __construct(){
		parent::__construct(array(
							'user_id' , 'username' , 'password' , 'phone' , 'email' , 'gender' , 'intro' , 'avatar' ,
							'birthday' , 'province' , 'city' , 'hash' , 'status' , 'created' , 'updated' , 'is_v' , 
							'come_from' , 'come_id' ) , DB_TABLE_USER );
	}
	
	function insertUsers( $username , $password , $phone , $email , $hash , $intro , $avatar , $status = USER_STATUS_REGISTER , $gender = USER_GENDER_SECRIT , $birthday = NOWTIME , $province = 10 , $city = 1000 , $come_from = 0  , $come_id = 0 , $is_v = 0 ){
		$iData = array();
		if (empty($email)){
			return false;
		}
		
		$iData['email'] = $email;
		$iData['username'] = $username;
		$iData['password'] = $password;
		$iData['phone'] = $phone;
		$iData['gender'] = $gender;
		$iData['intro'] = $intro;
		$iData['avatar'] = $avatar ;
		$iData['birthday'] = $birthday;
		$iData['province'] = $province;
		$iData['city'] = $city;
		$iData['hash'] = $hash;
		$iData['status'] = $status;
		$iData['created'] = NOWTIME;
		$iData['updated'] = NOWTIME;
		$iData['is_v'] = $is_v;
		$iData['come_from'] = $come_from;
		$iData['come_id'] = $come_id ;
		
		return $this->insertUserInfo($iData);
	}
	
	function insertUserInfo($iData){
		$user_id = $this->addNewData($iData);
		if ($user_id){
			userExpansionModel::getInstance()->initUserExpansion(array('user_id'=>$user_id));
			return $user_id;
		}else{
			return false;
		}
		
	}
	
	function resetExitUserInfoByWhereArr($updateArr , $whereArr){
		$result = self::$db->updateField ( $whereArr, $updateArr ,DB_TABLE_USER);
		return $result;
		
	}
	/**
	 * 通过email判定用户是否存在
	 * @param string $emailAddress
	 * @param int $userId
	 * @return number|boolean
	 */
	function checkIfEmailExist($emailAddress, $userId="") {
		$sqlComm = "select * from ". DB_TABLE_USER ." where email=:emailAddress";
		$sqlData['emailAddress'] = $emailAddress;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		foreach ( $result as $r_key=>$r_value ){
			if ( $r_value['email'] == $emailAddress && $r_value['user_id'] != $userId) {
				return 1;
			}else{
				return false;
			}
		}
		return false;
	}
	
	function checkIfUserNameExist( $username, $userId = "") {
		$sqlComm = "select * from ". DB_TABLE_USER ." where username=:username";
		$sqlData['username'] = $username;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		foreach ( $result as $r_key=>$r_value ){
			if ( $r_value['username'] == $username && $r_value['user_id'] != $userId) {
				return 1;
			}else{
				return false;
			}
		}
		return false;
	}
	
	function getUserInfoByWbidAndFrom( $wbid , $come_from ){
		if (empty($wbid) || empty($come_from)){
			return false;
		}
		$sqlComm = "select * from " . DB_TABLE_USER ." where come_from=:come_from and come_id =:come_id and status > 0";
		$sqlData['come_from'] = $come_from;
		$sqlData['come_id'] = $wbid;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if(!empty($result)){
			if(isset($result[0])){
				foreach($result as &$res){
					$res['avatar'] = commFun::getRealAvatarUrl( $res['avatar'] , IMAGE_PATH );
				}
				return $result[0];
			}else{
				return false;
			}
		}
		return false;
	}
	
	function getUserInfoByUserId($user_id ){
		if(empty($user_id)){
			return false;
		}
		$sqlComm = "select * from " . DB_TABLE_USER ." where user_id = :user_id and status > 0";
		$sqlData['user_id'] = $user_id;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if(!empty($result)){
			if(isset($result[0])){
				foreach($result as &$res){
					$res['avatar'] = commFun::getRealAvatarUrl( $res['avatar'] , IMAGE_PATH );
				}
				return $result[0];
			}else{
				return false;
			}
		}return false;
	}
	
	function getUserInfoByUsername( $username , $fields){
		if(empty($username)){
			return false;
		}
		$fields = $this->processFields($fields);
		$sqlComm = "select {$fields} from " . DB_TABLE_USER ." where username = :username and status > 0";
		$sqlData['username'] = $username;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if(!empty($result)){
			if(isset($result[0])){
				foreach($result as &$res){
					if ( isset($res['avatar']) ){
						$res['avatar'] = commFun::getRealAvatarUrl( $res['avatar'] , IMAGE_PATH );
					}
				}
				return $result[0];
			}else{
				return false;
			}
		}return false;
	}
	
	function getUserBaseInfoByHash ( $active_code ) {
		$sqlComm = "select * from " . DB_TABLE_USER . " where hash = :active_code and status > 0 ";
		$sqlData['active_code'] = $active_code;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['user_id']) && empty($result[0]['user_id']) == false) {
			foreach($result as &$res){
				$res['avatar'] = commFun::getRealAvatarUrl( $res['avatar'] , IMAGE_PATH );
			}
			return $result[0];
		} else {
			return false;
		}
	}
	
	function getUserInfoByEmailAndPassword ( $email , $password ) {
		$sqlComm = "select * from " . DB_TABLE_USER . " where email=:email and password=:password and status > 0";
		$sqlData['email'] = $email;
		$sqlData['password'] = encrypt::Sha1MD5($password);
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		
		if ( isset($result[0]['user_id']) && empty($result[0]['user_id']) == false) {
			foreach ($result as &$res){
				$res['avatar'] = commFun::getRealAvatarUrl( $res['avatar'] , IMAGE_PATH );
				if($res['gender'] == USER_GENDER_MALE ){
					$res['gender'] = '男';
				}elseif ( $res['gender'] == USER_GENDER_FEMALE ){
					$res['gender'] = '女';
				}else{
					$res['gender'] = '保密';
				}
			}
			return $result;
		} else {
			return false;
		}
	}
	
	function getAllUserNumberByTime( $begin , $end ){
		$sqlComm = "select count(user_id) num from " . DB_TABLE_USER . " where status > 0 and created > :begin and created < :end";
		$result = array();
		$sqlData = array( 'begin'=>$begin , 'end'=>$end );
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if(isset($result[0])) {
			return $result[0]['num'];
		} else {
			return 0;
		}
	}
	
	function __destruct(){}
	
}
