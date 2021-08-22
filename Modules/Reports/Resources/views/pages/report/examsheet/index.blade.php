
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
    /*.invoice {*/
    /*position: relative;*/
    /*background: #fff;*/
    /*border: 1px solid #f4f4f4;*/
    /*padding: 10px;*/
    /*!*margin: 10px 25px;*!*/
    /*}*/
    /*.page-header {*/
    /*margin: 10px 0 20px 0;*/
    /*font-size: 22px;*/
    /*}*/
    .heading {
        text-align: center;
        margin: 0px;
        padding: 0px;
    }
    p {
        font-size: 12px;
        padding-top: 5px;
    }
    h2, h5 {
        margin: 0px;
        padding: 0px;
    }

    .fontSize13 {
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

    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding: 2px !important;
        font-weight: 200;
    }

    .table>thead { text-align: center}

    @media  print {
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
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
            page-break-inside:avoid; page-break-after:always;
            margin-top: 10px;
        }
    }


</style>



<button class="btn btn-primary hidden-print pull-right" onclick="myFunction()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
@php $count=0; @endphp

<div class="col-sm-12">
    @foreach($studentList as $studentProfile)
        @php
            $myAdditionalSubList = array_key_exists($studentProfile->std_id, $additionalArrayList)?$additionalArrayList[$studentProfile->std_id]:[]
        @endphp
    <section class="invoice" style="margin: 0 auto;
                 width: 800px;
                 border: double #ccc;
                 padding: 10px; margin-top: 20px"
    >
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <img src="http://emsv1.com/assets/users/images/sacb-logo-new.png" style="position: absolute" height="60px;" width="60px">
                <div class="heading">
                    <h2 style="font-size: 20px; font-weight: bold">Syed Ahmed College</h2>
                    <p>Sukhanpukur, Gabtali, Bogura</p>

                    <h5 style="font-weight: bold">Exam Attendance Sheet</h5>
                </div>
            </div><!-- /.col -->
        </div>

        <div class="row invoice-info" style="margin-top: 15px; margin-left: 2px; margin-right: 2px; padding:10px; border: 2px solid #efefef;">
            <div class="col-sm-7 invoice-col">

                <table class="fontSize13">
                    <tr>
                        <td width="20%">ID</td>
                        <td>: {{$studentProfile->username}}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>:  {{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}} </td>
                    </tr>
                    <tr>
                        <td>Roll</td>
                        <td>: {{$studentProfile->gr_no}}</td>
                    </tr>

                </table>
            </div><!-- /.col -->

            <div class="col-sm-4 invoice-col studentInformation">
                <table class="fontSize13">
                    <tr>
                        <td>Class</td>
                        <td>: {{$studentProfile->batch()->batch_name}}</td>

                    </tr>
                    <tr>
                        <td>Section</td>
                        <td>: {{$studentProfile->section()->section_name}}</td>
                    </tr>

                    @php $studentInfo = findStudent($studentProfile->std_id) @endphp
                    @php $studentEnroll = $studentInfo->enroll(); @endphp
                    @if($studentEnroll->batch()->get_division())
                        <tr>
                            <td>Group</td>
                            <td colspan="3">:{{$studentEnroll->batch()->get_division()->name}}</td>
                        </tr>
                    @endif
                </table>
            </div><!-- /.col -->
        </div>
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table feeheadTable  table-bordered">
                    <thead>
                    <tr>
                        <th colspan="5" style="text-align: center; font-weight: bold">First Semester Exam 2019</th>
                    </tr>
                    <tr>
                        <th class="heading-section">Date</th>
                        <th class="heading-section" width="30%">Subject</th>
                        <th class="heading-section">Code</th>
                        <th class="heading-section">Student Signature</th>
                        <th class="heading-section">Inv. Sig</th>
                    </tr>

                    </thead>
                    <tbody>

                    @foreach($classSubjectList as $key=>$subject)
{{--                        {{dd($subject)}}--}}
                        @php $subType = $subject['type']; @endphp

                        {{--checking subject type--}}
                        @if($subType==1 || in_array($key, $myAdditionalSubList))
                    <tr>
                        <td> </td>
{{--                        <td>{{dd($subject)}}</td>--}}
                        <td>{{$subject['name']}}</td>
                        <td>{{implode(", ", $subject['code'])}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif

                    @endforeach

                    </tbody>


                </table>
            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>
        @php $count++ @endphp
        @if($count%1==0)
            <div class="breakNow"></div>
            @php $count=0; @endphp
        @endif

    @endforeach

</div>




<script>
    function myFunction() {
        window.print();
    }
</script>