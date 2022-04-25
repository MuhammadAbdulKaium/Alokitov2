@extends('layouts.master')
@section('css')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #000 !important;
        }

        .dnone {
            display: none;
        }

    </style>

@endsection

@section('content')
    <div class="content-wrapper">
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Student |<small>Profile Edit</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{ url('/student/manage') }}">Student</a></li>
                <li>SOP Setup</li>
                <li>Student</li>
                <li class="active">Student Profile Edit</li>
            </ul>
        </section>
        <section class="content">
            @if (Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                        style="text-decoration:none" data-dismiss="alert"
                        aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @elseif(Session::has('alert'))
                <p class="alert alert-warning alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                        style="text-decoration:none" data-dismiss="alert"
                        aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
            @elseif(Session::has('errorMessage'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                        style="text-decoration:none" data-dismiss="alert"
                        aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
            @endif
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Student Bulk Edit
                    </h3>
                </div>
                <div class="box-body table-responsive">
                    <form id="std_manage_search_form" method="POST" action="{{ url('/student/cadet/bulk/search') }}"
                        target="_blank">
                        @csrf
                        <input type="hidden" name="search_type" class="search_type" value="search">
                        <div class="row" style="margin-bottom: 50px">

                            <div class="col-sm-2">
                                <label for="">Class*</label>
                                <select name="classId" id="" class="form-control select-class" >
                                    <option value="">Select Class</option>
                                    @foreach ($batches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->batch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="">Section*</label>
                                <select name="sectionId" id="" class="form-control select-section">
                                    <option value="">Select Section*</option>

                                </select>
                            </div>


                            <div class="col-sm-4 ">
                                <label for="">Search Multiple Field</label>
                                <select class="form-control select-form js-example-basic-multiple" name="selectForm[]"
                                    multiple="multiple" style="color: #000;">
                                    <option value="">__Select__</option>
                                    <option value="CadetNumber">Student Number</option>
                                    <option value="FirstName">First Name</option>
                                    <option value="LastName">Last Name</option>
                                    <option value="NickName">Middle Name</option>
                                    <option value="BengaliName">Bengali Name</option>
                                    <option value="Gender">Gender</option>
                                    <option value="DateofBirth">Date of Birth</option>
                                    <option value="BirthPlace">Birth Place</option>
                                    <option value="Religion">Religion</option>
                                    <option value="BloodGroup">Blood Group</option>
                                    <option value="MeritPosition">Merit Position</option>
                                    <option value="PresentAddress">Present Address</option>
                                    <option value="PermanentAddress">Permanent Address</option>
                                    <option value="Nationality">Nationality</option>
                                    <option value="Language">Language</option>
                                    <option value="IdentificationMarks">Identification Marks</option>
                                    <option value="Hobby">Hobby</option>
                                    <option value="Aim">Aim</option>
                                    <option value="Dream">Dream</option>
                                    <option value="Idol">Idol</option>
                                    <option value="FatherName">Father's Name</option>
                                    <option value="FatherOccupation">Father's Occupation</option>
                                    <option value="FatherContact">Father's Contact</option>
                                    <option value="FatherEmail">Father's Email</option>
                                    <option value="MotherName">Mother's Name</option>
                                    <option value="MotherOccupation">Mother's Occupation</option>
                                    <option value="MotherContact">Mother's Contact</option>
                                    <option value="MotherEmail">Mother's Email</option>
                                    <option value="admissionYear">Admission Year</option>
                                    <option value="academicYear">Academic Year</option>
                                    <option value="academicLevel">Academic Level</option>
                                    <option value="batch">Batch</option>
                                    <option value="section">Section</option>
                                </select>
                            </div>
                            <div class="col-sm-1" style="margin-top: 25px;">
                                <label for="showNull">Show Null</label>
                                <input type="checkbox" name="showNull" id="showNull" style="width: 15px">
                            </div>
                            <div class="col-sm-1" style="margin-top: 25px;">
                                <button class="btn btn-sm btn-primary search-btn" type="button"><i
                                        class="fa fa-search"></i></button>

                            </div>
                            <div class="col-sm-1" style="margin-top: 25px;">
                                <button class="btn btn-sm btn-success print-btn" type="button"><i class="fa fa-print"></i>Print</button>
                                <button class="print-submit-btn" type="submit" style="display: none"></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="std_list_container_row">

                </div>
            </div>
        </section>
    </div>
@endsection



{{-- Scripts --}}
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });

            // multiSelect 
            $('.js-example-basic-multiple').select2();



            $('.select-class').change(function() {

                // Ajax Request Start
                $_token = "{{ csrf_token() }}";

                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/student/cadet/search-section') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'batch': $(this).val()
                    }, //see the _token
                    datatype: 'application/json',

                    beforeSend: function() {},

                    success: function(data) {
                        var txt = '<option value="">Select Section*</option>';
                        data.forEach(element => {
                            txt += '<option value="' + element.id + '">' + element
                                .section_name + '</option>';
                        });

                        $('.select-section').empty();
                        $('.select-section').append(txt);


                    }
                });
                // Ajax Request End
            });


            var selectForm = [];
            function searchStudents() {
                // var yearId = $('.select-year').val();
                var classId = $('.select-class').val();
                var sectionId = $('.select-section').val();
                selectForm = $('.select-form').val();

                // console.log(selectForm);
                if ($("#showNull").is(":checked")) {

                    $_token = "{{ csrf_token() }}";
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        url: "{{ url('/student/cadet/bulk/search') }}",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_manage_search_form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success: function(data) {
                            console.log(data);
                            // hide waiting dialog
                            waitingDialog.hide();
                            var std_list_container_row = $('#std_list_container_row');
                            std_list_container_row.html('');
                            std_list_container_row.append(data);


                        },

                        error: function(data) {
                            // hide waiting dialog
                            waitingDialog.hide();

                            alert(JSON.stringify(data));
                        }
                    });
                    // if (!newSelectForm.length > 0) {
                    //     Swal.fire('Error!', 'Select A.Y or Ad.Y or A.l or Batch or Section Multiple Field!', 'error');
                    // } else {
                    // }

                } else {
                    $_token = "{{ csrf_token() }}";
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        url: "{{ url('/student/cadet/bulk/search') }}",
                        type: 'Post',
                        cache: false,
                        data: $('form#std_manage_search_form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success: function(data) {
                            console.log(data);
                            // hide waiting dialog
                            waitingDialog.hide();
                            var std_list_container_row = $('#std_list_container_row');
                            std_list_container_row.html('');
                            std_list_container_row.append(data);

                        },

                        error: function(data) {
                            // hide waiting dialog
                            waitingDialog.hide();

                            alert(JSON.stringify(data));
                        }
                    });
                }


            }


            $('.search-btn').click(function() {
                $('.search_type').val('search');
                searchStudents();
            });
            // bulk Edit Save
            $(document).on('submit', 'form#std_cadet_bulk_edit', function(e) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                $('.search_type').val('search');
                e.preventDefault();
                var first_name = true;
                var language = true;

                $('input[name^=upload]').each(function() {
                    if ($(this).is(':checked')) {
                        var tr = $(this).parent().parent();
                        var languageVal = tr.find('input[name^=language]').val();
                        if (!languageVal && selectForm.indexOf("Language") !== -1) {
                            language = false;
                        }
                        var nameVal = tr.find('input[name^=first_name]').val();
                        if (!nameVal && selectForm.indexOf("FirstName") !== -1) {
                            first_name = false;
                        }
                    }
                })
              
                console.log(language);
                // ajax request
                if (!first_name) {
                    Toast.fire({
                        icon: "error",
                        title: "First Name Field is required"
                    });
                } else if (!language) {
                    Toast.fire({
                        icon: "error",
                        title: "Language Field is required"
                    });
                } else {
                    $.ajax({
                        url: "/student/cadet/bulk/edit/save",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_cadet_bulk_edit').serialize(),
                        // data: new FormData(this),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            // waitingDialog.show('Loading...');
                        },

                        success: function(data) {
                            // console.log(data);
                            waitingDialog.hide();
                            console.log(data);
                            if (data.errors) {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.errors
                                });
                            } else {
                                searchStudents();

                                Toast.fire({
                                    icon: 'success',
                                    title: 'Student Bulk Edit successfully!'
                                });
                            }


                        },

                        error: function(data) {
                            waitingDialog.hide();
                            alert(JSON.stringify(data));
                            console.log(data);
                        }
                    });
                }

            });

            // print click
            $('.print-btn').click(function(){
                $('.search_type').val("Print");
                $('.print-submit-btn').click();
            });
           

        });
    </script>
@stop
