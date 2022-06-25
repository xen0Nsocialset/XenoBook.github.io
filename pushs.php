<div class="user-card-all">
        
        <h1>Список уведомлений:</h1><br>
        <?php 
        
        if (isset($_POST['tofriend'])){
            if (isset($_POST['add'])){
                $push = mysqli_fetch_assoc(mysqli_query($connections,"SELECT * FROM `pushes` WHERE `id`=".$_POST['tofriend']));

                $user = mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".$push['userid']));
                $info1 = json_decode($user['info_json']);
                if ($info1 = null) $info1 = array();
                array_push($info1->friends, $push['fromuser']);
                $json = json_encode($info1, JSON_UNESCAPED_UNICODE);

                mysqli_query($connections,"UPDATE `users` SET `info_json`='{$json}' WHERE `id`=".$push['userid']);




                $fromuser = mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".$push['fromuser']));
                $info = json_decode($fromuser['info_json']);
                if ($info = null) $info = array();
                array_push($info->friends, $push['userid']);
                $json1 = json_encode($info, JSON_UNESCAPED_UNICODE);

                mysqli_query($connections,"UPDATE `users` SET `info_json`='{$json1}' WHERE `id`=".$push['fromuser']);
                mysqli_query($connections, "DELETE FROM `pushes` WHERE `id`=".$push['id']);
                print_r(mysqli_error($connections));
            }
            if (isset($_POST['del'])){
                mysqli_query($connections, "DELETE FROM `pushes` WHERE `id`=".$_POST['tofriend']);
            }
        }
        
        $allpushs = mysqli_query($connections,"SELECT * FROM `pushes` WHERE `userid`=".$_SESSION['user']['id']." LIMIT 20");
        
        while ($item = mysqli_fetch_assoc($allpushs)) {
            ?>
                <div class="user-card-posts">
                    <p style="margin-top:1vw;"><?php 
                    
                    if ($item['type']=="tofriend"){
                        $fromuser = mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM `users` WHERE `id` = ". $item['fromuser']));
                        ?>
                        <p style="text-align:center;">Пользователь с ником <a href="\?home=&id=<?php echo $fromuser['id']; ?>"><?php echo $fromuser['name'] ?></a> хочет чтобы вы стали друзьями.</p>
                        <style>
                            button{
                                width:30%;
                                font-size:1.2vw;
                            }
                        </style>
                        <form action="#" method="post" style="display:flex;justify-content: space-around; margin-top:2vw;">
                            <input type="hidden" name="tofriend" value="<?php echo $item['id'];?>">
                            <button type="submit" name="add">Принять заявку</button>
                            <button type="submit" name="del">Отклонить</button>
                        </form>
                    <?php
                    }
                    ?>
                    </p>
                </div>
            <?php
        }
        ?>
    </div>