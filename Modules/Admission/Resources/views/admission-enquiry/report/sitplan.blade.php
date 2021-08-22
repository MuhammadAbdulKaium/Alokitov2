<html>
<title>Student Report Card</title>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!-- Student Infromation -->
    <style type="text/css">
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font: 12pt "Tahoma";
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .sitplanDesign {
            border: 2px solid #8c8c8c;
            margin: 10px;
            padding: 5px;
        }
        .sitplan{
              width: 50%;
            float: left;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10px auto;
        }

        .subpage {
            padding: 1cm;
            height: 297mm;
            /*border: 5px solid orange;*/
            background-repeat: no-repeat;
            background-size: 210mm 297mm;
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

        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: 210mm;
                height: 297mm;
            }
            .page {
                margin-top: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }

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

        .label {
            font-size: 15px;
            padding: 5px;
            border: 1px solid #000000;
            border-radius: 1px;
            font-weight: 700;
        }

        .row-first {
            background-color: #b0bc9e;
        }

        .row-second {
            background-color: #e5edda;
        }

        .row-third {
            background-color: #5a6c75;
        }

        .text-center {
            text-align: center;
            font-size: 14px
        }

        .clear {
            clear: both;
        }

        .text-bold {
            font-weight: 700;
        }

        .calculation i {
            margin-right: 10px;
        }

        #std-photo {
            float: right;
            width: 20%;
            margin-left: 10px;
        }

        #inst {
            padding-bottom: 20px;
            width: 100%;
        }

        .report_card_table {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .sing {
            width: 25%;
            float: left;
            margin-top: 50px;
            text-align: center;
            line-height: 2px;
            font-size: 12px;
            font-weight: 600;
        }

        .semester {
            font-size: 11px;
            padding: 15px;
            height: 1266px;


        .singnature {
            height: 30px;
            width: 40px;
        }

        .std-info-table {
            font-size: 15px;
            line-height: 17px;
            margin-bottom: 30px;
            text-align: left;
        }

        #std-photo {
            width: 32%;
            float: left;
            margin-top: -10px;
        }


        #inst-logo {
            width: 32%;
            float: left;
            text-align: center;
        }

        #grade-scale {
            width: 32%;
            float: right;
            margin-top: -23px;
        }
        /*width: 24%*/

        .report-comments {
            width: 31%;
            float: left;
        }

        #qr-code {
            width: 20%;
            float: right;
        }
        /*commenting table */

        .table {
            border-collapse: collapse;
            line-height: 40px;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
            padding: 2px;
        }

        .subject {
            text-align: left;
            padding-left: 10px;
            font-weight: 900;
            height: 25px;
            font-size: 13px;
        }

        #header_row {
            line-height: 20px;
        }

    </style>
</head>

<body>

<div class="book">
    <div class="page">
        <div class="subpage">

            <div class="semester">

                <div id="inst" class="text-center clear" style="width: 100%;">
                    <b style="font-size:14px">
                        {{$instituteInfo->institute_name}}
                    </b>
                    <br>
                    <span style="font-size: 16px; font-weight: 500"> {{$instituteInfo->address1}} </span>
                </div>

                <div class="clear" style="width: 100%;">
                    <div id="inst-logo" style="text-align: center">
{{--                        <img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}" style="width:30px;height:30px;">--}}
                        <p style="font-size:12px; text-align: left; line-height: 2px; padding:10px  0px">
                            <b>Class: {{$batchName}}</b></p>
                    </div>

                </div>

                <div class="row">
                    @php $index=0; @endphp
                        @foreach($applicantEnquiry as $key=>$applicant)
                           @php $index++ @endphp
                                <div class="sitplan">
                                    <div class="sitplanDesign">
                                        <p>Name: {{$applicant->name}}</p>
                                        <p>Applicant No: {{$applicant->application_no}}</p>
                                        <p>Class: {{$applicant->batch()->batch_name}}</p>
                                    </div>
                                </div>
{{--                    {{$index}}--}}
                        @if($index==18) @php $index=0 @endphp  <div class="breakNow"></div>   @endif
                        @endforeach
                    </div>
            </div>

        </div>
    </div>

</div>
</body>
<script>
    window.print();
</script>

</html>