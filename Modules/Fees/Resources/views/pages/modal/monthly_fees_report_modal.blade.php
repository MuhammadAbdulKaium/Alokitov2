@php  $batchString="Class" @endphp

@if($report_type=="pdf")

    <style>
        #inst-info{
            float:left;
            width: 85%;
            margin: 30px;
            text-align: center;
        }
        .date-range {
            clear: both;
            width: 200px;
            border: 1px solid;
            margin-top: 10px !important;
            padding: 5;
            background: #eee;
            color: #000;
            margin-left: 20px !important;
            font-size: 12px;
        }

        #inst-photo {
            float: left;
            margin-top: 20px;
            margin-left: 20px !important;
        }


        * { margin: 0; padding: 0; }
        .header-info {
            clear: both;

        }
        #academic_year {
            float: left;
            margin-left: 50px;
            font-size: 14px;
        }

        #academic_level{
            float: right;
            margin-right: 50px;
            font-size: 14px;
        }

        .small-section {
            border-top: 2px solid #00a65a;
            padding: 10px;
        }



        body { font: 12px/1.4 Georgia, serif; }
        textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid black; padding: 5px; }
        #feesDailyCollection {
            padding: 20px;
        }
        #feesDailyCollection { clear: both; width: 100%; margin: 30px 0 0 0; border: 1px solid black; }
        #feesDailyCollection th { background: #eee; }
        #feesDailyCollection textarea { width: 80px; height: 50px; }
        #feesDailyCollection tr.item-row td { border: 0; vertical-align: top; }
        #feesDailyCollection td.description { width: 300px; }
        #feesDailyCollection td.item-name { width: 175px; }
        #feesDailyCollection td.description textarea, #feesDailyCollection td.item-name textarea { width: 100%; }
        #feesDailyCollection td.total-line { border-right: 0; text-align: right; }
        #feesDailyCollection td.total-value { border-left: 0; padding: 10px; }
        #feesDailyCollection td.total-value textarea { height: 20px; background: none; }
        #feesDailyCollection td.balance { background: #eee; }
        #feesDailyCollection td.blank { border: 0; }

    </style>

    <div class="header-info">
        <div id="inst-photo">
            @if($instituteInfo->logo)
                <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:100px;height:100px">
            @endif
        </div>
        <div id="inst-info">
            <b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
        </div>
    </div>


@endif



