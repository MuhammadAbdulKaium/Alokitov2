@extends('finance::layouts.master')
@section('section-title')
    <h1>
        <i class="fa fa-search"></i>Finance       </h1>
    <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/library/default/index">Finacne</a></li>
        <li class="active">Manage Account</li>
    </ul>


@endsection
<!-- page content -->
@section('page-content')
    <div class="box box-solid">

            <h2> Accounts</h2>
            <div class="wzuser account form col-md-10">
                <h4 style="margin-bottom:20px; ">Select account to open</h4>
                <form action="/finance/wzusers/account" id="WzuserAccountForm" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"><input type="hidden" name="data[_Token][key]" value="abf3601f86f591949f08f9fe717276fbf1b7955b" id="Token86802027"></div><label for="WzuserActive">Currently open account : "(NONE)"</label><div class="form-group"><label for="WzuserWzaccountId">Select account</label><select name="data[Wzuser][wzaccount_id]" class="form-control" id="WzuserWzaccountId">
                            <option value="0" selected="selected">(NONE)</option>
                            <option value="1">school001</option>
                        </select><span class="help-block">Note : If you wish to use multiple accounts simultaneously, please use different browsers for each. Also, please select (NONE) if you wish to deactivate all accounts.</span></div><div class="form-group"><input class="btn btn-primary" type="submit" value="Open"><span class="link-pad"></span><a href="/finance/dashboard" class="btn btn-default">Cancel</a></div><div style="display:none;"><input type="hidden" name="data[_Token][fields]" value="0654b1658d3418c149365ed3d991f9b36a4b74f0%3A" id="TokenFields94991304"><input type="hidden" name="data[_Token][unlocked]" value="" id="TokenUnlocked1874435370"></div></form>    </div>
        </div>
        <div class="col-md-6">
            <a href="#">
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

            <a href="#">
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


