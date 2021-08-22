@extends('student::pages.student-profile.profile-layout')

@section('profile-content')

   <style>
      .card.with-border {
         text-align: center;
         padding: 10px;
         margin-top: 20px;
      }
   </style>

   <div id="guardian_container">
      <a id="update-guard-data" class="btn btn-success" href="/student/profile/photo/{{$personalInfo->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Photos</a>
{{--{{$studentPhoto}}--}}
      <div class="row">
         @foreach($studentPhoto as $photo)
            <div class="col-md-2">
               <div class="card-group">
                  <div class="card with-border">
                     <img class="card-img-top" src="{{URL::asset('assets/users/images/'.$photo->image)}}" alt="Card image cap" width="150">
                     <div class="card-body">
                        <h5 class="card-title"><b>Cadet Number: {{$photo->cadet_no}}</b></h5>
                        <h5 class="card-title"><b>Class: {{$photo->batch()->batch_name}}</b></h5>
                        <h5 class="card-title"><b>Form: {{$photo->section()->section_name}}</b></h5>
                        <h5 class="card-title"><b>Academic Year: {{$photo->year()->year_name}}</b></h5>
                        <h5 class="card-title"><b>Date: {{date('d/m/Y', strtotime($photo->date))}}</b></h5>

                     </div>
                     <div class="card-footer">
{{--                        <small class="text-muted">Last updated 3 mins ago</small>--}}
                     </div>
                  </div>
               </div>
            </div>
         @endforeach
      </div>

   <!--/responsive-->
@endsection
