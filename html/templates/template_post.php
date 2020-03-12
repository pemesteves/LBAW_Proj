<?php function draw_post_card($id, $author, $uni, $date, $hour, $title, $post_content){ 
    draw_post_popup($id, $author, $uni, $date, $hour, $title, $post_content);    
?>
<div class="card mb-3" style="max-width:70%;margin:5% 15%">
    <button type="button" id="postModal-<?=$id?>" class="btn btn-primary" data-toggle="modal" data-target="#popup-<?=$id?>" style="text-align:left;background: none; color: inherit; border: none; padding: 0; font: inherit; cursor: pointer; outline: inherit;"> 
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
                    <div class="card-body" style="padding-top:0.2rem;padding-bottom:0.2rem">
                        <p class="card-text" style="margin-bottom:0rem"><small class="text-muted" style="margin-bottom:0rem"><?= $date ?></small></p>
                        <p class="card-text"><small class="text-muted" style="margin-bottom:0.2rem"><?= $hour ?></small></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8" style="flex-grow:1; max-width:100%">
                <div class="card" style="height: 100%">
                    <div class="card-body">
                        <h3 class="card-title"> <?= $title ?></h3>
                        <p class="card-text">
                        <?= $post_content ?>
                        </p>
                    </div>
                    <div class="card-footer" style="border-left:none;border-right:none;border-bottom:none">
                        <span class="comment"> 2 comments </span>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </button>
</div>

<?php } 

function draw_create_post(){ ?>
    <form id="post_form" method="post">
        <div class="container" id="post_container">
            <input id="post_title" type="text" required="required" placeholder="  Title"/>
            <textarea id="post_text" class="form-control" required placeholder="Write here..." rows="3"></textarea>
            <div id="post_form_lower">
                <div id="post_upload">
                    <div>
                        <p class="fa fa-plus">&ensp;image</p>
                        <input type="file"/>
                    </div>
                   <div>
                        <p class="fa fa-plus">&ensp;file</p>
                        <input type="file"/>
                   </div>  
                </div>
                <div>
                    <button id="post_form_post" type="submit">Post</button> 
                </div>
            </div>
        </div>
    </form>
<?php
}

function draw_create_post_input_fields(){ ?>
    
    <div class="row" style="padding:0; background-color: white; border: 0; border-bottom: 1px solid lightgrey;">
        <input type="text" required placeholder="&ensp;&nbsp;Title"
            style="width: 100%; margin: 0 auto; border: 0; border-radius: inherit"/>
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

function draw_post_popup($id, $author, $uni, $date, $hour, $title, $post_content){ ?>
<div class="modal fade" id="popup-<?=$id?>" tabindex="-1" role="dialog" aria-labelledby="postModal-<?=$id?>"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="overflow: initial; max-width: 90%; width: 90%; max-height: 90%; height: 90%">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header post_header" >
                <div class="container" style="border-bottom:0;border-radius:0;max-width: 90%;">
                    <div class="row">
                        <div class="col-2">
                            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:7rem; " onclick="window.location.href='./profile.php'"/>
                        </div>
                        <div class="col-7" style="background-color: transparent;">
                            <div class="row" style="background-color: transparent;">
                                <h2 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem"><?= $author ?></h2>
                            </div>
                            <div class="row" style="background-color: transparent;">
                                <h3 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem"><?= $uni ?></h3>
                            </div>
                        </div>
                        <div class="col-2" style="padding-top:0.2rem;padding-bottom:0.2rem; text-align: right;">
                            <p class="card-text" style="margin-bottom:0rem"><?= $date ?></p>
                            <p class="card-text"><?= $hour ?></p>
                        </div>
                        <div class="col-1" style="margin-right: 0; padding-right: 0;">
                            <button type="button" data-dismiss="modal" style="margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"><span class="fa fa-times"></span></button>
                            <button type="button" style="margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"> <span class="fa fa-ellipsis-v" ></span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body post_container" style="overflow-y: auto;">
                <div class="container" style="border-bottom:0;border-top:0;border-radius:0;height:100%;">
                    <div class="row">
                        <h2><?= $title ?></h2>  
                    </div>
                    <div class="row">
                        <p> <?= $post_content ?></p>
                    </div>
                    <form method="post">
                        <div class="row post_comment_form" style="border: 1px solid lightgrey; border-collapse: collapse;">
                            <div class="col-2">
                                <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; ">
                            </div>
                            <div class="col-9 post_comment_form_text">
                                <textarea class="form-control" required placeholder="Comment..." rows="1"></textarea>
                            </div>
                            <div class="col-1" style="padding: 0">
                                <button type="submit" style="padding: 0; max-height: 100%; height: 100%; max-width: 100%; width: 100%; background-color: white; border: 0;"><span class="fa fa-caret-right" style="float: left; font-size: 1.5em;margin-left: 0.75em;"></span></button>
                            </div>
                        </div>
                    </form>
                    <?php draw_comment('0', "Joaquin", "Talvez alguém nos vá informar através de email? Mas eu suponho que vamos ter aulas até mais tarde.") ?>
                    <?php draw_comment('5em', "Tiago", "Sim, já contactei vários professores e todos disseram que vai sair um comunicado oficial da faculdade.aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa aaaaaaaaaaaaaaaaaaaaaaaaaaa aaaaaaaaaaaaaaaaaaaa") ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

function draw_comment($padding_left, $author, $comment){ ?>
<div class="row comment_container <?=$padding_left != 0 ? 'comment_padding' : 'comment_no_padding' ?>">
    <div class="col-2 comment_user_info" >
        <div class="row">   
            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; "  onclick="window.location.href='./profile.php'">
        </div>
        <div class="row">
            <h4 style="font-size: 1em; margin: 0 auto;"><?= $author ?></h4>
        </div>
    </div>
    <div class="col-9 comment_text">
        <p><?= $comment?></p>
    </div>
    <div class="col-1 comment_opt" style="padding: 0">
        <button><span class="fa fa-ellipsis-v"></span></button>
    </div>
</div>
<?php
}
?>