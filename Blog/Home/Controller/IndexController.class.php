<?php
namespace Home\Controller;

class IndexController extends CommonController {
    public function index(){
        $this -> display();
    }

    public function article()
    {
        $re = D('Article')
            -> where(array('aststus' => '2'))
            -> relation(array('cate'))
            -> order('utime desc')
            -> page(I('get.p', 1))
            -> limit(5) -> field(array(
                'id',
                'uid',
                'cid',
                'title',
                'content',
                'is_admin',
                'utime',
                'comment',
                'visit',
                'comment_count',
            ))
            -> select();
        $re = array_map(function ($v){
            $v['utime'] = date('Y-m-d H:i:s', $v['utime']);
            $v['content'] =  htmlspecialchars_decode($v['content']);
            return $v;
        }, $re);
        $count      = D('Article') -> where(array('aststus' => '2')) -> count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $this -> ajaxReturn(array('html' => $re, 'page' => $show));
    }
}