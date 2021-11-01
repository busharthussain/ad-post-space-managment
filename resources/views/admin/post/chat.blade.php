@extends('layouts.superadminlayout')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{_lang('Chat')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('Chat')}}</li>
                    <!-- <li class="breadcrumb-item">Create Company</li> -->
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
                        <form>
                            <div class="card-body">
                                <h4 class="card-title">{{_lang('Recent Chats with')}} {!! $userName !!}</h4>
                                <h6 class="card-subtitle">{!! $postName !!}</h6>
                                <div class="chat-box">
                                    <!--chat Row -->
                                    <ul class="chat-list" id="post-chat">
                                        <ul class="chat-list"></ul>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body b-t">
                                <div class="row">
                                    <div class="col-8">
                                        <textarea placeholder={{_lang('Type your message here')}} id="message" style="min-height:inherit !important ;" name="message" class="form-control b-0"></textarea>
                                    </div>
                                    <div class="col-4 text-right">
                                        <div class="inline-btn">
                                            <div id="drag-and-drop-zone" class="uploader ">
                                                <div class="uploader myLabel">
                                                    <input type="file" name="file" id="file" accept="image/*">
                                                    <span><i class="fa fa-paperclip"></i></span>
                                                </div>
                                            </div>
                                            <a href="javascript: void(0)" id="send-message" class="btn btn-info btn-circle btn-lg"><i class="fa fa-paper-plane-o"></i> </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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

    {!! HTML::style('assets/css/uploader.css') !!}
    {!! HTML::style('assets/css/demo.css') !!}
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    {!! HTML::script('assets/js/jquery-migrate-1.2.1.min.js') !!}
    {!! HTML::script('assets/js/demo-preview.min.js') !!}
    {!! HTML::script('assets/js/dmuploader.min.js') !!}
    {!! HTML::script('assets/js/jquery.colorbox.js') !!}

    <link rel="stylesheet" type="text/css" href="{!! asset('/assets/css/colorbox.css') !!}"/>


    <script type="text/javascript">
        $renderRoute = '{{ URL::route('get.post.chats') }}';
        $sendPostMessageRoute = '{{ URL::route('send.post.message') }}';
        $uploadImageRoute = '{{ URL::route('post.message.image') }}';
        $defaultType = 'renderPostChats';
        $token = "{{ csrf_token() }}";
        $conversation_id = '{!! $id !!}';
        $batchId = $conversation_id;
        $tempImageId = 0;
        $page = 1;
        $asc = 'asc';
        $desc = 'desc';
        $sortType  = 'desc';
        $sortColumn = 'id';
        $assetURL = '{!! $assetUrl !!}';
        $relative_path = '{!! $relative_path !!}';
        $relativePathPost = '{!! $relativePathPost !!}';
        $imageType = true;
        $isChatBox = true;

        $(document).ready(function() {
            $type = 'uploadImage';
            renderAdmin();
            $type = $defaultType;
            updateFormData();
            renderAdmin();
            $('#message').keydown(function (e) {
                if (e.keyCode == 13) {
                    $type = 'sendPostMessage';
                    updateFormData();
                    renderAdmin();
                    $('#message').val('');
                }
            });

            $('#send-message').click(function (e) {
                e.preventDefault();
                if ($('#message').val() != '') {
                    $type = 'sendPostMessage';
                    updateFormData();
                    renderAdmin();
                    $('#message').val('');
                }
            })
        });

        var updateFormData = function () {
            $formData = {
                '_token': $token,
                page:  $page,
                sortType: $sortType,
                sortColumn: $sortColumn,
                message: $('#message').val(),
                conversation_id: $conversation_id
            };
        }

    </script>

    {!! HTML::script('assets/js/admin.js') !!}

@stop