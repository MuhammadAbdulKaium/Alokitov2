@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    @extends('layouts.master')

    @section('content')
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage |<small>Rules And Regulations</small></h1>
                <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Website</a></li>
                    <li class="active">Rules And Regulations</li>
                </ul>
            </section>
            <section class="content">

                @if(!$rules)

                    <div class="box box-solid">
                        <div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-search"></i>View Website Rules And Regulations</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('website/rules/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add</a>
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
                                        <h3 class="box-title"><i class="fa fa-search"></i>View Website Rules And Regulations</h3>
                                        <div class="box-tools">
                                            <a class="btn btn-primary btn-sm" href="{{url('website/rules/edit', $rules->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Edit</a>
                                            <a class="btn btn-danger btn-sm" href="{{url('website/rules/delete', $rules->id)}}" onclick="return confirm('Are you sure to Delete?')">Delete</a>
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
                                        <th>Teacher's Rules And Regulations:</th>
                                        <td>{{$rules->teacher_rule}}</td>

                                    </tr>
                                    <tr>
                                        <th>Student's Rules And Regulations:</th>
                                        <td>{{$rules->student_rule}}</td>

                                    </tr>
                                    <tr>
                                        <th>Parent's Rules And Regulations:</th>
                                        <td>{{$rules->parent_rule}}</td>

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

