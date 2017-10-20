<?php
    define('DSN',  'mysql:dbname=sys;host=rm-uf6lotk1aye60i2hro.mysql.rds.aliyuncs.com');
    define('USER', 'root');
    define('PWD',  'xyj2156fei@');
    include './pic/db.php';

	$http = new swoole_http_server("localhost", 65500);

	$http->on("start", function ($server) {
	    echo "Swoole http server is started at http://127.0.0.1:8888\n";
	});

	$http->on("request", function ($request, $response) {

		if($request -> server ['request_uri'] == '/favicon.ico'){
			return;
		}
		$get = $request -> get;
		$get = $get?$get:1;
	    $response->header("Content-Type", "image/jpeg");
	    $model = DB::Table('jief_pic_data') -> find('where pic_id='.$get['id']);
	    $response->end(file_get_contents($model -> pic));
	});

	$http->start();