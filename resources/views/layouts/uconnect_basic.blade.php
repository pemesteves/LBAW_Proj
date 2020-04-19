@extends('layouts.uconnect_template')


@section('nav_bar')
<nav class="navbar navbar-dark navbar-bar">
                <a class="navbar-brand" href="/<?php if($is_admin) echo 'admin'; else echo 'feed';?>">
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
                    <button type="button" class="btn btn-outline-light" onclick="window.location.href='<?php if($is_admin) echo '/admin'; else echo '/user/me'; ?>'"
                        <?php if($is_admin){ ?>    
                        style="opacity: 1; display: block;"    
                        <?php }?>
                    ><img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" alt="John"/>
                    @if (Auth::check()) 
                        {{ Auth::user()->name }} 
                    @endif
                    </button>
                    <?php if(!$is_admin){ ?>    
                    <button class="btn btn-outline-light dropdown-toggle dropdown-toggle-split" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <?php } ?>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">            
                        <div class="navbar-nav">
                            <a class="dropdown-item" href="#"><span class="fa fa-adjust"></span>&nbsp;Dark Mode</a>
                            <a class="dropdown-item" href="./about.php"><span class="fa fa-info-circle"></span>&nbsp;About Us</a>
                            <a class="dropdown-item" href="#"><span class="fa fa-cog"></span>&nbsp;Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/logout') }}"><span class="fa fa-sign-out"></span>&nbsp;Logout</a>
                        </div>
                    </div>
                </div>
            </nav>
@endsection