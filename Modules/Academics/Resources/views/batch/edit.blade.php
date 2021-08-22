<link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
<div class="modal-dialog" style="width:100%">
    <div class="modal-content" style="text-align: center ">

        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                    aria-hidden="true">×</span></button>
            <h4 class="modal-title">Edit Class</h4>
        </div>
        @foreach($batch as $value_data)
        <form id="add-batch-form" class="form-horizontal"
            action="{{url('academics/batch-data-edit', [$value_data->id])}}" method="post" role="form">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-body">

                <table id="" class="table table-bordered table-hover">
                    <tr>
                        <td>
                            <label class="control-label" for="academics_level_id">Academic Level*</label>
                        </td>
                        <td colspan="3">
                            <select id="academics_level_id" class="form-control" name="academics_level_id"
                                aria-required="true">
                                <option value="" disabled selected>----- Select Batch -----</option>
                                @foreach($academicLevel as $value)
                                <option value="{{$value->id}}"
                                    {{$value_data->academics_level_id==$value->id?'selected':''}}>
                                    {{$value->level_name}}
                                </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="control-label" for="batch_name">Class Name*</label></td>
                        <td> <input id="batch_name" class="form-control" value="{{$value_data->batch_name}}"
                                name="batch_name" maxlength="20" type="text"></td>
                        <td><label class="control-label" for="batch_alias">Class Alias*</label></td>
                        <td> <input id="batch_alias" class="form-control" value="{{$value_data->batch_alias}}"
                                name="batch_alias" maxlength="20" type="text"></td>
                    </tr>
                    {{-- <tr>
                        <td><label class="control-label" for="start_date">Start Date*</label></td>
                        <td> <input id="start_date" class="form-control" value="{{$value_data->start_date}}"
                                name="start_date" maxlength="20" type="text"></td>
                        <td><label class="control-label" for="end_date">End Date*</label></td>
                        <td> <input id="end_date" class="form-control" value="{{$value_data->end_date}}" name="end_date"
                                maxlength="20" type="text"></td>
                    </tr> --}}
                    <tr>
                        <td>Is Group</td>
                        @php $myDivision = $value_data->divisions; @endphp
                        <td>
                            <input type="hidden" name="division" value="off">
                            <input id="division-checkbox" class="checkbox" {{sizeof($myDivision)!=0?'checked':''}}
                                name="division" maxlength="20" type="checkbox">
                        </td>
                        <td>Group</td>
                        <td>
                            {{-- <div id="division-id">
                                <select class="form-control" id="division_id" name="division_id">
                                    <option value="" disabled selected>Select Division</option>
                                    @foreach($divisions as $division)
                                    <option value="{{$division->id}}"
                                        {{$myDivision?($division->id==$myDivision->id?'selected':''):''}}>
                                        {{$division->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div>
                                @foreach($divisions as $division)
                                    @php
                                        $myId = 0;
                                    @endphp
                                    @foreach ($myDivision as $item)
                                        @php
                                        if ($item->id == $division->id) {
                                            $myId = 1;
                                        }
                                        @endphp
                                    @endforeach
                                <input type="checkbox" name="divisions[]" value="{{$division->id }}" {{$myId?'checked':''}}>
                                {{$division->name}}
                                @endforeach
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <button type="submit" class="btn btn-primary btn-create pull-left">Update</button>
                            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                        </td>
                    </tr>
                </table>
            </div>


        </form>
        @endforeach
    </div>
</div>
<script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {

        //        /*Start, To Control check and uncheck of is division field based on division_id field*/
        //        if($('#division_id').val()!='')
        //        {
        //            // alert();
        //            $("#division-checkbox").attr("checked", true);
        //            $('#division-id').show();
        //        }
        //        else
        //        {
        //            //  alert();
        //            $("#division-checkbox").attr("checked", false);
        //            $('#division-id').hide();
        //        }



        /*End,To Control check and uncheck of is division field based on division_id field*/


        $('#division-checkbox').click(function () {

            if ($(this).is(':checked')) {
                $('#division-id').show();

            } else {
                // alert('un Check');
                $('#division-id').hide();
            }
        });

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
    $().ready(function () {
        // alert();
        // validate signup form on keyup and submit
        $("#add-batch-form").validate({
            rules: {
                academic_year: "required",
                academic_level: "required",
                batch_name: "required",
                start_date: "required",
                end_date: "required",
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
                section_name: "Please enter batch alias",
                division_id: "Please enter division name",


            }
        });
    });
</script>
