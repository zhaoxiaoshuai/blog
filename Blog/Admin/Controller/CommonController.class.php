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

}