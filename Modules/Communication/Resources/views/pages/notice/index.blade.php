@extends('communication::layouts.master')
@section('section-title')
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small> Notice</small>        </h1>
        <ul class="breadcrumb"><li><a href="{{URL::to('/home')}}"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="{{URL::to('/communication/notice/')}}">Communication</a></li>
        </ul>
    </section>

@endsection
<!-- page content -->
@section('page-content')
    {{--<section class="content">--}}
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-plus-square"></i> Notice @if(!empty($noticeProfile)) Update  @else Create @endif</h3>
            </div>

            <form id="notice-form"   @if(!empty($noticeProfile->id))   action="/communication/notice/update"    @else     action="/communication/notice/create"       @endif
                  method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="notice_id" @if(!empty($noticeProfile->id)) value="{{$noticeProfile->id}}" @endif>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group field-notice-notice_title required">
                                <label class="control-label" for="notice-notice_title">Title</label>
                                <input id="notice-notice_title" class="form-control" required name="notice_title" maxlength="255" placeholder="Enter Notice Title" aria-required="true" type="text"  @if(!empty($noticeProfile->title)) value="{{$noticeProfile->title}}" @endif>

                                <div class="help-block"></div>
                            </div>		</div>
                        <div class="col-sm-6">
                            <div class="form-group field-notice-notice_date required">
                                <label class="control-label" for="notice-notice_date">Date</label>
                                <input id="notice-date" class="form-control" required name="notice_date" @if(!empty($noticeProfile->notice_date)) value="{{date('d-m-Y',strtotime($noticeProfile->notice_date))}}" @endif placeholder="Select Notice Date" size="10" type="text">

                                <div class="help-block"></div>
                            </div>		</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group field-notice-notice_description required">
                                <label class="control-label" for="notice-notice_description">Description</label>
                                <textarea id="notice-notice_description" required  class="form-control" name="notice_description"  placeholder="Enter Notice Description">@if(!empty($noticeProfile->desc)) {{$noticeProfile->desc}} @endif</textarea>
                                <div class="help-block"></div>
                            </div>		</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group field-notice-notice_user_type required">
                                <label class="control-label">User Type</label>
                                <div id="notice-notice_user_type" aria-required="true">
                                    <label><input name="notice_user_type" required value="4" @if(!empty($noticeProfile->user_type) && ($noticeProfile->user_type==4)) checked="checked" @endif type="radio"> Student</label>
                                    <label><input name="notice_user_type" required value="3" @if(!empty($noticeProfile->user_type) && ($noticeProfile->user_type==3)) checked="checked" @endif type="radio"> Employee</label>
                                    <label><input name="notice_user_type" required value="2" @if(!empty($noticeProfile->user_type) && ($noticeProfile->user_type==2)) checked="checked" @endif type="radio"> Parent</label>
                                    <label><input name="notice_user_type" required value="1" @if(!empty($noticeProfile->user_type) && ($noticeProfile->user_type==1)) checked="checked" @endif type="radio"> General</label></div>

                                <div class="help-block"></div>
                            </div>		</div>
                        <div class="col-sm-6">
                            <div class="form-group field-notice-notice_file_path">
                                <label class="control-label" for="notice-notice_file_path">Notice File</label>
                                <input id="notice-notice_file_path" @if(!empty($noticeProfile->notice_file)) value="{{$noticeProfile->notice_file}}"  @endif name="notice_file_path" type="file">

                                <div class="help-block"></div>
                            </div>		</div>
                    </div>

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-create">Save</button>	<a class="btn btn-default btn-create" href="/dashboard/notice/index">Cancel</a></div>

            </form>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-search"></i> Notice		</h3>
                </div>
                @if($notices->count()>0)
                    <div class="box-body">
                        <table class="table table-striped table-bordered"><thead>
                            <tr>
                                <th>Serial No.</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>User Type</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php $i=1; @endphp
                            @foreach($notices as $notice)
                                <tr data-key="4">
                                    <td>{{$i++}}</td>
                                    <td>{{$notice->title}}</td>
                                    <td>{{$notice->desc}}</td>
                                    <td>
                                        @if($notice->user_type==1)
                                            General
                                        @elseif($notice->user_type==2)
                                            Parents
                                        @elseif($notice->user_type==3)
                                            Employee
                                        @elseif($notice->user_type==4)
                                            Student
                                        @endif
                                    </td>
                                    <td>{{date('d-m-Y',strtotime($notice->notice_date))}}</td>
                                    <td>
                                        @if($notice->status==1)
                                           <span id="activeNoticeStatus{{$notice->id}}" class="label label-success">Active</span>
                                        @elseif($notice->status==2)
                                            <span id="cancelNoticeStatus{{$notice->id}}" class="label label-danger">Cancel</span>
                                        @endif
                                            <span id="cancelNoticeStatus{{$notice->id}}" class="label label-danger" style="display: none">Cancel</span>
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-xs" href="/communication/notice/edit/{{$notice->id}}" ><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-info btn-xs" href="/communication/notice/view/{{$notice->id}}"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-eye"></i></a>
                                        @if(!$notice->status==2)
                                        <a  id="{{$notice->id}}" class="btn btn-danger btn-xs notice_delete" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        @endif
                                        @if($notice->status==1)
                                        <a  id="{{$notice->id}}" class="btn btn-primary btn-xs notice_cancel"  data-placement="top" data-content="cancel"><i class="fa fa-times"></i></a>
                                        @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="link" style="float: right">
                            {{$notices->links()}}
                        </div>
                    </div>



            </div>
            @else
                <div class="container">

                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 406.049;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="fa fa-warning"></i> No result found. </h5>
                </div>
                </div>

            @endif

        </div><!-- /.box-->

        </div>



@endsection

@section('page-script')


    $('#notice-date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

        {{--<script>--}}

    //invoice cancel

    // Notice status Cancel Ajax Request
    $('.notice_cancel').click(function() {

    var x = confirm("Are you sure you want to delete?");
    if(x) {
        var notice_id= $(this).attr('id')

        // ajax request
        $.ajax({

            url: '/communication/notice/notice_cancel/'+notice_id,
            type: 'GET',
            cache: false,
            beforeSend: function() {
                {{--alert($('form#Partial_allowForm').serialize());--}}
            },

            success:function(data){
                $('#'+notice_id).hide();
                $('#activeNoticeStatus'+notice_id).hide();
                $('#cancelNoticeStatus'+notice_id).show();
            },

            error:function(data){
                alert(JSON.stringify(data));
            }
        });
    }

    });


    //invoice delete

            // invoice delete ajax request
            $('.notice_delete').click(function(){
                var tr = $(this).closest('tr'),
                    del_id = $(this).attr('id');

                $.ajax({
                    url: "/communication/notice/delete/"+ del_id,
                    type: 'GET',
                    cache: false,
                    success:function(result){
                        tr.fadeOut(1000, function(){
                            $(this).remove();
                        });
                    }
                });
            });



@endsection

