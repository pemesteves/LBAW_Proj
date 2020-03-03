<?php
function draw_chat_info($image_src, $image_alt, $name, $num_mess){
?>
    <article class="row card">
        <img class="card-img-top" src="<?=$image_src?>" alt="<?=$image_alt?>" style="width:2em"/>
        <h2 class="card-title"><?=$name?>
        <?php        
        if(isset($num_mess) && is_numeric($num_mess) && $num_mess > 0){
            if($num_mess > 9)
                $num_mess = "+9"; 
        ?>
        <span class="badge badge-primary badge-pill" style="font-size: 0.4em;"><?=$num_mess?></span>
        <?php
        }
        ?>
        </h2>
    </article>
<?php
}

function draw_message($owner, $message){
?>
<li class="<?=$owner ? "my_message" : "other_message"?>">
    <p><?=$message?></p>
</li>
<?php
}
?>