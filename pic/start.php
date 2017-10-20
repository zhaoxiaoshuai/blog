<?php

	include './config.php';
	include './db.php';
	include './curl.php';
	date_default_timezone_set('PRC');

	(new Jief) -> run();

	class Jief 
	{
		private $cate_name = '';
		private $title_name = '';
		static private $log_path = './log/';
		static private $baseDir = './';

		public function run ()
		{
			echo '开始执行了。',"\n";
			$this -> now = time();
			$memaaa = memory_get_usage();
			$id = 1;
			$count = DB::Table('jief_pic') -> rowCount();
			$end = DB::Table('jief_pic') -> find(' WHERE status="0"', 'id') -> id;
			echo '查找上次中断时候的最后一个ID是'.$end,"\n";
			// $download = new DownLoad;
			$flag = true;
			do{
				$tmpData = DB::Table('jief_pic') -> find("WHERE status='0'");
				if( !$tmpData ){
					$this -> writeLog( "{$tmpData -> id}个已经是空的了。");
					continue;
				}
				$this -> cate_name =  $tmpData -> cate;
				$this -> title_name =  $tmpData -> title;
				$this -> cate_name_u = $tmpData -> cate;
				$this -> title_name_u = $tmpData -> title;
				$this -> setLogPath();
				$this -> writeLog(var_export($tmpData, true));

				$this -> writeLog("开始采集：{$this -> cate_name_u} {$this -> title_name_u} {$memaaa}");

				$id = $tmpData -> id;
				$data = DB::Table('jief_pic_data') -> select("WHERE pid={$id} AND status='0'");
				// 拼装路径
				$tmpPath = './'.$this -> cate_name.'/'.$this -> title_name.'/';
				foreach ($data as $k => $v) {
					$num = $k + 1;
					$this -> writeLog("开始采集：{$this -> cate_name_u} {$this -> title_name_u} {$num} 链接:{$v -> pic} {$memaaa}");
					$tmpUrl = $v -> pic;
					// 子进程处理
					$pid = pcntl_fork();
				    if ($pid == -1) {
				        $this -> writeLog ("不能创建子进程");
				    } elseif ($pid) {
				        $this -> writeLog ( "我是第{$num}个进程");
				    } else {// 子进程处理
						$res = curlDownFile($tmpUrl, $tmpPath);
						if($res){
							$this -> writeLog("采集：{$this -> cate_name_u} {$this -> title_name_u} title ID:{$id} 本ID:{$v -> pic_id} {$num} 成功 {$memaaa}");
							DB::Table('jief_pic_data') -> update(array('status' => '1'), $v -> pic_id);
							// echo $id, ' - ',$v -> pic_id, iconv('UTF-8', 'GB2312//IGNORE','成功 内存占用：'.$memaaa), "\n";
							
						} else {
							$flag = false;
							$this -> writeLog("采集：{$this -> cate_name_u} {$this -> title_name_u} title ID:{$id} 本ID:{$v -> pic_id} {$num} 失败 {$memaaa}");
							// echo $id, ' - ',$v -> pic_id, iconv('UTF-8', 'GB2312//IGNORE','失败 内存占用：'.$memaaa), "\n";
						}
				        // 业务处理 begin
				        // 业务处理 end
				        exit;// 一定要注意退出子进程,否则pcntl_fork() 会被子进程再fork,带来处理上的影响。
				    }

					$membbb = memory_get_usage();
					$memaaa = ' 内存占用：'.round(($membbb-$memaaa)/1024/1024, 2).'MB';

				// foreach ($data as $k => $v) {
				// 	$num = $k + 1;
				// 	$this -> writeLog(iconv('UTF-8', 'GB2312//IGNORE',"开始采集：{$this -> cate_name_u} {$this -> title_name_u} {$num} 链接:{$v -> pic} {$memaaa}"));
				// 	$tmpUrl = $v -> pic;
				// 	$tmpPath = './'.$this -> cate_name.'/'.$this -> title_name.'/';
				// 	$res = curlDownFile($tmpUrl, $tmpPath);

				// 	$membbb = memory_get_usage();
				// 	$memaaa = ' 内存占用：'.round(($membbb-$memaaa)/1024/1024, 2).'MB';

				// 	if($res){
				// 		$this -> writeLog(iconv('UTF-8', 'GB2312//IGNORE',"采集：{$this -> cate_name_u} {$this -> title_name_u} title ID:{$id} 本ID:{$v -> pic_id} {$num} 成功 {$memaaa}"));
				// 		DB::Table('jief_pic_data') -> update(array('status' => '1'), $v -> pic_id);
				// 		// echo $id, ' - ',$v -> pic_id, iconv('UTF-8', 'GB2312//IGNORE','成功 内存占用：'.$memaaa), "\n";
						
				// 	} else {
				// 		$this -> writeLog(iconv('UTF-8', 'GB2312//IGNORE',"采集：{$this -> cate_name_u} {$this -> title_name_u} title ID:{$id} 本ID:{$v -> pic_id} {$num} 失败 {$memaaa}"));
				// 		// echo $id, ' - ',$v -> pic_id, iconv('UTF-8', 'GB2312//IGNORE','失败 内存占用：'.$memaaa), "\n";
				// 	}
				}

				// 等待子进程执行结束
				while (pcntl_waitpid(0, $forkChild) != -1) {
					$forkChild = pcntl_wexitstatus($forkChild);
					$this -> writeLog("子进程 {$forkChild} 完成。");
				}
				if($flag){
					DB::Table('jief_pic') -> update(array('status' => '1'), $id);
				}
				$this -> writeLog("采集：{$this -> cate_name_u} {$this -> title_name_u} 完成 {$memaaa}");
			}while ($tmpData);
			$this -> writeLog( '采集完成。');
		}

		private function setLogPath () {
			// 设置日志的路径
			$logDir = './log/';
			$this -> country_name = $this -> cate_name;
			$this -> type_name = $this -> title_name;
			// 格式化运行时的 时间戳		
			$fileTime = date('Y-m-d_H-i-s',$this->now);
			// 日志文件名
			$logFile = "pic_{$this -> country_name}__" . $fileTime . '_idx_.log';
			// 完整的日志路径
			$this->logPath = $logDir . $logFile;
			return $this;
		}
		
		private function initLog() {
			// 格式化运行时的 时间戳
			$logTime  = date('Y-m-d H:i:s',$this->now);
			// 开始时间
			$startTime = "开始：{$logTime}\n";
			// 写日志
			$this->writeLog($startTime);
			return $this;
		}
		
		private function writeLog($log, $addTime = true) {
			if($addTime) {
				$time = date('Y-m-d H-i-s',time());
				$log  = $time . ':  ' . $log;
			}
			$log .= "\n";
			
			if (file_exists($this->logPath) && filesize($this->logPath) > 53324471) {
				$this->setLogPath();
			}
			file_put_contents($this->logPath, $log, FILE_APPEND);
		}
	}