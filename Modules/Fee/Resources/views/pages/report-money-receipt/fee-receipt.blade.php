@extends('fee::layouts.feereport-money-receipt')
<!-- page content -->
@section('page-content')
    <link rel="stylesheet" type="text/css" href="{{URL::to('css/datatable.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('css/datatables/dataTables.bootstrap.css')}}">

    <div class="box-body">
        {{--fee head create--}}
        <form id="transactionDetailsForm" action="{{URL::to('/fee/money-receipt/download')}}"  method="post">
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
            {{--<a href="/fee/money-receipt/download" id="PDF" class="btn btn-primary pull-right download"><i class="fa fa-file-pdf-o"></i>Download</a>--}}

        </form>
    </div>

    <div class="transactionDetails"></div>

@endsection


@section('page-script')
    <script>
        $('.search').click(function(e) {

            $('.transactionDetails').html("");
            e.preventDefault();
            // ajax request
            $.ajax({
                url: '/fee/money-receipt/fee/list',
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

    </script>


@endsection