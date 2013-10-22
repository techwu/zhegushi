<?php
class welcome extends CAction {
	function __construct(){
		parent::__construct('welcome');
	}

	function do_index(){
		$times = $this->getAdmCommonTime();
		//所有的故事数
		$story_count = storyCache::getInstance()->getStoryCount( false , false , false , array( 0 , 1 ) , $times['begin'] , $times['end'] );
		$story_count_add = storyCache::getInstance()->getStoryCount( false , false , false , array( 0 , 1 ) , $times['begin'] , $times['end'] );
		//审核通过的故事数
		$v_story_count = storyCache::getInstance()->getStoryCount( false , false , false , 1 , $times['begin'] , $times['end'] );
		$v_story_count_add = storyCache::getInstance()->getStoryCount( false , false , false , 1 , $times['begin'] , $times['end'] );
		
		$this->smarty->assign( 'story_count', $story_count );
		$this->smarty->assign( 'story_count_add', $story_count_add );
		$this->smarty->assign( 'v_story_count', $v_story_count );
		$this->smarty->assign( 'v_story_count_add', $v_story_count_add );
		$story_data_html = $this->smarty->fetch( TMPDIR."admin/container/dashboard/story.tables.tmpl.html" );
		$this->smarty->assign( 'story_data_html' , $story_data_html );
		
		//人物数据
		$user_count_add = userCache::getInstance()->getAllUserNumberByTime( $times['begin'] , $times['end'] );
		$user_count = userCache::getInstance()->getAllUserNumberByTime( $times['begin'] , $times['end'] );
		$this->smarty->assign( 'user_count', $user_count );
		$this->smarty->assign( 'user_count_add', $user_count_add );
		$user_data_html = $this->smarty->fetch( TMPDIR."admin/container/dashboard/user.tables.tmpl.html" );
		$this->smarty->assign( 'user_data_html' , $user_data_html );
		
		$data_content = $this->smarty->fetch( TMPDIR."admin/container/home/home.tmpl.html" );
		$this->smarty->assign( 'data_content' , $data_content );
		
		$frame_content = $this->smarty->fetch( TMPDIR."admin/container/welcome.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		$this->smarty->display(TMPDIR."admin/frame.tmpl.html");
	}
}