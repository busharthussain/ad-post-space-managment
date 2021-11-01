@extends('layouts.superadminlayout')

@section('content')

    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{!! $companyName !!}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.company') !!}">{{_lang('Company')}}</a></li>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="notificationMessage"></div>
                            <div class="row">
                                <div class="col-6">
                                    <!-- <h4 class="card-title">{!! $companyName !!} - All Communities</h4> -->
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-success pull-right" id="create-community" href="javascript: void(0)">{{_lang('Create Community')}}</a>
                                </div>
                            </div>
                            <div class="row m-t-40">
                                <div class="col-6">
                                    <p class="total-record">{{_lang('Total')}} <span id="total-record"></span> {{_lang('records found')}}</p>
                                </div>
                                <div class="col-6">
                                    <div class="" style="float: right;">
                                        <label class="">{{_lang('Search')}}:
                                            <input placeholder="" class="form-control search-input" name="search" id="search" type="search">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @include('partials._renderheaders')
                            <div class="paq-pager"></div>
                        </div>
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
        $renderRoute = '{{ URL::route('community.get') }}';
        $editRoute = '{{ URL::route('super.admin.edit.community') }}';
        $invitationRoute = '{{ URL::route('community.invitation') }}';
        $deleteRoute = '{!! URL::route('community.delete') !!}';
        $communityRoute = '{{ URL::route('super.admin.create.community') }}';
        $qrcodeDownloadRoute = '{{ URL::route('qrcode.download') }}';
        $createCommunityRoute = '{{ URL::route('super.admin.create.community', ['id' => $companyId]) }}';
        $defaultType = 'renderCommunites';
        $allowCommunities = '{!! $allowCommunities !!}';
        $totalCommunities = '{!! $totalCommunities !!}';
        $isAdminRole = '{!! $isAdminRole !!}';
        $token = "{{ csrf_token() }}";
        $page = 1;
        $search = '';
        $asc = 'asc';
        $desc = 'desc';
        $sortType  = 'desc';
        $sortColumn = 'created_at';

        $(document).ready(function() {
            updateFormData();
            $type = $defaultType;
            renderAdmin();
            $('#search').val('');

            $('#create-community').click(function (e) {
                e.preventDefault();
                if ($totalCommunities >= $allowCommunities) {
                    alert('{{_lang('You have allowed only')}} ' + $allowCommunities + ' {{_lang('communities to create. Please contact administrator for more communities')}}');
                    return false;
                }
                window.location = $createCommunityRoute;
            });

        })

        var updateFormData = function () {
            $formData = {
                '_token': $token,
                page:  $page,
                companyId: '{!! $companyId !!}',
                search: $search,
                sortType: $sortType,
                sortColumn: $sortColumn
            };
        }

    </script>

    {!! HTML::script('assets/js/admin.js') !!}

@stop