<?php
namespace Admin\Controller;
use Think\Controller;

class CodeController extends Controller {
    //生成验证码
    public function index(){
        $config =    array(
            'fontSize'    =>    20,     // 验证码字体大小
            'length'      =>    1,      // 验证码位数
            'useNoise'    =>    false,  // 关闭验证码杂点
            'imageW'      =>    140,    // 图片宽度
            'imageH'      =>    40,     // 图片高度
        );   $Verify = new \Think\Verify($config);
        $Verify->entry();
    }
    //验证码验证
    public static function _check($code, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
}