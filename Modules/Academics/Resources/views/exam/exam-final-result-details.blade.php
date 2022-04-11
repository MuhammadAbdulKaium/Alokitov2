@extends('layouts.master')


@section('content')
    <div class="content-wrapper">
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Exam |<small> Mark Entry</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li>SOP Setup</li>
                <li>Exam</li>
                <li class="active">Board Exam Mark Entry</li>
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
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i>Board Exam Mark Entry </h3>
                </div>
                <div class="box-body table-responsive">

                    <div class="" id="std_list_container_row">
                            <table class="table table-bordered" style="vertical-align: middle">
                                <thead>
                                <tr>
                                    <th rowspan="3">CN</th>
                                    <th rowspan="3">Name</th>
                                    <th rowspan="3">House</th>
                                    <th rowspan="1" colspan="3">Bangla paper 1 </th>
                                    <th rowspan="1" colspan="4">Bangla paper 2 </th>
                                    <th rowspan="3">GP</th>
                                    <th rowspan="1" colspan="3">English paper 1</th>
                                    <th rowspan="1" colspan="4">English paper 2</th>
                                    <th rowspan="3">GP</th>
                                    <th rowspan="1" colspan="4">Math</th>
                                    <th rowspan="3">GP</th>
                                    <th rowspan="1" colspan="5">Chemistry</th>
                                    <th rowspan="3">GP</th>
                                    <th rowspan="1" colspan="5">Biology</th>
                                    <th rowspan="3">GP</th>
                                    <th rowspan="1" colspan="5">Bangladesh & Global</th>
                                    <th rowspan="3">GP</th>
                                    <th rowspan="1" colspan="5">Physical Education</th>
                                    <th rowspan="3">GP</th>
                                    <th rowspan="1" colspan="5">Islam & Moral Education</th>
                                    <th rowspan="3">GP</th>
                                    <th rowspan="1" colspan="5">ICT </th>
                                    <th rowspan="3">GP</th>
                                    <th rowspan="3">Total</th>
                                    <th rowspan="3">%</th>
                                    <th rowspan="3" colspan="2">GP</th>
                                    <th rowspan="3">Grand Position</th>
                                </tr>
                                <tr>
                                    <th rowspan="1">F.Avg</th>
                                    <th rowspan="1">M/O</th>
                                    <th rowspan="1">T/E/C</th>
                                    <th rowspan="1">F.Avg</th>
                                    <th rowspan="1">M/O</th>
                                    <th rowspan="1">T/E/C</th>
                                    <th rowspan="1">Total</th>

                                    <th rowspan="1">F.Avg</th>
                                    <th rowspan="1">M/O</th>
                                    <th rowspan="1">T/E/C</th>
                                    <th rowspan="1">F.Avg</th>
                                    <th rowspan="1">M/O</th>
                                    <th rowspan="1">T/E/C</th>
                                    <th rowspan="1">Total</th>

                                    <th rowspan="1">F.Avg</th>
                                    <th rowspan="1">M/O</th>
                                    <th rowspan="1">T/E/C</th>
                                    <th rowspan="1">Total</th>

                                @for($i=0;$i<6;$i++)
                                    <th rowspan="1">F.Avg</th>
                                    <th rowspan="1">M/O</th>
                                    <th rowspan="1">T/E/C</th>
                                    <th rowspan="1">P</th>
                                    <th rowspan="1">Total</th>
                                    @endfor
                                </tr>
                                <tr>
                                    <th rowspan="1">20</th>
                                    <th rowspan="1">30</th>
                                    <th rowspan="1">70</th>
                                    <th rowspan="1">20</th>
                                    <th rowspan="1">30</th>
                                    <th rowspan="1">70</th>
                                    <th rowspan="1">200</th>

                                    <th rowspan="1">20</th>
                                    <th rowspan="1">30</th>
                                    <th rowspan="1">70</th>
                                    <th rowspan="1">20</th>
                                    <th rowspan="1">30</th>
                                    <th rowspan="1">70</th>
                                    <th rowspan="1">200</th>

                                    <th rowspan="1">20</th>
                                    <th rowspan="1">30</th>
                                    <th rowspan="1">70</th>
                                    <th rowspan="1">100</th>

                                    @for($i=0;$i<6;$i++)
                                        <th rowspan="1">20</th>
                                        <th rowspan="1">25</th>
                                        <th rowspan="1">50</th>
                                        <th rowspan="1">25</th>
                                        <th rowspan="1">100</th>
                                    @endfor

                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>569</td>
                                    <td>Takia</td>
                                    <td>SH</td>

                                    <td>19</td>
                                    <td>29</td>
                                    <td>69</td>
                                    <td>19</td>
                                    <td>29</td>
                                    <td>69</td>
                                    <td>194</td>
                                    <td>A+</td>

                                    <td>19</td>
                                    <td>29</td>
                                    <td>69</td>
                                    <td>19</td>
                                    <td>29</td>
                                    <td>69</td>
                                    <td>194</td>
                                    <td>A+</td>

                                    <td>19</td>
                                    <td>29</td>
                                    <td>68</td>
                                    <td>96</td>
                                    <td>A+</td>


                                    @for($i=0;$i<6;$i++)
                                        <th rowspan="1">18</th>
                                        <th rowspan="1">23</th>
                                        <th rowspan="1">46</th>
                                        <th rowspan="1">25</th>
                                        <th rowspan="1">84.5</th>
                                        <th rowspan="1">A+</th>
                                    @endfor
                                <!-- Total value -->
                                    <td>1190</td>
                                    <!-- Total perchantage -->
                                    <td>90</td>
                                    <!-- Gpa Score & Gpa -->
                                    <td>5.0</td>
                                    <td>A+</td>
                                    <!-- Grand Position  -->
                                    <td>2</td>
                                </tr>
                                </tbody>
                            </table>
                    </div>

                </div>
            </div>

            <div class="row">

            </div>
        </section>
    </div>
