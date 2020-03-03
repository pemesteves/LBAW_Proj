<?php
    include_once "templates/template_common.php";

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
        <li>
            <article>
                <img src="#" alt="" />
                <h2>Joaquin</h2>
                <p>1</p>
            </article>
        </li>
        <li>
            <article>
                <img src="#" alt="" />
                <h2>Joanna</h2>
                <p>+9</p>
            </article>
        </li>
        <li>
            <article>
                <img src="#" alt="" />
                <h2>Mary</h2>
            </article>
        </li>
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
        <img src="#" alt="" />
        <h2></h2>
    </header>

    <ul>
        <li class="other_message">
            <p>Quisque cursus risus augue, nec.</p>
        </li>
        <li class="my_message">
            <p>Aenean volutpat euismod diam, et pharetra quam.</p>
        </li>
        <li class="my_message">
            <p>Nunc non varius augue.</p>
        </li>
    </ul>

    <footer id="send_message">
        <img src="#" alt="" />
        <form class="form-inline" method="post">
            <input class="form-control mr-sm-2" type="text" placeholder="Write a message..." aria-label="Search">
            <!--aria-label????-->
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">&#8680;</button>
        </form>
    </footer>
</section>
<?php draw_footer(); ?>