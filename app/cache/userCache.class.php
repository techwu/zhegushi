<?php
class userCache extends CCache{
	/**
	 * @return userCache
	 */
	protected static $_userid = NULL;
	protected static $_status = 0;
	protected static $_userinfo = null;
	
	function __construct(){
		global $_ZSESSION;
		// 		var_dump($_ZSESSION);die();
		if( isset($_ZSESSION['user_id'])){
			if ( self::$_userid == NULL || !isset(self::$_userid) ) {
				self::$_userid = $_ZSESSION['user_id'];
				self::$_status = $_ZSESSION['status'];//已经是最新，会及时更新的
				self::$_userinfo = $_ZSESSION;
			}
		} else {
			self::$_userid = 0;
			self::$_status =  0;
			self::$_userinfo = '';
		}
		parent::__construct(true);
	}


	function __destruct(){}

	static function getInstance(){
		return parent::getInstance(get_class());
	}

	public function getAllUserNumberByTime( $begin , $end ){
		return userModel::getInstance()->getAllUserNumberByTime( $begin , $end );
	}
	
	public function _updateUserSessionByActiveCode($active_code ,  $refresh = false ){
		$user_info = userModel::getInstance()->getUserBaseInfoByHash($active_code);
		global $_ZSESSION;
		$_ZSESSION = $user_info;
		self::$_userid = $_ZSESSION['user_id'];
		self::$_status = $_ZSESSION['status'];//已经是最新，会及时更新的
		self::$_userinfo = $_ZSESSION;
		setcookie(DEFAULT_COOKIE_LOGON, '' , time() + 600, '/');
		return $this->_logonSetSession( $user_info , $refresh);
	}
	
	public function _logonSetSession( $userInfo , $refresh = false)  {
		if ( empty($userInfo) ) {
			return false;
		}
		$this->_setUserSession( $userInfo , $refresh);
		return true;
	}
	
	public function _setUserSession ( $userInfo , $refresh = false ) {
		$dbSession = new dbsession( $userInfo , $refresh);
	}
	
	/**
	 * 实现用户登录的功能函数
	 * @param String $emailAddress
	 * @param String $password
	 * @param bool $set_cookie
	 */
	public function _logonActive( $email_address , $password , $set_cookie=false ) { //just for one kind user
		if(empty($email_address) || empty($password)){
			return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error'=>"邮箱或密码不正确！" ));
		}
		$checkStatus = userModel::getInstance()->getUserInfoByEmailAndPassword( $email_address , $password );
		if ( $checkStatus === false ) {
			$DEFAULT_COOKIE_LOGON = isset($_COOKIE[DEFAULT_COOKIE_LOGON])?$_COOKIE[DEFAULT_COOKIE_LOGON]:0;
			$DEFAULT_COOKIE_LOGON ++;
			setcookie(DEFAULT_COOKIE_LOGON, $DEFAULT_COOKIE_LOGON, time() + 600, '/');
			if($DEFAULT_COOKIE_LOGON <= 2){
				return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error'=>'用户名或密码不正确')) ;
			}else{
				return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error'=>'用户名或密码不正确')) ;
			}
		} else {
			if ( $checkStatus === false ) {
				return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error'=>'用户名或密码不正确')) ;
			}
			global $_ZSESSION;
			$_ZSESSION = $checkStatus[0];
			
			$setStatus = $this->_logonSetSession( $checkStatus[0] );
			
			setcookie(DEFAULT_COOKIE_LOGON, '' , time() + 600, '/');
	
			if( isset($set_cookie) && ( $set_cookie == "true" || $set_cookie == true ) ) {
				//如果选中了记住密码
				$this->_setCookie( $checkStatus[0]['user_id'], $email_address, $password);
			} else {
				$cookie_key = ITEA_VC_USER_COOKIE;
				$cookie_value = '';
				$cookie_expire = time() + 3600*24 ;
				$cookie_path = DEFAULT_COOKIEPATH;
				$cookie_domian = DEFAULT_COOKIEDOMAIN;
				setcookie($cookie_key, $cookie_value, $cookie_expire, $cookie_path, $cookie_domian);
				userExpansionModel::getInstance()->updateUserCookie($checkStatus[0]['user_id'], "");
			}
			return (array('code'=>HTTP_ERROR_STATUS_OK , 'error'=>'登陆成功')) ;
		}
	}
	
