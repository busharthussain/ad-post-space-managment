@extends('layouts.superadminlayout')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{_lang('Messages')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('Chat')}}</li>
                    <li class="breadcrumb-item">{{_lang('Username')}}</li>
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
                            <div class="row m-b-30">
                                <div class="col-12">
                                    <h4>{!! $name !!} > {{_lang('Messages')}}</h4>
                                    <div class="table-responsive users-list">
                                        <table class="table table-hover upload-image-table">
                                            <h3>{{_lang('Received Messages')}}</h3>
                                            <tbody id="post-messages">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
        $renderRoute = '{{ URL::route('get.post.messages') }}';
        $chatRoute = '{{ URL::route('post.chat') }}';
        $defaultType = 'renderPostMessages';
        $token = "{{ csrf_token() }}";
        $relativePath = "{{ $relative_path }}";
        $page = 1;
        $postId = '{!! $id !!}';
        $assetURL = '{!! $assetUrl !!}';

        $(document).ready(function() {
            updateFormData();
            renderPostMessages();
        });

        var updateFormData = function () {
            $formData = {
                '_token': $token,
                page:  $page,
                id: $postId
            };
        }

        var renderPostMessages = function () {
            ajaxStartStop();
            $.ajax({
                url: $renderRoute,
                type: 'POST',
                data: $formData,
                success: function (data) {
                    $html = '';
                    $.each(data, function (i,v) {
                        $firstMessage = '';
                        $borrowFromTo = '';
                        if (v.date_to != '' && v.date_from != null) {
                            $borrowFromTo = '<div>Borrow From: ' + v.date_from + 'Borrow To: ' + v.date_to + '</div>\n';
                        }
                        if(typeof (v.first_image) != 'undefined' && v.first_image !=  null && v.first_image != '') {
                            $firstMessage = '<div class="chat-img"><img src="' + $assetURL + '/' + $relativePath + '/' + v.first_image + '" alt="image"></div>\n';
                        }

                        $html += '<tr>\n' +
                                    '<td width="17%">\n' +
                                        '<img src="'+$assetURL+'/'+v.relative_path+'/'+v.image+'" alt="image">\n' +
                                    '</td>\n' +
                                    '<td width="65%">\n' +
                                        '<span class="user-name"><a href="'+$chatRoute+'/'+v.conversation_id+'">'+v.name+'</a></span>\n' +
                                        '<span class="user-detials">'+v.message+'</span>\n' +
                                         $borrowFromTo +
                                    '</td>\n' +
                                    '<td width="18%">'+v.created_at+'</td>\n' +
                                '</tr>';
                    });
                    $('#post-messages').html($html);
                },
                error: function ($error) {
                    notificationMsg($error, error);
                }
            });
        };

    </script>

    {!! HTML::script('assets/js/admin.js') !!}

@stop