<?php 

include_once 'templates/template_common.php';
include_once 'templates/template_post.php';
include_once 'templates/template_group.php';

draw_header();
draw_navbar();
?>
<div id="about_container" class="container">
    <div class="row">
        <h2>About UConnect</h2>
    </div>
    <div class="row">
        <div class="col-sm-7">
            <div class="row">
                <p>UConnect is a project aiming to create an academic social network allowing efficient communication and connectivity between all parts of the university context.</p> 
                <p>Its implementation creates a unique platform in which every activity related to studying is included.</p>
            </div>
        </div>
        <div class="col-sm-5">
            <img src="./images/connecting.jpg" alt="Connecting"/>
        </div>
    </div>
    <div class="row">
        <h2>Our Team</h2>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="card">
                <img class="card-img-top" src="./images/lbaw2034_gt.jpg" alt="Joaquim Rodrigues"/>
                <div class="card-body"> 
                    <h3 class="card-title">Gustavo Torres</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <img class="card-img-top" src="./images/lbaw2034_jr.jpg" alt="Joaquim Rodrigues"/>
                <div class="card-body"> 
                    <h3 class="card-title">Joaquim Rodrigues</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <img class="card-img-top" src="./images/lbaw2034_pe.jpg" alt="Joaquim Rodrigues"/>
                <div class="card-body"> 
                    <h3 class="card-title">Pedro Esteves</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <img class="card-img-top" src="./images/lbaw2034_vv.jpg" alt="Joaquim Rodrigues"/>
                <div class="card-body"> 
                    <h3 class="card-title">Vitor Ventuzelos</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
draw_footer();
?>