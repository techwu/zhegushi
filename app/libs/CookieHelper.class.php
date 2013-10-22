<?php
/**
 * CookieHelper - 处理Cookie的初始化，更新某个cookie值，清空某个cookie值
 * @package libraries
 * @author   KevinChen
 * @copyright www.zhuaxia.com
 * @usage:
 */
class CookieHelper{
	/**
	 *  $system_cookie_name: 默认的cookie名称
	 */
	private $system_cookie_name = "TUDUI_SYSTEM_COOKIE";
	private $identifying_cookie_name = "ZX_IDF";
	/**
	 * 系统cookie中的值,由 param=value;param2=value2 这种格式组成
	 */
	private $system_cookie_value = "";
	private $persistent_cookie = false;
	private $cookie_domain = DEFAULT_COOKIEDOMAIN;
	private $cookie_path = DEFAULT_COOKIEPATH;
	
	function setSystemCookieName($cookie_name){
		$this->system_cookie_name = $cookie_name;
	}
	
	function CookieHelper($system_cookie_value){
		$this->system_cookie_value = $system_cookie_value;
	}
	
    /**
     * 设置是否长期保存cookie  
     * @param void
     * @return void
     */
	function setPersistent(){
		$this->persistent_cookie = true;
	}
	
    /**
     * 按照变量名，变量值写入系统cookie中  
     * @param string $cookie_name 需要存入cookie的变量名
     * @param string $cookie_value 变量值
     * @return boolean 
     */
	function writeCookie($cookie_name, $cookie_value) {
		$is_success = false;

		if ($cookie_name) {
			$this->removeCookie($cookie_name);
			
			$compose_name_value = $cookie_name . "=" . $cookie_value;			
			if ($this->system_cookie_value) {
				$this->system_cookie_value .= $compose_name_value . ";";
			} else {
				$this->system_cookie_value = $compose_name_value;
			}
			
			$is_success = $this->saveCookie();
		}
		
		return $is_success;
	}

    /**
     * 按照变量名，从系统cookie中获得变量值  
     * @param string $cookie_name 需要读取的cookie的变量名
     * @return string  $cookie_value 
     */	
	function getCookieValue($cookie_name){
		$cookie_value = null;
	
		if ($this->system_cookie_value) {
			$system_cookie_value = $this->system_cookie_value;
			$names_values = split(";",$system_cookie_value);
			for ($i = 0; $i < count($names_values); $i++ ) {
				$str_name_value = $names_values[$i];
				$name_value = split("=",$str_name_value);
				if (count($name_value) == 2){
					if ($name_value[0] == $cookie_name){
						$cookie_value = $name_value[1];
						break;
					}
				}
			}
		}		
	
		return 	$cookie_value;
	}
	
    /**
     * 按照变量名，从系统cookie中删除该变量  
     * @param string $cookie_name 需要删除的cookie的变量名
     * @return boolean 
     */	
	function removeCookie($cookie_name) {
		$is_success = null;
		$new_system_cookie_value = "";
	
		if ($this->system_cookie_value) {
			$system_cookie_value = $this->system_cookie_value;
			$names_values = split(";",$system_cookie_value);
			for ($i = 0; $i < count($names_values); $i++ ) {
				$str_name_value = $names_values[$i];
				$name_value = split("=",$str_name_value);
				if (count($name_value) == 2){
					if ($name_value[0] != $cookie_name){
						$new_system_cookie_value .= $str_name_value . ";";
					}
				}
			}
			
			$this->system_cookie_value = $new_system_cookie_value;
			$is_success = $this->saveCookie();
		}		
	
		return 	$is_success;	
	}
	
    /**
     * 清空系统的cookie中所有的值
     * @param string $cookie_name 需要读取的cookie的变量名
     * @return boolean
     * @author: KevinChen 
     */		
	function cleanCookie(){
		$this->system_cookie_value = "";
	}
	
    /**
     * 修改cookie有效时间，让cookie失效  
     * @return boolean
     * @author: KevinChen 
     */			
	function invalidateCookie() {
		$is_success = @setcookie($this->system_cookie_name, "", time()-2000000, '/');  // 清空当前域的cookie
		$is_success = @setcookie($this->system_cookie_name, "", time()-2000000, '/php_controller/'); //清空当前域的ajax cookie，
		$is_success = @setcookie($this->system_cookie_name, "", time()-2000000, $this->cookie_path,$this->cookie_domain); // 清空全域的cookie
		$is_success = @setcookie($this->identifying_cookie_name, "", time()-2000000 , $this->cookie_path,$this->cookie_domain);
		return $is_success;		
	}

    /**
     * 清空用户的session  
     * @return boolean
     * @author: KevinChen 
     */	
	function destroySession() {
		// Initialize the session
		session_start(); 

		// Unset all of the session variables.
		$_SESSION = array();
		
		// Delete the session cookie.
		if (isset($_COOKIE[session_name()])) {
		   setcookie(session_name(), '', time()-2000000, $this->cookie_path,$this->cookie_domain);
		}
		// Destroy the session
		session_destroy();
	}

	
	function saveIdentifyingCode($customer_id,$identifying_code,$token=""){
		$is_success = false;
		$identifying_cookie = $customer_id . "|" . $identifying_code . "|" . $token;
		$is_success = setcookie($this->identifying_cookie_name, $identifying_cookie, time()+60*60*24*365, $this->cookie_path,$this->cookie_domain);
		return $is_success;
	}
	
	function getIdentifyingCode(){
		$identifying_cookie = $_COOKIE[$this->identifying_cookie_name];
		if( empty($identifying_cookie) ) { 
			return false;
		}
		$arr = explode("|",$identifying_cookie);
		$ret['customer_id'] = $arr[0];
		$ret['identifying_code'] = $arr[1];
		$ret['token'] = $arr[2];
		return $ret;
	}
    /**
     * 将系统cookie的值写回到客户端中   
     * @return boolean
     * @author: KevinChen 
     */		
	private function saveCookie() {
		$is_success = false;
		$is_success = @setcookie($this->system_cookie_name, $this->system_cookie_value, time()+60*60*24*365, $this->cookie_path,$this->cookie_domain);
		return $is_success;
	}
	
}
