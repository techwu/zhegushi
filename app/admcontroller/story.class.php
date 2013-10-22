<?php
class story extends CAction {
	function __construct(){
		parent::__construct('home');
	}

	public function do_index(){
		$times = $this->getAdmCommonTime( 'story' , 'index' , 'story' , '所有故事' ,  '故事列表' );
		$page = isset($this->args[0]) ? $this->args[0] : 0; 
		$request = storyCache::getInstance()->getStories( false , false , false , true , true , false , '' , array( 0 , 1 ) , $page * PER_PAGE , PER_PAGE );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		
		$pages_fliper = $this->_pageFliper( $total ,  $page , '/story/index/' );
		$this->smarty->assign("pages_fliper",$pages_fliper);
		
		$this->smarty->assign( "story_list" , $datas );
		$this->smarty->assign( "story_count" , $total );
		
		$story_content = $this->smarty->fetch( TMPDIR."admin/container/story/story.pills.tmpl.html" );
		$this->smarty->assign( "story_content" , $story_content );
		
		$data_content = $this->smarty->fetch( TMPDIR."admin/container/story/list.tmpl.html" );
		$this->smarty->assign( 'data_content' , $data_content );
		
		$frame_content = $this->smarty->fetch( TMPDIR."admin/container/welcome.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		$this->smarty->display(TMPDIR."admin/frame.tmpl.html");
	}
	
	public function do_validate(){
		$times = $this->getAdmCommonTime( 'story' , 'validate' , 'story' , '待审核故事' ,  '待审核列表' );
		$page = isset($this->args[0]) ? $this->args[0] : 0;
		$request = storyCache::getInstance()->getStories( false , false , false , true , true , false , '' , 0 , $page * PER_PAGE , PER_PAGE );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		
		$pages_fliper = $this->_pageFliper( $total ,  $page , '/story/validate/' );
		$this->smarty->assign("pages_fliper",$pages_fliper);
		
		$this->smarty->assign( "story_list" , $datas );
		$this->smarty->assign( "story_count" , $total );
		
		$story_content = $this->smarty->fetch( TMPDIR."admin/container/story/story.pills.tmpl.html" );
		$this->smarty->assign( "story_content" , $story_content );
		
		$data_content = $this->smarty->fetch( TMPDIR."admin/container/story/list.tmpl.html" );
		$this->smarty->assign( 'data_content' , $data_content );
		
		$frame_content = $this->smarty->fetch( TMPDIR."admin/container/welcome.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		$this->smarty->display(TMPDIR."admin/frame.tmpl.html");
	}
	
	public function do_validated(){
		$times = $this->getAdmCommonTime( 'story' , 'validated' , 'story' , '已审核故事' ,  '已审核故事列表' );
		$page = isset($this->args[0]) ? $this->args[0] : 0;
		$request = storyCache::getInstance()->getStories( false , false , false , true , true , false , '' , 1 , $page * PER_PAGE , PER_PAGE );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
	
		$pages_fliper = $this->_pageFliper( $total ,  $page , '/story/validated/' );
		$this->smarty->assign("pages_fliper",$pages_fliper);
	
		$this->smarty->assign( "story_list" , $datas );
		$this->smarty->assign( "story_count" , $total );
	
		$story_content = $this->smarty->fetch( TMPDIR."admin/container/story/story.pills.tmpl.html" );
		$this->smarty->assign( "story_content" , $story_content );
	
		$data_content = $this->smarty->fetch( TMPDIR."admin/container/story/list.tmpl.html" );
		$this->smarty->assign( 'data_content' , $data_content );
	
		$frame_content = $this->smarty->fetch( TMPDIR."admin/container/welcome.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		$this->smarty->display(TMPDIR."admin/frame.tmpl.html");
	}
	
	public function do_recommended(){
		$times = $this->getAdmCommonTime( 'story' , 'recommended' , 'story' , '推荐故事' ,  '已推荐故事列表' );
		$page = isset($this->args[0]) ? $this->args[0] : 0;
		$request = storyCache::getInstance()->getRecommStories( false , false , true , true , false , 'find_in_set' , 1 , $page * PER_PAGE , PER_PAGE );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		
		$pages_fliper = $this->_pageFliper( $total ,  $page , '/story/recommended/' );
		$this->smarty->assign("pages_fliper",$pages_fliper);
		
		$this->smarty->assign( "story_list" , $datas );
		$this->smarty->assign( "story_count" , $total );
		
		$story_content = $this->smarty->fetch( TMPDIR."admin/container/story/story.pills.tmpl.html" );
		$this->smarty->assign( "story_content" , $story_content );
		
		$data_content = $this->smarty->fetch( TMPDIR."admin/container/story/list.tmpl.html" );
		$this->smarty->assign( 'data_content' , $data_content );
		
		$frame_content = $this->smarty->fetch( TMPDIR."admin/container/welcome.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		$this->smarty->display(TMPDIR."admin/frame.tmpl.html");
	}
	
	public function do_page(){
		$times = $this->getAdmCommonTime( 'story' , 'page' , 'story' , '推荐故事' ,  '故事单页' );
		$story_id = isset($this->args[0]) ? $this->args[0] : 0;
		$edit = isset( $_GET['edit'] ) ? $_GET['edit'] : '' ; 
		if (empty( $story_id )){
			show404();
		}
		$request = storyCache::getInstance()->getStories( $story_id  , false , false , true , true , false , '' ,  1 );
		$total = $request['result']['num'] ;
		$datas = $request['result']['data'] ;
		$pages_fliper = $this->_pageFliper( $total ,  0 , '/story/index/' );
		$this->smarty->assign("pages_fliper",$pages_fliper);
		
		$this->smarty->assign( "story_count" , $total );
		if ( $edit != 'true'){
			$this->smarty->assign( "story_list" , $datas );
			$story_content = $this->smarty->fetch( TMPDIR."admin/container/story/story.pills.tmpl.html" );
		}else{
			$validate_tags = tagsCache::getInstance()->getValidateTags( 0 , 20 );
			$this->smarty->assign('nav_validate_tags' , $validate_tags);
				
			$this->smarty->assign( "story" , $datas[0] );
			$story_content = $this->smarty->fetch( TMPDIR."admin/container/story/story.edit.tmpl.html" );
		}
		$this->smarty->assign( "story_content" , $story_content );
			
		$data_content = $this->smarty->fetch( TMPDIR."admin/container/story/list.tmpl.html" );
		$this->smarty->assign( 'data_content' , $data_content );
		
		$frame_content = $this->smarty->fetch( TMPDIR."admin/container/welcome.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		$this->smarty->display(TMPDIR."admin/frame.tmpl.html");
		
	}
	
	public function do_ajax_editStory(){
		$this->throwUserUnLogon();
		$title = isset($_POST['t']) ? $_POST['t'] : "";
		$story = isset($_POST['c']) ? $_POST['c'] : "";
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$tags = isset($_POST['tags']) ? $_POST['tags'] : "";
		$submit_method = isset($_POST['submit_method'])?trim($_POST['submit_method']):'';
		
		$request = storyCache::getInstance()->updateStory( $id, $title , $story , '' , $tags );
		if ( $submit_method == 'js' ){
			if ( $this->_checkUserLogon() === false ){
				echo json_encode(array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'发布信息需要先登录' ));
				return false;
			}
			echo json_encode($request);
			return true;
		}
	}
	
	public function do_ajax_operateStory(){
		$this->throwUserUnLogon();
		$operate   =isset($_POST['operate']) ?  $_POST['operate'] : "" ;
		$story_id   =isset($_POST['story_id']) ?  $_POST['story_id'] : 0 ;
		$story = storyCache::getInstance()->getStories ( $story_id ) ;
		if (empty($story)){
			echo json_encode(array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error_text'=>'操作无效'));
			return false;
		}
		switch( $operate ){
			case 'release':
				$update = array('is_v'=>1);
				$result = storyCache::getInstance()->updateStoryInfoByStoryId( $story_id , $update );
				echo json_encode($result);
				break;
			case 'delete':
				$update = array('status'=>0 , 'is_v'=>1);
				$result = storyCache::getInstance()->updateStoryInfoByStoryId( $story_id , $update );
				echo json_encode($result);
				break;
			case 'reset':
				$update = array( 'is_v'=>0 );
				$result = storyCache::getInstance()->updateStoryInfoByStoryId( $story_id , $update );
				echo json_encode($result);
				break;
			case 'recommend':
				$update = array('status'=>EXIST , 'is_v'=>1);
				$result = storyCache::getInstance()->updateStoryInfoByStoryId( $story_id , $update );
				
				storyCache::getInstance()->recommStory( $story_id );
				echo json_encode(array('code'=>HTTP_ERROR_STATUS_OK , 'error_text'=>'操作成功'));
				return true;
				break;
			case 'unrecommend':
				storyCache::getInstance()->unRecommStory( $story_id );
				echo json_encode(array('code'=>HTTP_ERROR_STATUS_OK , 'error_text'=>'操作成功'));
				return true;
				break;
			default:
				echo json_encode(array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error_text'=>'操作无效'));
				return false;
				break;
		}
	}
}