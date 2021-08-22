
{{-- @php  print_r($allEnrollments) @endphp --}}
<div class="col-md-12">
	<div class="box box-solid">
		<div class="et">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-search"></i> View Cadet List @if($searchData) ({{sizeof($searchData)}}) @endif</h3>
			</div>
		</div>
		<div class="card">
			@if(isset($searchData))
				@if($searchData->count()>0)
					@php $i=1; @endphp
					<table class="table">
						<thead>
						<tr>
							<th>SL</th>
							<th>Photo</th>
							<th>Cadet Number</th>
							<th>Name</th>
							<th>Bengali Name</th>
							<th>DOB</th>
							<th>Blood Group</th>
							<th>Admission Year</th>
							<th>Batch</th>
							<th>Academic Year</th>
							<th>Class</th>
							<th>Form</th>
							<th>Fees</th>
							<th>Guardian</th>
							<th>Mobile</th>
							<th>Action</th>
						</tr>

						</thead>
						<tbody>
						@foreach($searchData as $data)
							<tr>
								<td>{{$i}}</td>
								<td>
									{{--								<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$enroll->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">--}}

									@if($data->singelAttachment("PROFILE_PHOTO"))
										<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$data->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
									@else
										<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
									@endif
								</td>
								<td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->email}}</a></td>
								<td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->first_name}} {{$data->last_name}}</a></td>
								<td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->bn_fullname}}</a></td>
								<td>{{$data->student()->dob}}</td>
								<td>{{$data->student()->blood_group}}</td>
								<td> @if($data->enroll()->admissionYear) {{$data->enroll()->admissionYear->year_name}} @endif</td>
								<td>{{$data->student()->batch_no}}</td>
								<td> @if($data->year()) {{$data->year()->year_name}} @endif</td>
								<td> @if($data->batch()) {{$data->batch()->batch_name}} {{($data->batch()->get_division())?'- '.$data->batch()->get_division()->name:''}} @endif</td>
								<td> @if($data->section()) {{$data->section()->section_name}} @endif</td>
								<td>
								@if($data->student()->tuitionFees)
									{{$data->student()->tuitionFees->fees}}
									@endif
								</td>
								<td>
									@if($data->guardian())
										{{$data->guardian()->first_name}} {{$data->guardian()->last_name}}
									@endif
								</td>
								<td>
									@if($data->guardian())
										{{$data->guardian()->mobile}}
									@endif
								</td>
								<td>
									<a  href="{{url('/student/status/'.$data->std_id)}}" title="Student Status" data-target="#globalModal" data-toggle="modal">
										<span id="status_{{$data->std_id}}" class="fa fa fa-user-o fa-lg {{$data->status==1?'text-green':'text-red'}}"></span>
									</a>
									@php $classTopper = $data->classTopper; @endphp
									<a href="{{url('/student/manage/class-top/'.$data->std_id)}}" title="Class Topper" data-target="#globalModal" data-toggle="modal">
										<span id="ct_{{$data->std_id}}" class="fa fa fa-hand-o-up fa-lg {{$classTopper?($classTopper->status==1?'text-red':'text-blue'):'text-blue'}}"></span>
									</a>
								</td>
							</tr>
							@php $i += 1; @endphp
						@endforeach
						</tbody>
					</table>
{{--					<div class="text-center">--}}
{{--						{{ $searchData->appends(Request::only([--}}
{{--                        'search'=>'search',--}}
{{--                        'filter'=>'filter',--}}
{{--                        'academic_level'=>'academic_level',--}}
{{--                        'batch'=>'batch',--}}
{{--                        'section'=>'section',--}}
{{--                        'gr_no'=>'gr_no',--}}
{{--                        'email'=>'email',--}}
{{--                        '_token'=>'_token',--}}
{{--                        ]))->render() }}--}}
{{--					</div>--}}
				@else
					<h5 class="text-center"> <b>Sorry!!! No Result Found</b></h5>
				@endif
			@endif
		</div>
	</div>
</div>

<script>
	$(function () {
		$("#example2").DataTable();
		$('#example1').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false
		});

		// paginating
		$('.pagination a').on('click', function (e) {
			e.preventDefault();
			var url = $(this).attr('href').replace('store', 'find');
			loadRolePermissionList(url);
			// window.history.pushState("", "", url);
			// $(this).removeAttr('href');
		});
		// loadRole-PermissionList
		function loadRolePermissionList(url) {
			$.ajax({
				url: url,
				type: 'POST',
				cache: false,
				beforeSend: function() {
					// show waiting dialog
					waitingDialog.show('Loading...');
				},
				success:function(data){
					// hide waiting dialog
					waitingDialog.hide();
					// checking
					if(data.status=='success'){
						var std_list_container_row = $('#std_list_container_row');
						std_list_container_row.html('');
						std_list_container_row.append(data.html);
					}else{
						alert(data.msg)
					}
				},
				error:function(data){
					alert(JSON.stringify(data));
				}
			});
		}


		// downlaod student report

//		$('.download').click(function () {
//		    var download_type=$(this).attr("id");
////			alert(download_type);
//
//            $.ajax({
//
//                url: '/student/manage/download/excel/pdf',
//                type: 'POST',
//                cache: false,
//                data: $('form#downlaodStdExcelPDF').serialize()+ "&download_type=" + download_type,
//                datatype: 'json/application',
//
//                beforeSend: function () {
//                    // alert($('form#class_section_form').serialize());
//                    // show waiting dialog
////                    waitingDialog.show('Loading...');
//                },
//
//                success: function (data) {
//                    // hide waiting dialog
////                    waitingDialog.hide();
//					console.log(data);
//
//                },
//
//                error: function (data) {
//                    alert('error');
//                }
//            });
//
//
//        })




	});
</script>