@if(!empty($monthlyReportsView))


    @php  $batchAmountList = array(); @endphp

    @foreach ($monthlyReportsView as $key=>$month)
        @if(!empty($month))
            @php $start_date="";  $end_date=""; $start_date=date('Y-m-01',strtotime('2017-'.$key.'-12'));
                            $end_date= date('Y-m-t',strtotime('2017-'.$key.'-12'));
            @endphp
        @endif



        @foreach ($month as $k => $v)
            @if(array_key_exists($v->batch, $batchAmountList)==false)
                @php $batchAmountList[$v->batch] = ['payable'=>0,'paid'=>0];@endphp
            @endif
        @endforeach

        @foreach ($month as $k => $v)
            @if(array_key_exists($v->batch, $batchAmountList)==true)
                @php
                    $batchAmountList[$v->batch]['paid'] = $batchAmountList[$v->batch]['paid']+$v->payed_amount;
                    $batchAmountList[$v->batch]['payable'] = $batchAmountList[$v->batch]['payable']+$v->payable_amount;
                @endphp
            @endif
        @endforeach

    @endforeach



    <div class="box box-solid">
        <div><!-- break css -->
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-sitemap"></i> @if(!$report_type=="download") {{$batchString}} List @endif</h3>
                @if(!empty($academicYear))
                    <form action="/fees/monthly/report" method="get">
                        <input type="hidden" name="academic_years" @if(!empty($academicYear)) value="{{$academicYear}}" @endif>
                        <input type="hidden" name="academic_level"  @if(!empty($academicLevel)) value="{{$academicLevel}}" @endif>
                        <input type="hidden" name="batch"  @if(!empty($batch)) value="{{$batch}}" @endif>
                        <input type="hidden" name="section"  @if(!empty($section)) value="{{$section}}" @endif>
                        <input type="hidden" name="from_date"  @if(!empty($from_date)) value="{{$from_date}}" @endif>
                        <input type="hidden" name="to_date"  @if(!empty($to_date)) value="{{$to_date}}" @endif>
                        <button class="btn btn-primary pull-right" value="pdf" name="download">PDF</button>
                    </form>
                @endif

            </div>
            <div id="fees_report_table_container" class="box-body table-responsive">
                <div id="w1" class="grid-view">
                    <table id="feesDailyCollection" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#Serial</th>
                            <th>{{$batchString}} Name</th>
                            <th>Amount to be paid</th>
                            <th>Paid Amount</th>
                            <th>Discount</th>
                            <th>Outstanding Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalPaybale=0;
                            $totalPaid=0;
                            $totalDisocunt=0;
                            $newArray=array();
                        $i=1;

                        @endphp
                        @foreach($batchAmountList as $key=>$amount)
                            <tr data-key="0">
                                <td>{{ $i++}}</td>
                                <td>{{getBatchName($key)}}</td>
                                <td>{{$amount['payable']}}</td>
                                <td>{{$amount['paid']}}</td>
                                {{--<td>{{$batch->paidAmount()}}</td>--}}
                                {{--<td>{{$batch->discountAmount()}}</td>--}}
                                <td>---</td>
                                <td>---</td>
                            </tr>
                            @php  $totalPaybale+=$amount['payable'];$totalPaid+=$amount['paid'] @endphp
                         @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="text-bold text-green">
                            <td></td>
                            <td>GRAND TOTAL</td>
                            <td>{{ $totalPaybale}}</td>
                            <td>{{ $totalPaid}}</td>
                            <td></td>
                            <td></td>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

        @elseif(!empty($monthlyReportsView) && empty($monthlyReports))
            not found
        @endif


        {{--Montly pdf report here --}}

        @if(!empty($monthlyReports))

                 @foreach ($monthlyReports as $key=>$month)
                 @if(!empty($month))
                 @Php $start_date="";  $end_date=""; @endphp

                @php $start_date=date('Y-m-01',strtotime('2017-'.$key.'-12'));
                            $end_date= date('Y-m-t',strtotime('2017-'.$key.'-12')); @endphp
            <div class="date-range">  {{date('F j',strtotime($start_date))}}
        -
                {{date('F j',strtotime($end_date))}}</div>

        @endif
        @php  $batchAmountList = array(); @endphp

        @foreach ($month as $k => $v)

            @if(array_key_exists($v->batch, $batchAmountList)==false)
                @php $batchAmountList[$v->batch] = ['payable'=>0,'paid'=>0];@endphp
            @endif
        @endforeach

        @foreach ($month as $k => $v)
            @php $batchAmountList[$v->batch]['paid'] = $batchAmountList[$v->batch]['paid']+$v->payed_amount;
                  $batchAmountList[$v->batch]['payable'] = $batchAmountList[$v->batch]['payable']+$v->payable_amount;
            @endphp
        @endforeach

        @if(!empty($batchAmountList))
            <div id="fees_report_table_container" class="box-body table-responsive" >
                <div id="w1" class="grid-view">

                    <table id="feesDailyCollection" class="table table-striped table-bordered" style="margin-top: -60px !important;">
                        <thead>
                        <tr>
                            <th>{{$batchString}} Name</th>
                            <th>Amount to be paid</th>
                            <th>Paid Amount</th>
                            <th>Discount</th>
                            <th>Outstanding Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalPaybale=0;
                            $totalPaid=0;
                            $totalDisocunt=0;
                        $i=1;
                        @endphp
                        @foreach($batchAmountList as $key=>$amount)
                            <tr data-key="0">
                                <td>{{getBatchName($key)}}</td>
                                <td>{{$amount['payable']}}</td>
                                <td>{{$amount['paid']}}</td>
                                {{--<td>{{$batch->paidAmount()}}</td>--}}
                                {{--<td>{{$batch->discountAmount()}}</td>--}}
                                <td>---</td>
                                <td>---</td>
                            </tr>
                            @php
                                $totalPaybale+=$amount['payable'];
                                $totalPaid+=$amount['paid'];
                            @endphp
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr class="text-bold text-green">
                            <td>GRAND TOTAL</td>
                            <td>{{ $totalPaybale}}</td>
                            <td>{{ $totalPaid}}</td>
                            <td></td>
                            <td></td>
                        </tfoot>
                    </table>
                    @endif
                </div>
                @endforeach

@endif


