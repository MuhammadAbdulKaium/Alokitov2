@extends('layouts.master')

@section('content')
    @if(Session::has('message'))
        <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
            style="text-decoration:none" data-dismiss="alert"
            aria-label="close">&times;</a>{{ Session::get('message') }}</p>
    @elseif(Session::has('alert'))
        <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
    @elseif(Session::has('errorMessage'))
        <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
    @endif
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Event Marks</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="">Events</a></li>
                <li>SOP Setup</li>
                <li class="active">Event Marks</li>
            </ul>
        </section>

        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Event Marks Entry </h3>
                </div>
                <div class="box-body table-responsive">
                    <form id="std_search_form">
                        @csrf
    
                        <div class="row"  style="margin-bottom: 50px">
                            <div class="col-sm-3">
                                <select name="eventId" id="" class="form-control select-event" required>
                                    <option value="">--Event*--</option>
                                    @foreach ($events as $event)
                                        <option value="{{$event->id}}">{{$event->event_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="dateTime" id="" class="form-control select-date-time" required>
                                    <option value="">--Date Time*--</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="teamId" id="" class="form-control select-team" required>
                                    <option value="">--Team*--</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
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


{{--Scripts--}}

@section('scripts')
    <script>
        $(document).ready(function(){
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });


            var globalEventId = null;

            function tConvert (time) {
                // Check correct time format and split into components
                time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

                if (time.length > 1) { // If time format correct
                    time = time.slice (1);  // Remove full string match value
                    time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
                    time[0] = +time[0] % 12 || 12; // Adjust hours
                }
                return time.join (''); // return adjusted time or original string
            }

            $('.select-event').change(function () {
                var eventId = $(this).val();
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/event/date-time/from/event') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'eventId': eventId,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        globalEventId = eventId;

                        var txt = '<option value="">--Date Time*--</option>';

                        data.forEach(element => {
                            var date = element.slice(0, 10);
                            var time = tConvert(element.slice(11, 16));
                            txt += '<option value="'+element+'">Date: '+date+', Time: '+time+'</option>';
                        });

                        $('.select-date-time').html(txt);
                        $('.select-team').html('<option value="">--Team*--</option>');
                    }
                });
                // Ajax Request End
            });

            $('.select-date-time').change(function () {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/event/team/from/date-time') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'eventId': globalEventId,
                        'dateTime': $(this).val(),
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        var txt = '<option value="">--Team*--</option>';

                        data.forEach(element => {
                            txt += '<option value="'+element.id+'">'+element.name+'</option>';
                        });

                        $('.select-team').html(txt);
                    }
                });
                // Ajax Request End
            });

            $('form#std_search_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    url: "/event/student-search/for/marks",
                    type: 'POST',
                    cache: false,
                    data: $('form#std_search_form').serialize(),
                    datatype: 'application/json',

                    beforeSend: function() {
                    },

                    success:function(data){
                        $('#std_list_container_row').html(data);
                    },

                    error:function(data){
                    }
                });
            });
        });
    </script>
@endsection