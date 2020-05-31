<?php
    include_once "templates/template_common.php";
    include_once "templates/template_message.php";

    draw_header();
?>
<div id="full_page" class="d-flex flex-column  vh-100" style="padding: 0">
<?php
    draw_navbar();
?>
    <section class="container-fluid no-gutters" style="flex: 1 1 auto">
        <section class="row" style="height: 100%">
            <section id="chats" class="col-md-3" style="height: 100%; padding: 0; border-width: 0; border-right-width: 0.1em; border-style:solid; border-color: sandybrown">
                <header id="search_chat" class="row" style="margin: 0; padding: 0; width: 100%; height: 6.5%; border-color: sandybrown; border-width: 0; border-bottom-width: 0.1em; border-style: solid">
                    <form class="form-inline" method="post" style="width: 100%; justify-content:center;">
                        <!--<input class="form-control mr-sm-2" type="search" placeholder="Search messages" aria-label="Search" style="margin: 0; padding: 0; height: 100%; width: 80%;"/>
                        <button class="fa fa-search" type="submit" style="height: 100%; width: 15%; margin: 0; padding: 0; "></button>-->
                        <div class="input-group" style="margin-left:5px;margin-right:5px;border-width: 0.05em; border-color: lightgrey; border-radius: 2em; border-style:solid; background-color: white">
                            <input type="text" required class="form-control" placeholder="Search" aria-label="Search messages" aria-describedby="search-messages-button" style="border-width: 0; border-top-left-radius: inherit; border-bottom-left-radius: inherit;">
                            <div class="input-group-append" style="border-radius: inherit">
                                <button class="btn btn-outline-light fa fa-search fa-flip-horizontal" type="submit" id="search-messages-button" style="background-color: sandybrown; border-top-left-radius: inherit; border-bottom-left-radius: inherit;"></button>
                            </div>
                        </div>
                    </form>
                </header>
                <div class="col" style="height: 87%; justify-content:flex-start; padding: 0">
                
                </div>
                <footer id="create_chat" class="row" style="margin: 0; padding: 0; width: 100%; height: 6.5%">
                    <button class="btn" type="button" style="margin: 0; padding: 0; width: 100%; color: white; background-color: sandybrown; border-radius: 0;">
                        <p id="create_group_message"><i class="fa fa-plus"></i>&nbsp;Create Group Chat</p>
                    </button>
                </footer>
            </section>

            <section id="opened_message" class="col-md-9 d-flex flex-column" style="height: 100%">
                <header class="row" id="chat_info">
                    <img class="card-img" src="images/placeholder.png" alt="" style="width:2.5em; height:2.5em ; border-radius:50%" onclick="window.location.href='./profile.php'"/>
                    <h2>Mary</h2>
                </header>

                <section id="messages_col" class="d-flex flex-column" style="flex-grow:1">
                    
                </section>

                <footer class="row" id="send_message" style="border-width: 0; border-top-width: 0.1em; border-style:solid; border-color: sandybrown; height: 6.5%;">
                    <img class="chat_user_image" src="images/placeholder.png" alt=""  onclick="window.location.href='./profile.php'"/>
                    <form class="form-inline" method="post" style="max-width: 90%; width: 90%; justify-content:center;">
                        <div class="input-group chat_message_input" style="width:98%">
                            <input type="text" required class="form-control" placeholder="Write a message..." aria-label="msg-write" aria-describedby="send-message-button" style="border-width: 0; border-top-left-radius: inherit; border-bottom-left-radius: inherit;">
                            <div class="input-group-append" style="border-radius: inherit">
                                <button class="btn btn-outline-light fa fa-caret-left fa-flip-horizontal" type="submit" id="send-message-button" style="background-color: sandybrown; border-top-left-radius: inherit; border-bottom-left-radius: inherit;"></button>
                            </div>
                        </div>
                    </form>
                </footer>
            </section>
        </section>
    </section>
</div>
<?php draw_footer(); ?>