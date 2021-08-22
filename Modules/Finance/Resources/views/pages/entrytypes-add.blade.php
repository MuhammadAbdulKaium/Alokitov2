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
        <div style="padding:25px;">
            <div class="entrytypes add form col-md-5">
                <form action="/finance/entrytypes/add" id="EntrytypeAddForm" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"><input type="hidden" name="data[_Token][key]" value="9bc33bb485a5da6bd5b60f1ab1641f071a5e7979" id="Token1341991160"></div><div class="form-group required"><label for="EntrytypeLabel">Label</label><input name="data[Entrytype][label]" class="form-control" maxlength="255" type="text" id="EntrytypeLabel" required="required"></div><div class="form-group required"><label for="EntrytypeName">Name</label><input name="data[Entrytype][name]" class="form-control" maxlength="255" type="text" id="EntrytypeName" required="required"></div><div class="form-group"><label for="EntrytypeDescription">Description</label><textarea name="data[Entrytype][description]" class="form-control" rows="3" cols="30" id="EntrytypeDescription"></textarea></div><div class="form-group required"><label for="EntrytypeNumbering">Numbering</label><select name="data[Entrytype][numbering]" class="form-control" id="EntrytypeNumbering">
                            <option value="1">Auto</option>
                            <option value="2">Manual (required)</option>
                            <option value="3">Manual (optional)</option>
                        </select><span class="help-block">Note : How the entry numbering is handled.</span></div><div class="form-group"><label for="EntrytypePrefix">Prefix</label><input name="data[Entrytype][prefix]" class="form-control" maxlength="255" type="text" id="EntrytypePrefix"><span class="help-block">Note : Prefix to add before entry numbers.</span></div><div class="form-group"><label for="EntrytypeSuffix">Suffix</label><input name="data[Entrytype][suffix]" class="form-control" maxlength="255" type="text" id="EntrytypeSuffix"><span class="help-block">Note : Suffix to add after entry numbers.</span></div><div class="form-group"><label for="EntrytypeZeroPadding">Zero Padding</label><input name="data[Entrytype][zero_padding]" class="form-control" type="number" id="EntrytypeZeroPadding"><span class="help-block">Note : Number of zeros to pad before entry numbers.</span></div><div class="form-group required"><label for="EntrytypeRestrictionBankcash">Restrictions</label><select name="data[Entrytype][restriction_bankcash]" class="form-control" id="EntrytypeRestrictionBankcash">
                            <option value="1">Unrestricted</option>
                            <option value="2">Atleast one Bank or Cash account must be present on Debit side</option>
                            <option value="3">Atleast one Bank or Cash account must be present on Credit side</option>
                            <option value="4">Only Bank or Cash account can be present on both Debit and Credit side</option>
                            <option value="5">Only NON Bank or Cash account can be present on both Debit and Credit side</option>
                        </select><span class="help-block">Note : Restrictions to be placed on the ledgers selected in entry.</span></div><div class="form-group"><input class="btn btn-primary" type="submit" value="Submit"><span class="link-pad"></span><a href="/finance/entrytypes" class="btn btn-default">Cancel</a></div><div style="display:none;"><input type="hidden" name="data[_Token][fields]" value="a12a4d653cb6395c8660be962f1efaa4d31bd1e0%3A" id="TokenFields1803430578"><input type="hidden" name="data[_Token][unlocked]" value="" id="TokenUnlocked1911147407"></div></form></div>


        </div>
    </div>
@endsection
@section('page-script')
@endsection