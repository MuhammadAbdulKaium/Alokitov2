@extends('layouts.master')

@section('styles')
<style>
    .select2-selection--single {
        height: 33px !important;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Accounts |<small>Chart of Accounts</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="">Accounts</a></li>
            <li>SOP Setup</li>
            <li class="active">Chart of Accounts</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
        <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
        <p class="alert alert-warning alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
        <p class="alert alert-danger alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p class="alert alert-danger alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                    style="text-decoration:none" data-dismiss="alert"
                    aria-label="close">&times;</a>{{ $error }}</p>
            @endforeach
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Chart of Accounts
                        </h3>
                        <div class="box-tools">
                            <a class="btn btn-info" href="{{url('/accounts/chart-of-accounts-config')}}"
                                data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm">Accounts Code Config</a>
                            <a class="btn btn-success" href="{{url('/accounts/chart-of-accounts/create-group')}}"
                                data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Create
                                Group</a>
                            <a class="btn btn-success" href="{{url('/accounts/chart-of-accounts/create-ledger')}}"
                                data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Create
                                Ledger</a>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="accountsTable">
                            <thead>
                                <tr>
                                    <th>Auto Account Code</th>
                                    <th>Manual Account Code</th>
                                    <th>Account Name</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                function chartOfAccounts($accountId, $accounts, $margin){
                                $account = $accounts[$accountId];
                                $color = '';
                                $fontWeight = 'normal';
                                $fontSize = '';
                                if ($accountId == 1 || $accountId == 2 || $accountId == 3 || $accountId == 4){
                                $buttons = '';
                                } else{
                                $buttons = '<a class="btn btn-primary btn-xs"
                                    href="/accounts/chart-of-accounts/edit/'.$account->id.'" data-target="#globalModal"
                                    data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                                <a href="/accounts/chart-of-accounts/delete/'.$account->id.'"
                                    class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure to Delete?\')"
                                    data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>';
                                }
                                if ($account->account_type == 'group') {
                                    $color = '#7C0000';
                                } elseif ($account->account_type == 'ledger') {
                                    $color = '#00a65a';
                                    $fontWeight = 'bold';
                                } elseif($account->account_type == '') {
                                    $fontSize = 18;
                                    $fontWeight = 'bold';
                                }
                                echo '<tr style="color: '.$color.'; font-weight: '.$fontWeight.'; font-size: '.$fontSize.'px">
                                    <td><span style="margin-left: '.$margin.'px; color: '.$color.'">'.$account->account_code.'</span></td>
                                    <td><span style="margin-left: '.$margin.'px; color: '.$color.'">'.$account->manual_account_code.'</span></td>
                                    <td><span style="margin-left: '.$margin.'px">'.$account->account_name.'</span></td>
                                    <td>'.ucfirst($account->account_type).'</td>
                                    <td>'.$buttons.'</td>
                                </tr>';

                                $childs = $accounts->where('parent_id', $accountId);
                                foreach ($childs as $child){
                                    $margin += 30;
                                    chartOfAccounts($child->id, $accounts, $margin);
                                    $margin -= 30;
                                    }
                                }
                                @endphp
                                @foreach($nature as $na)
                                    {{chartOfAccounts($na->id, $accounts, 0)}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
            aria-hidden="true">
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
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        // $('#houseTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function () {
            $(this).slideUp('slow', function () {
                $(this).remove();
            });
        });
    });
</script>
@stop