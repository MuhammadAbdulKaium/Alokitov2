@extends('fee::layouts.feereport-money-receipt')
<!-- page content -->
@section('page-content')
    <link rel="stylesheet" type="text/css" href="{{URL::to('css/datatable.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('css/datatables/dataTables.bootstrap.css')}}">

    <div class="box-body">
        {{--fee head create--}}
        <form id="transactionDetailsForm"  action="{{URL::to('/fee/money-receipt/late-fine/download')}}"  method="post">
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
                        <label class="req">Start Date</label>
                        <input type="date" name="start_date">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="req">End Date</label>
                        <input type="date" name="end_date">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success search">Search</button>
            <input type="submit" name="pdf" value="Download" formtarget="_blank" class="btn btn-primary">

        </form>
    </div>

    <div class="transactionDetails"></div>

@endsection


@section('page-script')
    <script>
        $('.search').click(function(e) {
            e.preventDefault();
            $('.transactionDetails').html("");
            // ajax request
            $.ajax({
                url: '/fee/money-receipt/late-fine/list',
                type: 'POST',
                cache: false,
                data: $('form#transactionDetailsForm').serialize(),
                datatype: 'json/application',

                beforeSend: function() {
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    waitingDialog.hide();
                    $('.transactionDetails').append(data);
                },

                error:function(data){
                    {{--alert(JSON.stringify(data));--}}
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

    </script>


@endsection