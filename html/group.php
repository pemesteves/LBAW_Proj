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
draw_post_card($author, $uni, $date, $hour, $title, $post_content);


draw_footer();
?>