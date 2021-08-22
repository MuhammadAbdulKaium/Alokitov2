@extends('layouts.master')


@section('content')
    <div class="content-wrapper">
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Exam |<small>Seat Plan</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Course Management</li>
                <li class="active">Subject</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Exam Seat Plan </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <label for="">Academic Year</label>
                            <select name="" id="" class="form-control">
                                <option value="">2021</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Semester / Term</label>
                            <select name="" id="" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Exam Category</label>
                            <select name="" id="" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Exam Name</label>
                            <select name="" id="" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Date & Time</label>
                            <input type="text" id="date" class="form-control hasDatepicker date" name="date" maxlength="10"
                                   placeholder="Select Date" aria-required="true" size="10">
                        </div>
                        <div class="col-sm-1" style="margin-top: 23px">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 30px">
                        <div class="col-sm-6 check1-wrap">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="checkbox" value="6" class="check1 class">
                                        <span for="">Class 6</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="checkbox" class="check1 form-a" value="25">
                                        <span for="">Form A(25)</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="checkbox" class="check1 form-b" value="25">
                                        <span for="">Form B(25)</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <span for="">Total: <b class="total" data-class="6">0</b></span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select name="" id="" class="form-control">
                                            <option value="">Subject</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select name="" id="" class="form-control">
                                            <option value="">Criteria</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="checkbox" value="7" class="check1 class">
                                        <span for="">Class 7</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="checkbox" class="check1 form-a" value="25">
                                        <span for="">Form A(25)</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="checkbox" class="check1 form-b" value="25">
                                        <span for="">Form B(25)</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <span for="">Total: <b class="total" data-class="7">0</b></span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select name="" id="" class="form-control">
                                            <option value="">Subject</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select name="" id="" class="form-control">
                                            <option value="">Criteria</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-3">
                                    <label for="">Total: <span class="grand-total">0</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 check2-wrap">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="checkbox" class="check2 check2-seat">
                                        <span>Room 1: Korota (<span class="seat-row">10</span>*<span class="seat-col">3</span>*<span class="stu-per-seat">1</span> = <span class="total-seat">30</span>)</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <span>Per Seat: <b>1</b></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="checkbox">
                                        <span>Next Seat: Blank</span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="checkbox">
                                        <span>No Same Class Adjacent</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="checkbox" class="check2 check2-seat">
                                        <span>Room 1: Korota (<span class="seat-row">10</span>*<span class="seat-col">3</span>*<span class="stu-per-seat">2</span> = <span class="total-seat">60</span>)</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <span>Per Seat: <b>1</b></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="checkbox">
                                        <span>Next Seat: Blank</span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="checkbox">
                                        <span>No Same Class Adjacent</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <label for="" style="display: block; margin-bottom: 20px">Seats Occupied: <b class="check2-total">0</b></label>
                                    <button class="btn btn-success generate-seat">Generate Seats</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Summary </h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Summmary</th>
                                    <th>Invigilator</th>
                                    <th>History</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Room 101 (10*3*2 = 60) ..... Class 7, Class 8, Class 9</td>
                                    <td>
                                        <select name="" id="" class="form-control">
                                            <option value=""></option>
                                        </select>
                                    </td>
                                    <td><button class="btn btn-primary btn-xs"><i class="fa fa-history"></i></button></td>
                                </tr>
                                <tr>
                                    <td>Room 101 (10*3*2 = 60) ..... Class 7, Class 8, Class 9</td>
                                    <td>
                                        <select name="" id="" class="form-control">
                                            <option value=""></option>
                                        </select>
                                    </td>
                                    <td><button class="btn btn-primary btn-xs"><i class="fa fa-history"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1"></div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height: 40px"><i class="fa fa-home"></i> Rooms </h3>
                            <div class="box-tools" style="top: 20px">
                                Total Examinee: 200
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="line-height: 40px"><i class="fa fa-th"></i> Room 101 (10*3*2 = 60) </h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            <h3 class="text-success">1121</h3>
                                            <div>Arman Saleh</div>
                                            <div>Class 7, Form A</div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection



{{-- Scripts --}}

@section('scripts')
    <script>
        $(document).ready(function () {
            // $('#categoryTable').DataTable();
            $('#date').datepicker();
            // Check Box 1
            function check1GrandTotal(){
                var allTotal = $('.check1-wrap').find('.total');
                var grandTotal = 0;
                // console.log(allTotal);
                allTotal.each(function (index){
                    grandTotal += parseInt($(this).text());
                });
                $('.check1-wrap').find('.grand-total').text(grandTotal);
            }
            $('.check1').click(function () {
                var parent = $(this).parent().parent().parent();
                var classDiv = parent.find('.class');
                var formA = parent.find('.form-a');
                var formB = parent.find('.form-b');
                var total = parent.find('.total');
                var formAVal = parseInt((formA.is(':checked')) ? formA.val() : 0);
                var formBVal = parseInt((formB.is(':checked')) ? formB.val() : 0);
                if (classDiv.is(':checked')) {
                    total.text(formAVal + formBVal);
                }else{
                    total.text(0);
                }
                check1GrandTotal();
            })
            // Check Box 2
            // var row = 10;
            // var col = 3;
            // var studentPerSeat = 2;
            // $('.seat-row').text(row);
            // $('.seat-col').text(col);
            // $('.stu-per-seat').text(studentPerSeat);
            // $('.total-seat').text(row*col*studentPerSeat);
            $('.check2').click(function () {
                var allCheck = $('.check2-wrap').find('.check2-seat');
                var total = 0;
                allCheck.each(function (index){
                    if ($(this).is(':checked')) {
                        total += parseInt($(this).next().find('.total-seat').text());
                    }
                });
                $('.check2-total').text(total);
            });
            // Generate Seat
            $('.generate-seat').click(function() {
                var firstWrap = $('.check1-wrap');
                var secondWrap = $('.check2-wrap');
                var totals = firstWrap.find('.total');
                var classes = [];
                totals.each(function (index) {
                    classes.push({
                        name: "Class "+$(this).attr('data-class'),
                        totalStudent: parseInt($(this).text())
                    });
                });
                console.log(classes);
            });
        });
    </script>
@stop