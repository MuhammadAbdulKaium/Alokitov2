@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit Online Admission Form Duration</h4>
            </div>

            <form id="add-information-form" name="add-information-form"  class="form-horizontal" action="{{url('website/form/update', $date->id)}}" method="post" role="form">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <input id="campus_id" class="form-control" name="campus_id" type="hidden" value="{{session()->get('campus')}}">
                    <input id="institute_id" class="form-control" name="institute_id" type="hidden" value="{{session()->get('institute')}}">


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="starting_date">Starting Date</label>
                                <input type="datetime-local" name="starting_date" id="starting_date" class="form-control" onchange="compareDate()" value="{{$date->starting_date}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="ending_date">Ending Date</label>
                                <input type="datetime-local" name="ending_date" id="ending_date" class="form-control" onchange="compareDate()" value="{{$date->ending_date}}">
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
        function compareDate(){
            var statingDate = document.getElementById('starting_date').value;
            var endingDate = document.getElementById('ending_date').value;

            if(statingDate && endingDate)
            {
                if(endingDate <= statingDate)
                {
                    alert("Ending Date is Before Starting Date")
                }
            }
        }
    </script>



@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif




