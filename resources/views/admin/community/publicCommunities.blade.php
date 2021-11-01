@extends('layouts.superadminlayout')

@section('content')

    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{_lang('Public Communities')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! URL::route('super.admin.dashboard') !!}">{{_lang('Home')}}</a></li>
                    <li class="breadcrumb-item">{{_lang('Public Communities')}}</li>
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
                              <div class="col-4"></div>
                              <div class="col-3">
                                  <div class="icon-sec">
                                      <label class="icon-holer" for="swap"><i class="flag-icon flag-icon-dk" aria-hidden="true"></i></label>
                                      @if($country == 'dn')
                                      {!! Form::radio('option', 'denmark', null, ['id' => 'email','class' => 'with-gap radio-col-green notification-option','required' => 'required', 'checked' => 'checked']) !!}
                                      @else
                                      {!! Form::radio('option', 'denmark', null, ['id' => 'email','class' => 'with-gap radio-col-green notification-option','required' => 'required']) !!}
                                      @endif
                                       <label for="email">{{_lang('Denmark')}}</label>
                                  </div>
                              </div>
                              <div class="col-5">
                                <div class="icon-sec">
                                    <label class="icon-holer borrow-img"  for="radio_40"><i class="flag-icon flag-icon-gb" aria-hidden="true"></i></label>
                                    @if($country == 'en')
                                    {!! Form::radio('option', 'england', null, ['id' => 'notification','class' => 'with-gap radio-col-green notification-option','required' => 'required','checked' => 'checked']) !!}
                                    @else
                                     {!! Form::radio('option', 'england', null, ['id' => 'notification','class' => 'with-gap radio-col-green notification-option','required' => 'required']) !!}
                                    @endif
                                    <label for="notification">{{_lang('England')}}</label>
                                </div>
                            </div>
                            </div>
                            <div class="row m-t-40">
                                <div class="col-6">
                                    <p class="total-record">{{_lang('Total')}} <span id="total-record"></span>{{$count}} {{_lang('records found')}}</p>
                                </div>
                                <div class="col-6">
                                    <div class="" style="float: right;">
                                        <form method="get" class="custom-form-inline" action="{{url('/community/search')}}">
                                             <label class="">{{_lang('Search')}}:
                                              <input placeholder="" autocomplete="off" class="form-control search-input" name="search" type="search" value="@if(isset($search)){{$search}}@endif">
                                              </label>
                                           </form>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            @if(Session::get('del_msg'))
                              <div class="alert alert-success">
                                {{Session::get('del_msg')}}
                              </div>
                            @endif
                     <table id="myTable" class="table table-bordered post-table table-striped table-icon">
                        <thead>
                           <tr>
                              @if(!empty($headers))
                              
                              @foreach($headers AS $key => $header)
                              @php
                              $width = '';
                              if(!empty($header['width']))
                              $width = 'width='.$header['width'];
                              @endphp
                              @if(!empty($header['isSorter']))
                               <th {!! $width !!} id="{!! $header['sorterKey'] !!}">{!! $header['name'] !!}<i class="sorting fa fa-fw fa-sort"></i></th>
                                @else
                              <th {!! $width !!}>{!! $header['name'] !!}</th>
                              @endif
                              @endforeach
                              @endif
                           </tr>
                           
                        </thead>
                        <tbody>
                          @foreach($communities as $com)
                            <tr>
                                <td>{{$com->title}}</td>
                                <td>{{getTotalPosts($com->id)}}</td>
                                <td>{{getTotalUsers($com->id)}}</td>
                                <td>
                                    <ul>
                                    <li><a href="{{url('/community/edit')}}/{{$com->id}}"><i class="fa fa-pencil-square-o"></i></a></li>
                                    <li><a href="{{url('/community/user-all')}}/{{$com->id}}"><i class="fa fa-user"></i></a></li>
                                   </ul>
                                </td>
                               
                            </tr>
                          @endforeach
                        </tbody>
                     </table>
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
      $('#email').click(function() {
        window.location.replace("{{url('/public-community/dn')}}");
      });
       $('#notification').click(function() {
         window.location.replace("{{url('/public-community/en')}}");
      });
    </script>

    

@stop