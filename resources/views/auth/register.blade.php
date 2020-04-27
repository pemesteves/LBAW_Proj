@extends('layouts.uconnect_auth')


@section('content')

<script src="./js/body_auth_prop.js" async></script>
    <section id="register" class="container d-flex flex-column align-items-center">
        <div id="form_div" class="col-sm-7">
            <h2 class="text-primary">Register in UConnect</h2>
            <form class="d-flex flex-column justify-content-center" method="POST" action="{{ route('register') }}">
                <div class="form-group">
                    {{ csrf_field() }}
                    <input type="text" required class="row form-control" id="name" name="name" required="required" placeholder="Name">
                    @if ($errors->has('name'))
                        <span class="error">
                            {{ $errors->first('name') }}
                        </span>
                    @endif
                    <input type="email" required class="row form-control" id="email" name="email" placeholder="Email">
                    @if ($errors->has('email'))
                        <span class="error">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                    <input type="password" required class="row form-control" id="password" name="password" placeholder="Password">
                    @if ($errors->has('password'))
                        <span class="error">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                    <input type="password" required class="row form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                    @if ($errors->has('password_confirmation'))
                        <span class="error">
                            {{ $errors->first('password_confirmation') }}
                        </span>
                    @endif
                    <input type="text" required class="row form-control" id="university" name="university" placeholder="University">
                </div>
                <div class="form-group">
                    <select class="form-control" required id="occupation" name="occupation" placeholder="Occupation">
                        <option>Choose an option:</option>
                        <option>Student</option>
                        <option>Teacher</option>
                        <option>Organization</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <footer>
                <p>Already have an account? Then <a href="/login" style="color: #ffa31a">LOGIN</a></p>
            </footer>
        </div>
    </section>

@endsection
