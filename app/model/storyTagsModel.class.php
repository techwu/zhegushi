<?php
class storyTagsModel extends CModel{
	/**
	 * @return storyTagsModel
	 */
	
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	function __construct(){
		parent::__construct(array(
								'id' ,
								'story_id' ,
								'tag_id' ,
								'status' ,
				 			) , 
							DB_TABLE_STORY_TAGS );
	}
	function __destruct(){}
	
	function addStoryBindTag( $story_id , $tag_id , $status = EXIST ){
		$iData = array();
		if (empty($story_id) || empty($story_id)){
			return false;
		}
		$iData['story_id'] = $story_id;
		$iData['tag_id'] = $tag_id;
		$iData['status'] = $status ;
	
		return $this->insertStoryBindTag($iData);
	}
	
	function insertStoryBindTag($iData){
		if ( !isset( $iData['story_id'] ) || empty($iData['story_id']) ){
			return false;
		}
		return $this->addNewData($iData);
	}
	
	function resetStoryBindTag( $update , $where ){
		$result = self::$db->updateField ( $where, $update ,$this->table );
		return $result;
	}
	
	function getHotTags( $start = 0 , $limit = PER_PAGE , $status = EXIST ) {
		$sql_comm = "select distinct dtt.id , dtt.* from " . $this->table . " dtst left join  ". DB_TABLE_TAGS .
					' dtt on dtt.id = dtst.tag_id and dtt.status = ' . EXIST .
					' left join ' . DB_TABLE_STORY . ' dts on dtst.story_id = dts.id and dts.status = ' . EXIST . ' and dts.is_v = 1 ' .
					" where dtst.status = :status and dts.id <> 0 and dts.id is not null order by sum desc , dtt.id asc limit {$start},{$limit}";
		$sql_data['status'] = $status ;
// 		echo $sql_comm ;die();
		$result = array();
		self::$db->getQueryData( $result, $sql_comm, $sql_data );
		if ( isset($result[0]) && !empty($result[0])) {
			return $result;
		} else {
			return false;
		}
	}
	
	function getStoryTagsByStoryId( $story_id , $status = EXIST ){
// 		$fields = $this->processFields( $fields );
		$sql_comm = "select * from " . $this->table . " dtst left join  ". DB_TABLE_TAGS .
					' dtt on dtt.id = dtst.tag_id and dtt.status = '.EXIST.
					' where dtst.story_id = :story_id and dtst.status = :status order by dtst.id asc ';
		$sql_data['story_id'] = $story_id;
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