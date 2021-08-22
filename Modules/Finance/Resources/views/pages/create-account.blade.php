@extends('finance::layouts.master')
@section('section-title')
    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/library/default/index">Finacne</a></li>
        <li class="active">Manage Account</li>
    </ul>
@endsection
<!-- page content -->
@section('page-content')
    <div class="box box-body">
        <div class="form col-md-5">
            <form autocomplete="off" action="{{URL::to('/finance/account/store')}}" id="fAccountCreate" method="post" accept-charset="utf-8">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group required">
                    <label for="account_name">Account Name</label>
                    <input name="account_name" class="form-control" maxlength="255" type="text" id="accountName" required="required">
                    <span class="help-block">Note : It is recommended to use a descriptive label like "sample20142105" which includes both a short name and the accounting year.</span>
                </div>
                <div class="form-group">
                    <label for="account_id">Account Id</label>
                    <input name="account_id" class="form-control" required="required" maxlength="255" type="text" id="accountId">
                    <span class="help-block">Note : Use some unique value for this field to differentiate this account from other accounts.</span>
                </div>
                <div class="form-group required">
                    <label for="f_year_start">Financial year start</label>
                    <input id="start_date" name="f_year_start" class="form-control"  required="required" type="text">
                </div>
                <div class="form-group required">
                    <label for="f_year_end">Financial year end</label>
                    <input id="end_date" name="f_year_end" class="form-control"  required="required" type="text">
                </div>
                <div class="form-group required">
                    <label for="company_name">Company / Personal Name</label>
                    <input name="company_name" class="form-control" type="text" id="personName">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" class="form-control" rows="3" cols="30" id="address"></textarea>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input name="email" class="form-control" type="email" id="email">
                </div>
                <div class="form-group">
                    <input class="btn btn-success" type="submit" value="Submit">
                    <span class="link-pad"></span>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        $('#start_date').datepicker({format: 'dd-mm-yyyy',todayHighlight: true});
        $('#end_date').datepicker({format: 'dd-mm-yyyy',todayHighlight: true});
    </script>

@endsection