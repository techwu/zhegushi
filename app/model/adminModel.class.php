<?php
class adminModel extends CModel{
	/**
	 * @return adminModel
	 */
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct(array('user_id' , 'admin' , 'passwd' , 'status') , DB_TABLE_ADMIN);
	}
	
	public function addCrmUser($admin,$passwd,$status = EXIST ){
		$id = $this->isExistCrmUser($admin);
		if($id === false){
			$field = array('admin' , 'passwd' , 'status' , 'company' , 'phone');
			$data['admin'] = $admin ;
			$data['passwd'] = encrypt::Sha1MD5($passwd);
			$data['status'] = $status ;
			$res = self::$db->inputField($field,$data,DB_TABLE_ADMIN,true);
			return $res;		
		}else{
			return $id;
		}
	}
	
	
	function isExistCrmUser($admin){
		$sqlComm = "select user_id from " . DB_TABLE_ADMIN . " where admin=:admin ";
		$sqlData['admin'] = $admin;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0])) {
			return $result[0]['user_id'];
		} else {
			return false;
		}
	}
	
	public function updateAdminCookie($admin_id , $cookie ){
		//admin_id
		$check = $this->checkIfAdminExpansionExist($admin_id);
		if($check === false){
			$field = array('admin_id' , 'cookie');
			$data = array('admin_id'=>$admin_id , 'cookie'=>$cookie);
			$res = self::$db->inputField($field,$data,DB_TABLE_ADMIN_EXPANSION,true);
			return $res;
		}else{
			$where['admin_id'] = $admin_id;
			$update['cookie'] = $cookie;
			$result = self::$db->updateField ( $where, $update ,DB_TABLE_ADMIN_EXPANSION );
			return $result;
		}
	}
	
	function updateAdmin( $where , $update ){
		$result = self::$db->updateField ( $where, $update , DB_TABLE_ADMIN);
		return $result;
	}
	
	public function checkIfAdminExpansionExist( $admin_id ){
		$sqlComm = "select * from " . DB_TABLE_ADMIN_EXPANSION . " where admin_id = :admin_id ";
		$sqlData['admin_id'] = $admin_id;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0])) {
			return $result[0];
		} else {
			return false;
		}
	}
	
	public function checkCrmAdminAndPassword( $admin , $passwd ){
		$sqlComm = "select * from " . DB_TABLE_ADMIN . " where admin = :admin and passwd = :passwd and status >= :status";
		$sqlData['admin'] = $admin;
		$sqlData['passwd'] = encrypt::Sha1MD5($passwd);
		$sqlData['status'] = EXIST;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['user_id']) && $result[0]['user_id'] != 0) {
			return $result[0];
		} else {
			return false;
		}
	}
	
	public function getAllCrmAdminRows( $status ){
		$sqlComm = "select count( user_id ) num from " . DB_TABLE_ADMIN . " where status >= :status";
		$sqlData['status'] = EXIST ;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['num']) && $result[0]['num'] != 0) {
			return $result[0]['num'];
		} else {
			return 0 ;
		}
	}
	
	public function verifyAdminCookie($cookie) {
		$sqlComm = "select * from ". DB_TABLE_ADMIN_EXPANSION ." t1 left join ".DB_TABLE_ADMIN." t2 on t1.admin_id = t2.user_id where t1.cookie=:cookie";
		$sqlData['cookie'] = $cookie;
		
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if(isset($result[0]['cookie']) && !empty($result[0]['cookie'])) {
			return $result;
		} else {
			return false;
		}
		
	}
	
	function __destruct(){}
	
}