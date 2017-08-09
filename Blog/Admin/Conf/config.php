<?php
return array(
	//'配置项'=>'配置值'
    'NAV' => array(
        '用户管理' => array(
            '用户列表' => '/admin/member/index',
            '添加用户' => '/admin/member/add',
            '已删除用户' => '/admin/member/deleted',
        ),
        '文章管理' => array(
            '文章列表' => '/admin/article/index',
            '添加文章' => '/admin/article/add',
        ),
        '分类管理' => array(
            '分类列表' => '/admin/article/index',
            '添加分类' => '/admin/article/add',
        ),
        '导航管理' => array(
            '导航列表' => '/admin/article/index',
            '添加导航' => '/admin/article/add',
        ),
        '管理员管理' => array(
            '管理员列表' => '/admin/admin/index',
        ),
    ),
    'SEX' => array(
        'm' => '男',
        'w' => '女',
        'x' => '保密'
    ),
    'STATUS' => array(
        1 => '未激活',
        '正常',
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