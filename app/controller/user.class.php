<?php
class user extends CAction {
	function __construct() {
        parent::__construct('user');
    }
    
 	function __destruct(){
    }

    function do_index(){
    	webTools::goHome();
    }
   
	//用户登出
	public function do_logout() {
		$this->_logout();
		webTools::goBack();
	}

	/**
	 * #判断除用户邮箱是否存在#
	 * 	@return 当邮箱链接错误 返回 json_encode(array('code'=>HTTP_ERROR_STATUS_BADREQUEST));
	 * 			当邮箱不存在   返回 json_encode(array('code'=>HTTP_ERROR_STATUS_OK));
	 * 			当邮箱存在    返回 json_encode(array('code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR));
	 * echo json
	 */
	public function do_ajax_checkIfEmailExist() {
		$email_address = isset($_POST['e'])?$_POST['e']:'';
		$request = userCache::getInstance()->_checkIfEmailExist( $email_address );
		echo json_encode($request);		
		return true;
	}
	
	public function do_ajax_checkIfNickNameExist() {
		$username = isset($_POST['e'])?$_POST['e']:'';
		$request = userCache::getInstance()->_checkIfNickNameExist( $username );
		echo json_encode($request);
		return true;
	}
	public function do_ajax_logon(){
		$this->_logout();
		$email_address = isset($_POST['email'])?(trim($_POST['email'])):'';
		$password = isset($_POST['pwd'])?(trim($_POST['pwd'])):'';
		$set_cookie = isset($_POST['set_cookie'])?trim($_POST['set_cookie']):'false';
		$submit_method = isset($_POST['submit_method'])?trim($_POST['submit_method']):'';
		if( $set_cookie == 'true' || $set_cookie === true){
			$set_cookie = true;
		}else{
			$set_cookie = false;
		}
		$request = userCache::getInstance()->_logonActive($email_address, $password, $set_cookie );
		if ( $submit_method == 'js' ){
			echo json_encode($request);
		}else{
			if ( $request['code'] == HTTP_ERROR_STATUS_OK ){
				webTools::goBack();
			}else{
				webTools::goToUrl(BASE_URL.'signin?error='.$request['error']);
			}
		}
		return true;
	} 
	
	/**
	 * #用户注册表单提交#
	 * 	@return 当数据不正确 返回 json_encode(array('code'=>HTTP_ERROR_STATUS_BADREQUEST));
	 * 			当注册成功   返回 json_encode(array('code'=>HTTP_ERROR_STATUS_OK , 'user_id'=>$user_id));
	 * 			当邮箱已经存在等    返回 json_encode(array('code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR));
	 * echo json
	 */
	public function do_ajax_signupUser(){
		$username = isset($_POST['username']) ? $_POST['username'] : "";
		$passwd = isset($_POST['pwd']) ? $_POST['pwd'] : "";
		$email = isset($_POST['email']) ? $_POST['email'] : "";
		$re_passwd = isset($_POST['repwd']) ? $_POST['repwd'] : "";
		$submit_method = isset($_POST['submit_method'])?trim($_POST['submit_method']):'';
		
		$request = userCache::getInstance()->_signupUserActive( $username , $email , $passwd , $re_passwd );
		if ( $submit_method == 'js' ){
			echo json_encode($request);
			return true;
		}else{
			if ( $request['code'] == HTTP_ERROR_STATUS_OK ){
				webTools::goBack();
			}else{
				webTools::goToUrl(BASE_URL.'signup?error='.$request['error']);
			}
		}
	}
}