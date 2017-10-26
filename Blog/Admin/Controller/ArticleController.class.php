<?php
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends CommonController {
    /**
     * 文章列表
     */
    public function list()
    {
        //获取全部文章
        $data = D('Article') -> where('astatus!="3"') -> relation(array('cate', 'author','admin')) -> limit(10) -> page(I('get.p',1)) -> select();
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
		$id = I('param.name');
		$data = D('Article') -> relation(array('cate', 'author','admin','detail')) -> find($id);
		$this -> assign('data',$data) -> display();
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
            array('content','require','文章概述必须有。',1),
            array('content_detail','require','文章内容必须填写。',1),
            array('cid','require','请选择分类',1),
            array('title','require','请填写标题',1),
        );
        // 实例化文章模型
        $Article = D('Article');
//        开启事务
        $Article -> startTrans();
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
//        插入详情表的数据
        $detail = array(
            'content' => I('post.content_detail'),
            'created' => time(),
        );
        if(! $content_id = D('ArticleDetail') -> data($detail) -> add()){
//            回滚事务
            $Article -> rollback();
            $this -> ajaxReturn(
                array(
                    'status' => 402,
                    'msg' => '中途出了点小问题。'
                )
            );
        }
        $Article -> content_id = $content_id;
        if(empty($Article -> astatus)){
//            默认发布
            $Article -> astatus = '2';
        }
        if(empty($Article -> comment)){
//            默认开启评论
            $Article -> comment = '1';
        }
//        管理员添加的文章
        $Article -> is_admin = '1';
//        管理员的ID
        $Article -> uid = Session('admin') ['id'];
        if($Article -> add()){
//            提交事务
            $Article -> commit();
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
		$id = I('param.name');
		if(!$id){
			echo '参数不全。';die;
		}
		$cate = D('Cate') -> select();
		$data = D('Article') -> relation('detail') -> find($id);
        $this -> assign('cate' ,$cate) -> assign('data' ,$data) -> display();
    }
    /**
     * 修改文章
     */
    public function update()
    {
		$id = I('param.name');
		if(!$id){
			echo '参数不全。';die;
		}
		$rules = array(
            array('content','require','文章概述必须有。',1),
            array('content_detail','require','文章内容必须填写。',1),
            array('cid','require','请选择分类',1),
            array('title','require','请填写标题',1),
        );
        // 实例化文章模型
        $Article = D('Article');
		$detail_id = $Article -> find($id) ['content_id'];
//        开启事务
        $Article -> startTrans();
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
//        更新详情表的数据
        $detail = array(
            'content' => I('post.content_detail'),
            'created' => time(),
			'article_id' => $detail_id,
        );
		if(!D('ArticleDetail') -> data($detail) -> save()){
//            回滚事务
            $Article -> rollback();
            $this -> ajaxReturn(
                array(
                    'status' => 402,
                    'msg' => '中途出了点小问题。'
                )
            );
        }
        if(empty($Article -> astatus)){
//            默认发布
            $Article -> astatus = '2';
        }
        if(empty($Article -> comment)){
//            默认开启评论
            $Article -> comment = '1';
        }
//        管理员添加的文章
        $Article -> is_admin = '1';
//        管理员的ID
        $Article -> uid = Session('admin') ['id'];
		// 主键赋值，不然没法更新
        $Article -> id = $id;
        if($Article -> save()){
//            提交事务
            $Article -> commit();
            $this -> ajaxReturn(array(
                'status' => 0,
                'msg' => '修改成功',
            ));
        } else {
            $this -> ajaxReturn(array(
                'status' => 1,
                'msg' => '修改文章失败，请重试。',
            ));
        }
    }
    /**
     * 放入回收站
     */
    public function del()
    {
		if($this -> status(3)){
			$this -> ajaxReturn(
				array(
					'status' => 0,
					'msg' => '已放入回收站。'
				)
			);
		} else {
			$this -> ajaxReturn(
				array(
					'status' => 500,
					'msg' => '有点小问题，请稍候重试……'
				)
			);
		}
    }
    /**
     * 回收站
     */
    public function deleted()
    {
		//获取全部文章
        $data = D('Article') -> where('astatus="3"') -> relation(array('cate', 'author','admin')) -> limit(10) -> page(I('get.p',1)) -> select();
        $status = C('ARTICLE_STATUS');
        $cate = D('Cate') -> select();
        $this
            -> set(compact('data','status', 'cate'))
            -> display();
    }
    /**
     * 删除文章
     */
    public function delete()
    {
		$this -> ajaxReturn(
			array(
				'status' => 501,
				'msg' => '先在回收站放着吧'
			)
		);
    }

    public function test()
    {
        $data = D() -> relation('detail') -> find(1);
        $this -> ajaxreturn($data);
    }
	
	// 公共方法 修改状态值
	private function status(int $i)
	{
		$id = I('param.name');
		if(!$id){
			echo '';die;
		}
		$model = D('Article');
		if(!$model -> where ('id='.$id) -> count()){
			return false;
		}
		return $model -> where('id='.$id) -> save(array('astatus' => $i));
	}
	
	public function stop()
	{
		if($this -> status(1)){
			$this -> ajaxReturn(
				array(
					'status' => 0,
					'msg' => '已修改为未发布。'
				)
			);
		} else {
			$this -> ajaxReturn(
				array(
					'status' => 500,
					'msg' => '出了点问题， 请稍候重试……'
				)
			);
		}
	}
	
	public function start()
	{
		if($this -> status(2)){
			$this -> ajaxReturn(
				array(
					'status' => 0,
					'msg' => '已发布。'
				)
			);
		} else {
			$this -> ajaxReturn(
				array(
					'status' => 500,
					'msg' => '出了点问题， 请稍候重试……'
				)
			);
		}
	}
}