
@extends('employee::layouts.profile-layout')

@section('profile-content')
    <p class="text-right">
        <a class="btn btn-primary btn-sm" href="{{ url('employee/profile/create/experience/'.$employeeInfo->id) }}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Add</a>
    </p>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>SL</th>
                <th>Organization Name</th>
                <th>Organization Address</th>
                <th>Last Designation</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Total Duration</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @php
                    $fromDateForTotal = null;
                    $toDateForTotal = null;
                @endphp
                @foreach($experiences as $experience)
                    @php
                        $fromDate = \Carbon\Carbon::parse($experience->experience_from_date);
                        $toDate = \Carbon\Carbon::parse($experience->experience_to_date);

                        if ($loop->index == 0 ){
                            $fromDateForTotal = $fromDate;
                        }

                        $duration = date_diff($fromDate, $toDate);
                    @endphp
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$experience->experience_organization_name}}</td>
                        <td>{{$experience->experience_organization_address}}</td>
                        <td>{{$experience->experience_last_designation}}</td>
                        <td>{{$experience->experience_from_date}}</td>
                        <td>{{$experience->experience_to_date}}</td>
                        <td>{{ $duration->format('%y years, %m months, %d days') }}</td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="{{ url('employee/profile/edit/experience/'.$experience->id) }}"
                               data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                        class="fa fa-edit"></i></a>
                            <a href="{{ url('/employee/profile/delete/experience/'.$experience->id) }}" class="btn btn-danger btn-xs"
                               onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                               data-content="delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                @endforeach
                @if(isset($toDate))
                    @php
                        $toDateForTotal = $toDate;
                    @endphp
                    <tr>
                        <td colspan="6" style="text-align: right">Total Experience</td>
                        <td>{{date_diff($fromDateForTotal, $toDateForTotal)->format('%y years, %m months, %d days')}}</td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
