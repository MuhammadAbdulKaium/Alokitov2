@extends('onlineacademics::layouts.onlineacademic')
<!-- page content -->
@section('page-content')
<!-- grading scale -->
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
<div class="col-md-12">
    <div class="box box-solid">
        <div class="box-body">
            <div id="p0">
                @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                        style="text-decoration:none" data-dismiss="alert"
                        aria-label="close">&times;</a>{{ Session::get('message') }}</p>
                @endif

                @if(Session::has('error'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                        style="text-decoration:none" data-dismiss="alert"
                        aria-label="close">&times;</a>{{ Session::get('error') }}</p>
                @endif
            </div>

            <div class="row">
                @if(isset($topic_info->id))
                <form method="post" action="{{url('onlineacademics/onlineacademic/updateTopicInfo',$topic_info->id)}}"
                    enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    @else
                    <form method="post" action="{{url('onlineacademics/onlineacademic/getTopicInfo')}}"
                        enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        @endif

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Academic Level </label>
                                <select id="academic_level" class="form-control academicLevel" name="academic_level_id">
                                    <option value="" disabled="true" selected="true">--- Select Level ---</option>
                                    @if(isset($topic_info->academic_level_id))
                                    <option value="{{$topic_info->academic_level_id}}" selected="selected">
                                        {{$topic_info->academic_level}}</option>
                                    @else
                                    @foreach($allAcademicsLevel as $level)
                                    <option value="{{$level->id}}">{{$level->level_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @if($errors->has('academic_level_id'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong> {{ $errors->first('academic_level_id') }}</strong>
                                </span>
                                @endif
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Class</label>
                                <select id="batch" class="form-control academicBatch" name="batch">
                                    <option value="" disabled="true" selected="true">---Select Class ---</option>
                                    @if(isset($topic_info->academic_class_id))
                                    <option value="{{$topic_info->academic_class_id}}" selected="selected">
                                        {{$topic_info->academic_class}}</option>
                                    @endif
                                </select>
                                @if($errors->has('batch'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong> {{ $errors->first('batch') }}</strong>
                                </span>
                                @endif
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <div class="form-group">
                                <label class="control-label">Section</label>
                                <select id="section" class="form-control academicSection" name="section">
                                    <option value="" disabled="true" selected="true">--- Select Section ---</option>
                                    @if(isset($topic_info->academic_section_id))
                                    <option value="{{$topic_info->academic_section_id}}" selected="selected">
                                        {{$topic_info->academic_section}}</option>
                                    @endif
                                </select>
                                @if($errors->has('section'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong> {{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label class="control-label">Group</label>
                                <select id="group" class="form-control academicGroup" name="group">
                                    <option value="" disabled="true" selected="true">--- Select Group ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Subject</label>
                                <select id="subject" class="form-control academicSubject" name="subject">
                                    <option value="">--- Select Subject ---</option>
                                    @if(isset($topic_info->class_subject_id))
                                    <option value="{{$topic_info->class_subject_id}}" selected="selected">
                                        {{$topic_info->class_subject}}</option>
                                    @endif
                                </select>
                                @if($errors->has('subject'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong> {{ $errors->first('subject') }}</strong>
                                </span>
                                @endif
                                <span class="input-group-btn">
                                    <button id="search_btn" name="search" class="btn search-button" type="button">
                                        <i class="fa fa-search font-size22"></i>
                                    </button>
                                </span>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">File</label>
                                <input type="file" name="topic_attachment">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        @role(['super-admin','admin','teacher'])
                        <div class="col-sm-2">
                            <div class="input-group">
                                <label class="control-label">Topic Name</label>
                                <input id="class_topic" class="form-control" name="class_topic" type="text"
                                    value="{{ isset($topic_info->class_topic) ? $topic_info->class_topic : ''}}"
                                    placeholder="Write Topic">
                                @if($errors->has('class_topic'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong> {{ $errors->first('class_topic') }}</strong>
                                </span>
                                @endif

                                <!-- <input type="file" name="topic_attachment">  -->
                                <span class="input-group-btn form-group">
                                    <button name="submit" class="btn btn search-button" type="submit">
                                        @if(isset($topic_info->id))
                                        <span class="font-size18">Update</span>
                                        @else
                                        <span class="font-size18">Add</span>
                                        @endif
                                    </button>
                                </span>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        @endrole
                    </form>
            </div>
        </div>


        <div class="box-body table-responsive" id="getTopicList">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    <a data-sort="sub_master_name" style="color: #000;text-align: center;">
                                        Sl <i class="arrow down" style="float: right;"></i></a>
                                </th>
                                <th>
                                    <a data-sort="sub_master_alias" style="color: #000;">Level
                                        <button class="sort-btn btn-dsce table-sort dsce float-right"
                                            id="dsce">&#9650;</button>
                                        <button class="sort-btn btn-asc table-sort asce float-right"
                                            id="asce">&#9660;</button>
                                    </a>
                                </th>
                                <th>
                                    <a data-sort="sub_master_alias" style="color: #000;">Class
                                        <button class="sort-btn btn-dsce table-sort dsce float-right"
                                            id="dsce">&#9650;</button>
                                        <button class="sort-btn btn-asc table-sort asce float-right"
                                            id="asce">&#9660;</button>
                                    </a>
                                </th>
                                <th>
                                    <a data-sort="sub_master_alias" style="color: #000;">Section
                                        <button class="sort-btn btn-dsce table-sort dsce float-right"
                                            id="dsce">&#9650;</button>
                                        <button class="sort-btn btn-asc table-sort asce float-right"
                                            id="asce">&#9660;</button>
                                    </a>
                                </th>
                                <th>
                                    <a data-sort="sub_master_alias" style="color: #000;">Subject
                                        <button class="sort-btn btn-dsce table-sort dsce float-right"
                                            id="dsce">&#9650;</button>
                                        <button class="sort-btn btn-asc table-sort asce float-right"
                                            id="asce">&#9660;</button>
                                    </a>
                                </th>
                                <th>
                                    <a data-sort="sub_master_alias" style="color: #000;">Teacher
                                        <button class="sort-btn btn-dsce table-sort dsce float-right"
                                            id="dsce">&#9650;</button>
                                        <button class="sort-btn btn-asc table-sort asce float-right"
                                            id="asce">&#9660;</button>
                                    </a>
                                </th>
                                <th>
                                    <a data-sort="sub_master_alias" style="color: #000;">Topic
                                        <button class="sort-btn btn-dsce table-sort dsce float-right"
                                            id="dsce">&#9650;</button>
                                        <button class="sort-btn btn-asc table-sort asce float-right"
                                            id="asce">&#9660;</button>
                                    </a>
                                </th>
                                <th>
                                    <a data-sort="sub_master_alias" style="color: #000;">File Type
                                        <button class="sort-btn btn-dsce table-sort dsce float-right"
                                            id="dsce2">&#9650;</button>
                                        <button class="sort-btn btn-asc table-sort asce float-right"
                                            id="asce">&#9660;</button>
                                    </a>
                                </th>
                                @role(['super-admin','admin','teacher'])
                                <th>
                                    <a data-sort="sub_master_alias" style="color: #000;">Action
                                        <button class="sort-btn btn-dsce table-sort dsce float-right"
                                            id="dsce">&#9650;</button>
                                        <button class="sort-btn btn-asc table-sort asce float-right"
                                            id="asce">&#9660;</button>
                                    </a>
                                </th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($topic_list) && !empty($topic_list))
                            @foreach($topic_list as $row)
                            <tr class="gradeX" style="text-align: center;">
                                <td>{{ ++$loop->index }}.</td>
                                <td>{{ $row->academic_level }}</td>
                                <td>{{ $row->academic_class }}</td>
                                <td>{{ $row->academic_section }}</td>
                                <td>{{ $row->class_subject }}</td>
                                <td>{{ $row->class_teacher }}</td>
                                <td>{{ $row->class_topic }}</td>
                                <td>
                                    @if($row->file_path == 'null')
                                    <span>No File</span>
                                    @else
                                    <a href="{{ asset('upload/online_class_topic/'.$row->file_path) }}"
                                        download="{{ $row->file_path }}">
                                        {{ $row->file_path }}
                                    </a>
                                    @endif
                                </td>
                                @role(['super-admin','admin','teacher'])
                                <td>
                                    <a href="{{ url('onlineacademics/onlineacademic/edit', $row->id) }}"
                                        class="btn btn-primary btn-xs" data-placement="top" data-content="update">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ url('onlineacademics/onlineacademic/destroy', $row->id) }}"
                                        class="btn btn-danger btn-xs"
                                        onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                        data-content="delete"><i class="fa fa-trash-o"></i></a>
                                </td>
                                @else
                                <td>
                                    <a class="btn btn-primary btn-xs" data-placement="top" data-content="update">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a class="btn btn-danger btn-xs" data-placement="top" data-content="delete"><i
                                            class="fa fa-trash-o"></i></a>
                                </td>

                                @endrole
                            </tr>

                            @endforeach
                            @else
                            <tr style="text-align: center;">
                                <td colspan="9" style="color:red">Data Not Found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="link" style="float: right"></div>
            </div>
        </div>

        {{--topic list container row--}}
        <div id="topic_list_container">
            {{--topic list will be here--}}
        </div>
    </div>
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
@endsection

@section('scripts')
<script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true
        });
        $('#myTable').DataTable();
        setTimeout(function () {
            $('.alert').hide();
            $('.alert-info').hide();
            $('.alert-danger').hide();
        }, 3000);

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
            $(this).slideUp('slow', function () {
                $(this).remove();
            });
        });

        var subjects = [];

        // request for batch list using level id
        jQuery(document).on('change', '.academicYear', function () {
            // console.log("hmm its change");
            // get academic year id
            var year_id = $(this).val();
            var div = $(this).parent();
            var op = "";

            $.ajax({
                url: "{{ url('/academics/find/level') }}",
                type: 'GET',
                cache: false,
                data: {
                    'id': year_id
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function () {
                    console.log(year_id);
                },

                success: function (data) {
                    console.log('success');
                    //console.log(data.length);
                    op +=
                        '<option value="0" selected disabled>--- Select Level ---</option>';
                    for (var i = 0; i < data.length; i++) {
                        // console.log(data[i].level_name);
                        op += '<option value="' + data[i].id + '">' + data[i].level_name +
                            '</option>';
                    }

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append(
                        '<option value="" selected disabled>--- Select Section ---</option>'
                    );

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(
                        '<option value="" selected disabled>--- Select Class ---</option>'
                    );

                    // set value to the academic batch
                    $('.academicLevel').html("");
                    $('.academicLevel').append(op);
                },

                error: function () {

                }
            });
        });

        // request for batch list using level id
        jQuery(document).on('change', '.academicLevel', function () {
            // console.log("hmm its change");

            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op = "";

            $.ajax({
                url: "{{ url('/academics/find/batch') }}",
                type: 'GET',
                cache: false,
                data: {
                    'id': level_id
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function () {
                    console.log(level_id);
                },

                success: function (data) {
                    console.log('success');

                    //console.log(data.length);
                    op +=
                        '<option value="" selected disabled>--- Select Class ---</option>';
                    for (var i = 0; i < data.length; i++) {
                        op += '<option value="' + data[i].id + '">' + data[i].batch_name +
                            '</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append(
                        '<option value="0" selected disabled>--- Select Section ---</option>'
                    );
                },

                error: function () {

                }
            });
        });

        // request for section list using batch id
        jQuery(document).on('change', '.academicBatch', function () {
            console.log("hmm its change");

            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op = "";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {
                    'id': batch_id
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function () {
                    console.log(batch_id);
                },

                success: function (data) {
                    console.log('success');

                    //console.log(data.length);
                    op +=
                        '<option value="" selected disabled>--- Select Section ---</option>';
                    for (var i = 0; i < data.length; i++) {
                        op += '<option value="' + data[i].id + '">' + data[i].section_name +
                            '</option>';
                    }

                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                },

                error: function () {

                },
            });
        });

        // request for section list using batch id
        jQuery(document).on('change', '.academicSection', function () {
            // get academic level id
            var section_id = $(this).val();
            var class_id = $('#batch').val();
            var div = $(this).parent();
            var op = "";

            $.ajax({
                url: "{{ url('/academics/find/subjcet') }}",
                type: 'GET',
                cache: false,
                data: {
                    'class_id': class_id,
                    'section_id': section_id
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function () {
                    console.log(class_id, section_id);
                },

                success: function (data) {
                    console.log('success');

                    console.log(data);

                    subjects = data;

                    op +=
                        '<option value="" selected disabled>--- Select Subject ---</option>';
                    for (var i = 0; i < data.length; i++) {
                        op += '<option value="' + data[i].id + '">' + data[i].sub_name +
                            '</option>';
                    }

                    // set value to the academic batch
                    $('.academicSubject').html("");
                    $('.academicSubject').append(op);
                },

                error: function () {

                },


            });

            // An ajax request again for section wise Group
            $.ajax({
                url: "{{ url('/academics/find/group') }}",
                type: 'GET',
                cache: false,
                data: {
                    'section_id': section_id
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function () {

                },

                success: function (data) {
                    var op ='<option value="" selected disabled>--- Select Group ---</option>';
                    data.forEach((item) => {
                        op += '<option value="'+item.id+'">'+item.name+'</option>';
                    });

                    $('.academicGroup').empty();
                    $('.academicGroup').append(op);
                },

                error: function () {

                },
            });



        });

        // request for section list using batch id
        jQuery(document).on('change', '.academicGroup', function () {
            console.log($(this).val());

            // An ajax request for Group wise subject
            $.ajax({
                url: "{{ url('/academics/find/group/subject') }}",
                type: 'GET',
                cache: false,
                data: {
                    'division_id': $(this).val()
                }, //see the $_token
                datatype: 'application/json',

                beforeSend: function () {

                },

                success: function (data) {
                    console.log(data);

                    var newSubjects = [];

                    subjects.forEach((item, i) => {
                        data.forEach((ele) => {
                            if (item.sub_id == ele.id) {
                                newSubjects.push(item);
                            }
                        });
                    });

                    console.log(newSubjects);

                    var op =
                        '<option value="" selected disabled>--- Select Subject ---</option>';
                    for (var i = 0; i < data.length; i++) {
                        op += '<option value="' + newSubjects[i].id + '">' + newSubjects[i].sub_name +
                            '</option>';
                    }

                    // set value to the academic batch
                    $('.academicSubject').html("");
                    $('.academicSubject').append(op);
                },

                error: function () {

                },
            });



        });

        $('#search_btn').click(function (e) {
            e.preventDefault();

            // get academic level id
            var level_id = $('#academic_level').val();
            var class_id = $('#batch').val();
            var section_id = $('#section').val();
            var subject_id = $('#subject').val();
            var class_topic = $('#class_topic').val();

            //var div = $(this).parent();
            //var op="";

            $.ajax({
                url: "{{ url('/onlineacademics/onlineacademic/find/topic') }}",
                type: 'GET',
                cache: false,
                data: {
                    'level_id': level_id,
                    'class_id': class_id,
                    'section_id': section_id,
                    'subject_id': subject_id,
                    'class_topic': class_topic
                }, //see the $_token
                datatype: 'html',
                //datatype: 'application/json',

                beforeSend: function () {
                    console.log(level_id, class_id, section_id, subject_id, class_topic);
                    waitingDialog.show('Loading...');
                },
                success: function (data) {
                    if (data) {
                        console.log('success');
                        console.log(data);
                        //$('#example2').html('');
                        //$('#example2').append(data);
                        //alert(data.length);
                        waitingDialog.hide();
                        $('#getTopicList').hide();
                        $('#topic_list_container').html('');
                        $('#topic_list_container').append(data);
                    } else {
                        console.log('failed');
                    }
                },
                error: function () {

                },
            });
        });

    });
</script>
@endsection
