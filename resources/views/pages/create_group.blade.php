@extends('layouts.uconnect_basic')

@section('content')
<div id="create_card" class="card mb-3 border rounded">
    <div class="card-header">
        <h2>Create Group</h2>
    </div>
    <div class="card">
        <form method="post" action="/groups/create">
            <div class="card-body container">
                @csrf      
                <legend class="card-title uconnect-title" >Name: </legend>
                <input id="name" type="text" name="name" placeholder="Group Name" required/>

                <legend>Information:</legend>
                <textarea id="information" name="information" type="text" required></textarea>
            </div>
            <div class="card-footer no-gutters">      
                <button type="submit" class="btn-primary">Create Group</button>      
            </div>
        </form>    
    </div>
</div>


@endsection