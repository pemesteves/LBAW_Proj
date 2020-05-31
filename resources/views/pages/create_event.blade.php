@extends('layouts.uconnect_basic')

@section('content')
<div id="create_card" class="card mb-3 border rounded">
    <div class="card-header">
        <h2>Create Event</h2>
    </div>
    <div class="card">
        <form id="event_image_upload" method="post" action="/events/create" enctype="multipart/form-data">
            <div class="card-body container">
                @csrf      

                <img src="" class="card-img-top mx-auto d-block" alt="event_image"> 
                <div>
                    <input required name="image" type="file"/>
                </div>

                <legend class="card-title uconnect-title" >Name: </legend>
                <input id="name" type="text" name="name" placeholder="Event Name" required/>

                <legend>Information:</legend>
                <textarea id="information" name="information" type="text" required></textarea>
                
                <div class="row">
                    <div class="col-sm-6">
                        <legend><span class="fa fa-calendar"></span>&nbsp;Date:</legend>
                        <input type="date" name="date" required/> <!--Change calendar-->
                    </div>
                    <div class="col-sm-6">
                        <legend><span class="fa fa-map-pin"></span>&nbsp;Location:</legend>
                        <input type="text" name="location" required/> <!--Add Google Maps API-->
                    </div>
                </div>
            </div>
            <div class="card-footer no-gutters">      
                <button type="submit" class="btn-primary">CREATE EVENT</button>      
            </div>
        </form>    
    </div>
</div>


@endsection