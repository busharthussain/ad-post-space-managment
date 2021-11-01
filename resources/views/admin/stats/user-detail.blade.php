@extends('layouts.superadminlayout')

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{!! $user->name !!}</h3>
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
                            <div id="notificationMessage"></div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="stats-top-panel">
                                        <strong>{{_lang('Name')}}:<span class="name-user">{!! $user->name !!}</span></strong>
                                        <strong>{{_lang('Surname')}}:<span class="name-user">{!! $user->sur_name !!}</span></strong>
                                        <strong>{{_lang('Phone Number')}}: <span class="num-user">{!! $user->mobile_number !!}</span></strong>
                                        <strong>{{_lang('Postal Code')}}: <span class="code-user">{!! $user->postal_code !!}</span></strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stats-top-panel">
                                        <strong>{{_lang('Email')}}:<span class="name-user">{!! $user->email !!}</span></strong>
                                        <strong>{{_lang('City')}}: <span class="num-user">{!! $user->city !!}</span></strong>
                                        <strong>{{_lang('Date Of Birthday')}}: <span class="num-user">{!! !empty($user->date_of_birth) ? DateFromatNew($user->date_of_birth) : '' !!}</span></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="stats-top-panel">
                                        <strong>{{_lang('Interested Tags')}}:<span class="name-user">{!! $user->interest_tags !!}</span></strong>
                                        <strong>{{_lang('Looking Tags')}}:<span class="name-user">{!! $user->looking_tags !!}</span></strong>
                                        <strong>{{_lang('Joined Companies')}}: <span class="num-user">{!! $joinedCompanies !!}</span></strong>
                                        <strong>{{_lang('Joined Communities')}}: <span>{!! $joinedCommunities !!}</span></strong>
                                        <strong>{{_lang('Total Posts')}}: <span>{!! $totalPosts !!}</span></strong>
                                    </div>
                                    <div class="switch">
                                        <label>
                                            {{_lang('Active')}}
                                            <input type="checkbox" id="active" name="active" @if(!empty($user->active)) checked @endif>
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive m-t-40">
                                <table id="" class="table table-bordered table-striped table-icon">
                                    <thead>
                                    <tr>
                                        <th>{{_lang('Swap Posts')}}</th>
                                        <th>{{_lang('Borrow Posts')}}</th>
                                        <th>{{_lang('Wanted Posts')}}</th>
                                        <th>{{_lang('Give Away Posts')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{!! $swapPosts !!}</td>
                                        <td>{!! $borrowPosts !!}</td>
                                        <td>{!! $wantedPosts !!}</td>
                                        <td>{!! $giveAwayPosts !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        $addRecordRoute = '{{ URL::route('user.action') }}';
        $token = "{{ csrf_token() }}";
        $id = '{!! $id !!}';
        $checked = 0;

        $(document).ready(function () {
            $('#active').click(function (e) {
                if ($(this).is(':checked')) {
                    $checked = 1;
                } else {
                    $checked = 0;
                }
                $type = 'addRecord';
                updateFormData();
                renderAdmin();
            });
        })

        var updateFormData = function () {
            $formData = {
                '_token': $token,
                data:  [],
                id: $id,
                active: $checked
            };
        }

    </script>

    {!! HTML::script('assets/js/admin.js') !!}
@stop