<?php
namespace Admin\Controller;
use Admin\Model\Members;
use Think\Controller;
class MemberController extends Controller
{
    public function index()
    {
        $data = (new Members()) -> relation('MemberDetails')-> select();
        $this -> set(compact('data'));
        $this -> display();
    }
}