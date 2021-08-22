
@extends('academics::manage-assessments.index')

<!-- page content -->
@section('page-content')

    {{--teacher search area--}}
    <div class="box box-solid">



        <ul class="nav nav-tabs">
            <li class="my-tab active"><a data-toggle="tab" href="#ct_report">CT Report</a></li>
            <li class="my-tab "><a data-toggle="tab" href="#semester_report">Semester Report</a></li>
            <li class="my-tab"><a data-toggle="tab" href="#final_report">Final Report</a></li>
        </ul>

        <br/>
        <div class="tab-content">
            {{--manage all subject tab --}}
            <div id="ct_report" class="tab-pane fade in active">
                <div class="row">
                    <form target="_blank" method="post" action="{{url('/academics/manage/assessments/report-card/download/single')}}" id="std_class_test_report_card_search_form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    <label class="control-label" for="std_name_ct">Student Name / Username</label>
                                    <div class="form-group">
                                        <input class="form-control" id="std_name_ct" type="text" placeholder="Type Student Name Or Username">
                                        <input id="std_id_ct" name="std_id" type="hidden" value="" />
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="report_type" value="subject_detail">
                                <input type="hidden" name="report_format" value="4">
                                <div class="col-sm-4">

                                    <div class="form-group">
                                        <label for="sel1">Select Category:</label>
                                        <select name="category_id" class="form-control" id="sel1">
                                            <option value="">Select Category</option>
                                        @foreach($assementCategory as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer pull-right">

                            <button type="submit" class="download_std_report_card btn btn-success" ><i class="fa fa-plus-square" aria-hidden="true"></i> Download</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>


            {{--manage all subject tab --}}
            <div id="semester_report" class="tab-pane fade in ">
                <div class="row">
                    <form id="std_semester_report_card_search_form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    <label class="control-label" for="std_name">Student Name / Username</label>
                                    <div class="form-group">
                                        <input class="form-control" id="std_name" type="text" placeholder="Type Student Name Or Username">
                                        <input id="std_id" name="std_id" type="hidden" value="" />
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" for="">Report Type</label><br/>
                                    <label class="radio-inline"><input type="radio" name="report_format" value="0" required> Default </label>
                                    <label class="radio-inline"><input type="radio" name="report_format" value="1"> W/A (Detail) </label>
                                    <label class="radio-inline"><input type="radio" name="report_format" value="2"> W/A (Summary) </label>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer pull-right">

                            <a class="download_std_report_card btn btn-success" href=""  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                                <i class="fa fa-plus-square" aria-hidden="true"></i> Download
                            </a>
                        </div>
                    </form>
                </div>
            </div>





            {{--manage 4th subject tab--}}
            <div id="final_report" class="tab-pane fade in">
                <div class="row">
                    <form id="std_final_report_card_search_form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="request_type" value="view">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="control-label" for="std_name_final">Student Name / Username</label>
                                    <div class="form-group">
                                        <input class="form-control std_name" id="std_name_final" type="text" placeholder="Type Student Name Or Username">
                                        <input id="std_id_final" name="std_id" type="hidden" value="" />
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button id="view_report_card" class="btn btn-success pull-right"><i class="fa fa-click"></i> View Report Card</button>
                            <button type="reset" class="btn btn-default pull-left">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--teacher list container row--}}
    <div id="std_report_card_row">
        {{--teacer list will be here--}}
    </div>

@endsection