@endsection



{{-- Scripts --}}
@section('scripts')
    <script>
        $(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });

            $('.select-year').change(function () {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/academics/exam/board-exam-search-class') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'yearId': $(this).val()
                    }, //see the _token
                    datatype: 'application/json',

                    beforeSend: function () {},

                    success: function (data) {
                        var txt = '<option value="">Select Class*</option>';
                        data.forEach(element => {
                            txt += '<option value="'+element.id+'">'+element.batch_name+'</option>';
                        });

                        $('.select-class').empty();
                        $('.select-class').append(txt);
                        $('.select-form').empty();
                        $('.select-form').append('<option value="">Select Form*</option>');
                    }
                });
                // Ajax Request End
            });





            $('.select-class').change(function () {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/academics/exam/search-forms') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'batch': $(this).val()
                    }, //see the _token
                    datatype: 'application/json',

                    beforeSend: function () {},

                    success: function (data) {
                        var txt = '<option value="">Select Form*</option>';
                        data.forEach(element => {
                            txt += '<option value="'+element.id+'">'+element.section_name+'</option>';
                        });

                        $('.select-form').empty();
                        $('.select-form').append(txt);

                    }
                });
                // Ajax Request End
            });
            // Search Schedules
            $('.search-schedule-button').click(function (){
                searchSchedules('search');
            });

            // View Schedules
            $('.view-schedule-button').click(function () {
                searchSchedules('view');
            });


            // request for parent list using batch section id
            $('form#std_manage_search_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({

                    url: "/academics/exam/board-exam-result/search-students",
                    type: 'POST',
                    cache: false,
                    data: $('form#std_manage_search_form').serialize(),
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        console.log(data);
                        // hide waiting dialog
                        waitingDialog.hide();
                        // checking
                        if(data.status=='success'){
                            console.log(data.all);
                            var std_list_container_row = $('#std_list_container_row');
                            std_list_container_row.html('');
                            std_list_container_row.append(data.html);
                        }else{
//                            alert(data.msg)
                        }
                    },

                    error:function(data){
                        waitingDialog.hide();

                        alert(JSON.stringify(data));
                    }
                });
            });
        });
    </script>
@stop
