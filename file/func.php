<?php

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

function getFileType($filename)
{
    //fifo，char，dir，block，link，file
    switch (filetype($filename)) {
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

function getFileSize($filename)
{
    $size = filesize($filename); //获取文件大小
    $unit = ""; //单位
    if ($size > pow(2, 40)) { //可以优化一下
        $size = strstr($size / pow(2, 40), '.', TRUE) . substr(strstr($size / pow(2, 40), '.'), 0, 3);
        $unit = 'TB';
    } elseif ($size > pow(2, 30)) {
        $size = strstr($size / pow(2, 30), '.', TRUE) . substr(strstr($size / pow(2, 30), '.'), 0, 3);
        $unit = 'GB';
    } elseif ($size > pow(2, 20)) {
        $size = strstr($size / pow(2, 20), '.', TRUE) . substr(strstr($size / pow(2, 20), '.'), 0, 3);
        $unit = 'MB';
    } elseif ($size > pow(2, 10)) {
        $size = strstr($size / pow(2, 10), '.', TRUE) . substr(strstr($size / pow(2, 10), '.'), 0, 3);
        $unit = 'KB';
    } else {
        $unit = 'Byte';
    }
    return $size. $unit;
}


function redir($fn)
{
    if (!file_exists($fn)) {

        return '文件不存在!';
    }

    if (is_file($fn)) {
        $res = unlink($fn);
        return $res ? '删除文件成功!' : '删除文件失败!';
    }

    $dir = opendir($fn);
    while ($file = readdir($dir)) {

        if ($file != '.' && $file != '..') {

            $file = $fn . '/' . $file;

            if (is_dir($file)) {
                redir($file);
            } else {
                unlink($file);
            }
        }
    }
    closedir($dir);
    return rmdir($fn) ? '删除目录成功!' : '删除目录失败!';
}

function newdir($new)
{
    if (!file_exists($new)) {
        mkdir($new, 07777);
        return '创建成功';
    } else {
        return '已经存在';
    }
}

function newfile($newf)
{
    if (!file_exists($newf)) {
        touch($newf);
        return '创建成功';

    } else {
        return '已经存在';
    }
    return $newf;
}

function pre($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function getflie($file)
{
    $arr = array();
    $arr['basename'] = basename($file);
    $arr['size'] =  getFileSize($file);
    $arr['type'] = getFileType($file);
    $arr['ctime'] = date('Y/m/d H:i:s', filectime($file));
    $arr['mtime'] = date('Y/m/d H:i:s', filemtime($file));
    return $arr;
}

function upp($ne){
    // pre($ne['pic']);
    // $ne['pic'];
    $error = $ne['pic']['error'];
    // pre($type);
    if ($error > 0) {

        switch ($error) {
            case 1:
                echo "文件大小超出了 upload_max_filesize 的值";
                break;
            case 2:
                echo "上传的文件大小超出了MAX_FILE_SIZE指令的值";
                break;
            case 3:
                echo "如果文件没有完全上传";
                break;
            case 4:
                echo "没有指定上传文件";
                break;
            default:
                echo "未知错误";
        }
        exit;
    }

    $name = $ne['pic']['name'];
    $type = $ne['pic']['type'];
    $type = basename($type);
    $temp = $ne['pic']['tmp_name'];
    $allows = array('jpeg', 'jpg', 'png', 'gif', 'psd');
    global $dirname;
    $uploads = $dirname;
    $filename = date("YmdHis").mt_rand(100,999).'.'.$type;
    // echo $uploads;
    $path = $uploads.'/'.$filename;

    if(!in_array($type,$allows)){
        echo ' <script>alert("非法文件类型");window.location.href="?path='.$dirname.'"</script>';
        exit;
    }

    if(move_uploaded_file($temp, $path)){
        echo '<script>alert("上传图片成功!");window.location.href="?path='.$dirname.'"</script>';
    }else{
        echo '<script>alert("上传图片失败!");window.location.href="?path='.$dirname.'"</script>';
    }

}


