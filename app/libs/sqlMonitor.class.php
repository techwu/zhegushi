<?php
/**
 * commFun class.
 * 用于定义一些比较常用但是又很零碎的功能函数
 * @author wangxi <wangxi0618@gmail.com>
 */ 

class sqlMonitor  {
	
	protected static $instances=NULL;
	private $sqlRunArr;
	private $indentifyCode;
	
	/**
	 * @return sqlMonitor
	 */
	public static function getInstance(){
		//print_r( get_class() );die();
		if ( self::$instances == NULL ) {
			self::$instances = new sqlMonitor();
		} 
		return self::$instances;
	}
	
	public function __construct() {
		$this->indentifyCode = commFun::getUniqueId();
		$this->sqlRunArr = array();
	}
	
	public function insertMonQueue ( $sqlCommString , $runTime ) {
		$tmpArr = array();
		$tmpArr['sql'] = $sqlCommString;
		$tmpArr['time'] = $runTime;	
		array_push ( $this->sqlRunArr, $tmpArr );
	}
	
	public function readMonQueue ( ) {
		return $this->sqlRunArr;
	}
	
	public function getAverageRunTime ( ) {
		if ( count( $this->sqlRunArr ) == 0 ) {
			return 0;
		}
		$totalRunTime = 0;
		foreach ( $this->sqlRunArr as $s ) {
			$totalRunTime += intval( $s['time'] );
		}
		$averageRunTime = $totalRunTime / count( $this->sqlRunArr );
		return number_format( $averageRunTime , 3);
	}
	
	public function getSqlRunNumber ( ) {
		return count( $this->sqlRunArr );
	}
}

