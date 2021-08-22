<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/23/17
 * Time: 5:22 PM
 */?>
@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> Accounting
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{url('/home')}}"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('/finance')}}"><i class="fa fa-home"></i>Finance</a></li>
                <li><a href="{{url('accounting')}}">Accounting</a></li>
                <li><a href="#">List of Ledger and Group</a></li>
            </ul>
        </section>

        {{--<ul>
        @foreach($accHeads as $accHead)
            <li>
                {{ $accHead->chart_name }}
                @if(count($accHead->childs))
                    @include('accounting::pages.accHead.manageChild',['childs' => $accHead->childs])
                @endif
            </li>
        @endforeach
        </ul>--}}
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">List of Ledger and Group</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/acchead/add')}}">
                                        <i class="fa fa-plus-square"></i> Add</a>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                                    <div id="w1" class="grid-view">

                                        <table id="myTable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                {{--<th>#</th>--}}
                                                <th><a  data-sort="sub_master_name">Code</a></th>
                                                <th><a  data-sort="sub_master_alias">Name</a></th>
                                                <th><a  data-sort="sub_master_alias">Type</a></th>
                                                <th><a  data-sort="sub_master_alias">Parent</a></th>
                                                <th><a>Action</a></th>
                                                <th></th>
                                            </tr>

                                            </thead>

                                            <tbody>
                                            <?php $i=1;?>
                                            @foreach($accHeads as $accHead)
                                                <tr style="font-size: 20px;font-weight: 900">
                                                    {{--<td>{{ $i++}}</td>--}}
                                                    <td>{{ $accHead->chart_code }}</td>
                                                    <td>{{ $accHead->chart_name }}</td>
                                                    <td>@if($accHead->chart_type == 'G'){{'Group'}}
                                                        @else {{'Ledger'}} @endif
                                                    </td>
                                                    <td>{{--parent info--}}</td>
                                                    <td>@if($accHead->status==1) <p>Active</p>
                                                        @elseif($accHead->status==0) <p>Inactive</p>@endif
                                                    </td>
                                                    <td>
                                                        {{--<a href="" class="btn btn-primary btn-xs" id="section_view_{{$accHead->id}}" onclick="modalLoad(this.id)" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}
                                                        {{--<a href="" id="section_edit_{{$accHead->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>--}}
                                                        {{--<a href="{{ url('accounting/delete-section', $accHead->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}

                                                        {{--<a class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="modalLoad({{$accHead->id}})"><i class="fa fa-trash-o"></i></a>--}}
                                                    </td>
                                                    @if(count($accHead->childs))
                                                        @include('accounting::pages.accHead.manageChild',['childs' => $accHead->childs])
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        {{--data will sit here--}}
    </div>

    <script type = "text/javascript">
        function modalLoad(rowId) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&id='+rowId;
            $.ajax({
                url: "{{ url('accounting/acchead/edit')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#myModal').html(data);
                }
            });
        }
    </script>
@endsection