
@extends('admin::layouts.master')

@section('styles')
    <style>
        #admin-chart {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .anychart-credits {
            display: none;
        }
        .chart-box {
            height: 300px;
        }
        div#admin-chart,div#admin-chart2,div#admin-chart3,div#admin-chart4,div#admin-chart5,div#admin-chart6,div#admin-chart7,div#admin-chart8,div#admin-chart9,div#admin-chart10,div#admin-chart11,div#admin-chart12 {
            height: 300px;
        }
        #Welcome {
            position: absolute;
            margin: 0px;
            display: inline-block;
            top: 50%;
            transform: translate(0%, -50%);
        }
        #Header {
            position: absolute;
            margin: 0px;
            display: inline-block;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        #LogOut {
            position: absolute;
            right: 0;
            margin-right: 10px;
            display: inline-block;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            top: 50%;
            transform: translate(0%, -50%);
        }
        #LogOut:hover {
            color: white;
        }
        #top-bar {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 5%;
            max-height: 45px;
            background-color: black;
            color: white;
        }
        .container {
            display: inline-block;
            cursor: pointer;
            margin-left: 10px;
            margin-right: 10px;
        }
        .bar1, .bar2, .bar3 {
            width: 35px;
            height: 5px;
            background-color: white;
            margin: 6px 0;
            transition: 0.4s;
        }
        #left-menu {
            display: none;
            position: absolute;
            background-color: black;
            color: white;
            left: 0;
            top:4.8%;
            height:100%;
            width:25%;
            max-width:270px;
        }
        .change .bar1 {
            -webkit-transform: rotate(-45deg) translate(-9px, 6px);
            transform: rotate(-45deg) translate(-9px, 6px);
        }
        .change .bar2 {opacity: 0;}
        .change .bar3 {
            -webkit-transform: rotate(45deg) translate(-8px, -8px);
            transform: rotate(45deg) translate(-8px, -8px);
        }
        #left-menu h1{
            border-bottom-style: solid;
        }
        #left-menu .inactive {
            font-size: 25px;
            color: white;
            text-decoration: none;
        }
        #left-menu .active {
            font-size: 25px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
        }
        #left-menu .active:hover {
            color: white;
        }
        #myCanvas {
            position: relative;
            width:100%;
            height:100%;
        }
        #main-content {
            position: absolute;
            color: black;
            left: 0;
            top:4.8%;
            height:95.2%;
            width:100%;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="panel ">
                <div class="panel-body">
                    <div class="col-md-6">
                        <div class="box-header with-border">
                            <h3 class="box-title"> Role List</h3>
                            <div class="box-tools">
                                <a id="update-guard-data" class="btn btn-success" href="/role-management/role/add" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Role</a>
                            </div>
                        </div>
                        <table class="table table-dark">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Role</th>
                                <th scope="col">Display Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $key=>$role)
                                <tr>
                                    <th scope="row">{{$key+1}}</th>
                                    <td>{{$role->name}}</td>
                                    <td>{{$role->display_name}}</td>
                                    @if($role->status==0)
                                        <td>De-Active</td>
                                    @else
                                        <td>Active</td>
                                    @endif
                                    <td>
                                        <a href="/role-management/privillage/list/{{$role->id}}" class="btn btn-primary">Privilege</a>
                                        <button class="btn btn-primary">Status</button>
                                        <a href="/role-management/role/delete/{{$role->id}}" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="col-md-6">
                        <div class="box-header with-border">
                            <h3 class="box-title"> User List</h3>
                            <div class="box-tools">
                                <a id="update-guard-data" class="btn btn-success" href="/role-management/users/add" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-user-plus" aria-hidden="true"></i> Add User</a>
                            </div>
                        </div>
                        <table class="table table-dark">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">email</th>
                                <th scope="col">Username</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key=>$user)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->username}}</td>
                                @if($user->status==0)
                                    <td>De-Active</td>
                                @else
                                    <td>Active</td>
                                @endif
                                <td>
                                    <a href="/role-management/privillage/list/{{$user->id}}" class="btn btn-primary">Privilege</a>
                                    <button class="btn btn-primary">Status</button>
                                    <a href="/role-management/users/delete/{{$user->id}}" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        function delete_role($id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/role-management/role/delete/"+$id,
                type: 'DELETE',
                data: {
                    "id": $id // method and token not needed in data
                },
                cache: false,
                success:function(result){
                   console.log("Delete")
                }
            });
        }

    </script>
@endsection