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
            <link href="{{ asset('css/error.css') }}" rel="stylesheet">

            @if(!Auth::check())
                <link href="{{ asset('css/auth_theme.css') }}" rel="stylesheet">
            @else
                @if(Auth::user()->dark_mode)
                    <link href="{{ asset('css/dark_theme.css') }}" rel="stylesheet">
                @else
                    <link href="{{ asset('css/light_theme.css') }}" rel="stylesheet">
                @endif
            @endif
            

            
            @if(isset($css))
                @foreach($css as $c)
                    <link href='{{ asset("css/$c") }}' rel="stylesheet">
                @endforeach
            @endif

            <script src="{{ asset('js/navbar_mobile.js') }}" defer></script>        
            <script src="{{ asset('js/app.js') }}" defer> </script>
            <script src="{{ asset('js/input_validation.js') }}" defer> </script>
           
            
            

            @if(isset($js))
                @foreach($js as $j)
                    <script src='{{ asset("js/$j") }}' defer> </script>
                @endforeach
            @endif

            <style>
                body {
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
            <div id="full_page">
                <div>
                    @yield('nav_bar')
                </div>
                
                <div id='feedback' style='position: -webkit-sticky;position: sticky;top: 0;z-index:1;'>
                    
                </div>           

                <div id="content" style='flex-grow: 1;'>
                    
                @if(Session::has("success_message"))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?=
                    Session::get("success_message") 
                    ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                    @yield('content')
                    
                    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
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
                                        <label class="col-form-label">Title:</label>
                                        <input type="text" class="form-control" id="report_title">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Description:</label>
                                        <textarea class="form-control" id="report_description"></textarea>
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary sendReport">Send report</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        </body>
    </html>