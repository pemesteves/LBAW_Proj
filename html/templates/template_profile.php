<?php
    include_once("template_common.php");
    include_once("template_post.php");
?>

<?php function draw_profile()
{
    /**
     * Draws the profile page
     */?>
    <body>
    <br>
    <div class="container text-center d-flex justify-content-center" style="padding-top: 1em; padding-bottom: 1em">
    <div class="card flex-row flex-wrap border-0" style="height:25rem; width:60rem; border: none; padding:0 margin-right: 0">
        <div class="card-block " style="background: none; color: inherit; border: none; height:100%">
            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" alt="" class="rounded-circle" style="border-radius:50%; max-width:20rem;">
        </div>
        <div class="card-block px-2" >
            <h1 class="display-1" style="border:none;padding-top:0.2rem;padding-bottom:1rem;padding-left:5rem;padding-right:0rem">John</h1>
            <h2 class="card-text" style="border:none;padding-top:0.2rem;padding-bottom:1rem;padding-left:1.5rem;padding-right:0rem">University</h2>
            <h2 class="card-text" style="border:none;padding-top:0.2rem;padding-bottom:1rem;">Student</h2>

        </div>
    </div>
    <br>
    </div>
    <div class="container text-center">
    <div class="btn-group" >
        <button class="btn btn-secondary btn-lg dropdown-toggle dropdown-menu-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#000000; background-color:#FFFFFF; width:60rem; padding:1rem">
        Personal Information
        </button>
        <div class="dropdown-menu">
        </div>

    </div>
    <br>

    <div class="container text-center">
    <div class="btn-group" >
        <button class="btn btn-secondary btn-lg dropdown-toggle dropdown-menu-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#000000; background-color:#FFFFFF;  width:60rem; padding:1rem">
        Groups
        </button>
        <div class="dropdown-menu">
        </div>

    </div>

    <br>

    <div class="container text-center">
    <div class="btn-group" >
        <button class="btn btn-secondary btn-lg dropdown-toggle dropdown-menu-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#000000; background-color:#FFFFFF; width:60rem; padding:1rem">
        Friends
        </button>
        <div class="dropdown-menu">
        </div>

    </div>

    </div>

    <br>

    <?php

        $author = "John";
        $uni = "FEUP";
        $date = "07-03-2020";
        $hour = "14:30";
        $title = "Impressão na FEUP";
        $post_content = "Hoje de manhã estava na faculdade a tentar imprimir os conteúdos de PPIN e reparei que a impressora não reconhecia a minha pen. 
        Depois tentei enviar pelo FEUP WebPrint e também não recebeu nenhum ficheiro.
        Estamos sem sistema de impressão na faculdade?";

        draw_post_card(0, $author, $uni, $date, $hour, $title, $post_content);

    ?>    

</body>

<?php } ?>