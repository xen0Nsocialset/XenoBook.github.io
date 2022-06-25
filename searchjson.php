<?php 
include("include/config.php"); 
$words = (string)$_GET['words'];

$result = mysqli_query($connections, "SELECT * FROM `users` WHERE `name` LIKE '%{$words}%' ORDER BY `id` LIMIT 100"); 
$arr = array();

$q = mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".intval($_SESSION['user']['id']));
if (mysqli_num_rows($q) == 0){
    echo("<script>document.location.href  = '/auth.php'</script>");
    die;
}
$_SESSION['user'] = mysqli_fetch_assoc($q);

$sessionInfo = json_decode($_SESSION['user']['info_json']);


while ($item = mysqli_fetch_assoc($result)) {
    if ($item['id'] == $_SESSION['user']['id']) continue;

    $info = json_decode($item['info_json']);
    unset($item['password']);
    unset($item['login']);
    $check =  mysqli_query($connections, "SELECT * FROM `pushes` WHERE (`userid`={$item['id']} AND `fromuser`={$_SESSION['user']['id']}) OR (`userid`={$_SESSION['user']['id']} AND `fromuser`={$item['id']})");

    $friends = $info->friends;
    $user = array(
        'user' => $item,
        'name' => $item['name'],
        'friend' => in_array($_SESSION['user']['id'],$friends),
        'push' => (mysqli_num_rows($check) != 0)
    );
    array_push($arr, $user);
}




echo(json_encode($arr, JSON_UNESCAPED_UNICODE));









?>