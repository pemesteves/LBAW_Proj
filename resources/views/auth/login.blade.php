@extends('layouts.uconnect_auth')


@section('o_basic_content')
<script src="./js/body_auth_prop.js" async></script>
    <section id="login" class="container d-flex flex-column align-items-center">
        <div id="form_div" class="col-sm-7">
            <h2 class="text-primary">Login in UConnect</h2>
            <form class="d-flex flex-column justify-content-center" method="POST" action="{{ route('login') }}">
                <div class="form-group">
                    {{ csrf_field() }}
                    <input type="email" required class="row form-control" id="email" placeholder="Email">
                    @if ($errors->has('email'))
                        <span class="error">
                        {{ $errors->first('email') }}
                        </span>
                    @endif
                    <input type="password" required class="row form-control" id="password" placeholder="Password">
                    @if ($errors->has('password'))
                        <span class="error">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                </div>
                <button type="submit" class="row btn btn-primary" action="TO UPDATE" method="POST" style="color:whitesmoke">Login</button>
            </form>

            <footer style="margin: 0; margin-top: 1em; padding: 0;">
                <p class="row">Don't have an account? Then <a href="/register">&nbsp;REGISTER</a></p>
                <p class="row">Forgot your password? <a href="TO UPDATE">&nbsp;Recover password</a></p>
            </footer>
        </div>
    </section>
@endsection


@section('content')
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password" >Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <button type="submit">
        Login
    </button>
    <a class="button button-outline" href="{{ route('register') }}">Register</a>
</form>
@endsection