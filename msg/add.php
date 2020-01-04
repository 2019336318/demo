<?
include 'func.php';

$host = 'localhost';
$user = 'root';
$pwd = '123456';
$dbname = 'msg_board';

$conn = mysqli_connect($host, $user, $pwd, $dbname);

if (!$conn) {
    die('no');
}
mysqli_set_charset($conn, 'utf8');

if (isset($_GET['add'])) {

    $id = $_GET['add'];
    $sql = "SELECT 
        `id`,`title`,`content`,`time`,msg_user.`user_name` ,`img`
        FROM  msg 
        LEFT JOIN  
        msg_user 
        ON 
        msg.user=msg_user.user_id 
        WHERE id=$id ";
    $msg = @mysqli_query($conn, $sql);
    // dump($msg);
    while ($res = mysqli_fetch_assoc($msg)) {
        $msg_arr[] = $res;
    }
    // $res=mysqli_fetch_assoc($msg);

    // pre($msg_arr);
}

// 作者
$sql = "SELECT user_id,user_name FROM `msg_user`";
$user = mysqli_query($conn, $sql);
while ($result = mysqli_fetch_assoc($user)) {
    $authors[] = $result;
}
// pre($authors);
// die;
// pre($msg_arr);




if (isset($_POST['sub'])) {
    echo $msg_arr[0]['img'];
    // pre($_POST);
    // pre($_FILES);
    // if ($_POST['img_url']!= '') {
    $new_url = $_POST['img_url'];
    $tmp = $_FILES['img']['tmp_name'];
    $new_img = file_get_contents($tmp);
    // var_dump($new_img);
    // die;
    if ($new_img) {
        file_put_contents($new_url, $new_img);
    }
    // }



    // die;
    $id = $_POST['msg_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $time = time();

    $sql = "UPDATE 
                    msg 
                SET
                    `title`= '$title',
                    `content` = '$content',
                    `time` = '$time',
                    `user` = $author,
                    `img`= '$new_url'
                WHERE
                    `id` = $id";

    // pre($sql);
    // die;
    $res = mysqli_query($conn, $sql);
    // die;
    if ($res) {
        echo '
            <script>
            alert( "修改成功");
                location.href="demo.php";
            </script>';
    } else {
        echo '
            <script>
            alert( "修改失败");
                location.href="demo.php";
            </script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
    <form action="add.php" method="post" enctype="multipart/form-data">
        <label for="">标题</label>
        <input type="text" name='title' class="form-control" value="<?php echo $msg_arr[0]['title'] ?>" required>
        <label for="">内容</label>

        <textarea name="content" cols="30" rows="10" class="form-control" required>
        <? echo $msg_arr[0]['title'] ?>
        </textarea>
        <label for="">是否修改图片</label>
        <input type="file" name="img" id="">


        <input type="hidden" name="img_url" value="<?php echo $msg_arr[0]['img'] ?>">
        <img src="<?php echo $msg_arr[0]['img'] ?>" alt="" width="320px">
        <br>
        <br>


        <input type="hidden" name="msg_id" value="<?php echo $msg_arr[0]['id']; ?>">

        <label for="">作者</label>
        <select name="author">
            <?php foreach ($authors as $item) { ?>
        <option value="<?php echo $item['user_id']; ?>" <?php if ($item['user_id'] == $res['msg_user']) {
            echo 'selected="selected"';
        } ?>>
                    <?php echo $item['user_name']; ?>
                </option>
            <?php } ?>
        </select>
        <input type="submit" name="sub" class="btn btn-info" style="width: 50%">
        </table>
    </form>
</body>

</html>