<?php
/**
 * userlib.class.
 * @author Wumin <wm290291928@gmail.com>
 */ 

class userlib {
	/**
	 * 异步从远程获得用户的头像。
	 * @param unknown_type $userId
	 * @param unknown_type $avatarUrl
	 */
	public static function getOutSiteAvater( $userId , $avatarUrl ) {
		if ( empty($userId) || empty($avatarUrl) ) {
			return false;
		}
		importer("corelib.task");
		$taskHelper = new task();
		$taskHelper->setFile("./getUserAvatarOutSite.php");
		$taskHelper->setParm( array($userId , $avatarUrl) );
		$taskHelper->taskRun();
	}
	
	/**
	 * 获得用于显示的用户的数字
	 */
	public static function getUserOutNumber () {
		$maxUserId = userModel::getInstance()->getMaxUserId();
		return $maxUserId*6;
	}
	
	/**
	 * 生成用户的城市选择框
	 * @param unknown_type $proviceId
	 * @param unknown_type $cityId
	 * @param unknown_type $smartyHandle
	 */
	public static function locationSelection ( $proviceId , $cityId , $smartyHandle ) {
		$provinces = dictionaryModel::getInstance()->getAllProvinces();
		if ( !empty($cityId) ) {
			$cityStr = userlib::getUserSelectCity( $proviceId , $cityId );
		} else {
			$cityStr = "";
		}
		$smartyHandle->assign("provinces", $provinces);
		$smartyHandle->assign("cityStr", $cityStr);
		$smartyHandle->assign("proviceId", $proviceId);
		$smartyHandle->assign("cityId", $cityId);
		$retCode = $smartyHandle->fetch(TMPDIR."common/location.select.tpl.html");
		return $retCode;
	}
	
	/**
	 * 获得用户所选择的城市信息
	 * @param $province_id
	 * @param $selectedCity
	 */
	public static function getUserSelectCity ( $province_id , $selectedCity=0) {
		$cities = dictionaryModel::getInstance()->getCitiesByProviceId($province_id);
		$cities_str = "<select name='cityid[]' id='cityid' class='span2'>";
		
		if(isset($cities) && !empty($cities)) {
			foreach($cities as $key => $value){
				if($selectedCity != 0){
					if ( $selectedCity == $value['N_CITYID'] ) {
						$cities_str .= "<option value='".$value["N_CITYID"]."' selected='selected'>".$value["S_CITYNAME"].'</option>';
					} else {
						$cities_str .= "<option value='".$value["N_CITYID"]."'>".$value["S_CITYNAME"].'</option>';
					}		
				}else{
					if($key==0){
						$cities_str .= "<option value='".$value["N_CITYID"]."' selected='selected'>".$value["S_CITYNAME"].'</option>';
					}else{
						$cities_str .= "<option value='".$value["N_CITYID"]."'>".$value["S_CITYNAME"].'</option>';
					}
				}
			}
		}
		$cities_str .= '</select>';
		return $cities_str;
	}
	
	/**
	 * 判断一个用户的昵称是否合法
	 * @param string $nickname
	 * @return  1 合法  2 名称过长 3 包含非法字符
	 */
	public static function checkStrLegal ( $nickname ) {
	    $temp_len = ( strlen ( $nickname ) + mb_strlen ( $nickname, 'utf-8' ) ) / 2;
	    if ($temp_len < 1 || $temp_len > 20) {
	        return 2;
	    } else {
	        $reg = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_]+$/u'; //匹配中文字符，数字，字母的正则表达式
	        if (preg_match ( $reg, $nickname )) {
	            return 1;
	        } else {
	            return 3;
	        }
	    }
	}
	
}//end 