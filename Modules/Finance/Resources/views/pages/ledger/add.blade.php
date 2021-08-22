@extends('finance::layouts.master')
@section('section-title')

    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li>
            <a href="/">
                <i class="fa fa-home"></i>Home
            </a>
        </li>
        <li>
            <a href="/library/default/index">Finacne</a>
        </li>
        <li class="active">Manage Account</li>
    </ul>

@endsection

<!-- page content -->
@section('page-content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- ./col -->
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Ledger</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('message') }}</div>
                        @endif

                        <div class="ledgers add form">
                            <form action="{{URL::to('finance/accounts/ledger/store')}}" method="post" accept-charset="utf-8">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="ledger_id" value="">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Parent Group</label>
                                            {{ Form::select('group_id', $groupList, null, ['class' => 'form-control','id'=>'group']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Ledger Code</label>
                                            <input type="text" required name="code" id="l_code" value="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ledger name</label>
                                            <input type="text" required name="name" value="" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Opening balance</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select required name="op_balance_dc" class="form-control">
                                                        <option value="D">Dr</option>
                                                        <option value="C">Cr</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input required type="number" value="" name="op_balance" class="form-control">
                                                            <div class="input-group-addon">
                                                                <i>
                                                                    <div class="fa fa-info-circle" data-toggle="tooltip" title="Note: Assets & Expenses always have Dr balance and Liabilities & Incomes always have Cr balance.">
                                                                    </div>
                                                                </i>
                                                            </div>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                    <!-- /.form group -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                                <label><input type="checkbox" required name="type" value="1">Bank or Cash Account </label>
                                        </div>
                                        <!-- /.form group -->
                                        <div class="form-group">
                                            <label><input type="checkbox" name="reconciliation" value="2"> Reconciliation </label>
                                            <!-- /.input group -->
                                        </div>
                                        <!-- /.form group -->
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Notes</label>
                                            <textarea name="notes" required rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="form-group">
                            <input type="submit" name="Submit" value="Submit" class="btn btn-primary  pull-right">
                            <a href="https://otsglobal.org/accountant/accounts" class="btn btn-default pull-right" style="margin-right: 5px;">Cancel</a>
                        </div>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('page-script')

    <script>
        $(".select2-box").select2();
    </script>


@endsection


