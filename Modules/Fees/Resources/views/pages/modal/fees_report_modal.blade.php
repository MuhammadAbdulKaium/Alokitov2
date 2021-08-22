@if(!empty($report_type=="pdf"))

    <style>
        #inst-info{
            float:left;
            width: 85%;
            margin: 30px;
            text-align: center;
        }

        #inst-photo {
            float: left;
            margin-top: 20px;
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
    <div class="small-section" style="clear: both" >
        <div id="academic_year">
            <h4>Academic Year : {{$academicYearProfile->year_name}} </h4>
        </div>
        <div id="academic_level">
            <h4>Academic Level : {{$academicLevelProfile->level_name}}</h4>
        </div>

    </div>





@endif


@if(!empty($batchList))
    <div class="box box-solid">
        <div><!-- break css -->
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-sitemap"></i> @if(!$report_type=="download") Batch List @endif</h3>
                @if(!empty($academicYear))
                    <a class="btn btn-info  pull-right" href="/fees/report/pdf/{{$academicYear}}/{{$academicsLevel}}"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a> <!-- /.box-header -->
                    <a class="btn btn-info  pull-right" href="/fees/report/xlxs/{{$academicYear}}/{{$academicsLevel}}"><i class="fa fa-file-xls-o" aria-hidden="true"></i> Excel</a> </div><!-- /.box-header -->
            @endif

        </div>
        <div id="fees_report_table_container" class="box-body table-responsive">
            <div id="w1" class="grid-view">
                <table id="feesDailyCollection" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Fees Details</th>
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
                    @foreach($batchList as $batch)
                        <tr data-key="0">
                            <td>{{$i++}}</td>
                            <td>{{$batch->batch_name}}</td>
                            <td>{{$batch->payableAmount()}}</td>
                            <td>{{$batch->paidAmount()}}</td>
                            <td>{{$batch->discountAmount()}}</td>
                            <td>1000.00</td>
                        </tr>
                        @php
                            $totalPaybale+=$batch->payableAmount();
                            $totalPaid+=$batch->paidAmount();
                            $totalDisocunt+=$batch->discountAmount();
                        @endphp
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr class="text-bold text-green">
                        <td></td>
                        <td>GRAND TOTAL</td>
                        <td>{{ $totalPaybale}}</td>
                        <td>{{ $totalPaid}}</td>
                        <td>{{$totalDisocunt}}</td>
                        <td>1000.00</td></tr>
                    </tfoot>
                </table>
            </div>
            @if(!empty($report_type=="pdf") || !empty($report_type=="xlxs"))
            @else
            <div class="link" style="float: right">
            {!! $batchList->appends(Request::only([
            'search'=>'search',
            'filter'=>'filter',
            'academic_year'=>'academic_year',
            'academic_level'=>'academic_level',

            ]))->render() !!}</div>
            @endif


        </div>
    </div>

@else

    not found

@endif