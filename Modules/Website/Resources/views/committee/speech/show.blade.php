@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Committee</h4>
            </div>
            <div class="modal-body" style="overflow:auto">
                <table id="" class="table table-bordered table-hover table-striped">
                        <tr>
                            <th class="col-lg-4">Image</th>
                            <td><img height="80" src="/images/{{$values->image}}" alt=""></td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Name</th>
                            <td>{{$values->name}}</td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Designation</th>
                            <td>{{$values->designation}}</td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Phone</th>
                            <td>{{$values->phone}}</td>
                        </tr>
                    <tr>
                            <th class="col-lg-4">Email</th>
                            <td>{{$values->email}}</td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Speech</th>
                            <td>{{$values->speech}}</td>
                        </tr>
                </table>
            </div>

            <div class="modal-footer">
                <a data-dismiss="modal" class="btn btn-primary" type="button" href="{{ url('website/committee/edit', $values->id) }}"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"> Edit </a>
                <a data-dismiss="modal" class="btn btn-default" type="button"> Close </a>
            </div>

        </div>
    </div>

    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif
