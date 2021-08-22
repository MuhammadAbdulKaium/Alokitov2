@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
@stop


{{-- Content --}}
@section('content')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Cadet Mess Management |<small>Food Menu Schedule</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Cadet Mess Management</a></li>
            <li class="active">Food Menu Schedule</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header">
                        <h4><i class="fa fa-plus-square"></i> Food Menu Schedule</h5>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <form action="{{ url('/mess/print/food-menu-schedule') }}" method="GET">
                                @csrf

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="text" id="fromDate" class="form-control hasDatepicker"
                                            name="fromDate" maxlength="10" placeholder="From Date" aria-required="true"
                                            size="10" required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="text" id="toDate" class="form-control hasDatepicker" name="toDate"
                                            maxlength="10" placeholder="To Date" aria-required="true" size="10" required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success search-by-date-btn">Search</button>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <button class="btn btn-primary">Print</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="time">Time</label>
                                    <input type="time" class="form-control" id="time">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="menuCategory">Menu Category</label>
                                    <select class="form-control" name="" id="menuCategory">
                                        <option value="">--Select Category--</option>
                                        @foreach ($foodMenuCategories as $foodMenuCategory)
                                            <option value="{{ $foodMenuCategory->id }}">{{ $foodMenuCategory->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="menu">Menu</label>
                                    <select class="form-control" name="" id="menu">
                                        <option value="">--Select Menu--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="person">Persons#</label>
                                    <input class="form-control" type="number" name="" id="person" value="0" disabled>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group pt-2">
                                    <button class="btn btn-info" style="margin-top:23px" data-toggle="modal" data-target="#totalPersonsModal"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group pt-2">
                                    <button class="btn btn-success" id="set-schedule-btn" style="margin-top:23px">Set</button>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group pt-2">
                                    <button class="btn btn-primary" style="margin-top:23px" type="button"
                                        class="btn btn-primary" data-toggle="modal"
                                        data-target="#stockRequisition">Inventory Requisition</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 schedule-table-holder">
                
            </div>
        </div>
    </section>
</div>

{{-- All Modals --}}

<div class="modal fade" id="costing" tabindex="-1" role="dialog" aria-labelledby="costing" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title text-danger">Costing</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="box box-solid">
                            <div class="box-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Total Qty</th>
                                            <th>UoM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Porota</td>
                                            <td>2</td>
                                            <td>820</td>
                                            <td>pcs</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="box box-solid">
                            <div class="box-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Inventory Item</th>
                                            <th>Qty Cal.</th>
                                            <th>Qty</th>
                                            <th>UoM</th>
                                            <th>Cost-Rate</th>
                                            <th>Costing</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Flour</td>
                                            <td>410*2*0.05</td>
                                            <td>41</td>
                                            <td>kg</td>
                                            <td class="text-danger">19</td>
                                            <td class="text-primary">779</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" colspan="5">Total Costing</td>
                                            <td class="text-primary">779</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="stockRequisition" tabindex="-1" role="dialog" aria-labelledby="costing" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title" style="background-color: lightgray">Stock Requisition</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="box box-solid">
                            <div class="box-body">
                                <form action="">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        Ref No:
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input class="form-control" type="text" placeholder="RN00004">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        Date:
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="date" class="form-control hasDatepicker"
                                                            name="date" maxlength="10" placeholder="Date"
                                                            aria-required="true" size="10">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        Store:
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="" id="">
                                                            <option value="">select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        Requisition By:
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="" id="">
                                                            <option value="">select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        Due Date:
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="dueDate"
                                                            class="form-control hasDatepicker" name="dueDate"
                                                            maxlength="10" placeholder="Due Date" aria-required="true"
                                                            size="10">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="box box-solid">
                            <div class="box-body">
                                <table class="table table-striped table-bordered table-hover"
                                    style="border: 2px solid gray">
                                    <thead class="bg-success">
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Qty</th>
                                            <th>UoM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Flour</th>
                                            <th>200</th>
                                            <th>kg</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <form action="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                Comments:
                                            </div>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="" id="" cols="30"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-success">List</button>
                                    <button class="btn btn-success">Approve</button>
                                    <button class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="totalPersonsModal" tabindex="-1" role="dialog" aria-labelledby="costing" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title text-danger">Persons</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="box box-solid">
                            <div class="box-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            {{-- <th></th> --}}
                                            <th>Class/Department</th>
                                            <th>Form/Designation</th>
                                            <th>Strength</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="persons-table-row-holder">
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-right" colspan="3">Grand Total:</td>
                                            <td id="persons-table-grand-total">0</td>
                                            <td><span class="persons-table-add-row-btn text-success"><i class="fa fa-plus"></i></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td>
                                                <button class="btn btn-success" id="persons-table-save-btn">Save & Go</button>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- HTML for JS --}}
