@extends('layouts.superadminlayout')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">@if(empty($id)) {{_lang('Create')}} @else {{_lang('Update')}} @endif {{_lang('Company')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('Create Company')}}</li>
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
                            <div class="panel-body demo-panel-files" id='demo-files'></div>
                            <div id="notificationMessage"></div>
                            <div  id="loginform" class="create-company form-horizontal form-material">
                                <!-- <h4 class="invisible ">Create Company</h4> -->
                                {!! Form::model($data, ['id' => 'company-form', 'class' => 'form-horizontal','files'=> true]) !!}
                                    @include('admin.company.partials._form', ['submitButtonText' => 'Add'])
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
            <!-- .right-sidebar -->

            <!-- ============================================================== -->
            <!-- End Right sidebar -->
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

    {!! HTML::style('assets/css/uploader.css') !!}
    {!! HTML::style('assets/css/demo.css') !!}
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    {!! HTML::script('assets/js/jquery-migrate-1.2.1.min.js') !!}
    {!! HTML::script('assets/js/demo-preview.min.js') !!}
    {!! HTML::script('assets/js/dmuploader.min.js') !!}

    <script type="text/javascript">
        $addRecordRoute = '{{ URL::route('company.add') }}';
        $uploadImageRoute = '{{ URL::route('company.upload.image') }}';
        $uploadPdfRoute = '{{ URL::route('company.upload.pdf') }}';
        $redirectRoute = '{{ URL::route('super.admin.company') }}';
        $token = "{{ csrf_token() }}";
        $id = '{!! $id !!}';
        $viewOnly = '{!! $viewOnly !!}';
        $tempImageId = 0;
        $isUploadImage = false;

        $(document).ready(function() {
            if ($viewOnly) {
                $("#company-form :input").prop("disabled", true);
                $("input[type=button]").attr("disabled", "disabled");
            } else {
                console.log($viewOnly);
                ajaxStartStop();
                if ($id) {
                    $isUploadImage = true;
                }
                var quantity=1;
                $('.quantity-right-plus').click(function(e){
                    // Stop acting like a button
                    e.preventDefault();
                    // Get the field name
                    var quantity = parseInt($('#communities').val());
                    // If is not undefined
                    $('#communities').val(quantity + 1);
                    // Increment
                });

                $('.quantity-left-minus').click(function(e){
                    // Stop acting like a button
                    e.preventDefault();
                    // Get the field name
                    var quantity = parseInt($('#communities').val());
                    // If is not undefined
                    // Increment
                    if (quantity > 1) {
                        $('#communities').val(quantity - 1);
                    }
                });
                $type = 'uploadImage';
                renderAdmin();
                $type = 'uploadPdf';
                renderAdmin();
                $('#company-form').submit(function (e) {
                    e.preventDefault();
                    if ($isUploadImage == false) {
                        $message = '{{_lang('Please provide image')}}';
                        notificationMsg($message, 'error');
                        return false;
                    }
                    updateFormData();
                    $type = 'addRecord';
                    renderAdmin();
                });
            }

        })

        var updateFormData = function () {
            $formData = {
                '_token': $token,
                data:  $('#company-form').serialize(),
                tempImageId: $tempImageId,
                id: $id
            };
        }

    </script>

    {!! HTML::script('assets/js/admin.js') !!}

@stop