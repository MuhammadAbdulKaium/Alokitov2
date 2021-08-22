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
                    <td class="col-sm-3"><img src="{{URL::asset('assets/users/images/'.$institute->logo)}}" alt="No Image" height="80px"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav-tabs margin-bottom nav" id="">
                <li @if($page == "getway") class="active" @endif  id="#">
                    <a href="{{url('setting/sms/setting/getway')}}">Sms Setting Getaway</a>
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
                    <form action="/setting/sms/getway/update/" method="post" id="sms_institution_getway_update">
                        <input type="hidden" name="getway_id" value="{{$smsGetwayProfile->id}}">
                        @else

                            <form action="/setting/sms/getway/store/" method="post" id="sms_institution_getway_store">

                                @endif

                                <input type="hidden" name="_token" value="{{csrf_token()}}">



                                <div class="form-group">
                                    <label for="pwd">API Path:</label>
                                    @if(!empty($smsGetwayProfile))

                                        <div class="form-group">
                                            <input type="text" required  id="api_path" class="form-control" value="{{$smsGetwayProfile->api_path}}" name="api_path">
                                        </div>

                                    @else
                                        <div class="input-group control-group after-add-more">
                                            <input class="form-control api_path"  name="api_path[]" type="text" placeholder="Type Api Path">
                                            <div class="input-group-btn">
                                                <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> </button>
                                            </div>
                                        </div>

                                    @endif

                                </div>

                                <div class="form-group">
                                    <label for="remark"> Sender ID </label>
                                    <input type="text" id="sender_id" required value="@if(!empty($smsGetwayProfile)) {{$smsGetwayProfile->sender_id}} @endif " class="form-control" name="sender_id">
                                </div>


                                <div class="form-group">
                                    <label for="up-to-date"> Up to Date </label>
                                    <input type="text" id="dueDate" required value="@if(!empty($smsGetwayProfile)) {{date('d-m-Y',strtotime($smsGetwayProfile->activated_upto))}} @endif" class="form-control" name="activated_upto">
                                </div>


                                <div class="form-group">
                                    <label for="remark"> Remark </label>
                                    <input type="text" id="remark" required value="@if(!empty($smsGetwayProfile)) {{$smsGetwayProfile->remark}} @endif " class="form-control" name="remark">
                                </div>

                                <div class="form-group">
                                    <label for="up-to-date"> Status </label>
                                    <select  id="status" required  class="form-control" name="status">
                                        <option value="">Select Status</option>
                                        <option value="1" @if(!empty($smsGetwayProfile->status) && ($smsGetwayProfile->status==1) ) selected @endif>Single</option>
                                        <option value="2" @if(!empty($smsGetwayProfile->status) && ($smsGetwayProfile->status==2)) selected @endif>Multiple</option>
                                    </select>
                                </div>

                                @if(!empty($smsGetwayProfile))

                                    <button type="submit" class="btn btn-primary">Update</button>
                                @else
                                    <button type="submit" class="btn btn-default">Submit</button>
                @endif
            </div>


            </form>
        </div>
    </div>
    </div>


    <!-- Copy Fields-These are the fields which we get through jquery and then add after the above input,-->
    <div class="copy-fields hide">
        <div class="control-group input-group" style="margin-top:10px">
            <input class="form-control api_path" required  name="api_path[]" type="text" placeholder="Type Api Path">
            <div class="input-group-btn">
                <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> </button>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    //here first get the contents of the div with name class copy-fields and add it to after "after-add-more" div class.
    $(".add-more").click(function(){
    var html = $(".copy-fields").html();
    $(".after-add-more").after(html);
    });

    //here it will remove the current value of the remove button which has been pressed
    $("body").on("click",".remove",function(){
    $(this).parents(".control-group").remove();
    });


    $('#dueDate').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});



@endsection

