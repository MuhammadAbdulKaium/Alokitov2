
<div class="col-md-12">
	<div class="box box-solid">
		<div class="et">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-search"></i> View Student List</h3>
				<div class="box-tools">
					<form id="w0" action="{{url("/student/manage/deactive-student/download/excel")}}" method="post">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="hidden" name="academic_year" @if(!empty($allSearchInputs['academic_year'])) value="{{$allSearchInputs['academic_year']}} @endif">
						<input type="hidden" name="academic_level"  @if(!empty($allSearchInputs['academic_level']))  value="{{$allSearchInputs['academic_level']}}"  @endif>
						<input type="hidden" name="batch"  @if(!empty($allSearchInputs['batch']))  value="{{$allSearchInputs['batch']}}" @endif>
						<input type="hidden" name="section"  @if(!empty($allSearchInputs['section']))  value="{{$allSearchInputs['section']}}" @endif>
						{{--<input type="hidden" name="section" value="{{$allSearchInputs['section']}}">--}}
						{{--<input type="hidden" name="stu_detail_search" value="{{$allSearchInputs['academic_level']}}">--}}
						<button type="submit" class="btn btn-primary">
							<i class="icon-user icon-white"></i> Excel
						</button>
					</form>
				</div>
			</div>
		</div>

		<div class="box-body table-responsive">
			@if($deactiveStudents->count()>0)
				<table id="example1" class="table table-striped">
					<thead>
					<tr>
						<th>#</th>
						<th>Roll NO.</th>
						<th>Name</th>
						<th>Email</th>
						<th>Academic Year</th>
						<th>Course Name</th>
						<th>Class</th>
						<th>Section</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					{{--find current paginate number--}}
					@php $currentPage = $deactiveStudents->currentPage(); @endphp
					{{--reset item counter--}}
					@php $i = ((($currentPage*20)-20)+1); @endphp
					{{--student studentment looping--}}
					@foreach($deactiveStudents as $student)
						<tr>
							<td>{{$i}}</td>
							<td>{{$student->gr_no}}</td>
							<td><a href="/student/profile/personal/{{$student->id}}"> {{$student->first_name." ".$student->middle_name." ".$student->last_name}}</a></td>
							<td><a href="/student/profile/personal/{{$student->id}}"> {{$student->email}}</a></td>
							<td>{{Modules\Academics\Entities\AcademicsYear::getAcademicYearById($student->academic_year)}}</td>
							<td>{{Modules\Academics\Entities\AcademicsLevel::getAcademicLevelById($student->academic_level)}}</td>
							<td>{{Modules\Academics\Entities\Batch::getBatchNameById($student->batch)}}</td>
							<td>{{Modules\Academics\Entities\Section::getSectionNameById($student->section)}}</td>
							<td><span class="label label-danger">Deactive</span></td>
							<td>
								<a  href="{{url('/student/status/'.$student->std_id)}}" title="Student Status" data-target="#globalModal" data-toggle="modal">
									<span id="status_{{$student->std_id}}" class="fa fa fa-user-o fa-lg"></span>
								</a>
							</td>
						</tr>
						@php $i += 1; @endphp
					@endforeach
					</tbody>
				</table>
				{{--paginate--}}
				<div class="text-center">
					{{ $deactiveStudents->appends(Request::only([
					'search'=>'search',
					'filter'=>'filter',
					'academic_year'=>'academic_year',
					'academic_level'=>'academic_level',
					'batch'=>'batch',
					'section'=>'section',
					'gr_no'=>'gr_no',
					'email'=>'email',
					'_token'=>'_token',
					]))->render() }}
				</div>
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


    });
</script>