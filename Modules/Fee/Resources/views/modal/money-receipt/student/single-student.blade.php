<html>
<head>
    <title>Student Money Receipt</title>

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
    .heading h2 {
        font-size: 22px;
    }
    p {
        font-size: 12px;
        margin: 0px;
        padding: 0px;
    }
    h2, h5 {
        margin: 0px;
        padding: 0px;
    }

    .fontSize13 {
        font-size: 10px;
    }
    .feeheadTable {
        margin-top: 20px;
    }

    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding: 2px !important;
    }

    .table>thead { text-align: center}

    @media print {
        .heading h2 {
            font-size: 12px;
            margin-top: 10px;
            font-weight: bold;
        }
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
    }


</style>

@php
    $invoiceProfile=$transactionProfile->invoiceProfile();
        $studentProfile=$transactionProfile->invoiceProfile()->studentProfile();
        $studentBatchProfile=$studentProfile->batch();
        $studentSectionProfile=$studentProfile->section();
        $feeHeadProfile=$invoiceProfile->feehead();
        $feeSubheadProfile=$invoiceProfile->subhead();
$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
@endphp
</head>
<body>

{{--<section class="container">--}}
<button class="btn btn-primary hidden-print pull-right" onclick="myFunction()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
<div class="col-sm-12">

    <div class="col-sm-6">
        <section class="invoice" style="border: double #ccc;
                 padding: 10px;">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <img src="{{URL::to('/assets/users/images',$instituteInfo->logo)}}" style="position: absolute" height="40px;" width="40px">

                    <div class="heading">
                        <h2>{{$instituteInfo->institute_name}}</h2>
                        <p>{{$instituteInfo->address1}}</p>
                        <p style="border-radius:10px; border: 1px solid #ccc; font-weight: bold; margin-top: 5px; padding: 0px"> Money Receipt </p>
                    </div>
                </div><!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info" style="margin-top: 10px">
                <div class="col-sm-7 invoice-col">

                    <table class="fontSize13">
                        <tr>
                            <td width="20%">ID</td>
                            <td>: {{$studentProfile->username}}</td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>: {{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
                        </tr>
                        <tr>
                            <td>Class </td>
                            <td>: {{$studentBatchProfile->batch_name}}</td>
                        </tr>
                    </table>
                </div><!-- /.col -->
                <div class="col-sm-4 invoice-col studentInformation">
                    <table class="fontSize13">
                        <tr>
                            <td width="50%">Receipt No</td>
                            <td>: #{{$transactionProfile->id}}</td>
                        </tr>
                        <tr>
                            <td>Roll</td>
                            <td>: 02</td>
                        </tr>
                        <tr>
                            <td>Section</td>
                            <td>: {{$studentSectionProfile->section_name}}</td>
                        </tr>
                    </table>
                </div><!-- /.col -->
            </div><!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table feeheadTable  table-bordered fontSize13">
                        <thead>
                        <tr>
                            <td>SL.</td>
                            <td>Fee Head</td>
                            <td>Sub Head</td>
                            <td width="20%">Total</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{$feeHeadProfile->name}}</td>
                            <td>{{$feeSubheadProfile->name}}</td>
                            <td  style="text-align: right">{{$invoiceProfile->amount}}</td>
                        </tr>
                        {{--<tr>--}}
                        {{--<td colspan="3" style="text-align: right">Attendance Fine</td>--}}
                        {{--<td style="text-align: right">0</td>--}}
                        {{--</tr>--}}

                        {{--<tr>--}}
                        {{--<td colspan="3" style="text-align: right">Waiver </td>--}}
                        {{--<td style="text-align: right">0</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td colspan="3" style="text-align: right">Total Fee Amount</td>--}}
                        {{--<td style="text-align: right">0</td>--}}
                        {{--</tr>--}}
                        @php $invoiceProfile=$transactionProfile->invoiceProfile();
                        $waiver=$invoiceProfile->waiver_amount;
                        $paidAmount=$invoiceProfile->paid_amount;
                        $dueAmount=$invoiceProfile->due_amount;

                        @endphp
                        {{--<tr>--}}
                            {{--<td colspan="3" style="text-align: right">Waiver </td>--}}
                            {{--<td style="text-align: right">{{$waiver}}</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td colspan="3" style="text-align: right">Paid Amount </td>
                            <td style="text-align: right">{{$paidAmount}}</td>
                        </tr>

                        {{--<tr>--}}
                            {{--<td colspan="3" style="text-align: right">Due Amount </td>--}}
                            {{--<td style="text-align: right">{{$dueAmount}}</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td colspan="4"><strong>In word: </strong>{{ucfirst($f->format($paidAmount))}} Taka Only</td>
                        </tr>
                        </tbody>
                    </table>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-5 fontSize13">
                    <p>Received By: School</p>
                    <p>Date: {{date('d-m-Y',strtotime($transactionProfile->payment_date))}}</p>
                </div>
                <div class="col-sm-5 fontSize13 pull-right text-center">
                    <p>.........................</p>
                    <p>Accountant</p>
                </div>
            </div>



            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-xs-12">
                    {{--<a href="" class="btn btn-default"><i class="fa fa-print"></i> Print</a>--}}
                    {{--<button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment</button>--}}
                    {{--<button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>--}}
                </div>
            </div>
        </section>
    </div>

    <div class="col-sm-6">
        <section class="invoice" style="border: double #ccc;
                 padding: 10px;">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <img src="{{URL::to('/assets/users/images',$instituteInfo->logo)}}" style="position: absolute" height="40px;" width="40px">

                    <div class="heading">
                        <h2>{{$instituteInfo->institute_name}}</h2>
                        <p>{{$instituteInfo->address1}}</p>
                        <p style="border-radius:10px; border: 1px solid #ccc; font-weight: bold; margin-top: 5px; padding: 0px"> Money Receipt </p>
                    </div>
                </div><!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info" style="margin-top: 10px">
                <div class="col-sm-7 invoice-col">

                    <table class="fontSize13">
                        <tr>
                            <td width="20%">ID</td>
                            <td>: {{$studentProfile->username}}</td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>: {{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
                        </tr>
                        <tr>
                            <td>Class </td>
                            <td>: {{$studentBatchProfile->batch_name}}</td>
                        </tr>
                    </table>
                </div><!-- /.col -->
                <div class="col-sm-4 invoice-col studentInformation">
                    <table class="fontSize13">
                        <tr>
                            <td width="50%">Receipt No</td>
                            <td>: #{{$transactionProfile->id}}</td>
                        </tr>
                        <tr>
                            <td>Roll</td>
                            <td>: 02</td>
                        </tr>
                        <tr>
                            <td>Section</td>
                            <td>: {{$studentSectionProfile->section_name}}</td>
                        </tr>
                    </table>
                </div><!-- /.col -->
            </div><!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table feeheadTable  table-bordered fontSize13">
                        <thead>
                        <tr>
                            <td>SL.</td>
                            <td>Fee Head</td>
                            <td>Sub Head</td>
                            <td width="20%">Total</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{$feeHeadProfile->name}}</td>
                            <td>{{$feeSubheadProfile->name}}</td>
                            <td  style="text-align: right">{{$invoiceProfile->amount}}</td>
                        </tr>
                        {{--<tr>--}}
                        {{--<td colspan="3" style="text-align: right">Attendance Fine</td>--}}
                        {{--<td style="text-align: right">0</td>--}}
                        {{--</tr>--}}

                        {{--<tr>--}}
                        {{--<td colspan="3" style="text-align: right">Waiver </td>--}}
                        {{--<td style="text-align: right">0</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td colspan="3" style="text-align: right">Total Fee Amount</td>--}}
                        {{--<td style="text-align: right">0</td>--}}
                        {{--</tr>--}}
                        @php $invoiceProfile=$transactionProfile->invoiceProfile();
                        $waiver=$invoiceProfile->waiver_amount;
                        $paidAmount=$invoiceProfile->paid_amount;
                        $dueAmount=$invoiceProfile->due_amount;

                        @endphp
                        {{--<tr>--}}
                            {{--<td colspan="3" style="text-align: right">Waiver </td>--}}
                            {{--<td style="text-align: right">{{$waiver}}</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td colspan="3" style="text-align: right">Paid Amount </td>
                            <td style="text-align: right">{{$paidAmount}}</td>
                        </tr>

                        {{--<tr>--}}
                            {{--<td colspan="3" style="text-align: right">Due Amount </td>--}}
                            {{--<td style="text-align: right">{{$dueAmount}}</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td colspan="4"><strong>In word: </strong>{{ucfirst($f->format($paidAmount))}} Taka Only</td>
                        </tr>
                        </tbody>
                    </table>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-5 fontSize13">
                    <p>Received By: School</p>
                    <p>Date: {{date('d-m-Y',strtotime($transactionProfile->payment_date))}}</p>
                </div>
                <div class="col-sm-5 fontSize13 pull-right text-center">
                    <p>.........................</p>
                    <p>Accountant</p>
                </div>
            </div>




            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-xs-12">
                    {{--<a href="" class="btn btn-default"><i class="fa fa-print"></i> Print</a>--}}
                    {{--<button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment</button>--}}
                    {{--<button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>--}}
                </div>
            </div>
        </section>
    </div>

</div>
{{--</section>--}}


<script>
    function myFunction() {
        window.print();
    }
</script>

</body>
</html>