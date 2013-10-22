<?php
/**
 * commFun class.
 * 用于定义一些比较常用但是又很零碎的功能函数
 * @author wumin <wm290291928@gmail.com>
 */ 

class commFun  {

	/**
	 * 获得当前用户的ID
	 */
	public static function GET_CURRENT_USER_ID () {
		global $_ZSESSION;
		if( isset($_ZSESSION['user_id']) ) {
			return $_ZSESSION['user_id'];
		} else {
			return 0;
		}
	}
	
	/**
	 * 截取图片文件名的最后一个数字作为分URL的变量
	 * @param $computeStr
	 */
	public static function computeMachineHash ( $computeStr ) {
		$tmpArr = split ("\." , $computeStr);
		return $tmpArr[0][strlen($tmpArr[0])-1];
	}
	
	/**
	 * 图片物理地址到URL的转换
	 * @param unknown_type $pPicPath
	 */
	public static function pPic2Url ( $pPicPath ) {
		//echo $pPicPath;die();
		$hashMap =  commFun::computeMachineHash ( $pPicPath ) ;
		$replaceStr = str_replace( UPLOAD_FILE_PATH, '/css/picture/', $pPicPath);
		//echo $replaceStr;die();
		$retStr = $GLOBALS['GOODS_URL_PICTURE'][ $hashMap ].$replaceStr;
		return $retStr;
	}
	
	/**
	 * 转换用户的头像地址为URL
	 * 现在同时支持新旧地址
	 * @param string $picPath
	 * @return string $avatarUrl
	 */
	public static function fileUrl2PATH( $url ){
		$checkStatus = strpos ( $url , IMAGE_PATH );
		if ( $checkStatus !== false  ) {
			$replaceStr = str_replace( IMAGE_PATH, HOST_PATH , $url);
			return $replaceStr; 
		}else{
			$path = HOST_PATH . $url;
			return $path;
		}
		
	}
	
