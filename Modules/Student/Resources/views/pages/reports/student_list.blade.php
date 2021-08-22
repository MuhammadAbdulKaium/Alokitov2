
@if($downloadType=='excel')

    @if($allEnrollments->count()>0)
        <table id="example1" class="table table-striped" style="text-align: center">
            <thead>
            <tr>
                {{--<th>User ID</th>--}}
                <th>Std ID</th>
                <th>GR No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>User Name</th>
                <th>Academic Year</th>
                <th>Course Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Father</th>
                <th>Mother</th>
                <th>Contact</th>
                <th>Address</th>
            </tr>
            </thead>
            <tbody>
            @foreach($allEnrollments as $enroll)

                {{--student gurdians--}}
                @php
                    $stdProfile = $enroll->student();
                    $stdParents = $stdProfile->myGuardians();
                   $present = $stdProfile->user()->singleAddress("STUDENT_PRESENT_ADDRESS");
                @endphp
                <tr>
                    {{--<td>{{$enroll->user_id}}</td>--}}
                    <td>{{$enroll->std_id}}</td>
                    <td>{{$enroll->gr_no}}</td>
                    <td> {{$enroll->first_name." ".$enroll->middle_name." ".$enroll->last_name}}</td>
                    <td>{{$enroll->email}}</td>
                    <td>{{$enroll->user()->username}}</td>
                    <td>{{$enroll->year()->year_name}}</td>
                    <td>{{$enroll->level()->level_name}}</td>
                    <td>{{$enroll->batch()->batch_name}} @if(isset($enroll->batch()->get_division()->name)) - {{$enroll->batch()->get_division()->name}}@endif</td>
                    <td>{{$enroll->section()->section_name}}</td>
                    {{--gurdian Looping here--}}
                    @php $contact=''; $gudArrayList= []; @endphp
                    @if(!empty($stdParents) and ($stdParents->count()>0))
                        @foreach($stdParents as $index=>$parent)
                            {{--single gardian profile list--}}
                            @php $guardian = $parent->guardian(); @endphp
                            {{--checking guardina type--}}
                            @if($guardian->type==0)
                                {{--guardina array list maker--}}
                                @php  $gudArrayList[2] = $guardian->first_name." ".$guardian->last_name; @endphp
                            @elseif($guardian->type==1)
                                {{--guardina array list maker--}}
                                @php  $gudArrayList[1] = $guardian->first_name." ".$guardian->last_name; @endphp
                            @else
                                <td></td>
                            @endif

                            @if($parent->is_emergency == 1)
                                @php $contact=$guardian->mobile @endphp
                            @endif
                        @endforeach

                        @for ($x = 1; $x<3; $x++)
                            <td>{{array_key_exists($x, $gudArrayList)?$gudArrayList[$x]:'-'}}</td>
                        @endfor
                    @else
                        <td></td>
                        <td></td>
                    @endif

                    <td>{{$contact}}</td>
                    @if(!empty($present) and ($present->count()>0))
                        <td>{{$present->address}}</td>
                    @else
                        <td></td>
                    @endif

                </tr>

            @endforeach
            </tbody>
        </table>
    @endif

