<?php
/**
 * commFun class.
 * 用于定义一些比较常用但是又很零碎的功能函数
 * @author wangxi <wangxi0618@gmail.com>
 */ 

class webTools {
	static function gotoUrlThen( $destUrl ) {
		echo "<script>window.location=\"{$destUrl}\"</script>";
	}
	
	static function goToUrl ( $destUrl) {
		webTools::headerToUrl( $destUrl );
// 		echo "<script>window.location=\"{$destUrl}\"</script>";
	}
	//用header跳转
	static function headerToUrl ( $destUrl, $extHeader=array() ) {
		if( !empty($extHeader) ) {
			foreach($extHeader as $v) {
				header($v);
			}
		}
		header("Location: {$destUrl}");
	}
	
	static function goRoot( ) {
		webTools::goToUrl("/");
	}
	static function goHome() {
		webTools::goRoot( );
	}
	
	static function goLogon(){
		webTools::goToUrl( BASE_URL . LOGON_URL );
	}
	static function goBack(){
		if(isset($_COOKIE[COOKIE_CALLBACK_URL]) && !empty($_COOKIE[COOKIE_CALLBACK_URL])){
			webTools::goToUrl(BASE_URL . $_COOKIE[COOKIE_CALLBACK_URL]);
		}else{
			webTools::goHome();
		}
	}
}

