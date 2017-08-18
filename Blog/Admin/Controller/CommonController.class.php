<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller {
    //检测登录
    public function __construct(){
        if(!session('admin')){
            flu('error','请登录。');
            redirect(U('/admin/login/index'));
            die;
        }
        parent::__construct();
    }

    /** 带过去成功信息
     * @param $v
     * @return $this
     */
    public function withSuccess($v)
    {
        flu('success',$v);
        return $this;
    }

    /** 带过去失败信息
     * @param $v
     * @return $this
     */
    public function withError($v)
    {
        flu('error',$v);
        return $this;
    }

    /** 跳转
     * @param $url
     * @return $this
     */
    public function refresh($url)
    {
        redirect($url,0);
        return $this;
    }

    /**
     * 返回上一地址。
     * @return mixed
     */
    public function back()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        return $this;
    }

    public function upload_pic()
    {
//        上传方法
        $info   =   $this -> uploads(C('PIC_UPLOAD_PATH'), array('jpg', 'gif', 'png', 'jpeg'), 2 * 1024 *1024);
//        失败
        if($info['status'])
            $this -> ajaxReturn(array(
                'status' => 500,
                'msg' => $info['info']
            ));
//        成功
//        插入数据库，之所以不经过前台是因为，前台数据不可信
        $d = D('PhotoContent');
        $info = $info['info']['file'];
        $data['c_name'] = $info['name'];
        $data['md5'] = $info['md5'];
        $data['sha1'] = $info['sha1'];
        $data['size'] = $info['size'];
        $data['path'] = $info['savepath'].$info['savename'];
        $data['ctime'] = time();
        $data['pid'] = I('pid');
        $data['comment'] = I('comment');
//        print_r($data);die;
        $res = $d -> add($data);
        if(!$res)
            $this -> ajaxReturn(array(
                'status' => 502,
                'msg' => '数据库插入时候出错。'
            ));
        $this -> ajaxReturn(array(
            'status' => 0,
            'msg' => '上传成功！',
            'id' => $res
        ));
    }

    public function uploads($path, $type, $size = 2 * 1024 * 1024)
    {
        //        实例化上传对象
        $up = new \Think\Upload();
        $up -> MazSize = 2 * 1024 * 1024;
        $up -> exts = array('jpg', 'gif', 'png', 'jpeg');
        $up -> rootPath = C('PIC_UPLOAD_PATH');
//        上传方法
        $info   =   $up->upload();
        if($info)
            return array(
                'status' => 0,
                'info' => $info
            );
        else
            return array(
                'status' => 1,
                'info' => $up -> getError()
            );
    }
}