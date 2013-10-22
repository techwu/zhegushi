<?php
class storyModel extends CModel{
	/**
	 * @return storyModel
	 */
	
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	function __construct(){
		parent::__construct(array(
								'id' ,
								'user_id' ,
								'story' ,
								'title' ,
								'img' ,
								'is_v' ,
								'up',
								'down' ,
								'comments' ,
								'create_time' ,
								'status' ,
				 			) , 
							DB_TABLE_STORY );
	}
	
	function __destruct(){}
	
	function addStory( $user_id , $story , $img = '' , $title = '' , $up = 0 , $down = 0 , $comments = 0 , $is_v = 0 , $create_time = NOWTIME , $status = EXIST ){
		$iData = array();
		if (empty($story)){
			return false;
		}
		$iData['user_id'] = $user_id;
		$iData['img'] = $img;
		$iData['story'] = $story;
		$iData['title'] = $title;
		$iData['up'] = $up;
		$iData['down'] = $down;
		$iData['comments'] = $comments ;
		$iData['is_v'] = $is_v;
		$iData['create_time'] = $create_time;
		$iData['status'] = $status ;
	
		return $this->insertStory($iData);
	}
	
	function insertStory($iData){
		if ( !isset( $iData['story'] ) || empty($iData['story']) ){
			return false;
		}
		$id = $this->addNewData($iData);
		storyActionModel::getInstance()->addStoryAction( $id, $iData['user_id']) ;
		return $id ; 
	}
	
	function resetStoryByStoryId( $story_id , $update ){
		$result = self::$db->updateField ( array('id'=>$story_id) , $update ,$this->table );
		return $result;
	}
	
	function addStoryUp( $story_id ){
		$sql_comm = 'update ' . $this->table . ' set up = up+1 where id = :id ';
		$sql_data['id'] = $story_id ;  
		return $this->queryWrite ( $sql_comm, $sql_comm );
	}
	
	function addStoryDown( $story_id ){
		$sql_comm = 'update ' . $this->table . ' set down = down + 1 where id = :id ';
		$sql_data['id'] = $story_id ;
		return $this->queryWrite ( $sql_comm, $sql_comm );
	}
	
	function addStoryComments( $story_id ){
		$sql_comm = 'update ' . $this->table . ' set comments = comments + 1 where id = :id ';
		$sql_data['id'] = $story_id ;
		return $this->queryWrite ( $sql_comm, $sql_comm );
	}
	
