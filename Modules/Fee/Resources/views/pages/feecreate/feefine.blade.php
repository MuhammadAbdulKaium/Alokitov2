@extends('fee::layouts.feecreate')
<!-- page content -->
@section('page-content')
    <style>
        .req:after {
            content: "*";
            font-size: 14px;
            color: #cc0000;
            padding-left: 4px;
        }
    </style>
    <!-- grading scale -->
    <div class="box-body">
        @if(Session::has('message'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('message') }}</div>
        @endif

        {{--fee head create--}}
        <form id="subheadFine" action="{{URL::to('/fee/subhead/fine/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">

            <div class="form-group col-sm-4">
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
                <div class="help-block"></div>
            </div>


            <div class="form-group col-sm-4">
                <label class="req">Fees Head</label>
                <select class="form-control feeHead" name="fee_head" id="Feessubcategoryfine_feescategoryid">
                    <option value="">Please Select</option>
                    @foreach($feeHeads as $head)
                        <option value="{{$head->id}}">{{$head->name}}</option>
                    @endforeach
                </select>
                <div class="school_val_error" id="Feessubcategoryfine_feescategoryid_em_" style="display:none"></div>
            </div>

            <div class="form-group col-sm-4">
                <label class="req">Fees Sub Head</label>
                <select id="subHead" class="form-control feeSubHead" name="sub_head" >
                    <option value="">--- Select Sub Head ---</option>
                </select>
                <div class="school_val_error" id="Feessubcategoryfine_feessubcategoryid_em_" style="display:none"></div>
            </div>
        </div>
        <div class="row">

            <div class="form-group col-sm-4">
                <label class="req">Type</label>
                <select class="form-control" name="amount_percentage" id="Feessubcategoryfine_amount_percentage">
                    <option value="" selected="selected">Please select</option>
                    <option value="1">Amount</option>
                    <option value="2">Percentage</option>
                </select>
                <div class="school_val_error" id="Feessubcategoryfine_amount_percentage_em_" style="display:none"></div>
            </div>

            <div class="form-group col-sm-4">
                <label class="req" id="amount" style="display:none">Fine Amount</label>
                <label class="req" id="percentage" style="display:none">Fine Percentage</label>
                <label>&nbsp;</label>
                <input class="form-control" name="fine_amount" id="Feessubcategoryfine_fine_amount" type="text" />
                <div class="school_val_error" id="Feessubcategoryfine_fine_amount_em_" style="display:none"></div>
            </div>

            <div class="form-group col-sm-4">
                <label class="req">Fine Type</label>
                <select class="form-control" name="fine_type" id="Feessubcategoryfine_fine_type">
                    <option value="" selected="selected">Please select</option>
                    <option value="1">Fixed</option>
                    <option value="2">Incremental</option>
                </select>
                <div class="school_val_error" id="Feessubcategoryfine_fine_type_em_" style="display:none"></div>
            </div>

            <div class="form-group col-sm-4" style="display: none" id="monthorday">
                <label class="req">Fine Increment in</label>
                <select class="form-control" name="monthy_daily" id="Feessubcategoryfine_monthy_daily">
                    <option value="" selected="selected">Please select</option>
                    <option value="1">Monthly</option>
                    <option value="2">Daily</option>
                </select>
                <div class="school_val_error" id="Feessubcategoryfine_monthy_daily_em_" style="display:none"></div>
            </div>
        </div>
        <div class="row">
            {{--<div class="form-group col-sm-4" style="display: none" id="days">--}}
                {{--<label class="req">Days</label>--}}
                {{--<input class="form-control" name="Feessubcategoryfine[incremental_day]" id="Feessubcategoryfine_incremental_day" type="text" />--}}
                {{--<div class="school_val_error" id="Feessubcategoryfine_incremental_day_em_" style="display:none"></div>--}}
            {{--</div>--}}
            <div class="form-group col-sm-4" style="display: none" id="maximumvalue">
                <label class="req" id="amount1" style="display:none">Maximum Fine Amount</label>
                <label class="req" id="percentage1" style="display:none">Maximum Fine Percentage</label>
                <label>&nbsp;</label>
                <input class="form-control" name="maximum_fine" id="Feessubcategoryfine_maximum_fine" type="text" />
                <div class="school_val_error" id="Feessubcategoryfine_maximum_fine_em_" style="display:none"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div class="form_sep">
                    <input class="btn btn-info" id="button" onclick="return validate()" type="submit" name="yt0" value="Save" /> </div>
            </div>
        </div>
        </form>
    </div>

    {{--end free head --}}

