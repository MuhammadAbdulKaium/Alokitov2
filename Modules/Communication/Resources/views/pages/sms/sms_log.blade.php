@extends('communication::layouts.master')
@section('section-title')
    <h1><i class="fa fa-plus-square"></i>SMS Log</h1>
@endsection
<!-- page content -->
@section('page-content')
    <style>
        .popover-content {
            white-space: pre-line;
            margin-top: -40px;
            margin-bottom: -40px;

        }
    </style>
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- grading scale -->
    <div class="box box-solid">
        <div class="box-header with-border">
            <h4 class="box-title"><i class="fa fa-filter"></i> Search</h4>
        </div><!--./box-header-->
        <form id="smsLogSearchFrom" action="/communication/sms/sms_log/search" method="get">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group field-sms-log-start_time required">
                            <label class="control-label" for="sms-log-start_time">Start Date</label>
                            <div class='input-group date' id='start_time'>
                                <input type='text' required name="start_date" value="@if(!empty($allInputs->search_start_date)) {{date('d-m-Y',strtotime($allInputs->search_start_date))}}  @endif" class="form-control" />
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                            <div class="help-block"></div>
                        </div>			</div>
                    <div class="col-sm-3">
                        <div class="form-group field-sms-log-fp_edate required">
                            <label class="control-label" for="sms-log-fp_edate">End Date</label>
                            <div class='input-group date' id='end_time'>
                                <input type='text' required name="end_date" value="@if(!empty($allInputs->search_end_date)) {{date('d-m-Y',strtotime($allInputs->search_end_date))}}  @endif" class="form-control" />
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                            <div class="help-block"></div>
                        </div>			</div>

                    <div class="col-sm-3">
                        <div class="form-group field-sms-log-fees_pay_tran_mode">
                            <label class="control-label" for="sms-log-fees_pay_tran_mode">Group By</label>
                            <select id="user_group" class="form-control" name="user_group">
                                <option value="">---- Group By ----</option>
                                <option value="5" @if (!empty($allInputs) && ($allInputs->user_group =="5")) selected="selected" @endif >All</option>
                                <option value="1" @if (!empty($allInputs) && ($allInputs->user_group =="1")) selected="selected" @endif >Teacher</option>
                                <option value="2" @if (!empty($allInputs) && ($allInputs->user_group =="2")) selected="selected" @endif >Student</option>
                                <option value="3" @if (!empty($allInputs) && ($allInputs->user_group =="3")) selected="selected" @endif >Stuff</option>
                                <option value="4" @if (!empty($allInputs) && ($allInputs->user_group =="4")) selected="selected" @endif >Parent</option>


                            </select>

                            <div class="help-block"></div>
                        </div>			</div>
                    <div class="col-sm-3">
                        <div class="form-group" style="margin-top: 24px;">
                        <button type="submit"  class="btn btn-primary btn-create">Search</button></div>
                    </div>
                </div>

                </div>

        </form>
    </div>

    <div class="col-12">

        <div id="transaction_list_row">

            @if(!empty($searchSmsLog))
                @php $smsLogs=$smsLogs; @endphp
            @endif
            @if($smsLogs->count()>0)
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title text-center"><span style="float: left ">View Sms Logs</span><span class="label label-success">Total Sms= {{$smsLogsCount}}</span> </h3>
                        <div class="box-tools">
                            <a id="fees_payment_transaction_excel" class="btn btn-info btn-sm"><i class="fa fa-file-excel-o"></i> Excel</a>
                        </div>
                    </div>
                </div>



                <div class="box-body table-responsive">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                        <table id="fptList" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#TransacId</th>
                                <th>Name.</th>
                                <th>Mobile No</th>
                                <th>Message</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Sent at</th>
                                {{--<th>Action</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                        @foreach($smsLogs as $smsLog)
                                 <tr>
                                     <td>{{$smsLog->id}}</td>
                                     <td>

                                         @if($smsLog->user_group==1)
                                             @php $teacher=$smsLog->teacher() @endphp
                                         @if(!empty($teacher))
                                             <a href="/employee/profile/personal/{{$teacher->id}}">@if(!empty($teacher)) {{$teacher->first_name.' '.$teacher->middle_name.' '.$teacher->last_name}} @endif</a></td>
                                         @endif
                                    @elseif($smsLog->user_group==2)
                                         @php $student=$smsLog->student() @endphp
                                         @if(!empty($student))
                                         <a href="/student/profile/personal/{{$student->id}}">@if(!empty($student)) {{$student->first_name.' '.$student->middle_name.' '.$student->last_name}} @endif</a></td>
                                        @endif
                                     @elseif($smsLog->user_group==3)
                                         Stuff
                                      @elseif($smsLog->user_group==4)
                                         @php $parent=$smsLog->parents() @endphp
                                         @if(!empty($parent))
                                             <a href="/student/profile/personal/{{$parent->id}}">
                                      {{$parent->first_name.' '.$parent->middle_name.' '.$parent->last_name}} @endif</td>
                                             </a>
                                         @endif

                                     </td>
{{--                                     <td>@if($smsLog->user_group==4) {{ $smsLog->parents()->title." ".$smsLog->parents()->first_name." ".$smsLog->parents()->last_name}} @endif</td>--}}
                                     <td>{{$smsLog->user_no}}</td>
                                     <td>
                                         <a data-toggle="popover-x"
                                            data-target="#myPopover{{$smsLog->message_id}}">
                                             {{$smsLog->message_id}}
                                         </a>


                                         <div id="myPopover{{$smsLog->message_id}}" class="popover popover-default">
                                             <div class="arrow"></div>
                                             <h3 class="popover-title">
                                                 <span class="close pull-right" data-dismiss="popover-x">&times;</span>
                                                 Message
                                             </h3>
                                             <div class="popover-content">


                                                        @if(!empty($smsLog->message()))
                                                        {{$smsLog->message()->message}}
                                                        @endif
                                                   {{--// $message= preg_replace("/\r\n|\r|\n/",'<br/>',$message);--}}



{{--                                                 {{strip_tags($message,'<br>')}}--}}


                                             </div>

                                         </div>


                                     </td>
                                     <td>
                                         @if($smsLog->user_group==1)
                                                Teacher
                                             @elseif($smsLog->user_group==2)
                                            Student
                                                @elseif($smsLog->user_group==3)
                                            Stuff
                                             @elseif($smsLog->user_group==4)
                                            Parent
                                         @endif
                                     </td>
                                     <td>Delivered</td>
                                     <td>{{$smsLog->created_at}}</td>
                                     {{--<td><a href="/fees/invoice/payment/update"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-edit"></i></a></td>--}}
                                 </tr>
                            @endforeach

                        </table>
                    </div>
                    <div class="link" style="float: right">

                        {!! $smsLogs->appends(Request::only([
                             'search'=>'search',
                             'filter'=>'filter',
                             'payer_id'=>'std_id',
                                'search_start_date' => 'start_date',
                                'search_end_date' => 'end_date',
                                'user_group' => 'user_group',
                             ]))->render() !!}
                    </div>
                </div>
                 @else
                    <h3>There are no SMS Logs</h3>
                @endif
            </div>
        </div>

    </div>



@endsection

@section('page-script')

    $('#start_time').datepicker({format: 'dd-mm-yyyy'});
    $('#end_time').datepicker({format: 'dd-mm-yyyy'});


    {{--$(".foo").hover(function () {--}}
    {{--$(this).popover({--}}
    {{--title: "Bar",--}}
    {{--content: "Line 1 <br /> Line 2 <br /> Line 3",--}}
    {{--html: true--}}
    {{--}).popover('show');--}}
    {{--}, function () {--}}
    {{--$(this).popover('hide');--}}
    {{--});--}}

    {{--$(document).ready(function()){--}}
        {{--$(this).popover({--}}
        {{--html: true--}}
        {{--});--}}
    {{--});--}}



@endsection

