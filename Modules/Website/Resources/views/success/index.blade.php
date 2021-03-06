@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    @extends('layouts.master')

    @section('content')
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
        @if(Session::has('success'))
            <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
            </div>
        @elseif(Session::has('alert'))
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
            </div>
        @elseif(Session::has('warning'))
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
            </div>
        @endif
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <i class="fa fa-th-list"></i> Achievement and Success |<small></small></h1>
                <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Website</a></li>
                    <li class="active">Committee</li>
                </ul>
            </section>
            <section class="content">

                @if(count($successes) < 1)

                    <div class="box box-solid">
                        <div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-search"></i>View Website Committee</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('website/success/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add</a>
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
                                            <a class="btn btn-success btn-sm" href="{{url('website/success/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add</a>
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

                                                    <th><a  data-sort="sub_master_alias">Passing year</a></th>
                                                    <th><a  data-sort="sub_master_alias">Total Examine</a></th>
                                                    @if($data['psc']==true)
                                                    <th><a  data-sort="sub_master_alias">PSC passing Percentage </a></th>
                                                    @endif
                                                    @if($data['jsc']==true)
                                                    <th><a  data-sort="sub_master_alias">JSC passing Percentage</a></th>
                                                    @endif
                                                    @if($data['ssc']==true)
                                                    <th><a  data-sort="sub_master_alias">SSC passing Percentage</a></th>
                                                    @endif
                                                    @if($data['hsc']==true)
                                                    <th><a  data-sort="sub_master_alias">HSC passing Percentage</a></th>
                                                    @endif

                                                    <th><a>Action</a></th>
                                                </tr>

                                            </thead>
                                            <?php $i = 1 ?>
                                            @foreach($successes as $success)
                                            <tbody>

                                                    <tr class="gradeX">
                                                        <td>{{$i++}}</td>

                                                        <td>{{$success->passing_year}}</td>
                                                        <td>{{$success->total_examine}}</td>
                                                        @if($data['psc']==true)
                                                          <td>{{$success->psc_passing_rate}}</td>
                                                        @endif

                                                        @if($data['jsc']==true)
                                                            <td>{{$success->jsc_passing_rate}}</td>
                                                        @endif

                                                        @if($data['ssc']==true)
                                                            <td>{{$success->ssc_passing_rate}}</td>
                                                        @endif
                                                        @if($data['hsc']==true)
                                                            <td>{{$success->hsc_passing_rate}}</td>
                                                        @endif


                                                        <td>
                                                            <a href="{{ url('website/success/show', $success->id) }}" class="btn btn-primary btn-xs" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-content="view">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            <a href="{{ url('website/success/edit', $success->id) }}"  class="btn btn-primary btn-xs" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-content="update">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <a href="{{ url('website/success/delete', $success->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-content="delete">
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


