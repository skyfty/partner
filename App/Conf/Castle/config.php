<?php
return array(
    'APP_AUTOLOAD_PATH'     => '@/Action/Common',// 自动加载机制的自动搜索路径,注意搜索顺序
    'LAYOUT_ON'		=> 0,
	'URL_CASE_INSENSITIVE' =>true,
	'TMPL_ACTION_ERROR' => 'Public:message', 
	'TMPL_ACTION_SUCCESS' => 'Public:message',
	'TMPL_EXCEPTION_FILE'=>'./App/Tpl/Public/exception.html',
	'DEFAULT_TIMEZONE' => 'PRC',
	'LOAD_EXT_CONFIG' => 'db,version',
	'OUTPUT_ENCODE' => false,
    'LANG_SWITCH_ON' => false,
    'LANG_AUTO_DETECT' => false,
	'DEFAULT_LANG' => 'zh-cn', // 默认语言
    'LANG_LIST' => 'zh-cn',
    'VAR_LANGUAGE' => '1',
    'COOKIE_PATH' => __ROOT__,
    'SESSION_OPTIONS'=>array('cookie_path'=>__ROOT__, 'expire'=>36000),
	'TOKEN_ON'=>false,  // 是否开启令牌验证
    'SHOW_PAGE_TRACE' =>false,
    'URL_MODEL'=>2,
    'URL_PATHINFO_DEPR' =>"/",
);
?>