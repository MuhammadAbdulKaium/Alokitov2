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

    <div class="box box-body">
        <div style="padding:25px;">
            <div class="ledgers add form col-md-5">

                @if(Session::has('message'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('message') }}</div>
                @endif

                <form action="{{URL::to('finance/ledger/store')}}" id="LedgerStore" method="post" accept-charset="utf-8">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group required">
                        <label for="name">Ledger name</label>
                        <input name="name" class="form-control" maxlength="255" type="text" id="name" required="required">
                    </div>
                    <div class="form-group required">
                        <label for="LedgerGroupId">Parent group</label>
                        <select name="group_id" class="form-control" id="LedgerGroupId">

                            @foreach($parentGroupList as $group)
                                <optgroup label="{{$group->name}}">
                                @php $childGroupList=$group->getChildGroup($group->id); @endphp
                                    @foreach($childGroupList as $key=>$childGorup)
                                                <option value="{{$childGorup->id}}">{{$childGorup->name}}</option>
                                    @endforeach
                                </optgroup>

                               @endforeach
                        </select>
                    </div>
                    <label for="LedgerOpeningBalance">Opening Balance</label>
                    <table>
                        <tbody>
                        <tr class="table-top">
                            <td class="width-drcr" style="width: 80px;">
                                <div class="form-group required">
                                    <select name="dr_cr_status" class="form-control" id="dr_cr_status">
                                        <option value="">Select</option>
                                        <option value="Dr">Dr</option>
                                        <option value="Cr">Cr</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group required">
                                    <input name="balance" class="form-control" step="any" maxlength="28" type="number" id="balance">
                                    <span class="help-block">Note : Assets / Expenses always have Dr balance and Liabilities / Incomes always have Cr balance.</span>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="form-group required">
                        <div class="checkbox">
                            <label for="LedgerType">
                                <input type="checkbox" name="cash_acc" class="checkbox" value="1" id="cash_acc"> Bank or cash account
                            </label>
                        </div>
                        <span class="help-block">Note : Select if the ledger account is a bank or a cash account.</span>
                    </div>
                    <div class="form-group required">
                        <div class="checkbox">
                            <label for="reconciliation">
                                <input type="checkbox" name="reconciliation" class="checkbox" value="1" id="reconciliation"> Reconciliation
                            </label>
                        </div>
                        <span class="help-block">Note : If selected the ledger account can be reconciled from Reports &gt; Reconciliation.</span>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="Submit">
                        <span class="link-pad"></span>
                        <a href="/finance/accounts/show" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
@endsection