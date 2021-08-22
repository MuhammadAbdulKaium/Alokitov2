@extends('layouts.master')

@section('styles')
    <style>
        .import-table{
            height: 500px;
        }
        .import-table thead{
            position: sticky;
            top: 0;
            background: #0b460b;
        }
        .import-table thead th{
            color: white !important;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-upload"></i> Import Student        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student/default/index">Student</a></li>
                <li class="active">Import Student</li>
            </ul>    </section>
        <section class="content">

            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> Imported Student List</h3>
                </div>
                <div class="box-body">
                    <div id="w1" class="grid-view" >
                        <form id="std_list_import_form" method="POST">
                            @csrf
                        {{--<form action="{{url('/student/import/store/')}}" method="POST">--}}
                            <div class="table-responsive import-table" style="height: 500px">
                            <table id="myTable" class="table table-striped table-bordered display table-responsive" >
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cadet Number</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>BN Name</th>
                                    <th>Gender</th>
                                    <th>Present Address</th>
                                    <th>Permanent Address</th>
                                    <th>Nationality</th>
                                    <th>Birth Place</th>
                                    <th>Status</th>
                                    <th>Identify Mark</th>
                                    <th>Religion</th>
                                    <th>Language</th>
                                    <th>Blood Group</th>
                                    <th>Birth date</th>
                                    <th>Admission Year</th>
                                    <th>Academic Year</th>
                                    <th>Tuition Fees</th>
                                    <th>Academic Level</th>
                                    <th>Class</th>
                                    <th>Group</th>
                                    <th>Form</th>
                                    <th>Batch No</th>
                                    <th>Father</th>
                                    <th>Father Occupation</th>
                                    <th>Father Contact</th>
                                    <th>Mother</th>
                                    <th>Mother Occupation</th>
                                    <th>Mother Contact</th>
                                    <th>Guardian</th>
                                    <th>Relation</th>
                                    <th>Guardian Contact</th>
                                    <th>Hobby</th>
                                    <th>Aim</th>
                                    <th>Dream</th>
                                    <th>Idol</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($data))
                                        @php $count =1; @endphp
                                        @foreach($data[0] as $key => $student)
                                            @if(($student['first_name']==!null))
                                            <tr>
                                                <td class="text-center">{{$count}}</td>
                                                <td class="text-center">
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="username[]" value="{{$student['cadet_number']}}" required/>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="first_name[]" value="{{$student['first_name']}}" required />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="last_name[]" value="{{$student['last_name']}}"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="bn_fullname[]" value="{{$student['bengali_name']}}"/>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="gender[]" value="{{$student['gender']}}" class="form-control"/></td>
                                                <td class="text-center">
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" id="email_{{$count}}" name="present_address[]" value="{{$student['present_address_district_and_division']}}"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="permanent_address[]" value="{{$student['permanent_address_district_and_division']}}"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="nationality[]" value="{{$student['nationality']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="birth_place[]" value="{{$student['birthplace']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="student_type[]" value="{{$student['student_type']}}" class="form-control" />
                                                </td>
                                                <td>
                                                    <input type="text" name="identification_mark[]" value="{{$student['identification_mark']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="religion[]" value="{{$student['religion']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="language[]" value="{{$student['languages']}}" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="blood_group[]" value="{{$student['blood_group']}}" class="form-control"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="dob[]" value="{{$student['date_of_birth_yyyy_mm_dd']}}" class="form-control"/>
                                                </td>
                                                <td>
                                                    <input type="text" name="admission_year[]" value="{{$student['admission_year']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="academic_year[]" value="{{$student['academic_year']}}" class="form-control" required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="tution_fees[]" value="{{$student['tution_fees']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="academic_level[]" value="{{$student['academic_level']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="class[]" value="{{$student['class']}}" class="form-control"/>
                                                </td>
                                                <td>
                                                    <input type="text" name="group_science_humanities[]" value="{{$student['group_science_humanities']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="section[]" value="{{$student['form']}}" class="form-control"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="batch[]" value="{{$student['batch_no']}}" class="form-control"/>
                                                </td>

                                                <td>
                                                    <input type="text" name="father_name[]" value="{{$student['father']}}" class="form-control" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="father_occupation[]" value="{{$student['father_occupation']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="father_mobile[]" value="{{$student['father_mobile']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="mother_name[]" value="{{$student['mother']}}" class="form-control" required >
                                                </td>
                                                <td>
                                                    <input type="text" name="mother_occupation[]" value="{{$student['mother_occupation']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="mother_mobile[]" value="{{$student['mother_mobile']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="guardian_name[]" value="{{$student['guardian_name']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="guardian_relation[]" value="{{$student['relation']}}" class="form-control" />
                                                </td>
                                                <td>
                                                    <input type="text" name="guardian_mobile[]" value="{{$student['guardian_mobile']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="hobby[]" value="{{$student['hobby']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="aim[]" value="{{$student['aim']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="idol[]" value="{{$student['idol']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="dream[]" value="{{$student['dream']}}" class="form-control">
                                                </td>
                                                <?php $count++;?>
                                            </tr>
                                            @endif
                                        @endforeach

                                @endif
                                </tbody>
                            </table>
                            </div>
                            <!--./modal-body-->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info pull-right"><i class="fa fa-upload"></i> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <style>
        tr td input {
            width: 200px !important;
        }
    </style>
@endsection
@section('style')

@endsection
@section('scripts')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            $('form#std_list_import_form').on('submit', function (e) {
                e.preventDefault();

                var dataJson = $(this).serializeArray();
                dataJson = JSON.stringify(objectifyForm(dataJson));

                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '/student/import/store/',
                    data: $('form#std_list_import_form').serialize(),
                    // datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Submitting...');

                    },

                    success: function (data) {
                        waitingDialog.hide();

                        if (data == 420){
                            swal('Error!', "Error Inserting data.", 'error');
                        } else {
                            console.log(data);
                            console.log(data.inlineUser)
                            console.log(data.currentUser)
                            console.log(data.status)
                            if(data.status == 'recordSuccessfull')
                            {
                                swal({
                                    title: " AlL Data Insert Successfully !",
                                    html: true,
                                });
                                window.location.href = "{{ route('manage-student')}}";
                            }
                            if(data.status == 'inlineDuplicate')
                            {
                                var rowNum = ''
                                for (var user in data.inlineUser) {
                                    rowNum += "Duplicate data are ";
                                    rowNum += user;
                                    rowNum += '<br>';

                                }
                                swal({
                                    title: "Inline Duplicate !",
                                    text: '<h3>'+rowNum +'</h3>',
                                    html: true,
                                });

                            }
                            if (data.status == 'duplicate') {
                                var userName = ' '
                                data.currentUser.forEach(val => {
                                    userName +='User Name : ';
                                    userName += val.username ;
                                    userName +=' User :';
                                    userName += val.name ;
                                    userName +='<br>';
                                })
                                swal({
                                    title: " Duplicate Data! Already Exist !!! ",
                                    text: '<h3>'+userName +'</h3>',
                                    html: true,
                                });

                            }
                        }
                    },
                    error:function(data){
                        waitingDialog.hide();
                        console.log(data)
                        alert(JSON.stringify(data));
                    }
                });
            });

            /**
             * This function takes serializeArray output and jquery and make it javascript JSON object
             */
            function objectifyForm(formArray) {//serialize data function
                var returnArray = {};
                for (var i = 0; i < formArray.length; i++){
                    var attr_name = formArray[i]['name'];
                    returnArray[attr_name] = formArray[i]['value'];
                }
                return returnArray;
            }


            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
//
//            $('#myTable').dataTable({
//                "displayLength": 50
//            });

            var std_emails = [];
            var std_count = $("#std_count").val();
            for(var i = 1 ; i<= std_count; i++){
                std_emails[i] = $("#email_"+i).val();
            }
            if(std_count > 0){
                $.ajax({
                    url: "{{ url('/student/check/emails') }}",
                    type: 'POST',
                    cache: false,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "form_data": JSON.stringify(std_emails),
                    }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        //console.log(JSON.stringify(std_emails));

                    },

                    success:function(data){
                        for(var i = 1 ; i<= std_count; i++){
                            if(data[i] == 0){
                                var warning ='<div class="alert-warning"> Email Id Aleardy Exists</div>';
                                $("#email_"+i).after(warning);
                            }
                        }

                    },

                    error:function(){

                    }
                });
            }
            //alert(JSON.stringify(std_emails));
            //alert(std_count);

        });

    </script>
@endsection