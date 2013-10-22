<?php 
class mailModel extends CModel{
	/**
	 * @return mailModel
	 */
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	public function __construct(){
		parent::__construct( array('id' , 'type' , 'status' , 'send_time' , 'sender_name' , 'sender_email' , 'receiver_name' ,
							  'receiver_email' , 'subject' , 'content') , DB_TABLE_MAIL);
	}
	
	public function __destruct(){}
	
	/**
	 * 插入mail队列的函数
	 * @param array $iData
	 * 		$iData['type'] 1为激活邮件（参看数据表注释）
	 * 		$iData['sender_name'] 
	 *  	$iData['sender_email']
	 *   	$iData['receiver_name']
	 *   	$iData['receiver_email']
	 *   	$iData['subject']
	 *   	$iData['content']
	 * 		
	 * @return bool true/false
	 */
	public function insertMailQueue( $iData ) {
		$this->addNewData($iData);
	}

	public function getUnSentMailQueue ( $limit = 10 ) {
		$sqlComm = "select * from  ".DB_TABLE_MAIL." where status=:status order by type,id asc limit $limit";
		$sqlData['status'] = 0;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData , true);
		return $result;
	}
	
	public function updateMailStatus ( $mailId, $statusId ) {
		$sqlComm = "update  ".DB_TABLE_MAIL." set status=:status where id=:mail_id";
		$sqlData['mail_id'] = $mailId;
		$sqlData['status'] = $statusId;
		self::$db->queryWrite ( $sqlComm, $sqlData );
		return true;		
	}
}