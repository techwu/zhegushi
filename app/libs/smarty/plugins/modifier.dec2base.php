<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
function smarty_modifier_dec2base($iNum, $iBase=64, $iScale=0) { // cope with base 2..62
	$LDEBUG = FALSE;
	$sChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$sResult = ''; // Store the result

	// special case for Base64 encoding
	if ($iBase == 64)
	 $sChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';

	$sNum = is_integer($iNum) ? "$iNum" : (string)$iNum;
	$iBase = intval($iBase); // incase it is a string or some weird decimal

	// Check to see if we are an integer or real number
	if (strpos($sNum, '.') !== FALSE) {
		list ($sNum, $sReal) = explode('.', $sNum, 2);
		$sReal = '0.' . $sReal;
	} else
		$sReal = '0';

	while (bccomp($sNum, 0, $iScale) != 0) { // still data to process
		$sRem = bcmod($sNum, $iBase); // calc the remainder
		$sNum = bcdiv( bcsub($sNum, $sRem, $iScale), $iBase, $iScale );
		$sResult = $sChars[$sRem] . $sResult;
	}
	if ($sReal != '0') {
		$sResult .= '.';
		$fraciScale = $iScale;
		while($fraciScale-- && bccomp($sReal, 0, $iScale) != 0) { // still data to process
			if ($LDEBUG) print "<br> -> $sReal * $iBase = ";
			$sReal = bcmul($sReal, $iBase, $iScale); // multiple the float part with the base
			if ($LDEBUG) print "$sReal	=> ";
			$sFrac = 0;
			if (bccomp($sReal ,1, $iScale) > -1)
				list($sFrac, $dummy) = explode('.', $sReal, 2); // get the yourls_intval
			if ($LDEBUG) print "$sFrac\n";
			$sResult .= $sChars[$sFrac];
			$sReal = bcsub($sReal, $sFrac, $iScale);
		}
	}

	return $sResult;
}

/* vim: set expandtab: */

?>
