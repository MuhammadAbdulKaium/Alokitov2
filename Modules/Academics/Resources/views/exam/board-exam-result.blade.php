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
                    <form id="std_manage_search_form">
                        @csrf

                        <div class="row"  style="margin-bottom: 50px">
                            <div class="col-sm-1">
                                <select name="yearId" id="" class="form-control select-year" required>
                                    <option value="">-Year-</option>
                                    @foreach ($academicYears as $academicYear)
                                        @isset($selectedAcademicYear)
                                            <option value="{{$academicYear->id}}" {{($academicYear->id == $selectedAcademicYear->id)?'selected':''}}>{{$academicYear->year_name}}</option>
                                        @else
                                            <option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
                                        @endisset
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <select name="batchId" id="" class="form-control select-class" required>
                                    <option value="">Select Class*</option>
                                    @isset($batches)
                                        @foreach ($batches as $batch)
                                            @isset($selectedBatch)
                                                <option value="{{$batch->id}}" {{($batch->id == $selectedBatch->id)?'selected':''}}>{{$batch->batch_name}}</option>
                                            @else
                                                <option value="{{$batch->id}}">{{$batch->batch_name}}</option>
                                            @endisset
                                        @endforeach
                                    @endisset

                                </select>
                            </div>
                            <div class="col-sm-1">
                                <select name="sectionId" id="" class="form-control select-form">
                                    <option value="">Select Form*</option>
                                    @isset($allSection)
                                        @foreach ($allSection as $section)
                                            <option value="{{$section->id}}" {{($section->id == $selectedSection->id)?'selected':''}}>{{$section->section_name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="boardExamType" id="" class="form-control select-board-exam" required>
                                    <option value="">Select Board Exam*</option>

                                        <option value="PSC">PSC</option>
                                        <option value="JSC">JSC</option>
                                        <option value="SSC">SSC</option>
                                        <option value="HSC">HSC</option>


                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="" id="std_list_container_row">

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