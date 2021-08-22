<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Result Report</title>


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
            margin-top: 0px;
            font-weight: 200 !important;
            font-size: 14px;
            vertical-align: middle;
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

        #inst-photo{float:left; width: 15%;}
        #inst{padding-bottom: 3px; width: 100%;}
        #inst-info{float:left; text-align: center; width: 85%;}


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
            margin-left: 150mm;
            margin-top: -30px;
        }
        .convener {
            margin-left: 20mm;
        }

        #summary i {
            /*padding-right: 15px;*/
            vertical-align: middle;
        }
    </style>
</head>
<body>



@php
    $count=0; $slNo=1; $hMarks=0;
    $studentList = array_chunk($studentList, 24);
@endphp

<div class="col-sm-12">

@foreach($studentList as $studentChunk)
            <!-- title row -->
            <div class="row" style="margin-top:20px">
{{--                <div class="col-xs-12">--}}
                    {{--                    <img style="position: absolute" height="60px;" width="60px" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}"> @endif--}}
                    {{--<div class="heading">--}}
                    {{--<h2 style="font-size: 20px; font-weight: bold">Syed Ahmed College</h2>--}}
                    {{--<p>Sukhanpukur, Gabtoli, Bogra.</p>--}}

                    {{--<h5 style="font-weight: bold">Student Result Report</h5>--}}
                    {{--</div>--}}

{{--                    <div id="inst"  style="width: 100%;" >--}}
{{--                        <div id="inst-photo">--}}
{{--                            @if($instituteInfo->logo)--}}
                                <img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  style="width:85px;height:85px; margin-bottom: 5px; position: absolute; margin-left: 20px">
{{--                            @endif--}}
{{--                        </div>--}}
                        <div id="inst-info" style="width: 100%">
                            <b style="font-size: 25px; ">{{$instituteInfo->institute_name}}</b><br/>
                            <span style="font-size:12px; ">{{'Address: '.$instituteInfo->address1}}</span><br/>
                            <span style="font-size:12px;">{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email}}</span><br/>
                        </div>
{{--                    </div>--}}
{{--                </div>--}}
                <!-- /.col -->
            </div>

