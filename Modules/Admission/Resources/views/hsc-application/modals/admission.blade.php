<style>
	.batch_section_hs{
		display: none;
	}
</style>
@if($applicantList->count()>0)
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<form id="applicant_admit_form" action="{{url('/admission/hsc/promotion/store')}}" method="POST" target="_blank">
		{{--csrf token--}}
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		{{--box--}}
		<div class="box box-solid">
			{{--box header--}}
			<div class="box-header">
				<div class="row">
					<div class="col-sm-8">
						<div class="col-sm-4">
							<div class="form-group">
								<label>Mark selected as</label>
								<select id="promote_action" class="form-control col-sm-3" name="promote_type" required>
									<option value="" disabled selected>--- Select Type ---</option>
									<option value="APPROVED">Approved / Admitted</option>
									<option value="DISAPPROVED">Disapproved / Not Admitted</option>
								</select>
								<div class="help-block"></div>
							</div>
						</div>

						<div class="batch_section_hs">
							<div class="col-sm-4">
								<div class="form-group">
									<label>Select Batch</label>
									<select id="academicBatch" class="form-control col-sm-3" name="batch_id" required>

										<option value="" disabled selected>--- Select Batch / Class ---</option>
										@foreach($collegeBatchs as $batch )

											{{--batch --}}
											@php
												// checking batch division
												if($division = $batch->get_division()){
													$divisionName = $batch->batch_name.' ('. $division->name.')';
												}else{
													$divisionName = $batch->batch_name;
												}
											@endphp

											<option value="{{$batch->id}}">{{$divisionName}}</option>
										@endforeach
									</select>
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label>Select Section</label>
									<select id="section" class="form-control academicSection" name="section" required>
										<option value="" selected>--- Select Section ---</option>

									</select>
									<div class="help-block"></div>
								</div>
							</div>
						</div>

					</div>
					<div class="col-sm-4">
						<div class="btn-group pull-right" style="display:flex;">
							<button class="btn btn-primary pull-right" type="submit">Select & Continue</button>
						</div>
					</div>
				</div>
			</div>
			<hr/>
			{{--box body--}}
			<div class="box-body table-responsive" style="overflow-x:inherit">
				{{--applicant table--}}
				<table id="example2" class="table table-striped table-bordered text-center">
					<thead>
					<tr>
						<th width="1%">#</th>
						<th width="1%"> <label for="check_all_applicant"> <input type="checkbox" id="check_all_applicant"> </label> </th>
						<th width="10%" >Photo</th>
						<th width="10%" >App. ID</th>
						{{--<th width="10%" >App. Date</th>--}}
						<th width="15%" >Student Name</th>
						<th width="15%" >Father's Name</th>
						{{--<th width="15%" >Mother's Name</th>--}}
						<th width="5%" >Gender</th>
						<th width="10%" >Class (Group)</th>
						<th width="5%" >Mobile</th>
						{{--<th>Payment Status</th>--}}
						<th width="5%">Status</th>
						{{--<th>Action</th>--}}
					</tr>
					</thead>
					<tbody>
					@foreach($applicantList as $index=>$applicant)
						<tr>
							<td>{{($index+1)}}</td>
							<td>
								<label for="app_{{$applicant->id}}">
									{{--checking application status--}}
									@if($applicant->a_status==0)
										<input type="checkbox" class="app_list" id="app_{{$applicant->id}}" name="app_list[{{$applicant->id}}]" value="{{$applicant->a_no}}">
									@else
										<input type="checkbox" checked disabled>
									@endif
								</label>
							</td>
							<td>
								{{--checking std photo--}}
								@if($applicant->std_photo)
									<img src="{{$applicant->std_photo}}" width="45px" height="45px">
								@else
									<img src="{{asset('assets/users/images/user-default.png')}}" width="45px" height="45px">
								@endif
							</td>
							<td>{{$applicant->a_no}}</td>
							{{--<td>--}}
								{{--{{$applicant->created_at->format("d M, Y")}}<br/>--}}
								{{--({{$applicant->created_at->format("H:i:s a")}})--}}
							{{--</td>--}}
							<td>
								{{$applicant->s_name}} <br/>
								{{$applicant->s_name_bn}}
							</td>
							<td>
								{{$applicant->f_name}} <br/>
								{{$applicant->f_name_bn}}
							</td>
							{{--<td>--}}
								{{--{{$applicant->m_name}} <br/>--}}
								{{--{{$applicant->m_name_bn}}--}}
							{{--</td>--}}
							<td>{{$applicant->gender==0?'Male':'Female'}}</td>
							{{--batch --}}
							@php
								$myBatch = $applicant->batch();
								// checking batch division
								if($division = $myBatch->get_division()){
									$divisionName = $division->name;
								}else{
									$divisionName = '';
								}
							@endphp

							<td>
								{{$myBatch->batch_name}} <br/>
								@if($myBatch->get_division()) ({{$divisionName}}) @endif
							</td>
							<td>{{$applicant->s_mobile}}</td>
							{{--<td>{{$applicant->p_status==0?'Un-Paid':'Paid'}}</td>--}}
							<td class="{{$applicant->a_status==0?'text-danger':'text-green'}}">
								<i class="fa fa-{{$applicant->a_status==0?'question':'check'}}"></i>
								{{$applicant->a_status==0?'Pending':' Active'}}
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			{{--box footer--}}
			<div class="box-footer">
				<button  class="btn btn-primary pull-right" type="submit">Select & Continue</button>
			</div>

		</div><!-- /.box-->
	</form>

@else
	<div class="alert-auto-hide alert alert-info text-center" style="opacity: 257.188;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-info-circle"></i> No record found.
	</div>
@endif

<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- datatable script -->
<script>
    $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true,
            "pageLength": 50
        });

        // on change div show batch section here
        $("#promote_action").click(function () {
            var $option = $(this).find('option:selected');
            var value = $option.val();//to get content of "value" attrib
            if(value=="APPROVED"){
                $('.batch_section_hs').fadeIn();
            }
        });

        // request for section list using batch id
        $(document).on('change','#academicBatch',function(){
            console.log("hmm its change");

            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // clear std list container
                    $('#std_list_container_row').html('');
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="" selected>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                },
            });
        });



        // form submit action
        $("form#applicant_admit_form").submit(function() {
            // verify if any student is selected or not
            if(verifyStdList()){
                // check promote action
                if($('#promote_action').val()){
                    $(this)
                        .append('<input type="hidden" name="academic_year" value="'+$('#academic_year').val()+'"/>')
                        .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                        .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                        .append('<input type="hidden" name="applicant_status" value="'+$('#applicant_status').val()+'"/>');

                    // ajax request
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/admission/hsc/promotion/store',
                        data: $('form#applicant_admit_form').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success: function (response) {
                            // hide waiting dialog
                            waitingDialog.hide();
                            // checking response status
                            if(response.status){
                                // success alert
                                swal("Success!", response.msg, "success");
                            }else{
                                // success alert
                                swal("Error!", response.msg, "error");
                            }
                        },

                        error:function(response){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // server error alert
                            swal("Error", 'No Response from server', "error");
                        }
                    });
                    // return false
                    return false
                }else{
                    // sweet alert
                    swal("Warning", 'Please Select a Promo Action Type', "warning");
                    return false
                }
            }else{
                // sweet alert
                swal("Warning", 'No Applicant is selected', "warning");
                return false
            }
        });


        // verify check_all_applicant
        function verifyStdList() {
            // stdListSelected
            var stdListSelected = null;
            // applicant list looping
            $(".app_list").each(function () {
                if($(this).is(':checked')){
                    stdListSelected = "checked";
                    return false;
                }
            });
            // checking stdListSelected
            if(stdListSelected=="checked"){
                return true;
            }else{
                return false;
            }
        }


        // select student for admission
        $('#check_all_applicant').click(function () {
            // checking checkbox is checked or not
            if ($(this).is(":checked")) {
                // select all student
                $(".app_list").prop("checked", true );
            }else{
                $(".app_list").prop("checked", false );
            }
        });


    });
</script>