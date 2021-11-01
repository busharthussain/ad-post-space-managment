@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{_lang('Dashboard')}}</div>

                <div class="panel-body">
                    {{_lang('You are logged in')}}!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
