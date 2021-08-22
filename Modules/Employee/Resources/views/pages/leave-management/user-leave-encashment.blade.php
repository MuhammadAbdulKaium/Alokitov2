
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
                        <h3>Leave Encashment</h3>
                    </div>

                    <div class="card">
                        <form method="POST" id="emp_assign_submit_form">
                            {{--				<input id="listData" name="list[]" value="">--}}
                            <table class="table" id="example">
                                <thead>
                                <tr>
                                    <th><input id="selectAll" type="checkbox"></th>
                                    <th>Name</th>
                                    <th>Emp ID</th>
                                    <th>Leave Type</th>
                                    <th>Available Leave</th>
                                    <th>Salary Head</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($employeeData)
                                    @foreach($employeeData as $key =>$data)
                                        <tr>
                                            <td><input type="checkbox" name="checkbox[]" id="selectEmp_{{$data->user_id}}" onclick="selectEmp({{$data->user_id}})"></td>
                                            <td>{{$data->first_name}} {{$data->last_name}}</td>
                                            <td>{{$data->user_id}}</td>
                                            <td>Casual</td>
                                            <td><input type="number" name="" value="20" class="form-control"></td>
                                            <td>
                                                <select name="" id="" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="">Gross</option>
                                                    <option value="">Basic</option>
                                                </select>
                                            </td>
                                            <td>
                                                2000
                                                @csrf
{{--                                                <input type="hidden" id="emp_{{$data->user_id}}" name="emp_id[]" value="{{$data->user_id}}">--}}
{{--                                                <input type="hidden" id="dpt_{{$data->user_id}}" name="dept_id" value="{{$leave_type_id}}">--}}
{{--                                                <input type="hidden" id="dsg_{{$data->user_id}}" name="designation_id" value="{{$designation_id}}">--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
{{--                            <div>--}}
{{--                                <lebel>System date</lebel>--}}
{{--                                <input type="date" name="system_date" class="form-control">--}}
{{--                            </div>--}}
                            <input type="submit" id="assignData">
                        </form>
                        @else
                            <h5 class="text-center"> <b>Sorry!!! No Result Found</b></h5>
                        @endif
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
            $(document).ready(function() {
                $('#example').DataTable( {
                    initComplete: function () {
                        this.api().columns().every( function () {
                            var column = this;
                            var select = $('<select><option value=""></option></select>')
                                .appendTo( $(column.footer()).empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );

                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                        } );
                    }
                } );
            } );
    </script>

@endsection
