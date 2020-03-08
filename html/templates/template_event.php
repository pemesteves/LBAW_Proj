<?php
function draw_event_card($title, $description, $date, $address){
?>
<div class="card mb-3 border rounded" style="min-width: 540px;max-width:80%;margin:5% auto">
    <img src="images/aefeup.jpg" class="card-img-top mx-auto d-block" alt="..." style="border: 1px solid lightgrey; border-collapse: collapse; object-fit: cover; object-position: center; width: 100%; height: 25vh;">
    <div class="card" style="height: 100%; border: none">
        <div class="card-body" style="border: 1px solid lightgrey; border-collapse: collapse;" >
            <h1 class="card-title uconnect-title" ><?= $title ?></h1>
            <p class="card-text uconnect-paragraph" ><?= $description ?></p>
        </div>
        <div class="card-footer container no-gutters" style="border: 1px solid lightgrey; border-collapse: collapse; width: 100%; max-width: 100%;">            
            <div class="row">
                <div class="col-sm">
                    <span class="fa fa-calendar"></span> <?= $date ?>
                </div>
                <div class="col-sm" style="text-align: right;">
                    <span class="fa fa-user"></span>&nbsp;10 going
                </div>
            </div>    
            <div class="row">
                <div class="col">
                    <span class="fa fa-map-pin"></span>&nbsp;&nbsp;<?= $address ?>
                </div>
                <div class="col" style="text-align: right;">
                    <span class="fa fa-history"></span>&nbsp;Updated 1 hour ago
                </div>
            </div>   
        </div>
    </div>
</div>
<?php
}
?>