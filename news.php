<?php
$str = "";
for ($i=0; $i < count($sessionInfo->friends); $i++) { 
    if ($i == count($sessionInfo->friends)-1){
        $str .= ("`userid`=".$sessionInfo->friends[$i]." OR `userid`=" . $_SESSION['user']['id']);
        $str .= (" OR `fromuser`=".$sessionInfo->friends[$i]." OR `fromuser`=" . $_SESSION['user']['id']);
    }else{
        $str .= ("`userid`=".$sessionInfo->friends[$i] . " OR ");
        $str .= ("`fromuser`=".$sessionInfo->friends[$i] . " OR ");
    }
}
if (count($sessionInfo->friends) == 0){
    $str .= ("`userid`=" . $_SESSION['user']['id']);
    $str .= (" OR `fromuser`=" . $_SESSION['user']['id']);
}
$query =  mysqli_query($connections, "SELECT * FROM `posts` WHERE ".$str." ORDER BY `id` DESC LIMIT 30");
?>

<div class="user-card-all">
<h1>Новости:</h1>
<?php
while ($item = mysqli_fetch_assoc($query)){
    

        $fromuser = mysqli_fetch_assoc(mysqli_query($connections,"SELECT * FROM `users` WHERE `id`=".$item['fromuser']));
        $user = mysqli_fetch_assoc(mysqli_query($connections,"SELECT * FROM `users` WHERE `id`=".$item['userid']));
        $images = json_decode($item['images_json']);
        $formuserinfo = json_decode($fromuser['info_json']);
    ?>

    <div class="user-card-posts">
        <div class="user-card-posts-creator">
        <div id="avatar" style="background-image:url(<?php echo($formuserinfo->avatar);?>); background-repeat: no-repeat;background-size: cover;"></div>
            <h2 id="username" class="link" style="cursor:pointer;"><a href="?home=&id=<?php echo $fromuser['id']; ?>" target="_blank"> <?php echo($fromuser['name']); ?></a></h2>&nbsp;
            <h2 id="userpage" class="link" style="cursor:pointer;"><a href="?home=&id=<?php echo $user['id']; ?>" target="_blank"><?php if ($user['id'] != $_SESSION['user']['id']){echo("на странице ".$user['name']);}?></a></h2>
        </div>
        <p id="message" style="margin-top:1vw;"><?php echo($item['message']); ?></p>



        <?php if (count($images) == 0){?> <p id="date">
        <?php
            $timestamp = strtotime($item['date']);
            echo date('H:i:s d/m/Y', $timestamp);echo('</div>'); ?>
        </p> 
        
        <?php   continue;}
        ?>
        <div class="postImages">
            <?php 
                for ($j=0; $j < count($images); $j++) { ?>
                    <div style="height:10vw; overflow:hidden;width:<?php echo (100/count($images));?>%;"><a style="width:100%;" href="<?php echo($images[$j]);?>" target_="_blank"> <img style="width:100%; cursor:pointer;" src="<?php echo($images[$j]);?>"></a></div>
                
                <?php }
            ?>
        </div>
        <p id="date"><?php
            $timestamp = strtotime($item['date']);
            echo date('H:i:s d/m/Y', $timestamp); ?>
        </p>
    </div>

    <?php
}
?>
<script>
var load = 30;
var count = 30;

function get() {
    $.getJSON("get_news.php?offcet="+load+"&count="+count, function(data) {
        if (data != ""){
            load+=count;
        }
        console.log(load);
        data.forEach(element => {
            var clone = $("#card").clone(true);
            clone.appendTo(".user-card-all");
            console.log(element.fromuser.info_json);
            clone.find("#avatar").css("background-image", "url("+JSON.parse(element.fromuser.info_json).avatar+")");

            clone.css("display","block");
            
            var name = clone.find('#username');
            name.html(element.fromuser.name);
            name.find('a').attr("href","?home=&id="+element.fromuser.id);

            if (element.fromuser.id == element.user.id){
                clone.find("#userpage").hide();
            }else{
                var second = clone.find("#userpage");
                second.find('a').attr("href","?home=&id="+element.user.id);   
                second.html("на странице " + element.user.name);
            }
            clone.find(message).html(element.item.message);
            
            var images = JSON.parse(element.item.images_json);
            var count = 0;
            images.forEach(element => {
                count++;
            });
            console.log(count);
            var postimages = clone.find(".postImages");
            for (let i = 0; i < count; i++) {
                var imageclone = postimages.find("#image-holder").clone(true);
                imageclone.appendTo(postimages);
                imageclone.find('a').find("img").attr("src",images[i]);
                imageclone.css("width", (100.0/count)+"%");
                imageclone.css("display","block");
                imageclone.find('a').attr("href",images[i]);

                imageclone.attr("id","");
            }
            if (count == 0){
                postimages.css("display","none");
            }
            var date = clone.find("#date");
            date.html(element.item.date);
        });
    });
}
</script>

    <div class="user-card-posts" id="card" style="display:none">
        <div class="user-card-posts-creator">
            <div id="avatar" style="background-image:url(); background-repeat: no-repeat;background-size: cover;"></div>
            <h2 id="username" class="link" style="cursor:pointer;"><a href="?home=&id=15" target="_blank"> User</a></h2>&nbsp;
            <h2 id="userpage" class="link" style="cursor:pointer;"><a href="?home=&id=16" target="_blank"> на странице d</a></h2>
        </div>
        <p id="message" style="margin-top:1vw;">text</p>

        <div class="postImages">
            <div id="image-holder" style="height:10vw; overflow:hidden;width:0; display:none;"> 
                <a style="width:100%;" href="" target_="_blank">
                    <img style="width:100%; cursor:pointer;" src="">
                </a>
            </div>
        </div>
        <p id="date"></p>
        </div>

</div>
<button style="margin-top: 2vw;width:96%; margin-left:2%; font-size:1.2vw;" onclick="get()">Загрузить ещё</button>