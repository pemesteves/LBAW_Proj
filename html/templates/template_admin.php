<?php

    function draw_report(){
        ?>

        <div class="card" style="margin:2% 15%; padding-bottom:0rem">
            <div class="card-body">
                <div style="display:flex;justify-content:space-between">
                    <h5 class="card-title">Report title</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Post | 20-03-2020</h6>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <h6 class="card-text">Issue : Improper content</h6>
                    <div>
                        <button type="button" class="btn btn-success">Accept</button>
                        <button type="button" class="btn btn-danger" style="margin-top:0.2rem">Ignore</button>
                    </div>
                </div>

            </div>
        </div>

        <?php
    }


?>