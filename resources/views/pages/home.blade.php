@extends('layouts.uconnect_auth')

@section('content')
<div id="init_page_area" style="width:100%;height:100%;">
    <img src="./images/init.png" alt="init" style="position:absolute;bottom:0%;object-fit:fill;width: 100%; height: 50%";>
    <h4 style="display:block;position:absolute;top:15%;left:2%; font-size: 2em;">Connecting U to U</h4>
    <a href="/login" style="color:inherit"> 
        <button style="display:block;position:absolute;top:30%;right:15%;border:none;border-radius:10em;background-color:#f68e4d;width:200px;height:60px;text-align:center;color:white; font-weight: bold; height: 3.25em;">
            LOGIN 
        </button>
    </a>
    <a href="/register" style="color:inherit">
        <button style="display:block;position:absolute;top:40%;right:15%;border:none;border-radius:10em;background-color:#f68e4d;width:200px;height:60px;text-align:center;color:white; font-weight: bold; height: 3.25em;"> 
            REGISTER 
        </button>
    </a>
</div>

@endsection