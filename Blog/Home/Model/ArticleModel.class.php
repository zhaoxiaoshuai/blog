<?php
namespace Home\Model;
use Think\Model\RelationModel;

class ArticleModel extends RelationModel{

    protected $tableName = 'article';
    protected $_link = array(
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
    );
}
