<?php
/**
 * Created by PhpStorm.
 * User: 杰哥
 * Date: 2017/8/7
 * Time: 15:34
 */

namespace Admin\Model;


use Think\Model\RelationModel;

class PhotoModel extends RelationModel
{
    protected $tableName = 'photo';
    protected $_link = array(
            'PhotoContent'=> array(
                'mapping_type' => self::HAS_ONE,
                'mapping_key' => 'cid',
                'foreign_key' => 'id',
                'as_fields' => 'path',
            ),
            'Admin'=> array(
                'mapping_type' => self::HAS_ONE,
                'mapping_key' => 'pid',
                'foreign_key' => 'id',
                'as_fields' => 'username',
            ),
        );
}