<?php
if ($argc != 3)
    exit(0);

$_GET['g'] = "Manage";
$_GET['m'] = "cultivate";
$_GET['a'] = "pv";

define("WORD_DIR", dirname($_SERVER['SCRIPT_FILENAME']));
define("NO_AUTHORIZE_CHECK", true);
define("N_ACCOUNTID", $argv[1]);
define("N_ROLEID", $argv[2]);
chdir(WORD_DIR);
require 'index.php';