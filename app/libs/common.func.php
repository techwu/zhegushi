<?php
function arrayIntersect() {
	if (!func_num_args()) {
		return false;
	}
	$params = func_get_args() ; 
	foreach ( $params AS $arg) {
		if (!is_array($arg)) {
			return false;
		}
	}
	$result = array();
	$data = array_count_values(
			call_user_func_array('array_merge',$params )
	);
	foreach ($data AS $value => $count) {
		if ($count > 1) {
			$result[] = $value;
		}
	}
	return $result;
}

/**
 * 实现错误跟踪
 * @param unknown_type $errno
 * @param unknown_type $errstr
 * @param unknown_type $errfile
 * @param unknown_type $errline
 * @return unknown_type
 */

function tracking($errno, $errstr, $errfile, $errline) {
	if ( ERRNOTEMODE == 2 ) {
		if ( $errno == 2048 || $errno == 8192 || $errno == 8 || $errno == 2 ) {
			return true;
		}
	} else {
			if ( $errno == 2048 || $errno == 8192  ) {
			return true;
		}		
	}
	$debug=debug_backtrace();
	$errmsg='';
	foreach($debug as $id=>$val)
	{
		if( !isset($val['file']) ) {
			$val['file'] = '';		
		}
		if( !isset($val['line']) ) {
			$val['line'] = '';		
		}
		if($id==0) {
			$errmsg.="#$id [$errno : $errstr] {$val['function']} call at {$val['file']} ({$val['line']})\r\n";
		} else {
			$errmsg.="#$id {$val['function']} call at {$val['file']} ({$val['line']})\r\n";
		}
	}
	echo nl2br($errmsg);
	echo nl2br("------------------------------------------------------------------------------<br/>");
}


/**
 * 动态加载文件的函数
 * @param unknown_type $importStr
 * @return unknown_type
 */
function importer( $importStr ) {
	$importerO = new importer( $importStr );
	$importStatus = $importerO->importFile();
	if ( $importStatus === false ) {
		echo $importerO->getErrorMsg();
		exit(1);
	} 
	return true;
}
/*
 * Created on 2008-3-14
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
function remotePathParser( $rPath ) {
	$a1 = split(':',$rPath);
	$dPath = $a1[1];
	$a2 = split('@',$a1[0]);
	$serverName = $a2[1];
	$userName = $a2[0];
	return array('server'=>$serverName,'username'=>$userName,'path'=>$dPath);
}
function URL_DIVERSIONER($d_url){
	$d_url = trim($d_url,"/");
	if(strpos($d_url,"?")!==false){ //过滤掉query_string
		$d_url = substr($d_url,0,strpos($d_url,"?"));
	}
	$urlArray = explode("/" , $d_url );
	return $urlArray;
}

function zsetcookie($var, $value, $life = 0) {
	setcookie($var, $value,$life,DEFAULT_COOKIEPATH,DEFAULT_COOKIEDOMAIN);
}

function zaddslashes($string, $force = 0, $strip = FALSE) {
	if ( !defined( "MAGIC_QUOTES_GPC" ) ) {
		define( "MAGIC_QUOTES_GPC", "" );
	}
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = zaddslashes($val, $force, $strip);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
			$string = htmlspecialchars($string);
		}
	}
	return $string;
}

function get_microtime(){
	list($usec, $sec) = explode(' ', microtime()); 
	return ((float)$usec + (float)$sec); 
}

/**
* mode=1 : ip2long
* mode=0 : ip
*/
function getClientIP($mode=1){
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	$onlineip = isset($onlineipmatches[0]) ? $onlineipmatches[0] : '127.0.0.1';
	unset($onlineipmatches);
	if($mode==1){
			return ip2long($onlineip);
	}
	return $onlineip;
}
	
function DataToArray ( $dbData , $keyword ) {
//	print_r( $dbData );die();
	$retArray = array();
	if  ( is_array ($dbData)==false OR empty($dbData) ) {
		return $retArray;
	}
	foreach($dbData as $oneData ) {
		if ( isset($oneData[$keyword]) and empty($oneData[$keyword])==false) {
			$retArray[] = $oneData[$keyword];
		}
	}
//	return array_unique($retArray);
	return $retArray;
}

function arrayDefineUnique( $array ){
	$array = array_unique($array);
	for( $i = 0 ; $i < count($array) ; $i ++ ){
		if (isset($array[$i])){
			for( $j = 0 ; $j < count($array) ; $j ++ ){
				if(isset($array[$j]) && isset($array[$i])){
					if ( strpos($array[$i] ,  $array[$j]) !== false ){
						unset($array[$j]);
					}elseif (  strpos($array[$j] ,  $array[$i]) !== false  ){
						unset($array[$i]);
					}
				}
			}
		}
	}
	return $array;
}

function sortArray($array , $order_by , $order_type = 'ASC'){
	if ( !is_array($array) ){
		return array();
	}
	$order_type = strtoupper($order_type);
	if($order_type != 'DESC'){
		$order_type = SORT_ASC;
	} else {
		$order_type = SORT_DESC;
	}
	
	$order_by_array = array();
	foreach($array as $k => $v){
		$order_by_array[] = $array[$k][$order_by];
	}
	array_multisort($order_by_array , $order_type , $array);
	return $array;
}

