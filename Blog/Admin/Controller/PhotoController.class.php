<?php
namespace Admin\Controller;

class PhotoController extends CommonController
{
    public function index()
    {
        $data = D('Photo') -> where('status!=\'4\'') ->  relation(['PhotoContent','Admin']) -> select();
        $this -> set(compact('data')) ->  display();
    }

    public function edit()
    {
        $this -> display();
    }

    public function view()
    {
        $this -> display();
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
}