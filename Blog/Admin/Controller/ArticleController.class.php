<?php
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends Controller {
    /**
     * 文章列表
     */
    public function list()
    {
        //获取全部文章
        $data = D('Article') -> relation(array('cate', 'author','admin')) -> limit(10) -> page(I('get.p',1)) -> select();
        $status = C('ARTICLE_STATUS');
        $cate = D('Cate') -> select();
        $this
            -> set(compact('data','status', 'cate'))
            -> display();
    }
    /**
     * 文章详情
     */
    public function show()
    {

    }
    /**
     * 添加文章
     */
    public function add()
    {
        $cate = D('Cate') -> select();
        $this -> assign('cate' ,$cate) -> display();
    }
    /**
     * 执行添加文章
     */
    public function insert()
    {
        $rules = array(
            array('content','require','文章内容必须填写。',1), 
            array('cid','require','请选择分类',1), 
            array('title','require','请填写标题',1),
        );
        // 实例化文章模型
        $Article = D('Article');
        //验证
        if (!$Article->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 返回错误提示信息
            $this->ajaxReturn([
                'status' => 402,
                'msg' => $Article -> getError()
            ]);
        }
        // 补充信息
        $Article -> ctime = $Article -> utime = time();
        if(empty($Article -> astatus)){
            $Article -> astatus = '2';
        }
        if(empty($Article -> comment)){
            $Article -> comment = '1';
        }
        $Article -> is_admin = '1';
        $Article -> uid = Session('admin') ['id'];
        if($Article -> add()){
            $this -> ajaxReturn(array(
                'status' => 0,
                'msg' => '添加文章成功',
            ));
        } else {
            $this -> ajaxReturn(array(
                'status' => 1,
                'msg' => '添加文章失败，请重试。',
            ));
        }
        
    }
    /**
     * 修改文章
     */
    public function edit()
    {

    }
    /**
     * 修改文章
     */
    public function update()
    {

    }
    /**
     * 放入回收站
     */
    public function del()
    {

    }
    /**
     * 回收站
     */
    public function deleted()
    {

    }
    /**
     * 删除文章
     */
    public function delete()
    {

    }
}