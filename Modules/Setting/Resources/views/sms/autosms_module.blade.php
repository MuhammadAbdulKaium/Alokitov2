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
            <div class="col-md-6">

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
                @if(!empty($smsGetwayProfile))
                    <form action="/setting/sms/getway/update/" method="post" id="auto_sms_module">
                        <input type="hidden" name="getway_id" value="{{$smsGetwayProfile->id}}">
                        @else

                            <form action="/setting/sms/autosmsmodule/store/" method="post" id="auto_sms_module">

                                @endif

                                <input type="hidden" name="_token" value="{{csrf_token()}}">

                                <div class="form-group">
                                    <label for="pwd">Module Name:</label>
                                    <input type="text" id="status_code" required value="@if(!empty($smsGetwayProfile)) {{$smsGetwayProfile->remark}} @endif " class="form-control" name="status_code">

                                </div>

                                <div class="form-group">
                                    <label for="up-to-date">Description </label>
                                    <textarea class="form-control" name="description"></textarea>
                                </div>



                                <div class="form-group">
                                    <label for="up-to-date">Status </label>
                                    <select class="form-control" name="status">
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">Deactive</option>
                                    </select>
                                </div>

                                @if(!empty($smsGetwayProfile))

                                    <button type="submit" class="btn btn-primary">Update</button>
                                @else
                                    <button type="submit" class="btn btn-success">Submit</button>
                                @endif
                            </form>
            </div>



            <div class="col-md-6">
                <div class="auto_sms_module_list" style="border: 1px solid #efefef; padding: 15px;">
                    @if($autoSmsModuleList->count()>0)
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Modules Name</th>
                                {{--<th>Action</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($autoSmsModuleList as $module)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$module->status_code}}</td>
                                    <td><a href="/setting/sms_modules/update/{{$module->id}}" class="btn btn-block btn-default btn-icon glyphicons" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil"></i> </a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        Auto Sms Module Not Found
                    @endif
                </div>
            </div>
        </div>

    </div>
    </div>



@endsection

