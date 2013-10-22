<?php
class notification extends CAction{
	function __construct(){
		parent::__construct('user');
	}
	
	function __destruct(){}
	
	function do_index(){
		$this->do_list();
	}
	function do_list(){
		$page =  isset($this->args[1])?trim($this->args[1]):0;;
		$list_num = messageCache::getInstance()->_getMessageContactNum(self::$_userid , 1);
		
		self::$_userinfo['notification'] = 0 ;
		$this->smarty->assign('session_user_info' , self::$_userinfo);
		$frame_header = $this->smarty->fetch( TMPDIR."grape/navigation/navbar.tmpl.html" );
		
		$this->smarty->assign("frame_header", $frame_header);
		
		msgModel::getInstance()->updateMsgReadByUserid( self::$_userid , 1 );
		
		$messages = messageCache::getInstance()->_getUserMessageList(self::$_userid , 1 , 0);
		$page_fliper = $this->_pageFliper($list_num, $page , 'notification/list/' , 'vc');
		$this->smarty->assign('messages' , $messages);
		
		$this->smarty->assign('page_fliper' , $page_fliper);
		$this->smarty->assign('avatar' , commFun::getRealAvatarUrl( '' ));
		
		$frame_content = $this->smarty->fetch(TMPDIR."grape/container/message/notification.tmpl.html");//TODO
		$this->smarty->assign('frame_content' , $frame_content);
		$this->smarty->display(TMPDIR."grape/frame.tmpl.html");
	}
}