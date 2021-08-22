
@extends('employee::layouts.profile-layout')

@section('profile-content')
   <p class="text-right">
      <a class="btn btn-primary btn-sm" href="/employee/profile/personal/edit/{{$employeeInfo->id}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
   </p>
   <div class="table-responsive">
      <table class="table">
         <colgroup>
            <col style="width:15%">
            <col style="width:35%">
            <col style="width:15%">
            <col style="width:35%">
         </colgroup>
         <tbody>
            <tr>
               <th>Name</th>
               <td>{{$employeeInfo->title." ". $employeeInfo->first_name." ".$employeeInfo->middle_name." ".$employeeInfo->last_name}}</td>
               <th>Name Alias</th>
               <td>{{$employeeInfo->alias}}</td>
            </tr>
            <tr>
               <th>Joining Date</th>
               <td>{{ date('F d, Y', strtotime($employeeInfo->doj)) }}</td>
               <th>Date of Birth</th>
               <td>{{ date('F d, Y', strtotime($employeeInfo->dob)) }}</td>
            </tr>
            <tr>
               <th>Gender</th>
               <td>{{$employeeInfo->gender}}</td>
               <th>Birthplace</th>
               <td>{{$employeeInfo->birth_place}}</td>
            </tr>
            <tr>
               <th>Department</th>
               <td>
                  @if($employeeInfo->department())
                     {{$employeeInfo->department()->name}}
                  @endif
               </td>
               <th>Designation</th>
               <td>
                  @if($employeeInfo->designation())
                     {{$employeeInfo->designation()->name}}
                  @endif
               </td>
            </tr>
            <tr>
               <th>Category</th>
               <td>
                  @php
                     switch($employeeInfo->category) {
                        case '1': echo "Teaching"; break;
                        case '0': echo "Non-Teaching";  break;
                     }
                  @endphp
               </td>
               <th>Total Experience</th>
               <td>{{$employeeInfo->experience_year." Years ".$employeeInfo->experience_month."  Months"}} </td>
            </tr>
            <tr>
               <th>Blood Group</th>
               <td>{{$employeeInfo->blood_group}}</td>
               <th>Marital Status</th>
               <td>{{$employeeInfo->marital_status}}</td>
            </tr>
            <tr>
               <th>Nationality</th>
               <td>{{$employeeInfo->nationality()?$employeeInfo->nationality()->nationality:'-'}}</td>
               <th>Religion</th>
               <td>
                                    @php
                                       switch($employeeInfo->religion) {
                                  case '1': echo "Islam"; break;
                                  case '2': echo "Hinduism"; break;
                                  case '3': echo "Christian"; break;
                                  case '4': echo "Buddhism"; break;
                                  case '5': echo "Others"; break;
                                }
                                    @endphp
               </td>
            </tr>
            <tr>
               <th>Role</th>
               <td> {{$employeeInfo->user()->roles()->count()>0?$employeeInfo->user()->roles()->first()->display_name:'No Role'}} </td>
               <th>Reporting to</th>
               <td> </td>
            </tr>
            <tr>
               <th>Web Position</th>
               <td> {{$employeeInfo->sort_order}} </td>
               <th>Date of Retirement</th>
               <td>{{ $employeeInfo->dor }}</td>
            </tr>
         </tbody>
      </table>
   </div>
@endsection
