@extends('layouts.superadminlayout')

@section('content')

    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{_lang('All Communities')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('All Communities')}}</li>
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
                    {!! Form::open(['action' => 'admin\StatsController@exportCommunityStatExcel', 'id' => 'data-form', 'class' => 'form-horizontal']) !!}
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
                                    <div class="row m-b-30 {!! $class !!}">
                                        <div class=" col-12">
                                            <label style="font-weight: 600;margin-bottom: 10px;" for="first_name">{{_lang('Select Company/s')}}</label>
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
                                </div>
                            </div>
                            @if(isAdminRole())
                                <div class="row">
                                    <div class="col-12">
                                        <a id="get-record" href="javascript: void(0)" class="btn btn-success">{{_lang('Submit')}}</a>
                                        <a id="clear-filters" style="margin-left: 10px;" href="javascript: void(0)" class="btn btn-success">{{_lang('Clear')}}</a>
                                    </div>
                                </div>
                            @endif
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
        $renderRoute = '{{ URL::route('communities.get.stats') }}';
        $defaultType = 'renderCommunitiesStats';
        $token = "{{ csrf_token() }}";
        $page = 1;
        $search = '';
        $asc = 'asc';
        $desc = 'desc';
        $sortType  = 'desc';
        $sortColumn = 'c.created_at';

        $(document).ready(function() {
            window.asd = $('#companies').SumoSelect({
                csvDispCount: -1,
                selectAll: true,
                captionFormatAllSelected: _lang('All Companies'),
                placeholder: _lang('Select Companies')
            });
            $('select.SlectBox')[0].sumo.unSelectAll();
            $keyIndex = 'companies';
            $selectedIndex = $('#strCompaniesIndex').val();
            setSelected();

            updateFormData();
            $type = $defaultType;
            renderAdmin();

            $('#get-record').click(function (e) {
                updateFormData();
                $type = $defaultType;
                renderAdmin();
            });

            /**
             * This is used to clear filters
             */
            $('#clear-filters').click(function () {
                $('#companies')[0].sumo.unSelectAll();
            });

            $('#export-excel').click(function (e) {
                $('#data-form').submit();
            });
        })

        var updateFormData = function () {
            $formData = {
                '_token': $token,
                page:  $page,
                search: $search,
                sortType: $sortType,
                sortColumn: $sortColumn,
                arrCompanies: $('#companies').val()
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