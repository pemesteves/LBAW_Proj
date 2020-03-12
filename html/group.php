<?php 

include_once 'templates/template_common.php';
include_once 'templates/template_post.php';
include_once 'templates/template_group.php';

draw_header();
draw_navbar();

$name = "NIAEFEUP";
$members = "50";
draw_group_card($name, $members);
draw_create_post();


$author = "Matias";
$uni = "FEUP";
$date = "03-03-2020";
$hour = "10:28";
$title = "Jantar NIAEFEUP";
$post_content = "Caros colegas, marcamos um jantar para os membros do NIAEFEUP no dia 20 de Março de 2020, pelas 20:30h no restaurante X. O preço será de 10$. Os interessados que se inscrevam!
Cumprimentos, Matias.";
draw_post_card(10,$author, $uni, $date, $hour, $title, $post_content, 5, 0);

$author2 = "Bárbara";
$uni2 = "FEUP";
$date2 = "01-03-2020";
$hour2 = "21:22";
$title2 = "Nova App";
$post_content2 = "Depois de várias discussões entre a direção do NIAEFEUP, vamos elaborar uma nova App para facilitar a interatividade dos estudantes da FEUP com o Sigarra. Mais informações em breve.";
draw_post_card(11,$author2, $uni2, $date2, $hour2, $title2, $post_content2, 10, 1);


draw_footer();
?>