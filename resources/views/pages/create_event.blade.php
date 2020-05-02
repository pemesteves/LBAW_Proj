@extends('layouts.uconnect_basic')

@section('content')
<div id="create_event_card" class="card mb-3 border rounded">
    <div class="card-header">
        <h2>Create Event</h2>
    </div>
    <div class="card">
        <form method="POST" action="/events/create">
            <div class="card-body container">
                    @csrf      
                    <legend class="card-title uconnect-title" >Name: </legend>
                    <input type="text" name="name" placeholder="Event Name" required/>

                    <legend>Information:</legend>
                    <textarea  name="information" type="text" required></textarea>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <legend>Date:</legend>
                            <input type="date" name="date" required/> <!--Change calendar-->
                        </div>
                        <div class="col-sm-6">
                            <legend>Location:</legend>
                            <input type="text" name="location" required/> <!--Add Google Maps API-->
                        </div>
                    </div>
            </div>
            <div class="card-footer no-gutters">      
                <button type="submit" class="btn-primary">Create Event</button>      
            </div>
        </form>    
    </div>
</div>


@endsection