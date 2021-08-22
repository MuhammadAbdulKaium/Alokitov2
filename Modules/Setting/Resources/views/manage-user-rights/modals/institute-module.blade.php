
	@if($allModule->count()>0)

							<div class="col-md-12">
								<h4 class="text-center text-bold bg-blue-gradient">Institute Module List</h4>
								<form id="institute_module_form">
									<input type="hidden" name="_token" value="{{csrf_token()}}" />
										<table class="table table-responsive table-bordered table-striped">
											<thead>
											<tr>
												<th class="text-center">#</th>
												<th class="text-center">Icon</th>
												<th>Name</th>
												<th>Route</th>
												<th class="text-center">Status</th>
											</tr>
											</thead>
											<tbody>
											@foreach($allModule as $module)
												<tr>
													<td class="text-center"><input type="checkbox" name="{{$module->id}}" {{$module->checkInstitute($instituteProfile->id) ? 'checked' : '' }}/></td>
													<td class="text-center"><i class="{{$module->icon}}" aria-hidden="true"></i></td>
													<td>{{$module->name}}</td>
													<td>#{{$module->route}}</td>
													<td class="text-center"><i class="{{$module->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
													</td>
												</tr>
											@endforeach
											</tbody>
										</table>
									<div id="institute_module_table_submit_btn" class="modal-footer">
										<button class="btn btn-primary pull-right" type="submit">Submit</button>
									</div>
								</form>
							</div>
	@else
		<div class="alert-warning alert-auto-hide alert fade in" style="opacity: 474.119;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h6><i class="fa fa-warning"></i> No records found. </h6>
		</div>
	@endif

<script type="text/javascript">
	$(document).ready(function(){
            // request for section list using batch and section id
            $('form#institute_module_form').on('submit', function (e) {
                e.preventDefault();
                // add request type to the form
                $(this).append('<input id="institute_id" type="hidden" name="institute_id" value="'+$('#institute').val()+'"/>');
                $(this).append('<input type="hidden" name="request_type" value="assign"/>');
                // ajax request
                $.ajax({
                    url: '/setting/rights/setting/institute-module/',
                    type: 'GET',
                    cache: false,
                    data: $('form#institute_module_form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Submitting...');
                    },

                    success:function(data){
                        var institute_module_table_row =  $('#module_role_permission_table_row');
                        institute_module_table_row.html('');
                        institute_module_table_row.append(data);
                        // hide waiting dialog
                        waitingDialog.hide();
                    },

                    error:function(data){
                        // statements
                        //alert(JSON.stringify(data));
                        alert('Unable to perform the action')
                    }
                });
            });
	});
</script>