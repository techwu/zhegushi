<?php
/**                                                
 * ������pdodb���mysql���������~~                        
 * ������������master-slave���������mysql cluster         
 * ���������������������������������masterDB������������          
 * @author wumin <wumin.itea@gmail.com>           
 */

require_once (CORE_LIB_PATH . "pdoConfig.class.php");
require_once (CORE_LIB_PATH . "sqlMonitor.class.php");

class pdoDB {
	
	private static $readConnection = null;
	private static $writeConnection = null;
	private $lastSth;
	
	function __construct() {
		if (self::$readConnection == null) {
			self::$readConnection = pdoDB::getReadConnection ();
		}
		if (self::$writeConnection == null) {
			self::$writeConnection = pdoDB::getWriteConnection ();
		}
	}
	
	/**
	 * mysql db 
	 * @return PDO                
	 */
	static public function getWriteConnection() {
		try {
			$connection = new PDOConfig ( $GLOBALS ['MASTER_DB'] ['HOST'], $GLOBALS ['DBNAME'], $GLOBALS ['MASTER_DB'] ['USER'], $GLOBALS ['MATER_DB'] ['PASSWORD'] );
		} catch ( PDOException $e ) {
			echo $e->getMessage ();
			//header('HTTP/1.1 500 Internal Server Error');
		//die();                                       
		}
		$status = $connection->exec ( "SET NAMES utf8" );
		return $connection;
	}
	/**                                                        
	 * mysql db                          
	 * @return PDO                                             
	 */
	static public function getReadConnection() {
		$ran = rand ( 0, count ( $GLOBALS ['SLAVE_DB'] ) - 1 );
		
		try {
			$connection = new PDOConfig ( $GLOBALS ['SLAVE_DB'] [$ran] ['HOST'], $GLOBALS ['DBNAME'], $GLOBALS ['SLAVE_DB'] [$ran] ['USER'], $GLOBALS ['SLAVE_DB'] [$ran] ['PASSWORD'] );
		} catch ( PDOException $e ) {
			echo $e->getMessage ();
			//header('HTTP/1.1 500 Internal Server Error');       
		//die();                                              
		}
		
		$status = $connection->exec ( "SET NAMES utf8" );
		return $connection;
	}
	
	/**
	 * readonly mysql db handle
	 * @param String $sqlComm      
	 * @param array $sqlData     
	 */
	function queryPrepareFromSlave($sqlComm, $sqlData) {
		$sth = self::$readConnection->prepare ( $sqlComm, array (PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false ) );
		foreach ( $sqlData as $key => $value ) {
			$sth->bindValue ( ":{$key}", $value );
		}
		$sth->execute();
		return $sth;
	}
	
	/**
	 * @param String $sql                          
	 * @return Array $sqlData                      
	 */
	function queryPrepareFromMaster($sqlComm, $sqlData) {
		try {
			$sth = self::$writeConnection->prepare ( $sqlComm, array (PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false ) );
			//                                                                                                                            
			//������������                                                                                            
			foreach ( $sqlData as $key => $value ) {
				$sth->bindValue ( ":{$key}", $value);
			}
			$sth->execute ();
			//                      print_r( $sth->errorInfo() );die();                                                                   
		} catch ( PDOException $e ) {
			throw new Exception ( $e->getMessage (), $e->getCode () );
		}
		return $sth;
	}
	
	/**
	 * @param String $sqlComm
	 * @param Array $sqlData 
	 * @return unknown_type  
	 */
	function queryWrite($sqlComm, $sqlData=array()) {
		$timeHelper = new TimeHelper ();
		$timeHelper->start ();
		$sth = $this->queryPrepareFromMaster ( $sqlComm, $sqlData );
		$this->catchError ( $sth, $sqlComm, $sqlData );
		$timeHelper->stop ();
		sqlMonitor::getInstance ()->insertMonQueue ( $sqlComm, $timeHelper->spent ( false ) );
		$this->lastSth = $sth;
		return true;
	}
	/**                                                                                                         
	 * @param unknown_type $sth                                                                                 
	 * @return unknown_type                                                                                     
	 */
	function catchError($sth, $sqlComm = "", $sqlData = "") {
		$errorArr = $sth->errorInfo ();
		if (isset ( $errorArr ) && $errorArr [0] != "00000") {
			$logHandle = new zx_log ( "db_errorlog", "normal" );
			$sqlComm = str_replace ( "\n", " ", $sqlComm );
			$sqlComm = str_replace ( "\t", " ", $sqlComm );
			$logHandle->w_log ( "[$sqlComm]\t" . "[" . json_encode ( $sqlData ) . "]" );
		}
	}
	/**                                                                                                         
	 * @param Array $data    the memory to store query result                                                   
	 * @param String $sqlComm SQL query command                                                                 
	 * @param Array $sqlData        bind variable                                                               
	 * @param bool $fromMaster True query from master False query from slave                                    
	 * @param String $hashKey       change the return result by a key                                           
	 */
	function getQueryData(&$data, $sqlComm, $sqlData, $fromMaster = false, $hashKey = "") {
		$sqlComm = trim($sqlComm);
		if ( preg_match("/^insert/i", $sqlComm) || preg_match("/^update/i", $sqlComm) ) {
			echo "can't write data using read function!!!!<Br>";
			exit(0);
		} 
		$timeHelper = new TimeHelper ();
		$timeHelper->start ();
		$sth = ($fromMaster) ? $this->queryPrepareFromMaster ( $sqlComm, $sqlData ) : $this->queryPrepareFromSlave ( $sqlComm, $sqlData );
		$this->catchError ( $sth, $sqlComm, $sqlData );
		$result = array ();
		$retArray = array ();
		while ( $result = $sth->fetch(PDO::FETCH_ASSOC) ) {
			if (isset ( $hashKey ) && ! empty ( $hashKey )) {
				$data [$result [$hashKey]] = $result;
			} else {
				$data [] = $result;
			}
		}
		$sth->closeCursor ();
		$timeHelper->stop ();
		sqlMonitor::getInstance ()->insertMonQueue ( $sqlComm, $timeHelper->spent ( false ) );
	}
	
