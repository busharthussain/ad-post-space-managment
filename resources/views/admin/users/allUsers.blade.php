@extends('layouts.superadminlayout')

@section('content')

    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{_lang('All Users')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('All Users')}}</li>
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
                    <div class="card">
                        {!! Form::open(['action' => 'admin\UsersController@ExportExcelAllUsers', 'id' => 'data-form', 'class' => 'form-horizontal']) !!}
                      <div class="card-body form-horizontal form-material">
                            <div class="row">
                                <div class="col-12">
                                    <ul class="pull-right excel-link">
                                        <li><a href="javascript: void(0)"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a></li>
                                        <li><a href="javascript: void(0)" id="export-excel">{{_lang('Export Excel File')}}</a></li>
                                    </ul>
                                </div>
                            </div>
                           
                            
                            <div class="row m-t-40 m-b-10">
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
                         {!! Form::token() !!}
                        {!! Form::close() !!}
                       
                    </div>
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
        $renderRoute = '{{ URL::route('aapUsers.get') }}';
        $defaultType = 'renderUserStats';
        $companiesDropDownRoute = '{{ URL::route('get.companies') }}';
        $communitiesDropDownRoute = '{{ URL::route('get.communities') }}';
        $token = "{{ csrf_token() }}";
        $page = 1;
        $search = '';
        $asc = 'asc';
        $desc = 'desc';
        $sortType  = 'desc';
        $sortColumn = 'u.created_at';

        $(document).ready(function() {
           
            updateFormData();
            $type = $defaultType;
            renderAdmin();

            $('#get-record').click(function (e) {
                $startAge = $('#start_age').val();
                $endAge = $('#end_age').val();
                if ($endAge > 0 && $startAge < 1) {
                    alert('{{_lang('Select Start Range')}}');
                    return false;
                }
                if ($endAge < 1 && $startAge > 0) {
                    alert('{{_lang('Select End Range')}}');
                    return false;
                }
                if ($endAge> 0 && $endAge <= $startAge) {
                    alert('{{_lang('Please select end range greater than start date')}}');
                    return false;
                }

                updateFormData();
                $type = $defaultType;
                renderAdmin();
            });

            $('#companies').change(function (e) {
                e.preventDefault();
                $arrCompanies = $('#companies').val();
                $type = 'communitiesDropDown';
                updateFormData();
                renderAdmin();
            });

            $('#clear-filters').click(function () {
                $('#companies')[0].sumo.unSelectAll();
                $('#communities')[0].sumo.unSelectAll();
                $('#start_age').val(0);
                $('#end_age').val(0);
                $('#device_type').val('');
            });

            $('#export-excel').click(function(e){
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