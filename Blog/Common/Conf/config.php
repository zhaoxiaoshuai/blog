<?php
return array(
	//'配置项'=>'配置值'
    'MD5_KEY' => '1321354354365432134',
    'KEY' => '5465465413246854324',
    'DESCRIPTION' => '该用户没有留下蛛丝马迹。',
    'HOME_USER_FACE_PATH' => __WWW__.'/Public/uploads/home/face/',
    'PHOTO_UPLOADS_PATH' => __WWW__.'/Public/uploads/photo/',

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
);