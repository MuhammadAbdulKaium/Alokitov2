<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Waiver Report</title>

    <style>

        @page { margin: 100px 0px; }
        .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 100px; text-align: center; }
        .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;text-align: center;}
        .footer .pagenum:before { content: counter(page); }


        .small-section {
            padding: 10px;
        }

        .payment{
            padding-top: 10px;
            font-size: 16px;
        }

        #batch {
            float: left;
            margin-left: 50px;
            font-size: 14px;
        }

        #section{
            float: right;
            margin-right: 50px;
            font-size: 14px;
        }


        body { font: 12px/1.4 Georgia, serif; }
        textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid black; padding: 5px; }

        #tableDesign { clear: both; width: 100%; margin-left: 10px ; margin-right: 30px; border: 1px solid black; }
        #tableDesign th { background: #eee; }
        #tableDesign textarea { width: 80px; height: 50px; }
        #tableDesign tr.item-row td { border: 0; vertical-align: top; }
        #tableDesign td.description { width: 300px; }
        #tableDesign td.item-name { width: 175px; }
        #tableDesign td.description textarea, #tableDesign td.item-name textarea { width: 100%; }
        #tableDesign td.total-line { border-right: 0; text-align: right; }
        #tableDesign td.total-value { border-left: 0; padding: 10px; }
        #tableDesign td.total-value textarea { height: 20px; background: none; }
        #tableDesign td.balance { background: #eee; }
        #tableDesign td.blank { border: 0; }

        table#mytable,
        table#mytable td
        {
            border: none !important;
            text-align: center;
        }


        .header-section {
            width: 100%;
            position: relative;
            border-bottom: 2px solid #eee;
        }
        .header-section .logo {
            width: 30%;
            float: left;
        }

        .header-section .logo img {
            float: right;
        }

        .header-section .text-section {
            width: 70%;
            float: left;
            text-align: center;
            margin-top: 10px;
        }
        .header-section .text-section p {
            margin-right: 200px;
        }
        p.title {
            font-size: 25px;
            font-weight: bold;
            margin-top: 0px;
        }
        p.address-section {
            font-size: 12px;
            margin-top: -30px;
        }
        .report-title {
            width: 100%;
            margin: 0px;
            padding: 0px;
            text-align: center;
        }
        .report-title p {
            font-size: 13px;
            font-weight: 600;
            padding-top: 5px;

        }



    </style>

</head>
<body>

<div class="header">
    <div class="header-section">
        <div class="logo">
            <img src="{{public_path().'/assets/users/images/'.$instituteProfile->logo}}"  style="width:80px;height:80px">
        </div>
        <div class="text-section">
            <p class="title">{{$instituteProfile->institute_name}}</p><br/><p class="address-section">{{'Address: '.$instituteProfile->address1.',Phone: '.$instituteProfile->phone}}<br/>{{'E-mail: '.$instituteProfile->email.', Website: '.$instituteProfile->website}} </p>
        </div>
        <div class="report-title">
            <p>
                @if($waiver_type==1)
                    <span class="label label-info">General
                        @elseif($waiver_type==2)
                            <span class="label label-primary">Upbritti</span>
                            @elseif($waiver_type==3)
                                <span class="label label-primary">Scholarship</span>
                @endif
            </p>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>

<div class="small-section" style="clear: both" >
    <div class="payment">Total Student: {{$studentWaivers->count()}}</div>
</div>

@if(!empty($studentWaivers))
        <div class="" style="clear: both; padding-bottom: 20px;">
            <div id="batch">
                <h4>Class : {{getBatchName($batch)}} </h4>
            </div>
            @if($section>0)
            <div id="section">
                <h4>Section : {{getSectionName($section)}} </h4>
            </div>
                @endif

        </div>

        <table id="tableDesign" class="transactions_table" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Waiver Type</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>

                </thead>
                <tbody>

                @php

                    $i = 1
                @endphp
                @foreach($studentWaivers as $studentWaiver)

                    <tr>
                        <td>{{$studentWaiver->id}}</td>
                        <td>{{$studentWaiver->student()->first_name.' '.$studentWaiver->student()->middle_name.' '.$studentWaiver->student()->last_name}}</td>

                        <td>
                            @if($studentWaiver->waiver_type==1)
                                <span class="label label-info">General
                        @elseif($studentWaiver->waiver_type==2)
                                        <span class="label label-primary">Upbritti</span>
                       @elseif($studentWaiver->waiver_type==3)
                                     <span class="label label-primary">Scholarship</span>
                            @endif
                        </td>

                        <td>
                            @if($studentWaiver->type==1)
                                <span class="label label-info">Percent
                                    @else
                                        <span class="label label-primary">Amount</span>
                            @endif
                        </td>
                        <td>
                            {{$studentWaiver->value}}  @if($studentWaiver->type==1) % @else TK. @endif
                        <td>{{$studentWaiver->start_date}}</td>
                        <td>{{$studentWaiver->end_date}}</td>
                        <td>

                            @if($studentWaiver->status==1)
                                <span class="label label-success">Active
                                    @else
                                        <span class="label label-primary">Deactive</span>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@else

    <h3>No Result Fount</h3>

@endif



</body>
</html>