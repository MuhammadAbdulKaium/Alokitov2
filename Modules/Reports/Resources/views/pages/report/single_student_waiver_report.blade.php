<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Waiver Report</title>

    <style>

        @page { margin: 100px 0px; }
        .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 100px; text-align: center; }
        .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;text-align: center;}
        .footer .pagenum:before { content: counter(page); }



        .small-section {
            border-top: 2px solid #00a65a;
            padding: 10px;
        }

        .payment{
            padding-top: 10px;
            font-size: 16px;
        }


        body { font: 12px/1.4 Georgia, serif; }
        textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid black; padding: 5px; }
        #tableDesign {
            padding: 20px;
        }
        #tableDesign { clear: both; width: 100%; margin: 30px 0 0 0; border: 1px solid black; }
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
            <p>{{$reportTitle}}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>

<div class="small-section" style="clear: both" >
    <div class="payment"> Student Waiver:</div>
</div>

@if(!empty($studentWaiverProifle))


<table id="tableDesign" class="transactions_table" style="margin-top: 20px;">
        <thead>
        <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Waiver Type</th>
            <th>Amount</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>

            <tr>
                <td>{{$studentWaiverProifle->std_id}}</td>
                <td>{{$studentWaiverProifle->student()->first_name.' '.$studentWaiverProifle->student()->middle_name.' '.$studentWaiverProifle->student()->last_name}}</td>
                <td>
                    @if($studentWaiverProifle->type==1)
                        <span class="label label-info">Percent
                            @else
                                <span class="label label-primary">Amount</span>
                    @endif
                </td>
                <td>
                    {{$studentWaiverProifle->value}}  @if($studentWaiverProifle->type==1) % @else TK. @endif
                <td>{{$studentWaiverProifle->start_date}}</td>
                <td>{{$studentWaiverProifle->end_date}}</td>
                <td>

                    @if($studentWaiverProifle->status==1)
                        <span class="label label-success">Active
                            @else
                                <span class="label label-primary">Deactive</span>
                    @endif

                </td>
            </tr>
        </tbody>
    </table>
    @else
    <table id="tableDesign" class="transactions_table" style="margin-top: 20px;">
        <h3>Student Waiver Not Found</h3>
    </table>

    @endif



</body>
</html>