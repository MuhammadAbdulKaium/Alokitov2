@extends('onlineacademics::layouts.onlineacademic')
<!-- page content -->
@section('page-content')
<!-- grading scale -->
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
<style type="text/css">
.label-margin50{
    margin-left: 50px;
}
.redcolor{
    color: red;
}
</style>
@role(['super-admin','admin'])
<div class="col-md-12">
    <div class="box box-solid">
        <div class="box-body">
            <div id="p0">
                @if(Session::has('message'))
                    <p class="alert alert-success alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
                @endif

                @if(Session::has('error'))
                    <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('error') }}</p>
                @endif
            </div>

            <div class="row">
                <!--
                <form method="post" action="{{url('onlineacademics/onlineacademic/ClassHistory')}}">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                -->
                <form id="manage_class_schedule_form">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="col-sm-3">
                    <div class="row" style="padding-right: 0px">
                        
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label class="control-label ">Academic Level</label>
                                    <select id="level" class="form-control academicLevel" name="level" required>
                                        <option value="" selected disabled>--Select Level--</option>
                                        @foreach($allAcademicsLevel as $level)
                                            <option value="{{$level->id}}">{{$level->level_name}}</option>
                                        @endforeach
                                    </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="form-group">
                                <label class="control-label label-margin50">Class</label>
                                <select id="batch" class="form-control academicBatch" name="batch" required>
                                    <option value="" disabled="true" selected="true">--Select Class--</option>
                                    @if(isset($topic_info->academic_class_id))
                                        <option value="{{$topic_info->academic_class_id}}" selected="selected">{{$topic_info->academic_class}}</option>
                                    @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="control-label label-margin50">Section</label>
                                    <select id="section" class="form-control academicSection" name="section" required>
                                    <option value="" disabled="true" selected="true">--Select Section--</option>
                                    @if(isset($topic_info->academic_section_id))
                                    <option value="{{$topic_info->academic_section_id}}" selected="selected">{{$topic_info->academic_section}}</option>
                                    @endif
                                    </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="control-label label-margin50">Shift</label>
                                    <select id="shift" class="form-control academicShift" name="shift" required>
                                        <option value="" selected disabled>--Select Shift--</option>
                                        <option value="0">Day</option>
                                        <option value="1">Morning</option>
                                    </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="control-label label-margin50">Subject</label>
                                <select id="subject" class="form-control academicSubject" name="subject">
                                <option value="">--Select Subject--</option>
                                @if(isset($topic_info->class_subject_id))
                                <option value="{{$topic_info->class_subject_id}}" selected="selected">{{$topic_info->class_subject}}</option>
                                @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label label-margin50">Teacher</label>
                                <select id="teacher_id" class="form-control academicTeacher" name="teacher_id">
                                    <option value="">--Select Teacher--</option>
                                    @foreach($empList as $emp)
                                    <option value="{{$emp->id}}">{{$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label label-margin50">Topic</label>
                                <select id="subject_class_topic" class="form-control academicSubjectTopic" name="subject_class_topic">
                                    <option value="">--Select Topic--</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="row" style="margin: 0; padding: 0;">
                        <div class="col-sm-12 label-margin50">
                            <span><strong>Online Class Date</strong></span>
                        </div>
                    </div>
                    <div class="row" style="margin: 0; padding: 0;">     
                        <div class="col-sm-6" style="margin: 0; padding: 0;">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate required">
                                  <input type="text" class="form-control sdate" name="start_date" value="{{ date('m/d/Y') }}" required="" readonly="" aria-required="true" placeholder="Start Date">
                                <div class="help-block"></div>
                            </div>  
                        </div>
                        <div class="col-sm-6" style="margin: 0;">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate required">
                                <input type="text" value="{{ date('m/d/Y') }}" class="form-control sdate" name="end_date" required="" readonly="" aria-required="true" placeholder="End Date">
                                <div class="help-block"></div>
                            </div>  
                        </div>
                    </div>
                </div>

                <div class="col-sm-1" >
                    <div class="input-group">
                        <label class="control-label">Status</label>
                        <select id="status" class="form-control" name="status">
                            <option value="" disabled="true" selected="true">--Select Status--</option>
                            <option value="1">Unscheduled</option>
                            <option value="2">Scheduled</option>
                            <option value="3">Ongoing</option>
                            <option value="4">Completed</option>
                            <option value="5">Class Time Over</option>
                        </select>
                        <span class="input-group-btn">
                            <button id="class_schedule_list_search_btn" class="btn search-button" type="submit">
                                <i class="fa fa-search font-size22"></i>
                            </button>
                        </span>
                        <div class="help-block"></div>
                    </div>
                </div>
                </form>
            </div>
        </div>

        {{--Class Schedule list container row--}}
        <div id="class_schedule_list_container">
            {{--class schedule list will be here--}}
        </div>

</div>
</div>
 @endrole

 @role(['teacher'])
<div class="col-md-12">
    <div class="box box-solid">
        <div class="box-body">
            <div id="p0">
                @if(Session::has('message'))
                    <p class="alert alert-success alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
                @endif

                @if(Session::has('error'))
                    <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('error') }}</p>
                @endif
            </div>

            <div class="row">
                <!--
                <form method="post" action="{{url('onlineacademics/onlineacademic/ClassHistory')}}">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                -->
                <form id="manage_class_schedule_form">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="col-sm-3">
                    <div class="row" style="padding-right: 0px">
                        
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label class="control-label ">Academic Level</label>
                                    <select id="level" class="form-control academicLevel" name="level" required>
                                        <option value="" selected disabled>--Select Level--</option>
                                        @foreach($allAcademicsLevel as $level)
                                            <option value="{{$level->id}}">{{$level->level_name}}</option>
                                        @endforeach
                                    </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="form-group">
                                <label class="control-label label-margin50">Class</label>
                                <select id="batch" class="form-control academicBatch" name="batch" required>
                                    <option value="" disabled="true" selected="true">--Select Class--</option>
                                    @if(isset($topic_info->academic_class_id))
                                        <option value="{{$topic_info->academic_class_id}}" selected="selected">{{$topic_info->academic_class}}</option>
                                    @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="control-label label-margin50">Section</label>
                                    <select id="section" class="form-control academicSection" name="section" required>
                                    <option value="" disabled="true" selected="true">--Select Section--</option>
                                    @if(isset($topic_info->academic_section_id))
                                    <option value="{{$topic_info->academic_section_id}}" selected="selected">{{$topic_info->academic_section}}</option>
                                    @endif
                                    </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="control-label label-margin50">Shift</label>
                                    <select id="shift" class="form-control academicShift" name="shift" required>
                                        <option value="" selected disabled>--Select Shift--</option>
                                        <option value="0">Day</option>
                                        <option value="1">Morning</option>
                                    </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="control-label label-margin50">Subject</label>
                                <select id="subject" class="form-control academicSubject" name="subject">
                                <option value="">--Select Subject--</option>
                                @if(isset($topic_info->class_subject_id))
                                <option value="{{$topic_info->class_subject_id}}" selected="selected">{{$topic_info->class_subject}}</option>
                                @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label label-margin50">Teacher</label>
                                <select id="teacher_id" class="form-control academicTeacher" name="teacher_id">
                                    <option value="">--Select Teacher--</option>
                                    @foreach($empList as $emp)
                                    <option value="{{$emp->id}}">{{$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label label-margin50">Topic</label>
                                <select id="subject_class_topic" class="form-control academicSubjectTopic" name="subject_class_topic">
                                    <option value="">--Select Topic--</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="row" style="margin: 0; padding: 0;">
                        <div class="col-sm-12 label-margin50">
                            <span><strong>Online Class Date</strong></span>
                        </div>
                    </div>
                    <div class="row" style="margin: 0; padding: 0;">     
                        <div class="col-sm-6" style="margin: 0; padding: 0;">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate required">
                                  <input type="text" class="form-control sdate" name="start_date" value="{{ date('m/d/Y') }}" required="" readonly="" aria-required="true" placeholder="Start Date">
                                <div class="help-block"></div>
                            </div>  
                        </div>
                        <div class="col-sm-6" style="margin: 0;">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate required">
                                <input type="text" value="{{ date('m/d/Y') }}" class="form-control sdate" name="end_date" required="" readonly="" aria-required="true" placeholder="End Date">
                                <div class="help-block"></div>
                            </div>  
                        </div>
                    </div>
                </div>

                <div class="col-sm-1" >
                    <div class="input-group">
                        <label class="control-label">Status</label>
                        <select id="status" class="form-control" name="status">
                            <option value="" disabled="true" selected="true">--Select Status--</option>
                            <option value="1">Unscheduled</option>
                            <option value="2">Scheduled</option>
                            <option value="3">Ongoing</option>
                            <option value="4">Completed</option>
                            <option value="5">Class Time Over</option>
                        </select>
                        <span class="input-group-btn">
                            <button id="class_schedule_list_search_btn" class="btn search-button" type="submit">
                                <i class="fa fa-search font-size22"></i>
                            </button>
                        </span>
                        <div class="help-block"></div>
                    </div>
                </div>
                </form>
            </div>
        </div>

        {{--Class Schedule list container row--}}
        <div id="class_schedule_list_container">
            {{--class schedule list will be here--}}
        </div>

</div>
</div>
 @endrole

 @role(['student'])
<div class="col-md-12">
    <div class="box box-solid">
        <div class="box-body">
            <div id="p0">
                @if(Session::has('message'))
                    <p class="alert alert-success alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
                @endif

                @if(Session::has('error'))
                    <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('error') }}</p>
                @endif
            </div>

            <div class="row">

                
                <!--
                <form method="post" action="{{url('onlineacademics/onlineacademic/ClassHistory')}}">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                -->

                @php $enrollment = $personalInfo->enroll(); @endphp

                <form id="manage_class_schedule_form">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="col-sm-3">
                    <div class="row" style="padding-right: 0px">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label ">Academic Level</label>
                            <select id="level" class="form-control academicLevel" name="level" required>
                            <option value="{{$enrollment->level()->id}}">{{$enrollment->level()->level_name}} </option>
                            </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        @php $division = null; @endphp
                            @if($divisionInfo = $enrollment->batch()->get_division())
                                @php $division = " (".$divisionInfo->name.") ";
                            @endphp
                         @endif
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label label-margin50">Class</label>
                                <select id="batch" class="form-control academicBatch" name="batch" required>
                                <option value=" {{ $enrollment->batch()->id }}" selected="selected"> {{$enrollment->batch()->batch_name.$division}} </option>
                           
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="control-label ">Section</label>
                                    <select id="section" class="form-control academicSection" name="section" required>
                                    <option value="{{$enrollment->section()->id}}" selected="selected">{{$enrollment->section()->section_name}} </option>
                                    </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="control-label label-margin50">Shift</label>
                                    <select id="shift" class="form-control academicShift" name="shift" required>
                                        <option value="" selected disabled>--Select Shift--</option>
                                        <option value="0">Day</option>
                                        <option value="1">Morning</option>
                                    </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="control-label label-margin50">Subject</label>
                                <select id="subject" class="form-control academicSubject" name="subject">
                                <option value="">--Select Subject--</option>
                                @if(isset($topic_info->class_subject_id))
                                <option value="{{$topic_info->class_subject_id}}" selected="selected">{{$topic_info->class_subject}}</option>
                                @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label label-margin50">Teacher</label>
                                <select id="teacher_id" class="form-control academicTeacher" name="teacher_id">
                                    <option value="">--Select Teacher--</option>
                                    @foreach($empList as $emp)
                                    <option value="{{$emp->id}}">{{$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label label-margin50">Topic</label>
                                <select id="subject_class_topic" class="form-control academicSubjectTopic" name="subject_class_topic">
                                    <option value="">--Select Topic--</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="row" style="margin: 0; padding: 0;">
                        <div class="col-sm-12 label-margin50">
                            <span><strong>Online Class Date</strong></span>
                        </div>
                    </div>
                    <div class="row" style="margin: 0; padding: 0;">     
                        <div class="col-sm-6" style="margin: 0; padding: 0;">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate required">
                                  <input type="text" class="form-control sdate" name="start_date" value="{{ date('m/d/Y') }}" required="" readonly="" aria-required="true" placeholder="Start Date">
                                <div class="help-block"></div>
                            </div>  
                        </div>
                        <div class="col-sm-6" style="margin: 0;">
                            <div class="form-group field-feespaymenttransactionsearch-fp_sdate required">
                                <input type="text" value="{{ date('m/d/Y') }}" class="form-control sdate" name="end_date" required="" readonly="" aria-required="true" placeholder="End Date">
                                <div class="help-block"></div>
                            </div>  
                        </div>
                    </div>
                </div>

                <div class="col-sm-1" >
                    <div class="input-group">
                        <label class="control-label">Status</label>
                        <select id="status" class="form-control" name="status">
                            <option value="" disabled="true" selected="true">--Select Status--</option>
                            <option value="1">Unscheduled</option>
                            <option value="2">Scheduled</option>
                            <option value="3">Ongoing</option>
                            <option value="4">Completed</option>
                            <option value="5">Class Time Over</option>
                        </select>
                        <span class="input-group-btn">
                            <button id="class_schedule_list_search_btn" class="btn search-button" type="submit">
                                <i class="fa fa-search font-size22"></i>
                            </button>
                        </span>
                        <div class="help-block"></div>
                    </div>
                </div>
                </form>
            </div>
        </div>

        {{--Class Schedule list container row--}}
        <div id="class_schedule_list_container">
            {{--class schedule list will be here--}}
        </div>

</div>
</div>
 @endrole

</div>
@endsection

@section('page-script')
{{--<script>--}}
$(document).ready(function(){
    $(".dsce").hide();
    $(".asce").click(function(){
        $(".asce").hide();
        $(".dsce").show();
    });
    $(".dsce").click(function(){
        $(".dsce").hide();
        $(".asce").show();
    }); 
});

$('.online_delete_class').click(function(e){
del_id = $(this).attr('id');
var tr = $(this).closest('tr');

swal({
     title: "Are you sure?",
    text: "You want to delete Class Topic",
     type: "warning",
    showCancelButton: true,
    confirmButtonColor: '#DD6B55',
    confirmButtonText: 'Yes, I am sure!',
    cancelButtonText: "No, cancel it!",
    closeOnConfirm: false,
    closeOnCancel: false
  },
function(isConfirm){

if (isConfirm){
   $.ajax({
        url: "#"+ del_id,
        type: 'GET',
        cache: false,
       success:function(result){
               if(result=='success') {
                tr.fadeOut(1000, function () {
                $(this).remove();
 });
       swal("Success!", "Class Topic deleted successfully", "success");
}
else {
    swal("Waining!", "Can't delete Class Topic", "warning");
   }
 }
});

}
else {
     swal("Cancelled", "Your Class Topic is safe :)", "error");
     e.preventDefault();
  }
});
});

$('#search_start_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
$('#search_end_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
@endsection

@section('scripts')
<script>
jQuery(function(){
  jQuery.ajaxSetup({
    headers: { 'X-CSRF-Token' : '{{csrf_token()}}' }
  });
});  
</script>
<script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#myTable').DataTable();
        $('.sdate').datepicker({
            autoclose: true
        });
        setTimeout(function(){
            $('.alert').hide();
            $('.alert-info').hide();
            $('.alert-danger').hide();
        },3000);

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
                console.log(year_id);

            },

            success:function(data){
                console.log('success');

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
                $('.academicBatch').append('<option value="" selected disabled>--- Select Class ---</option>');

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
                // console.log(level_id);
            },

            success:function(data){
                console.log('success');

                //console.log(data.length);
                op+='<option value="" selected disabled>--- Select Class ---</option>';
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
                console.log('success');

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

            },
        });
    });

    // request for section list using batch id
    jQuery(document).on('change','.academicSection',function(){
        // get academic level id
        var section_id  = $(this).val();
        var class_id    = $('#batch').val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/subjcet') }}",
            type: 'GET',
            cache: false,
            data: {'class_id': class_id, 'section_id': section_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(class_id,section_id);
            },

            success:function(data){
                console.log('success');

                console.log(data);

                op+='<option value="" selected disabled>--- Select Subject ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                }

                // set value to the academic batch
                $('.academicSubject').html("");
                $('.academicSubject').append(op);
            },

            error:function(){

            },
        });
    });

    // request for teacher list using subject id
    jQuery(document).on('change','.academicSubject',function(){
        // get academic level id
        var class_id        = $('#batch').val();
        var section_id      = $('#section').val();
        var subject_id      = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            //url: "{{ url('/onlineacademics/find/teacher') }}",
            url: "{{ url('/onlineacademics/find/ajax_teacher_topic') }}",
            type: 'GET',
            cache: false,
            data: {'class_id': class_id, 'section_id': section_id, 'subject_id': subject_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(class_id,section_id,subject_id);
            },

            success:function(data){
                console.log('success');

                console.log(data);
                // set value to the academic teacher
                if(data.teacher_data){
                    //alert('teacher data goes to here');
                    op+='<option value="" selected disabled>--- Select Teacher ---</option>';
                    for(var i=0;i<data.teacher_data.length;i++){
                        op+='<option value="'+data.teacher_data[i].id+'">'+data.teacher_data[i].first_name+'</option>';
                    }

                    $('.academicTeacher').html("");
                    $('.academicTeacher').append(op);
                }
                // set value to the academic topic
                if(data.topic_data){
                    //alert('topic data goes to here');
                    op+='<option value="" selected disabled>--- Select Topic ---</option>';
                    for(var i=0;i<data.topic_data.length;i++){
                        op+='<option value="'+data.topic_data[i].id+'">'+data.topic_data[i].sub_topic+'</option>';
                    }

                    $('.academicSubjectTopic').html("");
                    $('.academicSubjectTopic').append(op);
                }
                
            },

            error:function(){

            },
        });
    });

    // request for teacher list using subject id
    jQuery(document).on('change','.academicTeacher',function(){
        // get academic level id
        var class_id        = $('#batch').val();
        var section_id      = $('#section').val();
        var subject_id      = $('#subject').val();
        var teacher_id      = $(this).val();
        var div             = $(this).parent();
        var op              = "";

        $.ajax({
            url: "{{ url('/onlineacademics/onlineacademic/find/ajax_topic') }}",
            type: 'GET',
            cache: false,
            data: {'class_id': class_id, 'section_id': section_id, 'subject_id': subject_id, 'teacher_id': teacher_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(class_id,section_id,subject_id,teacher_id);
            },

            success:function(data){
                console.log('success');

                console.log(data);

                op+='<option value="" selected disabled>--- Select Topic ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].sub_topic+'</option>';
                }

                // set value to the academic topic
                $('.academicSubjectTopic').html("");
                $('.academicSubjectTopic').append(op);
            },

            error:function(){

            },
        });
    });

    // request for section list using batch id
    jQuery(document).on('change','.academicTopic',function(){
        // get academic level id
        var section_id  = $('#section').val();
        var class_id    = $('#batch').val();
        var teacher_id  = $('#teacher_id').val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/onlineacademics/onlineacademic/find/topic') }}",
            type: 'GET',
            cache: false,
            data: {'section_id': section_id, 'class_id': class_id, 'teacher_id': teacher_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(section_id,class_id,teacher_id);
            },

            success:function(data){
                console.log('success');

                console.log(data);

                op+='<option value="" selected disabled>--- Select Topic ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].sub_topic+'</option>';
                }

                // set value to the academic batch
                $('.academicSubjectTopic').html("");
                $('.academicSubjectTopic').append(op);
            },

            error:function(){

            },
        });
    });

    // Tequest For Topic Schedule List Status Changed
    jQuery(document).on('click','.topic_schedule',function(){
        var ScheduleVal = $('#topic_scheule_name').val();
        alert('Value :'+ScheduleVal);
        // get academic level id
        var section_id  = $(this).val();
        var class_id    = $('#batch').val();
        var div = $(this).parent();
        var op="";
        /*
        $.ajax({
            url: "{{ url('/academics/find/subjcet') }}",
            type: 'GET',
            cache: false,
            data: {'class_id': class_id, 'section_id': section_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                console.log(class_id,section_id);
            },

            success:function(data){
                console.log('success');

                console.log(data);

                op+='<option value="" selected disabled>--- Select Subject ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                }

                // set value to the academic batch
                $('.academicSubject').html("");
                $('.academicSubject').append(op);
            },

            error:function(){

            },
        });
        */
    });

    jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
        $(this).slideUp('slow', function () {
            $(this).remove();
        });
    });

    // request for parent list using batch section id
    $('form#manage_class_schedule_form').on('submit', function (e) {
        e.preventDefault();
        // ajax request
        $.ajax({
            url: "{{ url('/onlineacademics/onlineacademic/ClassHistory') }}",
            type: 'POST',
            cache: false,
            data: $('form#manage_class_schedule_form').serialize(),
            datatype: 'html',
            // datatype: 'application/json',

            beforeSend: function() {
                // show waiting dialog
                waitingDialog.show('Loading...');
            },

            success:function(data){
                // hide waiting dialog
                waitingDialog.hide();
                // refresh attendance container row
                $('#class_schedule_list_container').html('');
                $('#class_schedule_list_container').append(data);
            },
            error:function(){
                // sweet alert
                swal("Error", 'Unable to load data form server', "error");
            }
        });
    });

});
</script>
@endsection