<table style="display: none">
    <tbody id="persons-table-row">
        <tr>
            {{-- <td>1</td> --}}
            <td>
                <select class="form-control class-department-field">
                    <option value="">--Select--</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}" data-type="1">{{ $batch->batch_name }}</option>
                    @endforeach
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" data-type="2">{{ $department->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select class="form-control form-designation-field">
                    <option value="">--Select--</option>
                </select>
            </td>
            <td>
                <input class="form-control strength-field" type="text" value="0" disabled>
            </td>
            <td>
                <input class="form-control total-persons-field" type="number" id="qty"/>
            </td>
            <td style="width:70px">
                <span class="text-danger persons-table-remove-row-btn"><i class="fa fa-minus"></i></span>
            </td>
        </tr>
    </tbody>
</table>

@stop


{{-- Scripts --}}

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('#fromDate').datepicker();
        $('#toDate').datepicker();
        $('#date').datepicker();
        $('#dueDate').datepicker();

        $(document).on('click', '.all_check', function () {
            if ($(this).is(':checked')) {
                $('.checkbox').prop('checked', true);
            } else {
                $('.checkbox').prop('checked', false);
            }
        });

        jQuery(function () {
            var j = jQuery;
            var addInput = '#qty';
            var n = 1;

            j(addInput).val(n);

            j('.plus').on('click', function () {
                j(addInput).val(++n);
            })

            j('.min').on('click', function () {
                if (n >= 1) {
                    j(addInput).val(--n);
                } else {

                }
            });
        });



        var fromDate = null;
        var toDate = null;
        var personDetails = [];
        var totalPersons = 0;

        function searchTableByDates() {
            if (fromDate && toDate) {
                if (fromDate <= toDate) {
                    // Ajax Request Start
                    $_token = "{{ csrf_token() }}";
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        url: "{{ url('/mess/food-menu-schedule/table') }}",
                        type: 'GET',
                        cache: false,
                        data: {
                            '_token': $_token,
                            'fromDate': fromDate,
                            'toDate': toDate
                        }, //see the _token
                        datatype: 'application/json',
                    
                        beforeSend: function () {},
                    
                        success: function (data) {
                            $('.schedule-table-holder').html(data);
                        }
                    });
                    // Ajax Request End
                }else{
                    swal('Error!', 'To Date can not be less than From Date', 'error');
                }
            }else{
                swal('Error!', 'Fill up the date fields first.', 'error');
            }
        }

        function calculateGrandTotal() {
            var allFields = $('.total-persons-field');
            var total = 0;
            allFields.each((index, value) => {
                if($(value).val()){
                    total += parseInt($(value).val());
                }
            });

            $('#persons-table-grand-total').text(total-1);
        }

        $('.search-by-date-btn').click(function () {
            var parent = $(this).parent().parent().parent();
            fromDate = parent.find('#fromDate').val();
            toDate = parent.find('#toDate').val();

            searchTableByDates();
        });

        $('#menuCategory').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/mess/get/menu/from/category') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'menuCategoryId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Select Menu--</option>';

                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.menu_name+'</option>';
                    });

                    $('#menu').html(txt);
                }
            });
            // Ajax Request End
        });

        $('.persons-table-add-row-btn').click(function () {
            var html = $('#persons-table-row').html();
            $('#persons-table-row-holder').append(html);
        });

        $(document).on('click', '.persons-table-remove-row-btn', function () {
            $(this).parent().parent().remove();
            calculateGrandTotal();
        });

        function getFormDesignations(type, value, parent) {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/mess/get/form-designation/from/class-department') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'batchDepartmentId': value,
                    'type': type,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="" data-type="'+type+'">--All--</option>';

                    data.formDesignations.forEach(element => {
                        if (type == 1) {
                            txt += '<option value="'+element.id+'" data-type="1">'+element.section_name+'</option>';
                        } else if(type == 2){
                            txt += '<option value="'+element.id+'" data-type="2">'+element.name+'</option>';
                        }                        
                    });

                    parent.find('.form-designation-field').html(txt);
                    parent.find('.strength-field').val(data.strength);
                }
            });
            // Ajax Request End
        }

        $(document).on('change', '.class-department-field', function () {
            var type = $(this).find(':selected').data('type');
            var parent = $(this).parent().parent();
            var value = $(this).val();

            getFormDesignations(type, value, parent);
        });

        $(document).on('change', '.form-designation-field', function () {
            var type = $(this).find(':selected').data('type');
            var parent = $(this).parent().parent();
            var value = $(this).val();

            if (value) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/mess/get/strength/from/form-designation') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'formDesignationId': value,
                        'type': type,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        parent.find('.strength-field').val(data);
                    }
                });
                // Ajax Request End
            } else{
                getFormDesignations(type, parent.find('.class-department-field').val(), parent);
            }            
        });

        $('#persons-table-save-btn').click(function () {
            var allRows = $('#persons-table-row-holder').children();
            var allFilledUp = true;
            personDetails = [];
            totalPersons = 0;

            allRows.each((index, value) => {
                var classVal = $(value).find('.class-department-field').val();
                var totalPersonsVal = $(value).find('.total-persons-field').val();

                if (classVal && totalPersonsVal) {
                    totalPersons += parseInt(totalPersonsVal);
                    type = $(value).find('.class-department-field').find(':selected').data('type');

                    if (type == 1) {
                        myObj = {
                            type: 1,
                            class: $(value).find('.class-department-field').val(),
                            form: $(value).find('.form-designation-field').val(),
                            strength: $(value).find('.strength-field').val(),
                            totalPersons: $(value).find('.total-persons-field').val(),
                        }
                    }else if(type == 2){
                        myObj = {
                            type: 2,
                            department: $(value).find('.class-department-field').val(),
                            designation: $(value).find('.form-designation-field').val(),
                            strength: $(value).find('.strength-field').val(),
                            totalPersons: $(value).find('.total-persons-field').val(),
                        }
                    }else{
                        myObj = {};
                        allFilledUp = false;
                    }

                    personDetails.push(myObj);
                } else{
                    allFilledUp = false;
                }                
            });

            if (allFilledUp) {
                $('#person').val(totalPersons);
                $('#totalPersonsModal').modal('hide');
            } else{
                swal('Error!', 'Fill up all the Class/Department and Total fields first', 'error');
            }
        });

        $('#set-schedule-btn').click(function () {
            var dates = $("input[name='dates[]']").map(function(){
                if ($(this).is(':checked')) {
                    return $(this).val();
                }
            }).get();

            var slots = $("input[name='slots[]']").map(function(){
                if ($(this).is(':checked')) {
                    return $(this).val();
                }
            }).get();

            var datas = {
                _token: "{{ csrf_token() }}",
                dates: dates,
                slots: slots,
                time: $('#time').val(),
                menuCategory: $('#menuCategory').val(),
                menu: $('#menu').val(),
                persons: $('#person').val(),
                personDetails: JSON.stringify(personDetails),
            }

            if (datas.time && datas.menuCategory && datas.menu && datas.persons) {
                if (dates.length > 0 && slots.length > 0) {
                    // Ajax Request Start
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        url: "{{ url('/mess/save/food-menu/schedule') }}",
                        type: 'GET',
                        cache: false,
                        data: datas,
                        datatype: 'application/json',
                    
                        beforeSend: function () {},
                    
                        success: function (data) {
                            if (data == 1) {
                                searchTableByDates();
                                personDetails = [];
                                totalPersons = 0;
                                $('#persons-table-row-holder').empty();
                                $('#time').val(''),
                                $('#menuCategory').val(''),
                                $('#menu').val(''),
                                $('#person').val('0'),

                                swal('Success!', 'Schedule saved successfully.', 'success');
                            } else{
                                swal('Error!', 'Error saving schedule', 'error');
                            }
                        }
                    });
                    // Ajax Request End
                } else {
                    swal('Error!', 'Choose at least 1 date and slot first', 'error');
                }
            } else{
                swal('Error!', 'Fill up all the fields first', 'error');
            }
        });

        $(document).on('input', '.total-persons-field', function() {
            calculateGrandTotal();
        });
    });
</script>
@stop