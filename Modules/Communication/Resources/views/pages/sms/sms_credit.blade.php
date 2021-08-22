@extends('communication::layouts.master')
@section('section-title')
    <h1><i class="fa fa-plus-square"></i>SMS Credit</h1>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
 <div class="row">
     <div class="panel panel-default">
         <div class="panel-body">
     <div class="col-md-3">
         <div class="panel panel-default">
             <div class="panel-heading">
                 <h3 class="panel-title"><i class="fa fa-comments-o" aria-hidden="true"></i>SMS Credit</h3></div>
             <div class="panel-body noPadding">
               <i class="fa fa-warning"></i> {{$totalSmsCreadit}}  of text messages are left. </div>
         </div>
         <button type="button" class="btn btn-info" data-toggle="modal" data-target="#buySms">Buy SMS</button>
         </div>

     </div>
         </div>
 </div>


    <div class="col-md-12 panel panel-default">
        @if($smsCredits->count()>0)
            <h4><strong>SMS Credit History</strong></h4>
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="feesListTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th><a  data-sort="sub_master_code">#TransacId</a></th>
                            <th><a  data-sort="sub_master_code">SMS Count</a></th>
                            <th><a  data-sort="sub_master_alias">Status</a></th>
                            <th><a  data-sort="sub_master_alias">Payable Status</a></th>
                            <th><a  data-sort="sub_master_alias">SMS Price</a></th>
                            <th><a  data-sort="sub_master_alias">Submission Date</a></th>
                            <th><a  data-sort="sub_master_alias">Accepted Date</a></th>
                            <th><a  data-sort="sub_master_alias">Payment Status</a></th>
                            <th><a  data-sort="sub_master_alias">Download Invoice</a></th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($smsCredits as $smsCredit)
                            <tr>
                                <td>{{$smsCredit->id}}</td>
                                <td>{{$smsCredit->sms_amount}}</td>
                                <td><span class="label label-success">Success</span></td>
                                <td>@if($smsCredit->payable==1)
                                        @php $totalSmsPrice=$smsPrice*$smsCredit->sms_amount @endphp
                                        <span class="label label-success">Yes</span>
                                    @else
                                        @php $totalSmsPrice=0; @endphp
                                        <span class="label label-primary">No</span>
                                    @endif</td>
                                <td>{{$totalSmsPrice}}</td>
                                <td>{{date('d-m-Y',strtotime($smsCredit->submission_date))}}</td>
                                <td>{{date('d-m-Y',strtotime($smsCredit->accepted_date))}}</td>
                                <td>
                                    @if($smsCredit->payable==1)
                                        @if($smsCredit->payment_status==1)
                                            <button class="btn btn-primary btn-xs">Paid</button>
                                        @endif

                                        @if($smsCredit->payment_status==0)
                                            <button class="btn btn-danger btn-xs">Unpaid</button>
                                        @endif
                                        @else
                                        <button class="btn btn-primary btn-xs">Free</button>
                                    @endif

                                </td>
                                <td>
                                    @php $user=Auth::user(); @endphp
                            @if($smsCredit->payable==1)
                                    <a href="/communication/sms/sms_credit/invoice/{{$smsCredit->id}}" class="btn btn-success btn-xs">Download</a>
                            @if($smsCredit->payment_status==0)
                                            @if($user->hasRole('super-admin'))
                                    <a  href="/communication/sms/sms_credit/payment-status/{{$smsCredit->id}}" class="btn btn-primary btn-xs invoicePayment"  onclick="return confirm('Are you sure you want to payment this sms credit?');">Payment</a>
                                        @endif
                                            @endif
                                @endif
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



    {{--// Create Buy Sms Modal Here is Now --}}

    <!-- Modal -->
        <div id="buySms" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Buy SMS</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{URL::to('/communication/sms/sms_credit/store')}}" method="post" class="form-horizontal">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="campus_id" value="{{$campus_id}}" class="form-control" id="email" readonly >
                            <input type="hidden" name="institution_id" value="{{$instituteId}}" class="form-control" id="email" readonly >
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="institution">  Name of the institution :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" readonly  value="{{$instituteProfile->institute_name}}" id="institution">
                                </div>
                            </div>


                            <div class="form-group required">
                                <label class="control-label col-sm-4" for="pwd">SMS amount :</label>
                                <div class="col-sm-6">
                                    <input type="number" name="sms_amount" class="form-control" id="pwd" placeholder="SMS amount">
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="control-label col-sm-4" for="pwd">Select Year:</label>
                                <div class="col-sm-6">
                                    <select name="year" class="form-control" id="year">
                                        <option value="">Select One</option>
                                        @for($i=2018; $i<2030; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="control-label col-sm-4" for="month">Year Month:</label>
                                <div class="col-sm-6">
                                    <select name="month" class="form-control" id="year">
                                        <option value="">Select One</option>
                                        @for($i=1; $i<=12; $i++)
                                            @php $dateName="2018-".$i."-01" @endphp

                                        <option value="{{$i}}">{{date('F', strtotime($dateName))}}</option>
                                            @endfor
                                    </select>

                                </div>
                            </div>

                        {{--@if(!empty($smsCreditMonth) && ($smsCreditMonth->count()>0))--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="control-label col-sm-4" for="pwd">Payable:</label>--}}
                                {{--<div class="col-sm-6">--}}
                                        {{--<select name="payable" class="form-control" id="payable">--}}
                                            {{--<option value="1">Yes</option>--}}
                                        {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--@else--}}
                                {{--<input type="hidden" name="payable" value="0" class="form-control">--}}

                            {{--@endif--}}

                            <div class="form-group">
                                <label class="control-label col-sm-4" for="pwd">Comment :</label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="comment" rows="5" id="comment"></textarea>
                                </div>
                            </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>

            </div>
        </div>

@endsection



