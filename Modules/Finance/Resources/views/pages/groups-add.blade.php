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
            @if(Session::has('message'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('message') }}</div>
            @endif
            <div class="groups add form col-md-5">
                <form action="/finance/groups/store" id="GroupAddForm" method="post" accept-charset="utf-8">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="group_id" @if(!empty($groupProfile)) value="{{$groupProfile->id}}" @endif >
                      <div class="form-group required">
                          <label for="GroupName">Group name</label>
                          <input name="group_name" class="form-control" maxlength="255" type="text" @if(!empty($groupProfile)) value="{{$groupProfile->name}}" @endif id="GroupName" required="required">
                      </div>

                    <div class="form-group required">
                        <label for="GroupName">Group Code</label>
                        <input name="group_code" class="form-control" maxlength="255" type="text" @if(!empty($groupProfile)) value="{{$groupProfile->group_code}}" @endif id="GroupCode" required="required">
                    </div>

                    <div class="form-group required">
                        <label for="GroupParentId">Parent group</label>
                        <select name="group_parent_id" class="form-control" id="GroupParentId">
                            <option value="">Select One</option>
                            @foreach($parentGorup as $group)
                                    <option @if($groupProfile->parent_id==$group->id) selected @endif value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="active_account" value="{{$groupProfile->account_id}}">

                    <div class="form-group">
                        <input class="btn btn-success" type="submit" value="Submit">
                    </div>

                </form>
            </div>


        </div>
    </div>
@endsection
@section('page-script')
@endsection