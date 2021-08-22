<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> All Assigned Teacher</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">


            {{--batch string--}}
            @php $batchString="Class"; @endphp

            <h5>Class - Subjcet Details</h5>
            <table class="table table-bordered text-center">
                <tbody>
                <tr>
                    <th>Subject</th>
                    <th>{{$classSubjectProfile->subject()->subject_name}}</th>
                    <input id="sub_id" type="hidden" value="{{$classSubjectProfile->subject()->id}}">
                </tr>
                <tr>
                    <th>Section</th>
                    <th>{{$classSubjectProfile->section()->section_name}}</th>
                </tr>
                <tr>
                    <th>{{$batchString}}</th>
                    <th>{{$classSubjectProfile->batch()->batch_name}}@if($classSubjectProfile->batch()->get_division()) ({{$classSubjectProfile->batch()->get_division()->name}})@endif</th>
                </tr>
                </tbody>
            </table>

            <h5>Assigned Teacher</h5>
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th>Teacher Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="assignedTeacherTableBody">
                @php $i=1; @endphp
                @foreach($subjectTeacherList as $teacher)
                    <tr id="row_{{$teacher->id}}">
                        <td>{{$teacher->employee()->first_name." ".$teacher->employee()->middle_name." ".$teacher->employee()->last_name}}</td>
                        <td>{{$teacher->status}}</td>
                        <td><p class="btn btn-danger" title="Remove Teacher" style="cursor: pointer" id="{{$teacher->id}}">Remove</p></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--./modal-body-->
<div class="modal-footer">
    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
</div>
<!--./modal-footer-->


<script type="text/javascript">
    $('#assignedTeacherTableBody tr td p').click(function(){
        var teacherId = $(this).attr('id');
        var subId = $('#sub_id').val();
        // ajax request
        $.ajax({
            url: '/academics/manage/subjcet/teacher/delete/'+teacherId,
            type: 'GET',
            cache: false,
            datatype: 'application/json',
            //
            beforeSend: function() {
                console.log(teacherId);
                // show waiting dialog
                waitingDialog.show('Deleting...');
            },
            success:function(data){
                // hide waiting dialog
                waitingDialog.hide();
                // checking
                if(data.status=='success'){
                    // remove row
                    $('#row_'+teacherId).remove();
                    // checking
                    if(data.list==null){
                        $('#view_'+subId).remove();
                    }
                    // sweet alert success
                    swal("Success", 'Teacher Removed', "success");
                }else{
                    // sweet alert warning
                    swal("Warning", 'Unable to perform the action', "warning");
                }
            },
            error:function(){
                // hide waiting dialog
                waitingDialog.hide();
                // sweet alert error
                swal("Error", 'No Response form server', "error");
            }

        });

    });

</script>
