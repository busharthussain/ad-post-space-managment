@extends('layouts.superadminlayout')
@php
use Carbon\Carbon;
@endphp
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
               <div class="card-body">
                  <div class="row">
                     <!--  <div class="col-12">
                                    <ul class="pull-right excel-link">
                                        <li><i class="fa fa-file-excel-o" aria-hidden="true"></i><a href="#" id="export-excel">{{_lang('Export Excel File')}}</a></li>
                                    </ul>
                                </div> -->
                     <div class="col-12">
                        <ul class="pull-right excel-link" >
                            <li><a  href="#"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a></li>
                            <li><a id="export-excel" href="#" >{{_lang('Export Excel File')}}</a></li>
                        </ul>
                     </div>
                  </div>   
                  <div class="row">
                     <div class="col-6">
                        <!-- <h4 class="card-title">All Companies</h4> -->
                     </div>
                     
                              
                  </div>
                  <div class="row m-t-40">
                     
                     <div class="col-6">
                        <p class="total-record">{{_lang('Total')}} <span id="total-record"> {{count($users)}} </span> {{_lang('records found')}}</p>
                     </div>
                     <div class="col-6">
                        <div class="" style="float: right;">
                           @if(isAdminRole())
                           <form method="post" class="custom-form-inline" action="{{url('/user/search')}}/{{$company_id}}">
                              <input type='hidden' name='_token' value='{{csrf_token()}}'>
                              <label class="">{{_lang('Search')}}:
                              <input placeholder="" id="search" class="form-control search-input" name="search" type="search" value="@if(isset($search)){{$search}}@endif">
                              </label>
                           </form>
                           @else
                           <form method="post" class="custom-form-inline" action="{{url('/user/search')}}">
                              <input type='hidden' name='_token' value='{{csrf_token()}}'>
                              <label class="">{{_lang('Search')}}:
                              <input placeholder="" autocomplete="off" class="form-control search-input" name="search" type="search" value="@if(isset($search)){{$search}}@endif">
                              </label>
                           </form>
                           @endif
                        </div>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table id="myTable" class="table table-bordered post-table table-striped table-icon">
                        <thead>
                           <tr>
                              @if(!empty($headers))
                              @php
                              $edit_display= 1 ; @endphp
                              @foreach($headers AS $key => $header)
                              @php
                              $width = '';
                              if(!empty($header['width']))
                              $width = 'width='.$header['width'];
                              @endphp
                              @if($header['name']=='Signup Date' || $header['name']=='Opret Bruger Date')
                              <th style="width: 13%;">{!! $header['name'] !!}</th>
                              @elseif($edit_display==5)
                              <th style="width: 14%;" ><span id="CF1-value">{!! $header['name'] !!}</span>
                                 @if(isAdminRole())
                                 @else
                                 <i id="CF1" class="fa fa-pencil-square-o"></i>@endif
                              </th>
                              @elseif($edit_display==6)
                              <th  >
                                 <span id="CF2-value">{!! $header['name'] !!}</span>
                                 @if(isAdminRole())
                                 @else
                                 <i id="CF2" class="fa fa-pencil-square-o"></i>@endif
                              </th>
                              @elseif($edit_display==7)
                              <th {!! $width !!} ><span id="CF3-value">{!! $header['name'] !!}</span>
                              @if(isAdminRole())
                              @else
                              <i id="CF3" class="fa fa-pencil-square-o"></i>@endif
                              </th>
                              @else
                              <th {!! $width !!}>{!! $header['name'] !!}
                               @if(!empty($header['isSorter']))
                                    <i class="sorting fa fa-fw fa-sort" id="{{$header['name']}}"></i>
                                 @endif</th>
                              @endif
                              @php $edit_display++; @endphp 
                              @endforeach
                              @endif
                           </tr>
                           </tr>
                        </thead>
                        <tbody id="page-data">
                           @php 
                           $i=10;
                           if(isset($_GET['page']))
                           {
                           $i=$_GET['page']*$i;
                           }
                           $check=0;
                           $printer=1;
                           @endphp
                           @foreach($users as $key)
                           @foreach($key as $k)
                           @if($check+10==$i && $printer<=10)
                           <tr>
                              @if(isAdminRole())
                              <td><img src="{{url('/users')}}/{{$k->image}}" class="img-responsive"></td>
                              @endif
                              <td><a href="{{url('/user/stats/detail/')}}/{{$k->id}}">{{$k->name}} {{$k->sur_name}}</a></td>
                              <td>{{$k->email}}</td>
                              <td>{{$k->mobile_number}}</td>
                              <!-- <td>{{$k->device_type}}</td> -->
                              <td>{{Carbon::parse($k->created_at)->format('d-m-Y')}}</td>
                              @if(isAdminRole())
                              <td>{{getCF($k->id,$company_id,1)}}</td>
                              <td>{{getCF($k->id,$company_id,2)}}</td>
                              <td>{{getCF($k->id,$company_id,3)}}</td>
                              @else
                              <td>
                                 <form class="custom-form-inline">
                                    <input type='hidden' name='_token' value='{{csrf_token()}}'>
                                    <input type='hidden' id='user_id_{{$k->id}}' value='{{$k->id}}'>
                                    <input type="text" class="form-control" value="{{getCF($k->id,$company_id,1)}}" id="field_values_1_{{$k->id}}" required>
                                    <button class="btn btn-success btn-sm" onclick="form1('{{$k->id}}')" type="button"><i class="fa fa-save"></i></button>
                                 </form>
                                 <p id="help_1_{{$k->id}}" style="font-size:16px;color: #83c462; position: absolute;"></p>
                              </td>
                              <td>
                                 <form class="custom-form-inline">
                                    <input type='hidden' name='_token' value='{{csrf_token()}}'>
                                    <input type='hidden' id='user_id_{{$k->id}}' value='{{$k->id}}'>
                                    <input type="text" class="form-control" value="{{getCF($k->id,$company_id,2)}}" id="field_values_2_{{$k->id}}" required>
                                    
                                    <button class="btn btn-success btn-sm" onclick="form2('{{$k->id}}')" type="button"><i class="fa fa-save"></i></button>
                                   
                                 </form>
                                  <p id="help_2_{{$k->id}}" style="font-size:16px;color: #83c462; position: absolute;"></p>
                              </td>
                              <td>
                                 <form class="custom-form-inline">
                                    <input type='hidden' name='_token' value='{{csrf_token()}}'>
                                    <input type='hidden' id='user_id_{{$k->id}}' value='{{$k->id}}'>
                                    <input type="text" class="form-control" value="{{getCF($k->id,$company_id,3)}}" id="field_values_3_{{$k->id}}" required>
                                    <button class="btn btn-success btn-sm" onclick="form3('{{$k->id}}')" type="button"><i class="fa fa-save"></i></button>
                                    
                                 </form>
                                 <p id="help_3_{{$k->id}}" style="font-size:16px;color: #83c462; position: absolute;"></p>
                              </td>
                              @endif
                           </tr>
                           @php $printer++; @endphp
                           @else
                           @php $check++; @endphp
                           @endif
                           @endforeach
                           @endforeach
                        </tbody>
                     </table>
                  </div>
                  <div class="pager-info">
                     <p>{{_lang('Displaying')}} @if($count>0) 1 @else 0 @endif {{_lang('of')}} {{$count}} {{_lang('Records')}} </p>
                     <div class="clr"></div>
                  </div>
                  <div class="pagination-links pagi-rght">
                     <ul class="pagination">
                        @if(isAdminRole())
                        @php $page=1; $i=1; $check=false;@endphp
                        @while($i<=$count)
                        @if(empty($_GET) && $check==false)
                        @php $check=true; @endphp
                        <li class="active"><a href="{{url('/company/users/')}}/{{$company_id}}?page=1">1</a></li>
                        @elseif(!empty($_GET) && $check==false)
                        @if($_GET['page']==$page)
                        @php $check=true; @endphp
                        <li class="active"><a href="{{url('/company/users/')}}/{{$company_id}}?page={{$_GET['page']}}">{{$_GET['page']}}</a></li>
                        @else
                        <li><a href="{{url('/company/users/')}}/{{$company_id}}?page={{$page}}">{{$page}}</a></li>
                        @endif
                        @else
                        <li><a href="{{url('/company/users/')}}/{{$company_id}}?page={{$page}}">{{$page}}</a></li>
                        @endif
                        @php $i=$i+10; $page++; @endphp
                        @endwhile
                        @else
                        @php $page=1; $i=1; $check=false;@endphp
                        @while($i<=$count)
                        @if(empty($_GET) && $check==false)
                        @php $check=true; @endphp
                        <li class="active"><a href="{{url('/users')}}?page=1">1</a></li>
                        @elseif(!empty($_GET) && $check==false)
                        @if($_GET['page']==$page)
                        @php $check=true; @endphp
                        <li class="active"><a href="{{url('/users')}}?page={{$_GET['page']}}">{{$_GET['page']}}</a></li>
                        @else
                        <li><a href="{{url('/users')}}?page={{$page}}">{{$page}}</a></li>
                        @endif
                        @else
                        <li><a href="{{url('/users')}}?page={{$page}}">{{$page}}</a></li>
                        @endif
                        @php $i=$i+10; $page++; @endphp
                        @endwhile
                        @endif
                     </ul>
                  </div>
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
         <form action="{{url('/user/export-excel')}}" method="post" id="export-form">
             <input type='hidden' name='_token' value='{{csrf_token()}}'>
                  <input placeholder="" id="search_hide" autocomplete="off" class="form-control search-input" name="search" type="hidden">
               <input type="hidden" name="company_id" value="{{$company_id}}"></form>
