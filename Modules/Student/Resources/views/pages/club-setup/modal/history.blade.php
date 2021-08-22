<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Activity Histories</h4>
</div>

<div class="modal-body" style="overflow:auto">
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">Time</th>
            <th scope="col">Activity</th>
            <th scope="col">Details</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($activitySchedules as $activitySchedule)
            <tr>
                <th scope="row">{{ $loop->index+1 }}</th>
                <td>{{ Carbon\Carbon::parse($activitySchedule->schedule)->format('d-m-Y') }}</td>
                <td>{{ Carbon\Carbon::parse($activitySchedule->schedule)->format('g:i A') }}</td>
                <td>{{ $activitySchedule->activity->activity_name }}</td>
                <td>{{ $activitySchedule->details }}</td>
              </tr>
            @endforeach
        </tbody>
      </table>
</div>