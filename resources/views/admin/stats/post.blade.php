@extends('layouts.superadminlayout')

@section('content')

    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{_lang('All Posts')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('All Posts')}}</li>
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
                <div id="notificationMessage"></div>
                <div class="col-12">
                    {!! Form::open(['action' => 'admin\StatsController@exportPostStatExcel', 'id' => 'data-form', 'class' => 'form-horizontal']) !!}
                        <div class="card">
                        <div class="card-body form-horizontal form-material">
                            <div class="row">
                                <div class="col-12">
                                    <ul class="pull-right excel-link">
                                        <li><a href="javascript: void(0)"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a></li>
                                        <li><a id="export-excel" href="javascript: void(0)">{{_lang('Export Excel File')}}</a></li>
                                    </ul>
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
                                    <div class="row {!! $class !!}">
                                        <div class="col-12">
                                            <label style="font-weight: 600;margin-bottom: 6px;" for="first_name">{{_lang('Select Company/s')}}</label>
                                        </div>
                                        <div class=" col-12">
                                            <select id="companies" name="companies[]" class="SlectBox custom-form" multiple>
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
                                                        <option value="{!! $key !!}">{!! $row !!}</option>
                                                    @endforeach
                                                @endif
                                                    <input type="hidden" name="strCompaniesIndex" id="strCompaniesIndex" value="{!! $strCompaniesIndex !!}">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row m-b-30">
                                        <div class="col-12">
                                            <label style="font-weight: 600;margin: 14px 0;" for="first_name">{{_lang('Select Communities/s')}}</label>
                                        </div>
                                        <div class="col-12">
                                            <select id="communities" name="communities[]" class="SlectBox" multiple>

                                                @if(!empty($arrCommunities))
                                                    @foreach($arrCommunities as $key => $row)
                                                        <option value="{!! $key !!}">{!! $row !!}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row m-b-30">
                                        <div class="col-12">
                                            <label style="font-weight: 600;margin: 0 0;">{{_lang('Option')}}</label>
                                            {!! Form::select('option', $arrOptions, null, ['id' => 'option','class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row m-b-30" style="margin: 11px 0 10px">
                                        <div class="col-6">
                                            <label style="font-weight: 600;">{{_lang('From Date')}}:</label>
                                            <div class="right-inner-addon" data-date-format="yyyy-mm-dd">
                                                {!! Form::text('borrow_from', null, ['class' => 'form-control', 'id' => 'borrow_from','required' => 'required']) !!}
                                                <img src="{!! asset('assets/images/calendar.png') !!}" alt="icon">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label style="font-weight: 600;">{{_lang('To Date')}}:</label>
                                            <div class="right-inner-addon date datepicker" data-date-format="yyyy-mm-dd">
                                                {!! Form::text('borrow_to', null, ['class' => 'form-control', 'id' => 'borrow_to','required' => 'required']) !!}
                                                <img src="{!! asset('assets/images/calendar.png') !!}" alt="icon">
                                            </div>
                                        </div>
                                    </div>
                                    <label style="font-weight: 600;margin-bottom: 10px;">{{_lang('Users')}}</label>
                                    {!! Form::select('users', $arrUsers, null, ['id' => 'users','class' => 'form-control']) !!}
                                    <label style="font-weight: 600;margin: 29px 0 0;">{{_lang('Type')}}</label>
                                    {!! Form::select('posts', ['' => _lang('All Posts'), '0' => _lang('Active'), '1' => _lang('Completed')], null, ['id' => 'posts','class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a id="get-record" href="javascript: void(0)" class="btn btn-success">{{_lang('Submit')}}</a>
                                    <a id="clear-filters" style="margin-left: 10px;" href="javascript: void(0)" class="btn btn-success">{{_lang('Clear')}}</a>
                                </div>
                            </div>
                            <div class="row m-t-40">
                                <div class="col-6">
                                    <p class="total-record">{{_lang('Total')}} <span id="total-record"></span> {{_lang('records found')}}</p>
                                </div>
                                <div class="col-6">
                                    <div class="" style="float: right;">
                                        <label class="">{{_lang('Search')}}:
                                            <input id="search" name="search" placeholder="" class="search-input" type="search">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @include('partials._renderheaders')
                            <div class="paq-pager"></div>
                        </div>
                    </div>
                    {!! Form::token() !!}
                    {!! Form::close() !!}
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

    <script type="text/javascript">
        $renderRoute = '{{ URL::route('post.get.stats') }}';
        $defaultType = 'renderPostStats';
        $companiesDropDownRoute = '{{ URL::route('get.companies') }}';
        $communitiesDropDownRoute = '{{ URL::route('get.communities') }}';
        $token = "{{ csrf_token() }}";
        $page = 1;
        $search = '';
        $asc = 'asc';
        $desc = 'desc';
        $sortType  = 'desc';
        $sortColumn = 'p.created_at';

        $(document).ready(function() {
            $("#borrow_to").datepicker({maxDate: 0, dateFormat: 'dd-mm-yy'});
            $("#borrow_from").datepicker({maxDate: 0, dateFormat: 'dd-mm-yy'});
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

            $keyIndex = 'companies';
            $selectedIndex = $('#strCompaniesIndex').val();
            setSelected();

            updateFormData();
            $type = $defaultType;
            renderAdmin();

            $('#companies').change(function (e) {
                e.preventDefault();
                $arrCompanies = $('#companies').val();
                $type = 'communitiesDropDown';
                updateFormData();
                renderAdmin();
            });

            $('#get-record').click(function (e) {
                $error = '';
                var StartDate = $('#borrow_from').val();
                var EndDate = $('#borrow_to').val();
                var eDate = new Date(EndDate);
                var sDate = new Date(StartDate);
                if (StartDate != '' && StartDate != '' && sDate > eDate) {
                    $error += '{{_lang('Please ensure that the End Date is greater than or equal to the Start Date')}}.';
                }
                if ($error != '') {
                    alert($error);
                    return false;
                }
                updateFormData();
                $type = $defaultType;
                renderAdmin();
            });

            $('#clear-filters').click(function () {
                $('#companies')[0].sumo.unSelectAll();
                $('#communities')[0].sumo.unSelectAll();
                $('#users').val('');
                $('#posts').val('');
                $('#option').val('');
                $('#borrow_from').val('');
                $('#borrow_to').val('');
            });

            $('#export-excel').click(function (e) {
                $error = '';
                var StartDate = $('#borrow_from').val();
                var EndDate = $('#borrow_to').val();
                var eDate = new Date(EndDate);
                var sDate = new Date(StartDate);
                if (StartDate != '' && StartDate != '' && sDate > eDate) {
                    $error += '{{_lang('Please ensure that the End Date is greater than or equal to the Start Date')}}.';
                }
                if ($error != '') {
                    alert($error);
                    return false;
                }
                $('#data-form').submit();
            });
        })

        /**
         * This is used to update form data
         */
        var updateFormData = function () {
            $formData = {
                '_token': $token,
                page:  $page,
                search: $search,
                sortType: $sortType,
                sortColumn: $sortColumn,
                arrCompanies: $('#companies').val(),
                arrCommunities: $('#communities').val(),
                users: $('#users').val(),
                posts: $('#posts').val(),
                option: $('#option').val(),
                borrow_to: $('#borrow_to').val(),
                borrow_from: $('#borrow_from').val(),
            };
        }

        /**
         * This is used to set selected
         */
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