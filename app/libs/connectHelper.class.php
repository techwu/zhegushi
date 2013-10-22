<?php
/**
 * 
 * @author wumin <wumin.itea@gmail.com>
 *
 */
class  connectHelper {
	/**
	 * 从人人网获取当前用户信息
	 */
	static function getRenRenInfo() {
		if( isset($_COOKIE[ RENREN_API_KEY . "_session_key"], $_COOKIE[ RENREN_API_KEY . "_user"]) ) {
			$sessionKey = $_COOKIE[ RENREN_API_KEY . "_session_key"];
			$xnUid = $_COOKIE[ RENREN_API_KEY . "_user"];
		} else {
			return false;
		}
		importer('corelib.xiaonei');
		$xn = new XNapp(RENREN_API_KEY, RENREN_SECRET, $sessionKey);
		$params = array(
			'uids' => $xnUid,
			'fields' => 'uid,name,sex,mainurl,birthday,hometown_location,university_history,work_history'
		);
		$infos = $xn->users('getInfo', $params);
		if( isset($infos[0]) ) {
			$infos[0]['session_key'] = $sessionKey;
			return $infos[0];
		} else {
			return false;
		}
	}
//	static function renrenAuth($callbackUrl){
//		importer('corelib.renren.RenRenClient') ;
//		$rrObj = new RenRenClient;
//		$rrObj->setSessionKey('3.c149c48e2c18d48c0110434f3189e070.21600.1294927200-346132863');
//		$rrObj->setCallId('12345678');
//		
//		$res = $rrObj->POST('users.getInfo', array('346132863,741966903','uid,name,tinyurl,headhurl,zidou,star'));
//		print_r($res);die();
//	}
	static function qqAuth($callbackUrl) {
		require_once CORE_LIB_PATH . 'Oauth/OpenSDK/Tencent/Weibo.php';
		if( !isset($_GET['oauth_verifier']) || !isset($_GET['oauth_token'])  ){
			//第一次调用
			OpenSDK_Tencent_Weibo::init(QQWB_AKEY, QQWB_SKEY);
			$request_token = OpenSDK_Tencent_Weibo::getRequestToken($callbackUrl);
			$url = OpenSDK_Tencent_Weibo::getAuthorizeURL($request_token);
//			var_dump($url);die();
			webTools::goToUrl($url);
			return false;
		}else{
			OpenSDK_Tencent_Weibo::init(QQWB_AKEY, QQWB_SKEY);
			$last_key = OpenSDK_Tencent_Weibo::getAccessToken($_GET['oauth_verifier']);
			if( $last_key ){
				$_SESSION['tencent_access_keys'] = $last_key;
				return true;
			}else{
				return false;
			}
		}
		
	}
	
