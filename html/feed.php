<?php 

include_once 'templates/template_common.php';
include_once 'templates/template_post.php';

draw_header();
draw_navbar();

?>
    <div class="container" style="margin:0; min-width:100%">
        <div class="row">
            <div class="col-sm-1">
                <!--
                <div class="card" style="width: 20rem; margin:1rem;border:none;">
                    <div class="card-header" style="background-color:rgba(244, 166, 98, 0.05);">
                        Groups
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color:rgba(244, 166, 98, 0.05);"><small>Let's Tennis</small></li>
                        <li class="list-group-item" style="background-color:rgba(244, 166, 98, 0.05);"><small>Hicking</small></li>
                    </ul>
                </div>

                <div class="card" style="width: 20rem; margin:1rem;border:none;margin-top:3rem">
                    <div class="card-header" style="background-color:rgba(244, 166, 98, 0.05);">
                        Events
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color:rgba(244, 166, 98, 0.05);"><small>NI Competition</small></li>
                        <li class="list-group-item" style="background-color:rgba(244, 166, 98, 0.05);"><small>Running</small></li>
                    </ul>
                </div>
                -->

                <div id="dl-menu" class="dl-menuwrapper">
                    <button id="dl-trigger" onclick="toggle()">Open Menu</button>
                    <ul id="dl-menu2" >
                        <li>
                            <h5 class="menu_title">Groups</h5>
                            <ul class="dl-submenu">
                                <li><a href="#"><small>Let's Tennis</small></a></li>
                                <li><a href="#"><small>Hicking</small></a></li>
                            </ul>
                        </li>
                        <li>
                            <h5 class="menu_title">Events</h5>
                            <ul class="dl-submenu">
                                <li><a href="#"><small>NI Competition</small></a></li>
                                <li><a href="#"><small>Running</small> </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <script> 
                    function toggle() { 
                        let opacity = document.querySelector('#dl-menu2').style.opacity; 
                        if(opacity == "1")
                            document.querySelector('#dl-menu2').style.opacity=0; 
                        else document.querySelector('#dl-menu2').style.opacity=1;
                    } 
                </script> 
            

            </div>
            <div class="col-sm-8" style="flex-grow:1;max-width:100%">

                <?php
                draw_post_card();
                draw_post_card();
                ?>

            </div>
        </div>
    </div>

<?

draw_footer();
?>