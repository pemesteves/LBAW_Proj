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
                    <ul id="dl-menu2">
                        <li>
                            <h5 class="menu_title">Groups</h5>
                            <ul class="dl-submenu">
                                <li><a href="group.php"><small>NIAEFEUP</small></a></li>
                                <li><a href="#"><small>AEFEUP</small></a></li>
                            </ul>
                        </li>
                        <li>
                            <h5 class="menu_title">Events</h5>
                            <ul class="dl-submenu">
                                <li><a href="event.php"><small>FEUPCaffe 12/3</small></a></li>
                                <li><a href="#"><small>Jantar NIAEFEUP</small> </a></li>
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
                $author = "Peter";
                $uni = "FEUP";
                $date = "07-03-2020";
                $hour = "14:30";
                $title = "Impressão na FEUP";
                $post_content = "Hoje de manhã estava na faculdade a tentar imprimir os conteúdos de PPIN e reparei que a impressora não reconhecia a minha pen. 
                Depois tentei enviar pelo FEUP WebPrint e também não recebeu nenhum ficheiro.
                Estamos sem sistema de impressão na faculdade?";

                $author2 = "Carlota";
                $uni2 ="FFUP";
                $hour2 = "21:14";
                $date2 = "07-03-2020";
                $title2 = "Faculdade fechada";
                $post_content2 = "Depois destas notícias de terem fechado a nossa faculdade e a do ICBAS, alguém sabe como se vai desenrolar o resto do semestre? Honestamente, estou muito confusa.";

                draw_post_card(0, $author2, $uni2, $date2, $hour2, $title2, $post_content2, 1, 10);
                draw_post_card(1, $author, $uni, $date, $hour, $title, $post_content, 5, 5);

                ?>

            </div>
        </div>
    </div>

<?php

draw_footer();
?>