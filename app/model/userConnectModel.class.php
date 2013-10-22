<?php
class userConnectModel extends CModel{
	/**
	 * @return userConnectModel
	 */
		
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	function __construct(){
		parent::__construct( array('user_connect_id' , 'user_id' , 'user_from' , 'status' , 'auth' , 'access') , DB_TABLE_USER_CONNECT);
	}

	public function __destruct(){}
	
	function insertUserConnectInfo($iData){
		return $this->addNewData($iData);
	}
}