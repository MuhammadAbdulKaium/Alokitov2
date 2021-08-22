<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Stock Item History</h4>
</div>
<div class="modal-body">
<table class="table-bordered table">
    <thead>
        <tr>
            <th width="18%">Date</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
    @foreach($histories as $history)
        <tr>
            <td>{{$history->created_at}}</td>
            <td>{{$history->remarks}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

<script>
    
</script>