@extends('layouts.superadminlayout')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">@if(empty($id)){{_lang('Create')}} @else {{_lang('Edit')}} @endif {{_lang('Post')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.post') !!}">{{_lang('All Posts')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('Create Post')}}</li>
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
            {!! Form::model($data, ['id' => 'post-form', 'class' => 'demo-radio-button', 'novalidate' => true]) !!}
                <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="notificationMessage"></div>
                            <div  id="loginform" class="create-post form-horizontal form-material">
                                <div class="row">
                                    <div class="col-8">
                                        <!-- <h4>Create Post</h4> -->
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
                                    <div class="post-checkbox-sec">
                                            <div class="row">
                                                @foreach($parentCategories as $row)
                                                    <div class="col-3">
                                                        <div class="icon-sec">
                                                            <label class="icon-holer borrow-img{!! $row->id !!}" for="swap"><img src="{!! asset('assets/images/'.$row->image.'') !!}" alt="icon"></label>
                                                            <input @if($selectedParentCategory == $row->id) checked @endif name="parent_category_id" type="radio" value="{!! $row->id !!}" id="parent_{!! $row->id !!}" class="with-gap radio-col-green parent-categories" />
                                                            <label for="parent_{!! $row->id !!}">{{_lang($row->title)}}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 post-input-section">
                                            @php
                                                $class = '';
                                                if ($isCompanyRole) {
                                                    $class = 'hide';
                                                }
                                            @endphp
                                                 <div class="row m-b-30 {!! $class !!}">
                                                    <div class=" col-5">
                                                        <label for="first_name">{{_lang('Select Company/s')}}</label>
                                                    </div>
                                                    <div class=" col-7">
                                                        <select id="companies" name="companies" class="SlectBox custom-form" multiple>
                                                            @php
                                                                $counter = 0;
                                                                $strCompaniesIndex = '';
                                                            @endphp
                                                            @if(!empty($arrCompanies))
                                                                @foreach($arrCompanies as $key => $row)
                                                                    @php
                                                                        if (!empty($selectedCompanies) && in_array($key,$selectedCompanies))
                                                                                $strCompaniesIndex .= $counter.',';
                                                                        $counter ++;
                                                                    @endphp
                                                                    <option value="{!! $key !!}">{{_lang( $row)}} </option>
                                                                @endforeach
                                                            @endif
                                                            <input type="hidden" name="strCompaniesIndex" id="strCompaniesIndex" value="{!! $strCompaniesIndex !!}">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row m-b-30">
                                                    <div class=" col-5">
                                                        <label for="first_name">{{_lang('Select Communities/s')}}</label>
                                                    </div>
                                                    <div class=" col-7">
                                                        <select id="communities" name="communities" class="SlectBox" multiple>
                                                            @php
                                                                $counter = 0;
                                                                $strCommunitiesIndex = '';
                                                            @endphp
                                                            @if(!empty($arrCommunities))
                                                                @foreach($arrCommunities as $key => $row)
                                                                    @php
                                                                        if (!empty($selectedCommunities) && in_array($key,$selectedCommunities))
                                                                            $strCommunitiesIndex .= $counter.',';
                                                                        $counter ++;
                                                                    @endphp
                                                                    <option value="{!! $key !!}">{{_lang($row)}} </option>
                                                                @endforeach
                                                            @endif
                                                            <input type="hidden" name="strCommunitiesIndex" id="strCommunitiesIndex" value="{!! $strCommunitiesIndex !!}">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row m-b-30">
                                                    <div class=" col-6">
                                                        {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'title','required' => 'required', 'placeholder' => _lang('Title')]) !!}
                                                    </div>
                                                    <div class=" col-6">
                                                        {!! Form::select('category_id', $categories, null, ['class' => 'form-control','required' => 'required', 'id' => 'category_id' ])!!}
                                                    </div>
                                                </div>
                                                <div class="row m-b-30">
                                                    <div class=" col-12">
                                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description','required' => 'required', 'placeholder' => _lang('Description')]) !!}
                                                    </div>
                                                </div>
                                            <div class="row m-b-30 post_images_categories">
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
                                            <div class="row m-b-30 post_images_categories">
                                                <div class=" col-12">
                                                    <div class="table-responsive scroll-table">
                                                        <table class="table table-hover upload-image-table">
                                                            <tbody id="post_images">
                                                            @if(!empty($images))
                                                                @foreach($images as $row)
                                                                    <tr id="row_{!! $row['id'] !!}">
                                                                        <td width="17%"><a href="javascript: void(0)"><img src="{!! asset(uploadPostThumbNailImage.'/'.$row['thumbnail_image']) !!}" alt="images"></a></td>
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
                                                <div class="row m-b-30 optional_category_data" id="main_category_3" style="display: none;">
                                                    <div class=" col-12">
                                                        <div id="wanted-img-holder" class="wanted-img-holder">
                                                            <img src="{!! asset('assets/images/wanted-placeholder.jpg') !!}" alt="placeholder" />
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="panel-body demo-panel-files" id='demo-files'></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row m-b-30">
                                                <div class="col-12">
                                                    <div class="tags-default">
                                                        <input class="form-control" type="text" name="tags" id="tags" value="{!! $tags !!}" data-role="tagsinput" placeholder="{{_lang('add tags')}}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-30">
                                                <div class="col-8">
                                                    {!! Form::text('zip_code', null, ['class' => 'form-control', 'id' => 'zip_code','required' => 'required', 'placeholder' => _lang('Zip Code')]) !!}
                                                </div>
                                            </div>
                                            <div class="row m-b-30">
                                                <div class="col-8">
                                                    {!! Form::text('city', null, ['class' => 'form-control', 'id' => 'city','required' => 'required', 'placeholder' => _lang('City')]) !!}
                                                </div>
                                            </div>
                                            <div class="row m-b-30 optional_category_data" style="display: none;" id="main_category_1">
                                                <div class="col-12">
                                                    <label class="m-b-20">{{_lang('Which categories do you to swap with in?')}}</label>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @foreach($categoriesList as $key => $row)
                                                                <div class="checkbox col-4 checkbox-primary pull-left p-t-0">
                                                                    <input @if(!empty($selectedCategories) && in_array($key, $selectedCategories)) checked @endif id="category_{!! $key !!}" type="checkbox" value="{!! $key !!}" name="child_categories[]">
                                                                    <label for="category_{!! $key !!}"> {{_lang($row)}}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-30 optional_category_data" style="display: none;" id="main_category_2">
                                                <div class="col-6">
                                                    <label>{{_lang('From')}}:</label>
                                                    <div class="right-inner-addon" data-date-format="yyyy-mm-dd">
                                                        {!! Form::text('borrow_from', null, ['class' => 'form-control', 'id' => 'borrow_from','required' => 'required']) !!}
                                                        <img src="{!! asset('assets/images/calendar.png') !!}" alt="icon">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label>To:</label>
                                                    <div class="right-inner-addon date datepicker" data-date-format="yyyy-mm-dd">
                                                        {!! Form::text('borrow_to', null, ['class' => 'form-control', 'id' => 'borrow_to','required' => 'required']) !!}
                                                        <img src="{!! asset('assets/images/calendar.png') !!}" alt="icon">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-30" id="product_condition_box">
                                                <div class=" col-5">
                                                    <label class="m-t-10" for="first_name">{{_lang('Product Condition')}}</label>
                                                </div>
                                                <div class="col-7">
                                                    {!! Form::select('product_condition_id', $productConditions, null, ['class' => 'form-control', 'id' => 'product_condition_id']) !!}
                                                </div>
                                            </div>
                                            <div class="privacy-doucment-sec">
                                                <div class="btn-sec">
                                                    <button class="btn btn-success">@if(empty($id)){{_lang('Create')}} @else {{_lang('Update')}} @endif</button>
                                                    <a href="{!! URL::route('super.admin.post') !!}" class="btn btn-success">{{_lang('Cancel')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::token() !!}
            {!! Form::close() !!}
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
    $addRecordRoute = '{{ URL::route('post.add') }}';
    $uploadImageRoute = '{{ URL::route('post.upload.image') }}';
    $companiesDropDownRoute = '{{ URL::route('get.companies') }}';
    $communitiesDropDownRoute = '{{ URL::route('get.communities') }}';
    $deleteRoute = '{{ URL::route('delete.post.image') }}';
    $redirectRoute = '{{ URL::route('super.admin.post') }}';
    $reloadAfterDelete = false;
    $token = "{{ csrf_token() }}";
    $id = '{!! $id !!}';
    $assetURL = '{!! asset('') !!}';
    $tags = '{!! $tags !!}';
    $viewOnly = '{!! $viewOnly !!}';
    $selectedParentCategory = '{!! $selectedParentCategory !!}';
    $categoryImages = JSON.parse('{!! $categoryImages !!}');
    $batchId = '{!! $batchId !!}';
    $arrCompanies = [];
    $tempImageId = '';
    $appendImage = true;
    $checkedParentId = '{!! $selectedParentCategory !!}';
    $active = 0;
    $(document).ready(function() {
        window.asd = $('#companies').SumoSelect({
            csvDispCount: -1,
            selectAll: true,
            captionFormatAllSelected: "{{_lang('All Companies')}}",
            placeholder: '{{_lang('Select Companies')}}'
        });
        window.asd = $('#communities').SumoSelect({
            csvDispCount: -1,
            selectAll: true,
            captionFormatAllSelected: "{{_lang('All Communities')}}",
            placeholder: '{{_lang('Select Communities')}}'
        });
        $('select.SlectBox')[0].sumo.unSelectAll();
//        if ($id) {
            $keyIndex = 'companies';
            $selectedIndex = $('#strCompaniesIndex').val();
            setSelected();
            $keyIndex = 'communities';
            $selectedIndex = $('#strCommunitiesIndex').val();
            setSelected();
//        }

        if ($selectedParentCategory == 3) {
//            $('.post_images_categories').hide();
            $categoryId = $('#category_id option:selected').val();
            $categoryImage = $categoryImages[$categoryId];
            console.log($categoryImage);
//                $assetURL
            $('#wanted-img-holder').html('<img src="' + $assetURL + 'assets/wanted/' + $categoryImage + '">');
        }
        if ($selectedParentCategory == 2 || $selectedParentCategory == 3) {
            hideProductCondition();
        }

        if ($viewOnly) {
            $("#post-form :input").prop("disabled", true);
            $("input[type=button]").attr("disabled", "disabled");
            $('#companies').attr("disabled", false);
            $('#communities').attr("disabled", false);
            $(".opt").off('click');
            $(".select-all").off('click');
        } else {
            ajaxStartStop();
            $("#borrow_to").datepicker({minDate: 0});
            $("#borrow_from").datepicker({minDate: 0});
            $type = 'uploadImage';
            renderAdmin();
            $('.optional_category_data').hide();
            $('#main_category_' + $selectedParentCategory).show();
            $('.parent-categories').click(function (e) {
                var id = $(this).attr('id');
                $checkedParentId = id.split('_')[1];
                if ($checkedParentId == 2 || $checkedParentId == 3) {
                    hideProductCondition();
                } else {
                    $('#product_condition_box').show();
                }
                console.log(id);
                $('.optional_category_data').hide();
                $('#main_category_' + $checkedParentId).show();
//                if ($checkedParentId == 3) {
//                    $('.post_images_categories').hide();
//                } else {
//                    $('.post_images_categories').show();
//                }
                refreshForm();
                $('#category_id').val('');
            });

            $('#companies').change(function (e) {
                e.preventDefault();
                $arrCompanies = $('#companies').val();
                $type = 'communitiesDropDown';
                updateFormData();
                renderAdmin();
            });

            $('#category_id').change(function (e) {
                e.preventDefault();
                if ($checkedParentId == 3) {
                    $categoryId = $('#category_id option:selected').val();
                    $categoryImage = $categoryImages[$categoryId];

                    if('{{get_lang()}}' == 'english'){
                        var imgSrc = $assetURL + 'assets/wanted/' + $categoryImage;
                    }
                    if('{{get_lang()}}' == 'dutch'){
                        var imgSrc = $assetURL + 'assets/wanted/danish/' + $categoryImage;
                    }
                    /*alert(imgSrc);*/
                    $('#wanted-img-holder').html('<img src="' +imgSrc+ '">');
                }
            });

            $('#post-form').submit(function (e) {
                e.preventDefault();
                $error = '';
                if ($checkedParentId == 2) {
                    var StartDate = $('#borrow_from').val();
                    var EndDate = $('#borrow_to').val();
                    var eDate = new Date(EndDate);
                    var sDate = new Date(StartDate);
                    if (StartDate != '' && StartDate != '' && sDate > eDate) {
                        $error += '{{_lang('Please ensure that the End Date is greater than or equal to the Start Date')}}.';
                    }
                    if (StartDate == '') {
                        $error += '{{_lang('Please provide start date')}}<br/>';
                    }
                    if (EndDate == '') {
                        $error += '{{_lang('Please provide end date')}}<br/>';
                    }
                }

                if ($('#communities').val() == '') {
                    $error += '{{_lang('Please select communities')}}<br/>';
                }
                var communityValue = $('#communities').val();
                $isSelectCommunity = false;
                $.each(communityValue, function (i, v) {
                    console.log(v)
                    if (v > 5) {
                        $isSelectCommunity = true;
                    }
                });
                if ($isSelectCommunity == true && $('#companies').val() == '') {
                    $error += '{{_lang('Please select companies')}}<br/>';
                }
                if ($('#category_id').val() == '') {
                    $error += '{{_lang('Please select category')}}<br/>';
                }
                if ($('#tags').val() == '') {
                    $error += '{{_lang('Please provide tags')}}<br/>';
                }
                if ($('#title').val() == '') {
                    $error += '{{_lang('Please provide title')}}<br/>';
                }
                if ($('#zip_code').val() == '') {
                    $error += '{{_lang('Please provide zip code')}}<br/>';
                }
                if ($('#city').val() == '') {
                    $error += '{{_lang('Please provide city')}}<br/>';
                }
                if ($('#description').val() == '') {
                    $error += '{{_lang('Please provide description')}}<br/>';
                }
                if ($checkedParentId != 3) {
                    if ($("#post_images tr").length < 1) {
                        $error += '{{_lang('Please upload atleast one image')}}<br/>';
                    }
                    if ($("#post_images tr").length > 5) {
                        $error += '{{_lang('Please upload maximum 5 images')}}<br/>';
                    }
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
    });

    var updateFormData = function () {
        $formData = {
            '_token': $token,
            data:  $('#post-form').serialize(),
            arrCompanies: $('#companies').val(),
            arrCommunities: $('#communities').val(),
            batchId: $batchId,
            active: $active,
            id: $id
        };
    }

    var refreshForm = function () {
//        $('#post-form')[0].reset();
//        $('select.SlectBox')[0].sumo.unSelectAll();
        jQuery("#parent_" + $checkedParentId).attr('checked', true);
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

    var hideProductCondition = function () {
        $('#product_condition_box').hide();
    }

</script>

{!! HTML::script('assets/js/admin.js') !!}

@stop