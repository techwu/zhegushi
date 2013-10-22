<?php
class zx_log {

	private $logfile;
	private $DEBUG_LOGMOD=0;
	private $SYS_LOGMOD=0;
	private $LOG_MOD;
	private $LOG_PATH = null;
	private $REAL_PATH ;
	private $REAL_FILENAME;
	
	public function __construct($filename, $logMod = 'DEBUG' ){
		try{
			$this->LOG_PATH = LOG_FILE_BASE_PATH;
			$this->logfile = $filename;
			$this->LOG_MOD = $logMod;

			$path_parts = pathinfo ( $filename );
			//$path_parts['dirname']
			if ( $logMod == 'DEBUG' ) {
				$this->REAL_PATH = $this->LOG_PATH.'/DEBUG/'.$path_parts["dirname"];
			} else {
				$this->REAL_PATH = $this->LOG_PATH.$path_parts["dirname"];
			}
			if ( !is_dir( $this->REAL_PATH ) ) {
				system("mkdir -p ".$this->REAL_PATH.";chmod -R 777 ".$this->REAL_PATH);
			}
			//$this->REAL_FILENAME = $path_parts["basename"].".".date("YmdH");	
			$this->REAL_FILENAME = $path_parts["basename"].".".date("YmdH");		
			//echo self::$REAL_FILENAME;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function w_log($str) {
		if ( $str == null || $str == '' ) {
			return null;
		}
		if ( $this->DEBUG_LOGMOD == 0 && $this->LOG_MOD == 'DEBUG' ) {
			return null;
		}
		
		$currentTime = date("Y-m-d H:i:s",time());
		//if ( $this->LOG_MOD == 'SYS' ) {
		if ( file_exists ($this->REAL_PATH."/".$this->REAL_FILENAME ) ) {	
			chmod ( $this->REAL_PATH."/".$this->REAL_FILENAME, 0777 );
		} 
		$filehandle=fopen($this->REAL_PATH."/".$this->REAL_FILENAME,"a");
		//print_r($this->REAL_PATH);echo "----";echo $this->REAL_FILENAME;
		//die( $this->REAL_PATH ."/".$this->REAL_FILENAME);
		//} else {
		//	$filehandle=fopen(self::$REAL_PATH."/".self::$REAL_FILENAME,"a");
			
		//}
		fwrite($filehandle,"[$currentTime]\t".$str."\n");
		fclose($filehandle);
	}
}
