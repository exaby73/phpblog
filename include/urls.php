<?php

/*
    URL_INCLUDE -> Holds absolute file path to root of document 
        echo in PHP include and require statements

    URL_HREF -> Holds http link to root of document
        echo in client-side HTML/JS such as href attributes
*/

$local_dev = getenv('LOCAL');

define("URL_INCLUDE", "{$_SERVER['DOCUMENT_ROOT']}");
if ($local_dev == "true") :
    define("URL_HREF", "http://{$_SERVER['HTTP_HOST']}");
else :
    define("URL_HREF", "https://{$_SERVER['HTTP_HOST']}");
endif;
