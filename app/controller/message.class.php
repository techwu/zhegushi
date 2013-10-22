<?php
class message extends CAction{
	function __construct(){
		parent::__construct('user');
	}
	
	function __destruct(){}
	
	public function do_ajax_getUserUnreadMessage(){
		$user_id = isset ($_POST['uid']) ? intval( $_POST['uid'] ): 0;
		if ( $this->_checkUserLogon() === false || $user_id != self::$_userid ){
			echo json_encode( array( 'code'=>HTTP_ERROR_STATUS_BADREQUEST , 'result'=>false , 'error'=>'请登录之后查看' ) );
			return false;
		}
		$message = messageCache::getInstance()->getUnreadMsgsByUserid( $user_id ) ;
// 		msgModel::getInstance()->updateUserChat(array('to_user_id'=>$user_id) , array('status'=>READ));
		$this->smarty->assign('message' , $message);
		$content = $this->smarty->fetch( TMPDIR."grape/container/message/message.unread.tmpl.html" );
		echo json_encode( array( 'code'=>HTTP_ERROR_STATUS_OK , 'result'=>array('content'=>$content , 'message_num'=>self::$_userinfo['message_num']) , 'error'=>'' ) );
		return false;
	}
}