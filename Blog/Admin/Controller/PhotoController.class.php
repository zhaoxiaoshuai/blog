<?php
namespace Admin\Controller;

class PhotoController extends CommonController
{
    public function index()
    {
        $data = D('Photo') -> where('status!=\'4\'') ->  relation(['PhotoContent','Admin']) -> select();
        $this -> set(compact('data')) ->  display();
    }

    public function add()
    {
        $this -> display();
    }

    public function insert()
    {
//        拿数据
        $data = I('post.');
//        判断数据名字长度和描述长度是不是合法
        $nlen = mb_strlen($data['name']);
        $dlen = mb_strlen($data['description']);
        if($nlen < 2 || $nlen > 8 || $dlen < 4 || $dlen > 100)
            $this -> ajaxReturn(array(
                'status' => 400,
                'msg' => '数据不符合要求。'
            ));
//        附加数据
        $data['ctime'] = $data['utime'] = time();
        $data['status'] = 2;
        $data['cid'] = 0;
        $data['pid'] = session('admin.id');
        $d = D('Photo');
//        判断相册是不是已经存在
        if($d -> where("pid={$data['pid']} AND name='{$data['name']}'") -> count())
            $this -> ajaxReturn(array(
                'status' => 401,
                'msg' => '相册已经存在。'
            ));
//        判断插入不成功
        if(!$d -> add($data))
            $this -> ajaxReturn(array(
                'status' => 500,
                'msg' => '添加失败。'
            ));
        $this -> ajaxReturn(array(
            'status' => 0,
            'msg' => '添加成功。'
        ));
    }

    public function edit()
    {
        $id = I('name');
        if(!$id)
            $this ->ajaxReturn(array(
                'status' => 400,
                'msg' => '名字错误'
            ));
        $d = D('Photo');
//        $admin = session('admin.id');
//        if(!$d -> where("id={$id} AND pid={$admin}") -> count())
//            $this ->ajaxReturn(array(
//                'status' => 403,
//                'msg' => '请确保这个相册属于您。'
//            ));
        $data = $d -> where("id={$id}") -> find();
        if(!$data)
            $this ->ajaxReturn(array(
                'status' => 403,
                'msg' => '请确保这个相册存在。'
            ));
        $this -> set(compact('data','id')) -> display();
    }

    public function update()
    {
        $data = I('post.');
        $id = $data['id'];
        unset($data['id']);
        if(!$id)
            $this ->ajaxReturn(array(
                'status' => 400,
                'msg' => '名字错误'
            ));
//        判断数据名字长度和描述长度是不是合法
        $nlen = mb_strlen($data['name']);
        $dlen = mb_strlen($data['description']);
        if($nlen < 2 || $nlen > 8 || $dlen < 4 || $dlen > 100)
            $this -> ajaxReturn(array(
                'status' => 400,
                'msg' => '数据不符合要求。'
            ));
//        附加数据
        $data['utime'] = time();
        $d = D('Photo');
        $admin = session('admin.id');
        if($d -> where("id!={$id} AND name='{$data['name']}' AND pid={$admin}") -> count())
            $this -> ajaxReturn(array(
                'status' => 402,
                'msg' => '该相册名称已经存在。'
            ));
        if(!$d -> where("id={$id}") -> save($data))
            $this -> ajaxReturn(array(
                'status' => 500,
                'msg' => '修改失败。'
            ));
        $this -> ajaxReturn(array(
            'status' => 0,
            'msg' => '修改成功。'
        ));
    }

    public function view()
    {
        $id = I('name');
        if(!$id)
            $this -> ajaxReturn(array(
                'status' => 400,
                'msg' => '参数不对'
            ));

        $d = D('Photo');
        $data = $d -> where('id='.$id) -> relation('PhotoList') -> find();

        if(!$data)
            $this ->ajaxReturn(array(
                'status' => 404,
                'msg' => '请确保相册是存在的。'
            ));

        $this -> assign('data',$data) -> display();
    }

    /**
     * 状态修改，停用启用，软删除
     */
    public function status()
    {
        $id = I('name');
        if(!$id)
            $this -> ajaxReturn(['status' => 403, 'msg' => '名字参数错误。。。']);
        $status = I('status');
        if(!in_array($status,[2,3,4]))
            $this -> ajaxReturn(['status' => 403, 'msg' => '状态参数错误。。。']);
        $d = D('Photo') -> where ('id='.$id);
        if(!$d -> count())
            $this -> ajaxReturn(['status' => 400, 'msg' => '相册不存在', $d -> getLastSql()]);
        $d -> status = $status;
        if($d -> where('id='.$id) -> save())
            $this -> ajaxReturn(['status' => 0, 'msg' => C('STATUS')[$status].'成功']);
        else
            $this -> ajaxReturn(['status' => 500, 'msg' => C('STATUS')[$status].'失败']);
    }

    /**
     * 已删除列表显示
     */
    public function deleted()
    {
        $data = D('Photo') -> where('status=\'4\'') -> relation(['PhotoContent','Admin']) -> select();
        $this -> set(compact('data')) -> display();
    }

    /**
     * 物理删除相册数据
     */
    public function del()
    {
        $id = I('name');
        if(!$id)
            $this -> ajaxReturn(['status' => 403, 'msg' => '参数错误。。。']);
        $d = D('Photo');
        if(!$d -> where ('id='.$id) -> count())
            $this -> ajaxReturn(['status' => 400, 'msg' => '相册不存在']);
        if($d -> where('id='.$id) -> delete()){
            $this -> ajaxReturn(['status' => 0, 'msg' => '删除成功']);
        } else
            $this -> ajaxReturn(['status' => 500, 'msg' => '删除失败']);
    }

    /**
     * 显示上传界面
     */
    public function upload()
    {
        $id = I('name');
        $d = D('Photo');
        if($id && !$d -> where('pid='.session('admin.id').' AND id='.$id) -> count())
            $this-> withError('这个相册不属于你。') -> back() ;

        $data = $d -> where('pid='.session('admin.id')) -> field(['id','name']) -> select();
        $this -> set(compact('data','id')) -> display();
    }

    /**
     * 实质上传方法
     */
    public function uploads()
    {
        $this -> ajaxReturn(I('post.'));
    }

    /**
     * 上传完成后保存照片信息
     */
    public function uploads_save()
    {
        $this -> ajaxReturn(I('post.'));
    }
}