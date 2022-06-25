<div class="user-card-all" style="display:flex; flex-wrap:wrap;">
        
        <h1 style="width: 50%;">Список друзей:</h1> <h1 style="float:right; width:50%; text-align:right; cursor:pointer;"><a href="?search=">Поиск</a></h1><br>
        <?php 
        

        if (isset($_POST['deleteGo'])){
            unset($sessionInfo->friends[intval($_POST['userid'])]);

            $user = mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".$_POST['id']));
            $info = json_decode($user['info_json']);
            for ($i=0; $i < count($info->friends); $i++) { 
                if ($info->friends[$i] == strval($_SESSION['user']['id'])){
                    unset($info->friends[$i]);
                    break;
                }
            }
            $info_json = json_encode($info, JSON_UNESCAPED_UNICODE);
            mysqli_query($connections, "UPDATE `users` SET `info_json` = '{$info_json}' WHERE `id`=".(int)$user['id']);
            
            
            $infous_json = json_encode($sessionInfo, JSON_UNESCAPED_UNICODE);
            mysqli_query($connections, "UPDATE `users` SET `info_json` = '{$infous_json}' WHERE `id`=".(int)$_SESSION['user']['id']);
            echo("<script>document.location.href  = '/?friends='</script>");
        }

        $allfriends = $sessionInfo->friends;
        for ($i=0; $i < count($allfriends); $i++) { 
            if ($allfriends != ''){
                $user = mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".$allfriends[$i]));
                ?>
                <div class="user-card-posts" style="display:flex; width:30%; margin-right:0.8%; flex-wrap:wrap;">
                    <div style="background-image:url(<?php echo(json_decode($user['info_json'])->avatar) ?>);width:10vw; height:10vw; background-repeat: no-repeat;background-size: contain;">
                    </div>
                    <div style="margin-left:2%; width:60%;">
                    <h1 class="link" style="cursor:pointer;"><a href="?home=&id=<?php echo $user['id']; ?>"><?php echo($user['name']) ?></a></h1>
                    </div>
                    <form action="" method="post" style="width:100%; margin-top:1vw;">
                        <input type="hidden" name="userid" value="<?php echo $i; ?>">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <button type="submit" style="width:100%; font-size:1vw;" name="deleteGo">Удалить</button>
                    </form>
                </div>
                
                <?php
            }
        }
        ?>
    </div>
    <?php
        
        if (count($allfriends) == 0){
            ?>
                <br><h2 style="text-align:center;">У вас нет друзей :(</h2>
            <?php
        }
    ?>