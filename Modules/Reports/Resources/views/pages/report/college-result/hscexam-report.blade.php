<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Board Result Report </title>


    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    <style>

        span.res-span-item.roll {
            text-align: right;
        }

        span.res-span-item {
            display: inline-block;
            width: 39px;
        }

        .heading {
            text-align: center;
            margin: 0px;
            padding: 0px;
        }

        p {
            font-size: 12px;
            padding-top: 5px;
        }

        h2,
        h5 {
            margin: 0px;
            padding: 0px;
        }

        .fontSize13 {
            font-size: 14px;
        }

        .fontSize13>tr>td {
            font-size: 14px;
        }

        .feeheadTable {
            margin-top: 20px;
            font-weight: 200 !important;
            font-size: 14px;
        }

        .heading-section {
            text-align: center;
        }

        .table>thead>tr>th,
        .table>tbody>tr>th,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>tbody>tr>td,
        .table>tfoot>tr>td {
            padding: 2px !important;
            font-weight: 200;
            line-height: 25px;
        }

        .table>thead {
            text-align: center
        }
        /*.studentResult{*/
        /*    margin: 5px;*/
        /*}*/
        .grupName{
            font-size: 16px;
            text-align: center;
            /*font-weight: bold;*/
            margin-top: 50px;
            letter-spacing: 5px;
            margin-bottom: 20px;
        }
        #inst-photo{
            float:left;
            width: 15%;
        }
        #inst-info{
            float:left;
            text-align: center;
            width: 85%;
        }

        #inst{
            padding-bottom: 3px;
            width: 100%;
        }

        @media print {
            .col-sm-1,
            .col-sm-2,
            .col-sm-3,
            .col-sm-4,
            .col-sm-5,
            .col-sm-6,
            .col-sm-7,
            .col-sm-8,
            .col-sm-9,
            .col-sm-10,
            .col-sm-11,
            .col-sm-12 {
                float: left;
            }
            .col-sm-12 {
                width: 100%;
            }
            .col-sm-11 {
                width: 91.66666667%;
            }
            .col-sm-10 {
                width: 83.33333333%;
            }
            .col-sm-9 {
                width: 75%;
            }
            .col-sm-8 {
                width: 66.66666667%;
            }
            .col-sm-7 {
                width: 58.33333333%;
            }
            .col-sm-6 {
                width: 50%;
            }
            .col-sm-5 {
                width: 41.66666667%;
            }
            .col-sm-4 {
                width: 33.33333333%;
            }
            .col-sm-3 {
                width: 25%;
            }
            .col-sm-2 {
                width: 16.66666667%;
            }
            .col-sm-1 {
                width: 8.33333333%;
            }
            .breakNow {
                page-break-inside: avoid;
                page-break-after: always;
                margin-top: 10px;
            }
        }

        .principal {
            margin-top: -30px;
            margin-left: 330mm;
        }
        .convener {
            margin-left: 20mm;
        }

    </style>
</head>
<body>



<button class="btn btn-primary hidden-print pull-right" onclick="myFunction()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
@php $count=0;


$tem = '';

                    $gpa5 = 0;
                    $totalPassCount = 0;
	                $totalFailCount = 0;
	                $totalStd = 0;
	                $percentage = 0;
	                $j=0;
foreach($allBatch as $batchId=>$batchName){
        if($j!=0) {
                 $tem .= '<div class="breakNow"></div>';
        }
        $j++;

    $tem .= '<p class="grupName">--------------------------------------------'.$batchName.'-'.$acdemicYear->year_name.'--------------------------------------------</p>';

        $passCount = 0;
		$failCount = 0;
		$stdCount = 0;
		$stdList = $boardTypeResultList[$batchId]['student'];
		$result = $boardTypeResultList[$batchId]['result'];

		$passList = $result['pass_list'];
		$failList = $result['fail_list'];

    //$tem .='<p>Passed</p>';
    // foreach($passList as $stdId => $pl){
    $count = count($passList);
    $i=1;
    foreach($stdList as $stdId => $stdRoll){
            if(array_key_exists($stdId, $passList)){
            $pl = $passList[$stdId];

            if((int)$pl===5){
               $gpa5++;
            }
             $passCount++;
             $stdCount++;
            $tem .='<span class="studentResult"> <span class="res-span-item roll"> '.$stdRoll.'</span> <span class="res-span-item gpa"> ['. sprintf("%.2f",$pl).']'.($count == $i?'':'').'</span></span>';
        $i++;}
    }

    if(count($passList)>0){
       $tem .= ' = '.$passCount.'<br/><br/>';
    }

   //$tem .=' <p>Failed</p>';
    $count = count($failList);

       foreach($stdList as $stdId => $stdRoll){
         if(array_key_exists($stdId, $failList)){
            $fl = $failList[$stdId];
             $failCount++;
             $stdCount++;

            $tem .='<span class="studentResult"> <span class="res-span-item roll">'. $stdRoll .'</span> <span class="res-span-item gpa"> [&nbsp;F' .(($fl>7)?7:$fl) .'&nbsp;]'.($count == $failCount?'':'').'</span></span>';
         }

    }
    if(count($failList)>0){
        $tem .='= '.$failCount;
    }

    $tem .='<hr>';

    $tem.= '
           <div class="singFooter" style="margin-top: 100px">
                <p class="convener reportFooter">Convener   </p>
                <p class="principal reportFooter">Principal   </p>
            </div>
    ';

        $totalPassCount += $passCount;
		$totalFailCount += $failCount;
		$totalStd += $stdCount;


		$percentage = round(($totalPassCount * 100) / $totalStd,2);
}









