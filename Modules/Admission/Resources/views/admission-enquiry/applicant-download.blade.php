@extends('admission::layouts.admission-layout')
<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1> <i class = "fa fa-users" aria-hidden="true"></i> Manage Enquiry </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Enquiry</a></li>
                <li class="active">Manage Enquiry</li>
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

            <div id="p0">
                <form id="applicant_enquiry_search_form" action="{{URL::to('/admission/applicant/download')}}" method="post" target="_blank">
                    {{ csrf_field() }}
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class = "fa fa-filter" aria-hidden="true"></i> Filter Options
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group {{ $errors->has('academic_year') ? ' has-error' : '' }}">
                                        <label class="control-label" for="academic_year">Academic Year</label>
                                        <select id="academic_year" class="form-control academicYear academicChange" name="academic_year" required>
                                            <option value="" selected>--- Select Year ---</option>
                                            @foreach($academicYears as $academicYear)
                                                <option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('academic_year'))
                                                <strong>{{ $errors->first('academic_year') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group {{ $errors->has('academic_level') ? ' has-error' : '' }}">
                                        <label class="control-label" for="academic_level">Academic Level</label>
                                        <select id="academic_level" class="form-control academicLevel academicChange" name="academic_level">
                                            <option value="" selected disabled>--- Select Level ---</option>
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('academic_level'))
                                                <strong>{{ $errors->first('academic_level') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 disabled">
                                    <div class="form-group {{ $errors->has('batch') ? ' has-error' : '' }}">
                                        <label class="control-label" for="batch">Batch</label>
                                        <select id="batch" class="form-control academicBatch academicChange" name="batch">
                                            <option value="" selected>--- Select Batch ---</option>
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('batch'))
                                                <strong>{{ $errors->first('batch') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group {{ $errors->has('applicant_status') ? ' has-error' : '' }}">
                                        <label class="control-label" for="applicant_status">Applicant Type</label>
                                        <select id="applicant_status" class="form-control applicantStatus academicChange" name="applicant_status">
                                            <option value="">--- Select Section ---</option>
                                            <option value="0" >Pending</option>
                                            <option value="1" >Active</option>
                                            {{--<option value="2">Approved</option>--}}
                                            {{--<option value="3">Waiting</option>--}}
                                            {{--<option value="4">Disapproved</option>--}}
                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('applicant_status'))
                                                <strong>{{ $errors->first('applicant_status') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="batch_name" id="getBatchName" value="">
                                {{--<div class="col-sm-3">--}}
                                    {{--<div class="form-group {{ $errors->has('section') ? ' has-error' : '' }}">--}}
                                        {{--<label class="control-label" for="section">Section</label>--}}
                                        {{--<select id="section" class="form-control academicSection academicChange" name="section">--}}
                                            {{--<option value="" selected disabled>--- Select Section ---</option>--}}
                                        {{--</select>--}}
                                        {{--<div class="help-block">--}}
                                            {{--@if ($errors->has('section'))--}}
                                                {{--<strong>{{ $errors->first('section') }}</strong>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>
                            {{--<div class="row">--}}
                                {{--<div class="col-sm-3">--}}
                                    {{--<div class="form-group {{ $errors->has('application_no') ? ' has-error' : '' }}">--}}
                                        {{--<label class="control-label" for="section">Application No.</label>--}}
                                        {{--<input type="text" id="application_no" class="form-control" name="application_no" placeHolder="Enter Application No.">--}}
                                        {{--<div class="help-block">--}}
                                            {{--@if($errors->has('application_no'))--}}
                                                {{--<strong>{{ $errors->first('application_no') }}</strong>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-3">--}}
                                    {{--<div class="form-group {{ $errors->has('applicant_email') ? ' has-error' : '' }}">--}}
                                        {{--<label class="control-label" for="applicant_email">Applicant Email</label>--}}
                                        {{--<input type="email" id="applicant_email" class="form-control" name="applicant_email" placeHolder="Enter Applicant Email">--}}
                                        {{--<div class="help-block">--}}
                                            {{--@if($errors->has('applicant_email'))--}}
                                                {{--<strong>{{ $errors->first('applicant_email') }}</strong>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-3">--}}
                                    {{--<div class="form-group {{ $errors->has('applicant_dob') ? ' has-error' : '' }}">--}}
                                        {{--<label class="control-label" for="applicant_dob">Applicant Birthdate</label>--}}
                                        {{--<input type="text" id="applicant_dob" class="form-control" name="applicant_dob" placeHolder="Enter Applicant Birthdate">--}}
                                        {{--<div class="help-block">--}}
                                            {{--@if($errors->has('applicant_dob'))--}}
                                                {{--<strong>{{ $errors->first('applicant_dob') }}</strong>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-3">--}}
                                    {{--<div class="form-group {{ $errors->has('applicant_status') ? ' has-error' : '' }}">--}}
                                        {{--<label class="control-label" for="applicant_status">Applicant Type</label>--}}
                                        {{--<select id="applicant_status" class="form-control applicantStatus academicChange" name="applicant_status">--}}
                                            {{--<option value="">--- Select Section ---</option>--}}
                                            {{--<option value="0" >Pending</option>--}}
                                            {{--<option value="1" >Active</option>--}}
                                            {{--<option value="2">Approved</option>--}}
                                            {{--<option value="3">Waiting</option>--}}
                                            {{--<option value="4">Disapproved</option>--}}
                                        {{--</select>--}}
                                        {{--<div class="help-block">--}}
                                            {{--@if ($errors->has('applicant_status'))--}}
                                                {{--<strong>{{ $errors->first('applicant_status') }}</strong>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div><!--./box-body-->
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-default pull-left">Reset</button>
                        </div>
                    </div><!--./box-solid-->
                </form>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            jQuery(document).on('change','.academicChange',function(){
                $('#manage_enquiry_content_row').html('');
            });

            //birth_date picker
            $('#applicant_dob').datepicker({
                autoclose: true
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
                $.ajax({
                    url: "{{ url('/academics/find/level') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statement
                    },

                    success:function(data){
                        //console.log(data.length);
                        var op ='<option value="0" selected>--- Select Level ---</option>';
                        for(var i=0;i<data.length;i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                        }

                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="" selected>--- Select Section ---</option>');

                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append('<option value="" selected>--- Select Batch ---</option>');

                        // set value to the academic batch
                        $('.academicLevel').html("");
                        $('.academicLevel').append(op);
                    },

                    error:function(){
                        // statement
                    }
                });
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicLevel',function(){
                // ajax request
                $.ajax({
                    url: "{{ url('/academics/find/batch') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statement
                    },

                    success:function(data){
                        var op='<option value="" selected >--- Select Batch ---</option>';
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
                        // statement
                    }
                });
            });

            // request for section list using batch id
            jQuery(document).on('change','.academicBatch',function(){
                $("#getBatchName").val('');
                var batchName=$(this).find('option:selected').text();
                $.ajax({
                    url: "{{ url('/academics/find/section') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statement
                    },

                    success:function(data){
                        var op ='<option value="" selected>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }

                        $("#getBatchName").val(batchName);
                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);
                    },

                    error:function(){

                    },
                });
            });

        });
    </script>

@endsection
