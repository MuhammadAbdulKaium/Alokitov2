@if($studentWaiverList->count()>0)
    <div class="box-header">
        <div class="pull-left">
            <h4>Total Student : {{$studentWaiverList->count()}}</h4>
        </div>
        <div class="pull-right" style="margin-bottom: 10px;">
        <button type="button" id="get_waiver_pdf"  data-key="pdf" class="btn btn-info btn-sm download-report"><i class="fa fa-file-excel-o"></i> PDF</button>
        {{--<button type="button" id="get_waiver_excel" data-key="xlxs"  class="btn btn-info btn-sm download-report"><i class="fa fa-file-excel-o"></i> Excel</button>--}}
        </div>
            <div id="p0">
            <div id="w1" class="grid-view">

                <table id="WaiverTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Waiver Type</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th class="action-column">Action</th>
                    </tr>

                    </thead>
                    <tbody>

                    @php

                        $i = 1
                    @endphp
                    @foreach($studentWaiverList as $studentWaiver)

                        <tr>
                            <td>{{$studentWaiver->id}}</td>
                            <td>{{$studentWaiver->student()->first_name.' '.$studentWaiver->student()->middle_name.' '.$studentWaiver->student()->last_name}}</td>

                            <td>
                                @if($studentWaiver->waiver_type==1)
                                    <span class="label label-info">General
                                        @elseif($studentWaiver->waiver_type==2)
                                            <span class="label label-primary">Upbritti</span>
                                        @elseif($studentWaiver->waiver_type==3)
                                            <span class="label label-primary">Scholarship</span>
                                @endif
                            </td>

                            <td>
                                @if($studentWaiver->type==1)
                                    <span class="label label-info">Percent
                                        @else
                                            <span class="label label-primary">Amount</span>
                                @endif
                            </td>
                            <td>
                                {{$studentWaiver->value}}  @if($studentWaiver->type==1) % @else TK. @endif
                            <td>{{$studentWaiver->start_date}}</td>
                            <td>{{$studentWaiver->end_date}}</td>
                            <td>

                                @if($studentWaiver->status==1)
                                    <span class="label label-success">Active
                                        @else
                                            <span class="label label-primary">Deactive</span>
                                @endif

                            </td>
                            <td>
                                <a href="/student/student-waiver/update-waiver/{{$studentWaiver->id}}" title="waiver" data-target="#globalModal" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></a>
                                <a  id="{{$studentWaiver->id}}" class="btn btn-danger btn-xs deleteWaiver" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><span class="glyphicon glyphicon-trash"></span></a>
                            </td>
                        </tr>
                    @endforeach
                    {{--{{ $invoice->render() }}--}}

                    </tbody>
                </table>
            </div>
            {{--<div class="link" style="float: right"> {{ $feesinvoices->links() }}</div>--}}
            <div class="link" style="float: right">


                {{--                    {!! $feesinvoices->appends(Request::all(['']))->render() !!}--}}
                {!! $studentWaiverList->appends(Request::only([
                    'search'=>'search',
                    'filter'=>'filter',
                    'academic_year'=>'academic_year',
                    'academic_level'=>'academic_level',
                    'batch'=>'batch',
                    'section'=>'section',
                    'waiver_type'=>'waiver_type',
                    ]))->render() !!}

            </div>

            @else
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                </div>
            @endif

        </div><!-- /.box-body -->


        <script>

            $(".download-report").click(function(){

                var report_type = $(this).attr('data-key');
//                alert(report_type);
                // dynamic html form
                $('<form id="get_waiver_report_form" action="/reports/student-waiver/export" method="post" style="display:none;"></form>')
                    .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                    .append('<input type="hidden" name="academic_year" value="'+$('#academic_year').val()+'"/>')
                    .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                    .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                    .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                    .append('<input type="hidden" name="waiver_type" value="'+$('#waiver_type').val()+'"/>')
                    .append('<input type="hidden" name="report_type" value="'+report_type+'"/>')
                    // append to body and submit the form
                    .appendTo('body').submit();
                // remove form from the body
                $('#get_waiver_report_form').remove();

            });





        </script>