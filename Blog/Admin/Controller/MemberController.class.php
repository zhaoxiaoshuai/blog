<?php
namespace Admin\Controller;
use Admin\Model\Members;
use Think\Controller;
class MemberController extends Controller
{
    public function index()
    {
        $data = (new Members()) -> where('status!=\'4\'') -> relation('MemberDetails') -> select();
        $this -> set(compact('data'));
        $this -> display();
    }

    public function edit()
    {
        $id = I('id');
        if(!$id) echo 403;
        echo $id;
    }

    public function view()
    {
        $id = I('id');
        if(!$id) {
            echo '参数非法';
            die;
        }
        echo $id;
    }

    public function deleted()
    {
        $data = (new Members()) -> where('status=\'4\'') -> relation('MemberDetails') -> select();
        $this -> set(compact('data'));
        $this -> display();
    }
}