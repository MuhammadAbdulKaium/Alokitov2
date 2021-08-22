    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ $pageTitle }} </h4>
        </div>
        <div class="modal-body">
            <div style="padding: 30px;">
                <table id="" style="width: 70%" class="table table-bordered table-hover table-striped">
                    @foreach($academicsYearView as $value)
                    <tr>
                        <th class="col-lg-4">Year Name</th>
                        <td>{{ isset($value->year_name)?$value->year_name:''}}</td>
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
