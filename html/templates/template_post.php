<?php function draw_post_card($id, $author, $uni, $date, $hour, $title, $post_content, $likes, $dislikes){ 
    draw_post_popup($id, $author, $uni, $date, $hour, $title, $post_content, $likes, $dislikes);    
?>
<div class="card mb-3" style="max-width:70%;margin:5% 15%">
    <button type="button" id="postModal-<?=$id?>" class="btn btn-primary" data-toggle="modal" data-target="#popup-<?=$id?>" style="text-align:ledt;background: none; color: inherit; border: none; padding: 0; font: inherit; cursor: pointer; outline: inherit;"> 
        <div class="row no-gutters">
            <div class="col-sm">
                <div class="card text-center" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                    <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" alt="..." style="border-radius:50%; max-width:5rem; padding-top:0.8rem">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem"><?= $author ?>
                        </li>
                        <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem"><?= $uni ?></li>
                        <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">4 friends
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                <div class="card" style="height: 100%; margin-bottom: 0;">
                    <div class="card-body" style="margin-bottom: 0;padding-bottom: 0;">
                        <h3 class="card-title"> <?= $title ?></h3>
                        <p class="card-text">
                        <?= $post_content ?>
                        </p>
                        <p class="card-text" style="margin-bottom:0rem; float: right;"><small class="text-muted" style="margin-bottom:0rem"><?= $date ?></small>, <small class="text-muted" style="margin-bottom:0.2rem"><?= $hour ?></small></p>
                    </div>
                    <div class="card-footer" style="border-left:none;border-right:none;border-bottom:none">
                        <span class="comment"> 2 comments </span>
                        <div style="float: right;">
                            <span class="fa fa-thumbs-up" style="color:darkgreen;">&nbsp;<?=$likes?>&nbsp;</span>
                            <span class="fa fa-thumbs-down" style="color: darkred;">&nbsp;<?=$dislikes?>&nbsp;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </button>
</div>

<?php } 

function draw_create_post(){ ?>
<form method="post">
    <div class="container" style="max-width:70%;margin:5% 15%; border: 1px solid lightgrey; border-radius: .5em;">
        <button style="width: 100%; margin: 0; padding: 0; border: 0; border-radius: inherit">
            <div class="row"
                style="border-bottom: 1px solid lightgrey; background-color: white; border-top-left-radius: inherit; border-top-right-radius: inherit;">
                <div class="col-1">
                    <span class="fa fa-plus"></span>
                </div>
                <div class="col-11" style="border-left: 1px solid lightgrey;">
                    <p style="margin-bottom: 0;">Create Post</p>
                </div>
            </div>
        </button>
        <?php draw_create_post_input_fields(); ?>
    </div>
</form>
<?php
}

function draw_create_post_input_fields(){ ?>
<div class="row" style="padding:0; background-color: white; border: 0; border-bottom: 1px solid lightgrey;">
    <input type="text" required placeholder="&ensp;&nbsp;Title"
        style="width: 100%; margin: 0 auto; border: 0; border-radius: inherit"></input>
</div>
<div class="row" style="border-radius: 0;">
    <textarea class="form-control" required placeholder="Write here..." rows="3" style="border: 0; "></textarea>
</div>
<div class="row" style="background-color: white; border-top: 1px solid lightgrey; border-bottom-left-radius: .5em; border-bottom-right-radius: .5em;">
    <div class="col-11 row">
        <div class="col-3">
            <p class="fa fa-plus" style="margin-bottom: 0;">&ensp;image</p>
        </div>
        <div class="col-3">
            <p class="fa fa-plus" style="margin-bottom: 0;">&ensp;file</p>
        </div>
    </div>
    <div class="col-1" style="padding: 0">
        <button type="submit"
            style="width: 100%; background-color: white; border: 0; border-left: 1px solid lightgrey; border-bottom-right-radius: .5em;">&nbsp;Post</button>
    </div>
</div>
<?php
}

