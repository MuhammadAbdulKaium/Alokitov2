<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$reportTitle}}</title>
    {{--<style>body { font-family:  'Siyamrupali'; } </style>--}}
    <style>
        /*@font-face {*/
            /*font-family: 'Siyamrupali';*/
            /*!*font-style: normal;*!*/
            /*!*font-weight: normal;*!*/
            /*!*src: url(http://venusitltd.com/fonts/Siyamrupali.ttf) format('truetype');*!*/
        /*}*/
        @page { margin: 100px 0px; }
        .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 100px; text-align: center; }
        .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;text-align: center;}
        .footer .pagenum:before { content: counter(page); }



        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }



        #details {
            clear: both;
            margin-top: 40px;
            margin-left: 20px;
        }

        #client {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0  0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }


        body { font: 11px/1.4 Georgia, serif; }
        textarea { border: 0; font: 11px Georgia, Serif; overflow: hidden; resize: none; }
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid black; padding: 5px; }

        #tableDesign { clear: both; width: 100%; margin-left: 10px; margin-right: 30px;  border: 1px solid black; }
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


        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 4px 0;
            text-align: center;
        }
        span.label {
            background: red;
            color:#FFffff;
            padding: 3px;
            height: 60px;
        }

        .transactions_table {
            font-size: 10px;
        }
        .payment {
            font-size: 12px;
            padding: 10px;
        }
    .paid-icon {
        padding-bottom: 10px;
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
            font-size: 20px;
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

{{--@php--}}
    {{--$std=$invoice->payer();--}}
    {{--$enroll=$std->singleEnroll();--}}
    {{--$fees=$invoice->fees();--}}
{{--@endphp--}}
<div class="header">
    <div class="header-section">
        <div class="logo">
            <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
        </div>
        <div class="text-section">
            <p class="title">{{$instituteInfo->institute_name}}</p><br/><p class="address-section">{{'Address: '.$instituteInfo->address1.',Phone: '.$instituteInfo->phone}}<br/>{{'E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}} </p>
        </div>
        <div class="report-title">
            <p>{{$reportTitle}}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>


    <div id="details" class="clearfix" style="width:100%">

@if($invoiceFines->count())
    <div class="payment">STUDENT FINE LIST</div>
        <table id="tableDesign">
            <thead>
            <tr>
                <th >#</th>
                <th>Student Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Division</th>
                <th>Date</th>
                <th>Fine Amount</th>
            </tr>
            </thead>
            <tbody>

           @php $i=1; $sumFineAmount=0; @endphp
            @foreach($invoiceFines as $fine)
            <tr>
                <td >{{$i++}}</td>
                <td >{{$fine->payer()->title.''.$fine->payer()->first_name.' '.$fine->payer()->middle_name.' '.$fine->payer()->last_name}} </td>
                <td >{{$fine->students()->batch()->batch_name}} </td>
                <td >{{$fine->students()->section()->section_name}} </td>
                <td >@if(!empty($fine->students()->batch()->get_division())) {{$fine->students()->batch()->get_division()->name}}  @endif </td>
                <td >{{date('d-m-Y',strtotime($fine->late_day))}} </td>
                <td >{{$fine->fine_amount}} BDT</td>
            </tr>
                @php $sumFineAmount=$sumFineAmount+$fine->fine_amount;@endphp
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="6" style="text-align:right;">Total Fine</th>
                <th>{{$sumFineAmount}} BDT</th>
            </tr>
        </table>
@endif
    </div>
</main>
<footer>
    Invoice was created on a computer and is valid without the signature and seal.
</footer>
</body>
</html>