@extends('layouts.superadminlayout')

@section('content')
    <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{_lang('Create Community')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.company') !!}">{{_lang('Company')}}</a></li>
                <li class="breadcrumb-item">{{_lang('Create Community')}}</li>
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
                            <!-- <h4>Create Community</h4> -->
                            {!! Form::model($data, ['id' => 'community-form', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                <div class="col-6">
                                        <div class="row">
                                            <div class="form-group col-12">
                                                {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'title','required' => 'required', 'placeholder' => _lang('Community Name')]) !!}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description','required' => 'required', 'placeholder' => _lang('Description')]) !!}
                                            </div>
                                        </div>
                                        @if($isCompanyOrUserRole)
                                            <div class="row">
                                                <div class="form-group col-4">
                                                    <div class="checkbox checkbox-primary pull-left p-t-10">
                                                        {{Form::hidden('is_lock',0)}}
                                                        {!! Form::checkbox('is_lock', 1, null, ['class' => 'field', 'id' => 'is_lock']) !!}
                                                        <label for="is_lock">{{_lang('Lock Community')}}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-8">
                                                    {!! Form::text('password', null, ['class' => 'form-control', 'id' => 'password', 'placeholder' => _lang('Enter Password')]) !!}
                                                </div>
                                            </div>
                                        @endif
                                </div>
                                <div class="col-6">
                                    <div class="photo-box">
                                        <ul class="photo-list">
                                            <li id="column_image">
                                                @if(!empty($data->image))
                                                    <img src="{!! asset(uploadCommunityThumbNail.'/'.$data->image) !!}" alt="Image">
                                                @else
                                                    <img src="{!! asset('assets/images/placeholder.png') !!}">
                                                @endif
                                            </li>
                                            <li>
                                                <div class="myLabel uploader_image" id="drag-and-drop-zone">
                                                    <input type="file" name="file" id="file_image" />
                                                    <span>{{_lang('Browse')}}</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-3">
                                            <label class="comunity-lable">{{_lang('Borrow')}}</label>
                                        </div>
                                        <div class="photo-box col-9">
                                            <div class="photo-list community-375-img">
                                                <div id="column_borrow">
                                                    @if(!empty($data->borrow_image))
                                                        <img src="{!! asset(uploadCommunityThumbNail.'/'.$data->borrow_image) !!}" alt="Borrow Image">
                                                    @else
                                                        <img src="{!! asset('assets/images/placeholder-2.png') !!}">
                                                    @endif
                                                </div>
                                                <div class="clearfix">
                                                    <div class="myLabel uploader_borrow" id="drag-and-drop-zone">
                                                        <input type="file" name="file" id="file_borrow" />
                                                        <span>{{_lang('Browse')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-3">
                                            <label class="comunity-lable">{{_lang('Swap')}}</label>
                                        </div>
                                        <div class="photo-box col-9">
                                            <div class="photo-list community-375-img">
                                                <div id="column_swap">
                                                    @if(!empty($data->swap_image))
                                                        <img src="{!! asset(uploadCommunityThumbNail.'/'.$data->swap_image) !!}" alt="Swap Image">
                                                    @else
                                                        <img src="{!! asset('assets/images/placeholder-2.png') !!}">
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="myLabel uploader_swap" id="drag-and-drop-zone">
                                                        <input type="file" name="file" id="file_swap" />
                                                        <span>{{_lang('Browse')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-3">
                                            <label class="comunity-lable">{{_lang('Give Away')}}</label>
                                        </div>
                                        <div class="photo-box col-9">
                                            <div class="photo-list community-375-img">
                                                <div id="column_giveaway">
                                                    @if(!empty($data->give_away_image))
                                                        <img src="{!! asset(uploadCommunityThumbNail.'/'.$data->give_away_image) !!}" alt="Give away Image">
                                                    @else
                                                        <img src="{!! asset('assets/images/placeholder-2.png') !!}">
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="myLabel uploader_giveaway" id="drag-and-drop-zone">
                                                        <input type="file" name="file" id="file_giveaway" />
                                                        <span>{{_lang('Browse')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-3">
                                            <label class="comunity-lable">{{_lang('Wanted')}}</label>
                                        </div>
                                        <div class="photo-box col-9">
                                            <div class="photo-list community-375-img">
                                                <div id="column_wanted">
                                                    @if(!empty($data->wanted_image))
                                                        <img src="{!! asset(uploadCommunityThumbNail.'/'.$data->wanted_image) !!}" alt="Give away Image">
                                                    @else
                                                        <img src="{!! asset('assets/images/placeholder-2.png') !!}">
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="myLabel uploader_wanted" id="drag-and-drop-zone">
                                                        <input type="file" name="file" id="file_wanted" />
                                                        <span>{{_lang('Browse')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="privacy-doucment-sec">
                                        <div class="btn-sec">
                                            <button class="btn btn-success">@if(empty($isEdit)) {{_lang('Create')}} @else {{_lang('Update')}} @endif</button>
                                            <a class="btn btn-success" href="{!! URL::route('super.admin.community', ['id' => $companyId]) !!}">{{_lang('Cancel')}}</a>
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
        $companyId = '{!! $companyId !!}';
        $addRecordRoute = '{{ URL::route('community.add') }}';
        $uploadImageRoute = '{{ URL::route('community.upload.image') }}';
        $redirectRoute = '{{ URL::route('super.admin.community') }}/' + $companyId;
        $token = "{{ csrf_token() }}";
        $id = '{!! $id !!}';
        $isEdit = '{!! $isEdit !!}';
        $viewOnly = '{!! $viewOnly !!}';
        $isCompanyOrUserRole = '{!! $isCompanyOrUserRole !!}';
        $arrRequiredImages = ['{{_lang('borrow')}}', '{{_lang('swap')}}', '{{_lang('giveaway')}}', '{{_lang('wanted')}}'];
        $arrUploadedImages = [];
        $borrow = $swap = $giveaway = $wanted = '';

        $(document).ready(function() {
            if ($isCompanyOrUserRole) {
                setLockCommunity();
                $('#is_lock').click(function (e) {
                    setLockCommunity();
                });
            }
            if($viewOnly) {
                $("#community-form :input").prop("disabled", true);
                $("input[type=button]").attr("disabled", "disabled");
            } else {
                ajaxStartStop();
                $type = 'communityUploader';
                renderAdmin();
                if ($isEdit) {
                    $arrUploadedImages = ['{{_lang('borrow')}}', '{{_lang('swap')}}', '{{_lang('giveaway')}}', '{{_lang('wanted')}}'];
                }
                $('#community-form').submit(function (e) {
                    e.preventDefault();
                    $error = arrDifference($arrRequiredImages, $arrUploadedImages);
                    $errorFields = [];
                    if ($('#title').val() == '') {
                        alert('{{_lang('Please fill title')}}');
                        return false;
                    }
                    if ($('#description').val() == '') {
                        alert('{{_lang('Please fill description')}}');
                        return false;
                    }
                    if ($('#is_lock').is(':checked') && $('#password').val() == '') {
                        alert('{{_lang('Please fill password')}}');
                        return false;
                    }
                    if ($error.length > 0) {
                        $message = '{{_lang('Please fill following requirements')}}<br/>';
                        $.each($error, function (i, v) {
                            $counter = i + 1;
                            $message += $counter + ': ' + '{{_lang('Please upload')}} '+ v + ' {{_lang('image')}}<br/>' + "\n";
                        });
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
                data:  $('#community-form').serialize(),
                id: $id,
                company_id: $companyId
            };
        }

        var setLockCommunity = function () {
            if ($('#is_lock').is(':checked')) {
                $('#password').attr('disabled', false);
            } else {
                $('#password').attr('disabled', true);
                $('#password').val('');
            }
        }

    </script>

    {!! HTML::script('assets/js/admin.js') !!}

@stop