@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Academic Year</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Course Management</li>
                <li class="active">Academic Year</li>
            </ul>
        </section>
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            @if(Session::has('message'))


                <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @endif
        </div>
        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Academic Year</h3>
                </div>
                @if($insertOrEdit=='insert')
                    <form id="academic_year_form" name="academic_year_form" action="{{url('academics/store-academic-year')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_name required">
                                        <label class="control-label" for="year_name">Year Name</label>
                                        <input type="text" id="year_name" class="form-control" name="year_name" maxlength="60" placeholder="Enter Year Name" aria-required="true">
                                        <div class="help-block">
                                            @if ($errors->has('year_name'))
                                                <strong>{{ $errors->first('year_name') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_code required">
                                        <label class="control-label" for="start_date">Start Date</label>
                                        <input type="text" id="start_date" class="form-control" name="start_date" maxlength="10" placeholder="Enter Start Date" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_alias required">
                                        <label class="control-label" for="end_date">End Date</label>
                                        <input type="text" id="end_date" class="form-control" name="end_date" maxlength="10" placeholder="Enter End Date" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_alias required">
                                        <label class="control-label" for="status">Status</label>
                                        <select id="status" class="form-control" name="status" aria-required="true" disabled>
                                            <option value="" selected disabled>--- Select Status ---</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                @endif
                @if($insertOrEdit=='edit')
                    @foreach($editdata as $value)
                        <form id="academic_year_form" action="{{ url('academics/edit-academic-year-perform', [$value->id]) }}" method="post">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group field-subjectmaster-sub_master_name required">
                                            <label class="control-label" for="year_name">Year Name</label>
                                            <input type="text" id="year_name" value="{{$value->year_name}}" class="form-control" name="year_name" maxlength="60" placeholder="Enter Year Name" aria-required="true">
                                            @if ($errors->has('year_name'))
                                                <strong>{{ $errors->first('year_name') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group field-subjectmaster-sub_master_code required">
                                            <label class="control-label" for="start_date">Start Date</label>
                                            <input type="text" id="start_date" value="{{$value->start_date}}" class="form-control" name="start_date" maxlength="10" placeholder="Enter Start Date" aria-required="true">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group field-subjectmaster-sub_master_alias required">
                                            <label class="control-label" for="end_date">End Date</label>
                                            <input type="text" id="end_date" value="{{$value->end_date}}" class="form-control" name="end_date" maxlength="10" placeholder="Enter End Date" aria-required="true">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group field-subjectmaster-sub_master_alias required">
                                            <label class="control-label" for="status">Status</label>
                                            <select id="status" class="form-control" name="status" aria-required="true">
                                                <option value="" selected disabled>--- Select Status ---</option>
                                                <option value="1" {{$value->status==1?'selected':''}}> Active</option>
                                                <option value="0"  {{$value->status==0?'selected':''}}>Inactive</option>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary btn-create">Update</button>
                                <a class="btn btn-default btn-create" href="{{url('academics/academic-year') }}" >Cancel</a>
                            </div>
                            <!-- /.box-footer-->
                        </form>
                    @endforeach
                @endif
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Academic Year List</h3>
                </div>
                <div class="box-body table-responsive">

                    <div id="w1" class="grid-view">

                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><a  data-sort="sub_master_name">Year Name</a></th>
                                <th><a  data-sort="sub_master_code">Start Date</a></th>
                                <th><a  data-sort="sub_master_alias">End Date</a></th>
                                <th><a  data-sort="sub_master_alias">Status</a></th>
                                <th><a>Action</a></th>
                            </tr>

                            </thead>
                            <tbody>

                            @if(isset($data))
                                @php
                                    $i = 1
                                @endphp
                                @foreach($data as $values)
                                    <tr class="gradeX">
                                        <td>{{$i++}}</td>
                                        <td>{{$values->year_name}}</td>
                                        <td>{{date('m-d-Y',strtotime($values->start_date))}}</td>
                                        <td>{{date('m-d-Y',strtotime($values->end_date))}}</td>
                                        <td>@if($values->status==1) <i class="fa fa-check"></i>@endif
                                            @if($values->status==0) <i class="fa fa-times"></i>@endif
                                        </td>

                                        <td>


                                            <a href="" class="btn btn-primary btn-xs" id="academic_year_view_{{$values->id}}" onclick="modalLoad(this.id)" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>
                                            <a href="{{ url('academics/edit-academic-year', $values->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                            <a href="{{ url('academics/delete-academic-year', $values->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            {{--{{ $data->render() }}--}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--
            <!-- /.box-->
        </section>
        <!-- Modal  -->
        <div class="modal fade" id="etsbModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
        </div>
    </div>
    <div id="slideToTop"><i class="fa fa-chevron-up"></i></div>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog" >
            <div class="modal-content" >
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<!--
TO load view of each row
 -->
@section('scripts')
    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

    <script type = "text/javascript">
        jQuery(document).ready(function () {
//        console.log('datepicker');
            $('#start_date').datepicker();
            $('#end_date').datepicker();
        });
        jQuery('#end_date').datepicker({
            "changeMonth":true,"changeYear":true,"autoSize":true,"dateFormat":"dd-mm-yy",
            "changeMonth": true,
            "yearRange": "1990:2030",
            "changeYear": true,
            "autoSize": true,
            "dateFormat": "dd-mm-yy"
        });

        jQuery('#start_date').datepicker({
            "changeMonth":true,"changeYear":true,"autoSize":true,"dateFormat":"dd-mm-yy",
            "changeMonth": true,
            "yearRange": "1990:2030",
            "changeYear": true,
            "autoSize": true,
            "dateFormat": "dd-mm-yy"
        });

        function modalLoad(rowId) {

            var data=rowId.split('_'); //To get the row id

            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                url: "{{ url('academics/view-academic-year') }}"+'/'+data[3],
                type: 'GET',
                cache: false,
                data: {'_token': $_token }, //see the $_token
                datatype: 'html',

                beforeSend: function() {
                },

                success: function(data) {

                    // alert(data.length);
//                    $('.modal-content').html(data);
                    if(data.length > 0) {
                        // remove modal body
                        $('.modal-body').remove();
                        // add modal content
                        $('.modal-content').html(data);
                    } else {
                        // add modal content
                        $('.modal-content').html('info');
                    }
                }
            });

        }
    </script>


    <script type="text/javascript">
        jQuery(document).ready(function () {
            var year = 0;
            if(year.length == 0 || year == 0)
            {
                year = 0;
            }
        });
    </script>
    <script type="text/javascript">

        $(document).ready(function(){
            //alert();
            $('#myTable').DataTable();
        });

        jQuery(document).ready(function() {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
        });



        $(document).ready(function() {
            // validate  form on keyup and submit
            $("#academic_year_form").validate({
                rules: {
                    year_name: "required",
                    start_date: "required",
                    end_date:"required",
                    status:"required",
                },
                messages: {
                    year_name: "Please enter academic year name",
                    start_date: "Please enter start date",
                    end_date:"Please enter end date",
                    status: "Select status",
                }
            });
        });

    </script>

@endsection
