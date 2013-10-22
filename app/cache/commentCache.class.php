<?php
class commentCache extends CCache{
	/**
	 * @return commentCache
	* */
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct(true);
	}
	
	function addComment( $comment , $story_id , $user_id , $level = 0 , $create_time = NOWTIME , $status = EXIST ){
		$comment .= ' '; 
		$reg = '/@[\x{4e00}-\x{9fa5}a-zA-Z0-9\_]+\ /u'; //@匹配
		preg_match_all( $reg, $comment ,$matches );
		if (!empty($matches) && isset($matches[0]) && !empty($matches[0])){
			foreach( $matches as $m ){
				if (!empty($m)){
					foreach ( $m as $u ){
						$u = trim(trim($u , '@'));
						$user = userModel::getInstance()->getUserInfoByUsername($u , 'user_id');
						if ( !empty( $user) && isset($user['user_id']) && !empty($u)){
							$content = '在故事中回复了您';
							$message_data = array('from_user_id'=>$user_id , 'to_user_id'=>$user['user_id'] , 'subject'=>'【这故事温馨提示】' ,'content'=>$content ,'created'=>NOWTIME , 'issysmsg'=>3 ) ;
							$message_id = messageCache::getInstance()->addMessage($message_data);
							$update = array('url'=>BASE_URL.'story/page/'.$story_id .'?reset=1&message='.$message_id);
							msgModel::getInstance()->updateUserChat(array('id'=>$message_id), $update);
							$comment = str_replace("@".$u,
									'<a href="'.BASE_URL.'profile/u/'.$user['user_id'].'">@'.$u.'</a>',
									$comment);
						}
					}
				}
			}
		}
		return commentModel::getInstance()->addComment( $comment , $story_id , $user_id , $level  , $create_time , $status );
	}
	
	function getCommentsByStoryId( $story_id , $status = EXIST ){
		return commentModel::getInstance()->getCommentsByStoryId($story_id , $status);
	}
	
	function getCommentLevelByStoryId( $story_id , $status = EXIST ){
		return commentModel::getInstance()->getCommentLevelByStoryId( $story_id , $status );
	}
	function __destruct(){}
}