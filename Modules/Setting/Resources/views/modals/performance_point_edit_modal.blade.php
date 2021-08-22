<!-- Modal content-->
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity Point Add</h4>
    </div>
    <div class="modal-body">
        @if($addOrEdit == "insert")
        <form action="/setting/performance/activity/point/add" method="post" id="point_add">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" value="{{$activity_id}}" name="cadet_performance_activity">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="control-label" for="name">Point</label>
                        <input type="number" id="name"  class="form-control" name="point" maxlength="60" placeholder="point" aria-required="true">
                        <div class="help-block">
                            @if ($errors->has('point'))
                                <strong>{{ $errors->first('point') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="control-group after-add-more">
                        <label class="control-label" for="name">Value</label>
                        <input type="text" id="name"  class="form-control" name="value" maxlength="60" placeholder="value" aria-required="true">
                        {{-- <div class="input-group-btn" style="padding-top: 22px;">
                            <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> </button>
                        </div> --}}
                        <div class="help-block">
                            @if ($errors->has('value'))
                                <strong>{{ $errors->first('value') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
        @endif
    </div>
    <div class="col-md-12">
        <table id="myTable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th><a>Point</a></th>
                <th><a>Value</a></th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @if(isset($pointList))
                    @php
                        $i = 1
                    @endphp
                    @foreach($pointList as $act)
                        <tr class="gradeX">
                            <td>{{$i++}}</td>
                            <td>{{$act->point}}</td>
                            <td>{{$act->value}}</td>
                            <td>
                                <a href="{{ url('setting/performance/activity/point/edit', $act->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>                                                  
                                <a href="{{ url('setting/performance/activity/point/delete', $act->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>