@endsection


@section('page-script')
    <script>
        function validate() {
            var finetype = $("#Feessubcategoryfine_amount_percentage option:selected").val();
            if (finetype == '1') {

            }
            if (finetype == '2') {
                var amount = $('#Feessubcategoryfine_fine_amount').val();
                if (amount > 100) {
                    alert("Fine amount(in percentage) must be less tha or equal to 100");
                    return false;
                }
                var fine_type = $("#Feessubcategoryfine_monthy_daily option:selected").val();
                if (fine_type == '2') {
                    var maximumamount = $('#Feessubcategoryfine_maximum_fine').val();
                    if (maximumamount > 100) {
                        alert("Maximum Fine amount(in percentage) must be less tha or equal to 100");
                        return false;
                    }
                }
            }
            var str = $("#Feessubcategoryfine_fine_type option:selected").val();
            if (str === '1') {

            }
            if (str === '2') {
                var fine_type = $("#Feessubcategoryfine_monthy_daily option:selected").val();
                if (fine_type == "") {
                    alert("Fine Incremental Method cannot be blank");
                    return false;
                }
                if (fine_type == "2") {
                    var days = $("#Feessubcategoryfine_incremental_day").val();
                    var maximumamount = $("#Feessubcategoryfine_maximum_fine").val();
                    if (days == "") {
                        alert("Days cannot be blank");
                        return false;
                    }
                    if (maximumamount == "") {
                        alert("Maximum amount cannot be blank");
                        return false;
                    }
                }
            }
        }
        $("#Feessubcategoryfine_amount_percentage").change(function() {
            var str = $("#Feessubcategoryfine_amount_percentage option:selected").val();
            if (str === '1') {
                $("#amount").show("slow");
                $("#percentage").hide("slow");
                $("#amount1").show("slow");
                $("#percentage1").hide("slow");
            }
            if (str === '2') {
                $("#amount").hide("slow");
                $("#percentage").show("slow");
                $("#amount1").hide("slow");
                $("#percentage1").show("slow");
            }
        });

        $("#Feessubcategoryfine_fine_type").change(function() {
            var str = $("#Feessubcategoryfine_fine_type option:selected").val();
            if (str === '1') {
                $("#monthorday").hide("slow");
                $("#days").hide("slow");
                $("#maximumvalue").hide("slow");

            }
            if (str === '2') {
                $("#monthorday").show("slow");
            }
        });
        $("#Feessubcategoryfine_monthy_daily").change(function() {
            var str = $("#Feessubcategoryfine_monthy_daily option:selected").val();
            if (str === '1') {
                $("#days").hide("slow");
                $("#maximumvalue").hide("slow");

            }
            if (str === '2') {
                $("#days").show("slow");
                $("#maximumvalue").show("slow");
            }
        });

        $("#Feessubcategory_feestype").change(function() {
            if ($("#Feessubcategory_excemption_deduction").val() === '3') {
                if ($("#Feessubcategory_deductionamountpercentage input").val() === '') {
                    alert("Please enter deduction amount");
                }
            }
        });

        $(document).ready(function() {
            var droplist = $('#Feessubcategory_category_gender');
            droplist.change(function() {
                console.log(droplist.val());
                if (droplist.val() === '1') {
                    if ($("#Feessubcategory_excemption_deduction").val() === '2') {
                        $("#Feessubcategory_deductionamountpercentage").hide("slow");
                        $("#Feessubcategory_gender").hide("slow");
                        $("#Feessubcategory_categoryid").show("slow");
                    }
                    if ($("#Feessubcategory_excemption_deduction").val() === '3') {
                        $("#Feessubcategory_gender").hide("slow");
                        $("#Feessubcategory_categoryid").show("slow");
                        $("#Feessubcategory_deductionamountpercentage").show("slow");
                    }
                } else {
                    if ($("#Feessubcategory_excemption_deduction").val() === '2') {
                        $("#Feessubcategory_deductionamountpercentage").hide("slow");
                        $("#Feessubcategory_categoryid").hide("slow");
                        $("#Feessubcategory_gender").show("slow");
                    }
                    if ($("#Feessubcategory_excemption_deduction").val() === '3') {
                        $("#Feessubcategory_categoryid").hide("slow");
                        $("#Feessubcategory_gender").show("slow");
                        $("#Feessubcategory_deductionamountpercentage").show("slow");
                    }
                }
            })
        });


        $('#subheadFine').submit(validate);



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



    </script>

@endsection
