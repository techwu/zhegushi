<?php
class storyActionModel extends CModel{
	/**
	 * @return storyActionModel
	 */
	
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	function __construct(){
		parent::__construct(array(
				'id' ,
				'story_id' ,
				'user_id' ,
				'action' ,
				'create_time'
				) ,
				DB_TABLE_STORY_ACTION );
	}
	
	function __destruct(){}
	
	function addStoryAction( $story_id , $user_id , $action = STORY_ACTION_CREATE , $create_time = NOWTIME ){
		$iData = array();
		if (empty($story_id)){
			return false;
		}
		$iData['story_id'] = $story_id;
		$iData['user_id'] = $user_id ;
		$iData['action'] = $action ;
		$iData['create_time'] = $create_time ;
		if ( in_array($action , array( STORY_ACTION_UP , STORY_ACTION_DOWN ) ) ){
			$check = $this->checkExistStoryAction($story_id, $user_id);
			if ( $check !== false ){
				$this->resetStoryAction( $iData , array('id'=>$check ) );
				return false; 
			}
		}
		return $this->insertStoryAction($iData);
	}
	
	function insertStoryAction($iData){
		if ( !isset( $iData['story_id'] ) || empty($iData['story_id']) ){
			return false;
		}
		$user_id = $this->addNewData($iData);
		if ($user_id){
			return $user_id;
		}else{
			return false;
		}
	}
	
	function resetStoryAction( $update , $where){
		$result = self::$db->updateField ( $where , $update ,$this->table );
		return $result;
	}
	
	function checkExistStoryAction ( $story_id , $user_id , $action = array( STORY_ACTION_UP , STORY_ACTION_DOWN ) ){
		if ( empty($action) || empty( $story_id ) || empty($user_id) ){
			return false;
		}
		
		$sql_where_ext = '' ;
		$sql_data = array() ;
		if (is_array( $action )){
			foreach ( $action as $k=>$a ){
				$sql_where_ext .= ' :action_' . $k . ',' ;
				$sql_data['action_'.$k] = $a ;
			}
			$sql_where_ext = trim( $sql_where_ext , ',' ) ;
		}else{
			$sql_where_ext = ' :action ' ;
			$sql_data['action'] = $action ;
		}
		
		$sql_comm = "select id from " . $this->table . ' where story_id = :story_id and user_id = :user_id and action in ('.$sql_where_ext.') ';
		
		$sql_data['story_id'] = $story_id;
		$sql_data['user_id'] = $user_id;
		$result = array();
		self::$db->getQueryData( $result, $sql_comm, $sql_data );
		if ( isset($result[0]) && !empty($result[0])) {
			return $result[0]['id'];
		} else {
			return false;
		}
	}
	
	function getActionsByStoryId( $story_id , $action = array( STORY_ACTION_CREATE , STORY_ACTION_DELETE , STORY_ACTION_UPDATE , STORY_ACTION_VALIDATE ) ){
// 		$fields = $this->processFields( $fields );
		if ( empty($action) || empty( $story_id ) ){
			return false;
		}
		$sql_where_ext = '' ;
		$sql_data = array() ;
		if (is_array( $action )){
			foreach ( $action as $k=>$a ){
				$sql_where_ext .= ' :action_' . $k . ',' ;
				$sql_data['action_'.$k] = $a ;
			}
			$sql_where_ext = trim( $sql_where_ext , ',' ) ;
		}else{
			$sql_where_ext = ' :action ' ;
			$sql_data['action'] = $action ;
		}
		$sql_comm = "select * from " . $this->table . " dtsa left join  ". DB_TABLE_USER .
					' dtu on dtsa.user_id = dtu.user_id ' .
					' where dtsa.story_id = :story_id  and dtsa.action in ('.$sql_where_ext.') order by dtsa.create_time desc ';
		$sql_data['story_id'] = $story_id;
		$result = array();
		self::$db->getQueryData( $result, $sql_comm, $sql_data );
		if ( isset($result[0]) && !empty($result[0])) {
			return $result;
		} else {
			return false;
		}
	}
	
	function getActionNumsByStoryIdAndAction( $story_id , $action = array( STORY_ACTION_CREATE , STORY_ACTION_DELETE , STORY_ACTION_UPDATE , STORY_ACTION_VALIDATE ) ){
		if ( empty($action) || empty( $story_id ) ){
			return 0;
		}
		$sql_where_ext = '' ;
		$sql_data = array() ;
		if (is_array( $action )){
			foreach ( $action as $k=>$a ){
				$sql_where_ext .= ' :action_' . $k . ',' ;
				$sql_data['action_'.$k] = $a ;
			}
			$sql_where_ext = trim( $sql_where_ext , ',' ) ;
		}else{
			$sql_where_ext = ' :action ' ;
			$sql_data['action'] = $action ;
		}
		$sql_comm = "select count(user_id) sum from " . $this->table .
					' where story_id = :story_id  and action in ('.$sql_where_ext.') ';
		$sql_data['story_id'] = $story_id;
		$result = array();
		self::$db->getQueryData( $result, $sql_comm, $sql_data );
		if ( isset($result[0]) && !empty($result[0])) {
			return $result[0]['sum'];
		} else {
			return 0;
		}
	}
}