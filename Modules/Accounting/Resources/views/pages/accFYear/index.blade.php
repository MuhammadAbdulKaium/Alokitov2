<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 5/23/17
 * Time: 4:53 PM
 */
?>
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
                <li><a href="#">Financial Year </a></li>
            </ul>
        </section>
        <?php
        $chk=0;
        foreach($accFYears as $accFYear){
            if($accFYear->status == 1){
                $chk++;
            }
        }
        ?>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Financial Year</h3>
                                @if($chk==0)
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accfyear/add')}}">
                                        <i class="fa fa-plus-square"></i> Add</a>
                                </div>
                                @endif
                            </div>
                            <div class="box-body table-responsive">
                                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                                    <div id="w1" class="grid-view">

                                        <table id="myTable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                {{--<th>#</th>--}}
                                                <th><a  data-sort="sub_master_name">SN</a></th>
                                                <th><a  data-sort="sub_master_alias">Start Date</a></th>
                                                <th><a  data-sort="sub_master_alias">End Date</a></th>
                                                <th><a>Action</a></th>
                                                <th><a></a></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i=1;?>
                                            @foreach($accFYears as $accFYear)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{date('d-m-Y',strtotime($accFYear->start_date))}}</td>
                                                    <td>{{date('d-m-Y',strtotime($accFYear->end_date))}}</td>
                                                    <td>@if($accFYear->status==1) <p>Active</p>
                                                        @elseif($accFYear->status==2) <p>Closed</p>@endif
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $diff = strtotime(date('Y-m-d')) - strtotime($accFYear->end_date);
                                                        $d = $diff/86400;
                                                        ?>
                                                    @if( $accFYear->status==1 && ($d >= -20 ))
                                                        <a class="btn btn-success btn-sm" onclick="yearClosing()">
                                                        <i></i>Year Closing</a>
                                                    @endif
                                                    </td>
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
        function yearClosing() {
            {{--var token = "{{ csrf_token() }}"; var dataSet = '_token='+token;--}}
            if(confirm('Are You Sure?')){
                window.open("{{ url('accounting/accfyear/yearclosing')}}","_self");
            }
        }
    </script>
@endsection






