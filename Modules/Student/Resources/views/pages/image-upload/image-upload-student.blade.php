@extends('layouts.master')


@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/dropzone.css') }}" rel="stylesheet" type="text/css"/>

    {{--<link href="https://raw.githubusercontent.com/enyo/dropzone/master/dist/dropzone.css" rel="stylesheet" type="text/css"/>--}}
@endsection


@section('content')

    {{--batch string--}}
    @php $batchString="Class"; @endphp

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Upload | <small>Images</small>
            </h1>

            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student/">Student</a></li>
                <li class="active">Upload Images</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Class and Section</h3>
                    </div>
                </div>
                <form id="std_manage_search_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="academic_year">Academic Year</label>
                                    <select id="academic_year" class="form-control academicYear" name="academic_year" required>
                                        <option value="">--- Select Academic Year ---</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{$year->id}}">{{$year->year_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="academic_level">Academic Level</label>
                                    <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                        <option value="" selected>--- Select Level ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="batch">{{$batchString}}</label>
                                    <select id="batch" class="form-control academicBatch" name="batch">
                                        <option value="" selected>--- Select {{$batchString}} ---</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="section">Section</label>
                                    <select id="section" class="form-control academicSection" name="section">
                                        <option value="" selected>--- Select Section ---</option>

                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <button type="button" id="std_search_btn" class="btn btn-info pull-right">Search</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>
            <input type="hidden" id="class_std_list" value="">
            <div class="col-md-12" id="dzsection"></div>
        </section>
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
    <script src="{{ URL::asset('js/dropzone.js') }}"></script>
    {{--<script src="https://raw.githubusercontent.com/enyo/dropzone/master/dist/dropzone.js" type="text/javascript"></script>--}}

    <!-- datatable script -->
    <script>
        $(function () {
            $('#std_search_btn').click(function () {
                // checking
                if($('#section').val()){

                    // ajax request
                    $.ajax({
                        url: "/student/upload/images/search",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_manage_search_form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // hide waiting dialog
                            waitingDialog.hide();

                            $('#dzsection').html('');
                            $('#class_std_list').val('');

                            // checking
                            if(data.std_count>0){
                                $('#dzsection').html(data.html);
                                $('#class_std_list').val(data.std_list);
                            }else{
                                // sweet alert
                                swal("Warning", 'No Student Found for this class section', "warning");
                            }
                        },

                        error:function(data){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // sweet alert
                            swal("Error", 'Unable to load data form server', "error");
                        }
                    });

                }else{
                    // sweet alert
                    swal("Warning", 'Please Double Check All Inputs are Selected ????', "warning");
                }
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
                    $('#dzsection').html('');
                    $('#class_std_list').val('');
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
                    $('.academicBatch').append('<option value="" selected>--- Select {{$batchString}} ---</option>');

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
                    $('#dzsection').html('');
                    $('#class_std_list').val('');
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="" selected>--- Select {{$batchString}} ---</option>';
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
                    $('#dzsection').html('');
                    $('#class_std_list').val('');
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
                }
            });
        });
    </script>
@endsection