	function getStoryByStoryId( $story_id , $is_v = 1 , $status = EXIST , $fields = '' ){
		$fields = $this->processFields($fields);
		$sqlComm = "select {$fields} from " . DB_TABLE_STORY . ' dts ' .
					"  where dts.is_v = :is_v and dts.status = :status and dts.id =:story_id";
		$sql_data['status'] = $status ;
		$sql_data['is_v'] = $is_v ;
		$sql_data['story_id'] = $story_id ;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sql_data );
		if ( !empty( $result ) && isset( $result[0] ) ) {
			foreach ( $result as &$r ){
				if (isset($r['img']) && !empty($r['img'])){
					$r['img'] = commFun::getRealAvatarUrl( $r['img'] , IMAGE_PATH );
				}
			}
			return $result[0];
		}else{
			return false ;
		}
	}
	
	function getStoryCounts( $story_ids = false , $user_ids = false , $tag_ids = false , $is_v = 1 , $begin = 0 , $end = NOWTIME , $status = EXIST ){
		$sql_user_where = '';
		$sql_data = array() ;
		if ( $user_ids !== false ){
			if (empty( $user_ids ) ){
				return false;
			}else{
				if ( is_array( $user_ids ) ){
					foreach ( $user_ids as $k=>$u ){
						$sql_data['user_id_'.$k] = $u ;
						$sql_user_where .= ':user_id_' . $k . ',' ;
					}
					$sql_user_where = trim( $sql_user_where , ',');
				}else{
					$sql_data['user_id'] = $user_ids ;
					$sql_user_where .= ':user_id ';
				}
				$sql_user_where = ' dts.user_id in (' . $sql_user_where . ') ';
			}
		}
		$sql_tags_where = '' ;
		$sql_tags_ext = '' ;
		$sql_tags_lefton = '' ;
		if ( $tag_ids !== false ){
			if ( empty($tag_ids) ){
				return false;
			}else {
				$sql_tags_ext = DB_TABLE_STORY_TAGS . ' dtst left join ';
				$sql_tags_lefton = ' on dts.id = dtst.story_id and dtst.status = '.EXIST ;
				if ( is_array( $tag_ids ) ){
					foreach ( $tag_ids as $k=>$t ){
						$sql_data['tags_id_'.$k] = $t ;
						$sql_user_where .= ':tags_id_' . $k . ',' ;
					}
					$sql_tags_where = trim( $sql_tags_where , ',');
				}else{
					$sql_data['tags_id'] = $tag_ids ;
					$sql_tags_where .= ':tags_id ';
				}
			}
		}
		$sql_story_ids_where = '' ;
		if ( $story_ids !== false ){
			if ( empty( $story_ids ) ){
				return false;
			}else{
				if ( is_array( $story_ids ) ){
					foreach ( $story_ids as $k=>$i ){
						$sql_data['story_id_'.$k] = $i ;
						$sql_story_ids_where .= ':story_id_' . $k . ',' ;
					}
					$sql_story_ids_where = trim( $sql_story_ids_where , ',') ;
				}else{
					$sql_data['story_id'] = $story_ids ;
					$sql_story_ids_where = ':story_id ';
				}
				$sql_story_ids_where = ' dts.id in (' . $sql_story_ids_where . ') and ';
			}
		}
		
		$sql_where = '' ;
		if ( !empty($sql_story_ids_where)){
			$sql_where = $sql_story_ids_where . $sql_where;
		}
		if ( !empty( $sql_user_where ) ){
			$sql_where .= $sql_user_where . ' and ';
		}
		if ( !empty( $sql_tags_where ) ){
			$sql_where .= ' dtst.tag_id in ('.$sql_tags_where . ') and ';
		}
		
		$sql_isv_where = '' ;
		if ( empty( $is_v ) ){
			$is_v = 0 ;
		}
		if ( is_array( $is_v ) ){
			foreach ( $is_v as $k=>$i ){
				$sql_data['is_v_'.$k] = $i ;
				$sql_isv_where .= ':is_v_' . $k . ',' ;
			}
			$sql_isv_where = trim( $sql_isv_where , ',');
		}else{
			$sql_data['is_v'] = $is_v ;
			$sql_isv_where = ':is_v ';
		}
		$sqlComm = 'select count(dts.id) sum from  ' . $sql_tags_ext . DB_TABLE_STORY . ' dts '.  $sql_tags_lefton .
					"  where {$sql_where} dts.is_v in ({$sql_isv_where}) and dts.status = :status and dts.create_time > :begin and dts.create_time < :end ";
		$sql_data['status'] = $status ;
		$sql_data['begin'] = $begin ;
		$sql_data['end'] = $end ;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sql_data );
