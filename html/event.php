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


$author = "Matias";
$uni = "FEUP";
$date = "03-03-2020";
$hour = "10:28";
$title = "Jantar NIAEFEUP";
$post_content = "Caros colegas, marcamos um jantar para os membros do NIAEFEUP no dia 20 de Março de 2020, pelas 20:30h no restaurante X. O preço será de 10$. Os interessados que se inscrevam!
Cumprimentos, Matias.";
draw_post_card(10,$author, $uni, $date, $hour, $title, $post_content);

$author2 = "Bárbara";
$uni2 = "FEUP";
$date2 = "01-03-2020";
$hour2 = "21:22";
$title2 = "Nova App";
$post_content2 = "Depois de várias discussões entre a direção do NIAEFEUP, vamos elaborar uma nova App para facilitar a interatividade dos estudantes da FEUP com o Sigarra. Mais informações em breve.";
draw_post_card(11,$author2, $uni2, $date2, $hour2, $title2, $post_content2);


draw_footer();
?>