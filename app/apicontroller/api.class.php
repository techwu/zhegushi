<?php
class api extends CAction {
	function __construct(){
		parent::__construct();
	}
	
	function __destruct(){}
	
	public function do_weixin(){
		$signature = isset($_GET['signature'])?$_GET['signature']:'' ;
		$timestamp = isset($_GET['timestamp'])?$_GET['timestamp']:time() ;
		$nonce = isset($_GET['nonce'])?$_GET['nonce']:'' ;
		$echostr = isset($_GET['echostr'])?$_GET['echostr']:'' ;
		if(!empty($echostr)){
			echo $echostr;
			return;
		}
		importer('corelib.weixin');
		$wechat = new weixin( $signature , $timestamp , $nonce , encrypt::checkMoreSumFunc('weixin' , 'techwu'));
		$validate = $wechat->checkSignature();
		if( !$validate ){
			echo null;
			return;
		}
		$user_key = $wechat->getResponseUserAndKey();
		
		if(empty($user_key['Content'])){
			echo $wechat->defaultResponse($user_key);
			return ;
		}
// 		$product_id = productModel::getInstance()->searchProductByNameLike($user_key['Content'] , RELEASED);
		//判定是哪种返回：
		$logHandle = new zx_log("log/data_log", "normal");
		$logHandle->w_log( $user_key['Content'] );
		$type = $wechat->getUserIndexType($user_key['Content']);
		$logHandle->w_log(  $type );
		if ( $type == 'help' ){
			$logHandle->w_log(   $wechat->defaultResponse($user_key , true) );
			echo  $wechat->defaultResponse($user_key , true);
			return ;
		}
		$content['ToUserName'] =  $user_key['FromUserName'];
		$content['FromUserName'] =  $user_key['ToUserName'];
		$content['CreateTime'] =  $timestamp ;//$user_key['ToUserName'];
		$content['MsgType'] =  'news';
		
		$more = 0 ;
		$content['ArticleCount'] =  1;
		
		$content['Articles'][0]['Title'] = "点击查看更多项目..用“？”或者“help”寻求更多帮助。";
		$content['Articles'][0]['Description'] = "点击查看更多项目..." ;
		$content['Articles'][0]['PicUrl'] = '';//"http://img.36tr.com";
		$content['Articles'][0]['Url'] = WWW_PATH;
		
		echo $wechat->responseMsg($content);
	}
}