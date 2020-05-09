@extends('layouts.uconnect_auth')


@section('content')
<script src="./js/body_auth_prop.js" async></script>
    <section id="login" class="container d-flex flex-column align-items-center">
        <div id="form_div" class="col-sm-7">
            <h2 class="text-primary">Reset Password</h2>
            <form id="reset_form" class="d-flex flex-column justify-content-center" method="post" action='/resetPass'>
                <div class="form-group">
                    @csrf
                    <span id='error_message'></span>
                    <input type="email" required class="row form-control" id="email" name="email" placeholder="Email">
                    <input type="text" class="row form-control" id="code" name="code" placeholder="Code" style="display:none;">
                    <input type="password" class="row form-control" id="pass1" name="pass" placeholder="Password" style="display:none;">
                    <input type="password" class="row form-control" id="pass2" name="pass_confirmation" placeholder="Repeat Password" style="display:none;">
                </div>
                <button type="submit" class="row btn btn-primary" style="color:whitesmoke">Send email</button>
            </form>

            <footer style="margin: 0; margin-top: 1em; padding: 0;">
                <p class="row">Don't have an account? Then <a href="/register">&nbsp;REGISTER</a></p>
                <p class="row">Did you just remember? Then <a href="/login">&nbsp;LOGIN</a></p>
            </footer>
        </div>
    </section>
@endsection