	public static function convertAvatarUrl( $picPath , $path = IMAGE_PATH ){
		$avatarUrl = "";
		$folder = '';
		
		$checkFolder = strpos($path, 'image/');
		if($checkFolder !== false ){
			$folder = 'image/';
			$checkFolder = strpos($path, 'avatar_image/');
			if($checkFolder !== false ){
				$folder = 'avatar_image/';
			}
		}else{
			$checkFolder = strpos($path, 'pdf/');
			if($checkFolder !== false ){
				$folder = 'pdf/';
			}
		}
		$checkStatus = strpos ( $picPath , $path );
		if ( $checkStatus !== false  ) {
			$replaceStr = str_replace( $path, $folder, $picPath);
			return $replaceStr; 
		}
		return $picPath;
	}
	public static function convertLogoUrl ( $picPath ) {
		return self::convertAvatarUrl($picPath , UPLOAD_FILE_PATH);
	}
	public static function convertPdfFileUrl ( $file_path ) {
		return self::convertAvatarUrl($file_path , UPLOAD_PDF_PATH);
	}
	/**
	 * 获得一个32位的唯一ID标识
	 * @return unknown_type
	 */
	public static function getUniqueId () {
		return md5(uniqid(mt_rand(), true));
	}
	public static function getUniqueIdTo8Bit(){
		for($a = self::getUniqueId(),$s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',$d = '',$f = 0;$f < 8;$g = ord( $a[ $f ] ),$d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],$f++);
		return $d;
	}
	public static function getUniqueIdTo16Bit(){
		for($a = self::getUniqueId(),$s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',$d = '',$f = 0;$f < 16;$g = ord( $a[ $f ] ),$d .= $s[ ( $g ^ ord( $a[ $f + 16 ] ) ) - $g & 0x1F ],$f++);
		return $d;
	}
	public static function getSpendTime($StartTime,$StopTime){
		$SpendTime=timeStrConverter($StartTime);
		return $SpendTime;
	}
	public static function getLastTime($StartTime,$StopTime){
//		$nowtime=time();
		$LastTime =timeStrconver($StopTime);
		//$LastTime =$this->conver($LastTime_s);
		return $LastTime;
		print_r($LastTime);die();
	}
	public static function getDeadType($StartTime,$StopTime){
		$nowtime=time();
		if($StopTime>$nowtime){
			$DeadLine_type =1;//was it overtime?
		}
		else{
			$DeadLine_type=0;
		}
		//$DeadLine =$this->conver($DeadLine_s);
		return $DeadLine_type;
	}
	/**
	 * 获得当前页面的内存使用
	 */
	public static  function getMemUsedInHuanReadable () {
		$memUsedByte =  memory_get_usage();
		$memUsedKbyte = $memUsedByte/1024;
		return intval($memUsedKbyte) . 'KB';
		$memUsedMbyte = $memUsedKbyte/1024;
		$retStr = $memUsedMbyte;
		$showUnit = " MB";
		if ( $memUsedMbyte < 1 ) {
			$retStr = $memUsedKbyte;
			$showUnit = " KB";
		} 
		if ( $memUsedKbyte < 1 ) {
			$retStr = $memUsedByte;
			$showUnit = " bytes";
		}
		$retStr = number_format( $retStr, 2 ).$showUnit;
		return $retStr;
	}
	
	/**
	 * 生成一个特定的session变量。
	 * 请在页面没有任何输出的时候使用此类
	 */
	public static function setSession( $sessKey, $sessValue ) {
		$_SESSION[$sessKey] = $sessValue;
		
	}
	/**
	 * 拿到指定KEY的session变量的值
	 * @param unknown_type $sessKey
	 * @param unknown_type $sessValue
	 */
	public static function getSession( $sessKey ) {
		if ( isset($_SESSION[$sessKey]) ) {
			return $_SESSION[$sessKey];
		} else {
			return false;
		} 	
	}
	
	/**
	 * 获得访问者的IP地址
	 */
	public static function get_ip(){
		global $_SERVER;
		if ( isset($_SERVER) ) {
			if ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
				$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} else if ( !empty($_SERVER["HTTP_CLIENT_ip"]) ) {
				$realip = $_SERVER["HTTP_CLIENT_ip"]; 
			} else {
				$realip = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
				$realip = getenv( 'HTTP_X_FORWARDED_FOR' );
			}else if ( getenv( 'HTTP_CLIENT_ip' ) ) {
				$realip = getenv( 'HTTP_CLIENT_ip' );
			} else {
				$realip = getenv( 'REMOTE_ADDR' );
			}
		}
		return $realip;
	}
	
	public static function getYearFlag( ) {
		$month = intval( date('m') );
		$yearFlag = 0;
		if ( $month > 6 ) {
			$yearFlag = 1;
		}
		$yearFlag = date('y').$yearFlag;
		return $yearFlag;		
	}	
	public static function removeSquareBrackets ( $cString ) {
		$cString = str_replace( "[" , "" , $cString);
		$cString = str_replace( "]" , "" , $cString);
		return $cString;
	}

	/**
	 * 把相对地址转换成绝对地址
	 * @param unknown_type $url
	 * @param unknown_type $URI
	 */
	public static function real_url( $url , $URI ) {   
	    $URI_PARTS = parse_url($URI);
	    $pnum = substr_count($url,"../");   
	    if(substr($url,0,1) == "/") $url = "http://".$URI_PARTS["host"].$url;   
	    if(substr($url,0,2) == "./") $url = dirname($URI).substr($url,1);   
	    if($pnum>0) {   
	        for($i=0;$i< ($pnum+1);$i++) {   
	            $URI = dirname($URI);   
	        }   
	        $url = str_replace("../","",$url);   
	        $url = $URI."/".$url;   
	    }   
	    return $url;   
	}
	
	public static function getRealAvatarUrl( $url , $base_url = IMAGE_PATH ) {
		if ( empty($url) ){
			$url = DEFAULT_AVATAR_SOURCE;
		}
		$URI_PARTS = parse_url($base_url);
//		$url = '../abc';
	    $pnum = substr_count($url,"../");  
	    if(substr($url,0,1) == "/") $url = "http://".$URI_PARTS["host"].$url;   
	    if(substr($url,0,2) == "./") $url = $base_url . substr($url,2);   
		if($pnum > 0) {   
//	        for($i=0;$i< ($pnum+1);$i++) {   
//	            $base_url = dirname($base_url);   
//	        }   
	        $url = str_replace("../","",$url);   
	        $url = $base_url.$url;   
	    }  
	    
		if(strpos( $url , 'http://') === false){
			$url = $base_url . $url;
		}
		return $url;  
	}
	
	public static function getRandAgent( ) {
		$GLOBALS['AGENT_STR'] = array(
	        'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQPinyin 689; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 3.0.04506.30)',                                                                                                                                                 
	        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; InfoPath.2; .NET CLR 2.0.50727)',                                               
	        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB6.4; .NET CLR 2.0.50727; CIBA; InfoPath.2)',                                 
	        'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/533.2 (KHTML, like Gecko) Chrome/5.0.342.5 Safari/533.2 ',                          
	        'Mozilla/5.0 Firefox/3.0.5',                                                                                                                     
	        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; QQDownload 618; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; InfoPath.2; OfficeLiveConnector.1.3; OfficeLivePatch.0.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; MAXTHON 2.0)',                                                                      
		);
		return $GLOBALS['AGENT_STR'][ rand(0,count($GLOBALS['AGENT_STR'])-1) ];           
	}
	
	public static function getUserBrowser( $user_OSagent ){
		if(strpos($user_OSagent,"Maxthon") && strpos($user_OSagent,"MSIE")) {
			$visitor_browser ="Maxthon(Microsoft IE)";
		}elseif(strpos($user_OSagent,"Maxthon 2.0")) {
			$visitor_browser ="Maxthon 2.0";
		}elseif(strpos($user_OSagent,"Maxthon")) {
			$visitor_browser ="Maxthon";
		}elseif(strpos($user_OSagent,"MSIE 9.0")) {
			$visitor_browser ="MSIE 9.0";
		}elseif(strpos($user_OSagent,"MSIE 8.0")) {
			$visitor_browser ="MSIE 8.0";
		}elseif(strpos($user_OSagent,"MSIE 7.0")) {
			$visitor_browser ="MSIE 7.0";
		}elseif(strpos($user_OSagent,"MSIE 6.0")) {
			$visitor_browser ="MSIE 6.0";
		} elseif(strpos($user_OSagent,"MSIE 5.5")) {
			$visitor_browser ="MSIE 5.5";
		} elseif(strpos($user_OSagent,"MSIE 5.0")) {
			$visitor_browser ="MSIE 5.0";
		} elseif(strpos($user_OSagent,"MSIE 4.01")) {
			$visitor_browser ="MSIE 4.01";
		}elseif(strpos($user_OSagent,"MSIE")) {
			$visitor_browser ="MSIE 较高版本";
		}elseif(strpos($user_OSagent,"NetCaptor")) {
			$visitor_browser ="NetCaptor";
		} elseif(strpos($user_OSagent,"Netscape")) {
			$visitor_browser ="Netscape";
		}elseif(strpos($user_OSagent,"Chrome")) {
			$visitor_browser =substr($user_OSagent, strpos($user_OSagent,"Chrome") ,20 );
			//$visitor_browser ="Chrome";
		} elseif(strpos($user_OSagent,"Lynx")) {
			$visitor_browser ="Lynx";
		} elseif(strpos($user_OSagent,"Opera")) {
			$visitor_browser ="Opera";
		} elseif(strpos($user_OSagent,"Konqueror")) {
			$visitor_browser ="Konqueror";
		} elseif(strpos($user_OSagent,"Mozilla/5.0")) {
			$visitor_browser =substr($user_OSagent, strpos($user_OSagent,"Mozilla/5.0") ,20 );
			//$visitor_browser ="Mozilla";
		} elseif(strpos($user_OSagent,"Firefox")) {
			$visitor_browser =substr($user_OSagent, strpos($user_OSagent,"Firefox") ,20 );
			//$visitor_browser ="Firefox";
		}elseif(strpos($user_OSagent,"U")) {
			//$visitor_browser =substr($user_OSagent, strpos($user_OSagent,"Firefox") ,20 );
			$visitor_browser ="Firefox";
		} else {
			$visitor_browser ="其它";
		}
		return $visitor_browser;
	}
	public static function userBrowser($user_OSagent){
		//$user_OSagent = $_SERVER['HTTP_USER_AGENT'];
		$Agent = $user_OSagent ;
		if (eregi('win',$Agent) && strpos($Agent, '95')) {
			$browserplatform="Windows 95";
		}
		elseif (eregi('win 9x',$Agent) && strpos($Agent, '4.90')) {
			$browserplatform="Windows ME";
		}
		elseif (eregi('win',$Agent) && ereg('98',$Agent)) {
			$browserplatform="Windows 98";
		}
		elseif (eregi('win',$Agent) && eregi('nt 5.0',$Agent)) {
			$browserplatform="Windows 2000";
		}
		elseif (eregi('win',$Agent) && eregi('nt 5.1',$Agent)) {
			$browserplatform="Windows XP";
		}
		elseif (eregi('win',$Agent) && eregi('nt 6.0',$Agent)) {
			$browserplatform="Windows Vista";
		}
		elseif (eregi('win',$Agent) && eregi('nt 6.1',$Agent)) {
			$browserplatform="Windows 7";
		}
		elseif (eregi('win',$Agent) && ereg('32',$Agent)) {
			$browserplatform="Windows 32";
		}
		elseif (eregi('win',$Agent) && eregi('nt',$Agent)) {
			$browserplatform="Windows NT";
		}elseif (eregi('Mac OS',$Agent)) {
			$browserplatform="Mac OS";
		}
		elseif (eregi('linux',$Agent)) {
			$browserplatform="Linux";
		}
		elseif (eregi('unix',$Agent)) {
			$browserplatform="Unix";
		}
		elseif (eregi('sun',$Agent) && eregi('os',$Agent)) {
			$browserplatform="SunOS";
		}
		elseif (eregi('ibm',$Agent) && eregi('os',$Agent)) {
			$browserplatform="IBM OS/2";
		}
		elseif (eregi('Mac',$Agent) && eregi('PC',$Agent)) {
			$browserplatform="Macintosh";
		}
		elseif (eregi('PowerPC',$Agent)) {
			$browserplatform="PowerPC";
		}
		elseif (eregi('AIX',$Agent)) {
			$browserplatform="AIX";
		}
		elseif (eregi('HPUX',$Agent)) {
			$browserplatform="HPUX";
		}
		elseif (eregi('NetBSD',$Agent)) {
			$browserplatform="NetBSD";
		}
		elseif (eregi('BSD',$Agent)) {
			$browserplatform="BSD";
		}
		elseif (ereg('OSF1',$Agent)) {
			$browserplatform="OSF1";
		}
		elseif (ereg('IRIX',$Agent)) {
			$browserplatform="IRIX";
		}
		elseif (eregi('FreeBSD',$Agent)) {
			$browserplatform="FreeBSD";
		}
		if ($browserplatform=='') {$browserplatform = "其他"; }
		
		return $browserplatform.' + '.self::getUserBrowser($user_OSagent);
	}
	
	/**
	 * 0-1背包算法PHP实现
	 * Author:wumin
	 * @param array $weights 物品重量数组
	 * @param array $values  对应物品价值数组
	 * @param int $limit  背包限重
	 * @return array  返回最大价值,可传入货物数组得到相应信息
	 */
	function knapsackAlgorithm($weights, $values, $limit, &$goods_list)
	{
		$tmp_val  = 0;
		$tmp_weight = 0;
		$max_val  = array();
		$goods_tmp  = array();
		$goods_list = array();
		foreach ($weights as $key => $weight)
		{
			for ($tmp_weight = $limit; $tmp_weight >= $weight; --$tmp_weight)
			{
				$tmp_goods_index = $tmp_weight - $weight;//找到一定放下当前$nVolume[$itemIndex]重量的点
				
				isset($max_val[$tmp_goods_index]) ?
				$max_val[$tmp_goods_index] :
				$max_val[$tmp_goods_index] = 0;
				isset($max_val[$tmp_weight]) ?
				$max_val[$tmp_weight] :
				$max_val[$tmp_weight] = 0;
				$tmp_val = $max_val[$tmp_goods_index] + $values[$key];
				if ($tmp_val > $max_val[$tmp_weight])
				{
					$max_val[$tmp_weight] = $tmp_val;
					$goods_tmp[$key][$tmp_weight] = 1;
				}
			}
		}
		//根据good_tmp数组倒推出货物列表
		for ($i = $key, $tmp_weight = $limit; $i >= 0; --$i)//按货物加载顺序逆序倒推回去,因为前面是按照顺序判断是否加入当前货物的
		{
			if(isset($goods_tmp[$i][$tmp_weight]))//如果当前货物在相应记录出现过
			{
				$goods_list[$weights[$i]] = $values[$i];
				$tmp_weight -= $weights[$key];
			}
		}
		return $max_val[$limit];
	}
	/**
	 * $weights 为字体大小
	 * $value 为汉字个数
	 */
	function greedyAlgorithm($weights, $values, $limit){
		$sum = 0 ;
		$count = 0 ;
		$min = 0;
		$max = 0;
		$minkey = 0;
		$maxkey = 0;
		$return = array();
		if( is_array($values) && !empty($values)){
			foreach ($values as $vk=>$v){
				 $sum += $v; 
				 if($min == 0 || $min <= $v){
				 	$min = $v;$minkey = $vk;
				 }
				 if($max == 0 || $max >= $v){
				 	$max = $v;$maxkey = $vk;
				 }
			}
			$ava = $limit/$sum;
			$intava =  intval($ava) ;
			$ceilava = $intava +1;
			$overflow = $ava - $intava ;
			$minra = $min/$sum ;
			$maxra = $max/$sum ;
			if( $intava > 18 ){
				$intava = 18;
				$ceilava = 18;
			}
			if ( $minra > $overflow ){
				foreach ($values as $vk=>$v){
					$return[$vk] = $intava ;
				}
			}elseif ( $minra < $overflow && $maxra > $overflow ){
				foreach ( $values as $vk=>$v ){
					$return[$vk] = $intava;
				}
				$return[$minkey] = $ceilava ;
			}elseif ($maxra < $overflow){
				foreach ( $values as $vk=>$v ){
					$return[$vk] = $intava ;
				}
				$return[$maxkey] = $ceilava ;
			}
		}
		return $return;
	}
}//end 





