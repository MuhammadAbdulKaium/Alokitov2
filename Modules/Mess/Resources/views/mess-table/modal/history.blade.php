<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> {{ $messTable->table_name }} Histories
    </h4>
</div>
<!--modal-header-->
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered" id="history-table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Activity</th>
                        <th>By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messTableHistories as $messTableHistory)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{ $messTableHistory->created_at }}</td>
                            <td>
                                @if ($messTableHistory->person_type == 1)
                                Student {{ $messTableHistory->cadet->first_name }} {{ $messTableHistory->cadet->last_name }}
                                @elseif ($messTableHistory->person_type == 2)
                                    Employee {{ $messTableHistory->employee->first_name }} {{ $messTableHistory->employee->last_name }}
                                @endif

                                @if ($messTableHistory->activity == 1)
                                    was added to seat no
                                @elseif($messTableHistory->activity == 2)
                                    was removed from seat no
                                @endif

                                {{ $messTableHistory->seat_no }}
                            </td>
                            <td>{{ $messTableHistory->user->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('#history-table').DataTable();
</script>
