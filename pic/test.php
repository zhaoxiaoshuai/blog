<?php

	include './config.php';
	include	 './db.php';

	$msg = '是不是共享了？';
	
	$mo = DB::Table('jief_pic');

	echo $mo -> getsql();
	
	
	for ($i = 0; $i < 13; $i++){
	    $pid = pcntl_fork();

	    if ($pid == -1) {
	        die("could not fork");

	    } elseif ($pid) {
	        echo "I'm the Parent $i\n";

	    } else {// 子进程处理
	        // $content = file_get_contents("prefix_name0".$i);
	        // 业务处理 begin
	    	echo $msg,"\n";
			print_r(DB::Table('jief_pic') -> find('where id="'.$i.'"'));
	        // 业务处理 end

	        exit;// 一定要注意退出子进程,否则pcntl_fork() 会被子进程再fork,带来处理上的影响。
	    }
	}

// 等待子进程执行结束
while (pcntl_waitpid(0, $status) != -1) {
    $status = pcntl_wexitstatus($status);
    echo "Child $status completed\n";
}