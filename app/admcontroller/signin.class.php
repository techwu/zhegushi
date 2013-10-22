<?php
class signin extends CAction {
	function __construct(){
		parent::__construct('user' );
	}

	function do_index(){
		$this->_logout();
// 		echo 1;die();
		$frame_content = $this->smarty->fetch( TMPDIR."admin/container/sign/signin.tmpl.html" );
		$this->smarty->assign('frame_content' , $frame_content);
		$this->smarty->display(TMPDIR."admin/frame.tmpl.html");
	}
}