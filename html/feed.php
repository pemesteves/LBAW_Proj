<?php 

include_once 'templates/template_common.php';
include_once 'templates/template_post.php';

draw_header();
draw_navbar();

?>
    <div class="container" style="margin:0; min-width:100%">
        <div class="row">
            <div class="col-sm-1" style="padding-left:0px">
                

                <div id="dl-menu" class="dl-menuwrapper">
                    <button id="dl-trigger" onclick="toggle()">Open Menu</button>
                    <ul id="dl-menu2" >
                        <li>
                            <h5 class="menu_title">Groups</h5>
                            <ul class="dl-submenu">
                                <li><a href="group.php"><small>Let's Tennis</small></a></li>
                                <li><a href="#"><small>Hicking</small></a></li>
                            </ul>
                        </li>
                        <li>
                            <h5 class="menu_title">Events</h5>
                            <ul class="dl-submenu">
                                <li><a href="event.php"><small>NI Competition</small></a></li>
                                <li><a href="#"><small>Running</small> </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <script> 
                    function toggle() { 
                        let opacity = document.querySelector('#dl-menu2').style.opacity; 
                        if(opacity == "1"){
                            document.querySelector('#dl-menu2').style.opacity=0;
                            document.querySelector('#dl-menu2').style.display="none";
                        }
                        else{
                            document.querySelector('#dl-menu2').style.opacity=1;
                            document.querySelector('#dl-menu2').style.display="block";
                        }
                    } 
                </script> 
            

            </div>
            <div class="col-sm-8" style="flex-grow:1;max-width:100%">

                <?php
                draw_create_post();
                draw_post_card(1);
                draw_post_card(2);
                ?>

            </div>
        </div>
    </div>

<?

draw_footer();
?>