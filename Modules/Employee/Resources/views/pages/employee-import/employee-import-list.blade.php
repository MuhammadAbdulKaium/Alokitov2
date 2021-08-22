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
        .import-table thead th a{
            color: white !important;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-upload"></i> Import Employee </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student/default/index">Employee</a></li>
                <li class="active">Import Employee</li>
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
                    <h3 class="box-title"><i class="fa fa-search"></i> Imported Employee List</h3>
                </div>
                <div class="box-body ">

                    <div id="w1" class="grid-view">
                        <form method="POST" id="std_list_import_form">
                            @csrf
                            {{--<form action="{{url('/student/import/store/')}}" method="POST">--}}
                            <div class="table-responsive import-table">
                            <table id="myTable" class="table table-striped table-bordered display" width="100%">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center"><a data-sort="sub_master_name">FM/BA Employee No</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Employee Type</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">First Name</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Last Name</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Alias</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Gender</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Birth date</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Date of Join</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Department</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Designation</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Position</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Date of Retirement</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Present Address</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Permanent Address</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Last Academic Qualification</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Special Qualification</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">NID No</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Passport No</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Birth Certificate No</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Driving License No</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">TIN No</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Email / Login Id</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Mobile</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Alternative Mobile</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Father's Name</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Mother's Name</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Marital Status</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Spouse Name</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Spouse Occupation</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Spouse Mobile</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Spouse NID</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Spouse Date of Birth</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 1 Name</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 1 Date of Birth</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 1 Gender</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 2 Name</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 2 Date of Birth</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 2 Gender</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 3 Name</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 3 Date of Birth</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 3 Gender</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 4 Name</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 4 Date of Birth</a></th>
                                    <th class="text-center"><a data-sort="sub_master_name">Child 4 Gender</a></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($data))
                                    @php $count =1; @endphp
                                    @foreach($data[0] as $key => $employee)
                                        @if(($employee['first_name']==!null))
                                            <tr>
{{--                                                    @foreach($users as $user)--}}
{{--                                                @if($employee['username']==$user->username)--}}
{{--                                                    class="alert-danger"--}}
{{--                                                @endif--}}
{{--                                            @endforeach>--}}
                                                <td class="text-center">{{$count}}</td>
                                                <td class="text-center">
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="employee_no[]" value="{{$employee['fm_ba_employee_no']}}" required/>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="category[]" value="{{$employee['category_teaching_non_teaching']}}" />
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="first_name[]" value="{{$employee['first_name']}}" required/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="last_name[]" value="{{$employee['last_name']}}"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="alias[]" value="{{$employee['name_alias']}}"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="gender[]" value="{{$employee['gender']}}"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="date_of_birth[]" value="{{$employee['date_of_birth_yyyy_mm_dd']}}"/>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="date_of_joining[]" value="{{$employee['date_of_joining_yyyy_mm_dd']}}"/></td>
                                                <td class="text-center">
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="department[]" value="{{$employee['department']}}" required/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input class="form-control text-center" type="text" name="designation[]" value="{{$employee['designation']}}" required/>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="position_serial[]" value="{{$employee['position_serial']}}"/>
                                                </td>

                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="date_of_retirement[]" value="{{$employee['date_of_retirement_yyyy_mm_dd']}}"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="present_address[]" value="{{$employee['current_address']}}"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="permanent_address[]" value="{{$employee['permanent_address']}}"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="last_academic_qualification[]" value="{{$employee['last_academic_qualification']}}"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="special_qualification[]" value="{{$employee['special_qualification']}}"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control" name="nid_no[]" value="{{$employee['nid_no']}}"/>
                                                </td>
                                                <td>

                                                    <input type="text" name="passport_no[]" value="{{$employee['passport_no']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="birth_certificate_no[]" value="{{$employee['birth_certificate_no']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="driving_license_no[]" value="{{$employee['driving_license_no']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="tin_no[]" value="{{$employee['tin_no']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="email_login_id[]" value="{{$employee['email_login_id']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="phone[]" value="{{$employee['mobile']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="alternative_mobile[]" value="{{$employee['alternative_mobile']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="fathers_name[]" value="{{$employee['fathers_name']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="mothers_name[]" value="{{$employee['mothers_name']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="marital_status[]" value="{{$employee['marital_status']}}" class="form-control">
                                                </td>
                                                <td>

                                                    <input type="text" name="spouse_name[]" value="{{$employee['spouse_name']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="spouse_occupation[]" value="{{$employee['spouse_occupation']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="spouse_mobile[]" value="{{$employee['spouse_mobile']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="spouse_nid[]" value="{{$employee['spouse_nid']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="spouse_date_of_birth[]" value="{{$employee['spouse_date_of_birth_yyyy_mm_dd']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_1_name[]" value="{{$employee['child_1_name']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_1_date_of_birth[]" value="{{$employee['child_1_date_of_birth_yyyy_mm_dd']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_1_gender[]" value="{{$employee['child_1_gender']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_2_name[]" value="{{$employee['child_2_name']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_2_date_of_birth[]" value="{{$employee['child_2_date_of_birth_yyyy_mm_dd']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_2_gender[]" value="{{$employee['child_2_gender']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_3_name[]" value="{{$employee['child_3_name']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_3_date_of_birth[]" value="{{$employee['child_3_date_of_birth_yyyy_mm_dd']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_3_gender[]" value="{{$employee['child_3_gender']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_4_name[]" value="{{$employee['child_4_name']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_4_date_of_birth[]" value="{{$employee['child_4_date_of_birth_yyyy_mm_dd']}}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="child_4_gender[]" value="{{$employee['child_4_gender']}}" class="form-control">
                                                </td>
                                                <?php $count++;?>
                                            </tr>
                                        @endif
                                    @endforeach

                                @endif
                                </tbody>
                            </table>
                            <!--./modal-body-->
                            </div>
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
                    url: '/employee/import/list/check/',
                    {{--data: {--}}
                            {{--    '_token' : '{{csrf_token()}}',--}}
                            {{--    'std_count' : '{{count($data)}}',--}}
                            {{--    'std_list': dataJson--}}
                            {{--},--}}
                    data: $('form#std_list_import_form').serialize(),
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Submitting...');

                    },

                    success: function (data) {

                        waitingDialog.hide();
                        // console.log(data.inlineUser)
                        // console.log(data.currentUser)
                        console.log(data);
                        console.log(data.recordSuccessfull)
                        console.log(data.recordData)
                        if(data.status == 'recordSuccessfull')
                        {
                            swal({
                                title: " AlL Data Insert Successfully !",
                                html: true,
                            });
                            window.location.href = "{{ route('manage-hr')}}";
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
                                userName += 'User Name : ';
                                userName += val.username;
                                userName += ' User :';
                                userName += val.name;
                                userName += '<br>';
                            })
                            swal({
                                title: " Duplicate Data! Already Exist !!! ",
                                text: '<h3>' + userName + '</h3>',
                                html: true,
                            });
                        }

                        if (data.status == 'emailDuplicate') {
                            var userName = ' '
                            data.duplicateEmail.forEach(val => {
                                userName += 'User Email : ';
                                userName += val.email;
                                userName += ' User :';
                                userName += val.name;
                                userName += '<br>';
                            })
                            swal({
                                title: " Duplicate Email! Already Exist !!! ",
                                text: '<h3>' + userName + '</h3>',
                                html: true,
                            });
                        }

                            // $.each(data, function(i, item) {
                        //      alert(i);
                        // });
                        // $.each(data, function( k, v ) {
                        //     alert( "Key: " + k + ", Value: " + v.name );
                        // });

                        {{--console.log(data)--}}

                        {{--window.location.href = "{{ route('manage-hr')}}";--}}
                    },

                    error:function(data){
                        waitingDialog.hide();
                        // alert(JSON.stringify(data));
                        alert("Error Entry !! Please Check again")
                    }
                });
            });
        function test(){
            alert('Test');
        }
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