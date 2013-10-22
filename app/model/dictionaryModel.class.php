<?php 
/**
 *for some dictionary Information
 *
 */
class dictionaryModel extends CModel{
	
	/**
	 * @return dictionaryModel
	 */
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct();
	}
	
	// return cities by province id
	function addCities( $N_CITYID , $S_CITYNAME , $N_PROVID , $S_STATE ){
		$field = array('N_CITYID','S_CITYNAME' , 'N_PROVID' , 'S_STATE');
		$data = array('N_CITYID'=>$N_CITYID,'S_CITYNAME'=>$S_CITYNAME , 'N_PROVID'=>$N_PROVID , 'S_STATE'=>$S_STATE);
		$res = self::$db->inputField($field,$data,T_CIG_DICTIONARY_CITY);
		return true;
	}
	
    function getCitiesByProviceId($prov_id) {
   		$sqlComm = "select * from " . T_CIG_DICTIONARY_CITY . " where N_PROVID = :prov_id";
		$sqlData['prov_id'] = $prov_id;
		
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['N_CITYID']) && !empty($result[0]['N_CITYID']) ) {
			return $result;
		} else {
			return false;
		}
    }
    
    function getProvinceIdByPname( $name ){
    	$sqlComm = "select * from " . T_CIG_DICTIONARY_PROVINCE . " where S_PROVNAME = :prov_name";
    	$sqlData['prov_name'] = $name;
		
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['N_PROVID']) && !empty($result[0]['N_PROVID']) ) {
			return $result[0]['N_PROVID'];
		} else {
			return 0;
		}
    }
    
	function getProvinceIdLikePname( $name ){
    	$sqlComm = "select * from " . T_CIG_DICTIONARY_PROVINCE . " where S_PROVNAME like :province and S_STATE = 1";
    	$sqlData = array('province'=>'%' . $name . '%');
		
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['N_PROVID']) && !empty($result[0]['N_PROVID']) ) {
			return $result[0]['N_PROVID'];
		} else {
			return 0;
		}
    }
    
	function getProvinceNameLikeChar($char , $limit = PER_PAGE ){
		$char = strtoupper($char);
		$sqlComm = "select S_PROVNAME name from " . T_CIG_DICTIONARY_PROVINCE . " where upper(S_PROVNAME) like :char and S_STATE = 1 order by N_PROVID desc limit {$limit}";
		$sqlData = array('char'=>'%'.$char.'%');//'limit'=>$limit);
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0])) {
			return $result;
		} else {
			return false;
		}
	}
    
    function getCityIdByCnameAndPid($location , $N_PROVID){
   	 	$sqlComm = "select * from " . T_CIG_DICTIONARY_CITY . " where N_PROVID = :prov_id and S_CITYNAME = :name";
		$sqlData['prov_id'] = $N_PROVID;
		$sqlData['name'] = $location;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['N_CITYID']) && !empty($result[0]['N_CITYID']) ) {
			return $result[0]['N_CITYID'];
		} else {
			return 0;
		}
    }
    
    function getReturnIDLikeCname( $location , $return = 'N_CITYID'){
    	$sqlComm = "select * from " . T_CIG_DICTIONARY_CITY . " where S_CITYNAME like '%{$location}%'";
		$sqlData = array();
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0][$return]) && !empty($result[0][$return]) ) {
			if( empty($return)){
				return $result[0];
			}else{
				return $result[0][$return];
			}
		} else {
			return 0;
		}
    }
    //// return all provinces
    function getAllProvinces() {
    	$sqlComm = "select * from ". T_CIG_DICTIONARY_PROVINCE ;
    	$sqlData = array();
    	$result = array();
    	
    	self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['N_PROVID']) && !empty($result[0]['N_PROVID']) ) {
			return $result;
		} else {
			return false;
		}
    }
    
    function getAllProvinceNumbers() {
    	$sqlComm = "select count(*) sum from ". T_CIG_DICTIONARY_PROVINCE ;
    	$sqlData = array();
    	$result = array();
    	 
    	self::$db->getQueryData( $result, $sqlComm, $sqlData );
    	if ( !empty($result) ) {
    		return $result[0]['sum'];
    	} else {
    		return 0;
    	}
    }
    
    function getAllCities(){
    	$sqlComm = "select * from ". T_CIG_DICTIONARY_CITY;
    	$sqlData = array();
    	$result = array();
    	
    	self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['N_CITYID']) && !empty($result[0]['N_CITYID']) ) {
			return $result;
		} else {
			return false;
		}
    }
    
    function getProvNameByProvId( $provice_id ){
    	$sqlComm = "select S_PROVNAME from " . T_CIG_DICTIONARY_PROVINCE . " where N_PROVID = :prov_id ";
		$sqlData['prov_id'] = $provice_id;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['S_PROVNAME']) && !empty($result[0]['S_PROVNAME']) ) {
			return $result[0]['S_PROVNAME'];
		} else {
			return '';
		}
    }
    
	function getProvNameByProvIds( $provice_ids ){
		if(is_array($provice_ids)	){
			$provice_ids = implode(',' , $provice_ids );
		}
		if( empty($provice_ids ) ){
			return false;
		}
    	$sqlComm = "select S_PROVNAME from " . T_CIG_DICTIONARY_PROVINCE . " where N_PROVID in ({$provice_ids}) ";
		$sqlData = array();
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]) ) {
			return $result;
		} else {
			return false;
		}
    }
	
    function getCityByProvIdAndCityId( $provice_id , $city_id ){
    	$sqlComm = "select S_CITYNAME from " . T_CIG_DICTIONARY_CITY . " where N_PROVID = :prov_id and  N_CITYID = :city_id";
		$sqlData['prov_id'] = $provice_id;
		$sqlData['city_id'] = $city_id;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['S_CITYNAME']) && !empty($result[0]['S_CITYNAME']) ) {
			return $result[0]['S_CITYNAME'];
		} else {
			return '';
		}
    }
	function getLocationByProvIdAndCityId( $provice_id , $city_id ){
		$province = $this->getProvNameByProvId( $provice_id );
    	$sqlComm = "select S_CITYNAME from " . T_CIG_DICTIONARY_CITY . " where N_PROVID = :prov_id and  N_CITYID = :city_id";
		$sqlData['prov_id'] = $provice_id;
		$sqlData['city_id'] = $city_id;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if ( isset($result[0]['S_CITYNAME']) && !empty($result[0]['S_CITYNAME']) ) {
			return $province .' '. $result[0]['S_CITYNAME'];
		} else {
			return $province .' '.'';
		}
    }
    
	function __destruct(){
	}
	
}