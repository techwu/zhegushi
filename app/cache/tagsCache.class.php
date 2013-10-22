<?php
class tagsCache extends CCache{
	/**
	 * @return tagsCache
	* */
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct(true);
	}
	
	function addTag( $story_id , $tags , $sum = 0 , $status = EXIST , $linked = 0 ){
		if ( empty( $tags ) ){
			return array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'不能为空' );
		}
		$tag_arr = explode( ' ' , $tags );
		if ( !empty( $tag_arr ) ){
			foreach ( $tag_arr as $tag ){
				if ( !empty($tag) ){
					$tag_id = tagsModel::getInstance()->addTag( $tag );
					if ( !empty( $tag_id ) ){
						storyTagsModel::getInstance()->addStoryBindTag($story_id, $tag_id);
					}
				}
			}
		}
		return array( 'code'=>HTTP_ERROR_STATUS_OK , 'result'=>false , 'error'=>'发布成功' );
	}
	
	function getTagInfoByTagId( $tag_id ){
		return tagsModel::getInstance()->getTagInfoByTagId( $tag_id );
	}
	
	function getTagsByStoryId( $story_id , $status = EXIST ){
		return storyTagsModel::getInstance()->getStoryTagsByStoryId( $story_id , $status );
	}
	
	function getHotTags( $start = 0 , $limit = PER_PAGE , $status = EXIST ) {
		return storyTagsModel::getInstance()->getHotTags($start , $limit , $status);
	}
	
	function getValidateTags( $start = 0 , $limit = PER_PAGE , $status = EXIST ){
		return tagsModel::getInstance()->getValidateTags( $start , $limit , $status );
	}
	
	function __destruct(){}
}