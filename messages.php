<script>



var myInterval = null; 
var userid = -1;
var username = "";
var myname = "<?php echo $_SESSION['user']['name']; ?>";
var myid = <?php echo $_SESSION['user']['id']; ?>;
    var recive = false;
function startLoop(id, name) {
    
    if (recive == true) return true;

    $('.mess-gen').remove();
    userid=id;
    username=name;
    clearInterval(myInterval);
    $(".user-input").css("display", "flex");
    var main = $("#m-main");
    var objDiv = $(".messages");
    var last = 0;
    var i = 0;
    
    myInterval = setInterval(function() {
        userid=id;
        username=name;
        $.getJSON("messageworks.php?id="+userid+"&type=view", function(data) {
            i = 0;
            $('.mess-gen').remove();
            console.log(data);
            data.forEach(element => {
                if (i > 50) return false;
                var clone = main.clone(true);
                clone.appendTo(".messages");
                var str = "";
                if (element.id == userid){
                    str += username + ":<br>";
                }else{
                    str += myname + ":<br>";
                    clone.css("margin-left","36%");
                }

                clone.find("p").html(str+(element.text));
                clone.css("display","block");
                clone.attr("class","mess-gen");
                
                i++;
            });
        });
        if (last != i){
            var div = document.getElementById("idmess");
            div.scrollTop = div.scrollHeight - div.clientHeight;
            last = i;
        }
    }, 1000);
    
    var div = document.getElementById("idmess");
            div.scrollTop = div.scrollHeight - div.clientHeight;
}

function send() {
    console.log("messageworks.php?id="+userid+"&text="+$("#mess").val().replace(/\r\n|\r|\n/g,"<br />")+"&type=send");
    $.getJSON("messageworks.php?id="+userid+"&text="+$("#mess").val().replace(/\r\n|\r|\n/g,"<br />")+"&type=send", function(data) {});
    $("#mess").val("");
    var div = document.getElementById("idmess");
    div.scrollTop = div.scrollHeight - div.clientHeight;
}

</script>



<div class="messages-main">
    <div class="m-left">
    <?php
        $allfriends = $sessionInfo->friends;
        for ($i=0; $i < count($allfriends); $i++) { 
            if ($allfriends != ''){
                $user = mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM `users` WHERE `id`=".$allfriends[$i]));
                ?>
                    <div class="messages-friend" onclick="startLoop(<?php echo $user['id'].',\''.$user['name'].'\''; ?>)">
                        <div style="background-image:url(<?php echo(json_decode($user['info_json'])->avatar);?>);width:3vw; height:3vw; background-repeat: no-repeat;background-size: contain;"></div>
                        <h2><?php echo($user['name']); ?></h2>
                    </div>
                <?php
            }
        }
    ?>
    </div>
    <div class="m-right">
        <div class="messages" id="idmess">
            <div class="message" id="m-main" style="display:none; margin-left:0%">
                <p>ebal1</p>
            </div>
        </div>
        <div style="margin-left:4%; width:92%;">
        <p id="sobesed" style="float:left"></p>
        <p id="your" style="float:right"></p>
        </div>
        <div class="user-input" style="display:none">
            <textarea name="message" id="mess" cols="30" rows="10"></textarea>
            <img draggable="false" style="width:5%;cursor:pointer;" src="webicons/send.svg" alt="" onclick="send()">
        </div>
    </div>
</div>