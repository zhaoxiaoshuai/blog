<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\AdminModel;

class AdminController extends Controller {
    public function add(){
        $this -> display();
    }
    public function insert(){
        $data = I('post.');
        //设置验证规则
        $rules = array(
            array('username','require','用户名必填！'), //默认情况下用正则进行验证
            array('username','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
            array('password','4,30','请输入4-16位密码',0,'length'), // 验证密码是否在指定长度范围
            array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
            array('phone','/^1[3|4|5|7|8][0-9]{9}$/','电话格式不正确'), // 验证手机号格式
            array('email','email','邮箱格式不正确'), // 自定义函数验证密码格式

        );
        // 实例化admin对象
        $users = M('admin');
        //验证
        if (!$users->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 返回错误提示信息
            $this->ajaxReturn($users->getError());
        }else{
            // 验证通过 可以进行其他数据操作
            $res = $users->add();
            if($res){
                $this->ajaxReturn( '添加成功');
            }else{
                $this->ajaxReturn( '添加失败');
            }

        }
    }
    public function index(){
        $this->display();
    }
}