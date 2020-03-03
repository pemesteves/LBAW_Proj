<?php
    include_once "templates/template_common.php";
    include_once "templates/template_message.php";

    draw_header();
    draw_navbar();
?>
<section id="chats">
    <header id="search_chat">
        <form class="form-inline" method="post">
            <input class="form-control mr-sm-2" type="search" placeholder="Search messages" aria-label="Search">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
        </form>
    </header>
    <ul>
        <?php 
        draw_chat_info("images/placeholder.png", "", "Joaquin", 1);
        draw_chat_info("images/placeholder.png", "", "Joanna", 10);
        draw_chat_info("images/placeholder.png", "", "Mary", 0);
        ?>
    </ul>
    <footer id="create_chat">
        <button type="button">
            <p id="create_group_icon">&#10133;</p>
            <p id="create_group_message">Create Group Chat</p>
        </button>
    </footer>
</section>
<section id="opened_message">
    <header id="chat_info">
        <img src="images/placeholder.png" alt="" />
        <h2></h2>
    </header>

    <ul>
        <?php 
        draw_message(false, "Quisque cursus risus augue, nec.");
        draw_message(true, "Aenean volutpat euismod diam, et pharetra quam.");
        draw_message(true, "Nunc non varius augue.");
        ?>
    </ul>

    <footer id="send_message">
        <img src="images/placeholder.png" alt="" />
        <form class="form-inline" method="post">
            <input class="form-control mr-sm-2" type="text" placeholder="Write a message..." aria-label="Search">
            <!--aria-label????-->
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">&#8680;</button>
        </form>
    </footer>
</section>
<?php draw_footer(); ?>