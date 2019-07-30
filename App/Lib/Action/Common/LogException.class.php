<?php

/**
 * Created by PhpStorm.
 * User: feiti
 * Date: 26/09/2016
 * Time: 16:27
 */
class LogException extends Exception {

    public $title;

    /**
     * 架构函数
     * @access public
     * @param string $message  异常信息
     */
    public function __construct($message,$title, $code = 2) {
        parent::__construct($message,$code);
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

}