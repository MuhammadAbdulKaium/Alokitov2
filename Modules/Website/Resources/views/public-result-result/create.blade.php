@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Public Exam Result</h4>
            </div>

            <form id="add-information-form" name="add-information-form"  class="form-horizontal" action="{{url('website/public_exam/store')}}" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <input id="campus_id" class="form-control" name="campus_id" type="hidden" value="{{session()->get('campus')}}">
                    <input id="institute_id" class="form-control" name="institute_id" type="hidden" value="{{session()->get('institute')}}">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="year">Year*</label>
                                <select id="year" class="form-control" name="year" required>
                                    <option value="" disabled selected hidden>--Select Exam Year--</option>
                                    @foreach($years as $year)
                                        <option value={{$year}}>{{$year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="name">Examination*</label>
                                <select id="name" class="form-control" name="name" required>
                                    <option value="" disabled selected hidden>--Select Examination--</option>
                                    <option value="PSC">PSC</option>
                                    <option value="JSC">JSC</option>
                                    <option value="SSC">SSC</option>
                                    <option value="HSC">HSC</option>
                                </select>
                                <div class="help-block help-block-error "></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="examinee">Examinee</label>
                                <input name="examinee" id="examinee" class="form-control" type="number">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="golden_a">Golden A+</label>
                                <input name="golden_a" id="golden_a" class="form-control" type="number">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="a_plus">A+</label>
                                <input name="a_plus" id="a_plus" class="form-control" type="number">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="pass_percentage">Pass %</label>
                                <input name="pass_percentage" id="pass_percentage" class="form-control" type="number" oninput="setTwoNumberDecimal(this)" step="0.01" value="0.00">
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

@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif




