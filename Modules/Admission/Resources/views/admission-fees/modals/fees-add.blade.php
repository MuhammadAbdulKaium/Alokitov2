<form id="applicant_fees_add_form">
	<input name="_token" value="{{csrf_token()}}" type="hidden">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title">
			<i class="fa fa-info-circle"></i> {{$applicantProfile->payment_status==0?'Add Fees':'Fees Details'}}
		</h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<table class="table table-responsive table-striped table-bordered">
			<tbody>
			<tr><td colspan="2"><p class="text-bold text-center bg-blue-gradient">Application Details</p></td></tr>
			<tr>
				<th style="width: 200px">Application Status</th>
				<td>
					{{--Applicant status--}}
					@php $applicationStatus = $applicantProfile->application_status; @endphp
					@if($applicationStatus==1)
						<span class="label label-info">Active</span>
					@elseif($applicationStatus==2)
						<span class="label label-primary">Waiting</span>
					@elseif($applicationStatus==3)
						<span class="label label-danger">Disapproved</span>
					@elseif($applicationStatus==4)
						<span class="label label-success">Approved</span>
					@else
						<span class="label label-primary">Pending</span>
					@endif
				</td>
			</tr>
			<tr>
				<th style="width: 200px">Application No.</th>
				<td>{{$applicantProfile->application_no}}</td>
			</tr>
			<tr>
				<th style="width: 200px">Applied Date</th>
				<td>{{date('d M, Y', strtotime($applicantProfile->created_at))}}</td>
			</tr>
			<tr>
				<th style="width: 200px">Applicant Name</th>
				<td>
					{{$applicantProfile->name}}
					<input type="hidden" name="applicant_id" value="{{$applicantProfile->applicant_id}}"/>
				</td>
			</tr>
			<tr>
				<th style="width: 200px">Academic Year</th>
				<td>{{$applicantProfile->academicYear()->year_name}}</td>
			</tr>
			<tr>
				<th style="width: 200px">Academic Details</th>
				<td>
					{{$applicantProfile->academicLevel()->level_name}} ({{$applicantProfile->batch()->batch_name}})
				</td>
			</tr>
			</tbody>

			<tfoot>
			<tr><td colspan="2"><p class="text-bold text-center bg-blue-gradient">Payment Details</p></td></tr>
			<tr>
				<th style="width: 200px">Payment Status</th>
				<td>
					@if($applicantProfile->payment_status==1)
						<i class="label label-success">Paid</i>
					@else
						<i class="label label-danger">Not Paid</i>
					@endif
				</td>
			</tr>
			@if($applicantProfile->payment_status==1)
				<tr>
					<th style="width: 200px">Payment Date</th>
					{{--<td>{{$applicantProfile->fees()->created_at}}</td>--}}
					<td>{{date('d M, Y', strtotime($applicantProfile->fees()->created_at))}}</td>
				</tr>
			@endif
			<tr>
				<th style="width: 200px">Application Fees Amount</th>
				@php $examDetails = $applicantProfile->examDetails(); @endphp
				<td>
					@if(!empty($examDetails) AND count($examDetails)>0)
						{{$examDetails->exam_fees}}
						<input type="hidden" name="fees_amount" value="{{$examDetails->exam_fees}}"/>
					@else
						<i class="label label-danger">Fees Setting Not Found</i>
					@endif

				</td>

			</tr>
			</tfoot>
		</table>
	</div>
	<div class="modal-footer">
		@if($examDetails)
			@if($applicantProfile->payment_status==0)
				<button type="submit" class="btn btn-info pull-left">Add Fees</button>
			@else
				<a href="{{url('/admission/fees/invoice/'.$applicantProfile->applicant_id)}}" class="btn btn-success pull-left text-bold">Download Invoice</a>
			@endif
		@endif
		<a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
	</div>
</form>
<script type="text/javascript">
    $(function() { // document ready


        $('form#applicant_fees_add_form').on('submit', function (e) {
            e.preventDefault();
            // ajax request
            $.ajax({
                type: 'POST',
                cache: false,
                url: '/admission/fees/store',
                data: $('form#applicant_fees_add_form').serialize(),
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function (data) {
                    // modal dismiss

                    // checking
                    if(data.status=='success'){
                        var applicant_id = data.applicant_id;
                        // statements
                        var applicant_payment=  $('#payment_status_'+applicant_id);
                        applicant_payment.removeClass('label-danger');
                        applicant_payment.addClass('label-success');
                        applicant_payment.html('Paid');

                        var applicant_action=  $('#payment_action_'+applicant_id);
                        applicant_action.removeClass('btn-primary');
                        applicant_action.addClass('btn-success');
                        applicant_action.html('Fees Details');

                        var payment_invoice =  $('#payment_invoice_'+applicant_id);
                        payment_invoice.removeClass('hide');

                        var payment_date =  $('#payment_date_'+applicant_id);
                        payment_date.removeClass('hide');
                        payment_date.html('('+data.fees_payment_date+')');

                    }else{
                        alert('Unable to add Payment');
                    }
                    $('#globalModal').modal('toggle');
                    waitingDialog.hide();
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        });


    });
</script>

