<?php
class searchEnginerModel extends CModel{
	/**
	 * @return searchEnginerModel
	 */
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct();
	}
	
	function insertSearchEnginerIp( $ip , $status ){
		$check = $this->getSearchEnginerIpByIp( $ip );
		if($check === false){
			$field = array('ip' , 'status');
			$data['ip'] = $ip ;
			$data['status'] = $status ;
			$res = self::$db->inputField($field , $data , SEARCH_ENGINER , true);
			return $res;
		}else{
			$data['ip'] = $ip ;
			$data['status'] = $status ;
			$this->updateSearchEnginer(array('ip'=>$ip , 'id'=>$check['id']), $data);
			return $check['id'];
		}
	}
	
	public function updateSearchEnginer( $where , $update ){
		$result = self::$db->updateField ( $where, $update , SEARCH_ENGINER );
		return $result;
	}
	
	function getSearchEnginerIpByIp( $ip , $status = EXIST ){
		$sql_status_ext = '';
		$sqlData = array() ;
		if ( is_array($status) ){
			foreach ( $status as $k=>$s ){
				$sqlData['status_'.$k] = $s;
				$sql_status_ext = ":status_" . $k . ',' ;
			}
			$sql_status_ext = trim($sql_status_ext , ',');
		}else{
			$sql_status_ext = ":status" ;
			$sqlData['status'] = $status ;
		}
		$sql = "select * from " . SEARCH_ENGINER . " where ip = :ip and status in ({$sql_status_ext})";
		$sqlData['ip'] = $ip;
		$result = array();
		self::$db->getQueryData( $result, $sql, $sqlData );
		if ( isset($result[0]) && !empty($result)) {
			return $result[0];
		} else {
			return false;
		}
	}
	
	function getSearchEnginerIps( $status = EXIST ){
		$sql_status_ext = '';
		$sqlData = array() ;
		if ( is_array($status) ){
			foreach ( $status as $k=>$s ){
				$sqlData['status_'.$k] = $s;
				$sql_status_ext = ":status_" . $k . ',' ;
			}
			$sql_status_ext = trim($sql_status_ext , ',');
		}else{
			$sql_status_ext = ":status" ;
			$sqlData['status'] = $status ;
		}
		$sql = "select ip from " . SEARCH_ENGINER . " where status in ({$sql_status_ext})";
		$result = array();
		self::$db->getQueryData( $result, $sql, $sqlData );
		if ( isset($result[0]) && !empty($result)) {
			return $result;
		} else {
			return false;
		}
	}
	
	function __destruct(){}
	
}