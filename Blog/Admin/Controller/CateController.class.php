<?php
namespace Admin\Controller;
use Admin\Model\MemberDetails;
use Admin\Model\Members;
use Admin\Model\CateModel;
use Think\Controller;
class CateController extends Controller
{
    /**
     * 分类列表
     */
    public function index()
    {
        $data = (new CateModel()) -> select();
        $this -> assign('cate', $data) ->  display();
    }
    /**
     * 分类添加
     */
    public function add()
    {
        $this -> display();
    }

    /**
     * 插入分类
     */
    public function insert()
    {
        $id = $_GET['name'];
        $name = I('name');
        if($id==='' || !$name)
            $this -> ajaxReturn( [
                'status' => 400,
                'msg' => '参数有问题。'
            ] );

        $description = I('description');
        if(strlen($description) < 10 || strlen($description) > 100)
            $this -> ajaxReturn( [
                'status' => 401,
                'msg' => '描述太短或者太长了。'
            ] );

        $d = new CateModel();
        if($d -> where("id={$id} AND name='{$name}'") -> count())
            $this -> ajaxReturn( [
                'status' => 402,
                'msg' => '不能和上级分类名重名。'
            ] );

        $d -> name = $name;
        $d -> description = $description;
        $d -> pid = $id;
        if(!$d -> add())
            $this->ajaxReturn( [
                'status' => 403,
                'msg' => '添加失败。。。',
                $res
            ]);

        $this -> ajaxReturn( [
            'status' => 0,
            'msg' => '添加成功。。。'
        ]);
    }

    /**
     * 分类编辑
     */
    public function edit()
    {
        $data = (new CateModel()) -> select();
        $this -> assign('cate', $data) ->  display();
    }
    /**
     * 分类编辑2 只为了显示指定页面
     */
    public function edit_()
    {
        $this ->  display();
    }

    /**
     * 分类数据库更新
     */
    public function update()
    {
        $id = $_GET['name'];
        $name = I('name');
        if($id==='' || !$name)
            $this -> ajaxReturn( [
                'status' => 400,
                'msg' => '参数有问题。'
            ] );

        $description = I('description');
        if(strlen($description) < 10 || strlen($description) > 100)
            $this -> ajaxReturn( [
                'status' => 401,
                'msg' => '描述太短或者太长了。'
            ] );

        $d = new CateModel();

        $d -> name = $name;
        $d -> description = $description;
        if(!$d -> where('id='.$id) ->  save())
            $this->ajaxReturn( [
                'status' => 403,
                'msg' => '更新失败。。。',
                $res
            ]);

        $this -> ajaxReturn( [
            'status' => 0,
            'msg' => '更新成功。。。'
        ]);
//
    }
}