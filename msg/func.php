<?php
function pre($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}
function dump($arr){
    echo '<pre>';
    var_dump($arr);
    echo '</pre>';
}

function conn($dbname){
    $host = 'localhost';
    $user = 'root';
    $pwd = '123456';
    // $dbname = 'msg_board';
    $conn=mysqli_connect($host,$user,$pwd,$dbname);
    if (!$conn) {
        die('no');
    }
}