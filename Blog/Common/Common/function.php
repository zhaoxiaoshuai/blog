<?php
namespace Common\Common;
 function index(){
    $config =    array(
        'fontSize'    =>    20,    // 验证码字体大小
        'length'      =>    3,     // 验证码位数
        'useNoise'    =>    false, // 关闭验证码杂点
    );
    $Verify = new \Think\Verify($config);
    $Verify->entry();

}