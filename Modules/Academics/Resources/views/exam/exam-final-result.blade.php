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
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>CN</th>
                                    <th>Name</th>
                                    <th>House</th>
                                    <th>Bangla paper 1 <br>(Class IX)</th>
                                    <th>Bangla paper 2 <br>(CLass IX)</th>
                                    <th>English paper 1<br>(CLass IX)</th>
                                    <th>English paper 2 <br>(CLass IX)</th>
                                    <th>Math<br>(CLass IX)</th>
                                    <th>Chemistry<br>(Class IX)</th>
                                    <th>Biology<br>(Class IX)</th>
                                    <th>Bangladesh & Global<br>(Class IX)</th>
                                    <th>Physical Education<br>(Class IX)</th>
                                    <th>Islam & Moral Education<br>(Class IX)</th>
                                    <th>ICT<br>(Class IX)</th>
                                    <th>Total</th>
                                    <th>%</th>
                                    <th>Position</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>569</td>
                                    <td>Takia </td>
                                    <td>SH</td>
                                    <td>10.0</td>
                                    <td>11.0</td>
                                    <td>10.0</td>
                                    <td>10.0</td>
                                    <td>10.0</td>
                                    <td>10.0</td>
                                    <td>11.0</td>
                                    <td>10.0</td>
                                    <td>10.0</td>
                                    <td>10.0</td>
                                    <td>10.0</td>
                                    <td>156</td>
                                    <td>%</td>
                                    <td>22</td>
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
