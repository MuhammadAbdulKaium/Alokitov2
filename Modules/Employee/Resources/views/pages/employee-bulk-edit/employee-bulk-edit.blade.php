@extends('layouts.master')
@section('styles')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #000 !important;
        }

        .select-section {
            width: 100% !important;
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
                <i class="fa fa-th-list"></i> Employee |<small>Bulk Edit</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Employee</a></li>
                <li>SOP Setup</li>
                <li>Employee</li>
                <li class="active">Employee Bulk Edit</li>
            </ul>
        </section>
        <section class="content">

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Employee Bulk Edit
                    </h3>
                </div>
                <div class="box-body table-responsive">
                    <form id="std_manage_search_form" method="POST" action="{{ url('/employee/bulk-edit/search') }}"
                        target="_blank">
                        @csrf
                        <input type="hidden" name="search_type" class="search_type" value="search">
                        <div class="row" style="margin-bottom: 50px">
                            <div class="col-sm-2">
                                <select name="category" id="" class="form-control select-category">
                                    <option value="">Category*</option>
                                    <option value="1">Teaching</option>
                                    <option value="0">Non Teaching</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="department" id="" class="form-control select-class ">
                                    <option value="">Select Department*</option>
                                    @foreach ($allDepartment as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="designation" id="selectSection" class="form-control select-section" >
                                    <option value="">Select Designation*</option>
                                    @foreach ($allDesignation as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-sm-3">
                                <select class="form-control select-form " name="selectForm[]" multiple="multiple">
                                    <option value="">__Select__</option>
                                    <option value="userName">User Name</option>
                                    <option value="role">Role</option>
                                    <option value="title">Title</option>
                                    <option value="first_name">First Name</option>
                                    <option value="middle_name">Middle Name</option>
                                    <option value="last_name">Last Name</option>
                                    <option value="employee_no">Employee No</option>
                                    <option value="position_serial">Position Serial</option>
                                    <option value="alias">Alias</option>
                                    <option value="gender">Gender</option>
                                    <option value="dob">Date of Birth</option>
                                    <option value="doj">Date of Joine</option>
                                    <option value="dor">Date of Retirement</option>
                                    <option value="department">Department</option>
                                    <option value="designation">Designation</option>
                                    <option value="category">Category</option>
                                    <option value="email">Email</option>
                                    <option value="phone">Phone</option>
                                    <option value="alt_mobile">Alt Mobile</option>
                                    <option value="religion">Religion</option>
                                    <option value="blood_group">Blood Group</option>
                                    <option value="birth_place">Birth Place</option>
                                    <option value="marital_status">Marital Status</option>
                                    <option value="nationality">Nationality</option>
                                    <option value="experience_year">Experience Year</option>
                                    <option value="experience_month">Experience Month</option>
                                    <option value="present_address">Present Address</option>
                                    <option value="permanent_address">Permanent Address</option>

                                </select>
                            </div>
                            <div class="col-sm-1">
                                <label for="showNull">Show Null</label>
                                <input type="checkbox" name="showNull" id="showNull" style="width: 15px">
                            </div>

                            <div class="col-sm-2">

                                <button class="btn btn-sm btn-primary search-btn" type="button"><i
                                        class="fa fa-search"></i></button>
                                <button class="btn btn-sm btn-success print-btn" type="button"><i
                                        class="fa fa-print"></i>Print</button>
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

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.select-form').select2({
                placeholder: "Select Multi Form"
            });

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

            function searchEmployee() {

                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/employee/bulk-edit/search') }}",
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


            $('.search-btn').click(function() {
                $('.search_type').val('search');
                searchEmployee();
            });
            // employee bulk edit save

            $(document).on('submit', 'form#employee_bulk_edit', function(e) {
                $('.search_type').val('search');
                e.preventDefault();
                $.ajax({
                    url: "/employee/bulk/edit/save",
                    type: 'POST',
                    cache: false,
                    data: $('form#employee_bulk_edit').serialize(),
                    // data: new FormData(this),
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        // waitingDialog.show('Loading...');
                    },

                    success: function(data) {
                        waitingDialog.hide();
                        console.log(data);
                        if (data.errors) {
                            Toast.fire({
                                icon: 'error',
                                title: data.errors
                            });
                        } else {
                            searchEmployee();
                            Toast.fire({
                                icon: 'success',
                                title: 'Employee Bulk Edit successfully!'
                            });
                        }
                    },

                    error: function(data) {
                        waitingDialog.hide();
                        alert(JSON.stringify(data));
                        console.log(data);
                    }
                });
            })
             // print click
             $('.print-btn').click(function(){
                $('.search_type').val("Print");
                $('.print-submit-btn').click();
            });

        })
    </script>
@endsection
