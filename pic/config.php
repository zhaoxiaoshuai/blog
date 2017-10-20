<?php

	define('DSN',  'mysql:dbname=sys;host=rm-uf6lotk1aye60i2hro.mysql.rds.aliyuncs.com');
    define('USER', 'root');
    define('PWD',  'xyj2156fei@');
	
	
	
	/**
	 * @param string $img_url 下载文件地址
	 * @param string $save_path 下载文件保存目录
	 * @param string $filename 下载文件保存名称
	 * @return bool
	 */
	function curlDownFile($img_url, $save_path = '', $filename = '') {
		if (trim($img_url) == '') {
			return false;
		}
		if (trim($save_path) == '') {
			$save_path = './';
		}

		//创建保存目录
		if (!file_exists($save_path) && !mkdir($save_path, 0777, true)) {
			return false;
		}
		if (trim($filename) == '') {
			$img_ext = strrchr($img_url, '.');
			$img_exts = array('.gif', '.jpg', '.png');
			if (!in_array($img_ext, $img_exts)) {
				return false;
			}
			$filename = strrchr($img_url, '-');
			$filename = ltrim($filename, '-');
		}

		// curl下载文件
		// $ch = curl_init();
		// $timeout = 30;
		// curl_setopt($ch, CURLOPT_URL, $img_url);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36');
		// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		// $img = curl_exec($ch);
		// curl_close($ch);

		// 保存文件到制定路径
		$tmp = file_put_contents($save_path.$filename, file_get_contents($img_url));
		unset($img, $url);
		return $tmp>1000?true:false;
	}
	
	function post($url, $data, $ref, $timeout = 3000)      
    {      
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $ref);   //构造来路    
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $handles = curl_exec($ch);
        curl_close($ch);
        return $handles;
    }

    //参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
	 function curl_request($url,$post='',$cookie='', $returnCookie=0, $ref = ''){
	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
	        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
	        curl_setopt($curl, CURLOPT_REFERER, $ref);
	        if($post) {
	            curl_setopt($curl, CURLOPT_POST, 1);
	            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
	        }
	        if($cookie) {
	            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
	        }
	        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
	        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        $data = curl_exec($curl);
	        if (curl_errno($curl)) {
	            return curl_error($curl);
	        }
	        curl_close($curl);
	        if($returnCookie){
	            list($header, $body) = explode("\r\n\r\n", $data, 2);
	            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
	            $info['cookie']  = substr($matches[1][0], 1);
	            $info['content'] = $body;
	            return $info;
	        }else{
	            return $data;
	        }
	}