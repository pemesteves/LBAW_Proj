<?php
function draw_chat_info($image_src, $image_alt, $name, $num_mess){
?>
    <article class="card-mb-3" style="border-color: sandybrown; border-width: 0; border-bottom-width: 0.1em; border-style: solid; width: 100%; height: 3em; padding: 0.25em">
        <div class="row">
            <div class="col-md-4">
                <img class="card-img" src="<?=$image_src?>" alt="<?=$image_alt?>" style="width:2.5em"/>
            </div>
            <div class="col-md-8">
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
            </div>
        </div>
    </article>
<?php
}

function draw_message($owner, $message){
?>
<p <?php if($owner) { ?> class="my_message" style="width:max-content; margin-left: auto; padding:0.5em; border-radius:1em; text-align:right; background-color:sandybrown; color: white; text-decoration:none"
    <?php } 
    else { ?> class="other_message" style="width:max-content; align-self:start; padding:0.5em; border-radius:1em; text-align:left; background-color:whitesmoke; color: black; text-decoration:none"
    <?php } ?> >
    <?=$message?>
</p>
<?php
}
?>