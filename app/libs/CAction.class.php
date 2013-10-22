<?php
/**
 * the base action class
 */
class CAction {
	/**
	 * 
	 * @var Smarty
	 */
	var $smarty;
	var $args;
	var $argc;
	var $memcache = NULL ;
	var $is_mobile = false;
	protected static $_userid = NULL;
	protected static $_status = 0;
	protected static $_userinfo = null;
	
	function __construct( $action = null , $nav = 'normal'){
		//method for $action
		global $MEMCACHE;
		$this->smarty = new CSmarty();
		if( empty($this->memcache)){
			$this->memcache = $MEMCACHE ;
		}
		$this->is_mobile = isAndroidOrIOsMobile();
		global $_ZSESSION;
		if( isset($_ZSESSION['user_id'])){
			if ( self::$_userid == NULL || !isset(self::$_userid) ) {
				self::$_userid = $_ZSESSION['user_id'];
				self::$_status = $_ZSESSION['status'];//已经是最新，会及时更新的
				self::$_userinfo = $_ZSESSION;
				self::$_userinfo['message_num'] = messageCache::getInstance()->getUnreadMsgNumByUserid(self::$_userid) ;
			}
		} else {
			self::$_userid = 0;
			self::$_status =  0;
			self::$_userinfo = false;
		}
		$this->smarty->assign("style", 'styles.css' );
		if (FRAME_PAGE_TYPE == 'newadmin'){
			$this->_admCommonAction($action , $nav);
		}else{
			$this->smarty->assign('frame_pagetitle' ,FRAME_TITLE);
			$this->smarty->assign('frame_keywords' , FRAME_KEYWORD );
			$this->smarty->assign("frame_description", FRAME_DESCRIPTION );
			$this->smarty->assign('session_user_id' , self::$_userid);
			$this->smarty->assign('session_user_status' , self::$_status);
			$this->smarty->assign('session_user_info' , self::$_userinfo);
			
			$this->smarty->assign("navsetting", 'navbar-'.$nav);
			$this->smarty->assign("navaction", $action );
			
			$validate_tags = tagsCache::getInstance()->getValidateTags( 0 , 20 );
			$this->smarty->assign('nav_validate_tags' , $validate_tags);
			
			$frame_header = $this->smarty->fetch( TMPDIR."grape/navigation/navbar.tmpl.html" );
			$frame_foot = $this->smarty->fetch( TMPDIR."grape/footer/footer.tmpl.html" );
			$this->smarty->assign("frame_header", $frame_header);
			$this->smarty->assign("frame_footer", $frame_foot);
		}
	}
	
	function init_args($args){
		$this->args = $args;
		if ( !isset($argc) ) {
			return false;
		}
		$this->argc = count($argc);
	}
	
	function do_error($errmsg){
		echo $errmsg;
	}
	
	function do_method($method){
		$func = "do_".$method;
		$ret = method_exists($this,$func);

		if( method_exists($this,$func)==false ){
			$func = "do_".$method;
			if(method_exists($this , $func) !== false){
				$this->$func();
			}else{
				show404();
			}
		}else{
			$this->$func();
		}
	}
	
	function throwUserUnLogon(){
		$check = $this->_checkUserLogon();
		if( !$check ){
			echo(json_encode(array('error_text' => '用户未登录', 'code' => HTTP_ERROR_STATUS_FORBIDEN)));
			exit();
		}
	}

	function _checkUserLogon(){
		if( empty(self::$_userid) || empty(self::$_status) ){
			return false;
		}else{
			return true;
		}
	}
	
	public function _logout( ) {
		userCache::getInstance()->_removeCookie();
		$dbSession = new dbsession("");
		$dbSession->destroy_session();
		global $_ZSESSION;
		$_ZSESSION = null;
		self::$_userinfo = false;
		self::$_userid = 0;
		self::$_status = 0;
	}
	
	function _pageFliper( $list_total_rows , $num , $url = "" , $extra_data='' , $limit = PER_PAGE , $show_pages = 5 , $is_form = false ){
		importer('corelib.PageFliper');
		$config['base_url'] = $url;
		$config['total_rows'] = $list_total_rows;
		$config['limit'] = $limit;
		$config['show_pages'] = $show_pages;//Max number of items you want shown per page
		$config['cur_page'] = $num;
		$config['prev_link'] = '&lt;&lt;上一页';
		$config['next_link'] = '下一页&gt;&gt;';
		$config['extra_data'] = $extra_data;
		$pager = new PageFliper($config);
		return $pager->doPageFilper( $is_form );
	}
	
