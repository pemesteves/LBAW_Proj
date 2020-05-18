@extends('layouts.uconnect_basic')

@section('content')

<br>
    <div id="profile_card" class="container" style="padding-top: 1em; margin-bottom: 0; background-color: white; border: 1px solid lightgrey;">
        <form id="user_image_upload" action="/users/me" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-4">
                    <div class="text-center" style="max-width: 75%; max-height: 80%;">
                        <img 
                            @if (isset($image) && $image !== null)
                                src="{{$image->file_path}}"
                            @else 
                                src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" 
                            @endif
                        alt="" class="rounded-circle" style="max-width:100%; max-height: 50%;"/>
                        <div style="display: none;">
                            <input name="image" type="file" 
                                @if (isset($image) && $image !== null)
                                    value="{{$image->file_path}}"
                                @endif
                            />
                        </div>    
                    </div>
                </div>
                <div class="col-8" style="padding: 0.2rem 1rem 0 0.2rem;">
                    <div class="row input_div">
                        <div class="col-sm-4">
                            <h2 style="border: 0; padding: 0">Name:</h2>
                        </div>
                        <div class="col-sm-8">
                            <input id="name" type="text" name="name" value="{{ Auth::user()->name }}">
                        </div>
                    </div>
                    <div class="row input_div">
                        <div class="col-sm-4">
                            <h2 style="border: 0; padding: 0">University:</h2>
                        </div>
                        <div class="col-sm-8">
                            <input id="university" type="text" name="university" value="{{ Auth::user()->userable->university }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card" id="personal_info_card">
                    <div class="card-header">
                        <h5 class="mb-0 text-link" style="font-size: 1.25em; font-weight:normal; ">
                            Personal Information
                        </h5>
                    </div>
                    <div id="collapseOne">
                        <div class="card-body">
                            <textarea id="personal_info" name="personal_info"
                                <?php if(Auth::user()->userable->personal_info == "") echo 'placeholder="No information yet."';?> 
                            >{{Auth::user()->userable->personal_info}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary" action="/users/me" method="post">Edit Profile</button>
            </div>
        </form>
    </div>
@endsection