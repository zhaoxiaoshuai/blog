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
//        实例化上传对象
        $up = new \Think\Upload();
        $up -> MazSize = 2 * 1024 * 1024;
        $up -> exts = array('jpg', 'gif', 'png', 'jpeg');
        $up -> rootPath = C('PIC_UPLOAD_PATH');
//        上传方法
        $info   =   $up->upload();
//        失败
        if(!$info)
            $this -> ajaxReturn(array(
                'status' => 500,
                'msg' => $up -> getError()
            ));
//        成功
        $this -> ajaxReturn(array_merge(array(
            'status' => 0,
            'msg' => '上传成功！',
        ),$info));
    }
}