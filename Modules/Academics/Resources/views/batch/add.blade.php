<link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                    aria-hidden="true">×</span></button>
            <h4 class="modal-title">Add Class</h4>
        </div>
        <form id="add-batch-form" name="add-batch-form" class="form-horizontal"
            action="{{url('academics/store-batch')}}" method="post" role="form">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-body" style="overflow:auto">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="academics_level_id">Academic Level*</label>
                            <select id="academics_level_id" class="form-control academicLevel" name="academics_level_id"
                                required>
                                <option value="" disabled selected hidden>--Select Academic Level--</option>
                                @foreach($academicLevels as $value)
                                <option value="{{$value->id}}">{{$value->level_name }}</option>
                                @endforeach
                            </select>
                            <div class="help-block help-block-error "></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="batch_name">Class Name*</label>
                            <input id="batch_name" class="form-control" name="batch_name" type="text">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="batch_alias">Class Alias*</label>
                            <input id="batch_alias" class="form-control" name="batch_alias" maxlength="20" type="text">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="start_date">Is Group</label>
                            <input id="division-checkbox" class="checkbox" name="division" maxlength="20"
                                type="checkbox">
                        </div>
                        <div class="col-md-6" id="division-id">
                            <label class="control-label" for="academic_level">Group</label>
                            @foreach($divisions as $division)
                            <input class="batch-division" type="checkbox" name="divisions[]" value="{{$division->id }}"> {{$division->name}}
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="start_date">Start Date*</label>
                            <input id="start_date" class="form-control" name="start_date" maxlength="20" type="text">
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="end_date">End Date*</label>
                            <input id="end_date" class="form-control" name="end_date" maxlength="20" type="text">
                        </div>
                    </div>
                </div> --}}

                <fieldset>
                    <legend>
                        Initial Form
                    </legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label" for="section_name">Form Name*</label>
                                <input id="section_name" class="form-control" name="section_name" maxlength="20"
                                    type="text">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="intake">Intake</label>
                                <input id="intake" class="form-control" name="intake" maxlength="20" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {{-- <label class="control-label" for="start_date">Is Division</label>
                                <input id="division-checkbox" class="checkbox" name="division" maxlength="20"
                                    type="checkbox"> --}}
                            </div>
                            <div class="col-md-6" id="division-id">
                                <label class="control-label" for="academic_level">Group</label>
                                @foreach($divisions as $division)
                                <input class="section-division" type="checkbox" name="section_divisions[]" value="{{$division->id }}"> {{$division->name}}
                                <input class="section-division-hidden" style="display: none" type="checkbox" name="section_divisions[]" value="{{$division->id }}">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </fieldset>

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
    jQuery(document).ready(function () {

        $('#division-id').hide();
        $('#division-checkbox').click(function () {
            if ($(this).is(':checked')) {
                //                alert('Check');
                $('#division-id').show();


            } else {
                // alert('un Check');
                $('#division-id').hide();

            }
        });


        $('.batch-division').click(function () {
            if ($('.batch-division').is(':checked')) {
                $('.section-division').attr('disabled', true);
            }else{
                $('.section-division').removeAttr('disabled');
            }

            var batchDivisions = $('.batch-division');
            var sectionDivisions = $('.section-division');
            var sectionDivisionsHidden = $('.section-division-hidden');

            var batchArr = [];

            batchDivisions.each(function (index){
                if ($(this).is(':checked')) {
                    batchArr.push(true);
                }else{
                    batchArr.push(false);
                }
            });

            sectionDivisions.each(function (index) {
                if (batchArr[index]) {
                    $(this).prop('checked', true);
                }else{
                    $(this).prop('checked', false);
                }
            });

            sectionDivisionsHidden.each(function (index) {
                if (batchArr[index]) {
                    $(this).prop('checked', true);
                }else{
                    $(this).prop('checked', false);
                }
            });
        })


        //        console.log('datepicker');
        $('#start_date').datepicker();
        $('#end_date').datepicker();
    });

    jQuery('#end_date').datepicker({
        "changeMonth": true,
        "changeYear": true,
        "autoSize": true,
        "dateFormat": "dd-mm-yy",
        "changeMonth": true,
        "yearRange": "1900:2018",
        "changeYear": true,
        "autoSize": true,
        "dateFormat": "dd-mm-yy"
    });

    jQuery('#start_date').datepicker({
        "changeMonth": true,
        "changeYear": true,
        "autoSize": true,
        "dateFormat": "dd-mm-yy",
        "changeMonth": true,
        "yearRange": "1900:2018",
        "changeYear": true,
        "autoSize": true,
        "dateFormat": "dd-mm-yy"
    });


    // request for batch list using level id
    jQuery(document).on('change', '.academicYear', function () {
        console.log("hmm its change");

        // get academic year id
        var year_id = $(this).val();
        var div = $(this).parent();
        var op = "";

        $.ajax({
            url: "{{ url('/academics/find/level') }}",
            type: 'GET',
            cache: false,
            data: {
                'id': year_id
            }, //see the $_token
            datatype: 'application/json',

            beforeSend: function () {
                console.log(year_id);

            },

            success: function (data) {
                console.log('success');

                //console.log(data.length);
                op += '<option value="0" selected disabled>--- Select Level ---</option>';
                for (var i = 0; i < data.length; i++) {
                    // console.log(data[i].level_name);
                    op += '<option value="' + data[i].id + '">' + data[i].level_name + '</option>';
                }
                // set value to the academic batch
                $('.academicLevel').html("");
                $('.academicLevel').append(op);
            },

            error: function () {

            }
        });
    });


    $().ready(function () {


        // validate signup form on keyup and submit
        $("#add-batch-form").validate({
            rules: {
                academic_year: "required",
                academic_level: "required",
                batch_name: "required",
                // start_date: "required",
                // end_date: "required",
                section_name: "required",
                batch_alias: "required",
                division_id: {
                    required: function () {
                        if ($('#division-checkbox').is(':checked')) {
                            return true;
                        }

                    }

                },


            },
            messages: {
                academic_year: "Please enter academic year name",
                academic_level: "Please enter academic level name",
                batch_name: "Please enter batch name",
                start_date: "Please enter start date",
                end_date: "Please enter end date",
                section_name: "Please enter section name",
                division_id: "Please enter division name",



            }
        });
    });
</script>
