
@extends('layouts.master')


@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-info-circle"></i> Promote | <small>Student</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student/">Student</a></li>
                <li class="active">Promote Student</li>
            </ul>
        </section>
        <section class="content">
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

            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Search Student</h3>
                        {{--<div class="box-tools">--}}
                        {{--<a class="btn btn-success btn-sm" href="#"><i class="fa fa-plus-square"></i> Add</a>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <form id="student_promote_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Academic Level</label>
                                    <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                        <option value="">--- Select Level ---</option>
                                        @foreach($academicLevels as $level)
                                            <option value="{{$level->id}}">{{$level->level_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="batch">Batch</label>
                                    <select id="batch" class="form-control academicBatch" name="batch" required>
                                        <option value="" selected disabled>--- Select Batch ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="section">Section</label>
                                    <select id="section" class="form-control academicSection" name="section" required>
                                        <option value="" selected disabled>--- Select Section ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-sm-4" style="margin-top: 25px;">
                                <div class="form-group">
                                    <input id="gr_no" class="form-control" name="gr_no" value="" placeholder="Enter Gr.No" type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4" style="margin-top: 25px;">
                                <div class="form-group">
                                    <input id="email" class="form-control" name="email" value="" placeholder="Enter Student Email Id." type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info">Search</button>   <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>

            {{--promo student list--}}
            <div id="promo_student_list">
{{--                @if($studentList>0)--}}
                {{--@if(count($studentList)>0 && count($promoStdList)>0)--}}
                @if($studentList)
                    <div class="box box-success box-solid alert-auto-hide">
                        <div class="box-header with-border">
                            <h3 class="box-title"> Success Students</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">GR. No.</th>
                                    <th>Name</th>
                                    <th class="text-center">Academic Year</th>
                                    <th class="text-center">Course</th>
                                    <th class="text-center">Batch</th>
                                    <th class="text-center">Section</th>
                                    <th class="text-center">Completion Status</th>
                                    <th class="text-center">Current Status</th>
                                    <th class="text-center">Profile Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                @foreach($studentList as $student)
                                    @if(array_key_exists($student->std_id, $promoStdList))
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td class="text-center">{{$student->gr_no}} </td>
                                            <td>
                                                <a href="{{url('student/profile/personal/'.$student->std_id)}}">
                                                    {{$student->first_name." ".$student->middle_name." ".$student->last_name}}
                                                </a>
                                                <input type="hidden" name="std_list[{{$i}}]" value="{{$student->std_id}}"/>
                                            </td>
                                            <td class="text-center">{{$student->year()->year_name}} </td>
                                            <td class="text-center">{{$student->level()->level_name}} </td>
                                            <td class="text-center">{{$student->batch()->batch_name}} </td>
                                            <td class="text-center">{{$student->section()->section_name}} </td>
                                            {{--<td class="text-center">{{$student->enroll()->enroll_status}} </td>--}}
                                            <td class="text-center"><span class="label bg-blue">In Progress</span> </td>
                                            <td class="text-center"><span class="label label-success">Active</span> </td>
                                            <td class="text-center"><span class="label label-success">Active</span> </td>
                                        </tr>
                                        @php $i=($i+1); @endphp
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert-auto-hide alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-info-circle"></i> Please select the required fields from the search form.
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script>
        $(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });

        // form#student_promote_form
        $('form#student_promote_form').on('submit', function (e) {
            e.preventDefault();

            // ajax request
            $.ajax({
                type: 'post',
                cache: false,
                url: '/student/promote/search',
                data: $('form#student_promote_form').serialize(),
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Searching...');
                },

                success: function (data) {
                    // statements
                    if(data.length>0){
                        // promo_student_list
                        var promo_student_list =  $('#promo_student_list');
                        // clear promo_student_list html
                        promo_student_list.html('');
                        // append promo_student_list
                        promo_student_list.append(data);
                        // hide dialog
                        waitingDialog.hide();
                    }else{
                        alert('no data response from server');
                    }
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });

        });

        // request for batch list using level id
        jQuery(document).on('change','.academicYear',function(){
            // get academic year id
            var year_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/level') }}",
                type: 'GET',
                cache: false,
                data: {'id': year_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    console.log(year_id);

                },

                success:function(data){
                    //console.log(data.length);
                    op+='<option value="0" selected disabled>--- Select Level ---</option>';
                    for(var i=0;i<data.length;i++){
                        // console.log(data[i].level_name);
                        op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                    }

                    // set value to the academic section
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append('<option value="" selected disabled>--- Select Batch ---</option>');

                    // set value to the academic batch
                    $('.academicLevel').html("");
                    $('.academicLevel').append(op);
                    // clear promo_student_list html
                    $('#promo_student_list').html('');
                },

                error:function(){

                }
            });
        });

        // request for batch list using level id
        jQuery(document).on('change','.academicLevel',function(){
            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/batch') }}",
                type: 'GET',
                cache: false,
                data: {'id': level_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {},

                success:function(data){
                    //console.log(data.length);
                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
                    // clear promo_student_list html
                    $('#promo_student_list').html('');
                },

                error:function(){}
            });
        });


        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    console.log(batch_id);
                },

                success:function(data){
                    //console.log(data.length);
                    op+='<option value="" selected disabled>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                    // clear promo_student_list html
                    $('#promo_student_list').html('');
                },

                error:function(){
                    //
                }
            });
        });
    </script>
@endsection
