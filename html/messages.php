<?php
    include_once "templates/template_common.php";
    include_once "templates/template_message.php";

    draw_header();
?>
<div id="full_page" class="d-flex flex-column no-gutters vh-100" style="padding: 0">
<?php
    draw_navbar();
?>
    <section class="container-fluid no-gutters" style="flex: 1 1 auto">
        <section class="row" style="height: 100%">
            <section id="chats" class="col-md-3" style="height: 100%; padding: 0; border-width: 0; border-right-width: 0.1em; border-style:solid; border-color: sandybrown">
                <header id="search_chat" class="row" style="margin: 0; padding: 0; width: 100%; height: 6.5%; border-color: sandybrown; border-width: 0; border-bottom-width: 0.1em; border-style: solid">
                    <form class="form-inline" method="post" style="width: 100%;">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search messages" aria-label="Search" style="margin: 0; padding: 0; height: 100%; width: 80%;"/>
                        <button class="fa fa-search" type="submit" style="height: 100%; width: 15%; margin: 0; padding: 0; "></button>
                    </form>
                </header>
                <div class="col" style="height: 87%; justify-content:flex-start; padding: 0">
                <?php 
                    draw_chat_info("images/placeholder.png", "", "Joaquin", 1);
                    draw_chat_info("images/placeholder.png", "", "Joanna", 10);
                    draw_chat_info("images/placeholder.png", "", "Mary", 0);
                ?>
                </div>
                <footer id="create_chat" class="row" style="margin: 0; padding: 0; width: 100%; height: 6.5%">
                    <button class="btn" type="button" style="margin: 0; padding: 0; width: 100%; color: white; background-color: sandybrown; border-radius: 0;">
                        <p id="create_group_message"><i class="fa fa-plus"></i>&nbsp;Create Group Chat</p>
                    </button>
                </footer>
            </section>

            <section id="opened_message" class="col-md-9 d-flex flex-column" style="height: 100%">
                <header class="row" id="chat_info" style="padding-top:0.5em; border-width: 0; border-bottom-width: 0.1em; border-style:solid; border-color: sandybrown">
                    <img class="card-img" src="images/placeholder.png" alt="" style="width:2.5em; height:2.5em" onclick="window.location.href='/profile.php'"/>
                    <h2>Mary</h2>
                </header>

                <section class="d-flex flex-column" style="flex-grow:1">
                    <?php 
                        draw_message(false, "Quisque cursus risus augue, nec.");
                        draw_message(true, "Aenean volutpat euismod diam, et pharetra quam.");
                        draw_message(true, "Nunc non varius augue.");
                    ?>
                </section>

                <footer class="row" id="send_message" style="border-width: 0; border-top-width: 0.1em; border-style:solid; border-color: sandybrown; height: 6.5%;">
                    <img src="images/placeholder.png" alt="" style="width:3.5rem; height:3.5rem"  onclick="window.location.href='/profile.php'"/>
                    <form class="form-inline" method="post" style="max-width: 90%; width: 90%;">
                        <input class="form-control mr-sm-2" type="text" placeholder="Write a message..."
                            aria-label="Search" style="margin: 0; padding: 0; height: 100%; max-width: 90%; width: 90%; ">
                        <!--aria-label????-->
                        <button type="submit" style="margin: 0; padding: 0; height: 100%; max-width: 8%; width: 8%;"><i class="fa fa-caret-right"></i></button>
                    </form>
                </footer>
            </section>
        </section>
    </section>
</div>
<?php draw_footer(); ?>