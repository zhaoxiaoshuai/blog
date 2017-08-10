<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Controller\CodeController;

class LoginController extends Controller {

    /**
     * 登陆页面
    */
    public function index()
    {
        $this->display();
    }

    /**
     * 验证登陆
     */
    public function dologin()
    {
        $data = I('post.');
        //设置验证规则
        $rules = array(
            array('username', 'require', '用户名必填！'),                   //验证用户名是否填写
            array('password', 'require', '密码必填！'),                     // 验证密码是否填写
            array('code', 'require', '验证码必填！', '1'),                     // 验证验证码是否填写
        );
        // 实例化admin对象
        $users = M('admin');
        //验证
        if (!$users->validate($rules)->create()) {
            // 如果创建失败 表示验证没有通过 返回错误提示信息
            $this->ajaxReturn([
                'status' => 401,
                'msg' => $users->getError()
            ]);
        }
        // 验证通过 可以进行其他数据操作
        //验证验证码
        $code = I('code');
        if (!CodeController::_check($code, $id = '')) {
            $this->ajaxReturn([
                'status' => 402,
                'msg' => '验证码错误'
            ]);
        }
        //验证用户是否存在
        $count = $users->where('username='.'\''.$data['username'].'\'')->count();
//        dump(!$count);die;
        if(!$count){
            $this->ajaxReturn([
                'status' => 403,
                'msg' => '用户名不存在',
            ]);
        }
        //验证密码
        $user = $users->where('username='.'\''.$data['username'].'\'')->find();
        $res = cryptcheck($data['password'],$user['password']);
        if(!$res){
            $this->ajaxReturn([
                'status' => 404,
                'msg' => '用户名或密码错误',
            ]);
        }
        session('admin',$user);
        $this->ajaxReturn([
            'status' => 0,
            'msg' => '登陆成功',
            cryptcheck($data['password'],$user['password'])
        ]);
    }
    /**
     * 退出登录
     */
    public function myclose()
    {
        //删除session('admin')
        session('admin',null);
        $this->ajaxReturn([
            'status' => 0,
            'msg' => '1秒后退出',
        ]);

    }
}