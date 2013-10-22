<?php
class task {
	
	private $filename;
	private $paramKey;
	
	/**
	 * Constructor
	 * @access	public
	 */
	function __construct(){
		
	}
	
	function setFile( $filename ) {
		$this->filename = $filename; 
	}
	
	function setParm( $paramArray ) {
		$this->paramKey = "";
		foreach ( $paramArray as $p ) {
			$this->paramKey .= " \"{$p}\"";
		}
	}
		
	function taskRun() {
		//echo "cd ".TASK_PATH.";".PHP_BIN_PATH." ".$this->filename." ".$this->paramKey." > /tmp/1.out &";
		exec("cd ".TASK_PATH.";".PHP_BIN_PATH." ".$this->filename." ".$this->paramKey." > /tmp/1.out &");
	}

}

