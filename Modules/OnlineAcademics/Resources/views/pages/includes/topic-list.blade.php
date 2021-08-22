<!-- DataTables -->
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<div class="box box-solid">
    <!--
	<div class="et">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> View Topic List</h3>
        </div>
    </div>
	-->
	
	<div class="box-body table-responsive">
		@if(isset($topic_list) && !empty($topic_list) && $topic_list->count()>0)
		<table id="example2" class="table table-striped table-bordered"> 
			<thead>        
				<tr>
					<th>#</th>
                    <th>Level</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Topic</th>
					<th>File Type</th>
                    <th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($topic_list as $row)
				<tr class="gradeX" style="text-align: center;">
					<td>{{ ++$loop->index }}.</td>
					<td>{{ $row->academic_level }}</td>
					<td>{{ $row->academic_class }}</td>
					<td>{{ $row->academic_section }}</td>
					<td>{{ $row->class_subject }}</td>
					<td>{{ $row->class_teacher }}</td>
					<td>{{ $row->class_topic }}</td>
					<td>
                        @if($row->file_path == 'null')
                        <span>No File</span>
                        @else
                            <a href="{{ asset('upload/online_class_topic/'.$row->file_path) }}" download="{{ $row->file_path }}">
                                {{ $row->file_path }}
                            </a>
                        @endif
                    </td>
					@role(['super-admin','admin','teacher'])
					<td>
						<a href="{{ url('onlineacademics/onlineacademic/edit', $row->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update">
							<i class="fa fa-edit"></i>
						</a>
					<a href="{{ url('onlineacademics/onlineacademic/destroy', $row->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
					</td>
					@endrole
				</tr>
				@endforeach 
			</tbody>
		</table>
		@else
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-warning"></i> No result found. </h5>
            </div>
        @endif
    </div>
    
</div>
<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>