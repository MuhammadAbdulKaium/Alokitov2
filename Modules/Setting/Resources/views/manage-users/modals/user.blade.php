<form action="{{url('/setting/manage/users/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <input type="hidden" name="user_id" value="0"/>
    {{--modal header--}}
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-university"></i> Add Campus Admin</h4>
    </div>
    {{--modal-body--}}
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="name">Campus Admin Name:</label>
                    <input id="name" type="text" class="form-control" name="name" required />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12   ">
                <div class="form-group">
                    <label for="email">Campus Admin Email:</label>
                    <input id="email" type="email" class="form-control" name="email" required />
                </div>
            </div>
        </div>
    </div>

    {{--modal-footer--}}
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        <button class="btn btn-primary pull-left" type="submit">Submit</button>
    </div>
</form>

<script></script>