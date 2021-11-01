@extends('layouts.superadminlayout')

@section('content')


    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">@if(empty($id)) {{_lang('Create')}} @else {{_lang('Edit')}} @endif {{_lang('Ads')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.ad') !!}">{{_lang('Ads Space')}}</a></li>
                    <li class="breadcrumb-item">@if(empty($id)) {{_lang('Create')}} @else {{_lang('Edit')}} @endif {{_lang('Ads')}}</li>
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
                          {!! Form::model($data, ['id' => 'add-form', 'class' => 'form-horizontal','files'=> true]) !!}
                            <div  id="loginform" class="create-post form-horizontal form-material">
                                <div class="row">
                                    <div class="col-8">
                                        <!-- <h4>@if(empty($id)) Create @else Edit @endif Ad</h4> -->
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group pull-right">
                                            <div class="switch">
                                                <label>
                                                    {{_lang('Active')}}
                                                    <input type="checkbox" id="active" name="active" @if(!empty($data->active) || empty($data)) checked @endif>
                                                    <span class="lever"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 post-input-section">
                                            <div class="row m-b-30">
                                                <div class="col-8">
                                                    {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'title','required' => 'required', 'placeholder' => _lang('Name')]) !!}
                                                </div>
                                            </div>
                                            <div class="row m-b-30">
                                                <div class="col-8">
                                                    {!! Form::text('link', null, ['class' => 'form-control', 'id' => 'link','required' => 'required', 'placeholder' => _lang('Link')]) !!}
                                                </div>
                                            </div>
                                            <div class="row m-b-30">
                                                <div class="col-8">
                                                    <label>{{_lang('Start')}}:</label>
                                                    <div class="right-inner-addon date datepicker" data-date-format="yyyy-mm-dd">
                                                        {!! Form::text('start_time', null, ['class' => 'form-control', 'id' => 'start_time','required' => 'required']) !!}
                                                        <img src="{!! asset('assets/images/calendar.png') !!}" alt="icon">
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-8">
                                                    <label>{{_lang('End')}}:</label>
                                                    <div class="right-inner-addon" data-date-format="yyyy-mm-dd">
                                                        {!! Form::text('end_time', null, ['class' => 'form-control', 'id' => 'end_time','required' => 'required']) !!}
                                                        <img src="{!! asset('assets/images/calendar.png') !!}" alt="icon">
                                                    </div>
                                                </div>
                                            </div>
                                            @include('admin.partials._common_filters')
                                        <div class="row">
                                            <div class=" col-12">
                                                <label style="font-weight: 600;margin-bottom: 10px;">{{_lang('Type')}}</label>
                                                {!! Form::select('type', ['0' => _lang('Top'), '1' => _lang('Classified')], null, ['id' => 'type','class' => 'form-control']) !!}
                                            </div>
                                            <div class="col-12">
                                                <label style="font-weight: 600;margin-bottom: 10px;">{{_lang('Options')}}</label>
                                                {!! Form::select('parent_category_id',['0' => 'All'] + $arrOptions, null, ['id' => 'option','class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="row m-t-10 m-b-30">
                                            <div class=" col-4">
                                                <label class="post-lable">{{_lang('Post Images')}}</label>
                                            </div>
                                            <div class=" col-8">
                                                <div id="drag-and-drop-zone" class="myLabel uploader">
                                                    <input type="file" name="file" id="file" accept="image/*" multiple />
                                                    <span>{{_lang('Upload')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-30">
                                            <div class=" col-12">
                                                <div class="table-responsive scroll-table">
                                                    <table class="table table-hover upload-image-table">
                                                        <tbody id="post_images">
                                                        @if(!empty($images))
                                                            @foreach($images as $row)
                                                                <tr id="row_{!! $row['id'] !!}">
                                                                    <td width="17%"><a href="javascript: void(0)"><img src="{!! asset(uploadAdThumbNailImage.'/'.$row['thumbnail_image']) !!}" alt="images"></a></td>
                                                                    <td width="70%">{!! substr($row['thumbnail_image'], 0, 8) !!}</td>
                                                                    <td width="13%"><a href="javascript: void(0)" id="delete_{!! $row['id'] !!}" class="delete_content">{{_lang('DELETE')}}</a></td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="privacy-doucment-sec">
                                            <div class="btn-sec m-borrow-btn">
                                                <button class="btn btn-success">@if(empty($id)) {{_lang('Create')}} @else {{_lang('Update')}} @endif</button>
                                                <a href="{!! URL::route('super.admin.ad') !!}" class="btn btn-success">{{_lang('Cancel')}}</a>
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


{!! HTML::style('assets/css/uploader.css') !!}
{!! HTML::style('assets/css/demo.css') !!}
{!! HTML::script('assets/js/jquery-migrate-1.2.1.min.js') !!}
{!! HTML::script('assets/js/demo-preview.min.js') !!}
{!! HTML::script('assets/js/dmuploader.min.js') !!}

<script type="text/javascript">
    $addRecordRoute = '{{ URL::route('ad.add') }}';
    $uploadImageRoute = '{{ URL::route('ad.upload.image') }}';
    $companiesDropDownRoute = '{{ URL::route('get.companies') }}';
    $communitiesDropDownRoute = '{{ URL::route('get.communities') }}';
    $deleteRoute = '{{ URL::route('delete.ad.image') }}';
    $redirectRoute = '{{ URL::route('super.admin.ad') }}';
    $reloadAfterDelete = false;
    $token = "{{ csrf_token() }}";
    $id = '{!! $id !!}';
    $assetURL = '{!! asset('') !!}';
    $batchId = '{!! $batchId !!}';
    $viewOnly = '{!! $viewOnly !!}';
    $arrCompanies = [];
    $tempImageId = '';
    $appendImage = true;
    $active = 0;
    $(document).ready(function() {
        ajaxStartStop();
        jQuery('#start_time').datetimepicker();
        jQuery('#end_time').datetimepicker();
        window.asd = $('#companies').SumoSelect({ csvDispCount: -1, selectAll:true, captionFormatAllSelected: "{{_lang('All Companies')}}", placeholder: _lang("Select Companies")});
        window.asd = $('#communities').SumoSelect({ csvDispCount: -1, selectAll:true, captionFormatAllSelected: "{{_lang('All Communities')}}", placeholder:_lang('Select Communities')});
        $('select.SlectBox')[0].sumo.unSelectAll();
        if ($id) {
            $keyIndex = 'companies';
            $selectedIndex = $('#strCompaniesIndex').val();
            setSelected();
            $keyIndex = 'communities';
            $selectedIndex = $('#strCommunitiesIndex').val();
            setSelected();
        }
        if ($viewOnly) {
            $("#add-form :input").prop("disabled", true);
            $("input[type=button]").attr("disabled", "disabled");
            $('#companies').attr("disabled", false);
            $('#communities').attr("disabled", false);
            $(".opt").off('click');
            $(".select-all").off('click');
        } else {
            $type = 'uploadImage';
            renderAdmin();
            $('#companies').change(function (e) {
                e.preventDefault();
                $arrCompanies = $('#companies').val();
                $type = 'communitiesDropDown';
                updateFormData();
                renderAdmin();
            });
            $('#add-form').submit(function (e) {
                e.preventDefault();
                $error = '';
                var startDateTime = $('#start_time').val();
                var endDateTime = $('#end_time').val();
                if (startDateTime == '') {
                    $error += '{{_lang('Please provide start date')}}<br/>';
                }
                if (endDateTime == '') {
                    $error += '{{_lang('Please provide end date')}}<br/>';
                }
                var startDateTime = new Date(startDateTime);
                var endDateTime = new Date(endDateTime);
                if (startDateTime.getTime() > endDateTime.getTime()) {
                    $error += '{{_lang('Start dateTime should be less than end date')}}.<br/>';
                }

                if ($('#title').val() == '') {
                    $error += '{{_lang('Please add Title')}}<br/>';
                }
                if ($('#link').val() == '') {
                    $error += '{{_lang('Please add Link')}}<br/>';
                }
                if ($('#communities').val() == '') {
                    $error += '{{_lang('Please select communities')}}<br/>';
                }
                var communityValue = $('#communities').val();
                $isSelectCommunity = false;
                $.each(communityValue, function (i, v) {
                    if (v > 5) {
                        $isSelectCommunity = true;
                    }
                });
                if ($isSelectCommunity == true && $('#companies').val() == '') {
                    $error += '{{_lang('Please select companies')}}<br/>';
                }
                if ($("#post_images tr").length < 1) {
                    $error += '{{_lang('Please upload atleast one image')}}<br/>';
                }
                if ($("#post_images tr").length > 3) {
                    $error += '{{_lang('Please upload maximum 3 images')}}<br/>';
                }
                if ($error != '') {
                    notificationMsg($error, 'error');
                    return false;
                }

                if ($('#active').is(":checked")) {
                    $active = 1;
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
            data:  $('#add-form').serialize(),
            arrCompanies: $('#companies').val(),
            arrCommunities: $('#communities').val(),
            batchId: $batchId,
            active: $active,
            id: $id
        };
    }

    var setSelected = function () {
        var selectedIndex = $selectedIndex.split(",");
        console.log(selectedIndex);
        $.each(selectedIndex, function (i, v) {
            if (v) {
                console.log(v)
                $('#' + $keyIndex)[0].sumo.selectItem(parseInt(v));
            }
        });
    }

</script>

{!! HTML::script('assets/js/admin.js') !!}

@stop