#!/bin/sh
. /Users/samdanilo/Sites/36tr/branch_danilo/mars/app/tasks/shell_config.sh
cd $crontab_path
$php_path $crontab_path/mailMan.php
$php_path $crontab_path/mailManWork.php
