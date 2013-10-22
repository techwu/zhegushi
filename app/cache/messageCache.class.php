<?php
class messageCache extends CCache{
	/**
	 * @return messageCache
	* */
	static function getInstance(){
		return parent::getInstance(get_class());
	}

	function __construct(){
		parent::__construct(true);
	}
	
	function addMessage( $iData ){
		return msgModel::getInstance()->addUserMessages($iData);
	}
	
	function _getUserMessageList( $user_id , $issysmsg = 0 , $page = 0 ){
		$talks = $this->_getChatList( $user_id , $page , $issysmsg );
		return $talks;
	}
	
	function _getMessageContactNum( $user_id , $issysmsg = 0 ){
		$list_nums = msgModel::getInstance()->getContactNum($user_id , $issysmsg );
		return $list_nums;
	}
	
	function _getUserTalkList( $user_id , $talk_user_id , $page = 0 ){
		$currentTab = 'USER';
		return $this->_getTalkList( $user_id , $talk_user_id , $page);
	}
	
	function _getChatList( $user_id , $page = 0 , $issysmsg = 0 ){
		if ($issysmsg == 0 ){
			$userInfo  = msgModel::getInstance()->getPmsgRelativeUserInfo ( $user_id ,0);//0表示是普通 所有与6号交流的人员信息
			$fromMsgInfo = msgModel::getInstance()->getFromMsg( $user_id ,0);//, $relativeUserIdArr);//40发信的信息
			$sendToMsgInfo = msgModel::getInstance()->getSendToMsg( $user_id ,0);//, $relativeUserIdArr);//9收信的信息
			$showMsgInfo = array();
			if($fromMsgInfo !== false){
				foreach ( $fromMsgInfo as $f ) {
					if(isset($userInfo[$f['to_user_id']])){
						$showMsgInfo[$f['to_user_id']]['user_info'] = $userInfo[$f['to_user_id']];
						$showMsgInfo[$f['to_user_id']]['sender_info'] = $userInfo[$user_id];
						$showMsgInfo[$f['to_user_id']]['receiver_info'] = $userInfo[$f['to_user_id']];
							
						$showMsgInfo[$f['to_user_id']]['pmsg_info'] = $f;//user
						$showMsgInfo[$f['to_user_id']]['pmsg_time'] = timeStrConverter( $f['create_time'] );
						$showMsgInfo[$f['to_user_id']]['pmsg_timestr'] = $f['create_time'];
						$showMsgInfo[$f['to_user_id']]['pmsg_id'] = $f['id'];
						$showMsgInfo[$f['to_user_id']]['pmsg_replyid'] = $f['to_user_id'];
						$showMsgInfo[$f['to_user_id']]['pmsg_type'] = 0;  //我发送给别人的信。
					}
				}
			}
			if($sendToMsgInfo !== false){
				foreach ( $sendToMsgInfo as $s ) {
					if(isset($s['from_user_id'] )&& isset($userInfo[$s['from_user_id']])){
						if ( isset($showMsgInfo[$s['from_user_id']]) ) {
							if ( $s['create_time'] > $showMsgInfo[$s['from_user_id']]['pmsg_info']['create_time'] ) {
								//echo "11<br/>";
								$showMsgInfo[$s['from_user_id']]['user_info'] = $userInfo[$s['from_user_id']];
								$showMsgInfo[$s['from_user_id']]['sender_info'] = $userInfo[$s['from_user_id']];
									
								$showMsgInfo[$s['from_user_id']]['pmsg_info'] = $s;
								$showMsgInfo[$s['from_user_id']]['pmsg_time'] = timeStrConverter( $s['create_time'] );
								$showMsgInfo[$s['from_user_id']]['pmsg_timestr'] = $s['create_time'];
								$showMsgInfo[$s['from_user_id']]['pmsg_id'] =  $s['id'];
								$showMsgInfo[$s['from_user_id']]['pmsg_replyid'] =  $s['from_user_id'];
								//别人发送给我的信。
								$showMsgInfo[$s['from_user_id']]['pmsg_type'] = 1;
							}
						} else {
							$showMsgInfo[$s['from_user_id']]['user_info'] = $userInfo[$s['from_user_id']];
							$showMsgInfo[$s['from_user_id']]['sender_info'] = $userInfo[$s['from_user_id']];
							//				$showMsgInfo[$s['from_user_id']]['receiver_info'] = $userInfo[$user_id];
							$showMsgInfo[$s['from_user_id']]['pmsg_info'] = $s;
							$showMsgInfo[$s['from_user_id']]['pmsg_time'] = timeStrConverter( $s['create_time'] );
							$showMsgInfo[$s['from_user_id']]['pmsg_timestr'] = $s['create_time'];
			
							$showMsgInfo[$s['from_user_id']]['pmsg_id'] = $s['id'];
							$showMsgInfo[$s['from_user_id']]['pmsg_replyid'] =  $s['from_user_id'];
							//别人发送给我的信。
							$showMsgInfo[$s['from_user_id']]['pmsg_type'] = 1;
						}
					}
				}
			}
			if( empty($showMsgInfo) ){
				return array();
			}
			$userArr = array();
			foreach ( $showMsgInfo as $key =>$f ) {
				$userArr =array_unique( array_merge( $userArr , array($key ) ));
			}
			
			$tmpArr = array();
			$tmpNumArr= msgModel::getInstance()->staticPmsgCompriseNumByUids($user_id , $userArr);
			
			foreach ( $showMsgInfo as $key =>$f ) {
				$f['pmsg_num'] = $tmpNumArr[$key]['num'];
				array_push( $tmpArr , $f );
			}
			$showMsgInfo = $tmpArr;
			$showMsgInfoTmp = multiArraySort($showMsgInfo , 'pmsg_timestr' , SORT_DESC);
// 			var_dump($showMsgInfoTmp);die();
			$showMsgInfoNew = array();
			for ( $i = 0 ; $i < PAGE_SIZE && isset($showMsgInfoTmp[$i + $page*PER_PAGE]) ; $i++ ){
				$showMsgInfoNew[$i] = $showMsgInfoTmp[$i + $page*PER_PAGE];
			}
// 			var_dump($showMsgInfoNew[0]['sender_info']);die();
			//echo $showHtmlStr;die();
			return  $showMsgInfoNew;
		}else{
			$sysInfo = msgModel::getInstance()->getSysMsg( $user_id  );
			if($sysInfo === false){
				return array();
			}
			return  $sysInfo;
		}
	}
	
