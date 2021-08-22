@extends('layouts.master')
<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-plus-square"></i>Users Reset Password</h1>
        </section>
        <section class="content">
            {{--@if(Session::has('success'))--}}
            {{--<div class="alert-success alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">--}}
            {{--<button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>--}}
            {{--<h4><i class="icon fa fa-check"></i>{{ Session::get('success') }}</h4>--}}
            {{--</div>--}}
            {{--@elseif(Session::has('warning'))--}}
            {{--<div class="alert-warning alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">--}}
            {{--<button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>--}}
            {{--<h4><i class="icon fa fa-check"></i>{{ Session::get('warning') }}</h4>--}}
            {{--</div>--}}
            {{--@endif--}}
            <div class="panel panel-default">


                @if($forgotUsers->count()>0)

                    <div class="box-body table-responsive">
                        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                            <div id="w1" class="grid-view">

                                <table id="feesListTable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><a  data-sort="sub_master_name">Name</a></th>
                                        <th><a  data-sort="sub_master_alias">Email</a></th>
                                        <th><a  data-sort="sub_master_alias">Phone</a></th>
                                        <th><a  data-sort="sub_master_alias">Status</a></th>
                                        <th><a  data-sort="sub_master_alias">Approve</a></th>
                                        <th><a>Action</a></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--{{$users->user()->roles()->first()->display_name}}--}}
                                    @php

                                        $i = 1
                                    @endphp
                                    @foreach($forgotUsers as $users)
                                        @php $role=$users->user()->roles()->first()
                                                @endphp

                                        <tr class="gradeX">
                                            <td>{{$i++}}</td>
                                            <td>{{$users->user()->name}}</td>
                                            <td>{{$role->display_name}}</td>
                                            <td>
                                                <form action="/reset/user/password" method="post">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="user_id" value="{{$users->user_id}}">
                                                 @if($role->display_name=="Student")
                                                        <input type="hidden" name="phone" value="{{$users->user()->student()->phone}}">
                                                    {{$users->user()->student()->phone}}
                                                    @elseif($role->display_name=="Parent")
                                                        <input type="hidden" name="phone" value="{{$users->user()->parent()->phone}}">
                                                    {{$users->user()->parent()->phone}}
                                                  @elseif($role->display_name=="Teacher")
                                                        <input type="hidden" name="phone" value="{{$users->user()->employee()->phone}}">
                                                    {{$users->user()->employee()->phone}}
                                                @endif

                                            </td>
                                            <td>@if($users->status=="1") <span class="label label-success">Active </span> @else <span class="label label-danger"> Deactvie</span> @endif</td>
                                            <td>
                                                @if($users->status=="1")
                                                <button type="submit" class="btn btn-info"  role="button">Reset Password</button>
                                                @else
                                                    <a href="#" disabled="" class="btn btn-warning"  role="button">Successfully Reset</a>
                                                @endif
                                            </td>
                                            </form>
                                            <td>
                                                <a  id="{{$users->id}}" class="btn btn-danger btn-xs deleteforgotuser" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><span class="glyphicon glyphicon-trash"></span></a>
                                            </td>

                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                            <div class="pagination-right pull-right"> {{ $forgotUsers->links() }}</div>

                        </div>

                        @else
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                            </div>
                        @endif
            </div>
        </section>
    </div>

    <!-- global modal -->
    <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw">
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{URL::asset('js/charts/chart.min.js')}}"></script>
    <script src="{{URL::asset('js/tokenInput.js')}}"></script>
    <script src="{{URL::asset('js/sweet-alert.js')}}"></script>

    <script>
        @if(Session::has('forgotpassmsg'))
                toastr.success("{{ Session::get('forgotpassmsg') }}");
        @endif

        // Forget Password delete Ajax Request
        $('.deleteforgotuser').click(function() {
            var tr = $(this).closest('tr');
            var forgotPasswordId= $(this).attr('id');

            // ajax request
            $.ajax({
                url: '/forgot-password/users/delete/'+forgotPasswordId,
                type: 'GET',
                cache: false,
                success:function(data){
                    tr.fadeOut(1000, function(){
                        $(this).remove();
                    });
                    toastr.info('Forgot Password User Successfully Deleted');

                }
            });

        });


    </script>

@endsection
