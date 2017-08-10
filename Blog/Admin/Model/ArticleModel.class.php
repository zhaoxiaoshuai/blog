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
    protected $tableName = '';
    protected $_link = array(
        'Article' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'Article',
            'foreign_key'   => 'userId',
            'mapping_name'  => 'articles',
            'mapping_order' => 'create_time desc',

            ),
        );
}