<?php
/**
 * CURL wrapper class
 * @author: KevinChen
 */
class curl{
	var $callback = false;
	var $user_agent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; Maxthon 2.0)";

	function setCallback($func_name) {
		$this->callback = $func_name;
	}

	
	function doHeadInfo($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FRESH_CONNECT,1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_NOBODY, 1); // important! set this option is using the "header method"
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,$this->user_agent);
		curl_setopt($ch, CURLOPT_REFERER,$url);
		curl_setopt($ch, CURLOPT_ENCODING,"gzip");
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		
		if(empty($data)){
//			$err = curl_error($ch);
			$err = false;
			curl_close($ch);
			return $err;
		}else{
			$info = curl_getinfo($ch);
			curl_close($ch);
			return $info;
		}
	}

	function doRequest($method, $url, $vars) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FRESH_CONNECT,1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,$this->user_agent);
		curl_setopt($ch, CURLOPT_REFERER , $url );
		curl_setopt($ch, CURLOPT_ENCODING,"gzip");
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
		}
		$data = curl_exec($ch);
		
		
		if ($data) {
			/**
			 *  因为历史的原因，这里hack一下~~
			 * 分析curl返回的结果
			 */
			$result = array();
			$header_size = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
			$result['header'] = $this->pass_header( substr($data, 0, $header_size) );
			$result['body'] = substr( $data , $header_size );
			$result['http_code'] = curl_getinfo($ch , CURLINFO_HTTP_CODE);
			$result['last_url'] = curl_getinfo($ch , CURLINFO_EFFECTIVE_URL);
			$result['last_sent']=curl_getinfo($ch ,CURLINFO_HEADER_OUT );		
			$data = $result;
			
			if ($this->callback)
			{
				$callback = $this->callback;
				$this->callback = false;
				return call_user_func($callback, $data);
			} else {
				curl_close($ch);
				return $data;
			}
		} else {
			curl_close($ch);
			return false;
		}
	}
	public function pass_header($header)
	{
		$result=array();
		$varHader=explode("\r\n", $header);
		if(count($varHader)>0)
		{
			for($i=0;$i<count($varHader);$i++)
			{
				$varresult=explode(":",$varHader[$i]);
				if(is_array($varresult) && isset($varresult[1]))
				$result[$varresult[0]]=$varresult[1];
			}
		}
		return $result;
	}	
	function head($url){
		return $this->doHeadInfo($url);
	}
	
	function get($url) {
		$checkPos = strpos ( $url , "#");
		if ( $checkPos !== false ) {
			$url = substr ( $url , 0 , $checkPos );
		}
//		echo $url;die();
		return $this->doRequest('GET', $url, 'NULL');
	}

	function post($url, $vars) {
		return $this->doRequest('POST', $url, $vars);
	}
}
