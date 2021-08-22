<hr/>
<div class="col-md-10 col-md-offset-1">
    <h3 class="box-title text-center"><i class="fa fa-book"></i> Exam Routine </h3>
    @if(count($classSubjectList)>0)
        <style type="text/css">
            body.dragging, body.dragging * {
                cursor: move !important;
            }

            .dragged {
                position: absolute;
                opacity: 0.5;
                z-index: 2000;
            }

            .sorted_table tr {
                cursor: pointer;
            }
        </style>
        <form id="std_admit_card_form" action="{{url('/reports/admit-card/download')}}" method="POST" target="_blank">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <table class="table table-striped table-bordered sorted_table text-center" role="grid" aria-describedby="example2_info">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Exam Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
                </thead>
                <tbody id="admit_card_form_body">
                {{--student enrollment looping--}}
                @foreach($classSubjectList as $index=>$classSubject)
                    {{--array to object conversion--}}
                    @php
                        $myIndex = $index+1;
                        $subject = (object)$classSubject
                    @endphp
                    {{--subject details--}}
                    <tr class="item" id="{{$myIndex}}">
                        <td>{{$myIndex}}</td>
                        <td>
                            {{$subject->code}}
                            <input id="{{$myIndex}}_sub_code" type="hidden" name="routine[{{$myIndex}}][sub_code]" value="{{$subject->code}}">
                        </td>

                        <td>
                            {{$subject->name}}
                            <input id="{{$myIndex}}_sub_name" type="hidden" name="routine[{{$myIndex}}][sub_name]" value="{{$subject->name}}">
                        </td>
                        <td>
                            <div class="form-group">
                                <input id="{{$myIndex}}_sub_date" type="text" readonly required class="form-control date_picker text-center" name="routine[{{$myIndex}}][date]">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input id="{{$myIndex}}_sub_start_time" type="text" readonly required class="form-control time_picker text-center" name="routine[{{$myIndex}}][start_time]">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input id="{{$myIndex}}_sub_end_time" type="text" readonly required class="form-control time_picker text-center" name="routine[{{$myIndex}}][end_time]">
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="box-footer text-right">
                <button id="std_admit_card_search_btn" type="submit" class="btn btn-info">Submit</button>
                <button type="reset" class="pull-left btn btn-default">Reset</button>
            </div>
        </form>
    @else
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h6><i class="fa fa-warning"></i> No result found. </h6>
        </div>
    @endif
</div>

<!-- DataTables -->
<script src="{{ URL::asset('js/jquery-sortable.js') }}"></script>
<script>
    $(function () {
        //Date picker
        $('.date_picker').datepicker({ autoclose: true });
        // time picker
        $('.time_picker').timepicker({
            'interval': 15,
            'minTime': '07:00am',
            'maxTime': '07:00pm'
        });
    });

    // Sortable rows
    $('.sorted_table').sortable({
        containerSelector: 'table',
        itemPath: '> tbody',
        itemSelector: 'tr',
        placeholder: '<tr class="placeholder"/>',
        onDrop: function ($item, container, _super) {
            _super($item, container);

            // sorting order
            var count = 1;
            $("tr.item").each(function() {
                $this = $(this);
                var item_id = $(this).attr('id');
                $("#"+item_id+'_sub_code').attr('name', 'routine['+count+'][sub_code]');
                $("#"+item_id+'_sub_name').attr('name', 'routine['+count+'][sub_name]');
                $("#"+item_id+'_sub_date').attr('name', 'routine['+count+'][date]');
                $("#"+item_id+'_sub_start_time').attr('name', 'routine['+count+'][start_time]');
                $("#"+item_id+'_sub_end_time').attr('name', 'routine['+count+'][end_time]');
                // counter
                count++;
            });
        }
    });

    $('#std_admit_card_search_btn').click(function () {
        var academic_year = $('#academic_year').val();
        var academic_level = $('#academic_level').val();
        var batch = $('#batch').val();
        var section = $('#section').val();
        var semester = $('#semester').val();

        $('#std_admit_card_form').append('<input type="hidden" name="academic_year" value="'+academic_year+'"/>')
            .append('<input type="hidden" name="academic_level" value="'+academic_level+'"/>')
            .append('<input type="hidden" name="batch" value="'+batch+'"/>')
            .append('<input type="hidden" name="section" value="'+section+'"/>')
            .append('<input type="hidden" name="semester" value="'+semester+'"/>');
    });


</script>