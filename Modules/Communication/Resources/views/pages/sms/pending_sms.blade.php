@extends('communication::layouts.master')
@section('section-title')
    <h1><i class="fa fa-plus-square"></i>Pending SMS</h1>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="col-md-12 panel panel-default">
        @if($smsCredits->count()>0)
            <h4><strong>Pending SMS </strong></h4>
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="feesListTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th><a  data-sort="sub_master_code">#TransacId</a></th>
                            <th><a  data-sort="sub_master_code">SMS Count</a></th>
                            <th><a  data-sort="sub_master_alias">Status</a></th>
                            <th><a  data-sort="sub_master_alias">Submission Date.</a></th>
                            <th><a  data-sort="sub_master_alias">Acceptance Date</a></th>
                            <th><a  data-sort="sub_master_alias">Paybale</a></th>
                            {{--<th><a  data-sort="sub_master_alias">Submitted By</a></th>--}}
                            <th><a  data-sort="sub_master_alias">Comment</a></th>
                            <th><a  data-sort="sub_master_alias">Remarks</a></th>
                            <th><a  data-sort="sub_master_alias">Action</a></th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($smsCredits as $smsCredit)
                            <tr>
                                <td>{{$smsCredit->id}}</td>
                                <td>{{$smsCredit->sms_amount}}</td>
                                <td> <span class="label label-primary">Pending</span></td>
                                <td>{{$smsCredit->submission_date}}</td>
                                <td>{{$smsCredit->acceptance_date}}</td>
                                <td>@if($smsCredit->payable==1)
                                   <span class="label label-success">Yes</span>
                                     @else
                                        <span class="label label-success">No</span>
                                    @endif
                                </td>
                                {{--<td>{{$smsCredit->submitted_by}}</td>--}}
                                <td>{{$smsCredit->comment}}</td>
                                <td>{{$smsCredit->remark}}</td>
                                <td>
                                    @if($smsCredit->status=="0")
                                    <a href="/communication/sms/sms_credit/update/{{$smsCredit->id}}" class="btn btn-primary btn-xs"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-edit"></i></a>
                                    @endif
                                        <a  id="{{$smsCredit->id}}" class="btn btn-danger btn-xs cancel_pending_sms" data-placement="top" data-content="delete"><i class="fa fa-times"></i></a>
                                        @role(['super-admin'])
                                        <a  id="{{$smsCredit->id}}" class="btn btn-success btn-xs approve_pending_sms"  data-placement="top" data-content="approve"><i class="fa fa-check"></i></a>
                                        @endrole
                                </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="link" style="float: right"> {{ $smsCredits->links() }}</div>
            </div>
            @else
                    <h3>There are no SMS Credit History</h3>

            @endif

        </div><!-- /.box-body -->



@endsection

@section('page-script')

{{--<script>--}}

     // pending sms delete ajax request
     $('.cancel_pending_sms').click(function(){
         var tr = $(this).closest('tr'),
             del_id = $(this).attr('id');
                //pop up
                swal({
                    title: "Are you sure?",
                    text: "You want to cancel pending sms",
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
                        url: "/communication/sms/pending_sms/cancel/"+ del_id,
                        type: 'GET',
                        cache: false,
                        success:function(result){
                            tr.fadeOut(1000, function(){
                                $(this).remove();
                            });
                        }
                    });
                    swal("Success!", "Your pending sms has been deleted!",'success');
                    window.location.href = href;
                } else {
                    swal("Cancelled", "Your pending sms  is safe", "error");
                }
                });



     });

     // pending sms delete ajax request
     $('.approve_pending_sms').click(function(){
         var tr = $(this).closest('tr'),
             sms_id = $(this).attr('id');


                swal({
                title: "Are you sure?",
                text: "You want to Approve pending sms",
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
                     url: "/communication/sms/pending_sms/approve/"+ sms_id,
                     type: 'GET',
                     cache: false,
                     success:function(result){
                         tr.fadeOut(1000, function(){
                             $(this).remove();
                         });
                     }
                 });

                 swal("Success!", "Your pending sms has been approved!", "success");
                 window.location.href = href;
             } else {
                 swal("Cancelled", "Your pending sms not approve", "error");
        }
     });

     });






@endsection

