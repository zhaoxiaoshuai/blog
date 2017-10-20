<?php
/**
 * Created by PhpStorm.
 * User: 杰哥
 * Date: 2017/8/7
 * Time: 15:34
 */

namespace Admin\Model;


use Think\Model\RelationModel;

class ArticleModel extends RelationModel
{
    protected $tableName = 'article';
    protected $_link = array(
        'Article' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Article',
            'foreign_key'   => 'userId',
            'mapping_name'  => 'articles',
            'mapping_order' => 'create_time desc',
            ),
        'cate' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Cate',
            'foreign_key'   => 'cid',
            'mapping_fields' => 'name cate',
            'as_fields' => 'cate',
            ),
        'author' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Members',
            'foreign_key'   => 'uid',
            'mapping_fields' => 'username',
            'as_fields' => 'username',
            ),
        'admin' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Admin',
            'foreign_key'   => 'uid',
            'mapping_fields' => 'username admin',
            'as_fields' => 'admin',
            ),
        'detail' => array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'ArticleDetail',
            'foreign_key'   => 'content_id',
            'mapping_fields' => 'content content_detail',
            'mapping_key' => 'article_id',
            'as_fields' => 'content_detail',
            ),
        );
}