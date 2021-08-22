<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ $pageTitle }} </h4>
        </div>
        <div class="modal-body">
            <table id="" class="table table-bordered table-hover table-striped">
                @foreach($batch as $value)
                    <tr>
                        <th class="col-lg-4">Academic Level</th>
                        <td>{{ isset($value->academic_level_id)?$value->academicsLevel()->level_name:''}}</td>
                    </tr>
                    <tr>
                        <th class="col-lg-4">Batch Name</th>
                        <td>{{ isset($value->batch_name)?ucfirst($value->batch_name):''}}</td>
                        <th class="col-lg-4">Batch Alias</th>
                        <td>{{ isset($value->batch_alias)?$value->batch_alias:''}}</td>
                    </tr>
                    <tr>
                        <th class="col-lg-4">Start Date</th>
                        <td>{{date('m-d-Y',strtotime($value->start_date))}}</td>
                        <th class="col-lg-4">End Date</th>
                        <td>{{date('m-d-Y',strtotime($value->end_date))}}</td>
                    </tr>
                    <tr>
                        <th class="col-lg-4">Division</th>
                        <td>{{isset($value->division_id)? $value->division()->name:''}}</td>
                        <th class="col-lg-4">Status</th>
                        <td>
                            @if($value->status==1) <p>Active</p>@endif
                            @if($value->status==0) <p>Inactive</p>@endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="modal-footer">
            <a data-dismiss="modal" class="btn btn-default" type="button"> Close </a>
        </div>

    </div>
</div>