function multiArraySort($multi_array,$sort_key,$sort=SORT_ASC , $exten_key = '' , $exten = '' , $exten_data = MAXNUM){
	$exten = explode(',', $exten);
	if(is_array($multi_array)){
		foreach ($multi_array as $row_array){
			if(is_array($row_array)){
				if( empty($exten) || empty($exten_key) ){
					$key_array[] = $row_array[$sort_key];
				}else{
					if( in_array($row_array[$exten_key] , $exten ) ){
						if( is_numeric($exten_data) ){
							$key_array[] = $row_array[$sort_key] + MAXNUM ;
						}else{
							$key_array[] = $exten_data.$row_array[$sort_key] ;
						}
					}else{
						$key_array[] = $row_array[$sort_key];
					}
				}
			}else{
				return $multi_array;
			}
		}
	}else{
		return $multi_array;
	}
	array_multisort($key_array,$sort,$multi_array);
	return $multi_array;
}

function multiArrDataConvertDataSum( $multi_array , $key_id , $value , $is_percent = true){
	$result = array();
	if($is_percent){
		$all_info_numbers = count($multi_array);
	}else{
		$all_info_numbers = 1;
	}
	if(!empty($multi_array)){
		foreach ( $multi_array as $arr ){
			if(!isset($result[$arr[$key_id]])){
				$result[$arr[$key_id]]['number'] = 1;
			}else{
				$result[$arr[$key_id]]['number'] ++ ;
			}
			$result[$arr[$key_id]]['value'] = $arr[$value];
		}
// 		$result = sort($result , 1);
		$result = multiArraySort($result , 'number' , SORT_DESC);
		if($is_percent){
			if(!empty($result) && !empty($all_info_numbers)){
				foreach ($result as &$r){
					$r['number'] = round($r['number']/$all_info_numbers , 4) * 100 . "%" ;
				}
			}
		}
	}
	return $result;
}

/**
* 生成验证的 identify code
*/
function identifying_code($email,$registration_date){
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	return md5($useragent . "-" .$email . "-" .$registration_date);
}

function makeToken($token_len=6){
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	$time = time();
	$rand_num = rand(1,10);
	$token_str = md5(GE_KEY . $useragent . $time . $rand_num);
	return $toke = substr($token_str,10,$token_len);
}

function show404(){
	header("HTTP/1.1 404 Not Found");
	//header("Location: /error/404.html");
	$file = TMPDIR.'404.html';
	$page = file_get_contents($file);
	echo $page;
	exit;
}

function gotoLogon(){
	if(strpos($_SERVER[REQUEST_URI],'logon')===false){
		if($_POST['ajax']==1){
			echo "@uni@";
			exit();
		}else{
			header("Location: /logon");
			exit();
		}
	}
}

