<style>
    .ui-autocomplete {z-index:2147483647;}
    .ui-autocomplete span.hl_results {background-color: #ffff66 ;}
</style>

<div class="modal-content">

    <form id="student_waiver">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" @if(!empty($studentProfile)) value="{{$studentProfile->std_id}}" @endif name="std_id">
        <input type="hidden" @if(!empty($studentWaiverProfile)) value="{{$studentWaiverProfile->id}}" @endif name="waiver_id" id="studentWaiverId">
       @if(!empty($studentWaiverProfile))  <input type="hidden"  value="{{$studentWaiverProfile->std_id}}"  name="std_id" id="std_id"/> @endif
        <input class="form-control" id="payer" name="student_name" type="hidden" @if(!empty($studentProfile)) value="{{$studentProfile->student()->first_name.' '.$studentProfile->student()->middle_name.' '.$studentProfile->student()->last_name}}"  @endif>

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
                <h4 class="modal-title">
                    <i class="fa fa-plus-square"></i> @if(!empty($studentWaiverProfile)) Update Waiver @else Add Waiver @endif	</h4>

        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12" id="name">
                    <label class="control-label" for="libraryborrowtransaction-lbt_issue_date">Name</label>
                    <div class="form-group field-libraryborrowtransaction-name required">
                        @if(!empty($studentWaiverProfile))
                        <input class="form-control" id="payer" name="payer_name" type="text" @if(!empty($studentWaiverProfile)) value="{{$studentWaiverProfile->student()->first_name.' '.$studentWaiverProfile->student()->middle_name.' '.$studentWaiverProfile->student()->last_name}}" disabled @endif >
                        @else
                            <input class="form-control" id="payer" name="payer_name" type="text" @if(!empty($studentProfile)) value="{{$studentProfile->student()->first_name.' '.$studentProfile->student()->middle_name.' '.$studentProfile->student()->last_name}}" disabled @endif>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">Waiver Type</label>
                        <br>
                        <select class="form-control" name="waiver_type">
                            <option value="">Select Waiver Type</option>
                            <option value="1"  @if(!empty($studentWaiverProfile)) @if($studentWaiverProfile->waiver_type=="1") selected @endif @endif>General Waiver</option>
                            <option value="2"  @if(!empty($studentWaiverProfile)) @if($studentWaiverProfile->waiver_type=="2") selected @endif @endif>Upobritti</option>
                            <option value="3"  @if(!empty($studentWaiverProfile)) @if($studentWaiverProfile->waiver_type=="3") selected @endif @endif >Scholarship</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">Waiver</label>
                        <br>
                        <label class="radio-inline"><input type="radio" id="percent" name="type" value="1" @if(!empty($studentWaiverProfile)) @if($studentWaiverProfile->type=="1") checked @endif @endif>Percent</label>
                        <label class="radio-inline"><input type="radio" id="amount" name="type" value="2" @if(!empty($studentWaiverProfile)) @if($studentWaiverProfile->type=="2")checked @endif @endif >Amount</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="control-label" for="libraryborrowtransaction-lbt_issue_date">Value</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="addOn">@if(!empty($studentWaiverProfile)) @if($studentWaiverProfile->type==1) % @else  ৳ @endif @endif</span>
                        <input type="text" class="form-control" name="value" placeholder="Value" @if(!empty($studentWaiverProfile)) value="{{$studentWaiverProfile->value}}" @endif  aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-libraryborrowtransaction-lbt_issue_date required">
                        <label class="control-label" for="libraryborrowtransaction-lbt_issue_date" >Start Date</label>
                        <input class="form-control datepicker" name="start_date" readonly="" size="10" type="text" @if(!empty($studentWaiverProfile)) value="{{date('d-m-Y',strtotime($studentWaiverProfile->start_date))}}" @endif>

                        <div class="help-block"></div>
                    </div>		</div>
                <div class="col-sm-6">
                    <div class="form-group field-libraryborrowtransaction-lbt_due_date required">
                        <label class="control-label" for="libraryborrowtransaction-lbt_due_date">End Date</label>
                        <input id="libraryborrowtransaction-lbt_due_date" class="form-control datepicker"  @if(!empty($studentWaiverProfile)) value="{{date('d-m-Y',strtotime($studentWaiverProfile->end_date))}}" @endif  name="end_date" readonly="" size="10" type="text">


                        <div class="help-block"></div>
                    </div>		</div>
            </div>
        </div>

        <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            <button type="submit" class="btn btn-primary pull-left">@if(!empty($studentWaiverProfile)) Update Waiver @else Add Waiver @endif</button></div>


<ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-3" tabindex="0" style="display: none;"></ul></form>

<script type="text/javascript">

    //        $('#libraryborrowtransaction-name').attr('disabled', 'disabled');
    $('.datepicker').datepicker({format: 'dd-mm-yyyy'});


    $(document).ready(function(){
        $('input[name="type"]').change(function(){
            if($('#percent').prop('checked')){
            $("#addOn").html("");
            $("#addOn").html("%");

            }else{
                $("#addOn").html("");
                $("#addOn").html("৳");
            }
        });
    });



    // request for student waiver create
    $('form#student_waiver').on('submit', function (e) {
        e.preventDefault();


//
//        var waiverId=$('#studentWaiverId').val();
//
//        if(waiverId!=0){
//            alert('asa');
//            var update_url='/student/student-waiver/add-waiver/store/'+waiverId;
//        } else {
//            var update_url='/student/student-waiver/add-waiver/store/';
//        }

            //ajax request
            $.ajax({

                url: '/student/student-waiver/add-waiver/store',
                type: 'POST',
                cache: false,
                data: $('form#student_waiver').serialize(),
                datatype: 'json/application',

                beforeSend: function() {
//                     alert($('form#student_waiver').serialize());
                },

                success:function(data){

                    $('#globalModal').modal('hide');

                    if(data.status=='success') {
                        if (data.type == 1) {
                            toastr.info('' + data.value + ' % Waiver is added to ' + data.student_name + '(' + data.student_id + ')');
                        } else {
                            toastr.info('' + data.value + ' Taka  waiver is added to ' + data.student_name + '(' + data.student_id + ')');
                        }
                    } else if(data.status=='error') {
                        toastr.info(' ' + data.student_name + ' Waiver Already Exists');
                    }


                },

                error:function(data){
                    alert('error');
                }
            });


    });


</script>
</div>