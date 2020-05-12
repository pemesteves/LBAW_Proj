<!DOCTYPE html>
    <html lang="en">
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            
            <!-- CSRF token -->
            <meta name="csrf-token" content="{{ csrf_token() }}">

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
            <link href="{{ asset('css/error.css') }}" rel="stylesheet">
            <link href="{{ asset('css/create.css') }}" rel="stylesheet">

            <script src="{{ asset('js/navbar_mobile.js') }}" defer></script>        
            <script src="{{ asset('js/app.js') }}" defer> </script>
            <script src="{{ asset('js/post.js') }}" defer> </script>
            <script src="{{ asset('js/resetPass.js') }}" defer> </script>
            <script src="{{ asset('js/reports.js') }}" defer> </script>
            <script src="{{ asset('js/friendship.js') }}" defer> </script>
            <script src="{{ asset('js/event.js') }}" defer> </script>
            <script src="{{ asset('js/group.js') }}" defer> </script>

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
            
            <section id='feedback'>
            </section>           

            <section id="content">
                
            @if(Session::has("success_message"))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= Session::get("success_message") ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

                @yield('content')
                
                <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportModalLabel">Report</h5>
                        <input type="hidden" id="report_id">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="report_title">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Description:</label>
                            <textarea class="form-control" id="report_description"></textarea>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary sendReport">Send message</button>
                    </div>
                    </div>
                </div>
            </div>

            </section>


            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        </body>
    </html>