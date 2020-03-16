<?php
function draw_event_card($title, $description, $date, $address){
?>
<div id="event_card" class="card mb-3 border rounded">
    <img src="images/aefeup.jpg" class="card-img-top mx-auto d-block" alt="...">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title uconnect-title" ><?= $title ?></h1>
            <p class="card-text uconnect-paragraph" ><?= $description ?></p>
        </div>
        <div class="card-footer container no-gutters">            
            <div class="row">
                <div class="col-sm-6">
                    <span class="fa fa-calendar"></span> <?= $date ?>
                </div>
                <div class="col-sm-6">
                    <span class="fa fa-user"></span>&nbsp;10 going
                </div>
            </div>    
            <div class="row" >
                <div class="col-sm-6">
                    <span class="fa fa-map-pin"></span>&nbsp;&nbsp;<?= $address ?>
                </div>
                <div class="col-sm-6">
                    <span class="fa fa-history"></span>&nbsp;Updated 1 hour ago
                </div>
            </div>   
        </div>
    </div>
</div>
<?php
}
?>