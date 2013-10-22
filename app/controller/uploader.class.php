<?php
class uploader extends CAction {
	function __construct(){
		parent::__construct('welcome');
	}

	function do_index(){
		importer("corelib.qiniuHelper");
		$qiniu_helper = new qiniuHelper('img' , 'img/logo/icon.jpg' );
		var_dump( $qiniu_helper->uploadFileTmp("image/png" , 'img/logo/icon.jpg') );
	} 
	
	/**
	 * #用于用户提交产品logo（无pid）#
	 * @uses 用户提交产品时以及用户修改产品时，上传logo
	 * @return
	 */
	public function do_ajax_uploadPicture(){
// 		$this->throwUserUnLogon();
		$user_id = isset($_POST['uid'])?$_POST['uid']:'';
		$attach = isset( $_POST['type'] ) ? $_POST['type'] : '';
		
		if( $user_id != self::$_userid || empty($attach)){
			echo json_encode(array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error_text'=>''));
			return false ;
		}
		if(isset ( $GLOBALS ["HTTP_RAW_POST_DATA"] )){
			$request = $GLOBALS ["HTTP_RAW_POST_DATA"];
		} else{
			$request = file_get_contents ( "php://input" );
		}
		switch ($attach) {
			case 'logo':
			case 'picture':
			default:
				$picture = _uploadLogo($request , $attach);
				if( $picture === false ){
					echo json_encode(array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error'=>'图片文件过大或者图片文件上传出错！', 'error_text'=>'图片文件过大或者图片文件上传出错！'));
					return false ;
				}
				if(empty( $picture )){
					$picture = DEFAULT_AVATAR_SOURCE;
				}
				break;
			case 'avatar':
				$picture = _uploadLogo($request , $attach);
				if( $picture === false ){
					echo json_encode(array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error_text'=>'图片文件过大或者图片文件上传出错！' , 'code'=>'too big'));
					return false ;
				}
				if(empty( $picture )){
					$picture = DEFAULT_AVATAR_SOURCE;
				}
				userModel::getInstance()->resetExitUserInfoByWhereArr( array('avatar'=>commFun::convertAvatarUrl($picture) ) , array('user_id'=>$user_id));
				//更新用户信息
				userCache::getInstance()->_updateUserSessionByUid( $user_id , true);
				break;
			case 'card' :
			case 'album':
			case 'poster' :
				$picture = _uploadLogo($request , $attach);
				if( $picture === false ){
					echo json_encode(array('code'=>HTTP_ERROR_STATUS_BADREQUEST , 'error'=>'图片文件过大或者图片文件上传出错！', 'error_text'=>'图片文件过大或者图片文件上传出错！'));
					return false ;
				}
				if(empty( $picture )){
					$picture = DEFAULT_AVATAR_SOURCE;
				}
				break;
		}
		$back_width = 300;
		
		echo json_encode(array('code'=>HTTP_ERROR_STATUS_OK , 'picture'=>IMAGE_PATH . $picture . '?imageView/2/w/'.$back_width , 'picturec'=>$picture, 'error'=>'' )) ;
		return true;
	}
	
	function __destruct(){}
}
