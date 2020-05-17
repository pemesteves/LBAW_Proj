@extends('layouts.uconnect_basic')

@section('content')
<div id="create_card" class="card mb-3 border rounded">
    <form method="post" action="/groups/{{$group->group_id}}">
        @csrf
        <img src="images/aefeup.jpg" class="card-img-top mx-auto d-block" alt="..."> <!--Add image upload -->
        <div class="card">
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