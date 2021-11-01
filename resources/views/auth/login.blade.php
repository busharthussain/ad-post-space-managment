@extends('layouts.app')

@section('content')

    <div class="lang-toggle pull-right">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
                <!-- ============================================================== -->
                <!-- Language -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if(get_lang() == 'english')
                            <i class="flag-icon flag-icon-gb"></i>
                        @else
                            <i class="flag-icon flag-icon-dk"></i>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right scale-up">
                        @if(get_lang() == 'english')
                            <a class="dropdown-item" href="{{route('changeLanguage','dutch')}}"><i class="flag-icon flag-icon-dk"></i>Danish</a>
                        @else
                            <a class="dropdown-item" href="{{route('changeLanguage','english')}}"><i class="flag-icon flag-icon-gb"></i>English</a>
                        @endif
                    </div>
                </li>
            </ul>

        </nav>
    </div>
    <section id="wrapper">
        <div class="login-register" style="background-image:url({!! asset('assets/images/login-register.jpg') !!});">
            <!-- Test -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="login-box card">
                <div class="card-body">
                    @if(\Session::has('message'))
                        <div class="alert alert-success">
                            {{ \Session::get('message') }}
                        </div>
                    @endif
                    <div class="logo-holder text-center">
                        <img src="{!! asset('assets/images/login-logo.png') !!}" alt="logo" />
                        <span>Sharepeeps</span>
                    </div>
                    <form class="form-horizontal form-material" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <h3 class="box-title m-b-20">{{_lang('Sign In')}}</h3>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <input id="email" type="email" placeholder="{{_lang('Email')}}" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <p style="position: relative;bottom: 10px;">{{ $errors->first('email') }}</p>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="col-xs-12">
                                    <input id="password" type="Password" class="form-control" name="password" placeholder="{{_lang('Password')}}" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <p style="position: relative;bottom: 10px;">{{ $errors->first('password') }}</p>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12 font-14">
                                        <div class="checkbox checkbox-primary pull-left p-t-0">
                                            <input id="checkbox-signup" type="checkbox">
                                            <label for="checkbox-signup">{{_lang('Remember me')}} </label>
                                        </div> <a href="{{ route('password.request') }}" id="to-recover" class="text-dark pull-right"><!-- <i class="fa fa-lock m-r-5"></i> --> {{_lang('Forgot Password')}}?</a> </div>
                                </div>
                                <div class="form-group text-center m-t-20">
                                    <div class="col-xs-12">
                                        <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit"> {{_lang('sign in')}}</button>
                                    </div>
                                </div>
                                <div class="form-group m-b-0">
                                    <!-- <div class="col-sm-12 text-center">
                                        <div>Don't have an account? <a href="pages-register.html" class="text-info m-l-5"><b>Sign Up</b></a></div>
                                    </div> -->
                                </div>
                    </form>
                    <form class="form-horizontal" id="recoverform" action="index.html">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>{{_lang('Recover Password')}}</h3>
                                <p class="text-muted">{{_lang('Enter your Email and instructions will be sent to you')}}! </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" placeholder="Email"> </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
