@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    @extends('layouts.master')

    @section('content')
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage |<small>Information</small></h1>
                <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Website</a></li>
                    <li class="active">Information</li>
                </ul>
            </section>
            <section class="content">

                @if(!$informations)

                    <div class="box box-solid">
                        <div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-search"></i>View Website Information</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('website/information/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add</a>
                                </div>
                            </div>
                        </div>

                        <div class="alert bg-warning text-warning">
                            <i class="fa fa-warning"></i> No record found.
                        </div>

                        @else

                            <div class="box box-solid">
                                <div>
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="fa fa-search"></i>View Website Information</h3>
                                        <div class="box-tools">
                                            <a class="btn btn-primary btn-sm" href="{{url('website/information/edit', $informations->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Edit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <colgroup>
                                        <col style="width:15%">
                                        <col style="width:35%">
                                        <col style="width:15%">
                                        <col style="width:35%">
                                    </colgroup>
                                    <tr>
                                        <th>Logo:</th>
                                        <td><img height="80" src="/images/{{$informations->school_logo}}" alt=""></td>
                                    </tr>
                                    <tr>
                                        <th>School Name:</th>
                                        <td>{{$informations->school_name}}</td>

                                        <th>Phone:</th>
                                        <td>{{$informations->school_phone}}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{$informations->school_email}}</td>

                                        <th>Facebook Link:</th>
                                        <td>{{$informations->school_fb}}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td>{{$informations->school_address}}</td>
                                    </tr>
                                </table>

                                <table class="table">
                                    <colgroup>
                                        <col style="width:15%">
                                        <col style="width:85%">

                                    </colgroup>
                                    <tr>
                                        <th>Contact Person:</th>
                                        <td>{{$informations->school_contact}}</td>
                                    </tr>
                                    <tr>
                                        <th>History:</th>
                                        <td>{{$informations->school_history}}</td>
                                    </tr>
                                    <tr>
                                        <th>Mission And Vision:</th>
                                        <td>{{$informations->school_mission}}</td>
                                    <tr>
                                        <th>Structure:</th>
                                        <td>{{$informations->school_structure}}</td>
                                    </tr>
                                </table>

                @endif
            </section>
        </div>

        <div class="modal"  id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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

@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif

