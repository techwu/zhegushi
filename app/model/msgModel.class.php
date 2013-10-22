<?php 
/**
 * msgModel 用户数据类
 * @author <wu@36kr.com>wumin
 */
class msgModel extends CModel{
	/**
	 * @return msgModel
	 */
	
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct(array('id' , 'from_user_id' , 'to_user_id' , 'subject' , 'content' , 'url' , 'from_show_type' , 'to_show_type' , 'issysmsg' ,
			'status' , 'created') , DB_TABLE_USER_CHAT );
	}
	
	function addUserMessages( $iData ){
		return $this->addNewData($iData);
	}
	
	/*
	 * 通过一个用户ID删除与此用户的所有
	*/
	public function deleteMsgByUid($user_id_1,$user_id_2){
		if($user_id_1 == $user_id_2){
			$sqlComm = "update ".DB_TABLE_USER_CHAT." set from_show_type=0,to_show_type=0 where from_user_id=:from_user_id and to_user_id=:to_user_id";
		}
		else{
			$sqlComm = "update ".DB_TABLE_USER_CHAT." set from_show_type=0 where from_user_id=:from_user_id and to_user_id=:to_user_id;".
					" update ".DB_TABLE_USER_CHAT." set to_show_type=0 where from_user_id=:to_user_id and to_user_id=:from_user_id";
		}
		$sqlData['from_user_id'] = $user_id_1;
		$sqlData['to_user_id'] = $user_id_2;
		//		print_r($sqlComm);die();
		self::$db->queryWrite( $sqlComm, $sqlData );
		return true;
	}
	
	public function deleteMsgByMid($user_id , $message_id){
		$sqlComm = "update ".DB_TABLE_USER_CHAT." set from_show_type=0 where id=:message_id and from_user_id =:from_user_id;"
				."update ".DB_TABLE_USER_CHAT." set to_show_type=0 where id=:message_id and to_user_id=:from_user_id";
		$sqlData['from_user_id'] = $user_id;
		$sqlData['message_id'] = $message_id;
		self::$db->queryWrite( $sqlComm, $sqlData );
		return true;
	}
	
	function getUnreadMsgFromChat( ) {
		$sqlComm = "select from_user_id , to_user_id from " . DB_TABLE_USER_CHAT . " where status = 1 ";
		$result = array();
		$sqlData = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if(isset($result[0])){
			return $result;
		}else{
			return false;
		}
	}
	
	function updateMsgReadByUserid($to_user_id , $issysmsg = 0 , $status = READ){
		$sql = "update " . DB_TABLE_USER_CHAT . " set status = :status where issysmsg = :issysmsg and to_user_id = :to_user_id" ;
		$sqlData = array( 'to_user_id'=>$to_user_id , 'issysmsg'=>$issysmsg ,'status'=>$status);
		self::$db->queryWrite ( $sql, $sqlData );
		return true;
	}
	 
	
	function updateStatus( $to_user_id  ){   //将所有信件置0
		$whereArr = array('to_user_id'=>$to_user_id );
		$create_time = time();
		$updateArr = array( 'status'=>0);
		$res = self::$db->updateField($whereArr , $updateArr , DB_TABLE_USER_CHAT );
		return $res;
	}
	
	function updateUserChat($whereArr , $updateArr){
		$res = self::$db->updateField($whereArr , $updateArr , DB_TABLE_USER_CHAT );
		return $res;
	}
	
	function updateStatusByToUserAndFromUserId( $to_user_id , $from_user_id , $status = READ){   //将所有信件置0
		$sql = "update " . DB_TABLE_USER_CHAT . " set status = :status where from_user_id = :from_user_id and to_user_id = :to_user_id " ;
		$sqlData = array( 'from_user_id'=>$from_user_id , 'to_user_id'=>$to_user_id , 'status'=>$status);
		self::$db->queryWrite ( $sql, $sqlData );
		return true;
	}
	/**
	 * 获得与某个人有过私信的所有用户列表。
	 * @param unknown_type $userid
	 */
	function getPmsgRelativeUserInfo ( $userid ,$is_system_mesg , $start = 0 , $limit = PER_PAGE ) {
		$sqlComm = "select u.*  from (((" . 
					"select to_user_id user_id from ". DB_TABLE_USER_CHAT ." where from_show_type=1 and from_user_id=:from_user_id and issysmsg = :is_system_mesg" . 
					" ) union ( " . 
					"select from_user_id user_id from  " . DB_TABLE_USER_CHAT . "  where to_user_id=:from_user_id and to_show_type=1 and issysmsg = :is_system_mesg)" .
					") union (select :from_user_id) ) mi left join ". DB_TABLE_USER ." u on u.user_id = mi.user_id " ;
		$sqlData['from_user_id'] = $userid;
		$sqlData['is_system_mesg'] = $is_system_mesg;
		$result = array();		
		self::$db->getQueryData( $result, $sqlComm, $sqlData ,false , 'user_id');
//		print_r($result);die();
		if( !empty($result) )	{
			foreach ($result as &$res){
				$res['avatar'] = commFun::getRealAvatarUrl( $res['avatar'] , IMAGE_PATH );
				if( empty( $res['username'] ) ){
					$email_arr = explode('@', $res['email']);
					$res['username'] = $email_arr[0];
				}
			}
			return $result;		
		}else{
			return false;
		}
	}
	
	function getContactNum( $userid , $is_system_mesg = 0 ){
		$sqlComm = "select count(*) sum from ((" . 
					"select to_user_id user_id from ". DB_TABLE_USER_CHAT .
					" where from_show_type=1 and from_user_id=:from_user_id and issysmsg = :is_system_mesg" . 
					" ) union ( " . 
					"select from_user_id user_id from  " . DB_TABLE_USER_CHAT . "  where to_user_id=:from_user_id ".
					" and to_show_type=1 and issysmsg = :is_system_mesg) " .
					") u ";
		$sqlData['from_user_id'] = $userid;
		$sqlData['is_system_mesg'] = $is_system_mesg;
		$result = array();		
		self::$db->getQueryData( $result, $sqlComm, $sqlData ,false);
// 		print_r($result);die();
		if( !empty($result[0]) )	{
			return $result[0]['sum'];		
		}else{
			return 0;
		}
	}
	/*
	 * 获得系统信息列表
	 */
	function getSysMsg( $user_id ){
		$sqlComm = "select * from ".DB_TABLE_USER_CHAT." uc left join ".DB_TABLE_USER." u on uc.to_user_id=u.user_id where to_user_id = :user_id and issysmsg = 1 and to_show_type =1 order by uc.id desc";
		$result = array();
		$sqlData= array('user_id'=>$user_id);
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
//		var_dump( $result, $sqlComm, $sqlData );die();
		if( !empty($result) )	{
			foreach ($result as &$res){
				$res['avatar'] = commFun::getRealAvatarUrl( $res['avatar'] , IMAGE_PATH );
				$res['created'] = timeStrConverter( $res['created']);
			}
			return $result;		
		}else{
			return false;
		}	
	}	
	
	/**
	 * 获得$userId发给别人的私信
	 * @param unknown_type $userId
	 * @param unknown_type $relativeUserIdArr
	 */
	function getFromMsg( $userId  ,$is_system_mesg = 0 ) {
		$sqlComm = "select SQL_NO_CACHE mi.* from (select max( id ) id from " . DB_TABLE_USER_CHAT . 
					" where from_user_id=:from_user_id and from_show_type=1 and issysmsg =:is_system_mesg group by to_user_id ) max ".
					" left join " . DB_TABLE_USER_CHAT . " mi on max.id = mi.id order by mi.id desc";
		$result = array();
		$sqlData['from_user_id'] = $userId;
		$sqlData['is_system_mesg'] = $is_system_mesg;
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if(isset($result[0])){
			return $result;
		}else{
			return false;
		}
	}
	
	/**
	 * 获得给用户$userId发的私信
	 * @param unknown_type $userId
	 * @param unknown_type $relativeUserIdArr
	 */
	function getSendToMsg ($userId ,$is_system_mesg = 0  ) {
		$sqlComm = "select SQL_NO_CACHE mi.* from (select max(id) id from ".DB_TABLE_USER_CHAT.
					" where to_user_id=:to_user_id  and to_show_type=1 and issysmsg =:is_system_mesg group by from_user_id) max ".
					" left join ".DB_TABLE_USER_CHAT." mi on max.id = mi.id order by mi.id desc";
		$result = array();
		$sqlData['to_user_id'] = $userId;
		$sqlData['is_system_mesg'] = $is_system_mesg;
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
		if(isset($result[0])){
			return $result;
		}else{
			return false;
		}	
	}
	
	function staticPmsgCompriseNumByUids ($userId, $userArr ) {
		if(empty($userArr)){
			return false;
		}elseif (!is_array($userArr)){
			return false;
		}
		$uArr = $userArr;
		$userArr = implode(",",$userArr);
//		$userId = self::$_userid;
		$sqlComm = "select sum(from_show_type) num,to_user_id userid from " . DB_TABLE_USER_CHAT . 
					" where (from_user_id = {$userId} and to_user_id in({$userArr})) group by to_user_id";
//		print_r($sqlComm);die();
		$sqlData = array();
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData ,false , 'userid');		
//		var_dump($result);die();
		
		$sqlComm = "select sum(to_show_type) num,from_user_id userid from ".DB_TABLE_USER_CHAT." 
					where 
					(to_user_id = {$userId} and from_user_id in({$userArr}))group by from_user_id";
		$sqlData = array();
		$result_1 = array();
		self::$db->getQueryData( $result_1, $sqlComm, $sqlData ,false , 'userid');
		foreach ( $uArr as $key){
			if( isset($result[$key]) && isset($result_1[$key])){
				$result[$key]['num'] = $result[$key]['num']+$result_1[$key]['num'];
			}elseif (!isset($result[$key]) && isset($result_1[$key])){
				$result[$key]['num']  = $result_1[$key]['num'] ;
			}elseif (isset($result[$key]) && !isset($result_1[$key])){
				
			}elseif (!isset($result[$key]) && !isset($result_1[$key])) {
				$result[$key]['num']  = 0;
			}
		}
