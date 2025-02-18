<?php

class WebAction extends GlobalAction {

	function _initialize(){
	
		if(!($this -> isSuper())) exit;
	
	}	

	public function getConfig(){

		$cache = include_once('../Config/config.cache.php');

		$config = array(
			
			'webname' => C('HD_webname'),
			
			'logourl' => C('HD_logourl'),
			
			'weburl' => C('HD_weburl'),
			
			'webtitle' => C('HD_webtitle'),
			
			'keywords' => C('HD_keywords'),
			
			'description' => C('HD_description'),
			
			'email' => C('HD_email'),
			
			'fox' => C('HD_fox'),
			
			'phone' => C('HD_phone'),
			
			'address' => C('HD_address'),
			
			'beian' => C('HD_beian'),
			
			'copyright' => C('HD_copyright'),
			
			'countcode' => C('HD_countcode'),
				
			'HTML_CACHE_ON' => $cache['HTML_CACHE_ON'],
			
			'HTML_FILE_SUFFIX' => $cache['HTML_FILE_SUFFIX'],
				
			'HTML_CACHE_TIME' => $cache['HTML_CACHE_TIME'],		

			'DB_TYPE' => C('DB_TYPE'),
			
			'DB_HOST' => C('DB_HOST'),
			
			'DB_NAME' => C('DB_NAME'),
			
			'DB_USER' => C('DB_USER'),
			
			'DB_PWD' => C('DB_PWD')

		);
		
		echo json_encode(array('success' => true, 'list' => $config));
		
	}
	
	public function saveWebInfo(){

		$flag = true;
	
		$data = I('post.');
		
		$file = C('HDCWS_DIR') . 'Config/config.web.php';

		$config = array(
		
			'HD_webname' => despiteStr($data['webname']),

			'HD_logourl' => despiteStr($data['logourl']),
			
			'HD_weburl' => despiteStr($data['weburl']),
			
			'HD_webtitle' => despiteStr($data['webtitle']),
			
			'HD_keywords' => despiteStr($data['keywords']),
			
			'HD_description' => despiteStr($data['description']),
			
			'HD_email' => despiteStr($data['email']),
			
			'HD_fox' => despiteStr($data['fox']),
			
			'HD_phone' => despiteStr($data['phone']),
			
			'HD_address' => despiteStr($data['address']),
			
			'HD_beian' => despiteStr($data['beian']),
			
			'HD_copyright' => despiteStr($data['copyright'])
		
		);

		$text = "<?php return " . var_export($config, true) . ";";
		
		if(false !== fopen($file, 'w+')){
			
			if(!file_put_contents($file, $text)) $flag = false;
			
		}else{
			
			$flag = false;
			
		}
	
		echo json_encode(array('success' => $flag));
		
	}

	public function saveDb(){

		$flag = true;

		$data = I('post.');
		
		$file = C('HDCWS_DIR') . 'Config/config.db.php';
		
		$config = array(
				
			'DB_TYPE' => $data['DB_TYPE'],
	
			'DB_HOST' => $data['DB_HOST'],
	
			'DB_NAME' => $data['DB_NAME'],
	
			'DB_USER' => $data['DB_USER'],
	
			'DB_PWD' => $data['DB_PWD'],
	
			'DB_PREFIX' => C('DB_PREFIX')
		
		);

		$text = "<?php return " . var_export($config, true) . ";";
		
		if(false !== fopen($file, 'w+')){
			
			if(!file_put_contents($file, $text)) $flag = false;
			
		}else{
			
			$flag = false;
			
		}
	
		echo json_encode(array('success' => $flag));
	
	}

	public function saveCache(){
	
		$flag = true;

		$data = I('post.');
		
		$file = C('HDCWS_DIR') . 'Config/config.cache.php';
		
		$config = array(
				
			'HTML_CACHE_ON' => $data['HTML_CACHE_ON'],
	
			'HTML_CACHE_RULES'=> array('*' => array('{$_SERVER.REQUEST_URI|md5}')),
				
			'HTML_FILE_SUFFIX' => $data['HTML_FILE_SUFFIX'],
			
			'HTML_CACHE_TIME' => $data['HTML_CACHE_TIME']			
		
		);

		$text = "<?php return " . var_export($config, true) . ";";
		
		if(false !== fopen($file, 'w+')){
			
			if(!file_put_contents($file, $text)) $flag = false;
			
		}else{
			
			$flag = false;
			
		}
	
		echo json_encode(array('success' => $flag));
	
	}	
	
	public function clearCache(){
		
		$appDir = C('HDCWS_DIR') . 'App';
		
		$runtimeDir = $appDir . '/Runtime';
		
		$htmlDir = $appDir . '/Html';

		if(file_exists($runtimeDir)) $this -> delDir($runtimeDir);
		
		if(file_exists($htmlDir)) $this -> delDir($htmlDir);
		
		echo 1;
		
	}
	
