<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ $pageTitle }} </h4>
        </div>

        <div class="modal-body">
            <div style="padding: 30px;">
                <table id="" class="table table-bordered table-hover table-striped">
                    @foreach($section as $value)
                    <tr>
                        <th class="col-lg-4">Academic Year</th>
                        <td>{{ isset($value->academics_year_id)?$value->academicsYear()->year_name:''}}</td>
                        <th class="col-lg-4">Batch Name</th>
                        <td>{{ isset($value->batch_id)?$value->batchName()->batch_name:''}}</td>
                    </tr>
                    <tr>
                        <th class="col-lg-4">Section Name</th>
                        <td>{{ isset($value->section_name)?ucfirst($value->section_name):''}}</td>
                        <th class="col-lg-4">Intake</th>
                        <td>{{ isset($value->intake)?$value->intake:''}}</td>
                    </tr>

                    <tr>
                        <th class="col-lg-4">Status</th>
                        <td>@if($value->status==1) <p>Active</p>@endif
                            @if($value->status==0) <p>Inactive</p>@endif
                        </td>
                    </tr>
                        @endforeach
                </table>
            </div>
        </div>

        <div class="modal-footer">
            <a data-dismiss="modal" class="btn btn-default" type="button"> Close </a>
        </div>

    </div>
</div>
