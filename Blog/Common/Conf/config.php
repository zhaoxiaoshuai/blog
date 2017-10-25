<?php
return array(

//    省略home 的做法
    'MODULE_ALLOW_LIST' => array ('Home','Admin'),
    'DEFAULT_MODULE' => 'Home',

    /* 数据库设置 */
    'DB_TYPE'               =>  'MySQL',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'blog',          // 数据库名
    'DB_USER'               =>  'xyj2156',      // 用户名
    'DB_PWD'                =>  '0002156000',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'jief_',    // 数据库表前缀
    'DB_PARAMS'             =>  array(), // 数据库连接参数


	//'配置项'=>'配置值'
    'MD5_KEY' => '1321354354365432134',
    'KEY' => '5465465413246854324',
    'DESCRIPTION' => '该用户没有留下蛛丝马迹。',
    'HOME_USER_FACE_PATH' => __WWW__.'/Public/uploads/home/face/',
    'PHOTO_UPLOADS_PATH' => __WWW__.'/Public/uploads/',
    'DEFAULT_PHOTO' => 'default.gif',
    'UPLOAD_PATH' => '/Public/uploads/',
    'PIC_UPLOAD_PATH' => 'photo/',
    'MD_PIC_UPLOAD_PATH' => 'markdown/',

    /**
     * 添加性别，状态信息，状态图标信息
     */
    'SEX' => array(
        'm' => '男',
        'w' => '女',
        'x' => '保密'
    ),
    'STATUS' => array(
        1 => '未激活',
        '启用',
        '停用',
        '已删除'
    ),
    'STATUS_ICON' => array(
        1 => 'label-warm',
        'label-success',
        'label-warm',
        'label-danger'
    ),
    'ARTICLE_STATUS' => array(
        1 => '未发布',
        '发布',
        '删除'
    ),
);
