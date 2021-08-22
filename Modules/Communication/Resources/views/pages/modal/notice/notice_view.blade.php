
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h4>
                <i class="fa fa-eye"></i> View Notice    </h4>
        </div>

        <div class="modal-body">
            <table id="w0" class="table table-striped table-bordered detail-view"><tbody><tr><th>Title</th><td>{{$noticeProfile->title}} </td></tr>
                <tr><th>Description</th><td>{{$noticeProfile->desc}}  </td></tr>
                <tr><th>User Type</th><td>

                        @if(!empty($noticeProfile->user_type) && ($noticeProfile->user_type==4))
                            Student
                        @elseif(!empty($noticeProfile->user_type) && ($noticeProfile->user_type==3))
                            Employer
                        @elseif(!empty($noticeProfile->user_type) && ($noticeProfile->user_type==2))
                            Parents
                        @elseif(!empty($noticeProfile->user_type) && ($noticeProfile->user_type==1))
                            General
                        @endif

                    </td></tr>


                <tr><th>Date</th><td>{{date('d-m-Y',strtotime($noticeProfile->notice_date))}}</td></tr>
                @if(!empty($noticeProfile->content()->file_name))
                                    <tr><th>Notice File</th><td><a href="{{URL::to('/assets/communication/notice/'.$noticeProfile->content()->file_name)}}">Download</a> </td></tr>
                                    @endif
                @if($noticeProfile->status==1)
                <tr><th>Status</th><td><span class="label label-success"> Active </span></td></tr>
                @else
                    <tr><th>Status</th><td><span class="label label-danger"> Cancel </span></td></tr>
                @endif
                </tbody></table></div>

        <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        </div>
    </div>