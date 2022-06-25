<?php

include("include/config.php"); 

if (isset($_GET['id'])){

    $user= mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".intval($_GET['id'])));
    $info = json_decode($user['info_json']);

    $check =  mysqli_query($connections, "SELECT * FROM `pushes` WHERE (`userid`={$user['id']} AND `fromuser`={$_SESSION['user']['id']}) OR (`userid`={$_SESSION['user']['id']} AND `fromuser`={$user['id']})");
    if (mysqli_num_rows($check) == 0){
        mysqli_query($connections,"INSERT INTO `pushes`(`userid`, `fromuser`, `type`) VALUES ('{$user['id']}','{$_SESSION['user']['id']}','tofriend')");
    }
    $arr=array("true");
    
    echo(json_encode($arr, JSON_UNESCAPED_UNICODE));
}

?>