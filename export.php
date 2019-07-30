<?php
/**
 * Created by PhpStorm.
 * User: feiti
 * Date: 2017/3/10
 * Time: 22:53
 */
define("PARAM_FILE", $argv[1]);
define("NO_AUTHORIZE_CHECK", true);
define("SWOOLE_CALL", true);
if ($argc != 2)
    exit(0);
$param_file_content = file_get_contents(PARAM_FILE);
if (!$param_file_content)
    exit(0);
chdir(dirname($_SERVER['SCRIPT_FILENAME']));
$_GET = unserialize($param_file_content);
if ($_GET['SESSION_ROLE_ID']) {
    define("SESSION_ROLE_ID", $_GET['SESSION_ROLE_ID']);
    unset($_GET['SESSION_ROLE_ID']);
}
require 'index.php';