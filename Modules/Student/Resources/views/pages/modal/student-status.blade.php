
<form id="student-status-update-form" action="" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="std_id" value="{{$studentProfile?$studentProfile->id:0}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title text-bold">
			<i class="fa fa-info-circle"></i> Change Cadet Status
		</h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-md-4">
				@if($studentProfile->singelAttachment("PROFILE_PHOTO"))
					<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$studentProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">
				@else
					<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">
				@endif
			</div>
			{{--find student enrollment details--}}
			@php $enrollment = $studentProfile->enroll() @endphp
			<div class="col-md-8">
				<table class="table table-responsive table-bordered table-striped">
					<tr>
						<th width="10%">Name</th>
						<td>{{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
					</tr>
					<tr>
						<th>Class</th>
						<td>{{$enrollment->batch()->batch_name}}</td>
					</tr>
					<tr>
						<th>Section</th>
						<td>{{$enrollment->section()->section_name}}</td>
					</tr>
					<tr>
						<th>Roll</th>
						<td>{{$enrollment->gr_no}}</td>
					</tr>
					<tr>
						<th>Status</th>
						<td>
							<label class="radio-inline"><input type="radio" name="status" {{$studentProfile->status==1?'checked':''}} value="1">Active</label>
							<label class="radio-inline"><input type="radio" name="status" {{$studentProfile->status==0?'checked':''}} value="0" >De-Active</label>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" id="std-status-btn" class="btn btn-primary pull-left">Submit</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</form>

<script>
    $(document).ready(function () {

        // class topper button action
        $('#std-status-btn').click(function () {
            // ajax request
            $.ajax({
                url: '/student/status',
                type: 'POST',
                cache: false,
                data: $('form#student-status-update-form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Submitting...');
                },

                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();

                    // checking status
                    if(data.status){
                        // std id
                        var std_id = data.std_id;
                        // checking status
                        var status_btn = $('#status_'+std_id);
                        // checking class
                        if (data.request_type=='1') {
                            status_btn.removeClass('text-red');
                            status_btn.addClass('text-green');
                        } else {
                            status_btn.removeClass('text-green');
                            status_btn.addClass('text-red');
                        }
                        // sweet alert success
                        swal("Success", data.msg, "success");
                    }else{
                        // sweet alert warning
                        swal("Warning", data.msg, "warning");
                    }
                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // sweet alert error
                    swal("Warning", 'No Response form server', "warning");
                }
            });

        });
    });
</script>