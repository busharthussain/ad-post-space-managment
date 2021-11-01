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
            <div class="login-box card">
                <div class="card-body">
                    <div class="logo-holder text-center">
                        <img src="{!! asset('assets/images/login-logo.png') !!}" alt="logo" />
                        <span>Sharepeeps</span>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form class="form-horizontal form-material" role="form" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        <h3 class="box-title m-b-20">{{_lang('Recover Password')}}</h3>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input id="email" type="email" placeholder="Email" class="form-control" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">{{_lang('Send Password Reset Link')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

@endsection
