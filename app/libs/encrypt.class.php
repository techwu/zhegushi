<?php
/*
 * encry 主要用于对明文的加密
 */
define('CHECHSUM_STR' , '');
class encrypt{
	
	public static $alg_version = '1';
	public static $key = "FGHIJklm-nitamazhenwuliaolaipojie";
	
	public static function linencrypt($pass) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
		//get vector size on ECB mode 
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND );
		//Creating the vector
		$cryptedpass = mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, self::$key, $pass, MCRYPT_MODE_ECB, $iv );
		//Encrypting using MCRYPT_RIJNDAEL_256 algorithm 
		$cryptedpass = self::urlsafe_b64encode( $cryptedpass );
		$cryptedpass = self::$alg_version . self::checksum ( $cryptedpass ).$cryptedpass;
		
		return $cryptedpass;
	}
	
	//decrpt
	public static function lindecrypt($enpass) {
		if (substr ( $enpass, 0, 1 ) == self::$alg_version) {
			if (substr ( $enpass, 1, 2 ) == self::checksum ( substr ( $enpass, 3 ) )) {
				$enpass = self::urlsafe_b64decode( substr ( $enpass, 3) );
				$iv_size = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
				$iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
				$decryptedpass = mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, self::$key, $enpass, MCRYPT_MODE_ECB, $iv );
				//Decrypting...
				return rtrim ( $decryptedpass, "\0\4" );
			} else{
				return '';
// 				echo "checksum is not right!";
			}
		} else{
			return '';
		}
	}
	
	public static function urlsafe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}
	public static function URLSafeBase64Encode($originalStr){
		$find = array("+","/");
		$replace = array("-", "_");
		return str_replace($find, $replace, base64_encode($originalStr));
	}
	
	public static function urlsafe_b64decode($string) {
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}
	
	/**
	 * Generate checksum using ASCII sum fo input string
	 * @param string $str
	 * @return string with two length
	 */
	public static function checksum($str) {
		$sum = 0;
		$str_len = strlen ( $str );
		if($str_len >0 ){
        	for($i = 0; $i < $str_len; ++ $i) {
				$sum += ord ( substr ( $str, $i, $i + 1 ) );
			}
			$checksum = chr ( ($sum % 25) + 97 ) . chr ( ($sum % $str_len) % 25 + 97 );
			return $checksum;
		}else{
			return false;
		}
	}
	
	public static function  Sha1MD5($str){
		$arr_number = array('0'=>1 ,'1'=>2 ,'2'=>3 ,'3'=>4 , '4'=>5 ,'5'=>6 ,'6'=>7 ,'7'=>8 ,'8'=>9 , '9'=>0);
		$arr_char = array('a'=>'b','b'=>'c' ,'c'=>'d' ,'d'=>'e' , 'e'=>'f' ,'f'=>'g' ,'g'=>'h' ,'h'=>'i' ,'i'=>'j' , 'j'=>'k' ,
						  'k'=>'l' ,'l'=>'m' ,'m'=>'n' ,'n'=>'o' , 'o'=>'p' ,'p'=>'q' ,'q'=>'r' ,'r'=>'s' ,'s'=>'t' , 't'=>'u' ,
		                  'u'=>'v' ,'v'=>'w' ,'w'=>'x' ,'x'=>'y' , 'y'=>'z' ,'z'=>'a' ) ;
		$o_pwd = md5($str);
		//do you like
		$pwd = $o_pwd ;
		$return = sha1($pwd);
		if($return != false){
			return $return;
		}else{
			return $pwd;
		}
	}
	public static function Sha1Str( $str ){
		$arr_number = array('0'=>1 ,'1'=>2 ,'2'=>3 ,'3'=>4 , '4'=>5 ,'5'=>6 ,'6'=>7 ,'7'=>8 ,'8'=>9 , '9'=>0);
		$arr_char = array('a'=>'b','b'=>'c' ,'c'=>'d' ,'d'=>'e' , 'e'=>'f' ,'f'=>'g' ,'g'=>'h' ,'h'=>'i' ,'i'=>'j' , 'j'=>'k' ,
						  'k'=>'l' ,'l'=>'m' ,'m'=>'n' ,'n'=>'o' , 'o'=>'p' ,'p'=>'q' ,'q'=>'r' ,'r'=>'s' ,'s'=>'t' , 't'=>'u' ,
		                  'u'=>'v' ,'v'=>'w' ,'w'=>'x' ,'x'=>'y' , 'y'=>'z' ,'z'=>'a' ) ;
		$o_pwd = $str;
		//do you like
		$pwd = $o_pwd ;
		$return = sha1($pwd);
		if($return != false){
			return $return;
		}else{
			return $pwd;
		}
	}
	
	public static function checkMoreSumFunc( $plain , $ciper ){
		return md5(encrypt::Sha1MD5(encrypt::checksum( CHECHSUM_STR . $plain . $ciper)));
	}
}