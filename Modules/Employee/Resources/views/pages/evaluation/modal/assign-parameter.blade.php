<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-edit"></i> Assign Evaluation Parameter</h4>
</div>

<div class="modal-body" style="overflow:auto">
    <form action="{{url('employee/assign/evaluation-parameter')}}" method="post">
        @csrf

        <input type="hidden" name="evaluation" value="{{$evaluation->id}}">

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="">Evaluation Parameters</label>
                    <select class="form-control" name="evaluationParameters[]" id="assign-parameter" multiple>
                        @foreach ($evaluationParameters as $evaluationParameter)
                        @php
                            $selected = '';

                            foreach ($evaluation->parameters as $parameter) {
                                if ($parameter->id == $evaluationParameter->id) {
                                    $selected = 'selected';
                                }
                            }
                        @endphp
                            <option value="{{$evaluationParameter->id}}" {{$selected}}>{{$evaluationParameter->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button class="btn btn-success" style="margin-top: 10px">Assign</button>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#assign-parameter').select2();

        $('#onlyYearEdit').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });
    })
</script>