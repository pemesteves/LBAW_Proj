@extends('layouts.uconnect_basic')

@section('content')

<div class="container" style="margin:0; min-width:100%">
        <div class="row">
            <div class="col-sm-1" style="padding-left:0px">
                <div id="dl-menu" class="dl-menuwrapper">
                    <button id="dl-trigger" onclick="toggle()">Open Menu</button>
                    <ul id="dl-menu2">
                        <li>
                            <h5 class="menu_title">Groups</h5>
                            <ul class="dl-submenu">
                                <li><a href="group.php"><small>NIAEFEUP</small></a></li>
                                <li><a href="#"><small>AEFEUP</small></a></li>
                            </ul>
                        </li>
                        <li>
                            <h5 class="menu_title">Events</h5>
                            <ul class="dl-submenu">
                                <li><a href="event.php"><small>FEUPCaffe 12/3</small></a></li>
                                <li><a href="#"><small>Jantar NIAEFEUP</small> </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <script> 
                    function toggle() { 
                        let opacity = document.querySelector('#dl-menu2').style.opacity; 
                        if(opacity == "1"){
                            document.querySelector('#dl-menu2').style.opacity=0;
                            document.querySelector('#dl-menu2').style.display="none";
                        }
                        else{
                            document.querySelector('#dl-menu2').style.opacity=1;
                            document.querySelector('#dl-menu2').style.display="block";
                        }
                    } 
                </script> 
            

            </div>
            <div class="col-sm-8" style="flex-grow:1;max-width:100%">
                @each('partials.post', $posts, 'post')

            </div>



        </div>
    </div>

@endsection