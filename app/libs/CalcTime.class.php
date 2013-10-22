<?php 
/**
 * CalcTime.class.php , implement caculating time string to human readable string
 * @author WangXi <wangxi@zhuaxia.com>
 * @since 2007-04-29
 * @package libraries/CalcTime
 */
class CalcTime {
	static $lang = array(
		'year' => '年',
		'month' => '个',
		'day' => '天',
		'hour' => '小时',
		'minute' => '分钟',
		'second' => '秒',
		'tail' => '前'
	
	);
	public function __construct() {

	}

	/**
	 * Implement item time string exchange
	 * @param String $timestr
	 * @param String $curstamp
	 * @return String $str
	 */ 
	public function getPopTimeStr($timestr, $curstamp) {
			// Specified timestamp and associative array
			$tmparr = preg_split('/[\s-:]/', $timestr);
			$timestamp = @mktime($tmparr[3], $tmparr[4], $tmparr[5], $tmparr[1], $tmparr[2], $tmparr[0]); 
			
			// stamp diff	
			$stampdiff = $curstamp - $timestamp;
			
			// maybe error,it's furtrue time
			if($stampdiff < 0){
				$str = "1".self::$lang['second'].self::$lang['tail'];
				return $str;
			};
			
			$day_diff = floor($stampdiff/86400);
			
			$year_diff = floor($day_diff/365);

			$sub_stamp = $stampdiff % 86400;
			$hour_diff = floor($sub_stamp/3600);

			$m_sub_diff = $sub_stamp%3600;
			$minute_diff = floor($m_sub_diff/60);
			
			$second_diff = $m_sub_diff%60;
			
			if($year_diff>0){
				$str = $year_diff.self::$lang['year'].$day_diff.self::$lang['day'].self::$lang['tail'];
			}else if($day_diff>0){
				$str = $day_diff.self::$lang['day'].$hour_diff.self::$lang['hour'].self::$lang['tail'];
			}else if($hour_diff>0){
				$str = $hour_diff.self::$lang['hour'].$minute_diff.self::$lang['minute'].self::$lang['tail'];
			}else if($minute_diff>0){
				$str = $minute_diff.self::$lang['minute'].$second_diff.self::$lang['second'].self::$lang['tail'];
			}else{
				$str = $second_diff.self::$lang['second'].self::$lang['tail'];
			}
		return $str;
	}
}

