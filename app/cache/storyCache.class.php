<?php
class storyCache extends CCache{
	/**
	 * @return storyCache
	* */
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct(true);
	}
	
	function updateStoryInfoByStoryId( $story_id , $update ){
		$result = storyModel::getInstance()->resetStoryByStoryId( $story_id , $update );
		if ( !empty($result) ){
			return array( 'code'=>HTTP_ERROR_STATUS_OK , 'result'=>false , 'error'=>'更新成功' );
		}else{
			return array( 'code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR , 'result'=>false , 'error'=>'更新失败' );
		}
	}
	
	function recommStory( $story_id ){
		storyRecommModel::getInstance()->addStoryRecomm($story_id);
		return array( 'code'=>HTTP_ERROR_STATUS_OK , 'result'=>false , 'error'=>'更新成功' );
	}
	
	function unRecommStory( $story_id ){
		storyRecommModel::getInstance()->updateStoryRecomm(array('story_id'=>$story_id) ,array('status'=>DELETED ));
		return array( 'code'=>HTTP_ERROR_STATUS_OK , 'result'=>false , 'error'=>'更新成功' );
	}
	
	function addStory( $user_id , $story , $img = '' , $title = '' , $tags = '' , $up = 0 , $down = 0 , $comments = 0  , $is_v = 1 , $status = EXIST ){
		if ( empty($user_id) ){
			return (array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'发布信息需要先登录' ));
		}
		if ( empty( $story ) ){
			return array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'不能为空' );
		}
