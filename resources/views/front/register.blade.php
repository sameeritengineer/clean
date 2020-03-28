@extends('front.index')
@section('title', 'Register')
@section('content')
<!-- Register Section Start -->
<section class="login">
<div class="container">
<div class="row">
<div class="col-lg-8 col-md-12 offset-md-0  offset-lg-2">
    <div class="message_us user_login">
        <div class="Contact_form" id="signup">
            <h1>Register</h1>
            <p>Enter your personal details below</p>
            @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
                @php
                    Session::forget('success');
                @endphp
            </div>
            @endif
        <form method="POST" action="{{ url('user/create') }}" class="dzForm" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">  
                                <input type="text" name="first_name" class="form-control" placeholder="First name" type="text" value="{{ old('first_name') }}">
                                @if ($errors->has('first_name'))
                                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="last_name" class="form-control" placeholder="Last name" type="text" value="{{ old('last_name') }}">
                                @if ($errors->has('last_name'))
                                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                 <input type="text" name="email" class="form-control" placeholder="E-mail" type="email" value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" placeholder="Password">
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" >
                                @if ($errors->has('confirm_password'))
                                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="mobile" class="form-control" placeholder="Phone number" value="{{ old('mobile') }}">
                                 @if ($errors->has('mobile'))
                                <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-block">
                            <input id="terms" type="checkbox"  checked>
                            <label for="terms">I agree to the <a href="{{route('termsconditions')}}">Terms of Service </a>&amp;
                             <a href="{{route('privacypolicy')}}">Privacy Policy</a></label>
                        </div>
                    </div>
                    <div class="col-md-12">
                    <button name="submit" type="submit" value="Submit" class="site-button "> <span>Sign up</span> </button>
                    <a href="{{route('Login')}}" class="FP_sign_sp">Sign in</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</section>
<!-- register Section End -->
@endsection