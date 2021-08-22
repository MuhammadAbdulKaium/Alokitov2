<form action="{{url('/academics/manage/assessments/report-card/download/single')}}" method="POST" target="_blank">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="std_id" value="{{$stdId}}">
    <input id="download_report_format" type="hidden" name="report_format" value="">

    {{--modal-header--}}
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-plus-square"></i> Student Report card Type</h4>
    </div>

    {{--modal-body--}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label class="control-label text-center">Select Report Type</label> <br/>
                    <label class="radio-inline"><input type="radio" name="report_type" value="subject_detail"> Subject Details </label>
                    <label class="radio-inline"><input type="radio" name="report_type" value="subject_group"> Subject Group </label>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" href="#" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-info pull-right">Submit</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        // find report_format
        var report_format = $("input:radio[name=report_format]:checked").val();
        // checking
        if(report_format != undefined){
            // update report type
            $('#download_report_format').val(report_format);
        }else{
            // update report type
            $('#download_report_format').val(0);
        }

    })
</script>