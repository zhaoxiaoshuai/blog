<?php
/**
 * Created by PhpStorm.
 * User: 杰哥
 * Date: 2017/8/7
 * Time: 15:34
 */

namespace Admin\Model;


use Think\Model\RelationModel;

class Members extends RelationModel
{
    protected $tableName = 'members';
    protected $_link = array(
            'MemberDetails'=> array(
                'mapping_type' => self::HAS_ONE,
                'foreign_key' => 'id',
                'as_fields' => 'sex,birth'
            ),
        );
}