function isSessionExpire(){
	global $_USERID;
//	print_r( $_USERID );die();
	if($_USERID<=0){
		gotoLogon();
	}
}
/**
 * 字符串加密以及解密函数
 *
 * @param string $string 原文或者密文
 * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
 * @param string $key 密钥
 * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
 *
 * @example
 *
 * 	$a = authcode('abc', 'ENCODE', 'key');
 * 	$b = authcode($a, 'DECODE', 'key');  // $b(abc)
 *
 * 	$a = authcode('abc', 'ENCODE', 'key', 3600);
 * 	$b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;	// 随机密钥长度 取值 0-32;
				// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
				// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
				// 当此值为 0 时，则不产生随机密钥
	$key = md5($key ? $key : GE_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

function filterSegmentKeyword($title){
		$title = htmlspecialchars_decode($title,ENT_QUOTES);
		$title = str_replace(array(":","<",">","/","?","'","[","]","*",".","(",")","-"), "", $title);
		$title = str_replace(array("\"","”","“","?","。","，","!","！",";","；")," ", $title);
		$title = trim($title);
		return $title;
}

function filterNotAllowChar($title){
		$title = htmlspecialchars_decode($title,ENT_QUOTES);
		$title = str_replace(array("“","”"), "\"", $title);
//	$title = str_replace(array("<",">","|","+","-"), "", $title);
		return $title;
}


function segmentKeywords(&$segments,$keywords,$seg_num=10){
	if(empty($keywords)||!is_array($keywords)){
		return;
	}
	$cws = scws_new();
	$cws->set_charset('utf8');
	$cws->set_dict(BASEPATH . 'lib/scws/dict.xdb');
	$cws->set_rule(BASEPATH . 'lib/scws/rules.ini');
	$cws->set_ignore(true);
	$cws->set_duality(true);
	$cws->set_multi(2); // '最短词' => 1, '二元' => 2, '重要单字' => 4, '全部单字' => 8
	
	$segments = array();
	foreach($keywords as $k=>$keyword){
		$top = array();
		$cws->send_text($keyword);
		$top = $cws->get_tops($seg_num);
		if(empty($top)){
			$segs = array();
		}else{
			$t = null;
			$segs = array();
			foreach($top as $t){
				$segs[] = $t['word'];
			}
		}
		$segments[$k] = $segs;
	}
	$cws->close();
}

function segmentSingleKeyword($keyword,$seg_num=10){
	$keyword = trim($keyword);
	$top = array();
	$cws = scws_new();
	$cws->set_charset('utf8');
	$cws->set_dict(BASEPATH . 'lib/scws/dict.xdb');
	$cws->set_rule(BASEPATH . 'lib/scws/rules.ini');
	$cws->set_ignore(true);
	$cws->set_duality(true);
	$cws->set_multi(2); // '最短词' => 1, '二元' => 2, '重要单字' => 4, '全部单字' => 8
	$cws->send_text($keyword);
	$top = $cws->get_tops($seg_num);
	$cws->close();

	if(empty($top)){
		return null;
	}else{
		$t = null;
		$kw = array();
		foreach($top as $t){
			$kw[] = $t['word'];
		}
	}
	return $kw;
}


function highlighTotalKeyword($keyword,&$content){
	if(empty($content) || empty($keyword)){
		return false;
	}
	$keyword = filterSegmentKeyword($keyword);
	$pos = strpos($content,$keyword);
	//try to total match
	if(strpos(strtolower($content),strtolower($keyword))!==false){
// 		$content = eregi_replace("/".quotemeta($keyword)."/i","<em class='search-key'>\\1</em>",$content);
		$content = preg_replace("/(?is)($keyword)/i","<em class='search-key'>$1</em>",$content);
		return true;
	}
	return false;
}

function highlighTotalKeywordArray($keyword , $content , $except_key = '' ){
	if(empty($content) || empty($keyword)){
		return $content;
	}
// 	var_dump($except_key);die();
	if( is_array($content) ){
		foreach ($content as $k=>&$a){
			if(!is_numeric($k)){
				if(!preg_match("/($except_key)/i", $k)) {
					$a = highlighTotalKeywordArray( $keyword, $a , $except_key );
				}
			}else{
				$a = highlighTotalKeywordArray( $keyword, $a , $except_key);
			}
		}
	}else{
		if(empty($content) || empty($keyword)){
			return $content;
		}else{
			highlighTotalKeyword($keyword,$content);
		}
	}
	return $content;
}

function highlightKeyword($keyword,$content){
	$keyword = filterSegmentKeyword($keyword);
	$keyword = trim($keyword);
	if(empty($keyword)) return $content;
	$keyword = preg_replace("/\s+/i","|",$keyword);
	$keys = explode("|",$keyword);
	$keys = array_unique($keys); // remove duplicate segment
	$regex = implode("|",$keys);
	$content = preg_replace("/(?is)($regex)/","<em class='search-key'>$1</em>",$content);
	return $content;
}

function makeRegisterAuthKey(){
	while(true){
		$mpkey = substr(md5(time()),5,10);
		$authkey = authcode($mpkey,"ENCODE","ge key");
		$authkey = str_replace(array("/","+"),"",$authkey);
		$authkey = strtoupper(substr($authkey,0,12));
		$num = rand(10,99);
		$authkey = "GE" .  $num . $authkey;
		if(strlen($authkey)==16){
			break;
		}
	}
	return $authkey;
}

function makeSign($str,$len=16){
	return substr(md5($str),0,$len);
}


function getDomain($url){
	$pos = stripos($url,"http://");
	if($pos===false){
		$url = "http://";
	}
	$pos = stripos($url,"/",7);
	if($pos===false){
		return $url;
	}
	$domain_url = substr($url,0,$pos);
	return $domain_url;
}

function isMobile(){
	$useragent=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
	$useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';

	$mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');

	$mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160*160',' 176*220','240*240','240*320','320*240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod' ,'iPad');

	$found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) || CheckSubstrs($mobile_token_list,$useragent);

	if ($found_mobile){ return true;}else{ return false;}

}

function isAndroidOrIOsMobile(){
	$useragent=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
	$useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
	
	$mobile_os_list=array('Android' , 'iPhone' ,'Mobile','Opera Mobi','Smartphone','Go.Web','iPAQ');
	
	$mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160*160',' 176*220','240*240','240*320','320*240','SonyEricsson','Nokia','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
	
	if(strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) {
		return false;
	}
	
	$found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) || CheckSubstrs($mobile_token_list,$useragent);
	
	if ($found_mobile){ return true;}else{ return false;}
}

function isIPad(){
	if (isMobile()){
		if(strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) {
			return true;
		}
	}
	return false;
}
/*
 *check robot
*/
function isRobot() {
	$kw_spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';
	$kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
	if(!strexists($_SERVER['HTTP_USER_AGENT'], 'http://') && preg_match("/($kw_browsers)/i", $_SERVER['HTTP_USER_AGENT'])) {
		return false;
	} elseif(preg_match("/($kw_spiders)/i", $_SERVER['HTTP_USER_AGENT'])) {
		return true;
	} else {
		return false;
	}
}

function isSearchEngine( $agentStr= "" ) {
	if ( empty($agentStr) ) {
		$agentStr = $_SERVER['HTTP_USER_AGENT'];
	}
	if (empty($agentStr)){
		return false;
	}
	$kw_spiders = 'Baiduspider|Python-urllib|Yandex|alexa.com|Yahoo|YodaoBot|ia_archiver|iaskspider|Sogou|Mediapartners-Google|msnbot|'.
			'Googlebot|Bot|Crawl|Spider|spider|slurp|sohu-search|lycos|robozilla|ApacheBench|Gigabot|QihooBot|JikeSpider|Sosospider|bingbot|bing';
	$kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
	if(preg_match("/($kw_spiders)/i", $agentStr)) {
		return true;
	} else {
		return false;
	}
}

