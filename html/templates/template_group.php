<?php
function draw_group_card($name, $members){
?>
<div class="card mb-3 border rounded" style="min-width: 540px;max-width:80%;margin:5% auto">
    <div class="row no-gutters">
        <div class="card text-center col-md-3" style="border:none;border-radius:0;height:100%;">
            <img src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png" class="card-img-top mx-auto d-block" alt="..." style="border-radius:50%; max-width:8rem">
        </div>
        <div class="card col-md-9" style="height: 100%; border: none">
            <div class="card-body">
                <h1 class="card-title"><?= $name ?></h1>
                <h2 class="card-subtitle" style="font-size:1.5em;"><?= $members?> members</h2>
                <p class="card-text" style="text-align: right;">Updated 2 days ago</p>
            </div>
        </div>
    </div>
</div>
<?php
}
?>