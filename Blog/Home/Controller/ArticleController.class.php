<?php
namespace Home\Controller;

class ArticleController extends CommonController {
    public function index(){
        $id = I('get.name');
        $data = D('Admin/Article')
            -> relation(array('cate','author','admin','detail'))
            -> find($id);
//        $data['content'] = str_replace("\n", '\n', $data['content']);
        $data['content'] = htmlspecialchars_decode($data['content']);
        $this -> assign('data', $data);
        $this -> display();
    }
}