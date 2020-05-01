@extends('layouts.uconnect_basic')

@section('content')
 
<div class="container error_div">
    <div class="col-sm-12">
        <div class="row">
            <h2>Nobody expects the error <?=$erro_code?>!</h2>
        </div>
        <div class="row">
            <img src="/images/error.svg" alt="Error Image" />
        </div>
        <div class="row">
            <p><span class="fa fa-exclamation-triangle">&nbsp;</span>Sorry, we can't find that <?php if(isset($property_not_found)) echo $property_not_found; else echo 'page';?>! It might be an old link or maybe it moved. </p>
        </div>
    </div>
</div>

@endsection