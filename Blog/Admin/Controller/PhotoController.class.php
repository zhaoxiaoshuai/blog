<?php
namespace Admin\Controller;

class PhotoController extends CommonController
{
    public function index()
    {
        $data = D('Photo') -> where('pid='.session('admin.id')) -> relation('PhotoContent') -> select();
        $this -> set(compact('data')) ->  display();
    }

    public function edit()
    {
        $this -> display();
    }
}