@extends('layouts.app')

@section('content')
    <section id="wrapper">
        <div class="login-register" style="background-image:url({!! asset('assets/images/login-register.jpg') !!});">
            <div class="login-box card">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
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
                    <form class="form-horizontal form-material" role="form" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">
                        <h3 class="box-title m-b-20">{{_lang('Recover Password')}}</h3>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <input id="email" type="email" placeholder="{{_lang('Email')}}" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <input id="password" type="password" placeholder="{{_lang('password')}}" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <p class="text-danger">{{ $errors->first('password') }}</p>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

                            <div class="col-md-6">
                                <input id="password-confirm" placeholder="{{_lang('confirmation')}}" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">{{_lang('Reset')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

@endsection
