@extends('layouts.superadminlayout')

@section('content')

    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{_lang('Top Searches')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('Top Searches')}}</li>
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
                     <div id="notificationMessage"></div>
                {!! Form::open(['action' => 'admin\StatsController@exportTopPostSearchStatExcel', 'id' => 'data-form', 'class' => 'form-horizontal']) !!}
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
                                    <div class="col-6">
                                        <label style="font-weight: 600;margin-bottom: 10px;">{{_lang('Options')}}</label>
                                        {!! Form::select('option', $arrOptions, null, ['id' => 'option','class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-12">
                                        <a style="margin-top:15px;" id="get-record" href="javascript: void(0)" class="btn btn-success">{{_lang('Submit')}}</a>
                                        <a id="clear-filters" style="margin-left: 10px;margin-top:15px;" href="javascript: void(0)" class="btn btn-success">{{_lang('Clear')}}</a>
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
        $renderRoute = '{{ URL::route('top.post.search.get.stats') }}';
        $defaultType = 'renderTopSearchPostStats';
        $token = "{{ csrf_token() }}";
        $page = 1;
        $search = '';
        $asc = 'asc';
        $desc = 'desc';
        $sortType  = 'desc';
        $sortColumn = 'psk.created_at';

        $(document).ready(function() {
            updateFormData();
            $type = $defaultType;
            renderAdmin();

            $('#clear-filters').click(function () {
                $('#option').val('');
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
                option: $('#option').val()
            };
        }

        $('#get-record').click(function (e) {
            updateFormData();
            $type = $defaultType;
            renderAdmin();
        });

    </script>

    {!! HTML::script('assets/js/admin.js') !!}

@stop