<?php
//db config
$GLOBALS['DBNAME'] = DBNAME;
$GLOBALS['MASTER_DB']['HOST'] = MASTER_DB_HOST;
$GLOBALS['MASTER_DB']['USER'] = MASTER_DB_USER;
$GLOBALS['MATER_DB']['PASSWORD'] = MASTER_DB_PASSWORD;
$GLOBALS['SLAVE_DB'][0]['HOST'] = SLAVE_DB_HOST;
$GLOBALS['SLAVE_DB'][0]['USER'] = SLAVE_DB_USER;
$GLOBALS['SLAVE_DB'][0]['PASSWORD'] = SLAVE_DB_PASSWORD;

/**
 * base tables
 */
define('DB_TABLE_MAIL' , 'mail');

define('DB_TABLE_ADMIN' , 'admin');
define('DB_TABLE_ADMIN_EXPANSION' , 'admin_expansion');

define('DB_TABLE_USER' , 'user');
define('DB_TABLE_USER_CONNECT' , 'user_connect');
define('DB_TABLE_USER_EXPANSION' , 'user_expansion');

define('DB_TABLE_USER_CHAT' , 'user_chat');
define('DB_TABLE_USER_SESSIONS' , 'user_sessions');

define('T_CIG_DICTIONARY_CITY','dictionary_city');
define('T_CIG_DICTIONARY_PROVINCE','dictionary_province');
// define('SEARCH_ENGINER' , 'db_itea_logs.search_enginer');

define('DB_TABLE_STORY' , 'story');
define('DB_TABLE_TAGS' , 'tags');
define('DB_TABLE_STORY_TAGS' , 'story_tags');
define('DB_TABLE_STORY_ACTION' , 'story_action');
define('DB_TABLE_STORY_COMMENDED' , 'story_recommed');

define('DB_TABLE_COMMENT' , 'comment');
