<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .clearfix {
            overflow: auto;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .container{
            width: 90%;
            margin: 0 auto;
        }
        img{
            width: 100%;
        }
        .header{
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }
        .logo{
            width: 16%;
            float: left;
            height: 90px;
        }
        .headline{
            float: left;
            padding: 0 20px;
        }
        .info{
            margin: 15px 0;
        }
        .patient-info{
            margin: 15px 0;
            font-size: 17px;
        }
        .patient-info span{
            margin-right: 15px;
        }
        .content{
            margin: 50px 0;
        }
        table#FeesInvoiceTables a {
            color: #fff;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            text-align: left;
            padding: 5px;
            border: 1px solid #ddd;
            font-size: xx-small;
        }

        th {
            background-color: #4CAF50;
            color: white;

        }

        .footer{
            text-align: center;
            border-top: 1px solid #f1f1f1;
            padding: 20px 0;
        }
        img.stamp {
            width: 150px;
            position: absolute;
            top: 9px;
            left: 82px;
        }
        .invoice-type {
            padding: 8px;
            border-radius: 5px;
            background: #4caf50;
            color: #fff;
        }
        .border {
            border: 1px dotted;
            overflow: hidden;
        }
        h3.invoice-number {
            background: #4caf50;
            padding:5px;


            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="">
    <div class=" clearfix" style="padding: 0;margin: 0;width: 100%;">

        <div class="row">
            <div class="row prescription-rows" style="position: relative">
                <div class="col-sm-12" style="text-align:center ;width: 100%">
                    <h3 class="invoice-number text-light-blue">Invoice: {{$generatedFees->inv_id}} </h3>
                </div>

                <div class="col-sm-1 logo">
                    <img src="{{ public_path('/assets/users/images/'.$institute->logo) }}" alt="" style="width:70px;object-fit: cover" height="80px">
                </div>
                <div class="col-sm-6"  style="position: absolute;top: 55px">
                    <h4><b>{{ $institute->institute_name }}</b></h4>
                    <h5 style="margin-top: 0px;">{{ $institute->address2 }}</h5>
                    <br>
                </div>
                <div class="col-sm-5" style="text-align: right;position: absolute;top: 50px;right: 4px;font-size: small">
                    <br><span class="invoice-type" >Student Copy</span> <br>
                    <span>{{$personalInfo->first_name}} {{$personalInfo->last_name}} ({{$personalInfo->email}} ) <br>
                        Roll: {{$personalInfo->gr_no}}</span>
                    <span><strong>Level:</strong> {{$personalInfo->level()->level_name}} <br> <strong>Class: </strong>{{$personalInfo->batch()->batch_name}} <strong>Section: </strong>{{$personalInfo->section()->section_name}}</span>
                </div>
            </div>

            <table  id="FeesInvoiceTables" class="table table-striped table-bordered" style="width: 100%;position:relative;margin-top: 80px">

            <thead>
                <tr>
                    <th><a  data-sort="sub_master_code">Invoice ID</a></th>
                    <th><a  data-sort="sub_master_code">Month Name</a></th>
                    <th><a  data-sort="sub_master_code">Year</a></th>
                    <th><a  data-sort="sub_master_code">Structure Name</a></th>
                    <th><a  data-sort="sub_master_alias">Fees</a></th>
                    <th><a  data-sort="sub_master_alias">Fine</a></th>
                    <th><a  data-sort="sub_master_alias">Fine Type</a></th>
                    <th><a  data-sort="sub_master_alias">Last Date of Payment</a></th>
                    <th><a  data-sort="sub_master_alias">Status</a></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$generatedFees->inv_id}}</td>
                    <td>
                        @foreach($month_list as $key=>$month)
                            @if($key==$generatedFees->month_name)
                                {{$month}}
                            @endif
                        @endforeach
                    </td>
                    <td>{{$generatedFees->year}}</td>
                    <td>{{$generatedFees->structure_name}}</td>
                    <td>{{$generatedFees->fees}}</td>
                    <td>{{$generatedFees->late_fine}}</td>
                    <td>{{$generatedFees->fine_type==1?'Daily':'Fixed'}}</td>
                    <td>{{$generatedFees->payment_last_date}}</td>
                    <td>{{$generatedFees->status==1?'Paid':($generatedFees->status==2?'Partially Paid':'Pending')}}</td>
                </tr>
                </tbody>
            </table>
            <div class="col-md-6" style="position: relative;right: 20px;bottom: 40px">
                @if($generatedFees->status==1)
                    <img src="{{ public_path('/assets/stamp/paid-stamp.png') }}" class="stamp">
                @elseif($generatedFees->status==2)
                    <img src="{{ public_path('/assets/stamp/partially-paid-stamp.png') }}" class="stamp">
                @else
                    <img src="{{ public_path('/assets/stamp/pending-rectangle.png') }}" class="stamp">
                @endif
            </div>
            <div class="col-md-6">
                <table class="table table-bordered" style="table-layout: fixed">
                    <thead>
                    <tr>
                        <th colspan="2" style="border: none;background: white"></th>
                        <th>Head</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($feesHeadDetails as $key=>$head)
                        <tr>
                            <td colspan="2"></td>
                            <td>
                                @isset($key)
                                    @foreach($feesHeads as $feesHead)
                                        @if(($feesHead->id == $key))
                                            {{$feesHead->fees_head}}
                                        @endif
                                    @endforeach
                                @endisset()
                            </td>
                            <td>
                                {{$head}}
                            </td>
                        </tr>

                    @endforeach
                    <tr style="border: none">
                        <td ></td>
                    </tr>
                    </tbody>


                </table>
            </div>
            <div class="border"></div>
        </div>


    </div>
    <div class=" clearfix" style="padding: 0;margin: 0;width: 100%;">

        <div class="row">
            <div class="row prescription-rows" style="position: relative">
                <div class="col-sm-12" style="text-align:center ;width: 100%">
                    <h3 class="invoice-number text-light-blue">Invoice: {{$generatedFees->inv_id}} </h3>
                </div>

                <div class="col-sm-1 logo">
                    <img src="{{ public_path('/assets/users/images/'.$institute->logo) }}" alt="" style="width:70px;object-fit: cover" height="80px">
                </div>
                <div class="col-sm-6"  style="position: absolute;top: 55px">
                    <h4><b>{{ $institute->institute_name }}</b></h4>
                    <h5 style="margin-top: 0px;">{{ $institute->address2 }}</h5>
                    <br>
                </div>
                <div class="col-sm-5" style="text-align: right;position: absolute;top: 50px;right: 4px;font-size: small">
                    <br><span class="invoice-type" >Office Copy</span> <br>
                    <span>{{$personalInfo->first_name}} {{$personalInfo->last_name}} ({{$personalInfo->email}} ) <br>
                        Roll: {{$personalInfo->gr_no}}</span>
                    <span><strong>Level:</strong> {{$personalInfo->level()->level_name}} <br> <strong>Class: </strong>{{$personalInfo->batch()->batch_name}} <strong>Section: </strong>{{$personalInfo->section()->section_name}}</span>
                </div>
            </div>

            <table  id="FeesInvoiceTables" class="table table-striped table-bordered" style="width: 100%;position:relative;margin-top: 80px">

                <thead>
                <tr>
                    <th><a  data-sort="sub_master_code">Invoice ID</a></th>
                    <th><a  data-sort="sub_master_code">Month Name</a></th>
                    <th><a  data-sort="sub_master_code">Year</a></th>
                    <th><a  data-sort="sub_master_code">Structure Name</a></th>
                    <th><a  data-sort="sub_master_alias">Fees</a></th>
                    <th><a  data-sort="sub_master_alias">Fine</a></th>
                    <th><a  data-sort="sub_master_alias">Fine Type</a></th>
                    <th><a  data-sort="sub_master_alias">Last Date of Payment</a></th>
                    <th><a  data-sort="sub_master_alias">Status</a></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$generatedFees->inv_id}}</td>
                    <td>
                        @foreach($month_list as $key=>$month)
                            @if($key==$generatedFees->month_name)
                                {{$month}}
                            @endif
                        @endforeach
                    </td>
                    <td>{{$generatedFees->year}}</td>
                    <td>{{$generatedFees->structure_name}}</td>
                    <td>{{$generatedFees->fees}}</td>
                    <td>{{$generatedFees->late_fine}}</td>
                    <td>{{$generatedFees->fine_type==1?'Daily':'Fixed'}}</td>
                    <td>{{$generatedFees->payment_last_date}}</td>
                    <td>{{$generatedFees->status==1?'Paid':($generatedFees->status==2?'Partially Paid':'Pending')}}</td>
                </tr>
                </tbody>
            </table>
            <div class="col-md-6" style="position: relative;right: 20px;bottom: 40px">
                @if($generatedFees->status==1)
                    <img src="{{ public_path('/assets/stamp/paid-stamp.png') }}" class="stamp">
                @elseif($generatedFees->status==2)
                    <img src="{{ public_path('/assets/stamp/partially-paid-stamp.png') }}" class="stamp">
                @else
                    <img src="{{ public_path('/assets/stamp/pending-rectangle.png') }}" class="stamp">
                @endif
            </div>
            <div class="col-md-6">
                <table class="table table-bordered" style="table-layout: fixed">
                    <thead>
                    <tr>
                        <th colspan="2" style="border: none;background: white"></th>
                        <th>Head</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($feesHeadDetails as $key=>$head)
                        <tr>
                            <td colspan="2"></td>
                            <td>
                                @isset($key)
                                    @foreach($feesHeads as $feesHead)
                                        @if(($feesHead->id == $key))
                                            {{$feesHead->fees_head}}
                                        @endif
                                    @endforeach
                                @endisset()
                            </td>
                            <td>
                                {{$head}}
                            </td>
                        </tr>

                    @endforeach
                    <tr style="border: none">
                        <td ></td>
                    </tr>
                    </tbody>


                </table>
            </div>
            <div class="border"></div>
        </div>


    </div>
    <div class=" clearfix" style="padding: 0;margin: 0;width: 100%;">

        <div class="row">
            <div class="row prescription-rows" style="position: relative">
                <div class="col-sm-12" style="text-align:center ;width: 100%">
                    <h3 class="invoice-number text-light-blue">Invoice: {{$generatedFees->inv_id}} </h3>
                </div>

                <div class="col-sm-1 logo">
                    <img src="{{ public_path('/assets/users/images/'.$institute->logo) }}" alt="" style="width:70px;object-fit: cover" height="80px">
                </div>
                <div class="col-sm-6"  style="position: absolute;top: 55px">
                    <h4><b>{{ $institute->institute_name }}</b></h4>
                    <h5 style="margin-top: 0px;">{{ $institute->address2 }}</h5>
                    <br>
                </div>
                <div class="col-sm-5" style="text-align: right;position: absolute;top: 50px;right: 4px;font-size: small">
                    <br><span class="invoice-type" >Bank Copy</span> <br>
                    <span>{{$personalInfo->first_name}} {{$personalInfo->last_name}} ({{$personalInfo->email}} ) <br>
                        Roll: {{$personalInfo->gr_no}}</span>
                    <span><strong>Level:</strong> {{$personalInfo->level()->level_name}} <br> <strong>Class: </strong>{{$personalInfo->batch()->batch_name}} <strong>Section: </strong>{{$personalInfo->section()->section_name}}</span>
                </div>
            </div>

            <table  id="FeesInvoiceTables" class="table table-striped table-bordered" style="width: 100%;position:relative;margin-top: 80px">

                <thead>
                <tr>
                    <th><a  data-sort="sub_master_code">Invoice ID</a></th>
                    <th><a  data-sort="sub_master_code">Month Name</a></th>
                    <th><a  data-sort="sub_master_code">Year</a></th>
                    <th><a  data-sort="sub_master_code">Structure Name</a></th>
                    <th><a  data-sort="sub_master_alias">Fees</a></th>
                    <th><a  data-sort="sub_master_alias">Fine</a></th>
                    <th><a  data-sort="sub_master_alias">Fine Type</a></th>
                    <th><a  data-sort="sub_master_alias">Last Date of Payment</a></th>
                    <th><a  data-sort="sub_master_alias">Status</a></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$generatedFees->inv_id}}</td>
                    <td>
                        @foreach($month_list as $key=>$month)
                            @if($key==$generatedFees->month_name)
                                {{$month}}
                            @endif
                        @endforeach
                    </td>
                    <td>{{$generatedFees->year}}</td>
                    <td>{{$generatedFees->structure_name}}</td>
                    <td>{{$generatedFees->fees}}</td>
                    <td>{{$generatedFees->late_fine}}</td>
                    <td>{{$generatedFees->fine_type==1?'Daily':'Fixed'}}</td>
                    <td>{{$generatedFees->payment_last_date}}</td>
                    <td>{{$generatedFees->status==1?'Paid':($generatedFees->status==2?'Partially Paid':'Pending')}}</td>
                </tr>
                </tbody>
            </table>
            <div class="col-md-6" style="position: relative;right: 20px;bottom: 40px">
                @if($generatedFees->status==1)
                    <img src="{{ public_path('/assets/stamp/paid-stamp.png') }}" class="stamp">
                @elseif($generatedFees->status==2)
                    <img src="{{ public_path('/assets/stamp/partially-paid-stamp.png') }}" class="stamp">
                @else
                    <img src="{{ public_path('/assets/stamp/pending-rectangle.png') }}" class="stamp">
                @endif
            </div>
            <div class="col-md-6">
                <table class="table table-bordered" style="table-layout: fixed">
                    <thead>
                    <tr>
                        <th colspan="2" style="border: none;background: white"></th>
                        <th>Head</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($feesHeadDetails as $key=>$head)
                        <tr>
                            <td colspan="2"></td>
                            <td>
                                @isset($key)
                                    @foreach($feesHeads as $feesHead)
                                        @if(($feesHead->id == $key))
                                            {{$feesHead->fees_head}}
                                        @endif
                                    @endforeach
                                @endisset()
                            </td>
                            <td>
                                {{$head}}
                            </td>
                        </tr>

                    @endforeach
                    <tr style="border: none">
                        <td ></td>
                    </tr>
                    </tbody>


                </table>
            </div>
            <div class="border"></div>
        </div>


    </div>
</div>

</body>
</html>

