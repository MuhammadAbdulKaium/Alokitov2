
<form id="class-topper-assign-form" action="" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title text-bold">
			<i class="fa fa-info-circle"></i> Assign Class Topper
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
			@php $classTopper = $studentProfile->classTopper @endphp
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
							{{--checking class topper--}}
							@if($classTopper AND $classTopper->status==1)
								<button id="{{$studentProfile->id}}" data-key="remove" class="btn btn-danger class-topper-status" type="button">Remove</button>
							@else
								<button id="{{$studentProfile->id}}" data-key="assign" class="btn btn-primary class-topper-status" type="button">Assign</button>
							@endif
						</td>
					</tr>
				</table>
			</div>
		</div>

	</div>
</form>

<script>
    $(document).ready(function () {

        // class topper button action
        $('.class-topper-status').click(function () {
            // find student id
            var std_id = $(this).attr('id');
            // find request type
            var request_type = $(this).attr('data-key');

            // ajax request
            $.ajax({
                url: '/student/manage/class-top',
                type: 'POST',
                cache: false,
                data: {'std_id':std_id, 'request_type':request_type, '_token':'{{csrf_token()}}'},
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
                        // checking status
                        var btn = $('#'+std_id);
                        var ct_btn = $('#ct_'+std_id);
                        // checking class
                        if (request_type=='remove') {
                            btn.removeAttr('data-key');
                            btn.attr('data-key', 'assign');
                            btn.removeClass('btn-danger');
                            btn.addClass('btn-primary');
                            btn.html('Assign');

                            ct_btn.removeClass('text-red');
                            ct_btn.addClass('text-blue');
                        } else {
                            btn.removeAttr('data-key');
                            btn.attr('data-key', 'remove');
                            btn.removeClass('btn-primary');
                            btn.addClass('btn-danger');
                            btn.html('Remove');

                            ct_btn.removeClass('text-blue');
                            ct_btn.addClass('text-red');
                        }
                        // sweet alert success
                        //swal("Success", data.msg, "success");
                    }else{
                        // sweet alert warning
                        //swal("Warning", data.msg, "warning");
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