@section('page-script')
    <script type="text/javascript">
        $(function() {

            $('.my-tab').click(function () {
                // empty report card container
                $('#std_report_card_row').html('');
            });


            // document ready Student CT Section
            $('#std_name_ct').keypress(function(){
                // clear std_id
                $('#std_id_ct').val('');
                // empty std_report_card_row
                $('#std_report_card_row').html('');


                // autoComplete
                $(this).autocomplete({
                    source: loadFromAjax,
                    minLength: 1,

                    select: function (event, ui) {
                        // Prevent value from being put in the input:
                        this.value = ui.item.label;
                        // Set the next input's value to the "value" of the item.
                        $(this).next("input").val(ui.item.id);
                        event.preventDefault();
                    }
                });

                function loadFromAjax(request, response) {
                    // find search term
                    var term = $('#std_name_ct').val();
                    // checking
                    if((term.trim() != '') && (term.length>2)){
                        $.ajax({
                            url: '/student/find/student',
                            dataType: 'json',
                            data:{'term': term},

                            success: function(data) {
                                // you can format data here if necessary
                                response($.map(data, function (el) {
                                    return {
                                        label: el.name,
                                        value: el.name,
                                        id:el.id
                                    };
                                }));
                            }
                        });
                    }
                }
            });



            // document ready
            $('#std_name').keypress(function(){
                // clear std_id
                $('#std_id').val('');
                // empty std_report_card_row
                $('#std_report_card_row').html('');


                // autoComplete
                $(this).autocomplete({
                    source: loadFromAjax,
                    minLength: 1,

                    select: function (event, ui) {
                        // Prevent value from being put in the input:
                        this.value = ui.item.label;
                        // Set the next input's value to the "value" of the item.
                        $(this).next("input").val(ui.item.id);
                        event.preventDefault();

                        var stdId=  $('#std_id').val();
//                        alert(stdId);
                        var downlaolLink='/academics/manage/assessments/report-card/download/'+stdId;
                        $("a.download_std_report_card").attr("href", downlaolLink);
                    }
                });

                function loadFromAjax(request, response) {
                    // find search term
                    var term = $('#std_name').val();
                    // checking
                    if((term.trim() != '') && (term.length>2)){
                        $.ajax({
                            url: '/student/find/student',
                            dataType: 'json',
                            data:{'term': term},

                            success: function(data) {
                                // you can format data here if necessary
                                response($.map(data, function (el) {
                                    return {
                                        label: el.name,
                                        value: el.name,
                                        id:el.id
                                    };
                                }));
                            }
                        });
                    }
                }
            });

            // document ready
            $('#std_name_final').keypress(function(){
                // clear std_id
                $('#std_id_final').val('');
                // empty std_report_card_row
                $('#std_report_card_row').html('');


                // autoComplete
                $(this).autocomplete({
                    source: loadFromAjax,
                    minLength: 1,

                    select: function (event, ui) {
                        // Prevent value from being put in the input:
                        this.value = ui.item.label;
                        // Set the next input's value to the "value" of the item.
                        $(this).next("input").val(ui.item.id);
                        event.preventDefault();
                    }
                });

                function loadFromAjax(request, response) {
                    // find search term
                    var term = $('#std_name_final').val();
                    // checking
                    if((term.trim() != '') && (term.length>2)){
                        $.ajax({
                            url: '/student/find/student',
                            dataType: 'json',
                            data:{'term': term},

                            success: function(data) {
                                // you can format data here if necessary
                                response($.map(data, function (el) {
                                    return {
                                        label: el.name,
                                        value: el.name,
                                        id:el.id
                                    };
                                }));
                            }
                        });
                    }
                }
            });

            // class test report card here



            // request for section list using batch and section id
            $('form#std_semester_report_card_search_form').on('submit', function (e) {
                // alert('hello');
                e.preventDefault();

                if($('#std_id').val()){
                    // ajax request
                    $.ajax({
                        url: '/academics/manage/assessments/report-card/show/',
                        type: 'POST',
                        cache: false,
                        data: $('form#std_semester_report_card_search_form').serialize(),
                        datatype: 'html',
                        // datatype: 'json/application',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // show waiting dialog
                            waitingDialog.hide();
                            // checking
                            if(data.length>0){
                                $('#std_report_card_row').html('');
                                $('#std_report_card_row').append(data);
                            }else{
                                // sweet alert
                                swal("Warning", 'No data response from the server', "warning");
                            }
                        },

                        error:function(data){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // sweet alert
                            swal("Error", 'Unable to load data form server', "error");
                            // empty report card container
                            $('#std_report_card_row').html('');
                        }
                    });
                }else{
                    // clear report card row
                    $('#std_report_card_row').html('');
                    // clear student name
                    $('#std_name').val('');
                    // sweet alert
                    swal("Warning", 'Please select a student', "warning");
                }

            });


            // request for section list using batch and section id
            $('form#std_final_report_card_search_form').on('submit', function (e) {
                // alert('hello');
                e.preventDefault();

                if($('#std_id_final').val()){
                    // ajax request
                    $.ajax({
                        url: '/academics/manage/assessments/final/report-card/single/',
                        type: 'POST',
                        cache: false,
                        data: $('form#std_final_report_card_search_form').serialize(),
                        datatype: 'html',
                        // datatype: 'json/application',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // show waiting dialog
                            waitingDialog.hide();
                            // checking
                            if(data.length>0){
                                $('#std_report_card_row').html('');
                                $('#std_report_card_row').append(data);
                            }else{
                                // sweet alert
                                swal("Warning", 'No data response from the server', "warning");
                            }
                        },

                        error:function(data){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // sweet alert
                            swal("Error", 'Unable to load data form server', "error");
                            // empty report card container
                            $('#std_report_card_row').html('');
                        }
                    });
                }else{
                    // clear report card row
                    $('#std_report_card_row').html('');
                    // clear student name
                    $('#std_name_final').val('');
                    // sweet alert
                    swal("Warning", 'Please select a student', "warning");
                }
            });
        });
    </script>
@endsection