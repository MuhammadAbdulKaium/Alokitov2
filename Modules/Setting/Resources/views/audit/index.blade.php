@extends('setting::layouts.master')
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>


@section('section-title')
    <h1>
        <i class="fa fa-th-list"></i>Audit <small>History</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Audit</li>
        <li class="active">History</li>
    </ul>
@endsection

@section('page-content')
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif
    </div>

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-plus-square"></i> Show Audit List</h3>
        </div>

        <form id="setting-Audit-form"  action="{{url('setting/audit/search')}}" method="get">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name">
                            <label class="control-label" for="name">Select User</label>
                            <input class="form-control" id="user_name" name="search_user" type="text" value="@if(!empty($allInputs)) {{$allInputs->user_name}} @endif " placeholder="Type Student Name">
                            <input id="user_id" name="user_id" type="hidden" value="@if(!empty($allInputs)) {{$allInputs->user_id}}  @endif"/>
                            <div class="help-block"></div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Audit Event</label>
                            <select id="audit_event" class="form-control" required name="audit_event" >
                                <option value="">Select Event</option>
                                <option value="created" @if (!empty($allInputs) && ($allInputs->audit_event =="created")) selected="selected" @endif>Created</option>
                                <option value="updated" @if (!empty($allInputs) && ($allInputs->audit_event =="updated")) selected="selected" @endif >Updated</option>
                                <option value="deleted" @if (!empty($allInputs) && ($allInputs->audit_event =="deleted")) selected="selected" @endif >Deleted</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="form-group field-feespaymenttransactionsearch-start_time required">
                            <label class="control-label" for="feespaymenttransactionsearch-start_time">Start Time</label>
                            <div class='input-group date' id='start_time'>
                                <input type='text' name="start_time" required value="@if(!empty($allInputs)) {{$allInputs->start_time}} @endif "class="form-control" />
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                            <div class="help-block"></div>
                        </div>          </div>
                    <div class="col-sm-3">
                        <div class="form-group field-feespaymenttransactionsearch-fp_edate required">
                            <label class="control-label" for="feespaymenttransactionsearch-fp_edate">End Time</label>
                            <div class='input-group date' id='end_time'>
                                <input type='text' name="end_time" required value="@if(!empty($allInputs)) {{$allInputs->end_time}} @endif " class="form-control" />
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                            <div class="help-block"></div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

                <button type="submit" class="btn btn-primary btn-create">Search</button>

            </div>
            <!-- /.box-footer-->
        </form>


        @if(!empty($searchAudit))
            @php $audits=$allAuditList; @endphp
        @endif

        @if($audits->count()>0)


            <div class="box box-solid">
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">

                            <table id="myTable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a  data-sort="sub_master_name">User Name</a></th>
                                    <th><a  data-sort="sub_master_name">Event</a></th>
                                    <th><a  data-sort="sub_master_name">Auditable Type</a></th>
                                    <th><a  data-sort="sub_master_name">Old Value</a></th>
                                    <th><a  data-sort="sub_master_name">New Value</a></th>
                                    <th><a  data-sort="sub_master_name">UrL</a></th>
                                    <th><a  data-sort="sub_master_name">IP</a></th>
                                    <th><a  data-sort="sub_master_name">Created At</a></th>
                                    <th><a>Action</a></th>
                                </tr>

                                </thead>
                                <tbody>

                                @if(isset($audits))
                                    @php
                                        $i = 1
                                    @endphp
                                    @foreach($audits as $audit)
                                        <tr class="gradeX">
                                            <td>{{$i++}}</td>
                                            <td> @if(!empty($audit->getUser()->name)){{$audit->getUser()->name}} @endif</td>
                                            <td>{{$audit->event}}</td>
                                            <td>{{$audit->auditable_type}}</td>
                                            <td>{{json_encode($audit->old_values)}}</td>
                                            <td>{{json_encode($audit->new_values)}}</td>
                                            <td>{{$audit->url}}</td>
                                            <td>{{$audit->ip_address}}</td>
                                            <td>{{$audit->created_at}}</td>
                                            <td>
                                                {{--<a href="{{ route('--}}
                                                {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}

                                                <a href="{{ url('setting/language/edit', $audit->id) }}" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('setting/language/delete', $audit->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                @endif
                                {{--{{ $data->render() }}--}}

                                </tbody>
                            </table>
                        </div>

                        {{--<div class="link" style="float: right"> {{ $feesinvoices->links() }}</div>--}}
                        <div class="link" style="float: right">


                            {{--                    {!! $feesinvoices->appends(Request::all(['']))->render() !!}--}}
                            {!! $audits->appends(Request::only([
                                'search'=>'search',
                                'filter'=>'filter',
                                'user_id'=>'user_id',
                                'audit_event'=>'audit_event',
                                'start_time'=>'start_time',
                                'end_time'=>'end_time',
                                ]))->render() !!}

                        </div>

                        @else
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                            </div>
                        @endif

                    </div>
                </div>
                <!-- /.box-body -->
            </div>

    </div>
    <!-- /.box-->

@endsection
<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>


@section('page-script')
    $('#start_time').datetimepicker();
    $('#end_time').datetimepicker();


    // get Fees autocomplete

    $('#user_name').keypress(function() {
    $(this).autocomplete({
    source: loadFromAjax,
    minLength: 1,

    select: function(event, ui) {
    // Prevent value from being put in the input:
    this.value = ui.item.label;
    // Set the next input's value to the "value" of the item.
    $(this).next("input").val(ui.item.id);
    event.preventDefault();
    }
    });

    /// load student name form
    function loadFromAjax(request, response) {
    var term = $("#search_fees_name").val();
    $.ajax({
    url: '/student/find/user',
    dataType: 'json',
    data: {
    'term': term
    },
    success: function(data) {
    // you can format data here if necessary
    response($.map(data, function(el) {
    return {
    label: el.name,
    value: el.name,
    id: el.id
    };
    }));
    }
    });
    }
    });


@endsection

