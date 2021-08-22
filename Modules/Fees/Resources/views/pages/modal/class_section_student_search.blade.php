@if($stdList->count()>0)
{{--    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>--}}
<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">
    <form id="class_section_std_from">

        {{--<input type="hidden" name="_token" value="{{csrf_token()}}">--}}
        {{--<input type="hidden" name="fees_id" value="{{$fees_id}}">--}}

    <div class="box-body table-responsive">
                <table id="studentList" class="table table-striped table-bordered display">
                    <thead>
                    <tr>
                        <th></th>
                        <th><a  data-sort="sub_master_name">Student Id</a></th>
                        <th><a  data-sort="sub_master_name">Student Name</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php

                        $i = 1
                    @endphp
                    @foreach($stdList as $std)
                        @php $stdProfile=$std->student(); @endphp
                        <tr class="gradeX">
                            <td></td>
                            <td>{{$std->id}}</td>
                            <td>@if(!empty($stdProfile)) {{$stdProfile->first_name.' '.$stdProfile->middle_name.' '.$stdProfile->last_name}} @endif</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
    <p><button type="button">Submit</button></p>
    {{--</form>--}}

        </div>
        @else
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-warning"></i></i> No result found. </h5>
            </div>
        @endif

    </div><!-- /.box-body -->


                {{--<script type="text/javascript" src="https://cdn.datatables.net/s/dt/dt-1.10.10,se-1.1.0/datatables.min.js" ></script>--}}
                {{--<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/js/dataTables.checkboxes.min.js" ></script>--}}

            {{--<script src="{{ URL::asset('js/datatables/jquery.dataTables.checkboxes.js') }}"></script>--}}
            {{--<!-- DataTables -->--}}
            {{--<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>--}}
            {{--<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>--}}
                <script>

//        $('#studentList').DataTable({
//            "paging": true,
//            "lengthChange": false,
//            "searching": true,
//            "ordering": false,
//            "info": true,
//            "autoWidth": false
//        });



 $('#studentList').DataTable({
        'columnDefs': [
            {
                'targets': 0,
                'checkboxes': {
                    'selectRow': true
                }
            }
        ],
        'select': {
            'style': 'multi'
        },
        'order': [[1, 'asc']]
    });

// Handle form submission event
$('#class_section_std_from').on('submit', function(e){
    alert(100);
    var form = this;

    var rows_selected = table.column(0).checkboxes.selected();

    // Iterate over all selected checkboxes
    $.each(rows_selected, function(index, rowId){
        // Create a hidden element
        $(form).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id[]')
                .val(rowId)
        );
    });

    // FOR DEMONSTRATION ONLY
    // The code below is not needed in production

    // Output form data to a console
    $('#example-console-rows').text(rows_selected.join(","));

    // Output form data to a console
    $('#example-console-form').text($(form).serialize());

    // Remove added elements
    $('input[name="id\[\]"]', form).remove();

    // Prevent actual form submission
    e.preventDefault();
});






// Handle form submission event
//            $('#class_section_std_from').on('submit', function(e){
//                var form = this;
//
//                var rows_selected = table.column(0).checkboxes.selected();
//
//                // Iterate over all selected checkboxes
//                $.each(rows_selected, function(index, rowId){
//                    // Create a hidden element
//                    $(form).append(
//                        $('<input>')
//                            .attr('type', 'hidden')
//                            .attr('name', 'id[]')
//                            .val(rowId)
//                    );
//                });
//
//                // FOR DEMONSTRATION ONLY
//                // The code below is not needed in production
//
//                // Output form data to a console
//                $('#example-console-rows').text(rows_selected.join(","));
//
//                // Output form data to a console
//                $('#class_section_std_from').text($(form).serialize());
//
//                // Remove added elements
//                $('input[name="id\[\]"]', form).remove();
//
//                // Prevent actual form submission
//                e.preventDefault();
//            });








//        // check box all select here
//        $('.chk_boxes').click(function(){
//            $('.chk_boxes1').prop('checked', this.checked);
//        });

    </script>


