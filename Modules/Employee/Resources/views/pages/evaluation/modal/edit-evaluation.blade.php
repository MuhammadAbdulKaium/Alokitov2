<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Evaluation</h4>
</div>

<div class="modal-body" style="overflow:auto">
    <form action="{{url('employee/update/evaluation/'.$evaluation->id)}}" method="post">
        @csrf

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Evaluation Name" name="name" required value="{{$evaluation->name}}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input class="form-control" type="number" placeholder="Score" name="score" required value="{{$evaluation->score}}">
                </div>
            </div>
            <div class="col-sm-3">
                <input type="text" id="onlyYearEdit" class="form-control hasDatepicker from-date" name="year" maxlength="10"
                placeholder="Effective For" aria-required="true" size="10" required value="{{$evaluation->year}}">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                Evaluation By:
            </div>
            <div class="col-sm-8">
                <select name="evaluationBy[]" id="designation-by-edit" multiple class="form-control" required>
                    <option value="hrfm" {{($evaluation->evaluation_by == 1 || $evaluation->evaluation_by == 3)?'selected':''}}>HR/FM</option>
                    <option value="cadets" {{($evaluation->evaluation_by == 2 || $evaluation->evaluation_by == 3)?'selected':''}}>Cadets</option>
                    @foreach ($designations as $designation)
                        @php
                            $selected = '';

                            foreach($evaluation->byDesignations as $des){
                                if ($designation->id == $des->id) {
                                    $selected = 'selected';
                                }
                            }                            
                        @endphp
                        <option value="{{$designation->id}}" {{$selected}}>{{$designation->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 15px">
            <div class="col-sm-4">
                Evaluation For:
            </div>
            <div class="col-sm-8">
                <select name="evaluationFor" id="designation-for-edit" class="form-control" required>
                    <option value="1" {{($evaluation->evaluation_for == 1)?'selected':''}}>HR/FM - Teaching</option>
                    <option value="2" {{($evaluation->evaluation_for == 2)?'selected':''}}>HR/FM - Non Teaching</option>
                    <option value="3" {{($evaluation->evaluation_for == 3)?'selected':''}}>Cadets</option>
                </select>
            </div>
        </div>

        <button class="btn btn-success" style="margin-top: 10px">Update</button>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#designation-by-edit').select2();

        $('#onlyYearEdit').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });
    })
</script>