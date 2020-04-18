<!DOCTYPE html>
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

            <link href="{{ asset('css/menu.css') }}" rel="stylesheet">
            <link href="{{ asset('css/post_form.css') }}" rel="stylesheet">
            <link href="{{ asset('css/posts.css') }}" rel="stylesheet">
            <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
            <link href="{{ asset('css/colors.css') }}" rel="stylesheet">
            <link href="{{ asset('css/authentication.css') }}" rel="stylesheet">
            <link href="{{ asset('css/event.css') }}" rel="stylesheet">
            <link href="{{ asset('css/group.css') }}" rel="stylesheet">
            <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
            <link href="{{ asset('css/about.css') }}" rel="stylesheet">
            <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">

            <script src="{{ asset('js/navbar_mobile.js') }}" defer></script>

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
            <section>
                @yield('nav_bar')
            </section>

            <section id="content">
                @yield('content')
            </section>


            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        </body>
    </html>