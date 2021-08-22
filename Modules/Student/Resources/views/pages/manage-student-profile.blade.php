@extends('layouts.master')


@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection


<!-- page content -->
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-th-list"></i> Manage | <small>Cadet</small></h1>
            <ul class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Cadet</a></li>
                <li class="active">Manage Cadet</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                <form id="manage_student_profile_search_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="return_type" value="view">
                    <input type="hidden" name="page_type" value="manage_std_profile">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="academic_year">Academic Year</label>
                                    <select id="academic_year" class="form-control academicYear" name="academic_year" required>
                                        <option value="" selected>--- Select Academic Year ---</option>
                                        {{--checking--}}
                                        @if(!empty($academicYears) AND $academicYears AND $academicYears->count()>0)
                                            {{--academic year looping--}}
                                            @foreach($academicYears as $year)
                                                <option value="{{$year->id}}">{{$year->year_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Level</label>
                                    <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
                                        <option value="">--- Select Level ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="batch">Class</label>
                                    <select id="batch" class="form-control academicBatch" name="batch" required>
                                        <option value="">--- Select Class---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="section">Section</label>
                                    <select id="section" class="form-control academicSection" name="section" required>
                                        <option value="">--- Select Section ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" for="Roll">Username</label>
                                <div class="form-group">
                                    <input type="text" id="student_username" class="form-control" name="gr_no" placeHolder="Enter Cadet Roll">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" for="student_email">Email</label>
                                <div class="form-group">
                                    <input type="text" id="student_email" class="form-control" name="email" placeHolder="Enter Cadet Email">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- ./box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right">Search</button>
                        <button type="reset" class="btn btn-default pull-left">Reset</button>
                    </div>
                </form>
            </div>
        </section>

        {{-- student_profile_list_container --}}
        <div id="student_profile_list_container">
            {{--student profile list will be shown here--}}
        </div>
    </div>



    <!-- global modal -->
    <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw">
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script>
        $(function () {

            // request for parent list using batch section id
            $('form#manage_student_profile_search_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    url: "/student/manage/search",
                    type: 'POST',
                    cache: false,
                    data: $('form#manage_student_profile_search_form').serialize(),
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();

                        // checking
                        if(data.status=='success'){

                            var student_profile_list_container = $('#student_profile_list_container');
                            student_profile_list_container.html('');
                            student_profile_list_container.append(data.html);

                        }else{
//                            alert(data.msg)
                        }
                    },

                    error:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();

//                        alert(JSON.stringify(data));
                    }
                });
            });


        });

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });

        // request for batch list using level id
        jQuery(document).on('change','.academicYear',function(){
            // console.log("hmm its change");

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
                    // clear std list container
                    $('#student_profile_list_container').html('');
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="0" selected>--- Select Level ---</option>';
                    for(var i=0;i<data.length;i++){
                        // console.log(data[i].level_name);
                        op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                    }

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="" selected>--- Select Section ---</option>');

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append('<option value="" selected>--- Select Class ---</option>');

                    // set value to the academic batch
                    $('.academicLevel').html("");
                    $('.academicLevel').append(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                }
            });
        });

        // request for batch list using level id
        jQuery(document).on('change','.academicLevel',function(){
            // console.log("hmm its change");

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
                    // clear std list container
                    $('#student_profile_list_container').html('');
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="" selected>--- Select Class ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="0" selected>--- Select Section ---</option>');
                },

                error:function(){
                    alert(JSON.stringify(data));
                }
            });
        });

        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            console.log("hmm its change");

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
                    // clear std list container
                    $('#student_profile_list_container').html('');
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="" selected>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                },
            });
        });
    </script>
@endsection
