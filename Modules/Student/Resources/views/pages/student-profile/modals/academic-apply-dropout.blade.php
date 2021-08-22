<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-ban" aria-hidden="true"></i> Apply Dropout
    </h4>
</div>
<form id="apply-dropout">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="history_id" value="{{$historyProfile->id}}">
    @php $enroll = $historyProfile->enroll(); @endphp
    @php $student = $enroll->student(); @endphp
    <div class="modal-body">
        <table class="table table-bordered">
            <colgroup>
                <col width="20%">
                <col width="30%">
                <col width="20%">
                <col width="30%">
            </colgroup>
            <tbody>
            <tr>
                <th>Student</th>
                <td colspan="3" id="stuName">{{$student->first_name.' '.$student->middle_name.' '.$student->last_name}}</td>
                <input id="std_id" type="hidden" name="std_id" value="{{$student->id}}">
            </tr>
            <tr>
                <th>Admission Year</th>
                @php $academicYear = $enroll->academicsYear(); @endphp
                <td>{{$academicYear->year_name}}</td>

                {{--@php $academicYear = $enroll->academicsYear(); @endphp--}}
                <th>Academic Year</th>
                <td>{{$enroll->academicsYear()->year_name}}</td>
            </tr>
            @php $division = null; @endphp
            @if($divisionInfo = $enroll->batch()->get_division())
                @php $division = " (".$divisionInfo->name.") "; @endphp
            @endif
            <tr>
                <th>Level</th>
                @php $level = $enroll->level(); @endphp
                <td>{{$level->level_name}}</td>

                <th>Batch</th>
                @php $batch = $enroll->batch(); @endphp
                <td>{{$batch->batch_name.$division}}</td>
            </tr>
            <tr>
                <th>Section</th>
                @php $section = $enroll->section(); @endphp
                <td>{{$section->section_name}}</td>

                <th>Roll</th>
                <td>{{$enroll->gr_no}}</td>
            </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="remark">Remark</label>
                    <textarea id="remark" class="form-control" name="remark" required></textarea>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>
    <!--./modal-body-->
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left">Submit</button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
    <!--./modal-footer-->
</form>


<script type="text/javascript">

    $(document).ready(function () {
        // request for section list using batch and section id
        $('form#apply-dropout').on('submit', function (e) {
            e.preventDefault();

            // class section details
            var std_id = $("#std_id").val();
            var remark = $("#remark").val();
            // checking
            if(std_id && remark){
                // ajax request
                $.ajax({
                    url: "/student/enrol-detail/apply-dropout",
                    type: 'POST',
                    cache: false,
                    data: $('form').serialize(),
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // show waiting dialog
                        waitingDialog.hide();
                        // checking
                        if(data.status=='success'){

                            // h_id
                            var h_id = data.h_id;
                            // change history icon
                            $('#enroll_icon_'+h_id).removeClass('fa-level-up bg-green');
                            $('#enroll_icon_'+h_id).addClass('fa-ban bg-red');

                            // change history label
                            $('#enroll_label_'+h_id).removeClass('label-primary');
                            $('#enroll_label_'+h_id).addClass('label-danger');
                            $('#enroll_label_'+h_id).html('DROPOUT');

                            $('#enroll_dropout_'+h_id).remove();

                            // hide modal
                            $('#globalModal').modal('hide');

                            swal("Success", data.msg, "success");
                        }else {
                            // sweet alert
                            swal("Warning", data.msg, "warning");
                        }
                    },

                    error:function(){
                        // show waiting dialog
                        waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            }else{
                // sweet alert
                swal("Warning", 'Please double check all inputs are selected.', "warning");
            }
        });
    });


</script>
