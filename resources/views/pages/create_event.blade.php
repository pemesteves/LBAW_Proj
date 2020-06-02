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

                <img src="" class="card-img-top mx-auto" alt="event_image" style="display: none;"> 
                <div>
                    <input required name="image" type="file"/>
                </div>

                <label class="card-title uconnect-title" >Name: </label>
                <input id="name" type="text" name="name" placeholder="Event Name" required/>

                <label>Information:</label>
                <textarea id="information" name="information" type="text" required></textarea>
                
                <fieldset style="max-width:100%; width:100%;">
                    <legend styel='padding-left:50px'>Specifics</legend>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label><span class="fa fa-calendar"></span>&nbsp;Date:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="date" name="date" min="<?=date("Y-m-d", strtotime("tomorrow"))?>" value="<?=date("Y-m-d", strtotime("tomorrow"));?>" required/> <!--Change calendar-->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label><span class="fa fa-map-pin"></span>&nbsp;Location:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" name="location" required/> <!--Add Google Maps API-->
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="card-footer no-gutters">      
                <button type="submit" class="btn-primary">CREATE EVENT</button>      
            </div>
        </form>    
    </div>
</div>


@endsection