<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 6/6/17
 * Time: 11:49 AM
 */
?>
@extends('layouts.master')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{URL::asset('css/datepicker3.css')}}">
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> Accounting Report
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{url('/home')}}"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('/finance')}}"><i class="fa fa-home"></i>Finance</a></li>
                <li><a href="{{url('/accounting')}}">Accounting</a></li>
                <li><a href="{{url('/accounting/accreport')}}">Reports</a></li>
                <li><a href="#"> Ledger Book </a></li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Ledger Book</h3>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Ledgers</label>
                                            <input list="myList" type="text" class="form-control" id="ledgersList">
                                        </div>
                                    </div>
                                    <datalist id="myList">
                                        @foreach($accHeads as $data)
                                            <option>{{$data->chart_code}} ----- {{$data->chart_name}}</option>
                                        @endforeach
                                    </datalist>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input readonly type="text" class="form-control pull-right" id="fromDate">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input readonly type="text" class="form-control pull-right" id="toDate">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pull-right"><a class="btn btn-info btn-sm" onclick="searchDataLoad()">Search</a></div>
                                    <div class="margin-r-5 pull-right"><a class="btn btn-info btn-sm" onclick="clearFormData()">Clear</a></div>
                                    <div class="margin-r-5 pull-right"><a class="btn btn-info btn-sm" onclick="excelDownload()">Excel Download</a></div>
                                </div>
                                <div class="col-md-4"></div>
                            </div>

                            <div class="box-body table-responsive">
                                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                                    <div id="w1" class="grid-view">
                                        {{--<table id="myTable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">#</th>
                                                <th style="text-align: center"><a  data-sort="sub_master_name">Date</a></th>
                                                <th style="text-align: center"><a  data-sort="sub_master_name">Serial</a></th>
                                                <th style="text-align: center"><a  data-sort="sub_master_alias">Details</a></th>
                                                <th style="text-align: center"><a>Dr</a></th>
                                                <th style="text-align: center"><a>Cr</a></th>
                                                <th style="text-align: center">Balance</th>
                                                <th style="text-align: center"><a></a></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i = 1;
                                                $closingBalance = 0;
                                            @endphp
                                            @foreach($accVoucherEntrys as $accVoucherEntry)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ date('d-m-Y',strtotime($accVoucherEntry->tran_date)) }}</td>
                                                    <td>{{ $accVoucherEntry->tran_serial }}</td>
                                                    <td>{{ $accVoucherEntry->tran_details }}</td>
                                                    <td style="text-align: right" >{{ $accVoucherEntry->tran_amt_dr }}</td>
                                                    <td style="text-align: right" >{{ $accVoucherEntry->tran_amt_cr }}</td>
                                                    <td style="text-align: right" >
                                                        @php
                                                        $balence = ($accVoucherEntry->tran_amt_dr - $accVoucherEntry->tran_amt_cr);
                                                        echo $closingBalance += $balence;
                                                        @endphp
                                                    </td>
                                                    <td style="text-align: center" ><a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" onclick="modalLoad({{$accVoucherEntry->tran_serial}})"><i class="fa fa-eye"></i>View</a></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th style="text-align: center">#</th>
                                                    <th style="text-align: center"><a  data-sort="sub_master_name">Date</a></th>
                                                    <th style="text-align: center"><a  data-sort="sub_master_name">Serial</a></th>
                                                    <th style="text-align: center"><a  data-sort="sub_master_alias">Details</a></th>
                                                    <th style="text-align: center"><a>Dr</a></th>
                                                    <th style="text-align: center"><a>Cr</a></th>
                                                    <th style="text-align: center">Balance</th>
                                                    <th style="text-align: center"><a></a></th>
                                                </tr>
                                            </tfoot>
                                        </table>--}}
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        {{--data will sit here--}}
    </div>

    <script type = "text/javascript">
        function modalLoad(rowId) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&id='+rowId;
            $.ajax({
                url: "{{ url('accounting/accvoucherentry/approve')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#myModal').html(data);
                }
            });
        }

        function dateChecking() {
            //checking for the voucher entry fromDate are in to the financial year
            var  start = stringToDate('<?php echo $f_date['start']?>', 'yyyy-mm-dd', '-');
            var  end = stringToDate('<?php echo $f_date['end']?>', 'yyyy-mm-dd', '-');
            var  value = stringToDate($('#fromDate').val(), 'mm/dd/yyyy', '/');
            var diff1 = (value - start) / (8.64e7);
            var diff2 = (end - value) / (8.64e7);

            var chk = 0;
            if(diff1 < 0){alert('date is Earlier then financial start date'); chk = 1;}
            if(diff2 < 0){alert('date is After then financial end date'); chk = 1;}

            //checking for the voucher entry toDate are in to the financial year
            var  start1 = stringToDate('<?php echo $f_date['start']?>', 'yyyy-mm-dd', '-');
            var  end1 = stringToDate('<?php echo $f_date['end']?>', 'yyyy-mm-dd', '-');
            var  value1 = stringToDate($('#toDate').val(), 'mm/dd/yyyy', '/');
            var  diff3 = (value1 - start1) / (8.64e7);
            var  diff4 = (end1 - value1) / (8.64e7);

            if(diff3 < 0){alert('date is Earlier then financial start date'); chk = 1;}
            if(diff4 < 0){alert('date is After then financial end date'); chk = 1;}
            return chk;
        }

        function searchDataLoad() {
            var chk = dateChecking();
            var token = "{{ csrf_token() }}";
            var ledgersList = $('#ledgersList').val();
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            var dataSet = '_token='+token+'&fromDate='+fromDate+'&toDate='+toDate+'&ledgersList='+ledgersList;

            if(ledgersList == ''){alert('Select a Ledger Name'); chk = 1;}
            if(chk == 0){
                $.ajax({
                    url: "{{ url('accounting/accreport/accledgerbook') }}",
                    type: 'post',
                    data: dataSet,
                    beforeSend: function () {
                    }, success: function (data) {
                        $('#w1').html(data);
                    }
                });
            }
        }
        function clearFormData() {
            $('#fromDate,#toDate,#ledgersList').val('');
        }

        function excelDownload() {
            var chk  = dateChecking();

            var ledgersList = $('#ledgersList').val();
            if(ledgersList == ''){alert('Select a Ledger Name'); chk = 1;}

            var fromDate = $('#fromDate').val();
            fromDate = fromDate.replace('/','-');
            fromDate = fromDate.replace('/','-');

            var toDate = $('#toDate').val();
            toDate = toDate.replace('/','-');
            toDate = toDate.replace('/','-');

            var gap = '_____';
            if(fromDate.length == 0){ fromDate = gap; };
            if(toDate.length == 0){ toDate = gap;};

            var myUrl = '{{url('accounting/accreport/accledgerbook/')}}';
            myUrl = myUrl+'/'+ledgersList+'/'+fromDate+'/'+toDate;

            if(chk == 0){
                window.location.href = myUrl;
            }
        }
    </script>

    <!-- jQuery 2.2.3 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

    <!-- bootstrap datepicker -->
    <script src="{{URL::asset('js/bootstrap-datepicker.js')}}"></script>

    <script>
        $(function () {
            //Date picker
            $('#fromDate,#toDate').datepicker({
                autoclose: true
            });
        });

        function stringToDate(_date,_format,_delimiter)
        {
            var formatLowerCase=_format.toLowerCase();
            var formatItems=formatLowerCase.split(_delimiter);
            var dateItems=_date.split(_delimiter);
            var monthIndex=formatItems.indexOf("mm");
            var dayIndex=formatItems.indexOf("dd");
            var yearIndex=formatItems.indexOf("yyyy");
            var month=parseInt(dateItems[monthIndex]);
            month-=1;
            var formatedDate = new Date(dateItems[yearIndex],month,dateItems[dayIndex]);
            return formatedDate;
        }
    </script>
@endsection
