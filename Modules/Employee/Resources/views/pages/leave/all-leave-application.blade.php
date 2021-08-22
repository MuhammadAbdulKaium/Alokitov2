
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
                            <h3 style="padding-left: 20px">All Application</h3>
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
                                        <select name="designation_id" id="designation" class="form-control">
                                            <option value="">--Designation--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="text" name="emp_id" placeholder="Emp ID" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="text" name="emp_name" placeholder="Name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select name="leave_type_id" id="" class="form-control" required>
                                            <option value="">--Leave Type--</option>
                                            <option value="">Pending</option>
                                            <option value="">Approved</option>
                                            <option value="">Reject</option>
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
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Leave Type</th>
                            <th>Req. Date</th>
                            <th>Req. From</th>
                            <th>Req. To</th>
                            <th>Req. For</th>
                            <th>Approve Date</th>
                            <th>Approve From</th>
                            <th>Approve To</th>
                            <th>Approve For</th>
                            <th>Available Day</th>
                            <th>Application Day</th>
                            <th>Remains</th>
                            <th>Status</th>
                            <th class="action-column">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allLeaveApplications as $leave)
                            <tr>
                                <td>1</td>
                                <td>{{$leave->employee_id}}</td>
                                <td>{{$leave->employee_id}}</td>
                                <td>{{$leave->leave_type}}</td>
                                <td>{{$leave->applied_date}}</td>
                                <td>{{$leave->req_start_date}}</td>
                                <td>{{$leave->req_end_date}}</td>
                                <td>{{$leave->req_for_date}}</td>
                                <td>{{$leave->approve_day ==null ? 'N/A' : $leave->approve_day}}</td>
                                <td>{{$leave->approve_start_date ==null ? 'N/A' : $leave->approve_start_date}}</td>
                                <td>{{$leave->approve_end_date ==null ? 'N/A' : $leave->approve_end_date}}</td>
                                <td>{{$leave->approve_for_date ==null ? 'N/A' : $leave->approve_for_date}}</td>
                                <td>{{$leave->available_day}}</td>
                                <td>{{$leave->approve_day}}</td>
                                <td>{{$leave->remains}}</td>
                                <td>{{$leave->status == 1 ? 'Pending' : ($leave->status == 2 ? 'Approved' : 'Reject')}}</td>
                                <td>
                                    <a href="" class="btn btn-primary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
