<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subject Wise Summary Report</title>


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
@php $count=0; @endphp

<div class="col-sm-12">
    <section class="invoice" style="margin: 0 auto;
                 width: 800px;
                 padding: 10px; margin-top: 20px"
    >
        <!-- title row -->
        <div class="row">
            {{--<div class="col-xs-12">--}}
            {{--                    <img style="position: absolute" height="60px;" width="60px" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}"> @endif--}}
            {{--<div class="heading">--}}
            {{--<h2 style="font-size: 20px; font-weight: bold">Syed Ahmed College</h2>--}}
            {{--<p>Sukhanpukur, Gabtoli, Bogra.</p>--}}

            {{--<h5 style="font-weight: bold">Subject Wise Result Summary Summary of Grading</h5>--}}
            {{--<h5 style="font-weight: bold"> Summary of Grading</h5>--}}
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

        {{--</div>--}}
        <!-- /.col -->
        </div>

    @php
        $data ='';
		if ($batchName->get_division()) {
		$data = $batchName->get_division()->name;
	} else {
		$data = '';
	}

    @endphp


    <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table feeheadTable  text-center table-bordered">
                    <tr align="center">
                        <td colspan="12"><b>Subject Wise Summary of Grading</b></td>
                    </tr>
                    <tr align="center">
                        <td  id="summary" colspan="12">
                            <i><b>Class:</b> {{$batchName->batch_name}},</i>
                            <i><b>Group:</b> {{$data}}, </i>
                            <i><b>Year:</b> {{$academicYear->year_name}},</i>
                            <i><b>Exam:</b> {{$semesterName->name}}</i>
                        </td>
                    </tr>

                    <tr>
                        <th style="text-align: center; font-weight: 700">Subject</th>
                        <th style="text-align: center; font-weight: 700">A+</th>
                        <th style="text-align: center; font-weight: 700">A</th>
                        <th style="text-align: center; font-weight: 700">A-</th>
                        <th style="text-align: center; font-weight: 700">B</th>
                        <th style="text-align: center; font-weight: 700">C</th>
                        <th style="text-align: center; font-weight: 700">D</th>
                        <th style="text-align: center; font-weight: 700">Total Pass</th>
                        <th style="text-align: center; font-weight: 700">F</th>
                        <th style="text-align: center; font-weight: 700">Absent</th>
                        <th style="text-align: center; font-weight: 700">Total Examinee</th>
                        <th style="text-align: center; font-weight: 700">Pass(%)</th>
                    </tr>


                    {{--subject looping--}}
                    @foreach ($classSubGroupArrayList as $groupSubId=>$groupSubDetail)
                        @php
                            $groupSubDetail = (object) $groupSubDetail ;
                            $subGradeList = array_key_exists($groupSubId, $subGroupResultList)?$subGroupResultList[$groupSubId]:[];

                        $totalFailed = $subGradeList ? $subGradeList['F'] : 0;
                        $totalAbsent = $subGradeList ? $subGradeList['ABSENT'] : 0;
                        $totalExaminee = $subGradeList ? $subGradeList['TOTAL'] : 0;
                        $totalPass = $totalExaminee - ($totalFailed+$totalAbsent);

                        $percentage = $totalPass>0?(($totalPass* 100)/$totalExaminee):0;

                        @endphp
                        <tr>
                            <td class="text-left">&nbsp;&nbsp;&nbsp;{{ ucfirst(strtolower($groupSubDetail->name)) }}</td>
                            <td>{{ $subGradeList ? $subGradeList['A+'] : '-' }}</td>
                            <td>{{ $subGradeList ? $subGradeList['A'] : '-' }}</td>
                            <td>{{ $subGradeList ? $subGradeList['A-'] : '-' }}</td>
                            <td>{{ $subGradeList ? $subGradeList['B'] : '-' }}</td>
                            <td>{{ $subGradeList ? $subGradeList['C'] : '-' }}</td>
                            <td>{{ $subGradeList ? $subGradeList['D'] : '-' }}</td>
                            <td>{{ $totalPass }}</td>
                            <td>{{ $totalFailed }}</td>
                            <td>{{ $totalAbsent}}</td>
                            <td>{{ $totalExaminee }}</td>
                            <td>{{ round($percentage,2) }}</td>
                        </tr>


                        @php
                            $totalFailed = 0;
							$totalAbsent = 0;
							$totalExaminee = 0;
							$totalPass = 0;
							$percentage = 0;
                        @endphp
                    @endforeach
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