@else

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Student List</title>
        <style type="text/css">
            table {
                width: 100%;
                font-size: 12px;
                border-collapse: collapse;
                border: 1px solid black;
            }
            table th {
                background: #8c8c8c;
                color: #fff;
            }

            table td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 5px;
            }
            /*.heading {*/
            /*border: none !important;*/
            /*}*/

            @page { margin-top: 150px; margin-bottom: 10px; }

            header {
                position: fixed;
                top: -150px;
                left: 0px;
                right: 0px;
                height: 90px;
            }

            .header-section {
                width: 100%;
                position: relative;
                margin-top: 30px;
            }

            .header-section .text-section {
                width: 100%;
                float: left;
                text-align: center;
            }
            /*.header-section .text-section p {*/
            /*margin-right: 200px;*/
            /*}*/
            p.title {
                font-size: 18px;
                font-weight: bold;
                margin-top: 0px;
            }
            p.address-section {
                font-size: 12px;
                margin-top: -35px;
            }

        </style>
    </head>
    <body>
    <header>
        <div class="header-section">
            {{--<div class="logo">--}}
            {{--<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:60px;height:60px">--}}
            {{--</div>--}}
            <div class="text-section">
                <p class="title">{{$instituteInfo->institute_name}}</p><br/><p class="address-section">Address: {{$instituteInfo->address1}} </p>
                <hr>
            </div>

            <div style="clear: both;"></div>
            <p style="text-align: center">Class Name: <strong>{{$classProfile->batch_name}}, </strong>  Section Name : <strong>{{$sectionProfile->section_name}}</strong></p>
        </div>
    </header>


    @if($allEnrollments->count()>0)
        <table id="example1" class="table table-striped" style="text-align: center;">
            <thead>
            <tr>
                {{--<th>User ID</th>--}}
                <th  width="5%">Photo</th>
                <th  width="6%">GR No.</th>
                <th width="15%">Name</th>
                <th  width="10%">User Name</th>
                {{--<th  width="15%">Email</th>--}}
                <th  width="13%">Father</th>
                <th  width="13%">Mother</th>
                <th  width="8%">Contact</th>
                <th>Address</th>
            </tr>
            </thead>
            <tbody>
            @foreach($allEnrollments as $enroll)

                {{--student gurdians--}}
                @php
                    $stdProfile = $enroll->student();
					$stdParents = $stdProfile->myGuardians();
				   $present = $stdProfile->user()->singleAddress("STUDENT_PRESENT_ADDRESS");
                @endphp
                <tr>
                    {{--<td>{{$enroll->user_id}}sdsdf</td>--}}
                    <td>
                        {{--<img class="img-thumbnail" src="images/st6.jpg" alt="Profile iamge">--}}
                        @if($stdProfile->singelAttachment('PROFILE_PHOTO'))
                            <img width="50px" height="45px" class="img-thumbnail" src="{{asset('/assets/users/images/'.$stdProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                            {{--<img width="50px" height="45px" class="img-thumbnail" src="{{public_path().'/assets/users/images/'.$stdProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}">--}}
                        @else
                            <img width="50px" height="45px" class="img-thumbnail" src="{{asset('/assets/users/images/user-default.png')}}">
                            {{--<img width="50px" height="45px" class="img-thumbnail" src="{{public_path().'/assets/users/images/user-default.png'}}">--}}
                        @endif

                    </td>
                    <td>{{$enroll->gr_no}}</td>
                    <td> {{$enroll->first_name." ".$enroll->middle_name." ".$enroll->last_name}}</td>
                    <td>{{$enroll->username}}</td>
                    {{--        <td>{{$enroll->email}}</td>--}}

                    {{--gurdian Looping here--}}
                    @php $contact=''; $gudArrayList= []; @endphp
                    @if(!empty($stdParents) and ($stdParents->count()>0))
                        @foreach($stdParents as $index=>$parent)
                            {{--single gardian profile list--}}
                            @php $guardian = $parent->guardian(); @endphp
                            {{--checking guardina type--}}
                            @if($guardian->type==0)
                                {{--guardina array list maker--}}
                                @php  $gudArrayList[2] = $guardian->first_name." ".$guardian->last_name; @endphp
                            @elseif($guardian->type==1)
                                {{--guardina array list maker--}}
                                @php  $gudArrayList[1] = $guardian->first_name." ".$guardian->last_name; @endphp
                            @else
                                <td></td>
                            @endif

                            @if($parent->is_emergency == 1)
                                @php $contact=$guardian->mobile @endphp
                            @endif
                        @endforeach

                        @for ($x = 1; $x<3; $x++)
                            <td>{{array_key_exists($x, $gudArrayList)?$gudArrayList[$x]:'-'}}</td>
                        @endfor
                    @else
                        <td></td>
                        <td></td>
                    @endif


                    <td>{{$contact}}</td>
                    @if(!empty($present) and ($present->count()>0))
                        <td>{{$present->address}}</td>
                    @else
                        <td></td>
                    @endif

                </tr>

            @endforeach
            </tbody>
        </table>
    @endif

    </body>
    </html>
@endif