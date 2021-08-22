
<form action="{{url('/academics/division/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
    <input type="hidden" name="division_id" value="{{$divisionProfile?$divisionProfile->id:'0'}}"/>

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> <b>{{$divisionProfile?'Update':'Add'}} Division</b></h4>
    </div>

    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="name">Name</label>
                    <input id="name" class="form-control" name="name" value="{{$divisionProfile?$divisionProfile->name:''}}" maxlength="20" type="text">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="short_name">Short Name</label>
                    <input id="short_name" class="form-control" name="short_name" value="{{$divisionProfile?$divisionProfile->short_name:''}}" maxlength="20" type="text">
                </div>
            </div>
        </div>
    </div>
    <!--./body-->

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-left">Submit</button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>
