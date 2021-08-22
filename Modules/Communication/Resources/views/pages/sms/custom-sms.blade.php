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
                <div class="col-md-6">
                    {{--action="/communication/sms/group/teacher/send_sms"--}}
                    <form  method="post" id="custom_sms_form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="form-group">
                            <label for="pwd">Add Mobile No:</label>
                        <div class="hiddenNumber"></div>
                        <div class="addMobileNumber">
                            <div class="input-group control-group after-add-more">
                                {{--<input type="hidden" value="" class="hiddenNumber" name="hidden_phone_number[]">--}}
                                <input class="form-control phone_number" name="phone_number[]" type="number" placeholder="add mobile number ">
                                <div class="input-group-btn">
                                    <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> </button>
                                </div>
                            </div>
                        </div>

                        </div>

                        <div class="form-group">
                            <label for="teacher">Choose a template :</label>
                            <button class="btn btn-success addTemplate" data-toggle="modal" data-target="#ChooseTemplate" type="button"><i class="glyphicon glyphicon-plus"></i> </button>
                        </div>

                        <div class="form-group">
                            <label for="teacher">Message :</label>
                            <textarea class="form-control" name="message" rows="5" id="comment"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>

                <div class="col-md-6">
                       <a href="/communication/add/group/number" class="btn btn-success pull-right" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add Group</a>

                    @if(!empty($groupList) && ($groupList->count()>0) )

                    <table class="table  table-bordered" style="margin-top:50px">
                        <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Group Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($groupList as $group)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$group->group_name}}</td>
                            <td>
                                <a href="/communication/group/view/{{$group->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                <a href="/communication/group/edit/{{$group->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                                <a id="{{$group->id}}" class="btn btn-success btn-xs copyGroupNumber"><i class="fa fa-files-o"></i></a>
                                <a id="{{$group->id}}" class="btn btn-danger btn-xs deletGroup"><i class="fa fa-trash"></i></a>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                        @endif

                </div>
            </div>

        </div>
        </div>


        <!-- Copy Fields-These are the fields which we get through jquery and then add after the above input,-->
        <div class="copy-fields hide">
            <div class="control-group input-group" style="margin-top:10px">
                <input class="form-control phone_number" type="number"  name="phone_number[]" placeholder="add mobile number">
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
                        <h4 class="modal-title">Choose SMS Template </h4>
                    </div>
                    <div class="modal-body">
                        @if(!empty($smsTemplateList) && ($smsTemplateList->count()>0))

                        <table class="table">
                            <thead>
                            <tr>
                                <td width="20%">Template</td>
                                <td>Message</td>
                            </tr>
                            </thead>
                            <tbody id="sms_template_table">

                            @foreach($smsTemplateList as $smsTemplate)
                            <tr class="item">
                                <td><button type="button" class="choose-template  btn btn-primary" />{{$smsTemplate->template_name}}</td>
                                <td class="msg">{{$smsTemplate->message}}</td>
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

        {{--$(document).ready(function(){--}}
            {{--swal("Hello world!");--}}
        {{--});--}}

        {{--<script>--}}
            // group delete ajax request
            $('.deletGroup').click(function(e){
                var tr = $(this).closest('tr'),
                    del_id = $(this).attr('id');

                swal({
                        title: "Are you sure?",
                        text: "You want to delete group",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes!',
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {

                        if (isConfirm) {

                            $.ajax({
                                url: "/communication/group/delete/" + del_id,
                                type: 'GET',
                                cache: false,
                                success: function (result) {
                                    tr.fadeOut(1000, function () {
                                        $(this).remove();
                                    });
                                    swal("Success!", "Group successfully deleted", "success");

                                }
                            });
                        } else {
                            swal("NO", "Your group is safe :)", "error");
                            e.preventDefault();
                        }
                    });
            });






            //    Send Teacher Sms custom_sms_form
        $('form#custom_sms_form').on('submit', function (e) {

                e.preventDefault();
                $.ajax({

                    url: '/communication/sms/group/custom-sms/send_sms',
                    type: 'POST',
                    cache: false,
                    data: $('form#custom_sms_form').serialize(),
                    datatype: 'json/application',

                    beforeSend: function() {
                        {{--alert($('form#custom_sms_form').serialize());--}}
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

        // copy group number ajax request

            $('.copyGroupNumber').click(function (e) {

               e.preventDefault();
               var id= $(this).attr("id");
                var yourArray = [];



        $.ajax({

                    url: '/communication/group/copy/'+id,
                    type: 'GET',
                    cache: false,
                    datatype: 'json/application',

                    beforeSend: function() {
                        waitingDialog.show('copy group number...');
                    },

                    success:function(data){
                        waitingDialog.hide();
                            $.each(data, function(k, v) {
                                $(".hiddenNumber").append('<input type="hidden" value="'+ data[k].mobile_number +'" class="hidden" name="hidden_phone_number[]">');
                        });

                            var mySet = new Set();;
                            $('input[name^="hidden_phone_number"]').each(function(i) {
                            var value = $(this).val();
                            if ( value != '' ) {
                            if(i === length-1) {  //The last one
                            } else {
                                mySet.add(value)
                            }
                            }
                            });
                                alert(mySet);
                              $(".addMobileNumber").html('');
                             for(let item of mySet) {
                                  $(".addMobileNumber").append('<div class="control-group input-group" style="margin-top:10px"><input class="form-control " type="number" name="phone_number[]" value="'+item+'" placeholder="add mobile number"><div class="input-group-btn"><button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> </button></div></div>');

                             }


                          {{--$.each(data, function(k, v) {--}}

                                        {{--$(".addMobileNumber").append('<div class="control-group input-group" style="margin-top:10px"><input class="form-control " type="text" name="phone_number[]" value="' + data[k].mobile_number + '" placeholder="add mobile number"><div class="input-group-btn"><button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> </button></div></div>');--}}
                                {{--}--}}
                        {{--});--}}

                    {{--var mySet = new Set();--}}
                    {{--mySet.add(1);--}}
                    {{--mySet.add(2);--}}
                    {{--mySet.add(1);--}}
                    {{--mySet.add(3);--}}
                    {{--mySet.add(3);--}}
                    {{--mySet.add(3);--}}

                    {{--for(let item of mySet) alert(item);--}}


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

