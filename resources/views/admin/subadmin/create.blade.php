@extends('layouts.superadminlayout')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">@if(empty($id)) Create @else Edit @endif {{_lang('Sub Admin')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.sub') !!}">{{_lang('Sub Admin')}}</a></li>
                    <li class="breadcrumb-item">@if(empty($id)) {{_lang('Create')}} @else {{_lang('Edit')}} @endif {{_lang('Sub Admin')}}</li>
                </ol>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div  id="loginform" class="create-company form-horizontal form-material">
                                <div id="notificationMessage"></div>
                            {!! Form::model($data, ['id' => 'sub-admin-form']) !!}
                                <div class="row">
                                    <div class="col-6">
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name','required' => 'required', 'placeholder' => _lang('Name')]) !!}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    {!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email','required' => 'required', 'placeholder' => _lang('Email')]) !!}
                                                </div>
                                            </div>
                                        @php
                                            $checkedStatus = null;
                                               if(empty($id))
                                                 $checkedStatus = 1;
                                        @endphp
                                            <div class="form-group">
                                                <div class="switch">
                                                    <label> {{_lang('Status')}}
                                                        <span style="margin:0 0 0 70px;">
                                                            {{Form::hidden('status',0)}}
                                                            {!! Form::checkbox('status', 1, $checkedStatus, ['class' => 'field', 'id' => 'status']) !!}
                                                                <span class="lever"></span>
                                                          </span>
                                                    </label>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="privacy-doucment-sec create-sub-admin-btn">
                                            <div class="btn-sec" style="margin-top:300px;">
                                                <button class="btn btn-success">@if(empty($id)) {{_lang('Add')}} @else {{_lang('Update')}} @endif</button>
                                                <a href="{!! URL::route('super.admin.sub') !!}" class="btn btn-success">{{_lang('Cancel')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::token() !!}
                            {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right sidebar -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">© 2018  Sharepeeps </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>

    <script type="text/javascript">
        $addRecordRoute = '{{ URL::route('sub.admin.add') }}';
        $redirectRoute = '{{ URL::route('super.admin.sub') }}';
        $id = '{!! $id !!}';
        $viewOnly = '{!! $viewOnly !!}';
        $token = "{{ csrf_token() }}";

        $(document).ready(function () {
            if ($viewOnly) {
                $("#sub-admin-form :input").prop("disabled", true);
                $("input[type=button]").attr("disabled", "disabled");
            }
            $('#sub-admin-form').submit(function (e) {
                e.preventDefault();
                updateFormData();
                $type = 'addRecord';
                renderAdmin();
            });
        })

        var updateFormData = function () {
            $formData = {
                '_token': $token,
                data:  $('#sub-admin-form').serialize(),
                id: $id
            };
        }

    </script>

    {!! HTML::script('assets/js/admin.js') !!}

@stop