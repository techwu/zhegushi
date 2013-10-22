<?php
class me extends CAction {
	function __construct(){
		parent::__construct('user');
	}

	public function do_index(){
		if ( empty( self::$_userid ) ){
			webTools::goHome();
		}
		$page = isset($this->args[0]) ? $this->args[0] : 0; 
		$request = storyCache::getInstance()->getStories( false , self::$_userid , false , true , true , false , '' , array( 0 , 1 ) , $page * PER_PAGE , PER_PAGE );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		
		$pages_fliper = $this->_pageFliper( $total ,  $page , '/me/index/' );
		$this->smarty->assign("pages_fliper",$pages_fliper);
		
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		
		$this->smarty->assign( 'story_list' , $datas );
		$story_html = $this->smarty->fetch( TMPDIR."grape/container/story/pills.tmpl.html" );
		$this->smarty->assign('story_html' , $story_html);
		
		$this->smarty->assign('user' , self::$_userinfo);
		$this->smarty->assign('story_total' , $total );
		$profile = $this->smarty->fetch( TMPDIR."grape/container/profile/profile.block.tmpl.html" );
		$this->smarty->assign('profile' , $profile);
		
		$frame_content = $this->smarty->fetch( TMPDIR."grape/container/profile/me.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		
		$this->smarty->assign('frame_pagetitle' , '我的小故事,' . FRAME_TITLE);
		
		$this->smarty->display(TMPDIR."grape/frame.tmpl.html");
	}
}