<?php
class story extends CAction {
	function __construct(){
		parent::__construct('home');
	}

	public function do_index(){
		$page = isset($this->args[0]) ? $this->args[0] : 0; 
		$request = storyCache::getInstance()->getStories( false , false , false , true , true , false , '' ,  1 , $page * PER_PAGE , PER_PAGE );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		
		$pages_fliper = $this->_pageFliper( $total ,  $page , '/story/index/' );
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
		
		$this->smarty->display(TMPDIR."grape/frame.tmpl.html");
	}
	
	public function do_page(){
		$story_id = isset($this->args[0]) ? $this->args[0] : 0;
		if (empty( $story_id )){
			show404();
		}
		$reset = isset($_GET['reset']) ? $_GET['reset'] : 0 ;
		if ( $reset == 1 && self::$_userid > 0){
			$message_id = isset($_GET['message']) ? $_GET['message'] : 0 ;
			if (!empty($message_id)){
				msgModel::getInstance()->updateUserChat(array('id'=>$message_id) , array('status'=>READ));
			}
			self::$_userinfo['message_num'] -- ;
			$this->smarty->assign('session_user_info' , self::$_userinfo);
			$frame_header = $this->smarty->fetch( TMPDIR."grape/navigation/navbar.tmpl.html" );
			$this->smarty->assign("frame_header", $frame_header);
		}
		$request = storyCache::getInstance()->getStories( $story_id  , false , false , true , true , false , '' ,  1 );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		if ( empty( $datas[0]) ){
			show404();
		}
		$pages_fliper = $this->_pageFliper( $total ,  0 , '/story/index/' );
		$this->smarty->assign("pages_fliper",$pages_fliper);
		$this->smarty->assign("page",'page');
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
		$tags = '好故事' ;
		if ( isset($datas[0]['tags_info']) && !empty($datas[0]['tags_info']) ){
			foreach ( $datas[0]['tags_info'] as $t ){
				$tags = $t['tag'] . ','. $tags ;
			}
		}
		$tags .= ',';
		
		$this->smarty->assign('frame_pagetitle' , $datas[0]['title'] .'-'. FRAME_TITLE);
		$this->smarty->assign('frame_keywords' , $tags . FRAME_KEYWORD );
		$this->smarty->assign("frame_description", htmlspecialchars($datas[0]['story']) );
		
		$this->smarty->display(TMPDIR."grape/frame.tmpl.html");
	}
	
	public function do_add(){
		$frame_content = $this->smarty->fetch( TMPDIR."grape/container/story/add.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		
		$this->smarty->display(TMPDIR."grape/frame.tmpl.html");
	}
	
	public function do_ajax_addStory(){
		$story = isset($_POST['c']) ? $_POST['c'] : "";
		$t = isset($_POST['t']) ? $_POST['t'] : "";
		$user_id = isset($_POST['uid']) ? $_POST['uid'] : 0;
		$pic = isset($_POST['pic']) ? $_POST['pic'] : '';
		$tags = isset($_POST['tags']) ? $_POST['tags'] : "";
		$submit_method = isset($_POST['submit_method'])?trim($_POST['submit_method']):'';
		$request = storyCache::getInstance()->addStory( $user_id, $story , $pic , $t , $tags );
		if ( $submit_method == 'js' ){
			if ( $this->_checkUserLogon() === false || $user_id != self::$_userid ){
				echo json_encode(array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'发布信息需要先登录' ));
				return false;
			}
			echo json_encode($request);
			return true;
		}else{
			if ( $request['code'] == HTTP_ERROR_STATUS_OK ){
				webTools::goBack();
			}else{
				webTools::goToUrl(BASE_URL.'signup?error='.$request['error']);
			}
		}
	}
	
	public function do_ajax_addUserActionStory(){
		$user_id = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
		$story_id = isset($_POST['sid']) ? $_POST['sid'] : 0 ;
		$action = isset($_POST['action']) ? $_POST['action'] : STORY_ACTION_UP ;
		
		if ( $this->_checkUserLogon() === false || $user_id != self::$_userid ){
			echo json_encode( array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'请先登录' ) );
			return false;
		}
		if ( !in_array( $action , array( STORY_ACTION_UP , STORY_ACTION_DOWN , STORY_ACTION_COMMENTS ) ) ){
			echo json_encode( array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'操作失败！' ) );
			return false ;
		}
		$request = storyCache::getInstance()->addUserActionStory( $user_id, $story_id , $action );
		echo json_encode($request);
		return true;
	}
	
	public function do_ajax_getComments(){
		$story_id = isset($_POST['sid']) ? $_POST['sid'] : 0 ;
		if (empty($story_id)){
			echo json_encode( array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'操作失败！' ) );
			return true ;
		}
		$comments = commentCache::getInstance()->getCommentsByStoryId($story_id);
		$this->smarty->assign('comments' , $comments);
		$html = $this->smarty->fetch( TMPDIR."grape/container/comments/comments.tmpl.html" );
		echo json_encode( array( 'code'=>HTTP_ERROR_STATUS_OK , 'result'=>array('comments'=>$html) , 'error'=>'' ) );
	}
	
	public function do_ajax_addUserComments(){
		$story_id = isset($_POST['sid']) ? $_POST['sid'] : 0 ;
		$comment = isset($_POST['comment']) ? $_POST['comment'] : '' ;
		$submit_method = isset($_POST['submit_method'])?trim($_POST['submit_method']):'';
		$result = array();
		if (empty($story_id)){
			$result = ( array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'操作失败！' ) );
		}
		if ( $this->_checkUserLogon() === false ){
			$result = ( array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'请先登录' ) );
		}
		importer('corelib.harmonious');
		$check = lib_lawless_string_filter($comment);
		if($check ){
			echo json_encode(array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'您发送的信息含有非法关键词'));
			return false;
		}
		if ( $submit_method == 'js' ){
			if ( !empty($result) ){
				echo json_encode($result);
				return false;
			}
			$request = storyCache::getInstance()->addUserActionStory( self::$_userid, $story_id , STORY_ACTION_COMMENTS );
			if ( $request['code'] != HTTP_ERROR_STATUS_OK ){
				echo json_encode( array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'操作失败！' ) );
				return true ;
			}
			$level = commentCache::getInstance()->getCommentLevelByStoryId( $story_id );
			
			commentCache::getInstance()->addComment( $comment , $story_id , self::$_userid , $level+1 );
			$comments = commentCache::getInstance()->getCommentsByStoryId($story_id);
			$this->smarty->assign('comments' , $comments);
			
			$html = $this->smarty->fetch( TMPDIR."grape/container/comments/comments.tmpl.html" );
			echo json_encode( array( 'code'=>HTTP_ERROR_STATUS_OK , 'result'=>array('comments'=>$html , 'comment_num'=>$request['result']['comments'] ) , 'error'=>'' ) );
		}else{
			if ( !empty($result) ){
				webTools::goBack();
				return false;
			}
			$request = storyCache::getInstance()->addUserActionStory( self::$_userid, $story_id , STORY_ACTION_COMMENTS );
			if ( $request['code'] != HTTP_ERROR_STATUS_OK ){
				webTools::goBack();
				return true ;
			}
			$level = commentCache::getInstance()->getCommentLevelByStoryId( $story_id );
			commentCache::getInstance()->addComment( $comment , $story_id , self::$_userid , $level+1 );
			webTools::goBack();
		}
	}
}