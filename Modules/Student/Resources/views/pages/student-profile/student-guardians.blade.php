@extends('student::pages.student-profile.profile-layout')

@section('profile-content')

   @php $parents = $personalInfo->myGuardians(); @endphp

   <div id="guardian_add_btn_container">
      @if($parents->count()<5)
         <p class="text-right">
{{--            <a id="update-guard-data" class="btn btn-success" href="/student/profile/guardian/parent/create/{{$personalInfo->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-user-plus" aria-hidden="true"></i> Select Guardian</a>--}}
            <a id="update-guard-data" class="btn btn-success" href="/student/profile/guardian/create/{{$personalInfo->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Member</a>
         </p>
      @endif
   </div>

   <div id="guardian_container">
      @if($personalInfo->myGuardians()->count()>0)
         @include('student::pages.student-profile.modals.guardian-parent-list')
      @else
         <div class="alert bg-warning text-warning">
            <i class="fa fa-warning"></i> No record found.
         </div>
      @endif
   </div>

   <!--/responsive-->
@endsection
