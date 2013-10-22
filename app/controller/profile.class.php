<?php
class profile extends CAction {
	function __construct(){
		parent::__construct('user');
	}

	public function do_u(){
		$user_id = isset($this->args[0]) ? $this->args[0] : 0; 
		$page = isset($this->args[1]) ? $this->args[1] : 0;
		$user_info = userModel::getInstance()->getUserInfoByUserId($user_id);
		if ($user_info === false){
			show404();
		}
		$request = storyCache::getInstance()->getStories( false , $user_id , false , true , true , false , '' ,  1 , $page * PER_PAGE , PER_PAGE );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
// 		var_dump($request);die();
		$pages_fliper = $this->_pageFliper( $total ,  $page , '/profile/u/'.$user_id.'/' );
		$this->smarty->assign("pages_fliper",$pages_fliper);
		
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		
		$this->smarty->assign( 'story_list' , $datas );
		$story_html = $this->smarty->fetch( TMPDIR."grape/container/story/pills.tmpl.html" );
		$this->smarty->assign('story_html' , $story_html);
		
		$this->smarty->assign('user' , $user_info);
		$this->smarty->assign('story_total' , $total );
		$profile = $this->smarty->fetch( TMPDIR."grape/container/profile/profile.block.tmpl.html" );
		$this->smarty->assign('profile' , $profile);
		
		$frame_content = $this->smarty->fetch( TMPDIR."grape/container/profile/me.tmpl.html" );
		
		$this->smarty->assign('frame_pagetitle' , $user_info['username'] . '-' . FRAME_TITLE);
		
		$this->smarty->assign('frame_content' , $frame_content);
		$this->smarty->display(TMPDIR."grape/frame.tmpl.html");
	}
}