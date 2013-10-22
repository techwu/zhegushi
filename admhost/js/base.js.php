<?php
header('Content-type: text/javascript');

// This php is used to generate a single JavaScript that bundles all JavaScript files 
// used in "allpopular.php" so as to reduce web requests.

// Name of all Javascript files to include in this bundle
$js = array(
        "jquery.js",
        "datepicker.js",
        "typeahead.js",
        "tooltip.js",
        "dropdown.js",
        "ajaxupload.js",
        "jquery.jcrop.js",
        "grape.root.js",
        "grape.ui.js",
        "grape.js",
        "analysis.js"
);
// Name of compressed JavaScript file
$js_compressed = "base.js";

// Bundle (and compress) the JavaScript files 
include("bundle.php");
?>