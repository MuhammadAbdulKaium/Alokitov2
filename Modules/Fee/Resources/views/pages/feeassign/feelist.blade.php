@extends('fee::layouts.feesassign')
<!-- page content -->
@section('page-content')
    @if(!empty($feeList) && ($feeList->count()>0))
    <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
        <thead>
        <tr>
            <th>Sl.No.</th>
            <th>Fee Category</th>
            <th>Fee Sub Category</th>
            <th>Fee Amount</th>
            <th>Fee For</th>
            <th>Class</th>
            <th>Section</th>
            <th>Student Name</th>
            <th>Assigned Date</th>
            <th>Manage</th>
        </tr>

        </thead>
        <tbody>
        @foreach($feeList as $index=>$fee)
        <tr class="gradeX">
            <td>{{ $index + $feeList->firstItem() }}</td>
            <td>{{$fee->feehead()->name}}</td>
            <td>{{$fee->subhead()->name}}</td>
            <td>{{$fee->amount}}</td>
            <td>Selected Batch</td>
            <td>
                @if ($fee->batch()->get_division())
                {{$fee->batch()->batch_name.' '.$fee->batch()->get_division()->name}}
                @else
                {{$fee->batch()->batch_name}}
                @endif
            </td>
            <td></td>
            <td></td>
            <td>{{date('d-m-Y',strtotime($fee->created_at))}}</td>

            {{--<td>{{$fee->section()->section_name}}</td>--}}
            <td><a id="{{$fee->id}}" class="delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{$feeList->render()}}
    @else
        <div class="alert alert-warning">
            Fee List Assign Create New one
        </div>

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
                    text: "You want to delete invoice",
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
                            url: "/fee/fees_assign/delete/" + del_id,
                            type: 'GET',
                            cache: false,
                            success: function (result) {
                                tr.fadeOut(1000, function () {
                                    $(this).remove();
                                });
                                swal("Success!", "Invoice successfully deleted", "success");

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