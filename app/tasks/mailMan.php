<?php
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set("PRC");
define("IN_GOLDEYE",1); //available the entry
require_once("./commons.php");

$mailInfo = mailModel::getInstance()->getUnSentMailQueue(10);
$counter = 1;
if( count($mailInfo) == 0 || empty($mailInfo)){
	echo "no mail to send~";
	exit();
}
foreach ( $mailInfo as $m ) {
	if ( $counter >= 5 ) {
		exit();
	}
	echo "[".date('Y-m-d H:i:s')."]\n";
// 	$m['reply_email'] = empty($m['reply_email']) ? 'service@36kr.net' : $m['reply_email'];
	mailCache::getInstance()->_sendMailDirectBySendCloud( array($m['receiver_email']) , $m['subject'] , $m['content'] , $m['sender_name'] , $m['sender_email']  , $m['reply_email'] );
	mailModel::getInstance()->updateMailStatus($m['id'], 10);
	$counter ++;
}
