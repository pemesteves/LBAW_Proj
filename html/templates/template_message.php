<?php
function draw_chat_info($image_src, $image_alt, $name, $num_mess){
?>
<li>
    <article>
        <img src="<?=$image_src?>" alt="<?=$image_alt?>" />
        <h2><?=$name?></h2>
        <?php        
        if(isset($num_mess) && is_numeric($num_mess) && $num_mess > 0){
            if($num_mess > 9)
                $num_mess = "+9"; 
        ?>
        <p><?=$num_mess?></p>
        <?php
        }
        ?>
    </article>
</li>
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