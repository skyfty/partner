<?php
/**
 * Created by PhpStorm.
 * User: feiti
 * Date: 2017/3/10
 * Time: 22:53
 */
define("SWOOLE_CALL", true);
chdir(dirname($_SERVER['SCRIPT_FILENAME']));
$msg = shell_exec("/www/web/ayihui_cn/bk.sh 2>&1");
if ($msg == "ok") {
    echo("lskdfjlskdfjlsdkf");
}
echo json_encode(array("Location"=>"http://b.ayihui.cn/ayihui.cn.tar.gz"));
