@extends('communication::layouts.sms-main-master')
@section('styles')
    <link href="{{ asset('css/sweetAlert.css') }}" rel="stylesheet"/>
@endsection
<!-- page content -->
@section('page-content')

    <style>
        /* Example tokeninput style #1: Token vertical list*/
        ul.token-input-list {
            overflow: hidden;
            height: auto !important;
            height: 1%;
            width: 100%;
            border: 1px solid #999;
            cursor: text;
            font-size: 12px;
            font-family: Verdana;
            z-index: 999;
            margin: 0;
            padding: 0;
            background-color: #fff;
            list-style-type: none;
            clear: left;
        }

        ul.token-input-list li {
            list-style-type: none;
        }

        ul.token-input-list li input {
            border: 0;
            width: 350px;
            padding: 3px 8px;
            background-color: white;
            -webkit-appearance: caret;
        }

        li.token-input-token {
            overflow: hidden;
            height: auto !important;
            height: 1%;
            margin: 3px;
            padding: 3px 5px;
            background-color: #d0efa0;
            color: #000;
            font-weight: bold;
            cursor: default;
            display: block;
        }

        li.token-input-token p {
            float: left;
            padding: 0;
            margin: 0;
        }

        li.token-input-token span {
            float: right;
            color: #777;
            cursor: pointer;
        }

        li.token-input-selected-token {
            background-color: #08844e;
            color: #fff;
        }

        li.token-input-selected-token span {
            color: #bbb;
        }

        div.token-input-dropdown {
            position: absolute;
            width: 400px;
            background-color: #fff;
            overflow: hidden;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            cursor: default;
            font-size: 12px;
            font-family: Verdana;
            z-index: 1;
        }

        div.token-input-dropdown p {
            margin: 0;
            padding: 5px;
            font-weight: bold;
            color: #777;
        }

        div.token-input-dropdown ul {
            margin: 0;
            padding: 0;
        }

        div.token-input-dropdown ul li {
            background-color: #fff;
            padding: 3px;
            list-style-type: none;
        }

        div.token-input-dropdown ul li.token-input-dropdown-item {
            background-color: #fafafa;
        }

        div.token-input-dropdown ul li.token-input-dropdown-item2 {
            background-color: #fff;
        }

        div.token-input-dropdown ul li em {
            font-weight: bold;
            font-style: normal;
        }

        div.token-input-dropdown ul li.token-input-selected-dropdown-item {
            background-color: #d0efa0;
        }

    </style>

    <!-- grading scale -->
    <div class="col-md-12">
        <div class="row">
            <span class="">All Parent Count  <span id="pt_count">0</span></span>
            <hr>
            <span class="">Batch Section Student Count <span id="bs_count">0</span></span>
            <hr>
            <div class="col-md-6">
                {{--communication/sms/group/parents/send_sms"--}}
                <form id="parents_sms_form"  method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group">
                        <label for="student"> <a href="javascript:void(0)" id="add_all_parent" style="text-decoration: underline;"> Select All Parent </a> :</label>
                        <input type="text" class="form-control" id="demo-input-local" name="parent" />
                        <input type="hidden" id="parents_count" name="parent_count" value="0">
                    </div>

                    <div class="form-group">
                        <label for="student">Select Class:</label>
                        <select name="batch" id="select_batch" class="form-control academicBatch">
                            @if($batchs->count()>0)
                                <option value="">Select Batch</option>
                                @foreach($batchs as $batch )
                                    <option value="{{$batch->id}}">{{$batch->batch_name}} @if(!empty($batch->get_division())) {{$batch->get_division()->name}} @endif</option>
                                @endforeach
                            @endif
                        </select>
                    </div>



                    <div class="form-group">
                        <label for="section">Section</label>
                        <select id="select_section" class="form-control academicSection" name="section">
                            <option value="" selected disabled>--- Select Section ---</option>
                        </select>
                        <div class="help-block"></div>
                    </div>

                    <div class="form-group">
                        <label for="pwd">Select By Name or Mobile No:</label>
                        <div class="input-group control-group after-add-more">
                            <input type="hidden" id="bsparents_count" name="bsparents_count" value="0">

                            <input type="text" class="form-control" id="demo-input-locals" name="student" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="student">Choose a template :</label>
                        <button class="btn btn-success addTemplate" data-toggle="modal" data-target="#ChooseTemplate" type="button"><i class="glyphicon glyphicon-plus"></i> </button>
                    </div>

                    <div class="form-group">
                        <label for="student">Message :</label>
                        <textarea class="form-control" name="message" rows="5" id="comment"></textarea>
                    </div>

            </div>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
    </div>


    <!-- Copy Fields-These are the fields which we get through jquery and then add after the above input,-->
    <div class="copy-fields hide">
        <div class="control-group input-group" style="margin-top:10px">
            <input type="text" name="addmore[]" class="form-control" placeholder="Enter Mobile Number">
            <div class="input-group-btn">
                <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> </button>
            </div>
        </div>
    </div>


    </div><!-- /.box-body -->


    <!-- Modal -->
    <div id="ChooseTemplate" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="tomas">sdafd</div>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Choose SMS Template</h4>
                </div>
                <div class="modal-body">
                        @if(!empty($parentSmsTemplateList) && ($parentSmsTemplateList->count()>0))

                        <table class="table">
                            <thead>
                            <tr>
                                <td width="20%">Template</td>
                                <td>Message</td>
                            </tr>
                            </thead>
                            <tbody id="sms_template_table">

                            @foreach($parentSmsTemplateList as $parentSms)
                                <tr class="item">
                                    <td><button type="button" class="choose-template  btn btn-primary" />{{$parentSms->template_name}}</td>
                                    <td class="msg">{{$parentSms->message}}</td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    @else
                        <div class="alert-auto-hide alert alert-warning alert-dismissable" style="opacity: 287.258;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="fa fa-info-circle"></i>  No SMS Template Found.
                        </div>


                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>



@endsection

@section('page-script')

    {{--$('#select_batch').change(function() {--}}
    {{--var batch=($(this).val());--}}
    {{--alert(batch);--}}
    {{--});--}}



    //  Send Teacher Sms teacher_sms_form
    $('form#parents_sms_form').on('submit', function (e) {

        e.preventDefault();
        $.ajax({

        url: '/communication/sms/group/parents/send_sms',
        type: 'POST',
        cache: false,
        data: $('form#parents_sms_form').serialize(),
        datatype: 'json/application',

        beforeSend: function() {
        {{--alert($('form#teacher_sms_form').serialize());--}}
        waitingDialog.show('Sending Sms...');
        },

        success:function(data){
        waitingDialog.hide();
            if(data=='success') {
            swal("Success!", "Sms Successfully Sent", "success");
            } else {
            swal("Alert", "Can't sent sms low sms credit", "error");
            }
        },

        error:function(data){
        swal("Error", "Sms Not Sent", "error");
        }
        });

    });





    // send message

    // Add a token programatically
    $("#add_all_parent").click(function () {
     var pt_count = parseInt($('#parents_count').val());

        $.ajax({
            url: '/student/find/parents/all',
            dataType: 'json',
            async: false,

            beforeSend: function () {
            // show waiting dialog
            waitingDialog.show('Loading...');
            },

            success: function (data) {

                // hide waiting dialog
                waitingDialog.hide();

                $.each(data, function( index, value ) {
                    // add value
                    $("#demo-input-local").tokenInput("add",value);
                });
            }
        });
    });


    {{--$("#demo-input-locals").tokenInput();--}}

    /// Token Input and get all class and section Data;
    $("#demo-input-local").tokenInput("/student/find/parents/all",{
        preventDuplicates: true,
        searchDelay: 200,

        onAdd: function (item) {
            var pt_count = parseInt($('#parents_count').val())
            var item_id = item.id;
            var pt_count = parseInt($('#pt_count').text());
            var user_id = '<input type="hidden" id="item_'+item_id+'_user_id" name="user_id[]" value="'+item.user_id+'">';
            var phone = '<input type="hidden" id="item_'+item_id+'_phone" name="phone[]" value="'+item.phone+'">';

            $('#parents_sms_form').append(user_id);
            $('#parents_sms_form').append(phone);
            $('#parents_count').val(pt_count+1);
            var total_student = item.parents_count;

            $('#pt_count').text(total_student)
            {{--alert(total_student);--}}
        },

        onDelete: function (item) {
            var pt_count = parseInt($('#parents_count').val())
            var item_id = item.id;
            var pt_count = parseInt($('#pt_count').text());

            var total_student = item.parents_count;
            $('#pt_count').text(pt_count-1)
            {{--alert(pt_count-total_student);--}}

            // remove item batch section
            $('#item_'+item_id+'_user_id').remove();
            $('#item_'+item_id+'_phone').remove();
            $('#parents_count').val(pt_count-1);
        }
    });




    $(".choose-template ").click(function() {
        var $row = $(this).closest("tr");    // Find the row
        var $text = $row.find(".msg").text(); // Find the text

        // Let's test it out
        {{--alert($text);--}}
$('#comment').val($text)
        $("#ChooseTemplate").modal("hide");
    });



    //here first get the contents of the div with name class copy-fields and add it to after "after-add-more" div class.
    $(".add-more").click(function(){
        var html = $(".copy-fields").html();
        $(".after-add-more").after(html);
    });
    //here it will remove the current value of the remove button which has been pressed
    $("body").on("click",".remove",function(){
        $(this).parents(".control-group").remove();
    });





    // Add a token programatically
    $('#select_section').change(function() {

        var batch=($('#select_batch').val());
        var section= $('#select_section').val();
        var request_url='/student/find/parents/'+batch+'/'+section;
                {{--alert(request_url);--}}
        var bs_count = parseInt($('#bsparents_count').val());
        $.ajax({
            url: request_url,
            dataType: 'json',
            async: false,

            beforeSend: function () {
            // show waiting dialog
            waitingDialog.show('Loading...');
            },

            success: function (data) {
            // hide waiting dialog
            waitingDialog.hide();

              if(data.length==0) {
                swal("Alert", "Parents Not Found", "error");
            } else {
                $.each(data, function( index, value ) {
                    // add value
                    $("#demo-input-locals").tokenInput("add",value);
                });
                 }
            }
        });
    });


    {{--$("#demo-input-locals").tokenInput();--}}

    /// Token Input and get all class and section Data;
    $("#demo-input-locals").tokenInput("/student/find/parents/all",{
        preventDuplicates: true,
        searchDelay: 200,

        onAdd: function (item) {
            var bs_count = parseInt($('#bsparents_count').val())
            var item_id = item.id;
            var bs_count = parseInt($('#bs_count').text());
            var user_id = '<input type="hidden" id="item_'+item_id+'_user_id" name="user_id[]" value="'+item.user_id+'">';
            var phone = '<input type="hidden" id="item_'+item_id+'_phone" name="phone[]" value="'+item.phone+'">';

            $('#parents_sms_form').append(user_id);
            $('#parents_sms_form').append(phone);
            $('#bsparents_count').val(bs_count+1);
            var total_student = item.parents_count;

            $('#bs_count').text(total_student)
            {{--alert(total_student);--}}
        },

        onDelete: function (item) {
            var bs_count = parseInt($('#bsparents_count').val())
            var item_id = item.id;
            var bs_count = parseInt($('#bs_count').text());

            var total_student = item.parents_count;
            $('#bs_count').text(bs_count-1)
            {{--alert(pt_count-total_student);--}}

            // remove item batch section
            $('#item_'+item_id+'_user_id').remove();
            $('#item_'+item_id+'_phone').remove();
            {{--alert(bs_count);--}}
$('#bsparents_count').val(bs_count-1);
        }
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
                // set value to the academic batch
                $('.academicSection').html("");
                $('.academicSection').append(op);

                $('.academicSubject').html("");
                $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                $('#assessment_table_row').html('');
                // semester reset
                $('.academicSemester option:first').prop('selected', true);
            },
            error:function(){
                // statements
            },
        });
    });





@endsection



