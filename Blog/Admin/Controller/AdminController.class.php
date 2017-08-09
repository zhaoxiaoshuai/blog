<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\Admin;

class AdminController extends Controller {
    //显示添加页面
    public function add(){
        $this -> display();
    }
    //执行插入数据库
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
            $this->ajaxReturn([
                'status'=>'403',
                'msg'=>$users->getError()
            ]);
        }else{
            // 验证通过 可以进行其他数据操作
            $data['ctime'] = time();
            $data['lasttime'] = time();
            $res = $users->add();
            if($res){
                $this->ajaxReturn( [
                    'status'=>'0',
                       'msg'=>'添加成功'
                ]);
            }else{
                $this->ajaxReturn( [
                    'status'=>'404',
                    'msg'=>'添加失败'
                ]);
            }
        }
    }
    //显示列表
    public function index(){
        $data = (new Admin()) -> select();
        $this -> set(compact('data'));
        $this->display();
    }
    //显示修改页面
    public function edit()
    {

    }
    //执行修改
    public function update()
    {

    }
    //删除
    public function delete($id)
    {
        $res = (new Admin()) -> where("id=$id")->delete();
        if($res){
            $data = [
                'status'=>'0',
                'msg'=>'删除成功'
            ];
        }else{
            $data = [
                'status'=>'404',
                'msg'=>'删除失败'
            ];
        }
        $this->ajaxReturn($data);
    }

}