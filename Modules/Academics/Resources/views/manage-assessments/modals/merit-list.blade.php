{{--checking grade list count--}}
@if($examSummaryList->count()>0)
    {{--DataTables--}}
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <h4 class="text-center text-bold bg-green">Semester Exam Merit List</h4>
    <div class="col-md-10 col-md-offset-1">
        <table id="example1" class="table table-responsive table-bordered table-striped text-center">
            <thead>
            <tr>
                {{--<th class="text-center">#</th>--}}
                <th>Gr NO.</th>
                <th>Name</th>
                <th>Marks</th>
                <th>Position</th>
            </tr>
            </thead>
            <tbody>
            @foreach($examSummaryList as $index=>$examSummary)
                {{--std profile--}}
                @php $stdProfile = $examSummary->student(); @endphp
                <tr>
                    {{--<td>{{($index+1)}}</td>--}}
                    <td>{{$stdProfile->enroll()->gr_no}}</td>
                    <td>{{$stdProfile->first_name." ".$stdProfile->middle_name." ".$stdProfile->last_name}}</td>
                    <td>{{$examSummary->marks}}</td>
                    <td>{{$examSummary->merit}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert-warning alert-auto-hide alert fade in" style="opacity: 474.119;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h6><i class="fa fa-warning"></i> No records found. </h6>
    </div>
@endif


<script>
    $(document).ready(function () {
        // dataTable
        $("#example13").DataTable();
        $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true,
            "pageLength":25
        });
    });
</script>