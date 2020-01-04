<?php
    header('Content-Type:text/html;charset=utf8');

    //目录读取函数
    function showDir($dirname)
    {
        $dir = opendir($dirname);
        while(false != ($file = readdir($dir))){
            if($file != '.'){ //排除当前目录
                $dir_arr[] = iconv('gbk','utf-8',$file);
            }            
        }
        closedir($dir);
        return $dir_arr;
    }

    // 获取文件类型
    function getFileType($filename)
    {
        switch (filetype($filename)){
			case 'dir':
				return '目录';
			case 'char':
				return '字符设备';
			case 'block':
				return '块设备';
			case 'file':
				return '文件';
			case 'link':
				return '链接';
			default:
				return '未知类型';
		}
    }

    //获取文件大小
    function getFileSize($filename){
        $size = filesize($filename); //获取文件大小
        $unit = ""; //单位
        if($size>pow(2,40)){ //可以优化一下
            $size = strstr($size / pow(2,40),'.',TRUE).substr(strstr($size/pow(2,40),'.'),0,3);
            $unit = 'TB';
        }elseif($size>pow(2,30)){
            $size = strstr($size / pow(2,30),'.',TRUE).substr(strstr($size/pow(2,30),'.'),0,3);
            $unit = 'GB';
        }elseif($size>pow(2,20)){
            $size = strstr($size / pow(2,20),'.',TRUE).substr(strstr($size/pow(2,20),'.'),0,3);
            $unit = 'MB';
        }elseif($size>pow(2,10)){
            $size = strstr($size / pow(2,10),'.',TRUE).substr(strstr($size/pow(2,10),'.'),0,3);
            $unit = 'KB';
        }else{
            $unit = 'Byte';
        }
        return $size.$unit;
    }


    //获取文件信息
    function getFileInfo($file){
        $arr = [];
        $arr['basename'] = basename($file); //获取文件名
        $arr['size'] = getFileSize($file); //获取文件大小
        $arr['type'] = getFileType($file); //获取文件类型
        $arr['ctime'] = date("Y/m/d H:i:s",filectime($file)); //获取创建时间
        $arr['mtime'] = date("Y/m/d H:i:s",filemtime($file)); //获取修改时间
        return $arr;
    }


    /**
     * rmdir(dirname) 删除空目录
     * unlink(dirname) 删除文件
     */

    function delDir($dirname){

        if(!file_exists($dirname)){
            return '文件不存在!';
        }
        //如果是文件，则直接删除
        if(is_file($dirname)){
            $res = unlink($dirname);
            return $res ? '删除文件成功!' : '删除文件失败!';
        }

        // 如果是目录
        $dir = opendir($dirname);

        //清空目录  如果能读取，即目录不为空，删除目录内的文件
        while($filename = readdir($dir)){
            if($filename != '.' && $filename != '..'  ){
                // 如果是文件 则删除
                // $filename = $dirname.DIRECTORY_SEPARATOR.$filename;
                $filename = $dirname.'/'.$filename;

                if(is_dir($filename)){ //如果是目录
                    delDir($filename); //递归调用
                }else{
                    unlink($filename);
                }
            }
        }

        //将清空的目录删除
        closedir($dir);
        return rmdir($dirname) ? '删除目录成功!' : '删除目录失败!';
    }

    // 打印函数
    function pre($arr){
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }