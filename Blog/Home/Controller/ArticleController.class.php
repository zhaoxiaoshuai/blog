<?php
namespace Home\Controller;

class ArticleController extends CommonController {
    public function index(){
        $id = I('get.name');
        $data = D('Article')
            -> relation(array('cate','author','admin'))
            -> find($id);
//        $data['content'] = str_replace("\n", '\n', $data['content']);
        $this -> assign('data', $data);
        $this -> display();
    }
}