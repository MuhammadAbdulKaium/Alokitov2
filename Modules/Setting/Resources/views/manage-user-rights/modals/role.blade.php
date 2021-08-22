
<form action="{{url('/setting/rights/role/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <input type="hidden" name="role_id" value="@if($roleProfile){{$roleProfile->id}}@else '0' @endif"/>

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-plus-square"></i> @if($roleProfile)Update @else Add @endif Role</h4>
    </div>

    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="@if($roleProfile){{$roleProfile->name}}@endif"/>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="display_name">Display Name:</label>
                    <input type="text" class="form-control" name="display_name" value="@if($roleProfile){{$roleProfile->display_name}}@endif"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="description">Role Description:</label>
                    <input type="text" class="form-control" name="description" value="@if($roleProfile){{$roleProfile->description}}@endif"/>
                </div>
            </div>
        </div>
    </div>

    <!--./modal-body-->
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        <button class="btn btn-primary pull-left" type="submit">Submit</button>
    </div>
    <!--./modal-footer-->
</form>