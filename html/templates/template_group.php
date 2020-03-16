<?php
function draw_group_card($name, $members){
?>
<div id="group_card" class="card mb-3 border rounded">
    <div class="row no-gutters">
        <div class="card text-center col-sm-3">
            <img src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png" class="card-img-top mx-auto d-block" alt="..." style="border-radius:50%; max-width:8rem">
        </div>
        <div class="card col-sm-9" >
            <div class="card-body">
                <h1 class="card-title"><?= $name ?></h1>
                <h2 class="card-subtitle"><?= $members?> members</h2>
                <p class="card-text"><span class="fa fa-history"></span>&nbsp;Updated 2 days ago</p>
            </div>
        </div>
    </div>
</div>
<?php
}
?>