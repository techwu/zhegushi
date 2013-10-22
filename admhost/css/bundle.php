<?php
// This php is used to generate a single CSS that bundles all CSS files 
// used in "myfeed.php" so as to reduce web requests.

// Input parameters
// $css: Array of all CSS files to include in this bundle
// $css_compressed: Name of compressed CSS file 


/**
 * CSS compressor
 * Copyright: 2007 New Digital Group, Inc.
 * Author: Monte Ohrt (monte [at] ohrt [dot] com)
 * License: LGPL
 * Usage: $output = compress_css($string);
 */
function compress_css($buffer) {
  // remove comments
  $pattern = '!/\*[^*]*\*+([^/][^*]*\*+)*/!';
  $buffer = preg_replace($pattern, '', $buffer);
  // remove new lines, tabs, spaces
  $buffer = str_replace(array("\r\n","\r","\n","\t",' {','} ',';}'),array('','','','','{','}','}'),$buffer);
  // drop more unecessary spaces
  $buffer = preg_replace(array('!\s+!','!(\w+:)\s*([\w\s,#]+;?)!'),array(' ','$1$2'),$buffer);
  return $buffer;
}

// Bundle all CSS files
$all_contents = "";
for ($i = 0, $n = count($css); $i < $n; $i++) {
	$filename = $css[$i];
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	$all_contents .= $contents; // Append contents
	$all_contents .= "\n"; // Separate CSS files with new lines
}

//compress the css file
$all_contents = compress_css($all_contents);

// Whether to compress the CSS bundle (mostly for online service).
// Otherwise generate the http response (mostly for offline development).
// 
// The default is the former. This option is specified by running the following shell build script: 
// 			/usr/tudui/php/bin/php -f myfeed.css.php true
// here "true" is specified for $argv[0], i.e. $compress.
$compress =  true;
if ($compress) {
	$filename = "tmp_" . $css_compressed;
	$handle = fopen($filename, "w");
	$contents = fwrite($handle, $all_contents);
	fclose($handle);
	$css_return = '../css/' . $css_compressed;
	if(file_exists('yuicompressor.jar')){
		system("java -jar yuicompressor.jar --type css --charset utf-8 -o $css_compressed $filename; rm $filename;");
		system("mv $css_compressed $css_return ");
	}else{
		system("mv $filename  $css_return ");
	}
}else{
	$filename = "tmp_" . $css_compressed;
	$handle = fopen($filename, "w");
	$contents = fwrite($handle, $all_contents);
	fclose($handle);
	$css_return = '../css/' . $css_compressed;
	system("mv $filename  $css_return ");
}
echo $all_contents;
?>
