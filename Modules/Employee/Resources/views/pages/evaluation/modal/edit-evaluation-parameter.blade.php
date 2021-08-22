<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Evaluation Parameter</h4>
</div>

<div class="modal-body" style="overflow:auto">
    <form action="{{url('employee/update/evaluation-parameter/'.$evaluationParameter->id)}}" method="post">
        @csrf

        <div class="form-group">
            <input class="form-control" type="text" placeholder="Evaluation Parameter" name="name" value="{{$evaluationParameter->name}}" required>
        </div>

        <button class="btn btn-success">Update</button>
    </form>
</div>