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
}