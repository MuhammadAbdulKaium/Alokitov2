@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    @extends('layouts.master')

    @section('content')
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage |<small>Online Admission Form Duration</small></h1>
                <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Website</a></li>
                    <li class="active">Online Admission Form Duration</li>
                </ul>
            </section>
            <section class="content">

                @if(!$date)

                    <div class="box box-solid">
                        <div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-search"></i>View Website Online Admission Form Duration</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('website/form/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add</a>
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
                                        <h3 class="box-title"><i class="fa fa-search"></i>View Website Online Admission Form Duration</h3>
                                        <div class="box-tools">
                                            <a class="btn btn-primary btn-sm" href="{{url('website/form/edit', $date->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Edit</a>
                                            <a class="btn btn-danger btn-sm" href="{{url('website/form/delete', $date->id)}}" onclick="return confirm('Are you sure to Delete?')">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <colgroup>
                                        <col style="width:15%">
                                        <col style="width:85%">
                                    </colgroup>

                                    <tr>
                                        <th>Date Format:</th>
                                        <td>Date - Time</td>

                                    </tr>

                                    <tr>
                                        <th>Stating Date:</th>
                                        <td>{{date('Y-M-d - H:i:s', strtotime($date->starting_date))}}</td>

                                    </tr>
                                    <tr>
                                        <th>Ending Date:</th>
                                        <td>{{date('Y-M-d - H:i:s ', strtotime($date->ending_date))}}</td>

                                    </tr>
                                    <tr>
                                        <th>
                                            <form id="add-information-form" name="add-information-form"  class="form-horizontal" action="{{url('website/form/update', $date->id)}}" method="post" role="form">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                @if($date->is_active == 0)
                                                    <input id="is_active" class="form-control" name="is_active" type="hidden" value="1">
                                                    <button type="submit" class="btn btn-primary btn-success">Activate</button>
                                                @else
                                                    <input id="is_active" class="form-control" name="is_active" type="hidden" value="0">
                                                    <button type="submit" class="btn btn-primary btn-warning">Deactivate</button>
                                                @endif
                                            </form>

                                        </th>
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


