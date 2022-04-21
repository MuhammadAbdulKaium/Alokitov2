<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
         .page-break {
            page-break-after: always;
        }
          .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        /* header Style Start */
        .header{
            width: 100%;
        
        }
        .header-left{
            width: 30%;
            float: left;
        }
        .student-img img{
            width: 100px;
            height: 100px;
        }
        .tab-1{
            margin-left: 1em;
        }
        .tab-2{
            margin-left: 0.7em;
        }
        .tab-3{
            margin-left: 5.1em;
        }
        .tab-4{
            margin-left: 5.6em;
        }
        .student-info{
            margin-top: 20px;
        }
        .student-info p{
            margin: 5px 0;
        }
        .header-middle{
            width: 50%;
            float: left;
            text-align: center;
            padding-top: 20px;
        }
        .institute-info h2{
            margin: 0;
            padding: 0;
        }
        .institute-info p{
           margin-top: 10px;
            padding: 0;
        }
        .institute-logo img{
            width: 80px;
            height: 80px;
        }
        .institute-report{
            margin-top: 10px;
        }
        .institute-report .headline{
            border: 1px solid black;
            padding: 5px 0;
            border-radius: 20px;
           width: 60%;
           margin: 0 auto;

        }
        .header-right{
            float: right;
            width: 20%;
            padding-top: 10px;
        }
        .grade-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .grade-table table th{
            font-size: 12px;
        }
        .grade-table table td{
            font-size: 10px;
        }
        .grade-table table th,.grade-table table td {
            border: 1px solid #000;
            text-align: center;
            padding: 2px;
        }
        .exam-detail{
            padding-top: 10px;
        }
        .exam-detail p{
            font-size: 12px;
            margin: 3px 0;

        }
        /* header Style End */
        /* main Content Start */
        .content{
            padding-top: 20px;
        }
        .main_table table {
            width: 100%;
            border-collapse: collapse;
        }
        .main_table table thead{
            background-color: #B0BC9E;
        }
        .main_table table th{
            border: 1px solid #000;
            text-align: center;
            padding: 5px;
        }
        .main_table table td{
            border: 1px solid #000;
            text-align: center;
            padding: 2px;
            font-size: 14px;
        }
        .color-red{
            color: red;
        }
        .tabel_footer b{
            margin: 0 10px;
            padding: 5px 0 !important;
        }
        /* main Content End */
        /* signature Style Start */
        .signature{
            width: 100%;
            padding-top: 80px;
            text-align: center;

        }
        .singature-left{
            width: 33.33%;
            float: left;
        }
        .singature-left p{
            border-top: 2px dotted #000;
            width: 50%;
            margin: 10px auto;
        }
        .singature-middle{
            width: 33.33%;
            float: left;
        }
        .singature-middle p{
            border-top: 2px dotted #000;
            width: 50%;
            margin: 10px auto;
        }
        .singature-right{
            width: 33.33%;
            float: right;
        }
        .singature-right p{
            border-top: 2px dotted #000;
            width: 50%;
            margin: 10px auto;
        }
        /* signature Style End */
    </style>
</head>
<body>
        @if (sizeof($studentInfo)>0)
            @foreach ($studentInfo as $student )
                
            <div class="header clearfix">
                <div class="header-left">
                    <div class="student-details">
                        <div class="student-img">
                            <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" alt="logo">
                        </div>
                        @php
                            if($student->Student->singleStudent){
                                $studentFullName = $student->Student->singleStudent->first_name.' '. $student->Student->singleStudent->middle_name.' '. $student->Student->singleStudent->last_name;
                            }else {
                                $studentFullName = " ";
                            }
                            if($student->Student->singleStudent->singleParent){
                                $fatherName = " ";
                                $motherName = " ";
                                foreach ($student->Student->singleStudent->singleParent as $key => $guardian) {
                                    if($guardian->singleGuardian->type == 1){
                                        $fatherName = $guardian->singleGuardian->title.' '.$guardian->singleGuardian->first_name.' '.$guardian->singleGuardian->last_name;
                                    }
                                    if($guardian->singleGuardian->type == 0){
                                        $motherName = $guardian->singleGuardian->title.' '.$guardian->singleGuardian->first_name.' '.$guardian->singleGuardian->last_name;
                                    }
                                  
                                }
                            }
                        @endphp
                        <div class="student-info">
                            <p><b>Name of Student :</b> {{$studentFullName}} </p>
                            <p><b>Father's Name<span class="tab-1">:</span> </b> {{$fatherName}} </p>
                            <p><b>Mother's Name<span class="tab-2">:</span> </b> {{$motherName}} </p>
                            <p><b>Class<span class="tab-3">:</span> </b> {{$student->batch->batch_name}} </p>
                            <p><b>Roll<span class="tab-4">:</span> </b></p>
                        </div>
                    </div>
                </div>
                <div class="header-middle">
                    <div class="institute-info">
                        <h2 class="cadet_Name">{{$institute->institute_name}}</h2>
                        <p>{{ $institute->address1 }} </p>
                    </div>
                    <div class="institute-logo">
                        <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" alt="logo">
                    </div>
                    <div class="institute-report">
                        <h2 class="headline">PROGRESS REPORT</h2>
                    </div>
                </div>
                <div class="header-right">
                    <div class="grade-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Marks (%)</th>
                                    <th>Grade</th>
                                    <th>Point</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>80-100</td>
                                    <td>A+</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td>70-79</td>
                                    <td>A</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td>60-69</td>
                                    <td>A-</td>
                                    <td>3.5</td>
                                </tr>
                                <tr>
                                    <td>50-59</td>
                                    <td>B</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>40-49</td>
                                    <td>C</td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>33-39</td>
                                    <td>D</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>0-32</td>
                                    <td>F</td>
                                    <td>0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="exam-detail">
                        <p><b>Exam :</b> {{$student->examName->exam_name}} </p>
                        <p><b>Duration :</b> 14 Nov, 2019 - 28 Nov, 2019 </p>
                    </div>
                </div>
            </div>
            <div class="content clearfix">
                <div class="main_table ">
    
                    <table>
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Mark</th>
                                <th>Tutorial</th>
                                <th>W/A</th>
                                <th>CW</th>
                                <th>MCQ</th>
                                <th>W/A</th>
                                <th>Total</th>
                                <th>Highest</th>
                                <th>Grade</th>
                                <th>Point</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Bangle 1st</td>
                                <td>100</td>
                                <td><span class="color-red">/120</span></td>
                                <td><span class="color-red">0/120</span></td>
                                <td>45/70</td>
                                <td>18/30</td>
                                <td>50.4/80</td>
                                <td><span class="color-red">50.4</span></td>
                                <td>-</td>
                                <td><span class="color-red">F</span></td>
                                <td><span class="color-red">0</span></td>
                            </tr>
                            <tr>
                                <td colspan="11" class="tabel_footer">
                                   <i>
                                       <b>Total:700,</b>
                                       <b>Obtained: 277.33,</b>
                                       <b>Highest: N/A, </b>
                                       <b>Percent: 39.62%,</b>
                                       <b>Failed in 7 subject</b>
                                   </i>
                                </td>
                            </tr>
                            
                        </tbody>
                        
                    </table>
                </div>
                <div class="signature clearfix">
                    <div class="singature-left">
                        <p>Guardian</p>
                    </div>
                    <div class="singature-middle">
                        <p>Class Teacher</p>
                    </div>
                    <div class="singature-right">
                        <p>{{$institute->institute_name}}</p>
                    </div>
                </div>
            </div>
            <div class="clearfix page-break"></div>
            @endforeach
        @endif

</body>
</html>