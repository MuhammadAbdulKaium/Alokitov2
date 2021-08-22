<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tutorial Exam Report </title>


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
    </style>
</head>
<body>



<button class="btn btn-primary hidden-print pull-right" onclick="myFunction()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
@php $count=0; @endphp

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
                <div class="heading">
                    <h2 style="font-size: 20px; font-weight: bold">Syed Ahmed College</h2>
                    <p>Sukhanpukur, Gabtoli, Bogra.</p>

                    <h5 style="font-weight: bold">1st Tutorial Examination-2019</h5>
                </div>
            </div>
            <!-- /.col -->
        </div>


        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table feeheadTable  table-bordered">
                    <tr align="center">
                        <td width="20%" rowspan="2">Subject</td>
                        <td rowspan="2">Part</td>
                        <td  colspan="2">Marks</td>
                        <td  rowspan="2">Total Marks</td>
                        <td rowspan="2">Intotal Marks</td>
                        <td rowspan="2">Grade Poit</td>
                        <td rowspan="2">Highest Marks</td>
                        <td rowspan="2">GPA</td>
                        <td rowspan="2">GPA (Without 4th)</td>
                        <td rowspan="2">Total Marks & Position</td>
                    </tr>


                    <tr>
                        <td>MCQ</td>
                        <td>CW</td>
                    </tr>

                    <tr>
                        <td rowspan="2">Bangla</td>
                        <td>1st</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td></td>
                        <td rowspan="10"></td>
                        <td rowspan="10"></td>
                        <td rowspan="10"></td>
                    </tr>

                    <tr>
                        <td>2nd</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td rowspan="2">English</td>
                        <td>1st</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>2nd</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td rowspan="2">English</td>
                        <td>1st</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>2nd</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>ICT</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td rowspan="2">Science</td>
                        <td>1st</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td rowspan="2"></td>
                        <td rowspan="2"></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>2nd</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>



                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>

</div>

<script>
    function myFunction() {
        window.print();
    }
</script>
</body>
</html>