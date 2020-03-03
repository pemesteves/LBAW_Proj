<?php 

include_once 'templates/template_common.php';
include_once 'templates/template_post.php';
include_once 'templates/template_group.php';

draw_header();
draw_navbar();

draw_group_card();

draw_post_card();
draw_post_card();
draw_post_card();

draw_footer();
?>