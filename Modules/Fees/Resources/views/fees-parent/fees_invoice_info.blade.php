
@extends('layouts.master')
<!-- page content -->
@section('content')

    <link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
            font-size: 14px;
        }
        #calendar {
            margin: 50px auto;
        }
        .fc-content{
            text-align: center;
        }
        .fc-title{
            cursor: pointer;
        }
    </style>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus-square"></i> View Student Fees Information
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Student</a></li>
                <li><a href="#">Manage Attendance</a></li>
                <li class="active">View Student Invoice List</li>
            </ul>
        </section>
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
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="box box-solid">
                            <form id="std_fees_search_form" method="POST">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="doc_type" value="html">
                                <input type="hidden" name="request_type" value="view">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="student">Select Student:</label>
                                                <div class="col-sm-9">
                                                    <select id="std_id" class="form-control" name="std_id">
                                                        <option value="" selected disabled>-- Select Student --</option>
                                                        @foreach($studentList as $student)
                                                            @php $stdInfo = $student->myStudent(); @endphp
                                                            <option value="{{$stdInfo->id}}" >{{$stdInfo->first_name." ".$stdInfo->middle_name." ".$stdInfo->last_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <button class="btn btn-info" type="submit">Submit</button>
                                        </div>
                                    </div>

                            </form>
                        </div>
                    </div>

                    <div id="feesInvoiceContainer" class="col-md-12"></div>
                </div>
            </div>
        </section>
    </div>

    <!-- global modal -->
    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(function() { // document ready


            // set today in the date picker
            //$('#datepicker').val($.datepicker.formatDate('mm/dd/yy',  new Date(Date.now())));

            // request for section list using batch and section id
            $('form#std_fees_search_form').on('submit', function (e) {
                // prevent default
                e.preventDefault();


                    // ajax request
                    $.ajax({
                        url: "{{ url('/student/parent/fees/show/invoice') }}",
                        type: 'POST',
                        cache: false,
                        data: $('form').serialize(),
                        datatype: 'html',
                        //datatype: 'application/json',

                        beforeSend: function() {
                        },
                        success:function(data){
                            if(data){
                                $('#feesInvoiceContainer').html('');
                                $('#feesInvoiceContainer').append(data);
                            }

                        },
                        error:function(){
                            //console.log(data);
                        },
                    });
                });
            });


    </script>
@endsection
