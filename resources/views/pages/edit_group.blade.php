@extends('layouts.uconnect_basic')

@section('content')
<div id="create_card" class="card mb-3 border rounded">
    <form id="group_image_upload" method="post" action="/groups/{{$group->group_id}}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-title">
                <img 
                @if (isset($image) && $image !== null)
                    src="{{$image->file_path}}"
                @else
                    src="http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png" 
                @endif
                class="mx-auto d-block" alt="group_image"> 
                <div style="display: none;">
                    <input name="image" type="file" 
                        @if (isset($image) && $image !== null)
                            value="{{$image->file_path}}"
                        @endif
                    />
                </div>      
            </div>
            <div class="card-body">
                <legend class="card-title uconnect-title" >Name: </legend>
                <h1 class="card-title uconnect-title" >
                    <input id="name" type="text" name="name" placeholder="Group Name" value="{{ $group->name }}" required/>
                </h1>
                <legend>Information:</legend>
                <p class="card-text uconnect-paragraph" >
                    <textarea id="information" name="information" type="text" required>{{$group->information}}</textarea>
                </p>
            </div>       
            <div class="card-footer container no-gutters">  
                <button type="submit" class="btn-primary">Edit Group</button>      
            </div>
        </div>
    </form>
</div>
    
@endsection