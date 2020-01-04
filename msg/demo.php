<?php
include 'func.php';

$host = 'localhost';
$user = 'root';
$pwd = '123456';
$dbname = 'msg_board';

// $conn=mysqli_connect($host,$user,$pwd,$dbname );
$conn = mysqli_connect($host, $user, $pwd);

if (!$conn) {
    die('no');
}
// else{
//     echo ' yes';
// }

mysqli_select_db($conn, $dbname);

mysqli_set_charset($conn, 'utf8');
// 选择输出
$sql = 'SELECT
    `id`,`title`,`content`,`time`,msg_user.`user_name`,`img` 
 FROM 
    msg 
 LEFT JOIN 
    msg_user 
 ON 
    msg.user=msg_user.user_id
    
 ORDER BY
    `id`   
    ';

$msg = @mysqli_query($conn, $sql);

// dump($msg);
while ($res = mysqli_fetch_assoc($msg)) {
    $msg_arr[] = $res;
}
// $res=mysqli_fetch_assoc($msg);

// pre($msg_arr);
// die;
// 提交留言
// pre($_FILES);

if (!empty($_POST) && isset($_POST['sub'])) {
    // var_dump($_POST);
    // die;
    // pre($_FILES['img']);
    $type =pathinfo($_FILES['img']['name'])['extension'];
    // pre($type).'<br/>';
    // $tyarr = array('jpeg','jpg','png','gif');
    // var_dump(!is_array($type,$tyarr));
    // if(!is_array($type,$tyarr)){
    //     echo '图片类型不符合';
    //     // die;
    // }
    // pre($type);
    // die;
   
    // pre($url);
    // die;
    $title = $_POST['title'];
    $content = $_POST['content'];
    $time = time();
    $author = 1;
    $url = file_get_contents($_FILES['img']['tmp_name']);
    $img_file = "img/{$time}".rand().rand().'.'."$type"; 
    // echo $img_file;
    // echo !file_exists($img_file);
    if(!file_exists($img_file)){
        touch($img_file);
        file_put_contents($img_file,$url);
    }
    
    // die;
    // $img = addslashes($url);
    $sql = "INSERT INTO 
                msg
                (`title`,`content`,`time`,`user`,`img`)
            VALUES
                ('$title','$content','$time','$author','$img_file')
            ";
    // pre($img);
    // pre($sql);
    $res = mysqli_query($conn, $sql);
    // die;
    if ($res) {
        echo '
        <script>
        alert( "留言成功");
            location.href="demo.php";
        </script>';
    } else {
        echo '
        <script>
        alert( "留言失败");
            location.href="demo.php";
        </script>';
    }
}

// 删除
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $sql = "SELECT `img` FROM `msg` WHERE `id`={$id}";
    // echo $sql;
    $res = mysqli_query($conn, $sql);
    $re_img_url=mysqli_fetch_row($res);
    $dirname = pathinfo( $_SERVER['SCRIPT_FILENAME'])['dirname'];
    $re_img=$dirname.'/'.$re_img_url[0];
    // echo $re_img;
    
    // var_dump($re_img_url[0]);
    // pre( pathinfo( $_SERVER['SCRIPT_FILENAME'])['dirname']);
    // unlink($img_url);
    // pre($res);
    $sql = "DELETE FROM msg WHERE id={$id} ";

    // die;
    $res = mysqli_query($conn, $sql);
    if ($res) {
        unlink($re_img);
        echo '
        <script>
        alert( "删除成功");
            location.href="demo.php";
        </script>';
    } else {
        echo '<script>alert( "删除失败");
            location.href="demo.php";
        </script>';
    }
}




mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        td{
            vertical-align: middle !important;
            text-align: center;
        }
    </style>
</head>

<body>
    <table width=700 class="table table-striped">
        <tr>
            <td>id</td>
            <td>标题</td>
            <td>内容</td>
            <td>时间</td>
            <td>图片</td>
            <td>作者</td>
            <td> 操作</td>
        </tr>
        <?php foreach ($msg_arr as $item) { ?>
            <tr>
                <td> <?php echo $item['id']; ?> </td>
                <td> <?php echo $item['title']; ?> </td>
                <td> <?php echo $item['content']; ?> </td>
            
                <td> <?php if ('' != $item['time']) {
                            echo date("Y-m-d H:i:s", $item['time']);
                        }; ?> </td>
                <td> <?php echo "<img src=".$item['img']." style='width:240px';height:auto;>"?></td>

                <td><?php echo $item['user_name']; ?> </td>
                <td>
                    <a href="?del=<?php echo $item['id'] ?>" class="btn btn-danger" onclick="return confirm('是否删除')"> 删除 </a>
                    <a href="add.php?add=<?php echo $item['id'] ?>" class="btn btn-info "> 编辑 </a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <br>
    <hr>
    <h3 class="text-center">说点什么</h3>
    <form action="demo.php" method="post" class="text-center" style="width: 800px;margin:0 auto;" enctype="multipart/form-data">
        <label for="">标题</label>
        <input type="text" name='title' class="form-control" required>
        <label for="">内容</label>
        <textarea name="content" cols="30" rows="10" class="form-control" required></textarea>
        <br>
        <input type="file" name="img" required>
        <br>
        <input type="submit" name="sub" class="btn btn-info" style="width: 50%" style="margin: 0 auto;">

        </table>
    </form>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</html>