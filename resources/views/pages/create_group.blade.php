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
                <img src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png" class="mx-auto d-block" alt="group_image"
                    title="Click here to upload the group image"
                    style="border-radius: 50%; max-width: 8rem;"> 
                <div style="display: none;">
                    <input name="image" type="file"/>
                </div>      
                <div id="upload_help" style="text-align: center; margin-top: -0.5em; margin-right: -7.5em; background-color: transparent;">
                    <span class="fa fa-question-circle text-info"
                    title="A group can have an image. If you want to change it you can click on this image and upload a new one. If you don't upload an image now, your group will have this image until you edit it."></span>
                </div>
            </div>
            <div class="card-body container">
                <label class="card-title uconnect-title" >Name: </label>
                <input id="name" type="text" name="name" placeholder="Group Name" required/>

                <label>Information:</label>
                <textarea id="information" name="information" type="text" required></textarea>
            </div>
            <div class="card-footer no-gutters">      
                <button type="submit" class="btn-primary">Create Group</button>      
            </div>
        </form>    
    </div>
</div>


@endsection