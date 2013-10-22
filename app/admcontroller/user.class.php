<?php
class user extends CAction {
	function __construct() {
        parent::__construct();
    } 
    
    function __destruct(){}
	
    function do_index(){
    	webTools::goHome();
    }
    
    public function do_logout() {
    	userCache::getInstance()->_logout();
    	if(isset($_COOKIE[COOKIE_CALLBACK_URL]) && !empty($_COOKIE[COOKIE_CALLBACK_URL])){
    		webTools::goToUrl(BASE_URL . $_COOKIE[COOKIE_CALLBACK_URL]);
    	}else{
    		webTools::goHome();
    	}
    }
    
    public function do_ajax_logon(){
    	$user = isset($_POST['email'])?$_POST['email']:'' ;
    	$password = isset($_POST['pwd'])?$_POST['pwd']:'' ;
    	$submit_method = isset($_POST['submit_method'])?trim($_POST['submit_method']):'';
    	
    	$request = adminCache::getInstance()->adminSignin($user, $password);
    	if ( $submit_method == 'js' ){
	    	echo json_encode( $request );
    	}else{
    		if ( $request['code'] == HTTP_ERROR_STATUS_OK ) {
    			webTools::goHome();
    		}else{
    			webTools::goLogon();
    		}
    	}
    }
}
