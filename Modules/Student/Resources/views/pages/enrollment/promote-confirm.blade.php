
@extends('layouts.master')

@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-info-circle"></i> Confirm | <small>Student</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student/">Student</a></li>
                <li class="active">Confirm Student</li>
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

            @if(count($studentList)>0 AND count($promoStdList)>0)
                <form action="{{url('/student/promote')}}" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" >
                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i> Preview Selected Details
                                        </div>
                                        <table class="table">
                                            <colgroup>
                                                <col style="width:125px">
                                            </colgroup>
                                            <tbody>
                                            <tr>
                                                <th class="text-center">Academic Level</th>
                                                <td>{{$academicLevel->level_name}}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Batch</th>
                                                <td>{{$academicBatch->batch_name}}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Section</th>
                                                <td>{{$academicSection->section_name}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <div class="panel panel-warning">
                                        <div class="panel-body">
                                            <h4> <i class="fa fa-cog"></i> Apply Action </h4>
                                            <div class="form-group">
                                                <div class="col-sm-8">
                                                    <input id="confirm_promo_action" name="promo_action_type" value="{{$promoteAction}}" type="hidden">
                                                </div>
                                            </div>
                                            <h4 class="text-yellow"><strong>{{$promoteAction}}</strong></h4>
                                            <h4><i class="fa fa-hand-o-right"></i></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    @if($promoteAction=="PROMOTE" || $promoteAction=="REPEAT")
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <i class="fa fa-check-circle"></i> Select Promote Details
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                        <label class="control-label" for="academic_year">Academic Year</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <select id="academic_year" class="form-control academicYear" name="academic_year" required>
                                                                <option value="">--- Select Academic Year ---</option>
                                                                @foreach($academicYears as $year)
                                                                    <option value="{{$year->id}}">{{$year->year_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                        <label class="control-label" for="academic_level">Academic Level</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
                                                                <option value="" selected disabled>--- Select Level ---</option>
                                                            </select>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                        <label class="control-label" for="batch">Batch</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <select id="batch" class="form-control academicBatch" name="batch" required>
                                                                <option value="" selected disabled>--- Select Batch ---</option>
                                                            </select>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                        <label class="control-label" for="section">Section</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <select id="section" class="form-control academicSection" name="section" required>
                                                                <option value="" selected disabled>--- Select Section ---</option>
                                                            </select>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--<div id="course-notice" class="alert bg-warning text-warning text-bold" style="display:none">--}}
                                                {{--<i class="fa fa-warning" aria-hidden="true">--}}
                                                {{--</i> No course available in selected academic year.--}}
                                                {{--<a href="#" target="_blank" style="color:inherit">Click here to create</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    @else
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <i class="fa fa-check-circle"></i> Select Graduate Details
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                        <label class="control-label" for="graduate_year">Graduate Year</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <select id="graduate_year" class="form-control" name="graduate_year" required>
                                                                <option value="">--- Select Graduate Year ---</option>
                                                                @foreach($academicYears as $year)
                                                                    <option value="{{$year->id}}">{{$year->year_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                        <label class="control-label" for="graduate_month">Graduate Month</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <select id="graduate_month" class="form-control" name="graduate_month" required>
                                                                <option value="">--- Select Graduate Month ---</option>
                                                                <option value="1">January</option>
                                                                <option value="2">February</option>
                                                                <option value="3">March</option>
                                                                <option value="4">April</option>
                                                                <option value="5">May</option>
                                                                <option value="6">June</option>
                                                                <option value="7">July</option>
                                                                <option value="8">August</option>
                                                                <option value="9">September</option>
                                                                <option value="10">October</option>
                                                                <option value="11">November</option>
                                                                <option value="12">December</option>
                                                            </select>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--./box-body-->
                        <div class="box-footer">
                            <a class="btn btn-primary" href="{{ url()->previous() }}"><i class="fa fa-times" aria-hidden="true"></i>
                                 Cancel</a>
                            <button type="submit" class="btn btn-success pull-right" onclick="return confirm('Are you sure to Continue ?')"><i class="fa fa-floppy-o" aria-hidden="true"></i> Confirm &amp; Submit</button>
                        </div>
                    </div>

                    {{--selected student list--}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-check-square-o"></i> Selected Student</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-responsive">
                                <colgroup>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                    <col class="text-center">
                                    <col>
                                </colgroup>
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">GR. No.</th>
                                    <th>Name</th>
                                    <th class="text-center">Academic Year</th>
                                    <th class="text-center">Course</th>
                                    <th class="text-center">Batch</th>
                                    <th class="text-center">Section</th>
                                    {{--<th class="text-center">Completion Status</th>--}}
                                    {{--<th class="text-center">Current Status</th>--}}
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
                                                <input type="hidden" name="std_list[{{$student->std_id}}]" value="{{$student->gr_no}}"/>
                                            </td>
                                            <td class="text-center">{{$student->year()->year_name}} </td>
                                            <td class="text-center">{{$academicLevel->level_name}} </td>
                                            <td class="text-center">{{$academicBatch->batch_name}} </td>
                                            <td class="text-center">{{$academicSection->section_name}} </td>
                                            {{--<td class="text-center">{{$student->enroll()->enroll_status}} </td>--}}
                                            {{--<td class="text-center">{{$student->enroll()->batch_status}} </td>--}}
                                        </tr>
                                        @php $i=($i+1); @endphp
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </form>
            @else
                <div class="alert-auto-hide alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="fa fa-info-circle"></i> Please select the required fields from the search form.
                </div>
            @endif

        </section>
    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script>
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

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append('<option value="" selected disabled>--- Select Batch ---</option>');

                    // set value to the academic batch
                    $('.academicLevel').html("");
                    $('.academicLevel').append(op);
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

                beforeSend: function() {
                    // console.log(level_id);
                },

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
                },

                error:function(){

                }
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
                },

                error:function(){
                    //
                },
            });
        });
    </script>
@endsection
