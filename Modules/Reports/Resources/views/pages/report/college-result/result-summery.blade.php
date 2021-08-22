<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Result Summary</title>


    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    <style>

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


        #inst-photo{float:left; width: 15%; text-align: center}
        #inst{padding-bottom: 3px; width: 100%; text-align: center}
        #inst-info{float:left; text-align: center; width: 85%; padding-right: 100px;}

        #summary i {
            padding-right: 50px;
            vertical-align: middle;
        }

        .upper{
            text-transform: uppercase !important;
        }


        .reportFooter {
            margin-top: 20px; font-weight: bold; display: inline-block;
        }
        .principal {
            margin-left: 140mm;
        }
        .convener {
            margin-left: 20mm;
        }

    </style>
</head>
<body>



<button class="btn btn-primary hidden-print pull-right" onclick="myFunction()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
@php $count=0;
$allPercentage = 0;
$allFail = 0;
$allPass = 0;
$allExaminee = 0;

@endphp

<div class="col-sm-12">
    <section class="invoice" style="margin: 0 auto;
                 width: 800px;
                 border: double #ccc;
                 padding: 10px; margin-top: 20px"
    >
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                {{--                    <img style="position: absolute" height="60px;" width="60px" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}"> @endif--}}
                {{--<div class="heading">--}}
                {{--<h2 style="font-size: 20px; font-weight: bold">Syed Ahmed College</h2>--}}
                {{--<p>Sukhanpukur, Gabtoli, Bogra.</p>--}}

                {{--<h5 style="font-weight: bold">Half Yearly Examination-2019</h5>--}}
                {{--<h5 style="font-weight: bold"> Result Summary</h5>--}}
                {{--</div>--}}


                <div id="inst" class="text-center clear" style="width: 100%; margin-top: 30px;" >
                    <div id="inst-photo">
                        @if($instituteInfo->logo)
                            <img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  style="width:85px;height:85px; margin-bottom: 5px;">
                        @endif
                    </div>
                    <div id="inst-info">
                        <b style="font-size: 25px;">{{$instituteInfo->institute_name}}</b><br/>
                        <span style="font-size:12px;">{{'Address: '.$instituteInfo->address1}}</span><br/>
                        <span style="font-size:12px;">{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email}}</span><br/>
                    </div>
                </div>

            </div>
            <!-- /.col -->
        </div>


        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <h5 class="text-center"><b>Examination: </b>{{$semesterName->name}},  <b>Year: </b> {{$academicYear->year_name}}</h5>
                <hr>
                {{--<table class="table feeheadTable  table-bordered text-center">--}}
                {{--<tr align="center">--}}
                {{--<td  id="summary" colspan="12">--}}
                {{--<i><b>Year:</b> ,</i>--}}
                {{--<i><b>Exam:</b> </i>--}}
                {{--</td>--}}
                {{--</tr>--}}
                {{--</table>--}}

                <table class="table feeheadTable  table-bordered text-center">
                    <tr align="center">
                        <td colspan="5">
                            <h5> <b>Result Summary</b> </h5>
                        </td>
                    </tr>
                    <tr>
                        <td class="upper"><b>Group</b></td>
                        <td><b>Number of Examinees</b></td>
                        <td><b>Number of Passing</b></td>
                        <td><b>Number of Failed</b></td>
                        <td><b>Pecentage of Passing</b></td>
                    </tr>

                    @foreach($allBatch as $batchId => $batchName)

                        @php

                            $batchSummery = array_key_exists($batchId ,$subSummaryResultList) ? $subSummaryResultList[$batchId] : [];
							$aPlus = array_key_exists('A+',$batchSummery)?$batchSummery['A+']:0;
							$a = array_key_exists('A',$batchSummery)?$batchSummery['A']:0;
							$aMinus = array_key_exists('A-',$batchSummery)?$batchSummery['A-']:0;
							$b = array_key_exists('B',$batchSummery)?$batchSummery['B']:0;
							$c = array_key_exists('C',$batchSummery)?$batchSummery['C']:0;
							$d = array_key_exists('D',$batchSummery)?$batchSummery['D']:0;
							$f = array_key_exists('F',$batchSummery)?$batchSummery['F']:0;
							$totalPass= ($aPlus+$a+ $aMinus + $b + $c+ $d);
							$totalExaminee= ($totalPass+ $f);
							$passPercentage= $totalPass>0? (round((($totalPass * 100) / $totalExaminee),2)):0;


                            $allExaminee += $totalExaminee;
                            $allPass += $totalPass;
                            $allFail += $f;
                            $allPercentage =$allPass>0 ? (round((($allPass * 100) / $allExaminee),2)):0;;

                        @endphp
                        <tr>
                            <td class="text-left"><b>{{ $batchName }}</b></td>
                            <td>{{ $totalExaminee }}</td>
                            <td>{{ $totalPass }}</td>
                            <td>{{ $f }}</td>
                            <td>{{ round($passPercentage,2) }}</td>

                        </tr>
                        @php $passPercentage = 0; @endphp
                    @endforeach
                    <tr>
                        <td class="upper text-left"><b>TOTAL</b></td>
                        <td><b>{{$allExaminee}}</b></td>
                        <td><b>{{$allPass}}</b></td>
                        <td><b>{{$allFail}}</b></td>
                        <td><b>{{$allPercentage}}</b></td>
                    </tr>




                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table feeheadTable  table-bordered text-center">
                    <tr align="center">
                        <td colspan="9">
                            <h5><b>Summary of Grading</b></h5>
                        </td>
                    </tr>
                    <tr align="center">
                        <td width="30%"><b>GROUP</b></td>
                        <td><b>A+</b></td>
                        <td><b>A</b></td>
                        <td><b>A-</b></td>
                        <td><b>B</b></td>
                        <td><b>C</b></td>
                        <td><b>D</b></td>
                        <td><b>F</b></td>
                        <td width="20%"><b>Total Examinee</b></td>
                    </tr>

                    @php
                        $allAPlus = 0;
                        $allA = 0;
                        $allAMinus = 0;
                        $allB = 0;
                        $allC = 0;
                        $allD = 0;
                        $allF = 0;
                        $allExaminee = 0;
                    @endphp

                    @foreach($allBatch as $batchId => $batchName)

                        @php
                            $batchSummery = array_key_exists($batchId ,$subSummaryResultList) ? $subSummaryResultList[$batchId] : [];
                            $aPlus = array_key_exists('A+',$batchSummery)?$batchSummery['A+']:0;
                            $a = array_key_exists('A',$batchSummery)?$batchSummery['A']:0;
                            $aMinus = array_key_exists('A-',$batchSummery)?$batchSummery['A-']:0;
                            $b = array_key_exists('B',$batchSummery)?$batchSummery['B']:0;
                            $c = array_key_exists('C',$batchSummery)?$batchSummery['C']:0;
                            $d = array_key_exists('D',$batchSummery)?$batchSummery['D']:0;
                            $f = array_key_exists('F',$batchSummery)?$batchSummery['F']:0;
                            $totalPass= ($aPlus+$a+ $aMinus + $b + $c+ $d);
                            $totalExaminee= ($totalPass+ $f);

                         $allAPlus += $aPlus;
                        $allA += $a;
                        $allAMinus += $aMinus;
                        $allB += $b;
                        $allC += $c;
                        $allD += $d;
                        $allF += $f;
                        $allExaminee += $totalExaminee;

                        @endphp
                        <tr>
                            <td class="text-left"><b>{{ $batchName }}</b></td>
                            <td>{{ $aPlus }}</td>
                            <td>{{ $a }}</td>
                            <td>{{ $aMinus }}</td>
                            <td>{{ $b }}</td>
                            <td>{{ $c }}</td>
                            <td>{{ $d }}</td>
                            <td>{{ $f }}</td>
                            <td>{{ $totalExaminee }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td  class="text-left"><b>TOTAL</b></td>
                        <td><b>{{ $allAPlus }}</b></td>
                        <td><b>{{ $allA }}</b></td>
                        <td><b>{{ $allAMinus }}</b></td>
                        <td><b>{{ $allB }}</b></td>
                        <td><b>{{ $allC }}</b></td>
                        <td><b>{{ $allD }}</b></td>
                        <td><b>{{ $allF }}</b></td>
                        <td><b>{{ $allExaminee }}</b></td>
                    </tr>

                </table>
            </div>
            <!-- /.col -->
        </div>

        <!-- /.row -->
        <div class="singFooter" style="margin-top: 50px">
            <p class="convener reportFooter">Convener   </p>
            <p class="principal reportFooter">Principal   </p>
        </div>

    </section>

</div>

<script>
    function myFunction() {
        window.print();
    }
    window.print();
</script>
</body>
</html>