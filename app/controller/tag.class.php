<?php
class tag extends CAction {
	function __construct(){
		parent::__construct('home');
	}

	public function do_index(){
		$tag_id = isset($this->args[0]) ? $this->args[0] : 0;
		$page = isset($this->args[1]) ? $this->args[1] : 0; 
		
		$tag_info = tagsCache::getInstance()->getTagInfoByTagId( $tag_id );
		if (empty($tag_info)){
			show404();
		}
		$request = storyCache::getInstance()->getStories( false , false , $tag_id , true , true , false , '' , 1 , $page * PER_PAGE , PER_PAGE );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		
		$pages_fliper = $this->_pageFliper( $total ,  $page , '/tag/index/'.$tag_id . '/' );
		$this->smarty->assign("pages_fliper",$pages_fliper);
		
		$this->smarty->assign( 'story_list' , $datas );
		$story_html = $this->smarty->fetch( TMPDIR."grape/container/story/pills.tmpl.html" );
		$this->smarty->assign('story_html' , $story_html);
		
		$hot_tags = tagsCache::getInstance()->getHotTags( 0 , 10 );
		
		$this->smarty->assign('tmpl' , 'main-sidebar');
		$this->smarty->assign('hot_tags' , $hot_tags);
		$validate_tags = tagsCache::getInstance()->getValidateTags( 0 , 20 );
		$this->smarty->assign('validate_tags' , $validate_tags);
		$sidebar = $this->smarty->fetch( TMPDIR."grape/common/sidebar.tmpl.html" );
		$this->smarty->assign('sidebar' , $sidebar);
		
		$frame_content = $this->smarty->fetch( TMPDIR."grape/container/story/list.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		
		$this->smarty->assign('frame_pagetitle' , $tag_info['tag'] . ',' . FRAME_TITLE);
		
		$this->smarty->display(TMPDIR."grape/frame.tmpl.html");
	}
}