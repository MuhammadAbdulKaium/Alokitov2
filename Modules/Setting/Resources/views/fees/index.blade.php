@extends('setting::layouts.master')

@section('section-title')
    <h1>
        <i class="fa fa-th-list"></i> Fees <small>Setting</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Configuration</li>
        <li class="active">Fees</li>
    </ul>
@endsection

@section('page-content')
    <div class="panel panel-default">

        <div class="panel-body">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>

            @elseif(Session::has('error'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('error') }} </h4>
                </div>

            @endif
            <div class="col-md-5">

                <form action="/setting/fees/setting/store" method="post" id="fees_setting">
                    <input type="hidden" @if(!empty($feesSettingProfile)) value="{{$feesSettingProfile->id}}" @endif name="fees_setting_id">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group">
                        <label for="pwd">Attribute Name</label>
                        <input type="text" id="attribute" @if(!empty($feesSettingProfile)) value="{{$feesSettingProfile->attribute}}" @endif required class="form-control" name="attribute">
                    </div>
                    <div class="form-group">
                        <label for="up-to-date">Setting Type </label>
                        <select class="form-control" name="setting_type">
                            <option value="">Select Type</option>
                            <option @if(!empty($feesSettingProfile->setting_type)==1) selected="selected" @endif  value="1">Fine</option>
                            <option @if(!empty($feesSettingProfile->setting_type)==2) selected="selected" @endif  value="2">Tuition Fees</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Value</label>
                        <input type="text" id="value" required class="form-control" @if(!empty($feesSettingProfile)) value="{{$feesSettingProfile->value}}" @endif name="value">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>
    </div>

@endsection

@section('page-script')


@endsection