function isBrowser( $agentStr = ''){
	if ( empty($agentStr) ) {
		$agentStr = $_SERVER['HTTP_USER_AGENT'];
	}
	if (empty($agentStr)){
		return false;
	}
	
	if( isMobile() ){
		return true;
	}
	$kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla|AppleWebKit|Safari|Firefox|Chrome';
	if(preg_match("/($kw_browsers)/i", $agentStr)) {
		return true;
	} else {
		return false;
	}
}


function getDomainSign($url){
	$domain_url = getDomain($url);
	return substr(md5($domain_url),0,16);
}

function genRelaceRegex(){
	global $replace_domain;
	if(empty($replace_domain)) return;
	$regex_arr = array();
	foreach($replace_domain as $v){
		$regex_arr[] = "/<img src='([^']+".$v."[^']+)'\/>/i";
	}
	return $regex_arr;
}

/**
 * 实现GB2312编码转换为utf8编码~~
 * @param string $str
 * @return string
 */
function gb2312ToUtf8($str , $fromCode='gbk'){
	$tempStr = mb_convert_encoding($str,'utf-8', $fromCode); 
	return $tempStr;
}

function utf8ToGbk ( $str ) {
	return mb_convert_encoding( $str , 'gbk' , 'utf-8');
}

/**
 * 时间转换函数，将时间转换成几分钟前，几小时前的形式。
 * @param timestamp $createTime
 * @return 	string $str
 */
function timeStrConverter( $createTime ){
	//echo $createTime;die();
	$timeValue = ceil((time() - $createTime)/60);
	//echo "{$timeValue}:::";
	if($timeValue<0){
		$timeValue = 0-$timeValue;
	}
	if( $timeValue<20 ){
		$str = " {$timeValue}分钟前 ";
	}elseif( $timeValue<1440 ){  //一天以内的时间
		$hours = date("G");//当前时间点
		$createHours = date("G",$createTime);
		//echo $createHours;echo ":::";
		//echo $hours;echo ":::";
		if ($createHours<=$hours){
			$str = '今天';
		}else{
			$str = '昨天';
		}
		if( $createHours != 0 ){
			$str .= ' ' . date("G:i",$createTime);
		}
	}elseif( $timeValue>=525600 ){  //一年以上的时间
		$createHours = date("G",$createTime);
		if( $createHours != 0 ){
			$str = date("Y年n月j日 G:i", $createTime);
		}else{
			$str = date("Y年n月j日", $createTime);
		}
	}else{ //今年内的时间 并且 一天以上的时间
		$createHours = date("G",$createTime);
		if( $createHours != 0 ){
			$str = date("n月j日 G:i", $createTime);
		}else{
			$str = date("n月j日", $createTime);
		}
	}
	//echo $str;
	return $str;
}

/**
 * 时间转换函数，将时间转换成几分钟,几小时的形式。
 * @param timestamp $endtime
 * @return 	string $str
 */
function timeStrConver($endtime){
	$timeValue = ceil(($endtime - time())/60);
	if($timeValue<=0){
		$str="";
	}
	if( $timeValue<60 ){
		$str = " {$timeValue}分钟 ";
	}elseif( $timeValue<1440 ){  //一天以内的时间
		$HourValue=ceil($timeValue/60)-1;
		$MinValue=$timeValue-$HourValue*60-1;
		$str= "{$HourValue}小时{$MinValue}分钟";
	}elseif($timeValue<43200){
		$DayValue=ceil($timeValue/1440)-1;
		$HourValue=ceil(($timeValue-($DayValue*1440))/60)-1;
		$MinValue=$timeValue-$HourValue*60-$DayValue*1440;
		$str= "{$DayValue}天{$HourValue}小时{$MinValue}分钟";
//		print_r($DayValue);print_r($HourValue);print_r($MinValue);die();
	}elseif( $timeValue>=525600 ){  //一年以上的时间
		$YearValue=ceil($timeValue/525600)-1;
		$str = "有效期{$YearValue}年";
	}else{ //今年内的时间 并且 一月以上的时间
		$MonValue=ceil($timeValue/43200)-1;
		$DayValue=ceil(($timeValue-($MonValue*43200))/1440)-1;
		$HourValue=ceil(($timeValue-($DayValue*1440)-($MonValue*43200))/60)-1;
		$MinValue=$timeValue-$HourValue*60-$DayValue*1440-$MonValue*43200;
		$str = "{$MonValue}月{$DayValue}天{$HourValue}小时{$MinValue}分钟";
	}
	return $str;
}
/**
 * 通过指定的时间获得此时间的显示方式：
 * 	1.xxxx-1-1为xxxx年初
 *  2.xxxx-xx-1为xxxx年xx月初
 *  3.xxxx-12-31为xxxx年末
 *  4.xxxx-6-1为xxxx年中
 *  5.xxxx-9-30为xxxx下半年
 *  6.xxxx-3-31为第一季度
 */
