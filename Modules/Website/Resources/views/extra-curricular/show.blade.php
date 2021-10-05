@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Extra Curricular Activities</h4>
            </div>
            <div class="modal-body" style="overflow:auto">
                <table id="" class="table table-bordered table-hover table-striped">
                        <tr>
                            <th class="col-lg-4">Type</th>
                            <td>{{$extraCurricular->curricular_type}}</td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Image</th>
                            <td><img height="80" src="/images/{{$extraCurricular->file}}" alt=""></td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Title</th>
                            <td>{{$extraCurricular->name}}</td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Description</th>
                            <td><textarea class="form-control">{{$extraCurricular->description}}</textarea></td>
                        </tr>

                </table>
            </div>

            <div class="modal-footer">
                <a data-dismiss="modal" class="btn btn-primary" type="button" href="{{ url('website/extra/edit', [$typeArray[0], $extraCurricular->id]) }}"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"> Edit </a>
                <a data-dismiss="modal" class="btn btn-default" type="button"> Close </a>
            </div>

        </div>
    </div>

    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif





