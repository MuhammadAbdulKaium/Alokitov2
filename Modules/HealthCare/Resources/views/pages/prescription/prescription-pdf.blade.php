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
            width: 100%;
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
            width: 40%;
            float: left;
            padding: 0 20px;
        }
        .medical-officer-info{
            float: right;
            text-align: right;
        }
        .sub-header{
            background: #d9edf7;
            padding: 0 10px;
        }
        .sub-header-content{
            width: 33%;
            float: left;
        }
        .content{
            margin: 30px 0;
        }
        .left-content{
            float: left;
            width: 35%;
        }
        .right-content{
            float: left;
            padding-left: 10px;
            border-left: 1px solid #f1f1f1; 
        }
        .prescription-topics{
            min-height: 100px;
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
        <h1 style="text-align: center">Prescription</h1>

        <div class="header clearfix">
            <div class="logo">
                <img src="{{ public_path('assets/users/images/'.$institute->logo) }}" alt="">
                {{-- <img src="{{ asset('assets/users/images/'.$institute->logo) }}" alt=""> --}}
            </div>
            <div class="headline">
                <h3>{{ $institute->institute_name }}</h3>
                <p>{{ $institute->address2 }}</p>
            </div>
            <div class="medical-officer-info">
                <h3>{{ $medicalOfficer->name }}</h3>
                <p>Medical Officer</p>
                <p>{{ $institute->institute_name }}</p>
            </div>
        </div>
        <div class="sub-header clearfix">
            <div class="sub-header-content">
                <p><b>Patient Name:</b> {{ $patient->first_name }} {{ $patient->last_name }}</p>
                <p><b>Age:</b> {{ $patientAge }}</p>
                <p><b>Sex:</b> {{ $patient->gender }}</p>
            </div>
            <div class="sub-header-content">
                <p><b>ID:</b> {{ $patient->id }}</p>
                @if ($userType == 1)
                    <p><b>Title:</b> {{ $patient->title }}</p>
                @elseif($userType == 2)
                    <p><b>Designation:</b> {{ $patient->designation()->name }}</p>
                @endif
            </div>
            <div class="sub-header-content">
                <p><b>Prescription ID:</b> {{ ($prescription)?$prescription->id:"-----" }}</p>
                <p><b>Date:</b> {{ $todayDate->format('d M, Y') }}</p>
                @if ($prescription->follow_up)
                    <p><b>Follow UP Prescription ID:</b> {{ $prescription->follow_up }}</p>
                @endif
            </div>
        </div>
        @php
            $content = json_decode($prescription->content, 1);
        @endphp
        <div class="content clearfix">
            <div class="left-content">
                <div class="prescription-topics">
                    <h4>Clinical History:</h4>
                    <ul class="topic-list">
                        @foreach ($content['clinicalHistories'] as $clinicalHistory)
                            <li>{{ $clinicalHistory }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="prescription-topics">
                    <h4>Physical Examination:</h4>
                    <ul class="topic-list">
                        @foreach ($content['physicalExaminations'] as $physicalExamination)
                            <li>{{ $physicalExamination }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="prescription-topics">
                    <h4>Investigation:</h4>
                    <ul class="topic-list">
                        @foreach ($content['investigations'] as $investigation)
                            <li>{{ $investigation['title'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="right-content">
                <div class="prescription-topics">
                    <h4>Diagnosis:</h4>
                    <ul class="topic-list">
                        @foreach ($content['diagnosis'] as $diagnosis)
                            <li>{{ $diagnosis }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="prescription-topics">
                    <h4>Treatment:</h4>
                    <ul class="topic-list">
                        @foreach ($content['treatments'] as $treatment)
                            <li>
                                {{ $treatment['drugName'] }} (Qty: {{ $treatment['quantity'] }})
                                <div>{{ $treatment['interval'] }} - {{ $treatment['comment'] }} - {{ $treatment['days'] }}days (Till: {{ $treatment['endDate'] }})</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="prescription-topics">
                    <h4>Excuse:</h4>
                    <ul class="topic-list">
                        @foreach ($content['excuses'] as $excuse)
                            <li>{{ $excuse['startDate'] }} - {{ $excuse['endDate'] }} ({{ $excuse['days'] }}days): {{ $excuse['comment'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer clearfix">
            <div>{{ $institute->institute_name }}</div>
            <div>{{ $institute->address1 }}</div>
            <div><b>Website: </b>{{ $institute->website }}</div>
        </div>
    </div>
</body>
</html>