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
            <i class="fa fa-th-list"></i> View |<small>House</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">House</a></li>
            <li>SOP Setup</li>
            <li class="active">View House</li>
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
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> View House </h3>
                    </div>
                    <div class="box-body">
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-sm-3">
                                <select name="" id="" class="form-control select-house">
                                    <option value="">--House--</option>
                                    @foreach ($houses as $house)
                                        <option value="{{$house->id}}">{{$house->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-success search-house-table-btn">Search</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 house-table-holder">

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

        $('.search-house-table-btn').click(function () {
            var houseId = $('.select-house').val();

            if (houseId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/house/search/house') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'houseId': houseId,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        console.log(data);
                        $('.house-table-holder').empty();
                        $('.house-table-holder').append(data);
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