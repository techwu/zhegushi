<?php
class validate extends CAction {
	function __construct(){
		parent::__construct('welcome');
	}
	
	function do_index(){
		$name = isset($_POST['username'])?$_POST['username']:'';
		$passwd = isset($_POST['password'])?$_POST['password']:'';
		$session_id = isset($_COOKIE['PHPSESSID'])?$_COOKIE['PHPSESSID']:'';
// 		var_dump($name , $passwd);die();
		if ( !empty( $name ) && !empty( $passwd ) ){
			if( $name == $GLOBALS['under_line_user']['name'] && $passwd == $GLOBALS['under_line_user']['passwd']){
// 				var_dump($name , $passwd);die();
				setcookie(CHECK_NEW_STATION, encrypt::Sha1MD5('nishiduomewuliaocaipojiemima' . $session_id ), time() + 3600 * 24 , DEFAULT_COOKIEPATH, DEFAULT_COOKIEDOMAIN);
				webTools::goHome();
			}
		}
		$this->smarty->display(TMPDIR."grape/underline/logon.tpl.html");
	}
	
	function do_a(){
		echo "您的ip有作弊嫌疑，所以已经将您的ip禁止掉，您可以发邮件到：wu@36kr.com 启用您的ip";die();
	}
	
	function __destruct(){}
}
	