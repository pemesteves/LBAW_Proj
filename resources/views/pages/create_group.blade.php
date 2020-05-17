@extends('layouts.uconnect_basic')

@section('content')
<div id="create_card" class="card mb-3 border rounded">
    <div class="card-header">
        <h2>Create Group</h2>
    </div>
    <div class="card">
        <form id="group_image_upload" method="post" action="/groups/create" enctype="multipart/form-data">
            @csrf
            <div class="card-title">
                <img src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png" class="mx-auto d-block" alt=""> 
                <div style="display: none;">
                    <input name="image" type="file"/>
                </div>      
            </div>
            <div class="card-body container">
                <legend class="card-title uconnect-title" >Name: </legend>
                <input type="text" name="name" placeholder="Group Name" required/>

                <legend>Information:</legend>
                <textarea  name="information" type="text" required></textarea>
            </div>
            <div class="card-footer no-gutters">      
                <button type="submit" class="btn-primary">Create Group</button>      
            </div>
        </form>    
    </div>
</div>


@endsection