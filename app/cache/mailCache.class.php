<?php
class mailCache extends CCache{
	/**
	 * @return mailCache
	 */
	function __construct(){
		parent::__construct(true);
	}
	
	
	function __destruct(){}
	
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	/*邮箱地址的对应*/
	public static $storage_mail = array( "163"=>"http://mail.163.com/" , "126"=>"http://mail.126.com/" , "188"=>"http://mail.188.com/" ,
			"gmail"=>"http://gmail.com" , "hotmail"=>"http://login.live.com/" , "live"=>"http://login.live.com/" ,
			"yahoo"=>"http://mail.cn.yahoo.com/" , "sina"=>"http://mail.sina.com/", "sohu"=>"http://mail.sohu.com/" ,
			"tom"=>"http://mail.tom.com/" , "139"=>"http://mail.139.com/" , "qq"=>"http://mail.qq.com" ,
			"21cn"=>"http://mail.21cn.com/" , "263"=>"http://mail.263.net/" );
	
	public static $head_mail = array("163" , "126" , "188" ,
			"gmail" , "hotmail" , "live" ,
			"yahoo" , "sina" , "sohu",
			"tom" , "139" , "qq",
			"21cn" , "263");
	
	//根据邮箱地址得到邮箱的host
	public function _createEmailHost( $email ){
		$start=explode('@',$email);
		$index = explode('.',$start[1]);
		$index = $index[0];
		if(in_array( $index , self::$head_mail) ){  //邮箱后缀名是否在数组中
			$email_address = self::$storage_mail[$index];
		} else if(in_array(strtolower($index),self::$head_mail)){//邮箱后缀名转换>成小写后是否在数组中
			$email_address = self::$storage_mail[strtolower($index)];
		}else{                   //如果邮箱后缀名不在数组中 就是用他本身的后缀作为网址的地址
			$email_address = substr($email, strpos($email, '@') + 1);
			$email_address = 'http://mail.'.$email_address;
		}
		return $email_address;
	}
	
	//将发送邮件的相关信息保存到数据库中
	public function _sendEmail($recieve_email , $recieve_name , $title , $content , $sender_name = '' , $sender_email = '' , $reply_email = '' , $send_type = MAIL_SENDTYPE_DIRECT) {
		$isSucc = false;
		
		if (empty($sender_email)){
			$sender_email = $GLOBALS['EMAIL_ADMIN']['email'];
		}
		if ( empty($sender_name) ){
			$sender_name = $GLOBALS['EMAIL_ADMIN']['name'];
		}
		if (empty($reply_email)){
			$reply_email = $sender_email ;
		}
		
		if ($send_type == MAIL_SENDTYPE_DIRECT ){
			$recieves = array($recieve_email);
			return $this->_sendMailDirectBySendCloud( $recieves , $title , $content , $sender_name , $sender_email , $reply_email );
		}else{
			$mailData = array();
			$mailData['type'] = $send_type;
			$mailData['status'] = 0;
			$mailData['send_time'] = date("Y-m-d H:i:s");
			$mailData['sender_name'] = $sender_name;
			$mailData['sender_email'] = $sender_email;
			$mailData['receiver_name'] = $recieve_name ;
			$mailData['receiver_email'] = $recieve_email;
			$mailData['subject'] = $title ;
			$mailData['content'] = $content ;
			$isSucc = mailModel::getInstance()->insertMailQueue($mailData);
			return (array('code'=>HTTP_ERROR_STATUS_OK, 'error'=>'操作成功'));
		}
	}
	
	function _sendMailDirectBySendCloud( $recieves , $title , $content , $sender_name = '' , $sender_email = '' , $reply_email = '' , $is_batch = false  , $send_to_adm = false ){
		include CORE_LIB_PATH . 'sendCloud/SendCloudLoader.php'; // 导入SendCloud依赖
		if (empty($sender_email)){
			$sender_email = $GLOBALS['EMAIL_ADMIN']['email'];
		}
		if ( empty($sender_name) ){
			$sender_name = $GLOBALS['EMAIL_ADMIN']['name'];
		}
		if (empty($reply_email)){
			$reply_email = $sender_email ;
		}
		try {
			if ( $is_batch ){
				$key = 'batch';
			}else{
				$key = 'daily';
			}
			$sendCloud = new SendCloud($GLOBALS['SENDCLOUD'][$key]['api_user'] , $GLOBALS['SENDCLOUD'][$key]['api_key']);
			$message = new SendCloud\Message();
			if ($send_to_adm){
				$message->addRecipients(array($GLOBALS['EMAIL_ADMIN']['email'])) // 添加第一个收件人
						->setReplyTo($reply_email) // 添加回复地址
						->setFromName($sender_name) // 添加发送者称呼
						->setFromAddress($sender_email) // 添加发送者地址
						->setSubject($title)  // 邮件主题
						->setBody( $content ); // 邮件正文html形式
				$sendCloud->send($message);
			}
			if (!empty($recieves)){
				foreach ($recieves as $r){
					$email_validate = filter_var($r, FILTER_VALIDATE_EMAIL);
					if ($email_validate){
						$message->setRecipients(array($r))
								->setReplyTo($reply_email) // 添加回复地址
								->setFromName($sender_email) // 添加发送者称呼
								->setFromAddress($sender_email) // 添加发送者地址
								->setSubject($title)  // 邮件主题
								->setBody( $content ); // 邮件正文html形式
						$sendCloud->send($message);
					}
				}
			}
			return (array('code'=>HTTP_ERROR_STATUS_OK, 'error'=>'操作成功'));
		} catch (Exception $e) {
			var_dump($e->getMessage());
			return (array('code'=>HTTP_ERROR_STATUS_OK, 'error'=>$e->getMessage()));
				
		}
	}
}