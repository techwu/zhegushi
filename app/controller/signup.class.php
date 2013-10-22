<?php
class signup extends CAction {
	function __construct(){
		parent::__construct('user' );
	}

	function do_index(){
		$this->_logout();
		$error = isset($_GET['error']) ? $_GET['error'] : '';
		$this->smarty->assign('error' , $error);
		
		$frame_content = $this->smarty->fetch( TMPDIR."grape/container/sign/signup.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		
		$this->smarty->display(TMPDIR."grape/frame.tmpl.html");
	}
}