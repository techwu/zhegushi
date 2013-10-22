<?php
/**
 * 修正昵称，把#后边的部分去掉
 * @param unknown_type $nickname
 * @return Ambigous <>|unknown
 */
function smarty_modifier_fixnickname($nickname)
{
	$nicknameArr = split('#', $nickname);
	if( isset($nicknameArr[0]) ) {
		return $nicknameArr[0];
	} else {
		return $nickname;
	}
}

