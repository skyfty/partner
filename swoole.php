<?php
$http = new swoole_http_server("0.0.0.0", 9501);

function client_request($request, $response) {
    if ($request->get['type']) {
        $execmd = "php '".$request->get['type'].".php' "."'".$request->get['pf']."'";
        $retstring = exec($execmd);
    }
    $retinfo = json_decode($retstring, true);
    $response->header("Cache-Control", "no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
    $response->header("Pragma", "no-cache");

    if ($retinfo) {
        if ($retinfo['Location']) {
            $response->status(302);
            $response->header("Content-Type", "text/html");
            $response->header("Location", $retinfo['Location']);
            $response->end();
        } elseif ($retinfo['Sendfile']) {
            $response->sendfile($retinfo['Sendfile']);
        }
    } else {
        $response->header("Content-Type", "text/html; charset=utf-8");
        $response->end($retstring);
    }
}


$http->on('request', function ($request, $response) {
    if ($request->get['timer'] && $request->get['timer'] >= 1000 && $request->get['type']) {
        swoole_timer_tick($request->get['timer'], function($timer_id, $request){
            $response = $request[1];$request = $request[0];
            if ($request->get['type']) {
                $retstring = exec("php '".$request->get['type'].".php' "."'".$request->get['pf']."'");
                if ($retstring == "continue") {
                    return;
                }
                $response->header("Content-Type", "text/html; charset=utf-8");
                $response->end($retstring);
            }
            swoole_timer_clear($timer_id);
        }, array($request, $response));
    } else {
        client_request($response, $request);
    }
});

$http->start();