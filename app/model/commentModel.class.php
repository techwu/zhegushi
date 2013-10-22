<?php
class commentModel extends CModel{
	/**
	 * @return commentModel
	 */
	
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	function __construct(){
		parent::__construct(array(
								'id' ,
								'comment' ,
								'story_id' ,
								'user_id' ,
								'level' ,
								'create_time' ,
								'status'
				 			) , 
							DB_TABLE_COMMENT );
	}
	
	function __destruct(){}
	
	function addComment( $comment , $story_id , $user_id , $level = 0 , $create_time = NOWTIME , $status = EXIST ){
		$iData = array();
		if (empty($comment)){
			return false;
		}
		$iData['comment'] = $comment ;
		$iData['story_id'] = $story_id ;
		$iData['user_id'] = $user_id;
		$iData['level'] = $level;
		$iData['create_time'] = $create_time;
		$iData['status'] = $status ;
		
		return $this->insertComment($iData);
	}
	
	function insertComment($iData){
		if ( !isset( $iData['comment'] ) || empty($iData['comment']) ){
			return false;
		}
		return $this->addNewData($iData);
	}
	
	function getCommentsByStoryId( $story_id , $status = EXIST ){
		$sqlComm = "select * from ".$this->table . ' dtc left join ' . DB_TABLE_USER . ' dtu on dtc.user_id = dtu.user_id ' . 
					' where dtc.story_id = :story_id and dtc.status =:status order by id asc ';
		$result = array();
		$sqlData= array('story_id'=>$story_id , 'status'=>$status);
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if( !empty($result) )	{
			foreach ($result as &$res){
				$res['avatar'] = commFun::getRealAvatarUrl( $res['avatar'] , IMAGE_PATH );
				$res['create_time'] = timeStrConverter( $res['create_time']);
			}
			return $result;
		}else{
			return false;
		}
	}
	
	function getCommentLevelByStoryId( $story_id , $status = EXIST ){
		$sqlComm = "select max(level) level from ".$this->table . ' dtc left join ' . DB_TABLE_USER . ' dtu on dtc.user_id = dtu.user_id ' .
					' where dtc.story_id = :story_id and dtc.status =:status order by id asc ';
		$result = array();
		$sqlData= array('story_id'=>$story_id , 'status'=>$status);
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if( isset($result[0]) && !empty($result[0]) )	{
			return $result[0]['level'];
		}else{
			return 0;
		}
	}
}