function draw_post_popup($id, $author, $uni, $date, $hour, $title, $post_content, $likes, $dislikes){ ?>
<div class="modal fade" id="popup-<?=$id?>" tabindex="-1" role="dialog" aria-labelledby="postModal-<?=$id?>"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="overflow: initial; max-width: 90%; width: 90%; max-height: 90%; height: 90%">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header">
                <div class="container" style="border-bottom:0;border-radius:0;max-width: 90%;">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:7rem; " onclick="window.location.href='./profile.php'"/>
                        </div>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-9" style="background-color: transparent;">
                                    <div class="row" style="background-color: transparent;">
                                        <h2 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem"><?= $author ?></h2>
                                    </div>
                                    <div class="row" style="background-color: transparent;">
                                        <h3 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem"><?= $uni ?></h3>
                                    </div>
                                </div>
                                <div class="col-sm-3" style="padding-top:0.2rem;padding-bottom:0.2rem; text-align: right; font-size: 1.25em;">
                                    <p class="card-text" style="margin-bottom:0rem"><?= $date ?></p>
                                    <p class="card-text"><?= $hour ?></p>
                                </div>
                            </div>
                            <div class="row justify-content-end" style="font-size: 1.2em;">
                                <span class="fa fa-thumbs-up" style="color:darkgreen;">&nbsp;<?=$likes?>&nbsp;</span>
                                <span class="fa fa-thumbs-down" style="color: darkred;">&nbsp;<?=$dislikes?>&nbsp;</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" data-dismiss="modal" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"><span class="fa fa-times"></span></button>
                    <button type="button" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"> <span class="fa fa-ellipsis-v" ></span></button>
                </div>
            </div>
            <div class="modal-body" style="overflow-y: auto;">
                <div class="container" style="border-bottom:0;border-top:0;border-radius:0;height:100%;">
                    <div class="row">
                        <h2><?= $title ?></h2>  
                    </div>
                    <div class="row">
                        <p> <?= $post_content ?></p>
                    </div>
                    <form method="post">
                        <div class="row" style="border: 1px solid lightgrey; margin: 0;">
                            <div class="col-2">
                                <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; ">
                            </div>
                            <div class="col-9">
                                <input type="text" required name="comment" placeholder="Comment..." style="max-width: 100%; width: 100%; max-height: 100%; height: 100%; border: 1px solid lightgrey; border-collapse: collapse; padding-left: .5em;" />
                            </div>
                            <div class="col-1" style="padding: 0">
                                <button type="submit" style="padding: 0; max-height: 100%; height: 100%; max-width: 100%; width: 100%; background-color: white; border: 0;"><span class="fa fa-caret-right" style="float: left; font-size: 1.5em;"></span></button>
                            </div>
                        </div>
                    </form>
                    <div style="border: 1px solid lightgrey;">
                        <?php draw_comment('0', "Joaquin", "Talvez alguém nos vá informar através de email? Mas eu suponho que vamos ter aulas até mais tarde.") ?>
                        <?php draw_comment('5em', "Tiago", "Sim, já contactei vários professores e todos disseram que vai sair um comunicado oficial da faculdade.") ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

function draw_comment($padding_left, $author, $comment){ ?>
<div class="row" style="padding-top: .5em; padding-bottom: .5em; padding-left: <?=$padding_left != 0 ? '5em' : 0 ?>; background-color: transparent;">
    <div class="col-sm-2" >
        <div class="row" >   
            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; "  onclick="window.location.href='./profile.php'">
        </div>
        <div class="row">
            <h4 style="font-size: 1em; margin: 0 auto;"><?= $author ?></h4>
        </div>
    </div>
    <div class="col-sm-9">
        <p style="max-width: 100%; width: 100%; padding-left: .5em; border: 1px solid lightgrey;"><?= $comment?></p>
    </div>
    <div class="col-sm-1" style="padding: 0">
        <button style="padding: 0; max-height: 100%; height: 100%; max-width: 100%; width: 100%; background-color: white; border: 0; background-color: transparent;"><span class="fa fa-ellipsis-v" style="float: left; margin-top: -1.5em;"></span></button>
    </div>
</div>
<?php
}
?>