@endphp


<div class="col-sm-12">
    <section class="invoice" style="margin: 0 auto;
                 width: 1500px;
                 padding: 10px; margin-top: 20px"
    >
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">

                {{--<img style="position: absolute" height="60px;" width="60px" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}"> @endif--}}
{{--                <div class="heading">--}}
{{--                    <h2 style="font-size: 20px; font-weight: bold">Syed Ahmed College</h2>--}}
{{--                    <p>Sukhanpukur, Gabtoli, Bogra.</p>--}}
{{--                    <h5 style="font-weight: bold">Result of HSC Examination 2019</h5>--}}
{{--                </div>--}}
{{--                <br/>--}}
{{--                <p class="text-center">Total Appeared : {{ $totalStd }}, Total Passed: {{$totalPassCount}}, Percentage Of Passed: {{ $percentage }}, GPA 5: {{$gpa5}}</p>--}}

                <div id="inst" class="text-center clear" style="width: 100%; margin-top: 30px;" >
                    <div id="inst-photo">
                        @if($instituteInfo->logo)
                            <img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  style="width:85px;height:85px; margin-bottom: 5px;">
                        @endif
                    </div>
                    <div id="inst-info">
                        <b style="font-size: 25px; margin-right: 200px">{{$instituteInfo->institute_name}}</b><br/>
                        <span style="font-size:12px; margin-right: 200px">{{'Address: '.$instituteInfo->address1}}</span><br/>
                        <span style="font-size:12px;margin-right: 200px">{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email}}</span><br/>
                        <b style="font-size: 16px; margin-right: 200px">{{$semesterName}}--- {{$categoryName}}</b><br/>
                        <p style="font-size:20px; font-weight: bold; margin-right: 200px">Total Appeared : {{ $totalStd }}, Total Passed: {{$totalPassCount}}, Percentage Of Passed: {{ $percentage }}, GPA 5: {{$gpa5}}</p>

                    </div>
                </div>
            </div>
            <br/>
            <br/>
            <!-- /.col -->
        </div>


        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                {!! $tem !!}
                {{--@php--}}
                    {{--$totalPassCount = 0;--}}
	                {{--$totalFailCount = 0;--}}
                {{--@endphp--}}
                {{--@foreach($allBatch as $batchId=>$batchName)--}}
                    {{--<p class="grupName">-----------------------------------------------{{$batchName}}-------------------------------------------------------</p>--}}
                    {{--<br>--}}
                    {{--@php--}}
                        {{--$passCount = 0;--}}
                        {{--$failCount = 0;--}}
                        {{--$stdList = $boardTypeResultList[$batchId]['student'];--}}
	                    {{--$result = $boardTypeResultList[$batchId]['result'];--}}

                        {{--$passList = $result['pass_list'];--}}
                        {{--$failList = $result['fail_list'];--}}
                    {{--@endphp--}}
                    {{--<p>Passed</p>--}}
                    {{--@foreach($passList as $stdId => $pl)--}}

                        {{--@if(array_key_exists($stdId, $stdList))--}}
                            {{--@php $passCount++ @endphp--}}
                            {{--<span class="studentResult">{{$stdList[$stdId]}} [{{  $pl }}]</span>--}}
                        {{--@endif--}}
                    {{--@endforeach--}}
                    {{--= {{$passCount}}--}}

                    {{--<p>Failed</p>--}}
                    {{--@foreach($failList as $stdId => $fl)--}}
                        {{--@if(array_key_exists($stdId, $stdList))--}}
                            {{--@php $failCount++ @endphp--}}
                            {{--<span class="studentResult">{{$stdList[$stdId]}} [F{{  $fl>7?7:$fl }}]</span>--}}
                        {{--@endif--}}
                    {{--@endforeach--}}
                    {{--= {{$failCount}}--}}
                    {{--<hr>--}}

                    {{--@php--}}
                        {{--$totalPassCount += $passCount;--}}
                        {{--$totalFailCount += $failCount;--}}
                    {{--@endphp--}}

                {{--@endforeach--}}
            </div>
            <!-- /.col -->

        </div>
        <!-- /.row -->

    </section>

</div>

<script>
    window.print();
</script>
</body>
</html>