// 		var_dump($result, $sqlComm, $sql_data);die();
		if ( !empty( $result ) && isset( $result[0] ) ) {
			return $result[0]['sum'];
		}else{
			return 0 ;
		}
	}
	
	function getStories( $story_ids = false , $user_ids = false , $tag_ids = false , $orderby = 'id' , $is_v = 1 , $start = 0 , $limit = PER_PAGE , $begin = 0 , $end = NOWTIME , $status = EXIST ){
		$sql_user_where = '';
		$sql_data = array() ;
		if ( $user_ids !== false ){
			if (empty( $user_ids ) ){
				return false;
			}else{
				if ( is_array( $user_ids ) ){
					foreach ( $user_ids as $k=>$u ){
						$sql_data['user_id_'.$k] = $u ;
						$sql_user_where .= ':user_id_' . $k . ',' ;
					}
					$sql_user_where = trim( $sql_user_where , ',');
				}else{
					$sql_data['user_id'] = $user_ids ;
					$sql_user_where .= ':user_id ';
				}
				$sql_user_where = ' dts.user_id in (' . $sql_user_where . ') ';
			}
		}
		$sql_tags_where = '' ;
		$sql_tags_ext = '' ;
		$sql_tags_lefton = '' ;
		if ( $tag_ids !== false ){
			if ( empty($tag_ids) ){
				return false;
			}else {
				$sql_tags_ext = DB_TABLE_STORY_TAGS . ' dtst left join ';
				$sql_tags_lefton = ' on dts.id = dtst.story_id and dtst.status = '.EXIST ;
				if ( is_array( $tag_ids ) ){
					foreach ( $tag_ids as $k=>$t ){
						$sql_data['tags_id_'.$k] = $t ;
						$sql_user_where .= ':tags_id_' . $k . ',' ;
					}
					$sql_tags_where = trim( $sql_tags_where , ',');
				}else{
					$sql_data['tags_id'] = $tag_ids ;
					$sql_tags_where .= ':tags_id ';
				}
			}
		}
		$sql_story_ids_where = '' ;
		if ( $story_ids !== false ){
			if ( empty( $story_ids ) ){
				return false;
			}else{
				if ( is_array( $story_ids ) ){
					foreach ( $story_ids as $k=>$i ){
						$sql_data['story_id_'.$k] = $i ;
						$sql_story_ids_where .= ':story_id_' . $k . ',' ;
					}
					$sql_story_ids_where = trim( $sql_story_ids_where , ',') ;
				}else{
					$sql_data['story_id'] = $story_ids ;
					$sql_story_ids_where = ':story_id ';
				}
				$sql_story_ids_where = ' dts.id in (' . $sql_story_ids_where . ') and ';
			}
		}
		
		$sql_where = '' ;
		if ( !empty($sql_story_ids_where)){
			$sql_where = $sql_story_ids_where . $sql_where;
		}
		if ( !empty( $sql_user_where ) ){
			$sql_where .=  $sql_user_where . ' and ';
		}
		if ( !empty( $sql_tags_where ) ){
			$sql_where .= ' dtst.tag_id in ('.$sql_tags_where . ') and ';
		}
		
		$sql_isv_where = '' ;
		if ( empty( $is_v ) ){
			$is_v = 0 ;
		}
		if ( is_array( $is_v ) ){
			foreach ( $is_v as $k=>$i ){
				$sql_data['is_v_'.$k] = $i ;
				$sql_isv_where .= ':is_v_' . $k . ',' ;
			}
			$sql_isv_where = trim( $sql_isv_where , ',');
		}else{
			$sql_data['is_v'] = $is_v ;
			$sql_isv_where = ':is_v ';
		}
		$oder_sql = '';
		$select_ext = '';
		if ( empty ($orderby ) || $orderby == 'id' || $orderby == 'newest') {
			$oder_sql = 'dts.id desc' ;
		}elseif ( $orderby == 'hot'){
			$select_ext = ', (dts.up + dts.comments)*1000000/('. NOWTIME . '- dts.create_time ) as ord ' ;
			$oder_sql = ' ord desc , dts.id desc ';
		}elseif ( $orderby == 'find_in_set' && !empty( $story_ids ) ){
			$story_ids = implode(',', $story_ids);
			$oder_sql = ' find_in_set(id , '.$story_ids.') ';
		}
		
		$sqlComm = "select distinct(dts.id) id , dts.* {$select_ext} from  {$sql_tags_ext} " . DB_TABLE_STORY . ' dts '.  $sql_tags_lefton .
					"  where {$sql_where} dts.is_v in ({$sql_isv_where}) and dts.status = :status and dts.create_time > :begin ".
					" and dts.create_time < :end  order by {$oder_sql} limit {$start},{$limit}";
		$sql_data['status'] = $status ;
		$sql_data['begin'] = $begin ;
		$sql_data['end'] = $end ;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sql_data );
// 		if ( isset($_GET['debug'] ) && $_GET['debug'] == 'wumin'){
// 			var_dump( $result, $sqlComm, $sql_data );die();
// 		}
		if ( !empty( $result ) && isset( $result[0] ) ) {
			foreach ( $result as &$r ){
				if (!empty($r['img'])){
					$r['img'] = commFun::getRealAvatarUrl( $r['img'] , IMAGE_PATH );
				}
			}
			return $result;
		}else{
			return false ;
		}
	}
}