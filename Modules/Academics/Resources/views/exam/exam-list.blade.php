@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Exam |<small>Lists</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Academics</a></li>
            <li>SOP Setup</li>
            <li>Exam</li> 
            <li class="active">Exam Lists</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Exam Lists </h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-stripped" id="exam-list-table">
                    <thead>
                        <th>SL</th>
                        <th>Exam Name</th>
                        <th>Year</th>
                        <th>Term</th>
                        <th>Class</th>
                        <th>Form</th>
                        <th>Status</th>
                        <th>Approval Steps</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($examLists as $examList)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $examList->exam->exam_name }}</td>
                                <td>{{ $examList->year->year_name }}</td>
                                <td>{{ $examList->term->name }}</td>
                                <td>{{ $examList->batch->batch_name }}</td>
                                <td>{{ $examList->section->section_name }}</td>
                                <td>
                                    @if ($examList->publish_status == 1)
                                        <span class="text-warning">Pending</span>
                                    @elseif ($examList->publish_status == 2)
                                        <span class="text-success">Published</span>
                                    @elseif ($examList->publish_status == 3)
                                        <span class="text-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{!! $examListHasApproval[$examList->id]['approval_text'] !!}</td>
                                <td>
                                    <a href="{{ url('/academics/exam/tabulation-sheet/exam/'.$examList->id) }}" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-file"></i></a>
                                    @if ($allSectionPermitted && $examList->publish_status == 0 || in_array($examList->section->id, $permittedSectionIds))
                                        <a href="{{ url('/academics/exam/send-for-approval/'.$examList->id) }}" class="btn btn-xs btn-primary">Send For Approval</a>
                                    @endif
                                    @if($examListHasApproval[$examList->id]['has_approval'])
                                        @if ($allSectionPermitted && $examList->publish_status == 1)
                                            <button class="btn btn-xs btn-success" id="exam-approve-btn" 
                                            data-exam-list-id="{{ $examList->id }}">Approve</button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection



{{-- Scripts --}}
@section('scripts')
<script src="https://www.jqueryscript.net/demo/jQuery-Based-Bootstrap-Popover-Enhancement-Plugin-Bootstrap-Popover-X/bootstrap-popover-x.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
        $('#exam-list-table').DataTable();

        $(document).on('click', '#exam-approve-btn', function () {
            var examListId = $(this).data('exam-list-id');

            swal({
                title: "Are you sure?",
                text: "You want to approve this exam?",
                type: "warning",
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }, function () {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/academics/exam/tabulation-sheet-exam/approve') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'exam_list_id': examListId,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },
                
                    success: function (data) {
                        // hide waiting dialog
                        waitingDialog.hide();
                        console.log(data);
                
                        if (data.status == 1) {
                            swal({
                                title: "Success!",
                                text: data.message,
                                type: "success",
                            }, function () {
                                location.reload();
                            });
                        } else {
                            swal('Error!', data.message, 'error');
                        }
                    },
                
                    error: function (error) {
                        // hide waiting dialog
                        waitingDialog.hide();
                
                        console.log(error);
                    }
                });
                // Ajax Request End
            });
        });
    });
</script>
@stop