{{--{{dd($tabulationSheet)}}--}}

        @php
            $subGradeList =  array_key_exists('grade_count', $tabulationSheet)?$tabulationSheet['grade_count']:[];
			$aPlus = array_key_exists('A+',$subGradeList)?$subGradeList['A+']:0;
			$a = array_key_exists('A',$subGradeList)?$subGradeList['A']:0;
			$aMinus = array_key_exists('A-',$subGradeList)?$subGradeList['A-']:0;
			$b = array_key_exists('B',$subGradeList)?$subGradeList['B']:0;
			$c = array_key_exists('C',$subGradeList)?$subGradeList['C']:0;
			$d = array_key_exists('D',$subGradeList)?$subGradeList['D']:0;
			$f = array_key_exists('F',$subGradeList)?$subGradeList['F']:0;
			$total = array_key_exists('TOTAL',$subGradeList)?$subGradeList['TOTAL']:0;
			$totalPass= ($aPlus+$a+ $aMinus + $b + $c+ $d);
			$totalExaminee= $total;
			$totalFail= ($total-$totalPass);
			$passPercentage= $totalPass>0?(round((($totalPass * 100) / $totalExaminee),2)):0;
        @endphp

        {{--my assessmentlist--}}
        @php $myAssList = [];  @endphp
        {{--categroy looping--}}
        @foreach($catDetailArrayList as $catId=>$assList)
            {{--category checking--}}
            @if($category!=$catId) @continue @endif
            @php $myAssList = $assList;  @endphp
        @endforeach
        <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive" style="margin-top: 30px">
                    <table class="table feeheadTable  table-bordered text-center">
                        @php
                            $data ='';
							if ($batchName->get_division()) {
							$data = $batchName->get_division()->name;
						} else {
							$data = '';
						}

                        @endphp

                        <tr>
                            <td id="summary" style="text-align: center" colspan="{{count($myAssList)+8}}">
                                <h4><b>Subject Wise Marks</b></h4>
                            </td>
                        </tr>

                        <tr>
                            <td id="summary" style="text-align: center" colspan="{{count($myAssList)+8}}">
                                <i><strong>Class: </strong>{{$batchName->batch_name}},</i>
                                <i><strong>Group: </strong>{{$data}},</i>
                                <i><strong>Subject: </strong> {{$subjectName->subject_name}},</i>
                                <i><strong>Year: </strong> {{$academicYear->year_name}},</i>
                                <i><strong>Exam: </strong>{{$semesterName->name}}</i>
                            </td>
                        </tr>
                        <tr valign="middle">
                            <td rowspan="2">#</td>
                            <td  rowspan="2">Student ID</td>
                            <td rowspan="2">Roll</td>
                            <td  rowspan="2">Name</td>
                            <td colspan="{{count($myAssList)}}" align="center">Marks</td>
                            <td rowspan="2">Total</td>
                            <td rowspan="2">100%</td>
                            <td rowspan="2">LG</td>
                            <td rowspan="2">PG</td>
                        </tr>
                        <tr>
                            {{--assessment looping--}}
                            @foreach($myAssList as $assId=>$assName)
                                <td>{{$assName}}</td>
                            @endforeach
                        </tr>
                        {{--student list--}}
                        @foreach($studentChunk as $std)
                            @php
                                $std = (object)$std;
                                $gradeCount = array_key_exists('grade_count',$tabulationSheet)?$tabulationSheet['grade_count']:[];
								$stdresult = array_key_exists($std->id,$tabulationSheet)?$tabulationSheet[$std->id]:[];
								$sub_list = array_key_exists('sub_list',$stdresult)?$stdresult['sub_list']:[];
								$subResult = array_key_exists($classSubjectGroup,$sub_list)?$sub_list[$classSubjectGroup]:[];
								$subAssList = array_key_exists('ass_list',$subResult)?$subResult['ass_list']:[];
                                $subFailAssList = array_key_exists('fail_ass_list',$subResult)?$subResult['fail_ass_list']:[];
                            @endphp
                            <tr>
                                <td>{{ $slNo++ }}</td>
                                <td>{{ $std->username }}</td>
                                <td>{{ $std->gr_no}}</td>
                                <td  class="text-left">&nbsp;&nbsp;&nbsp;{{ $std->name}}</td>

                                {{--assessment looping--}}
                                @foreach($myAssList as $assId=>$assName)
                                    <td style="color:{{(in_array($assId, $subFailAssList) || (array_key_exists($assId, $subAssList) && $subAssList[$assId]=='Abs'))?'red':'black'}};">{{array_key_exists($assId, $subAssList)?$subAssList[$assId]:'-'}}</td>
                                @endforeach
                                {{--@endforeach--}}

                                @php
                                    $obMarks = $subResult?$subResult['obtained_mark']:0;
                                    if($obMarks>$hMarks){
                                        $hMarks = $obMarks;
                                    }
                                @endphp

                                {{-- ['exam_mark', 'obtained_mark', 'percentage', 'lg', 'gp'] --}}
                                <td style="color:{{($subResult && $subResult['lg']=='F')?'red':'black'}};">{{$obMarks}} / {{$subResult?$subResult['exam_mark']:''}}</td>
                                <td style="color:{{($subResult && $subResult['lg']=='F')?'red':'black'}};">{{$subResult?$subResult['percentage']:''}}</td>
                                <td style="color:{{($subResult && $subResult['lg']=='F')?'red':'black'}};">{{$subResult?$subResult['lg']:''}}</td>
                                <td style="color:{{($subResult && $subResult['lg']=='F')?'red':'black'}};">{{$subResult?$subResult['gp']:''}}</td>
                            </tr>
                        @endforeach
                        @if($loop->last == true)

                                <tr>
                                    <th colspan="{{count($myAssList)+8}}">
                                        <h5 id="summary" style="text-align: center">
                                            <i>Highest Marks: {{$hMarks}},</i>
                                            <i>Passing Percentage: {{$passPercentage}},</i>
                                            <i>Fail Percentage: {{100-$passPercentage}}</i>
                                        </h5>
                                    </th>
                                </tr>

                                <tr>
                                    <th colspan="{{count($myAssList)+8}}">
                                        <h5 id="summary" style="text-align: center">
                                            <i>A+ = {{$gradeCount['A+']}},</i>
                                            <i> A = {{$gradeCount['A']}},</i>
                                            <i> A- = {{$gradeCount['A-']}},</i>
                                            <i> B = {{$gradeCount['B']}},</i>
                                            <i> C = {{$gradeCount['C']}},</i>
                                            <i> D = {{$gradeCount['D']}},</i>
                                            {{--<i> F = {{$gradeCount['F']}},</i>--}}
                                            <i> F = {{$totalFail}},</i>
                                            <i> Total Examinee = {{$totalExaminee}}</i>
                                            {{--<i> Absent = {{$gradeCount['ABSENT']}} </i>--}}
                                            {{--<i> Total Subject = {{$gradeCount['total']}}</i>--}}
                                        </h5>
                                    </th>
                                </tr>
                        @endif


                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="singFooter" style="margin-top: 50px">
                <p class="convener reportFooter">Convener   </p>
                <p class="principal reportFooter">Principal   </p>
            </div>




{{--</section>--}}

<div class="breakNow"></div>


@endforeach
</div>




<script>
function myFunction() {
window.print();
}
// window.print();
</script>
</body>
</html>