	function importAction( $action = null ){
		$action_arr = array();
		if(empty($action)){
			$action_arr[0] =  DEFAULT_ACTION_METHOD ;
			$action_arr[1] = DEFAULT_MOBILE_ACTION_METHOD ;
			return $action_arr;
		}
		if( strpos($action, '.') === 0){
			$action_arr[0] = DEFAULT_ACTION_METHOD;
			$action_arr[1] = substr($action , 1);
			return $action_arr;
		}
		if( strpos($action, '.') === false){
			$action_arr[0] =  $action ;
			$action_arr[1] = DEFAULT_MOBILE_ACTION_METHOD ;
			return $action_arr;
		}
		$action_arr = explode( '.' , $action);
		if(!isset($action_arr[0]) || empty($action_arr[0]) || $action_arr[0] == 'home'){
			$action_arr[0] =  DEFAULT_ACTION_METHOD ;
		}
		if(!isset($action_arr[1]) || empty($action_arr[1])){
			$action_arr[1] =  DEFAULT_MOBILE_ACTION_METHOD ;
		}
		return $action_arr;
	}
	private function _admCommonAction($action = null , $nav = 'normal'){
		$this->smarty->assign( 'session_active_page' , $action );
	
		$this->smarty->assign('frame_pagetitle' ,'');
		$this->smarty->assign('frame_keywords' ,'');
		$this->smarty->assign("frame_description", '' );
		$this->smarty->assign('session_user_id' , self::$_userid);
		$this->smarty->assign('session_user_status' , self::$_status);
		$this->smarty->assign('session_user_info' , self::$_userinfo);
	
		$this->smarty->assign("navsetting", 'navbar-'.$nav);
		$frame_header = $this->smarty->fetch( TMPDIR."admin/navigation/navbar.tmpl.html" );
		$frame_foot = $this->smarty->fetch( TMPDIR."admin/footer/footer.tmpl.html" );
		$this->smarty->assign("frame_header", $frame_header);
		$this->smarty->assign("frame_footer", $frame_foot);
	}
	
	public function getAdmCommonTime( $action = DEFAULT_ACTION_METHOD , $method = DEFAULT_METHOD_METHOD , $breadcrumb = 'home' , $title = '数据统计' ,  $coordinate = '数据统计' ){
		if (empty(self::$_userid)){
			webTools::goLogon();
		}
		$begin_date = isset($_GET['begin'])&&!empty($_GET['begin']) ? $_GET['begin'] : date('Y-m-d' , 0 )  ;
		$end_date = isset($_GET['end'])&&!empty($_GET['end']) ? $_GET['end'] : date('Y-m-d') . ' 23:59:59'  ;
		$end = strtotime( $end_date ) ;
		$begin = strtotime( $begin_date ) ;
		$breadcrumb_arr = array('home'=>'<li><i class="icon-home"></i>&nbsp;<a href="">首页</a></li>' ,
				'product'=>'<li><a href="story">故事</a></li>' ,
				'circle'=>'<li><a href="user">用户</a></li>' ,
				'timeline'=>'<li><a href="tag">标签</a></li>' ,
				'event'=>'<li><a href="album">专题</a></li>' ,
				'timeline'=>'<li><a href="comment">评论</a></li>' ,
		);
		$breadcrumb = explode('>', $breadcrumb);
		$breadcrumb_str = '';
		if (!empty($breadcrumb)){
			foreach ( $breadcrumb as $k=>$b ){
				$breadcrumb_str .= isset($breadcrumb_arr[$b]) ? $breadcrumb_arr[$b] : '' ;
			}
		}
		$begin_date = date('Y-m-d' , $begin);
		$end_date = date('Y-m-d' , $end);
		$this->smarty->assign( 'begin' , $begin_date );
		$this->smarty->assign( 'end' , $end_date );
		$this->smarty->assign('action' , $action);
		$this->smarty->assign('method' , $method);
		$this->smarty->assign('title' , $title);
		$this->smarty->assign('coordinate' , $coordinate);
		$this->smarty->assign('breadcrumb' , $breadcrumb_str);
		$sidebar = $this->smarty->fetch( TMPDIR."admin/common/sidebar/sidebar.tmpl.html" );
		$this->smarty->assign('sidebar' , $sidebar);
	
		$breadcrumb = $this->smarty->fetch( TMPDIR."admin/common/breadcrumb/breadcrumb.tmpl.html" );
		$this->smarty->assign('breadcrumb' , $breadcrumb);
		return array('begin'=>$begin , 'end'=>$end , 'begin_date'=>$begin_date , 'end_date'=>$end_date);
	}
}

