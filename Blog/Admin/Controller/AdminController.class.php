<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\AdminModel;

class AdminController extends Controller {

    /**
     * 显示添加页面
     */
    public function add(){
        $this -> display();
    }

    /**
     * 执行插入数据库
     */
    public function insert(){
        $data = I('post.');
        //设置验证规则
        $rules = array(
            array('username','require','用户名必填！'),                   //默认情况下用正则进行验证
            array('username','','帐号名称已经存在！',0,'unique',1),       // 在新增的时候验证name字段是否唯一
            array('password','4,30','请输入4-16位密码',0,'length'),       // 验证密码是否在指定长度范围
            array('repassword','password','确认密码不正确',0,'confirm'),  // 验证确认密码是否和密码一致
            array('phone','/^1[3|4|5|7|8][0-9]{9}$/','电话格式不正确'),   // 验证手机号格式
            array('email','email','邮箱格式不正确'),                      // 自定义函数验证密码格式
        );
        // 实例化admin对象
        $users = M('admin');
        //验证
        if (!$users->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 返回错误提示信息
            $this->ajaxReturn([
                'status'=>402,
                'msg'=>$users->getError()
            ]);
        }else{
            // 验证通过 可以进行其他数据操作
            $data['ctime'] = time();            //添加创建时间
            $data['lasttime'] = time();         //默认创建时间就是第一次登陆时间
            $data['password'] = encrypt($data['password']);
            $res = $users->add($data);
            if($res){
                $this->ajaxReturn( [
                    'status'=>0,
                       'msg'=>'添加成功'
                ]);
            }else{
                $this->ajaxReturn( [
                    'status'=>401,
                    'msg'=>'添加失败'
                ]);
            }
        }
    }
    /**
     * 显示列表
    */
    public function index(){
        $data = (new AdminModel()) -> select();
        $this -> set(compact('data'));
        $this->display();
    }

    /**
     * 显示修改页面
     */
    public function edit()
    {
        $id = I('id');
        $user = D('admin') -> where('id='.$id)->find();
        $this -> set(compact(['user','id']));
        $this -> display();
    }

    /**
     * 执行修改
     */
    public function update()
    {
        $id = I('get.')['id'];
        //设置验证规则
        $rules = array(
            array('phone','/^1[3|4|5|7|8][0-9]{9}$/','电话格式不正确'), // 验证手机号格式
            array('email','email','邮箱格式不正确'),                    // 自定义函数验证密码格式
        );
        // 实例化admin对象
        $users = M('admin');
        //验证
        if (!$users->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 返回错误提示信息
            $this->ajaxReturn([
                'status'=>402,
                'msg'=>$users->getError()
            ]);
        }else{
            // 验证通过 可以进行其他数据操作
            $res = $users->where('id='.$id)->save();
            if($res){
                $this->ajaxReturn( [
                    'status'=>0,
                    'msg'=>'修改成功'
                ]);
            }else{
                $this->ajaxReturn( [
                    'status'=>401,
                    'msg'=>'修改失败'
                ]);
            }
        }
    }

    /**
     * 删除
     */
    public function delete()
    {
        $id = I('id');
        if(!$id) {
            $data = [
                'status' => 401,
                   'msg' => '参数错误。'
            ];
        }
        $user = D('admin') -> where('id='.$id)->count();
        if($user){
            $data = [
                'status' => 404,
                   'msg' => '用户不存在'
            ];
        }
        $res = D('admin') -> where("id=$id")->delete();
        if($res){
            $data = [
                'status' => 0,
                    'msg'=> '删除成功'
            ];
        }else{
            $data = [
                'status' => 403,
                   'msg' => '删除失败'
            ];
        }
        $this->ajaxReturn($data);
    }
    /**
     * 修改个人密码页面
     */
    public function password()
    {
        $this -> assign('id',I('id')) -> display();
    }
    /**
     * 执行修改密码
     */
    public function pass()
    {
        $id =  I('get.id');
        $password = I('post.newpassword');
        $password2 = I('post.newpassword2');
        if(!$id){
            $this -> ajaxReturn(['status' => 401, 'msg' => '参数错误。。。']);
        }
        //验证新密码长度
        if (strlen($password) < 4 || strlen($password)>18){
            $this -> ajaxReturn(['status' => 402, 'msg' => '新密码长度不正确']);
        }
        //验证两次密码的一致性
        if($password != $password2){
            $this -> ajaxReturn(['status' => 403, 'msg' => '两次密码不一致。。。']);
        }
        //验证管理员密码
        $oldpassword = I('post.password');
        $adminpassword = session('admin')['password'];
        if(!cryptcheck($oldpassword, $adminpassword))
            $this -> ajaxReturn([
                'status' => 404,
                'msg' => '原密码出错'
            ]);
        //验证数据库有没有这个用户
        $admin = new AdminModel();
        if(!$admin -> where('id='.$id) -> count()){
            $this -> ajaxReturn([
                'status' => 405,
                'msg' => '没有这个用户。'
            ]);
        }
        //设置密码
        $admin -> password = encrypt($password);
        if($admin -> where('id='.$id) -> save()){
            //删除session('admin')
            session('admin',null);
            $this -> ajaxReturn([
                'status' => 0,
                'msg' => '密码已修改。'
            ]);
        }
        //设置失败
        $this -> ajaxReturn([
            'status' => 500,
            'msg' => '服务器问题，重试一下。'
        ]);

    }
    /**
     * 个人信息
     */
    public function show()
    {
        $this -> display();
    }

}