	/**
	 * @param: $fieldArr , $dataArr , $tableName , $getRowID ID
	 * @return: false/true/$row_id                                                                   
	 */
	function inputField($fieldArr, $dataArr, $tableName, $getRowID = false, $ignore = false) {
		if ($dr = is_array ( $dataArr ) and $fr = is_array ( $fieldArr ) and $ts = (empty ( $tableName ) == false)) {
			$sqlData = array ();
			$fields = '(';
			$values = '(';
			foreach ( $fieldArr as $fieldKey ) {
				$fields .= "`{$fieldKey}`,";
				$sqlData [$fieldKey] = $dataArr [$fieldKey];
				$values .= ":{$fieldKey},";
			}
			$fields = rtrim ( $fields, ',' ) . ')';
			$values = rtrim ( $values, ',' ) . ')';
			if ($ignore == true) {
				$sql = "INSERT IGNORE INTO {$tableName} {$fields} VALUES {$values};";
			} else {
				$sql = "INSERT INTO {$tableName} {$fields} VALUES {$values};";
			}
			$result = $this->queryWrite ( $sql, $sqlData );
			if ($result == true and $getRowID == true) {
				$operaRes = $this->insert_id ();
			} else {
				$operaRes = $result;
			}
		} else {
			$operaRes = false;
		}
		return $operaRes;
	}
	
	function updateField( $whereArr , $updateArr , $tableName ){
		$result = 0;
		$sqlStr = "";
		$whereStr = "";
		if(!is_array($whereArr)){
			$whereArr = array($whereArr);
		}
		if(!is_array($updateArr)){
			return $result;
		}
		if(count($whereArr) > 0){
			foreach ($whereArr as $k=>$v){
				$whereStr = $whereStr . " $k =:$k and";
				$sqlData["$k"] = $v;
			}
			$whereStr = rtrim ( $whereStr, 'and' );
			if(count($updateArr)> 0){
				foreach ( $updateArr as $key=>$value ){
					$sqlStr = $sqlStr . " $key = :$key ,";
					$sqlData["$key"] = $value;
				}
				$sqlStr = rtrim ( $sqlStr, ',' );
				$sqlComm = "UPDATE " . $tableName . " SET $sqlStr WHERE $whereStr" ;

				$result = $this->queryWrite ( $sqlComm, $sqlData );
//				var_dump($sqlComm, $sqlData);die();
			}
		}
		return $result;
	}
	
function checkExist($whereArr , $tableName){
		$whereStr = "";
		if(!is_array($whereArr)){
			$whereArr = array($whereArr);
		}
		if(count($whereArr) > 0){
			foreach ($whereArr as $k=>$v){
				$whereStr = $whereStr."$k = :$k and ";
				$sqlData["$k"] = $v ;
		    }
			$whereStr .= ' 1 ';
			$sqlComm = "select * from $tableName where $whereStr ";
			$result = array();
// 			echo json_encode($sqlComm). json_encode($sqlData);die();
			$this->getQueryData($result, $sqlComm, $sqlData);
		}
		if (isset($result[0])){
			return 	$result ;
		}else{
			return false;
		}
	}
	
	function affected_rows($sth) {
		return $sth->rowCount ();
	}
	
	function insert_id() {
		//return $dbh->lastInsertId();
		return self::$writeConnection->lastInsertId ();
	}
	
	/**
	 * ���������������SQL���������������������
	 */
	function affect_rows() {
		return $this->lastSth->rowCount ();
	}
	
	function beginTransaction() {
		self::$writeConnection->beginTransaction ();
	}
	
	function commit() {
		self::$writeConnection->commit ();
	}
	
	function rollBack() {
		self::$writeConnection->rollback ();
	}

}
