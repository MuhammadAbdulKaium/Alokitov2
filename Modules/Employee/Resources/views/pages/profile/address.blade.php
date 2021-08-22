
@extends('employee::layouts.profile-layout')

@section('profile-content')
                     <p class="text-right">
                        <a class="btn btn-primary btn-sm" href="/employee/profile/address/edit/{{$employeeInfo->id}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>    
                     </p>

                     <div class="table-responsive">
                          @if($present = $employeeInfo->user()->singleAddress("EMPLOYEE_PRESENT_ADDRESS")) @endif
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
                                 <td>@if($present) @if($present->city()) {{$present->city()->name}} @endif @else - @endif</td>
                                 <th>City</th>
                                 <td>@if($present) @if($present->state()) {{$present->state()->name}} @endif @else - @endif</td>
                              </tr>
                              <tr>
                                 <th>Country
                                 </th>
                                 <td>@if($present) @if($present->country()) {{$present->country()->name}} @endif @else - @endif</td>
                                 <th>House No</th>
                                 <td>@if($present) {{$present->house}}@else - @endif</td>
                              </tr>
                              <tr>
                                 <th>Zip Code</th>
                                 <td>@if($present){{$present->zip}}@else - @endif</td>
                                 <th>Phone No</th>
                                 <td>@if($present){{$present->phone}}@else - @endif</td>
                              </tr>
                           </tbody>
                        </table>

                        @if($permanent = $employeeInfo->user()->singleAddress("EMPLOYEE_PERMANENT_ADDRESS"))@endif
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
                                 <td>@if($permanent) @if($permanent->city()) {{$permanent->city()->name}} @endif @else - @endif</td>
                                 <th>City</th>
                                 <td>@if($permanent) @if($permanent->state()) {{$permanent->state()->name}} @endif @else - @endif</td>
                              </tr>
                              <tr>
                                 <th>Country</th>
                                 <td>@if($permanent) @if($permanent->country()) {{$permanent->country()->name}} @endif @else - @endif</td>
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
