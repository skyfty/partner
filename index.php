<?php
ini_set('display_errors',false);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('Yourphp',true);
define('APP_LANG',true);
define ('APP_NAME','App');
define ('APP_PATH','./App/');
define ('UPLOAD_PATH','./Uploads/');

require 'Base/ThinkPHP.php';