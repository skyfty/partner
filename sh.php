<?php
define("NO_AUTHORIZE_CHECK", true);
chdir("/www/web/ayihui_cn/public_html");
$_GET['g'] = "Manage";
$_GET['m'] = "Index";
$_GET['a'] = "timer_task";
require 'index.php';