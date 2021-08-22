<form action="{{url('/admin/manage/users/uno/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <input type="hidden" name="uno_id" value="{{$userProfile?$userProfile->id:'0'}}"/>
    {{--modal header--}}
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-user"></i> {{$userProfile?"Update":"Add"}} User (HighAdmin)</h4>
    </div>
    {{--modal-body--}}
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="name">HighAdmin Name:</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{$userProfile?$userProfile->name:''}}" required />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12   ">
                <div class="form-group">
                    <label for="email">HighAdmin Number:</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{$userProfile?$userProfile->email:''}}" required />
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