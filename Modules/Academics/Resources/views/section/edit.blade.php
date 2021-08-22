<link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>
<div class="modal-dialog">
    <div class="modal-content">

        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Edit Form</h4>
        </div>
        <form id="add-section-form" class="form-horizontal" action="{{url('academics/section-data-edit', [$section->id])}}" method="post" role="form">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-body" style="overflow:auto">
                <table id="" class="table table-bordered table-hover table-striped">
                    <tr>
                        <td> <label class="control-label " for="academics_level">Academic Level</label> </td>
                        {{--academic batch profile--}}
                        @php
                            $batch = $section->batch();
                            $myLevel = $batch->academicsLevel();
                        @endphp
                        <td>
                            <select id="academics_level" class="form-control academicLevel" name="academics_level" required>
                                <option value="" selected disabled>--Select Level --</option>
                                @foreach($academicLevels as $level)
                                    <option value="{{$level->id}}" {{$myLevel?($level->id==$myLevel->id?'selected':''):''}}>{{$level->level_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        @php $batchList = $myLevel->batch(); @endphp
                        <td><label class="control-label" for="batch_id">Class*</label></td>
                        <td><select id="batch_id" class="form-control academicBatch edit-academic-batch" name="batch_id" aria-required="true">
                                <option value="" selected disabled>--Select Class--</option>
                                @if($batchList->count())
                                    @foreach($batchList as $batch)
                                        {{--checking division--}}
                                        @if($division = $batch->division())
                                            @php $batchName = $batch->batch_name." - ".$division->name; @endphp
                                        @else
                                            @php $batchName = $batch->batch_name; @endphp
                                        @endif
                                        <option value="{{$batch->id }}" {{$batch->id==$section->batch_id?"selected":''}}>{{$batchName}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="control-label" for="section_name">Form Name*</label></td>
                        <td><input id="section_name" class="form-control" value="{{$section->section_name}}" name="section_name" maxlength="20" type="text"></td>
                        <td><label class="control-label" for="intake">Intake</label></td>
                        <td>  <input id="intake" class="form-control" value="{{$section->intake}}" name="intake" maxlength="20" type="text"></td>
                    </tr>
                    <tr>
                        <td>Is Group</td>
                        @php $myDivision = $section->divisions; @endphp
                        <td>
                            <input type="hidden" name="division" value="off">
                            @if ($dvisionChange)    
                            <input id="edit-division-checkbox" class="checkbox" {{sizeof($myDivision)!=0?'checked':''}}
                                name="division" maxlength="20" type="checkbox">
                            @else
                            <input checked disabled id="edit-division-checkbox" class="checkbox"
                                name="division" maxlength="20" type="checkbox">
                            <input style="display: none" checked id="edit-division-checkbox" class="checkbox"
                                name="division" maxlength="20" type="checkbox">
                            @endif
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
                            <div id="edit-division-id">
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

                                @if ($dvisionChange)
                                <input type="checkbox" name="divisions[]" value="{{$division->id }}" {{$myId?'checked':''}}>
                                {{$division->name}}
                                @else
                                <input type="checkbox" style="display: none" name="divisions[]" value="{{$division->id }}" checked>
                                <input type="checkbox" disabled {{$myId?'checked':''}}>
                                {{$division->name}}
                                @endif
                                
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <button type="submit" class="btn btn-primary btn-create">Update</button>
                            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>
<script type ="text/javascript">

    $(document).ready(function() {
        // validate signup form on keyup and submit
        $("#add-section-form").validate({
            rules: {
                academicyear_id: "required",
                batch_id: "required",
                section_name:"required",
            },

            messages: {
                academicyear_id: "Please enter academic year name",
                batch_id: "Please enter batch name",
                section_name:"Please enter section name",

            }
        });



        // $('#edit-division-id').hide();
        // $('#edit-division-checkbox').click(function () {
        //     if ($(this).is(':checked')) {
        //         // alert('Check');
        //         $('#edit-division-id').show();
        //     } else {
        //         // alert('un Check');
        //         $('#edit-division-id').hide();
        //     }
        // });
    });
</script>
