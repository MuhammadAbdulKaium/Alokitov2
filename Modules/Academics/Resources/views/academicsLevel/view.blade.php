<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">{{ $pageTitle }} </h4>
        </div>
        <div class="modal-body">
            <div style="padding: 30px;">
                <table id="" class="table table-bordered table-hover table-striped">
                    @foreach($academicLevel as $value)
                        <tr>
                            <th class="col-lg-4">Academic Level Name</th>
                            <td>{{isset($value->level_name)?$value->level_name:''}}</td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Academic Level Code</th>
                            <td>{{ isset($value->level_code)?ucfirst($value->level_code):''}}</td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Status</th>
                            <td>
                                @if($value->is_active==1) <p>Active</p>@endif
                                @if($value->is_active==0) <p>Inactive</p>@endif
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
