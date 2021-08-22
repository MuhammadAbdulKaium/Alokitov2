    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ $pageTitle }} </h4>
        </div>
        <div class="modal-body">
            <div style="padding: 30px;">
                <table id="" style="width: 70%" class="table table-bordered table-hover table-striped">
                    @foreach($academicYear as $value)
                    <tr>
                        <th class="col-lg-4">Year Name</th>
                        <td>{{ isset($value->year_name)?$value->year_name:''}}</td>
                    </tr>
                    <tr>
                        <th class="col-lg-4">Start Date</th>
                        <td>{{ isset($value->start_date)?ucfirst($value->start_date):''}}</td>
                    </tr>
                    <tr>
                        <th class="col-lg-4">End Date</th>
                        <td>{{ isset($value->end_date)?ucfirst($value->end_date):'' }}</td>
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