	function _getTalkList( $user_id , $talk_user_id , $page){
// 		var_dump($talk_user_id);die();
		if ( empty( $talk_user_id) ) {
			return false;
		}
		$pmsgInfo = msgModel::getInstance()->getPmsgBoth( $user_id , $talk_user_id , $page * PER_PAGE );
		
		$talk_infos = array();
		$i = 0;
		if(!empty($pmsgInfo)){
// 			var_dump($pmsgInfo);
			$userIdArr = array( $user_id , $talk_user_id);
			$userInfo = userModel::getInstance()->getUserInfoByUserIds( $userIdArr , 'user_id' );
			if( !isset( $userInfo[$talk_user_id] )){
				return false;
			}
			$talk_user_info = $userInfo[$talk_user_id];
			foreach ( $pmsgInfo as $p ) {
				$s['pmsg_info'] = $p;
				$s['pmsg_time'] = timeStrConverter( $p['create_time'] );
					
				$s['pmsg_num'] = 0;
				if ( $p['from_user_id'] == $user_id ) {
					$s['pmsg_type']  = 0;
					$s['pmsg_replyid'] = $p['to_user_id'];
					$s['sender_info'] = $userInfo[$user_id];
					$s['receiver_info'] = $userInfo[$talk_user_id];
				} else {
					$s['pmsg_type']  = 1;
					$s['pmsg_replyid'] = $p['from_user_id'];
					$s['receiver_info'] = $userInfo[$user_id];
					$s['sender_info'] = $userInfo[$talk_user_id];
				}
				$talk_infos[] = $s;
			}
		}
		return $talk_infos;
	}
	
	function getUnreadMsgNumByUserid($to_user_id){
		return msgModel::getInstance()->getUnreadMsgNumByUserid($to_user_id);
	}
	
	function getUnreadMsgsByUserid( $to_user_id ){
		$message = msgModel::getInstance()->getUnreadMsgsByUserid($to_user_id);
		return $message ;		
	}
	
	function __destruct(){}
}