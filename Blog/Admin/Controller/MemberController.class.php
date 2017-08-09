<?php
namespace Admin\Controller;
use Admin\Model\MemberDetails;
use Admin\Model\Members;
use Think\Controller;
class MemberController extends Controller
{
    /**
     * 用户列表
     */
    public function index()
    {
        $data = (new Members()) -> where('status!=\'4\'') -> relation('MemberDetails') -> select();
        $this -> set(compact('data'));
        $this -> display();
    }
    /**
     * 用户添加第一步
     */
    public function add()
    {
        $this -> display();
    }

    public function insert(){
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
        $users = D('Members');
        $users -> startTrans();
        //验证
        if (!$users->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 返回错误提示信息
            $this->ajaxReturn([
                'status' => 402,
                'msg' => $users -> getError()
            ]);
        }

        // 验证通过 可以进行其他数据操作
        $users -> status = 2;
        $users -> password = encrypt($users -> password);
        $users -> add() ;
        $res = $users -> getLastInsID();
        if($res){
            $details = D('MemberDetails');
            $details -> create(['id' => $res, 'ctime' => time(), 'cip' => get_client_ip()]);
            if($details -> add()){
                $users -> commit();
                $this->ajaxReturn( [
                    'status' => 0,
                    'msg' => '添加成功。。。'
                ]);
            } else
                $this->ajaxReturn( [
                    'status' => 401,
                    'msg' => '添加失败。。。'
                ]);
        }
        $this->ajaxReturn( [
            'status' => 400,
            'msg' => '添加失败。。。',
            $res
        ]);
    }

    /**
     * 用户编辑
     */
    public function edit()
    {
        $id = I('name');
        if(!$id) echo '<center>参数出问题了</center>';
        $d = new Members();
        if(!$d -> where('id='.$id) -> count())
            $this -> ajaxReturn(['status' => 403, 'msg' => '没有此用户。']);
        $data = $d -> where('id='.$id) -> relation('MemberDetails') -> find();
        $this -> set(compact('data')) -> display();
    }

