<?php

/**
 * Created by PhpStorm.
 * User: feiti
 * Date: 26/09/2016
 * Time: 16:27
 */
class FileLock {
    private $fp = null;
    public function __construct() {
        $this->fp = fopen('./ayihui.lock', 'r');
        flock($this->fp, LOCK_EX);

    }

    public function __destruct(){
        flock($this->fp, LOCK_UN);
        fclose($this->fp);
    }
}