function timelineTimeConvert( $time ){
	$y = date('Y', $time);
	$m = date('m', $time);
	$d = date('j', $time);
	
	$thisday = array("firsttime"=>mktime(0,0,0,$m,$d,$y),"lasttime"=>mktime(23,59,59,$m,$d,$y));
	
	$ytime = yFristAndLastTime($time);
	$mtime = mFristAndLastTime($time);
	if ( $thisday['firsttime'] == $ytime['firsttime'] ){
		return $y . ' 年   初' ; 
	}
	if ( $thisday['firsttime'] == $mtime['firsttime'] ){
		return $y . '年' . $m . '月初' ;
	}
	if( $thisday['lasttime'] == $ytime['lasttime'] ){
		return $y . '年末' ;
	}
	if( $thisday['lasttime'] == $mtime['lasttime'] ){
		return $y . '年' . $m . '月  末' ;
	}
	return date('Y年m月d日' , $time );
}
/**
 *
 * 获取指定年月的开始和结束时间戳
 *
 * @param int $time 当月任意时间戳
 * @return array(开始时间,结束时间)
 */
function mFristAndLastTime($time=0){
	$time = $time ? $time : time();
	$y = date('Y', $time);
	$m = date('m', $time);
	$d = date('t', $time);
	return array("firsttime"=>mktime(0,0,0,$m,1,$y),"lasttime"=>mktime(23,59,59,$m,$d,$y));
}

function yFristAndLastTime($time=0){
	$time = $time ? $time : time();
	$y = date('Y', $time);
	$m = date('m', $time);
	$d = date('t', $time);
	return array("firsttime"=>mktime(0,0,0,1,1,$y),"lasttime"=>mktime(23,59,59,$m,$d,$y));
}

function weekFirstAndLastTime($time=0){
	$time = $time ? $time : time();
	$y = date('Y', $time);
	$m = date('m', $time);
	$w = date('w');
	$d = date('t', $time);
	
	$firstday = date('d') - $w;
	if(substr($firstday,0,1) == "-"){
		$firstMonth = $m - 1;
		$lastMonthDay = date("t",$firstMonth);
		$firstday = $lastMonthDay - substr($firstday,1);
		$firsttime = strtotime($y."-".$firstMonth."-".$firstday);
	}else{
		$firsttime = strtotime($y."-".$m."-".$firstday);
	}
	 
	$lastday = date('d') + (7 - $w);
	if($lastday > $d){
		$lastday = $lastday - $d;
		$lastMonth = $m + 1;
		$lasttime = strtotime($y."-".$lastMonth."-".$lastday);
	}else{
		$lasttime = strtotime($y."-".$m."-".$lastday);
	}
	return array("firsttime"=>$firsttime,"lasttime"=>$lasttime);
}

function post2Str () {
	return json_encode($_POST);
}

 /*
        * 中文截取，支持gb2312,gbk,utf-8,big5 
        *
        * @param string $str 要截取的字串
        * @param int $start 截取起始位置
        * @param int $length 截取长度
        * @param string $charset utf-8|gb2312|gbk|big5 编码
        * @param $suffix 是否加尾缀
        */
function c_substr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
	if(function_exists("mb_substr")){
    	if(mb_strlen($str, $charset) <= $length) return $str;
        	$slice = mb_substr($str, $start, $length, $charset);
    }else{
       	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
       	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
       	$re['gbk']          = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
       	$re['big5']          = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
       	preg_match_all($re[$charset], $str, $match);
        if(count($match[0]) <= $length) return $str;
       	$slice = join("",array_slice($match[0], $start, $length));
    }
    if($suffix) return $slice."...";
    return $slice;
}
/*
 * 中文字符串长度，支持gb2312,gbk,utf-8,big5
*
* @param string $str 要截取的字串
* @param string $charset utf-8|gb2312|gbk|big5 编码
*/
function abslength($str , $charset="utf-8") 
{
	if(empty($str)){
		return 0;
	}
	if(function_exists('mb_strlen')){
		return mb_strlen($str,$charset);
	}
	else {
		preg_match_all("/./u", $str, $ar);
		return count($ar[0]);
	}
}

/**
 * 把$data的key改成$keyName列的值
 * @param unknown_type $data
 * @param unknown_type $keyName
 */
function changeDataKeys($data, $keyName) {
	$resArr = array();
	foreach($data as $v) {
		$resArr[$v[$keyName]] = $v;
	}
	return $resArr;
}


function createSign ($paramArr ,$appSecret) { 
   // global $appSecret; 
    $sign = $appSecret; 
    ksort($paramArr); 
    foreach ($paramArr as $key => $val) { 
       if ($key !='' && $val !='') { 
           $sign .= $key.$val; 
       } 
    } 
    $sign = strtoupper(md5($sign));  //Hmac方式
//    $sign = strtoupper(md5($sign.$appSecret)); //Md5方式	
    return $sign; 
}

//组参函数 
function createStrParam ($paramArr) { 
    $strParam = ''; 
    foreach ($paramArr as $key => $val) { 
       if ($key != '' && $val !='') { 
           $strParam .= $key.'='.urlencode($val).'&'; 
       } 
    } 
    return $strParam; 
} 

