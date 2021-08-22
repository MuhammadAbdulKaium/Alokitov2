@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
   {{--{{$recordInfo}}--}}
   <div class="col-md-12">
      <ul class="nav nav-tabs">
         <li class="active"><a data-toggle="tab" href="#enroll_active">Active</a></li>
{{--         <li class=""><a data-toggle="tab" href="#enroll_history">History</a></li>--}}
      </ul>
      <div class="tab-content">
         {{--student current/active enroll--}}
         <div id="enroll_active" class="tab-pane fade in active">
            <div class="row">
               <div class="col-md-12">
                  <p class="text-right">
                     <a id="update-guard-data" class="btn btn-success float-right" href="/student/profile/fector/entity/creates/{{$personalInfo->id}}/6" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Records</a>
                  </p>
                  <br/>
                  <table class="table table-striped table-bordered text-center">
                     <thead>
                        <th scope="col">#</th>                  
                        <th scope="col">Academic Year</th>
                        <th scope="col">Academic Label</th>
                        {{-- <th scope="col">Division</th> --}}
                        <th scope="col">Class</th>
                        <th scope="col">Form</th>
                        <th scope="col">Date</th>
                        <th scope="col">Remarks</th>
                        <th scope="col">Action</th>
                     </thead>
                     <tbody>
                     @php $enrollment = $personalInfo->enroll(); @endphp
                     @foreach ($recordInfo as $item)
                        @php
                              $i = 1
                        @endphp
                        <tr>
                           <td>{{$i++}}</td>
                           <td>{{$item->year()->year_name}}</td>
                           <td>{{$enrollment->level()->level_name}}</td>
                           {{-- <td>{{$item->batch()->division_id}}</td> --}}
                           <td>{{$item->batch()->batch_name}}</td>
                           <td>{{$item->section()->section_name}}</td>
                           <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                           <td>{{$item->remarks}}</td>
                           <td>
                              <a id="update-guard-data" class="btn btn-success float-right" href="/student/performance/assessment/show/{{$personalInfo->id}}/{{$item->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-user-plus" aria-hidden="true"></i> Edit</a>
                              <a href="" class="btn btn-primary">Delete</a>
                           </td>
                        </tr>
                     @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>

         <div id="enroll_history" class="tab-pane fade in">
            <div class="row">
               <div class="col-md-12">
                  <br/>
                  <ul class="timeline">
                     <li class="time-label">
                        <div class="panel panel-default">
                           <div class="panel-body">
                              <div class="text-bold" style="margin-bottom:5px;font-size:18px">
                                 Level :
                                 <div class="pull-right">
                                    <a class="btn btn-primary btn-sm" id="update-course" href="#" title="Update" data-target="#globalModal" data-toggle="modal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                 </div>
                              </div>

                           </div>
                        </div>
                     </li>

                  </ul>

                  <div class="alert bg-warning text-warning">
                     <i class="fa fa-warning"></i> No record found.	</div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!--/responsive-->
@endsection
