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

mysqli_select_db($conn, $dbname);

mysqli_set_charset($conn, 'utf8');

if (!empty($_FILES)) {
    pre($_FILES);
    $url = file_get_contents($_FILES['img']['tmp_name']);
    // pre(addslashes($url));
    $url=addslashes($url);
    $sql = "INSERT INTO 
    img
    (`img`)
VALUES
    ('$url')
";
$res = mysqli_query($conn, $sql);
    echo $res;
    die;
    $newimg=stripslashes($url);
    file_put_contents('2.png',$newimg);
    exit;
}

// file_put_contents('1.png',$url);




?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="img" id="">
        <br>
        <button>22222</button>
    </form>
</body>

</html>