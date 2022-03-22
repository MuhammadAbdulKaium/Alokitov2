@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    @extends('layouts.master')

    @section('content')
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Manage |<small>Committee</small></h1>
                <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Website</a></li>
                    <li class="active">Committee</li>
                </ul>

                <section class="content">

                @if(count($committees) < 1)

                    <div class="box box-solid">
                        <div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-search"></i>View Website Committee</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('website/committee/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add</a>
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
                                        <h3 class="box-title"><i class="fa fa-search"></i>View Website Committee</h3>
                                        <div class="box-tools">
                                            <a class="btn btn-success btn-sm" href="{{url('website/committee/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-body table-responsive">
                                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                                    <div id="w1" class="grid-view">
                                        <table id="myTable" class="table table-striped table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th><a  data-sort="sub_master_code">Serial</a></th>
                                                    <th><a  data-sort="sub_master_alias">Image</a></th>
                                                    <th><a  data-sort="sub_master_alias">Name</a></th>
                                                    <th><a  data-sort="sub_master_alias">Designation</a></th>
                                                    <th><a  data-sort="sub_master_alias">Phone</a></th>
                                                    <th><a  data-sort="sub_master_alias">Email</a></th>
                                                    <th><a  data-sort="sub_master_alias">Speech</a></th>
                                                    <th><a>Action</a></th>
                                                </tr>

                                            </thead>
                                            <?php $i = 1 ?>
                                            @foreach($committees as $committee)
                                            <tbody>

                                                    <tr class="gradeX">
                                                        <td>{{$i++}}</td>
                                                        <td><img height="80" src="/images/{{$committee->image}}" alt=""></td>
                                                        <td>{{$committee->name}}</td>
                                                        <td>{{$committee->designation}}  <span class="badge badge-primary">@if($committee->type==1) Principle @elseif($committee->type==2) Chairman @endif</span></td>
                                                        <td>{{$committee->phone}}</td>
                                                        <td>{{$committee->email}}</td>
                                                        <td>{{substr($committee->speech, 0, 50)}}....</td>

                                                        <td>
                                                            <a href="{{ url('website/committee/show', $committee->id) }}" class="btn btn-primary btn-xs" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-content="view">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            <a href="{{ url('website/committee/edit', $committee->id) }}"  class="btn btn-primary btn-xs" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-content="update">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <a href="{{ url('website/committee/delete', $committee->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-content="delete">
                                                                <i class="fa fa-trash-o"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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


