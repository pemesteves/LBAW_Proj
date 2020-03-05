<?php 

include_once 'templates/template_common.php';
include_once 'templates/template_post.php';
include_once 'templates/template_event.php';

draw_header();
draw_navbar();

draw_event_card();

draw_create_post();

draw_post_card();
draw_post_card();
draw_post_card();

draw_footer();
?>