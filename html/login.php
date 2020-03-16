<?php 
include_once("templates/template_authentication.php");

draw_header();

?>
        <nav class="navbar navbar-dark" style="background-color: sandybrown;"> <!-- #ffa31a -->
            <a class="navbar-brand" href="./index.php">
                <h1 style="color: whitesmoke;">UConnect <span class="fa fa-graduation-cap"></span></h1>
                </a> <!-- whitesmoke -->
        </nav>
<?php

draw_login();

draw_footer();
?>