//解析xml函数
function getXmlData ($strXml) {
	$pos = strpos($strXml, 'xml');
	if ($pos) {
		$xmlCode=simplexml_load_string($strXml,'SimpleXMLElement', LIBXML_NOCDATA);
		$arrayCode=get_object_vars_final($xmlCode);
		return $arrayCode ;
	} else {
		return '';
	}
}
	
function get_object_vars_final($obj){
	if(is_object($obj)){
		$obj=get_object_vars($obj);
	}
	if(is_array($obj)){
		foreach ($obj as $key=>$value){
			$obj[$key]=get_object_vars_final($value);
		}
	}
	return $obj;
}

function fixNickname($nickname) {
	$nickname = trim($nickname);
	$nicknameArr = split('#', $nickname);
	if( isset($nicknameArr[0]) ) {
		return $nicknameArr[0];
	} else {
		return $nickname;
	}
}

function CheckSubstrs($substrs,$text)
{
	foreach($substrs as $substr)
	if(false!==strpos($text,$substr)){
		return true;
	}
	return false;
}


	function getFileExt($filename){
		return strtolower(substr(strrchr($filename, '.'), 1, 10));
	}
	
	function getFileName( $filename ){
		return urldecode(substr(strrchr($filename, '/'), 1, 100));
	}
	
	function getFileHeader($filename){
		$file_ext = getFileExt($filename);
		foreach ($GLOBALS['mime_types'] as $k=>$v){
			if($k == $file_ext){
				return $v;
			}
		}
		return 'application/force-download';
	}
	/*
	 * g该函数用来获得指定目录下指定扩展名的文件列表
	 * $path_name String 指定目录名
	 * $ext array 包含扩展名的数组(包含.js.php)
	 * @return array
	 */
	function getFileForm( $path_name , $exts = false){
		if ($exts !== false){
			for ($i = 0,$limit = count($exts) ; $i<$limit ; $i++){
				$exts[$i] = "*.".$exts[$i];
			}
		}
		$filter = !($exts)? "*":'{' . implode($exts, ',') . '}';
		$pattern = $path_name . '/' . $filter;
		return glob($pattern , GLOB_BRACE);
	}
	
	function getExtNameOfFile( $filename ){
		$file_ext = getFileExt( $filename );
	 	foreach ($GLOBALS['filetypedata'] as $k=>$v){
            foreach ($v as $name){
                if($name == $file_ext){
                    return $k;
                }
            }
        }
		return 'folder';
	}
	/*
	 * 操作上传图片
	 */
	function _uploadLogo($request , $attach = 'attach'){
		$checkPos = strpos ( $request , 'image/pjpeg');	
		$checkPos2 = strpos ( $request , '----FILE-FILE-FORM-DATA-BOUNDARY--');
		$pos2Length = strlen( '----FILE-FILE-FORM-DATA-BOUNDARY--' );
		
		//$request = substr ( $request , $checkPos+strlen('image/pjpeg') , $checkPos2-strlen( '----FILE-FILE-FORM-DATA-BOUNDARY--') );
		$request = substr ( $request , $checkPos+strlen('image/pjpeg') , $checkPos2);
		$request = str_replace( '----FILE-FILE-FORM-DATA-BOUNDARY--' , '' , $request);
		$request = trim( $request );
// 		if ( $_FILES[$attach]['size'][0] > 2097152 || $_FILES[$attach]['error'][0] != 0 ){
// 			return false ;
// 		}
		importer("corelib.qiniuHelper");
		$qiniu_helper = new qiniuHelper($attach);
		$file = $qiniu_helper->uploadFile();
		if( !empty($file) ){
			return $file; //就是一个图片地址    
		}else{
			return false;
		}
	}
	/*
	 * 操作上传文件
	 */
	function _uploadPdfFile( $attach = 'attach' , $type = 'product' , $identify = '' ){
		importer("corelib.qiniuHelper");
		$qiniu_helper = new qiniuHelper($attach , $type.'/'.$attach.'/'.md5($identify.time()) );
		$file = $qiniu_helper->uploadFile(false);
		if( !empty($file) ){
			return $file; //就是一个图片地址
		}else{
			return false;
		}
	}
	
	function formatFileSize($file_size){
		$unit = array('Bytes' , 'KB' , 'MB' , 'GB' , 'TB' , 'PB' , 'EB' , 'ZB' , 'YB');
		$i = 0;
		while( $file_size >= 1024 && $i < 8){
			$file_size /= 1024;
			++$i;
		}
		$file_size_tmp = sprintf("%.2f" , $file_size);
		return ($file_size_tmp - (int)$file_size_tmp?$file_size_tmp:$file_size) . $unit[$i];
	}
	
	function getStrInitial($str){
		$asc=ord(substr($str,0,1));
		if ($asc<160){ //非中文
			if ($asc>=48 && $asc<=57){
				return '1'; //数字
			}elseif ($asc>=65 && $asc<=90){
				return chr($asc); // A--Z
			}elseif ($asc>=97 && $asc<=122){
				return chr($asc-32); // a--z
			}else{
				return '~'; //其他
			}
		}else{ //中文
			$asc=$asc*1000+ord(substr($str,1,1));
			//获取拼音首字母A--Z
			if ($asc>=176161 && $asc<176197){
				return 'A';
			}elseif ($asc>=176197 && $asc<178193){
				return 'B';
			}elseif ($asc>=178193 && $asc<180238){
				return 'C';
			}elseif ($asc>=180238 && $asc<182234){
				return 'D';
			}elseif ($asc>=182234 && $asc<183162){
				return 'E';
			}elseif ($asc>=183162 && $asc<184193){
				return 'F';
			}elseif ($asc>=184193 && $asc<185254){
				return 'G';
			}elseif ($asc>=185254 && $asc<187247){
				return 'H';
			}elseif ($asc>=187247 && $asc<191166){
				return 'J';
			}elseif ($asc>=191166 && $asc<192172){
				return 'K';
			}elseif ($asc>=192172 && $asc<194232){
				return 'L';
			}elseif ($asc>=194232 && $asc<196195){
				return 'M';
			}elseif ($asc>=196195 && $asc<197182){
				return 'N';
			}elseif ($asc>=197182 && $asc<197190){
				return 'O';
			}elseif ($asc>=197190 && $asc<198218){
				return 'P';
			}elseif ($asc>=198218 && $asc<200187){
				return 'Q';
			}elseif ($asc>=200187 && $asc<200246){
				return 'R';
			}elseif ($asc>=200246 && $asc<203250){
				return 'S';
			}elseif ($asc>=203250 && $asc<205218){
				return 'T';
			}elseif ($asc>=205218 && $asc<206244){
				return 'W';
			}elseif ($asc>=206244 && $asc<209185){
				return 'X';
			}elseif ($asc>=209185 && $asc<212209){
				return 'Y';
			}elseif ($asc>=212209){
				return 'Z';
			}else{
				return '~';
			}
		}
	}
	