//		print_r($result);die();
		return $result;
		
	}
	
	function getPmsgBoth($_userid , $user_id , $start = 0, $num = PER_PAGE) {
		$sqlComm = "select * from ".DB_TABLE_USER_CHAT." where (
					(from_user_id = :userA and to_user_id = :userB) and from_show_type=1 
					) or (
					(from_user_id=:userB and to_user_id = :userA) and to_show_type=1)".
					" order by id desc limit {$start},{$num};";
		$sqlData['userA'] = $_userid;
		$sqlData['userB'] = $user_id;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );
// 		var_dump($result, $sqlComm, $sqlData)	;die();
		return $result;
	}
	
	function getPmsgNumBetweenIds($user_id1 , $user_id2){
		$sqlComm = "select count(*) sum from ".DB_TABLE_USER_CHAT." where (
					(from_user_id = :userA and to_user_id = :userB) and from_show_type=1 
					) or (
					(from_user_id=:userB and to_user_id = :userA) and to_show_type=1)";
		$sqlData['userA'] = $user_id1;
		$sqlData['userB'] = $user_id2;
		$result = array();
		self::$db->getQueryData( $result, $sqlComm, $sqlData );	
		if(isset($result[0])){
			return $result[0]['sum'];
		}else{
			return 0;
		}
	}

	function getUnreadMsgNumByUserid($to_user_id){
	    $sqlComm = "select  sum(status) as msg_num from ". DB_TABLE_USER_CHAT ." where to_user_id = :to_user_id and to_show_type = 1 ";
	    $sqlData['to_user_id'] = $to_user_id;
	    $result = array();
	    $res = self::$db->getQueryData($result, $sqlComm, $sqlData);
	    if(isset($result[0]) && !empty($result[0]['msg_num'])){
	   		return $result[0]['msg_num'];
	    }else{ 
	    	return 0;
	    }
	}
	
	function getUnreadMsgsByUserid( $to_user_id , $status = UNREAD ){
		$sqlComm = "select  dtuc.* , dtu.username , dtu.avatar  from ". DB_TABLE_USER_CHAT ." dtuc left join ". DB_TABLE_USER ." dtu on dtuc.from_user_id = dtu.user_id and dtu.status =  ". EXIST . ' '.
					" where dtuc.to_user_id = :to_user_id and dtuc.to_show_type = 1 and dtuc.status = :status ";
		$sqlData['to_user_id'] = $to_user_id;
		$sqlData['status'] = $status;
		$result = array();
		$res = self::$db->getQueryData($result, $sqlComm, $sqlData);
		if(isset($result[0]) && !empty($result[0])){
			foreach ( $result as &$res ){
				if ( $res['issysmsg'] == 1 ){
					$res['avatar'] = commFun::getRealAvatarUrl( '' );
					$res['username'] = '这故事系统提醒';
				}else{
					$res['avatar'] = commFun::getRealAvatarUrl( $res['avatar']  );
				}
				$res['created'] = timeStrConverter( $res['created']);
			}
			return $result;
		}else{
			return false;
		}
	}
	
	function getUnreadMsgNumBetweenUsers($to_user_id , $from_user_id ){
		$sqlComm = "select  sum(status) as msg_num from ". DB_TABLE_USER_CHAT ." where to_user_id = :to_user_id and from_user_id = :from_user_id and to_show_type = 1 and issysmsg = 0";
		$sqlData['to_user_id'] = $to_user_id;
		$sqlData['from_user_id'] = $from_user_id;
		$result = array();
		$res = self::$db->getQueryData($result, $sqlComm, $sqlData);
		if(isset($result[0]) && !empty($result[0]['msg_num']) ){
			return $result[0]['msg_num'];
		}else{
			return 0;
		}
	}
	
	function getMsgNumByTime($from_time , $end_time , $from_show_type = EXIST , $to_show_type = EXIST,$status = array(READ , UNREAD) ){
		if(!is_array($status)){
			$status = array($status) ; 
		}
		$status = implode(',', $status);
		$sqlComm = "select  count(*) as msg_num from ". DB_TABLE_USER_CHAT ." where create_time >= {$from_time}  and create_time <= {$end_time} and from_show_type = :from_show_type and to_show_type = :to_show_type 
		            and issysmsg = 0 and status in ({$status })";
		$sqlData['from_show_type'] = $from_show_type;
		$sqlData['to_show_type'] = $to_show_type;
		$result = array();
		$res = self::$db->getQueryData($result, $sqlComm, $sqlData);
		if(isset($result[0]) && !empty($result[0]['msg_num']) ){
			return $result[0]['msg_num'];
		}else{
			return 0;
		}
	}
	
	function getMsgListByTime($from_time , $end_time , $start = 0 , $limit = PAGE_SIZE , $from_show_type = EXIST , $to_show_type = EXIST){
		$sqlComm = "select * from ". DB_TABLE_USER_CHAT ." where create_time >= {$from_time}  and create_time <= {$end_time} and from_show_type = :from_show_type and to_show_type = :to_show_type
		            and issysmsg = 0 order by id desc limit {$start} , {$limit} ";
		$sqlData['from_show_type'] = $from_show_type;
		$sqlData['to_show_type'] = $to_show_type;
		$result = array();
		$res = self::$db->getQueryData($result, $sqlComm, $sqlData);
		if(isset($result[0]) && !empty($result[0]) ){
		return $result;
		}else{
		return false ;
		}
	}
	
	function getMsgList($start = 0 , $limit = PAGE_SIZE , $from_show_type = EXIST , $to_show_type = EXIST){
		$sqlComm = "select * from ". DB_TABLE_USER_CHAT ." where from_show_type = :from_show_type and to_show_type = :to_show_type and issysmsg = 0 order by id desc limit {$start} , {$limit} ";
		$sqlData['from_show_type'] = $from_show_type;
		$sqlData['to_show_type'] = $to_show_type;
		$result = array();
		$res = self::$db->getQueryData($result, $sqlComm, $sqlData);
		if(isset($result[0]) && !empty($result[0]) ){
		return $result;
		}else{
		return false ;
		}
	}
	
	function getMsgListByFromUserId($from_user_id , $from_show_type = EXIST , $to_show_type = EXIST){
		$sqlComm = "select * from ". DB_TABLE_USER_CHAT ." where from_user_id =:from_user_id and from_show_type = :from_show_type and to_show_type = :to_show_type and issysmsg = 0 order by id desc";
		$sqlData['from_user_id'] = $from_user_id;
		$sqlData['from_show_type'] = $from_show_type;
		$sqlData['to_show_type'] = $to_show_type;
		$result = array();
		$res = self::$db->getQueryData($result, $sqlComm, $sqlData);
		if(isset($result[0]) && !empty($result[0]) ){
			return $result;
		}else{
			return false ;
		}
	}
	
	function getUnreadSysMsgNumByUserid($to_user_id){
	    $sqlComm = "select  sum(status) as sysmsg_num from ". DB_TABLE_USER_CHAT ." where to_user_id = :to_user_id and to_show_type = 1 and issysmsg = 1";
	    $sqlData['to_user_id'] = $to_user_id;
	    $result = array();
	    $res = self::$db->getQueryData($result, $sqlComm, $sqlData);
	    if(isset($result[0])  && !empty($result[0]['sysmsg_num'])){
	    	return $result[0]['sysmsg_num'];
	    }else{ 
	    	return 0;
	    }
	}
	
    function getUnreadMsgNumFromChat(){ 
	    $sqlComm = "select to_user_id , sum(status) as unread_number from ". DB_TABLE_USER_CHAT ." group by to_user_id " ;
	    $sqlData = array();
	    $result = array();
	    $res = self::$db->getQueryData($result, $sqlComm, $sqlData);
	    if(isset($result[0])){
	    return $result;
	    }else{ 
	    	return false;
	    }
	}

	function getUserGetMsgNumsByUserAndTime( $to_user_id , $begin = 0  , $end = NOWTIME ){
		$sqlData = array();
		if (empty($to_user_id)){
			return 0;
		}
		
		if(!empty($to_user_id)){
			$sql_user_data_insert = '';
			if(is_array($to_user_id)){
				foreach ($to_user_id as $k=>$l){
					$sqlData['to_user_id_'.$k] = $l;
					$sql_user_data_insert .= ":to_user_id_{$k},";
				}
				$sql_user_data_insert = trim($sql_user_data_insert , ',');
			}else{
				$sql_user_data_insert = ' :to_user_id ' ;
				$sqlData['to_user_id'] = $to_user_id;
			}
		}
		
		$sqlComm = "select count(id) sum from ". DB_TABLE_USER_CHAT ." uc left join " . DB_TABLE_USER . " ttu  on ttu.user_id = uc.from_user_id ".
					" where uc.to_user_id in ({$sql_user_data_insert}) and create_time > :begin and create_time < :end " ;
		$sqlData['begin']=$begin ;
		$sqlData['end']=$end;
		$result = array();
		$res = self::$db->getQueryData($result, $sqlComm, $sqlData);
		if(isset($result[0])){
			return $result[0]['sum'];
		}else{
			return 0;
		}
	}
	
	function getUserPostMsgNumsByUserAndTime( $from_user_id , $begin = 0  , $end = NOWTIME ){
		$sqlData = array();
		if (empty($from_user_id)){
			return 0;
		}
	
		if(!empty($from_user_id)){
			$sql_user_data_insert = '';
			if(is_array($from_user_id)){
				foreach ($from_user_id as $k=>$l){
					$sqlData['from_user_id_'.$k] = $l;
					$sql_user_data_insert .= ":from_user_id_{$k},";
				}
				$sql_user_data_insert = trim($sql_user_data_insert , ',');
			}else{
				$sql_user_data_insert = ' :from_user_id ' ;
				$sqlData['from_user_id'] = $from_user_id;
			}
		}
	
		$sqlComm = "select count(id) sum from ". DB_TABLE_USER_CHAT .
				" where from_user_id in ({$sql_user_data_insert}) and create_time > :begin and create_time < :end " ;
		$sqlData['begin']=$begin ;
		$sqlData['end']=$end;
		$result = array();
		$res = self::$db->getQueryData($result, $sqlComm, $sqlData);
		if(isset($result[0])){
			return $result[0]['sum'];
		}else{
			return 0;
		}
	}
	
	function __destruct(){
		
	}
	
}