	public function _signupUserActive( $username , $email , $passwd , $re_passwd ){
		if( empty( $email )  ){
			return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST, 'error'=>'注册邮箱不能为空' , 'error_id'=>'email'));
		}
		
		$email_validate = filter_var($email, FILTER_VALIDATE_EMAIL);
		// 		|| empty($passwd) || strlen($passwd) < 6 || ! || empty($re_passwd) || $re_passwd!=$passwd
		if( empty( $email ) || !$email_validate ){
			return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST, 'error'=>'注册邮箱格式不正确' , 'error_id'=>'email'));
		}
		
		if ( empty($passwd) || strlen($passwd) < 6 || empty($re_passwd) || $re_passwd!=$passwd ){
			return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST, 'error'=>'注册密码填写出错' , 'error_id'=>'passwd'));
		}
		
		$active_code = md5(commFun::getUniqueId());
		
		$user_info = array( 'username'=>$username , 'email'=>$email , 'password'=>encrypt::Sha1MD5($passwd) , 'avatar'=>'avatar/avatar'.rand(1, 89).'.jpg' , 'hash'=>$active_code , 'status'=>USER_STATUS_REGISTER , 'created'=>NOWTIME , 'updated'=>NOWTIME );
		$insert_id = userModel::getInstance()->insertUserInfo( $user_info );
		
		if( isset( $insert_id ) && is_numeric( $insert_id ) && $insert_id > 0) {
			$callback = isset($_COOKIE['COOKIE_CALLBACK_URL']) && !empty($_COOKIE['COOKIE_CALLBACK_URL'])?trim($_COOKIE['COOKIE_CALLBACK_URL']) : '';
			//发送激活邮件
			$title = '【这故事温馨提示】恭喜您邮箱注册成功';
			$content = '感谢您注册这故事，您已经拥有喜欢故事、评论故事、分享故事等权限，希望我们能让您得到快乐';
			$request = mailCache::getInstance()->_sendEmail( $email , $username , $title , $content , '' , '' , '' , MAIL_SENDTYPE_DIRECT);
			userCache::getInstance()->_updateUserSessionByUid( $insert_id ,  true );
			return (array('code'=>HTTP_ERROR_STATUS_OK , 'result'=>array('user_id'=>$insert_id) , 'error'=>'注册成功'));
		}else{
			return (array('code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR, 'error'=>'注册失败，或者邮箱已经被注册！'));
		}
	}
	
	public function _checkIfEmailExist( $email_address ){
		if( empty($email_address) ) {
			return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST, 'error'=>'邮箱格式不正确'));
		}
		$email_validate = filter_var($email_address, FILTER_VALIDATE_EMAIL);
		if(empty($email_address) || !$email_validate) {
			return (array('code'=>HTTP_ERROR_STATUS_BADREQUEST, 'error'=>'邮箱格式不正确'));
		}
		$checkStatus = userModel::getInstance()->checkIfEmailExist( $email_address);
		if ($checkStatus === false) {
			return (array('code'=>HTTP_ERROR_STATUS_OK, 'error'=>'邮箱可以使用'));
		} else {
			return (array('code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR, 'error'=>'邮箱已被使用'));
		}
	}
	
	public function _checkIfNickNameExist( $nickname ){
		if( empty($nickname) ) {
			return (array('code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR, 'error'=>'昵称不能为空'));
		}
		importer('corelib.userlib');
		$user = new userlib();
		$result = $user->checkStrLegal($nickname);
		if( $result == 2 ) {
			return (array('code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR, 'error'=>'昵称过长'));
		}else if ($result == 3 ){
			return (array('code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR, 'error'=>'昵称填写包含非法字符'));
		}
		$checkStatus = userModel::getInstance()->checkIfUserNameExist( $nickname);
		if ($checkStatus === false) {
			return (array('code'=>HTTP_ERROR_STATUS_OK, 'error'=>''));
		} else {
			return (array('code'=>HTTP_ERROR_STATUS_INTERNALSERVERERROR, 'error'=>'此昵称太热了。'));
		}
	}
	
	public function _userLogin($userId, $type ) {
		$baseInfo = userModel::getInstance()->getUserInfoByUserId( $userId );
		if ( empty($baseInfo) ) {
			return false;
		}
		$dbSession = new dbsession( $baseInfo );
		$this->_setCookie($userId , $type . '-' .$baseInfo['email'] , encrypt::Sha1Md5($baseInfo['come_id']));
		self::$_userid = $userId;
		return true;
	}
	
	//删除cookie
	public function _removeCookie() {
		setcookie(ITEA_VC_USER_COOKIE,'', time()-3600, DEFAULT_COOKIEPATH, DEFAULT_COOKIEDOMAIN);
		setcookie(DEFAULT_SESSION_NAME,'', time()-3600, DEFAULT_COOKIEPATH, DEFAULT_COOKIEDOMAIN);
		setcookie(RE_VC_SESSION_COOKIE, '', time()-3600, DEFAULT_COOKIEPATH, DEFAULT_COOKIEDOMAIN);
		setcookie(DEFAULT_COOKIE_LOGON, 0, time()-3600, DEFAULT_COOKIEPATH, DEFAULT_COOKIEDOMAIN);
		setcookie("PHPSESSID",'', time()-3600, DEFAULT_COOKIEPATH, DEFAULT_COOKIEDOMAIN);
	}
	//设置用户cookie
	public function _setCookie( $userId , $emailAddress, $password, $time="") {
		$cookie_key = ITEA_VC_USER_COOKIE;
		$cookie_value = md5(encrypt::Sha1Md5($emailAddress . $password) . time());
		if($time == ''){
			$cookie_expire = time() + 3600 ;
		}else{
			$cookie_expire = time() + $time;
		}
		$cookie_path = DEFAULT_COOKIEPATH;
		$cookie_domian = DEFAULT_COOKIEDOMAIN;
		setcookie($cookie_key, $cookie_value, $cookie_expire, $cookie_path, $cookie_domian);
		userExpansionModel::getInstance()->updateUserCookie($userId, $cookie_value);
	}
	
	/**
	 * 为用户生成Session
	 * @param unknown_type $userInfo
	 */
	public function _updateUserSessionByUid($user_id ,  $refresh = false ){
		$user_info = userModel::getInstance()->getUserInfoByUserId($user_id);
		global $_ZSESSION;
		$_ZSESSION = $user_info;
		self::$_userid = $_ZSESSION['user_id'];
		self::$_status = $_ZSESSION['status'];//已经是最新，会及时更新的
		self::$_userinfo = $_ZSESSION;
		setcookie(DEFAULT_COOKIE_LOGON, 0 , time() + 600, '/');
	
		return $this->_logonSetSession( $user_info , $refresh);
	}
}