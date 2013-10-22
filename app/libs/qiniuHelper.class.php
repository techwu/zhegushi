<?php
/**
 控件：<input type="file" name="attach[]">
 * @author		Wumin(mail:wm290291928@gmail.com)
 * @since		Version 2.0
 * 用于对图片的处理，上传至七牛
 */

require_once CORE_LIB_PATH . 'qbox/oauth.php';
require_once CORE_LIB_PATH . 'qbox/rs.php';
require_once CORE_LIB_PATH . 'qbox/fileop.php';
require_once CORE_LIB_PATH . 'qbox/authtoken.php';
require_once CORE_LIB_PATH . 'qbox/client/rs.php';
Class qiniuHelper{
	var $time;
	
	var $attach ;
	var $fileid ;
	var $uptoken ;
	var $mime ;
	var $filetypedata = array(); //note 允许的文件类型
	var $filetypeids = array(); //note 允许的文件类型
	var $filetypes = array(); //note 允许的文件类型
	
	var $bucket = '';
	var $client = null;
	var $rs = null;
	function __construct( $attach = 'logo' , $file_id = '' , $mime = 'image/png' , $bucket = QBOX_BUCKET , $time = 0 ){
		$this->time = $time ? $time : time();
		$this->attach = $attach;
		$this->fileid = empty($file_id) ? $this->getFileUniqId() : $file_id ;
		$this->filetypedata =array(
				'av' => array('av', 'wmv', 'wav' , 'mov' , 'avi' , 'mpeg' ),
				'real' => array('rm', 'rmvb'),
				'binary' => array('dat'),
				'flash' => array('swf'),
				'html' => array('html', 'htm'),
				'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
				'office' => array('doc', 'xls', 'ppt' , 'docx'),
				'pdf' => array('pdf'),
				'rar' => array('rar'),
				'text' => array('txt'),
				'bt' => array('bt'),
				'zip' => array('tar', 'rar', 'zip', 'gz'));
		$this->uptoken = $this->makeAuthToken(array('expiresIn' => 3600));
		$this->mime = $mime ;
		$this->client = QBox\OAuth2\NewClient();
		
		/**
		 * 初始化 Qbox Reource Service Transport
		*/
		$this->bucket = $bucket;
		$this->rs = QBox\RS\NewService($this->client, $this->bucket);
	}	
	
	function makeAuthToken($params = array('expiresIn' => 3600)){
		return QBox\MakeAuthToken($params);
	}
	
	function getFileUniqId(){
		$levelDate = date('Ymd');
		return $this->attach . '/' . $levelDate . "/" .uniqid();
	}
	
	function uploadFile(){
		$file = $_FILES[$this->attach]['tmp_name'][0];
		if(empty($file)){
			return false;
		}
		$filename = $_FILES[$this->attach]['name'][0];
		$ext = getFileExt($filename);
		$image_info = getimagesize($file);
		if(!isset($image_info[0]) || empty($image_info[0]) ){
			return false;
		}
		setcookie('upload_image_width' , $image_info[0] , time() + 300 , DEFAULT_COOKIEPATH, DEFAULT_COOKIEDOMAIN);
// 		var_dump($image_info[0]);die();
// 		list($result, $code, $error) = $this->rs->PutFile($this->fileid, $this->mime, $file);
		if(!in_array($ext , $this->filetypedata['image'])){
			return false;
		}
		$this->mime = isset($GLOBALS['mime_types'][$ext]) ? $GLOBALS['mime_types'][$ext] : '' ;
		list($result, $code, $error) = QBox\RS\UploadFile(
				$this->uptoken ,
				QBOX_BUCKET ,
				$this->fileid ,
				$this->mime,
				$file
		);
		if ($code == 200) {
			return $this->fileid ;
		}else{
			return false;
		}
	}
	
	function uploadFileTmp($mime = 'image/jpg', $dir = ''){
		$this->mime = $mime;//isset($GLOBALS['mime_types'][$ext]) ? $GLOBALS['mime_types'][$ext] : '' ;
// 		$this->fileid = '';
		list($result, $code, $error) = QBox\RS\UploadFile(
				$this->uptoken ,
				QBOX_BUCKET ,
				$this->fileid ,
				$this->mime,
				HOST_PATH.$dir
		);
		if ($code == 200) {
			return $this->fileid ;
		}else{
			return false;
		}
	}
	/**
	 *  图像处理接口（可持久化存储缩略图）
	 * @param unknown_type $source_img_url
	 * @param unknown_type $params
	 * 
	 * $params = {
	 *   "thumbnail": <ImageSizeGeometry>,
	 *   "gravity": <GravityType>, =NorthWest, North, NorthEast, West, Center, East, SouthWest, South, SouthEast
	 *   "crop": <ImageSizeAndOffsetGeometry>,
	 *   "quality": <ImageQuality>,
	 *   "rotate": <RotateDegree>,
	 *   "format": <DestinationImageFormat>, =jpg, gif, png, tif, etc.
	 *   "auto_orient": <TrueOrFalse>
	 * }
	 */
	function imageMogrifyAs( $fileid , $source_img_url, $params){
		list($result, $code, $error) = $this->rs->Get(commFun::convertAvatarUrl($source_img_url, WWW36TREE_PATH) , 'download.png');
// 		
		if(!isset($result['url'])){
			return false;
		}
		$img_url = $result['url'];
		list($result, $code, $error) = $this->rs->ImageMogrifyAs($this->fileid . $fileid  , $img_url, $params);
// 		var_dump($img_url , $result, $code, $error);die();
		if ($code == 200 ){
			return $this->fileid . $fileid;
		}else{
			return false;
		}
	}
	
	function getImageInfo($file_id){
		list($result, $code, $error) = $this->rs->Get($file_id , 'download.png');
		if(!isset($result['url'])){
			return false;
		}
		$imgInfo = @file_get_contents(QBox\FileOp\ImageInfoURL($result['url']));
		return json_decode($imgInfo , true);
	}
	
	function deleteImage(){
		$rs = QBox\RS\NewService($this->client, $this->bucket);
		$key = '';
		/**
		 * 删除指定的文件
		 */
		list($code, $error) = $rs->Delete($key);
		echo "===> Delete $key result:\n";
		echo "<img src='http://img.36tr.com/'>";
		if ($code == 200) {
			echo "Delete file $key ok!\n";
		} else {
			$msg = QBox\ErrorMessage($code, $error);
			die("Delete failed: $code - $msg\n");
		}
	}
	function __destruct(){
		
	}
}