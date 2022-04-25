
@extends('layouts.master')

@section('styles')
    <style type="text/css">
        body.dragging, body.dragging * {
            cursor: move !important;
        }

        .dragged {
            position: absolute;
            opacity: 0.5;
            z-index: 2000;
        }

        .sorted_table tr {
            cursor: pointer;
        }

        .select2-container{
            width: 100% !important;
            /* min-width: 150px; */
        }
    </style>

    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <!-- select2 -->
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet"/>
@endsection

<!-- page content -->
@section('content')

    {{--batch string--}}
    @php $batchString="Class"; @endphp

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus-square"></i> Manage Subject
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student">Student</a></li>
                <li><a href="/student/manage/profile">Manage Academics</a></li>
                <li class="active">Manage Subject</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif

            @if(Session::has('batchId') AND Session::has('sectionId'))
                @php
                    $batchId = Session::get('batchId');
                    $sectionId = Session::get('sectionId');
                @endphp
            @else
                @php $batchId = null; $sectionId = null; @endphp
            @endif

            <div class="box box-solid">
                <div class="box-body">
                    <!-- <h2 class="page-header edusec-page-header-1">
                        <i class="fa fa-info-circle"></i> Personal Details
                    </h2> -->

                    <ul class="nav nav-tabs">
                        <li class="my-tab active"><a data-toggle="tab" href="#class_subject">Class Subject</a></li>
                        <li class="my-tab"><a data-toggle="tab" href="#fourth_subject">Variable Subject</a></li>
                    </ul>

                    <br/>
                    <div class="tab-content">
                        {{--manage all subject tab --}}
                        <div id="class_subject" class="tab-pane fade in active">
                            <div class="row">
                                {{--include all subject manage page--}}
                                @include('academics::manage-academics.includes.manage-class-all-subject')
                            </div>
                        </div>

                        {{--manage 4th subject tab--}}
                        <div id="fourth_subject" class="tab-pane fade in">
                            {{--include all subject manage page--}}
                            @include('academics::manage-academics.includes.manage-class-fourth-subject')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- global modal -->
    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
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
    <select name="teachers" class="form-control" multiple>
        <option value=""></option>
    </select>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/jquery-sortable.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/select2.full.min.js')}}"></script>

    {{--page script--}}
    <script type="text/javascript">
        var my_group_list = '<option value="0" selected>N/A</option>';
        var teacherList = {!! $teacherList !!};

        $(document).ready(function () {
            $('#count_all_class_subject').click(function () {
                // checking is checked or not
                if ($(this).is(':checked')) {
                    // check all
                    $('.is_countable').prop('checked',true);
                } else {
                    $('.is_countable').prop('checked',false);
                }
            });

            $("#example2").DataTable();
            $('#example1').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });

            $('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            // Sortable rows
            $('.sorted_table').sortable({
                containerSelector: 'table',
                itemPath: '> tbody',
                itemSelector: 'tr',
                placeholder: '<tr class="placeholder"/>',
                onDrop: function ($item, container, _super) {
                    _super($item, container);

                    // sorting order
                    var count = 1;
                    $("tr.item").each(function() {
                        $this = $(this)
                        var rowId = $this.attr("id");
                        var sortIid = rowId.replace("row","sort");
                        $("#"+sortIid).val(count);
                        var subId = rowId.replace("row_","");
                        $('#'+subId).removeAttr('name');
                        $('#'+subId).attr('name','subjects[sub_'+count+']');
                        count++;
                    });
                }
            });

            // set submit button disable
            sbmitButtonStatus();

            $(document).on('change', '#batch_id', function (param) {
                // remove previous all table row
                $("#tableBody").html('');
                // remove previous all checked subjects
                $(":checkbox").prop( "checked", false);
                // set counter default value
                $("#tr_count").val(0);

                // append table rows
                $("#tableHead").addClass('hide');
                $("#tableBody").addClass('hide');
                $("#tableFoot").addClass('hide');
                $(":submit").addClass("hide");
            });

            // request for sction subject list using section id
            $(document).on('change','.section_id',function(){
                // console.log("hmm its change");
                // get academic secton id
                var batch_id = $("#batch_id").val();
                var section_id = $(this).val();
                // checking batch and section
                if(batch_id && section_id){
                    // load section subject
                    loadBatchSectionSubjectList(batch_id, section_id);
                }
            });

            $(document).on('change','.copy_from',function(){
                // console.log("hmm its change");
                // table row
                var selectedValue = $("#copy_from").val();
                var td = '';
                // get academic secton id
                var batch_id = $("#"+selectedValue+"_batch").val();
                var section_id = $("#"+selectedValue+"_section").val();
                // checking batch and section
                if(batch_id && section_id){
                    // load section subject
                    loadBatchSectionSubjectList(batch_id, section_id, true);
                }
            });

            function sbmitButtonStatus(){
                // disable submit button
                if ($("#tr_count").val() == 0) {
                    $(":submit").attr("disabled", true);
                }
                // active submit button
                if ($("#tr_count").val() != 0) {
                    $(":submit").removeAttr("disabled");
                }
            }

        });

        @if(Session::has('batchId') AND Session::has('sectionId'))
        // get academic secton id
        var batch_id = '{{$batchId}}';
        var section_id = '{{$sectionId}}';
        // checking batch and section
        if(batch_id && section_id){
            // load section subject
            loadBatchSectionSubjectList(batch_id, section_id);
        }
        @endif


        function loadBatchSectionSubjectList(batch_id, section_id, is_copy=null) {
            // table row
            var td = '';
            // ajax request
            $.ajax({
                url: "{{ url('/academics/manage/class/subject/section') }}",
                type: 'GET',
                cache: false,
                data: {'section': section_id, 'batch':batch_id },
                datatype: 'application/json',

                beforeSend: function() {
                    // remove previous all table row
                    $("#tableBody").html('');
                    // remove previous all checked subjects
                    $(":checkbox").prop( "checked", false);
                    // set counter default value
                    $("#tr_count").val(0);

                    // append table rows
                    $("#tableHead").addClass('hide');
                    $("#tableBody").addClass('hide');
                    $("#tableFoot").addClass('hide');
                    $(":submit").addClass("hide");
                },

                success:function(responseData){
                    var data = responseData.class_sub_list;
                    sub_group = responseData.sub_group_list;

                    my_group_list = '<option value="0" selected>N/A</option>';
                    // checking subject group list
                    if(sub_group.length>0){
                        // looping
                        for(var i=0;i<sub_group.length;i++){
                            my_group_list += '<option value="'+sub_group[i].id+'">'+sub_group[i].name+'</option>';
                        }
                    }

                    // checking class subject list
                    if(data.length>0){
                        // looping
                        for(var i=0;i<data.length;i++){
                            var teacher = data[i].teacher;
                            var viewTeacher = null;
                            var sub_count_check = (data[i].is_countable==1?'checked':'');
                            // if(teacher>0){
                            //     viewTeacher = '<a href="/academics/manage/subjcet/teacher/assign/'+data[i].id+'" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Assign</a><a id="view_'+data[i].id+'" href="/academics/manage/subjcet/teacher/view/'+data[i].id+'" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> / View</a>'
                            // }else{
                            //     viewTeacher =  '<a href="/academics/manage/subjcet/teacher/assign/'+data[i].id+'" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Assign</a>'
                            // }

                            let teacherOptions = "";
                            var teacherSelected = "";
                            teacherList.forEach(teacher => {
                                teacherSelected = (data[i].teacherIds.includes(teacher.id))?"selected":"";
                                teacherOptions += '<option value="'+teacher.id+'" '+teacherSelected+'>'+teacher.first_name+' ('+teacher.single_user.username+')</option>';
                            });

                            viewTeacher = '<select class="form-control subject-teacher-list" name="subject'+data[i].subjectId+'[teachers][]" multiple>'+teacherOptions+'</select>';

                            // cs id
                            var cs_id = is_copy?0:data[i].id;
                            // table row
                            td += '<tr class="item" id="row_'+data[i].subjectId+'"><td><input type="hidden" name="subject'+data[i].subjectId+
                                '[is_countable]" value="0"><input type="checkbox" class="is_countable" name="subject'+data[i].subjectId+
                                '[is_countable]" value="1" '+sub_count_check+'></td><td><input id="cs_id_'+data[i].subjectId+
                                '" type="hidden" name="subject'+data[i].subjectId+'[cs_id]" value="'+cs_id+'">'+data[i].subjectName+
                                '<input type="hidden" name="subject'+data[i].subjectId+'[id]" value="'+data[i].subjectId+
                                '"><input type="hidden" id="sort_'+data[i].subjectId+'" name="subject'+data[i].subjectId+
                                '[sort_order]" value="'+data[i].sortOrder+'"></td> <td><input class="form-control" type="text" name="subject'+
                                data[i].subjectId+'[code]" value="'+data[i].subjectCode+'" required></td> <td><select id="subject_type'+
                                data[i].subjectId+'" class="form-control subject_type" name="subject'+data[i].subjectId+
                                '[type]" required><option value="0" selected> N/A</option><option value="1">Compulsory</option><option value="2">Elective</option><option value="3">Optional</option></select></td><td><select id="subject_group'+
                                data[i].subjectId+'" class="form-control select-subject-group" name="subject'+data[i].subjectId+'[group]" required disabled>'+my_group_list+
                                '</select></td><td><select id="subject_list'+data[i].subjectId+'" class="form-control" name="subject'+data[i].subjectId+
                                '[list]" required><option value="0" selected> N/A</option><option value="1">One</option><option value="2">Two</option><option value="3">Three</option></select></td><td>'+viewTeacher+'</td> </tr>';

                            // marking selected the checkbox
                            $("#"+data[i].subjectId).prop( "checked", true );
                            //add name attribute
                            $("#"+data[i].subjectId).attr('name','subjects[sub_'+data[i].sortOrder+']');
                            //add value attribute
                            $("#"+data[i].subjectId).attr('value', data[i].subjectId);
                        }
                        var deleteItem = '<input type="hidden" name="deleteList[]" value="">';

                        // append table rows
                        $("#tableHead").removeClass('hide');
                        $("#tableBody").removeClass('hide');
                        $("#tableFoot").removeClass('hide');
                        $(":submit").removeClass("hide");


                        $("#tableBody").append(td);
                        $("#tableBody").append(deleteItem);
                        // set counter value
                        document.getElementById('tr_count').value = data.length;

                        // make the subject type selected
                        for(var m=0;m<data.length;m++){
                            $("#subject_type"+data[m].subjectId).val(data[m].subjectType).change();
                            $("#subject_group"+data[m].subjectId).val(data[m].subjectGroup).change();
                            $("#subject_list"+data[m].subjectId).val(data[m].subjectList).change();
                        }
                        sbmitButtonStatus();
                        $("#copy_from").attr("disabled", "disabled");
                    }
                    else{

                        $("#copy_from").removeAttr('disabled');
                    }

                    $('.subject-teacher-list').select2();
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        }


        function addSubject(id){
            if($("#section_id").val() != null){
                var subjectId = id+"_s";
                var subjectName = $("#"+id+"_s").val();
                var subjectCode = $("#"+id+"_c").val();
                var subjectGroup = $("#"+id+"_sg").val();
                var rowCount = parseInt(document.getElementById('tr_count').value);
                var count = rowCount + 1;

                if($('#'+id).is(':checked')){
                    //add name and value
                    $('#'+id).attr('name','subjects[sub_'+count+']');
                    $('#'+id).attr('value', id);

                    let teacherOptions = "";
                    teacherList.forEach(teacher => {
                        teacherOptions += '<option value="'+teacher.id+'">'+teacher.first_name+' ('+teacher.single_user.username+')</option>';
                    });

                    viewTeacher = '<select class="form-control subject-teacher-list" name="subject'+id+'[teachers][]" multiple>'+teacherOptions+'</select>';

                    var td = '<tr class="item" id="row_'+id+'"><td><input type="hidden" name="subject'+id+
                    '[is_countable]" value="0"><input type="checkbox" class="is_countable" name="subject'+id+
                    '[is_countable]" value="1" checked></td><td><input id="cs_id_'+id+'" type="hidden" name="subject'+id+
                    '[cs_id]" value="0">'+subjectName+'<input type="hidden" name="subject'+id+'[id]" value="'+id+
                    '"><input type="hidden" id="sort_'+id+'" name="subject'+id+'[sort_order]" value="'+(count)+
                    '"></td> <td><input class="form-control" type="text" name="subject'+id+'[code]" value="'+subjectCode+
                    '" required></td> <td><select class="form-control subject_type" name="subject'+id+
                    '[type]" required><option value="0" selected> N/A </option><option value="1" selected>Compulsory</option><option value="2">Elective</option><option value="3">Optional</option></select></td></td> <td><select class="form-control select-subject-group" name="subject'+
                    id+'[group]" required disabled>'+my_group_list+'</select></td> <td><select class="form-control" name="subject'+id+
                    '[list]" required><option value="0" selected> N/A </option><option value="1">one</option><option value="2">Two</option><option value="3">Three</option></select></td><td>'+
                    viewTeacher+'</td> </tr>';

                    $("#tableHead").removeClass('hide');
                    $("#tableBody").removeClass('hide');
                    $("#tableFoot").removeClass('hide');
                    $(":submit").removeClass("hide");

                    $("#tableBody").append(td);

                    // Select subject group
                    if (subjectGroup) {
                        $('#row_'+id).find('.select-subject-group').val(subjectGroup).change();
                    }

                    // row count
                    document.getElementById('tr_count').value =  count;
                    sbmitButtonStatus();
                }else{
                    // decrease row counter
                    rowCount--;
                    document.getElementById('tr_count').value =  rowCount;

                    // make delete list
                    var subClassId = $("#cs_id_"+id).val();
                    // add the classSubject to the deletelist
                    if(subClassId > 0){
                        var deleteCounter = parseInt(document.getElementById('delete_count').value);
                        var deleteCount = deleteCounter+1;
                        document.getElementById('delete_count').value =  deleteCount;
                        var deleteItem = '<input type="hidden" name="deleteList[id_'+deleteCount+']" value="'+subClassId+'">';
                        $("#tableBody").append(deleteItem);
                    }

                    // remove row
                    $('#row_'+id).remove();
                    $('#'+id).removeAttr('name');
                    $('#'+id).removeAttr('value');

                    // sorting order
                    var mycount = 1;
                    $("tr.item").each(function() {
                        $this = $(this)
                        var rowId = $this.attr("id");
                        var sortIid = rowId.replace("row","sort");
                        $("#"+sortIid).val(mycount);
                        var subId = rowId.replace("row_","");
                        $('#'+subId).removeAttr('name');
                        $('#'+subId).attr('name','subjects[sub_'+mycount+']');
                        mycount++;
                    });
                    sbmitButtonStatus();
                }
            }else{
                $(":checkbox").prop( "checked", false);
                alert('Please select a section');
            }
            $('.subject-teacher-list').select2();
        }

        // submit button diable function
        function sbmitButtonStatus(){
            // disable submit button
            if ($("#tr_count").val() == 0) {
                $(":submit").attr("disabled", true);
                // hide table
                $("#tableHead").addClass('hide');
                $("#tableBody").addClass('hide');
                $("#tableFoot").addClass('hide');
            }
            // active submit button
            if ($("#tr_count").val() != 0) {
                $(":submit").removeAttr("disabled");
            }
        }

        ///////////////////////////////////////////// Manage 4th subject Script ///////////////////////////////////////////////////////////////
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
                    // statements
                },
                success:function(data){
                    op+='<option value="" selected disabled>--- Select {{$batchString}} ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);

                    // set value to the academic section
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                    $('#fourth_subject_list_container').html('');
                },
                error:function(){
                    // statements
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
                    // statements
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }

                    // set value to the academic subject
                    $('.academicSubject').html("");
                    $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                    // set value to the academic subject
                    $('.academicAssessment option:first').prop('selected',true);
                    // semester list reset

                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                    $('#fourth_subject_list_container').html('');
                },
                error:function(){
                    // statements
                }
            });
        });

        // request for section list using batch id
        jQuery(document).on('change','#batch_id',function(){
            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/get/form/from/academics-batch') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // statements
                },

                success:function(data){
                    op+='<option value="" selected disabled>-- Select Section --</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }

                    // set value to the academic batch
                    $('#section_id').html("");
                    $('#section_id').append(op);
                },
                error:function(){
                    // statements
                }
            });
        });

        // request for section list using batch id
        jQuery(document).on('change','.academicSection',function(){
            $('#fourth_subject_list_container').html('');
        });

        // find class sectin additional subject list
        $('#class_section_fourth_subject_list_finder').click(function () {
            // fourth_subject_list_container
            var fourth_subject_list_container = $('#fourth_subject_list_container');

            // checking class section id
            if($('#class_section_id').val()){
                // ajax request
                $.ajax({
                    url: "{{ url('/academics/manage/class/section/fourth/subject/list') }}",
                    type: 'POST',
                    data: $('form#class_section_fourth_subject_assign_form').serialize(),
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Submitting...');
                        // empty fourth_subject_list_container
                        fourth_subject_list_container.html('');
                    },
                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // append response
                        fourth_subject_list_container.append(data)
                    },
                    error:function(){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            }else{

            }

        });

        $(document).on('click', '.subject_form_submit_btn', function () {
            // make disable select fields enable
            $('#class_subject_form').find(':input').prop('disabled', false);
            $('#class_subject_form').submit();
        });

        $(document).on('change', '.subject_type', function () {
            console.log("Yeah");
            var thisTr = $(this).parent().parent();
            var thisGroupId = thisTr.find('.select-subject-group').val();
            var thisTypeId = $(this).val();

            var allTr = $('.select-subject-group');

            allTr.each((index, item) => {
                if ($(item).val() == thisGroupId && $(item).val() != 0) {
                    $(item).parent().parent().find('.subject_type').val(thisTypeId);
                }
            });
        });  
    </script>
@endsection
