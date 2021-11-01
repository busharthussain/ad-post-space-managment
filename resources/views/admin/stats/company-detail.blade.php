@extends('layouts.superadminlayout')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{!! $company->name !!}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('Stats')}}</li>
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
                            <div class="row">
                                <div class="col-6">
                                    <div class="stats-top-panel">
                                        <strong>{{_lang('Total Users')}}:<span class="name-user">{!! $totalUsers !!}</span></strong>
                                        <strong>{{_lang('Total Email Sent')}}: <span class="num-user">{!! $totalEmails !!}</span></strong>
                                        <strong>{{_lang('Total Communities')}}: <span class="code-user">{!! $totalCommunities !!}</span></strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stats-top-panel">
                                        <strong>{{_lang('Total Messages')}}:<span class="name-user">{!! $totalMessages !!}</span></strong>
                                        <strong>{{_lang('Total Push Notifications')}}: <span class="num-user">{!! $totalNotifications !!}</span></strong>
                                        <strong>{{_lang('Total Posts')}}: <span class="code-user">{!! $totalPosts !!}</span></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive m-t-40">
                                <table id="" class="table table-bordered table-striped table-icon">
                                    <thead>
                                    <tr>
                                        <th>{{_lang('Community Name')}}</th>
                                        <th>{{_lang('Total Users')}}</th>
                                        <th>{{_lang('Total Posts')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($communitiesData)
                                        @foreach($communitiesData as $row)
                                            <tr>
                                                <td>{!! $row->title !!}</td>
                                                <td>{!! $row->total_users !!}</td>
                                                <td>{!! $row->total_posts !!}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
@stop