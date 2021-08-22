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

    @php
        $functionCore= new \App\Http\Controllers\Helpers\Accounting\FunctionCore;
    @endphp


    <!-- Main content -->
    <section class="content">

        <!-- Small boxes (Stat box) -->
        <?php if ($functionCore->calculate($opdiff['opdiff_balance'], 0, '!=')) {
            echo '<div><div role="alert" class="alert alert-danger">There is a difference in opening balance of'.$functionCore->toCurrency($opdiff['opdiff_balance_dc'], $opdiff['opdiff_balance']).'</div></div>';
        }; ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Chart of Accounting</h3>
                    </div>
                <div class="pull-right" style="margin-bottom: 20px">
                    <a href="/finance/accounts/groups/add" class="btn btn-success">Create Group</a>
                    <a href="/finance/accounts/ledger/add" class="btn btn-success">Create Legder</a>
                    {{--<button type="button" class="btn btn-success">Export to XLS</button>--}}
                </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="ledgertable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <table id="ledgertable" class="stripped dataTable no-footer" role="grid">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 207px;">Account Code</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 404px;">Account Name</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 72px;">Type</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 174px;">O/P Balance (৳)</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 172px;">C/L Balance (৳)</th>
                                    {{--<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 157px;">Actions</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                            $financialAccountObj= new \Modules\Finance\Entities\FinancialAccount;
                                    $accountListData= $financialAccountObj->print_account_chart($accountList,-1, $this);
                                    @endphp
                                {{$accountListData}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
    </section>
    <!-- /.content -->



@endsection

@section('page-script')




@endsection


