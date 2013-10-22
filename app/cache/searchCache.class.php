<?php
class searchCache extends CCache{
	/**
	 * @return searchCache
	 */
	function __construct(){
		parent::__construct(true);
	}
	
	
	function __destruct(){}
	
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	public function _searchAlbum($searchKey , $start=0 , $page_size = BIG_PAGE_SIZE) {
		return array(21, 22, 23 , 24 , 25 , 27);
		$res = searchModel::getInstance()->searchAlbum($searchKey , true , $start , $page_size);
		if ( $res===false ) {
			$res = searchModel::getInstance()->searchAlbum($searchKey, false ,$start , $page_size);
		}
		if ( $res===false ) {
			return false;
		}
		if ( isset($res['matches']) && is_array($res["matches"]) ) {
			$ids = DataToArray($res['matches'], 'id');
		} else {
			$ids = array();
		}
		return $ids;
	}
	
	public function _searchTimeline($searchKey , $page = 0 , $page_size = PAGE_SIZE){
		return array(9139 ,9138 , 9137 , 9136 , 9135 , 9134 , 9122 );
	}
	
	
	
}