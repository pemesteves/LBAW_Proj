<?php 

include_once 'templates/template_common.php';
include_once 'templates/template_post.php';
include_once 'templates/template_event.php';

draw_header();
draw_navbar();

$title = "FEUPCaffé 12/3";
$description = "Mais uma noite incrível proporcionada pela tua AE!";
$date = "12-03-2020";
$address = "R. Dr. Júlio de Matos 882, 4200-365 Porto";
draw_event_card($title, $description, $date, $address);

draw_create_post();

draw_post_card();
draw_post_card();
draw_post_card();

draw_footer();
?>