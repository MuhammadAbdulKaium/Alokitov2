@extends('setting::layouts.master')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

@section('section-title')

    <h1>
        <i class="fa fa-th-list"></i> Change <small>User Password</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Change</li>
        <li class="active">User Password</li>
    </ul>
@endsection

@section('page-content')
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        @if(Session::has('success'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('success') }}</p>
        @endif
    </div>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Password</h3>
        </div>

        <form autocomplete="off" id="setting-Change-Password-form" name="setting_Change-Password_form" action="{{url('setting/change/password/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="box-body">
                <div class="row">


                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Select User Type</label>
                            <select required  class="form-control" id="selectUser">
                                <option value="" >Select User</option>
                                <option value="1" >Student</option>
                                <option value="2" >Teacher</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Select User</label>
                            <input autocomplete="off" id="user_name" class="form-control" placeholder="Enter User name or E-mail address" type="text" required>
                            <input autocomplete="off" id="user_id" name="user_id" value="" type="hidden">
                            <div class="help-block">
                                @if ($errors->has('select_user'))
                                    <strong>{{ $errors->first('user_id') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Password</label>
                            <input autocomplete="off" type="password" id="password" class="form-control" name="password" maxlength="60" placeholder="Enter Password" aria-required="true">
                            <div class="help-block">
                                @if ($errors->has('password'))
                                    <strong>{{ $errors->first('password') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Confirm Password</label>
                            <input autocomplete="off" type="password" id="confirm_password" class="form-control" name="confirm_password" maxlength="60" placeholder="Enter Confirm Password"  aria-required="true">
                            <div class="help-block">
                                @if ($errors->has('confirm_password'))
                                    <strong>{{ $errors->first('confirm_password') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-create">Update</button>
                <button type="reset" class="btn btn-default btn-create">Reset</button>
            </div>
            <!-- /.box-footer-->
        </form>

    </div>

    <!-- /.box-->
@endsection

@section('page-script')

    {{--<script>--}}

            $('#user_name').keypress(function(){
                // checking user type
                if($("#selectUser").val()){
                    // empty user_id
                    $('#user_id').val('');
                    // checking
                        $(this).autocomplete({
                            source: loadFromAjax,
                            minLength: 1,

                            select: function (event, ui) {
                                // Prevent value from being put in the input:
                                this.value = ui.item.label;
                                // Set the next input's value to the "value" of the item.
                                $(this).next("input").val(ui.item.id);
                                event.preventDefault();
                            }
                        });


                        function loadFromAjax(request, response) {
                            var term = $("#user_name").val();
                            var user_type = $("#selectUser").val();
                            $.ajax({
                                url: '/setting/password/change/user',
                                dataType: 'json',
                                data:{'term': term,'user_type':user_type},
                                success: function(data) {
                                    // you can format data here if necessary
                                    response($.map(data, function (el) {
                                        return {
                                            label: el.name,
                                            value: el.name,
                                            id:el.id
                                        };
                                    }));
                                }
                            });
                        }
                }else{
                    // alert notification
                    alert('Please Select a User Type');
                    // empty user_name
                    $("#user_name").val('');
                    // empty user_id
                    $('#user_id').val('');
                }
            });

@endsection

