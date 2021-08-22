@extends('fee::layouts.feesassign')
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box-body">

        @if(Session::has('message'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('message') }}</div>
        @endif
        <style>
            .forselectSection {
                display: none;
            }
            .forselectSudent {
                display: none;
            }
        </style>

        {{--fee head create--}}
        <form  action="{{URL::to('/fee/assign/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="amount" value="" id="feeAmount">
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">

                        <label class="control-label" for="class">Class</label>
                        <select name="class_id" class="form-control academicBatch" id="class_id">
                            <option value="">Select Class</option>
                            @foreach($batchs as $batch)
                                @if($batch->get_division())
                                    <option value="{{$batch->id}}">{{$batch->batch_name.' '.$batch->get_division()->name}}</option>
                                @else
                                    <option value="{{$batch->id}}">{{$batch->batch_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="req">Fees Head</label>
                        <select class="form-control feeHead" name="fee_head" id="Feessubcategoryfine_feescategoryid">
                            <option value="">Please Select</option>
                            @foreach($feeHeads as $head)
                                <option value="{{$head->id}}">{{$head->name}}</option>
                            @endforeach
                        </select>
                        <div class="school_val_error" id="Feehead" style="display:none"></div>
                    </div>

                    <div class="form-group">
                        <label class="req">Fees Sub Head</label>
                        <select id="subHead" class="form-control feeSubHead" name="sub_head" >
                            <option value="">--- Select Sub Head ---</option>
                        </select>
                        <div class="school_val_error" id="Feessubcategoryfine_feessubcategoryid_em_" style="display:none"></div>
                    </div>

                    <div class="form-group ">
                    <p>Fee Amount: <span class="subheadClassAmount"></span> </p>
                    </div>

                <div class="form-group" id="amount" style="display:none">
                    <div class="alert alert-success">
                        <b>  <label id="tick"></label></b>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Feesallocation_feesfor">Fee For</label>
                    <select class="form-control" data-required="true" name="fee_allocaitonfor" id="FeesallocationFor">
                        <option value="prompt">Please Select</option>
                        <option value="1">Full Class</option>
                        <option value="2">Selected Section</option>
                        <option value="3">Student in a Class</option>
                    </select>
                    <div class="school_val_error" id="Feesallocation_feesfor_em_" style="display:none"></div>
                </div>

                    <div class="form-group forselectSection">
                        <label for="for_section">Select Section</label>
                        <select id="section" class="form-control academicSection" name="section">
                            <option value="" selected>--- Select Section ---</option>

                        </select>
                    </div>

                    {{--<div class="form-group forselectSudent">--}}
                        {{--<label class="control-label" for="feehead">Student ID</label>--}}
                        {{--<input class="form-control" id="std_name" name="payer_name" type="text" value="@if(!empty($allInputs)) {{$allInputs->payer_name}} @endif " placeholder="Type Student Name">--}}
                        {{--<input id="std_id" name="std_id" type="hidden" value="@if(!empty($allInputs)) {{$allInputs->payer_id}}  @endif"/>--}}
                        {{--<div class="help-block"></div>--}}
                    {{--</div>--}}
                    <div class="form-group forselectSudent">
                        <label class="control-label" for="forselectSudent">Select Student</label>
                        <select class="form-control studentlist" name="student[]" id="studentSelect">
                        </select>
                    </div>

            <button type="submit" class="btn btn-success">Submit</button>
            <button type="reset" class="btn btn-primary" value="Reset">Reset</button>
            </div>
            </div>
        </form>
    </div>
    {{--end free head --}}

    {{--fee head list--}}
    {{--<table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">--}}
    {{--<thead>--}}
    {{--<tr>--}}
    {{--<th># NO</th>--}}
    {{--<th>Class</th>--}}
    {{--<th>Period</th>--}}
    {{--<th>Amount</th>--}}
    {{--<th>Action</th>--}}
    {{--</tr>--}}

    {{--</thead>--}}
    {{--<tbody>--}}

    {{--<tr class="gradeX">--}}
    {{--<td>1</td>--}}
    {{--<td>One</td>--}}
    {{--<td>1st Period</td>--}}
    {{--<td>20</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}


    {{--<tr class="gradeX">--}}
    {{--<td>2</td>--}}
    {{--<td>Two</td>--}}
    {{--<td>1st Period</td>--}}
    {{--<td>30</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}


    {{--<tr class="gradeX">--}}
    {{--<td>3</td>--}}
    {{--<td>Four</td>--}}
    {{--<td>2nd Period</td>--}}
    {{--<td>40</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}

    {{--<tr class="gradeX">--}}
    {{--<td>4</td>--}}
    {{--<td>Five</td>--}}
    {{--<td>1st Period</td>--}}
    {{--<td>50</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}

    {{--</tbody>--}}
    {{--</table>--}}



@endsection




@section('page-script')
    <script>


        $('#studentSelect').select2({
            multiple: true,
            placeholder: 'student name'
        });



        // get subhead by head id
        $(document).on('change','.academicBatch',function(){
            // console.log("hmm its change");

            // get academic level id
            var class_id = $(this).val();
            var op="";

            $.ajax({
                url: "{{ url('/fee/class/wise/student/list/') }}",
                type: 'GET',
                cache: false,
                data: {'class_id': class_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {

                },

                success:function(data){
                    console.log('success');
                    op+='';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].std_id+'">'+data[i].first_name+data[i].middle_name+data[i].last_name+'</option>';
                    }

//                    alert(op);
                    // set value to the academic batch
                    $('.studentlist').html("");
                    $('.studentlist').append(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                }
            });
        });





//        $('#subheadFine').submit(validate);

        // get subhead by head id
        $(document).on('change','.feeHead',function(){
            // console.log("hmm its change");

            // get academic level id
            var head_id = $(this).val();
            var class_id = $('#class_id').val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/fee/feesubhead/list/') }}",
                type: 'GET',
                cache: false,
                data: {'head_id': head_id,'class_id':class_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {

                },

                success:function(data){
                    console.log('success');
                    op+='<option value="" selected>--- Select Sub Head ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].sub_head+'</option>';
                    }

//                    alert(op);
                    // set value to the academic batch
                    $('.feeSubHead').html("");
                    $('.feeSubHead').append(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                }
            });
        });


        $(document).on('change','.feeSubHead',function(){
            // console.log("hmm its change");

            // get sub id  id
            var subhead_id = $(this).val();
            var div = $(this).parent();
            var op="";
//            alert(subhead_id);

            $.ajax({
                url: "{{ url('/fee/subhead/class/amount/') }}",
                type: 'GET',
                cache: false,
                data: {'id': subhead_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {

                },

                success:function(data){
                    // set value to the academic batch
                    $('.subheadClassAmount').html("");
                    $('.subheadClassAmount').append(data);

                    $('#feeAmount').val("");
                    $('#feeAmount').val(data);
                },

                error:function(){
                    alert(JSON.stringify(data));
                }
            });
        });


$(document).on('change','#FeesallocationFor',function() {
    var selectedFor = $(this).val();
    if(selectedFor==2){
        $(".forselectSudent").hide();
        $(".forselectSection").show();

        // get academic level id
        var batch_id = $('.academicBatch').val();
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
//                $('#std_list_container_row').html('');
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
            },
        });

    }else if(selectedFor==3){
        $(".forselectSection").hide();
        $(".forselectSudent").show();
    } else {
        $(".forselectSection").hide();
        $(".forselectSudent").hide();
    }
});


// get student name and select auto complete

//$('#std_name').keypress(function() {
//    $(this).autocomplete({
//        source: loadFromAjax,
//        minLength: 1,
//
//        select: function(event, ui) {
//            // Prevent value from being put in the input:
//            this.value = ui.item.label;
//            // Set the next input's value to the "value" of the item.
//            $(this).next("input").val(ui.item.id);
//            event.preventDefault();
//        }
//    });
//
//    /// load student name form
//    function loadFromAjax(request, response) {
//        var term = $("#std_name").val();
//        $.ajax({
//            url: '/student/find/student',
//            dataType: 'json',
//            data: {
//                'term': term
//            },
//            success: function(data) {
//                // you can format data here if necessary
//                response($.map(data, function(el) {
//                    return {
//                        label: el.name,
//                        value: el.name,
//                        id: el.id
//                    };
//                }));
//            }
//        });
//    }
//});
//


    </script>

@endsection