<?php
header('Content-type: text/javascript');

// This php is used to generate a single JavaScript that bundles all JavaScript files 
// used in "allpopular.php" so as to reduce web requests.

// Name of all Javascript files to include in this bundle
$js = array(
        "jquery/jquery.js",
		"jquery/jquery.artzoom.js",
		"jquery/jquery.lazyload.js",
        "datepicker.js",
        "bootstrap/typeahead.js",
        "bootstrap/tooltip.js",
        "bootstrap/popover.js",
        "bootstrap/carousel.js",
        "bootstrap/dropdown.js",
        "bootstrap/respond.min.js",
        "bootstrap/collapse.js",
        "ajaxupload.js",
        "grape.root.js",
        "grape.ui.js",
        "self/validate.js",
        "self/position.js",
        "self/modal.js",
        "self/toggle.js",
        "self/fileUpload.js",
        "self/handler.js",
        "self/trigger.js",
        "self/bubble.js",
        "self/placeholder.js" ,
        'grape.js'
);
// Name of compressed JavaScript file
$js_compressed = "base.js";

// Bundle (and compress) the JavaScript files 
include("bundle.php");
?>