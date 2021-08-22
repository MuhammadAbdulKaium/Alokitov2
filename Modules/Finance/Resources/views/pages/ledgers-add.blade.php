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
            <div class="col-md-6">
                @if(Session::has('message'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('message') }}</div>
                @endif

                <form action="{{URL::to('finance/ledger/store')}}" id="LedgerStore" method="post" accept-charset="utf-8">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="ledger_id" @if(!empty($ledgerProfile)) value="{{$ledgerProfile->id}}" @endif>
                <div class="box-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input id="name" type="text" @if(!empty($ledgerProfile)) value="{{$ledgerProfile->name}}" @endif  class="form-control" name="name" required="" data-validation-required-message="Value Required">
                    </div>
                    <div class="form-group">
                        <label>Ledger Code</label>
                        <input id="ledger_code" type="text" @if(!empty($ledgerProfile)) value="{{$ledgerProfile->ledger_code}}" @endif class="form-control" name="ledger_code" data-validation-required-message="Value Required">
                    </div>
                    <div class="form-group">
                        <label>Parent Group</label>
                        <select id="group_id" name="group_id" class="form-control" style="width: 100%;" required="">
                            <option value="">Please Select</option>
                            @foreach($parentGroupList as $group)
                                <optgroup label="{{$group->name}}">
                                    @php $childGroupList=$group->getChildGroup($group->id); @endphp
                                    @foreach($childGroupList as $key=>$childGorup)
                                        <option @if(!empty($ledgerProfile) &&($ledgerProfile->group_id==$childGorup->id)) selected @endif value="{{$childGorup->id}}">{{$childGorup->name}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach

                         </select>
                    </div>
                    <div class="form-group">
                        <label>Opening Balance</label>
                        <div class="row">
                            <div class="col-md-2">
                                <select id="dr_cr_status" name="dr_cr_status" class="form-control select2" style="width: 100%;" required="">
                                    <option @if(!empty($ledgerProfile) && ($ledgerProfile->dr_cr_status=='D')) selected @endif value="D">Dr</option>
                                    <option @if(!empty($ledgerProfile) && ($ledgerProfile->dr_cr_status=='C')) selected @endif value="C">Cr</option>
                                </select>
                            </div>
                            <div class="col-md-10">
                                <input id="balance" @if(!empty($ledgerProfile->balance))  value="{{$ledgerProfile->balance}}" @endif type="text" class="form-control" name="balance" data-validation-required-message="Value Required">
                                <span class="help-block">Note : Assets / Expenses always have Dr balance and Liabilities / Incomes always have Cr balance.</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Bank Or Cash Account</label>
                        <input class="flat-red" id="ledger_type" value="1" name="cash_acc" type="checkbox">
                        <span class="help-block">Note : Select if the ledger account is a bank or a cash account.</span>
                    </div>
                    <div class="form-group">
                        <label>Reconciliation</label>
                        <input class="flat-red" id="ledger_reconciliation" value="1" name="ledger[reconciliation]" type="checkbox">
                        <span class="help-block">Note : If selected the ledger account can be reconciled from Reports &gt; Reconciliation.</span>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes"  class="form-control" rows="3" maxlength="500" cols="30" id="notes">@if(!empty($ledgerProfile->notes))  {{$ledgerProfile->notes}} @endif</textarea>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-left">Submit</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
@endsection