@extends('fee::layouts.feesassign')
<!-- page content -->
@section('page-content')

    @if(!empty($waiverAssignList))
        <div class="dropdown pull-right" style="margin-bottom: 10px">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Download
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a target="_blank" href="/fee/waiver-assign/download/pdf">PDF</a></li>
                <li><a target="_blank" href="/fee/waiver-assign/download/excel">Excel</a></li>
            </ul>
        </div>
    <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
        <thead>
        <tr>
            <th># NO</th>
            <th>STD ID</th>
            <th>Name</th>
            <th>Roll</th>
            <th>Class</th>
            <th>Section</th>
            <th>Fee Head</th>
            <th>Waiver Type</th>
            <th>Waiver Amount</th>
            <th>Action</th>
        </tr>

        </thead>
        <tbody>
        @php $i=1; @endphp
        @foreach($waiverAssignList as $waiver)

        <tr class="gradeX">
            <td>{{$i++}}</td>
            @php $studentProfile=$waiver->studentProfile(); @endphp
            <td>{{$studentProfile->username}}</td>
            <td>{{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
            <td>{{$studentProfile->gr_no}}</td>
            <td>
                @if ($waiver->batch()->get_division())
                    {{$waiver->batch()->batch_name.' '.$waiver->batch()->get_division()->name}}
                @else
                    {{$waiver->batch()->batch_name}}
                @endif
            </td>
            <td>{{$waiver->section()->section_name}}</td>
            <td>{{$waiver->feehead()->name}}</td>
            <td>{{$waiver->waiver_type()->name}}</td>
            <td>{{$waiver->amount}}
                            @if($waiver->amount_percentage==1) %
                                @else
                    ৳
                @endif
            </td>
            <td>
                {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
                <a id="{{$waiver->id}}" class="delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
            @endforeach

        </tbody>
    </table>
    {{$waiverAssignList->links()}}
    @else
        No Record Found
    @endif

@endsection



@section('page-script')
    <script>
        // invoice delete ajax request
        $('.delete_class').click(function(e){
            var tr = $(this).closest('tr'),
                del_id = $(this).attr('id');

            swal({
                    title: "Are you sure?",
                    text: "You want to delete assign waiver",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes!',
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {

                    if (isConfirm) {
                        $.ajax({
                            url: "/fee/waiver_assign/delete/" + del_id,
                            type: 'GET',
                            cache: false,
                            success: function (result) {
                                tr.fadeOut(1000, function () {
                                    $(this).remove();
                                });
                                swal("Success!", "Waive Assign  deleted", "success");

                            }
                        });
                    } else {
                        swal("NO", "Your Fee and Invoice is safe :)", "error");
                        e.preventDefault();
                    }
                });
        });

    </script>

@endsection