function getFirstCharOfChineseChar($s0){   
	if(empty($s0)){
		return null;
	}
    $fchar = ord($s0{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "I";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return null;
}
function getFirstCharOfChineseStr($zh){//取一串汉字的首字母
    $ret = "";
    $s1 = iconv("UTF-8","gb2312//IGNORE", $zh);
    $s2 = iconv("GBK","UTF-8//IGNORE", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getFirstCharOfChineseChar($s2);
        }else{
            $ret .= $s1;
        }
    }
    return $ret;
}
function getEmailBody($text){
	
}

function h_decode($var){
    if( is_array($var) ){
        foreach ($var as $key=>$value){
            $var[$key] = h($value);
        }
    } else {
        $var = htmlspecialchars_decode($var);
    }
    return $var;
}

function h($var){
    if( is_array($var) ){
        foreach ($var as $key=>$value){
            $var[$key] = h($value);
        }
    } else {
        $var = htmlspecialchars($var);
    }
    return $var;
}

/*
	 *  产生认领码:有效期半个小时
	 */
function createClaimCode($product_id){
	$lastCheckCode = commFun::getSession( "claimCode".$product_id );
	$lastTime = time();
	$mClaimCode = '';
	if ( !empty( $lastCheckCode) ) {
		$mClaimCode = $lastCheckCode;
	} else {
		$mClaimCode = strtoupper(substr(md5(rand()),0,6));
		commFun::setSession( 'claimCode'.$product_id, $mClaimCode );
	}
	commFun::setSession( 'claimCodeTime'.$product_id, $lastTime );
	return $mClaimCode;
}
	
function verifyClaimCode( $inputClaimCode , $product_id ) {
	if ( empty($inputClaimCode) ) {
		return -1;
	}
	$verifyStatus = 0;
	$corrClaimCode = commFun::getSession( "claimCode".$product_id );
	$corrClaimCodeTime= commFun::getSession( "claimCodeTime".$product_id );
	$now = time();
	if ( $corrClaimCode == $inputClaimCode && $now < $corrClaimCodeTime + 1800 ) {
		$verifyStatus =  1;
		commFun::setSession( 'checkCode'.$product_id, "" );
	} else {
		$verifyStatus =  -1;
	}
	if($now >= $corrClaimCodeTime + 1800){
		commFun::setSession( 'checkCode'.$product_id, "" );
		$verifyStatus =  0;
	}
	return $verifyStatus;		
}

function encryptEmail($email_name){
	$n =strpos($email_name, "@");
	$name = substr($email_name, 0,$n);
	$first_char = substr($name, 0,1);
	$last_char = substr($name, $n-1,1);
	$first_2char = substr($name, 0,2);
	$last_2char = substr($name, $n-2,2);
	if($n<=4){
		switch ($n){
			case "1":
				$enname = "*";
				break;
			case "2":
				$enname = $first_char."*";
				break;
			case "3":
				$enname = $first_char."*".$last_char;
				break;
			case "4":
				$enname = $first_char."**".$last_char;
				break;
				default;		
		}
	}else{
		$b = str_repeat("*", $n-4);
		$enname = $first_2char.$b.$last_2char;
	}
	$en_email_name = str_replace($name, $enname, $email_name);
	return $en_email_name;
}
/*
 * time
 * */

function getTheMonday( $time = '') {
//	return strtotime('2010-07-19');
	if($time == ''){
		$thisMonday = strtotime("this Monday");
		if( $thisMonday > time() ) {
			$thisMonday = strtotime("last Monday");
		}
		return $thisMonday;
	}else{
		$thisMonday = strtotime("this Monday" , $time);
		if( $thisMonday > $time ) {
			$thisMonday = strtotime("last Monday" , $time);
		}
		return $thisMonday;
	}
}

function dayOfWeek( $time ){
	$t = "";
	switch(date("D" ,strtotime( $time ))){
		case "Mon": $t=1;break;
		case "Tue": $t=2;break;
		case "Wed": $t=3;break;
		case "Thu": $t=4;break;
		case "Fri": $t=5;break;
		case "Sat": $t=6;break;
		default: $t=7;break;
	}
	return $t;
}

function exchangeMoney( $money ){
	return $money/10000;
}

function _getProvinceIdAndCityId( $location ){
		$split = ' ';
		$proid = dictionaryModel::getInstance()->getProvinceIdLikePname($location);
		
		if(empty($proid)){
			$proid = dictionaryModel::getInstance()->getReturnIDLikeCname($location , '');
			if(empty($proid)){
				$provinces = dictionaryModel::getInstance()->getAllProvinces();
				foreach ($provinces as $p){
					if(strpos( $location ,$p['S_PROVNAME'] )!== false){
						$proid = $p['N_PROVID'];
					}
				}
				if(empty($proid)){
					$proid = 0;
				}
				$citys = dictionaryModel::getInstance()->getAllCities();
				foreach ($citys as $c){
					if(strpos($location ,$c['S_CITYNAME'])!== false ){
						$proid = $c['N_PROVID'] . $split . $c['N_CITYID'];
					}
					if(empty( $proid ) || strpos($proid , ' ')==false){
						$rep = str_replace( '区', '' ,$c['S_CITYNAME']);
						if(strpos($location ,$rep )!== false ){
							$proid = $c['N_PROVID'] . $split . $c['N_CITYID'];
						}
					}
					if(empty( $proid )){
						if(strpos( $c['S_CITYNAME'] , $location)!== false ){
							$proid = $c['N_PROVID'] . $split . $c['N_CITYID'];
						}
					}
				}
				if( empty( $proid ) ){
					$proid = $proid . $split . '0';
				}
				return $proid;
			}else{
				return $proid['N_PROVID'] . $split . $proid['N_PROVID'];
			}
		}else{
			return $proid . $split . 0;
		}
	}
	
	function _processEmployees( $number ){
		if($number < 10 && $number > 0){
			return 1;
		}elseif ($number >= 10 && $number < 50){
			return 2;
		}elseif ($number >= 50){
			return 3;
		}else{
			return 0;
		}
	}	
	
	function processInputTxt( $text ){
		return str_replace(array("\r\n", "\n", "\r"), "<br>", $text);
	}
	
	function reProcessInputTxt( $text ){
		return str_replace(array("<br>" , "&lt;br&gt;"), "\n", $text);
	}
	
	function processAtInputTxt( $text ){
		$text = processInputTxt($text);
		$pattern = "/@([\s\S]*?) /i";
		preg_match_all($pattern, $text, $matches);
		$content ='';
		if (!empty($matches[1])) {
			foreach ($matches[1] as $key => $value) {
		 		$result = userModel::getInstance()->getProductLogo($value);
			 	if ($result[0]['name'] == $value) {
			 		$content = str_replace($value, "<a popover='popover' data-placement='top' data-trigger='mouseover' data-html='true' data-preload='".DB36KR_NET."product/card/".urldecode("{$value}")."'>".$result[0]['name']."</a>", $text);
			 	}
			 	return $content;
			}

			
		}
	}
	
	function unsetArrayField( $array , $field ){
// 		var_dump($array);die();
		if(!empty($array)){
			foreach ($array as &$a){
				foreach ($field as $f){
					if(isset($a[$f]) ){
						unset($a[$f]);
					}
				}
			}
		}else{
			$array = array();
		}
		return $array;
	}
	
	function unsetSignArrayField( $array , $field ){ //一维数组
		if(!empty($array)){
				foreach ($field as $f){
					if(isset($array[$f])){
						unset($array[$f]);
					}
				}
		}else{
			$array = array();
		}
		return $array;
	}
	function validate_email($email){
		$exp = "^[a-z'0-9]+([._-][a-z'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
		if(eregi($exp,$email)){ //先用正则表达式验证email格式的有效性
			if(myCheckDNSRR(array_pop(explode("@",$email)),"MX")){//再用checkdnsrr验证email的域名部分的有效性
				return true;
			}else{
				return false;
			}
	
		}else{
			return false;
		}
	}
	function myCheckDNSRR($hostName, $recType = ''){
		if(!empty($hostName)) {
			if( $recType == '' ) $recType = "MX";
			exec("nslookup -type=$recType $hostName", $result);
			// check each line to find the one that starts with the host
			// name. If it exists then the function succeeded.
			foreach ($result as $line) {
				if(eregi("^$hostName",$line)) {
					return true;
				}
			}
			// otherwise there was no mail handler for the domain
			return false;
		}
		return false;
	}
	
	function strexists($haystack, $needle) {
		return !(strpos($haystack, $needle) === FALSE);
	}
	
function exchageCheckCode( $str ){
	return str_replace('o', '0', strtoupper($str));
}