	protected function delDir($dir){
		
		//先删除目录下的文件：
		$dh = opendir($dir);
		
		while($file = readdir($dh)){
			
			if($file!="." && $file!=".."){
		    	
				$fullpath = $dir . "/" . $file;
		      
		      	if(!is_dir($fullpath)) {

					unlink($fullpath);
		      
		      	}else{

		      		$this -> delDir($fullpath);
		      
		      	}
		      
			}
 
		}
		 
		closedir($dh);

		//删除当前文件夹：
		if(rmdir($dir)){
			
		    return true;
		  
		}else{

			return false;
		
		}

	}
	
	//上传logo
	public function uploadLogo(){

		$uploadDir = C('UPLOAD_Logo_Dir');

		if(!empty($_FILES)){

			$fileKey = 'logo';

			$imgPath = rtrim($uploadDir, '/') . '/' . date('Y');

			$targetFile = C('HDCWS_DIR') . $imgPath;

			$this -> mkdirs($targetFile);
	
			$tempFile = $_FILES[$fileKey]['tmp_name'];
	
			$fileTypes = '*.jpg;*.jpeg;*.png;*.bmp;*.gif';
	
			$fileParts = pathinfo($_FILES[$fileKey]['name']);
	
			$extension = $fileParts['extension'];
	
			$imgName = md5($_FILES[$fileKey]['name'] . time()) . '.' . $extension;
	
			$targetFile .= '/' . $imgName;
	
			if(preg_match("/\.\*/", $fileTypes) || preg_match("/\." . $extension . "/i", $fileTypes)){
	
				move_uploaded_file($tempFile, $targetFile);
	
				echo $imgPath . '/' . $imgName;
	
				exit;
	
			}else{
	
				echo 0;
	
			}
	
		}else{
	
			echo 0;
	
		}
	
		echo 0;
	
	}
	
	public function getTableList(){

        $dbtables = M() -> query('SHOW TABLE STATUS');

        $total = 0;
        
        foreach($dbtables as $k => $v){

			$dbtables[$k]['id'] = $v['Name'];

            $dbtables[$k]['size'] = get_byte($v['Data_length'] + $v['Index_length']);
            
        }
	
		echo json_encode(array('success' => true, 'list' => $dbtables));
	
	}
	
	public function optimize(){

		$table[] = I('tablename' , '');

        if(empty($table)){

            echo 0;exit;

        }

        $strTable = implode(', ', $table);
        
        if (!M() -> query("OPTIMIZE TABLE {$strTable} ")) {

            $strTable = '';

        }

        echo 1;
	
	}
	
	public function repair(){

        $table[] = I('tablename' , '');

        if(empty($table)){

            echo 0;exit;

        }

        $strTable = implode(', ', $table);
        
        if (!M() -> query("REPAIR TABLE {$strTable} ")) {

            $strTable = '';

        }

        echo 1;

	}

