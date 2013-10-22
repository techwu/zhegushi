<?php
// This php is used to generate a single JavaScript that bundles all JavaScript files 
// used in a php page so as to reduce web requests.

// Input parameters
// $js: Array of all Javascript files to include in this bundle
// $js_compressed: Name of compressed JavaScript file 

// Bundle all JavaScript files
$all_contents = "";
for ($i = 0, $n = count($js); $i < $n; $i++) {
	$filename = $js[$i];
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);

	$all_contents .= $contents; // Append contents
	$all_contents .= "\n"; // Separate CSS files with new lines
}

// Whether to compress the JavaScript bundle (mostly for online service).
// Otherwise generate the http response (mostly for offline development).
// 
// The default is the former. This option is specified by running the following shell build script: 
// 			/usr/tudui/php/bin/php -f allpopular.js.php true
// here "true" is specified for $argv[0], i.e. $compress.
global $argv;
$compress = true;//$argv[0];
if ($compress) {
	$filename = "tmp_" . $js_compressed;
	$handle = fopen($filename, "w");
	$contents = fwrite($handle, $all_contents);
	fclose($handle);
	$js_return = $js_compressed;
	if(file_exists('yuicompressor.jar')){
		system("java -jar yuicompressor.jar --type js --charset utf-8 -o $js_compressed $filename; rm $filename;");
		system("mv $js_compressed $js_return ");
	}else{
		system("mv $filename $js_return ");
	}	
}else{
	$filename = "tmp_" . $js_compressed;
	$handle = fopen($filename, "w");
	$contents = fwrite($handle, $all_contents);
	fclose($handle);
	$js_return = '../js/' . $js_compressed;
	system("mv $filename $js_return ");
}
echo $all_contents;
?>
