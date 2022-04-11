@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Exam |<small>Tabulation Sheet (Term Wise) - Summary</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Academics</a></li>
            <li>Reports</li>
            <li class="active">Tabulation Sheet (Term Wise) - Summary</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
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
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Tabulation Sheet (Term Wise) - Summary</h3>
            </div>
            <div class="box-body">
                <form id="search-results-form" method="POST" action="{{ url('/academics/exam/tabulation-sheet-term-summary/search-marks') }}" target="_blank">
                    @csrf

                    <input type="hidden" name="type" class="select-type" value="search">

                    <div class="row"  style="margin-bottom: 50px">
                        <div class="col-sm-2">
                            <select name="yearId" id="" class="form-control select-year" required>
                                <option value="">Academic Year*</option>
                                @foreach ($academicYears as $academicYear)
                                    <option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="termId" id="" class="form-control select-term" required>
                                <option value="">Term / Semester*</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="batchId[]" id="" class="form-control select-class" multiple required>
                                <option value="">Select Class*</option>    
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->batch_name }}</option>
                                @endforeach                            
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="sectionId" id="" class="form-control select-form">
                                <option value="">Select Form</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="checkbox" class="form-check-input " name="compact" id="exampleCheck1" checked>
                            <label class="form-check-label" for="exampleCheck1">Compact</label>
                            <button class="btn btn-sm btn-primary search-btn" type="button"><i class="fa fa-search"></i></button>
                            <button class="btn btn-sm btn-primary print-btn" type="button"><i class="fa fa-print"></i></button>
                            <button class="print-submit-btn" type="submit" style="display: none"></button>
                        </div>
                    </div>
                </form>

                <div class="marks-table-holder table-responsive">
                    
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
        $('.select-class').select2({
            placeholder: "Select Class*",
        });
        $('.select-class').change(function () {
            if ($(this).val()) {
                if ($(this).val().length == 1) {
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
                            var txt = '<option value="">Select Form</option>';
                            data.forEach(element => {
                                txt += '<option value="'+element.id+'">'+element.section_name+'</option>';
                            });

                            $('.select-form').html(txt);
                        }
                    });
                    // Ajax Request End
                } else {
                    $('.select-form').html('<option value="">Select Form</option>');
                }
            } else {
                $('.select-form').html('<option value="">Select Form</option>');
            }
        });

        $('.search-btn').click(function () {
            var year = $('.select-year').val();
            var term = $('.select-term').val();
            var batch = $('.select-class').val();

            if (year && term && batch) {
                $('.select-type').val('search');
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/academics/exam/tabulation-sheet-term-summary/search-marks') }}",
                    type: 'POST',
                    cache: false,
                    data: $('form#search-results-form').serialize(),
                    datatype: 'application/json',
                
                    beforeSend: function () {
                        waitingDialog.show('Loading...');
                    },
                
                    success: function (data) {
                        waitingDialog.hide();
                        console.log(data);

                        $('.marks-table-holder').html(data);
                    },

                    error:function(data){
                        waitingDialog.hide();
                        alert(JSON.stringify(data));
                    }
                });
                // Ajax Request End
            } else {
                swal('Error!', 'Please Fill up all the required fields first.', 'error');
            }
        });

        $('.print-btn').click(function () {
            $('.select-type').val('print');
            $('.print-submit-btn').click();
        });
    });
</script>
@stop