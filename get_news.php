<?php 
include("include/config.php");



$result = mysqli_query($connections, "SELECT * FROM `posts` ORDER BY `id` LIMIT ".$_GET['offcet'].", ".$_GET['count']);
$arr = array();

while ($item = mysqli_fetch_assoc($result)) {
    $userid = mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".$item['userid']));
    $fromuser = mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".$item['fromuser']));
    $part = array(
        'user'=>$userid,
        'fromuser'=>$fromuser,
        'item'=>$item

    );

    array_push($arr,$part);
}

echo(json_encode($arr, JSON_UNESCAPED_UNICODE));

?>