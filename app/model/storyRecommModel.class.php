<?php
class storyRecommModel extends CModel{
	/**
	 * @return storyRecommModel
	 */

	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct(array(
						'id' ,
						'story_id' ,
						'level' ,
						'stage' ,
						'create_time' ,
						'status' ,
				) ,
				DB_TABLE_STORY_COMMENDED );
	}

	function __destruct(){}
	
	function addStoryRecomm( $story_id , $level = 0 , $stage = 1 , $create_time = NOWTIME , $status = EXIST ){
		$iData = array();
		if (empty($story_id) || empty( $stage )){
			return false;
		}
		$iData['story_id'] = $story_id;
		$iData['level'] = $level;
		$iData['stage'] = $stage;
		$iData['create_time'] = $create_time;
		$iData['status'] = $status ;
	
		return $this->insertStoryRecomm($iData);
	}
	
	function insertStoryRecomm($iData){
		if ( !isset( $iData['story_id'] ) || empty($iData['story_id']) ){
			return false;
		}
		$id = $this->addNewData($iData);
		return $id ;
	}
	
	function updateStoryRecomm($where , $update){
		return self::$db->updateField($where, $update, $this->table);
	}
	
	function isExistRecommByStoryId( $story_id , $status = EXIST){
		$sql_comm = "select true from " . $this->table .
					' where story_id = :story_id and status = :status  ' ;
		$sql_data['story_id'] = $story_id ;
		$sql_data['status'] = $status ;
		$result = array();
		self::$db->getQueryData( $result, $sql_comm, $sql_data );
		if ( isset($result[0]) && !empty($result[0])) {
			return true;
		} else {
			return false;
		}
	}
	
	function getRecommStoryIds( $status = EXIST ){
		$sql_comm = "select distinct story_id from " . $this->table .
				' where status = :status order by create_time desc ' ;
		$sql_data['status'] = $status ;
		$result = array();
		self::$db->getQueryData( $result, $sql_comm, $sql_data );
		if ( isset($result[0]) && !empty($result[0])) {
			return $result;
		} else {
			return false;
		}
	}

}