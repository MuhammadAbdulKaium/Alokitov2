@extends('setting::layouts.master')

@section('section-title')
    <h1>
        <i class="fa fa-th-list"></i> Setting <small>Sms</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Configuration</li>
        <li class="active">Language Name</li>
    </ul>
@endsection

@section('page-content')
    <div class="box box-solid">
        <div class="box-body">
            <h2> Institute Inforatmion</h2>
            <table id="w1" class="table table-striped table-bordered detail-view">
                <tbody>
                <tr class="odd">
                    <th class="col-sm-3">Name</th>
                    <td class="col-sm-3">{{$institute->institute_name}}</td>
                    <th class="col-sm-3">Alias</th>
                    <td class="col-sm-3">{{$institute->institute_alias}}</td>
                </tr>
                <tr class="even">
                    <th>Address Line 1</th>
                    <td colspan="3">{{$institute->address1}}
                    </td>
                </tr>
                <tr class="odd">
                    <th class="col-sm-3">Address Line 2</th>
                    <td class="col-sm-3"></td>
                    <th class="col-sm-3">Phone</th>
                    <td class="col-sm-3">{{$institute->phone}}</td>
                </tr>
                <tr class="even">
                    <th class="col-sm-3">Email Id</th>
                    <td class="col-sm-3"><a href="mailto:{{$institute->email}}">{{$institute->email}}</a></td>
                    <th class="col-sm-3">Website</th>
                    <td class="col-sm-3"><a href="{{$institute->website}}">{{$institute->website}}</a></td>
                </tr>
                <tr class="odd">
                    <th class="col-sm-3">Logo</th>
                    <td class="col-sm-3"><img src="{{URL::asset('assets/users/images/'.$institute->logo)}}"  height="80px" alt="No Image"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>



    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav-tabs margin-bottom nav" id="">
                <li @if($page == "getway") class="active" @endif  id="#">
                    <a href="{{url('setting/sms/setting/getway')}}">Sms Setting Getway</a>
                </li>
                <li @if($page == "autosmsmodule") class="active" @endif  id="#">
                    <a href="{{url('setting/sms/setting/autosmsmodule')}}">Auto Sms Module</a>
                </li>
                <li @if($page == "autosmssetting") class="active" @endif  id="#">
                    <a href="{{url('setting/sms/setting/autosmssetting')}}">Auto Sms Setting</a>
                </li>

            </ul>
        </div>


        <div class="panel-body">
            <div class="col-md-12">

                @if(Session::has('success'))
                    <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                    </div>

                @elseif(Session::has('update_msg'))
                    <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('update_msg') }} </h4>
                    </div>

                @elseif(Session::has('warning'))
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                    </div>

                @endif

                <div class="auto_sms_module_list" style="border: 1px solid #efefef; padding: 15px;">
                    @if($autoSmsModuleList->count()>0)
                        <form action="/setting/sms/autosmssetting/store/" method="post" id="auto_sms_setting">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="sms_setting" value="{{$autoSmsSettingsList->count()}}">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Modules Name</th>
                                    <th>User Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1; $j=0;@endphp
                                @foreach($autoSmsModuleList as $module)
                                    <tr>
                                        <td>{{$module->status_code}}</td>
                                        <input type="hidden" name="auto_sms_module[{{$module->$i}}]" value="{{$module->id}}">
                                        <td><div class="form-group">

                                                <label class="radio-inline">
                                                    <input value="4" id="user_type" name="user_type-{{$module->id}}[]" type="checkbox" @if(!empty($userTypeArray)) @if(in_array(4,$userTypeArray[$j])) checked @endif @endif>
                                                    Guardian                                    </label>
                                                <label class="radio-inline">
                                                    <input value="2"  id="user_type" name="user_type-{{$module->id}}[]" type="checkbox"  @if(!empty($userTypeArray)) @if(in_array(2,$userTypeArray[$j])) checked @endif @endif>
                                                    Student   </label>

                                                <label class="radio-inline">
                                                    <input value="1"  id="user_type" name="user_type-{{$module->id}}[]" type="checkbox" @if(!empty($userTypeArray)) @if(in_array(1,$userTypeArray[$j])) checked @endif @endif>
                                                    Teacher                                    </label>

                                                <label class="radio-inline">
                                                    <input value="3"  id="user_type" name="user_type-{{$module->id}}[]" type="checkbox"  @if(!empty($userTypeArray)) @if(in_array(3,$userTypeArray[$j])) checked @endif @endif>
                                                    Stuff                                    </label>

                                        </td>
                                    </tr>
                                    @php $i++; $j++; @endphp
                                @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            @else
                                Auto Sms Module Not Found
                    @endif
                </div>
            </div>
        </div>

    </div>


    </div>



@endsection

@section('page-script')


@endsection

