
{{-- @php  print_r($allEnrollments) @endphp --}}
<div class="col-md-12">
	<div class="box box-solid">
		<div class="et">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-search"></i> Cadet Daily Task Scheduled</h3>
			</div>
		</div>
		<div class="card">
			@if($period)
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Day</th>
							<th>Date</th>
							@for($i=1;$i<=25;$i++)
								<th>Slot {{$i}}</th>
							@endfor
						</tr>
					</thead>
					<tbody>
					@php $count =1; @endphp
					@foreach ($period as $date)
						<tr>
							<td><input type="text" value="{{$date->format('l')}}" name="day[]" id="day_{{$count}}"></td>
							<td>{{$date->format('Y-m-d')}}</td>
							@for($i=1;$i<=25;$i++)
								<td>
									<div class="slot-task">
										<div class="form-group">
											<select name="avtivity_cat[]" id="activity_cat">
												<option value="">--Activity Category--</option>
												@foreach($categories as $cat)
													<option value="{{$cat->id}}">{{$cat->category_name}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<select name="activity[]" id="activity_id">
												<option value="" selected disabled>--- Select Activity ---</option>
											</select>
										</div>
										<div class="form-group">
											<label for="">Start Time</label>
											<input type="time" name="activity_time_start[]">
										</div>
										<div class="form-group">
											<label for="">End Time</label>
											<input type="time" name="activity_time_end[]">
										</div>
									</div>
								</td>
							@endfor
						</tr>
					@endforeach
					</tbody>
				</table>
			@endif
		</div>
	</div>
</div>

<script>
	$(function () {
		var host = window.location.origin;
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
		$("#activity_cat").change(function(){
			// alert($(this).val())
			if($(this).val() != "")
			{
				$.ajax({
					type: "get",
					url: host + '/student/cadet/activity/'+ $(this).val(),
					data: "",
					dataType: "json",
					contentType: "application/json; charset=utf-8",
					success: function (response) {
						console.log(response);
						$("#activity_id").html(response);
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						alert(errorThrown);
						console.log(errorThrown);
					}
				});
			}
			else
			{
				$("#activity_id").html("");
			}
		});

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