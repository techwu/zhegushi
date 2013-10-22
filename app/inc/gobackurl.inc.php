<?php
	$classArray = array('user' , 'userBase' , 'searchBase' , 'welcome' , 'home' , 
						'image' , 'js' , 'images' , 'css' , 'index.php' , 'robots.txt' , 
						'favicon.icon' , 'weitong' , 'error' , 'showImage.php' , 'scripts' , 
						'test' , 'image' , 'avatar_image' , 'img' , 'pdf' , 'readpdf' , 'read' ,
						'logon' , 'register' , 'signin' , 'signup' , 'click' , 'null' , 
						'sidebar' , 'vcuser' , 'submit' , 'connected' ,
						'passwdreseted' , 'forgetpasswd' ,'setting' , 'search' ,
						'logon' , 'login', 'signin' , 'signup', 'validate' ,
						"insertCompany" , "insertProduct" , "insertPerson" , 'server');

if (!empty($_SERVER['REQUEST_URI']) ) {
	$requestUri = $_SERVER['REQUEST_URI']; 
	$url = explode("/",$requestUri);
	$inarray = true;
	$requestUri = substr( $requestUri , 1, strlen($requestUri) );
	
	if( is_array( $url ) ){
		foreach( $url as $key => $value){
			if( empty($requestUri)){
				$inarray = false ;
				break ;
			}
			if( in_array ($value , $classArray)){
				$inarray = false ;
				break ;
			}
			if( in_array ($value , $classArray)){
				$inarray = false ;
				break ;
			}
			$pos = strpos( $value ,"ajax");
			if( $pos === false){
				//do nothing;
			}else if( $inarray ){
				$inarray = false ;
				break ;
			}
			
			$pos = strpos( $value ,"submit");
			if( $pos === false){
				//do nothing;
			}else if( $inarray ){
				$inarray = false ;
				break ;
			}
			
			if($inarray){
				$pos_sencha = strpos( $value ,"sencha");
				if( $pos_sencha === false){
					//do nothing;
				}else{
					$inarray = false ;
					break ;
				}
			}
			if(!$inarray){
				break;
			}
		}
		if( $inarray ){
			$requestUri = isset($requestUri)?$requestUri:'';
			setcookie(COOKIE_CALLBACK_URL , $requestUri, time()+300, DEFAULT_COOKIEPATH , DEFAULT_COOKIEDOMAIN );
		}
// 		if( $inarray || empty($requestUri)){
// 			global $_ZSESSION;
// 			if( isset($_ZSESSION['user_id']) && !empty($_ZSESSION['user_id'])){
// 				if(FRAME_PAGE_TYPE == 'normal'){
// 					$cookies = isset($_COOKIE[ITEA_VC_USER_COOKIE])?$_COOKIE[ITEA_VC_USER_COOKIE]:'';
// 					//echo "<script src=\"".VC36TREE_PATH."user/sslogon/".$_COOKIE[DEFAULT_SESSION_NAME] .'/'. $cookies . "\"></script>";
// 				}elseif(FRAME_PAGE_TYPE == 'vc'){
// 					$cookies = isset($_COOKIE[ITEA_VC_USER_COOKIE])?$_COOKIE[ITEA_VC_USER_COOKIE]:'';
// 					//echo "<script src=\"".WWW36TREE_PATH."user/sslogon/".$_COOKIE[DEFAULT_SESSION_NAME] .'/'. $cookies . "\"></script>";
// 				}
// 			}
// 		}
	}
	
	
}

