@extends('layouts.superadminlayout')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{_lang('Send Invite')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('Send Invite')}}</li>
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
                            {!! Form::open(['id' => 'data-form', 'class' => 'form-horizontal']) !!}
                                <div  id="loginform" class="create-post form-horizontal form-material">
                                <div class="post-checkbox-sec send-email-3nd-sec">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="icon-sec">
                                                    {!! Form::radio('type', 'email', null, ['id' => 'email','class' => 'send-message-to with-gap radio-col-green','required' => 'required', 'checked' => 'checked']) !!}
                                                    <label for="email">{{_lang('Email Invitation')}}</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="icon-sec">
                                                    {!! Form::radio('type', 'user', null, ['id' => 'user','class' => 'send-message-to with-gap radio-col-green','required' => 'required']) !!}
                                                    <label for="user">{{_lang('App Users')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                    <div class="row">
                                        <div class="col-6 post-input-section">
                                            <div class="row">
                                                <div class=" col-12">
                                                    <div class="tags-default m-b-30" id="tags-input">
                                                        <input class="form-control" type="text" value="" id="emails" name="emails" data-role="tagsinput" placeholder="{{_lang('Add Email')}}" />
                                                    </div>
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
                                                <div class=" col-12 search-bar-border" id="users-list">
                                                    <div class="form-group has-feedback has-search">
                                                        <i class="fa fa-search form-control-feedback"></i>
                                                        <input  placeholder="Search Users"  type="text" class="form-control" id="search" name="search" />
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
                                                <div class="btn-sec m-borrow-btn Invitation-btn">
                                                    <button class="btn btn-success">{{_lang('Send Invitation')}}</button>
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
        $communityId = '{!! $communityId !!}';
        $addRecordRoute = '{{ URL::route('send.invitation.community') }}';
        $getCommunitiesRoute = '{{ URL::route('super.admin.get.communities') }}';
        $token = "{{ csrf_token() }}";

        $(document).ready(function() {
            CKEDITOR.replace('description');
            showHideTags();
            $('body').on('click', '#checkbox_0', function() {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
            $('#data-form').submit(function (e) {
                e.preventDefault();
                $error = '';
                if ($('input:radio[name=type]:checked').val() == 'email') {
                    if ($('#emails').val() == '') {
                        $error += '{{_lang('Please add email')}}<br/>';
                    }
                } else {
                    if ($('#data-form :checkbox:checked').length < 1) {
                        $error += '{{_lang('Please select atleast one user')}}<br/>';
                    }
                }
                if (CKEDITOR.instances.description.getData() == '') {
                    $error += '{{_lang('Please add description')}}';
                }
                console.log($error);
                if ($error != '') {
                    notificationMsg($error, 'error');
                    return false;
                }
                updateFormData();
                $type = 'addRecord';
                renderAdmin();
            });

            $('.send-message-to').click(function (e) {
                showHideTags();
                renderCommunities();
            });
            $('#search').keydown(function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    renderCommunities();
                }
            });
        })

        /**
         * This is used to render communities/user data
         */
        var renderCommunities = function () {
            $formData = {
                '_token': $token,
                id: 'user',
                search: $('#search').val()
            };
            $type = 'getCommunitiesOrUsers';
            renderAdmin();
        }

        /**
         * This is used to show/hide tags
         */
        var showHideTags = function () {
            console.log($('input:radio[name=type]:checked').val());
            if ($('input:radio[name=type]:checked').val() == 'email') {
                $('#tags-input').show();
                $('#users-list').css('visibility', 'hidden');
            } else {
                $('#tags-input').hide();
                $('#users-list').css('visibility', 'visible');
            }
        }

        /**
         * This is used to update form data
         */
        var updateFormData = function () {
            $formData = {
                '_token': $token,
                data:  $('#data-form').serialize(),
                description: CKEDITOR.instances.description.getData()
            };
        }

    </script>

    {!! HTML::script('assets/js/admin.js') !!}

@stop