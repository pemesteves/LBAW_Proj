@extends('layouts.uconnect_auth')


@section('content')

<script src="./js/body_auth_prop.js" async></script>
    <section id="register" class="container d-flex flex-column align-items-center">
        <div id="form_div" class="col-sm-7">
            <h2 class="text-primary">Register in UConnect</h2>
            <form class="d-flex flex-column justify-content-center">
                <div class="form-group">
                    <input type="text" required class="row form-control" id="name" required="required" placeholder="Name">
                    <input type="email" required class="row form-control" id="email" placeholder="Email">
                    <input type="password" required class="row form-control" id="password" placeholder="Password">
                    <input type="password" required class="row form-control" id="confpassword" placeholder="Confirm Password">
                    <input type="text" required class="row form-control" id="university" placeholder="University">
                </div>
                <div class="form-group">
                    <select class="form-control" required id="occupation" placeholder="Occupation">
                        <option>Choose an option:</option>
                        <option>Student</option>
                        <option>Teacher</option>
                        <option>Organization</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" action="TO UPDATE" method="POST">Register</button>
            </form>
            <footer>
                <p>Already have an account? Then <a href="/login" style="color: #ffa31a">LOGIN</a></p>
            </footer>
        </div>
    </section>

@endsection
