@extends('layouts.master')

@section('title')
    Login Page
@endsection

@section('content')
    @include('includes.message-block')
    <center>
        <div class="row">
            <div class="col-md-12">
                <h1>Login</h1>
                <br>
                <form action="{{ route('user.login') }}" method="post">
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label class="lab_email" for="email">Your Email</label>
                        <input class="form-control" type="text" name="email" id="email"
                               value="{{ Request::old('email') }}" placeholder="Enter Email Here">
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label class="lab_pass" for="password">Your Password</label>
                        <input class="form-control" type="password" name="password" id="password"
                               value="{{ Request::old('password') }}" placeholder="Enter Password Here">
                    </div>
                    <button type="submit" class="btn btn-primary lgbtn">Login</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                    {{csrf_field()}}
                </form>
                <br>
                <a class="btn btn-primary" href="{{url('/auth/facebook')}}" id="btn-fblogin">
                    <i class="fa fa-facebook"></i> Login with Facebook
                </a>
                <a class="btn btn-primary" href="{{ url('/auth/google') }}" id="btn-gologin">
                    <i class="fa fa-google"></i> Login with Google
                </a>
                <br><br>
                <h4 class="newAcctxt">Don't have an Account? &nbsp; <a class="newAcclink"
                                                                       href="{{ route('user.sign-up') }}">Sign up For
                        Free!</a></h4>
            </div>
        </div>
    </center>
@endsection