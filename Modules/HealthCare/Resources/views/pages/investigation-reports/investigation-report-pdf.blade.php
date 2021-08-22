@php
    $result = null;
    if($investigationReport->result){
        $result = json_decode($investigationReport->result, 1);
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
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
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center">Lab Test Report</h1>

        <div class="header clearfix">
            <div class="logo">
                <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" alt="">
                {{-- <img src="{{ asset('assets/users/images/'.$institute->logo) }}" alt=""> --}}
            </div>
            <div class="headline">
                <h3>{{ $institute->institute_name }}</h3>
                <p>{{ $institute->address2 }}</p>
            </div>
        </div>
        <div class="sub-header clearfix">
            <div class="info clearfix">
                <span><b>Lab Test Id:</b> {{ $investigationReport->id }}, </span>
                <span><b>Prescription Id:</b> {{ $investigationReport->prescription->id }}, </span>
                <span><b>Date:</b> {{ $date }} </span>
            </div>
            <div class="patient-info">
                <span><b>Patient Name:</b> {{ $patient->first_name }} {{ $patient->last_name }}, </span>
                <span><b>Age:</b> {{ $patientAge }}, </span>
                <span><b>Sex:</b> {{ $patient->gender }} </span>
            </div>
        </div>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th colspan="3" style="text-align: center">{{ $investigationReport->investigation->report_type }}</th>
                    </tr>
                    <tr>
                        <th>Test: {{ $investigationReport->investigation->title }}</th>
                        <th>Sample: {{ $investigationReport->investigation->sample }}</th>
                        <th>Lab ID: {{ $investigationReport->investigation->lab_id }}</th>
                    </tr>
                </thead>
            </table>
            @foreach ($reportPattern as $table)
                @php
                    $i = $loop->index;
                @endphp
                <h3>{{ $table['title'] }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Test</th>
                            <th>Result</th>
                            <th>Unit</th>
                            <th>Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table['tests'] as $test)
                            <tr style="font-size: {{ $test['style']['fontSize'] }}; font-weight: {{ $test['style']['fontWeight'] }}; color: {{ $test['style']['fontColor'] }}">
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $test['testName'] }}</td>
                                <td>
                                    @if ($result)
                                        {{ $result[$i][$loop->index] }}    
                                    @else
                                        0   
                                    @endif
                                </td>
                                <td>{{ $test['unit'] }}</td>
                                <td>
                                    @if ($test['rangeType'] == 1)    
                                        {{ $test['fromRange'] }} - {{ $test['toRange'] }}
                                    @elseif($test['rangeType'] == 2)
                                        M: {{ $test['genderRange']['fromRangeMale'] }} - {{ $test['genderRange']['toRangeMale'] }} | 
                                        F: {{ $test['genderRange']['fromRangeFemale'] }} - {{ $test['genderRange']['toRangeFemale'] }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach 
        </div>
        <div class="footer">
            <div>{{ $institute->institute_name }}</div>
            <div>{{ $institute->address1 }}</div>
            <div><b>Website: </b>{{ $institute->website }}</div>
        </div>
    </div>
</body>
</html>