{{--datatable style sheet--}}
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<h4 class="text-center text-bold bg-blue-gradient">Uploaded Attendance History</h4>
<div class="col-md-12">
    @if(!empty($attendanceHistory) AND $attendanceHistory->count()>0)
        <table id="example1" class="table table-bordered table-responsive table-striped text-center">
            <thead>
            <tr class="bg-gray">
                <th>#</th>
                <th>File Name</th>
                <th>Content Name</th>
                <th>Uploaded At</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody class="text-bold">
            @php $i=1; @endphp
            @foreach($attendanceHistory as $singleAttendance)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$singleAttendance->file_name}}</td>
                    <td>{{$singleAttendance->name}}</td>
                    <td>{{ date('d M Y - H:i:s', strtotime($singleAttendance->uploaded_at) ) }}</td>
                    <td>
                        @if($singleAttendance->status == 0)
                        <a id="{{$singleAttendance->id}}" class="btn btn-default attendanceUpload btn-sm upload-atd-history-file {{$singleAttendance->status==1?'hide':'btn-default'}}"  title="Click here to Upload" >
                            <i class="fa fa-upload" aria-hidden="true"></i>
                        </a>
                        @endif
                        <a id="att_path_{{$singleAttendance->id}}" class="btn btn-default btn-sm" href="{{url($singleAttendance->path.$singleAttendance->name)}}" title="Click here to download" download>
                            <i class="fa fa-download" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                @php $i+=1; @endphp
            @endforeach
            </tbody>
        </table>
    @else
        <div id="w0-success-0" class="alert-warning text-center alert-auto-hide alert fade in">
            <h5 class="text-bold"><i class="fa fa-warning"></i> Attendance List is empty !!!</h5>
        </div>
    @endif
</div>
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>

<script>
    $(document).ready(function () {
        // dataTable
        $("#example2").DataTable();
        $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });


    });
</script>