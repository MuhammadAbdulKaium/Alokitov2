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
    }

    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding: 2px !important;
    }

    .table>thead { text-align: center}

    @media print {
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


{{--<section class="container">--}}
<button class="btn btn-primary hidden-print pull-right" onclick="myFunction()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
<div class="col-sm-12">
    <section class="invoice" style="margin: 0 auto;
                 width: 800px;
                 border: double #ccc;
                 padding: 10px;"
    >
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <img src="{{URL::to('/assets/users/images',$instituteInfo->logo)}}" style="position: absolute" height="80px;" width="80px">
                <div class="heading">
                    <h2 style="font-size: 20px; font-weight: bold">{{$instituteInfo->institute_name}}</h2>
                    <p>{{$instituteInfo->address1}}</p>

                    <h5 style="font-weight: bold"> @if($entry[0]->entrytype_id==1)
                            Receipt
                        @elseif($entry[0]->entrytype_id==2)
                            Payment
                        @endif</h5>
                </div>
            </div><!-- /.col -->
        </div>

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table feeheadTable  table-bordered fontSize13">
                    <thead>
                    <tr>
                        <th>Dr/Cr</th>
                        <th>Ledger</th>
                        <th>Dr Amount (৳)</th>
                        <th>Cr Amount (৳)</th>
                        <th>Narration</th>
                    </tr>

                    </thead>
                    <tbody>

                    </tbody>
                    @foreach ($curEntryitems as $row => $entryitem)
                        <tr>
                            <td>
                                @if ($entryitem['dc'] == 'D')
                                    Dr
                                @else
                                    Cr
                                @endif

                            </td>
                            <td>{{$entryitem['ledger_name']}}</td>
                            <td>
                                @if($entryitem['dc'] == 'D')
                                    {{ $entryitem['dr_amount']}}
                                @endif
                            </td>
                            <td>  @if($entryitem['dc'] == 'C')
                                    {{ $entryitem['cr_amount']}}
                                @endif</td>
                            <td>{{$entryitem['narration']}}</td>
                        </tr>
                    @endforeach
                </table>
                <p>Notes: {{$entry[0]->notes}}</p>
            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

</div>
{{--</section>--}}


<script>
    function myFunction() {
        window.print();
    }
</script>