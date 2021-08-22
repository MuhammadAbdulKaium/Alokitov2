
<form action="{{url('/academics/timetable/period/category/store/')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title text-bold">
			<i class="fa fa-info-circle"></i> {{$periodCategoryProfile->name}}
		</h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<h5 class="text-bold text-center bg-green">Assign History</h5>
				@php $periodClassSections = $periodCategoryProfile->classSections(); @endphp
				{{--checking--}}
				@if($periodClassSections->count()>0)
					<table class="table text-center table-bordered table-responsive table-striped">
						<thead>
						<tr>
							<th>Academic Year</th>
							<th>Academic Details</th>
							<th>Shift</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
						@foreach($periodClassSections as $periodClassSection)
							<tr id="period_row_{{$periodClassSection->id}}">
								<td>{{$periodClassSection->academicYear()->year_name}}</td>
								<td>{{$periodClassSection->academicLevel()->level_name." / ".$periodClassSection->batch()->batch_name." (".$periodClassSection->section()->section_name.")"}}</td>
								<td>{{$periodClassSection->cs_shift=='0'?'Day':'Morning'}}</td>
								<td>
									<a id="{{$periodClassSection->id}}" class="periodClassSection" onclick="return confirm('Are you sure?');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				@else
					<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 470.237;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h5><i class="fa fa-warning"></i> No result found. </h5>
					</div>
				@endif
			</div>
		</div>
	</div>
	<div class="modal-footer">
		{{--<button type="submit" class="btn btn-info pull-left">Submit</button>--}}
		<a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
	</div>
</form>

<script>
    $(document).ready(function () {

        $('.periodClassSection').click(function () {
            var periodCategoryId = $(this).attr('id');
            // ajax request
            $.ajax({
                url: '/academics/timetable/period/category/assign/delete/'+periodCategoryId,
                type: 'GET',
                cache: false,
                datatype: 'application/json',

                // beforeSend action
                beforeSend: function() { },

                // success action
                success:function(data){
                    if(data.status=='success'){
                        $('#period_row_'+periodCategoryId).remove();
                    }else{
                        alert(data.msg);
                    }
                },
                // error action
                error:function(){ }
            });
        });
    });
</script>