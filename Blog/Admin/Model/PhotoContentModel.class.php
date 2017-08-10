<?php
/**
 * Created by PhpStorm.
 * User: 杰哥
 * Date: 2017/8/7
 * Time: 15:34
 */

namespace Admin\Model;


use Think\Model\RelationModel;

class PhotoContentModel extends RelationModel
{
    protected $tableName = 'photo_content';
    protected $_link = array(
        'Photo'=> array(
            'mapping_type' => self::HAS_ONE,
            'foreign_key' => 'cid',
            'as_fields' => 'path',
        ),
    );
}