    /**
     * 用户编辑
     */
    public function update()
    {
        $id = I('get.')['name'];
//        $this -> ajaxReturn(['status' => $id,I('post.'),'msg' => '收到数据']);
        $rules = array(
            array('username','require','用户名必填！'), //默认情况下用正则进行验证
            array('username','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
            array('password','4,30','请输入4-16位密码',0,'length'), // 验证密码是否在指定长度范围
            array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
            array('phone','/^1[3|4|5|7|8][0-9]{9}$/','电话格式不正确'), // 验证手机号格式
            array('email','email','邮箱格式不正确'), // 自定义函数验证密码格式

        );
        $d = new Members();
        $d -> startTrans();
        //验证
        if (!$d->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 返回错误提示信息
            $this->ajaxReturn([
                'status' => 402,
                'msg' => $d -> getError()
            ]);
        }
        $data = [
            'birth'=> strtotime($_POST['birth']),
            'description' => $_POST['description'],
            'addr' => $_POST['addr'],
            'sex' => $_POST['sex'],
        ];
        if( ($d -> where('id='.$id) -> save()) !== false && D('MemberDetails')-> where('id='.$id) -> save($data)){
            $d -> commit();
            $this -> ajaxReturn([
                'status' => 0,
                'msg' => '更新成功。'
            ]);
        }
        $this -> ajaxReturn([
            'status' => 403,
            'msg' => '更新失败。',
        ]);
    }

    /**
     * 修改密码
     */
    public function password()
    {
        $this -> assign('name',I('name')) -> display();
    }

    /**
     * 修改密码
     */
    public function pass()
    {
        $id = $_GET['name'];
        if(!$id)
            $this -> ajaxReturn(['status' => 401, 'msg' => '参数错误。。。']);

//        验证两次密码的一致性
        $password = I('newpassword');
        $password2 = I('newpassword2');
        if($password != $password2)
            $this -> ajaxReturn(['status' => 401, 'msg' => '两次密码不一致。。。']);

//        验证管理员密码
        $admin  = I('oldpassword');
        $oldpassword = session('admin')['password'];
        if(!cryptcheck($admin, $oldpassword))
            $this -> ajaxReturn([
                'status' => 403,
                'msg' => '管理员密码出错'
            ]);

//        验证数据库有没有这个用户
        $d = new Members;
        if(!$d -> where('id='.$id) -> count())
            $this -> ajaxReturn([
                'status' => 404,
                'msg' => '没有这个用户。'
            ]);

//        设置密码
        $d -> password = $password;
        if($d -> where('id='.$id) -> save())
            $this -> ajaxReturn([
                'status' => 0,
                'msg' => '密码已修改。'
            ]);

//        设置失败
        $this -> ajaxReturn([
            'status' => 500,
            'msg' => '服务器问题，重试一下。'
        ]);
    }

    /**
     * 用户查看
     */
    public function view()
    {
        $id = I('name');
        if(!$id)
            $this -> ajaxReturn([
                'status' => 400,
                'msg' => '参数不完整'
            ]);
        $d = new Members;
        if(!$d -> where('id='.$id) -> count())
            $this -> ajaxReturn([
                'status' => 401,
                'msg' => '用户不存在'
            ]);

        $data = $d -> where('id='.$id) -> relation('MemberDetails') -> find();
        $this -> assign('data',$data) -> display();
    }

    /**
     * 状态修改，停用启用，软删除
     */
    public function status()
    {
        $id = I('name');
        if(!$id)
            $this -> ajaxReturn(['status' => 403, 'msg' => '参数错误。。。']);
        $status = I('status');
        if(!in_array($status,[1,2,3,4]))
            $this -> ajaxReturn(['status' => 403, 'msg' => '参数错误。。。']);
        $d = D('Members') -> where ('id='.$id);
        if(!$d -> count())
            $this -> ajaxReturn(['status' => 400, 'msg' => '用户不存在', $d -> getLastSql()]);
        $d -> status = $status;
        if($d -> where('id='.$id) -> save())
            $this -> ajaxReturn(['status' => 0, 'msg' => '成功'.C('STATUS')[$status]]);
        else
            $this -> ajaxReturn(['status' => 500, 'msg' => C('STATUS')[$status]].'失败');
    }

    /**
     * 已删除列表显示
     */
    public function deleted()
    {
        $data = (new Members()) -> where('status=\'4\'') -> relation('MemberDetails') -> select();
        $this -> set(compact('data'));
        $this -> display();
    }

    /**
     * 用户恢复
     */
    public function recovery()
    {
        $id = I('name');
        if(!$id)
            $this -> ajaxReturn(['status' => 403, 'msg' => '参数错误。。。']);
        $d = D('Members') -> where('status="4" AND id='.$id);
        if(!$d -> count())
            $this -> ajaxReturn(['status' => 400, 'msg' => '用户不存在']);
        $d -> status = 2;
        if($d -> where('status="4" AND id='.$id) -> save())
            $this -> ajaxReturn(['status' => 0, 'msg' => '成功还原']);
        else
            $this -> ajaxReturn(['status' => 500, 'msg' => '内部错误。']);
    }

    /**
     * 物理删除用户数据
     */
    public function del()
    {
        $id = I('name');
        if(!$id)
            $this -> ajaxReturn(['status' => 403, 'msg' => '参数错误。。。']);
        $d = D('Members') -> where ('id='.$id);
        $d -> startTrans();
        if(!$d -> count())
            $this -> ajaxReturn(['status' => 400, 'msg' => '用户不存在', $d -> getLastSql()]);
        if($d -> where('id='.$id) -> delete() && D('MemberDetails') -> where('id='.$id) -> delete()){
            $d -> commit();
            $this -> ajaxReturn(['status' => 0, 'msg' => '删除成功']);
        } else
            $this -> ajaxReturn(['status' => 500, 'msg' => '删除失败']);
    }
}