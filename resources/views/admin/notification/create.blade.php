@extends('layouts.superadminlayout')

@section('content')
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor notification-breadcrumb"></h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{_lang('Home')}}</a></li>
                <li class="breadcrumb-item notification-breadcrumb"></li>
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
                        <div id="notificationMessage"></div>
                        {!! Form::model($data, ['id' => 'data-form', 'class' => 'form-horizontal']) !!}
                        <div  id="loginform" class="create-post form-horizontal form-material">
                            <div class="post-checkbox-sec send-email-sec">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="icon-sec">
                                                <label class="icon-holer" for="swap"><i class="fa fa-envelope-o" aria-hidden="true"></i></label>
                                                {!! Form::radio('option', 'email', null, ['id' => 'email','class' => 'with-gap radio-col-green notification-option','required' => 'required', 'checked' => 'checked']) !!}
                                                 <label for="email">{{_lang('Email')}}</label>
                                            </div>
                                        </div>
                                        @if(empty($notificationHide))
                                            <div class="col-6">
                                            <div class="icon-sec">
                                                <label class="icon-holer borrow-img"  for="radio_40"><i class="fa fa-bell-o" aria-hidden="true"></i></label>
                                                {!! Form::radio('option', 'notification', null, ['id' => 'notification','class' => 'with-gap radio-col-green notification-option','required' => 'required']) !!}
                                                <label for="notification">{{_lang('Notification')}}</label>
                                            </div>
                                        </div>
                                        @endif
                                        {{--<div class="col-4">--}}
                                            {{--<div class="icon-sec">--}}
                                                {{--<label class="icon-holer wanted-img"  for="radio_41"><i class="fa fa-comment-o" aria-hidden="true"></i></label>--}}
                                                {{--{!! Form::radio('option', 'message', null, ['id' => 'message','class' => 'with-gap radio-col-green notification-option','required' => 'required']) !!}--}}
                                                {{--<label for="message">{{_lang('Message')}}</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    </div>
                            </div>
                            <div class="post-checkbox-sec send-email-2nd-sec">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="icon-sec">
                                                {!! Form::radio('type', 'community', null, ['id' => 'community','class' => 'send-message-to with-gap radio-col-green','required' => 'required', 'checked' => 'checked']) !!}
                                                <label for="community">{{_lang('Community')}}</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="icon-sec">
                                                {!! Form::radio('type', 'user', null, ['id' => 'user','class' => 'send-message-to with-gap radio-col-green','required' => 'required']) !!}
                                                <label for="user">{{_lang('Users')}}</label>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                                <div class="row">
                                    <div class="col-6 post-input-section">
                                        <div class="row m-b-30">
                                            <div class=" col-12">
                                                {!! Form::select('device_type', ['' => 'All', 'android' => 'Android', 'ios' => 'IOS'], null, ['id' => 'device_type','class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="row m-b-30">
                                            <div class=" col-12">
                                                {!! Form::text('subject', null, ['class' => 'form-control', 'id' => 'subject','required' => 'required', 'placeholder' => _lang('Subject')]) !!}
                                            </div>
                                        </div>
                                        <div class="row m-b-30">
                                            <div class=" col-12">
                                                {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description','required' => 'required', 'placeholder' => _lang('Description')]) !!}
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row m-b-30">
                                            <div class=" col-12 search-bar-border">

                                                <div class="form-group has-feedback has-search">
                                                    <i class="fa fa-search form-control-feedback"></i>
                                                    <input id="search" name="search"  type="text" class="form-control" placeholder="{{_lang('Search')}}">
                                                </div>
                                                <div class="table-responsive scroll-table">
                                                    <table class="table upload-image-table">
                                                        <tbody id="community-users">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="privacy-doucment-sec">
                                            <div class="btn-sec m-borrow-btn">
                                                <button class="btn btn-success">{{_lang('Send')}}</button>
                                                <button class="btn btn-success">{{_lang('Cancel')}}</button>
                                            </div>
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
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
    <!-- </div> -->
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
    $addRecordRoute = '{{ URL::route('notification.add') }}';
    $redirectRoute = '{{ URL::route('super.admin.notifications') }}';
    $getCommunitiesRoute = '{{ URL::route('super.admin.get.communities') }}';
    $reloadAfterDelete = false;
    $token = "{{ csrf_token() }}";
    $notification_id = '{!! $id !!}';
    $data = '{!! $data !!}';
    $(document).ready(function () {
        updateBreadCrumb();
        showHideDeviceType();
        showhideCkeditor();
        if ($data != '' && $('input:radio[name=option]:checked').val() == 'email') {
            CKEDITOR.instances.description.setData($data.description);
        }
        if ($('.send-message-to').is(':checked')) {
            renderCommunities();
        }
        $('#device_type').change(function () {
            renderCommunities();
        });
        $('#search').val('');
        $('#search').keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                renderCommunities();
            }
        });
        $('.send-message-to').click(function (e) {
            updateBreadCrumb();
            showHideDeviceType();
            $formData = {
                '_token': $token,
                id: this.id,
                notification_id: $notification_id,
                device_type: $('#device_type').val()
            };
            $type = 'getCommunitiesOrUsers';
            renderAdmin();
        });

        $('#data-form').submit(function(e) {
           e.preventDefault();
            if ($(".checkbox:checked").length < 1) {
                alert('{{_lang('Please select atleast one checkbox')}}');
                return false;
            }
            updateFormData();
            $type = 'addRecord';
            renderAdmin();
        });

        $('.notification-option').click(function (e) {
            $('#description').val('');
            showhideCkeditor();
            updateBreadCrumb();
        });

        $('body').on('click', '#checkbox_0', function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

    });

    var updateBreadCrumb = function () {
        $optionValue = $typeValue = '';
        if (typeof ($('input:radio[name=option]:checked').val()) != 'undefined')
            $optionValue = $('input:radio[name=option]:checked').val();
        if (typeof ($('input:radio[name=type]:checked').val()) != 'undefined')
            $typeValue = $('input:radio[name=type]:checked').val();
        $('.notification-breadcrumb').html(_lang('Send')+' ' + _lang($optionValue) +' '+_lang('to')+' '+ _lang($typeValue));
    };


    var showHideDeviceType = function () {
        if ($('input:radio[name=type]:checked').val() == 'user') {
            $('#device_type').show();
        } else {
            $('#device_type').hide();
        }
    }

    var showhideCkeditor = function () {
        if ($('input:radio[name=option]:checked').val() == 'email') {
            CKEDITOR.replace('description');
        } else {
            if (typeof (CKEDITOR.instances.description) != 'undefined') {
                CKEDITOR.instances.description.setData('');
                CKEDITOR.instances['description'].destroy();
            }
        }
    }

    /**
     * This is used to render communities/user data
     */
    var renderCommunities = function () {
        $formData = {
            '_token': $token,
            id: $('input:radio[name=type]:checked').val(),
            notification_id: $notification_id,
            device_type: $('#device_type').val(),
            search: $('#search').val()
        };
        $type = 'getCommunitiesOrUsers';
        renderAdmin();
    }

    /**
     * This is used to update form data
     */
    var updateFormData = function () {
        $description = $('#description').val();
        if ($('input:radio[name=option]:checked').val() == 'email') {
            $description = CKEDITOR.instances.description.getData();
        }
        $formData = {
            '_token': $token,
            data:  $('#data-form').serialize(),
            description: $description,
            id: $notification_id
        };
    }
    $(document).ready(function() {
       CKEDITOR.config.removePlugins = 'Save,Print,Preview,Find,About,Maximize,ShowBlocks';
    });
</script>

{!! HTML::script('assets/js/admin.js') !!}

@stop