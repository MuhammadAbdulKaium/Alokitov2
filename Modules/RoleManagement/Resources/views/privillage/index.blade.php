
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
                    @foreach($parents as $menu)
                    <div class="col-md-3 ">
                        <div class="box">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <h4>{{$menu->menu_name}}</h4>
                                </label>
                            </div>

                            @foreach($childs as $child)
                                @if($menu->id == $child->parent_id)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            <h5>{{$child->menu_name}}</h5>
                                        </label>
                                    </div>
                                @endif
                                    @foreach($grands as $grand)
                                        @if($menu->id == $child->parent_id)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    <h5>{{$grand->menu_name}}</h5>
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                            @endforeach

                        </div>
                    </div>
                    @endforeach
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