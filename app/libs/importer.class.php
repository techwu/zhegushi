<?php
class importer {
	/**
	 * 保存用户import进来的串，通过split(".")分解的数组
	 */
	private $importStr;
	private $importArr;
	private $importBasePath;
	private $importFilePath;
	private $errorMesg;
	
	function __construct ( $importStr ) {
		$this->importStr = $importStr;
		$this->importArr = explode( ".", $importStr );
		$this->errorMesg = "";
	}
	
	function setErrorMsg ( $msgStr ) {
		$this->errorMesg = __FILE__."\t".__LINE__."\t".$this->importStr."\t".$msgStr;
	}
	
	function getErrorMsg ( ) {
		return $this->errorMesg;
	}
	
	function getBasePath( ) {
		if ( !is_array($this->importArr) ) {
			$this->setErrorMsg( "can not find out base path" );
			return false;
		}
		$baseStr = $this->importArr[0];
		switch( $baseStr ) {
			case 'corelib':
				$this->importBasePath = CORE_LIB_PATH;
				break;
			case 'controller':
				$this->importBasePath = APP_PATH.'/controller/';
				break;
			case 'cache':
				$this->importBasePath = APP_PATH.'/cache/';
				break;
			case 'model':
				$this->importBasePath = APP_PATH."/model/";
				break;				
			default:
				$this->importBasePath = "";
		}
		if ( empty($this->importBasePath) ) {
			$this->setErrorMsg( "can not identified base path" );
			return false;
		}
		return true;
	}
	function getFilePath() {
		if ( !is_array($this->importArr) ) {
			$this->setErrorMsg( "can not find out base path" );
			return false;
		}
		$pathStr = "";
		for ( $i=1, $count=count($this->importArr); $i<$count; $i++ ) {
			$pathStr .= $this->importArr[$i]."/";
		}
		$pathStr = substr( $pathStr, 0, -1 );
		$pathStr .= ".class.php";
		$this->importFilePath = $pathStr;
		return true;
	}
	
	function importFile() {
		$checkStatus = $this->getBasePath();
		if ( $checkStatus === false ) {
			return false;
		}
		unset( $checkStatus );
		$checkStatus = $this->getFilePath();
		if ( $checkStatus === false ) {
			return false;
		}		
		$fullFilePath = $this->importBasePath."/".$this->importFilePath;
//		die($fullFilePath);
		require_once ( $fullFilePath );
		return true;
	}
	
}
