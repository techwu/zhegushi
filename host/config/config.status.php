<?php
define('MAXNUM' , 99999);

define("DELETED" , 0);
define("EXIST" , 1);

/*
 * HTTP STATUS CODE
*/
define('HTTP_ERROR_STATUS_CONTINUE' , 100); //当传输成功，但是需要用户进一步操作时，比如用户报名，但是没有产品，就会抛出100的error_num
define('HTTP_ERROR_STATUS_PROCESSING' , 102);
define('HTTP_ERROR_STATUS_OK' , 200);//ok ajax处理正确。
define('HTTP_ERROR_STATUS_ACCEPTED' , 202);
define('HTTP_ERROR_STATUS_RESET' , 205);
define('HTTP_ERROR_STATUS_BADREQUEST' , 400);//ajax判断请求的参数，如果参数出现问题就会抛出400错误
define('HTTP_ERROR_STATUS_FORBIDEN' , 403);	//ajax判断用户请求是否有权限
define('HTTP_ERROR_STATUS_FILENOTFOUND' , 404);//ajax判断页面是否404
define('HTTP_ERROR_STATUS_INTERNALSERVERERROR' , 500);//ajax出现数据库异常


define('USER_STATUS_DELETE' , 0 );
define('USER_STATUS_REGISTER' , 1 );
define('USER_STATUS_ACTIVE' , 2 );

define('USER_GENDER_FEMALE' , 2);
define('USER_GENDER_MALE' , 1);
define('USER_GENDER_SECRIT' , 0);

//message
define('UNREAD' , 1);
define('READ' , 0);

//mail
define('MAIL_SENDTYPE_DIRECT' , 0 );
define('MAIL_SENDTYPE_FAST' , 1 );
define('MAIL_SENDTYPE_SLOW' , 10 );

//story action
define('STORY_ACTION_CREATE' , 1);
define('STORY_ACTION_UPDATE' , 2);
define('STORY_ACTION_VALIDATE' , 3);
define('STORY_ACTION_DELETE' , 4);
define('STORY_ACTION_UP' , 10 );
define('STORY_ACTION_DOWN' , 11 );
define('STORY_ACTION_COMMENTS' , 12 );