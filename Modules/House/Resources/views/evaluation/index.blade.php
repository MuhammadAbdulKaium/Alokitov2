@extends('layouts.master')

@section('styles')
    <style>
        .evaluation-table, .evaluation-table thead tr th, .evaluation-table tbody tr th{
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>House</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">House</a></li>
            <li>SOP Setup</li>
            <li class="active">Evaluation</li>
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
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Students Evaluation </h3>
                        <div class="box-tools">
                            <a class="btn btn-primary" href="{{url('/house/weightage-config')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-edit"></i> Weightage Config</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-sm-2">
                                <select name="" id="" class="form-control select-year">
                                    <option value="">--Academic Year*--</option>
                                    @foreach ($academicYears as $academicYear)
                                        <option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="" id="" class="form-control select-semester">
                                    <option value="">--Semester*--</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="" id="" class="form-control select-type">
                                    <option value="">--Category--</option>
                                    <option value="1">Academics</option>
                                    <option value="2">Extra-Curricular Activities</option>
                                    <option value="3">Co-Curricular Ativities</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="" id="" class="form-control select-event">
                                    <option value="">--Events--</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-success search-evaluation-table-btn">Search</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 evaluation-table-holder">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
            aria-hidden="true">
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
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('.alert-auto-hide').fadeTo(7500, 500, function() {
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
                url: "{{ url('/house/get-semester/from-year') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'academicYearId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Semester*--</option>';

                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.name+'</option>';
                    });

                    $('.select-semester').empty();
                    $('.select-semester').append(txt);
                }
            });
            // Ajax Request End
        });

        $('.select-type').change(function () {
            var type = $(this).val();
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/get-events/from-type') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'type': type,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Event--</option>';

                    if (type == 1) {
                        data.forEach(element => {
                            txt += '<option value="'+element.id+'">'+element.exam_name+'</option>';
                        });
                    } else if(type == 2 || type == 3){
                        data.forEach(element => {
                            txt += '<option value="'+element.id+'">'+element.category_name+'</option>';
                        });
                    }

                    $('.select-event').empty();
                    $('.select-event').append(txt);
                }
            });
            // Ajax Request End
        });

        $('.search-evaluation-table-btn').click(function () {
            var academicYearId = $('.select-year').val();
            var semesterId = $('.select-semester').val();
            var type = $('.select-type').val();
            var eventId = $('.select-event').val();

            if (academicYearId && semesterId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/house/search-evaluation-table') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'academicYearId': academicYearId,
                        'semesterId': semesterId,
                        'type': type,
                        'eventId': eventId,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        console.log(data);
                        $('.evaluation-table-holder').empty();
                        $('.evaluation-table-holder').append(data);
                    }
                });
                // Ajax Request End
            }else{
                swal('Error', 'Please Fill Up the required fields first!', 'error');
            }

            
        });
    });
</script>
@stop