<?php
function draw_chat_info($image_src, $image_alt, $name, $num_mess){
?>
    <article class="card-mb-3 chat_user_info_article" >
        <div class="row chat_user_info_article_div" style="margin-left:15px">
            
                <img class="card-img" src="<?=$image_src?>" alt="<?=$image_alt?>" style="width:2.5em;height:2.5em; border-radius:50%"/>
            
            
                <h2 class="card-title" style="margin-left:10px"><?=$name?>
                <?php        
                if(isset($num_mess) && is_numeric($num_mess) && $num_mess > 0){
                    if($num_mess > 9) $num_mess = "+9"; 
                ?>
                <span class="badge badge-primary badge-pill" style="font-size: 0.4em;"><?=$num_mess?></span>
                <?php
                }
                ?>
                </h2>
            
        </div>
    </article>
<?php
}

function draw_message($owner, $message){
?>
<p <?php if($owner) { ?> class="chat_my_message"
    <?php } 
    else { ?> class="chat_other_message"
    <?php } ?> >
    <?=$message?>
</p>
<?php
}
?>