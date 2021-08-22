
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
    .heading h4 {
        font-size: 14px;
        font-weight: bold;
        margin: 0px;
    }
    .heading p {
        font-size: 13px;
    }
    .heading h5 {
        font-size: 13px;
        font-weight: bold;
        margin: 0px;
    }
    .fontSize13 {
        font-size: 13px;
    }
    .feeheadTable {
        margin-top: 20px;
    }

    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding: 2px !important;
    }

    .table>thead { text-align: center;background: #808080; color: #fff; font-weight: bold}
    .table>tbody>tr>td {
        font-weight: 600;
    }

    @media print {
        h2{
            font-size: 16px;
            margin-top: 10px;
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

        .breakNow {
            page-break-inside:avoid; page-break-after:always;
            margin-top: 10px;
        }


    }
    @page {
        margin: 6cm 8cm;
    }
    .marginLeft{
        margin-left: 10px;
    }
    .marginRight{
        margin-left: 10px;
    }



</style>

{{--<section class="container">--}}
<button class="btn btn-primary hidden-print pull-right" onclick="myFunction()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
<div class="col-sm-12">
    @php $count=0; @endphp
    @foreach($studentarray as $key=>$value)

        <div class="col-sm-6" style="margin-top: 5px"; >
            <section class="invoice" style="border-style:double; padding: 5px">
                <!-- title row -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="heading">
                            <img height="50xp" width="50px" src="{{URL::to('/assets/users/images',$instituteInfo->logo)}}">
                            <h4>{{$instituteInfo->institute_name}}</h4>
                            <p>{{$instituteInfo->address1}}</p>
                            <h5> Due Amount </h5>
                        </div>
                    </div><!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                    <div class="col-sm-7 invoice-col">

                        <table class="fontSize13">
                            <tr>
                                <td width="20%">ID</td>
                                <td>:{{$studentarray[$key]['std_id']}}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>:  {{$studentarray[$key]['std_name']}} </td>
                            </tr>
                            <tr>
                                <td>Class </td>
                                <td>: {{$studentarray[$key]['class']}}</td>
                            </tr>
                        </table>
                    </div><!-- /.col -->
                    <div class="col-sm-4 invoice-col studentInformation">
                        <table class="fontSize13">
                            <tr>
                                <td>Roll</td>
                                <td>: {{$studentarray[$key]['std_roll']}}</td>
                            </tr>
                            <tr>
                                <td>Section</td>
                                <td>: {{$studentarray[$key]['section']}}</td>
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
                                <td>Payable Amount</td>
                                <td>Waiver Amount</td>
                                <td>Paid Amount</td>
                                <td>Due Amount</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{$studentarray[$key]['payable_amount']}}</td>
                                <td>{{$studentarray[$key]['waiver_amount']}}</td>
                                <td>{{$studentarray[$key]['paid_amount']}}</td>
                                <td>{{$studentarray[$key]['due_amount']}}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div><!-- /.col -->
                </div><!-- /.row -->

            </section>
        </div>
                @php $count++ @endphp
                @if($count%8==0)
                    <div class="breakNow"></div>
            @php $count=0; @endphp
            @endif
            @endforeach
        </div>
        {{--</section>--}}


        <script>
            function myFunction() {
                window.print();
            }
        </script>