	public function store(){

        $M = M();

        //防止备份数据过程超时
        function_exists('set_time_limit') && set_time_limit(0);

        $tables = explode(',', I('name'));

        if(empty($tables)){

            echo 0;exit;

        }

        $time = time();//开始时间
        
        $path = C('HDCWS_DIR') . C('Save_Db_Dir');
        
        $this -> mkdirs($path);

        $path .= '/' . date("YmdHis");

        $pre = "# -----------------------------------------------------------\n";

        //取得表结构信息:1，表示表名和字段名会用``包着的,0 则不用``
        $M -> execute("SET SQL_QUOTE_SHOW_CREATE = 1");

        $outstr = '';

        foreach ($tables as $table) {

            $outstr .= "# 表的结构 {$table} \n";
            
            $outstr .= "DROP TABLE IF EXISTS `{$table}`;\n";
            
            $tmp = $M -> query("SHOW CREATE TABLE {$table}");
            
            $outstr .= $tmp[0]['Create Table'] . " ;\n\n";

        }

        $sqlTable = $outstr;

        $outstr = "";

        $file_n = 1;

        $backedTable = array();

        //表中的数据
        foreach ($tables as $table) {

            $backedTable[] = $table;

            $outstr .= "\n\n# 转存表中的数据：{$table} \n";

            $tableInfo = $M -> query("SHOW TABLE STATUS LIKE '{$table}'");

            $page = ceil($tableInfo[0]['Rows'] / 10000) - 1;

            for ($i = 0; $i <= $page; $i++) {
            	
                $query = $M -> query("SELECT * FROM {$table} LIMIT " . ($i * 10000) . ", 10000");

                foreach($query as $val){
                	
                    $temSql = "";
                    
                    $tn = 0;
                    
                    $temSql = '';
                    
                    foreach ($val as $v) {
                    	
                        $temSql.=$tn == 0 ? "" : ",";
                        
                        $temSql.=$v == '' ? "''" : "'{$v}'";
                        
                        $tn++;

                    }
                    
                    $temSql = "INSERT INTO `{$table}` VALUES ({$temSql});\n";

                    $sqlNo = "\n# Time: " . date("Y-m-d H:i:s") . "\n" .
                            "# -----------------------------------------------------------\n" .
                            "# SQLFile Label：#{$file_n}\n# -----------------------------------------------------------\n\n\n";
                   	
                    if($file_n == 1){
                   		
                        $sqlNo = "# Description:备份的数据表[结构]：" . implode(",", $tables) . "\n".
                                 "# Description:备份的数据表[数据]：" . implode(",", $backedTable) . $sqlNo;
                    
                   	} else {
                   		
                   		$sqlNo = "# Description:备份的数据表[数据]：" . implode(",", $backedTable) . $sqlNo;
                    
                   	}

                    if(strlen($pre) + strlen($sqlNo) + strlen($sqlTable) + strlen($outstr) + strlen($temSql) > C("USER_SQL_FILESIZE")){
                        
                    	$file = $path . "_" . $file_n . ".sql";
                        
                    	$outstr = $file_n == 1 ? $pre . $sqlNo . $sqlTable . $outstr : $pre . $sqlNo . $outstr;
                       
                        if(!file_put_contents($file, $outstr, FILE_APPEND)){
                        	
                            echo 0;exit;
                            
                        }
    
                        $sqlTable = $outstr = "";
                        
                        $backedTable = array();
                        
                        $backedTable[] = $table;
                        
                        $file_n++;

                    }

                    $outstr.=$temSql;

                }

            }

        }

        if (strlen($sqlTable . $outstr) > 0) {
        	
            $sqlNo = "\n# Time: " . date("Y-m-d H:i:s") . "\n" .
                    "# -----------------------------------------------------------\n" .
                    "# SQLFile Label：#{$file_n}\n# -----------------------------------------------------------\n\n\n";
            
            if ($file_n == 1) {
            	
                $sqlNo = "# Description:备份的数据表[结构] " . implode(",", $tables) . "\n".
                         "# Description:备份的数据表[数据] " . implode(",", $backedTable) . $sqlNo;
            
            } else {
            	
                $sqlNo = "# Description:备份的数据表[数据] " . implode(",", $backedTable) . $sqlNo;
            
            }
            
            $file = $path . "_" . $file_n . ".sql";
            
            $outstr = $file_n == 1 ? $pre . $sqlNo . $sqlTable . $outstr : $pre . $sqlNo . $outstr;

            if (!file_put_contents($file, $outstr, FILE_APPEND)) {

            	echo 0;exit;

            }

            $file_n++;

        }

        $time = time() - $time;

        echo 1;exit;

	}
	
	public function getRestoreList(){

        $size = 0;

        $pattern = "*.sql";

        $filelist = glob(C('HDCWS_DIR') . C('Save_Db_Dir') . '/' . $pattern);

        $fileArray = array();

        foreach($filelist  as $i => $file){

            if (is_file($file)){

                $_size = filesize($file);

                $size += $_size;

                $name = basename($file);

                $pre = substr($name, 0, strrpos($name, '_'));

                $number = str_replace(array($pre. '_', '.sql'), array('', ''), $name);

                $fileArray[] = array(
                
                	'id' => $name,

                    'name' => $name,

                    'pre' => $pre,

                    'time' => date('Y-m-d H:i:s', filemtime($file)),

                    'size' => get_byte($size),

                    'number' => $number

                );

            }

        }

        if(empty($fileArray)) $fileArray = array();
        
        else{
        
        	$files = array();
        	
        	for($i = count($fileArray) - 1; $i >= 0; $i--) $files[] = $fileArray[$i];
        
        }

        //krsort($fileArray);

        echo json_encode(array('success' => true, 'list' => $files));
	
	}

	public function backdata(){

        function_exists('set_time_limit') && set_time_limit(0); //防止备份数据过程超时

        $file = I('name');

        if(empty($file)){

            echo 0;exit;

        }
        
        $file = C('HDCWS_DIR') . C('Save_Db_Dir') . '/' . $file;
        
        if(!file_exists($file)){
        	
        	echo 0;exit;
        	
        }

        $time = time();

        $M = M();

        $execute = 0;

        $file = fopen($file, "r");
       	
        $sql = "";

        while(!feof($file)){

        	$tem = trim(fgets($file));

        	//过滤,去掉空行、注释行(#,--)
       		if(empty($tem) || $tem[0] == '#' || ($tem[0] == '-' && $tem[1] == '-')) continue;

       		//统计一行字符串的长度
            $end = strlen($tem) - 1;
            
            $sql .= $tem;

            //检测一行字符串最后有个字符是否是分号，是分号则一条sql语句结束，否则sql还有一部分在下一行中
			if($tem[$end] == ";"){

				$M -> execute($sql);

				$sql = "";

            }

        }

       	fclose($file);

        $time = time() - $time;
        
        echo 1;

	}

	public function delsql(){

        $files = explode(',', I('name'));

        if(empty($files)){

       		echo 0;exit;

        }
        
        foreach($files as $file){

            unlink(C('HDCWS_DIR') . C('Save_Db_Dir') . '/' . $file);

        }

        echo 1;
	
	}

}