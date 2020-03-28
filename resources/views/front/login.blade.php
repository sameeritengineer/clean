@extends('front.index')
@section('title', 'log in')
@section('content')
<section class="login">
<div class="container">
<div class="row">
<div class="col-lg-5 offset-md-12 offset-lg-3 offset-md-0">
    <div class="message_us user_login">
        <div class="Contact_form" >
            <h1>Log In</h1>
            <p>Enter your e-mail address and your password.</p>
            <form method="post" class="dzForm">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <input name="dzName" required="" class="form-control" placeholder="E-mail" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <input name="dzEmail" class="form-control" required="" placeholder="Password" type="password">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-block">
                            <input id="check1" type="checkbox">
                            <label for="check1">Remember me</label>
                        </div>
                        <a href="" class="forgot_pwd" id=""><i class="fa fa-unlock-alt"></i> Forgot Password</a>
                    </div>
                    <div class="col-md-12">
                        <button name="submit" type="submit" value="Submit" class="site-button "> <span>Sign in</span> </button>
                        <a href="{{route('create')}}" class="FP_sign_sp">Create an account</a>
                    </div>
                </div>
            </form>
        </div>
        <!-- forget password end-->
    </div>
</div>
</div>
</div>
</section>
<!-- login Section End -->
@endsection