// 		importer('corelib.harmonious');
// 		$check = lib_lawless_string_filter($story);
// 		if($check ){
// 			return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'您的故事中含有非法关键词'));
// 		}
		$story .= ' ';
		$reg = '/@[\x{4e00}-\x{9fa5}a-zA-Z0-9\_]+\ /u'; //@匹配
		preg_match_all( $reg, $story ,$matches );
		$messages = array();
		if (!empty($matches) && isset($matches[0]) && !empty($matches[0])){
			foreach( $matches as $m ){
				if (!empty($m)){
					foreach ( $m as $u ){
						$u = trim(trim($u , '@'));
						$user = userModel::getInstance()->getUserInfoByUsername($u , 'user_id');
						if ( !empty( $user) && isset($user['user_id']) && !empty($u)){
							$content = '在故事中@了您';
							$message_data = array('from_user_id'=>$user_id , 'to_user_id'=>$user['user_id'] , 'subject'=>'【这故事温馨提示】' ,'content'=>$content ,'created'=>NOWTIME , 'issysmsg'=>3 ) ;
							$message_id = messageCache::getInstance()->addMessage($message_data);
							$story = str_replace("@".$u,
									'<a href="'.BASE_URL.'profile/u/'.$user['user_id'].'">@'.$u.'</a>',
									$story);
							$messages[] = $message_id;
						}
					}
				}
			}
		}
		
		$story_id = storyModel::getInstance()->addStory($user_id, $story , $img , $title , $up , $down , $comments  , $is_v , NOWTIME , $status );
		if (!empty($messages)){
			foreach ( $messages as $message_id ){
				$update = array('url'=>BASE_URL.'story/page/'.$story_id .'?reset=1&message='.$message_id);
				msgModel::getInstance()->updateUserChat(array('id'=>$message_id), $update);
			}
		}
		
		if ( $story_id == false ){
			return array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'发布失败' );
		}else{
			tagsCache::getInstance()->addTag( $story_id , $tags );
			return array( 'code'=>HTTP_ERROR_STATUS_OK , 'result'=>array('id'=>$story) , 'error'=>'发布成功' );
		}
	}
	
	function updateStory( $id , $title , $story , $img = '' , $tags = ''){
		if ( empty($id) ){
			return (array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'发布信息需要先登录' ));
		}
		if ( empty( $story ) ){
			return array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'不能为空' );
		}
		$story_id = storyModel::getInstance()->resetStoryByStoryId($id, array('title'=>$title , 'story'=>$story ) );
		if ( empty($story_id ) ){
			return array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'发布失败' );
		}else{
			storyTagsModel::getInstance()->resetStoryBindTag(array('status'=>0), array('story_id'=>$id) );
			tagsCache::getInstance()->addTag( $id , $tags );
			return array( 'code'=>HTTP_ERROR_STATUS_OK , 'result'=>array( 'id'=>$id ) , 'error'=>'发布成功' );
		}
	}
	
	function getRecommStories( $create_user_ids = false , $tag_ids = false , $addition_user = false , $addition_tags = false , $addition_edits = false , $orderby = 'find_in_set' , $is_v = 1  , $start = 0 , $limit = PER_PAGE , $begin = 0 , $end = NOWTIME , $status = EXIST ){
		$story_ids = storyRecommModel::getInstance()->getRecommStoryIds();
		if ( empty( $story_ids ) ){
			return false;
		}
		$story_ids = DataToArray($story_ids, 'story_id');
		return $this->getStories( $story_ids , $create_user_ids , $tag_ids , $addition_user , $addition_tags  , $addition_edits , $orderby , $is_v   , $start , $limit  , $begin, $end , $status );
	}
	
	function getStoryCount( $has_story_ids = false , $create_user_ids = false , $tag_ids = false , $is_v = 1 , $begin = 0 , $end = NOWTIME , $status = EXIST ){
		return storyModel::getInstance()->getStoryCounts( $has_story_ids , $create_user_ids , $tag_ids , $is_v , $begin , $end , $status);
	}
	
	function getStories( $has_story_ids = false , $create_user_ids = false , $tag_ids = false , $addition_user = false , $addition_tags = false , $addition_edits = false , $orderby = 'id' , $is_v = 1  , $start = 0 , $limit = PER_PAGE , $begin = 0 , $end = NOWTIME , $status = EXIST ){
		$stories = storyModel::getInstance()->getStories( $has_story_ids , $create_user_ids , $tag_ids , $orderby , $is_v , $start , $limit , $begin , $end , $status );
		
		if (!empty( $stories)){
			foreach ( $stories as &$s ){
				$s['share_story'] = '#这故事分享#'.c_substr($s['story'], 0 , 120 );
				if ( $addition_user ){
					$s['user_info'] = userModel::getInstance()->getUserInfoByUserId($s['user_id']);
				}
				if ( $addition_tags ){
					$s['tags_info'] = tagsCache::getInstance()->getTagsByStoryId($s['id']); 
				}
				if ( $addition_edits ){
					$s['action_info'] = storyActionModel::getInstance()->getActionsByStoryId( $s['id'] , array( STORY_ACTION_CREATE , STORY_ACTION_DELETE , STORY_ACTION_UPDATE ) );
				}
				$s['is_recommended'] = storyRecommModel::getInstance()->isExistRecommByStoryId( $s['id'] );
				$s['create'] = timeStrConverter( $s['create_time'] );
				$s['comment'] = commentCache::getInstance()->getCommentsByStoryId($s['id']);
			}
		}
		$story_count = $this->getStoryCount( $has_story_ids , $create_user_ids , $tag_ids , $is_v , $begin , $end , $status );
		return array('code'=>HTTP_ERROR_STATUS_OK , 'result'=>array( 'num'=>$story_count , 'data'=>$stories ) , 'error'=>'' );
	}
	
	function addUserActionStory( $user_id , $story_id , $action ){
		if ( !in_array( $action , array( STORY_ACTION_UP , STORY_ACTION_DOWN , STORY_ACTION_COMMENTS ) ) ){
			return array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'操作失败！' );
		}
		
		$check = storyActionModel::getInstance()->addStoryAction($story_id, $user_id , $action);
		$story = storyModel::getInstance()->getStoryByStoryId( $story_id , 1 , EXIST , 'user_id' );
		if ( $check !== false && !empty($story) && isset($story['user_id']) ){
			if ($story['user_id'] != $user_id ){
				if ( $action ==  STORY_ACTION_UP ){
					$content = '赞了您的故事';
				} elseif (  $action ==  STORY_ACTION_COMMENTS ){
					$content = '评论了您的故事';
				}else{
					$content = '与您进行互动';
				}
				$message_data = array('from_user_id'=>$user_id , 'to_user_id'=>$story['user_id'] , 'subject'=>'这故事温馨提示' ,'content'=>$content ,'created'=>NOWTIME , 'issysmsg'=>3 ) ;
				$message_id = messageCache::getInstance()->addMessage($message_data);
				$update = array('url'=>BASE_URL.'story/page/'.$story_id .'?reset=1&message='.$message_id);
				msgModel::getInstance()->updateUserChat(array('id'=>$message_id), $update);
			}
		}
		$up = storyActionModel::getInstance()->getActionNumsByStoryIdAndAction( $story_id , STORY_ACTION_UP );
		$down = storyActionModel::getInstance()->getActionNumsByStoryIdAndAction( $story_id , STORY_ACTION_DOWN );
		$comments = storyActionModel::getInstance()->getActionNumsByStoryIdAndAction( $story_id , STORY_ACTION_COMMENTS );
		
		storyModel::getInstance()->resetStoryByStoryId( $story_id , array( 'up'=>$up , 'down'=>$down , 'comments'=>$comments ));
		
		return array('code'=>HTTP_ERROR_STATUS_OK , 'result'=>array( 'up'=>$up , 'down'=>$down , 'comments'=>$comments ) , 'error'=>'' );
	}
	
	function __destruct(){}
}