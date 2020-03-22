<?php function draw_header() { ?>

    <!doctype html>
    <html lang="en">
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
            
            <!-- Font Awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

            <!-- Google Fonts -->
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Barlow|Cormorant+Garamond&display=swap">

            <!-- Costume Css -->
            <link rel="stylesheet" href="./css/menu.css">
            <link rel="stylesheet" href="./css/post_form.css">
            <link rel="stylesheet" href="./css/posts.css">
            <link rel="stylesheet" href="./css/chat.css">
            <link rel="stylesheet" href="./css/colors.css">
            <link rel="stylesheet" href="./css/authentication.css">
            <link rel="stylesheet" href="./css/event.css">
            <link rel="stylesheet" href="./css/group.css">
            <link rel="stylesheet" href="./css/profile.css">
            <link rel="stylesheet" href="./css/about.css">
            <link rel="stylesheet" href="./css/navbar.css">

            <script src="./js/navbar_mobile.js" defer></script>

            <style>
                body {
                    background-color: rgba(244, 166, 98, 0.05);
                    font-family: 'Barlow', Arial, Helvetica, sans-serif
                } 

                h1, h2, h3, h4, h5, h6 {
                    font-family: 'Cormorant Garamond', 'Times New Roman', Times, serif;
                    font-weight: bold;
                }
            </style>

            <title>UConnect: We're getting there</title>
        </head>
        <body>
<?php } 
function draw_navbar($is_admin = false) { 
    ?>
            <nav class="navbar navbar-dark navbar-bar">
                <a class="navbar-brand" href="./<?php if($is_admin) echo 'admin.php'; else echo 'feed.php';?>">
                    <h1>UConnect <span class="fa fa-graduation-cap"></span></h1>
                </a> <!-- whitesmoke -->
                <?php if(!$is_admin){?>
                <form class="form-inline">
                    <div class="input-group">
                        <input type="text" required class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search-button">
                        <div class="input-group-append">
                            <button class="btn btn-outline-light fa fa-search fa-flip-horizontal" type="submit" id="search-button"></button>
                        </div>
                    </div>
                </form>
                <button id="navbar_pers_info_mobile" onclick="show_pers_info()"><span class="fa fa-id-card"></span></button>
                <?php } ?>
                <div id="navbar_pers_info" class="btn-group" 
                    <?php if($is_admin){ ?>    
                    style="opacity: 1; display: block; width: auto;"    
                    <?php }?>
                >
                    <?php if(!$is_admin){ ?>
                    <button type="button" class="btn btn-outline-light fa fa-bell"></button>
                    <button type="button" class="btn btn-outline-light fa fa-envelope" onclick="window.location.href='./messages.php'"></button>
                    <?php } ?>
                    <button type="button" class="btn btn-outline-light" onclick="window.location.href='<?php if($is_admin) echo './admin.php'; else echo './profile.php'; ?>'"
                        <?php if($is_admin){ ?>    
                        style="opacity: 1; display: block;"    
                        <?php }?>
                    ><img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" alt="John"/> John</button>
                    <?php if(!$is_admin){ ?>    
                    <button class="btn btn-outline-light dropdown-toggle dropdown-toggle-split" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <?php } ?>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">            
                        <div class="navbar-nav">
                            <a class="dropdown-item" href="#"><span class="fa fa-adjust"></span>&nbsp;Dark Mode</a>
                            <a class="dropdown-item" href="./about.php"><span class="fa fa-info-circle"></span>&nbsp;About Us</a>
                            <a class="dropdown-item" href="#"><span class="fa fa-cog"></span>&nbsp;Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href=""><span class="fa fa-sign-out"></span>&nbsp;Logout</a>
                        </div>
                    </div>
                </div>
            </nav>
<?php } ?>

<?php function draw_footer() { ?>
            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        </body>
    </html>
<?php } ?>