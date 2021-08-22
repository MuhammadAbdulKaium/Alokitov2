@extends('fees::layouts.master')
<style>
    .InvoiceAdvanceSearch {
        display: none;
    }
</style>
@php  $batchString="Class" @endphp
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="col-md-12">
        {{--<h4 class="box-title"><i class="fa fa-filter"></i> Search</h4>--}}
        <div class="box box-solid">
            <form id="extra_payment_search">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Payer Name </label>

                                <input class="form-control" id="std_name" name="payer_name" type="text" value="@if(!empty($allInputs)) {{$allInputs->payer_name}} @endif " placeholder="Type Student Name">
                                <input id="std_id" name="std_id" type="hidden" value="@if(!empty($allInputs)) {{$allInputs->payer_id}}  @endif"/>
                                <div class="help-block"></div>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="academic_level">Academic Level</label>
                                <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                    <option value="" selected disabled>--- Select Level ---</option>
                                    @foreach($allAcademicsLevel as $level)
                                        <option value="{{$level->id}}">{{$level->level_name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group batchValidation">
                                <label class="control-label" for="batch">{{$batchString}}</label>
                                <select id="batch" class="form-control academicBatch" name="batch" onchange="">
                                    <option value="" selected disabled>--- Select {{$batchString}} ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group sectionValidation">
                                <label class="control-label" for="section">Section</label>
                                <select id="section" class="form-control academicSection" name="section">
                                    <option value="" selected disabled>--- Select Section ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <a style="float: right" href="/fees/advance/payment/modal" class="btn btn-success" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Advance Payment</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <button type="reset" class="btn btn-default" value="Reset">Reset</button>

                            <button type="submit"  class="btn btn-primary btn-create">Search</button>
                        </div>

                        </div>

                        </div>



                    </div>


            </form>

            {{--//  Start advance search here from--}}

            {{--//  End advance search here from--}}

        </div>
    </div>

        <div class="search_result">
            @include('fees::pages.modal.extrapayment_amount')
        </div>


        </div><!-- /.box-body -->
        @endsection

        @section('page-script')

            {{--alert(100);--}}

                    $(function() { // document ready
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
                                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
                                    for(var i=0;i<data.length;i++){
                                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                                    }
                                    // set value to the academic batch
                                    $('.academicBatch').html("");
                                    $('.academicBatch').append(op);
                                    // set value to the academic secton
                                    $('.academicSection').html("");
                                    $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');

                                    $('.academicSubject').html("");
                                    $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                                    $('#assessment_table_row').html('');
                                    // semester reset
                                    $('.academicSemester option:first').prop('selected', true);
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


                        // request for section list using batch and section id
                        jQuery(document).on('change','.academicSection',function(){
                            // get academic level id
                            var batch_id = $("#batch").val();
                            var section_id = $(this).val();
                            var op="";

                            $.ajax({
                                url: "{{ url('/academics/find/subjcet') }}",
                                type: 'GET',
                                cache: false,
                                data: {'class_id': batch_id, 'section_id':section_id}, //see the $_token
                                datatype: 'application/json',

                                beforeSend: function() {
                                    // statements
                                },

                                success:function(data){
                                    op+='<option value="" selected disabled>--- Select Subject ---</option>';
                                    for(var i=0;i<data.length;i++){
                                        op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                                    }
                                    // set value to the academic batch
                                    $('.academicSubject').html("");
                                    $('.academicSubject').append(op);
                                    $('#assessment_table_row').html('');
                                    // semester reset
                                    $('.academicSemester option:first').prop('selected', true);
                                    //console.log(op);
                                },
                                error:function(){
                                    // statements
                                },
                            });
                        });       // request for section list using batch and section id
                        jQuery(document).on('change','.academicSubject',function(){
                            $('#assessment_table_row').html('');
                            // semester reset
                            $('.academicSemester option:first').prop('selected', true);
                        });

                        // request for section list using batch and section id
                        $('form#std_grade_book_search_form').on('submit', function (e) {
                            // alert('hello');
                            e.preventDefault();

                            if($('#subject').val() && $('#semester').val() ){
                                // ajax request
                                $.ajax({
                                    url: "{{ url('/academics/manage/assessments/gradebook/') }}",
                                    type: 'POST',
                                    cache: false,
                                    data: $('form#std_grade_book_search_form').serialize(),
                                    datatype: 'html',

                                    beforeSend: function() {
                                        // statements
                                        // show waiting dialog
                                        waitingDialog.show('Loading...');
                                    },

                                    success:function(data){
                                        if(data.length>0){
                                            //alert(JSON.stringify(data));
                                            $('#assessment_table_row').html('');
                                            $('#assessment_table_row').append(data);
                                            // show waiting dialog
                                            waitingDialog.hide();
                                        }else{
                                            alert('No data response from the server');
                                        }
                                    },

                                    error:function(data){
                                        // statements
                                        alert(JSON.stringify(data));
                                    }
                                });
                            }else{
                                $('#assessment_table_row').html('');
                                alert('Please double check all inputs are selected.');
                            }

                        });
                    });

            {{--<script>--}}

            $('#search_start_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
            $('#search_end_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});


        // get student name and select auto complete

        $('#std_name').keypress(function() {
            $(this).autocomplete({
                source: loadFromAjax,
                minLength: 1,

                select: function(event, ui) {
                    // Prevent value from being put in the input:
                    this.value = ui.item.label;
                    // Set the next input's value to the "value" of the item.
                    $(this).next("input").val(ui.item.id);
                    event.preventDefault();
                }
            });

            /// load student name form
            function loadFromAjax(request, response) {
                var term = $("#std_name").val();
                $.ajax({
                    url: '/student/find/student',
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
                                id: el.id
                            };
                        }));
                    }
                });
            }
        });

            $(".academicLevel").change(function(){
               $('.batchValidation').addClass('required');
                $('#batch').attr("required", true);
                $('#section').attr("required", true);

                $('.sectionValidation').addClass('required');
            });



            // Search Extra Payment Ajax Request
            $('form#extra_payment_search').on('submit', function (e) {
            e.preventDefault();

            // ajax request
            $.ajax({

            url: '/fees/advance_payment/search',
            type: 'GET',
            cache: false,
            data: $('form#extra_payment_search').serialize(),
            datatype: 'html',

            beforeSend: function() {
            // alert($('form#extra_payment_search').serialize());
            },

            success:function(data){
                $(".search_result").html('');
                $(".search_result").append(data);
            },
            error:function(data){
                alert('error');
                }
            });


            });

            $("#advance_search").click(function () {
        $(".InvoiceAdvanceSearch").css("display","");
        $(".InvoiceAdvanceSearch").slideDown();
    })




            // ajax pagination

            $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            getfeesCollections(url);
            {{--window.history.pushState("", "", url);--}}
            });

            function getfeesCollections(url) {
            $.ajax({
            url : url
            }).done(function (data) {

            $('.search_result').html('');
            $('.search_result').append(data);
            {{--$('#batch_payment_list_row').html('');--}}
            {{--$('#batch_payment_list_row').append(data);--}}
            {{--//                alert("sdfsad");--}}

            }).fail(function () {
            alert('Articles could not be loaded.');
            });
            }




        @endsection

