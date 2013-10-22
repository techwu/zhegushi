<?php
class tagsModel extends CModel{
	/**
	 * @return tagsModel
	 */
	
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	function __construct(){
		parent::__construct(array(
								'id' ,
								'tag' ,
								'sum' ,
								'validate' ,
								'status' ,
								'linked'
				 			) , 
							DB_TABLE_TAGS );
	}
	
	function __destruct(){}
	
	function addTag( $tag , $sum = 1 , $validate = 0 , $status = EXIST , $linked = 0 ){
		$iData = array();
		if (empty($tag)){
			return false;
		}
		$iData['tag'] = $tag ;
		$iData['sum'] = $sum ;
		$iData['status'] = $status ;
		$iData['validate'] = $validate ;
		$iData['linked'] = $linked;
		
		return $this->insertTag($iData);
	}
	
	function insertTag($iData){
		if ( !isset( $iData['tag'] ) || empty($iData['tag']) ){
			return false;
		}
		$check = $this->isExistTag( $iData['tag'] );
		if ( $check === false ){
			return $this->addNewData($iData);
		}else{
			$iData['sum'] =  $check[0]['sum'] + $iData['sum'] ;
			$iData['validate'] =  $check[0]['validate'] ;
			$this->resetTag( $iData , array('id'=>$check[0]['id']) );
			return $check[0]['id'];
		}
	}
	
	function resetTag( $update , $where){
		$result = self::$db->updateField ( $where , $update ,$this->table );
		return $result;
	}
	
	function isExistTag( $tag , $status = EXIST ){
		return self::$db->checkExist(array('tag'=>$tag , 'status'=>$status ), $this->table );
	}
	
	function getTagInfoByTagId( $tag_id ){
		$tag =  self::$db->checkExist(array('id'=>$tag_id), $this->table );
		if (isset($tag[0]) && !empty($tag[0])){
			return $tag[0];
		}else{
			return false;
		}
	}
	
	function getValidateTags( $start = 0 , $limit = PER_PAGE , $status = EXIST ){
		$sql_comm = "select * from " . $this->table .' dtt ' .
					' where dtt.status = :status and dtt.validate = 1 order by dtt.id asc limit '.$start.','.$limit ;
		$sql_data['status'] = $status ;
		$result = array();
		self::$db->getQueryData( $result, $sql_comm, $sql_data );
		if ( isset($result[0]) && !empty($result[0])) {
			return $result;
		} else {
			return false;
		}
	}
}