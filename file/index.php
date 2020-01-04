<?php
include 'func.php';
date_default_timezone_set('Asia/Shanghai');
$dirname = pathinfo($_SERVER['SCRIPT_FILENAME'])['dirname'];

// pre($_SERVER);

// $a = 'D:/phpstudy_pro/WWW/file';

// $a = substr($a,0,strrpos($a,'/'));
// echo $a;




// pre($info);

if (!empty($_GET['path'])) {
    $dirname = $_GET['path'];
}

$info = showDir($dirname);
// echo '当前路径' . $dirname . "<br>";
foreach ($info as $value) {
    $info_list[] = getflie($dirname . '/' . $value);
}
// pre($info_list);
// echo $_GET['path'];
if (!empty($_GET['del'])) {
    // echo '删除路径'.$_GET['del']."<br>";
    // echo '当前路径'.$new_path = dirname($_GET['del'])."<br>";
    $msg = reDir($_GET['del']);
    $new_path = dirname($_GET['del']);
    if ($msg) {
        echo "<script>alert(\"{$msg}\");</script>";
        echo "<script>window.location.href='?path=$new_path'</script>";
    }
}

// 创建目录
if (!empty($_POST) && isset($_POST['dir'])) {
    $new =  $_POST['dir'];
    $newdir = $dirname . '/' . $new;
    $mess = newdir($newdir);
    if ($mess) {
        echo "<script>alert(\"{$mess}\");</script>";
        echo "<script>window.location.href='?path=$dirname'</script>";
    }
}

// 创建文件
if (!empty($_POST) && isset($_POST['file'])) {
    $new =  $_POST['file'];
    $newfile = $dirname . '/' . $new;
    $message = newfile($newfile);
    if ($message) {
        echo "<script>alert(\"{$message}\");</script>";
        echo "<script>window.location.href='?path=$dirname'</script>";
    }
}

// 上传文件
if (!empty($_FILES)) {

    $pic = $_FILES;
    upp($pic);
}

// pre($_SESSION);
$count = 0;
if (file_exists("count.txt")) {
    $count = file_get_contents("count.txt");
}
$count++;
file_put_contents("count.txt", $count);




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>文件系统</title>
    <link type="text/css" rel="stylesheet" href="./lib/bootstrap/css/bootstrap.css" />
    <style>
        input[name='pic'] {
            width: 100%;
            height: 100%;
            /* background: #CCC; */
            position: absolute;
            /* z-index: -1; */
            opacity: 0;
            top: 0;
            left: 0;
        }

        .pic {
            /* background: #CCC; */
            position: relative;
            padding: 10px 30px;
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: #FFF;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <h2 class="text-center text-primary">简易文件系统</h2>
        </div>
        <div class="row">

            <p align="center"><?php echo "访问量：" . $count;; ?></p>
            <form action="" method="post" enctype="multipart/form-data" class="col-sm-4">
                <div class="form-group">
                    <label class="sr-only" for="exampleInputEmail3">上传图片</label>
                    <div class="pic btn btn-info">
                        <input type="file" name="pic" class="form-control" id="exampleInputEmail3" placeholder="上传图片">
                        Plase Up Your Pic
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">上传图片</button>

            </form>

            <form class="form-inline pull-right" method="post">
                <div class="form-group">
                    <label class="sr-only" for="exampleInputEmail3">目录</label>
                    <input type="text" name="dir" class="form-control" id="exampleInputEmail3" placeholder="创建目录">
                </div>
                <button type="submit" class="btn btn-primary">创建目录</button>
            </form>

            <form class="form-inline pull-right" method="post">
                <div class="form-group">
                    <label class="sr-only" for="exampleInputEmail3">文件</label>
                    <input type="text" name="file" class="form-control" id="exampleInputEmail3" placeholder="创建文件">
                </div>
                <button type="submit" class="btn btn-primary">创建文件</button>
            </form>
        </div>
        <hr />
        <div class="row">
            <table class="table table-striped table-hover table-responsive table-bordered">
                <tr>
                    <td>文件名称</td>
                    <td>文件大小</td>
                    <td>文件类型</td>
                    <td>创建时间</td>
                    <td>修改时间</td>
                    <td>操作</td>
                </tr>
                <?php foreach ($info_list as $item) {
                    $basename = $item['basename'];
                    if ($basename == '..') {
                        $link = substr($dirname, 0, strrpos($dirname, '/'));
                    } else {
                        $link = $dirname . '/' . $basename;
                    }
                ?>
                    <tr>
                        <td><?php
                            if ($basename == '..') {
                                echo "<a href='?path={$link}'> 上一层目录 </a>";
                            }
                            if ($item['type'] == '目录') {
                                echo "<a href='?path={$link}' class='text-primary'> {$basename} </a>";
                            } else {
                                echo "<span class='text-muted'> {$basename} </span>";
                            }

                            ?></td>
                        <td>
                            <?php
                            if ($item['type'] !== '目录') {
                                echo $item['size'];
                            }
                            ?>
                        </td>
                        <td><?php echo $item['type']; ?></td>
                        <td><?php echo $item['ctime']; ?></td>
                        <td><?php echo $item['mtime']; ?></td>
                        <td>
                            <?php
                            if ($basename !== '..') {
                                echo "<a href='?del={$link}'class='btn btn-sm btn-warning' onclick='return confirm(\"确定要删除吗?\");'>删除</a>";
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <script src="./lib/jquery/jquery.min.js"></script>
    <script src="./lib/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>