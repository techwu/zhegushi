<?php
/**
 * CSmarty的基类定义
 */
//if(!defined('GENERAL_PHP_FRAMEWORK')){exit('Access Denied');}

require_once CORE_LIB_PATH."/smarty/Smarty.class.php";
class CSmarty extends Smarty{
	function CSmarty(){
		//$this->Smarty();
		$this->template_dir = SMARTY_TMPDIR;
		$this->compile_dir = SMARTY_TMPDIRC;
		$this->compile_check = true;
		$this->caching = false;
		$this->cache_dir = SMARTY_CACHEDIR;
		$this->left_delimiter  =  SMARTY_DLEFT;
		$this->right_delimiter =  SMARTY_DRIGHT;
		$this->cache_lifetime = LIFTTIME;

		/*init variable*/
		$this->assign('BASE_MAIN_URL',BASE_MAIN_URL);
		$this->assign('BASE_URL',BASE_URL);
		$this->assign('IMAGE_PATH',IMAGE_PATH);
		$this->assign('STATIC_PATH',STATIC_PATH);
		
		$this->assign('WWW_PATH',WWW_PATH);
		$this->assign('API_PATH',API_PATH);
		
		$this->assign("version",VERSION);
		$this->assign("CSSEXT", CSSEXT);
		$this->assign("JSEXT", JSEXT);
		$this->assign("CSSPATH", CSSPATH);
		$this->assign("JSPATH", JSPATH);
		$this->assign("footer_js", "");
		$this->assign("header_js", "");
		$this->assign("header_css", "");
		$this->assign("LESSPATH",LESSPATH);
		$this->assign("LESSEXT", LESSEXT);
	}
	function setDelimiter( $left_delimiter = SMARTY_DLEFT  , $right_delimiter = SMARTY_DRIGHT ){
		$this->left_delimiter  =  $left_delimiter;
		$this->right_delimiter =  $right_delimiter;
	}
	
}
