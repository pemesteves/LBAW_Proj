<?php
    include_once("template_common.php");
    include_once("template_post.php");
?>

<?php function draw_profile()
{
    /**
     * Draws the profile page
     */?>
    <br>
    <div id="profile_card" class="container" style="padding-top: 1em; margin-bottom: 0; background-color: white; border: 1px solid lightgrey;">
        <div class="row">
            <div class="col-4">
                <div class="text-center" style="max-width: 75%; max-height: 80%;">
                    <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" alt="" class="rounded-circle" style="max-width:100%; max-height: 50%;"/>
                </div>
            </div>
            <div class="col-8" style="padding: 0.2rem 1rem 0 0.2rem;">
                <div class="row">
                    <h1 style="border: 0; font-size: 5rem;">John</h1>
                </div>
                <div class="row">
                    <h2 style="border: 0; padding: 0">FEUP</h2>
                </div>
                <div class="row">
                    <h2 style="border: 0; padding: 0;">Student</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="accordion" style="width: 100%;">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Personal Information
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            John, byname John Lackland, French Jean sans Terre, (born c. 1166—died October 18/19, 1216, Newark, Nottinghamshire, England), king of England from 1199 to 1216.
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Groups
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>AEFEUP</li>
                                <li>NIAEFEUP</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Friends
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <li>Joanna</li>
                                <li>Joaquin</li>
                                <li>Mary</li>
                                <li class="more">...</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

        $author = "John";
        $uni = "FEUP";
        $date = "07-03-2020";
        $hour = "14:30";
        $title = "Impressão na FEUP";
        $post_content = "Hoje de manhã estava na faculdade a tentar imprimir os conteúdos de PPIN e reparei que a impressora não reconhecia a minha pen. 
        Depois tentei enviar pelo FEUP WebPrint e também não recebeu nenhum ficheiro.
        Estamos sem sistema de impressão na faculdade?";

        draw_post_card(0, $author, $uni, $date, $hour, $title, $post_content, 1, 10);

    ?>

</body>

<?php } ?>