	static function doubanAuth($callbackUrl){
		importer('corelib.Oauth.doubanOAuth');
		
		if( !isset($_REQUEST['oauth_token'])) {
			//第一次调用
			$o = new doubanOAuth( DOUBAN_AKEY , DOUBAN_SKEY );
			//获取request token
			$keys = $o->getRequestToken();
			$_SESSION['douban_request_keys'] = $keys;
			//去sina登录，获取access token
			$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callbackUrl);
			webTools::goToUrl($aurl);
			return false;
		} elseif ( isset($_REQUEST['oauth_token']) ) {
			//从豆瓣返回
			$o = new doubanOAuth( DOUBAN_AKEY , DOUBAN_SKEY ,$_SESSION['douban_request_keys']['oauth_token'] , $_SESSION['douban_request_keys']['oauth_token_secret'] );
			$last_key = $o->getAccessToken( $_REQUEST['oauth_token'] );//$_REQUEST['oauth_verifier'] ) ;
//			var_dump($last_key);die();
			$_SESSION['douban_request_keys'] = $last_key;
			return true;
		}
	}
	
	static function getDoubanInfo($user_id=0){
		$dbc = self::getDoubanClient();
		if( $dbc === false ) {
			return false;
		}
		
		$ret = $dbc->getUserInfo();
//		echo $ret;die();
		/*	<?xml version="1.0" encoding="UTF-8"?>
			<entry xmlns="http://www.w3.org/2005/Atom" 
				xmlns:db="http://www.douban.com/xmlns/" 
				xmlns:gd="http://schemas.google.com/g/2005" 
				xmlns:openSearch="http://a9.com/-/spec/opensearchrss/1.0/" 
				xmlns:opensearch="http://a9.com/-/spec/opensearchrss/1.0/">
				<id>http://api.douban.com/people/54857375</id>
				<title>吴敏</title>
				<link href="http://api.douban.com/people/54857375" rel="self"/>
				<link href="http://www.douban.com/people/54857375/" rel="alternate"/>
				<link href="http://img3.douban.com/icon/u54857375-2.jpg" rel="icon"/>
				<content>一个创业者,一个偏执狂...一个游戏爱好者,一个电影爱好者,一个爱好爱好者.</content>
			
				<db:attribute name="n_mails">0</db:attribute>
				<db:attribute name="n_notifications">0</db:attribute>
				<db:location id="beijing">北京</db:location>
				<db:signature></db:signature>
				<db:uid>54857375</db:uid>
				<uri>http://api.douban.com/people/54857375</uri>
			</entry>

		 */
		if( isset($ret['error']) ) {
			return false;
		} else {
			return $ret;
		}
	}
	static function getQQInfo($user_id=0){
		require_once CORE_LIB_PATH . 'Oauth/OpenSDK/Tencent/Weibo.php';
		if(isset($_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]) && isset($_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET])){
			OpenSDK_Tencent_Weibo::init(QQWB_AKEY, QQWB_SKEY);
			//已经取得授权
			$uinfo = OpenSDK_Tencent_Weibo::call('user/info');
			return $uinfo;
		}else{
			return false;
		}
	}
	/**
	 * 去新浪微博验证
	 * @param unknown_type $callbackUrl	通常应该为当前URL
	 */
	static function weiboAuthOld($callbackUrl) {
		importer('corelib.weibooauth');
	
		if( !isset($_REQUEST['oauth_verifier']) ) {
			//第一次调用，去新浪登录
			$o = new WeiboOAuth( WB_AKEY , WB_SKEY );
			//获取request token
			$keys = $o->getRequestToken();
			$_SESSION['weibo_request_keys'] = $keys;
			//去sina登录，获取access token
			$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callbackUrl);
			webTools::goToUrl($aurl);
			return false;
		} else {
			//从微博返回
			$o = new WeiboOAuth( WB_AKEY , WB_SKEY ,$_SESSION['weibo_request_keys']['oauth_token'] , $_SESSION['weibo_request_keys']['oauth_token_secret'] );
			$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
			$_SESSION['weibo_access_keys'] = $last_key;
			return true;
		}
	}
	
	static function weiboAuth($callbackUrl) {
		importer('corelib.Oauth.OAuthV2');
	
		if( !isset($_REQUEST['code']) ) {
			//第一次调用，去新浪登录
			$o = new weibAuthV2( WB_AKEY , WB_SKEY );
			//获取request token
			//			$keys = $o->getRequestToken();
			//			$_SESSION['weibo_request_keys'] = $keys;
			//去sina登录，获取access token
			$aurl = $o->getAuthorizeURL( $callbackUrl);
			webTools::goToUrl($aurl);
			return false;
		} else {
			$o = new weibAuthV2( WB_AKEY , WB_SKEY );
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = $callbackUrl;
			try {
				$token = $o->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			
			}
			//从微博返回
			//			$o = new WeiboOAuth( WB_AKEY , WB_SKEY ,$_SESSION['weibo_request_keys']['oauth_token'] , $_SESSION['weibo_request_keys']['oauth_token_secret'] );
			//			$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
			$_SESSION['weibo_access_keys'] = $token;
			
			return true;
		}
	}
	
	/**
	 * 从新浪微博获取当前用户信息
	 */
	static function getWeiboInfo() {
		$wbc = self::getWeiboClient();
		if( $wbc === false ) {
			return false;
		}
	
		//		$ret = $wbc->verify_credentials();
		$ms  = $wbc->home_timeline(); // done
		$uid_get = $wbc->get_uid();
		$uid = $uid_get['uid'];
		$ret = $wbc->show_user_by_id( $uid);
		//		var_dump($ret);die();
		if( isset($ret['error']) ) {
			return false;
		} else {
			return $ret;
		}
	}
	
	/**
	 * @return WeiboClient
	 */
	static function getWeiboClient($userId=0) {
		importer('corelib.Oauth.OAuthV2');
		if( isset($_SESSION['weibo_access_keys']) ) {
			$keys = $_SESSION['weibo_access_keys'];
		} else {
			if( empty($userId) ) {
				return false;
			} else {
				//从数据库读取
				$connInfo = userModel::getInstance()->getUserConnect($userId, 1);
				if( empty($connInfo) || empty($connInfo['access']) ) {
					return false;
				} else {
					$accessArr = split(',', $connInfo['access']);
					$keys = array(
						'oauth_token' => $accessArr[0],
						'oauth_token_secret' => $accessArr[1]
					);
				}
			}
		}
		$wbc = new weiboClientV2(WB_AKEY, WB_SKEY, $keys['access_token']);
		return $wbc;
	}
	
	static function getDoubanClient($userId=0){
		importer('corelib.Oauth.doubanOAuth');
//		var_dump($_SESSION);die();
//		$keys['oauth_token'] = $_REQUEST['oauth_token'];

		if( isset($_SESSION['douban_request_keys']) ) {
			$keys = $_SESSION['douban_request_keys'];
		} else {
			if( empty($userId) ) {
				return false;
			} else {
				//从数据库读取
				$connInfo = userModel::getInstance()->getUserConnect($userId, 3);
				if( empty($connInfo) || empty($connInfo['access']) ) {
					return false;
				} else {
					$accessArr = split(',', $connInfo['access']);
					$keys = array(
						'oauth_token' => $accessArr[0],
						'oauth_token_secret' => $accessArr[1]
					);
				}
			}
		}
		$dbc = new DoubanClient(DOUBAN_AKEY, DOUBAN_SKEY, $keys['oauth_token'], $keys['oauth_token_secret']);
		return $dbc;
	}
	/**
	 * 同步到其他网站
	 * @param unknown_type $userId
	 * @param unknown_type $syncType	分享类型：goods,collect,like,ask,answer
	 * @param unknown_type $twitterId
	 * @param unknown_type $offsite		1-人人网，3-新浪微博
	 */
	static function sync($userId, $syncType, $tid, $offsite=0) {
		
		
	}
	
	/**
	 * 整理格式并发布
	 */
	static function renrenSync($syncType, $desc, $goods_title, $goods_url, $img_src = false) {
		
	}
	
	static function renrenPublishFeed($template_id, $title_data, $body_data, $attachment, $desc, $action_links) {
		importer('corelib.xiaonei');
		
		//获取sessionKey和xnUid
		if( isset($_COOKIE[ RENREN_API_KEY . "_session_key"], $_COOKIE[ RENREN_API_KEY . "_user"]) ) {
			$sessionKey = $_COOKIE[ RENREN_API_KEY . "_session_key"];
			$xnUid = $_COOKIE[ RENREN_API_KEY . "_user"];
		} else {
			return false;
		}
		
		//
		$title_data['xnuid'] = $xnUid;
		
		$xn = new XNapp(RENREN_API_KEY, RENREN_SECRET, $sessionKey);
		$arr = array();
		$arr['template_id'] = $template_id;
		$arr['title_data'] = json_encode($title_data);
		$arr['body_data'] = json_encode($body_data);
		$arr['attachment'] = json_encode($attachment);
		$arr['desc'] = $desc;
		$arr['action_links'] = json_encode($action_links);
		$res = $xn->feed('publish', $arr);
		return $res;
	}
}