@extends('layouts.uconnect_basic')

@section('content')

<div id="feed_container" class="container" >
        <div class="row">
            <div id="sidebar" class="col-sm-2 d-print-none">
        
            

            </div>
            <div id='settings_container' class="col-sm-8">

                <div style='margin-top:10%'>

                    <div style='margin-bottom:5rem; padding-bottom:1rem; border-bottom:solid 2px sandybrown'>
                        <a href='/archived' style='text-decoration:none; color:black'>
                            <h3>See archived posts</h3>
                        </a>
                    </div>

                    <div>
                        <button type="button" class="btn btn-danger">Delete Account</button>
                    </div>
                </div>

            </div>
            <div class="col-sm-2 d-print-none">
                
            </div>
        </div>
    </div>


@endsection