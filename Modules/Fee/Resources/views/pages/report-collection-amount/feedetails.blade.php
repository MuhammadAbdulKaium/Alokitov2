@extends('fee::layouts.feereport-colleciton-amount')
<!-- page content -->
@section('page-content')
    <style>
        .userprofile{
            padding: 15px;
            border: 2px solid #efefef;
            border-radius: 10px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{URL::to('css/datatable.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('css/datatables/dataTables.bootstrap.css')}}">

    <div class="box-body">
        {{--fee head create--}}
        <form id="searchFeesDetailsForm" action="{{URL::to('/fee/student/fee/details/download')}}"  method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="year">Select Year</label>
                        <select class="form-control" id="year" name="year_id">
                            <option value="">Select Year</option>
                            @foreach($academicYearList as $year)
                                <option value="{{$year->id}}">{{$year->year_name}}</option>
                            @endforeach
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Select Class</label>
                        <select name="class_id" class="form-control academicBatch" id="feehead">
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
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="section">Section</label>
                        <select id="section" class="form-control academicSection" name="section">
                            <option value="" selected>--- Select Section ---</option>

                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="req">Fees Head</label>
                        <select class="form-control feeHead" name="fee_head" id="Feessubcategoryfine_feescategoryid">
                            <option value="">Please Select</option>
                            @foreach($feeHeads as $head)
                                <option value="{{$head->id}}">{{$head->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="req">Fees Sub Head</label>
                            <select id="subHead" class="form-control feeSubHead" name="sub_head" >
                                <option value="">--- Select Sub Head ---</option>
                            </select>
                            <div class="school_val_error" id="Feessubcategoryfine_feessubcategoryid_em_" style="display:none"></div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success search">Search</button>
            <input type="submit" name="pdf" value="Download" formtarget="_blank" class="btn btn-primary">
        </form>
    </div>

    <div class="studentFeeDetails"></div>

@endsection


@section('page-script')
    {{--<script>--}}

        $('.search').click(function(e) {
            e.preventDefault();
            // ajax request
            $.ajax({
                url: '/fee/student/fee/details',
                type: 'POST',
                cache: false,
                data: $('form#searchFeesDetailsForm').serialize(),
                datatype: 'json/application',

                beforeSend: function() {
                    {{--alert($('form#Partial_allowForm').serialize());--}}
                },

                success:function(data){
                    $('.studentFeeDetails').html("");
                    $('.studentFeeDetails').append(data);
                },

                error:function(data){
                    {{--alert(JSON.stringify(data));--}}
                }
            });

        });

        // get subhead by head id
        $(document).on('change','.feeHead',function(){
            // console.log("hmm its change");

            // get academic level id
            var head_id = $(this).val();
            var class_id = $('.academicBatch').val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/fee/head/class/subheadlist/') }}",
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

        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            console.log("hmm its change");

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
        });



 @endsection