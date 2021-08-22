
@extends('layouts.master')

@section('styles')
<!-- DataTables -->
  <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div>
                <div class="panel ">
                    <div  class="admin-chart" style="padding: 9px;">
                        <div class="row">
                            <form method="POST" id="std_manage_search_form">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select name="dept_id" id="department" class="form-control" required>
                                            <option value="">--Department--</option>
                                            @foreach($allDepartments as $department)
                                                <option value="{{$department->id}}">{{$department->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select name="designation_id" id="designation" class="form-control" required>
                                            <option value="">--Designation--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <input type="text" name="emp_id" placeholder="Emp ID" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <input type="text" name="emp_name" placeholder="Name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <select name="leave_type_id" id="" class="form-control" required>
                                            <option value="">--Leave Type--</option>
                                            @foreach($allLeaveType as $leave)
                                                <option value="{{$leave->id}}">{{$leave->name}}</option>
                                            @endforeach()
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <input type="text" name="duration" placeholder="Duration" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <select name="leave_process_procedure" id="" class="form-control" required>
                                            <option value="">--Leave Process Type--</option>
                                            <option value="1">Sequential</option>
                                            <option value="2">Working Day</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <input type="submit" name="search">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="std_list_container_row" class="row">
                        @if(Session::has('success'))
                            <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                            </div>
                        @elseif(Session::has('alert'))
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                            </div>
                        @elseif(Session::has('warning'))
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    </div>



@endsection

@section('scripts')
    <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet"/>
    <script src='https://d3js.org/d3.v4.min.js'></script>
    <script src="{{asset('js/jquery.bar.chart.min.js')}}"></script>
    {{--    <script src="{{URL::asset('js/any-chartCustom.js') }}"></script>--}}

    {{--    <script src="{{asset('js/pic-chart-js.js')}}"></script>--}}

    {{--    <script src="{{URL::asset('js/pic-chart.js')}}"></script>--}}
    <script src="{{URL::asset('js/alokito-Chart.js')}}"></script>

    <script type="text/javascript">
        var host = window.location.origin;
        $( document ).ready(function() {


            $("#department").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/employee/department/designation/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#designation").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#campusId").html("");
                }
            });

            $(function () {
                // request for parent list using batch section id
                $('form#std_manage_search_form').on('submit', function (e) {
                    e.preventDefault();
                    // ajax request
                    $.ajax({
                        url: "/employee/employee/search/",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_manage_search_form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // console.log(data);
                            // alert(data);
                            // hide waiting dialog
                            waitingDialog.hide();
                            // checking
                            if(data.status=='success'){
                                // alert(data);
                                var std_list_container_row = $('#std_list_container_row');
                                std_list_container_row.html('');
                                std_list_container_row.append(data.html);
                            }else{
                           alert(data.msg)
                            }
                        },

                        error:function(data){
                       alert(JSON.stringify(data));
                        }
                    });
                });


            });
        });
    </script>

@endsection
