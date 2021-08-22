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

    <div class="box box-solid">
        <div class="col-md-6">
            @if(Session::has('message'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('message') }}</div>
            @endif
            <div class="wzuser account form col-md-10">
                <h4 style="margin-bottom:20px; ">Select account to open</h4>
                <form action="/finance/account/active" id="accountActive" method="post" accept-charset="utf-8">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                       <label for="accountAccount">Currently open account :
                        @if(empty($activeAccount))
                        "(NONE)"
                            @else
                            {{$activeAccount->account_name}}
                        @endif
                    </label>
                    <div class="form-group">
                        <label for="WzuserWzaccountId">Select account</label>
                        <select name="account_id" class="form-control" id="accountId">
                            <option value="">Select Account</option>
                            @foreach($accountList as $account)
                            <option @if(!empty($activeAccount) && ($activeAccount->id==$account->id)) selected @endif value="{{$account->account_id}}">{{$account->account_name}}</option>

                             @endforeach
                        </select>
                        <span class="help-block">Note : If you wish to use multiple accounts simultaneously, please use different browsers for each. Also, please select (NONE) if you wish to deactivate all accounts.</span>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="Open">
                        <span class="link-pad"></span>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <a href="{{URL::to('/finance/account/create')}}">
                <div class="col-md-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>Create</h3>
                            <h4>Account</h4>
                        </div>
                        <div class="icon">
                            <i class="fa fa-plus"></i>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{URL::to('/finance/account/manage')}}">
                <div class="col-md-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>Manage</h3>
                            <h4>Account</h4>
                        </div>
                        <div class="icon">
                            <i class="fa fa-list"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="clearfix"></div>
    </div>


@endsection

@section('page-script')




@endsection


