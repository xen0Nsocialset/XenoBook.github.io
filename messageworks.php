<?php include("include/config.php"); ?>
<?php 
if ($_GET['type']=="send"){
    $q = mysqli_query($connections, "SELECT * FROM `messages` WHERE (`userone`={$_SESSION['user']['id']} AND `usertwo`={$_GET['id']}) OR (`userone`={$_GET['id']} AND `usertwo`={$_SESSION['user']['id']})");
    if (mysqli_num_rows($q) == 0){
        $data=array(
            array(
                "id"=>$_SESSION['user']['id'],
                "text"=>$_GET['text']
            )

        );

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        mysqli_query($connections, "INSERT INTO `messages`(`userone`, `usertwo`, `messages_json`) VALUES ({$_SESSION['user']['id']},{$_GET['id']},'{$json}')");
        echo("Creates new tree");
    }else{
        $messages = mysqli_fetch_assoc($q);
        $data = json_decode($messages['messages_json']);
        array_push($data, array("id"=>$_SESSION['user']['id'], "text"=>$_GET['text']));
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        mysqli_query($connections,"UPDATE `messages` SET `messages_json`='{$json}' WHERE `id`={$messages['id']}");        
        echo("Add in tree");

    }
}
if ($_GET['type']=="view"){
    $q = mysqli_query($connections, "SELECT * FROM `messages` WHERE (`userone`={$_SESSION['user']['id']} AND `usertwo`={$_GET['id']}) OR (`userone`={$_GET['id']} AND `usertwo`={$_SESSION['user']['id']})");
    if (mysqli_num_rows($q) != 0){
        $messages = mysqli_fetch_assoc($q);
        echo($messages['messages_json']);
    }
    
}




?>