<?php

$config	= array(
    'DEFAULT_THEME'		        => 'Default',
    'DEFAULT_CHARSET'           => 'utf-8',
    'APP_GROUP_LIST'            => 'Manage,Castle',
    'DEFAULT_GROUP'             => 'Castle',
    'DB_FIELDS_CACHE'           => false,
    'DB_FIELDTYPE_CHECK'        => false,
    'LOG_RECORD'                => false,
    'APP_SUB_DOMAIN_DEPLOY'     =>1,
    'APP_SUB_DOMAIN_RULES'      =>array(
        'c'         =>array('Manage/'),
        'b'         =>array('Castle/'),
    ),
);

$database = array(
    'DB_PREFIX'=>'5k_a_',
    'DB_TYPE'=>'mysql',
    'DB_HOST'=>'localhost',
    'DB_PORT'=>'3306',
    'DB_NAME'=>'partner2',
    'DB_USER'=>'root',
    'DB_PWD'=>'feitianyu',
);
return array_merge($database, $config);

?>