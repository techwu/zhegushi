#!/bin/sh
#define the configs of shell
php_path='php'
#crontab_path='/Users/wumin/Zend/workspaces/DefaultWorkspace/branch_danilo/mars/app/tasks'
crontab_path='/var/www/krnet/mars/app/tasks'
#crontab_path='/Users/wumin/Zend/workspaces/DefaultWorkspace/krnet/mars/app/tasks'
dm=`date +%Y%m%d%H%M`
echo $dm
log_path='/tmp/log/'
log_result_host='127.0.0.1'
rsync_user='daemon'
