@extends('layouts.uconnect_basic')

@section('content')

<div id="feed_container" class="container" >
        <div class="row">
            <div id="sidebar" class="col-sm-2 d-print-none">
        
            

            </div>
            <div id='settings_container' class="col-sm-8">

                <div style='margin-top:10%'>

                    @if (count($posts) === 0)
                        <h4 style="text-align:center">No archived posts to see</h4>
                    @else
                        @each('partials.post', $posts, 'post')
                    @endif
                    
                </div>

            </div>
            <div class="col-sm-2 d-print-none">
                
            </div>
        </div>
    </div>


@endsection