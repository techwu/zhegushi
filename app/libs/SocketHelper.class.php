<?php
require_once(BASEPATH.'lib/php_net.php');
require_once(BASEPATH.'lib/exception/SocketException.class.php');
require_once(BASEPATH.'lib/zx_log.class.php');
require_once(BASEPATH.'lib/TimeHelper.class.php');

class SocketHelper{

	//validate feed server parameter
	private static $VALIDATOR_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'11234',
		'tmout_connect_sec'=>1,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>1,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>2048,
		'read_buf_size'=>2048
	);

	//crawler server parmeter
	private static $SAVER_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'1978',
		'tmout_connect_sec'=>1,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>1,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>2048,
		'read_buf_size'=>2048																
	);

	//item server parmeter
	private static $ITEM_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'10000',
		'tmout_connect_sec'=>5,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>5,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>2048,
		'read_buf_size'=>10240																
	);						
	//catalog server
	private static $CATALOG_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'2001',
		'tmout_connect_sec'=>5,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>5,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>2048,
		'read_buf_size'=>10240																
	);	
	//Item search server
	private static $ITEMSEARCH_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'4001',
		'tmout_connect_sec'=>5,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>5,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>2048,
		'read_buf_size'=>10240,
	);	
	//Item search server
	private static $DI_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'9990',
		'tmout_connect_sec'=>5,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>5,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>2048,
		'read_buf_size'=>10240,
	);	

	//SELECTER server parmeter
	private static $SELECTER_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'13000',
		'tmout_connect_sec'=>1,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>1,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>2048,
		'read_buf_size'=>2048																
	);	

	//GET images OR all item text							
	private static $MMFETCHER_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'30000',
		'tmout_connect_sec'=>5,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>5,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>1024,
		'read_buf_size'=>2048,
	);							

	// ZCache Server							
	private static $ZCache_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'33133',
		'tmout_connect_sec'=>5,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>5,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>1024,
		'read_buf_size'=>2048,
	);	

	//user search server
	private static $USERSEARCH_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'2001',
		'tmout_connect_sec'=>5,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>5,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>2048,
		'read_buf_size'=>10240																
	);
	private static $MATCH_SERVER = array(
		'host'=>'192.168.1.5',
		'port'=>'11983',
		'tmout_connect_sec'=>5,
		'tmout_connect_usec'=>0,
		'tmout_send_sec'=>5,
		'tmout_send_usec'=>0,
		'tmout_rev_sec'=>60,
		'tmout_rev_usec'=>0,
		'send_buf_size'=>2048,
		'read_buf_size'=>10240																
	);																		

	private static $DIVIDE_CHAR = "@tudui@";
	private static $MSG_VERSION = "1";
	private static $MSG_VERSION_LEN = 1;
	private static $DATA_NUM_LEN = 8;
	private static $logHandle = null;
	private static $LOGFILE = "/tmp/SocketHelper.log";
	private static $HOLD_POSITIONS = 8;
	private static $MSG_VERSION2 = "2";
	private $server = null;
	private $socket = null;
	private $CONN_TYPE;
	private $CONN_EXPR;

	/* 
	 * Class constructor
	 */
	function __construct() {
		self::$logHandle = new zx_log( self::$LOGFILE );
		//this->$CONN_EXPR = 
	}

	/* 
	 * Class destructor
	 */
	function __destruct() {
		$this->server = null;
		$this->socket = null;
	}
	/*
	 * @parameter 
	 * 		$type: specify which socket you need.
	 * 			   1==means validate socket.
	 * 			   2==means crawler socket.
	 * 			   3==means item socket.
	 * 			   4==means catalog socket
	 * 			   5==item search socket
	 * 			   6==item search socket
	 * 			   7==selecter server
	 *			   8==item all text for wap
	 * */
	function init( $type ) {
		$logHeader = "init().";
		try{
			$this->CONN_TYPE = $type;
			$this->CONN_EXPR = $this->CONN_TYPE == 5 || $this->CONN_TYPE == 3 || $this->CONN_TYPE == 6 || $this->CONN_TYPE == 8 || $this->CONN_TYPE == 9;

			//self::$logHandle->w_log( $logHeader."Entry...\n");	
			if ( !( 1 == $type || 2 == $type || 3 == $type || 4 == $type ||  5 == $type||  6 == $type ||  7 == $type || 8 == $type || 9 == $type || 10 == $type || 11 == $type)) {
				$this->throwSocketException("E101");
			}

			//notice: if the server and socket exist, it is not allow to init them again.
			if ( $this->server && $this->socket ) {
				$this->throwSocketException("E101");
			}
			//self::$logHandle->w_log( $logHeader."type=".$type."\n");
			//set the server.
			if ( 1 == $type ) {
				$this->server = self::$VALIDATOR_SERVER;
			} else if ( 2 == $type ) {
				$this->server = self::$SAVER_SERVER;
			} else if ( 3 == $type ) {
				$this->server = self::$ITEM_SERVER;
			} else if ( 4 == $type ) {
				$this->server = self::$CATALOG_SERVER;
			} else if ( 5 == $type ) {
				$this->server = self::$ITEMSEARCH_SERVER;
			} else if ( 6 == $type ) {
				$this->server = self::$DI_SERVER;
			}else if ( 7 == $type ) {
				$this->server = self::$SELECTER_SERVER;
			}
			elseif ( 8 == $type ){
				$this->server = self::$MMFETCHER_SERVER;
			}elseif( 9 == $type){
				$this->server = self::$ZCache_SERVER;
			}elseif( 10 == $type){

				$this->server = self::$USERSEARCH_SERVER;
			}elseif( 11 == $type){

				$this->server = self::$MATCH_SERVER;
			}

			if ( $this->CONN_EXPR ) {
				$this->socket = fsockopen($this->server['host'] , $this->server['port'] ,  $erstr, $errno, 5 );
			} else {
				// Create a TCP/IP socket.
				$this->socket = php_socket_create();
				if ( false == $this->socket ) {
					$this->throwSocketException("E101");
				}

				//Connect the socket to the server
				$result = php_socket_connect_with_tmout($this->socket, $this->server['host'], 
					$this->server['port'], 
					$this->server['tmout_connect_sec'], 
					$this->server['tmout_connect_usec']);


				//self::$logHandle->w_log( $logHeader."result=".$result."\n");	
				if ( 1 == $result ) {
					//success.
				} else if ( 0 == $result ) {
					//time out.
					$this->throwSocketException("E102");
				} else {
					$this->throwSocketException("E101");
				}
			}

		} catch (Exception $e){
			throw $e;	
		}			
	}

	function sendMsg($message) {
		$logHeader = "sendMsg().";
		try{
			//message length
			$msg_len = strlen($message);
			$str_msg_len = "" . $msg_len;
			for ($i=0; $i<(self::$DATA_NUM_LEN - strlen($msg_len)); $i++){
				$str_msg_len = "0" . $str_msg_len;

			}
			//final message		
			$message = self::$MSG_VERSION . $str_msg_len . $message;
			self::$logHandle->w_log( $logHeader."message=".$message."\n");
			if ( !$this->socket ) {
				$this->throwSocketException("E101");
			}

			$result = php_socket_send_with_tmout($this->socket, $message, $this->server['tmout_send_sec'], $this->server['tmout_send_usec'], $this->server['send_buf_size']);
			self::$logHandle->w_log( $logHeader."result=".$result."\n");
			if ( false == $result ) {
				$this->throwSocketException("E101");
			}
		} catch (Exception $e){
			throw $e;	
		}				
	}

	function receiceMsg() {
		$logHeader = "receiceMsg().";
		self::$logHandle->w_log( $logHeader."Entry...\n");	
		try{
			$out = "";

			if ( !$this->socket ) {
				$this->throwSocketException("E101");
			}

			//get version and data length info.
			$command = php_socket_read_with_tmout($this->socket, 
				(self::$MSG_VERSION_LEN + self::$DATA_NUM_LEN), 
				$this->server['tmout_rev_sec'], 
				$this->server['tmout_rev_usec'], 
				$this->server['read_buf_size']);

			self::$logHandle->w_log( $logHeader."command=".$command."\n");

			if ( false == $command ) {	
				$this->throwSocketException("E101");
			}

			$version = substr($command, 0, self::$MSG_VERSION_LEN);
			if ( $version != 1 ) {
				$this->throwSocketException("E101");
			}

			$data_len = substr($command, self::$MSG_VERSION_LEN, self::$DATA_NUM_LEN);
			$data_len = (int)$data_len;

			$out = php_socket_read_with_tmout($this->socket, $data_len, $this->server['tmout_rev_sec'], $this->server['tmout_rev_usec'], $this->server['read_buf_size']);
			if ( false == $out ) {
				$this->throwSocketException("E101");
			}

			return $out;
		} catch (Exception $e){
			throw $e;
		}				
	}

	/*
	 * Implement send message to a server using new message format
	 */	
	function sendMsg2($message) {
		$logHeader = "sendMsg2().";
		try{
			//get the connection type
			$type = $this->CONN_TYPE;

			//message length
			$msg_len = strlen($message);
			$str_msg_len = "" . $msg_len;
			for ($i=0; $i<(self::$DATA_NUM_LEN - strlen($msg_len)); $i++){
				$str_msg_len = "0" . $str_msg_len;
			}

			$hold_string = "";
			for ( $i=0 ; $i<self::$HOLD_POSITIONS ; $i++ ) {
				$hold_string .= "0";
			}
			//self::$logHandle->w_log($logHeader."hold_string=".$hold_string."\n");
			//final message		
			$message = self::$MSG_VERSION2 . $hold_string . $str_msg_len . $message;
			//self::$logHandle->w_log($logHeader."message=".$message."\n");
			if ( !$this->socket ) {
				$this->throwSocketException("E101");
			}
			if ( $this->CONN_EXPR ) {
				fputs($this->socket , $message );
			} else {
				$result = php_socket_send_with_tmout($this->socket, $message, $this->server['tmout_send_sec'], $this->server['tmout_send_usec'], $this->server['send_buf_size']);
				if ( false == $result ) {
					$this->throwSocketException("E101");
				}	
			}
		} catch (Exception $e){
			throw $e;	
		}				
	}

	/*
	 * Implement receive message from a server using new message format
	 */	
	function receiceMsg2() {
		$logHeader = "receiceMsg2().";
		try{
			if ( !$this->socket ) {
				$this->throwSocketException("E101");
			}
			if ( $this->CONN_EXPR ) {
				$command = 0 ;
				$command = fgets( $this->socket , 18 );
				//self::$logHandle->w_log($logHeader."command=".$command."\n");	
				if ( false == $command ) {	
					$this->throwSocketException("E101");
				}
				$version = substr($command, 0, self::$MSG_VERSION_LEN);
				if ( $version != self::$MSG_VERSION2) {
					$this->throwSocketException("E101");
				}
				$data_len = substr($command, self::$MSG_VERSION_LEN + self::$HOLD_POSITIONS , self::$DATA_NUM_LEN);
				$data_len = (int)$data_len;
				/*
				if ( $this->CONN_TYPE == 6 ) {
					$data_len = $data_len - 661 ;	
				}
				 */

				//self::$logHandle->w_log($logHeader."data_len=".$data_len."\n");	
				$rec_size = 0 ;
				$SOCKET_READ_SIZE = 1024 * 10 ;
				$timeHelper = new TimeHelper();
				$timeHelper->start();
				self::$logHandle->w_log($logHeader."data_len=".$data_len."\n");	
				//die();
				$out = "";
				while ( strlen($out) < $data_len ) {
					$getSize = $data_len - strlen($out);
					if ( $getSize < $SOCKET_READ_SIZE ) {
						self::$logHandle->w_log($logHeader."getSize=".$getSize."\n");	
						$out .= fread($this->socket , $getSize );
						self::$logHandle->w_log($logHeader."-----------------------------\n");	
					} else {
						$out .= fread($this->socket , $SOCKET_READ_SIZE);
					}

				}
				$timeHelper->stop();
				self::$logHandle->w_log($logHeader."runTime=".$timeHelper->spent()."\n");	

				//self::$logHandle->w_log($logHeader."rec_size=".$rec_size."\n");	

			} else {
				$out = "";
				//get version and data length info.
				$command = php_socket_read_with_tmout($this->socket, 
					(self::$MSG_VERSION_LEN + self::$DATA_NUM_LEN+self::$HOLD_POSITIONS), 
					$this->server['tmout_rev_sec'], 
					$this->server['tmout_rev_usec'], 
					$this->server['read_buf_size']);
				self::$logHandle->w_log($logHeader."command=".$command."\n");
				if ( false == $command ) {	
					$this->throwSocketException("E101");
				}

				$version = substr($command, 0, self::$MSG_VERSION_LEN);
				if ( $version != self::$MSG_VERSION2) {
					$this->throwSocketException("E101");
				}

				$data_len = substr($command, self::$MSG_VERSION_LEN + self::$HOLD_POSITIONS , self::$DATA_NUM_LEN);
				$data_len = (int)$data_len;

				$out = php_socket_read_with_tmout($this->socket, 
					$data_len, $this->server['tmout_rev_sec'], 
					$this->server['tmout_rev_usec'], 
					$this->server['read_buf_size']);
				self::$logHandle->w_log($logHeader."out=".$out."\n");	
				if ( false == $out ) {
					$this->throwSocketException("E101");
				}	
			}

			//self::$logHandle->w_log($logHeader."out=".$out."\n");	
			return $out;
		} catch (Exception $e){
			throw $e;
		}				
	}	

	/*
	 * Implement send message to a server using new message format for wap
	 */	
	function sendMsg3($message) 
	{
		//var_dump($message);
		$msg_len = strlen($message);
		$str_msg_len = "" . $msg_len;
		for ($i=0; $i<(self::$DATA_NUM_LEN - strlen($msg_len)); $i++){
			$str_msg_len = "0" . $str_msg_len;
		}

		$hold_string = "";
		for ( $i=0 ; $i<self::$HOLD_POSITIONS ; $i++ ) {
			$hold_string .= "0";
		}
		//self::$logHandle->w_log($logHeader."hold_string=".$hold_string."\n");
		//final message
		$message = self::$MSG_VERSION2 . $hold_string . $str_msg_len . $message;
		//echo "<br/>mesg:{$num}";
		if ( is_resource($this->socket) )
			$result = fputs($this->socket , $message );  //sent bit
		else 
			$result = false;
		//echo "<br/>socket:{$this->socket} res:{$result}";

		return $result;
	}

	/*
	 * Implement receive message from a server using new message format for wap
	 */	
	function receiceMsg3($bottom='on')
	{
		if ( is_resource($this->socket) )
		{
			$command = 0 ;
			$command = fgets( $this->socket , 18 );
			//echo 'receiceMsg3_command:';var_dump($command);exit;
			$version = substr($command, 0, self::$MSG_VERSION_LEN);

			$data_len = substr($command, self::$MSG_VERSION_LEN + self::$HOLD_POSITIONS , self::$DATA_NUM_LEN);
			$data_len = intval($data_len);

			$out = "";
			while ( strlen($out) < $data_len ) 
			{
				$getSize = $data_len - strlen($out);

				if ( $getSize < 4096 ) 
				{
					$out .= fread($this->socket , $getSize );
				} else 
				{
					$out .= fread($this->socket , 4096);
				}
			}
			if ($bottom=='on')
				fclose($this->socket);
			//echo "<br/>socket:{$this->socket} out:{$out}";exit;
		}
		else 
			$out = false;
		return $out;		
	}

	function closeSocket(){
		try{
			if ( $this->CONN_EXPR ) {
				fclose($this->socket);
			}
			$result = php_socket_close($this->socket);
			if ( false == $result ) {
				$this->throwSocketException("E101");
			}
		} catch (Exception $e) {
			throw $e;     
		}
	}

	function getDivideChar() {
		return self::$DIVIDE_CHAR;
	}

	private function throwSocketException($ex_code) {
		throw new SocketException($ex_code);
	}
}
