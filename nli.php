<?php
if ($argc != 4)
    exit(0);
$_GET['g'] = "Manage";
$_GET['m'] = $argv[2];
$_GET['a'] = "nli";

define("WORD_DIR", dirname($_SERVER['SCRIPT_FILENAME']));
define("NO_AUTHORIZE_CHECK", true);
define("N_EXCEL_IMPORT_FILE", $argv[1]);
define("N_EXCEL_MODEL", $argv[2]);
define("N_ROLEID", $argv[3]);
chdir(WORD_DIR);
require 'index.php';