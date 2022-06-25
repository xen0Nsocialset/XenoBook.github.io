<?php include("include/config.php"); 

if (isset($_GET['exit'])){
    unset($_SESSION['user']);
 } 


if (!isset($_SESSION['user'])){
    header('Location: /auth.php');
    die;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XenoBook</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" defer></script>
    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
</head>

<body>

    <?php if (isset($_GET['exit'])){
       unset($_SESSION['user']);
    } 
    $check =  mysqli_query($connections, "SELECT * FROM `pushes` WHERE (`userid`={$_SESSION['user']['id']})");
    if (mysqli_num_rows($check) != 0){
        ?>
        <style>
        #pushb{
            box-shadow: 0px 0px 13px red;
            background: unset;
        }
        </style>
        <?php
    }
    
    ?>
    <div class="m-container">
        <div class="left-menu">
            <div style="margin-top:5vw" title="Дом"><a  href="?home="><img src="webicons\home.svg" alt=""></a>   </div>
            <div title="Новости"><a href="?news="><img src="webicons\news.svg" alt=""></a>   </div>
            <div title="Сообщения"><a href="?messages="><img src="webicons\message.svg" alt=""></a></div>
            <div title="Уведомления"><a  href="?pushs="><img id="pushb" src="webicons\pushs.svg" alt=""></a></div>
            <div title="Друзья"><a href="?friends="><img src="webicons\friends.svg" alt=""></a></div>
            <!-- <div><a href=""><img src="icons\group.svg" alt=""></a>  </div> -->
            <div title="Выйти"><a href="/?exit="><img src="webicons\turn-off.svg" alt=""></a>  </div>
        </div>
        <div class="right-main">
            <?php 
                if (!isset($_SESSION['user'])){
                    echo("<script>document.location.href  = '/auth.php'</script>");
                    die;
                }
                $q = mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".intval($_SESSION['user']['id']));
                if (mysqli_num_rows($q) == 0){
                    echo("<script>document.location.href  = '/auth.php'</script>");
                    die;
                }
                $_SESSION['user'] = mysqli_fetch_assoc($q);
                
                $sessionInfo = json_decode($_SESSION['user']['info_json']);
                if (isset($_GET['home'])){
                    include("home.php");
                }else
                if (isset($_GET['pushs'])){
                    include("pushs.php");
                }else
                if (isset($_GET['friends'])){
                    include("friendslist.php");
                }else
                if (isset($_GET['search'])){
                    include("search.php");
                }else
                if (isset($_GET['messages'])){
                    include("messages.php");
                }else
                if (isset($_GET['messages'])){
                    include("messages.php");
                }else if (isset($_GET['news'])){
                    include("news.php");
                }else{
                    ?>
                        <p style="color:gray; text-align:center; font-size:1.2vw;margin-top:20vh;">Приветствую всех новых пользователей. Слева вы можете видеть панель меню, а справа, та часть в которой текст - основная.<br>
                        Для того чтобы добавить друга, перейдите во вкладку <a href="?friends">"Друзья"</a> и нажмите на кнопку <a href="?search=">"Поиск"</a> в верхней правой части страницы.<br>
                        Для редактирования своей страницы, перейдите во вкладку <a href="?home=">"Дом"</a> и нажмите на карандаш в левом верхнем углу. Для создания поста, на той же странице есть форма.<br>
                         </p>
                    <?php
                }
            ?>
        </div>
    </div>
</body>

</html>