<?php
//Sphinx搜索
define("SPHINX_SERVER", "127.0.0.1");
define("SPHINX_PORT", 9312);
define("SPHINX_MAX" , 1000 );
/*for pagefliper*/
define("PAGE_SIZE" , 10);
define('PER_PAGE' , 10); 

$GLOBALS['ttf'] = array(0=>'arial' , 1=>'comicbd' , 2=>'kkb' , 3=>'wesley');									
//CRM category list
$GLOBALS['SPECIAL_ACTION'] = array();
$GLOBALS['ROUTIN_METHOD'] = array();
$GLOBALS['ACTION_NAME'] = array( 
		'aboutus' , 'add' , 'admin' , 'advisor' ,'agents' , 'ajax' , 'api' , 'app' , 'apply' , 'ask' , 'avatar' , 'avatar_image' ,'album', 'alexa' ,
		'build' , 'built' , 'blog' ,
		'cache' , 'category', 'chat' , 'chart' , 'claim' , 'click' , 'cloud' , 'company' , 'comments' , 'connect' , 'connected', 'console' ,  'contact' ,  'contactus' , 'css' , 'crm', 'coordinate' ,
		'data' , 'download', 'dm' , 'do' , 'done' , 'dstudio' , 'discuss', 'download' ,
		'employee' , 'event' , 'events' , 'eventlist' , 'email' ,
		'fan' , 'fans' , 'fav' , 'feedback'  , 'feeds' , 'feedlist' , 'follow' , 'follows' ,  'found' , 'founded' , 'forever' , 'foruser' , 'forgetpasswd', 'friend' , 'friends', 'file' ,  
        'getflash' , 'group' , 'guess' , 'guide' , 
                           'home', 'learning' , 'help' , 'hot' ,
						   'inbox' ,'info' , 'infos' , 'infomation' ,'ignore' , 'import' , 'imp' , 'index' , 'interest' , 'invester' , 'investor' , 'investers' , 'investors' , 'invite' , 'find' , 'img' , 'image' , 'images' , 
						   'js' , 'json' , 
							//k 
							'knowledge' , 'know' ,
                           'link' , 'links' , 'list' , 'lists' , 'location' , 'locations', 'log' , 'logon' , 'login' , 'logs' , 
						   'mail' , 'market' , 'markets' , 'manage' , 'mine' , 'mobile' , 'monit' , 'mserver' , 'me' , 'messages' , 'message' , 'music' ,
                           'new' , 'news' , 'newest' , 'notification' , 'notifications' , 'circle',
							//o
						   'openday' , 'order' ,'organ' , 'organization' ,
						   'passwd' , 'password' , 'passwdreseted' , 'pdf' , 'person' ,'personal' , 'people' , 'picture' , 'photo' , 'product' , 'products' , 'profile' , 
							//q
						   'recomm' , 'recruit' , 'resume' , 'register' , 'read' , 'reset' , 'reads' , 'reference' , 'report' , 'reports' ,
						   'search' , 'sencha' , 'server' , 'service' , 'services' , 'set' , 'setting' , 'settings' , 'skill' , 'skills' , 'show' , 'sidebar' , 'signin' , 'signup' ,'support', 'spam' , 'submit' , 'summary',  'static', 'startup' , 'startups' , 'subscribe' , 'story' ,
                           'temp' , 'template' , 'test' , 'title' , 'twiter' , 'tag' ,  'tool' , 'tools' ,'timeline',
                           'user' , 'UserBase' , 'username' ,  'userStatic' , 'userstatic' , 'uploader' ,
                           'version' , 'vc' , 'vcwall', 'vclist' , 'view' , 'vent', 'validate' , 'visualization' ,
                           'web' , 'welcome' , 'wall' , 'whisper' , 'weibo' , 'weitong' , 'weekly' ,
							//x , y , z
							'image.php' , 'index.php' , 'robot' , 'robots' , 'robots.txt', 'error' , 'avatar_image' , 'script' , 'scripts', 'favicon.ico' , 'favicon' ,'showImage.php' , 'showImage' , 'updateUserPwd.php' ,
							);