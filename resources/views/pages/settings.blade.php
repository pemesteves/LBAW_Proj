@extends('layouts.uconnect_basic')

@section('content')

<div id="feed_container" class="container" >
        <div class="row">
            <div id="sidebar" class="col-sm-2 d-print-none">
        
            

            </div>
            <div id='settings_container' class="col-sm-8">

                <div style='margin-top:10%'>

                    <div style='margin-bottom:5rem; padding-bottom:1rem; border-bottom:solid 2px sandybrown'>
                        <a href='/archived' style='text-decoration:none; color:black'>
                            <h3>See archived posts</h3>
                        </a>
                    </div>

                    <div>
                        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Delete Account, are you sure? There is no turn around!</h5>
                                        <input type="hidden" id="report_id">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id='deleteUserForm' action='/users/delete' method='post'>
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label class="col-form-label">Password:</label>
                                            <input type="password" name='password' class="form-control" id="password">
                                        </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" form="deleteUserForm" id='deleteUser' class="btn btn-primary">Delete me</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete Account</button>
                    </div>
                </div>

            </div>
            <div class="col-sm-2 d-print-none">
                
            </div>
        </div>
    </div>


@endsection