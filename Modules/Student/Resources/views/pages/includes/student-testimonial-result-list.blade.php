
<div class="col-md-12">
	<div class="box box-solid">
		<div class="et">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-search"></i> View Testimonial Result List</h3>
				<div class="box-tools">
					<form id="w0" action="{{url("/student/manage/download/excel")}}" method="post">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="hidden" name="academic_year" @if(!empty($allSearchInputs['academic_year'])) value="{{$allSearchInputs['academic_year']}} @endif">
						<input type="hidden" name="academic_level"  @if(!empty($allSearchInputs['academic_level']))  value="{{$allSearchInputs['academic_level']}}"  @endif>
						<input type="hidden" name="batch"  @if(!empty($allSearchInputs['batch']))  value="{{$allSearchInputs['batch']}}" @endif>
						<input type="hidden" name="section"  @if(!empty($allSearchInputs['section']))  value="{{$allSearchInputs['section']}}" @endif>
						<input type="hidden" name="gr_no"  @if(!empty($allSearchInputs['gr_no']))  value="{{$allSearchInputs['gr_no']}}" @endif>
						<input type="hidden" name="email"  @if(!empty($allSearchInputs['email']))  value="{{$allSearchInputs['email']}}" @endif>
						{{--<input type="hidden" name="section" value="{{$allSearchInputs['section']}}">--}}
						{{--<input type="hidden" name="stu_detail_search" value="{{$allSearchInputs['academic_level']}}">--}}
						<input type="hidden" name="student_name" value="{{csrf_token()}}">
						<button type="submit" class="btn btn-primary">
							<i class="icon-user icon-white"></i> Excel
						</button>
					</form>
				</div>
			</div>
		</div>

		<div class="box-body table-responsive">
			@if($allEnrollments->count()>0)
				<form id="Studentresult" method="post" >
				<table id="example" class="table table-striped">
					<thead>
					<tr>
						<th>#</th>
						<th>Roll NO.</th>
						<th>Name</th>
						<th>Email</th>
						<th>UserId</th>
						<th>Result Type</th>
						<th>GPA</th>
						<th>GPA Details</th>
						<th>REG No.</th>
						<th>Year</th>
					</tr>
					</thead>
					<tbody>
					{{--find current paginate number--}}
					{{--reset item counter--}}
					@php $i =1; @endphp

					{{--student enrollment looping--}}
					@foreach($allEnrollments as $enroll)
						<tr>
							<td>{{$i}}</td>
							<td>{{$enroll->gr_no}}</td>
							<td><a href="/student/profile/personal/{{$enroll->std_id}}"> {{$enroll->first_name." ".$enroll->middle_name." ".$enroll->last_name}}</a></td>
							<td><a href="/student/profile/personal/{{$enroll->std_id}}">{{$enroll->email}}</a></td>
							<td><a href="/student/profile/personal/{{$enroll->std_id}}">{{$enroll->username}}</a></td>
							<td>
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="hidden" name="std_id[]" value="{{$enroll->std_id}}">
								<select class="form-control" id="result_type" name="result_type[{{$enroll->std_id}}]">
									<option @if($resultType==1) selected @endif value="1">PSC</option>
									<option @if($resultType==2) selected @endif  value="2">JSC</option>
									<option @if($resultType==3) selected @endif  value="3">SSC</option>
									<option @if($resultType==4) selected @endif  value="4">HSC</option>
								</select>
							</td>
							<td><input type="text" class="form-control" name="gpa[{{$enroll->std_id}}]" @if(!empty($enroll->testimonial_result($resultType)->gpa)) value="{{$enroll->testimonial_result($resultType)->gpa}}" @endif> </td>
							<td> <input type="text" class="form-control" name="gpa_details[{{$enroll->std_id}}]" @if(!empty($enroll->testimonial_result($resultType)->gpa_details)) value="{{$enroll->testimonial_result($resultType)->gpa_details}}" @endif></td>
							<td><input type="text" class="form-control" name="reg_no[{{$enroll->std_id}}]" @if(!empty($enroll->testimonial_result($resultType)->reg_no)) value="{{$enroll->testimonial_result($resultType)->reg_no}}" @endif></td>
							<td><input type="text" class="form-control" name="year[{{$enroll->std_id}}]" @if(!empty($enroll->testimonial_result($resultType)->year)) value="{{$enroll->testimonial_result($resultType)->year}}" @endif></td>
							</td>
						</tr>
						@php $i += 1; @endphp
					@endforeach

					</tbody>
				</table>
				<button type="submit" class="btn btn-success">Submit</button>
				</form>
				{{--paginate--}}
			@else
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h6><i class="fa fa-warning"></i> No result found. </h6>
				</div>
			@endif
		</div>
	</div>






</div>

<script>
    $(function () {
        $('#example1').DataTable({
            'iDisplayLength': 100
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


    });


    $(document).ready(function() {
        var table = $('#example1').DataTable();

        $('#Studentresult').on('submit', function(e) {
            e.preventDefault();

            // Serialize form data
            var data = table.$('input,select,textarea').serialize();

            // Submit form data via ajax
            $.ajax({
                type: "POST",
                url: '/student/testimonial/result/manage/store',
                data: data,

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function(data){
                    waitingDialog.hide();
                }
            });

            // Prevent form submission
            return false;
        } );


        var table = $('#example').DataTable();

        // Handle form submission event
        $('#frm-example').on('submit', function(e){
            // Prevent actual form submission
            e.preventDefault();
            waitingDialog.show('Loading...');

            // Serialize form data
            var data = table.$('input,select,textarea').serialize();

            // Submit form data via Ajax
            $.ajax({
                url: '/student/result/manage/store',
                data: data,
                success: function(data){
                    waitingDialog.hide();
                }
            });

            // FOR DEMONSTRATION ONLY
            // The code below is not needed in production

            // Output form data to a console
            $('#example-console-form').text(data);
        });



    } );

</script>