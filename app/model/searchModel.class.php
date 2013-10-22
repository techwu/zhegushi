<?php 
/**
 * searchModel 用户数据类
 * @author wumin
 *
 */
class searchModel extends CModel{

	/**
	 * @return searchModel
	 */
	static function getInstance(){
		return parent::getInstance(get_class());
	}
	
	static $sphClient;

	function __construct(){
		parent::__construct();
	}
	
	
	private function _getSphinxClient() {
		if( empty( self::$sphClient ) ) {
			importer('corelib.sphinxapi');
			self::$sphClient = new SphinxClient();
			self::$sphClient->SetServer('127.0.0.1', SPHINX_PORT);//SPHINX_SERVER
			self::$sphClient->SetArrayResult(true);
		}
		self::$sphClient->ResetFilters();
		self::$sphClient->ResetGroupBy();
		self::$sphClient->SetSortMode( SPH_SORT_RELEVANCE );
		self::$sphClient->SetMatchMode( SPH_MATCH_ANY );
		return self::$sphClient;
	}
	
	/**
	 * 搜索用户
	 * @param unknown_type $searchKey
	 * @param unknown_type $page
	 * @param unknown_type $pageSize
	 */
	function searchVc($searchKey, $start=0, $pageSize= PAGE_SIZE) {
		$sc = $this->_getSphinxClient();
		$sc->SetMatchMode( SPH_MATCH_PHRASE );
		$sc->SetLimits($start, $pageSize, SPHINX_MAX);
		$sc->SetFieldWeights ( array ( "username"=>1000, "investphil"=>200 , 'intro'=>100 ) );
		return $sc->Query($searchKey, 'vc');
	}

	function searchProduct($searchKey, $start=0, $pageSize= BIG_PAGE_SIZE ) {
		$sc = $this->_getSphinxClient();
		$sc->SetFieldWeights ( array ( "product_name"=>1000 , 'identify'=>1000 , 'S_CITYNAME'=>1 , 'S_PROVNAME'=>1 , 'url'=>500 , 
									 	"keywords"=>100 , 'categary'=>30 , 'needtail'=>200 , 'abstract'=>150 ,
										"album"=>50 , 'intro'=>10 , 'pc_name'=>30 , 'infname'=>300 , 'infemail'=>300
							 ) );
		$sc->SetLimits($start, $pageSize , SPHINX_MAX);
		return $sc->Query($searchKey, 'product');
	}
	
	function searchServices($searchKey, $start=0, $pageSize= BIG_PAGE_SIZE ) {
		$sc = $this->_getSphinxClient();
		$sc->SetFieldWeights ( array ( "sname"=>1000 , 'sidentify'=>1000 , 'swebsite'=>500 ,
				"snkey"=>100 , 'cname'=>30 , 'sabstract'=>200 , 'sdetail'=>150 
				) );
		$sc->SetLimits($start, $pageSize, SPHINX_MAX);
		return $sc->Query($searchKey, 'product');
	}
	
	function searchOrganization($searchKey, $start=0, $pageSize= PAGE_SIZE) {
		$sc = $this->_getSphinxClient();
		$sc->SetFieldWeights ( array ( "organization"=>1000,  'intro'=>200 ) );
		$sc->SetLimits($start, $pageSize, SPHINX_MAX);
		return $sc->Query($searchKey, 'org');
	}
	
	function searchAlbum($searchKey, $accurate = false , $start=0, $pageSize= PAGE_SIZE) {
		$sc = $this->_getSphinxClient();
		if( $accurate ){
			$sc->SetMatchMode( SPH_MATCH_ALL );
		}
		$sc->SetFieldWeights ( array ( "album"=>1000 , 'intro'=>500 ,  'url'=>200 ) );
		$sc->SetLimits($start, $pageSize, SPHINX_MAX);
		return $sc->Query($searchKey, 'album');
	}
	
	function buildKeywords( $query, $index = 'product', $hits = false ){
		$sc = $this->_getSphinxClient();
		return $sc->BuildKeywords($query, $index, $hits);
	}
	
	function __destruct(){
		
	}
}