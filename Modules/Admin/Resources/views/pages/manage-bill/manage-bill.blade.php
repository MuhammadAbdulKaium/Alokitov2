
@extends('admin::layouts.master')

@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ Module::asset('admin:css/style.css') }}" />
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Bill</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Bill Management</a></li>
                <li class="active">Institute Payment List</li>
            </ul>
        </section>
        <section class="content">
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

            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <ul id="assessmentNav" class="nav-tabs margin-bottom nav">
                            <li @if($page == "bill-info") class="active" @endif id="tab-setup"><a href="/admin/bills/bill-info">Billing Info</a></li>
                            <li @if($page == "manage-bill") class="active" @endif id="tab-assessment"><a href="/admin/bills/manage-bill">Bill Management</a></li>
                            <li @if($page == "subscription-management") class="active" @endif id="tab-assessment"><a href="/admin/bills/subscription-management">Subscription Management</a></li>
                            <li @if($page == "bill-reports") class="active" @endif id="tab-reportcard"><a href="/admin/bills/bill-reports">Reports</a></li>
                        </ul>
                        <!-- page content div -->
                        @yield('page-content')
                    </div>
                </div>
                <div class="box box-solid">

                    <div class="et">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> Institute Payment List</h3>
                            <div class="box-tools">
                                <a class="btn btn-success btn-sm" href="{{url('admin/billing/info/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                                    <i class="fa fa-plus-square"></i> Add Billing Info
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="example1" class="table table-bordered table-striped table-responsive table-hover {{ $page }}">
                                    <thead>
                                        <tr>
                                            <th width="3%">#</th>
                                            <th width="17%">Institute Name</th>
                                            <th width="80%">
                                                <table width="100%" class="table table-bordered">
                                                    <th width="12%" scope="col">Campus</th>
                                                    <th width="8%" scope="col" class="text-center">Total students</th>
                                                    <th width="6%" scope="col">Year</th>
                                                    <th width="6%" scope="col">Month</th>
                                                    <th width="9%" scope="col" class="text-center">Rate Per Student</th>
                                                    <th width="9%" scope="col" class="text-center">Calculated Rate</th>
                                                    <th width="8%" scope="col" class="text-center">Accepted Rate</th>
                                                    <th width="8%" scope="col" class="text-center">Total SMS</th>
                                                    <th width="8%" scope="col" class="text-center">Rate Per SMS</th>
                                                    <th width="8%" scope="col" class="text-center">Total SMS Price</th> 
                                                    <th width="9%" scope="col" class="text-center">Accepted SMS Price</th> 
                                                    <th width="9%" scope="col" class="text-center">Action</th> 
                                                </table>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($instituteList as $index=>$instituteProfile)
                                            <tr>
                                                <td width="3%">{{$index+1}}</td>
                                                <th width="17%">{{$instituteProfile->institute_name}}</th>
                                                <td width="80%">                                                        
                                                    <table class="table table-bordered" width="100%">
                                                        @foreach($instituteProfile->campus() as $cam) 
                                                            <tr>                                                                     
                                                                <td width="12%">{{ $cam->name }}</td>
                                                                <td width="8%" class="text-center">{{ $cam->student()->count() }}</td>
                                                                @if(array_key_exists($cam->id, $allBillingInfoArrayListByCampus)==TRUE)
                                                                    @php $billingInfoByCampus = $allBillingInfoArrayListByCampus[$cam->id]; @endphp
                                                                    <td width="6%">{{ $billingInfoByCampus['year']}}</td>
                                                                    <td width="6%">{{ $billingInfoByCampus['month']}}</td>
                                                                    <td width="9%" class="text-center">{{ $billingInfoByCampus['rate']}}</td>
                                                                    <td width="9%" class="text-center">{{ $billingInfoByCampus['total_amount']}}</td>
                                                                    <td width="8%" class="text-center">{{ $billingInfoByCampus['accepted_amount']}}</td>
                                                                    @if(array_key_exists($cam->id, $allInstituteSmsCountByCampus) == TRUE)
                                                                        @php $instituteSmsCountByCampus = $allInstituteSmsCountByCampus[$cam->id]; @endphp
                                                                        <td width="8%" class="text-center">@isset( $instituteSmsCountByCampus['total_sms'] ) {{ $instituteSmsCountByCampus['total_sms'] }} @endisset</td>
                                                                    @endif
                                                                    <td width="8%" class="text-center">@isset ( $billingInfoByCampus['rate_per_sms'] ) {{ $billingInfoByCampus['rate_per_sms']}} @endisset</td>
                                                                    <td width="8%" class="text-center">@isset ( $billingInfoByCampus['total_sms_price'] ) {{ $billingInfoByCampus['total_sms_price']}} @endisset</td>
                                                                    <td width="9%" class="text-center">@isset ( $billingInfoByCampus['accepted_sms_price'] ) {{ $billingInfoByCampus['accepted_sms_price']}} @endisset</td>
                                                                    <td width="9%" class="text-center">
                                                                        <a class="btn btn-primary btn-xs" href="{{url('admin/billing/info/'.$billingInfoByCampus['id'].'/edit')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                                                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                                                        </a>
            
                                                                        <a class="btn btn-danger btn-xs" href="{{url('admin/billing/info/'.$billingInfoByCampus['id'].'/delete')}}" onclick="return confirm('Are you sure to Delete?')" data-content="delete">
                                                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                        </a>
                                                                    </td>
                                                                @else
                                                                    <td width="6%"></td>
                                                                    <td width="6%"></td>
                                                                    <td width="9%"></td>
                                                                    <td width="9%"></td>
                                                                    <td width="8%"></td>
                                                                    <td width="8%"></td>
                                                                    <td width="8%"></td>
                                                                    <td width="8%"></td>
                                                                    <td width="9%"></td>
                                                                    <td width="9%"></td>
                                                                @endif
                                                            </tr>                                                                                                                       
                                                        @endforeach
                                                    </table>
                                                </td>
                                                {{--checking--}}
                                                {{--@if(array_key_exists($instituteProfile->id, $allBillingInfoArrayList)==true)
                                                    {{--institute billing details--}}
                                                    {{--@php $billingInfo = $allBillingInfoArrayList[$instituteProfile->id]; @endphp
                                                    <td>{{$billingInfo['rate']}}</td>
                                                    <td>{{$billingInfo['total_amount']}}</td>
                                                    <td>{{$billingInfo['accepted_amount']}}</td>
                                                    <td>
                                                        <a class="btn btn-primary btn-xs" href="{{url('admin/billing/info/'.$billingInfo['id'].'/edit')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                                        </a>

                                                        <a class="btn btn-danger btn-xs" href="{{url('admin/billing/info/'.$billingInfo['id'].'/delete')}}" onclick="return confirm('Are you sure to Delete?')" data-content="delete">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                {{--@else
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                @endif --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{--global modal--}}
            <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" id="modal-content">
                        <div class="modal-body" id="modal-body">
                            <div class="loader">
                                <div class="es-spinner">
                                    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script>

        jQuery(document).ready(function () {

            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": true
            });

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });


        });
    </script>
@endsection
