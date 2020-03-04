<?php
    include_once "templates/template_common.php";
    include_once "templates/template_message.php";

    draw_header();
    draw_navbar();
?>
<section class="container" style="height: 100vh">
    <section class="row">
        <section id="chats" class="col-md-3">
            <header id="search_chat" class="row" style="margin: 0; padding: 0; width: 100%">
                <form class="form-inline" method="post">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search messages" aria-label="Search"/>
                    <button class="fa fa-search" type="submit"></button>
                </form>
            </header>
            <?php 
                draw_chat_info("images/placeholder.png", "", "Joaquin", 1);
                draw_chat_info("images/placeholder.png", "", "Joanna", 10);
                draw_chat_info("images/placeholder.png", "", "Mary", 0);
            ?>
            <footer id="create_chat" class="row" style="margin: 0; padding: 0; width: 100%;">
                <button type="button" style="margin: 0; padding: 0; width: 100%;">
                    <i class="fa fa-plus"></i>
                    <p id="create_group_message">Create Group Chat</p>
                </button>
            </footer>
        </section>

        <section id="opened_message" class="col-md-9">
            <header id="chat_info">
                <img src="images/placeholder.png" alt="" style="width:2em" />
                <h2>Mary</h2>
            </header>

            <ul>
                <?php 
                    draw_message(false, "Quisque cursus risus augue, nec.");
                    draw_message(true, "Aenean volutpat euismod diam, et pharetra quam.");
                    draw_message(true, "Nunc non varius augue.");
                ?>
            </ul>

            <footer id="send_message">
                <img src="images/placeholder.png" alt="" style="width:2em" />
                <form class="form-inline" method="post">
                    <input class="form-control mr-sm-2" type="text" placeholder="Write a message..."
                        aria-label="Search">
                    <!--aria-label????-->
                    <button type="submit"><i class="fa fa-caret-right"></i></button>
                </form>
            </footer>
        </section>
    </section>
</section>
<?php draw_footer(); ?>