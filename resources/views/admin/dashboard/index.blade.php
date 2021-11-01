@extends('layouts.superadminlayout', ['userChart', 'postChart'])

@section('content')

<style type="text/css">
    .charts{
         margin: 0 0 0 -20px;
    }
</style>
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{_lang('Dashboard')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item active">{{_lang('Dashboard')}}</li>
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
            <!-- Row -->
            <div class="card-group">
                <!-- Column -->
                <div class="card" style="margin-left:0;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="m-b-0 text-left icon-size"><i class="fa fa-users text-success"></i></h2>
                                    </div>
                                    <div class="col-6">
                                        <h3 class="text-right font-size">{!! $totalUsers !!}</h3>    
                                    </div>
                                </div>
                                <div class="title-box">
                                    <h3 class="card-subtitle">{{_lang('Total Users')}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                @if(!empty($isShowAllStats))
                    <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="m-b-0 text-left icon-size"><i class="fa fa-building text-warning"></i></h2>
                                    </div>
                                    <div class="col-6">
                                        <h3 class="text-right font-size">{!! $totalCompanies !!}</h3>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <h3 class="card-subtitle" style="position: relative;top: 7px;">{{_lang('Total Companies')}}</h3></div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Column -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="m-b-0 text-left icon-size"><i class="fa fa-handshake-o text-info"></i></h2>
                                    </div>
                                    <div class="col-6">
                                        <h3 class="text-right font-size">{!! $totalCommunities !!}</h3>  
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <h6 class="card-subtitle" style="position: relative;top: 7px;">{{_lang('Total Communities')}}</h6></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                @if(!empty($isShowAllStats))
                    <div class="card" style="margin-right:0;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="m-b-0 text-left icon-size"><i class="fa fa-shopping-basket text-purple"></i></h2>
                                    </div>
                                    <div class="col-6">
                                        <h3 class="text-right font-size">{!! $totalPosts !!}</h3>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <h6 class="card-subtitle" style="position: relative;top: 7px;">{{_lang('Total Posts')}}</h6></div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Column -->
            </div>
            <!-- Row -->
            <!-- Row -->
            @if(!empty($isShowAllStats))
                <div class="card-group nd-group">
                    <div class="card" style="margin-left:0;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h2 class="m-b-0 text-left icon-size">
                                                <div class="icon-holer">
                                                    <img src="{!! asset('assets/images/swap-icon.png') !!}" alt="homepage" class="light-logo" />
                                                </div>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-right font-size text-purple">{!! $swapPosts !!}</h3>
                                        </div>
                                    </div>
                                    <h6 class="card-subtitle">{{_lang('Swap Posts')}}</h6></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h2 class="m-b-0 text-left icon-size">
                                                <div class="icon-holer">
                                                    <img src="{!! asset('assets/images/borrow-icon.png') !!}" alt="homepage" class="light-logo" />
                                                </div>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-right font-size text-success">{!! $borrowPosts !!}</h3>
                                        </div>
                                    </div>
                                    <h6 class="card-subtitle">{{_lang('Borrow Posts')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                               <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h2 class="m-b-0 text-left icon-size">
                                                <div class="icon-holer">
                                                    <img src="{!! asset('assets/images/wanted-icon.png') !!}" alt="homepage" class="light-logo" />
                                                </div>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-right font-size text-warning">{!! $wantedPosts !!}</h3>
                                        </div>
                                    </div>
                                    <h6 class="card-subtitle">{{_lang('Wanted Posts')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="card" style="margin-right:0;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h2 class="m-b-0 text-left icon-size">
                                                <div class="icon-holer">
                                                    <img src="{!! asset('assets/images/give-icon.png') !!}" alt="homepage" class="light-logo" />
                                                </div>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-right font-size text-info">{!! $giveAwayPosts !!}</h3>
                                        </div>
                                    </div>
                                    <h6 class="card-subtitle">{{_lang('Give away Posts')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <!-- Row -->
                <div class="card-group" style="margin:0 0 16px;">
                    <div class="card" style="margin-left:0;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h2 class="m-b-0 text-left icon-size">
                                                <div class="icon-holer">
                                                    <img src="{!! asset('assets/images/swap-icon.png') !!}" alt="homepage" class="light-logo" />
                                                </div>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-right font-size text-purple">{!! $swapPostsEnd !!}</h3>
                                        </div>
                                    </div>
                                    <h6 class="card-subtitle">{{_lang('End Swap Deals')}}</h6></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h2 class="m-b-0 text-left icon-size">
                                                <div class="icon-holer">
                                                    <img src="{!! asset('assets/images/borrow-icon.png') !!}" alt="homepage" class="light-logo" />
                                                </div>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-right font-size text-success">{!! $borrowPostsEnd !!}</h3>
                                        </div>
                                    </div>
                                    <h6 class="card-subtitle">{{_lang('End Borrow Deals')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                               <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h2 class="m-b-0 text-left icon-size">
                                                <div class="icon-holer">
                                                    <img src="{!! asset('assets/images/wanted-icon.png') !!}" alt="homepage" class="light-logo" />
                                                </div>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-right font-size text-warning">{!! $wantedPostsEnd !!}</h3>
                                        </div>
                                    </div>
                                    <h6 class="card-subtitle">{{_lang('End Wanted Deals')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="card" style="margin-right:0;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h2 class="m-b-0 text-left icon-size">
                                                <div class="icon-holer">
                                                    <img src="{!! asset('assets/images/give-icon.png') !!}" alt="homepage" class="light-logo" />
                                                </div>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-right font-size text-info">{!! $giveAwayPostsEnd !!}</h3>
                                        </div>
                                    </div>
                                    <h6 class="card-subtitle">{{_lang('End Give Away Deals')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <div class="form-group col-6" style="padding-left: 0px;">
                    {!! Form::select('month', $months, $month, ['id' => 'month','class' => 'form-control']) !!}
                </div>
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-6 col-xlg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="panel panel-default">
                                    <div class="panel-heading">{{_lang('Users Chart')}}</div>

                                    <div class="panel-body">
                                        {!! $userChart->html() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xlg-6">
                        <div class="card recent-act-sec">
                            <div class="card-body bg-green" style="max-height: 60px;">
                                <h4 class="card-title">{{_lang('Recent Activities')}}</h4>
                            </div>
                            <div style="clear: both;"></div>
                            <div class="comment-widgets m-b-20">

                                @if($changeLogs)
                                    @foreach($changeLogs as $row)
                                            <!-- Comment Row -->
                                            <div class="d-flex flex-row comment-row">
                                                <div class="p-2"><span class="round"><img src="{!! getLogoImage($row->user_id) !!}" alt="user" width="50"></span></div>
                                                <div class="comment-text w-100">
                                                    <div class="name-user">
                                                        <h5>{!! $row->name !!}</h5>
                                                        <span class="date pull-right">{!! $row->created_at !!}</span>
                                                    </div>
                                                    <p class="m-b-5 m-t-10">{!! $row->message !!}</p>
                                                </div>
                                            </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div id="chartContainer" style="width: 100%; height: 300px"></div>
            @endif

            <!-- Column -->
            <!-- Row -->
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer"> © 2018  Sharepeeps </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>

    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

    <script type="text/javascript">
        $swapPercentage = '{!! $swapPercentage !!}';
        $borrowPercentage = '{!! $borrowPercentage !!}';
        $wantedPercentage = '{!! $wantedPercentage !!}';
        $giveAwayPercentage = '{!! $giveAwayPercentage !!}';
        $isShowAllStats = '{!! $isShowAllStats !!}';
        $reloadRoute = '{!! URL::route('super.admin.dashboard') !!}';

        window.onload = function() {
            if ($isShowAllStats == true) {
                renderChart();
                $('.canvasjs-chart-credit').hide();
            }
            $('#month').change(function () {
                window.location = $reloadRoute + '/' + this.value;
            });
        }

        function renderChart() {
            var percentage = [];
            if ($swapPercentage > 0) {
                percentage.push({label: "{{_lang('Swap Posts')}}", y: $swapPercentage, legendText: "{{_lang('Swap Posts')}}"});
            }
            if ($borrowPercentage > 0) {
                percentage.push({label: "{{_lang('Borrow Posts')}}", y: $borrowPercentage, legendText: "{{_lang('Borrow Posts')}}"});
            }
            if ($wantedPercentage > 0) {
                percentage.push({label: "{{_lang('Wanted Posts')}}", y: $wantedPercentage, legendText: "{{_lang('Wanted Posts')}}"});
            }
            if ($giveAwayPercentage > 0) {
                percentage.push({label: "{{_lang('Give Away Posts')}}", y: $giveAwayPercentage, legendText: "{{_lang('Give Away Posts')}}"});
            }

            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "{{_Lang('Posts')}}",
                    fontSize: 20
                },
                axisY: {
                    title: "Products in %"
                },
                legend :{
                    verticalAlign: "center",
                    horizontalAlign: "right"
                },
                data: [
                    {
                        type: "pie",
                        indexLabelFormatter: function(e) {
                            if (e.dataPoint.y == 0)
                                return "";
                            else
                                return e.dataPoint.y;
                        },
                        options: {
                            tooltips: {
                                enabled: false
                            }
                        },
                        toolTip:{
                            enabled: false,
                        },
                        showInLegend: true,
                        creditText:"",
                        toolTipContent: "{label} <br/> {y} %",
                        indexLabel: "{y} %",
                        dataPoints: percentage
                    }
                ]
            });
            chart.render();
        }
    </script>
@stop