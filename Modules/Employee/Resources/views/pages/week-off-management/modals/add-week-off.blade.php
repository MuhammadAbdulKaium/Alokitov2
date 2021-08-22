<div class="modal-content" id="modal-content">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-plus-square"></i> Add Week Off
        </h4>
    </div>
    <form id="leave-type-form" action="{{url('/employee/manage/week-off/store')}}" method="post">
        <input name="_token" value="{{csrf_token()}}" type="hidden">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="departments">Department(s)</label>
                        <select id="departments" name="departments[]" class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Select Department(s)" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                            @foreach($departmentList as $department)
                                <option value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="text-center"><label>Week Off Day(s)</label></p>
                    <table class="table table-responsive table-bordered table-striped text-center">
                        <tbody>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="hidden" name="week_off_days[0]">
                                    <div>
                                        <label><input type="radio" name="week_off_days[0]" value="1" style="margin-left: 10px;"> Every</label>
                                        <label><input type="radio" name="week_off_days[0]" value="2" style="margin-left: 10px;"> Odd</label>
                                        <label><input type="radio" name="week_off_days[0]" value="3" style="margin-left: 10px;"> Even</label>
                                    </div>
                                    <div class="help-block"></div>
                                </div>
                            </td>
                            <td><b>Sunday</b></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="hidden" name="week_off_days[1]">
                                    <div>
                                        <label><input type="radio" name="week_off_days[1]" value="1" style="margin-left: 10px;"> Every</label>
                                        <label><input type="radio" name="week_off_days[1]" value="2" style="margin-left: 10px;"> Odd</label>
                                        <label><input type="radio" name="week_off_days[1]" value="3" style="margin-left: 10px;"> Even</label>
                                    </div>
                                    <div class="help-block"></div>
                                </div>
                            </td>
                            <td><b>Monday</b></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="hidden" name="week_off_days[2]">
                                    <div>
                                        <label><input type="radio" name="week_off_days[2]" value="1" style="margin-left: 10px;"> Every</label>
                                        <label><input type="radio" name="week_off_days[2]" value="2" style="margin-left: 10px;"> Odd</label>
                                        <label><input type="radio" name="week_off_days[2]" value="3" style="margin-left: 10px;"> Even</label>
                                    </div>
                                    <div class="help-block"></div>
                                </div>
                            </td>
                            <td><b>Tuesday</b></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="hidden" name="week_off_days[3]">
                                    <div>
                                        <label><input type="radio" name="week_off_days[3]" value="1" style="margin-left: 10px;"> Every</label>
                                        <label><input type="radio" name="week_off_days[3]" value="2" style="margin-left: 10px;"> Odd</label>
                                        <label><input type="radio" name="week_off_days[3]" value="3" style="margin-left: 10px;"> Even</label>
                                    </div>
                                    <div class="help-block"></div>
                                </div>
                            </td>
                            <td><b>Wednesday</b></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="hidden" name="week_off_days[4]">
                                    <div>
                                        <label><input type="radio" name="week_off_days[4]" value="1" style="margin-left: 10px;"> Every</label>
                                        <label><input type="radio" name="week_off_days[4]" value="2" style="margin-left: 10px;"> Odd</label>
                                        <label><input type="radio" name="week_off_days[4]" value="3" style="margin-left: 10px;"> Even</label>
                                    </div>
                                    <div class="help-block"></div>
                                </div>
                            </td>
                            <td><b>Thursday</b></td>
                        </tr>

                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="hidden" name="week_off_days[5]">
                                    <input type="hidden" name="week_off_days[7]">
                                    <div>
                                        <label><input type="radio" name="week_off_days[5]" value="1" style="margin-left: 10px;"> Every</label>
                                        <label><input type="radio" name="week_off_days[5]" value="2" style="margin-left: 10px;"> Odd</label>
                                        <label><input type="radio" name="week_off_days[5]" value="3" style="margin-left: 10px;"> Even</label>
                                    </div>
                                    <div class="help-block"></div>
                                </div>
                            </td>
                            <td><b>Friday</b></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="hidden" name="week_off_days[6]">
                                    <div>
                                        <label><input type="radio" name="week_off_days[6]" value="1" style="margin-left: 10px;"> Every</label>
                                        <label><input type="radio" name="week_off_days[6]" value="2" style="margin-left: 10px;"> Odd</label>
                                        <label><input type="radio" name="week_off_days[6]" value="3" style="margin-left: 10px;"> Even</label>
                                    </div>
                                    <div class="help-block"></div>
                                </div>
                            </td>
                            <td><b>Saturday</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-success">Submit</button>
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        </div>
    </form>
    <script src="{{ URL::asset('js/select2.full.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
</div>