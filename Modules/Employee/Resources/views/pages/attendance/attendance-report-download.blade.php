<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Employee Attendance Report</title>
    <style>
        @if($report_type=='pdf')

@page { margin: 100px 0px; }
        .header { position: fixed; left: 0px; top: -100px; right: 0px; height: 100px; text-align: center; }
        .footer { position: fixed; left: 0px; bottom: -50px; right: 0px; height: 50px;text-align: center;}
        .footer .pagenum:before { content: counter(page); }



        body { font: 12px/1.4 Georgia, serif; }
        /*textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }*/
        table { border-collapse: collapse; }
        table td, table th { border: 1px solid #d6d6d6; padding: 5px; line-height: 12px; }

        #tableDesign { clear: both; width: 100%; margin-left: 15px ; margin-right: 30px; border: 1px solid #d6d6d6; margin-top: 10px }
        #tableDesign tr>th { background: #eee;}
        #tableDesign textarea { width: 80px; height: 50px; }
        #tableDesign tr.item-row td { border: 0; vertical-align: top; }

        table#mytable,
        table#mytable td
        {
            border: none !important;
            text-align: center;
        }


        .header-section {
            margin-top: 10px;
            width: 100%;
            position: relative;
            border-bottom: 2px solid #eee;
        }

        .header-section .logo {
            float: left;
            width: 15%;
        }

        .header-section .text-section {
            width: 70%;
            float: left;
            text-align: center;
        }
        /*.header-section .text-section p {*/
        /*margin-right: 200px;*/
        /*}*/
        p.title {
            font-size: 25px;
            font-weight: bold;
            margin-top: 0px;
        }
        p.address-section {
            font-size: 12px;
            margin-top: -30px;
        }
        .header-section. report-title {
            width: 15%;
            margin: 0px;
            padding: 0px;
            text-align: center;
        }
        .report-title p {
            font-size: 13px;
            font-weight: 600;
            padding-top: 5px;

        }
        @endif

    </style>

</head>
<body>

@if($report_type=='pdf')
    <div class="header">
        <div class="header-section">
            <div class="logo">
                <img src="{{public_path().'/assets/users/images/'.$instituteProfile->logo}}"  style="width:80px;height:80px">
            </div>
            <div class="text-section">
                <p class="title">{{$instituteProfile->institute_name}}</p><br/><p class="address-section">{{'Address: '.$instituteProfile->address1.',Phone: '.$instituteProfile->phone}}<br/>{{'E-mail: '.$instituteProfile->email.', Website: '.$instituteProfile->website}} </p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
@endif

<table id="tableDesign" class="table table-striped">
    <thead>
    <tr>
        <th colspan="8" style="text-align: center">Staff Attendance Report ({{date('l  j, F Y', strtotime($date))}}) </th>
    </tr>
    <tr>
        <th>#</th>
        <th>Employee </th>
        <th>Emp ID </th>
        <th>Department </th>
        <th>Designation </th>
        <th>Sign In Time</th>
        <th>Sign Out Time</th>
        <th>Working Hour </th>
    </tr>
    </thead>
    <tbody>
    @if(!isset($empAttAll))
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> No Attendance Data Found!!!</h4>
        </div>
    @else
        @foreach($empAttAll as $index=>$data)
            <tr>
                <td>{{($index+1)}}</td>
                <td>{{$data->first_name.' '.$data->middle_name.' '.$data->last_name}}</td>
                <td>e_{{$data->user_id}}</td>
                <td>{{$data->department}}</td>
                <td>{{$data->designation}}</td>
                <td>{{$data->in_time?(date('h:i:s A',strtotime($data->in_time))): '-'}}</td>
                <td>{{$data->out_time?(date('h:i:s A', strtotime($data->out_time))): '-'}}</td>
                <td>
                    {{--checking in_time and out time--}}
                    @if(($data->in_time) AND ($data->out_time))
                        @php
                            $inTime =  $from_time = new DateTime($data->in_time);
							$outTime =  $from_time = new DateTime($data->out_time);
							// difference checking
							$workingTime = $inTime->diff($outTime)->format('%h hours %i Min');
                        @endphp
                        {{--pring working time--}}
                        {{$workingTime}}
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<!-- /.box-body -->

</body>
</html>

