@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
    <p class="text-right no-margin">
        <a id="edit-address" class="btn btn-primary btn-sm" href="/student/profile/address/edit/{{$personalInfo->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
        </a>
    </p>

    <div class="table-responsive">
        @if($present = $personalInfo->user()->singleAddress("STUDENT_PRESENT_ADDRESS"))@endif
        <legend class="page-header">
            Current Address
        </legend>
        <table class="table tbl-profile">
            <colgroup>
                <col style="width:200px">
                <col style="width:300px">
                <col style="width:200px">
                <col style="width:300px">
            </colgroup>
            <tbody>
            <tr>
                <th>Address</th>
                <td colspan="3">@if($present){{$present->address}}@else - @endif</td>
            </tr>
            <tr>
                <th>Area</th>
                <td>@if($present){{$present->city()?$present->city()->name:' - '}}@else - @endif</td>
                <th>City</th>
                <td>@if($present){{$present->state()?$present->state()->name:' - '}}@else - @endif</td>
            </tr>
            <tr>
                <th>Country
                </th>
                <td>@if($present){{$present->country()?$present->country()->name:' - '}}@else - @endif</td>
                <th>House No</th>
                <td>@if($present){{$present->house}}@else - @endif</td>
            </tr>
            <tr>
                <th>Zip Code</th>
                <td>@if($present){{$present->zip}}@else - @endif</td>
                <th>Phone No</th>
                <td>@if($present){{$present->phone}}@else - @endif</td>
            </tr>
            </tbody>
        </table>

        @if($permanent = $personalInfo->user()->singleAddress("STUDENT_PERMANENT_ADDRESS"))@endif
        <h4 class="page-header">
            Permanent Address
        </h4>
        <table class="table tbl-profile">
            <colgroup>
                <col style="width:200px">
                <col style="width:300px">
                <col style="width:200px">
                <col style="width:300px">
            </colgroup>
            <tbody>
            <tr>
                <th>Address</th>
                <td colspan="3">@if($permanent){{$permanent->address}}@else - @endif</td>
            </tr>
            <tr>
                <th>Area</th>
                <td>@if($permanent){{$permanent->city()?$permanent->city()->name:' - '}}@else - @endif</td>
                <th>City</th>
                <td>@if($permanent){{$permanent->state()?$permanent->state()->name:' - '}}@else - @endif</td>
            </tr>
            <tr>
                <th>Country</th>
                <td>@if($permanent){{$permanent->country()?$permanent->country()->name:' - '}}@else - @endif</td>
                <th>House No</th>
                <td>@if($permanent){{$permanent->house}}@else - @endif</td>
            </tr>
            <tr>
                <th>Zip Code</th>
                <td>@if($permanent){{$permanent->zip}}@else - @endif</td>
                <th>Phone No</th>
                <td>@if($permanent){{$permanent->phone}}@else - @endif</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection



