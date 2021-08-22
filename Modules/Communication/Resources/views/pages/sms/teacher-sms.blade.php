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
            height: 30px;
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
            <span class="">Teacher <span id="tp_count">0</span></span>
            <div class="col-md-6">
                {{--action="/communication/sms/group/teacher/send_sms"--}}
                <form  method="post" id="teacher_sms_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="teacher_count" name="teacher_count" value="0">
                    <input type="hidden" id="custom_teacher_count" name="custom_teacher_count" value="0">

                    <div class="form-group">
                        <label for="teacher"> <a href="javascript:void(0)" id="add_all_teacher" style="text-decoration: underline;"> Select All Teacher </a>:</label>
                        <input type="text" class="form-control" id="demo-input-local" name="teacher_selection" />
                    </div>

                    <div class="form-group">
                        <label for="teacher">Choose a template :</label>
                        <button class="btn btn-success addTemplate" data-toggle="modal" data-target="#ChooseTemplate" type="button"><i class="glyphicon glyphicon-plus"></i> </button>
                    </div>

                    <div class="form-group">
                        <label for="teacher">Message :</label>
                        <textarea class="form-control" name="message" rows="5" id="comment"></textarea>
                    </div>
            </div>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
        </form>
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
                    <h4 class="modal-title">Choose SMS Template </h4>
                </div>
                <div class="modal-body">
                    @if(!empty($teacherSmsTemplateList) && ($teacherSmsTemplateList->count()>0))

                    <table class="table">
                        <thead>
                        <tr>
                            <td width="20%">Template</td>
                            <td>Message</td>
                        </tr>
                        </thead>
                        <tbody id="sms_template_table">

                        @foreach($teacherSmsTemplateList as $teacherSms)
                        <tr class="item">
                            <td><button type="button" class="choose-template  btn btn-primary" />{{$teacherSms->template_name}}</td>
                            <td class="msg">{{$teacherSms->message}}</td>
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


<script>

//        $(document).ready(function () {
//            $('.teacher_name').onkeyup(function () {
//                alert(1);
//            });
//        });
//


    function demodata(auto) {
        autocomplete_teacher(auto);
    }



    // auto complete fucntion

    function autocomplete_teacher(auto){

        //alert(auto.value);

        $(auto).autocomplete({
            source: loadFromAjax,
            minLength: 1,

            select: function(event, ui) {
                // Prevent value from being put in the input:
                this.value = ui.item.label;
                // Set the next input's value to the "value" of the item.
                $(this).next("input").val(ui.item.id);
                $(auto).before('<input id="teacher_id" name="user_id[]"  type="hidden" value="'+ui.item.id+'" /> <input id="phone" name="phone[]" type="hidden" value="'+ui.item.phone+'" />');
               var custom_teacher_count = parseInt($('#custom_teacher_count').val());
                $('#custom_teacher_count').val(custom_teacher_count+1);
                event.preventDefault();
            }
        });

        /// load Teacher and and class ajax form
        function loadFromAjax(request, response) {
            var term = $(".teacher_name").val();
            $.ajax({
                url: '/employee/find/teacher',
                dataType: 'json',
                data: {
                    'term': term
                },
                success: function(data) {
                    // you can format data here if necessary
                    response($.map(data, function(el) {
                        return {
                            label: el.name,
                            value: el.name,
                            id: el.user_id,
                            phone: el.phone
                        };
                    }));
                },

            });
        }
    }





</script>

@endsection

@section('page-script')

    {{--$(document).ready(function(){--}}
        {{--swal("Hello world!");--}}
    {{--});--}}

    {{--<script>--}}
//    Send Teacher Sms teacher_sms_form
    $('form#teacher_sms_form').on('submit', function (e) {


            e.preventDefault();
            $.ajax({

                url: '/communication/sms/group/teacher/send_sms',
                type: 'POST',
                cache: false,
                data: $('form#teacher_sms_form').serialize(),
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

    {{--$("#submitBtn").click(function () {--}}
    {{--var custom_teacher_count = parseInt($('#custom_teacher_count').val());--}}
    {{--if(custom_teacher_count !=0){--}}
    {{--}--}}
        {{--$('#grade-upload-form')--}}
            {{--.append('<input type="text" name="academic_level" value="'+$('#academic_level').val()+'"/>')--}}
        {{--}).submit();--}}
    {{--});--}}


    // Add a token programatically
    $("#add_all_teacher").one("click",function () {
    var cs_counter = parseInt($('#teacher_count').val());
    $.ajax({
        url: '/employee/find/teacher',
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
         waitingDialog.hide();
        }
    });
    });




    /// Token Input and get all class and section Data;
    $("#demo-input-local").tokenInput("/employee/find/teacher",{
    preventDuplicates: true,
    searchDelay: 200,

    onAdd: function (item) {
    var cs_counter = parseInt($('#teacher_count').val())
    var item_id = item.id;
    var tp_count = parseInt($('#tp_count').text());
    var user_id = '<input type="hidden" id="item_'+item_id+'_user_id" name="user_id[]" value="'+item.user_id+'">';
    var phone = '<input type="hidden" id="item_'+item_id+'_phone" name="phone[]" value="'+item.phone+'">';

    $('#teacher_sms_form').append(user_id);
    $('#teacher_sms_form').append(phone);
    $('#teacher_count').val(cs_counter+1);
    var total_teacher = item.teacher_count;

    $('#tp_count').text(total_teacher)
    {{--alert(total_teacher);--}}
    },

    onDelete: function (item) {
    var cs_counter = parseInt($('#teacher_count').val())
    var item_id = item.id;
    var tp_count = parseInt($('#tp_count').text());

    var total_teacher = item.teacher_count;
    $('#tp_count').text(tp_count-1)
    {{--alert(tp_count-total_teacher);--}}

    // remove item batch section
    $('#item_'+item_id+'_user_id').remove();
    $('#item_'+item_id+'_phone').remove();
    $('#teacher_count').val(cs_counter-1);
    }
    });





    // sms template choosen design

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





@endsection

