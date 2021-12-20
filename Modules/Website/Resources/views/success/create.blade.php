@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))
@php
if(isset($success))
$p=0;
else
    $success=null;



@endphp

    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">@if($success) Update @else Add  @endif Previous year Result</h4>
            </div>

            <form id="add-information-form" name="add-information-form"  class="form-horizontal" @if($success) action="{{url('website/success/update',$success->id)}}"  @else action="{{url('website/success/store')}}" @endif method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <input id="campus_id" class="form-control" name="campus_id" type="hidden" value="{{session()->get('campus')}}">
                    <input id="institute_id" class="form-control" name="institute_id" type="hidden" value="{{session()->get('institute')}}">



                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label" for="name">Passing Year*</label>
                                <input class="form-control" type="number" name="passing_year" min="1900" max="2099" step="1" @if($success) value="{{$success->passing_year}}" @else value="2016" @endif  required/>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="designation">Total Examine</label>
                                <input id="designation" class="form-control" @if($success) value="{{$success->total_examine}}" @endif name="total_examine" maxlength="15" type="number" required>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label" for="psc_passing_rate">PSC Passing Rate</label>
                                <input  class="form-control" @if($success) value="{{$success->psc_passing_rate}}"  @endif name="psc_passing_rate" type="number" min="0" max="100" step=".01">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="psc_passing_rate">JSC Passing Rate</label>
                                <input  class="form-control" @if($success) value="{{$success->psc_passing_rate}}"  @endif name="jsc_passing_rate" type="number" min="0" max="100" step=".01">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="psc_passing_rate">SSC Passing Rate</label>
                                <input  class="form-control" @if($success) value="{{$success->ssc_passing_rate}}"  @endif name="ssc_passing_rate" type="number" min="0" max="100" step=".01">
                            </div>  <div class="col-md-6">
                                <label class="control-label" for="hsc_passing_rate">HSC Passing Rate</label>
                                <input  class="form-control" @if($success) value="{{$success->hsc_passing_rate}}"  @endif name="hsc_passing_rate" type="number" min="0" max="100" step=".01">
                            </div>


                        </div>
                    </div>

                    <!--./body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-create">Save</button>
                        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

    </script>

@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif




