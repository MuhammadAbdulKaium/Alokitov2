{{--DataTables--}}
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
{{--lable--}}
<h4 class="text-center text-bold bg-green">Result Sheet</h4>
@if(!empty($assMarks) && !empty($studentList))
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<div class="col-md-12">
				<div class="btn-group pull-right" style="display:flex; margin-bottom: 5px">
					<button id="w4" class="btn btn-success text-bold btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						Download <span class="caret"></span>
					</button>
					<ul id="w5" class="dropdown-menu dropdown-menu-right">
						<li>
							<a class="download-assessment-result" data-key="ALL" style="cursor:pointer">All List</a>
						</li>
						<li>
							<a class="download-assessment-result" data-key="PASSED" style="cursor:pointer">Passed List</a>
						</li>
						<li>
							<a class="download-assessment-result" data-key="FAILED" style="cursor:pointer">Failed List</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		{{--resutl table--}}
		<table id="example1" class="table table-bordered table-responsive table-striped">
			<thead>
			<tr>
				<th class="text-center">Std. ID</th>
				<th class="text-center">Std. Name</th>
				<th class="text-center">Mark</th>
				<th class="text-center">Passing Points</th>
				<th class="text-center">Result</th>
			</tr>
			</thead>
			<tbody class="text-center">
			@for($i=0; $i<count($studentList); $i++)
				@if(array_key_exists($studentList[$i]['id'], $assMarks))
					<tr>
						<td>{{$studentList[$i]['id']}}</td>
						<td>{{$studentList[$i]['name']}}</td>
						@if(array_key_exists($studentList[$i]['id'], $assMarks))
							@php $stdMark = $assMarks[$studentList[$i]['id']]; @endphp
							<td class="text-center">{{$stdMark['ass_mark']}} / {{$stdMark['ass_points']}}</td>
							<td class="text-center">{{$stdMark['pass_points']}}</td>
							<td class="text-center">{{$stdMark['ass_result']}}</td>
						@else
							<td>N/A</td>
						@endif
					</tr>
				@endif
			@endfor
			</tbody>
		</table>
	</div>
@else
	<div class="col-md-12">
		<div  class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 423.642;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="fa fa-warning"></i></i> No record found
		</div>
	</div>
@endif


<script>
    $(document).ready(function () {
        // dataTable
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });

        $('.download-assessment-result').click(function () {
            // type of result list
            var result_list_type = $(this).attr('data-key');
            // dynamic html form
            var result_form =  $('<form id="std_assessment_result_download_form" action="/academics/manage/assessment/result" method="POST"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="batch" value="'+$("#batch").val()+'"/>')
                .append('<input type="hidden" name="section" value="'+$("#section").val()+'"/>')
                .append('<input type="hidden" name="subject" value="'+$("#subject").val()+'"/>')
                .append('<input type="hidden" name="semester" value="'+$("#semester").val()+'"/>')
                .append('<input type="hidden" name="ass_cat_id" value="'+$("#ass_cat_id").val()+'"/>')
                .append('<input type="hidden" name="ass_id" value="'+$("#assessment").val()+'"/>')
                .append('<input type="hidden" name="result_list_type" value="'+result_list_type+'"/>')
                .append('<input type="hidden" name="request_type" value="pdf"/>').appendTo('body').submit();
            // remove form from the body
            $('#std_assessment_result_download_form').remove();


        });
    });
</script>