</div>
<script src="https://unpkg.com/sweetalert2@7.17.0/dist/sweetalert2.all.js"></script>
<script type="text/javascript">
   $('#CF1').click(function(){
     var value=$('#CF1-value').text();
     swal({
           html: "<form action='{{url('/user/update/custom-field/1')}}' method='post'><input type='hidden' name='_token' value='{{csrf_token()}}'><div class='form-group'><input name='field_name' type='text' value='"+value+"' class='form-control'></div><div class='form-group'><input type='submit' value='Save' class='btn btn-success pull-right'></div></form>",
           showCancelButton: false,
           showConfirmButton: false
   
           });
   });
    $('#CF2').click(function(){
        var value=$('#CF2-value').text();
      swal({
           html: "<form action='{{url('/user/update/custom-field/2')}}' method='post'><input type='hidden' name='_token' value='{{csrf_token()}}'><div class='form-group'><input name='field_name' type='text' value='"+value+"' class='form-control'></div><div class='form-group'><input type='submit' value='Save' class='btn btn-success pull-right'></div></form>",
           showCancelButton: false,
           showConfirmButton: false
   
           });
   
   });
   $('#CF3').click(function(){
     var value=$('#CF3-value').text();
      swal({
           html: "<form action='{{url('/user/update/custom-field/3')}}' method='post'><input type='hidden' name='_token' value='{{csrf_token()}}'><div class='form-group'><input type='text' name='field_name' value='"+value+"' class='form-control'></div><div class='form-group'><input type='submit' value='Save' class='btn btn-success pull-right'></div></form>",
           showCancelButton: false,
           showConfirmButton: false
            });
      });
   var help='';
   function form1(id){
       $.ajax({
               url:"{{url('/user/update/custom-field-values/1')}}",
               type: 'post',
               data:{
                   '_token' : '{{csrf_token()}}',
                   'field_values' : $('#field_values_1_'+id).val(),
                   'user_id' :  $('#user_id_'+id).val(),
                   'company_id' : "{{$company_id}}"
               },
               success: function(data) {
                if(help=='')
                {
                  help = '#help_1_'+id;
                  $(help).html(data); 
                }
                else
                {
                  $(help).html(''); 
                    help = '#help_1_'+id;
                  $(help).html(data);  
                }
               }
       });
   }
   function form2(id){
       $.ajax({
               url:"{{url('/user/update/custom-field-values/2')}}",
               type: 'post',
               data:{
                   '_token' : '{{csrf_token()}}',
                   'field_values' : $('#field_values_2_'+id).val(),
                   'user_id' :  $('#user_id_'+id).val(),
                   'company_id' : "{{$company_id}}"
               },
               success: function(data) {
                if(help=='')
                {
                  help = '#help_2_'+id;
                  $(help).html(data); 
                }
                else
                {
                  $(help).html(''); 
                    help = '#help_2_'+id;
                  $(help).html(data);  
                }
               }
       });
   }
   
   function form3(id){
       $.ajax({
               url:"{{url('/user/update/custom-field-values/3')}}",
               type: 'post',
               data:{
                   '_token' : '{{csrf_token()}}',
                   'field_values' : $('#field_values_3_'+id).val(),
                   'user_id' :  $('#user_id_'+id).val(),
                   'company_id' : "{{$company_id}}"
               },
               success: function(data) {
                  if(help=='')
                {
                  help = '#help_3_'+id;
                  $(help).html(data); 
                }
                else
                {
                  $(help).html(''); 
                    help = '#help_3_'+id;
                  $(help).html(data);  
                }
               }
       });
   }
   //export excel file
   $('#export-excel').click(function()
      {
         // if($('#search').val()!='')
         // {
         //     $('#search_hide').val($('#search').val());
         // }
       
        $('#export-form').submit();
      });
   //data sorter 

  


</script>

@stop