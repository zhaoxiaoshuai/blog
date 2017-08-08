<?php
namespace Admin\Controller;
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
        $users -> add() ;
        $res = $users -> getLastInsID();
        if($res){
            $details = D('MemberDetails');
            $details -> create(['id' => $res, 'ctime' => time(), 'cip' => $_SERVER['REMOTE_ADDR']]);
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
        $id = I('id');
        if(!$id) echo 403;
        echo $id;
    }

    /**
     * 用户查看
     */
    public function view()
    {
        $id = I('id');
        if(!$id) {
            echo '参数非法';
            die;
        }
        echo $id;
    }

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