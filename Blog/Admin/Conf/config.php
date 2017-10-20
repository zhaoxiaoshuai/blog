<?php
return array(
	//'配置项'=>'配置值'
    'NAV' => array(
        '用户管理' => array(
            '用户列表' => '/admin/member/index',
            '已删除用户' => '/admin/member/deleted',
        ),
        '文章管理' => array(
            '文章列表' => '/admin/article/list',
            '添加文章' => '/admin/article/add',
            '分类添加' => '/admin/cate/index',
            '分类编辑' => '/admin/cate/edit',
        ),
        '相册管理' => array(
            '用户相册查看' => '/admin/photo/index',
            '相册回收站' => '/admin/photo/deleted',
        ),
        '导航管理' => array(
            '导航列表' => '/admin/nav/index',
            '添加导航' => '/admin/nav/add',
        ),
        '管理员管理' => array(
            '管理员列表' => '/admin/admin/index',
        ),
    ),
);