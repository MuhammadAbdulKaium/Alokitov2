
<section class="content">
<div class="box box-solid">
    @if(!empty($studentList)>0)
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-search"></i> Student Manage Waiver
            </h3>
        </div>
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
                <div id="w2" class="grid-view">

                    <form id="studentWaiverListForm">
                        <input type="hidden" name="_token" value="{{csrf_token()}}"/>

                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Roll</th>
                            <th>Student Name</th>
                            <th>Waiver Type</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($studentList as $student)
                            @php $waiverProfile=$student->student_waiver(); @endphp
                            <tr>
{{--                                <td>{{$waiverProfile}}</td>--}}
                                <td>{{$student->gr_no}} <input type="hidden" name="std_grno[{{$student->std_id}}]" value="{{$student->gr_no}}" ></td>
                                <td>{{$student->first_name." ".$student->middle_name." ".$student->last_name}} <input type="hidden" name="std_ids[{{$i++}}]" value="{{$student->std_id}}" ></td>
                                <td><div class="form-group field-waiver-lbt_issue_date">
                                        <select class="form-control" name="waiver_type[{{$student->std_id}}]">
                                            <option  value="">Select Waiver Type</option>
                                            <option @if(!empty($waiverProfile)) @if($waiverProfile->waiver_type==1) selected @endif @endif  value="1">General Waiver</option>
                                            <option @if(!empty($waiverProfile)) @if($waiverProfile->waiver_type==2) selected @endif @endif  value="2">Upobritti</option>
                                            <option  @if(!empty($waiverProfile)) @if($waiverProfile->waiver_type==3) selected @endif @endif  value="3">Scholarship</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group field-waiver-lbt_issue_date">
                                        <select class="form-control" name="type[{{$student->std_id}}]">
                                            <option value="">Select Type</option>
                                            <option  @if(!empty($waiverProfile)) @if($waiverProfile->type==1) selected @endif @endif value="1">Percent</option>
                                            <option  @if(!empty($waiverProfile)) @if($waiverProfile->type==2) selected @endif @endif value="2">Amount</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="value[{{$student->std_id}}]" placeholder="Value"  @if(!empty($waiverProfile)) value="{{$waiverProfile->value}}" @endif  aria-describedby="basic-addon1">
                                </td>
                                <td>
                                    <div class="form-group field-waiver-lbt_issue_date">
                                        <input class="form-control datepicker" name="start_date[{{$student->std_id}}]"   @if(!empty($waiverProfile)) value="{{date('d-m-Y',strtotime($waiverProfile->start_date))}}" @endif  size="10" type="text">

                                        <div class="help-block"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group field-waiver-lbt_due_date">
                                        <input id="waiver-lbt_due_date" class="form-control datepicker" name="end_date[{{$student->std_id}}]"  @if(!empty($waiverProfile)) value="{{date('d-m-Y',strtotime($waiverProfile->end_date))}}" @endif   name="end_date"  size="10" type="text">
                                        <div class="help-block"></div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary ">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>


            @else
                <div class="container" style="margin-top: 20px">
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                    </div>
                </div>

            @endif

        <!-- /.box-body -->

</div>
</div>
</section>


<script>


    //        $('#waiver-name').attr('disabled', 'disabled');
    $('.datepicker').datepicker({format:"dd-mm-yy"});



    // request for payers fees payer id and fees id
    $('form#studentWaiverListForm').on('submit', function (e) {
        e.preventDefault();
        var fees_id=$('.feesId_for_link').val();

        // ajax request
        $.ajax({

            url: '/student/manage/waiver/store',
            type: 'POST',
            cache: false,
            data: $('form#studentWaiverListForm').serialize(),
            datatype: 'json/application',

            beforeSend: function() {
                // alert($('form#class_section_form').serialize());
                // show waiting dialog
                waitingDialog.show('Loading...');
            },

            success:function(data){
                // hide waiting dialog
                if(data=='success') {
                    waitingDialog.hide();
                    swal("Success!", "Waiver Successfully  Added", "success");
                }

            },

            error:function(data){
                alert('error');
            }
        });


    });
</script>
